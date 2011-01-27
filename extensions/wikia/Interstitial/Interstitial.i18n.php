<?php
/**
 * Author: Sean Colombo
 * Date: 20100127
 *
 * Internationalization file for Interstitials extension.
 */

$messages = array();

$messages['en'] = array(
	// If we were displaying interstitials but there is no campaign code, this would be an egregious error.
	// An extremely friendly message is probably much better than a blank interstitial.  At least we get to tell them
	// how we feel for X seconds.
	"interstitial-default-campaign-code" => "Wikia Loves You!",
	"interstitial-skip-ad" => "Skip this ad",

	"interstitial-already-logged-in-no-link" => "You are already logged in and there is no destination set.",
	"interstitial-disabled-no-link" => "There is no destination set and interstitials are not enabled on this wiki.",
	"interstitial-link-away" => "There is nothing to see here!<br /><br />Would you like to go to the [[{{MediaWiki:Mainpage}}|Main Page]] or perhaps a [[Special:Random|random page]]?",

	// oasis Exitstitial
	"exitstitial-title" => "Leaving ", // @todo FIXME: no trailing whitespace support in Translate extension. Needs parameter or hard coded space.
	"exitstitial-register" => "<a href=\"#\" class=\"register\">Register</a> or <a href=\"#\" class=\"login\">Login</a> to skip ads.",
	"exitstitial-button" => "Skip This Ad"
);

/** Message documentation (Message documentation)
 * @author Siebrand
 */
$messages['qqq'] = array(
	'interstitial-disabled-no-link' => "''On the World Wide Web, interstitials are web page advertisements that are displayed before or after an expected content page, often to display advertisements or confirm the user's age.''",
);

/** Breton (Brezhoneg)
 * @author Fohanno
 */
$messages['br'] = array(
	'interstitial-default-campaign-code' => "Wikia a gar ac'hanoc'h !",
);

/** Spanish (Español)
 * @author Bola
 */
$messages['es'] = array(
	'interstitial-default-campaign-code' => '¡Wikia te quiere!',
	'interstitial-skip-ad' => 'Omitir este anuncio',
	'interstitial-already-logged-in-no-link' => 'Ya estás identificado y no hay ninguna configuración destinada',
	'interstitial-disabled-no-link' => 'No hay ninguna configuración destinada y no está activado en este wiki interstitials.',
	'interstitial-link-away' => '¡Aquí no hay nada que ver!<br /><br />¿Prefieres ir a la [[{{MediaWiki:Mainpage}}|Portada]] o quizás prefieres una [[Special:Random|página aleatoria]]?',
	'exitstitial-register' => '<a href="#" class="register">Regístrate</a> o <a href="#" class="login">identifícate</a> para omitir la publicidad.',
	'exitstitial-button' => 'Omitir este anuncio',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'interstitial-default-campaign-code' => 'Wikia te ama!',
	'interstitial-skip-ad' => 'Saltar iste annuncio',
	'interstitial-already-logged-in-no-link' => 'Tu ha jam aperite session e il non ha un destination definite.',
	'interstitial-disabled-no-link' => 'Nulle destination ha essite definite e le annuncios interstitial non es activate in iste wiki.',
	'interstitial-link-away' => 'Il ha nihil a vider hic!<br /><br />Vole tu ir al [[{{MediaWiki:Mainpage}}|pagina principal]] o forsan a un [[Special:Random|pagina aleatori]]?',
	'exitstitial-register' => '<a href="#" class="register">Crea un conto</a> o <a href="#" class="login">aperi session</a> pro saltar le publicitate.',
	'exitstitial-button' => 'Saltar iste annuncio',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'interstitial-default-campaign-code' => 'Викија ве сака!',
	'interstitial-skip-ad' => 'Прескокни ја рекламава',
	'interstitial-already-logged-in-no-link' => 'Веќе сте најавени, а нема зададено одредница.',
	'interstitial-disabled-no-link' => 'Нема зададено одредница, а на викито не се овозможени меѓупросторни реклами.',
	'interstitial-link-away' => 'Тука нема што да се види!<br /><br />Дали би сакале да појдете на [[{{MediaWiki:Mainpage}}|Главната страница]] или пак да отворите [[Special:Random|случајна]]?',
	'exitstitial-register' => '<a href="#" class="register">Регистрирајте се</a> или <a href="#" class="login">Најавете се</a> за да ги прескокнете рекламите.',
	'exitstitial-button' => 'Прескокни ја рекламава',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'interstitial-default-campaign-code' => 'Wikia houdt van u!',
	'interstitial-skip-ad' => 'Deze advertentie overslaan',
	'interstitial-already-logged-in-no-link' => 'U bent al aangemeld en er is nog geen bestemming ingesteld.',
	'interstitial-disabled-no-link' => 'Er is nog geen bestemming ingesteld en voorloopadvertenties zijn niet ingeschakeld op deze wiki.',
	'interstitial-link-away' => 'Er is hier niets te zien!<br /><br />Wilt u naar de [[{{MediaWiki:Mainpage}}|Hoofdpagina]] of misschien naar een [[Special:Random|willekeurige pagina]]?',
	'exitstitial-register' => '<a href="#" class="register">Registreer</a> of <a href="#" class="login">Meld u aan</a> om advertenties te kunnen verbergen.',
	'exitstitial-button' => 'Deze advertentie overslaan',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Audun
 */
$messages['no'] = array(
	'interstitial-default-campaign-code' => 'Wikia elsker deg!',
	'interstitial-skip-ad' => 'Hopp over annonse',
	'interstitial-already-logged-in-no-link' => 'Du er allerede logget inn, og det er ikke angitt et mål.',
	'interstitial-disabled-no-link' => 'Det er ikke valgt noe mål, og entrésider er ikke aktivert for denne wikien.',
	'interstitial-link-away' => 'Det er ingenting å se her!<br /><br />Vil du gå til [[{{MediaWiki:Mainpage}}|hovedsiden]] eller kanskje til en [[Special:Random|tilfeldig side]]?',
	'exitstitial-register' => '<a href="#" class="register">Registrer deg</a> eller <a href="#" class="login">Logg inn</a> for å hoppe over annonser.',
	'exitstitial-button' => 'Hopp over annonse',
);

