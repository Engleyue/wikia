<?php

class AccountCreationTrackerController extends WikiaSpecialPageController {
	const TRACKING_COOKIE_NAME = 'ACT';
	const TRACKING_COOKIE_TTL = 365; // in days
	const CLOSE_WIKI_REASON = 'wiki closed via AccountTracker';

	protected $tracker = null;

	public function __construct() {
		$this->tracker = F::build('AccountCreationTracker');

		parent::__construct( 'Tracker', 'Tracker', false );
	}

	public function index() {
		if( !$this->wg->User->isAllowed( 'accounttracker' ) ) {
			$this->displayRestrictionError($this->user);
			$this->skipRendering();
			return false;
		}

		$this->wg->Out->addScriptFile( $this->wg->ExtensionsPath . "/wikia/AccountCreationTracker/ACT.js" );
		$this->wg->Out->addScriptFile( $this->wg->ExtensionsPath . "/wikia/AccountCreationTracker/jquery.dataTables.min.js" );
		
		$this->response->addAsset('extensions/wikia/AccountCreationTracker/ACT.scss');
		

		$username = $this->getVal( 'username' );
		$accounts = array();

		if( !empty( $username ) ) {
			$user = User::newFromName( $username );
			$userId = $user->getId();

			if( !empty( $userId ) ) {
				$accounts = $this->tracker->getAccountsByUser( $user );
				$userIds = array();

				foreach ( $accounts as $accountDetails ) {
					$userIds[] = $accountDetails['user']->getId();
				}

				$wikisCreated = count( $this->tracker->getWikisCreatedByUsers( $userIds ) );
			}
			else {
				$this->setVal( 'usernameNotFound', true );
			}
		}

		$title = F::build('Title', array('Tracker', NS_SPECIAL), 'newFromText' );
		

		$this->setVal( 'username', $username );
		$this->setVal( 'accounts', $accounts );
		$this->setVal( 'url_form', $title->getFullURL() );
		$this->setVal( 'wikis_created', $wikisCreated );
	}

	public function blockAccountGroup( $groupId ) {
		if ( !$this->wg->User->isAllowed( 'phalanx' ) ) {
			$this->displayRestrictionError($this->user);
                        $this->skipRendering();
                        return false;
		}

		if ( !class_exists( 'Phalanx' ) ) {
			// TODO display some error
                        $this->skipRendering();
                        return false;
		}

		$data = array(
				'text' => $groupId,
				'exact' => false,
				'case' => false,
				'regex' => false,
				'timestamp' => wfTimestampNow(),
				'expire' => null,
				'author_id' => $wgUser->getId(),
				'reason' => 'cookie-based block via AccountCreationTracker',
				'lang' => 'all',
				'type' => Phalanx::TYPE_COOKIE,
			     );

		$status = PhalanxHelper::save( $data );

		return $status;
	}

	function actionCloseWikis( Array $userIds ) {
		$wikis = $this->tracker->getWikisCreatedByUsers( $userIds );

		foreach ( $wikis as $id ) {
			WikiFactory::disableWiki( $id, 0, self::CLOSE_WIKI_REASON );
		}
	}

	public function renderContributions() {
		$contribs = $this->request->getVal( 'contributions' );
		$this->response->setVal( 'contributions', $contribs );
	}


	public function onAddNewAccount( $user, $byEmail ) {
		if(!($this->request instanceof WikiaRequest)) {
			$this->setRequest( F::build('WikiaRequest', array( 'params' => array( $_POST, $_GET ) ) ) );
		}

		$hash = $this->request->getCookie( self::TRACKING_COOKIE_NAME );
		if( empty( $hash ) ) {
			$wgDevelEnvironment = F::app()->getGlobal( 'wgDevelEnvironment' );
			if( !empty( $wgDevelEnvironment ) ) {
				$domain = '.wikia-dev.com';
			}
			else {
				$domain = '.wikia.com';
			}

			$hash = md5( $user->getId() );
			$this->request->setCookie( self::TRACKING_COOKIE_NAME, $hash, ( time() + ( self::TRACKING_COOKIE_TTL * 86400 ) ), '/', $domain );
		}

		$this->tracker->trackAccount( $user, $hash );

		return true;
	}

	public function onUserLoginComplete( User &$user, &$inject_html ) {
		if(!($this->request instanceof WikiaRequest)) {
			$this->setRequest( F::build('WikiaRequest', array( 'params' => array( $_POST, $_GET ) ) ) );
		}

		$hash = $this->request->getCookie( self::TRACKING_COOKIE_NAME );
		if( empty( $hash ) ) {
			$wgDevelEnvironment = F::app()->getGlobal( 'wgDevelEnvironment' );
			if( !empty( $wgDevelEnvironment ) ) {
				$domain = '.wikia-dev.com';
			}
			else {
				$domain = '.wikia.com';
			}

			$hash = md5( $user->getId() );
			$this->request->setCookie( self::TRACKING_COOKIE_NAME, $hash, ( time() + ( self::TRACKING_COOKIE_TTL * 86400 ) ), '/', $domain );
		}

		$this->tracker->trackLogin( $user, $hash );

		return true;
	}

}
