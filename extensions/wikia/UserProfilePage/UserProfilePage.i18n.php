<?php

$messages = array();

$messages['en'] = array(
	'userprofilepage-desc' => 'Provides a user page that is fun and easy to update',

	'userprofilepage-edit-avatar-label' => 'Edit picture',
	'userprofilepage-users-notes-title' => 'About me',
	'userprofilepage-about-section-title' => 'My $1 interview',
	'userprofilepage-edit-button' => 'Edit',
	'userprofilepage-about-article-title' => 'About',
	'userprofilepage-about-empty-section' => 'This section is empty. Click edit to add some stuff here!',
	'userprofilepage-edit-permision-denied' => 'Edit permissions denied',
	'userprofilepage-edit-permision-denied-info' => 'You do not have permissions to edit somebody\'s user page or subpage',

	// Masthead
	'userprofilepage-leave-message' => 'Leave message',
	'userprofilepage-edits-since' => 'Edits since joining this wiki<br/>$1',
	'userprofilepage-user-last-action-edit' => '$1 edited the <a href="$2">$3</a> page',
	'userprofilepage-user-last-action-new' => '$1 created <a href="$2">$3</a>',
	'userprofilepage-user-last-action-delete' => '$1 deleted the <a href="$2">$3</a> page',

	//Top Wikis
	'userprofilepage-top-wikis-title' => '$1\'s Top wiki\'s',
	'userprofilepage-top-wikis-edit-count' => 'Number of edits',
	'userprofilepage-top-wikis-hidden-see-more' => 'You have hidden {{PLURAL:$1|$1 wiki|$1 wikis}}',
	'userprofilepage-top-wikis-unhide-label' => 'unhide',
	'userprofilepage-top-wikis-hide-label' => 'hide',

	//Recent activity
	'userprofilepage-recent-activity-title' => '$1\'s recent activity',
	'userprofilepage-activity-edit'    => 'edited the $1 page',
	'userprofilepage-activity-new'     => 'created $1',
	'userprofilepage-activity-comment' => 'commented on $1',
	'userprofilepage-activity-image'   => 'uploaded an image to $1',
	'userprofilepage-activity-video'   => 'uploaded a video to $1',
	'userprofilepage-activity-delete'  => 'deleted the $1 page',
	'userprofilepage-activity-talk'    => 'left a message on $1\'s talk page',
	'userprofilepage-recent-activity-default' => '$1 has joined Wikia',
	'userprofilepage-top-recent-activity-see-more' => 'See all',

	//Top pages
	'userprofilepage-top-pages-title' => '$1\'s Top $2 pages',
	'userprofilepage-top-page-unhide-label' => 'unhide',
	'userprofilepage-top-pages-hidden-see-more' => 'You have hidden {{PLURAL:$1|$1 page|$1 pages}}',
	'userprofilepage-top-pages-default' => 'You do not have any top pages yet. Why not check out some <a href="$1">random pages</a> on the wiki?',

	'recipes-template-aboutuser-fields' => '* wpTitle
** type|input
** label|title
** hint|title
** hintHideable|true
** required|true
** readOnly|true
* wpDescription
** type|input
** label|user-description
** hint|user-description
** hintHideable|true
** required|true
* wpBirthDate
** type|input
** label|user-birthdate
** hint|user-birthdate
** hintHideable|true
* wpSpecies
** type|input
** label|user-species
** hint|user-species
** hintHideable|true
* wpAbilities
** type|input
** label|user-abilities
** hint|user-abilities
** hintHideable|true
* wpGender
** type|input
** label|user-gender
** hint|user-gender
** hintHideable|true
* wpAffiliation
** type|input
** label|user-affiliation
** hint|user-affiliation
** hintHideable|true',
	'recipes-template-skip-toggle-types' => 'aboutuser',
	'recipes-template-user-description-label' => 'Description',
	'recipes-template-user-description-hint' => 'Short info about the user',
	'recipes-template-user-birthdate-label' => 'Birth date',
	'recipes-template-user-birthdate-hint' => 'Date of birth (in any format)',
	'recipes-template-user-species-label' => 'Species',
	'recipes-template-user-species-hint' => 'Species',
	'recipes-template-user-abilities-label' => 'Abilities',
	'recipes-template-user-abilities-hint' => 'Abilities',
	'recipes-template-user-gender-label' => 'Gender',
	'recipes-template-user-gender-hint' => 'Gender',
	'recipes-template-user-affiliation-label' => 'Affiliation',
	'recipes-template-user-affiliation-hint' => 'Affiliation',
	'recipes-template-aboutuser-wikitext' => '<!--Change at your own risk //--><div class="interview">
*<div class="question">How would you describe yourself?</div>
*<div class="answer"><<wpDescription>></div>
*<div class="question">When did you born?</div>
*<div class="answer"><<wpBirthDate>></div>
*<div class="question">What\'s your species?</div>
*<div class="answer"><<wpSpecies>></div>

*<div class="question">What are your abilities?</div>
*<div class="answer"><<wpAbilities>></div>
*<div class="question">What\'s your gender?</div>
*<div class="answer"><<wpGender>></div>
*<div class="question">Do you have any affiliation?</div>
*<div class="answer"><<wpAffiliation>></div></div>',
);
