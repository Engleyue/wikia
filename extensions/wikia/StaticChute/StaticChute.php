<?php

class StaticChute {

	public $fileType; // js|css|html
	public $supportedFileTypes = array('js', 'css', 'html');
	public $minify = true;
	public $compress = true;
	public $httpCache = true;
	public $bytesIn = 0;
	public $bytesOut = 0;

	public $config = array();

	// macbre: RT #18410
	private $path = false;

	// macbre: RT #18765
	private $theme = false;

	// RT #23935 - one value to purge'em all
	const imagesCacheBuster = '000';

	public function __construct($fileType){
		// macbre: we will return HTTP 400 when file type is invalid (RT #18825)
		if (! in_array($fileType, $this->supportedFileTypes)){
			return;
		}

		$this->fileType = $fileType;

		$this->generateConfig();
	}


	private function generateConfig(){
		$widgetsAssets = $this->getWidgetsAssets();

		$this->config = array();

		// JS served for anon on article view
		$this->config['monaco_anon_article_js'] = array(
			'common/jquery/jquery-1.3.2.js',
			'common/jquery/jquery.json-1.3.js',
			'common/jquery/jquery.cookies.2.1.0.js',
			'common/jquery/jquery.wikia.js',
			'common/jquery/jquery.timeago.js',

			'common/ajax.js',
			'common/urchin.js',
			'common/wikibits.js',
			'monaco/js/main.js',
			'monaco/js/tracker.js',
			'monaco/js/SearchAutoComplete.js',
			'common/widgets/js/widgetsConfig.js',
			'monaco/js/widgetsFramework.js',
			'../extensions/wikia/ProblemReports/js/ProblemReports-loader.js',
			'../extensions/wikia/AdEngine/AdEngine.js',
			'../extensions/wikia/Userengagement/Userengagement.js',
			'../extensions/wikia/TieDivLibrary/TieDivLibrary.js',
			'common/contributed.js',
			'../extensions/wikia/ShareFeature/js/ShareFeature.js',
			'../extensions/wikia/CreatePage/js/CreatePage.js',
			'../extensions/wikia/Interstitials/Interstitials.js',
		);
		$this->config['monaco_anon_article_js'] = array_merge($this->config['monaco_anon_article_js'], $widgetsAssets['js']);

		// JS served for logged-in
		$this->config['monaco_loggedin_js'] = array(
			'common/yui_2.5.2/utilities/utilities.js',
			'common/yui_2.5.2/cookie/cookie-beta.js',
			'common/yui_2.5.2/container/container.js',
			'common/yui_2.5.2/autocomplete/autocomplete.js',
			'common/yui_2.5.2/animation/animation-min.js',
			'common/yui_2.5.2/logger/logger.js',
			'common/yui_2.5.2/menu/menu.js',
			'common/yui_2.5.2/tabview/tabview.js',
			'common/yui_2.5.2/slider/slider.js',
			'common/yui_extra/tools-min.js',
			'common/yui_extra/carousel-min.js',

			'common/jquery/jquery-1.3.2.js',
			'common/jquery/jquery.json-1.3.js',
			'common/jquery/jquery.cookies.2.1.0.js',
			'common/jquery/jquery.wikia.js',
			'common/jquery/jquery-ui-1.7.1.custom.js',
			'common/jquery/jquery.timeago.js',

			'common/ajax.js',
			'common/urchin.js',
			'common/wikibits.js',
			'common/ajaxwatch.js',
			'monaco/js/main.js',
			'monaco/js/tracker.js',
			'monaco/js/SearchAutoComplete.js',
			'common/widgets/js/widgetsConfig.js',
			'monaco/js/widgetsFramework.js',
			'../extensions/wikia/ProblemReports/js/ProblemReports-loader.js',
			'../extensions/wikia/AdEngine/AdEngine.js',
			'../extensions/wikia/TieDivLibrary/TieDivLibrary.js',
			'common/contributed.js',
			'../extensions/wikia/ShareFeature/js/ShareFeature.js',
			'../extensions/wikia/CreatePage/js/CreatePage.js',
		);
		$this->config['monaco_loggedin_js'] = array_merge($this->config['monaco_loggedin_js'], $widgetsAssets['js']);

		// JS served for anon for everything that's not an article view
		$this->config['monaco_anon_everything_else_js'] = array(
			'common/yui_2.5.2/utilities/utilities.js',
			'common/yui_2.5.2/cookie/cookie-beta.js',
			'common/yui_2.5.2/container/container.js',
			'common/yui_2.5.2/autocomplete/autocomplete.js',
			'common/yui_2.5.2/animation/animation-min.js',
			'common/yui_2.5.2/logger/logger.js',
			'common/yui_2.5.2/menu/menu.js',
			'common/yui_2.5.2/tabview/tabview.js',
			'common/yui_2.5.2/slider/slider.js',
			'common/yui_extra/tools-min.js',

			'common/jquery/jquery-1.3.2.js',
			'common/jquery/jquery.json-1.3.js',
			'common/jquery/jquery.cookies.2.1.0.js',
			'common/jquery/jquery.wikia.js',
			'common/jquery/jquery.timeago.js',

			'common/ajax.js',
			'common/urchin.js',
			'common/wikibits.js',
			'monaco/js/main.js',
			'monaco/js/tracker.js',
			'monaco/js/SearchAutoComplete.js',
			'common/widgets/js/widgetsConfig.js',
			'monaco/js/widgetsFramework.js',
			'../extensions/wikia/ProblemReports/js/ProblemReports-loader.js',
			'../extensions/wikia/AdEngine/AdEngine.js',
			'../extensions/wikia/Userengagement/Userengagement.js',
			'../extensions/wikia/TieDivLibrary/TieDivLibrary.js',
			'common/contributed.js',
			'../extensions/wikia/ShareFeature/js/ShareFeature.js',
			'../extensions/wikia/CreatePage/js/CreatePage.js',
			'../extensions/wikia/Interstitials/Interstitials.js',
		);
		$this->config['monaco_anon_everything_else_js'] = array_merge($this->config['monaco_anon_everything_else_js'], $widgetsAssets['js']);

		// YUI package
		$this->config['yui'] = array(
			'common/yui_2.5.2/utilities/utilities.js',
			'common/yui_2.5.2/cookie/cookie-beta.js',
			'common/yui_2.5.2/container/container.js',
			'common/yui_2.5.2/autocomplete/autocomplete.js',
			'common/yui_2.5.2/animation/animation-min.js',
			'common/yui_2.5.2/logger/logger.js',
			'common/yui_2.5.2/menu/menu.js',
			'common/yui_2.5.2/tabview/tabview.js',
			'common/yui_2.5.2/slider/slider.js',
			'common/yui_extra/tools-min.js',
			'common/yui_extra/carousel-min.js',
		);

		// jQuery package (for home skin)
		$this->config['jquery'] = array(
			'common/jquery/jquery-1.3.2.js',
			'common/jquery/jquery.json-1.3.js',
			'common/jquery/jquery.cookies.2.1.0.js',
			'common/jquery/jquery.wikia.js',
		);

		// JS for monobook (both anons/logged-in)
		$this->config['monobook_js'] = array(
			'common/yui_2.5.2/utilities/utilities.js',
			'common/yui_2.5.2/cookie/cookie-beta.js',
			'common/yui_2.5.2/container/container.js',
			'common/yui_2.5.2/autocomplete/autocomplete.js',
			'common/yui_2.5.2/logger/logger.js',
			'common/yui_2.5.2/menu/menu.js',
			'common/yui_2.5.2/tabview/tabview.js',
			'common/yui_extra/tools-min.js',

			'common/jquery/jquery-1.3.2.js',
			'common/jquery/jquery.json-1.3.js',
			'common/jquery/jquery.cookies.2.1.0.js',
			'common/jquery/jquery.wikia.js',

			'common/urchin.js',
			'common/wikibits.js',
			'monobook/main.js',
			'monobook/tracker.js',
			'common/tracker.js',
			'../extensions/wikia/ProblemReports/js/ProblemReports-loader.js',
			'common/contributed.js',
		);

		// CSS
		$this->config['monaco_css'] = array(
			'common/yui_2.5.2/container/assets/container.css',
			'common/yui_2.5.2/tabview/assets/tabview.css',
        		'common/shared.css',
			'monaco/css/monobook_modified.css',
			'monaco/css/reset_modified.css',
			'common/wikia-ui.css',
			'monaco/css/root.css',
			'monaco/css/header.css',
			'monaco/css/article.css',
			'monaco/css/widgets.css',
			'monaco/css/modal.css',
			'monaco/css/footer.css',
			'monaco/css/star_rating.css',
			'monaco/css/ny.css',
			'../extensions/wikia/Blogs/css/Blogs.css',
			'../extensions/wikia/Masthead/css/Masthead.css',
			'../extensions/wikia/ShareFeature/css/ShareFeature.css',
			'../extensions/wikia/CreatePage/css/CreatePage.css',
		);
		$this->config['monaco_css'] = array_merge($this->config['monaco_css'], $widgetsAssets['css']);

		// printable CSS
		$this->config['monaco_css_print'] = array(
			'monaco/css/print.css',
			'common/commonPrint.css',
		);
		
		$this->config['corporate_page_js'] = array(
			'common/wikibits.js',
			'common/jquery/jquery-1.4.1.min.js',
			'common/jquery/jquery.wikia.js',
			'common/urchin.js',
			'corporate/js/main.js',
			'monaco/js/tracker.js',
			'corporate/js/tracker.js',
		);

		$this->config['corporate_specialpage_js'] = array(
			'common/wikibits.js',
			'common/jquery/jquery-1.4.1.min.js',
			'common/jquery/jquery.wikia.js',
			'common/urchin.js',
			'corporate/js/main.js',
			'monaco/js/tracker.js',
			'corporate/js/tracker.js',
			'common/yui_2.5.2/utilities/utilities.js',
			'common/yui_extra/tools-min.js',
		);
		
		$this->config['corporate_page_css'] = array(		
			'common/yui300css-reset-min.css',
			'common/wikia-ui.css',	
			'common/shared.css',
			'corporate/css/modal.css',
			'corporate/css/main.css',
		);
	}


	/* message function that will print the message appropriately based on the format */
	public function comment ($msg){
		switch ($this->fileType){
		  case 'js': return "\n/*" . $msg . "*/\n";
		  case 'css': return "\n/*" . $msg . "*/\n";
		  case 'html': return "\n<!-- ". htmlspecialchars($msg) . "-->\n";
		  default: return htmlspecialchars($msg);
		}
	}


	/* For the supplied arguments, return a list of files to include args is an array (usually $_GET)
 	 * that can contain either 'package' or 'files' & 'dir'
 	 * 'packages' is a a way to call a predefined list of files for include. For example 'anon'. Multiple
 	 * packages can be included, separated by comma
 	 * 'files' is a csv separated list of files to include from 'dir' (default /)
 	 */
	public function getFileList($args){
		$out = array();

		if (!empty($args['packages'])){
			$basedir = realpath(dirname(__FILE__) . '/../../../skins/');
			foreach(explode(',', $args['packages']) as $package){
				if (empty($this->config[$package])){
					continue;
				} else {
					foreach ($this->config[$package] as $f){
						$out[] = realpath($basedir . '/' . $f);
					}

					// macbre: rt #18765
					// add possibility to add more files to current package
					$moreFiles = $this->getMoreFileList($package, $args);
					if (!empty($moreFiles)) {
						foreach ($moreFiles as $f) {
							$out[] = realpath($basedir . '/' . $f);
						}
					}
				}
			}

		} else if (!empty($args['files'])){
			$basedir = realpath(dirname(__FILE__) . '/../../../');
			foreach(explode(',', $args['files']) as $file){
				// We don't trust user input. Check to make sure the requested file is
				// in the document root
				$rfile = realpath($basedir . $file);
				if (!preg_match("#^$basedir#", $rfile)){
					trigger_error("Requested file $file is not in document root", E_USER_WARNING);
					continue;
				} else {
					$out[] = $rfile;
				}
			}
		}

		return $out;
	}

	private function getMoreFileList($package, $args) {
		switch($package) {
			case 'monaco_css':
				if (!empty($args['usetheme'])) {
					return array('monaco/' . basename($args['usetheme']) . '/css/main.css');
				}
				break;
		}
		return false;
	}

	/* Walk through a list of files and get the latest modified time in the list
	* @param $files -array of files to check. Assumed to be relative to basedir
	* @return unix timestamp of the latest modified file, -1 if no files
	* @return string containing minfied javascript, unless there is an error, then the un-minified javascript
	*/
	private function getLatestMod($files){
		$maxtime = -1;
		foreach($files as $file){
			$time=@filemtime($this->unixbasedir . $file);
			if ($time > $maxtime){
				$maxtime = $time;
			}
		}

		return $maxtime;
	}

	/* We used to use the lastmod, but this isn't reliable in production environments where
	 * we have multiple apaches that have different timestamps on the files, it
	 * produces too many distinct urls
	 */
	public function getChecksum($files){
		$data = '';
		foreach($files as $file){
			$data .= file_get_contents($file);
		}
		return md5($data . self::imagesCacheBuster);
	}

	public function getChuteHtmlForPackage($package, $type = null){
		wfProfileIn(__METHOD__);

		global $wgStylePath, $wgStyleVersion;

		if ($type === null){
			$type = $this->fileType;
		}

		// detect whether to use merged JS/CSS files
		global $wgAllInOne, $wgRequest;
		if(empty($wgAllInOne)) {
			$wgAllInOne = false;
		}
		$this->allinone = $wgRequest->getBool('allinone', $wgAllInOne);

		// detect whether user requested printable version of the page
		$this->printable = $wgRequest->getBool('printable');

		if ($this->allinone) {
			// get URL to StaticChute
			$urls = array($this->getChuteUrlForPackage($package, $type));
			$prefix = '';
			$cb = '';
		}
		else {
			// include files separately
			global $wgStyleVersion;
			$urls = $this->config[$package];

			// get more files (rt #18765)
			$moreUrls = $this->getMoreFileList($package, array('usetheme' => $this->theme));
			if (!empty($moreUrls)) {
				$urls = array_merge($urls, $moreUrls);
			}

			$prefix = $wgStylePath . '/';
			$cb = "?{$wgStyleVersion}";
		}

		if ($type == 'css') {
			$media = $this->getPackageMediaType($package);

			if ($media == 'print' && $this->printable) {
				$media = '';
			}

			if (!empty($media)) {
				$media = " media=\"{$media}\"";
			}
		}

		$html = '';
		foreach ($urls as $u){
			$u = htmlspecialchars($u);
			if ($type == "css"){
				if ($this->allinone) {
					$html .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$prefix}{$u}{$cb}\"{$media} />";
				}
				else {
					// IE has some strange limit of ~30 links per page
					// output <style> + @import when not using merged CSS files
					$html .= "\n\t\t\t@import url({$prefix}{$u}{$cb});";
				}
			} else if ($type == "js"){
				$html .= "<script type=\"text/javascript\" src=\"{$prefix}{$u}{$cb}\"></script>";
			}
		}

		if ($type == 'css' && !$this->allinone) {
			$html = "<style type=\"text/css\"{$media}>{$html}\n\t\t</style>";
		}

		wfProfileOut(__METHOD__);

		return $html;
	}

	// macbre: RT #18410
	public function setChuteUrlPath($path) {
		$this->path = $path;
	}

	public function getChuteUrlPath() {
		global $wgExtensionsPath;
		return !empty($this->path) ? $this->path : $wgExtensionsPath;
	}

	public function useLocalChuteUrl() {
		global $wgServer, $wgScriptPath;
		/**
		 * do not use wgScriptPath, problem with dofus/memory-alpha
		 * rt#18410
		 */
		$this->setChuteUrlPath($wgServer . '/extensions');
	}

	// macbre: RT #18765
	public function setTheme($theme) {
		$this->theme = $theme;
	}

	public function getChuteUrlForPackage($package, $type = null){
		if ($type === null){
			$type = $this->fileType;
		}
		$files = $this->getFileList(array('packages'=>$package));

		if (empty($files)){
			trigger_error("Invalid package for " . __METHOD__, E_USER_WARNING);
			return false;
		}

		$checksum = $this->getChecksum($files);

		$params = array('type'=> $type, 'packages'=> $package, 'checksum'=> $checksum);

		// macbre: RT #18765
		if (!empty($this->theme)) {
			$params['usetheme'] = $this->theme;
		}

		return $this->getChuteUrlPath() . '/wikia/StaticChute/?' . http_build_query($params);
	}

	private function getWidgetsAssets() {
		$js = $css = array();
		$dir = dirname(__FILE__) . '/../WidgetFramework/Widgets/';
		if(is_dir($dir)) {
			if($dh = opendir($dir)) {
				while(($file = readdir($dh)) !== false) {
					if(filetype($dir.$file) == 'dir') {
						if(file_exists($dir.$file.'/'.$file.'.js')) {
							$js[] = '../extensions/wikia/WidgetFramework/Widgets/'.$file.'/'.$file.'.js';
						}
						if(file_exists($dir.$file.'/'.$file.'.css')) {
							$css[] = '../extensions/wikia/WidgetFramework/Widgets/'.$file.'/'.$file.'.css';
						}
					}
				}
			}
			closedir($dh);
		}

		// Nick wrote: opendir will sort the files differently on different servers.
		// Force them to a consistent order so that get checksum works properly
		sort($js);
		sort($css);
		return array('js' => $js, 'css' => $css);
	}

	private function getPackageMediaType($package) {
		if (substr($package, -6) == '_print') {
			$media = 'print';
		}
		else {
			$media = '';
		}
		return $media;
	}

	public function minifyHtmlData($html){
		// Taking the easy, safe path. This could be improved if you want to go through the
		// effort/expense/risk of processing the DOM. For now just strip leading space on each line
		$min = preg_replace('/^\s+/', '', $html);
    		return $min;
	}

	public function minifyHtmlFile($file){
		$html = file_get_contents($file);
    		return self::minifyHtmlData($html);
	}


	public function minifyCssData($css){
		require_once dirname(__FILE__) . '/Minify_CSS_Compressor.php';

		// macbre: RT #11257 - add ? to images included in CSS
		$css = preg_replace("#\.(png|gif)([\"'\)]+)#s", '.\\1?' . self::imagesCacheBuster . '\\2', $css);

    		return Minify_CSS_Compressor::process($css);
	}

	public function minifyCssFile($file){
		$css = file_get_contents($file);
    		return $this->minifyCssData($css);
	}

	/* Remove comments and superfluous white space from javascript.
	* Utilize JSMin from Douglas Crawford
	* http://www.crockford.com/javascript/jsmin.html
	* This file will need to be compiled by running "make" in this directory
	*
	* @param $js - javascript code to minimize
	* @return minified js, unless there is an error, return original js
	*/
	public function minifyJSFile($jsfile){

		$jsmin = dirname(__FILE__) . '/jsmin';
		if (!is_executable($jsmin)){
			$min = $this->comment("jsmin binary missing or not executable, reverting to MUCH slower PHP method") . $this->minifyJSPHP(file_get_contents($jsfile));
		} else {
			$min = shell_exec("cat $jsfile | $jsmin");
		}

    		return $min;
	}

	public function minifyJSData($js){
		// Write the data to a temporary file first
		$tmpfile = tempnam(sys_get_temp_dir(), 'minifyTemp');
		file_put_contents($tmpfile, $js);

		$min = self::minifyJSFile($tmpfile);
		unlink($tmpfile);

    		return $min;
	}


	/* Remove comments and superfluous white space from javascript.
	* Utilize JSMin from Douglas Crawford
	* http://www.crockford.com/javascript/jsmin.html
	* This is the PHP port, which is a backup if the C version isn't available
	* We utilize caching heavily, but if performance becomes an issue, use consider the C version
	*
	* @param $js - javascript code to minimize
	* @return minified js, unless there is an error, return original js
	*/
	public function minifyJSPHP($js){

		// This is kinda slow. We need to cache.
		$cacheDir = sys_get_temp_dir() . '/minifyCache';
		if (mt_rand(1,10000) == 42){
			// One out of every 10000 requests, clear out the cache
			exec("rm -rf $cacheDir");
		}
		if (!is_dir($cacheDir)){
			mkdir($cacheDir, 0777, true); //recursively create the cache dir
		}

		$cacheFile = $cacheDir . '/' . md5($js) . '.' . $this->fileType;
		if (file_exists($cacheFile)){
			return file_get_contents($cacheFile);
		}

		require_once dirname(__FILE__) . '/JSMin.php';
		try {
			$min = JSMin::minify($js);
		} catch (JSMinException $e){
			$msg = "Error minifying javascript: " . $e->getMessage();
			trigger_error($msg, E_USER_WARNING);
			return $js . $this->comment($msg);
		}

		// Cache
		file_put_contents($cacheFile, $min);

		return $min;
	}


	/* Take a list of $files, checks / sets http headers, and returns the combined output (if applicable)
	* @param $files - array of files to process. Full unix path. See getFileList()
	* @return can be one of:
		string output if successful
		bool false on error
		bool true for a conditional get that was not modified (304)
	*/
	public function process($files){
		if (!is_array($files) || empty($files)){
			header('HTTP/1.0 400 Bad Request');
			trigger_error("$files must be an array of file names", E_USER_WARNING);
			return false;
	 	}

		// If the browser sent caching headers, check to see if the files have been modified
		$latestMod = $this->getLatestMod($files);
		$dateFormat = 'D, d M Y H:i:s \G\M\T';
		header('Last-Modified: ' . gmdate($dateFormat));



		$ifModSince=getenv('HTTP_IF_MODIFIED_SINCE');
		if ($this->httpCache && !empty($ifModSince) && date_default_timezone_set('UTC') && $latestMod <= strtotime($ifModSince)){
			// Times match, files have not changed since their last request.
			header('HTTP/1.1 304 Not Modified');
			return true;
		}

		$thirteen = gmdate( 'D, d M Y H:i:s', $latestMod ) . ' GMT';
		if ($this->httpCache && !empty($_GET['maxmod']) && date_default_timezone_set('UTC')){
			// Since we have a timestamp that will change with the url, set an Expires header
			// far into the future. This will make it so that the browsers won't even check this
			// url to see if the files have changed, saving an http request.
			header('Expires: ' . gmdate($dateFormat, strtotime("+13 years")));
			header('X-Pass-Cache-Control: max-age=' . (13 * 365 * 24 * 60 * 60));
		} else if ($this->httpCache && !empty($_GET['checksum'])){
			// Alternate form of versioning the url
			header('Expires: ' . gmdate($dateFormat, strtotime("+13 years")));
			header('X-Pass-Cache-Control: max-age=' . (13 * 365 * 24 * 60 * 60));
		}

		$this->setContentType();

		$out = ''; $fileCount = 0;
		$stime = microtime(true);
		foreach($files as $file){
			if (!is_readable($file)){
				continue;
			}
			$fileCount++;

			$rawData = file_get_contents($file);

			// macbre: remove BOM
			$rawData = str_replace("\xEF\xBB\xBF", '', $rawData);

			if ($this->minify){
				switch ($this->fileType){
				  case 'css': $data = $this->minifyCssData($rawData); break;
				  case 'js': $data = $this->minifyJSFile($file); break;
				  case 'html': $data = $this->minifyHtmlData($rawData); break;
				  default: $data = $rawData;
				}
			} else {
				$data = $rawData;
			}

			$this->bytesIn += strlen($rawData);
			$this->bytesOut += strlen($data);

      			$out .= $this->comment(basename($file)) . $data;
          	}

		if (empty($out)){
			return false;
		} else {
			if ($this->compress){
				ob_start("ob_gzhandler");
			}
			$time = round(microtime(true) - $stime, 1);
			$shaved = $this->bytesIn - $this->bytesOut;
			$pct = round(($shaved / $this->bytesIn) * 100,2);
			return $this->comment("$fileCount files in $time seconds, shaved $shaved off {$this->bytesIn}, $pct%") . $out;
		}
	}


	/*
	* Send out Content-Type headers depending on the file type
	*/
	public function setContentType() {
		switch($this->fileType){
		  case 'js': header('Content-type: text/javascript'); break;
		  case 'css': header('Content-type: text/css'); break;
		  case 'html': break; // Apache does html by default
		}
	}

}
