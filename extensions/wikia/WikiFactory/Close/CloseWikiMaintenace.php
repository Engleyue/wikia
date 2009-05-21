<?php
/**
 * @package MediaWiki
 * @addtopackage maintenance
 */

include( $IP . "/maintenance/backup.inc" );
include( "Archive/Tar.php" );

class CloseWikiMaintenace {

	private
		$mOptions,
		$mDumpDirectory,
		$mImgDirectory,
		$mAction,
		$mCityID,
		$mHistory,
		$mWiki;

	public function __construct( $options ) {
		global $wgDevelEnvironment;

		if( !$wgDevelEnvironment ) {
			$this->mDumpDirectory = "/opt/dbdumps";
			$this->mZipDirectory = "/opt/dbdumps";
		}
		else {
			$this->mDumpDirectory = "/tmp/dumps";
			$this->mZipDirectory = "/tmp/dumps";
		}
		$this->mHistory = true;
		$this->mOptions = $options;
	}

	/**
	 * main entry point
	 *
	 * @access public
	 *
	 * @param boolean $setupif true just create directory tree and HTML index
	 */
	public function execute( $setup ) {
		global $wgCityId, $wgDBname, $wgServer;

		$this->mCityID = $wgCityId;

		if( $setup ) {
			$this->setupDirectories();
		}
		elseif( !wfReadOnly() ) {
			/**
			 * check what we have to do with this wikia
			 */
			if( $wgCityId ) {
				$this->mWiki = WikiFactory::getWikiByID( $wgCityId );
				$this->mAction = $this->mWiki->city_public;
			}
			Wikia::log( __CLASS__, "info", "maintenance on {$wgCityId}" );
			$this->checkDuplicates();
			switch( $this->mAction ) {

				case WikiFactory::STATUS_OPEN:
					$this->dumpXMl();
					$this->compressImages();
					$this->createIndex( $wgDBname, $wgServer );
					break;

				case WikiFactory::STATUS_CLOSED:
				case WikiFactory::STATUS_REDIR:
					$this->dumpXMl();
					$this->compressImages();
					$this->createIndex( $wgDBname, $wgServer );
					break;

				case WikiFactory::STATUS_DELETE:
					Wikia::log( __CLASS__, "info", "close and delete on {$wgCityId}" );
					$this->dumpXMl();
					if( $this->compressImages() === true ) {
						$this->removeImageDirectory();
					}
					$this->cleanWikiFactory();
					$this->dropDB();
					break;
			}
		}
		return true;
	}

	public function dumpXMl() {
		global $wgDBname, $wgDevelEnvironment;

		/**
		 * @name dumpfile
		 */
		$dumpfile = sprintf("%s/full.xml.gz", $this->getDirectory( $wgDBname ) );
		$args = array(
			"--full",
			"--quiet",
			"--output=gzip:{$dumpfile}",
			"--xml"
		);
		if( isset( $this->mOptions[ "server" ] ) ) {
			$args[] = "--server=" . $this->mOptions[ "server" ] ;
		}
		Wikia::log( __CLASS__, "info", "dumping {$wgDBname} to {$dumpfile}");
		$dumper = new BackupDumper( $args );
		$dumper->dump( WikiExporter::FULL, WikiExporter::TEXT );
	}

	/**
	 * pack all images from image table, use PEAR Archive_Tar for archive.
	 *
	 * @access public
	 *
	 * @return integer status of packing operation
	 */
	public function compressImages() {
		global $wgUploadDirectory, $wgDBname;

		/**
		 * @name dumpfile
		 */
		$zipfile = sprintf("%s/images.tar", $this->getDirectory( $wgDBname ) );
		Wikia::log( __CLASS__, "info", "Packing images from {$wgUploadDirectory} to {$zipfile}" );

		$tar = new Archive_Tar( $zipfile );

		if( ! $tar ) {
			Wikia::log( __CLASS__, "tar", "Cannot open {$zipfile}" );
			wfDie( "Cannot open {$zipfile}" );
		}
		$files = $this->getFilesList();
		if( is_array( $files ) && count( $files ) ) {
			Wikia::log( __CLASS__, "info", sprintf( "Packed %d images", count( $files ) ) );
			return $tar->create( $files );
		}
		else {
			Wikia::log( __CLASS__, "info", "List of images is empty" );
			return true;
		}
	}

	/**
	 * drop database. Danger! Use with caution
	 *
	 * @access public
	 */
	public function dropDB() {

		global $wgSharedDB, $wgDBname;
		wfProfileIn( __METHOD__ );

		/**
		 * check if database is used in more than one wiki
		 */
		$this->checkDuplicates();

		$dbw = wfGetDB( DB_MASTER );
		$dbw->selectDB( $wgSharedDB );
		$dbw->begin();
		$dbw->query( "DROP DATABASE `$wgDBname`");
		$dbw->commit();

		wfProfileOut( __METHOD__ );
	}

	/**
	 * remove image folder from public view. Make sure that you have them zipped
	 *
	 * @access public
	 */
	public function removeImageDirectory() {
		global $wgUploadDirectory;

		wfProfileIn( __METHOD__ );
		if( is_dir( $wgUploadDirectory ) ) {
			/**
			 * what should we use here?
			 */
			$cmd = "rm -rf {$wgUploadDirectory}";
			wfShellExec( $cmd, $retval );
			Wikia::log( __CLASS__, "info", "{$wgUploadDirectory} removed");
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Get images list from database
	 *
	 * use tables: images & filearchive (when $this->mHistory is set to true)
	 *
	 * @return array
	 */
	private function getFilesList() {

		$images = array();

		wfProfileIn( __METHOD__ );

		$dbr = wfGetDB( DB_SLAVE );
		$sth = $dbr->select(
			array( "image" ),
			array( "img_name", "img_media_type" ),
			false,
			__METHOD__
		);
		while( $row = $dbr->fetchObject( $sth ) ) {
			$file = wfLocalFile( $row->img_name );
			$path = $file->getPath();
			if( is_file( $path ) ) {
				$images[] = $path;
				Wikia::log( __CLASS, "info", "adding {$path} to archive" );
			}
			if( $file && $this->mHistory ) {
				$oldFiles = $file->getHistory();
				foreach( $oldFiles as $oldfile ) {
					$path = $oldfile->getPath();
					if( is_file( $path ) ) {
						$images[] = $oldfile->getPath();;
						Wikia::log( __CLASS, "info", "adding {$path} to archive" );
					}
				}
			}
		}
		$dbr->freeResult( $sth );

		wfProfileOut( __METHOD__ );

		return $images;
	}

	/**
	 * clean wikifactory tables:
	 *
	 *  remove rows from city_variables
	 *  remove rows from city_categories
	 *  remove row from city_list
	 *
	 *  old values will be stored in archive database
	 *
	 *  eventually all tables which use city_id should have foreign key to
	 *  city_list( city_id )
	 */
	public function cleanWikiFactory() {

		wfProfileIn( __METHOD__ );

		/**
		 * do only on inactive wikis
		 */
		$wiki = WikiFactory::getWikiByID( $this->mCityID );
		if( isset( $wiki->city_id ) && $wiki->city_public != 1 ) {

			$timestamp = wfTimestampNow();
			$dbw = wfGetDB( DB_MASTER );
			$dba = wfGetDBExt( DB_MASTER );
			$dba->selectDb( "archive" );

			$dba->begin();

			/**
			 * copy city_list to archive
			 */
			$dba->insert(
				"city_list",
				array(
					"city_id"                => $wiki->city_id,
					"city_path"              => $wiki->city_path,
					"city_dbname"            => $wiki->city_dbname,
					"city_sitename"          => $wiki->city_sitename,
					"city_url"               => $wiki->city_url,
					"city_created"           => $wiki->city_created,
					"city_founding_user"     => $wiki->city_founding_user,
					"city_adult"             => $wiki->city_adult,
					"city_public"            => $wiki->city_public,
					"city_additional"        => $wiki->city_additional,
					"city_description"       => $wiki->city_description,
					"city_title"             => $wiki->city_title,
					"city_founding_email"    => $wiki->city_founding_email,
					"city_lang"              => $wiki->city_lang,
					"city_special_config"    => $wiki->city_special_config,
					"city_umbrella"          => $wiki->city_umbrella,
					"city_ip"                => $wiki->city_ip,
					"city_google_analytics"  => $wiki->city_google_analytics,
					"city_google_search"     => $wiki->city_google_search,
					"city_google_maps"       => $wiki->city_google_maps,
					"city_indexed_rev"       => $wiki->city_indexed_rev,
					"city_deleted_timestamp" => $timestamp,
					"city_factory_timestamp" => $timestamp,
					"city_useshared"         => $wiki->city_useshared,
					"ad_cat"                 => $wiki->ad_cat,
				),
				__METHOD__
			);
			$dba->commit();

			/**
			 * move city_variables to archive
			 */
			$sth = $dbw->select(
				array( WikiFactory::table( "city_variables" ) ),
				array( "cv_city_id", "cv_variable_id", "cv_value" ),
				array( "cv_city_id" => $this->mCityID ),
				__METHOD__
			);
			while( $row = $dbw->fetchObject( $sth ) ) {
				$dba->insert(
					"city_variables",
					array(
						"cv_city_id"     => $row->cv_city_id,
						"cv_variable_id" => $row->cv_variable_id,
						"cv_value"       => $row->cv_value,
						"cv_timestamp"   => $timestamp
					),
					__METHOD__
				);
			}
			$dbw->freeResult( $sth );

			$dbw->begin();
			$dbw->delete(
				WikiFactory::table( "city_variables" ),
				array( "cv_city_id" => $this->mCityID ),
				__METHOD__
			);

			$dbw->delete(
				WikiFactory::table( "city_list_log" ),
				array( "cv_city_id" => $this->mCityID ),
				__METHOD__
			);

			$dbw->delete(
				WikiFactory::table( "city_list" ),
				array( "city_id" => $this->mCityID ),
				__METHOD__
			);

			$dbw->commit();
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * go trough *ALL* city_list and crete directories for dump
	 *
	 * @access private
	 *
	 * @param string $database city_dbname  in city_list
	 * @param string $url city_url in city_list
	 */
	private function createIndex( $database, $url ) {

		$directory = $this->getDirectory( $database );
		$haveXml = is_file( "{$directory}/full.xml.gz" ) ? true : false;
		$haveZip = is_file( "{$directory}/images.tar" ) ? true : false;

		$Tmpl = new EasyTemplate( dirname( __FILE__) . "/templates/" );
		$Tmpl->set( "directory", $directory );
		$Tmpl->set( "haveXml", $haveXml );
		$Tmpl->set( "haveZip", $haveZip );
		$Tmpl->set( "database", $database );
		$Tmpl->set( "url", $url );
		if( file_put_contents( "{$directory}/index.html", $Tmpl->render( "index-html" ) ) ) {
			Wikia::log( __CLASS__, "index", "create {$directory}/index.html" );
		}
	}

	/**
	 * go trough *ALL* city_list and crete directories for dump
	 */
	public function setupDirectories() {

		$dbr = wfGetDB( DB_SLAVE );
		$sth = $dbr->select(
			WikiFactory::table( "city_list" ),
			array( "city_id", "city_dbname", "city_url" ),
			false,
			__METHOD__
		);
		while( $row = $dbr->fetchObject( $sth ) ) {
			$this->createIndex( $row->city_dbname, $row->city_url );
		}
	}

	/**
	 * dump directory is created as
	 *
	 * <root>/<first letter>/<two first letters>/<database>
	 */
	private function getDirectory( $database ) {
		$database = strtolower( $database );
		$directory = sprintf(
			"%s/%s/%s/%s",
			$this->mDumpDirectory,
			substr( $database, 0, 1),
			substr( $database, 0, 2),
			$database
		);
		if( !is_dir( $directory ) ) {
			Wikia::log( __CLASS__ , "dir", "create {$directory}" );
			wfMkdirParents( $directory );
		}

		return $directory;
	}

	/**
	 * check if we have any inconsistence in database (duplicates etc.)
	 * Die if found any
	 *
	 * @access private
	 *
	 * @return
	 */
	private function checkDuplicates() {
		global $wgDBname;

		/**
		 * check if database is used in more than one wiki
		 */
		$dbw = wfGetDB( DB_MASTER );

		/**
		 * check city_list table
		 */
		$Row = $dbw->selectRow(
			WikiFactory::table( "city_list" ),
			array( "count(*) as count" ),
			array( "city_dbname" => $wgDBname ),
			__METHOD__
		);
		if( $Row->count > 1 ) {
			wfDie( "{$wgDBname} is used more than once in city_list table\n" );
		}

		/**
		 * check city_variables table
		 */
		$Row = $dbw->selectRow(
			WikiFactory::table( "city_variables" ),
			array( "count(*) as count" ),
			array(
				"cv_value" => serialize( $wgDBname ),
				"cv_variable_id = (SELECT cv_id FROM " .
				WikiFactory::table( "city_variables_pool" ) .
				" WHERE cv_name='wgDBname')",
			),
			__METHOD__
		);
		if( $Row->count > 1 ) {
			wfDie( "{$wgDBname} is used more than once in city_variables table\n" );
		}

		/**
		 * check if value from city_list match value from city_variables
		 */
		if( $this->mWiki->city_dbname !== $wgDBname ) {
			wfDie( "city_variables {$wgDBname} is different than city_list {$this->mWiki->city_dbname}\n" );
		}
	}
}
