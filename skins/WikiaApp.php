<?php
/**
 * Mobile Skin for webkit mobile
 *
 * @author Hyun Lim
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once( dirname(__FILE__) . '/MonoBook.php' );

global $wgHooks, $wgEnableArticleCommentsExt, $wgEnableFacebookConnectExt,
	$wgEnableFacebookConnectPushing, $wgEnableMWSuggest, $wgAjaxWatch;

$wgEnableArticleCommentsExt = false;
$wgEnableFacebookConnectExt = false;
$wgEnableFacebookConnectPushing = false;
$wgEnableMWSuggest = false;
$wgAjaxWatch = false;

//$wgHooks['MakeGlobalVariablesScript'][] = 'SkinWikiaApp::onMakeGlobalVariablesScript';
//$wgHooks['SkinAfterContent'][] = 'SkinWikiaApp::onSkinAfterContent';
$wgHooks['SkinTemplateOutputPageBeforeExec'][] = 'SkinWikiaApp::onSkinTemplateOutputPageBeforeExec';
$wgHooks['SkinGetHeadScripts'][] = 'SkinWikiaApp::onSkinGetHeadScripts';

/**
 * @todo document
 * @ingroup Skins
 */
class SkinWikiaApp extends SkinTemplate {
	const COOKIE_NAME = 'mobileapp';
	const COOKIE_DURATION = 86400;/*24h*/
	
	function __construct() {
		parent::__construct();
		$this->mRenderColumnOne = false;
		$this->useHeadElement = true;
	}
	
	function initPage( OutputPage $out ) {
		global $wgHooks, $wgUseSiteCss, $wgRequest, $wgCookiePrefix;
		
		//this will force the skin after the first visit, only for selected mobile platforms
		if( empty( $_COOKIE[ $wgCookiePrefix . self::COOKIE_NAME ] ) ) {	
			$mobServ = MobileService::getInstance();
			
			if ( $mobServ->isMobile() ) {
				$wgRequest->response()->setcookie( self::COOKIE_NAME, 1, time() + self::COOKIE_DURATION );
			}
		}
		
		SkinTemplate::initPage( $out );
		
		$this->skinname  = 'wikiaapp';
		$this->stylename = 'wikiaapp';
		$this->themename = 'wikiaapp';
		$this->template  = 'MonoBookTemplate';
		
		$wgUseSiteCss = false;
		
		$out->addMeta("viewport", "width=320");
	}
	
	public static function onSkinTemplateOutputPageBeforeExec( &$obj, &$tpl ){
		$tpl->set('skipColumnOne', true);
		$tpl->set('skipFooter', true);
		return true;
	}
	
	function setupSkinUserCss( OutputPage $out ){
		foreach ( AssetsManager::getInstance()->getGroupCommonURL( 'wikiaapp_css' ) as $src ) {
			$out->addStyle( $src );
		}
	}
	
	public function onSkinGetHeadScripts(&$scripts) {
		global $wgJsMimeType;
		
		foreach ( AssetsManager::getInstance()->getGroupCommonURL( 'wikiaapp_js' ) as $src ) {
			$scripts .= "\n<script type=\"$wgJsMimeType\" src=\"{$src}\"></script>";
		}
		
		return true;
	}

	function printTopHtml() {}
	
	public static function onMakeGlobalVariablesScript( $vars ) {
		global $wgContLang;
		
		$vars['CategoryNamespaceMessage'] = $wgContLang->getNsText(NS_CATEGORY);
		$vars['SpecialNamespaceMessage'] = $wgContLang->getNsText(NS_SPECIAL);
		
		return true;
	}
	
	protected function afterContentHook () {
		global $wgCityId, $wgRightsUrl;
		
		$data = '';
		
		// load Google Analytics code
		$data .= AnalyticsEngine::track('GA_Urchin', AnalyticsEngine::EVENT_PAGEVIEW);

		// onewiki GA
		$data .= AnalyticsEngine::track('GA_Urchin', 'onewiki', array($wgCityId));
		
		return $data;
	}
	
	/**
	 * empty stub for compatibility with MonoBook.php wikiaBox()
	 */
	function wikiaBox() {
		return;
	}

}