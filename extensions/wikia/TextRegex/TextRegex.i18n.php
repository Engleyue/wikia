<?php
/**
 * Internationalisation file for extension TextRegex.
 *
 * @ingroup Extensions
 */

$messages = array();

/** English
 * @author Bartek Łapiński
 */
$messages['en'] = array(
	'textregex' => 'Text regex',
	'textregex-desc' => '[[Special:textregex/XXXX|Filter]] out unwanted phrases in edited pages, based on regular expressions',
	'textregex-page-title' => 'List of unwanted expressions',
	'textregex-error-unblocking' => 'Error unblocking ($1).
Try once again.',
	'textregex-currently-blocked' => "'''Currently blocked phrases:'''",
	'textregex_nocurrently-blocked' => 'No blocked phrases found',
	'textregex-addedby-user' => 'added by $1 on $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 remove]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statistics]',
	'textregex-unblock-succ' => 'Unblock succedeed',
	'textregex-block-succ' => 'Block succedeed',
	'textregex-unblock-message' => 'Phrase \'\'\'$1\'\'\' has been removed from unwanted expressions.',
	'textregex-block-message' => 'Phrase \'\'\'$1\'\'\' has been added to unwanted expressions.',
	'textregex-regex-block' => 'Phrase to block:',
	'textregex-submit-regex' => 'Add phrase',
	'textregex-empty-regex' => 'Give a proper phrase to block.',
	'textregex-invalid-regex' => 'Invalid regex.',
	'textregex-already-added' => '"$1" is already added',
	'textregex-nodata-found' => 'No data found',
	'textregex-stats-record' => "word ''$1'' was used by $2 on $3 (''comment: $4'')",
	'textregex-select-subpage' => 'Select one of list of phrases:',
	'textregex-select-default' => '-- select --',
	'textregex-create-subpage' => 'or create new list:',
	'textregex-select-regexlist' => 'go to the list',
	'textregex-invalid-regexid' => 'Invalid phrase.',
	'textregex-phrase-statistics' => 'Statistics for "\'\'\'$1\'\'\'" phrase (number of records: $2)',
	'textregex-return-mainpage' => '[{{SERVER}}$1 return to the list]',
);

/** Message documentation (Message documentation)
 * @author Siebrand
 */
$messages['qqq'] = array(
	'textregex-addedby-user' => 'If $2 is a time stamp, split date and time',
	'textregex-phrase-statistics' => 'Parameters:
* $1 is the regular expression text
* $2 is the number of records (can be used for plural support)',
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'textregex' => 'Teks regex',
	'textregex-desc' => '[[Special:textregex/XXXX|Filter]] ongewensde frases uit gewysigde bladsye, gebaseer op reguliere uitdrukkings',
	'textregex-page-title' => 'Lys van ongewenste reguliere uitdrukkings',
	'textregex-error-unblocking' => "'n Fout het voorgekom met die deblokkade ($1).
Probeer asseblief weer.",
	'textregex-currently-blocked' => "'''Frases wat tans geblokkeer word:'''",
	'textregex_nocurrently-blocked' => 'Geen geblokkeerde frases gevind nie',
	'textregex-addedby-user' => 'bygevoeg deur $1 op $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 verwyder]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statistieke]',
	'textregex-unblock-succ' => 'Die deblokkade is uitgevoer',
	'textregex-block-succ' => 'Die blokkade is uitgevoer',
	'textregex-unblock-message' => "Sinsnede '''$1''' is van die lys met ongewenste uitdrukkings verwyder.",
	'textregex-block-message' => "Sinsnede '''$1''' is by die lys van ongewenste uitdrukkings gevoeg.",
	'textregex-regex-block' => 'Regex-frase om te blokkeer:',
	'textregex-submit-regex' => 'Voeg regex by',
	'textregex-empty-regex' => "Verskaf 'n behoorlike regex om te blokkeer.",
	'textregex-invalid-regex' => 'Ongeldige regex.',
	'textregex-already-added' => '"$1" is reeds bygevoeg',
	'textregex-nodata-found' => 'Geen data gevind nie',
	'textregex-stats-record' => 'die woord "$1" is gebruik deur $2 in $3 (\'\'opmerking: $4\'\')',
	'textregex-select-subpage' => "Kies uit 'n lys van reguliere uitdrukkings:",
	'textregex-select-default' => '-- kies --',
	'textregex-create-subpage' => "of skep 'n nuwe lys:",
	'textregex-select-regexlist' => 'gaan na die lys',
	'textregex-invalid-regexid' => 'Ongeldige ID of regex.',
	'textregex-phrase-statistics' => "Statistieke vir die frase '''$1''' (aantal rekords: $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 terug na die lys]',
);

/** Breton (Brezhoneg)
 * @author Gwenn-Ael
 * @author Y-M D
 */
$messages['br'] = array(
	'textregex' => 'Regex testenn',
	'textregex-desc' => "[[Special:textregex/XXXX|Sil]] ar frazennoù dic'hoantaus er pajennoù, diazezet war eztaoladennoù poellel",
	'textregex-page-title' => 'Roll an eztaoladennoù strobus',
	'textregex-error-unblocking' => "Ur fazi 'zo bet e-pad an distankañ ($1). Klaskit adarre.",
	'textregex-currently-blocked' => "'''Frazennoù stanket evit bremañ :'''",
	'textregex_nocurrently-blocked' => "N'eo bet kavet frazenn stanket ebet",
	'textregex-addedby-user' => "bet ouzhpennet gant $1 d'an $2",
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 dilemel]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 stadegoù]',
	'textregex-unblock-succ' => 'Graet eo an distankadur',
	'textregex-block-succ' => 'Graet eo ar stankadur',
	'textregex-unblock-message' => "Dilemet eo bet ar frazenn '''$1''' deus ar frazennoù ne vez ket c'hoant outo.",
	'textregex-block-message' => "Ouzhpennet eo bet ar frazenn '''$1''' d'ar frazennoù ne vez ket c'hoant outo.",
	'textregex-regex-block' => 'Frazenn da stankañ :',
	'textregex-submit-regex' => 'Ouzhpennañ ur frazenn',
	'textregex-empty-regex' => 'Reiñ un eztaoladenn da stankañ.',
	'textregex-invalid-regex' => "N'eo ket mat ar regex.",
	'textregex-already-added' => 'Ouzhpennet eo bet "$1" dija',
	'textregex-nodata-found' => "N'eus ket bet kavet roadennoù",
	'textregex-stats-record' => "ar ger''$1'' a oa bet implijet gant $2 war $3 (''displegadenn: $4'')",
	'textregex-select-subpage' => 'Dibab unan eus frazennoù al listenn :',
	'textregex-select-default' => '-- dibab --',
	'textregex-create-subpage' => 'pe krouit ur roll nevez :',
	'textregex-select-regexlist' => "mont d'ar roll",
	'textregex-invalid-regexid' => 'Frazenn fall.',
	'textregex-phrase-statistics' => "Stadegoù evit ar frazenn '''$1''' (niver a enrolladennoù : $2)",
	'textregex-return-mainpage' => "[{{SERVIJER}}$1 distreiñ d'ar roll.]",
);

/** Finnish (Suomi)
 * @author Centerlink
 */
$messages['fi'] = array(
	'textregex-page-title' => 'Luettelo ei-toivotuista lausekkeista',
	'textregex-submit-regex' => 'Lisää fraasi',
	'textregex-select-default' => '-- valitse --',
	'textregex-create-subpage' => 'tai luo uusi luettelo:',
	'textregex-select-regexlist' => 'siirry luetteloon',
	'textregex-invalid-regexid' => 'Virheellinen fraasi.',
);

/** French (Français)
 * @author IAlex
 */
$messages['fr'] = array(
	'textregex' => 'Expression rationnelle de texte',
	'textregex-desc' => '[[Special:textregex/XXXX|Filtre]] des phrases indésirables dans les pages, basé sur des expression rationnelles',
	'textregex-page-title' => "Liste d'expressions indésirables",
	'textregex-error-unblocking' => 'Erreur lors du déblocage ($1). Essayez encore une fois.',
	'textregex-currently-blocked' => "'''Phrases actuellement bloqués :'''",
	'textregex_nocurrently-blocked' => 'Aucune phrase bloquée trouvée',
	'textregex-addedby-user' => 'ajouté par $1 le $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 supprimer]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statistiques]',
	'textregex-unblock-succ' => 'Le déblocage a réussi',
	'textregex-block-succ' => 'Le blocage a réussi',
	'textregex-unblock-message' => "La phrase '''$1''' a été supprimée des expressions indésirables.",
	'textregex-block-message' => "La phrase '''$1''' a été ajoutée aux expressions indésirables.",
	'textregex-regex-block' => 'Expression rationnelle à bloquer :',
	'textregex-submit-regex' => 'Ajouter une expression rationnelle',
	'textregex-empty-regex' => 'Donnez une expression rationnelle à bloquer correcte.',
	'textregex-invalid-regex' => 'Expression rationnelle invalide.',
	'textregex-already-added' => '« $1 » a déjà été ajouté',
	'textregex-nodata-found' => "Aucune donnée n'a été trouvée",
	'textregex-stats-record' => "le mot ''$1'' a été utilisé par $2 le $3 (''commentaire : $4'')",
	'textregex-select-subpage' => 'Sélectionnez une expression rationnelle de la liste :',
	'textregex-select-default' => '-- sélectionner --',
	'textregex-create-subpage' => 'ou créez une nouvelle liste :',
	'textregex-select-regexlist' => 'aller à la dernière',
	'textregex-invalid-regexid' => "Identifiant d'expression rationnelle invalide.",
	'textregex-phrase-statistics' => "Statistiques pour la phrase '''$1''' (nombre d'enregistrements : $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 revenir à la liste]',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'textregex' => 'Expresión regular de texto',
	'textregex-desc' => '[[Special:textregex/XXXX|Filtrar]] frases non desexadas nas páxinas editadas, baseándose en expresións regulares',
	'textregex-page-title' => 'Lista de expresións non desexadas',
	'textregex-error-unblocking' => 'Erro ao desbloquear ($1). Inténteo de novo.',
	'textregex-currently-blocked' => "'''Frases actualmente bloqueadas:'''",
	'textregex_nocurrently-blocked' => 'Non se atopou ningunha frase bloqueada',
	'textregex-addedby-user' => 'engadido por $1 o $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 eliminar]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 estatísticas]',
	'textregex-unblock-succ' => 'O desbloqueo tivo éxito',
	'textregex-block-succ' => 'O bloqueo tivo éxito',
	'textregex-unblock-message' => "A frase \"'''\$1'''\" eliminouse das expresións non desexadas.",
	'textregex-block-message' => "A frase \"'''\$1'''\" engadiuse ás expresións non desexadas.",
	'textregex-regex-block' => 'Expresión regular a bloquear:',
	'textregex-submit-regex' => 'Engadir unha expresión regular',
	'textregex-empty-regex' => 'Dea unha expresión regular axeitada para bloqueala.',
	'textregex-invalid-regex' => 'Expresión regular incorrecta.',
	'textregex-already-added' => '"$1" xa se engadiu',
	'textregex-nodata-found' => 'Non se atopou ningún dato',
	'textregex-stats-record' => "a palabra \"'''\$1'''\" foi empregada por \$2 o \$3 (''comentario: \$4'')",
	'textregex-select-subpage' => 'Seleccione unha expresión regular da lista:',
	'textregex-select-default' => '-- seleccione --',
	'textregex-create-subpage' => 'ou cree unha nova lista:',
	'textregex-select-regexlist' => 'ir á lista',
	'textregex-invalid-regexid' => 'O identificador da expresión regular non é válido.',
	'textregex-phrase-statistics' => "Estatísticas para a frase \"'''\$1'''\" (número de rexistros: \$2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 voltar á lista]',
);

/** Hungarian (Magyar)
 * @author Glanthor Reviol
 */
$messages['hu'] = array(
	'textregex-page-title' => 'Nemkívánatos kifejezések listája',
	'textregex-addedby-user' => 'hozzáadta $1 ekkor: $2',
	'textregex-unblock-succ' => 'A blokk feloldása sikeres',
	'textregex-block-succ' => 'A blokk sikeres',
	'textregex-submit-regex' => 'Reguláris kifejezés hozzáadása',
	'textregex-invalid-regex' => 'Érvénytelen reguláris kifejezés.',
	'textregex-nodata-found' => 'Nem található adat',
	'textregex-select-subpage' => 'Válassz egyet a reguláris kifejezések listájából:',
	'textregex-select-default' => '–– kiválasztás ––',
	'textregex-create-subpage' => 'vagy új lista készítése:',
	'textregex-select-regexlist' => 'ugrás a listára',
	'textregex-invalid-regexid' => 'Érvénytelen reguláris kifejezés azonosító',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'textregex' => 'Regex texto',
	'textregex-desc' => '[[Special:textregex/XXXX|Filtrar]] phrases indesirabile in paginas modificate, a base de expressiones regular',
	'textregex-page-title' => 'Lista de expressiones indesirabile',
	'textregex-error-unblocking' => 'Error durante le disblocada ($1). Proba lo de novo.',
	'textregex-currently-blocked' => "'''Phrases actualmente blocate:'''",
	'textregex_nocurrently-blocked' => 'Nulle phrase blocate trovate',
	'textregex-addedby-user' => 'addite per $1 a $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 remover]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statisticas]',
	'textregex-unblock-succ' => 'Disblocada succedite',
	'textregex-block-succ' => 'Blocada succedite',
	'textregex-unblock-message' => "Le phrase '''$1''' ha essite removite del expressiones indesirabile.",
	'textregex-block-message' => "Le phrase '''$1''' ha essite addite al phrases indesirabile.",
	'textregex-regex-block' => 'Phrase a blocar:',
	'textregex-submit-regex' => 'Adder phrase',
	'textregex-empty-regex' => 'Da un phrase appropriate a blocar.',
	'textregex-invalid-regex' => 'Regex invalide.',
	'textregex-already-added' => '"$1" ha ja essite addite',
	'textregex-nodata-found' => 'Nulle datos trovate',
	'textregex-stats-record' => "le parola ''$1'' esseva usate per $2 le $3 (''commento: $4'')",
	'textregex-select-subpage' => 'Selige un phrase del lista:',
	'textregex-select-default' => '-- seliger --',
	'textregex-create-subpage' => 'o crea un nove lista:',
	'textregex-select-regexlist' => 'ir al lista',
	'textregex-invalid-regexid' => 'Phrase invalide.',
	'textregex-phrase-statistics' => "Statisticas pro le phrase '''$1''' (numero de registros: $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 retornar al lista]',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'textregex' => 'Регуларен израз за текст',
	'textregex-desc' => '[[Special:textregex/XXXX|Филтрирање]] на непожелни изрази во уредуваните страници, на основа на регуларни изрази',
	'textregex-page-title' => 'Листа на непожелни изрази',
	'textregex-error-unblocking' => 'Грешка при одблокирањето ($1). Обидете се повторно.',
	'textregex-currently-blocked' => "'''Моментално блокирани изрази:'''",
	'textregex_nocurrently-blocked' => 'Нема пронајдено блокирани изрази',
	'textregex-addedby-user' => 'додадено од $1 на $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 отстрани]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 статистики]',
	'textregex-unblock-succ' => 'Одблокирањето успеа',
	'textregex-block-succ' => 'Блокирањето успеа',
	'textregex-unblock-message' => "Изразот '''$1''' е отстранет од листата на непожелни изрази.",
	'textregex-block-message' => "Изразот '''$1''' е додаден во листата на непожелни изрази.",
	'textregex-regex-block' => 'Регуларен израз за блокирање:',
	'textregex-submit-regex' => 'Додај регуларен израз',
	'textregex-empty-regex' => 'Назначете правилен регуларен израз за блокирање.',
	'textregex-invalid-regex' => 'Неважечки регуларен израз.',
	'textregex-already-added' => '„$1“ е веќе додадено',
	'textregex-nodata-found' => 'Нема пронајдено податоци',
	'textregex-stats-record' => "зборот ''$1'' е употребен од $2 на $3 (''коментар: $4'')",
	'textregex-select-subpage' => 'Одберете еден регуларен израз од листата:',
	'textregex-select-default' => '-- одберете --',
	'textregex-create-subpage' => 'или создајте нова листа:',
	'textregex-select-regexlist' => 'оди на листата',
	'textregex-invalid-regexid' => 'Неважечки идентификатор на регуларниот израз.',
	'textregex-phrase-statistics' => "Статистики за изразот '''$1''' (број на записи: $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 назад кон листата]',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'textregex' => 'Tekst reguliere expressie',
	'textregex-desc' => "[[Special:textregex/XXXX|Ongewenste tekstdelen filteren]] in bewerkte pagina's, gebaseerd op reguliere expressies",
	'textregex-page-title' => 'Lijst met ongewenste reguliere expressies',
	'textregex-error-unblocking' => 'Er is een fout opgetreden bij het deblokkeren ($1).
Probeer het opnieuw.',
	'textregex-currently-blocked' => "'''Op dit moment geblokkeerde tekstdelen:'''",
	'textregex_nocurrently-blocked' => 'Er zijn geen geblokkeerde tekstdelen gevonden',
	'textregex-addedby-user' => 'toegevoegd door $1 op $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 verwijderen]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statistieken]',
	'textregex-unblock-succ' => 'De deblokkade is uitgevoerd',
	'textregex-block-succ' => 'De blokkade is uitgevoerd',
	'textregex-unblock-message' => "Het tekstdeel '''$1''' is verwijderd van de lijst met ongewenste teksten.",
	'textregex-block-message' => "Het tekstdeel '''$1''' is toegevoegd aan de lijst met ongewenste teksten.",
	'textregex-regex-block' => 'Te blokkeren reguliere expressie:',
	'textregex-submit-regex' => 'Reguliere expressie toevoegen',
	'textregex-empty-regex' => 'Geen een correcte te blokkeren reguliere expressie op.',
	'textregex-invalid-regex' => 'Ongeldige reguliere expressie',
	'textregex-already-added' => '"$1" is al toegevoegd',
	'textregex-nodata-found' => 'Geen gegevens gevonden',
	'textregex-stats-record' => 'het woord "$1" is gebruikt door $2 in $3 (\'\'opmerking: $4\'\')',
	'textregex-select-subpage' => 'Selecteer uit een lijst van reguliere expressies:',
	'textregex-select-default' => '-- selecteren --',
	'textregex-create-subpage' => 'of maak een nieuwe lijst:',
	'textregex-select-regexlist' => 'ga naar de lijst',
	'textregex-invalid-regexid' => 'Ongeldig herkenningsteken of ongeldige reguliere expressie.',
	'textregex-phrase-statistics' => "Statistieken voor het tekstdeel '''$1''' (aantal records: $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 terug naar de lijst]',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'textregex' => 'Regulære uttrykk for tekst',
	'textregex-desc' => '[[Special:textregex/XXXX|Filtrer]] ut uønskede fraser fra redigerte sider basert på regulære uttrykk',
	'textregex-page-title' => 'Liste over uønskede uttrykk',
	'textregex-error-unblocking' => 'Feil ved oppheving av blokkering ($1).
Prøv igjen.',
	'textregex-currently-blocked' => "'''Nåværende blokkerte fraser:'''",
	'textregex_nocurrently-blocked' => 'Ingen blokkerte fraser funnet',
	'textregex-addedby-user' => 'lagt til av $1, $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 fjern]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statistikk]',
	'textregex-unblock-succ' => 'Oppheving av blokkering lyktes',
	'textregex-block-succ' => 'Blokkering lyktes',
	'textregex-unblock-message' => "Frasen '''$1''' har blitt fjernet fra uønskede uttrykk.",
	'textregex-block-message' => "Frasen '''$1''' har blitt lagt til uønskede uttrykk.",
	'textregex-regex-block' => 'Frase som skal blokkeres:',
	'textregex-submit-regex' => 'Legg til frase',
	'textregex-empty-regex' => 'Oppgi en ordentlig frase som skal blokkeres.',
	'textregex-invalid-regex' => 'Ugyldig regulært uttrykk.',
	'textregex-already-added' => '«$1» er allerede lagt til',
	'textregex-nodata-found' => 'Ingen data funnet',
	'textregex-stats-record' => "ordet ''$1'' ble brukt av $2, $3 (''kommentar: $4'')",
	'textregex-select-subpage' => 'Velg en liste med fraser:',
	'textregex-select-default' => '-- velg --',
	'textregex-create-subpage' => 'eller opprett en ny liste:',
	'textregex-select-regexlist' => 'gå til listen',
	'textregex-invalid-regexid' => 'Ugyldig frase.',
	'textregex-return-mainpage' => '[{{SERVER}}$1 gå tilbake til listen]',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 */
$messages['pms'] = array(
	'textregex' => 'Regex (Espression Regolar) ëd test',
	'textregex-desc' => "[[Special:textregex/XXXX|Filtra]] le fras pa vorsùe ant le pàgine modificà, basà dzora a d'espression regolar",
	'textregex-page-title' => "Lista dj'espression nen vorsùe",
	'textregex-error-unblocking' => "Eror an dësblocand ($1). Preuva n'àutra vira.",
	'textregex-currently-blocked' => "'''Fras blocà al moment:'''",
	'textregex_nocurrently-blocked' => 'Pa trovà gnun-e fras blocà',
	'textregex-addedby-user' => 'giontà da $1 su $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 gava]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 statìstiche]',
	'textregex-unblock-succ' => 'Dësblocagi andàit bin',
	'textregex-block-succ' => 'Blocagi andàit bin',
	'textregex-unblock-message' => "La fras '''$1''' a l'é stàita gavà da j'espression pa vorsùe.",
	'textregex-block-message' => "La fras '''$1''' a l'é stàita giontà a j'espression pa vorsùe.",
	'textregex-regex-block' => "Fras d'espression regolar da bloché:",
	'textregex-submit-regex' => 'Gionta espression regolar',
	'textregex-empty-regex' => "Dà n'espression regolar bon-a da bloché.",
	'textregex-invalid-regex' => 'Espression regolar pa bon-a.',
	'textregex-already-added' => '"$1" a l\'é già giontà.',
	'textregex-nodata-found' => 'Pa gnun dat trovà',
	'textregex-stats-record' => "la paròla ''$1'' a l'era dovrà da $2 su $3 (''coment: $4'')",
	'textregex-select-subpage' => "Selession-a na lista d'espression regolar:",
	'textregex-select-default' => '-- selession-a --',
	'textregex-create-subpage' => 'o crea na lista neuva:',
	'textregex-select-regexlist' => 'va a la lista',
	'textregex-invalid-regexid' => "Identificator d'espression regolar pa bon.",
	'textregex-phrase-statistics' => "Statìstiche për la fras '''$1''' (nùmer d'argistrassion: $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 artorna a la lista]',
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Jesielt
 * @author Luckas Blade
 */
$messages['pt-br'] = array(
	'textregex' => 'Texto regex (expressões regulares)',
	'textregex-desc' => '[[Special:textregex/XXXX|Filtrar]] frases indesejadas em páginas editadas, baseado em expressões regulares',
	'textregex-page-title' => 'Lista de expressões indesejadas',
	'textregex-error-unblocking' => 'Erro ao desbloquear ($1).
Tente novamente.',
	'textregex-currently-blocked' => "'''Frases atualmente bloqueadas:'''",
	'textregex_nocurrently-blocked' => 'Nenhuma frase bloqueada encontrada',
	'textregex-addedby-user' => 'adicionado por $1 em $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 remover]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 estatísticas]',
	'textregex-unblock-succ' => 'Desbloqueio efetuado',
	'textregex-block-succ' => 'Bloqueio efetuado',
	'textregex-unblock-message' => "A frase '''$1''' foi removida da list ade expressões indesejadas.",
	'textregex-block-message' => "A frase '''$1''' foi adicionada à lista de expressões indesejadas.",
	'textregex-regex-block' => 'Frase a bloquear:',
	'textregex-submit-regex' => 'Adicionar frase',
	'textregex-empty-regex' => 'Forneça uma frase a bloquear.',
	'textregex-invalid-regex' => 'Regex (expressão regular) inválida.',
	'textregex-already-added' => '"$1" já está bloqueado',
	'textregex-nodata-found' => 'Nenhum dado encontrado',
	'textregex-stats-record' => "A palavra ''$1'' foi usada por $2 em $3 (''comentário: $4'')",
	'textregex-select-subpage' => 'Selecione uma da lista de frases:',
	'textregex-select-default' => '-- selecionar --',
	'textregex-create-subpage' => 'ou crie uma nova lista:',
	'textregex-select-regexlist' => 'ir para a lista',
	'textregex-invalid-regexid' => 'Frase inválida.',
	'textregex-phrase-statistics' => "Estatísticas para a frase \"'''\$1'''\" (número de gravações: \$2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 retornar à lista]',
);

/** Russian (Русский)
 * @author Lockal
 */
$messages['ru'] = array(
	'textregex' => 'Регулярные выражения для текста',
	'textregex-desc' => '[[Special:textregex/XXXX|Фильтрация]] фраз на редактируемых страницах, основанная на регулярных выражениях',
	'textregex-page-title' => 'Список нежелательных выражений',
	'textregex-error-unblocking' => 'Ошибка при разблокировании ($1). Попробуйте ещё раз.',
	'textregex-currently-blocked' => "'''В настоящий момент блокируются фразы:'''",
	'textregex_nocurrently-blocked' => 'Заблокированные фразы не найдены',
	'textregex-addedby-user' => 'добавлена $1 в $2',
	'textregex-remove-url' => '[{{SERVER}}$1&id=$2 удалить]',
	'textregex-stats-url' => '[{{SERVER}}$1&id=$2 статистика]',
	'textregex-unblock-succ' => 'Разблокировка выполнена успешно',
	'textregex-block-succ' => 'Блокировка выполнена успешно',
	'textregex-unblock-message' => "Фраза '''$1''' удалена из списка нежелательных регулярных выражений.",
	'textregex-block-message' => "Фраза '''$1''' добавлена в список нежелательных регулярных выражений.",
	'textregex-regex-block' => 'Блокировать фразу:',
	'textregex-submit-regex' => 'Добавить фразу',
	'textregex-empty-regex' => 'Задайте подходящую фразу для блокировки.',
	'textregex-invalid-regex' => 'Неверное регулярное выражение.',
	'textregex-already-added' => '«$1» уже добавлена',
	'textregex-nodata-found' => 'Ничего не найдено',
	'textregex-stats-record' => "слово ''$1'' использовано $2 в $3 (''комментарий: $4'')",
	'textregex-select-subpage' => 'Выберите из списка фраз:',
	'textregex-select-default' => '-- выбрать --',
	'textregex-create-subpage' => 'или создайте новый список:',
	'textregex-select-regexlist' => 'перейти к списку',
	'textregex-invalid-regexid' => 'Неверное фраза.',
	'textregex-phrase-statistics' => "Статистика для фразы '''$1''' (число записей: $2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 Возврат к списку]',
);

/** Serbian Cyrillic ekavian (Српски (ћирилица))
 * @author Verlor
 */
$messages['sr-ec'] = array(
	'textregex-page-title' => 'листа непожељних израза',
	'textregex-currently-blocked' => "'''тренутно блокиране фразе:'''",
	'textregex_nocurrently-blocked' => 'Нису нађене блокиране фразе',
	'textregex-addedby-user' => 'додао $1 на $2',
	'textregex-unblock-succ' => 'Деблокирање успешно',
	'textregex-block-succ' => 'Блокирање је успело',
	'textregex-unblock-message' => "Фраза '''$1''' уклоњена је са списак непожељних израза",
	'textregex-block-message' => "Фраза '''$1''' додана је на листу непожељних порука",
	'textregex-regex-block' => 'Фраза за блокирање:',
	'textregex-submit-regex' => 'Додај фразу',
	'textregex-empty-regex' => 'Наведите фразу коју треба блокирати.',
	'textregex-already-added' => '$1" је већ блокирано',
	'textregex-nodata-found' => 'Нису нађени подаци',
	'textregex-select-subpage' => 'Одабери један списак фраза',
	'textregex-select-default' => '-- одабери --',
	'textregex-create-subpage' => 'или направи нови списак',
	'textregex-select-regexlist' => 'Повратак на списак',
	'textregex-invalid-regexid' => 'неважећа фраза',
	'textregex-phrase-statistics' => "Статистика за \"'''\$1'''\"  фразу (број појављивања: \$2)",
	'textregex-return-mainpage' => '[{{SERVER}}$1 повратак на списак]',
);

