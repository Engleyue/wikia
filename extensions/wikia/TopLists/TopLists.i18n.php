<?php
/**
 * TopLists extension message file
 */

$messages = array();

$messages['en'] = array(
	//info
	'toplists-desc' => 'Top 10 lists',

	//rights
	'right-toplists-create-edit-list' => 'Create and edit Top 10 list pages',
	'right-toplists-create-item' => 'Create and add items to a Top 10 list page',

	//special pages
	'createtoplist' => 'Create a new Top 10 list',
	'edittoplist' => 'Edit Top 10 list',

	//category
	'toplists-category' => 'Top 10 Lists',

	//errors
	'toplists-error-invalid-title' => 'The supplied text is not valid.',
	'toplists-error-invalid-picture' => 'The selected picture is not valid.',
	'toplists-error-title-exists' => 'This page already exists. You can go to <a href="$2" target="_blank">$1</a> or supply a different name.',
	'toplists-error-title-spam' => 'The supplied text contains some words recognized as spam.',
	'toplists-error-article-blocked' => 'You are not allowed to create a page with this name. Sorry.',
	'toplists-error-article-not-exists' => '"$1" does not exist. Do you want to <a href="$2" target="_blank">create it</a>?',
	'toplists-error-picture-not-exists' => '"$1" does not exist. Do you want to <a href="$2" target="_blank">upload it</a>?',
	'toplists-error-duplicated-entry' => 'You can\'t use the same name more than once.',
	'toplists-error-empty-item-name' => 'The name of an existing item can\'t be empty.',
	'toplists-item-cannot-delete' => 'Deletion of this item failed.',
	'toplists-error-image-already-exists' => 'An image with the same name already exists.',
	'toplists-error-add-item-anon' => 'Anonymous users are not allowed to add items to lists. Please <a class="ajaxLogin" id="login" href="$1">Log in</a> or <a class="ajaxLogin" id="signup" href="$2">register a new account</a>.',
	'toplists-error-add-item-permission' => 'Permission error: Your account has not been granted the right to create new items.',
	'toplists-error-add-item-list-not-exists' => 'The "$1" Top 10 list does not exist.',
	'toplists-error-backslash-not-allowed' => 'The "/" character is not allowed in the title of a Top 10 list.',
	'toplists-upload-error-unknown' => 'An error occurred while processing the upload request. Please try again.', 

	//editor
	'toplists-editor-title-label' => 'List name',
	'toplists-editor-title-placeholder' => 'Enter a name for the list',
	'toplists-editor-related-article-label' => 'Related page <small>(optional, but selects an image)</small>',
	'toplists-editor-related-article-placeholder' => 'Enter an existing page name',
	'toplists-editor-image-browser-tooltip' => 'Add a picture',
	'toplists-editor-remove-item-tooltip' => 'Remove item',
	'toplists-editor-drag-item-tooltip' => 'Drag to change order',
	'toplists-editor-add-item-label' => 'Add a new item',
	'toplists-editor-add-item-tooltip' => 'Add a new item to the list',
	'toplists-create-button' => 'Create list',
	'toplists-update-button' => 'Save list',
	'toplists-cancel-button' => 'Cancel',
	'toplists-items-removed' => '$1 {{PLURAL:$1|item|items}} removed',
	'toplists-items-created' => '$1 {{PLURAL:$1|item|items}} created',
	'toplists-items-updated' => '$1 {{PLURAL:$1|item|items}} updated',
	'toplists-items-nochange' => 'No items changed',

	//image browser/selector
	'toplits-image-browser-no-picture-selected' => 'No picture selected',
	'toplits-image-browser-clear-picture' => 'Clear picture',
	'toplits-image-browser-selected-picture' => 'Currently selected: $1',
	'toplists-image-browser-upload-btn' => 'Choose',
	'toplists-image-browser-upload-label' => 'Upload your own',

	//article edit summaries
	'toplists-list-creation-summary' => 'Creating a list, $1',
	'toplists-list-update-summary' => 'Updating a list, $1',
	'toplists-item-creation-summary' => 'Creating a list item',
	'toplists-item-update-summary' => 'Updating a list item',
	'toplists-item-remove-summary' => 'Item removed from list',
	'toplists-item-restored' => 'Item restored',

	//list view
	'toplists-list-related-to' => 'Related to:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />vote|$1<br />votes}}',
	'toplists-list-created-by' => 'by [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Vote up',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|vote|votes}} in $2',
	'toplists-list-add-item-label' => 'Add item',
	'toplists-list-add-item-name-label' => 'Keep the list going...',
	'toplists-list-item-voted' => 'Voted',

	//createpage dialog
	'toplists-createpage-dialog-label' => 'Top 10 list',

	//watchlist emails
	'toplists-email-subject' => 'A Top 10 list has been changed',
	'toplists-email-body' => "Hello from Wikia!

The list <a href=\"$1\">$2</a> on Wikia has been changed.

 $3

Head to Wikia to check out the changes! $1

- Wikia\n\nYou can <a href=\"$4\">unsubscribe</a> from changes to the list.",

	//time
	'toplists-seconds' => '$1 {{PLURAL:$1|second|seconds}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minute|minutes}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hour|hours}}',
	'toplists-days' => '$1 {{PLURAL:$1|day|days}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|week|weeks}}',

	//FB connect article vote message
	'toplists-msg-fb-OnRateArticle-link' => '$ARTICLENAME',
	'toplists-msg-fb-OnRateArticle-short' =>  'has voted on a Top 10 list on $WIKINAME!', // @todo FIXME: If possible add username as a variable here.
	'toplists-msg-fb-OnRateArticle' => '$TEXT',
	
	//Create list call to action
	'toplists-create-heading' => '<em>New!</em> Create Your Own Top Ten',
	'toplists-create-button-msg' => 'Create a list'
);

/** Message documentation (Message documentation) */
$messages['qqq'] = array(
	'toplists-category' => 'The name for the category that lists all the Top 10 Lists on a wiki',
	'toplits-image-browser-selected-picture' => '$1 is the title of the image page.',
);

/** Breton (Brezhoneg)
 * @author Fulup
 * @author Y-M D
 */
$messages['br'] = array(
	'toplists-desc' => 'Roll Top 10',
	'right-toplists-create-edit-list' => 'Krouiñ pe kemmañ pajennoù eus ar roll Top 10',
	'right-toplists-create-item' => "Krouiñ pe ouzhpennañ elfennoù d'ur bajenn eus roll an Top 10",
	'createtoplist' => 'Krouiñ ur roll Top 10 nevez',
	'edittoplist' => 'Kemmañ ur roll Top 10',
	'toplists-error-invalid-title' => "N'eo ket reizh an destenn pourchaset",
	'toplists-error-invalid-picture' => "N'eo ket reizh ar skeudenn diuzet.",
	'toplists-error-title-exists' => 'N\'eus ket eus ar bajenn-se. Gellout a rit mont da <a href="$2" target="_blank">$1</a> pe reiñ un anv disheñvel.',
	'toplists-error-title-spam' => 'En destenn pourchaset ez eus un nebeut gerioù anavezet evel strobus.',
	'toplists-error-article-blocked' => "Ho tigarez. N'oc'h ket aotreet da grouiñ ur bajenn nevez dezhi an anv-mañ.",
	'toplists-error-article-not-exists' => 'N\'eus ket eus ar pennad "$1". Ha fellout a ra deoc\'h <a href="$2" target="_blank">e grouiñ</a> ?',
	'toplists-error-picture-not-exists' => 'N\'eus ket eus ar restr "$1". Ha fellout a ra deoc\'h <a href="$2" target="_blank">hec\'h enporzhiañ</a> ?',
	'toplists-error-duplicated-entry' => "N'hallit ket obet gant an hevelep anv ouzhpenn ur wezh.",
	'toplists-error-empty-item-name' => "N'hall ket anv un elfenn bzeañ goullo.",
	'toplists-item-cannot-delete' => "C'hwitet eo bet diverkadenn an elfenn-mañ",
	'toplists-error-image-already-exists' => "Ur skeudenn dezhi an hevelep anv zo c'hoazh.",
	'toplists-error-add-item-anon' => 'N\'eo ket aotreet an implijerien dizanv da ouzhpennañ elfennoù d\'ar rolloù. <a class="ajaxLogin" id="login" href="$1">Kevreit</a> pe <a class="ajaxLogin" id="signup" href="$2">savit ur gont nevez</a>.',
	'toplists-error-add-item-permission' => "Fazi aotre : N'eo ket aotreet ho kont da grouiñ elfennoù nevez.",
	'toplists-error-add-item-list-not-exists' => 'N\'eus ket eus ar roll Top 10 "$1".',
	'toplists-error-backslash-not-allowed' => 'N\'eo ket aotreet an arouezenn "/" e titl ur roll Top 10.',
	'toplists-editor-title-label' => 'Anv ar roll',
	'toplists-editor-title-placeholder' => 'Roit un anv evit ar roll',
	'toplists-editor-related-article-label' => 'Pajenn kar',
	'toplists-editor-related-article-placeholder' => "Merkañ anv ur bajenn zo anezhi c'hoazh",
	'toplists-editor-image-browser-tooltip' => 'Ouzhpennañ ur skeudenn',
	'toplists-editor-remove-item-tooltip' => 'Tennañ an objed',
	'toplists-editor-drag-item-tooltip' => 'Lakait da riklañ evit cheñch an urzh',
	'toplists-editor-add-item-label' => 'Ouzhpennañ un elfenn nevez',
	'toplists-editor-add-item-tooltip' => "Ouzhpennañ un objed nevez d'ar roll",
	'toplists-create-button' => 'Krouiñ ur roll',
	'toplists-update-button' => 'Enrollañ ar roll',
	'toplists-cancel-button' => 'Nullañ',
	'toplists-items-removed' => '$1 {{PLURAL:$1|objed|objed}} dilamet',
	'toplists-items-created' => '$1 {{PLURAL:$1|objed|objed}} krouet',
	'toplists-items-updated' => '$1 {{PLURAL:$1|objed|objed}} hizivaet',
	'toplists-items-nochange' => "N'eus bet cheñchet elfenn ebet",
	'toplits-image-browser-no-picture-selected' => "N'eus skeudenn diuzet ebet",
	'toplits-image-browser-clear-picture' => 'Diverkañ ar skeudenn',
	'toplits-image-browser-selected-picture' => 'Skeudenn diuzet evit ar mare : $1',
	'toplists-image-browser-upload-btn' => 'Dibab',
	'toplists-image-browser-upload-label' => 'Enporzhiañ ho hini',
	'toplists-list-creation-summary' => 'O krouiñ ur roll, $1',
	'toplists-list-update-summary' => "Oc'h enporzhiañ ur roll, $1",
	'toplists-item-creation-summary' => 'O krouiñ ur roll elfennoù',
	'toplists-item-update-summary' => 'O hizivaat ur roll elfennoù',
	'toplists-item-remove-summary' => 'Objed dilamet eus ar roll',
	'toplists-item-restored' => 'Elfenn assavet',
	'toplists-list-related-to' => 'Liammet ouzh :',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />mouezh|$1<br />mouezh}}',
	'toplists-list-created-by' => 'gant [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Votiñ a-du',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|vot|vot}} e $2',
	'toplists-list-add-item-label' => 'Ouzhpennañ un elfenn',
	'toplists-list-add-item-name-label' => 'Lezel ar roll da vont...',
	'toplists-list-item-voted' => 'Votet',
	'toplists-createpage-dialog-label' => 'Roll Top 10',
	'toplists-email-subject' => 'Kemmet ez eus bet ur roll Top 10',
	'toplists-email-body' => 'Demat a-berzh Wikia !

Kemmet eo bet ar roll <a href="$1">$2</a> war Wikia.

 $3

Emgav war Wikia evit gwiriekaat ar c\'hemmoù ! $1

- Wikia

Gellout a rit <a href="$4">paouez da resevout</a> kemmoù ar roll-mañ.',
	'toplists-seconds' => '$1 {{PLURAL:$1|eilenn|eilenn}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|munut|munut}}',
	'toplists-hours' => '$1 {{PLURAL:$1|eur|eur}}',
	'toplists-days' => '$1 {{PLURAL:$1|deiz|deiz}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|sizhun|sizhun}}',
	'toplists-msg-fb-OnRateArticle-short' => 'en deus votet war ur roll Top 10 list war $WIKINAME !',
);

/** German (Deutsch)
 * @author Avatar
 */
$messages['de'] = array(
	'toplists-desc' => 'Top 10 Listen',
	'right-toplists-create-edit-list' => 'Erstelle und bearbeite Top 10 Listen',
	'right-toplists-create-item' => 'Erstelle und füge Einträge zu einer Top 10 Liste hinzu',
	'createtoplist' => 'Erstelle eine neue Top 10 Liste',
	'edittoplist' => 'Top 10 Liste bearbeiten',
	'toplists-category' => 'Top 10 Listen',
	'toplists-error-invalid-title' => 'Der angegebene Text ist nicht zulässig.',
	'toplists-error-invalid-picture' => 'Das gewählte Bild ist nicht zulässig.',
	'toplists-error-title-exists' => 'Diese Seite existiert bereits. Du kannst zu <a href="$2" target="_blank">$1</a> wechseln oder einen anderen Namen angeben.',
	'toplists-error-title-spam' => 'Der angegebene Text enthält Wörter, die als Spam erkannt wurden.',
	'toplists-error-article-blocked' => 'Du kannst keine Seite mit diesem Namen erzeugen, sorry.',
	'toplists-error-article-not-exists' => '"$1" existiert nicht. Möchtest du diesen <a href="$2" target="_blank">Eintrag erstellen</a>?',
	'toplists-error-picture-not-exists' => '"$1" existiert nicht. Möchtest du diese <a href="$2" target="_blank">Datei hochladen</a>?',
	'toplists-error-duplicated-entry' => 'Du kannst den gleichen Namen nicht mehr als einmal benutzen.',
	'toplists-error-empty-item-name' => 'Der Name eines existierenden Eintrags darf nicht leer sein.',
	'toplists-item-cannot-delete' => 'Die Löschung dieses Eintrags ist fehlgeschlagen.',
	'toplists-error-image-already-exists' => 'Es existiert bereits ein Bild mit diesem Namen.',
	'toplists-error-add-item-anon' => 'Nicht-angemeldete Benutzer dürfen keine Einträge zu Listen hinzufügen. Bitte <a class="ajaxLogin" id="login" href="$1">melde dich an</a> oder <a class="ajaxLogin" id="signup" href="$2">erstelle ein neues Benutzerkonto</a>.',
	'toplists-error-add-item-permission' => 'Keine ausreichenden Rechte: Mit deinem Benutzerkonto kannst du keine neuen Einträge erstellen.',
	'toplists-error-add-item-list-not-exists' => 'Die Top 10 Liste "$1" existiert nicht.',
	'toplists-error-backslash-not-allowed' => 'Das Zeichen "/" ist im Titel von Top 10 Listen nicht erlaubt.',
	'toplists-editor-title-label' => 'Name der Liste',
	'toplists-editor-title-placeholder' => 'Gib der Liste einen Namen',
	'toplists-editor-related-article-label' => 'Verwandte Seite <small>(optional, aber wählt ein Bild)</small>',
	'toplists-editor-related-article-placeholder' => 'Gib den Namen einer bestehenden Seite an',
	'toplists-editor-image-browser-tooltip' => 'Füge ein Bild hinzu',
	'toplists-editor-remove-item-tooltip' => 'Eintrag entfernen',
	'toplists-editor-drag-item-tooltip' => 'Klicken und ziehen um die Reihenfolge zu ändern',
	'toplists-editor-add-item-label' => 'Neuen Eintrag hinzufügen',
	'toplists-editor-add-item-tooltip' => 'Füge einen neuen Eintrag zur Liste hinzu',
	'toplists-create-button' => 'Liste erstellen',
	'toplists-update-button' => 'Liste speichern',
	'toplists-cancel-button' => 'Abbrechen',
	'toplists-items-removed' => '$1 {{PLURAL:$1|Eintrag|Einträge}} entfernt',
	'toplists-items-created' => '$1 {{PLURAL:$1|Eintrag|Einträge}} erstellt',
	'toplists-items-updated' => '$1 {{PLURAL:$1|Eintrag|Einträge}} aktualisiert',
	'toplists-items-nochange' => 'Keine Einträge geändert',
	'toplits-image-browser-no-picture-selected' => 'Kein Bild ausgewählt',
	'toplits-image-browser-clear-picture' => 'Bild entfernen',
	'toplits-image-browser-selected-picture' => 'Aktuell ausgewählt: $1',
	'toplists-image-browser-upload-btn' => 'Wähle',
	'toplists-image-browser-upload-label' => 'Eigenes Bild hochladen',
	'toplists-list-creation-summary' => 'Erstelle eine Liste, $1',
	'toplists-list-update-summary' => 'Aktualisiere eine Liste, $1',
	'toplists-item-creation-summary' => 'Erstelle einen Listen-Eintrag',
	'toplists-item-update-summary' => 'Aktualisiere einen Listen-Eintrag',
	'toplists-item-remove-summary' => 'Eintrag aus Liste entfernt',
	'toplists-item-restored' => 'Eintrag wiederhergestellt',
	'toplists-list-related-to' => 'Verwandt zu:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />Stimme|$1<br />Stimmen}}',
	'toplists-list-created-by' => 'von [[User:$1|$1]]',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|Stimme|Stimmen}} in $2',
	'toplists-list-add-item-label' => 'Eintrag hinzufügen',
	'toplists-list-item-voted' => 'Abgestimmt',
	'toplists-createpage-dialog-label' => 'Top 10 Liste',
	'toplists-email-subject' => 'Eine Top 10 Liste wurde geändert',
	'toplists-email-body' => 'Wikia sagt Hallo!

Die Liste <a href="$1">$2</a> in Wikia wurde geändert.

 $3

Besuche Wikia um dir die Änderungen anzusehen! $1

- Wikia

Du kannst die Änderungsbenachrichtigungen zu dieser Liste <a href="$4">abbestellen</a>.',
	'toplists-seconds' => '$1 {{PLURAL:$1|Sekunde|Sekunden}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|Minute|Minuten}}',
	'toplists-hours' => '$1 {{PLURAL:$1|Stunde|Stunden}}',
	'toplists-days' => '$1 {{PLURAL:$1|Tag|Tage}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|Woche|Wochen}}',
	'toplists-msg-fb-OnRateArticle-short' => 'hat bei einer Top 10 Liste abgestimmt ($WIKINAME)!',
);

/** Spanish (Español)
 * @author Peter17
 */
$messages['es'] = array(
	'toplists-cancel-button' => 'Cancelar',
	'toplists-list-add-item-label' => 'Añadir elemento',
	'toplists-seconds' => '$1 {{PLURAL:$1|segundo|segundos}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hora|horas}}',
	'toplists-days' => '$1 {{PLURAL:$1|dia|dias}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|semana|semanas}}',
);

/** Finnish (Suomi)
 * @author Centerlink
 * @author Nike
 */
$messages['fi'] = array(
	'toplists-desc' => 'Top 10 -luettelot',
	'right-toplists-create-edit-list' => 'Luo ja muokkaa Top 10 -luettelosivuja',
	'right-toplists-create-item' => 'Luo ja lisää kohteita Top 10 -luettelosivulle',
	'createtoplist' => 'Luo uusi Top 10 -luettelo',
	'edittoplist' => 'Muokkaa Top 10 -luetteloa',
	'toplists-category' => 'Top 10 -luettelot',
	'toplists-error-invalid-title' => 'Toimitettu teksti ei kelpaa.',
	'toplists-error-invalid-picture' => 'Valittu kuva ei kelpaa.',
	'toplists-error-title-exists' => 'Tämä sivu on jo olemassa. Voit siirtyä kohteeseen <a href="$2" target="_blank">$1</a> tai tarjota eri nimen.',
	'toplists-error-title-spam' => 'Tarjottu teksti sisältää joitakin sanoja, jotka on tunnistettu roskapostina.',
	'toplists-error-article-blocked' => 'Et voi valitettavasti luoda tämän nimistä sivu.',
	'toplists-error-article-not-exists' => '"$1" ei ole olemassa. Haluatko <a href="$2" target="_blank">luoda sen</a>?',
	'toplists-error-picture-not-exists' => '"$1" ei ole olemassa. Haluatko <a href="$2" target="_blank">kopioida sen palvelimelle</a>?',
	'toplists-error-duplicated-entry' => 'Et voi käyttää samaa nimeä kuin kerran.',
	'toplists-error-empty-item-name' => 'Olemassaolevan kohteen nimi ei voi olla tyhjä.',
	'toplists-item-cannot-delete' => 'Tämän kohteen poistaminen epäonnistui.',
	'toplists-error-image-already-exists' => 'Samanniminen kuva on jo olemassa.',
	'toplists-error-add-item-anon' => 'Anonyymit käyttäjät eivät voi lisätä kohteita luetteloihin. Ole hyvä ja <a class="ajaxLogin" id="login" href="$1">Kirjaudu sisään</a> tai <a class="ajaxLogin" id="signup" href="$2">rekisteröi uusi tili</a>.',
	'toplists-error-add-item-permission' => 'Käyttöoikeusvirhe: Tilille ei ole myönnetty oikeutta luoda uusia kohteita.',
	'toplists-error-add-item-list-not-exists' => '"$1" Top 10 -luetteloa ei ole olemassa.',
	'toplists-error-backslash-not-allowed' => '"/"-merkki ei ole sallittu Top 10 -luettelon otsikossa.',
	'toplists-editor-title-label' => 'Luettelonimi',
	'toplists-editor-title-placeholder' => 'Kirjoita luettelon nimi',
	'toplists-editor-related-article-label' => 'Liittyvä sivu <small>(valinnainen, mutta valitsee kuvan)</small>',
	'toplists-editor-related-article-placeholder' => 'Anna olemassa olevan sivun nimi',
	'toplists-editor-image-browser-tooltip' => 'Lisää kuva',
	'toplists-editor-remove-item-tooltip' => 'Poista kohde',
	'toplists-editor-drag-item-tooltip' => 'Voit muuttaa järjestystä vetämällä',
	'toplists-editor-add-item-label' => 'Lisää uusi kohde',
	'toplists-editor-add-item-tooltip' => 'Lisää uusi kohde luetteloon',
	'toplists-create-button' => 'Luo luettelo',
	'toplists-update-button' => 'Tallenna luettelo',
	'toplists-cancel-button' => 'Peruuta',
	'toplists-items-removed' => '$1 {{PLURAL:$1|kohde|kohdetta}} poistettu',
	'toplists-items-created' => '$1 {{PLURAL:$1|kohde|kohdetta}} luotu',
	'toplists-items-updated' => '$1 {{PLURAL:$1|kohde|kohdetta}} päivitetty',
	'toplists-items-nochange' => 'Ei muuttuneita kohteita',
	'toplits-image-browser-no-picture-selected' => 'Ei kuvaa valittuna',
	'toplits-image-browser-clear-picture' => 'Tyhjennä kuva',
	'toplits-image-browser-selected-picture' => 'Tällä hetkellä valittuna: $1',
	'toplists-image-browser-upload-btn' => 'Valitse',
	'toplists-image-browser-upload-label' => 'Lähetä oma',
	'toplists-list-creation-summary' => 'Luodaan luetteloa, $1',
	'toplists-list-update-summary' => 'Päivitetään luetteloa, $1',
	'toplists-item-creation-summary' => 'Luodaan luettelokohde',
	'toplists-item-update-summary' => 'Päivitetään luettelokohde',
	'toplists-item-remove-summary' => 'Kohde poistettu luettelosta',
	'toplists-item-restored' => 'Kohde palautettu',
	'toplists-list-related-to' => 'Liittyy kohteeseen:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />ääni|$1<br />ääntä}}',
	'toplists-list-created-by' => ': [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Kannatusääni',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|ääni|ääntä}} kohteessa $2',
	'toplists-list-add-item-label' => 'Lisää kohde',
	'toplists-list-add-item-name-label' => 'Pidä luettelo käynnissä...',
	'toplists-list-item-voted' => 'Äänestetty',
	'toplists-createpage-dialog-label' => 'Top10-lista',
	'toplists-email-subject' => 'Top10-lista on muuttunut',
	'toplists-email-body' => 'Hei Wikiasta!

Luettelo <a href="$1">$2</a> Wikiassa on muuttunut.

 $3

Suuntaa Wikiaan muutosten tarkistamiseksi! $1

- Wikia

Voit <a href="$4">perua päivitykset</a> luettelon muutoksista.',
	'toplists-seconds' => '$1 {{PLURAL:$1|sekunti|sekuntia}} sitten',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuutti|minuuttia}} sitten',
	'toplists-hours' => '$1 {{PLURAL:$1|tunti|tuntia}} sitten',
	'toplists-days' => '$1 {{PLURAL:$1|päivä|päivää}} sitten',
	'toplists-weeks' => '$1 {{PLURAL:$1|viikko|viikkoa}} sitten',
	'toplists-msg-fb-OnRateArticle-short' => 'on äänestänyt Top10-listaa wikissä $WIKINAME!',
);

/** French (Français)
 * @author Jean-Frédéric
 * @author Peter17
 * @author Verdy p
 */
$messages['fr'] = array(
	'toplists-desc' => 'Listes Top 10',
	'right-toplists-create-edit-list' => 'Créer et modifier des pages de liste Top 10',
	'right-toplists-create-item' => 'Créer et ajouter des éléments à une page de liste Top 10',
	'createtoplist' => 'Créer une nouvelle liste Top 10',
	'edittoplist' => 'Modifier une liste Top 10',
	'toplists-category' => 'Top 10 des listes',
	'toplists-error-invalid-title' => 'Le texte fourni n’est pas valide.',
	'toplists-error-invalid-picture' => 'L’image sélectionnée n’est pas valide.',
	'toplists-error-title-exists' => 'Cette page existe déjà. Vous pouvez aller à <a href="$2" target="_blank">$1</a> ou fournir un nom différent.',
	'toplists-error-title-spam' => 'Le texte fourni contient quelques mots reconnus comme indésirables.',
	'toplists-error-article-blocked' => 'Vous n’êtes pas autorisé à créer une page avec ce nom. Désolé.',
	'toplists-error-article-not-exists' => 'L’article « $1 » n’existe pas. Voulez-vous <a href="$2" target="_blank">le créer</a> ?',
	'toplists-error-picture-not-exists' => 'Le fichier « $1 » n’existe pas. Voulez-vous <a href="$2" target="_blank">le téléverser</a> ?',
	'toplists-error-duplicated-entry' => 'Vous ne pouvez pas utiliser le même nom plus d’une fois.',
	'toplists-error-empty-item-name' => 'Le nom d’un élément existant ne peut pas être vide.',
	'toplists-item-cannot-delete' => 'La suppression de cet élément a échoué.',
	'toplists-error-image-already-exists' => 'Une image avec le même nom existe déjà.',
	'toplists-error-add-item-anon' => 'Les utilisateurs anonymes ne sont pas autorisés à ajouter des éléments aux listes. Veuillez <a class="ajaxLogin" id="login" href="$1">vous connecter</a> ou <a class="ajaxLogin" id="signup" href="$2">vous inscrire avec un nouveau compte</a> .',
	'toplists-error-add-item-permission' => 'Erreur d’autorisation : le droit de créer de nouveaux éléments n’a pas été accordé à votre compte.',
	'toplists-error-add-item-list-not-exists' => 'La liste Top 10 « $1 » n’existe pas.',
	'toplists-error-backslash-not-allowed' => 'Le caractère « / » n’est pas autorisé dans le titre d’une liste Top 10.',
	'toplists-editor-title-label' => 'Nom de liste',
	'toplists-editor-title-placeholder' => 'Entrez un nom pour la liste',
	'toplists-editor-related-article-label' => 'Page connexe <small>(optionnel, mais définit une image)</small>',
	'toplists-editor-related-article-placeholder' => 'Entrez un nom de page existante',
	'toplists-editor-image-browser-tooltip' => 'Ajouter une image',
	'toplists-editor-remove-item-tooltip' => 'Retirer l’élément',
	'toplists-editor-drag-item-tooltip' => 'Faites glisser pour changer l’ordre',
	'toplists-editor-add-item-label' => 'Ajouter un nouvel élément',
	'toplists-editor-add-item-tooltip' => 'Ajouter un nouvel élément à la liste',
	'toplists-create-button' => 'Créer une liste',
	'toplists-update-button' => 'Sauvegarder la liste',
	'toplists-cancel-button' => 'Annuler',
	'toplists-items-removed' => '{{PLURAL:$1|Un élément supprimé|$1 éléments supprimés}}',
	'toplists-items-created' => '{{PLURAL:$1|Un élément créé|$1 éléments créés}}',
	'toplists-items-updated' => '{{PLURAL:$1|Un élément|$1 éléments}} mis à jour',
	'toplists-items-nochange' => 'Aucun élément modifié',
	'toplits-image-browser-no-picture-selected' => 'Aucune image sélectionnée',
	'toplits-image-browser-clear-picture' => 'Effacer l’image',
	'toplits-image-browser-selected-picture' => 'Image actuellement sélectionnée : $1',
	'toplists-image-browser-upload-btn' => 'Choisir',
	'toplists-image-browser-upload-label' => 'Téléversez la vôtre',
	'toplists-list-creation-summary' => 'Création d’une liste, $1',
	'toplists-list-update-summary' => 'Mise à jour d’une liste, $1',
	'toplists-item-creation-summary' => 'Création d’un élément de liste',
	'toplists-item-update-summary' => 'Mise à jour d’un élément de liste',
	'toplists-item-remove-summary' => 'Élément retiré de la liste',
	'toplists-item-restored' => 'Élément restauré',
	'toplists-list-related-to' => 'Relatif à :',
	'toplists-list-votes-num' => '{{PLURAL:$1|un<br />vote|$1<br />votes}}',
	'toplists-list-created-by' => 'par [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Voter pour',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|vote|votes}} en $2',
	'toplists-list-add-item-label' => 'Ajouter un élément',
	'toplists-list-add-item-name-label' => 'Laisser aller la liste...',
	'toplists-list-item-voted' => 'Voté',
	'toplists-createpage-dialog-label' => 'Liste Top 10',
	'toplists-email-subject' => 'Une liste Top 10 a été modifiée',
	'toplists-email-body' => 'Bonjour de Wikia !

La liste <a href="$1">$2</a> sur Wikia a été modifiée.

 $3

Rendez-vous sur Wikia pour vérifier les modifications ! $1

- Wikia

Vous pouvez <a href="$4">vous désinscrire</a> des modifications de cette liste.',
	'toplists-seconds' => '$1 seconde{{PLURAL:$1||s}}',
	'toplists-minutes' => '$1 minute{{PLURAL:$1||s}}',
	'toplists-hours' => '$1 heure{{PLURAL:$1||s}}',
	'toplists-days' => '$1 jour{{PLURAL:$1||s}}',
	'toplists-weeks' => '$1 semaine{{PLURAL:$1||s}}',
	'toplists-msg-fb-OnRateArticle-short' => 'a voté sur une liste du Top 10 sur $WIKINAME !',
	'toplists-create-heading' => '<em>Nouveau !</em> Créez votre propre top dix',
	'toplists-create-button-msg' => 'Créer une liste',
);

/** Galician (Galego)
 * @author Toliño
 * @author Xanocebreiro
 */
$messages['gl'] = array(
	'toplists-editor-title-label' => 'Nome de lista',
	'toplists-editor-related-article-label' => 'Páxina relacionada <small>(opcional, pero selecciona unha imaxe)</small>',
	'toplists-editor-related-article-placeholder' => 'Introduza un nome de páxina existente',
	'toplists-editor-image-browser-tooltip' => 'Engadir unha imaxe',
	'toplists-editor-remove-item-tooltip' => 'Eliminar o elemento',
	'toplists-editor-add-item-label' => 'Engadir un elemento novo',
	'toplists-create-button' => 'Crear unha lista',
	'toplists-update-button' => 'Gardar a lista',
	'toplists-cancel-button' => 'Cancelar',
	'toplists-image-browser-upload-label' => 'Cargar a súa',
	'toplists-list-related-to' => 'Relacionado con:',
	'toplists-seconds' => '$1 {{PLURAL:$1|segundo|segundos}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hora|horas}}',
	'toplists-days' => '$1 {{PLURAL:$1|día|días}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|semana|semanas}}',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'toplists-desc' => 'Listas Top 10',
	'right-toplists-create-edit-list' => 'Crear e modificar paginas de lista Top 10',
	'right-toplists-create-item' => 'Crear e adder elementos a un pagina de lista Top 10',
	'createtoplist' => 'Crear un nove lista Top 10',
	'edittoplist' => 'Modificar lista Top 10',
	'toplists-category' => 'Listas Top 10',
	'toplists-error-invalid-title' => 'Le texto fornite non es valide.',
	'toplists-error-invalid-picture' => 'Le imagine seligite non es valide.',
	'toplists-error-title-exists' => 'Iste pagina existe jam. Tu pote vader a <a href="$2" target="_blank">$1</a> o fornir un altere nomine.',
	'toplists-error-title-spam' => 'Le texto fornite contine alcun parolas recognoscite como spam.',
	'toplists-error-article-blocked' => 'Regrettabilemente, il non es permittite crear un pagina con iste nomine.',
	'toplists-error-article-not-exists' => '"$1" non existe. Vole tu <a href="$2" target="_blank">crear lo</a>?',
	'toplists-error-picture-not-exists' => '"$1" non existe. Vole tu <a href="$2" target="_blank">incargar lo</a>?',
	'toplists-error-duplicated-entry' => 'Tu non pote usar le mesme nomine plus de un vice.',
	'toplists-error-empty-item-name' => 'Le nomine de un elemento existente non pote esser vacue.',
	'toplists-item-cannot-delete' => 'Le deletion de iste elemento ha fallite.',
	'toplists-error-image-already-exists' => 'Un imagine con le mesme nomine jam existe.',
	'toplists-error-add-item-anon' => 'Usatores anonyme non ha le permission de adder elementos a listas. Per favor <a class="ajaxLogin" id="login" href="$1">aperi session</a> o <a class="ajaxLogin" id="signup" href="$2">crea un nove conto</a>.',
	'toplists-error-add-item-permission' => 'Error de permission: Tu conto non ha le derecto de crear nove elementos.',
	'toplists-error-add-item-list-not-exists' => 'Le lista Top 10 "$1" non existe.',
	'toplists-error-backslash-not-allowed' => 'Le character "/" non es permittite in le titulo de un lista Top 10.',
	'toplists-editor-title-label' => 'Nomine del lista',
	'toplists-editor-title-placeholder' => 'Entra un nomine pro le lista',
	'toplists-editor-related-article-label' => 'Pagina connexe <small>(optional, ma selige un imagine)</small>',
	'toplists-editor-related-article-placeholder' => 'Entra le nomine de un pagina existente',
	'toplists-editor-image-browser-tooltip' => 'Adder un imagine',
	'toplists-editor-remove-item-tooltip' => 'Remover elemento',
	'toplists-editor-drag-item-tooltip' => 'Trahe pro cambiar le ordine',
	'toplists-editor-add-item-label' => 'Adder un nove elemento',
	'toplists-editor-add-item-tooltip' => 'Adder un nove elemento al lista',
	'toplists-create-button' => 'Crear lista',
	'toplists-update-button' => 'Salveguardar lista',
	'toplists-cancel-button' => 'Cancellar',
	'toplists-items-removed' => '$1 {{PLURAL:$1|elemento|elementos}} removite',
	'toplists-items-created' => '$1 {{PLURAL:$1|elemento|elementos}} create',
	'toplists-items-updated' => '$1 {{PLURAL:$1|elemento|elementos}} actualisate',
	'toplists-items-nochange' => 'Nulle elemento cambiate',
	'toplits-image-browser-no-picture-selected' => 'Nulle imagine seligite',
	'toplits-image-browser-clear-picture' => 'Rader imagine',
	'toplits-image-browser-selected-picture' => 'Actualmente seligite: $1',
	'toplists-image-browser-upload-btn' => 'Seliger',
	'toplists-image-browser-upload-label' => 'Incargar un proprie',
	'toplists-list-creation-summary' => 'Crea un lista, $1',
	'toplists-list-update-summary' => 'Actualisa un lista, $1',
	'toplists-item-creation-summary' => 'Crea un elemento de lista',
	'toplists-item-update-summary' => 'Actualisa un elemento de lista',
	'toplists-item-remove-summary' => 'Elemento removite del lista',
	'toplists-item-restored' => 'Elemento restaurate',
	'toplists-list-related-to' => 'Connexe a:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />voto|$1<br />votos}}',
	'toplists-list-created-by' => 'per [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Votar positivemente',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|voto|votos}} in $2',
	'toplists-list-add-item-label' => 'Adder elemento',
	'toplists-list-add-item-name-label' => 'Mantener le lista in marcha...',
	'toplists-list-item-voted' => 'Votate',
	'toplists-createpage-dialog-label' => 'Lista Top 10',
	'toplists-email-subject' => 'Un lista Top 10 ha essite cambiate',
	'toplists-email-body' => 'Salute de Wikia!

Le lista <a href="$1">$2</a> in Wikia ha cambiate.

 $3

Veni a Wikia pro examinar le cambios! $1

- Wikia

Tu pote <a href="$4">cancellar le subscription</a> al cambios in iste lista.',
	'toplists-seconds' => '$1 {{PLURAL:$1|secunda|secundas}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuta|minutas}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hora|horas}}',
	'toplists-days' => '$1 {{PLURAL:$1|die|dies}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|septimana|septimanas}}',
	'toplists-msg-fb-OnRateArticle-short' => 'ha votate in un lista Top 10 in $WIKINAME!',
	'toplists-create-heading' => '<em>Nove!</em> Crea tu proprie top dece',
	'toplists-create-button-msg' => 'Crear un lista',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'toplists-desc' => 'Списоци на 10 најкотирани',
	'right-toplists-create-edit-list' => 'Создајте или уредете статии на списокот на 10 најкотирани',
	'right-toplists-create-item' => 'Создавајте и додавајте ставки на список на 10 најкотирани',
	'createtoplist' => 'Создај нов список на 10 најкотирани',
	'edittoplist' => 'Уреди список на 10 најкотирани',
	'toplists-category' => 'Списоци на 10 најкотирани',
	'toplists-error-invalid-title' => 'Дадениот текст е неважечки',
	'toplists-error-invalid-picture' => 'Одбраната слика не е важечка',
	'toplists-error-title-exists' => 'Статијава веќе постои. Можете да појдете на <a href="$2" target="_blank">$1</a> или да дадете друго име',
	'toplists-error-title-spam' => 'Дадениот текст содржи извесни зборови што се сметаат за спам',
	'toplists-error-article-blocked' => 'Нажалост, не ви е дозволено да создадете статија со ова име',
	'toplists-error-article-not-exists' => '„$1“ не постои., Дали сакате да ја <a href="$2" target="_blank">создадете</a>?',
	'toplists-error-picture-not-exists' => '„$1“ не постои. Дали сакате да ја <a href="$2" target="_blank">подигнете</a>?',
	'toplists-error-duplicated-entry' => 'Истото име не може да се користи повеќе од еднаш',
	'toplists-error-empty-item-name' => 'Името на постоечка ставка не може да стои празно',
	'toplists-item-cannot-delete' => 'Бришењето на ставката не успеа',
	'toplists-error-image-already-exists' => 'ВБеќе постои слика со истото име',
	'toplists-error-add-item-anon' => 'Анонимните корисници не можат да додаваат ставки на списокот. <a class="ajaxLogin" id="login" href="$1">Најавете се</a> или <a class="ajaxLogin" id="signup" href="$2">регистрирајте сметка</a>.',
	'toplists-error-add-item-permission' => 'Грешка во дозволите. Вашата сметка нема добиено право за создавање на нови ставки.',
	'toplists-error-add-item-list-not-exists' => 'Не постои список на 10 најкотирани со наслов „$1“.',
	'toplists-error-backslash-not-allowed' => 'Знакот „/“ не е дозволен во наслов на список на 10 најкотирани.',
	'toplists-editor-title-label' => 'Презиме',
	'toplists-editor-title-placeholder' => 'Внесете име на списокот',
	'toplists-editor-related-article-label' => 'Поврзана страница <small>(по избор, но одбира слика)</small>',
	'toplists-editor-related-article-placeholder' => 'Внесете име на постоечка статија',
	'toplists-editor-image-browser-tooltip' => 'Додај слика',
	'toplists-editor-remove-item-tooltip' => 'Отстрани ставка',
	'toplists-editor-drag-item-tooltip' => 'Влечете за промена на редоследот',
	'toplists-editor-add-item-label' => 'Додај нова ставка',
	'toplists-editor-add-item-tooltip' => 'Додај нова ставка во списокот',
	'toplists-create-button' => 'Создај список',
	'toplists-update-button' => 'Зачувај список',
	'toplists-cancel-button' => 'Откажи',
	'toplists-items-removed' => '{{PLURAL:$1|Отстранета е $1 ставка|Отстранети се $1 ставки}}',
	'toplists-items-created' => '{{PLURAL:$1|Создадена е $1 ставка|Создадени се $1 ставки}}',
	'toplists-items-updated' => '{{PLURAL:$1|Подновена е $1 ставка|Подновени се $1 ставки}}',
	'toplists-items-nochange' => 'Нема изменети ставки',
	'toplits-image-browser-no-picture-selected' => 'Нема одбрано слика',
	'toplits-image-browser-clear-picture' => 'Исчисти слика',
	'toplits-image-browser-selected-picture' => 'Моментално одбрана: $1',
	'toplists-image-browser-upload-btn' => 'Одбери',
	'toplists-image-browser-upload-label' => 'Подигнете своја',
	'toplists-list-creation-summary' => 'Создавање на спиок, $1',
	'toplists-list-update-summary' => 'Поднова на список, $1',
	'toplists-item-creation-summary' => 'Создавање на ставка во список',
	'toplists-item-update-summary' => 'Поднова на ставка во список',
	'toplists-item-remove-summary' => 'Отстранета ставка од список',
	'toplists-item-restored' => 'Ставката е повратена',
	'toplists-list-related-to' => 'Поврзано со:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br/ >глас|$1<br/ >гласа}}',
	'toplists-list-created-by' => 'од [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Глас нагоре',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|глас|гласа}} in $2',
	'toplists-list-add-item-label' => 'Додај ставка',
	'toplists-list-add-item-name-label' => 'Продолжете го списокот...',
	'toplists-list-item-voted' => 'Гласано',
	'toplists-createpage-dialog-label' => 'Список на 10 најкотирани',
	'toplists-email-subject' => 'Списокот на 10 најкотирани е изменет',
	'toplists-email-body' => 'Здраво од Викија!

Списокот <a href="$1">$2</a> на Викија е променет.

 $3

Појдете на Викија за да видите што се изменило! $1

- Викија

Можете да се <a href="$4">отпишете</a> од ваквите известувања за промени на списокот.',
	'toplists-seconds' => '$1 {{PLURAL:$1|секунда|секунди}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|минута|минути}}',
	'toplists-hours' => '$1 {{PLURAL:$1|час|часа}}',
	'toplists-days' => '$1 {{PLURAL:$1|ден|дена}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|недела|недели}}',
	'toplists-msg-fb-OnRateArticle-short' => 'гласаше на списокот на 10 најкотирани на $WIKINAME!',
	'toplists-create-heading' => '<em>Ново!</em> Создајте свои „10 најкотирани“',
	'toplists-create-button-msg' => 'Создај список',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'toplists-desc' => 'Top 10 lijsten',
	'right-toplists-create-edit-list' => 'Top 10 lijsten aanmaken en bewerken',
	'right-toplists-create-item' => 'Items aanmaken en toevoegen aan Top 10 lijsten',
	'createtoplist' => 'Nieuwe Top 10 lijst aanmaken',
	'edittoplist' => 'Top 10 lijst beweken',
	'toplists-category' => 'Top 10 lijsten',
	'toplists-error-invalid-title' => 'De opgegeven tekst wordt niet opgeslagen.',
	'toplists-error-invalid-picture' => 'De geselecteerde afbeelding is niet geldig.',
	'toplists-error-title-exists' => 'Deze pagina bestaat al. U kunt naar <a href="$2" target="_blank">$1</a> gaan of een andere naam opgeven.',
	'toplists-error-title-spam' => 'De opgegeven tekst bevat woorden die zijn herkend als spam.',
	'toplists-error-article-blocked' => 'Een pagina aanmaken met deze naam is helaas niet toegestaan.',
	'toplists-error-article-not-exists' => '"$1" bestaat niet. Wilt u deze <a href="$2" target="_blank">aanmaken</a>?',
	'toplists-error-picture-not-exists' => '"$1" bestaat niet. Wilt u het bestand <a href="$2" target="_blank">uploaden</a>?',
	'toplists-error-duplicated-entry' => 'U kunt dezelfde naam niet opnieuw gebruiken.',
	'toplists-error-empty-item-name' => 'De naam van een bestaand item kan niet leeg zijn.',
	'toplists-item-cannot-delete' => 'Het verwijderen van dit item is mislukt.',
	'toplists-error-image-already-exists' => 'Er bestaat al een afbeelding met die naam.',
	'toplists-error-add-item-anon' => 'Anonieme gebruikers mogen geen items toevoegen aan lijsten. <a class="ajaxLogin" id="login" href="$1">Meld u aan</a> of <a class="ajaxLogin" id="signup" href="$2">registreer een nieuwe gebruiker</a>.',
	'toplists-error-add-item-permission' => 'Rechtenprobleem: uw gebruiker heeft geen rechten om nieuwe items aan te maken.',
	'toplists-error-add-item-list-not-exists' => 'De Top 10 lijst "$1" bestaat niet.',
	'toplists-error-backslash-not-allowed' => 'Het teken "/" mag niet gebruikt worden in de naam van een Top 10 lijst.',
	'toplists-editor-title-label' => 'Lijstnaam',
	'toplists-editor-title-placeholder' => 'Voer een naam in voor de lijst',
	'toplists-editor-related-article-label' => 'Gerelateerde pagina <small>(optioneel, maar selecteert een afbeelding)</small>',
	'toplists-editor-related-article-placeholder' => 'Voer een bestaande paginanaam in',
	'toplists-editor-image-browser-tooltip' => 'Afbeelding toevoegen',
	'toplists-editor-remove-item-tooltip' => 'Item verwijderen',
	'toplists-editor-drag-item-tooltip' => 'Sleep om de volgorde te wijzigen',
	'toplists-editor-add-item-label' => 'Nieuw item toevoegen',
	'toplists-editor-add-item-tooltip' => 'Nieuw item aan de lijst toevoegen',
	'toplists-create-button' => 'Lijst aanmaken',
	'toplists-update-button' => 'Lijst opslaan',
	'toplists-cancel-button' => 'Annuleren',
	'toplists-items-removed' => '$1 {{PLURAL:$1|item|items}} verwijderd',
	'toplists-items-created' => '$1 {{PLURAL:$1|item|items}} aangemaakt',
	'toplists-items-updated' => '$1 {{PLURAL:$1|item|items}} bijgewerkt',
	'toplists-items-nochange' => 'Geen items gewijzigd',
	'toplits-image-browser-no-picture-selected' => 'Geen afbeelding geselecteerd',
	'toplits-image-browser-clear-picture' => 'Afbeelding wissen',
	'toplits-image-browser-selected-picture' => 'Geselecteerd: $1',
	'toplists-image-browser-upload-btn' => 'Kiezen',
	'toplists-image-browser-upload-label' => 'Eigen uploaden',
	'toplists-list-creation-summary' => 'Lijst $1 aangemaakt',
	'toplists-list-update-summary' => 'Lijst $1 bijgewerkt',
	'toplists-item-creation-summary' => 'Lijstitem aangemaakt',
	'toplists-item-update-summary' => 'Lijstitem bijgewerkt',
	'toplists-item-remove-summary' => 'Lijstitem verwijderd',
	'toplists-item-restored' => 'Item teruggeplaatst',
	'toplists-list-related-to' => 'Gerelateerd aan:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />stem|$1<br />stemmen}}',
	'toplists-list-created-by' => 'door [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Positief beoordelen',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|stem|stemmen}} in $2',
	'toplists-list-add-item-label' => 'Item toevoegen',
	'toplists-list-add-item-name-label' => 'Houd de lijst gaande...',
	'toplists-list-item-voted' => 'Gestemd',
	'toplists-createpage-dialog-label' => 'Top 10 lijst',
	'toplists-email-subject' => 'Er is een Top 10 lijst gewijzigd',
	'toplists-email-body' => 'De hartelijke groeten van Wikia!

De lijst <a href="$1">$2</a> op Wikia is gewijzigd.

 $3

Ga naar Wikia om de wijzigingen te bekijken! $1

- Wikia

U kunt <a href="$4">uitschrijven</a> van wijzigingen op deze lijst.',
	'toplists-seconds' => '$1 {{PLURAL:$1|seconde|seconden}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuut|minuten}}',
	'toplists-hours' => '$1 {{PLURAL:$1|uur|uren}}',
	'toplists-days' => '$1 {{PLURAL:$1|dag|dagen}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|week|weken}}',
	'toplists-msg-fb-OnRateArticle-short' => 'heeft gestemd op een Top 10 lijst op $WIKINAME!',
	'toplists-create-heading' => '<em>Nieuw!</em> Maak uw eigen Top 10 aan',
	'toplists-create-button-msg' => 'Lijst aanmaken',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Audun
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'toplists-desc' => 'Topp-10-lister',
	'right-toplists-create-edit-list' => 'Opprett og rediger Topp-10-listesider.',
	'right-toplists-create-item' => 'Opprett og legg elementer til en Topp-10-listeside',
	'createtoplist' => 'Opprett en ny Topp-10-liste',
	'edittoplist' => 'Rediger Topp-10-liste',
	'toplists-category' => 'Topp-10-lister',
	'toplists-error-invalid-title' => 'Den oppgitte teksten er ikke gyldig.',
	'toplists-error-invalid-picture' => 'Det valgte bildet er ikke gyldig.',
	'toplists-error-title-exists' => 'Denne siden eksisterer allerede. Du kan gå til <a href="$2" target="_blank">$1</a> eller oppgi et annet navn.',
	'toplists-error-title-spam' => 'Den oppgitte teksten inneholder noen ord som gjenkjennes som spam.',
	'toplists-error-article-blocked' => 'Du har ikke tillatelse til å opprette en side med dette navnet. Beklager.',
	'toplists-error-article-not-exists' => '«$1» eksisterer ikke. Vil du <a href="$2" target="_blank">opprette den</a>?',
	'toplists-error-picture-not-exists' => '«$1» eksisterer ikke. Vil du <a href="$2" target="_blank">laste det opp</a>?',
	'toplists-error-duplicated-entry' => 'Du kan ikke bruke det samme navnet mer enn én gang.',
	'toplists-error-empty-item-name' => 'Navnet på et eksisterende element kan ikke være blankt.',
	'toplists-item-cannot-delete' => 'Sletting av dette elementet mislyktes.',
	'toplists-error-image-already-exists' => 'Et bilde med det samme navnet eksisterer allerede.',
	'toplists-error-add-item-anon' => 'Anonyme bukrere er ikke tillatt å legge til objekter i listene. Vennligst <a class="ajaxLogin" id="login" href="$1">Logg inn</a> eller <a class="ajaxLogin" id="signup" href="$2">registrer en ny konto</a>.',
	'toplists-error-add-item-permission' => 'Tillatelsesfeil: Kontoen din har ikke blitt gitt rettighetene til å opprette nye elementer.',
	'toplists-error-add-item-list-not-exists' => 'Topp-10-listen «$1» eksisterer ikke.',
	'toplists-error-backslash-not-allowed' => '«/»-tegnet er ikke tillatt i tittelen på en Topp-10-liste.',
	'toplists-editor-title-label' => 'Listenavn',
	'toplists-editor-title-placeholder' => 'Oppgi et navn til listen',
	'toplists-editor-related-article-label' => 'Relatert side <small>(valgfritt, men velger et bilde)</small>',
	'toplists-editor-related-article-placeholder' => 'Oppgi et navn på en eksisterende side',
	'toplists-editor-image-browser-tooltip' => 'Legg til et bilde',
	'toplists-editor-remove-item-tooltip' => 'Fjern element',
	'toplists-editor-drag-item-tooltip' => 'Dra for å endre rekkefølgen',
	'toplists-editor-add-item-label' => 'Legg til et nytt element',
	'toplists-editor-add-item-tooltip' => 'Legg et nytt element til listen',
	'toplists-create-button' => 'Opprett liste',
	'toplists-update-button' => 'Lagre liste',
	'toplists-cancel-button' => 'Avbryt',
	'toplists-items-removed' => '$1 {{PLURAL:$1|element|elementer}} fjernet',
	'toplists-items-created' => '$1 {{PLURAL:$1|element|elementer}} opprettet',
	'toplists-items-updated' => '$1 {{PLURAL:$1|element|elementer}} oppdatert',
	'toplists-items-nochange' => 'Ingen elementer endret',
	'toplits-image-browser-no-picture-selected' => 'Ikke noe bilde valgt',
	'toplits-image-browser-clear-picture' => 'Fjern bilde',
	'toplits-image-browser-selected-picture' => 'For øyeblikket valgte: $1',
	'toplists-image-browser-upload-btn' => 'Velg',
	'toplists-image-browser-upload-label' => 'Last opp ditt eget',
	'toplists-list-creation-summary' => 'Oppretter en liste, $1',
	'toplists-list-update-summary' => 'Oppdaterer en liste, $1',
	'toplists-item-creation-summary' => 'Oppretter et listeelement',
	'toplists-item-update-summary' => 'Oppdaterer et listeelement',
	'toplists-item-remove-summary' => 'Element fjernet fra listen',
	'toplists-item-restored' => 'Element gjennopprettet',
	'toplists-list-related-to' => 'Relatert til:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br/>stemme|$1<br/>stemmer}}',
	'toplists-list-created-by' => 'av [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Stem oppover',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|stemme|stemmer}} i $2',
	'toplists-list-add-item-label' => 'Legg til element',
	'toplists-list-add-item-name-label' => 'La listen fortsette...',
	'toplists-list-item-voted' => 'Stemt',
	'toplists-createpage-dialog-label' => 'Topp-10-liste',
	'toplists-email-subject' => 'En topp-10-liste har blitt endret',
	'toplists-email-body' => 'Wikia sier hei!

Listen <a href="$1">$2</a> på Wikia har blitt endret.

 $3

Gå til Wikia for å sjekke endringene. $1

- Wikia

Du kan <a href="$4">slette abbonementet</a> på endringer i listen.',
	'toplists-seconds' => '{{PLURAL:$1|ett sekund|$1 sekund}}',
	'toplists-minutes' => '{{PLURAL:$1|ett minutt|$1 minutt}}',
	'toplists-hours' => '{{PLURAL:$1|én time|$1 timer}}',
	'toplists-days' => '{{PLURAL:$1|én dag|$1 dager}}',
	'toplists-weeks' => '{{PLURAL:$1|én uke|$1 uker}}',
	'toplists-msg-fb-OnRateArticle-short' => 'har stemt på en Topp 10-liste på $WIKINAME!',
	'toplists-create-heading' => '<em>Nyhet!</em> Lag din egen Topp ti',
	'toplists-create-button-msg' => 'Opprett en liste',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 */
$messages['pms'] = array(
	'toplists-desc' => 'Liste dij prim 10',
	'right-toplists-create-edit-list' => 'Crea e modìfica le pàgine dle liste dij Prim 10',
	'right-toplists-create-item' => "Creé e gionta dj'element a na pàgina ëd lista dij Prim 10",
	'createtoplist' => 'Crea na lista neuva dij Prim 10',
	'edittoplist' => 'Modìfica dij Prim 10',
	'toplists-category' => 'Liste dij prim 10',
	'toplists-error-invalid-title' => 'Ël test dàit a va nen bin.',
	'toplists-error-invalid-picture' => "La figura selessionà a l'é pa bon-a.",
	'toplists-error-title-exists' => 'Sta pàgina a esist già. It peule andé a <a href="$2" target="_blank">$1</a> o dé un nòm diferent.',
	'toplists-error-title-spam' => 'Ël test dàit a conten quàiche paròle arconossùe com rumenta.',
	'toplists-error-article-blocked' => 'It peule pa creé na pàgina con sto nòm. Spiasent.',
	'toplists-error-article-not-exists' => '"$1" a esist pa. Veus-to <a href="$2" target="_blank">creelo</a>?',
	'toplists-error-picture-not-exists' => '"$1" a esist pa. Veus-to <a href="$2" target="_blank">carielo</a>?',
	'toplists-error-duplicated-entry' => 'It peule pa dovré ëll midem nòm pi che na vira.',
	'toplists-error-empty-item-name' => "Ël nòm ëd n'element esistent a peul pa esse veuid.",
	'toplists-item-cannot-delete' => 'Scanselassin dë sto element falìa.',
	'toplists-error-image-already-exists' => 'na figura con ë midem nòm a esist già.',
	'toplists-error-add-item-anon' => 'J\'utent anònim a peulo pa gionté element a la lista. Për piasì <a class="ajaxLogin" id="login" href="$1">Intra</a> o <a class="ajaxLogin" id="signup" href="$2">registra un cont neuv</a>.',
	'toplists-error-add-item-permission' => "Eror ëd përmess: Tò cont a l'ha pa ël drit ëd creé element neuv.",
	'toplists-error-add-item-list-not-exists' => 'La lista "$1" dij Prim 10 a esist pa.',
	'toplists-error-backslash-not-allowed' => 'Ël caràter "/" a l\'é pa përmëttù ant ël tìtol ëd na lista dij Prim 10.',
	'toplists-editor-title-label' => 'Nòm lista',
	'toplists-editor-title-placeholder' => 'Ansëriss un nòm për la lista',
	'toplists-editor-related-article-label' => 'Pàgina corelà <small>(opsional, ma selession-a na figura)</small>',
	'toplists-editor-related-article-placeholder' => 'Ansëriss un nòm ëd pàgina esistent',
	'toplists-editor-image-browser-tooltip' => 'Gionta na figura',
	'toplists-editor-remove-item-tooltip' => 'Gava element',
	'toplists-editor-drag-item-tooltip' => 'Tira për cangé ordin',
	'toplists-editor-add-item-label' => "Gionta n'element neuv",
	'toplists-editor-add-item-tooltip' => "Gionta n'element neuv a la lista",
	'toplists-create-button' => 'Crea lista',
	'toplists-update-button' => 'Salva lista',
	'toplists-cancel-button' => 'Scancela',
	'toplists-items-removed' => '$1 {{PLURAL:$1|element|element}} gavà',
	'toplists-items-created' => '$1 {{PLURAL:$1|element|element}} creà',
	'toplists-items-updated' => '$1 {{PLURAL:$1|element|element}} agiornà',
	'toplists-items-nochange' => 'Pa gnun element cangià',
	'toplits-image-browser-no-picture-selected' => 'Pa gnun-e figure selessionà',
	'toplits-image-browser-clear-picture' => 'Scansela figura',
	'toplits-image-browser-selected-picture' => 'Selessionà al moment: $1',
	'toplists-image-browser-upload-btn' => 'Sern',
	'toplists-image-browser-upload-label' => 'Caria tò pròpi',
	'toplists-list-creation-summary' => 'Creé na lista, $1',
	'toplists-list-update-summary' => 'Agiorné na lista, $1',
	'toplists-item-creation-summary' => "Creé n'element ëd na lista",
	'toplists-item-update-summary' => "Agiorné n'element ëd na lista",
	'toplists-item-remove-summary' => 'Element gavà da na lista',
	'toplists-item-restored' => 'Element ripristinà',
	'toplists-list-related-to' => 'Corelà a:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />vot|$1<br />vot}}',
	'toplists-list-created-by' => 'da [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Vòta su',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|vot|vot}} an $2',
	'toplists-list-add-item-label' => 'Gionta element',
	'toplists-list-add-item-name-label' => 'Manten la lista andasend...',
	'toplists-list-item-voted' => 'Votà',
	'toplists-createpage-dialog-label' => 'Lista dij prim 10',
	'toplists-email-subject' => "Na lista dij Prim 10 a l'é stàita cangià",
	'toplists-email-body' => 'Cerea da Wikia!

La lista <a href="$1">$2</a> su Wikia a l\'é stàita cangià.

 $3

Và su Wikia për controlé ij cambi! $1

- Wikia

It peule <a href="$4">disiscrivte</a> dai cambi a la lista.',
	'toplists-seconds' => '$1 {{PLURAL:$1|second|second}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuta|minute}}',
	'toplists-hours' => '$1 {{PLURAL:$1|ora|ore}}',
	'toplists-days' => '$1 {{PLURAL:$1|di|di}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|sman-a|sman-e}}',
	'toplists-msg-fb-OnRateArticle-short' => 'a l\'ha votà su na lista dij Prim 10 su $WIKINAME!',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'toplists-desc' => 'د سر 10 لړليکونه',
	'edittoplist' => 'د سر 10 لړليکونه سمول',
	'toplists-editor-title-label' => 'د لړليک نوم',
	'toplists-editor-related-article-label' => 'اړونده مخ',
	'toplists-editor-image-browser-tooltip' => 'يو انځور ورګډول',
	'toplists-create-button' => 'لړليک جوړول',
	'toplists-update-button' => 'لړليک خوندي کول',
	'toplists-cancel-button' => 'ناګارل',
	'toplists-image-browser-upload-btn' => 'ټاکل',
	'toplists-seconds' => '$1 {{PLURAL:$1|ثانيه|ثانيې}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|دقيقه|دقيقې}}',
	'toplists-hours' => '$1 {{PLURAL:$1|ساعت|ساعتونه}}',
	'toplists-days' => '$1 {{PLURAL:$1|ورځ|ورځې}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|اونۍ|اونۍ}}',
);

/** Portuguese (Português)
 * @author GTNS
 * @author Waldir
 */
$messages['pt'] = array(
	'toplists-error-invalid-title' => 'O texto fornecido não é válido.',
	'toplists-error-invalid-picture' => 'A imagem seleccionada não é válida.',
	'toplists-error-title-exists' => 'Esta página já existe. Você pode ir para <a href="$2" target="_blank">$1</a> ou fornecer um nome diferente.',
	'toplists-error-title-spam' => 'O texto introduzido contém algumas palavras identificadas como spam.',
	'toplists-error-article-blocked' => 'Você não tem permissão para criar uma página com este nome.',
	'toplists-error-article-not-exists' => '"$1" não existe. Você deseja <a href="$2" target="_blank">criá-lo</a> ?',
	'toplists-error-picture-not-exists' => '"$1" não existe. Você deseja <a href="$2" target="_blank">carregá-lo</a> ?',
	'toplists-error-duplicated-entry' => 'Você não pode usar o mesmo nome mais de uma vez.',
	'toplists-error-empty-item-name' => 'O nome de um item existente não pode ser vazio.',
	'toplists-item-cannot-delete' => 'A eliminação deste item falhou.',
	'toplists-error-image-already-exists' => 'Uma imagem com o mesmo nome já existe.',
	'toplists-error-add-item-anon' => 'Utilizadores anónimos não têm permissão para adicionar itens a listas. Por favor <a class="ajaxLogin" id="login" href="$1">autentique-se</a> ou <a class="ajaxLogin" id="signup" href="$2">crie uma nova conta</a>.',
	'toplists-error-add-item-permission' => 'Erro de permissão: Não foi concedida à sua conta a capacidade de criar novos itens.',
	'toplists-seconds' => '$1 {{PLURAL:$1|segundo|segundos}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hora|horas}}',
	'toplists-days' => '$1 {{PLURAL:$1|dia|dias}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|semana|semanas}}',
);

/** Russian (Русский)
 * @author Eleferen
 */
$messages['ru'] = array(
	'toplists-desc' => 'Список Топ 10',
	'toplists-error-invalid-picture' => 'Выбранное изображение является недопустимым.',
	'toplists-error-empty-item-name' => 'Имя существующего элемента не может быть пустым.',
	'toplists-editor-title-placeholder' => 'Введите имя списка',
	'toplists-editor-image-browser-tooltip' => 'Добавить изображение',
	'toplists-editor-remove-item-tooltip' => 'Удалить пункт',
	'toplists-editor-add-item-label' => 'Добавить новый пункт',
	'toplists-editor-add-item-tooltip' => 'Добавить новый элемент в список',
	'toplists-create-button' => 'Создать список',
	'toplists-update-button' => 'Сохранить список',
	'toplists-cancel-button' => 'Отмена',
	'toplits-image-browser-no-picture-selected' => 'Не выбрано изображение',
	'toplists-seconds' => '$1 {{PLURAL:$1|секунда|секунды|секунд}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|минута|минуты|минут}}',
	'toplists-hours' => '$1 {{PLURAL:$1|час|часа|часов}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|неделя|недели|недель}}',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'toplists-desc' => 'Mga talaan ng pinakamatataas na 10',
	'right-toplists-create-edit-list' => 'Likhain at baguhin ang mga pahina ng talaan ng Pinakamataas na 10',
	'right-toplists-create-item' => 'Lumikha at idagdag ang mga bagay sa isang pahina ng talaan ng Pinakamataas na 10',
	'createtoplist' => 'Lumikha ng isang bagong talaan ng Pinakamataas na 10',
	'edittoplist' => 'Baguhin ang talaan ng Pinakamataas na 10',
	'toplists-category' => 'Mga Talaan ng Pinakamatataas na 10',
	'toplists-error-invalid-title' => 'Hindi tanggap ang ibinigay na teksto.',
	'toplists-error-invalid-picture' => 'Hindi tanggap ang napiling larawan.',
	'toplists-error-title-exists' => 'Umiiral na ang pahinang ito. Makakapunta ka sa <a href="$2" target="_blank">$1</a> o magbigay ng isang ibang pangalan.',
	'toplists-error-title-spam' => 'Ang ibinigay na teksto ay naglalaman ng ilang mga salitang kinikilala bilang mga liham na manlulusob.',
	'toplists-error-article-blocked' => 'Hindi ka pinapayagang lumikha ng isang pahinang may ganitong pangalan. Paumanhin.',
	'toplists-error-article-not-exists' => 'Hindi umiiral ang "$1".  Nais mo bang <a href="$2" target="_blank">likhain ito</a>?',
	'toplists-error-picture-not-exists' => 'Hindi umiiral ang "$1".  Nais mo bang <a href="$2" target="_blank">ikargang papaitaas ito</a>?',
	'toplists-error-duplicated-entry' => 'Hindi mo magagamit ang katulad na pangalan nang mahigit sa isa.',
	'toplists-error-empty-item-name' => 'Ang pangalan ng isang umiiral na bagay ay hindi maaaring walang laman.',
	'toplists-item-cannot-delete' => 'Nabigo ang pagbubura ng bagay na ito.',
	'toplists-error-image-already-exists' => 'Umiiral na ang isang larawan na may katulad na pangalan.',
	'toplists-error-add-item-anon' => 'Ang hindi nakikilalang mga tagagamit ay hindi pinapayagang magdagdag ng mga bagay sa mga talaan. Mangyaring <a class="ajaxLogin" id="login" href="$1">Lumagdang papasok</a> o <a class="ajaxLogin" id="signup" href="$2">magpatala ng isang bagong akawnt</a>.',
	'toplists-error-add-item-permission' => 'Kamalian sa pahintulot: Ang akawnt mo ay hindi nabigyan ng karapatan upang lumikha ng bagong mga bagay.',
	'toplists-error-add-item-list-not-exists' => 'Hindi umiiral ang talaan ng Pinakamataas na 10 ng "$1".',
	'toplists-error-backslash-not-allowed' => 'Ang bantas na "/" ay hindi pinapayagan sa loob ng pamagat ng isang talaan ng Pinakamataas na 10.',
	'toplists-editor-title-label' => 'Pangalan ng talaan',
	'toplists-editor-title-placeholder' => 'Magpasok ng isang pangalan para sa talaan',
	'toplists-editor-related-article-label' => 'Kaugnay na pahina <small>(maaaring wala, subalit pumipili ng isang larawan)</small>',
	'toplists-editor-related-article-placeholder' => 'Magpasok ng isang umiiral na pangalan ng pahina',
	'toplists-editor-image-browser-tooltip' => 'Magdagdag ng isang larawan',
	'toplists-editor-remove-item-tooltip' => 'Tanggalin ang bagay',
	'toplists-editor-drag-item-tooltip' => 'Kaladkarin upang baguhin ang pagkakasunud-sunod',
	'toplists-editor-add-item-label' => 'Magdagdag ng isang bagong bagay',
	'toplists-editor-add-item-tooltip' => 'Magdagdag ng isang bagong bagay sa talaan',
	'toplists-create-button' => 'Likhain ang talaan',
	'toplists-update-button' => 'Sagipin ang talaan',
	'toplists-cancel-button' => 'Huwag ituloy',
	'toplists-items-removed' => '$1 {{PLURAL:$1|bagay|mga bagay}} ang natanggal',
	'toplists-items-created' => '$1 {{PLURAL:$1|bagay|mga bagay}} ang nalikha',
	'toplists-items-updated' => '$1 {{PLURAL:$1|bagay|mga bagay}} ang naisapanahon',
	'toplists-items-nochange' => 'Walang nabagong mga bagay',
	'toplits-image-browser-no-picture-selected' => 'Walang napiling larawan',
	'toplits-image-browser-clear-picture' => 'Hawiin ang larawan',
	'toplits-image-browser-selected-picture' => 'Kasalukuyang napili: $1',
	'toplists-image-browser-upload-btn' => 'Pumili',
	'toplists-image-browser-upload-label' => 'Ikargang paitaas ang mula sa sarili mo',
	'toplists-list-creation-summary' => 'Lumilikha ng isang talaan, $1',
	'toplists-list-update-summary' => 'Nagsasapanahon ng isang talaan, $1',
	'toplists-item-creation-summary' => 'Lumilikha ng isang bagay sa talaan',
	'toplists-item-update-summary' => 'Nagsasapanahon ng isang bagay sa talaan',
	'toplists-item-remove-summary' => 'Tinanggal ang bagay mula sa talaan',
	'toplists-item-restored' => 'Naipanumbalik ang bagay',
	'toplists-list-related-to' => 'Kaugnay ng:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />boto|$1<br />mga boto}}',
	'toplists-list-created-by' => 'ni [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Bumotong paitaas',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|boto|mga boto}} sa $2',
	'toplists-list-add-item-label' => 'Idagdag ang bagay',
	'toplists-list-add-item-name-label' => 'Panatilihing nagpapatuloy ang talaan...',
	'toplists-list-item-voted' => 'Nakaboto na',
	'toplists-createpage-dialog-label' => 'Talaan ng Pinakamataas na 10',
	'toplists-email-subject' => 'Binago ang isang talaan ng Pinakamataas na 10',
	'toplists-email-body' => 'Kumusta mula sa Wikia! 

Ang talaang <a href="$1">$2</a> sa Wikia ay nabago. 

 $3 

Tumungo sa Wikia upang suriin ang mga pagbabago! $1 

 - Wikia 

 Maaari kang <a href="$4">huwag tumanggap</a> ng mga pagbabago sa talaan.',
	'toplists-seconds' => '$1 {{PLURAL:$1|segundo|mga segundo}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuto|mga minuto}}',
	'toplists-hours' => '$1 {{PLURAL:$1|oras|mga oras}}',
	'toplists-days' => '$1 {{PLURAL:$1|araw|mga araw}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|linggo|mga linggo}}',
	'toplists-msg-fb-OnRateArticle-short' => 'ay bumoto sa isang talaan ng Pinakamataas na 10 sa $WIKINAME!',
);

