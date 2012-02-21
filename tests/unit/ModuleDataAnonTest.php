<?php

class ModuleDataAnonTest extends PHPUnit_Framework_TestCase {

	// initialize skin only once
	public static function setUpBeforeClass() {
		global $wgTitle, $wgUser, $wgForceSkin, $wgOut;

		$wgForceSkin = 'oasis';
		$wgTitle = Title::newMainPage();
		$wgUser = User::newFromId(0); // anon

		wfSuppressWarnings();
		ob_start();

		$wgOut->setCategoryLinks(array('foo' => 1, 'bar' => 2));

		$skin = $wgUser->getSkin();
		$skin->outputPage(new OutputPage());

		wfClearOutputBuffers();
		wfRestoreWarnings();
	}

	function testAccountNavigationModule() {
		global $wgUser;
		$userName = $wgUser->getName();

		$moduleData = Module::get('AccountNavigation')->getData();

		// there should be no avatar or profile link
		$this->assertNull($moduleData['profileLink']);
		$this->assertNull($moduleData['profileAvatar']);
		$this->assertNull($moduleData['dropdown']);

		// log in / register links
		$this->assertRegExp('/Special:Signup(.*)type=login/', $moduleData['personal_urls']['login']['href']);
		$this->assertRegExp('/Special:Signup(.*)type=signup/', $moduleData['personal_urls']['register']['href']);

		// user data
		$this->assertTrue($moduleData['isAnon']);
		$this->assertEquals($userName, $moduleData['username']);
	}

	function testBodyModule() {
		global $wgTitle, $wgOasisNavV2;

		//Special search page has a custom list of modules
		$wgTitle = Title::newFromText('Special:Search');
		$moduleData = Module::get('Body')->getData();
		$railList = $moduleData['railModuleList'];

		if ( empty( $wgOasisNavV2 ) ) {
			$this->assertEquals( $railList[1450][0], 'PagesOnWiki' );
		} else {
			$this->assertNotEquals( $railList[1450][0], 'PagesOnWiki' );
		}

		$this->assertEquals($railList[1250][0], 'LatestActivity');
		$this->assertEquals($railList[1300][0], 'LatestPhotos');

		// Assert that content page has a Search Module
		$wgTitle = Title::newFromText('Foo');
		$moduleData = Module::get('Body')->getData();
		$railList = $moduleData['railModuleList'];
		$this->assertEquals($railList[1500][0], 'Search');
	}

}
