<?php
/**
 * OutboundScreen - redirects external links to special page
 *
 * @author Łukasz Garczewski (TOR) <tor@wikia-inc.com>
 * @author Adrian 'ADi' Wieczorek <adi(at)wikia.com>
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

$wgHooks['LinkerMakeExternalLink'][] = 'efOutboundScreen';
$wgAutoloadClasses['Outbound'] = dirname( __FILE__ ) . '/SpecialOutboundScreen_body.php';
$wgSpecialPages['Outbound'] = 'Outbound';
$wgExtensionMessagesFiles['Outbound'] = dirname(__FILE__) . '/OutboundScreen.i18n.php';

$wgOutboundScreenConfig = array(
	'redirectDelay' => !empty($wgOutboundScreenRedirectDelay) ? intval($wgOutboundScreenRedirectDelay) : 10,
	'anonsOnly' => true,
	'adLayoutMode' => !empty($wgOutboundScreenAdLayout) ? $wgOutboundScreenAdLayout : 'classic'
);

function efOutboundScreen ( $url, $text, $link, $attribs, $linktype, $linker ) {
	global $wgCityId, $wgUser, $wgOutboundScreenConfig;
	static $whiteList;

	// skip logic when in FCK
	global $wgWysiwygParserEnabled;
	if(!empty($wgWysiwygParserEnabled)) {
		return true;
	}

	if ( strpos( $url, 'http://' ) !== 0 ) {
		return true;
	}

	$loggedIn = $wgUser->isLoggedIn();

	if(($wgOutboundScreenConfig['anonsOnly'] == false) || (($wgOutboundScreenConfig['anonsOnly'] == true) && !$loggedIn)) {
		if(!is_array($whiteList)) {
			$whiteList = array();
			$whiteListContent = wfMsgExt('outbound-screen-whitelist', array( 'language' => 'en' ));
			if(!empty($whiteListContent)) {
				$lines = explode("\n", $whiteListContent);
				foreach($lines as $line) {
					if(strpos($line, '* ') === 0 ) {
						$whiteList[] = trim($line, '* ');
					}
				}
			}
			$wikiDomains = WikiFactory::getDomains($wgCityId);
			$whiteList = array_merge($wikiDomains, $whiteList);
		}

		$isWhitelisted = false;
		foreach($whiteList as $whiteListedUrl) {
			$matches = null;
			preg_match('/'.$whiteListedUrl.'/i', $url, $matches);
			if(isset($matches[0])) {
				$isWhitelisted = true;
				break;
			}
		}

		if(!$isWhitelisted) {
			// make the actual link
			$special = Title::newFromText( 'Special:Outbound' );
			if($special instanceof Title) {
				// RT #19167
				$link = Xml::tags('a', array(
					'class' => 'external',
					'rel' => 'nofollow',
					'title' => $url,
					'href' => $special->getFullURL('u=' . urlencode($url)),
				), $text);

				return false;
			}
		}
	}

	return true;
}
