<?php
$messages = array();

$messages['en'] = array(
	'userprofilepage-desc' => 'Provides a user page that is fun and easy to update',
	'userprofilepage-activity-edit'    => '$1 edited the $2 article $3 ago',
	'userprofilepage-activity-new'     => '$1 created $2 $3 ago',
	'userprofilepage-activity-comment' => '$1 commented on $2 $3 ago',
	'userprofilepage-activity-image'   => '$1 uploaded an image to $2 $3 ago',
	'userprofilepage-activity-video'   => '$1 uploaded a video to $2 $3 ago',
	'userprofilepage-activity-delete'  => '$1 deleted the $2 page $3 ago',
	'userprofilepage-activity-talk'    => '$1 left a message on $2\'s talk page $3 ago',
	'userprofilepage-recent-activity-title' => '$1\'s recent activity',
	'userprofilepage-top-wikis-title' => '$1\'s Top wiki\'s',
	'userprofilepage-wiki-edits' => '$1 {{PLURAL:$1|edit|edits}}',
	'userprofilepage-users-notes-title' => '$1\'s notes',
	'userprofilepage-about-section-title' => 'About $1',
	'userprofilepage-edit-button' => 'edit',
	'userprofilepage-top-pages-section-title' => '$1\'s Top $2 pages',
	'userprofilepage-hidden-top-pages-section-title' => 'Hidden Top pages',
	'userprofilepage-top-page-edits' => '$1 edits',
	'userprofilepage-about-article-title' => 'About',
	'userprofilepage-about-empty-section' => 'This section is empty. Click edit to add some stuff here!',
	'recipes-template-aboutuser-fields' => '
* wpTitle
** type|input
** label|title
** hint|title
** hintHideable|true
** required|true
** readOnly|true
* wpDescription
** type|textarea
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
	'recipes-template-aboutuser-wikitext' => '{|
|-
| <<wpDescription>>
|-
! colspan="2" style="font-size:9pt; font-weight:bold;" align="left" | Biographical information
|-
| Born
| <<wpBirthDate>>
|}
{|
|-
! colspan="2" style="font-size:9pt; font-weight:bold;" | Physical description
! colspan="2" style="font-size:9pt; font-weight:bold;" | Family information
|-
| Species
| <<wpSpecies>>
| Abilities
| <<wpAbilities>>
|-
| Gender
| <<wpGender>>
| Affiliation
| <<wpAffiliation>>
|-
|}'
);
