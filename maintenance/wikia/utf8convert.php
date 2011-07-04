<?php

/**
 * Script converts local mediawiki database
 * to use UTF-8 character set (with collation utf8_bin)
 *
 * By default script outputs on stdout SQL which needs to be executed.
 * You can use --force to actually run these queries immediately.
 *
 * @package MediaWiki
 * @addtopackage maintenance
 *
 * @author Władysław Bodzek
 */

ini_set( "include_path", dirname(__FILE__)."/../../maintenance/" );

$optionsWithArgs = array(
	'database',
);


require_once( "commandLine.inc" );

class SqlCommand {
	protected $command = '';
	protected $arguments = array();
	protected $comments = array();
	public function __construct( $command ) {
		$this->command = $command;
	}
	public function add( $argument, $priority = 0 ) {
		$this->arguments[] = array( $priority, $argument );
	}
	public function comment( $comment, $key = null ) {
		if (is_null($key)) {
			$this->comments[] = $comment;
		} else {
			$this->comments[$key] = $comment;
		}
	}
	public function getCommand() { return $this->command; }
	public function getArguments() { return $this->arguments; }
	public function getComments() { return $this->comments; }
}

class SqlScript {
	const MODE_DEFAULT = 0;
	const MODE_QUICK = 1;
	protected $commands = array();
	protected function get( $id, $command ) {
		if (!isset($this->commands[$id])) {
			$this->commands[$id] = new SqlCommand($command);
		}
		return $this->commands[$id];
	}
	public function database( $name ) {
		$name = strtolower($name);
		return $this->get("DATABASE:{$name}","ALTER DATABASE {$name}");
	}
	public function table( $name ) {
		$name = strtolower($name);
		return $this->get("TABLE:{$name}","ALTER TABLE {$name}");
	}
	public function getCommands() {
		return $this->commands;
	}
	public function getSql( $flags = self::MODE_DEFAULT ) {
		$sql = array();
		foreach ($this->commands as $command)
			$sql = array_merge( $sql, $this->getSqlFromCommand($command,$flags) );
		return $sql;
	}
	public function getSqlFromCommand( SqlCommand $commandObj, $flags = self::MODE_DEFAULT ) {
		$sql = array();
		
		$command = $commandObj->getCommand();
		$arguments = $commandObj->getArguments();
		$comments = $commandObj->getComments();
		foreach ($comments as $comment) {
			$sql[] = $comment;
		}
		if ($flags == self::MODE_QUICK) {
			$a = array();
			foreach ($arguments as $argument)
				$a[$argument[0]][] = $argument[1];
			ksort($a);
			foreach ($a as $priority => $list) {
//				var_dump("-------",$arguments,$priority,$list);
				$sql[] = "{$command}\n  ".implode(",\n  ",$list).";\n";
			}
		} else {
			foreach ($arguments as $argument)
				$sql[] = "{$command} {$argument[1]};\n";
		}
		
		return $sql;
	}
	
}

class DatabaseWalker {
	protected $connection = null;
	protected $name = '';
	public function __construct( $connection ) {
		$this->connection = $connection;
		$this->name = $this->connection->getDBname();
	}
	protected function getDb() {
		return $this->connection;
	}
	public function getDatabases() {
		$db = $this->getDb();
		$set = $db->select("`information_schema`.`schemata`",'*',array(
			'schema_name' => $this->name,
		),__METHOD__);
		
		$data = array();
		while ($row = $db->fetchObject($set)) {
			$data[] = $row;
		}
		return $data;
	}
	public function getTables() {
		$db = $this->getDb();
		$set = $db->select("`information_schema`.`tables`",'*',array(
			'table_schema' => $this->name,
		),__METHOD__);
		
		$data = array();
		while ($row = $db->fetchObject($set)) {
			$data[] = $row;
		}
		return $data;
	}
	public function getFields() {
		$db = $this->getDb();
		$set = $db->select("`information_schema`.`columns`",'*',array(
			'table_schema' => $this->name,
		),__METHOD__);
		
		$data = array();
		while ($row = $db->fetchObject($set)) {
			$data[] = $row;
		}
		return $data;
	}
}


class Utf8DbConvert {

	protected $force = false;

	public function __construct( $options ) {
		// load command line options
		$this->options = $options;
		
		global $wgDBname;
		$this->force = isset($options['force']);
		$this->quick = isset($options['quick']);
		$this->databaseName = isset($options['database']) ? $options['database'] : $wgDBname;
		
		$this->db = wfGetDb(DB_SLAVE,array(),$this->databaseName);
		$this->walker = new DatabaseWalker($this->db);
		
		$this->script = new SqlScript();
	}
	
	protected function getDb() {
		return $this->db;
	}

	protected function query( $sql ) {
		$t = microtime(true);
		$timestamp = gmdate( 'Y-m-d H:i:s.', (int)$t ) . sprintf("%06d",($t - floor($t)) * 1000000);
		echo "-- $timestamp\n";
		echo $sql . "\n";
		if ($this->force) {
			$this->getDb()->query($sql);
		}
	}

	protected function processDatabases() {
		$databases = $this->walker->getDatabases();
		foreach ($databases as $database) {
			if ($database->DEFAULT_COLLATION_NAME !== 'utf8_bin') {
				$this->script->database($database->SCHEMA_NAME)->add("DEFAULT CHARACTER SET utf8 COLLATE utf8_bin");
//				$sqlList[] = "ALTER DATABASE `{$database->SCHEMA_NAME}` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";
			}
		}
	}

	protected function processTables() {
		$tables = $this->walker->getTables();
		foreach ($tables as $table) {
			if ($table->TABLE_COLLATION !== 'utf8_bin') {
				$this->script->table($table->TABLE_NAME)->add("DEFAULT CHARACTER SET utf8 COLLATE utf8_bin");
//				$sqlList[] = "ALTER TABLE `{$table->TABLE_NAME}` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";
			}
		}
	}

	protected function getIntermediateFieldType( $type, &$baseType = null ) {
		list( $baseType ) = explode( '(', $type, 2 );
		$typeExtra = substr( $type, strlen($baseType) );
		$baseType = strtolower($baseType);
		$matrix = array(
			'tinytext' => 'tinyblob',
			'text' => 'blob',
			'mediumtext' => 'mediumblob',
			'longtext' => 'longblob',
			'char' => 'binary',
			'varchar' => 'varbinary',
		);
		if (isset($matrix[$baseType])) {
			return $matrix[$baseType] . $typeExtra;
		}
		return false;
	}

	protected function dumpTable( $tableName ) {
		static $lastTableName = null;
		if ($tableName == $lastTableName) return false;
		$lastTableName = $tableName;
		$db = $this->getDb();
		$set = $db->query("SHOW CREATE TABLE `{$tableName}`;");
		while ($row = $db->fetchRow($set)) {
			$sqlLines = preg_split("/\r?\n/",$row[1]);
			foreach ($sqlLines as $k => $line) {
				$sqlLines[$k] = "-- {$line}";
			}
			$sql = implode("\n",$sqlLines);
			return $sql;
		}
	}

	protected function processFields() {
		$fields = $this->walker->getFields();
		foreach ($fields as $field) {
			if (!is_null($field->COLLATION_NAME) && $field->COLLATION_NAME !== 'utf8_bin') {
				if (!$this->force) {
					$tableDump = $this->dumpTable($field->TABLE_NAME);
					if ($tableDump) {
						$this->script->table($field->TABLE_NAME)->comment($tableDump);
//						$sqlList[] = $tableDump;
					}
				}
				$rest = ($field->IS_NULLABLE == 'YES' ? "NULL" : "NOT NULL")
					. (!is_null($field->COLUMN_DEFAULT) ? " DEFAULT '{$field->COLUMN_DEFAULT}'" : "");
				$baseType = null;
				$transColumnType = $this->getIntermediateFieldType( $field->COLUMN_TYPE, $baseType );
				if ($transColumnType) {
					$this->script->table($field->TABLE_NAME)->add("MODIFY {$field->COLUMN_NAME} {$transColumnType} $rest");
					$this->script->table($field->TABLE_NAME)->add("MODIFY {$field->COLUMN_NAME} {$field->COLUMN_TYPE} CHARACTER SET utf8 COLLATE utf8_bin {$rest}",1);
//					$sqlList[] = "ALTER TABLE `{$field->TABLE_NAME}` MODIFY {$field->COLUMN_NAME} {$transColumnType} $rest;";
//					$sqlList[] = "ALTER TABLE `{$field->TABLE_NAME}` MODIFY {$field->COLUMN_NAME} {$field->COLUMN_TYPE} CHARACTER SET utf8 COLLATE utf8_bin {$rest};";
				} else if ($baseType == 'enum') {
					$this->script->table($field->TABLE_NAME)->add("MODIFY {$field->COLUMN_NAME} {$field->COLUMN_TYPE} CHARACTER SET utf8 COLLATE utf8_bin {$rest}");
//					$sqlList[] = "ALTER TABLE `{$field->TABLE_NAME}` MODIFY {$field->COLUMN_NAME} {$field->COLUMN_TYPE} CHARACTER SET utf8 COLLATE utf8_bin {$rest};";
				} else {
					$this->script->table($field->TABLE_NAME)->comment("--{$field->TABLE_NAME}.{$field->COLUMN_NAME} -- could not find intermediate type for {$field->COLUMN_TYPE}\n");
//					$sqlList[] = "--{$field->TABLE_NAME}.{$field->COLUMN_NAME} -- could not find intermediate type for {$field->COLUMN_TYPE}\n";
				}
			}
		}
	}
	
	protected function executeScript() {
		$mode = SqlScript::MODE_DEFAULT;
		if ($this->quick)
			$mode = SqlScript::MODE_QUICK;
		$sql = $this->script->getSql($mode);
		foreach ($sql as $stmt)
			$this->query($stmt);
	}

	public function execute() {
		$this->query("-- GATHERING DATA");
		$this->processDatabases();
		$this->processTables();
		$this->processFields();
//		echo "execute\n";
//		var_dump($this->script);
		$this->query("-- PERFORMING CHANGES");
		$this->executeScript();
		$this->query("-- ALL DONE");
	}

}

/**
 * used options:
 */
$maintenance = new Utf8DbConvert( $options );
$maintenance->execute();
