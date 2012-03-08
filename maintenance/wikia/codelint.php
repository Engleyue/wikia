<?php
/**
 * @addto maintenance
 * @author Maciej Brencz (Macbre) <macbre at wikia-inc.com>
 * @see https://internal.wikia-inc.com/wiki/CodeLint
 *
 * SERVER_ID=1 php codelint.php --conf=/usr/wikia/docroot/wiki.factory/LocalSettings.php --mode=php --file=/home/macbre/trunk/extensions/wikia/CodeLint/examples/anticode.php
 *
 */
ini_set( "include_path", dirname(__FILE__)."/.." );
require_once( 'commandLine.inc' );

function printHelp() {
		echo <<<HELP
Performs lint check on given file / directory
USAGE: php codelint.php --file|--dir [--help] [--blacklist] [--mode] [--format] [--output]

	--help
		Show this help information

	--file
		File(s) to lint (you can provide multiple files separated by comma)

	--dir
		Directory to lint (subdirectories will be included, you can provide multiple directories separated by comma)

	--blacklist
		Comma separated list of directories / files to skip when performing lint check

	--mode
		Set working mode (can be "php", "js" or "css")

	--format[=text]
		Report format (either text, json or html)

	--output
		Name of the file to write report to

HELP;
}

if (!CodeLint::isNodeJsInstalled()) {
	echo "You need to have nodejs installed in order to use this tool!\n\n";
	die(1);
}

// show help and quit
if (isset($options['help'])) {
	printHelp();
	die(1);
}

// parse command line options
$files = isset($options['file']) ? explode(',', $options['file']) : false;
$dirs = isset($options['dir']) ? explode(',', $options['dir']) : false;
$blacklist = isset($options['blacklist']) ? explode(',', $options['blacklist']) : false;
$mode = isset($options['mode']) ? $options['mode'] : 'js';
$format = isset($options['format']) ? $options['format'] : 'text';
$output = isset($options['output']) ? $options['output'] : false;

// create lint class
try {
	$lint = CodeLint::factory($mode);
}
catch (Exception $e) {
	echo $e->getMessage() . "\n\n";
	printHelp();
	die(1);
}

// perform code linting
if (!empty($files)) {
	$results = $lint->checkFiles($files, $blacklist);
}
else if (!empty($dirs)) {
	$results = $lint->checkDirectories($dirs, $blacklist);
}
else {
	echo "Please provide either file name or directory to lint\n\n";
	printHelp();
	die(1);
}

//var_dump($results);

// generate report
$report = $lint->formatReport($results, $format);

// save it to file
if (!empty($output)) {
	file_put_contents($output, $report);
	echo "\nReport saved to {$output}\n";
}
else {
	echo $report;
}

// this script is run by CruiseControl requiring return code to equal zero
die(0);