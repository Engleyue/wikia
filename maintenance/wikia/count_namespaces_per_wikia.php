<?php

/**
 * Counts namespaces usage across all Wikia pages
 *
 * @package MediaWiki
 * @addtopackage maintenance
 *
 * @author Władysław Bodzek
 */

ini_set( "include_path", dirname(__FILE__)."/../../maintenance/" );

$optionsWithArgs = array(
	'input',
	'namespace',
);


require_once( "commandLine.inc" );


class CountNamespacesPerWikia {
	
	const UNKNOWN_NAME_REGEX = "/^Namespace-(.*)\$/";

	public function __construct( $options ) {
		// load command line options
		$this->options = $options;
	}
	
	public function execute() {
		global $wgExternalSharedDB;
		$db = wfGetDB( DB_SLAVE, array(), $wgExternalSharedDB );
		
		$res = $db->select(
			'city_list',
			array( 'city_id', 'city_dbname' ),
			array( 'city_public' => 1),
			__METHOD__,
			array(
				'ORDER BY' => 'city_id',
			)
		);
		
		$data = array();
		while ($row = $db->fetchObject($res)) {
			echo "Count namespace for Wiki: " . $row->city_dbname . " \n";
			
			$dbr = wfGetDB( DB_SLAVE, array(), $row->city_dbname );
			
			$res2 = $dbr->select(
				'page',
				array( 'page_namespace', 'count(page_namespace) as cnt' ),
				array(),
				__METHOD__
			);			
			
			while ( $row2 = $dbr->fetchObject( $res2 ) ) {
				$name = $data[ $row2->page_namespace ]['ns_name'];
				if ( empty( $name ) ) {
					$name = $this->resolveNamespace( $row2->page_namespace );
				}
				$data[ $row2->page_namespace ] = array(
					'ns_name' => $name,
					'cnt' => $data[ $row2->page_namespace ] + $row2->cnt
				);
			}
			$dbr->freeResult($res2);
		}
		$db->freeResult($res);
		
		echo "\n\n\n\n";
		foreach ( $data as $ns => $cnt ) {
			echo $ns. ";" . $cnt . "\n";
		}
		
		return $data;
	}
	
	public function stderr() {
		static $fp;
		if (!$fp) {
			$fp = fopen("php://stderr","w");
		}
		$args = func_get_args();
		foreach ($args as $v) {
			fwrite($fp,(string)$v);
		}
	}
	
	public function readList( $input ) {
		$contents = @file_get_contents($input);
		$lines = preg_split("/[\r\n]+/",$contents);
		
		$data = array();
		foreach ($lines as $line) {
			if ( empty($line) ) continue; 
			list( $ns, $name, $count ) = explode(',',$line);
			$ns = intval($ns);
			$data[$ns] = array(
				'id' => $ns,
				'name' => $name,
				'count' => intval($count),
			);
		}
		return $data;
	}
	
	public function getNamespaceName( $ns ) {
		$ns = intval($ns);
		if (!$ns) {
			$name = "Main";
		} else {
			$name = MWNamespace::getCanonicalName($ns);
			if ( empty($name) ) {
				$name = "Namespace-{$ns}";
			}
		}
		return $name;
	}
	
	public function isUnknown( $name, &$ns = null ) {
		if (preg_match(self::UNKNOWN_NAME_REGEX,$name,$matches)) {
			$ns = intval($matches[1]);
			return true;
		}
		return false;
	}
	
	public function resolveNamespace( $ns ) {
		$name = $this->getNamespaceName($ns);
		if ($this->isUnknown($name)) {
			global $wgExternalDatawareDB;
			$db = wfGetDB( DB_SLAVE, array(), $wgExternalDatawareDB );
			
			$this->stderr("finding wiki with edits in namespace $ns\n");
			$row = $db->selectRow(
				'pages',
				'page_wikia_id',
				array(
					'page_namespace' => $ns,
				),
				__METHOD__,
				array(
					'LIMIT' => 1,
				)
			);
			
			if ($row) {
				$wikiId = $row->page_wikia_id;
				$this->stderr("executing script to fetch namespace name from wiki $wikiId\n");
				$dir = dirname(__FILE__);
				$conf = $this->options['conf'];
				$cmd = "SERVER_ID={$wikiId} php {$dir}/count_namespaces.php --conf {$conf} --namespace $ns";
				
				$output = array();
				$name = exec($cmd,$output);
				$this->stderr("found namespace $ns to be called \"$name\"\n");
			}
		}
		return $name;
	}
	
	public function count_namespace() {
		if ( array_key_exists('namespace',$this->options)) {
			echo $this->getNamespaceName($this->options['namespace']);
		} else {
			$list = array();
			if ( array_key_exists('input',$this->options)) {
				$list = $this->readList($this->options['input']);
			} else {
				$list = $this->getList();
			}

			foreach ($list as $k => $n) {
				$name = $n['name'];
				if ($this->isUnknown($name)) {
					$this->stderr("resolving namespace {$n['id']}\n");
					$name = $this->resolveNamespace($n['id']);
				}
				$list[$k]['name'] = $name;
			}
			
			foreach ($list as $k => $v) {
				echo "{$v['id']},{$v['name']},{$v['count']}\n";
			}
		}
	}

}

/**
 * used options:
 */
$maintenance = new CountNamespacesPerWikia( $options );
$maintenance->execute();
