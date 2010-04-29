<?php

$messages = array();

$messages['en'] = array(
	'wikiafollowedpages-special-heading-category' => "Categories ($1) ",
	'wikiafollowedpages-special-heading-article' => "Articles ($1)",
	'wikiafollowedpages-special-heading-blogs' => "Blogs and Posts ($1)",
	'wikiafollowedpages-special-heading-forum' => 'Forum threads ($1)',
	'wikiafollowedpages-special-heading-project' => 'Project pages ($1)',
	'wikiafollowedpages-special-heading-user' => 'User pages ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Templates pages ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki pages ($1)',
	'wikiafollowedpages-special-heading-media' => 'Images and videos ($1)',
	'wikiafollowedpages-special-namespace' => "($1 page) ",
	'wikiafollowedpages-special-empty' => "This user's followed pages list is empty. (Add pages to this list by clicking \"Follow\" at the top of an article). ",
	'wikiafollowedpages-special-anon' => 'Please log in to create or view your followed pages list.', 

	'wikiafollowedpages-special-showall' => 'Show All',
	'wikiafollowedpages-special-title' => 'Followed pages',
	'wikiafollowedpages-special-delete-tooltip' => 'Remove this page',

	'wikiafollowedpages-special-hidden' => 'This user has chosen to hide their followed pages list from public view.',  
	'wikiafollowedpages-special-hidden-unhide' => 'Unhide this list.',
	'wikiafollowedpages-special-blog-by' => 'by $1',
	'wikiafollowedpages-masthead' => 'Followed pages',  
	'wikiafollowedpages-following' => 'Following',
	'wikiafollowedpages-special-title-userbar' => 'Followed pages',

	'tog-enotiffollowedpages' => 'E-mail me when a page I\'m following is changed',
	'tog-enotiffollowedminoredits' => 'E-mail me for minor edits',

	'wikiafollowedpages-prefs-advanced' => 'Advanced options',  
	'wikiafollowedpages-prefs-watchlist' => 'Special:Watchlist only',
 
	'tog-hidefollowedpages' => 	'Make my followed pages lists private', 
	'follow-categoryadd-summery' => "Page added to category", //TODO check this 
	'follow-bloglisting-summery' => "Blog posted on blog page",

	'wikiafollowedpages-userpage-heading' => "Pages I'm Following",
	'wikiafollowedpages-userpage-more'  =>  'More',
	'wikiafollowedpages-userpage-hide'  =>  'hide', 
	'wikiafollowedpages-userpage-empty' => "This user's followed pages list is empty. (Add pages to this list by clicking \"Follow\" at the top of an article).",

	'enotif_subject_categoryadd' => '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Dear $WATCHINGUSERNAME,

There has been an edit to a page you are watching on {{SITENAME}}.

See "$PAGETITLE_URL" for the current version.

$NEWPAGE

$PAGESUMMARY

Please visit and edit often...

{{SITENAME}}

___________________________________________
* Check out our featured wikis! http://www.wikia.com

* Want to control which emails you receive? 
Go to: {{fullurl:{{ns:special}}:Preferences}}.',

	'enotif_body_categoryadd-html' => '<p>
Dear $WATCHINGUSERNAME,
<br /><br />
There has been an edit to a page you are watching on {{SITENAME}}.
<br /><br />
See <a href="$PAGETITLE_URL">$PAGETITLE</a> for the current version.
<br /><br />
$NEWPAGEHTML
<br /><br />
$PAGESUMMARY
<br /><br />
Please visit and edit often...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Check out our featured wikis!</a></li>
<li>Want to control which emails you receive? Go to <a href="{{fullurl:{{ns:special}}:Preferences}}">User Preferences</a></li>
</ul>
</p>', 
	'enotif_subject_blogpost' => '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
	'enotif_body_blogpost' => 'Dear $WATCHINGUSERNAME,

There has been an edit to a page you are watching on {{SITENAME}}.

See "$PAGETITLE_URL" for the current version.

$NEWPAGE

$PAGESUMMARY

Please visit and edit often...

{{SITENAME}}

___________________________________________
* Check out our featured wikis! http://www.wikia.com

* Want to control which emails you receive? 
Go to: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Dear $WATCHINGUSERNAME,
<br /><br />
There has been an edit to a page you are watching on {{SITENAME}}.
<br /><br />
See <a href="$PAGETITLE_URL">$PAGETITLE</a> for the current version.
<br /><br />
$NEWPAGEHTML
<br /><br />
$PAGESUMMARY
<br /><br />
Please visit and edit often...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Check out our featured wikis!</a></li>
<li>Want to control which emails you receive? Go to <a href="{{fullurl:{{ns:special}}:Preferences}}">User Preferences</a></li>
</ul>
</p>'
);
