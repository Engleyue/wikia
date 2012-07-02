<?php
# Example WikiMedia extension
# with WikiMedia's extension mechanism it is possible to define
# new tags of the form
# <TAGNAME> some text </TAGNAME>
# the function registered by the extension gets the text between the
# tags as input and can transform it into arbitrary HTML code.
# Note: The output is not interpreted as WikiText but directly
#       included in the HTML output. So Wiki markup is not supported.
# To activate the extension, include it from your LocalSettings.php
# with: include("extensions/YourExtensionName.php");

//Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
$wgHooks['ParserFirstCallInit'][] = 'newpageExtension';

function newpageExtension($parser) {
	# register the extension with the WikiText parser
	# the first parameter is the name of the new tag.
	# In this case it defines the tag <example> ... </example>
	# the second parameter is the callback function for
	# processing the text between the tags
	$parser->setHook('newpage', 'renderNewpage');
	return true;
}

# The callback function for converting the input text to HTML output
function renderNewpage($input, $argv, &$parser) {
	# $argv is an array containing any arguments passed to the
	# extension like <example argument="foo" bar>..
	# Put this on the sandbox page:  (works in MediaWiki 1.5.5)
	#   <example argument="foo" argument2="bar">Testing text **example** in between the new tags</example>

	$saneinput = empty($argv['emptysearchbox']) ? $parser->getTitle()->getText() : '';
	$action = Skin::makeSpecialUrl('NewWebsite');
	$header = wfMsgExt('newmark-header', array('parseinline'));
	$submit = wfMsg('newmark-submit');

	$output = "<p />
		<form action=\"$action\" method=\"get\">
		$header
		<input type=\"text\" name=\"param\" size=\"40\" maxlength=\"80\" value=\"$saneinput\" />
		<input type=\"submit\" value=\"$submit\" />
		</form><p />";

	return $output;
}