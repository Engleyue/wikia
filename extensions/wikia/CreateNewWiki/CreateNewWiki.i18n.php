<?php
/**
* Internationalisation file for the CreateNewWiki extension.
*
* @addtogroup Languages
*/

$messages = array();

$messages['en'] = array(
	// general messages
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Wiki creation wizard]]',
	'cnw-next' => 'Next',
	'cnw-back' => 'Back',
	'cnw-or' => 'or',
	'cnw-title' => 'Create New Wiki',
	// step1 - create a wiki
	'cnw-name-wiki-headline' => 'Start a Wiki',
	'cnw-name-wiki-creative' => 'Wikia is the best place to build a website and grow a community around what you love.',
	'cnw-name-wiki-label' => 'Name your wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Give your wiki an address',
	'cnw-name-wiki-language' => '',
	'cnw-name-wiki-domain' => '.wikia.com',
	'cnw-name-wiki-submit-error' => 'Oops! You need to fill in both of the boxes above to keep going.',
	// step2 - signup/login
	'cnw-login' => 'Log In',
	'cnw-signup' => 'Create Account',
	'cnw-signup-prompt' => 'Need an account?',
	'cnw-call-to-signup' => 'Sign up here',
	'cnw-login-prompt' => 'Already have an account?',
	'cnw-call-to-login' => 'Log in here',
	'cnw-auth-headline' => 'Log In',
	'cnw-auth-headline2' => 'Sign Up',
	'cnw-auth-creative' => 'Log in to your account to continue building your wiki.',
	'cnw-auth-signup-creative' => 'You\'ll need an account to continue building your wiki.<br />It only takes a minute to sign up!',
	'cnw-auth-facebook-signup' => 'Sign up with Facebook',
	'cnw-auth-facebook-login' => 'Login with Facebook',
	// step3 - wiki description
	'cnw-desc-headline' => 'What\'s your wiki about?',
	'cnw-desc-creative' => 'Describe your topic',
	'cnw-desc-placeholder' => 'This will appear on the main page of your wiki.',
	'cnw-desc-tip1' => 'Hint',
	'cnw-desc-tip1-creative' => 'Use this space to tell people about your wiki in a sentence or two',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Give your visitors some specific details about your subject',
	'cnw-desc-tip3' => 'Pro Tip',
	'cnw-desc-tip3-creative' => 'Let people know they can help your wiki grow by editing and adding pages',
	'cnw-desc-choose' => 'Choose a category',
	'cnw-desc-select-one' => 'Select one',
	'cnw-desc-default-lang' => 'Your wiki will be in $1',
	'cnw-desc-change-lang' => 'change',
	'cnw-desc-lang' => 'Language',
	'cnw-desc-wiki-submit-error' => 'Please choose a category',
	// step4 - select theme
	'cnw-theme-headline' => 'Choose a theme',
	'cnw-theme-creative' => 'Choose a theme below, you\'ll be able to see a preview of each theme as you select it.',
	'cnw-theme-instruction' => 'You can also design your own theme later by going to "My Tools".',
	// step5 - upgrade
	'cnw-upgrade-headline' => 'Do you want to upgrade?',
	'cnw-upgrade-creative' => 'Upgrading to Wikia Plus allows you to remove ads from <span class="wiki-name"></span>, a one time offer only available to new founders.',
	'cnw-upgrade-marketing' => 'Wikia Plus is a great solution for:<ul>
<li>Professional Wikis</li>
<li>Non-profits</li>
<li>Families</li>
<li>Schools</li>
<li>Personal projects</li>
</ul>
Upgrade through PayPal to get an ad-free wiki for only $4.95 per month!',
	'cnw-upgrade-now' => 'Upgrade Now',
	'cnw-upgrade-decline' => 'No thanks, continue to my wiki',
	// wiki welcome message
	'cnw-welcome-headline' => 'Congratulations! $1 has been created',
	'cnw-welcome-instruction1' => 'Click the button below to start adding pages to your wiki.',
	'cnw-welcome-instruction2' => 'You\'ll see this button throughout your wiki, use it any time you want to add a new page.',
	'cnw-welcome-help' => 'Find answers, advice, and more on <a href="http://community.wikia.com">Community Central</a>.',
	// error messages
	'cnw-error-general' => 'Something went wrong while creating your wiki.  Please try again later.',
	'cnw-error-general-heading' => 'Create New Wiki Error',
	'cnw-error-database' => 'Database Error: $1',
	'cnw-badword-header' => 'Whoa there',
	'cnw-badword-msg' => 'Hi, please refrain from using these bad words or banned words in your Wiki Description: $1',
	'cnw-error-wiki-limit-header' => 'Wiki limit reached',
	'cnw-error-wiki-limit' => 'Hi, you are limited to $1 wiki creation per day.  Wait 24 hours before creating another wiki.',
	'cnw-error-blocked-header' => 'Account blocked',
	'cnw-error-blocked' => 'You have been blocked by $1. The reason given was: $2. (Block ID for reference: $3)',
	'cnw-error-torblock' => 'Creating wikis via the Tor Network is not allowed.',
);

/** Message documentation (Message documentation)
 * @author Siebrand
 * @author Wyz
 */
$messages['qqq'] = array(
	'cnw-next' => 'Text for "Next" Button',
	'cnw-back' => 'Text for "Back" Button',
	'cnw-or' => 'Division for login or Facebook login',
	'cnw-title' => 'General Title for this feature',
	'cnw-name-wiki-headline' => 'H1 for this step',
	'cnw-name-wiki-creative' => 'Creative or instruction for this step following H1',
	'cnw-name-wiki-label' => 'Label for wiki name field',
	'cnw-name-wiki-wiki' => '"Wiki"',
	'cnw-name-wiki-domain-label' => 'Label for wiki domain field',
	'cnw-name-wiki-submit-error' => 'Error message to display when the there are errors in the fields',
	'cnw-login' => 'Text for "Log In" Button',
	'cnw-signup' => 'Text for "Create account" Button',
	'cnw-signup-prompt' => 'ask if user needs to create an account',
	'cnw-call-to-signup' => 'Call to action to create an account (clickable link)',
	'cnw-login-prompt' => 'ask if user already has a login',
	'cnw-call-to-login' => 'Call to action to login (clickable link)',
	'cnw-auth-headline' => 'H1 for this step',
	'cnw-auth-creative' => 'Creative or instruction for this step following H1 for login',
	'cnw-auth-signup-creative' => 'Creative or instruction for this step following H1 for signup',
	'cnw-auth-facebook-signup' => '"Sign up with Facebook" Button',
	'cnw-auth-facebook-login' => '"Login with Facebook" Button',
	'cnw-desc-headline' => 'H1 for this step',
	'cnw-desc-creative' => 'Creative or instruction for this step following H1',
	'cnw-desc-placeholder' => 'Placeholder for the textarea',
	'cnw-desc-tip1' => 'First Tip label',
	'cnw-desc-tip1-creative' => 'The first tip<br />
Be carefull to keep it short as there are 3 successive balloon tips to display in a small space',
	'cnw-desc-tip2' => 'Second Tip label',
	'cnw-desc-tip2-creative' => 'The second tip<br />
Be carefull to keep it short as there are 3 successive balloon tips to display in a small space',
	'cnw-desc-tip3' => 'Third Tip label',
	'cnw-desc-tip3-creative' => 'The third tip<br />
Be carefull to keep it short as there are 3 successive balloon tips to display in a small space',
	'cnw-desc-choose' => 'Label for category',
	'cnw-desc-select-one' => 'Default empty label for category',
	'cnw-desc-default-lang' => 'Letting user know which language this wiki will be in.  $1 will be wiki language',
	'cnw-desc-change-lang' => 'Call to action to change the language',
	'cnw-desc-lang' => 'Label for language',
	'cnw-desc-wiki-submit-error' => 'General error message for not selecting category',
	'cnw-theme-headline' => 'H1 for this step',
	'cnw-theme-creative' => 'Creative or instruction for this step following H1',
	'cnw-theme-instruction' => 'Details on how Toolbar can be used as an alternative later',
	'cnw-upgrade-headline' => 'H1 for this step',
	'cnw-upgrade-creative' => 'Creative for this step. Leave the span in there for wiki name',
	'cnw-upgrade-marketing' => 'Marketing Pitch for Wikia plus upgrade',
	'cnw-upgrade-now' => 'Call to action button to upgrade to Wikia plus',
	'cnw-upgrade-decline' => 'Wikia plus rejection',
	'cnw-welcome-headline' => 'Headliner for modal. $1 is wikiname',
	'cnw-welcome-instruction1' => 'First line of instruction to add a page',
	'cnw-welcome-instruction2' => 'Second line of instruction to add a page after the button',
	'cnw-welcome-help' => 'Message to Community central with embedded anchor. (leave blank if community does not exist)',
	'cnw-error-general' => 'Generic error message to alert users that something went wrong while creating wiki',
	'cnw-error-general-heading' => 'Heading for generic error in modal dialog',
	'cnw-error-blocked' => 'Parameters:
* $1 is a username
* $2 is a block reason
* $3 is a block ID',
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'cnw-next' => 'Volgende',
	'cnw-back' => 'Vorige',
	'cnw-or' => 'of',
	'cnw-title' => "Skep 'n nuwe wiki",
	'cnw-name-wiki-headline' => "Begin 'n Wiki",
	'cnw-signup' => 'Skep gebruiker',
	'cnw-auth-facebook-login' => 'Teken aan met Facebook',
	'cnw-desc-headline' => 'Waaroor gaan u wiki?',
	'cnw-desc-creative' => 'Beskryf u onderwerp',
	'cnw-desc-tip1' => 'Wenk',
	'cnw-desc-tip2' => 'Pst!',
	'cnw-desc-tip3' => 'Protip',
	'cnw-desc-choose' => "Kies 'n kategorie",
	'cnw-desc-select-one' => 'Kies een',
	'cnw-desc-default-lang' => 'Die hooftaal van u wiki is: $1',
	'cnw-desc-change-lang' => 'wysig',
	'cnw-desc-lang' => 'Taal',
	'cnw-desc-wiki-submit-error' => "Kies 'n kategorie",
	'cnw-theme-headline' => 'Ontwerp u wiki',
	'cnw-upgrade-headline' => 'Wil u opgradeer?',
	'cnw-upgrade-now' => 'Opgradeer nou',
	'cnw-badword-header' => 'Pas op!',
);

/** Arabic (العربية)
 * @author OsamaK
 */
$messages['ar'] = array(
	'cnw-next' => 'التالي',
	'cnw-back' => 'السابق',
	'cnw-or' => 'أو',
	'cnw-title' => 'إنشاء ويكي جديدة',
	'cnw-name-wiki-headline' => 'بدء ويكي',
	'cnw-signup' => 'أنشئ حسابا',
	'cnw-desc-lang' => 'اللغة',
);

/** Azerbaijani (Azərbaycanca)
 * @author Cekli829
 */
$messages['az'] = array(
	'cnw-next' => 'Növbəti',
	'cnw-back' => 'Əvvəlki',
	'cnw-name-wiki-wiki' => 'Viki',
	'cnw-login' => 'Daxil ol',
	'cnw-auth-headline' => 'Daxil ol',
	'cnw-desc-lang' => 'Dil',
);

/** Bulgarian (Български)
 * @author DCLXVI
 */
$messages['bg'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Съветник за създаване на уики]]',
	'cnw-back' => 'Връщане',
	'cnw-or' => 'или',
	'cnw-title' => 'Създаване на ново уики',
	'cnw-name-wiki-headline' => 'Създаване на уики',
	'cnw-name-wiki-wiki' => 'Уики',
	'cnw-login' => 'Влизане',
	'cnw-signup' => 'Създаване на сметка',
	'cnw-auth-headline' => 'Влизане',
	'cnw-auth-headline2' => 'Регистриране',
	'cnw-auth-facebook-signup' => 'Регистриране чрез Facebook',
	'cnw-auth-facebook-login' => 'Влизане с Facebook',
	'cnw-desc-lang' => 'Език',
);

/** Breton (Brezhoneg)
 * @author Fohanno
 * @author Fulup
 * @author Y-M D
 */
$messages['br'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Skoazeller evit krouiñ wikioù]]',
	'cnw-next' => "War-lerc'h",
	'cnw-back' => 'A-raok',
	'cnw-or' => 'pe',
	'cnw-title' => 'Krouiñ ur wiki nevez',
	'cnw-name-wiki-headline' => 'Kregiñ gant ur wiki',
	'cnw-name-wiki-creative' => "Wikia eo al lec'h gwellañ evit sevel ul lec'hienn wiki ha lakaat ur gumuniezh da greskiñ en-dro d'ar pezh a garit.",
	'cnw-name-wiki-label' => "Roit un anv d'ho wiki",
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => "Roit ur chomlec'h d'ho wiki",
	'cnw-name-wiki-submit-error' => "Hopala ! Bezit sur eo bet leuniet mat an div vaezienn a-us evit gallout kenderc'hel.",
	'cnw-login' => 'Kevreañ',
	'cnw-signup' => 'Krouiñ ur gont',
	'cnw-signup-prompt' => "Ezhomm hoc'h eus ur gont ?",
	'cnw-call-to-signup' => 'Sinit amañ',
	'cnw-login-prompt' => "Ur gont hoc'h eus dija ?",
	'cnw-call-to-login' => 'Kevreit amañ',
	'cnw-auth-headline' => 'Kevreañ',
	'cnw-auth-headline2' => 'En em enskrivañ',
	'cnw-auth-creative' => "Kevreit ouzh ho kont evit kenderc'hel da sevel ho wiki.",
	'cnw-auth-signup-creative' => "Ezhomm ho po deus ur gont evit kenderc'hel da sevel ur wiki.<br />Ne bado nemet ur vunutenn evit bezañ enskrivet !",
	'cnw-auth-facebook-signup' => 'En em enskrivañ dre Facebook',
	'cnw-auth-facebook-login' => 'Kevreañ gant Facebook',
	'cnw-desc-headline' => 'Eus petra zo kaoz en ho wiki ?',
	'cnw-desc-creative' => 'Deskrivit ho sujed',
	'cnw-desc-placeholder' => 'Dont a ray war wel war bajenn bennañ ho wiki.',
	'cnw-desc-tip1' => 'Tun',
	'cnw-desc-tip1-creative' => "Implijit ar c'horn-mañ da zisplegañ d'an dud krak-ha-berr eus petra zo kaoz en ho wiki",
	'cnw-desc-tip2' => 'Kuzul 2',
	'cnw-desc-tip2-creative' => 'Merkit un nebeud displegadurioù war an danvez evit ar weladennerien',
	'cnw-desc-tip3' => 'Tun digant unan a-vicher',
	'cnw-desc-tip3-creative' => "Kelaouiñ an dud e c'hellont dont evit sikour da greskiñ ho wiki en ur gemmañ pe en ur ouzhpennañ pajennoù",
	'cnw-desc-choose' => 'Dibabit ur rummad',
	'cnw-desc-select-one' => 'Diuzañ unan',
	'cnw-desc-default-lang' => 'E $1 e vo ho wiki',
	'cnw-desc-change-lang' => 'kemmañ',
	'cnw-desc-lang' => 'Yezh',
	'cnw-desc-wiki-submit-error' => 'Dibabit ur rummad, mar plij',
	'cnw-theme-headline' => 'Krouit ho wiki',
	'cnw-theme-creative' => "Dibabit un dodenn amañ dindan, gellout a reoc'h rakwelet pep dodenn en ur ziuzañ anezhi.",
	'cnw-theme-instruction' => 'Gellout a rit ivez krouiñ ho todenn hiniennel un tamm diwezhatoc\'h en ur vont e "Ma ostilhoù".',
	'cnw-upgrade-headline' => "C'hoant hoc'h eus da lakaat a-live ?",
	'cnw-upgrade-creative' => "Hizivaat war-zu Wikia Plus a aotre ac'hanoc'h da dennañ ar bruderezhioù eus <span class=\"wiki-name\"></span>, ur c'hinnig dibar evit an diazezourien nevez hepken.",
	'cnw-upgrade-marketing' => 'Un diskoulm a-feson eo Wikia Plus evit :<ul>
<li>Wikioù a-vicher</li>
<li>Kevredigezhioù</li>
<li>Familhoù</li>
<li>Skolioù</li>
<li>Raktresoù personel</li>
</ul>
Hizivait dre PayPal evit kaout ur wiki nevez ouzhpenn evit $4.95 ar miz nemetken !',
	'cnw-upgrade-now' => 'Lakaat a-live bremañ',
	'cnw-upgrade-decline' => "N'eo ket dav, kenderc'hel gant ma wiki",
	'cnw-welcome-headline' => "Gourc'hemennoù, krouet hoc'h eus $1",
	'cnw-welcome-instruction1' => "Klikit war ar bouton amañ dindan evit kregiñ da ouzhpennañ pajennoù d'ho wiki.",
	'cnw-welcome-instruction2' => "Gwelet a reot ar bouton-mañ hed-ha-hed ho wiki, grit gantañ bep tro ha ma fello deoc'h ouzhpennañ ur bajenn nevez.",
	'cnw-welcome-help' => 'Kavout a reot respontoù, kuzulioù ha kement zo war <a href="http://community.wikia.com">Kalonenn ar gumuniezh</a>.',
	'cnw-error-general' => "Un dra bennak a zo aet a-dreuz pa veze krouet ho wiki. Mar plij adklaskit diwezhatoc'h.",
	'cnw-error-general-heading' => "Ur fazi 'zo bet e-pad krouidigezh ur wiki nevez",
	'cnw-badword-msg' => "Ac'hanta, mar plij chomit hep implijout gerioù vil pe difennet e deskrivadur ho wiki : $1",
	'cnw-error-wiki-limit-header' => 'Bevenn ar wikioù bet tizhet',
	'cnw-error-wiki-limit' => "Ac'hanta, bevennet eo ar c'hrouiñ wikioù da $1 wiki dre zen ha dre zevezh. Gortozit 24 eurvezh a-benn gellout krouiñ unan all.",
);

/** German (Deutsch)
 * @author Claudia Hattitten
 * @author George Animal
 * @author LWChris
 */
$messages['de'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Assistent zum Erstellen eines Wikis]]',
	'cnw-next' => 'Nächste',
	'cnw-back' => 'Zurück',
	'cnw-or' => 'oder',
	'cnw-title' => 'Neues Wiki erstellen',
	'cnw-name-wiki-headline' => 'Ein Wiki starten',
	'cnw-name-wiki-creative' => 'Wikia ist der beste Ort, um rund um dein Lieblingsthema eine Website aufzubauen und eine Community wachsen zu lassen.',
	'cnw-name-wiki-label' => 'Benenne dein Wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Gib deinem Wiki eine Adresse',
	'cnw-name-wiki-submit-error' => 'Ups! Du musst beide Felder oben ausfüllen um weiterzumachen.',
	'cnw-login' => 'Anmelden',
	'cnw-signup' => 'Benutzerkonto erstellen',
	'cnw-signup-prompt' => 'Brauchst du ein Benutzerkonto?',
	'cnw-call-to-signup' => 'Hier registrieren',
	'cnw-login-prompt' => 'Hast du bereits ein Benutzerkonto?',
	'cnw-call-to-login' => 'Hier anmelden',
	'cnw-auth-headline' => 'Anmelden',
	'cnw-auth-headline2' => 'Registrieren',
	'cnw-auth-creative' => 'Melde dich mit deinem Benutzerkonto an, um mit der Erstellung deines Wikis fortzufahren.',
	'cnw-auth-signup-creative' => 'Du benötigst ein Konto, um mit der Erstellung deines Wikis fortzufahren.<br />Es dauert nur eine Minute sich zu registrieren!',
	'cnw-auth-facebook-signup' => 'Über Facebook registrieren',
	'cnw-auth-facebook-login' => 'Über Facebook anmelden',
	'cnw-desc-headline' => 'Worum geht es in deinem Wiki?',
	'cnw-desc-creative' => 'Beschreibe dein Thema',
	'cnw-desc-placeholder' => 'Dies wird auf der Hauptseite deines Wikis erscheinen.',
	'cnw-desc-tip1' => 'Tipp',
	'cnw-desc-tip1-creative' => 'Nutze dieses Feld, um den Leuten dein Wiki in ein oder zwei Sätzen vorzustellen',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Gib deinen Besuchern ein paar spezifische Details zu deinem Thema',
	'cnw-desc-tip3' => 'Profi Tipp',
	'cnw-desc-tip3-creative' => 'Lass die Leute wissen, dass sie deinem Wiki durch Bearbeiten und Hinzufügen von Seiten dabei helfen können zu wachsen',
	'cnw-desc-choose' => 'Eine Kategorie auswählen',
	'cnw-desc-select-one' => 'Bitte wählen',
	'cnw-desc-default-lang' => 'Die Sprache deines Wikis wird $1',
	'cnw-desc-change-lang' => 'ändern',
	'cnw-desc-lang' => 'Sprache',
	'cnw-desc-wiki-submit-error' => 'Bitte wähle eine Kategorie',
	'cnw-theme-headline' => 'Wähle ein Theme',
	'cnw-theme-creative' => 'Wähle unten ein Theme, du wirst eine Vorschau jedes Themes sehen sobald du es auswählst.',
	'cnw-theme-instruction' => 'Du kannst später auch dein eigenes Theme entwerfen, indem du auf "Werkzeugkasten" klickst.',
	'cnw-upgrade-headline' => 'Möchtest du ein Upgrade?',
	'cnw-upgrade-creative' => 'Ein Upgrade auf Wikia Plus ermöglicht es dir, Anzeigen von <span class="wiki-name"></span> zu entfernen, ein einmaliges Angebot, nur für neue Gründer verfügbar.',
	'cnw-upgrade-marketing' => 'Wikia Plus ist eine großartige Lösung für:<ul>
<li>Professionelle Wikis</li>
<li>Nicht kommerzielle</li>
<li>Familien</li>
<li>Schulen</li>
<li>Persönliche Projekte</li>
</ul>
Upgrade mit PayPal um ein werbefreies Wiki für nur $4,95 pro Monat zu bekommen!',
	'cnw-upgrade-now' => 'Jetzt upgraden',
	'cnw-upgrade-decline' => 'Nein danke, weiter zu meinem Wiki',
	'cnw-welcome-headline' => 'Herzlichen Glückwunsch! $1 wurde erstellt',
	'cnw-welcome-instruction1' => 'Klick auf die Schaltfläche unten, um Seiten zu deinem Wiki hinzufügen.',
	'cnw-welcome-instruction2' => 'Du wirst diese Schaltfläche in deinem ganzen Wiki sehen, nutze sie jedes Mal wenn du eine neue Seite hinzufügen willst.',
	'cnw-welcome-help' => 'Finde Antworten, Ratschläge und mehr auf <a href="http://community.wikia.com">Community Central</a>.',
	'cnw-error-general' => 'Bei der Erstellung deines Wikis ist etwas schief gelaufen. Bitte versuche es später erneut.',
	'cnw-error-general-heading' => 'Neues Wiki Erstellen Fehler',
	'cnw-badword-header' => 'Ganz ruhig',
	'cnw-badword-msg' => 'Hallo, bitte verwende keines der folgenden Schimpfwörter oder unerlaubten Begriffe in deiner Wiki-Beschreibung: $1',
	'cnw-error-wiki-limit-header' => 'Wiki Limit erreicht',
	'cnw-error-wiki-limit' => 'Hallo, du darfst nur {{PLURAL:$1|ein Wiki|$1 Wikis}} am Tag gründen. Warte 24 Stunden bevor du ein weiteres Wiki gründest.',
);

/** Spanish (Español)
 * @author VegaDark
 */
$messages['es'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Asistente de creación de wikis]]',
	'cnw-next' => 'Siguiente',
	'cnw-back' => 'Atrás',
	'cnw-or' => 'o',
	'cnw-title' => 'Crear un nuevo wiki',
	'cnw-name-wiki-headline' => 'Comenzar una wiki',
	'cnw-name-wiki-creative' => 'Wikia es el mejor sitio para construir un sitio web y hacer crecer una comunidad en torno a lo que te gusta.',
	'cnw-name-wiki-label' => 'Nombre de tu wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Dale a tu wiki una dirección',
	'cnw-name-wiki-submit-error' => '¡Oops! Asegúrate que todos los campos estén rellenados con las entradas válidas anteriores.',
	'cnw-login' => 'Entrar',
	'cnw-signup' => 'Crear una cuenta',
	'cnw-signup-prompt' => '¿Necesitas una cuenta?',
	'cnw-call-to-signup' => 'Regístrate aquí',
	'cnw-login-prompt' => '¿Ya tienes una cuenta?',
	'cnw-call-to-login' => 'Iniciar sesión aquí',
	'cnw-auth-headline' => 'Entrar',
	'cnw-auth-headline2' => 'Registrarse',
	'cnw-auth-creative' => 'Inicia sesión con tu cuenta para continuar la contrucción de tu wiki.',
	'cnw-auth-signup-creative' => 'Necesitarás una cuenta para seguir construyendo tu wiki.<br />¡Solamente tardarás un minuto para registrarte!',
	'cnw-auth-facebook-signup' => 'Registraste con Facebook',
	'cnw-auth-facebook-login' => 'Entrar con Facebook',
	'cnw-desc-headline' => '¿De qué trata tu wiki?',
	'cnw-desc-creative' => 'Describe el tema',
	'cnw-desc-placeholder' => 'Esto aparecerá en la página principal de tu wiki.',
	'cnw-desc-tip1' => 'Sugerencia',
	'cnw-desc-tip1-creative' => 'Dile a la gente de qué trata tu wiki',
	'cnw-desc-tip2' => 'Consejo 2',
	'cnw-desc-tip2-creative' => 'Asegúrate de incluir detalles',
	'cnw-desc-tip3' => 'Consejo 3',
	'cnw-desc-tip3-creative' => 'Invita a la gente para que ayude a editar',
	'cnw-desc-choose' => 'Elige una categoría',
	'cnw-desc-select-one' => 'Selecciona una',
	'cnw-desc-default-lang' => 'Tu wiki será en $1',
	'cnw-desc-change-lang' => 'cambiar',
	'cnw-desc-lang' => 'Idioma',
	'cnw-desc-wiki-submit-error' => 'Por favor, elige una categoría',
	'cnw-theme-headline' => 'Diseña tu wiki',
	'cnw-theme-creative' => 'Escoge un tema que se ajuste a tu wiki.',
	'cnw-theme-instruction' => 'Puedes cambiar el tema o diseñar tu propio en cualquier momento usando "Mis Herramientas" situada en la barra de herramientas en la parte inferior de la página.',
	'cnw-upgrade-headline' => '¿Deseas actualizar?',
	'cnw-upgrade-creative' => 'Actualizarte a Wikia Plus te permite eliminar la publicidad de <span class="wiki-name"></span>, oferta única disponible a los nuevos fundadores',
	'cnw-upgrade-marketing' => 'Wikia Plus es una gran solución para:<ul>
<li>Wiki profesionales</li>
<li>Sin fines de lucro</li>
<li>Familias</li>
<li>Escuelas</li>
<li>Proyectos personales</li>
</ul>
Actualizar a través de PayPal para conseguir una wiki sin publicidad ¡por solo $4.95 al mes!',
	'cnw-upgrade-now' => 'Actualizar ahora',
	'cnw-upgrade-decline' => 'No, gracias, ir a mi wiki',
	'cnw-welcome-headline' => '¡Enhorabuena! Has creado $1',
	'cnw-welcome-instruction1' => 'Ahora haz clic al botón de abajo para empezar a llenar tu wiki con información.',
	'cnw-welcome-instruction2' => 'Verás este botón a través de tu wiki. Úsalo en cualquier momento cuando quieras agregar una página nueva.',
	'cnw-welcome-help' => 'Encuentra respuestas, consejos, y más en la <a href="http://es.wikia.com">Comunidad Central</a>.',
	'cnw-error-general' => 'Algo salió mal al crear tu wiki. Por favor, inténtalo de nuevo más tarde.',
	'cnw-error-general-heading' => 'Error',
	'cnw-badword-header' => '¡Oops!',
	'cnw-badword-msg' => 'Hola, por favor abstente de usar estas malas palabras o palabras prohibidas en la descripción de tu wiki: $1',
	'cnw-error-wiki-limit-header' => 'Has alcanzado el límite de wikis',
	'cnw-error-wiki-limit' => 'Hola, estás limitado a $1 creación de wikis por día. Espera 24 horas antes de crear otro wiki.',
);

/** Basque (Euskara)
 * @author An13sa
 */
$messages['eu'] = array(
	'cnw-next' => 'Hurrengoa',
	'cnw-back' => 'Atzera',
	'cnw-or' => 'edo',
	'cnw-name-wiki-wiki' => 'Wikia',
	'cnw-desc-lang' => 'Hizkuntza',
);

/** Persian (فارسی)
 * @author BlueDevil
 */
$messages['fa'] = array(
	'cnw-login' => 'ورود به سامانه',
	'cnw-signup' => 'ایجاد حساب جدید',
	'cnw-auth-headline' => 'ورود به سامانه',
	'cnw-auth-headline2' => 'ثبت نام',
	'cnw-desc-tip1' => 'راهنمایی',
);

/** Finnish (Suomi)
 * @author Tofu II
 */
$messages['fi'] = array(
	'cnw-name-wiki-label' => 'Nimeä wikisi',
	'cnw-desc-wiki-submit-error' => 'Valitse luokka',
	'cnw-upgrade-now' => 'Päivitä nyt',
	'cnw-welcome-headline' => 'Onnittelut! $1 on luotu',
);

/** French (Français)
 * @author Od1n
 * @author Peter17
 * @author Wyz
 */
$messages['fr'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Assistant de création de wiki]]',
	'cnw-next' => 'Suite',
	'cnw-back' => 'Retour',
	'cnw-or' => 'ou',
	'cnw-title' => 'Créer un nouveau wiki',
	'cnw-name-wiki-headline' => 'Commencer un wiki',
	'cnw-name-wiki-creative' => 'Wikia est le meilleur endroit pour construire un site Web et monter une communauté autour de ce que vous aimez.',
	'cnw-name-wiki-label' => 'Nommez votre wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Donnez une adresse à votre wiki',
	'cnw-name-wiki-submit-error' => 'Désolé ! Vous devez remplir les deux champs ci-dessus pour continuer.',
	'cnw-login' => 'Se connecter',
	'cnw-signup' => 'Créer un compte',
	'cnw-signup-prompt' => 'Il faut un compte ?',
	'cnw-call-to-signup' => 'Inscrivez-vous ici',
	'cnw-login-prompt' => 'Vous avez déjà un compte ?',
	'cnw-call-to-login' => 'Connectez-vous ici',
	'cnw-auth-headline' => 'Se connecter',
	'cnw-auth-headline2' => 'S’inscrire',
	'cnw-auth-creative' => 'Connectez-vous à votre compte pour continuer à construire votre wiki.',
	'cnw-auth-signup-creative' => 'Vous aurez besoin d’un compte pour continuer à construire votre wiki.<br />Cela ne prendre qu’une minute pour vous inscrire !',
	'cnw-auth-facebook-signup' => 'S’identifier avec Facebook',
	'cnw-auth-facebook-login' => 'Se connecter avec Facebook',
	'cnw-desc-headline' => 'De quoi parle votre wiki ?',
	'cnw-desc-creative' => 'Décrivez le sujet',
	'cnw-desc-placeholder' => 'Ceci apparaîtra sur la page d’accueil de votre wiki.',
	'cnw-desc-tip1' => 'Astuce',
	'cnw-desc-tip1-creative' => 'Utilisez ceci pour indiquer de quoi parle votre wiki en une phrase ou deux',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Donnez à vos visiteurs quelques détails spécifiques à propos du sujet',
	'cnw-desc-tip3' => 'Conseil d’expert',
	'cnw-desc-tip3-creative' => 'Faites savoir aux gens qu’ils peuvent aider à faire grandir votre wiki en modifiant ou en ajoutant des pages',
	'cnw-desc-choose' => 'Choisissez une catégorie',
	'cnw-desc-select-one' => 'Sélectionnez-en une',
	'cnw-desc-default-lang' => 'Votre wiki sera en $1',
	'cnw-desc-change-lang' => 'modifier',
	'cnw-desc-lang' => 'Langue',
	'cnw-desc-wiki-submit-error' => 'Veuillez choisir une catégorie',
	'cnw-theme-headline' => 'Choisissez un thème',
	'cnw-theme-creative' => 'Choisissez un thème ci-dessous, vous pourrez voir un aperçu de chaque thème lorsque vous le sélectionnerez.',
	'cnw-theme-instruction' => 'Vous pouvez aussi concevoir votre propre thème plus tard via « Mes outils ».',
	'cnw-upgrade-headline' => 'Souhaitez-vous mettre à niveau ?',
	'cnw-upgrade-creative' => 'Mettre à niveau vers Wikia Plus vous permet de retirer les publicités de <span class="wiki-name"></span>, une offre unique disponible uniquement aux nouveaux fondateurs.',
	'cnw-upgrade-marketing' => 'Wikia Plus est une solution idéale pour :<ul>
<li>Les wikis professionnels</li>
<li>Les projets à but non lucratif</li>
<li>Les familles</li>
<li>Les écoles</li>
<li>Les projets personnels</li>
</ul>
Mettez à niveau via Paypal pour obtenir un wiki sans publicité pour seulement 4,95 $ par mois !',
	'cnw-upgrade-now' => 'Mettre à niveau maintenant',
	'cnw-upgrade-decline' => 'Non merci, continuer vers mon wiki',
	'cnw-welcome-headline' => 'Félicitations ! $1 a été créé.',
	'cnw-welcome-instruction1' => 'Cliquez sur le bouton ci-dessous pour commencer à ajouter des pages à votre wiki.',
	'cnw-welcome-instruction2' => 'Vous verrez ce bouton partout sur votre wiki, utilisez-le chaque fois que vous souhaitez ajouter une nouvelle page.',
	'cnw-welcome-help' => 'Trouvez des réponses, conseils et plus sur sur <a href="http://community.wikia.com">Community Central</a>.',
	'cnw-error-general' => 'Quelque chose s’est mal passé lors de la création de votre wiki. Veuillez réessayer plus tard.',
	'cnw-error-general-heading' => 'Erreur lors de la création d’un nouveau wiki',
	'cnw-error-database' => 'Erreur de base de données : $1',
	'cnw-badword-header' => 'Eh Oh',
	'cnw-badword-msg' => 'Bonjour, veuillez éviter d’utiliser des mots grossiers ou interdits dans la description de votre wiki : $1',
	'cnw-error-wiki-limit-header' => 'Limite de wikis atteinte',
	'cnw-error-wiki-limit' => 'Bonjour, vous êtes limité à la création de $1 wikis par jour. Attendez 24 heures avant de créer un autre wiki.',
	'cnw-error-blocked-header' => 'Compte bloqué',
	'cnw-error-blocked' => 'Vous avez été bloqué par $1. La raison invoquée était : $2. (ID de blocage pour référence : $3)',
	'cnw-error-torblock' => "Créer des wikis via le réseau Tor n'est pas autorisé.",
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Asistente para a creación de wikis]]',
	'cnw-next' => 'Seguinte',
	'cnw-back' => 'Volver',
	'cnw-or' => 'ou',
	'cnw-title' => 'Crear un novo wiki',
	'cnw-name-wiki-headline' => 'Comezar un wiki',
	'cnw-name-wiki-label' => 'Déalle un nome ao seu wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Déalle un enderezo ao seu wiki',
	'cnw-login' => 'Rexistro',
	'cnw-signup' => 'Crear unha conta',
	'cnw-signup-prompt' => 'Necesita unha conta?',
	'cnw-call-to-signup' => 'Rexístrese aquí',
	'cnw-login-prompt' => 'Xa ten unha conta?',
	'cnw-call-to-login' => 'Acceder ao sistema',
	'cnw-auth-headline' => 'Rexistro',
	'cnw-auth-headline2' => 'Rexistrarse',
	'cnw-auth-facebook-signup' => 'Rexístrese co Facebook',
	'cnw-auth-facebook-login' => 'Acceda co Facebook',
	'cnw-desc-headline' => 'De que vai o seu wiki?',
	'cnw-desc-creative' => 'Describa o seu tema',
	'cnw-desc-placeholder' => 'Isto aparecerá na páxina principal do seu wiki.',
	'cnw-desc-tip1' => 'Suxestión',
	'cnw-desc-tip2' => 'Consello',
	'cnw-desc-tip3' => 'Consello de experto',
	'cnw-desc-choose' => 'Seleccione unha categoría',
	'cnw-desc-select-one' => 'Seleccione un',
	'cnw-desc-default-lang' => 'O seu wiki estará en $1',
	'cnw-desc-change-lang' => 'cambiar',
	'cnw-desc-lang' => 'Lingua',
	'cnw-desc-wiki-submit-error' => 'Seleccione unha categoría',
	'cnw-theme-headline' => 'Escolla un tema visual',
	'cnw-upgrade-headline' => 'Quere realizar a actualización?',
	'cnw-upgrade-now' => 'Actualizar agora',
	'cnw-upgrade-decline' => 'Non, grazas. Quero continuar ata o meu wiki',
	'cnw-welcome-headline' => 'Parabéns! Creouse $1',
	'cnw-error-general-heading' => 'Erro durante a creación do novo wiki',
	'cnw-badword-header' => 'Vaites!',
	'cnw-error-wiki-limit-header' => 'Alcanzouse o límite de wikis',
);

/** Hebrew (עברית)
 * @author Amire80
 * @author שומבלע
 */
$messages['he'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|אשף יצירת ויקי]]',
	'cnw-next' => 'הבא',
	'cnw-back' => 'הקודם',
	'cnw-or' => 'או',
	'cnw-title' => 'יצירת ויקי חדש',
	'cnw-name-wiki-headline' => 'להתחיל ויקי',
	'cnw-name-wiki-creative' => 'ויקיה – המקום הטוב ביותר לבנות אתר ולטפל קהילה סביב דברים שאתם אוהבים.',
	'cnw-name-wiki-label' => 'שם הוויקי',
	'cnw-name-wiki-wiki' => 'ויקי',
	'cnw-name-wiki-domain-label' => 'כתובת הוויקי',
	'cnw-name-wiki-submit-error' => 'אוי! צריך למלא את שתי התיבות למעל כדי להמשיך.',
	'cnw-login' => 'כניסה',
	'cnw-signup' => 'יצירת חשבון',
	'cnw-signup-prompt' => 'צריכים חשבון?',
	'cnw-call-to-signup' => 'להירשם כאן',
	'cnw-login-prompt' => 'כבר יש לכם חשבון?',
	'cnw-call-to-login' => 'להיכנס כאן',
	'cnw-auth-headline' => 'כניסה',
	'cnw-auth-headline2' => 'הרשמה',
	'cnw-auth-creative' => 'כניסה לחשבון כדי להמשיך לבנות את הוויקי שלכם',
	'cnw-auth-signup-creative' => 'צריך חשבון כדי להמשיך לבנות את הוויקי שלכם.<br />לוקח רק דקה להירשם!',
	'cnw-auth-facebook-signup' => 'הרשמה עם פייסבוק',
	'cnw-auth-facebook-login' => 'התחברות עם פייסבוק',
	'cnw-desc-headline' => 'על מה הוויקי שלכם?',
	'cnw-desc-creative' => 'תארו את הנושא שלכם',
	'cnw-desc-placeholder' => 'זה יופיע בדף הראשי של הוויקי שלכם.',
	'cnw-desc-tip1' => 'עצה',
	'cnw-desc-tip1-creative' => 'השתמשו במרווח הזה כדי לספר לאנשים על הוויקי שלכם במשפט או שניים',
	'cnw-desc-tip2' => 'אהם־אהם',
	'cnw-desc-tip2-creative' => 'תנו למבקרים שלכם כמה פרטים על הנושא שלכם',
	'cnw-desc-tip3' => 'עצה למקצוענים',
	'cnw-desc-tip3-creative' => 'ספרו לאנשים שהם יכולים לעזור לוויקי לצמוח על ידי עריכה והוספת מידע לדפים',
	'cnw-desc-choose' => 'בחירת קטגוריה',
	'cnw-desc-select-one' => 'לבחור אחת',
	'cnw-desc-default-lang' => 'הוויקי שלכם יהיה ב$1',
	'cnw-desc-change-lang' => 'לשנות',
	'cnw-desc-lang' => 'שפה',
	'cnw-desc-wiki-submit-error' => 'נא לבחור קטגוריה',
	'cnw-theme-headline' => 'נא לבחור ערכת עיצוב',
	'cnw-theme-creative' => 'נא לבחור באחת מערכות העיצוב להלן. אפשר יהיה לראות תצוגה מקדימה של כל ערכה תוך כדי הבחירה.',
	'cnw-theme-instruction' => 'אפשר גם לעצב ערכת עיצוב משלכם דרך "הכלים שלי".',
	'cnw-upgrade-headline' => 'לשדרג?',
	'cnw-upgrade-creative' => 'שדרוג ל־Wikia Plus מאפשר להסיר פרסומות מעל <span class="wiki-name"></span>, הצעה חד־פעמית למייסדי ויקי חדש.',
	'cnw-upgrade-marketing' => 'Wikia Plus – פתרון נהדר בשביל:<ul>
<li>אתרי ויקי מקצועיים</li>
<li>מוסדות ללא כוונת רווח</li>
<li>משפחות</li>
<li>מוסדות חינוך</li>
<li>מיזמים אישיים</li>
</ul>
שדרגו באמצעות פייפאל כדי לקבל ויקי נקי מפרסומות עבוק 4.95 דולר לחודש בלבד!',
	'cnw-upgrade-now' => 'לשדרג עכשיו',
	'cnw-upgrade-decline' => 'לא, תודה, אני רוצה ללכת לוויקי שלי',
	'cnw-welcome-headline' => 'ברכות! הוויקי $1 נוצר',
	'cnw-welcome-instruction1' => 'לחצו על הכפתור להלן כדי להתחיל להוסיף דפים לוויקי שלכם.',
	'cnw-welcome-instruction2' => 'הכפתור הזה יופיע בכל דף בוויקי, אפשר להשתמש בו בכל זמן שאתם רוצים להוסיף עמוד חדש.',
	'cnw-welcome-help' => 'מצאו תשובות, עצות ועוד ב־<a href="http://community.wikia.com">Community Central</a>.',
);

/** Hungarian (Magyar)
 * @author Dani
 */
$messages['hu'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Wikikészítő varázsló]]',
	'cnw-next' => 'Következő',
	'cnw-back' => 'Vissza',
	'cnw-or' => 'vagy',
	'cnw-title' => 'Új wiki létrehozása',
	'cnw-name-wiki-headline' => 'Wiki indítása',
	'cnw-name-wiki-label' => 'A wiki neve',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Adj a wikinek egy címet',
	'cnw-name-wiki-submit-error' => 'Hoppá! Ki kell töltened mindkét fenti mezőt a folytatáshoz.',
	'cnw-login' => 'Bejelentkezés',
	'cnw-signup' => 'Fiók létrehozása',
	'cnw-signup-prompt' => 'Fiókra van szükséged?',
	'cnw-call-to-signup' => 'Itt regisztrálhatsz',
	'cnw-login-prompt' => 'Már van fiókod?',
	'cnw-call-to-login' => 'Itt bejelentkezhetsz',
	'cnw-auth-headline' => 'Bejelentkezés',
	'cnw-auth-headline2' => 'Regisztráció',
	'cnw-auth-facebook-signup' => 'Regisztráció Facebookkal',
	'cnw-auth-facebook-login' => 'Bejelentkezés Facebookkal',
	'cnw-desc-headline' => 'Miről szól a wiki?',
	'cnw-desc-creative' => 'Írd körül a témát',
	'cnw-desc-placeholder' => 'Ez a wiki kezdőlapján fog megjelenni.',
	'cnw-desc-tip1' => 'Emlékeztető',
	'cnw-desc-tip3' => 'Profi tipp',
	'cnw-desc-choose' => 'Válassz egy kategóriát',
	'cnw-desc-select-one' => 'Válassz egyet',
	'cnw-desc-change-lang' => 'módosítás',
	'cnw-desc-lang' => 'Nyelv',
	'cnw-desc-wiki-submit-error' => 'Válassz egy kategóriát',
	'cnw-theme-headline' => 'Válassz egy témát',
	'cnw-error-general-heading' => 'Hiba új wiki létrehozásakor',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Assistente pro crear wikis]]',
	'cnw-next' => 'Sequente',
	'cnw-back' => 'Retornar',
	'cnw-or' => 'o',
	'cnw-title' => 'Crear nove wiki',
	'cnw-name-wiki-headline' => 'Comenciar un wiki',
	'cnw-name-wiki-creative' => 'Wikia es le optime loco pro construer un sito web e cultivar un communitate circa lo que tu ama.',
	'cnw-name-wiki-label' => 'Nomina tu wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Da un adresse a tu wiki',
	'cnw-name-wiki-submit-error' => 'Ups! Es necessari completar ambe le quadros hic supra pro continuar.',
	'cnw-login' => 'Aperir session',
	'cnw-signup' => 'Crear conto',
	'cnw-signup-prompt' => 'Necessita un conto?',
	'cnw-call-to-signup' => 'Inscribe te hic',
	'cnw-login-prompt' => 'Tu jam ha un conto?',
	'cnw-call-to-login' => 'Aperi session hic',
	'cnw-auth-headline' => 'Aperir session',
	'cnw-auth-headline2' => 'Crear conto',
	'cnw-auth-creative' => 'Aperi session a tu conto pro continuar le construction de tu wiki.',
	'cnw-auth-signup-creative' => 'Es necessari haber un conto pro continuar le construction de tu wiki.<br />Le inscription prende solmente un minuta!',
	'cnw-auth-facebook-signup' => 'Crear conto con Facebook',
	'cnw-auth-facebook-login' => 'Aperir session con Facebook',
	'cnw-desc-headline' => 'Que es le thema de tu wiki?',
	'cnw-desc-creative' => 'Describe tu topico',
	'cnw-desc-placeholder' => 'Isto apparera in le pagina principal de tu wiki.',
	'cnw-desc-tip1' => 'Consilio',
	'cnw-desc-tip1-creative' => 'Usa iste spatio pro explicar le thema de tu wiki al visitatores in un phrase o duo',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Specifica al visitatores alcun detalios a proposito de tu thema',
	'cnw-desc-tip3' => 'Consilio extra',
	'cnw-desc-tip3-creative' => 'Dice al gente que illes pote adjutar tu wiki a crescer per modificar e adder paginas',
	'cnw-desc-choose' => 'Selige un categoria',
	'cnw-desc-select-one' => 'Selige un',
	'cnw-desc-default-lang' => 'Tu wiki essera in $1',
	'cnw-desc-change-lang' => 'cambiar',
	'cnw-desc-lang' => 'Lingua',
	'cnw-desc-wiki-submit-error' => 'Per favor selige un categoria',
	'cnw-theme-headline' => 'Designar tu wiki',
	'cnw-theme-creative' => 'Selige un apparentia hic infra. Tu videra un previsualisation de cata apparentia quando tu lo selige.',
	'cnw-theme-instruction' => 'Es equalmente possibile designar tu proprie apparentia usante "Mi instrumentos".',
	'cnw-upgrade-headline' => 'Vole tu actualisar?',
	'cnw-upgrade-creative' => 'Le actualisation a Wikia Plus permitte remover le publicitate de <span class="wiki-name"></span>. Iste offerta es disponibile solmente pro le nove fundatores.',
	'cnw-upgrade-marketing' => 'Wikia Plus es un ideal solution pro:<ul>
<li>Wikis professional</li>
<li>Organisationes sin scopo lucrative</li>
<li>Familias</li>
<li>Scholas</li>
<li>Projectos personal</li>
</ul>
Compra le actualisation per PayPal pro obtener un wiki sin publicitate pro solmente 4,95$ per mense!',
	'cnw-upgrade-now' => 'Actualisar ora',
	'cnw-upgrade-decline' => 'No gratias, continuar a mi wiki',
	'cnw-welcome-headline' => 'Felicitationes, tu ha create $1',
	'cnw-welcome-instruction1' => 'Clicca sur le button hic infra pro comenciar a adder paginas a tu wiki.',
	'cnw-welcome-instruction2' => 'Tu videra iste button ubique in tu wiki. Usa lo cata vice que tu vole adder un nove pagina.',
	'cnw-welcome-help' => 'Trova responsas, consilios e plus in <a href="http://community.wikia.com">le centro del communitate</a>.',
	'cnw-error-general' => 'Qualcosa errava durante le creation de tu wiki. Per favor reproba plus tarde.',
	'cnw-error-general-heading' => 'Error de creation de nove wiki',
	'cnw-badword-header' => 'Stop!',
	'cnw-badword-msg' => 'Salute, per favor abstine te de usar le sequente parolas improprie o bannite in le description de tu wiki: $1',
	'cnw-error-wiki-limit-header' => 'Limite de wikis attingite',
	'cnw-error-wiki-limit' => 'Salute, tu es limitate al creation de $1 wikis per die. Attende 24 horas ante de crear un altere wiki.',
);

/** Ingush (ГІалгІай Ğalğaj)
 * @author Sapral Mikail
 */
$messages['inh'] = array(
	'cnw-name-wiki-wiki' => 'ВIикIи',
);

/** Japanese (日本語)
 * @author Tommy6
 * @author Wrightbus
 */
$messages['ja'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|ウィキ作成ウィザード]]',
	'cnw-next' => '次へ',
	'cnw-back' => '戻る',
	'cnw-or' => 'または',
	'cnw-title' => '新しいウィキを作成',
	'cnw-name-wiki-headline' => 'ウィキを作成する',
	'cnw-name-wiki-creative' => 'ウィキアは、あなたが好きなものに関するウェブサイトやコミュニティを構築するのに最適の場所です。',
	'cnw-name-wiki-label' => 'ウィキの名称を入力してください',
	'cnw-name-wiki-domain-label' => 'ウィキのアドレスを入力してください',
	'cnw-name-wiki-submit-error' => '上の入力欄2つとも記入する必要があります。',
	'cnw-login' => 'ログイン',
	'cnw-signup' => 'アカウントを作成',
	'cnw-signup-prompt' => 'アカウントが必要ですか？',
	'cnw-call-to-signup' => 'こちらでアカウントを登録してください',
	'cnw-login-prompt' => '既にアカウントをお持ちですか？',
	'cnw-call-to-login' => 'こちらでログインしてください',
	'cnw-auth-headline' => 'ログインする',
	'cnw-auth-headline2' => 'アカウントを作成する',
	'cnw-auth-creative' => '続けるにはログインする必要があります。',
	'cnw-auth-signup-creative' => '続けるにはアカウントを作成する必要があります。<br />アカウントの作成は数分で完了します。',
	'cnw-auth-facebook-signup' => 'Facebook を利用してアカウントを登録',
	'cnw-auth-facebook-login' => 'Facebook を利用してログイン',
	'cnw-desc-headline' => 'どんなウィキを作成しますか？',
	'cnw-desc-creative' => 'このウィキでどんな話題を扱うのかを記入してください',
	'cnw-desc-placeholder' => 'ここに入力した内容がメインページに表示されます。',
	'cnw-desc-choose' => 'カテゴリを選択',
	'cnw-desc-select-one' => '一つを選択',
	'cnw-desc-default-lang' => 'ウィキの言語設定は $1 になっています',
	'cnw-desc-change-lang' => '変更する',
	'cnw-desc-lang' => '言語',
	'cnw-desc-wiki-submit-error' => 'カテゴリを選択してください',
	'cnw-error-general' => 'ウィキの作成中に問題が発生しました。時間をおいてもう一度お試しください。',
	'cnw-error-general-heading' => 'ウィキ作成エラー',
);

/** Khmer (ភាសាខ្មែរ)
 * @author គីមស៊្រុន
 */
$messages['km'] = array(
	'cnw-next' => 'បន្ទាប់',
	'cnw-back' => 'ត្រលប់ក្រោយ',
	'cnw-or' => 'ឬ',
	'cnw-title' => 'បង្កើតវិគីថ្មី',
	'cnw-name-wiki-headline' => 'ចាប់ផ្ដើមវិគីមួយ',
	'cnw-name-wiki-creative' => 'Wikia ជាកន្លែងដ៏ប្រសើរបំផុតក្នុងការស្ថាបនាវិបសៃថ៍និង​ពង្រីកសហគមន៍ជុំវិញអ្នកអ្វីដែលអ្នកស្រឡាញ់ចូលចិត្ត។',
	'cnw-name-wiki-label' => 'ឈ្មោះវិគីរបស់អ្នក',
	'cnw-name-wiki-wiki' => 'វិគី',
	'cnw-name-wiki-domain-label' => 'ផ្ដល់អាសយដ្ឋានមួយដល់វិគីរបស់អ្នក',
	'cnw-name-wiki-submit-error' => 'អូ៎! អ្នកត្រូវតែបំពេញប្រអប់ទាំង២ខាងលើដើម្បីបន្តទៅមុខទៀត។',
	'cnw-login' => 'កត់ឈ្មោះចូល',
	'cnw-signup' => 'បង្កើតគណនី',
	'cnw-signup-prompt' => 'ត្រូវការគណនីមួយ?',
	'cnw-call-to-signup' => 'ចុះឈ្មោះនៅទីនេះ',
	'cnw-login-prompt' => 'មានគណនីមួយរួចហើយ?',
	'cnw-call-to-login' => 'កត់ឈ្មោះចូលនៅទីនេះ',
	'cnw-auth-headline' => 'កត់ឈ្មោះចូល',
	'cnw-auth-headline2' => 'ចុះឈ្មោះ',
	'cnw-auth-creative' => 'កត់ឈ្មោះចូលទៅក្នុងគណនីរបស់អ្នកដើម្បីបន្តស្ថាបនាវិគីរបស់អ្នក។',
	'cnw-auth-signup-creative' => 'អ្នកនឹងត្រូវការគណនីមួយដើម្បីបន្តស្ថាបនាវិគីរបស់អ្នក។ <br />អ្នកចំណាយពេលតែមួយនាទីប៉ុណ្ណោះក្នុងការចុះឈ្មោះ!',
	'cnw-auth-facebook-signup' => 'ចុះឈ្មោះដោយប្រើ Facebook',
	'cnw-auth-facebook-login' => 'កត់ឈ្មោះចូលដោយប្រើ Facebook',
	'cnw-desc-headline' => 'តើវិគីរបស់អ្នកនិយាយពីអ្វី?',
	'cnw-desc-creative' => 'ពណ៌នាពីប្រធានបទរបស់អ្នក',
	'cnw-desc-placeholder' => 'របស់នេះនឹងត្រូវបង្ហាញនៅលើទំព័រដើមរបស់វិគីរបស់អ្នក។',
	'cnw-desc-tip1' => 'គន្លឹះ',
	'cnw-desc-tip1-creative' => 'ប្រើកន្លែងទំនេរនេះ ដើម្បីសរសេរពីរបីល្បះរៀបរាប់ពីវិគីរបស់អ្នកអោយអ្នកដទៃ។',
	'cnw-desc-tip2' => 'គន្លឺះ',
	'cnw-desc-tip2-creative' => 'ផ្ដល់ព័ត៌មានលំអិតដល់អ្នកទស្សនា ស្ដីពីប្រធានបទរបស់អ្នក',
	'cnw-desc-tip3' => 'គន្លឺះថ្នាក់ខ្ពស់',
	'cnw-desc-tip3-creative' => 'អោយអ្នកដទៃបានដឹងថាពួកគេអាចជួយពង្រីកវិគីរបស់អ្នក​តាមរយៈការកែប្រែនិងបន្ថែមទំព័រ',
	'cnw-desc-choose' => 'ជ្រើសរើសចំណាត់ថ្នាក់ក្រុម',
	'cnw-desc-select-one' => 'ជ្រើសរើសមួយ',
	'cnw-desc-default-lang' => 'វិគីរបស់នឹងត្រូវសរសេរជា $1',
	'cnw-desc-change-lang' => 'ផ្លាស់ប្តូរ',
	'cnw-desc-lang' => 'ភាសា',
	'cnw-desc-wiki-submit-error' => 'សូមជ្រើសរើសចំណាត់ថ្នាក់ក្រុមមួយ',
	'cnw-theme-headline' => 'ជ្រើសរើសរចនាបថ',
	'cnw-theme-creative' => 'ជ្រើសរើសរចនាបថខាងក្រោម។ អ្នកនឹងអាចមើលរចនាបថនោះជាមុនពេលដែលជ្រើសរើសវា។',
	'cnw-theme-instruction' => 'អ្នកក៏អាចធ្វើការឌីស្សាញរចនាបថដោយខ្លួនអ្នកនាពេលក្រោយដោយចូលទៅ"ឧបករណ៍"។',
	'cnw-upgrade-decline' => 'ទេ។ បន្តទៅវិគីរបស់ខ្ញុំ',
	'cnw-welcome-headline' => 'សូមអបអរសាទរ! $1 ត្រូវបានបង្កើតហើយ',
	'cnw-welcome-instruction1' => 'ចុចលើប៊ូតុងខាងក្រោមដើម្បីចាប់ផ្ដើមបន្ថែមទំព័រទៅលើវិគីរបស់អ្នក។',
	'cnw-welcome-instruction2' => 'អ្នកនឹងឃើញប៊ូតុងនេះគ្រប់ទីកន្លែងលើវិគីរបស់អ្នក។ សូមប្រើវានៅពេលណាក៏បានដើម្បីបន្ថែមទំព័រថ្មីមួយ។',
	'cnw-welcome-help' => 'រកចំលើយ ដំបូន្មាន និង អ្វីៗបន្ថែមទៀតនៅលើ<a href="http://community.wikia.com">មជ្ឍមណ្ឌលសហគមន៍</a>។',
	'cnw-error-general' => 'មានបញ្ហាពេលបង្កើតវិគីរបស់អ្នក។ សូមព្យាយាមម្ដងទៀតនៅពេលក្រោយ។',
	'cnw-error-general-heading' => 'បញ្ហាក្នុងការបង្កើតវិគីថ្មី',
);

/** Korean (한국어)
 * @author Wrightbus
 */
$messages['ko'] = array(
	'cnw-login' => '로그인',
);

/** Kurdish (Latin) (Kurdî (Latin))
 * @author George Animal
 */
$messages['ku-latn'] = array(
	'cnw-or' => 'an',
	'cnw-signup' => 'Hesabekî çêke',
	'cnw-desc-lang' => 'Ziman',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'cnw-next' => 'Weider',
	'cnw-back' => 'Zréck',
	'cnw-or' => 'oder',
	'cnw-name-wiki-headline' => 'Eng Wiki ufänken',
	'cnw-name-wiki-label' => 'Gitt Ärer Wiki en Numm',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-login' => 'Aloggen',
	'cnw-auth-headline' => 'Aloggen',
	'cnw-desc-tip1' => 'Tipp',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-choose' => 'Eng Kategorie wielen',
	'cnw-desc-change-lang' => 'änneren',
	'cnw-desc-lang' => 'Sprooch',
	'cnw-upgrade-headline' => 'Wëllt Dir en Upgrade maachen?',
	'cnw-upgrade-now' => 'Elo aktualiséieren',
);

/** Lithuanian (Lietuvių)
 * @author Vpovilaitis
 */
$messages['lt'] = array(
	'cnw-name-wiki-headline' => 'Sukurti naują Wiki',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Помошник за создавање на вики]]',
	'cnw-next' => 'Следно',
	'cnw-back' => 'Назад',
	'cnw-or' => 'или',
	'cnw-title' => 'Создај ново вики',
	'cnw-name-wiki-headline' => 'Започнете вики',
	'cnw-name-wiki-creative' => 'Викија е најдоброто место за да изработите мрежно место и да создадете растечка заедница што се темели на она што го сакате.',
	'cnw-name-wiki-label' => 'Именувајте го викито',
	'cnw-name-wiki-wiki' => 'Вики',
	'cnw-name-wiki-domain-label' => 'Дајте му адреса на викито',
	'cnw-name-wiki-submit-error' => 'Упс! Ќе треба да ги пополните обете горенаведени полиња за да продолжите.',
	'cnw-login' => 'Најава',
	'cnw-signup' => 'Создај сметка',
	'cnw-signup-prompt' => 'Ви треба сметка?',
	'cnw-call-to-signup' => 'Регистрирајте се тука',
	'cnw-login-prompt' => 'Веќе имате сметка?',
	'cnw-call-to-login' => 'Најавете се тука',
	'cnw-auth-headline' => 'Најавете се',
	'cnw-auth-headline2' => 'Регистрација',
	'cnw-auth-creative' => 'Најавете се на вашата сметка за да продолжите со изработка на викито.',
	'cnw-auth-signup-creative' => 'Ќе ви треба сметка за да продолжите со изработка на викито.<br />Регистрацијата ќе ви одземе само минутка!',
	'cnw-auth-facebook-signup' => 'Регистрација со Facebook',
	'cnw-auth-facebook-login' => 'Најава со Facebook',
	'cnw-desc-headline' => 'Која е тематиката на викито?',
	'cnw-desc-creative' => 'Опишете ја вашата тема',
	'cnw-desc-placeholder' => 'Ова ќе се прикажува на главната страница на викито',
	'cnw-desc-tip1' => 'Совет',
	'cnw-desc-tip1-creative' => 'Овој простор користете го за да ги известите луѓето за вашето вики во една до две реченици',
	'cnw-desc-tip2' => 'Псст',
	'cnw-desc-tip2-creative' => 'Наведете што повеќе подробности за тематиката',
	'cnw-desc-tip3' => 'Совет за стручњаци',
	'cnw-desc-tip3-creative' => 'Соопштете им на луѓето дека можат да уредуваат страници на викито и така да помогнат во неговиот развој',
	'cnw-desc-choose' => 'Одберете категорија',
	'cnw-desc-select-one' => 'Одберете една категорија',
	'cnw-desc-default-lang' => 'Викито ќе биде на $1',
	'cnw-desc-change-lang' => 'измени',
	'cnw-desc-lang' => 'Јазик',
	'cnw-desc-wiki-submit-error' => 'Одберете категорија',
	'cnw-theme-headline' => 'Уредете го изгледот на викито',
	'cnw-theme-creative' => 'Подолу изберете изглед. За секој избран изглед ќе се прикаже преглед .',
	'cnw-theme-instruction' => 'Подоцна можете да изработите свој изглед преку „Мои алатки“.',
	'cnw-upgrade-headline' => 'Сакате да се надградите?',
	'cnw-upgrade-creative' => 'Надградуваќи се на Викија Плус добивате можност да ги отстраните рекламите од <span class="wiki-name"></span> - еднократна понуда само за нови основачи',
	'cnw-upgrade-marketing' => 'Викија Плус е одлично решение за:<ul>
<li>Стручни деловни викија</li>
<li>Непрофитни организации</li>
<li>Семејства</li>
<li>Училишта</li>
<li>Лични проекти</li>
</ul>
Надградете се преку PayPal и вашето вики ќе биде без реклами за само $4,95 месечно!',
	'cnw-upgrade-now' => 'Надгради веднаш',
	'cnw-upgrade-decline' => 'Не, благодарам. Однеси ме на викито.',
	'cnw-welcome-headline' => 'Честитаме! Го создадовте $1',
	'cnw-welcome-instruction1' => 'Стиснете на копчето подолу за да почнете да додавате страници на викито.',
	'cnw-welcome-instruction2' => 'Ова копче ќе биде присутно ширум целото вики. Користете го секогаш кога сакате да додадете нова страница.',
	'cnw-welcome-help' => 'Одговори на прашања, совети и друго ќе добиете на <a href="http://community.wikia.com">Центарот на заедницата</a>.',
	'cnw-error-general' => 'Нешто тргна наопаку при создавањето на вашето вики. Обидете се подоцна.',
	'cnw-error-general-heading' => 'Грешка при создавање на ново вики',
	'cnw-error-database' => 'Грешка во базата: $1',
	'cnw-badword-header' => 'Предупредување',
	'cnw-badword-msg' => 'Здраво. Ве молиме да се воздржите од употреба на непристојни зборови или забранетите зборови наведени во описот на викито: $1',
	'cnw-error-wiki-limit-header' => 'Границата на создадени викија е достигната',
	'cnw-error-wiki-limit' => 'Здраво. Можете да создавате само по $1 вики дневно. Почекајте 24 часа, па потоа создајте друго.',
	'cnw-error-blocked-header' => 'Сметката е блокирана',
	'cnw-error-blocked' => 'Блокирани сте од $1. Понудената причина гласи: $2. (назнака или навод на блокирањето: $3)',
	'cnw-error-torblock' => 'Не е дозволено создавање на викија преку Tor-мрежа.',
);

/** Malayalam (മലയാളം)
 * @author Praveenp
 */
$messages['ml'] = array(
	'cnw-next' => 'അടുത്തത്',
	'cnw-or' => 'അഥവാ',
	'cnw-title' => 'പുതിയ വിക്കി സൃഷ്ടിക്കുക',
	'cnw-name-wiki-headline' => 'വിക്കി തുടങ്ങുക',
	'cnw-name-wiki-label' => 'താങ്കളുടെ വിക്കിയുടെ പേര്',
	'cnw-name-wiki-wiki' => 'വിക്കി',
	'cnw-login' => 'പ്രവേശിക്കുക',
	'cnw-signup' => 'അംഗത്വമെടുക്കുക',
	'cnw-signup-prompt' => 'അംഗത്വം ആവശ്യമുണ്ടോ?',
	'cnw-call-to-signup' => 'ഇവിടെ അംഗത്വമെടുക്കുക',
	'cnw-login-prompt' => 'മുമ്പേ അംഗത്വമുണ്ടോ?',
	'cnw-call-to-login' => 'ഇവിടെ പ്രവേശിക്കുക',
	'cnw-auth-headline' => 'പ്രവേശിക്കുക',
	'cnw-auth-headline2' => 'അംഗത്വമെടുക്കുക',
	'cnw-auth-facebook-signup' => 'ഫേസ്ബുക്ക് ഉപയോഗിച്ച് അംഗത്വമെടുക്കുക',
	'cnw-auth-facebook-login' => 'ഫേസ്ബുക്ക് ഉപയോഗിച്ച് പ്രവേശിക്കുക',
	'cnw-desc-headline' => 'താങ്കളുടെ വിക്കി എന്തിനെക്കുറിച്ചുള്ളതാണ്?',
	'cnw-desc-creative' => 'താങ്കളുടെ വിഷയം വിശദമാക്കുക',
	'cnw-desc-choose' => 'ഒരു വർഗ്ഗം തിരഞ്ഞെടുക്കുക',
	'cnw-desc-select-one' => 'ഒരെണ്ണം തിരഞ്ഞെടുക്കുക',
	'cnw-desc-change-lang' => 'മാറ്റംവരുത്തുക',
	'cnw-desc-lang' => 'ഭാഷ',
	'cnw-desc-wiki-submit-error' => 'ദയവായി ഒരു വർഗ്ഗം തിരഞ്ഞെടുക്കുക',
	'cnw-welcome-headline' => 'അഭിനന്ദനങ്ങൾ! $1 സൃഷ്ടിക്കപ്പെട്ടിരിക്കുന്നു',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Pendeta penciptaan Wiki]]',
	'cnw-next' => 'Berikutnya',
	'cnw-back' => 'Sebelumnya',
	'cnw-or' => 'atau',
	'cnw-title' => 'Cipta Wiki Baru',
	'cnw-name-wiki-headline' => 'Cipta Wiki Baru',
	'cnw-name-wiki-creative' => 'Wikia ialah tempat terbaik untuk membina sebuah tapak web dan menghimpunkan sebuah komuniti yang berkongsi kegemaran anda.',
	'cnw-name-wiki-label' => 'Namakan wiki anda',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Berikan alamat wiki anda',
	'cnw-name-wiki-submit-error' => 'Eh eh! Anda perlu mengisi kedua-dua kotak di atas sebelum menyambung.',
	'cnw-login' => 'Log masuk',
	'cnw-signup' => 'Buka Akaun',
	'cnw-signup-prompt' => 'Perlukan akaun?',
	'cnw-call-to-signup' => 'Daftar di sini',
	'cnw-login-prompt' => 'Sudah ada akaun?',
	'cnw-call-to-login' => 'Log masuk di sini',
	'cnw-auth-headline' => 'Log masuk',
	'cnw-auth-headline2' => 'Daftar Diri',
	'cnw-auth-creative' => 'Log masuk ke dalam akaun anda untuk terus membina wiki anda.',
	'cnw-auth-signup-creative' => 'Anda perlukan akaun untuk terus membina wiki anda.<br />Pendaftaran hanya mengambil masa seminit!',
	'cnw-auth-facebook-signup' => 'Berdaftar dengan Facebook',
	'cnw-auth-facebook-login' => 'Log masuk dengan Facebook',
	'cnw-desc-headline' => 'Apakah topik wiki anda?',
	'cnw-desc-creative' => 'Jelaskan topik anda',
	'cnw-desc-placeholder' => 'Ini akan terpapar pada laman utama wiki anda.',
	'cnw-desc-tip1' => 'Petua',
	'cnw-desc-tip1-creative' => 'Gunakan ruangan ini untuk menjelaskan wiki anda secara ringkas.',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Nyatakan perincian tentang subjek anda untuk makluman para pengunjung.',
	'cnw-desc-tip3' => 'Petua Pro',
	'cnw-desc-tip3-creative' => 'Jemput orang ramai untuk menyunting dan menambahkan laman supaya wiki anda berkembang',
	'cnw-desc-choose' => 'Pilih kategori',
	'cnw-desc-select-one' => 'Pilih satu',
	'cnw-desc-default-lang' => 'Wiki anda adalah dalam $1',
	'cnw-desc-change-lang' => 'ubah',
	'cnw-desc-lang' => 'Bahasa',
	'cnw-desc-wiki-submit-error' => 'Sila pilih kategori',
	'cnw-theme-headline' => 'Pilih tema',
	'cnw-theme-creative' => 'Pilih tema di bawah. Anda boleh melihat pratonton untuk setiap tema yang anda pilih.',
	'cnw-theme-instruction' => 'Anda boleh mereka tema anda lain kali dengan menggunakan "Alatan Saya".',
	'cnw-upgrade-headline' => 'Nak naik taraf?',
	'cnw-upgrade-creative' => 'Jika anda naik taraf ke Wikia Plus, anda boleh gugurkan iklan dari <span class="wiki-name"></span>, tawaran sekali sahaja buat pengasas baru.',
	'cnw-upgrade-marketing' => 'Wikia Plus merupakan yang terbaik untuk:<ul>
<li>Wiki Profesional</li>
<li>Pertubuhan bukan untang</li>
<li>Keluarga</li>
<li>Sekolah</li>
<li>Projek peribadi</li>
</ul>
Naik taraf wiki anda kepada wiki tanpa iklan dengan membayar $4.95 (USD) sebulan melalui PayPal!',
	'cnw-upgrade-now' => 'Naik Taraf Sekarang',
	'cnw-upgrade-decline' => 'Tak apalah, terus ke wiki saya',
	'cnw-welcome-headline' => 'Bagus! $1 telah dicipta',
	'cnw-welcome-instruction1' => 'Klik butang di bawah untuk mulai membuka laman pada wiki anda.',
	'cnw-welcome-instruction2' => 'Anda akan melihat butang ini di seluruh wiki. Gunakannya pada bila-bila masa anda ingin menambahkan laman baru.',
	'cnw-welcome-help' => 'Cari jawapan, nasihat, dan banyak lagi di <a href="http://community.wikia.com">Community Central</a>.',
	'cnw-error-general' => 'Kami menghadapi masalah ketika memproseskan penciptaan wiki anda. Sila cuba lagi lain kali.',
	'cnw-error-general-heading' => 'Ralat Penciptaan Wiki Baru',
	'cnw-error-database' => 'Ralat pangkalan data: $1',
	'cnw-badword-header' => 'Nanti kejap',
	'cnw-badword-msg' => 'Hai, sila hindari penggunaan kata-kata kesat/terlarang dalam Penerangan Wiki anda: $1',
	'cnw-error-wiki-limit-header' => 'Had wiki dicapai',
	'cnw-error-wiki-limit' => 'Maaf, anda tidak boleh membuka lebih daripada $1 wiki sehari. Tunggi 24 jam sebelum membuka satu lagi wiki.',
	'cnw-error-blocked-header' => 'Akaun disekat',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Wiki Wizard wiki aanmaken]]',
	'cnw-next' => 'Volgende',
	'cnw-back' => 'Vorige',
	'cnw-or' => 'of',
	'cnw-title' => 'Nieuwe wiki aanmaken',
	'cnw-name-wiki-headline' => 'Wiki oprichten',
	'cnw-name-wiki-creative' => 'Wikia is de beste plaats om een website te bouwen en een gemeenschap te laten groeien om het onderwerp dat u aan het hart gaat.',
	'cnw-name-wiki-label' => 'Geef uw wiki een naam',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Geef uw wiki een adres',
	'cnw-name-wiki-submit-error' => 'U moet beide bovenstaande velden invullen om door te kunnen gaan.',
	'cnw-login' => 'Aanmelden',
	'cnw-signup' => 'Registreren',
	'cnw-signup-prompt' => 'Wilt u zich registreren?',
	'cnw-call-to-signup' => 'Hier aanmelden',
	'cnw-login-prompt' => 'Hebt u al een gebruiker?',
	'cnw-call-to-login' => 'Hier aanmelden',
	'cnw-auth-headline' => 'Aanmelden',
	'cnw-auth-headline2' => 'Registreren',
	'cnw-auth-creative' => 'Meld u aan om door te gaan met het opbouwen van uw wiki.',
	'cnw-auth-signup-creative' => 'U hebt een gebruiker nodig om door te kunnen gaan met het bouwen van uw wiki.<br />Registreren kost maar een minuutje van uw tijd!',
	'cnw-auth-facebook-signup' => 'Aanmelden met Facebook',
	'cnw-auth-facebook-login' => 'Aanmelden met Facebook',
	'cnw-desc-headline' => 'Waar gaat uw wiki over?',
	'cnw-desc-creative' => 'Beschrijf uw onderwerp',
	'cnw-desc-placeholder' => 'Dit wordt weergegeven op de hoofdpagina van uw wiki.',
	'cnw-desc-tip1' => 'Tip',
	'cnw-desc-tip1-creative' => 'Gebruik deze ruimte om mensen over uw wiki te vertellen in een paar zinnen',
	'cnw-desc-tip2' => 'Pst!',
	'cnw-desc-tip2-creative' => 'Geef uw bezoeker wat details over uw onderwerp',
	'cnw-desc-tip3' => 'Protip',
	'cnw-desc-tip3-creative' => "Laat mensen weten dat ze kunnen helpen om uw wiki te bewerken en pagina's toe te voegen",
	'cnw-desc-choose' => 'Kies een categorie',
	'cnw-desc-select-one' => 'Maak een keuze',
	'cnw-desc-default-lang' => 'De hoofdtaal van uw wiki is: $1',
	'cnw-desc-change-lang' => 'wijzigen',
	'cnw-desc-lang' => 'Taal',
	'cnw-desc-wiki-submit-error' => 'Kies een categorie',
	'cnw-theme-headline' => 'Ontwerp uw wiki',
	'cnw-theme-creative' => 'Kies hieronder een vormgeving. Als u een vormgeving selecteert, wordt een voorvertoning weergegeven.',
	'cnw-theme-instruction' => 'U kunt uw thema of ontwerp altijd later aanpassen via "Mijn hulpmiddelen".',
	'cnw-upgrade-headline' => 'Wilt u upgraden?',
	'cnw-upgrade-creative' => 'Upgraden naar Wikia Plus maakt het mogelijk om advertenties te verwijderen van <span class="wiki-name"></span>. Deze aanbieding is alleen beschikbaar voor nieuwe oprichters.',
	'cnw-upgrade-marketing' => "Wikia Plus is prima oplossing voor:<ul>
<li>Professionele wiki's</li>
<li>Organisaties zonder winstoogmerk</li>
<li>Families</li>
<li>Scholen</li>
<li>Persoonlijke projecten</li>
</ul>
Schaf uw upgrade aan via PayPal. Geen advertenties voor maar $4,95 per maand!",
	'cnw-upgrade-now' => 'Nu upgraden',
	'cnw-upgrade-decline' => 'Nee, bedankt. Ik wil naar mijn wiki',
	'cnw-welcome-headline' => 'Gefeliciteerd. U hebt de wiki $1 aangemaakt',
	'cnw-welcome-instruction1' => "Klik op de onderstaande knop om pagina's aan uw wiki toe te voegen.",
	'cnw-welcome-instruction2' => 'U ziet deze knop overal in uw wiki. Gebruik hem als u een nieuwe pagina wilt toevoegen.',
	'cnw-welcome-help' => 'Antwoorden, advies en meer op <a href="http://community.wikia.com">Community Central</a>.',
	'cnw-error-general' => 'Er is iets misgegaan tijdens het aanmaken van uw wiki. Probeer het later opnieuw.',
	'cnw-error-general-heading' => 'Fout bij het aanmaken van een nieuwe wiki',
	'cnw-error-database' => 'Databasefout: $1',
	'cnw-badword-header' => 'Pas op!',
	'cnw-badword-msg' => 'Hallo. Probeer u te onthouden van het gebruiken van ongewenste woorden in uw wikibeschrijving: $1',
	'cnw-error-wiki-limit-header' => 'De wikilimiet is bereikt',
	'cnw-error-wiki-limit' => "Hallo. U mag maximaal $1 wiki's per dag aanmaken. Wacht 24 uur om een nieuwe wiki aan te kunnen maken.",
	'cnw-error-blocked-header' => 'Gebruiker geblokkeerd',
	'cnw-error-blocked' => 'U bent geblokkeerd door $1. De reden die gegeven is: $2. (Blokkadenummer voor referentie: $3)',
	'cnw-error-torblock' => "Wiki's aanmaken via het Tor Network is niet toegestaan.",
);

/** ‪Nederlands (informeel)‬ (‪Nederlands (informeel)‬)
 * @author MarkvA
 * @author Siebrand
 */
$messages['nl-informal'] = array(
	'cnw-name-wiki-creative' => 'Wikia is de beste plaats om een website te bouwen en een gemeenschap te laten groeien om het onderwerp dat je aan het hart gaat.',
	'cnw-name-wiki-label' => 'Geef je wiki een naam',
	'cnw-name-wiki-domain-label' => 'Geef je wiki een adres',
	'cnw-name-wiki-submit-error' => 'Je moet beide bovenstaande velden invullen om door te kunnen gaan.',
	'cnw-auth-creative' => 'Meld je aan om door te gaan met het opbouwen van je wiki.',
	'cnw-auth-signup-creative' => 'Je hebt een gebruiker nodig om door te kunnen gaan met het bouwen van uw wiki.<br />Registreren kost maar een minuutje van je tijd!',
	'cnw-desc-headline' => 'Waar gaat je wiki over?',
	'cnw-desc-creative' => 'Beschrijf je onderwerp',
	'cnw-desc-placeholder' => 'Dit wordt weergegeven op de hoofdpagina van je wiki.',
	'cnw-desc-tip1-creative' => 'Gebruik deze ruimte om mensen over je wiki te vertellen in een paar zinnen',
	'cnw-desc-tip2-creative' => 'Geef je bezoeker wat details over je onderwerp',
	'cnw-desc-tip3-creative' => "Laat mensen weten dat ze kunnen helpen om je wiki te bewerken en pagina's toe te voegen",
	'cnw-desc-default-lang' => 'De hoofdtaal van je wiki is: $1',
	'cnw-theme-creative' => 'Kies hieronder een vormgeving. Als je een vormgeving selecteert, wordt een voorvertoning weergegeven.',
	'cnw-theme-instruction' => 'Je kunt je thema of ontwerp altijd later aanpassen via "Mijn hulpmiddelen".',
	'cnw-upgrade-headline' => 'Wil je upgraden?',
	'cnw-welcome-instruction1' => "Klik op de onderstaande knop om pagina's aan je wiki toe te voegen.",
	'cnw-welcome-instruction2' => 'Je ziet deze knop overal in je wiki. Gebruik hem als je een nieuwe pagina wilt toevoegen.',
	'cnw-error-general' => 'Er is iets misgegaan tijdens het aanmaken van je wiki. Probeer het later opnieuw.',
	'cnw-error-wiki-limit' => "Hoi. Je mag maximaal $1 wiki's per dag aanmaken. Wacht 24 uur om een nieuwe wiki aan te kunnen maken.",
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Audun
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Veiviser for opprettelse av wiki]]',
	'cnw-next' => 'Neste',
	'cnw-back' => 'Tilbake',
	'cnw-or' => 'eller',
	'cnw-title' => 'Opprett ny wiki',
	'cnw-name-wiki-headline' => 'Start en wiki',
	'cnw-name-wiki-creative' => 'Wikia er det beste stedet å bygge et nettsted og lage en fellesskap rundt det du elsker.',
	'cnw-name-wiki-label' => 'Navngi wikien din',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Gi wikien din en adresse',
	'cnw-name-wiki-submit-error' => 'Ops! Du må fylle ut begge boksene over for å fortsette.',
	'cnw-login' => 'Logg inn',
	'cnw-signup' => 'Opprett konto',
	'cnw-signup-prompt' => 'Trenger du en konto?',
	'cnw-call-to-signup' => 'Registrer deg her',
	'cnw-login-prompt' => 'Har du allerede en konto?',
	'cnw-call-to-login' => 'Logg inn her',
	'cnw-auth-headline' => 'Logg inn',
	'cnw-auth-headline2' => 'Registrer deg',
	'cnw-auth-creative' => 'Logg inn på kontoen din og fortsett å bygge wikien din.',
	'cnw-auth-signup-creative' => 'Du vil trenge en konto for å fortsette å bygge wikien din.<br />Det tar bare et øyeblikk å registrere seg!',
	'cnw-auth-facebook-signup' => 'Registrer deg med Facebook',
	'cnw-auth-facebook-login' => 'Logg inn med Facebook',
	'cnw-desc-headline' => 'Hva handler wikien din om?',
	'cnw-desc-creative' => 'Beskriv emnet ditt',
	'cnw-desc-placeholder' => 'Dette vil vises på hovedsiden til wikien din.',
	'cnw-desc-tip1' => 'Hint',
	'cnw-desc-tip1-creative' => 'Bruk denne plassen til å fortelle folk om wikien din med en setning eller to',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Gi dine besøkende noen spesifikke detaljer om feltet ditt',
	'cnw-desc-tip3' => 'Eksperttips',
	'cnw-desc-tip3-creative' => 'La folk få vite at de kan hjelpe wikien din med å vokse ved å redigere og legge til sider',
	'cnw-desc-choose' => 'Velg kategori',
	'cnw-desc-select-one' => 'Velg en',
	'cnw-desc-default-lang' => 'Wikien din vil være på $1',
	'cnw-desc-change-lang' => 'endre',
	'cnw-desc-lang' => 'Språk',
	'cnw-desc-wiki-submit-error' => 'Vennligst velg en kategori',
	'cnw-theme-headline' => 'Velg et tema',
	'cnw-theme-creative' => 'Velg et tema under. Du vil kunne se en forhåndsvisning av hvert tema når du markerer det.',
	'cnw-theme-instruction' => 'Du kan også utforme ditt eget tema senere ved å gå til «Mine verktøy».',
	'cnw-upgrade-headline' => 'Vil du oppgradere?',
	'cnw-upgrade-creative' => 'Å oppgradere til Wikia Pluss lar deg fjerne annonser fra <span class="wiki-name"></span>. Et engangstilbud er tilgjengelig kun for nye grunnleggere.',
	'cnw-upgrade-marketing' => 'Wikia Pluss er en flott løsning for:<ul>
<li>Profesjonelle wikier</li>
<li>Ideelle prosjekter</li>
<li>Familier</li>
<li>Skoler</li>
<li>Personlige prosjekter</li>
</ul>
Oppgrader gjennom PayPal for å få en reklamefri wiki til kun $4,95 per måned!',
	'cnw-upgrade-now' => 'Oppgrader nå',
	'cnw-upgrade-decline' => 'Nei takk, fortsett til wikien min',
	'cnw-welcome-headline' => 'Gratulerer! $1 har blitt opprettet',
	'cnw-welcome-instruction1' => 'Trykk på knappen under for å begynne å legge til siden på wikien din.',
	'cnw-welcome-instruction2' => 'Du vil se denne knappen på wikien din, bruk den når du vil legge til en ny side.',
	'cnw-welcome-help' => 'Finn svar, råd, og mer på <a href="http://community.wikia.com">Community Central</a>.',
	'cnw-error-general' => 'Noe gikk galt under opprettning av wikien din. Vennligst prøv igjen senere.',
	'cnw-error-general-heading' => 'Opprett ny wiki-feil',
	'cnw-error-database' => 'Databasefeil: $1',
	'cnw-badword-header' => 'Hei der',
	'cnw-badword-msg' => 'Hei, vennligst unngå å bruke disse grove eller forbudte ordene i beskrivelsen av wikien: $1',
	'cnw-error-wiki-limit-header' => 'Wikigrense nådd',
	'cnw-error-wiki-limit' => 'Hei, du er begrenset til $1 wikiopprettelse per dag.  Vent 24 timer før du oppretter en annen wiki.',
	'cnw-error-blocked-header' => 'Konto blokkert',
	'cnw-error-blocked' => 'Du har blitt blokkert av $1. Begrunnelsen var: $2. (Blokkerings-ID for referanse: $3)',
	'cnw-error-torblock' => 'Å opprette wikier via Tor-nettverket er ikke tillatt.',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'cnw-or' => 'يا',
	'cnw-title' => 'يو نوی ويکي جوړول',
	'cnw-name-wiki-headline' => 'يو ويکي پيلول',
	'cnw-name-wiki-label' => 'خپله ويکي نومول',
	'cnw-name-wiki-wiki' => 'ويکي',
	'cnw-name-wiki-domain-label' => 'خپل ويکي ته يوه پته ورکول',
	'cnw-login' => 'ننوتل',
	'cnw-signup' => 'ګڼون جوړول',
	'cnw-signup-prompt' => 'آيا يو ګڼون غواړۍ؟',
	'cnw-call-to-signup' => 'نومليکل دلته ترسره کېږي',
	'cnw-login-prompt' => 'آيا وار دمخې يو ګڼون لرۍ؟',
	'cnw-call-to-login' => 'دلته ننوتل',
	'cnw-auth-headline' => 'ننوتل',
	'cnw-auth-headline2' => 'نومليکل',
	'cnw-desc-headline' => 'ستاسې ويکي د څه په اړه دی؟',
	'cnw-desc-placeholder' => 'دا به ستاسې د ويکي په لومړي مخ ښکاره شي.',
	'cnw-desc-tip1-creative' => 'دلته په يوې يا دوه کرښو کې خلکو ته مالومات ورکړۍ چې ستاسې ويکي د څه په اړه دی',
	'cnw-desc-choose' => 'يوه وېشنيزه ټاکل',
	'cnw-desc-select-one' => 'يو وټاکۍ',
	'cnw-desc-default-lang' => 'ستاسې ويکي به په $1 ژبه وي',
	'cnw-desc-change-lang' => 'بدلول',
	'cnw-desc-lang' => 'ژبه',
	'cnw-desc-wiki-submit-error' => 'يوه وېشنيزه وټاکۍ',
	'cnw-theme-headline' => 'خپل ويکي سکښتل',
	'cnw-welcome-headline' => 'بختور مو شه، د $1 ويکي جوړ شو',
);

/** Portuguese (Português)
 * @author Hamilton Abreu
 */
$messages['pt'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Assistente de criação de wikis]]',
	'cnw-next' => 'Seguinte',
	'cnw-back' => 'Anterior',
	'cnw-or' => 'ou',
	'cnw-title' => 'Criar uma Wiki Nova',
	'cnw-name-wiki-headline' => 'Iniciar uma Wiki',
	'cnw-name-wiki-creative' => 'A Wikia é o melhor sítio para criar um site e desenvolver uma comunidade em torno de um tema do seu agrado.',
	'cnw-name-wiki-label' => 'Nome da sua wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Dê um endereço à sua wiki',
	'cnw-name-wiki-submit-error' => 'Para continuar tem de preencher ambas as caixas acima.',
	'cnw-login' => 'Iniciar Sessão',
	'cnw-signup' => 'Criar Conta',
	'cnw-signup-prompt' => 'Precisa de uma conta?',
	'cnw-call-to-signup' => 'Registe-se aqui',
	'cnw-login-prompt' => 'Já tem uma conta?',
	'cnw-call-to-login' => 'Inicie uma sessão aqui',
	'cnw-auth-headline' => 'Iniciar Sessão',
	'cnw-auth-headline2' => 'Registar',
	'cnw-auth-creative' => 'Entre na sua conta para continuar a criar a wiki',
	'cnw-auth-signup-creative' => 'Precisa de ter uma conta para continuar a construir a sua wiki.<br />Só leva um minuto a registar-se!',
	'cnw-auth-facebook-signup' => 'Registe-se pelo Facebook',
	'cnw-auth-facebook-login' => 'Entrar pelo Facebook',
	'cnw-desc-headline' => 'Qual é o assunto da sua wiki?',
	'cnw-desc-creative' => 'Descreva o seu assunto',
	'cnw-desc-placeholder' => 'Isto irá aparecer na página principal da sua wiki.',
	'cnw-desc-tip1' => 'Sugestão',
	'cnw-desc-tip1-creative' => 'Use este espaço para descrever a sua wiki aos visitantes numa frase ou duas',
	'cnw-desc-tip2' => 'Conselho',
	'cnw-desc-tip2-creative' => 'Dê aos visitantes detalhes específicos sobre o assunto da sua wiki',
	'cnw-desc-tip3' => 'Dica Profissional',
	'cnw-desc-tip3-creative' => 'Diga às pessoas que podem ajudar a desenvolver a wiki editando e acrescentando páginas',
	'cnw-desc-choose' => 'Escolher uma categoria',
	'cnw-desc-select-one' => 'Seleccione uma',
	'cnw-desc-default-lang' => 'A sua wiki será em $1',
	'cnw-desc-change-lang' => 'alterar',
	'cnw-desc-lang' => 'Língua',
	'cnw-desc-wiki-submit-error' => 'Escolha uma categoria, por favor',
	'cnw-theme-headline' => 'Escolha uma variante do tema',
	'cnw-theme-creative' => 'Escolha uma variante do tema; pode ver uma antevisão de cada variante, clicando-a.',
	'cnw-theme-instruction' => 'Também pode criar uma variante personalizada mais tarde, usando "As Minhas Ferramentas".',
	'cnw-upgrade-headline' => 'Pretende a versão melhorada?',
	'cnw-upgrade-creative' => 'A versão Wikia Plus permite-lhe remover os anúncios da <span class="wiki-name"></span>, uma oferta única só disponível para novos fundadores.',
	'cnw-upgrade-marketing' => 'A versão Wikia Plus é excelente para:<ul>
<li>Wikis Profissionais</li>
<li>Organizações sem Fins Lucrativos</li>
<li>Famílias</li>
<li>Escolas</li>
<li>Projectos Pessoais</li>
</ul>
Adopte esta versão através do PayPal para ter uma wiki livre de anúncios por apenas $4.95 por mês!',
	'cnw-upgrade-now' => 'Usar a Versão Melhorada',
	'cnw-upgrade-decline' => 'Não obrigado, continuar para a minha wiki',
	'cnw-welcome-headline' => 'Parabéns! A $1 foi criada',
	'cnw-welcome-instruction1' => 'Clique o botão abaixo para começar a criar páginas na sua wiki.',
	'cnw-welcome-instruction2' => 'Verá este botão em todas as páginas da wiki; use-o em qualquer altura para criar uma página nova.',
	'cnw-welcome-help' => 'Encontrará respostas, conselhos e mais na <a href="http://pt.wikia.com">Comunidade Central</a>.',
	'cnw-error-general' => 'Ocorreu um erro não identificado ao criar a sua wiki. Tente novamente mais tarde, por favor.',
	'cnw-error-general-heading' => 'Erro ao Criar Wiki Nova',
	'cnw-badword-header' => 'Atenção',
	'cnw-badword-msg' => 'Não use estas palavras impróprias ou proibidas na Descrição da Wiki, por favor: $1',
	'cnw-error-wiki-limit-header' => 'O limite de wikis foi atingido',
	'cnw-error-wiki-limit' => 'Olá, está limitado à criação de $1 wiki por dia. Aguarde 24 horas antes de criar outra wiki.',
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Aristóbulo
 */
$messages['pt-br'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Assistente de criação de wikis]]',
	'cnw-next' => 'Seguinte',
	'cnw-back' => 'Anterior',
	'cnw-or' => 'ou',
	'cnw-title' => 'Criar uma Wiki Nova',
	'cnw-name-wiki-headline' => 'Iniciar uma Wiki',
	'cnw-name-wiki-creative' => 'A Wikia é o melhor lugar para criar um site e desenvolver uma comunidade em torno de um tema do seu agrado.',
	'cnw-name-wiki-label' => 'Nome da sua wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Dê um endereço à sua wiki',
	'cnw-name-wiki-submit-error' => 'Para continuar tem de preencher ambas as caixas acima.',
	'cnw-login' => 'Iniciar Sessão',
	'cnw-signup' => 'Criar Conta',
	'cnw-signup-prompt' => 'Precisa de uma conta?',
	'cnw-call-to-signup' => 'Registe-se aqui',
	'cnw-login-prompt' => 'Já tem uma conta?',
	'cnw-call-to-login' => 'Inicie uma sessão aqui',
	'cnw-auth-headline' => 'Iniciar Sessão',
	'cnw-auth-headline2' => 'Registar',
	'cnw-auth-creative' => 'Entre na sua conta para continuar a criar a wiki',
	'cnw-auth-signup-creative' => 'Precisa de ter uma conta para continuar a construir a sua wiki.<br />Só leva um minuto para registrar-se!',
	'cnw-auth-facebook-signup' => 'Registre-se pelo Facebook',
	'cnw-auth-facebook-login' => 'Entrar pelo Facebook',
	'cnw-desc-headline' => 'Qual é o assunto da sua wiki?',
	'cnw-desc-creative' => 'Descreva o seu assunto',
	'cnw-desc-placeholder' => 'Isto irá aparecer na página principal da sua wiki.',
	'cnw-desc-tip1' => 'Sugestão',
	'cnw-desc-tip1-creative' => 'Use este espaço para descrever a sua wiki aos visitantes numa frase ou duas',
	'cnw-desc-tip2' => 'Ei',
	'cnw-desc-tip2-creative' => 'Dê aos visitantes alguns detalhes específicos sobre o assunto',
	'cnw-desc-tip3' => 'Sugestão Para Profissionais',
	'cnw-desc-tip3-creative' => 'Informe as pessoas de que podem ajudar a desenvolver a wiki editando e acrescentando páginas',
	'cnw-desc-choose' => 'Escolher uma categoria',
	'cnw-desc-select-one' => 'Selecione uma',
	'cnw-desc-default-lang' => 'A sua wiki será em $1',
	'cnw-desc-change-lang' => 'alterar',
	'cnw-desc-lang' => 'Língua',
	'cnw-desc-wiki-submit-error' => 'Escolha uma categoria, por favor',
	'cnw-theme-headline' => 'Escolha um Compositor de temas',
	'cnw-theme-creative' => 'Escolha uma compositor de temas abaixo; verá uma antevisão de cada compositor à medida que a escolher.',
	'cnw-theme-instruction' => 'Tambem poderá criar um compositor personalizado mais tarde, usando "As Minhas Ferramentas".',
	'cnw-upgrade-headline' => 'Pretende a versão melhorada?',
	'cnw-upgrade-creative' => 'A versão Wikia Plus permite-lhe remover os anúncios da <span class="wiki-name"></span>, uma oferta única só disponível para novos fundadores.',
	'cnw-upgrade-marketing' => 'A versão Wikia Plus é excelente para:<ul>
<li>Wikis Profissionais</li>
<li>Organizações sem Fins Lucrativos</li>
<li>Famílias</li>
<li>Escolas</li>
<li>Projetos Pessoais</li>
</ul>
Adote esta versão através do PayPal para ter uma wiki livre de anúncios por apenas $4.95 por mês!',
	'cnw-upgrade-now' => 'Usar a Versão Melhorada',
	'cnw-upgrade-decline' => 'Não obrigado, continuar para a minha wiki',
	'cnw-welcome-headline' => 'Parabéns! A $1 foi criada',
	'cnw-welcome-instruction1' => 'Clique o botão abaixo para começar a criar páginas na sua wiki.',
	'cnw-welcome-instruction2' => 'Verá este botão em toda a wiki; use-o em qualquer altura para criar uma página nova.',
	'cnw-welcome-help' => 'Encontrará respostas, conselhos e mais na <a href="http://pt.wikia.com">Comunidade Central</a>.',
	'cnw-error-general' => 'Algo deu errado ao criar a sua wiki. Por favor, tente novamente mais tarde.',
	'cnw-error-general-heading' => 'Erro ao Criar uma Wiki Nova',
	'cnw-badword-header' => 'Atenção',
	'cnw-badword-msg' => 'Não use palavras impróprias ou proibidas na Descrição da Wiki, por favor',
);

/** Romanian (Română)
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'cnw-or' => 'sau',
	'cnw-title' => 'Crează un nou wiki',
	'cnw-name-wiki-headline' => 'Porneşte un wiki',
	'cnw-name-wiki-creative' => 'Wikia este cel mai bun loc ca să construieşti un sait web şi să creşti o comunitate în jurul a ceea ce-ţi place.',
	'cnw-name-wiki-domain-label' => 'Dă-i wiki-ului tău o adresă',
	'cnw-name-wiki-submit-error' => 'Ups! Trebuie să completezi ambele căsuţe de mai sus pentru a continua.',
	'cnw-login' => 'Autentificare',
	'cnw-signup' => 'Crează un cont',
	'cnw-signup-prompt' => 'Ai nevoie de-un cont?',
	'cnw-call-to-signup' => 'Înscrie-te aici',
	'cnw-login-prompt' => 'Aveţi deja un cont?',
	'cnw-call-to-login' => 'Autentificaţi-vă aici',
	'cnw-auth-headline' => 'Autentificare',
	'cnw-auth-headline2' => 'Înregistrare',
	'cnw-auth-facebook-signup' => 'Înscrieţi-vă cu Facebook',
	'cnw-auth-facebook-login' => 'Autentificaţi-vă cu Facebook',
	'cnw-desc-creative' => 'Descrieţi-vă subiectul',
	'cnw-desc-placeholder' => 'Asta va apărea pe pagina principală a wiki-ului tău.',
	'cnw-desc-tip2-creative' => 'Dă-le vizitatorilor tăi unele detalii specifice despre subiectul tău',
	'cnw-desc-tip3-creative' => 'Informează oamenii despre faptul că pot să ajute wiki-ul tău să crească prin modificarea şi adăugarea de pagini',
	'cnw-desc-choose' => 'Alege o categorie',
	'cnw-desc-select-one' => 'Selectează una',
	'cnw-desc-default-lang' => 'Wiki-ul tău va fi în $1',
	'cnw-desc-change-lang' => 'schimbă',
	'cnw-desc-lang' => 'Limbă',
	'cnw-desc-wiki-submit-error' => 'Te rugăm alege o categorie',
	'cnw-theme-headline' => 'Alege o temă',
	'cnw-upgrade-headline' => 'Doriţi să realizaţi o actualizare?',
	'cnw-upgrade-marketing' => 'Wikia Plus este o soluţie nemaipomenită pentru:<ul>
<li>Wiki-urile profesionale</li>
<li>Acţiunile nonprofit</li>
<li>Familii</li>
<li>Școli</li>
<li>Proiecte personale</li>
</ul>
Actualizează prin PayPal pentru a obţine un wiki cu reclame gratis pentru doar 4.95$ pe lună!',
	'cnw-upgrade-now' => 'Actualizează acum',
	'cnw-upgrade-decline' => 'Nu, mulţumesc. Continuă spre wiki-ul meu',
	'cnw-welcome-instruction1' => 'Apasă pe butonul de mai jos pentru a începe să adaugi pagini wiki-ului tău.',
);

/** Russian (Русский)
 * @author Kuzura
 */
$messages['ru'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Мастер создания вики]]',
	'cnw-next' => 'Далее',
	'cnw-back' => 'Назад',
	'cnw-or' => 'или',
	'cnw-title' => 'Создать новую вики',
	'cnw-name-wiki-headline' => 'Начать вики',
	'cnw-name-wiki-creative' => 'Викия - это лучшее место для создания веб-сайта о том, что вы любите, и роста сообщества вокруг этого.',
	'cnw-name-wiki-label' => 'Название вики',
	'cnw-name-wiki-wiki' => 'Вики',
	'cnw-name-wiki-domain-label' => 'Задайте вашей вики адрес',
	'cnw-name-wiki-submit-error' => 'Вам нужно заполнить оба полях выше для того, чтобы продолжить.',
	'cnw-login' => 'Представиться системе',
	'cnw-signup' => 'Создать учётную запись',
	'cnw-signup-prompt' => 'Нужен аккаунт?',
	'cnw-call-to-signup' => 'Регистрация здесь',
	'cnw-login-prompt' => 'Вы уже зарегистрированы?',
	'cnw-call-to-login' => 'Войти в систему',
	'cnw-auth-headline' => 'Представиться системе',
	'cnw-auth-headline2' => 'Регистрация',
	'cnw-auth-creative' => 'Войти в свою учётную запись, чтобы продолжить создание вики.',
	'cnw-auth-signup-creative' => 'Вам нужна учётная запись, чтобы продолжить создание вики.<br />Регистрация займёт всего одну минуту!',
	'cnw-auth-facebook-signup' => 'Зарегистрироваться через Facebook',
	'cnw-auth-facebook-login' => 'Войти через Facebook',
	'cnw-desc-headline' => 'О чём будет ваша вики?',
	'cnw-desc-creative' => 'Опишите вашу тему',
	'cnw-desc-placeholder' => 'Это будет отображаться на заглавной странице вики.',
	'cnw-desc-tip1' => 'Подсказка',
	'cnw-desc-tip1-creative' => 'Используйте это место, чтобы кратко рассказать людям о вашей вики в одно или два предложения.',
	'cnw-desc-tip2-creative' => 'Раскройте посетителям вики некоторые подробности вашей темы',
	'cnw-desc-tip3' => 'Совет',
	'cnw-desc-tip3-creative' => 'Люди должны знать, что они могут помочь вашей вики расти, редактируя и добавляя страницы',
	'cnw-desc-choose' => 'Выберите категорию',
	'cnw-desc-select-one' => 'Выберите одно',
	'cnw-desc-default-lang' => 'Ваша вики будет в разделе $1',
	'cnw-desc-change-lang' => 'изменить',
	'cnw-desc-lang' => 'Язык',
	'cnw-desc-wiki-submit-error' => 'Пожалуйста, выберите категорию',
	'cnw-theme-headline' => 'Выбрать тему',
	'cnw-theme-creative' => 'Выберите тему ниже; вы можете просмотреть каждую тему до того, как сделать окончательный выбор.',
	'cnw-theme-instruction' => 'Вы также можете создавать свои собственные темы позже, перейдя в "Мои инструменты".',
	'cnw-upgrade-headline' => 'Вы хотите обновить?',
	'cnw-upgrade-now' => 'Обновить сейчас',
	'cnw-upgrade-decline' => 'Нет, спасибо; продолжить создание моей вики',
	'cnw-welcome-headline' => 'Поздравляем! $1 создана',
	'cnw-welcome-instruction1' => 'Нажмите на кнопку ниже, чтобы начать добавлять страницы на вики.',
	'cnw-welcome-instruction2' => 'Вы будете видеть эту кнопку всегда и можете использовать её в любое время, когда захотите добавить новую страницу.',
	'cnw-welcome-help' => 'Найти ответы, советы и многое другое на <a href="http://community.wikia.com">Центральной вики</a>.',
	'cnw-error-general' => 'Что-то пошло не так при создании вики. Пожалуйста, повторите попытку позже.',
	'cnw-error-general-heading' => 'Ошибка при создании новой вики',
	'cnw-badword-header' => 'Эй там',
	'cnw-badword-msg' => 'Пожалуйста, воздержитесь от использования плохих или запрещенных слов в описании вики: $1',
	'cnw-error-wiki-limit-header' => 'Лимит создания вики',
	'cnw-error-wiki-limit' => 'Вы достигли ограничения $1 на создание викий в день. Подождите 24 часа, прежде чем создавать другую вики.',
);

/** Serbian Cyrillic ekavian (‪Српски (ћирилица)‬)
 * @author Rancher
 */
$messages['sr-ec'] = array(
	'cnw-next' => 'Напред',
	'cnw-back' => 'Назад',
	'cnw-or' => 'или',
	'cnw-title' => 'Прављење нове викије',
	'cnw-name-wiki-headline' => 'Започињање викије',
	'cnw-name-wiki-label' => 'Дајте назив викији',
	'cnw-name-wiki-wiki' => 'Вики',
	'cnw-login' => 'Пријави ме',
	'cnw-signup' => 'Отвори налог',
	'cnw-signup-prompt' => 'Немате налог?',
	'cnw-call-to-signup' => 'Отворите налог овде',
	'cnw-call-to-login' => 'Пријавите се овде',
	'cnw-auth-headline' => 'Започните викију',
	'cnw-desc-headline' => 'Шта се ради на овој викији?',
	'cnw-desc-creative' => 'Опишите тему',
	'cnw-desc-tip1' => 'Први савет',
	'cnw-desc-tip2' => 'Други савет',
	'cnw-desc-tip2-creative' => 'Не заборавите да додате детаље',
	'cnw-desc-tip3' => 'Трећи савет',
	'cnw-desc-choose' => 'Изаберите категорију',
	'cnw-desc-change-lang' => 'промени',
	'cnw-desc-lang' => 'Језик',
	'cnw-desc-wiki-submit-error' => 'Изаберите категорију',
	'cnw-upgrade-headline' => 'Желите ли да доградите?',
);

/** Swedish (Svenska)
 * @author Lokal Profil
 * @author McDutchie
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Guide för att skapa en wiki]]',
	'cnw-next' => 'Nästa',
	'cnw-back' => 'Tillbaka',
	'cnw-or' => 'eller',
	'cnw-title' => 'Skapa en ny Wiki',
	'cnw-name-wiki-headline' => 'Starta en Wiki',
	'cnw-name-wiki-creative' => 'Wikia är den bästa platsen att bygga en webbplats och växa en gemenskap kring det du älskar.',
	'cnw-name-wiki-label' => 'Ge din wiki ett namn',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Ge din wiki en adress',
	'cnw-name-wiki-submit-error' => 'Hoppsan! Du måste fylla i båda rutorna ovan att hålla igång.',
	'cnw-login' => 'Logga in',
	'cnw-signup' => 'Skapa konto',
	'cnw-signup-prompt' => 'Behöver du ett konto?',
	'cnw-call-to-signup' => 'Registrera dig här',
	'cnw-login-prompt' => 'Har du redan ett konto?',
	'cnw-call-to-login' => 'Logga in här',
	'cnw-auth-headline' => 'Logga in',
	'cnw-auth-headline2' => 'Registrera dig',
	'cnw-auth-creative' => 'Logga in på ditt konto för att fortsätta bygga på din wiki.',
	'cnw-auth-signup-creative' => 'Du kommer att behöva ett konto för att fortsätta bygga på din wiki. <br /> Det tar bara en minut att registrera dig!',
	'cnw-auth-facebook-signup' => 'Registrera dig med Facebook',
	'cnw-auth-facebook-login' => 'Logga in med Facebook',
	'cnw-desc-headline' => 'Vad handlar din wiki om?',
	'cnw-desc-creative' => 'Beskriv ditt ämne',
	'cnw-desc-placeholder' => 'Det här kommer att visas på huvudsidan i din wiki.',
	'cnw-desc-tip1' => 'Tips',
	'cnw-desc-tip1-creative' => 'Använd detta utrymme för att berätta för folk vad din wiki handlar om med en mening eller två',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Ge dina besökare några specifika detaljer om ditt ämne',
	'cnw-desc-tip3' => 'Experttips',
	'cnw-desc-tip3-creative' => 'Låt folk vet att de kan hjälpa din wiki växa genom att redigera och lägga till sidor',
	'cnw-desc-choose' => 'Välj en kategori',
	'cnw-desc-select-one' => 'Välj en',
	'cnw-desc-default-lang' => 'Din wiki kommer att vara på $1',
	'cnw-desc-change-lang' => 'ändra',
	'cnw-desc-lang' => 'Språk',
	'cnw-desc-wiki-submit-error' => 'Välj en kategori',
	'cnw-theme-headline' => 'Designa din wiki',
	'cnw-theme-creative' => 'Välj ett tema nedan. Du kommer att se en förhandsgranskning av varje tema när du markerar det.',
	'cnw-theme-instruction' => 'Du kan också utforma ditt egna tema senare på "Mina verktyg".',
	'cnw-upgrade-headline' => 'Vill du uppgradera?',
	'cnw-upgrade-creative' => 'Om du uppgraderar till Wikia Plus kan du ta bort annonser från <span class="wiki-name"></span>. Ett engångserbjudande endast tillgängligt för nya grundare.',
	'cnw-upgrade-marketing' => 'Wikia Plus är en bra lösning för:<ul>
<li>Professionella Wikis</li>
<li>Ideella organisationer</li>
<li>Familjer</li>
<li>Skolor</li>
<li>Personliga projekt</li>
</ul>
Uppgradera via PayPal för att få en reklamfri wiki för endast $4,95 per månad!',
	'cnw-upgrade-now' => 'Uppgradera nu',
	'cnw-upgrade-decline' => 'Nej tack, fortsätt till min wiki',
	'cnw-welcome-headline' => 'Gratulerar, har du skapat $1',
	'cnw-welcome-instruction1' => 'Klicka på knappen nedan för att börja lägga till sidor på din wiki.',
	'cnw-welcome-instruction2' => 'Du kommer att se den här knappen på din wiki, använd den när du vill lägga till en ny sida.',
	'cnw-welcome-help' => 'Hitta svar, råd och mer på <a href="http://community.wikia.com">Community Central</a> .',
	'cnw-error-general' => 'Någonting gick fel när du skapade din wiki. Var god försök igen senare.',
	'cnw-error-general-heading' => 'Skapa ny Wiki-fel',
	'cnw-badword-header' => 'Hallå där',
	'cnw-badword-msg' => 'Hej, var god avstå från att använda dessa grova eller fula ord i beskrivningen av din wiki: $1',
	'cnw-error-wiki-limit-header' => 'Wiki-gräns nådd',
	'cnw-error-wiki-limit' => 'Hej, du är begränsad till $1 skapelser av wikis per dag. Vänta 24 timmar innan du skapar en annan wiki.',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'cnw-next' => 'తదుపరి',
	'cnw-back' => 'వెనుకకు',
	'cnw-or' => 'లేదా',
	'cnw-name-wiki-label' => 'మీ వికీ పేరు',
	'cnw-name-wiki-wiki' => 'వికీ',
	'cnw-name-wiki-domain-label' => 'వికీ చిరునామా',
	'cnw-signup-prompt' => 'ఖాతా కావాలా?',
	'cnw-call-to-signup' => 'ఇక్కడ నమోదు చేసుకోండి',
	'cnw-login-prompt' => 'ఇప్పటికే మీకు ఖాతా ఉందా?',
	'cnw-call-to-login' => 'ఇక్కడ ప్రవేశించండి',
	'cnw-auth-headline' => 'వికీని మొదలుపెట్టడం',
	'cnw-desc-headline' => 'మీ వికీ దేని గురించి?',
	'cnw-desc-tip1' => 'చిట్కా',
	'cnw-desc-tip1-creative' => 'మీ వికీ దేని గురించో ప్రజలకు చెప్పండి',
	'cnw-desc-tip2' => 'చిట్కా 2',
	'cnw-desc-tip2-creative' => 'వివరాలను చేర్చడం మర్చిపోకండి',
	'cnw-desc-tip3' => 'చిట్కా 3',
	'cnw-desc-tip3-creative' => 'తోడ్పడమని ప్రజలని ఆహ్వానించండి',
	'cnw-desc-choose' => 'వర్గాన్ని ఎంచుకోండి',
	'cnw-desc-default-lang' => 'మీ వికీ $1 లో ఉంటుంది',
	'cnw-desc-change-lang' => 'మార్చండి',
	'cnw-desc-lang' => 'భాష',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Madyikerong panlikha ng Wiki]]',
	'cnw-next' => 'Susunod',
	'cnw-back' => 'Bumalik',
	'cnw-or' => 'o',
	'cnw-title' => 'Lumikha ng Bagong Wiki',
	'cnw-name-wiki-headline' => 'Magsimula ng isang Wiki',
	'cnw-name-wiki-creative' => 'Ang Wikia ang pinakamahusay na pook upang makapagtatag ng isang websayt at makapagpalaki ng isang pamayanan sa paligid ng minamahal mong bagay.',
	'cnw-name-wiki-label' => 'Pangalanan ang wiki mo',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Bigyan ang wiki ng isang tirahan',
	'cnw-name-wiki-submit-error' => 'Naku! Dapat mong kapwa punuan ang mga kahong nasa itaas upang makapagpatuloy.',
	'cnw-login' => 'Lumagdang Papasok',
	'cnw-signup' => 'Lumikha ng Akawnt',
	'cnw-signup-prompt' => 'Kailangan mo ng isang akawnt?',
	'cnw-call-to-signup' => 'Magpatala rito',
	'cnw-login-prompt' => 'Mayroon ka nang isang akawnt?',
	'cnw-call-to-login' => 'Lumagda rito',
	'cnw-auth-headline' => 'Lumagdang Papasok',
	'cnw-auth-headline2' => 'Magpatala Na',
	'cnw-auth-creative' => 'Lumagda papasok sa akawnt mo upang makapagpatuloy sa pagtatayo ng wiki mo.',
	'cnw-auth-signup-creative' => 'Kakailanganin mo ang isang akawnt upang makapagpatuloy sa pagbubuo ng wiki mo.<br />Isang minuto lang ang kailangan sa pagpapatala!',
	'cnw-auth-facebook-signup' => 'Magpatalang may Facebook',
	'cnw-auth-facebook-login' => 'Lumagdang may Facebook',
	'cnw-desc-headline' => 'Tungkol ba saan ang wiki mo?',
	'cnw-desc-creative' => 'Ilarawan ang paksa mo',
	'cnw-desc-placeholder' => 'Lilitaw ito sa pangunahing pahina ng wiki mo.',
	'cnw-desc-tip1' => 'Pahiwatig',
	'cnw-desc-tip1-creative' => 'Gamitin ang puwang na ito upang sabihin sa mga tao ang tungkol sa wiki mo sa pamamagitan ng isa o dalawang mga pangungusap',
	'cnw-desc-tip2' => 'Hoy',
	'cnw-desc-tip2-creative' => 'Bigyan ang mga panauhin mo ng ilang partikular na mga detalye ukol sa paksa mo',
	'cnw-desc-tip3' => 'Pahiwatig ng Dalubhasa',
	'cnw-desc-tip3-creative' => 'Ipaalam sa mga tao na makakatulong sila sa pagpapalaki ng wiki mo sa pamamagitan ng pamamatnugot at pagdaragdag ng mga pahina',
	'cnw-desc-choose' => 'Pumili ng isang kategorya',
	'cnw-desc-select-one' => 'Pumili ng isa',
	'cnw-desc-default-lang' => 'Ang wiki mo ay magiging nasa $1',
	'cnw-desc-change-lang' => 'baguhin',
	'cnw-desc-lang' => 'Wika',
	'cnw-desc-wiki-submit-error' => 'Mangyaring pumili ng isang kategorya',
	'cnw-theme-headline' => 'Pumili ng isang tema',
	'cnw-theme-creative' => 'Pumili ng isang temang nasa ibaba, magagawa mong makakita ng isang paunang pagtanaw ng bawat tema habang pinipili mo ito.',
	'cnw-theme-instruction' => 'Makakapagdisenyo ka rin ng sarili mong tema paglaon sa pamamagitan ng pagpunta sa "Mga Kasangkapan Ko".',
	'cnw-upgrade-headline' => 'Nais mo bang magpataas ng antas?',
	'cnw-upgrade-creative' => 'Ang pagpapataas upang maging Wikia Plus ay nagpapahintulot sa iyong matanggal ang mga anunsiyo mula sa <span class="wiki-name"></span>, isang pang-isang ulit lamang na alok na makukuha ng bagong mga tagapagtatag.',
	'cnw-upgrade-marketing' => 'Ang Wikia Plus ay isang mahusay na tugon para sa:<ul>
<li>Mga Wiking Pangdalubhasa</li>
<li>Hindi nakikinabang</li>
<li>Mga mag-anak</li>
<li>Mga paaralan</li>
<li>Mga proyektong pansarili</li>
</ul>
Magpataas ng antas sa pamamagitan ng PayPal upang makakuha ng wiking walang anunsiyo sa halagang $4.95 lang bawat buwan!',
	'cnw-upgrade-now' => 'Itaas na ang Antas Ngayon',
	'cnw-upgrade-decline' => 'Salamat ngunit ayaw ko, magpatuloy sa aking wiki',
	'cnw-welcome-headline' => 'Maligayang bati! Nalikha na ang $1',
	'cnw-welcome-instruction1' => 'Pindutin ang pindutang nasa ibaba upang makapagsimulang magdagdag ng mga pahina sa wiki mo.',
	'cnw-welcome-instruction2' => 'Makikita mo ang pinduting ito sa kabuuan ng wiki mo, gamitin ito anumang oras na nais mong magdagdag ng isang bagong pahina.',
	'cnw-welcome-help' => 'Maghanap ng mga sagot, mga payo, at marami pa sa <a href="http://community.wikia.com">Lunduyan ng Pamayanan</a>.',
);

/** Tatar (Cyrillic) (Татарча/Tatarça (Cyrillic))
 * @author Ajdar
 */
$messages['tt-cyrl'] = array(
	'cnw-title' => 'Яңа вики ясау',
	'cnw-name-wiki-headline' => 'Вики башлау',
	'cnw-name-wiki-label' => 'Викиның исеме',
	'cnw-name-wiki-wiki' => 'Вики',
	'cnw-login' => 'Керү',
	'cnw-desc-lang' => 'Тел',
);

/** Vietnamese (Tiếng Việt)
 * @author Xiao Qiao
 * @author XiaoQiaoGrace
 */
$messages['vi'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Cửa sổ tạo Wiki]]',
	'cnw-next' => 'Tiếp theo',
	'cnw-back' => 'Trở lại',
	'cnw-or' => 'hoặc',
	'cnw-title' => 'Tạo Wiki mới',
	'cnw-name-wiki-headline' => 'Bắt đầu một Wiki',
	'cnw-name-wiki-creative' => 'Wikia là nơi tốt nhất để xây dựng một trang web và phát triển một cộng đồng xung quanh những gì bạn yêu thích.',
	'cnw-name-wiki-label' => 'Tên wiki của bạn',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Cho wiki của bạn một địa chỉ',
	'cnw-name-wiki-submit-error' => 'Rất tiếc! Bạn cần phải điền vào cả hai ô trống ở trên để tiếp tục.',
	'cnw-login' => 'Đăng nhập',
	'cnw-signup' => 'Mở tài khoản',
	'cnw-signup-prompt' => 'Cần một tài khoản?',
	'cnw-call-to-signup' => 'Đăng ký ở đây',
	'cnw-login-prompt' => 'Bạn đã có tài khoản?',
	'cnw-call-to-login' => 'Đăng nhập tại đây',
	'cnw-auth-headline' => 'Đăng nhập',
	'cnw-auth-headline2' => 'Đăng ký',
	'cnw-auth-creative' => 'Đăng nhập vào tài khoản của bạn để tiếp tục xây dựng wiki của mình.',
	'cnw-auth-signup-creative' => 'Bạn sẽ cần một tài khoản để tiếp tục xây dựng wiki của mình.<br />Chỉ mất một phút để đăng ký!',
	'cnw-auth-facebook-signup' => 'Đăng ký cùng Facebook',
	'cnw-auth-facebook-login' => 'Đăng nhập cùng Facebook',
	'cnw-desc-headline' => 'Wiki của bạn đề cập về?',
	'cnw-desc-creative' => 'Mô tả chủ đề của bạn',
	'cnw-desc-placeholder' => 'Điều này sẽ xuất hiện trên trang chính wiki của bạn.',
	'cnw-desc-tip1' => 'Gợi ý',
	'cnw-desc-tip1-creative' => 'Sử dụng khoảng trống này để nói với mọi người về wiki của bạn trong một hoặc hai câu',
	'cnw-desc-tip2-creative' => 'Cung cấp cho người truy cập một số chi tiết cụ thể về chủ đề của bạn',
	'cnw-desc-tip3' => 'Mẹo hữu dụng',
	'cnw-desc-tip3-creative' => 'Hãy để mọi người biết họ có thể giúp wiki của bạn phát triển bằng cách sửa đổi và tạo các trang',
	'cnw-desc-choose' => 'Chọn một thể loại',
	'cnw-desc-select-one' => 'Chọn một',
	'cnw-desc-default-lang' => 'Wiki của bạn sẽ đặt tại $1',
	'cnw-desc-change-lang' => 'thay đổi',
	'cnw-desc-lang' => 'Ngôn ngữ',
	'cnw-desc-wiki-submit-error' => 'Hãy chọn một thể loại',
	'cnw-theme-headline' => 'Chọn một chủ đề',
	'cnw-theme-creative' => 'Chọn một chủ đề dưới đây, bạn sẽ có thể xem thử từng chủ đề như bạn đã chọn nó.',
	'cnw-theme-instruction' => 'Bạn cũng có thể thiết kế chủ đề sau này bằng cách vào "Công cụ".',
	'cnw-upgrade-headline' => 'Bạn có muốn nâng cấp?',
	'cnw-upgrade-creative' => 'Nâng cấp lên Wikia Plus cho phép bạn để loại bỏ quảng cáo từ <span class="wiki-name"></span> , một thời gian cung cấp chỉ dành cho những người sáng lập mới.',
	'cnw-upgrade-marketing' => 'Wikia Plus là một giải pháp tuyệt vời cho:<ul>
<li>Wiki chuyên nghiệp</li>
<li>Phi lợi nhuận</li>
<li>Gia đình</li>
<li>Trường học</li>
<li>Dự án cá nhân</li>
</ul>
Nâng cấp thông qua PayPal để có được một wiki quảng cáo miễn phí với chỉ $4,95 cho một tháng!',
	'cnw-upgrade-now' => 'Nâng cấp ngay',
	'cnw-upgrade-decline' => 'Không, cảm ơn, tiếp tục wiki của tôi',
	'cnw-welcome-headline' => 'Chúc mừng! $1 đã được tạo ra',
	'cnw-welcome-instruction1' => 'Nhấn vào nút dưới đây để bắt đầu thêm các trang cho wiki của bạn.',
	'cnw-welcome-instruction2' => 'Bạn sẽ thấy nút này trong suốt wiki của bạn, sử dụng nó bất cứ lúc nào bạn muốn thêm một trang mới.',
	'cnw-welcome-help' => 'Tìm câu trả lời, lời khuyên và nhiều hơn trên <a href="http://community.wikia.com">Cộng đồng trung tâm</a>.',
	'cnw-error-general' => 'Một cái gì đó đã xảy ra trong khi tạo ra wiki của bạn. Xin vui lòng thử lại sau.',
	'cnw-error-general-heading' => 'Lỗi tạo Wiki mới',
	'cnw-badword-msg' => 'Chào bạn, xin vui lòng tránh sử dụng những từ ngữ xấu hoặc từ bị cấm trong các mô tả Wiki: $1',
	'cnw-error-wiki-limit-header' => 'Wiki đã đạt đến giới hạn',
	'cnw-error-wiki-limit' => 'Chào, bạn bị giới hạn khi tạo ra $1 wiki mỗi ngày.  Hãy chờ 24 giờ trước khi tạo một wiki khác.',
);

/** Chinese (中文) */
$messages['zh'] = array(
	'createnewwiki-desc' => '[[Special:CreateNewWiki|Wiki creation wizard]]',
	'cnw-next' => 'Next',
	'cnw-back' => 'Back',
	'cnw-or' => 'or',
	'cnw-title' => 'Create New Wiki',
	'cnw-name-wiki-headline' => 'Start a Wiki',
	'cnw-name-wiki-creative' => 'Wikia is the best place to build a website and grow a community around what you love.',
	'cnw-name-wiki-label' => 'Name your wiki',
	'cnw-name-wiki-wiki' => 'Wiki',
	'cnw-name-wiki-domain-label' => 'Give your wiki an address',
	'cnw-name-wiki-submit-error' => 'Oops! You need to fill in both of the boxes above to keep going.',
	'cnw-login' => 'Log In',
	'cnw-signup' => 'Create Account',
	'cnw-signup-prompt' => 'Need an account?',
	'cnw-call-to-signup' => 'Sign up here',
	'cnw-login-prompt' => 'Already have an account?',
	'cnw-call-to-login' => 'Log in here',
	'cnw-auth-headline' => 'Log In',
	'cnw-auth-headline2' => 'Sign Up',
	'cnw-auth-creative' => 'Log in to your account to continue building your wiki.',
	'cnw-auth-signup-creative' => "You'll need an account to continue building your wiki.<br />It only takes a minute to sign up!",
	'cnw-auth-facebook-signup' => 'Sign up with Facebook',
	'cnw-auth-facebook-login' => 'Login with Facebook',
	'cnw-desc-headline' => "What's your wiki about?",
	'cnw-desc-creative' => 'Describe your topic',
	'cnw-desc-placeholder' => 'This will appear on the main page of your wiki.',
	'cnw-desc-tip1' => 'Hint',
	'cnw-desc-tip1-creative' => 'Use this space to tell people about your wiki in a sentence or two',
	'cnw-desc-tip2' => 'Psst',
	'cnw-desc-tip2-creative' => 'Give your visitors some specific details about your subject',
	'cnw-desc-tip3' => 'Pro Tip',
	'cnw-desc-tip3-creative' => 'Let people know they can help your wiki grow by editing and adding pages',
	'cnw-desc-choose' => 'Choose a category',
	'cnw-desc-select-one' => 'Select one',
	'cnw-desc-default-lang' => 'Your wiki will be in $1',
	'cnw-desc-change-lang' => 'change',
	'cnw-desc-lang' => 'Language',
	'cnw-desc-wiki-submit-error' => 'Please choose a category',
	'cnw-theme-headline' => 'Choose a theme',
	'cnw-theme-creative' => "Choose a theme below, you'll be able to see a preview of each theme as you select it.",
	'cnw-theme-instruction' => 'You can also design your own theme later by going to "My Tools".',
	'cnw-upgrade-headline' => 'Do you want to upgrade?',
	'cnw-upgrade-creative' => 'Upgrading to Wikia Plus allows you to remove ads from <span class="wiki-name"></span>, a one time offer only available to new founders.',
	'cnw-upgrade-marketing' => 'Wikia Plus is a great solution for:<ul>
<li>Professional Wikis</li>
<li>Non-profits</li>
<li>Families</li>
<li>Schools</li>
<li>Personal projects</li>
</ul>
Upgrade through PayPal to get an ad-free wiki for only $4.95 per month!',
	'cnw-upgrade-now' => 'Upgrade Now',
	'cnw-upgrade-decline' => 'No thanks, continue to my wiki',
	'cnw-welcome-headline' => 'Congratulations! $1 has been created',
	'cnw-welcome-instruction1' => 'Click the button below to start adding pages to your wiki.',
	'cnw-welcome-instruction2' => "You'll see this button throughout your wiki, use it any time you want to add a new page.",
	'cnw-welcome-help' => 'Find answers, advice, and more on <a href="http://community.wikia.com">Community Central</a>.',
);

/** Simplified Chinese (‪中文(简体)‬)
 * @author Hydra
 */
$messages['zh-hans'] = array(
	'cnw-next' => '下一步',
	'cnw-back' => '前一步',
	'cnw-or' => '或',
	'cnw-title' => '创造一个新的维基',
	'cnw-name-wiki-headline' => '开始一个维基',
	'cnw-name-wiki-label' => '给您的维基一个名字',
	'cnw-name-wiki-wiki' => '维基',
	'cnw-login' => '登录',
	'cnw-signup' => '创造户口',
	'cnw-signup-prompt' => '需要一个户口吗？',
	'cnw-call-to-signup' => '在这里注册',
	'cnw-login-prompt' => '已经有了户口吗？',
	'cnw-call-to-login' => '在这里登入',
	'cnw-auth-headline' => '登入',
	'cnw-auth-headline2' => '注册',
	'cnw-desc-tip1' => '暗示',
	'cnw-desc-tip2' => '喂',
	'cnw-desc-choose' => '选择一个分类',
	'cnw-desc-select-one' => '选一',
	'cnw-desc-change-lang' => '改变',
	'cnw-desc-lang' => '语言',
	'cnw-desc-wiki-submit-error' => '请选一个分类',
	'cnw-upgrade-decline' => '不了谢谢，请继续到我的维基',
	'cnw-welcome-headline' => '恭喜！$1 已被创造',
);

/** Traditional Chinese (‪中文(繁體)‬)
 * @author Wrightbus
 */
$messages['zh-hant'] = array(
	'cnw-next' => '下一步',
	'cnw-back' => '前一步',
	'cnw-or' => '或',
	'cnw-auth-headline' => '登入',
	'cnw-auth-facebook-login' => '以Facebook登入',
	'cnw-desc-tip1' => '提示',
	'cnw-upgrade-now' => '立即升級',
	'cnw-error-general-heading' => '創建新Wiki時發生錯誤',
);

