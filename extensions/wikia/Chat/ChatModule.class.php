<?php
class ChatModule extends Module {

	var $wgStylePath;
	var $wgExtensionsPath;
	var $wgBlankImgUrl;
	var $globalVariablesScript;
	var $username;
	var $roomId;
	var $roomName;
	var $roomTopic;
	var $userList;
	var $messages;
	var $isChatMod;
	var $bodyClasses = '';
	var $themeSettings;
	var $avatarUrl;
	var $nodeHostname;
	var $nodePort;
	var $pathToProfilePage;
	var $pathToContribsPage;
	var $mainPageURL;
	var $wgFavicon = '';
	var $jsMessagePackagesUrl = '';
	var $app;

	public function executeIndex() {
		global $wgUser, $wgDevelEnvironment, $wgRequest, $wgCityId, $wgFavicon;
		wfProfileIn( __METHOD__ );

		$this->app = WF::build('App');

		// String replacement logic taken from includes/Skin.php
		$this->wgFavicon = str_replace('images.wikia.com', 'images1.wikia.nocookie.net', $wgFavicon);

		// add messages (fetch them using <script> tag)
		F::build('JSMessages')->enqueuePackage('Chat', JSMessages::INLINE); // package defined in Chat_setup.php

		$this->mainPageURL = Title::newMainPage()->getLocalURL();

		// Variables for this user
		$this->username = $wgUser->getName();
		$this->avatarUrl = AvatarService::getAvatarUrl($this->username, 50);

		// Find the chat for this wiki (or create it, if it isn't there yet).
		$this->roomName = $this->roomTopic = "";
		$this->roomId = NodeApiClient::getDefaultRoomId($this->roomName, $this->roomTopic);

		// Set the hostname of the node server that the page will connect to.
		$this->nodePort = NodeApiClient::PORT;
		if($wgDevelEnvironment){
			$this->nodeHostname = NodeApiClient::HOST_DEV_FROM_CLIENT;
		} else {
			$this->nodeHostname = NodeApiClient::HOST_PRODUCTION_FROM_CLIENT;
		}

		// Some building block for URLs that the UI needs.
		$this->pathToProfilePage = Title::makeTitle( NS_USER, '$1' )->getFullURL();
		$this->pathToContribsPage = SpecialPage::getTitleFor( 'Contributions', '$1' )->getFullURL();

		// Some i18n'ed strings used inside of templates by Backbone. The <%= stuffInHere % > is intentionally like
		// that & will end up in the string (substitution occurs later).
		$this->editCountStr = wfMsg('chat-edit-count', "<%= editCount %>");
		$this->memberSinceStr = "<%= since %>";

		if ($wgUser->isAllowed( 'chatmoderator' )) {
			$this->isChatMod = 1;
			$this->bodyClasses .= ' chat-mod ';
		} else {
			$this->isChatMod = 0;
		}

		// Adding chatmoderator group for other users. CSS classes added to body tag to hide/show option in menu.
		$userChangeableGroups = $wgUser->changeableGroups();
		if (in_array('chatmoderator', $userChangeableGroups['add'])) {
			$this->bodyClasses .= ' can-give-chat-mod ';
		}

		$this->globalVariablesScript = Skin::makeGlobalVariablesScript(Module::getSkinTemplateObj()->data);

		//Theme Designer stuff
		$themeSettings = new ThemeSettings();
		$this->themeSettings = $themeSettings->getSettings();

		// Since we don't emit all of the JS headscripts or so, fetch the URL to load the JS Messages packages.
		$this->jsMessagePackagesUrl = F::build('JSMessages')->getExternalPackagesUrl();

		wfProfileOut( __METHOD__ );
	}

}
