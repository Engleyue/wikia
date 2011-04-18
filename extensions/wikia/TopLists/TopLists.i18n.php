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
	'right-toplists-edit-item' => 'Edit items in a Top 10 list page',
	'right-toplists-delete-item' => 'Delete items from a Top 10 list page',

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
	'toplists-list-vote-up' => 'Keep this as short as possible. Everything exceeding allowed lenght will be cutted out!',
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
	'toplists-category' => 'Rolloù Top 10',
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
	'toplists-editor-related-article-label' => 'Pajenn kar <small>(diret, met termeniñ a ra ur skeudenn)</small>',
	'toplists-editor-related-article-placeholder' => "Merkañ anv ur bajenn zo anezhi c'hoazh",
	'toplists-editor-image-browser-tooltip' => 'Ouzhpennañ ur skeudenn',
	'toplists-editor-remove-item-tooltip' => 'Tennañ an objed',
	'toplists-editor-drag-item-tooltip' => 'Lakait da riklañ evit cheñch an urzh',
	'toplists-editor-add-item-label' => 'Ouzhpennañ un elfenn nevez',
	'toplists-editor-add-item-tooltip' => "Ouzhpennañ un objed nevez d'ar roll",
	'toplists-create-button' => 'Sevel ar roll',
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
	'toplists-create-heading' => "<em>Nevez!</em> Savit ho roll Top 10 deoc'h-c'hwi",
	'toplists-create-button-msg' => 'Sevel ur roll',
);

/** German (Deutsch)
 * @author Avatar
 * @author LWChris
 */
$messages['de'] = array(
	'toplists-desc' => 'Top 10 Listen',
	'right-toplists-create-edit-list' => 'Erstelle und bearbeite Top 10 Listen',
	'right-toplists-create-item' => 'Erstelle und füge Einträge zu einer Top 10 Liste hinzu',
	'right-toplists-edit-item' => 'Elemente in einer Top 10 Liste bearbeiten',
	'right-toplists-delete-item' => 'Elemente aus einer Top 10 Liste löschen',
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
	'toplists-upload-error-unknown' => 'Beim Verarbeiten der Upload Anfrage ist ein Fehler aufgetreten. Bitte versuche es erneut.',
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
	'toplists-list-vote-up' => 'Zustimmen',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|Stimme|Stimmen}} in $2',
	'toplists-list-add-item-label' => 'Eintrag hinzufügen',
	'toplists-list-add-item-name-label' => 'Führe die Liste fort...',
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
	'toplists-create-heading' => '<em>Neu!</em> Erstelle deine eigene Top 10 Liste',
	'toplists-create-button-msg' => 'Liste erstellen',
);

/** Spanish (Español)
 * @author Bola
 * @author Peter17
 */
$messages['es'] = array(
	'toplists-desc' => 'Los 10 mejores',
	'right-toplists-create-edit-list' => 'Crea y edita páginas de los 10 mejores',
	'right-toplists-create-item' => 'Crea y añade elementos a una página de los 10 mejores',
	'createtoplist' => 'Crea una nueva lista de los 10 mejores',
	'edittoplist' => 'Editar los 10 mejores',
	'toplists-category' => 'Los 10 mejores',
	'toplists-error-invalid-title' => 'El texto especificado no es válido.',
	'toplists-error-invalid-picture' => 'La imagen seleccionada no es válida.',
	'toplists-error-title-exists' => 'La página ya existe. Puedes ir a <a href="$2" target="_blank">$1</a> o proporciona un nombre diferente.',
	'toplists-error-title-spam' => 'El texto especificado contiene algunas palabras identificadas como spam.',
	'toplists-error-article-blocked' => 'No estás autorizado para crear una página con este nombre. Lo sentimos.',
	'toplists-error-article-not-exists' => '"$1" no existe. ¿Quieres <a href="$2" target="_blank">crearla</a>?',
	'toplists-error-picture-not-exists' => '"$1" no existe. ¿Quieres <a href="$2" target="_blank">subirla</a>?',
	'toplists-error-duplicated-entry' => 'No puedes usar el mismo nombre más de una vez.',
	'toplists-error-empty-item-name' => 'El nombre de un elemento existente no puede estar vacío.',
	'toplists-item-cannot-delete' => 'Falló el borrado de este elemento.',
	'toplists-error-image-already-exists' => 'Ya existe una imagen con el mismo nombre.',
	'toplists-error-add-item-anon' => 'Los usuarios anónimos no están autorizados para añadir elementos a las listas. Por favor <a class="ajaxLogin" id="login" href="$1">inicia sesión</a> o <a class="ajaxLogin" id="signup" href="$2">registra una cuenta nueva</a>.',
	'toplists-error-add-item-permission' => 'Error de permisos: No se ha concedido el derecho a tu cuenta para crear nuevos elementos.',
	'toplists-error-add-item-list-not-exists' => 'Los 10 mejores "$1" no existe.',
	'toplists-error-backslash-not-allowed' => 'El caracter "/" no está permitido en el título de los 10 mejores.',
	'toplists-upload-error-unknown' => 'Ha ocurrido un error mientras procesábamos tu petición de subida. Por favor, inténtalo de nuevo.',
	'toplists-editor-title-label' => 'Nombre de la lista',
	'toplists-editor-title-placeholder' => 'Introduce un nombre para la lista',
	'toplists-editor-related-article-label' => 'Página relacionada <small>(opcional, pero selecciona una imagen)</small>',
	'toplists-editor-related-article-placeholder' => 'Escribe el nombre de una página existente',
	'toplists-editor-image-browser-tooltip' => 'Añade una imagen',
	'toplists-editor-remove-item-tooltip' => 'Eliminar el elemento',
	'toplists-editor-drag-item-tooltip' => 'Arrastra para cambiar el orden',
	'toplists-editor-add-item-label' => 'Añade un nuevo elemento',
	'toplists-editor-add-item-tooltip' => 'Añade un nuevo elemento a la lista',
	'toplists-create-button' => 'Crear una lista',
	'toplists-update-button' => 'Guardar lista',
	'toplists-cancel-button' => 'Cancelar',
	'toplists-items-removed' => '$1 {{PLURAL:$1|elemento borrado|elementos borrados}}',
	'toplists-items-created' => '$1 {{PLURAL:$1|elemento creado|elementos creados}}',
	'toplists-items-updated' => '$1 {{PLURAL:$1|elemento actualizado|elementos actualizados}}',
	'toplists-items-nochange' => 'No hay elementos modificados',
	'toplits-image-browser-no-picture-selected' => 'Ninguna imagen seleccionada',
	'toplits-image-browser-clear-picture' => 'Borrar imagen',
	'toplits-image-browser-selected-picture' => 'Actualmente seleccionado: $1',
	'toplists-image-browser-upload-btn' => 'Escoge',
	'toplists-image-browser-upload-label' => 'Sube tu propio',
	'toplists-list-creation-summary' => 'Creando una lista, $1',
	'toplists-list-update-summary' => 'Actualizando una lista, $1',
	'toplists-item-creation-summary' => 'Creando un elemento de una lista',
	'toplists-item-update-summary' => 'Actualizando un elemento de una lista',
	'toplists-item-remove-summary' => 'Elemento borrado de la lista',
	'toplists-item-restored' => 'Elemento restaurado',
	'toplists-list-related-to' => 'Relacionado con:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />voto|$1<br />votos}}',
	'toplists-list-created-by' => 'por el usuario [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Votar',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|voto|votos}} en $2',
	'toplists-list-add-item-label' => 'Añadir elemento',
	'toplists-list-add-item-name-label' => 'Mantener la lista en curso...',
	'toplists-list-item-voted' => 'Votado',
	'toplists-createpage-dialog-label' => 'Lista de los 10 mejores',
	'toplists-email-subject' => 'Una lista de los 10 mejores ha sido modificada',
	'toplists-email-body' => '¡Hola desde Wikia!

La lista <a href="$1">$2</a> en Wikia ha sido modificada.

 $3

¡Dirígete a Wikia para ver los cambios!

- Wikia

Puedes <a href="$4">cancelar</a>  tu subscripción de los cambios a la lista.',
	'toplists-seconds' => '$1 {{PLURAL:$1|segundo|segundos}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hora|horas}}',
	'toplists-days' => '$1 {{PLURAL:$1|dia|dias}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|semana|semanas}}',
	'toplists-msg-fb-OnRateArticle-short' => 'ha votado en una lista en $WIKINAME!',
	'toplists-create-heading' => '<em>¡Nuevo!</em> Crea tus 10 mejores',
	'toplists-create-button-msg' => 'Crear una lista',
);

/** Persian (فارسی) */
$messages['fa'] = array(
	'toplists-editor-title-label' => 'نام فهرست',
	'toplists-editor-remove-item-tooltip' => 'حذف مورد',
	'toplists-cancel-button' => 'لغو',
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
	'toplists-error-invalid-title' => 'Teksti ei kelpaa.',
	'toplists-error-invalid-picture' => 'Valittu kuva ei kelpaa.',
	'toplists-error-title-exists' => 'Tämä sivu on jo olemassa. Voit siirtyä kohteeseen <a href="$2" target="_blank">$1</a> tai tarjota eri nimen.',
	'toplists-error-title-spam' => 'Teksti sisältää ilmaisuja, jotka luokitellaan roskapostiksi.',
	'toplists-error-article-blocked' => 'Et voi valitettavasti luoda tämän nimistä sivu.',
	'toplists-error-article-not-exists' => 'Sivua ”$1” ei ole olemassa. Haluatko <a href="$2" target="_blank">luoda sen</a>?',
	'toplists-error-picture-not-exists' => 'Kuvaa ”$1” ei ole olemassa. Haluatko <a href="$2" target="_blank">tallentaa sellaisen</a>?',
	'toplists-error-duplicated-entry' => 'Voit käyttää samaa nimeä vain kerran.',
	'toplists-error-empty-item-name' => 'Olemassaolevan kohteen nimi ei voi olla tyhjä.',
	'toplists-item-cannot-delete' => 'Tämän kohteen poistaminen epäonnistui.',
	'toplists-error-image-already-exists' => 'Samanniminen kuva on jo olemassa.',
	'toplists-error-add-item-anon' => 'Anonyymit käyttäjät eivät voi lisätä kohteita luetteloihin. Ole hyvä ja <a class="ajaxLogin" id="login" href="$1">kirjaudu sisään</a> tai <a class="ajaxLogin" id="signup" href="$2">rekisteröi uusi tunnus</a>.',
	'toplists-error-add-item-permission' => 'Käyttöoikeusvirhe: Tilille ei ole myönnetty oikeutta luoda uusia kohteita.',
	'toplists-error-add-item-list-not-exists' => 'Top10-listaa $1 ei ole olemassa.',
	'toplists-error-backslash-not-allowed' => '/-merkki ei ole sallittu Top10-listan otsikossa.',
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
 * @author McDutchie
 * @author Peter17
 * @author Verdy p
 * @author Wyz
 */
$messages['fr'] = array(
	'toplists-desc' => 'Listes de top 10',
	'right-toplists-create-edit-list' => 'Créer et modifier des pages de liste de top 10',
	'right-toplists-create-item' => 'Créer et ajouter des éléments à une page de liste de top 10',
	'right-toplists-edit-item' => 'Modifier les éléments dans une page de liste de top 10',
	'right-toplists-delete-item' => 'Supprimer les éléments dans une page de liste de top 10',
	'createtoplist' => 'Créer une nouvelle liste de top 10',
	'edittoplist' => 'Modifier une liste de top 10',
	'toplists-category' => 'Listes de top 10',
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
	'toplists-error-image-already-exists' => 'Une image existe déjà avec le même nom.',
	'toplists-error-add-item-anon' => 'Les utilisateurs anonymes ne sont pas autorisés à ajouter des éléments aux listes. Veuillez <a class="ajaxLogin" id="login" href="$1">vous connecter</a> ou <a class="ajaxLogin" id="signup" href="$2">vous inscrire avec un nouveau compte</a> .',
	'toplists-error-add-item-permission' => 'Erreur de permission : Votre compte n’a pas les droits pour créer de nouveaux éléments.',
	'toplists-error-add-item-list-not-exists' => 'La liste de top 10 « $1 » n’existe pas.',
	'toplists-error-backslash-not-allowed' => 'Le caractère « / » n’est pas autorisé dans le titre d’une liste de top 10.',
	'toplists-upload-error-unknown' => 'Une erreur s’est produite lors du traitement de la demande d’import. Veuillez réessayer.',
	'toplists-editor-title-label' => 'Nom de la liste',
	'toplists-editor-title-placeholder' => 'Saisissez un nom pour la liste',
	'toplists-editor-related-article-label' => 'Page connexe <small>(optionnel, mais définit une image)</small>',
	'toplists-editor-related-article-placeholder' => 'Saisissez un nom de page existante',
	'toplists-editor-image-browser-tooltip' => 'Ajouter une image',
	'toplists-editor-remove-item-tooltip' => 'Retirer l’élément',
	'toplists-editor-drag-item-tooltip' => 'Faites glisser pour changer l’ordre',
	'toplists-editor-add-item-label' => 'Ajouter un nouvel élément',
	'toplists-editor-add-item-tooltip' => 'Ajouter un nouvel élément à la liste',
	'toplists-create-button' => 'Créer une liste',
	'toplists-update-button' => 'Enregistrer la liste',
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
	'toplists-list-add-item-name-label' => 'Continuer la liste...',
	'toplists-list-item-voted' => 'Voté',
	'toplists-createpage-dialog-label' => 'Liste de top 10',
	'toplists-email-subject' => 'Une liste de top 10 a été modifiée',
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
	'toplists-msg-fb-OnRateArticle-short' => 'a voté sur une liste de top 10 sur $WIKINAME !',
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

/** Hungarian (Magyar) */
$messages['hu'] = array(
	'toplists-desc' => 'Top 10-es lista',
	'right-toplists-create-edit-list' => 'Top 10-es lista létrehozása és szerkesztése',
	'right-toplists-create-item' => 'Top 10-es lista létrehozása és elemek hozzáadása',
	'createtoplist' => 'Új Top 10-es lista létrehozása',
	'edittoplist' => 'Top 10-es lista szerkesztése',
	'toplists-category' => 'Top 10-es listák',
	'toplists-error-invalid-title' => 'A megadott szöveg érvénytelen.',
	'toplists-error-invalid-picture' => 'A megadott kép érvénytelen.',
	'toplists-error-title-exists' => 'Ez a lap már létezik. Ugrás a <a href="$2" target="_blank">$1</a> vagy adj meg másik nevet.',
	'toplists-error-title-spam' => 'A megadott szöveg spam szavakat tartalmaz.',
	'toplists-error-article-blocked' => 'Ilyen nevű lapot nem hozhat létre. Bocsánat.',
	'toplists-error-article-not-exists' => '"$ 1" nem létezik. <a href="$2" target="_blank">Létre kívánja hozni</a>?',
	'toplists-error-picture-not-exists' => '"$ 1" nem létezik. Szeretné <a href="$2" target="_blank">feltölteni</a>?',
	'toplists-error-duplicated-entry' => 'Többször nem használhatja ugyanazt a nevet.',
	'toplists-error-empty-item-name' => 'Egy létező elem neve nem lehet üres.',
	'toplists-item-cannot-delete' => 'A törlés sikertelen.',
	'toplists-error-image-already-exists' => 'Már létezik kép ezzel a névvel.',
	'toplists-error-add-item-anon' => 'Névtelen felhasználók nem jogosultak elemek hozzáadásához a listákhoz. Kérjük, <a class="ajaxLogin" id="login" href="$1">jelentkezzen be,</a> vagy <a class="ajaxLogin" id="signup" href="$2">regisztráljon</a>.',
	'toplists-error-add-item-permission' => 'Jogosultsági hiba: a felhasználói fiók nem rendelkezik engedéllyel új elemek létrehozásához.',
	'toplists-error-add-item-list-not-exists' => 'A "$ 1" Top 10-es lista nem létezik.',
	'toplists-error-backslash-not-allowed' => 'A "/" karakter nem engedélyezett a Top 10-es lista nevében.',
	'toplists-editor-title-label' => 'A lista neve',
	'toplists-editor-title-placeholder' => 'Írja be a lista nevét',
	'toplists-editor-related-article-label' => 'Kapcsolódó oldal <small>(nem kötelező, de kiválaszt egy képet)</small>',
	'toplists-editor-related-article-placeholder' => 'Adja meg egy létező lap nevét',
	'toplists-editor-image-browser-tooltip' => 'Kép hozzáadása',
	'toplists-editor-remove-item-tooltip' => 'Az elem törlése',
	'toplists-editor-drag-item-tooltip' => 'A sorrend módosításához húzza át az elemet',
	'toplists-editor-add-item-label' => 'Új elem hozzáadása',
	'toplists-editor-add-item-tooltip' => 'Új elem hozzáadása a listához',
	'toplists-create-button' => 'Lista létrehozása',
	'toplists-update-button' => 'Lista mentése',
	'toplists-cancel-button' => 'Mégse',
	'toplists-items-removed' => '$1 {{PLURAL:$1|elem|elem}} eltávolítva',
	'toplists-items-created' => '$1 {{PLURAL:$1|elem|elem}} létrehozva',
	'toplists-items-updated' => '$1 {{PLURAL:$1|elem|elem}} frissítve',
	'toplists-items-nochange' => 'Az elemek nem módosultak.',
	'toplits-image-browser-no-picture-selected' => 'Nincs kiválasztott kép',
	'toplits-image-browser-clear-picture' => 'Kép eltávolítása',
	'toplits-image-browser-selected-picture' => 'Kijelölve: $1',
	'toplists-image-browser-upload-btn' => 'Válasszon',
	'toplists-image-browser-upload-label' => 'Saját feltöltése',
	'toplists-list-creation-summary' => 'Lista létrehozása, $1',
	'toplists-list-update-summary' => 'Lista frissítése, $1',
	'toplists-item-creation-summary' => 'Listaelem létrehozása',
	'toplists-item-update-summary' => 'Listaelem frissítése',
	'toplists-item-remove-summary' => 'Elem eltávolítva a listáról',
	'toplists-item-restored' => 'Elem visszaállítása',
	'toplists-list-related-to' => 'Kapcsolódik a következő(k)höz:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />szavazat|$1<br />szavazatok}}',
	'toplists-list-created-by' => '[[Felhasználó:$1|$1]] által',
	'toplists-list-vote-up' => 'Szavazás',
	'toplists-list-add-item-label' => 'Elem hozzáadása',
	'toplists-list-item-voted' => 'Szavazat elküldve',
	'toplists-createpage-dialog-label' => 'Top 10-es lista',
	'toplists-email-subject' => 'A Top 10-es lista megváltozott',
	'toplists-create-button-msg' => 'Lista létrehozása',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'toplists-desc' => 'Listas Top 10',
	'right-toplists-create-edit-list' => 'Crear e modificar paginas de lista Top 10',
	'right-toplists-create-item' => 'Crear e adder elementos a un pagina de lista Top 10',
	'right-toplists-edit-item' => 'Modificar elementos in un pagina con lista Top 10',
	'right-toplists-delete-item' => 'Deler elementos de un pagina con lista Top 10',
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
	'toplists-upload-error-unknown' => 'Un error occurreva durante le tractamento del requesta de incargamento, per favor reproba.',
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

/** Kurdish (Latin) (Kurdî (Latin))
 * @author George Animal
 */
$messages['ku-latn'] = array(
	'toplists-editor-title-label' => 'Navê lîstê',
	'toplists-cancel-button' => 'Betal bike',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'toplists-desc' => 'Списоци на 10 најкотирани',
	'right-toplists-create-edit-list' => 'Создајте или уредете статии на списокот на 10 најкотирани',
	'right-toplists-create-item' => 'Создавајте и додавајте ставки на список на 10 најкотирани',
	'right-toplists-edit-item' => 'Уреди ставки на страницата „10 најкотирани“',
	'right-toplists-delete-item' => 'Избриши ставки на страницата „10 најкотирани“',
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
	'toplists-upload-error-unknown' => 'Се појави грешка при обработката на барањето за подигање. Обидете се повторно.',
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
	'right-toplists-edit-item' => 'Items in een Top 10-lijstpagina bewerken',
	'right-toplists-delete-item' => 'Items uit een Top 10-lijstpagina verwijderen',
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
	'toplists-upload-error-unknown' => 'Er is een fout opgetreden bij het verwerken van de uploadverzoek. Probeer het nog een keer.',
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
	'toplists-desc' => 'Topp 10-lister',
	'right-toplists-create-edit-list' => 'Opprett og rediger Topp 10-listesider.',
	'right-toplists-create-item' => 'Opprett og legg elementer til en Topp 10-listeside',
	'right-toplists-edit-item' => 'Rediger elementer i en Topp 10-listeside',
	'right-toplists-delete-item' => 'Slett elementer fra en Topp 10-listeside',
	'createtoplist' => 'Opprett en ny Topp 10-liste',
	'edittoplist' => 'Rediger Topp 10-liste',
	'toplists-category' => 'Topp 10-lister',
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
	'toplists-error-add-item-list-not-exists' => 'Topp 10-listen «$1» eksisterer ikke.',
	'toplists-error-backslash-not-allowed' => '«/»-tegnet er ikke tillatt i tittelen på en Topp 10-liste.',
	'toplists-upload-error-unknown' => 'En feil har oppstått under behandlingen av opplastningsforespørselen, vennligst prøv igjen.',
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
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />stemme|$1<br />stemmer}}',
	'toplists-list-created-by' => 'av [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Stem oppover',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|stemme|stemmer}} i $2',
	'toplists-list-add-item-label' => 'Legg til element',
	'toplists-list-add-item-name-label' => 'La listen fortsette...',
	'toplists-list-item-voted' => 'Stemt',
	'toplists-createpage-dialog-label' => 'Topp 10-liste',
	'toplists-email-subject' => 'En topp 10-liste har blitt endret',
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
	'toplists-error-article-blocked' => 'A peul pa creé na pàgina con sto nòm-sì. An dëspias.',
	'toplists-error-article-not-exists' => '"$1" a esist pa. Veus-to <a href="$2" target="_blank">creelo</a>?',
	'toplists-error-picture-not-exists' => '"$1" a esist pa. Veus-to <a href="$2" target="_blank">carielo</a>?',
	'toplists-error-duplicated-entry' => 'A peul pa dovré ël midem nòm pi che na vira.',
	'toplists-error-empty-item-name' => "Ël nòm ëd n'element esistent a peul pa esse veuid.",
	'toplists-item-cannot-delete' => "La scancelassion ëd s'element a l'é falìa.",
	'toplists-error-image-already-exists' => 'Na figura con ël midem nòm a esist già.',
	'toplists-error-add-item-anon' => 'J\'utent anònim a peulo pa gionté d\'element a la lista. Për piasì <a class="ajaxLogin" id="login" href="$1">ch\'a intra ant ël sistema</a> o <a class="ajaxLogin" id="signup" href="$2">ch\'a registra un cont neuv</a>.',
	'toplists-error-add-item-permission' => "Eror ëd përmess: Sò cont a l'ha pa ël drit ëd creé d'element neuv.",
	'toplists-error-add-item-list-not-exists' => 'La lista "$1" dij Prim 10 a esist pa.',
	'toplists-error-backslash-not-allowed' => 'Ël caràter "/" a l\'é pa përmëttù ant ël tìtol ëd na lista dij Prim 10.',
	'toplists-editor-title-label' => 'Nòm ëd lista',
	'toplists-editor-title-placeholder' => 'Buté un nòm për la lista',
	'toplists-editor-related-article-label' => 'Pàgina corelà <small>(opsional, ma selession-a na figura)</small>',
	'toplists-editor-related-article-placeholder' => 'Buté un nòm ëd na pàgina esistenta',
	'toplists-editor-image-browser-tooltip' => 'Gionta na figura',
	'toplists-editor-remove-item-tooltip' => "Gavé l'element",
	'toplists-editor-drag-item-tooltip' => "Fé sghijé për cangé l'órdin",
	'toplists-editor-add-item-label' => "Gionta n'element neuv",
	'toplists-editor-add-item-tooltip' => "Gionta n'element neuv a la lista",
	'toplists-create-button' => 'Creé na lista',
	'toplists-update-button' => 'Salvé la lista',
	'toplists-cancel-button' => 'Scancela',
	'toplists-items-removed' => '$1 {{PLURAL:$1|element|element}} gavà',
	'toplists-items-created' => '$1 {{PLURAL:$1|element|element}} creà',
	'toplists-items-updated' => '$1 {{PLURAL:$1|element|element}} agiornà',
	'toplists-items-nochange' => 'Pa gnun element cangià',
	'toplits-image-browser-no-picture-selected' => 'Pa gnun-e figure selessionà',
	'toplits-image-browser-clear-picture' => 'Scancelé la figura',
	'toplits-image-browser-selected-picture' => 'Selessionà al moment: $1',
	'toplists-image-browser-upload-btn' => 'Sern',
	'toplists-image-browser-upload-label' => "Ch'a caria la soa",
	'toplists-list-creation-summary' => 'Creé na lista, $1',
	'toplists-list-update-summary' => 'Agiorné na lista, $1',
	'toplists-item-creation-summary' => "Creé n'element ëd na lista",
	'toplists-item-update-summary' => "Agiorné n'element ëd na lista",
	'toplists-item-remove-summary' => 'Element gavà da na lista',
	'toplists-item-restored' => 'Element ripristinà',
	'toplists-list-related-to' => 'Corelà a:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />vot|$1<br />vot}}',
	'toplists-list-created-by' => 'da [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Voté a pro',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|vot|vot}} an $2',
	'toplists-list-add-item-label' => "Gionté n'element",
	'toplists-list-add-item-name-label' => 'Lassé core la lista...',
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
	'toplists-create-heading' => '<em>Neuv!</em> Crea Toa Lista dij Prim Des',
	'toplists-create-button-msg' => 'Crea na lista',
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
 * @author Hamilton Abreu
 * @author Waldir
 */
$messages['pt'] = array(
	'toplists-desc' => 'Listas Top 10',
	'right-toplists-create-edit-list' => 'Criar e editar páginas de listas Top 10',
	'right-toplists-create-item' => 'Criar e adicionar elementos à página de uma lista Top 10',
	'right-toplists-edit-item' => 'Editar itens na página de uma lista Top 10',
	'right-toplists-delete-item' => 'Eliminar itens da página de uma lista Top 10',
	'createtoplist' => 'Criar uma lista Top 10',
	'edittoplist' => 'Editar lista Top 10',
	'toplists-category' => 'Listas Top 10',
	'toplists-error-invalid-title' => 'O texto fornecido não é válido.',
	'toplists-error-invalid-picture' => 'A imagem seleccionada não é válida.',
	'toplists-error-title-exists' => 'Esta página já existe. Pode ir para <a href="$2" target="_blank">$1</a> ou fornecer um nome diferente.',
	'toplists-error-title-spam' => 'O texto introduzido contém algumas palavras identificadas como spam.',
	'toplists-error-article-blocked' => 'Não pode criar uma página com este nome. Desculpe.',
	'toplists-error-article-not-exists' => '"$1" não existe. Deseja <a href="$2" target="_blank">criá-lo</a> ?',
	'toplists-error-picture-not-exists' => '"$1" não existe. Deseja <a href="$2" target="_blank">enviá-lo</a> ?',
	'toplists-error-duplicated-entry' => 'Não pode usar o mesmo nome mais de uma vez.',
	'toplists-error-empty-item-name' => 'O nome de um elemento existente não pode ser vazio.',
	'toplists-item-cannot-delete' => 'A eliminação deste elemento falhou.',
	'toplists-error-image-already-exists' => 'Já existe uma imagem com o mesmo nome.',
	'toplists-error-add-item-anon' => 'Utilizadores anónimos não têm permissão para adicionar elementos a listas. Por favor <a class="ajaxLogin" id="login" href="$1">autentique-se</a> ou <a class="ajaxLogin" id="signup" href="$2">crie uma conta</a>.',
	'toplists-error-add-item-permission' => 'Erro de permissões: Não foi concedida à sua conta a capacidade de criar elementos.',
	'toplists-error-add-item-list-not-exists' => 'A lista Top 10 "$1" não existe.',
	'toplists-error-backslash-not-allowed' => 'O carácter "/" não é permitido no título de uma lista Top 10.',
	'toplists-upload-error-unknown' => 'Ocorreu um erro ao processar o pedido de upload. Tente novamente, por favor.',
	'toplists-editor-title-label' => 'Nome da lista',
	'toplists-editor-title-placeholder' => 'Introduza um nome para a lista',
	'toplists-editor-related-article-label' => 'Página relacionada <small>(opcional, mas selecciona uma imagem)</small>',
	'toplists-editor-related-article-placeholder' => 'Introduza o nome de uma página existente',
	'toplists-editor-image-browser-tooltip' => 'Adicionar uma imagem',
	'toplists-editor-remove-item-tooltip' => 'Remover o elemento',
	'toplists-editor-drag-item-tooltip' => 'Arraste para alterar a ordem',
	'toplists-editor-add-item-label' => 'Acrescentar um elemento',
	'toplists-editor-add-item-tooltip' => 'Adicionar um elemento à lista',
	'toplists-create-button' => 'Criar lista',
	'toplists-update-button' => 'Gravar lista',
	'toplists-cancel-button' => 'Cancelar',
	'toplists-items-removed' => '$1 {{PLURAL:$1|elemento removido|elementos removidos}}',
	'toplists-items-created' => '$1 {{PLURAL:$1|elemento criado|elementos criados}}',
	'toplists-items-updated' => '$1 {{PLURAL:$1|elemento actualizado|elementos actualizados}}',
	'toplists-items-nochange' => 'Não foi alterado nenhum elemento',
	'toplits-image-browser-no-picture-selected' => 'Não foi seleccionada nenhuma imagem',
	'toplits-image-browser-clear-picture' => 'Limpar imagem',
	'toplits-image-browser-selected-picture' => 'Seleccionada neste momento: $1',
	'toplists-image-browser-upload-btn' => 'Escolher',
	'toplists-image-browser-upload-label' => 'Faça o upload de uma',
	'toplists-list-creation-summary' => 'A criar uma lista, $1',
	'toplists-list-update-summary' => 'A actualizar uma lista, $1',
	'toplists-item-creation-summary' => 'A criar um elemento de uma lista',
	'toplists-item-update-summary' => 'A actualizar um elemento de uma lista',
	'toplists-item-remove-summary' => 'Elemento removido da lista',
	'toplists-item-restored' => 'Elemento restaurado',
	'toplists-list-related-to' => 'Relacionado a:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />voto|$1<br />votos}}',
	'toplists-list-created-by' => 'por [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Voto positivo',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|voto|votos}} em $2',
	'toplists-list-add-item-label' => 'Adicionar elemento',
	'toplists-list-add-item-name-label' => 'Continuar a lista...',
	'toplists-list-item-voted' => 'Votado',
	'toplists-createpage-dialog-label' => 'Lista Top 10',
	'toplists-email-subject' => 'Uma lista Top 10 foi alterada',
	'toplists-email-body' => 'Olá da Wikia!

A lista <a href="$1">$2</a> na Wikia foi alterada.

 $3

Vá a Wikia verificar as mudanças! $1

- Wikia

Pode <a href="$4">cancelar a subscrição</a> de alterações à lista.',
	'toplists-seconds' => '$1 {{PLURAL:$1|segundo|segundos}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
	'toplists-hours' => '$1 {{PLURAL:$1|hora|horas}}',
	'toplists-days' => '$1 {{PLURAL:$1|dia|dias}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|semana|semanas}}',
	'toplists-msg-fb-OnRateArticle-short' => 'votou numa lista Top 10 na $WIKINAME!',
	'toplists-create-heading' => '<em>Novo!</em> Crie o Seu Próprio Top 10',
	'toplists-create-button-msg' => 'Criar uma lista',
);

/** Russian (Русский)
 * @author Eleferen
 */
$messages['ru'] = array(
	'toplists-desc' => 'Список Топ 10',
	'edittoplist' => 'Изменить список топ-10',
	'toplists-category' => 'Списки топ-10',
	'toplists-error-invalid-picture' => 'Выбранное изображение является недопустимым.',
	'toplists-error-empty-item-name' => 'Имя существующего элемента не может быть пустым.',
	'toplists-editor-title-label' => 'Название списка',
	'toplists-editor-title-placeholder' => 'Введите имя списка',
	'toplists-editor-image-browser-tooltip' => 'Добавить изображение',
	'toplists-editor-remove-item-tooltip' => 'Удалить пункт',
	'toplists-editor-add-item-label' => 'Добавить новый пункт',
	'toplists-editor-add-item-tooltip' => 'Добавить новый элемент в список',
	'toplists-create-button' => 'Создать список',
	'toplists-update-button' => 'Сохранить список',
	'toplists-cancel-button' => 'Отмена',
	'toplits-image-browser-no-picture-selected' => 'Не выбрано изображение',
	'toplits-image-browser-clear-picture' => 'Очистить изображение',
	'toplists-image-browser-upload-btn' => 'Выбрать',
	'toplists-list-creation-summary' => 'Создание списка, $1',
	'toplists-list-update-summary' => 'Обновление списка, $1',
	'toplists-item-creation-summary' => 'Создание элемента списка',
	'toplists-item-update-summary' => 'Обновление элемента списка',
	'toplists-list-add-item-label' => 'Добавить элемент',
	'toplists-createpage-dialog-label' => 'Список топ-10',
	'toplists-seconds' => '$1 {{PLURAL:$1|секунда|секунды|секунд}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|минута|минуты|минут}}',
	'toplists-hours' => '$1 {{PLURAL:$1|час|часа|часов}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|неделя|недели|недель}}',
	'toplists-create-button-msg' => 'Создать список',
);

/** Serbian Cyrillic ekavian (‪Српски (ћирилица)‬)
 * @author Rancher
 */
$messages['sr-ec'] = array(
	'edittoplist' => 'Уреди топ 10 листу',
	'toplists-category' => 'Топ 10 листе',
	'toplists-error-invalid-title' => 'Наведени текст није исправан.',
	'toplists-error-invalid-picture' => 'Наведена слика није исправна.',
	'toplists-error-empty-item-name' => 'Назив ставке не сме остати празан.',
	'toplists-item-cannot-delete' => 'Брисање ставке није успело.',
	'toplists-error-image-already-exists' => 'Слика с истим називом већ постоји.',
	'toplists-error-add-item-list-not-exists' => '„$1“ топ 10 листа не постоји.',
	'toplists-error-backslash-not-allowed' => 'Коса црта је забрањена у називу топ 10 листе.',
	'toplists-editor-title-label' => 'Назив списка',
	'toplists-editor-title-placeholder' => 'Унесите назив списка',
	'toplists-editor-related-article-label' => 'Сродна страница <small>(необавезно)</small>',
	'toplists-editor-related-article-placeholder' => 'Унесите назив странице',
	'toplists-editor-image-browser-tooltip' => 'Додајте слику',
	'toplists-editor-remove-item-tooltip' => 'Уклоните ставку',
	'toplists-create-button' => 'Направи списак',
	'toplists-update-button' => 'Сачувај списак',
	'toplists-cancel-button' => 'Откажи',
	'toplists-items-removed' => '$1 {{PLURAL:$1|ставка је уклоњена|ставке су уклоњене|ставки је уклоњено}}',
	'toplists-items-created' => '$1 {{PLURAL:$1|ставка је направљена|ставке су направљене|ставки је направљено}}',
	'toplists-items-updated' => '$1 {{PLURAL:$1|ставка је ажурирана|ставке су ажуриране|ставки је ажурирано}}',
	'toplists-items-nochange' => 'Нема измењених ставки',
	'toplits-image-browser-no-picture-selected' => 'Нема изабраних слика',
	'toplits-image-browser-clear-picture' => 'Очисти слику',
	'toplits-image-browser-selected-picture' => 'Изабрано: $1',
	'toplists-image-browser-upload-btn' => 'Изабери',
	'toplists-image-browser-upload-label' => 'Отпремање',
	'toplists-list-creation-summary' => 'Прављење списка, $1',
	'toplists-item-creation-summary' => 'Прављење списка ставки',
	'toplists-item-update-summary' => 'Ажурирање списка ставки',
	'toplists-item-remove-summary' => 'Ставка је уклоњена са списка',
	'toplists-item-restored' => 'Ставка је враћена',
	'toplists-list-related-to' => 'У вези са:',
	'toplists-list-created-by' => 'од члана [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Гласај',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|глас|гласа|гласова}} у $2',
	'toplists-list-add-item-label' => 'Додај ставку',
	'toplists-list-add-item-name-label' => 'Настави са списком...',
	'toplists-list-item-voted' => 'Гласано',
	'toplists-createpage-dialog-label' => 'Топ 10 листа',
	'toplists-email-subject' => 'Топ 10 листа је промењена',
	'toplists-seconds' => '$1 {{PLURAL:$1|секунда|секунде|секунди}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|минут|минута|минута}}',
	'toplists-hours' => '$1 {{PLURAL:$1|сат|сата|сати}}',
	'toplists-days' => '$1 {{PLURAL:$1|дан|дана|дана}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|недеља|недеље|недеља}}',
	'toplists-create-heading' => '<em>Ново!</em> Направите топ 10',
	'toplists-create-button-msg' => 'Направи списак',
);

/** Swedish (Svenska)
 * @author Tobulos1
 */
$messages['sv'] = array(
	'toplists-error-invalid-title' => 'Den medföljande texten är inte giltig.',
	'toplists-error-invalid-picture' => 'Den valda bilden är inte giltig.',
	'toplists-error-title-exists' => 'Den här sidan finns redan. Du kan gå till <a href="$2" target="_blank">$1</a> eller ge den ett annat namn.',
	'toplists-error-title-spam' => 'Den medföljande texten innehåller en del ord som räknas som spam.',
	'toplists-error-article-blocked' => 'Du har inte tillåtelse att skapa en sida med detta namn. Tyvärr.',
	'toplists-error-article-not-exists' => '"$1" existerar inte. Vill du <a href="$2" target="_blank">skapa den</a>?',
	'toplists-error-picture-not-exists' => '"$1" existerar inte. Vill du <a href="$2" target="_blank">ladda upp den</a>?',
	'toplists-error-duplicated-entry' => 'Du kan inte använda samma namn mer än en gång.',
	'toplists-error-empty-item-name' => 'Namnet på ett befintligt objekt kan inte vara tomt.',
	'toplists-item-cannot-delete' => 'Borttagning av detta objektet misslyckades.',
	'toplists-error-image-already-exists' => 'En bild med samma namn finns redan.',
	'toplists-error-add-item-anon' => 'Anonyma användare är inte tillåtna att lägga till objekt i listor. Vänligen <a class="ajaxLogin" id="login" href="$1">logga in</a> eller <a class="ajaxLogin" id="signup" href="$2">registrera ett konto</a>.',
	'toplists-error-add-item-permission' => 'Tillståndsfel: Ditt konto har inte beviljats rätten att skapa nya objekt.',
	'toplists-error-backslash-not-allowed' => 'Tecknet "/" är inte tillåtet i rubriken till en Topp 10 lista.',
	'toplists-upload-error-unknown' => 'Ett fel uppstod vid bearbetningen av uppladdningen. Försök igen.',
	'toplists-editor-title-label' => 'Listnamn',
	'toplists-editor-title-placeholder' => 'Ange ett namn för listan',
	'toplists-editor-related-article-label' => 'Relaterad sida <small>(valfritt, men väljer en bild)</small>',
	'toplists-editor-related-article-placeholder' => 'Ange ett befintligt namn för en sida',
	'toplists-editor-image-browser-tooltip' => 'Lägg till en bild',
	'toplists-editor-remove-item-tooltip' => 'Ta bort objekt',
	'toplists-editor-drag-item-tooltip' => 'Dra för att ändra ordning',
	'toplists-editor-add-item-label' => 'Lägg till ett nytt objekt',
	'toplists-editor-add-item-tooltip' => 'Lägg till ett nytt objekt i listan',
	'toplists-create-button' => 'Skapa lista',
	'toplists-update-button' => 'Spara listan',
	'toplists-cancel-button' => 'Avbryt',
	'toplists-items-removed' => '$1 {{PLURAL:$1|objekt|objekt}} borttagna',
	'toplists-items-created' => '$1 {{PLURAL:$1|objekt|objekt}} skapade',
	'toplists-items-updated' => '$1 {{PLURAL:$1|objekt|objekt}} uppdaterade',
	'toplists-items-nochange' => 'Inga objekt ändrades',
	'toplits-image-browser-no-picture-selected' => 'Ingen bild markerad',
	'toplits-image-browser-selected-picture' => 'Markerade: $1',
	'toplists-image-browser-upload-btn' => 'Välj',
	'toplists-image-browser-upload-label' => 'Ladda upp dina egna',
	'toplists-list-creation-summary' => 'Skapar en lista, $1',
	'toplists-list-update-summary' => 'Uppdaterar en lista, $1',
	'toplists-item-creation-summary' => 'Skapar ett listobjekt',
	'toplists-item-update-summary' => 'Uppdaterar ett listobjekt',
	'toplists-item-remove-summary' => 'Objekt raderat från listan',
	'toplists-item-restored' => 'Objekt återställt',
	'toplists-list-related-to' => 'Relaterat till:',
	'toplists-list-votes-num' => '{{PLURAL:$1|1<br />röst|$1<br />röster}}',
	'toplists-list-created-by' => 'av [[User:$1|$1]]',
	'toplists-list-vote-up' => 'Rösta upp',
	'toplists-list-hotitem-count' => '$1 {{PLURAL:$1|röst|röster}} i $2',
	'toplists-list-add-item-label' => 'Lägg till objekt',
	'toplists-list-add-item-name-label' => 'Håll igång listan...',
	'toplists-list-item-voted' => 'Röstat',
	'toplists-createpage-dialog-label' => 'Topp 10-lista',
	'toplists-email-body' => 'Ett hej från Wikia!

Listan <a href="$1">$2</a> på Wikia har blivit ändrad.

 $3

Besök Wikia för att kolla förändringarna! $1

- Wikia

Du kan <a href="$4">avbryta prenumerationen</a> från förändringslistan när du vill.',
	'toplists-seconds' => '$1 {{PLURAL:$1|sekund|sekunder}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|minut|minuter}}',
	'toplists-hours' => '$1 {{PLURAL:$1|timme|timmar}}',
	'toplists-days' => '$1 {{PLURAL:$1|dag|dagar}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|vecka|veckor}}',
	'toplists-msg-fb-OnRateArticle-short' => 'har röstat på Topp 10 listan på $WIKINAME!',
	'toplists-create-heading' => '<em>Nyhet!</em> Skapa Din Egen Topp Tio',
	'toplists-create-button-msg' => 'Skapa en lista',
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

/** Ukrainian (Українська) */
$messages['uk'] = array(
	'toplists-seconds' => '$1 {{PLURAL:$1|секунда|секунди|секунд}}',
	'toplists-minutes' => '$1 {{PLURAL:$1|хвилина|хвилини|хвилин}}',
	'toplists-hours' => '$1 {{PLURAL:$1|година|години|годин}}',
	'toplists-days' => '$1 {{PLURAL:$1|день|дні|днів}}',
	'toplists-weeks' => '$1 {{PLURAL:$1|тиждень|тижня|тижнів}}',
);

