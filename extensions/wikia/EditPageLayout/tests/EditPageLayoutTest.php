<?php
require_once dirname(__FILE__) . '/../EditPageLayout_setup.php';
wfLoadAllExtensions();

class EditPageLayoutTest extends PHPUnit_Framework_TestCase {

	private function editPageFactory(Title $title) {
		$article = WF::build('Article', array($title));

		return WF::build('EditPageLayout', array($article));
	}

	public function testMainPageEdit() {
		// setup edit page object
		$title = WF::build('Title', array(), 'newMainPage');
		$editPage = $this->editPageFactory($title);

		// it should extend MW core class
		$this->assertInstanceOf('EditPage', $editPage);

		$this->assertEquals($title->getArticleID(), $editPage->getArticle()->getID());
		$this->assertEquals($title, $editPage->getEditedTitle());

		$formAction = str_replace('action=edit', 'action=submit', $title->getEditURL());
		$this->assertEquals($formAction, $editPage->getFormAction());
	}

	public function testCustomFormHandler() {
		$title = WF::build('Title', array('Foo'), 'newFromText');
		$editPage = $this->editPageFactory($title);

		$customHandler = WF::build('Title', array('Special:CustomHandler'), 'newFromText');

		$editPage->setCustomFormHandler($customHandler);
		$this->assertEquals($customHandler, $editPage->getCustomFormHandler());
		$this->assertEquals($customHandler->getLocalURL('action=submit'), $editPage->getFormAction());
		$this->assertEquals($title, $editPage->getEditedTitle());
	}

	public function testAddingFields() {
		$title = WF::build('Title', array('Foo'), 'newFromText');
		$editPage = $this->editPageFactory($title);

		// test custom checkboxes
		$editPage->addCustomCheckbox('foo', 'label', true /* $checked */);
		$editPage->addCustomCheckbox('bar', 'test', false /* $checked */);

		$this->assertEquals(array(
			array(
				'name' => 'foo',
				'label' => 'label',
				'checked' => true,
			),
			array(
				'name' => 'bar',
				'label' => 'test',
				'checked' => false,
			),
		), $editPage->getCustomCheckboxes());

		// test hidden fields
		$editPage->addHiddenField(array(
			'type' => 'checkbox',
			'name' => 'foo',
			'value' => true,
			'label' => 'label',
		));
		$editPage->addHiddenField(array(
			'type' => 'text',
			'name' => 'bar',
			'value' => 'summary',
			'required' => false,
		));
		$editPage->addHiddenField(array(
			'type' => 'textarea',
			'name' => 'field123',
			'value' => 'qwerty',
			'required' => true,
		));
		$editPage->addHiddenField(array(
			'type' => 'hidden',
			'name' => 'test',
			'value' => 'hidden value',
		));

		$this->assertEquals(
			'<fieldset id="EditPageHiddenFields">'.
				'<label>label<input type="checkbox" name="foo" value="1" data-required="" checked="checked" /></label>'.
				'<input type="text" name="bar" value="summary" data-required="" />'.
				'<textarea name="field123" data-required="1">qwerty</textarea>'.
				'<input type="hidden" name="test" value="hidden value" data-required="" />'.
			'</fieldset>',
			$editPage->renderHiddenFields());

		// summary box
		$this->assertStringStartsWith('<textarea id="wpSummary" name="wpSummary" placeholder=', $editPage->renderSummaryBox());
	}

	public function testPreloadText() {
		$title = WF::build('Title', array('NewArticle'), 'newFromText');
		$editPage = $this->editPageFactory($title);

		$preload = 'Preload - testing, testing...';
		$editPage->setPreloadedText($preload);
		$editPage->showEditForm();

		$this->assertEquals($preload, $editPage->textbox1);
	}

	public function testEditNotices() {
		$title = WF::build('Title', array('NewArticle'), 'newFromText');
		$editPage = $this->editPageFactory($title);

		$editPage->addEditNotice('<div>foo bar</div>');
		$editPage->addEditNotice('<div>second notice</div>');

		$this->assertEquals(array(
			'foo bar',
			'second notice'
		), $editPage->getNotices());
		$this->assertEquals('<div>foo bar</div><div>second notice</div>', $editPage->getNoticesHtml());
	}
}
