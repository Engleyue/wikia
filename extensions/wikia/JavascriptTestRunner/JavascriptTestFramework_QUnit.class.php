<?php

class JavascriptTestFramework_QUnit extends JavascriptTestFramework {

	public $javascriptFiles = array(
		'extensions/wikia/JavascriptTestRunner/js/qunit.js',
	);

	public $styleFiles = array(
		'extensions/wikia/JavascriptTestRunner/css/qunit.css',
	);

	public $forbiddenOutputs = array(
		'mwarticle',
	);

	public $html = '
<h1 id="qunit-header">AdConfig Tests</h1>
<h2 id="qunit-banner"></h2>
<div id="qunit-testrunner-toolbar"></div>
<h2 id="qunit-userAgent"></h2>
<ol id="qunit-tests"></ol>
<div id="qunit-fixture">test markup, will be hidden</div>
';

}
