<?php

class OasisModule extends Module {

	private static $extraBodyClasses = array();

	private $printStyles;

	/**
	 * Add extra CSS classes to <body> tag
	 * @author: Inez Korczyński
	 */
	public static function addBodyClass($className) {
		self::$extraBodyClasses[] = $className;
	}

	// template vars
	var $anonSiteCss;
	var $body;
	var $bodyClasses;
	var $csslinks;
	var $globalVariablesScript;

	var $googleAnalytics;
	var $headlinks;
	var $jsAtBottom;
	var $pagecss;
	var $printableCss;
	var $comScore;
	var $quantServe;

	// skin/template vars
	var $pagetitle;
	var $displaytitle;
	var $mimetype;
	var $charset;
	var $body_ondblclick;
	var $dir;
	var $lang;
	var $pageclass;
	var $skinnameclass;
	var $bottomscripts;

	// global vars
	var $wgEnableOpenXSPC;
	var $wgEnableCorporatePageExt;

	public function executeIndex() {
		global $wgOut, $wgUser, $wgTitle, $wgRequest, $wgCityId, $wgAllInOne, $wgContLang, $wgJsMimeType;

		$wikiWelcome = $wgRequest->getVal('wiki-welcome');
		if(!empty($wikiWelcome)) {
			global $wgExtensionsPath;
			$wgOut->addStyle(wfGetSassUrl('extensions/wikia/CreateNewWiki/css/WikiWelcome.scss'));
			$wgOut->addScript('<script src="'.$wgExtensionsPath.'/wikia/CreateNewWiki/js/WikiWelcome.js"></script>');
		}

		$allInOne = $wgRequest->getBool('allinone', $wgAllInOne);

		$this->body = wfRenderModule('Body');

		// generate list of CSS classes for <body> tag
		$this->bodyClasses = array('mediawiki', $this->dir, $this->pageclass);
		$this->bodyClasses = array_merge($this->bodyClasses, self::$extraBodyClasses);
		$this->bodyClasses[] = $this->skinnameclass;

		if(Wikia::isMainPage()) {
			$this->bodyClasses[] = 'mainpage';
		}

		// add skin theme name
		$skin = $wgUser->getSkin();
		if(!empty($skin->themename)) {
			$this->bodyClasses[] = "oasis-{$skin->themename}";
		}

		$this->setupStaticChute();

		// Add the wiki and user-specific overrides last.  This is a special case in Oasis because the modules run
		// later than normal extensions and therefore add themselves later than the wiki/user specific CSS is
		// normally added.
		// See Skin::setupUserCss()
		global $wgOasisLastCssScripts;
		if(!empty($wgOasisLastCssScripts)){
			foreach($wgOasisLastCssScripts as $cssScript){
				$wgOut->addStyle( $cssScript );
			}
		}

		// If the user is not logged in, we can combine the Wikia.css and the "-" MediaWiki CSS files.
		// For logged in users, Wikia.css and "-" files are already be in the wgOut->styles array.
		if($wgUser->isLoggedIn()){
			$this->anonSiteCss = "";
		} else {
			$this->anonSiteCss = WikiaAssets::GetSiteCSS($skin->themename, $wgContLang->isRTL(), $allInOne); // Wikia.css, "-"
		}

		// Remove the media="print CSS from the normal array and add it to another so that it can be loaded asynchronously at the bottom of the page.
		$this->printStyles = array();
		$tmpOut = new OutputPage();
		$tmpOut->styles = $wgOut->styles;
		foreach($tmpOut->styles as $style => $options) {
			if (isset($options['media']) && $options['media'] == 'print') {
				unset($tmpOut->styles[$style]);
				$this->printStyles[$style] = $options;
			}
		}

		// render CSS <link> tags
		$this->csslinks = $tmpOut->buildCssLinks();

		$this->headlinks = $wgOut->getHeadLinks();

		$this->pagetitle = htmlspecialchars( $this->pagetitle );
		$this->displaytitle = htmlspecialchars( $this->displaytitle );
		$this->mimetype = htmlspecialchars( $this->mimetype );
		$this->charset = htmlspecialchars( $this->charset );

		$this->globalVariablesScript = Skin::makeGlobalVariablesScript(Module::getSkinTemplateObj()->data);

		// printable CSS (to be added at the bottom of the page)
		// FIXME: move to renderPrintCSS() method
		$StaticChute = new StaticChute('css');
		$StaticChute->useLocalChuteUrl();
		$oasisPrintStyles = $StaticChute->config['oasis_css_print'];
		foreach($oasisPrintStyles as $cssUrl){
			$this->printStyles[$cssUrl] = array("media" => "print");
		}

		// If this is an anon article view, use the combined version of the print files.
		if($allInOne){
			// Create the combined URL.
			global $parserMemc, $wgStyleVersion;
			$cb = $parserMemc->get(wfMemcKey('wgMWrevId'));

			global $wgDevelEnvironment;
			if(empty($wgDevelEnvironment)){
				$prefix = "__wikia_combined/";
			} else {
				global $wgWikiaCombinedPrefix;
				$prefix = $wgWikiaCombinedPrefix;
			}

			// Completely replace the print styles with the combined version.
			$this->printStyles = array(
				"/{$prefix}cb={$cb}{$wgStyleVersion}&type=PrintCSS&isOasis=true" => array("media" => "print")
			);
		}

		$this->printableCss = $this->renderPrintCSS(); // The HTML for the CSS links (whether async or not).

		// setup loading of JS/CSS using WSL (WikiaScriptLoader)
		$this->loadJs();

		// FIXME: create separate module for stats stuff?
		// load Google Analytics code
		$this->googleAnalytics = AnalyticsEngine::track('GA_Urchin', AnalyticsEngine::EVENT_PAGEVIEW);

		// onewiki GA
		$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'onewiki', array($wgCityId));

		// track page load time
		$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'pagetime', array('oasis'));

		// track browser height
		$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'browser-height');

		// record which varnish this page was served by
		$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'varnish-stat');

		$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'noads');

		$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'abtest');

		// Add important Gracenote analytics for reporting needed for licensing on LyricWiki.
		if (43339 == $wgCityId){
			$this->googleAnalytics .= AnalyticsEngine::track('GA_Urchin', 'lyrics');
		}

		// macbre: RT #25697 - hide Comscore & QuantServe tags on edit pages

		if(!in_array($wgRequest->getVal('action'), array('edit', 'submit'))) {
			$this->comScore = AnalyticsEngine::track('Comscore', AnalyticsEngine::EVENT_PAGEVIEW);
			$this->quantServe = AnalyticsEngine::track('QuantServe', AnalyticsEngine::EVENT_PAGEVIEW);
		}

	} // end executeIndex()

	/**
	 * @author Sean Colombo
	 */
	private function renderPrintCSS() {
		global $wgRequest;
		wfProfileIn( __METHOD__ );

		if ($wgRequest->getVal('printable')) {
			// render <link> tags for print preview
			$tmpOut = new OutputPage();
			$tmpOut->styles = $this->printStyles;
			$ret = $tmpOut->buildCssLinks();
		} else {
			// async download
			$cssReferences = Wikia::json_encode(array_keys($this->printStyles));

			$ret = <<<EOF
		<script type="text/javascript">/*<![CDATA[*/
			(function(){
				var cssReferences = $cssReferences;
				var len = cssReferences.length;
				for(var i=0; i<len; i++)
					setTimeout("wsl.loadCSS.call(wsl, '" + cssReferences[i] + "', 'print')", 100);
			})();
		/*]]>*/</script>
EOF;
		}

		wfProfileOut( __METHOD__ );
		return $ret;
	} // end delayedPrintCSSdownload()

	private function setupStaticChute() {
		global $wgUser, $wgOut, $wgJsMimeType, $wgRequest, $wgAllInOne, $wgTitle;
		wfProfileIn(__METHOD__);

		$skin = $wgUser->getSkin();
		$allInOne = $wgRequest->getBool('allinone', $wgAllInOne);

		// Merged JS files via StaticChute
		// get the right package from StaticChute
		$staticChute = new StaticChute('js');
		$staticChute->useLocalChuteUrl();

		// If we decide to use CoreJS, then that will replace the staticChute call as well as the call to "-".
		$isAnonArticleView = false;

		$packagePrefix = "oasis_";
		if($wgUser->isLoggedIn()) {
			$package = $packagePrefix.'loggedin_js';
		} else {
			// list of namespaces and actions on which we should load package with YUI
			$ns = array(NS_SPECIAL);
			$actions = array('edit', 'preview', 'submit');

			// add blog namespaces
			global $wgEnableBlogArticles;
			if(!empty($wgEnableBlogArticles)) {
				$ns = array_merge($ns, array(NS_BLOG_ARTICLE, NS_BLOG_ARTICLE_TALK, NS_BLOG_LISTING, NS_BLOG_LISTING_TALK));
			}

			if(in_array($wgTitle->getNamespace(), $ns) || in_array($wgRequest->getVal('action', 'view'), $actions)) {
				// edit mode & special/blog pages (package with YUI)
				$package = $packagePrefix.'anon_everything_else_js';
			} else {
				// view mode (package without YUI)
				$package = $packagePrefix.'anon_article_js';

				// Use CoreJS via __wikia_combined instead of StaticChute and "-".
				$isAnonArticleView = true;
			}
		}

		// If this is an anon on an article-page, we can combine two of the files into one.
		if($isAnonArticleView && $allInOne){
			global $parserMemc, $wgStyleVersion;
			$cb = $parserMemc->get(wfMemcKey('wgMWrevId'));

			global $wgDevelEnvironment;
			if(empty($wgDevelEnvironment)){
				$prefix = "__wikia_combined/";
			} else {
				global $wgWikiaCombinedPrefix;
				$prefix = $wgWikiaCombinedPrefix;
			}
			// This would load our JS too late.
			// $wgOut->addScript("<script type=\"$wgJsMimeType\" src=\"/{$prefix}cb={$cb}{$wgStyleVersion}&type=CoreJS\"><!-- combined anon site js --></script>");

			// Replace the normal StaticChute with the combined call.
			$this->jsFiles = "<script type=\"$wgJsMimeType\" src=\"/{$prefix}cb={$cb}{$wgStyleVersion}&type=CoreJS&isOasis=true\"><!-- combined anon Core JS (StaticChute and '-') --></script>";
		} else {
			// If we use StaticChute right on the page (rather than loaded asynchronously), we'll use this var.
			$this->jsFiles = $staticChute->getChuteHtmlForPackage($package);

			// add site JS
			// copied from Skin::getHeadScripts
			global $wgUseSiteJs;
			if (!empty($wgUseSiteJs)) {
				$jsCache = $wgUser->isLoggedIn() ? '&smaxage=0' : '';
				$wgOut->addScript("<script type=\"$wgJsMimeType\" src=\"".
						htmlspecialchars(Skin::makeUrl('-',
								"action=raw$jsCache&gen=js&useskin=" .
								urlencode( $skin->getSkinName() ) ) ) .
						"\"></script>");
			}
		}

		wfProfileOut(__METHOD__);
	}

	// TODO: implement as a separate module?
	private function loadJs() {
		global $wgTitle, $wgOut, $wgJsMimeType, $wgUser;
		wfProfileIn(__METHOD__);

		// decide where JS should be placed (only add JS at the top for special and edit pages)
		if ($wgTitle->getNamespace() == NS_SPECIAL || BodyModule::isEditPage()) {
			$this->jsAtBottom = false;
		}
		else {
			$this->jsAtBottom = true;
		}

		// load WikiaScriptLoader
		// macbre: this is minified version of /skins/wikia/js/WikiaScriptLoader.js using Google Closure
		$this->wikiaScriptLoader = <<<JS
var WikiaScriptLoader=function(){var b=navigator.userAgent.toLowerCase();this.useDOMInjection=b.indexOf("opera")!=-1||b.indexOf("firefox/3.")!=-1;this.isIE=b.indexOf("opera")==-1&&b.indexOf("msie")!=-1;this.headNode=document.getElementsByTagName("HEAD")[0]}; WikiaScriptLoader.prototype={loadScript:function(b,c){this.useDOMInjection?this.loadScriptDOMInjection(b,c):this.loadScriptDocumentWrite(b,c)},loadScriptDOMInjection:function(b,c){var a=document.createElement("script");a.type="text/javascript";a.src=b;var d=function(){a.onloadDone=true;typeof c=="function"&&c()};a.onloadDone=false;a.onload=d;a.onreadystatechange=function(){a.readyState=="loaded"&&!a.onloadDone&&d()};this.headNode.appendChild(a)},loadScriptDocumentWrite:function(b,c){document.write('<script src="'+ b+'" type="text/javascript"><\/script>');var a=function(){typeof c=="function"&&c()};typeof c=="function"&&this.addHandler(window,"load",a)},loadScriptAjax:function(b,c){var a=this,d=this.getXHRObject();d.onreadystatechange=function(){if(d.readyState==4){var e=d.responseText;if(a.isIE)eval(e);else{var f=document.createElement("script");f.type="text/javascript";f.text=e;a.headNode.appendChild(f)}typeof c=="function"&&c()}};d.open("GET",b,true);d.send("")},loadCSS:function(b,c){var a=document.createElement("link"); a.rel="stylesheet";a.type="text/css";a.media=c||"";a.href=b;this.headNode.appendChild(a)},addHandler:function(b,c,a){if(window.addEventListener)window.addEventListener(c,a,false);else window.attachEvent&&window.attachEvent("on"+c,a)},getXHRObject:function(){var b=false;try{b=new XMLHttpRequest}catch(c){for(var a=["Msxml2.XMLHTTP.6.0","Msxml2.XMLHTTP.3.0","Msxml2.XMLHTTP","Microsoft.XMLHTTP"],d=a.length,e=0;e<d;e++){try{b=new ActiveXObject(a[e])}catch(f){continue}break}}return b}};window.wsl=new WikiaScriptLoader;
JS;

		$this->wikiaScriptLoader = "\n\n\t<script type=\"text/javascript\">/*<![CDATA[*/{$this->wikiaScriptLoader}/*]]>*/</script>";

		wfProfileIn(__METHOD__ . '::regexp');

		// get JS files from <script> tags returned by StaticChute
		// TODO: get StaticChute package (and other JS files to be loaded) here
		$jsReferences = array();
		preg_match_all("/src=\"([^\"]+)/", $this->jsFiles, $matches, PREG_SET_ORDER);
		foreach($matches as $scriptSrc) {
			$jsReferences[] = str_replace('&amp;', '&', $scriptSrc[1]);
		}

		// move JS files added by extensions to list of files to be loaded using WSL
		$headscripts = $wgOut->getScript();

		preg_match_all("#<script type=\"{$wgJsMimeType}\" src=\"([^\"]+)\"></script>#", $headscripts, $matches, PREG_SET_ORDER);
		foreach($matches as $scriptSrc) {
			$jsReferences[] = str_replace('&amp;', '&', $scriptSrc[1]);
			$headscripts = str_replace($scriptSrc[0], '', $headscripts);
		}

		// move <link> tags from headscripts to csslinks (fix SMW issue)
		preg_match_all("#<link ([^>]+)>#", $headscripts, $matches, PREG_SET_ORDER);
		foreach($matches as $linkTag) {
			$this->csslinks .= "\n\t" . trim($linkTag[0]);
			$headscripts = str_replace($linkTag[0], '', $headscripts);
		}

		wfProfileOut(__METHOD__ . '::regexp');

		// add user JS (if User:XXX/wikia.js page exists)
		// copied from Skin::getHeadScripts
		if($wgUser->isLoggedIn()){
			wfProfileIn(__METHOD__ . '::checkForEmptyUserJS');

			$userJS = $wgUser->getUserPage()->getPrefixedText().'/wikia.js';
			$userJStitle = Title::newFromText($userJS);

			if ($userJStitle->exists()) {
				global $wgSquidMaxage;
				$siteargs = array(
					'action' => 'raw',
					'maxage' => $wgSquidMaxage,
				);

				$jsReferences[] = Skin::makeUrl($userJS, wfArrayToCGI($siteargs));
			}

			wfProfileOut(__METHOD__ . '::checkForEmptyUserJS');
		}

		// generate code to load JS files
		$jsReferences = Wikia::json_encode($jsReferences);
		$jsLoader = <<<JS
	<script type="text/javascript">/*<![CDATA[*/
		(function(){
			var jsReferences = $jsReferences;
			var len = jsReferences.length;
			for(var i=0; i<len; i++)
				wsl.loadScript(jsReferences[i]);
		})();
	/*]]>*/</script>
JS;

		// use loader script instead of separate JS files
		$this->jsFiles = $jsLoader;

		// add inline scripts
		$this->jsFiles .= trim($headscripts);

		wfProfileOut(__METHOD__);
	}

}
