<?php
/**
 * WikiaMobile skin entry point
 *
 * @author Jakub Olek <jakubolek(at)wikia-inc.com>
 * @authore Federico "Lox" Lucignano <federico(at)wikia-inc.com>
 */
class WikiaMobileService extends WikiaService {
	//AppCache will be disabled for the first several releases
	//const CACHE_MANIFEST_PATH = 'wikia.php?controller=WikiaMobileAppCacheController&method=serveManifest&format=html';
	const LYRICSWIKI_ID = 43339;

	static protected $initialized = false;

	function __construct(){
		parent::__construct();

		if ( !self::$initialized ) {
			//singleton
			F::setInstance( __CLASS__, $this );
			self::$initialized = true;
		}
	}

	function init(){
		$this->wf->LoadExtensionMessages( 'WikiaMobile' );
		F::build('JSMessages')->enqueuePackage('WkMbl', JSMessages::INLINE);
	}

	public function index() {
		$this->wf->profileIn( __METHOD__ );
		$skin = $this->wg->user->getSkin();
		$jsHeadPackages = array( 'wikiamobile_js_head' );
		$jsBodyPackages = array( 'wikiamobile_js_body' );
		$scssPackages = array( 'wikiamobile_scss' );
		$jsBodyFiles = '';
		$jsHeadFiles = '';
		$cssLinks = '';
		$styles = $skin->getStyles();
		$scripts = $skin->getScripts();
		$assetsManager = F::build( 'AssetsManager', array(), 'getInstance' );
		$templateObject = $this->app->getSkinTemplateObj();

		//let extensions manipulate the asset packages (e.g. ArticleComments,
		//this is done to cut down the number or requests)
		$this->app->runHook(
				'WikiaMobileAssetsPackages',
				array(
						&$jsHeadPackages,
						&$jsBodyPackages,
						&$scssPackages
				)
		);

		//force main SCSS as first to make overriding it possible
		foreach ( $assetsManager->getURL( $scssPackages ) as $s ) {
			//packages/assets are enqueued via an hook, let's make sure we should actually let them through
			if ( $assetsManager->checkAssetUrlForSkin( $s, $skin ) ) {
				//W3C standard says type attribute and quotes (for single non-URI values) not needed, let's save on output size
				$cssLinks .= "<link rel=stylesheet href=\"" . $s . "\"/>";
			}
		}

		foreach ( $styles as $s ) {
			//safe URL's as getStyles performs all the required checks
			//W3C standard says type attribute and quotes (for single non-URI values) not needed, let's save on output size
			$cssLinks .= "<link rel=stylesheet href=\"{$s['url']}\"/>";//this is a strict skin, getStyles returns only elements with a set URL
		}

		foreach ( $assetsManager->getURL( $jsHeadPackages ) as $s ) {
			if ( $assetsManager->checkAssetUrlForSkin( $s, $skin ) ) {
				//HTML5 standard, no type attribute required == smaller output
				$jsHeadFiles .= "<script src=\"{$s}\"></script>";
			}
		}

		foreach ( $assetsManager->getURL( $jsBodyPackages ) as $s ) {
			//packages/assets are enqueued via an hook, let's make sure we should actually let them through
			if ( $assetsManager->checkAssetUrlForSkin( $s, $skin ) ) {
				//HTML5 standard, no type attribute required == smaller output
				$jsBodyFiles .= "<script src=\"{$s}\"></script>";
			}
		}

		foreach ( $scripts as $s ) {
			//safe URL's as getScripts performs all the required checks
			//HTML5 standard, no type attribute required == smaller output
			$jsBodyFiles .= "<script src=\"{$s['url']}\"></script>";
		}

		//Bottom Scripts
		//do not run this hook, all the functionalities hooking in this don't take into account the pecularity of the mobile skin
		//$this->wf->RunHooks( 'SkinAfterBottomScripts', array ( $this->wg->User->getSkin(), &$bottomscripts ) );


		//global variables
		//from Output class
		//and from ResourceLoaderStartUpModule
		$res = new ResourceVariablesGetter();
		$vars = array_diff_key(
					//I found that this array merge is the fastest
					$this->wg->Out->getJSVars() + $res->get(),
					array_flip( $this->wg->WikiaMobileExcludeJSGlobals )
		);

		//AppCache will be disabled for the first several releases
		//$this->appCacheManifestPath = ( $this->wg->DevelEnvironment && !$this->wg->Request->getBool( 'appcache' ) ) ? null : self::CACHE_MANIFEST_PATH . "&{$this->wg->StyleVersion}";
		$this->mimeType = $templateObject->get( 'mimetype' );
		$this->charSet = $templateObject->get( 'charset' );
		$this->showAllowRobotsMetaTag = !$this->wg->DevelEnvironment;
		$this->topScripts = $skin->getTopScripts();
		$this->globalVariablesScript = WikiaSkin::makeInlineVariablesScript( $vars );
		$this->bodyClasses = array( 'wkMobile', $templateObject->get( 'pageclass' ) );
		$this->pageTitle = $this->wg->Out->getHTMLTitle();
		$this->cssLinks = $cssLinks;
		$this->headLinks = $this->wg->Out->getHeadLinks();
		$this->headItems = $skin->getHeadItems();
		$this->jsHeadFiles = $jsHeadFiles;
		$this->languageCode = $templateObject->get( 'lang' );
		$this->languageDirection = $templateObject->get( 'dir' );
		$this->wikiaNavigation = $this->app->renderView( 'WikiaMobileNavigationService', 'index' );
		$this->advert = $this->app->renderView( 'WikiaMobileAdService', 'index' );
		$this->pageContent = $this->app->renderView( 'WikiaMobileBodyService', 'index', array(
			'bodyText' => $templateObject->get( 'bodytext' ),
			'categoryLinks' => $templateObject->get( 'catlinks')
		) );
		$this->wikiaFooter = $this->app->renderView( 'WikiaMobileFooterService', 'index' );
		$this->jsBodyFiles = $jsBodyFiles;

		//tracking
		$trackingCode = '';

		if(!in_array( $this->wg->request->getVal( 'action' ), array( 'edit', 'submit' ) ) ) {
			$trackingCode .= AnalyticsEngine::track(
					'QuantServe',
					AnalyticsEngine::EVENT_PAGEVIEW,
					array(),
					array( 'extraLabels'=> array( 'mobilebrowser' ) )
				) .
				AnalyticsEngine::track(
					'Comscore',
					AnalyticsEngine::EVENT_PAGEVIEW
				);
		}

		//Stats for Gracenote reporting
		if ( $this->wg->cityId == self::LYRICSWIKI_ID ){
			$trackingCode .= AnalyticsEngine::track('GA_Urchin', 'lyrics');
		}

		$trackingCode .= AnalyticsEngine::track( 'GA_Urchin', AnalyticsEngine::EVENT_PAGEVIEW ).
			AnalyticsEngine::track( 'GA_Urchin', 'onewiki', array( $this->wg->cityId ) ).
			AnalyticsEngine::track( 'GA_Urchin', 'pagetime', array( 'wikiamobile' ) ).
			AnalyticsEngine::track('GA_Urchin', 'varnish-stat').
			AnalyticsEngine::track( 'GAS', 'usertiming' );

		$this->response->setVal( 'trackingCode', $trackingCode );
		$this->wf->profileOut( __METHOD__ );
	}
}