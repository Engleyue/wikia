<?php
class AdSS_Controller extends SpecialPage {

	function __construct() {
		parent::__construct("AdSS");
	}

	function execute( $subpage ) {
		global $wgRequest, $wgUser, $wgOut;

		wfLoadExtensionMessages( 'AdSS' );
		$this->setHeaders();
		$wgOut->setPageTitle( wfMsg( 'adss-sponsor-links' ) );

		if( $subpage == 'admin' ) {
			$adminController = new AdSS_AdminController();
			$adminController->execute( $subpage );
			return;
		}

		$adForm = new AdSS_AdForm();
		if ( $wgRequest->wasPosted() && AdSS_Util::matchToken( $wgRequest->getText( 'wpToken' ) ) ) {
			$submitType = $wgRequest->getText( 'wpSubmit' );
			$adForm->loadFromRequest( $wgRequest );
			if( $wgUser->isAllowed( 'adss-admin' ) ) {
				$this->saveSpecial( $adForm );
			} else {
				$this->save( $adForm );
			}
		} elseif( $subpage == 'paypal/return' ) {
			$this->processPayPalReturn( $wgRequest->getText( "token" ) );
		} elseif( $subpage == 'paypal/cancel' ) {
			$wgOut->addHTML( wfMsgWikiHtml( 'adss-paypal-error' ) );
		} else {
			$page = $wgRequest->getText( 'page' );
			if( !empty( $page ) ) {
				$title = Title::newFromText( $page );
				if( $title && $title->exists() ) {
					$adForm->set( 'wpPage', $page );
				}
			}
			$adForm->set( 'wpType', 'site' );
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/view")' );
			$this->displayForm( $adForm );
		}
	}

	function displayForm( $adForm ) {
		global $wgOut, $wgAdSS_templatesDir, $wgUser;

		$sitePricing = AdSS_Util::getSitePricing();

		$adsCount = count( AdSS_Publisher::getSiteAds() );
		if( $adsCount > 0 ) {
			$currentShare = min( $sitePricing['max-share'], 1/$adsCount );
		} else {
			$currentShare = $sitePricing['max-share'];
		}

		$tmpl = new EasyTemplate( $wgAdSS_templatesDir );
		$tmpl->set( 'action', $this->getTitle()->getLocalUrl() );
		if( $wgUser->isAllowed( 'adss-admin' ) ) {
			$tmpl->set( 'submit', 'Add this ad NOW' );
		} else {
			$tmpl->set( 'submit', wfMsgHtml( 'adss-button-pay-paypal' ) );
		}
		$tmpl->set( 'token', AdSS_Util::getToken() );
		$tmpl->set( 'sitePricing', $sitePricing );
		$tmpl->set( 'pagePricing', AdSS_Util::getPagePricing( Title::newFromText( $adForm->get( 'wpPage' ) ) ) );
		$tmpl->set( 'adForm', $adForm );
		$tmpl->set( 'ad', AdSS_Ad::newFromForm( $adForm ) );
		$tmpl->set( 'currentShare', intval( $currentShare * 100 ) );

		$wgOut->addHTML( $tmpl->render( 'adForm' ) );
		$wgOut->addStyle( wfGetSassUrl( 'extensions/wikia/AdSS/css/adform.scss' ) );
	}

	function save( $adForm ) {
		global $wgOut, $wgPayPalUrl;

		if( !$adForm->isValid() ) {
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/view/errors")' );
			$this->displayForm( $adForm );
			return;
		}

		//TODO: authenticate as an existing advertiser (using password) or register new account
		// (for now, authenticate via PayPal)
		$selfUrl = $this->getTitle()->getFullURL();
		$returnUrl = $selfUrl . '/paypal/return';
		$cancelUrl = $selfUrl . '/paypal/cancel';
		$pp = new PaymentProcessor();
		if( $pp->fetchToken( $returnUrl, $cancelUrl ) ) {
			// redirect to PayPal
			$_SESSION['ecToken'] = $pp->getToken();
			$_SESSION['AdSS_adForm'] = $adForm;
			//TODO use http meta redirect so we can add GA tracking
			$wgOut->redirect( $wgPayPalUrl . $pp->getToken() );
		} else {
			// show error
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/paypal/redirect/error")' );
			$wgOut->addHTML( wfMsgWikiHtml( 'adss-paypal-error' ) );
		}
	}

	function saveSpecial( $adForm ) {
		global $wgOut;

		if( !$adForm->isValid() ) {
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/view/errors")' );
			$this->displayForm( $adForm );
			return;
		}

		$user = AdSS_User::newFromForm( $adForm );
		if( !$user->loadFromDB() ) {
			$user->save();
		}

		$ad = AdSS_Ad::newFromForm( $adForm );
		if( $ad->pageId > 0 ) {
			$ad->weight = 1;
		}
		$ad->expires = strtotime( "+1 month", time() ); 
		$ad->setUser( $user );

		$ad->save();
		AdSS_Util::flushCache( $ad->pageId, $ad->wikiId );

		$wgOut->addInlineScript( '$.tracker.byStr("adss/form/saveSpecial")' );
		$wgOut->addHTML( "Your ad has been added to the system." );
	}

	function processPayPalReturn( $token ) {
		global $wgAdSS_templatesDir, $wgOut, $wgAdSS_contactEmail;

		if( empty( $_SESSION['ecToken'] ) || ( $_SESSION['ecToken'] != $token ) ) {
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/paypal/return/error")' );
			$wgOut->addHTML( wfMsgWikiHtml( 'adss-token-error' ) );
			return;
		}
		unset( $_SESSION['ecToken'] );
		$adForm = $_SESSION['AdSS_adForm'];

		$pp_new = new PaymentProcessor( $token );

		$payerId = $pp_new->fetchPayerId();
		if( $payerId === false ) {
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/paypal/return/error")' );
			$wgOut->addHTML( wfMsgWikiHtml( 'adss-paypal-error' ) );
			return;
		}

		$baid = false;
		$pp_existing = PaymentProcessor::newFromPayerId( $payerId, $adForm->get( 'wpEmail' ) );
		if( $pp_existing ) {
			$user = AdSS_User::newFromId( $pp_existing->getUserId() );
			wfDebug( "AdSS: got existing user: {$user->toString()})\n" );
			$baid = $pp_existing->getBillingAgreement();
			if( $baid ) wfDebug( "AdSS: got existing BAID: $baid\n" );
		} else {
			$user = AdSS_User::newFromForm( $adForm );
			$user->save();
			wfDebug( "AdSS: created new user: {$user->toString()})\n" );
		}
		$pp_new->setUserId( $user->id );

		if( $baid === false ) {
			$baid = $pp_new->createBillingAgreement();
			wfDebug( "AdSS: created new BAID: $baid\n" );
		}
		if( $baid === false ) {
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/paypal/return/error")' );
			$wgOut->addHTML( wfMsgWikiHtml( 'adss-paypal-error' ) );
			return;
		}

		$ad = AdSS_Ad::newFromForm( $adForm );
		$ad->setUser( $user );
		$ad->save();
		if( $ad->id == 0 ) {
			$wgOut->addInlineScript( '$.tracker.byStr("adss/form/paypal/return/error")' );
			$wgOut->addHTML( wfMsgWikiHtml( 'adss-error' ) );
			return;
		}

		$wgOut->addInlineScript( '$.tracker.byStr("adss/form/paypal/return/ok")' );
		$wgOut->addHTML( wfMsgWikiHtml( 'adss-form-thanks' ) );

		if( !empty( $wgAdSS_contactEmail ) ) {
			$to = array();
			foreach( $wgAdSS_contactEmail as $a ) {
				$to[] = new MailAddress( $a );
			}
			//FIXME move it to a template
			$subject = '[AdSS] new ad pending approval';

			$body = "New ad has been just created and it's waiting your approval:\n";
			$body .= Title::makeTitle( NS_SPECIAL, "AdSS/admin" )->getFullURL();
			$body .= "\n\n";
			$body .= "Created by: {$ad->getUser()->toString()}\n";
			$body .= "Ad link text: {$ad->text}\n";
			$body .= "Ad URL: {$ad->url}\n";
			$body .= "Ad description: {$ad->desc}\n";

			UserMailer::send( $to, new MailAddress( 'adss@wikia-inc.com' ), $subject, $body );
		}
	}

}
