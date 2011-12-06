<?php
/**
 * WikiaMobile page
 *
 * @author Jakub Olek <bukaj.kelo(at)gmail.com>
 * @authore Federico "Lox" Lucignano <federico(at)wikia-inc.com>
 */
class WikiaMobileService extends WikiaService {
	const CACHE_MANIFEST_PATH = 'wikia.php?controller=WikiaMobileAppCacheController&method=serveManifest&format=html';

	static private $initialized = false;

	private $templateObject;

	function init(){
		if ( !self::$initialized ) {
			F::setInstance( __CLASS__, $this );
			self::$initialized = true;
			$this->wf->LoadExtensionMessages( 'WikiaMobile' );
		}
	}

	/**
	 * @brief Sets the template object for internal use
	 *
	 * @requestParam QuickTeamplate $templateObject
	 */
	public function setTemplateObject(){
		$this->templateObject = $this->getVal( 'templateObject') ;
	}

	public function index() {
		$bottomscripts = '';
		$jsBodyFiles = '';
		$jsHeadFiles = '';
		$cssFiles = '';
		$tmpOut = new OutputPage();

		$tmpOut->styles = array(  ) + $this->wg->Out->styles;

		foreach( $tmpOut->styles as $style => $options ) {
			if ( isset( $options['media'] ) || strstr( $style, 'shared' ) || strstr( $style, 'index' ) ) {
				unset( $tmpOut->styles[$style] );
			}
		}



		//Bottom Scripts
		$this->wf->RunHooks( 'SkinAfterBottomScripts', array ( $this->wg->User->getSkin(), &$bottomscripts ) );

		//force skin main CSS file to be the first so it will be always overridden by other files
		$cssFiles .= "<link rel=\"stylesheet\" href=\"" . AssetsManager::getInstance()->getSassCommonURL( 'extensions/wikia/WikiaMobile/css/WikiaMobile.scss' ) . "\"/>";
		$cssFiles .= $tmpOut->buildCssLinks();

		$srcs = AssetsManager::getInstance()->getGroupCommonURL('wikiamobile_js_head');

		//TODO: add scripts from $wgOut as needed
		foreach ( $srcs as $src ) {
			$jsHeadFiles .= "<script type=\"{$this->wg->JsMimeType}\" src=\"$src\"></script>\n";
		}

		//Scripts are splitted in batches to not cross the 25Kb un-gizipped cap for caching on mobile browsers
		$srcs = AssetsManager::getInstance()->getGroupCommonURL( 'wikiamobile_js_body' );

		//TODO: add scripts from $wgOut as needed
		foreach ( $srcs as $src ) {
			$jsBodyFiles .= "<script type=\"{$this->wg->JsMimeType}\" src=\"$src\"></script>\n";
		}

		//AppCache will be disabled for the first several releases
		//$this->appCacheManifestPath = ( $this->wg->DevelEnvironment && !$this->wg->Request->getBool( 'appcache' ) ) ? null : self::CACHE_MANIFEST_PATH . "&{$this->wg->StyleVersion}";
		$this->mimeType = $this->templateObject->data['mimetype'];
		$this->charSet = $this->templateObject->data['charset'];
		$this->showAllowRobotsMetaTag = !$this->wg->DevelEnvironment;
		$this->globalVariablesScript = Skin::makeGlobalVariablesScript( $this->templateObject->data['skinname'] );
		$this->pageTitle = $this->wg->Out->getPageTitle();
		$this->cssLinks = $cssFiles;
		$this->headLinks = $this->wg->Out->getHeadLinks();
		$this->jsHeadFiles = $jsHeadFiles;
		$this->languageCode = $this->templateObject->data['lang'];
		$this->languageDirection = $this->templateObject->data['dir'];
		$this->wikiaNavigation = $this->sendRequest( 'WikiaMobileNavigationService', 'index' )->toString();
		$this->advert = $this->sendRequest( 'WikiaMobileAdService', 'index' )->toString();
		$this->pageContent = $this->sendRequest( 'WikiaMobileBodyService', 'index', array(
			'bodyText' => $this->templateObject->data['bodytext'],
			'categoryLinks' => $this->templateObject->data['catlinks']
		) )->toString();
		$this->wikiaFooter = $this->sendRequest( 'WikiaMobileFooterService', 'index', array(
			'copyrightLink' => $this->templateObject->data['copyright']
		))->toString();
		$this->jsBodyFiles = $jsBodyFiles;
		$this->bottomscripts = $bottomscripts;

		//tracking
		$this->quantcastTracking = AnalyticsEngine::track(
			'QuantServe',
			AnalyticsEngine::EVENT_PAGEVIEW,
			array(),
			array( 'extraLabels'=> array( 'mobilebrowser' ) )
		);
		$this->comscoreTracking = AnalyticsEngine::track('Comscore', AnalyticsEngine::EVENT_PAGEVIEW);
		$this->gaTracking = AnalyticsEngine::track( 'GA_Urchin', AnalyticsEngine::EVENT_PAGEVIEW );
		$this->gaOneWikiTracking = AnalyticsEngine::track( 'GA_Urchin', 'onewiki', array( $this->wg->CityId ) );
	}
}