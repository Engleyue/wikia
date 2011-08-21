<?php
/*
 * @author: Tomek Odrobny, Sean Colombo
 *
 * Class for getting a list of the top images on a given article.  Also allows
 * retriving thumbnails of those images which are scaled either by an aspect-ratio
 * or specific dimensions.
 */
class ImageServing {
	private $maxCount = 10;
	private $minSize = 75;
	private $queryLimit = 50;
	private $articles = array();
	private $width;
	private $proportion;
	private $deltaY = 0;
	private $db;

	/**
	 * @param $articles \type{\arrayof{\int}} List of articles ids to get images
	 * @param $articles \type{\arrayof{\int}} List of articles ids to get images
	 * @param $width \int image width
	 * @param $width \int
	 * @param $proportionOrHight can by array with proportion(example: array("w" => 1, "h" => 1)) or just height in pixels (example: 100)  proportion will be
	 * calculated automatically
	 */
	function __construct($articles = null, $width = 100, $proportionOrHeight = array("w" => 1, "h" => 1), $db = null){
		if(!is_array($proportionOrHeight)) {
			$height = (int) $proportionOrHeight;
			$this->proportion = array("w" => $width, "h" => $height);
		} else {
			$this->proportion = $proportionOrHeight;
		}

		$this->articles = array();

		if( is_array( $articles ) ) {
			foreach($articles as $article){
				$articleId = ( int ) $article;
				$this->articles[$articleId] = $articleId;
			}
		}

		$this->app = F::app();
		$this->width = $width;
		$this->memc =  $this->app->getGlobal( 'wgMemc' );
		$this->imageServingDrivers = $this->app->getGlobal( 'wgImageServingDrivers' );

		$this->deltaY = (round($this->proportion['w']/$this->proportion['h']) - 1)*0.1;
		$this->db = $db;
	}
	/**
	 * getImages - get array with list of top images for all article passed into the constructor
	 *
	 * @author Tomek Odrobny
	 *
	 * @access public
	 *
	 * @param $n \type{\arrayof{\int}} number of images to get for each article
	 * @param $driver \ImageServingDriver allow to force driver
	 *
	 * @return  \type{\arrayof{\topImage}}
	 */
	public function getImages( $n = 5, $driver = null) {
		global $wgMemc;

		wfProfileIn( __METHOD__ );
		$articles = $this->articles;
		$out = array();

		if( !empty( $articles ) ) {
			if( $this->db == null ) {
				$db = wfGetDB( DB_SLAVE, array() );
			} else {
				$db = $this->db;
			}

			$this->articlesByNS = array();
			foreach($articles as $key => $value) {
				$mcValue = $this->memc->get( $this->makeKey($key) , null );
				if(!empty($mcValue)) {
					unset($articles[$key]);
					$this->addArticleToList($mcValue);
				}
			}

			$res = $db->select(
				array( 'page' ),
				array(
					'page_namespace as ns',
					'page_title as title',
					'page_id as id'
				),
				array(
					'page_id' =>  $articles
				),
				__METHOD__
			);

			while ($row =  $db->fetchRow( $res ) ) {
				$this->addArticleToList($row);
			}


			if(empty($driver)) {
				foreach($this->imageServingDrivers as $key => $value ){
					if(!empty($this->articlesByNS[$key])) {
						$driver = new $value($db, $this);
						$driver->setArticlesList($this->articlesByNS[$key]);
						unset($this->articlesByNS[$key]);
						$out = $out + $driver->execute($n);
					}
				}

				$driver = new ImageServingDriverMainNS($db, $this);
				//rest of article in MAIN name spaces
				foreach( $this->articlesByNS as $value ) {
					$driver->setArticlesList( $value );
					$out = $out + $driver->execute($n);
				}
			} else {
				$driver = new $driver($db, $this);
				//rest of article in MAIN name spaces
				foreach( $this->articlesByNS as $value ) {
					$driver->setArticlesList( $value );
					$out = $out + $driver->execute($n);
				}
			}
			
			if(empty($out)){
				// TODO: Hook for finding fallback images.
				// TODO: Hook for finding fallback images.
			}
		}

		wfProfileOut(__METHOD__);

		return $out;
	}

	private function addArticleToList($value) {
		if( empty($this->articlesByNS[$value['ns']] )) {
			$this->articlesByNS[$value['ns']]  = array();
		}
		$this->articlesByNS[$value['ns']][$value['id']] = $value;
	}

	private function makeKey( $key  ) {
		return wfMemcKey("imageserving-article-details", $key);
	}

	/**
	 *  !!! deprecated !!! use getImages fetches an array with thumbnails and titles for the supplied files
	 *  TODO: remove it image serving work also with FILE_NS we keep this function for backward compatibility
	 * @author Federico "Lox" Lucignano
	 *
	 * @param Array $fileNames a list of file names to fetch thumbnails for
	 * @return Array an array containing the url to the thumbnail image and the title associated to the file
	 */
	public function getThumbnails( $fileNames = null ) {
		wfProfileIn( __METHOD__ );

		$imagesIds = array();
		if( !empty( $fileNames ) ) {
			foreach ( $fileNames as $fileName ) {
				if(!($fileName instanceof LocalFile)) {
					$title = Title::newFromText( $fileName, NS_FILE );
				} else {
					$img = $fileName;
					$title = $img->getTitle();
				}
			}

			$imagesIds[ $title->getArticleId() ] = $title->getDBkey();
			$this->articles[ $title->getArticleId() ] = $title->getArticleId();
		}

		$out = $this->getImages(1);

		$ret = array();
		foreach($imagesIds as $key => $value) {
			if(!empty($out[$key]) && count($out[$key]) > 0) {
				$ret[ $value ] = $out[$key][0];
			}
		}

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * getUrl - generate url for cut images
	 *
	 * @access public
	 *
	 * @param $name \string dbkey of image or File object
	 * @param $width \int
	 * @param $height \int
	 *
	 * @return  \string url for image
	 */

	public function getUrl( $name, $width = 0, $height = 0 ) {
		if ($name instanceof File) {
			$img = $name;
		}
		else {
			//TODO: Create files local cache of IS
			$file_title = Title::newFromText( $name ,NS_FILE );
			$img = wfFindFile( $file_title  );
			if( empty($img) ) {
				return "";
			}
		}

		$issvg = false;
		$mime = strtolower($img->getMimeType());
		if( $mime == 'image/svg+xml' || $mime == 'image/svg' ) {
			$issvg = true;
		}

		return wfReplaceImageServer( $img->getThumbUrl( $this->getCut( $width, $height ) . "-" . $img->getName().($issvg ? ".png":"") ) );
	}

	/**
	 * getUrl - generate cut frame for Thumb
	 *
	 * @param $width \int
	 * @param $height \int
	 * @param $align \string "center", "origin"
	 *
	 *
	 * @return \string prefix for thumb image
	 */
	public function getCut( $width, $height, $align = "center", $issvg = false  ) {
		//rescal of png always use width 512;
		if($issvg) {
			$height = round((512 * $height) / $width);
			$width = 512;
		}

		$pHeight = round(($width)*($this->proportion['h']/$this->proportion['w']));

		if($pHeight >= $height) {
			$pWidth =  round($height*($this->proportion['w']/$this->proportion['h']));
			$top = 0;
			if ($align == "center") {
				$left = round($width/2 - $pWidth/2) + 1;
			} else if ($align == "origin") {
				$left = 0;
			}
			$right = $left + $pWidth + 1;
			$bottom = $height;
		} else {
			if ($align == "center") {
				$deltaYpx = round($height*$this->deltaY);
				$bottom = $pHeight + $deltaYpx;
				$top = $deltaYpx;
			} else if ($align == "origin") {
				$bottom = $pHeight;
				$top = 0;
			}

			if( $bottom > $height ) {
				$bottom = $pHeight;
				$top = 0;
			}

			$left = 0;
			$right = $width;

		}
		return "{$this->width}px-$left,$right,$top,$bottom";
	}
}
