<?php

class ModuleTest extends WikiaBaseTest {

	function setUp() {
		global $wgAutoloadClasses, $IP;

		$wgAutoloadClasses['UnitTestModule'] = dirname( __FILE__ ) . '/modules/UnitTestModule.class.php';
		$wgAutoloadClasses['OasisTemplate'] = $IP . '/skins/Oasis.php';
	}

	function testModuleGet() {
		$result = F::app()->renderView('UnitTest', 'Index');
		$this->assertEquals(
			'Foo',
			$result
		);
	}

	function testViewSpecialPageLink() {
		$this->assertTag (
			array("tag" => "a"),
			Wikia::specialPageLink('CreatePage', 'button-createpage', 'wikia-button')
		);
	}

	function testViewLink() {
		$this->assertTag (
			array("tag" => "a"),
			Wikia::link(Title::newFromText("Test"))
		);
	}

	function testGetDataAll() {
		$this->markTestSkipped();
		$random = rand();
		$data = Module::get('UnitTest', 'Index2', array('foo2' => $random))->getData();

		$this->assertEquals(
			$random,
			$data['foo2']
		);
	}

	function testGetDataOne() {
		$this->markTestSkipped();
		$random = rand();

		$this->assertEquals(
			$random,
			Module::get('UnitTest', 'Index2', array('foo2' => $random))->getData('foo2')
		);
	}

	function testSetGetSkinTemplate() {
		$template = new OasisTemplate();
		$app = F::app();

		$app->setSkinTemplateObj($template);

		$this->assertEquals(
			$template,
			$app->getSkinTemplateObj()
		);
	}

	function testNotExistingModule() {
		$this->setExpectedException('WikiaException');
		$this->assertNull(Module::get('ModuleThatDoesNotExist'));
	}

}
