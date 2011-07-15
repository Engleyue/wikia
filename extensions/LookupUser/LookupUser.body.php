<?php
/**
 * Provides the special page to look up user info
 *
 * @file
 */
class LookupUserPage extends SpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'LookupUser'/*class*/, 'lookupuser'/*restriction*/ );
	}

	function getDescription() {
		return wfMsg( 'lookupuser' );
	}

	/**
	 * Show the special page
	 *
	 * @param $subpage Mixed: parameter passed to the page or null
	 */
	public function execute( $subpage ) {
		global $wgRequest, $wgUser;
		wfLoadExtensionMessages( 'LookupUser' );

		$this->setHeaders();

		# If the user doesn't have the required 'lookupuser' permission, display an error
		if ( !$wgUser->isAllowed( 'lookupuser' ) ) {
			$this->displayRestrictionError();
			return;
		}

		if ( $subpage ) {
			$target = $subpage;
		} else {
			$target = $wgRequest->getText( 'target' );
		}

		$id = '';
		if( $wgRequest->getText( 'mode' ) == 'by_id' ) {
			$id = $target; #back up the number
			$u = User::newFromId($id); #create
			if( $u->loadFromId() ) { #test
				$target = $u->getName(); #overwrite text
			}
		}

		$emailUser = $wgRequest->getText( 'email_user' );
		if($emailUser) {
			$this->showForm( $emailUser, $id, $target );
		}
		else
		{
			$this->showForm( $target, $id );
		}

		if ( $target ) {
			$this->showInfo( $target, $emailUser );
		}
	}

	/**
	 * Show the LookupUser form
	 * @param $target Mixed: user whose info we're about to look up
	 */
	function showForm( $target, $id = '', $email = '' ) {
		global $wgScript, $wgOut;
		$title = htmlspecialchars( $this->getTitle()->getPrefixedText() );
		$action = htmlspecialchars( $wgScript );
		$target = htmlspecialchars( $target );
		$ok = wfMsg( 'go' );
		$username_label = wfMsg( 'username' );
		$email_label = wfMsg( 'email' ) ;
		$inputformtop = wfMsg( 'lookupuser' );

		$wgOut->addWikiMsg('lookupuser-intro');

		$wgOut->addHTML( <<<EOT
<fieldset>
<legend>{$inputformtop}</legend>
<form method="get" action="$action">
<input type="hidden" name="title" value="{$title}" />
<table border="0">
<tr>
<td align="right">$username_label</td>
<td align="left"><input type="text" size="50" name="target" value="$target" /></td>
<td align="center"><input type="submit" value="$ok" /></td>
</tr>
</table>
</form>
EOT
		);

		$wgOut->addHTML( <<<EOT
<form method="get" action="$action">
<input type="hidden" name="title" value="{$title}" />
<table border="0">
<tr>
<td align="right">$email_label</td>
<td align="left"><input type="text" size="50" name="target" value="{$email}" /></td>
<td align="center"><input type="submit" value="$ok" /></td>
</tr>
</table>
</form>
EOT
		);

		$wgOut->addHTML( <<<EOT
<form method="get" action="$action">
<input type="hidden" name="title" value="{$title}" />
<input type="hidden" name="mode" value="by_id" />
<table border="0">
<tr>
<td align="right">ID</td>
<td align="left"><input type="text" size="10" name="target" value="$id" /></td>
<td align="center"><input type="submit" value="$ok" /></td>
</tr>
</table>
</form>
</fieldset>
EOT
		);
	}

	/**
	 * Retrieves and shows the gathered info to the user
	 * @param $target Mixed: user whose info we're looking up
	 */
	function showInfo( $target, $emailUser = "" ) {
		global $wgOut, $wgLang, $wgScript;
		//Small Stuff Week - adding table from Special:LookupContribs --nAndy
		global $wgExtensionsPath, $wgStyleVersion, $wgJsMimeType, $wgStylePath;
		
		/**
		 * look for @ in username
		 */
		$count = 0; $aUsers = array(); $userTarget = "";
		if( strpos( $target, '@' ) !== false ) {
			/**
			 * find username by email
			 */
			$emailUser = htmlspecialchars( $emailUser );
			$dbr = wfGetDB( DB_SLAVE );
			
			$oRes = $dbr->select( "user", "user_name", array( "user_email" => $target ), __METHOD__ );

			$loop = 0;
			while( $oRow = $dbr->fetchObject( $oRes ) ) {
				if ($loop === 0) {
					$userTarget = $oRow->user_name;
				}
				if (!empty($emailUser) && ($emailUser == $oRow->user_name)) {
					$userTarget = $emailUser;
				}
				$aUsers[] = $oRow->user_name;
				$loop++;
			}
			$count = $loop;
		}

		$user = User::newFromName( (!empty($userTarget)) ? $userTarget : $target );
		if ( $user == null || $user->getId() == 0 ) {
			$wgOut->addWikiText( '<span class="error">' . wfMsg( 'lookupuser-nonexistent', $target ) . '</span>' );
		} else {
			if ( $count > 1 ) {
				$action = htmlspecialchars( $wgScript );
				$title = htmlspecialchars( $this->getTitle()->getPrefixedText() );
				$ok = wfMsg( 'go' );
				$foundInfo = wfMsg('lookupuser-foundmoreusers');
				$options = array();
				if (!empty($aUsers) && is_array($aUsers)) {
					foreach ($aUsers as $id => $userName) {
						$options[] = XML::option( $userName, $userName, ($userName == $userTarget) );
					}
				}
				$selectForm = Xml::openElement( 'select', array( 'id' => 'email_user', 'name' => "email_user" ) );
				$selectForm .= "\n" . implode( "\n", $options ) . "\n";
				$selectForm .= Xml::closeElement( 'select' );
				$selectForm .= "({$count})";
			
				$wgOut->addHTML( <<<EOT
<fieldset>
<form method="get" action="$action">
<input type="hidden" name="title" value="{$title}" />
<input type="hidden" name="target" value="{$target}" />
<table border="0">
<tr>
<td align="right">{$foundInfo}</td>
<td align="left">$selectForm</td>
<td colspan="2" align="center"><input type="submit" value="$ok" /></td>
</tr>
</table>
</form>
EOT
				);
			}

			$authTs = $user->getEmailAuthenticationTimestamp();
			if ( $authTs ) {
				$authenticated = wfMsg( 'lookupuser-authenticated', $wgLang->timeanddate( $authTs ) );
			} else {
				$authenticated = wfMsg( 'lookupuser-not-authenticated' );
			}
			$optionsString = '';
			foreach ( $user->getOptions() as $name => $value ) {
				$optionsString .= "$name = $value <br />";
			}
			$name = $user->getName();
			if( $user->getEmail() ) {
				$email = $user->getEmail();
			} else {
				$email = wfMsg( 'lookupuser-no-email' );
			}
			if( $user->getRegistration() ) {
				$registration = $wgLang->timeanddate( $user->getRegistration() );
			} else {
				$registration = wfMsg( 'lookupuser-no-registration' );
			}
			$wgOut->addWikiText( '*' . wfMsg( 'username' ) . ' [[User:' . $name . '|' . $name . ']] (' .
				$wgLang->pipeList( array(
					'<span id="lu-tools">[[User talk:' . $name . '|' . wfMsg( 'talkpagelinktext' ) . ']]',
					'[[Special:Contributions/' . $name . '|' . wfMsg( 'contribslink' ) . ']]</span>)'
				) ) );
			$wgOut->addWikiText( '*' . wfMsgForContent( 'lookupuser-toollinks', $name, urlencode($name) ) );
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-id', $user->getId() ) );
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-email', $email, $name ) );
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-info-authenticated', $authenticated ) );
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-realname', $user->getRealName() ) );
			
			//Begin: Small Stuff Week - adding table from Special:LookupContribs --nAndy
			$wgOut->addExtensionStyle("{$wgExtensionsPath}/wikia/LookupContribs/css/table.css?{$wgStyleVersion}");
			$wgOut->addScript("<script type=\"{$wgJsMimeType}\" src=\"{$wgStylePath}/common/jquery/jquery.dataTables.min.js?{$wgStyleVersion}\"></script>\n");
			
			$oTmpl = new EasyTemplate( dirname( __FILE__ ) . "/templates/" );
			$oTmpl->set_vars(array(
				'username'  => $name
			));
			$wgOut->addHTML( $oTmpl->execute('contribution.table') );
			//End: Small Stuff Week
			
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-registration', $registration ) );
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-touched', $wgLang->timeanddate( $user->mTouched ) ) );
			$wgOut->addWikiText( '*' . wfMsg( 'lookupuser-useroptions' ) . '<br />' . $optionsString );
		}
	}
	
	/**
	 * @brief: Ajax call loads data for jQuery.table plugin
	 * 
	 * @author Andrzej 'nAndy' Łukaszewski
	 */
	public function loadAjaxContribData() {
		global $wgRequest, $wgUser, $wgCityId, $wgDBname, $wgLang;
		
		wfProfileIn( __METHOD__ );
	
		$username 	= $wgRequest->getVal('username');
		$dbname		= $wgRequest->getVal('wiki');
		$mode 		= $wgRequest->getVal('mode');
		$nspace		= $wgRequest->getVal('ns', -1);
		$limit		= $wgRequest->getVal('limit');
		$offset		= $wgRequest->getVal('offset');
		$loop		= $wgRequest->getVal('loop');
		$order		= $wgRequest->getVal('order');
		$numOrder	= $wgRequest->getVal('numOrder');

		$result = array(
			'sEcho' => intval($loop), 
			'iTotalRecords' => 0, 
			'iTotalDisplayRecords' => 0, 
			'sColumns' => '',
			'aaData' => array()
		);
		
		if ( empty($wgUser) ) return '';
		if ( $wgUser->isBlocked() ) return '';
		if ( !$wgUser->isLoggedIn() ) return '';
		if ( !$wgUser->isAllowed( 'lookupcontribs' ) ) {
			wfProfileOut( __METHOD__ );
			return Wikia::json_encode($result);
		}
		
		$oLC = new LookupContribsCore($username);
		if ( $oLC->checkUser() ) {
			if ( empty($mode) ) {
				$oLC->setLimit($limit);
				$oLC->setOffset($offset);
				$activity = $oLC->checkUserActivity();
				if ( !empty($activity) ) {
					$result['iTotalRecords'] = intval($limit);
					$result['iTotalDisplayRecords'] = intval($activity['cnt']);
					$result['sColumns'] = 'id,title,url,lastedit,userrights,blocked';
					$rows = array();
					foreach ( $activity['data'] as $row ) {
						$rows[] = array(
							$row['id'], // wiki Id
							$row['title'], //wiki title
							$row['url'], // wiki url 
							$wgLang->timeanddate( wfTimestamp( TS_MW, $row['last_edit'] ), true ), //last edited
							LookupUserPage::getUserData($username, $row['id'], $row['url']),
							LookupUserPage::getUserData($username, $row['id'], $row['url'], true),
						);
					}
					$result['aaData'] = $rows;
				}
			} else {
				$oLC->setDBname($dbname);
				$oLC->setMode($mode);
				$oLC->setNamespaces($nspace);
				$oLC->setLimit($limit);
				$oLC->setOffset($offset);
				$data = $oLC->fetchContribs();
				/* order by timestamp desc */
				$nbr_records = 0;
				$result = array();
				$res = array();
				if ( !empty($data) && is_array($data) ) {
					$result['iTotalRecords'] = intval($limit);
					$result['iTotalDisplayRecords'] = intval($data['cnt']);
					$result['sColumns'] = 'id,title,url,lastedit';
					$rows = array();
					if ( isset($data['data']) ) {
						$loop = 1;
						foreach ($data['data'] as $id => $row) {
							list ($link, $diff, $hist, $contrib, $edit, $removed) = array_values($oLC->produceLine( $row ));
							$rows[] = array(
								$loop + $offset, // id
								$link, // title 
								$diff, // diff 
								$hist, // history
								$contrib, //user contribution (link to special page)
								$edit
							);
							$loop++;
						}
					}
					$result['aaData'] = $rows;
				}
			}
		}
		
		wfProfileOut( __METHOD__ );
		return Wikia::json_encode($result);
	}
	
	/**
	 * @brief: Returns memc key
	 * 
	 * @param string $userName name of a use
	 * @param integer $wikiId id of a wiki
	 * 
	 * @author Andrzej 'nAndy' Łukaszewski
	 * 
	 * @return string
	 */
	public static function getUserLookupMemcKey($userName, $wikiId) {
		return 'lookupUser'.'user'.$userName.'on'.$wikiId;
	}
	
	/**
	 * @brief: Returns data for jQuery.table plugin used by ajax call LookupUserPage::loadAjaxContribData()
	 * 
	 * @param string $userName name of a use
	 * @param integer $wikiId id of a wiki
	 * @param string $wikiUrl url address of a wiki
	 * @param boolean $checkingBlocks a flag which says if we're checking user groups or block information
	 * 
	 * @author Andrzej 'nAndy' Łukaszewski
	 * 
	 * @return string
	 */
	public static function getUserData($userName, $wikiId, $wikiUrl, $checkingBlocks = false) {
		wfProfileIn( __METHOD__ );
		
		global $wgMemc;
		
		$cachedData = $wgMemc->get( LookupUserPage::getUserLookupMemcKey($userName, $wikiId) );
		$cachedData = null;
		
		if( !empty($cachedData) ) {
			if( $checkingBlocks === false ) {
				if( $cachedData['groups'] === false ) {
					
					wfProfileOut( __METHOD__ );
					return '-';
				} else {
					
					wfProfileOut( __METHOD__ );
					return implode(', ', $cachedData['groups']);
				}
			} else {
				wfProfileOut( __METHOD__ );
				return ( $cachedData['blocked'] === true ) ? 'Y' : 'N';
			}
		} else {
			if( $checkingBlocks === false ) {
				$result = '<span class="user-groups-placeholder">';
			} else {
				$result = '<span class="user-blocked-placeholder">';
			}
			
			$result .= '<img src="/skins/common/images/ajax.gif" />'.
				'<input type="hidden" class="name" value="'.$userName.'" />'.
				'<input type="hidden" class="wikiId" value="'.$wikiId.'" />'.
				'<input type="hidden" class="wikiUrl" value="'.$wikiUrl.'" />'.
				'</span>';
			
			wfProfileOut( __METHOD__ );
			return $result;
		}
	}
	
	/**
	 * @brief: Ajax call loads data for two new columns: user rights and blocked
	 * 
	 * @author Andrzej 'nAndy' Łukaszewski
	 */
	public function requestApiAboutUser() {
		wfProfileIn( __METHOD__ );
		
		global $wgRequest, $wgMemc;
		
		$userName = $wgRequest->getVal('username');
		$wikiUrl = $wgRequest->getVal('url');
		$wikiId = $wgRequest->getVal('id');
		$apiUrl = $wikiUrl.'api.php?action=query&list=users&ususers='.$userName.'&usprop=blockinfo|groups&format=php';
		
		$cachedData = $wgMemc->get( LookupUserPage::getUserLookupMemcKey($userName, $wikiId) );
		$cachedData = null;
		
		if( !empty($cachedData) ) {
			//$result = array('success' => true, 'data' => $cachedData);
			$result = array('success' => true, 'data' => $cachedData, '$apiUrl' => $apiUrl);
		} else {
			/*
			$req = HttpRequest::factory($apiUrl, array());
			$req->execute();
			$httpRequestResult = $req;
			*/
			$result = unserialize(file_get_contents($apiUrl));
			
			if( isset($result['query']['users'][0]) ) {
				$userData = $result['query']['users'][0];
				
				if( !isset($userData['groups']) ) {
					$userData['groups'] = false;
				} else {
					$userData['groups'] = LookupUserPage::selectGroups($userData['groups']);
				}
				
				if( !isset($userData['blockedby']) ) {
					$userData['blocked'] = false;
				} else {
					$userData['blocked'] = true;
				}
				
				//$result = array('success' => true, 'data' => $userData);
				$result = array('success' => true, 'data' => $userData, '$apiUrl' => $apiUrl);
				$wgMemc->set( LookupUserPage::getUserLookupMemcKey($userName, $wikiId), $userData, 3600 ); //1h
			} else {
				$result = array('success' => false);
			}
		}
		
		wfProfileOut( __METHOD__ );
		return json_encode($result);
	}
	
	/**
	 * @brief: Returns only selected user groups/rights
	 * 
	 * @param array $groups array with wiki names of groups like: sysop, bureaucrat, chatmoderator
	 * 
	 * @return array
	 * 
	 * @author Andrzej 'nAndy' Łukaszewski
	 */
	public static function selectGroups($groups) {
		wfProfileIn( __METHOD__ );
		
		$userGroups = array();
		
		foreach($groups as $group) {
			if( $group == 'sysop') {
				$userGroups[] = wfMsg('lookupuser-admin');
			}
			
			if( $group == 'bureaucrat') {
				$userGroups[] = wfMsg('lookupuser-bureaucrat');
			}
			
			if( $group == 'chatmoderator') {
				$userGroups[] = wfMsg('lookupuser-chatmoderator');
			}
		}
		
		wfProfileOut( __METHOD__ );
		return $userGroups;
	}
}