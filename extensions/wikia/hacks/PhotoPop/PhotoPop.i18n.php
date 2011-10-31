<?php
/**
 * Internationalisation file for Special:PhotoPop extension / game.
 *
 * @addtogroup Extensions
 */

$messages = array();

$messages['en'] = array(
	//Special page, game setup
	'photopop-setup-title' => 'Photo Pop setup',
	'photopop-setup-category-label' => 'Category',
	'photopop-setup-category-tip' => 'Enter a category name...',
	'photopop-setup-icon-label' => 'Game icon',
	'photopop-setup-icon-tip' => 'Enter a file article title...',
	'photopop-setup-watermark-label' => 'Watermark',
	'photopop-setup-watermark-tip' => 'Enter a file article title...',
	'photopop-setup-submit-label' => 'Update',
	'photopop-current-settings-title' => 'Current settings',
	'photpop-category-none' => 'N/A',
	'photopop-error-category-non-existing' => "The specified category doesn't exist",
	'photopop-error-field-compulsory' => 'This field is compulsory',
	'photopop-error-file-non-existing' => "The specified file article doesn't exist",
	'photopop-error-db-error' => 'An error occurred, settings were not saved',
	'photopop-settings-saved' => 'Settings have been saved',
	'photopop-image-preview' => 'Preview of images: ',

	//game
	'photopop-game-round' => 'Round',
	'photopop-game-correct' => 'Correct',
	'photopop-game-points' => 'points',
	'photopop-game-total' => 'total',
	'photopop-game-score' => 'score',
	'photopop-game-wiki' => 'Wiki',
	'photopop-game-date' => 'Date',
	'photopop-game-timeup' => 'time\'s up!',
	'photopop-game-please-wait' => 'Please wait',
	'photopop-game-loading-image' => 'Loading image...',
	'photopop-game-loading' => 'Loading...',
	'photopop-game-highscore' => 'Highscore',
	'photopop-game-highscores' => 'High Scores',
	'photopop-game-continue' => 'It\'s:',
	'photopop-game-yougot' => 'You got',
	'photopop-game-outof' => 'out of',
	'photopop-game-progress' => 'progress',
	'photopop-game-image-load-error' => 'Image could not be loaded. We\'re sorry',
	'photopop-game-tutorial-intro' => 'Tap the screen to take a peek of the mystery image underneath.',
	'photopop-game-tutorial-continue' => 'After the answer is revealed tap the "next" button to continue on to a new image.',
	'photopop-game-tutorial-drawer' => 'The fewer peek you take, the fewer guesses you make, and the less time you take, the bigger your score!',
	'photopop-game-tutorial-tile' => 'Tap the "answer" button to make your guess.',

	//old
	'photopop' => 'Photo Pop game',
	'photopop-title-tag-homescreen' => 'Photo Pop',
	'photopop-title-tag-selectorscreen' => 'Photo Pop',
	'photopop-title-tag-playgame' => 'Photo Pop - $1 $2',
	'photopop-desc' => 'Creates a page where the Foggy Foto game can be played in HTML5 + Canvas. It will be accessible via Nirvana\'s APIs',
	'photopop-score' => 'score: <span>$1</span>',
	'photopop-progress' => 'photos: <span>$1</span>',
	'photopop-progress-numbers' => '$1/$2',
	'photopop-continue-correct' => 'correct!',
	'photopop-continue-timeup' => 'time\'s up!',
	'photopop-finished-heading' => 'finished!',
	'photopop-endgame-highscore-summary' => 'highscore: $1',
	'photopop-endgame-completion-summary' => 'you got $1 out of $2 correct.',
	'photopop-endgame-score-summary' => 'score: $1',
	'photopop-tutorial-text' => 'Tap the screen to uncover a hidden image. Then tap the arrow to guess what it is.',
);

/** Message documentation (Message documentation) */
$messages['qqq'] = array(
	'photopop' => 'Special page name for "Foggy Foto" game.',
	'photopop-desc' => '{{desc}}',
	'photopop-progress' => 'Parameters:
* $1 is replaced with {{msg-wikia|photopop-progress-numbers}}.',
	'photopop-progress-numbers' => 'This is the format of the numbers that will be substituted into the "$1" portion of {{msg-wikia|photopop-progress}}. Parameters:
* $1 is what number photo the player is on (starting with 1)
* $2 is the total number of photos in a round of the game.',
);

/** German (Deutsch)
 * @author LWChris
 */
$messages['de'] = array(
	'photopop' => 'Photo Pop Spiel',
	'photopop-title-tag-homescreen' => 'Photo Pop',
	'photopop-title-tag-selectorscreen' => 'Photo Pop',
	'photopop-title-tag-playgame' => 'Photo Pop - $1 $2',
	'photopop-desc' => 'Erstellt eine Seite, auf der das Foto-Aufdeck-Spiel mit einer HTML5 Canvas gespielt werden kann. Es wird über Nirvana APIs erreichbar sein',
	'photopop-score' => 'Punkte: <span>$1</span>',
	'photopop-progress' => 'Fotos: <span>$1</span>',
	'photopop-continue-correct' => 'richtig!',
	'photopop-continue-timeup' => 'Zeit abgelaufen!',
	'photopop-finished-heading' => 'fertig!',
	'photopop-endgame-highscore-summary' => 'Highscore: $1',
	'photopop-endgame-completion-summary' => 'Du hattest $1 von $2 richtig!',
	'photopop-endgame-score-summary' => 'Ergebnis: $1',
	'photopop-tutorial-text' => 'Tippe auf den Bildschirm um ein verdecktes Bild aufzudecken. Tippe dann auf den Pfeil um zu raten, was es ist.',
);

/** French (Français)
 * @author Gomoko
 * @author Wyz
 */
$messages['fr'] = array(
	'photopop' => 'Jeu Foggy Foto',
	'photopop-title-tag-homescreen' => 'Photo Pop',
	'photopop-title-tag-selectorscreen' => 'Photo Pop',
	'photopop-title-tag-playgame' => 'Photo Pop - $1 $2',
	'photopop-desc' => 'Crée une page où le jeu Foggy Foto peut être joué dans un canevas HTML5. Elle sera accessible via les APIs de Nirvana.',
	'photopop-score' => 'Score : <span>$1</span>',
	'photopop-progress' => 'Photos : <span>$1</span>',
	'photopop-continue-correct' => 'CORRECT !',
	'photopop-continue-timeup' => 'LE TEMPS EST ÉCOULÉ !',
	'photopop-finished-heading' => 'terminé!',
	'photopop-endgame-highscore-summary' => 'score maximal: $1',
	'photopop-endgame-completion-summary' => 'vous avez $1 sur $2 de bon.',
	'photopop-endgame-score-summary' => 'score: $1',
	'photopop-tutorial-text' => "Touchez l'écran pour dévoiler une image cachée. Puis touchez la flèche pour deviner ce que c'est.",
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'photopop' => 'Матна слика',
	'photopop-title-tag-homescreen' => 'Фотоскок',
	'photopop-title-tag-selectorscreen' => 'Фотоскок',
	'photopop-title-tag-playgame' => 'Фотоскок - $1 $2',
	'photopop-desc' => 'Создава страница кајшто се игра играта „Матна слика“ (Foggy Foto) во HTML5 + Canvas. Ќе биде достапна преку прилозите (API) на Nirvana',
	'photopop-score' => 'резултат: <span>$1</span>',
	'photopop-progress' => 'Слики: <span>$1</span>',
	'photopop-progress-numbers' => '$1/$2',
	'photopop-continue-correct' => 'ТОЧНО!',
	'photopop-continue-timeup' => 'ВРЕМЕТО ИСТЕЧЕ!',
	'photopop-finished-heading' => 'готово!',
	'photopop-endgame-highscore-summary' => 'најдобар резултат: $1',
	'photopop-endgame-completion-summary' => 'погодивте $1 од $2.',
	'photopop-endgame-score-summary' => 'резултат: $1',
	'photopop-tutorial-text' => 'Потчукнете на екранот за да се појави скриена слика. Пооа потчукнете на стрелката за да погодите што се крие во неа.',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'photopop' => 'Permainan Foggy Foto',
	'photopop-title-tag-homescreen' => 'Photo Pop',
	'photopop-title-tag-selectorscreen' => 'Photo Pop',
	'photopop-title-tag-playgame' => 'Photo Pop - $1 $2',
	'photopop-desc' => 'Mewujudkan laman permainan Foggy Foto di HTML5 + Canvas. Boleh dicapai melalui API Nirvana',
	'photopop-score' => 'Markah: <span>$1</span>',
	'photopop-progress' => 'Gambar: <span>$1</span>',
	'photopop-continue-correct' => 'YA, BETUL!',
	'photopop-continue-timeup' => 'MASA DAH TAMAT!',
	'photopop-finished-heading' => 'siap!',
	'photopop-endgame-highscore-summary' => 'markah tertinggi: $1',
	'photopop-endgame-completion-summary' => 'anda menjawab betul $1 daripada $2.',
	'photopop-endgame-score-summary' => 'markah: $1',
	'photopop-tutorial-text' => 'Ketik skrin untuk mendedahkan gambar yang tersorok. Kemudian, ketik anak panah untuk menekanya.',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'photopop' => 'Foggy Fotospel',
	'photopop-title-tag-homescreen' => 'Fotopop',
	'photopop-title-tag-selectorscreen' => 'Fotopop',
	'photopop-title-tag-playgame' => 'Fotopop - $1 $2',
	'photopop-desc' => "Maakt een pagina aan het Foggy Fotospel gespeeld kan worden in HTML5 met Canvas. Dit is beschikbaar via Nirvana's API's",
	'photopop-score' => 'Score: <span>$1</span>',
	'photopop-progress' => "Foto's: <span>$1</span>",
	'photopop-progress-numbers' => '$1/$2',
	'photopop-continue-correct' => 'Correct!',
	'photopop-continue-timeup' => 'De tijd is op!',
	'photopop-finished-heading' => 'Klaar!',
	'photopop-endgame-highscore-summary' => 'highscore: $1',
	'photopop-endgame-completion-summary' => 'U hebt $1 van de $2 vragen juist.',
	'photopop-endgame-score-summary' => 'score: $1',
	'photopop-tutorial-text' => 'Tik op het scherm om een verborgen afbeelding te onthullen. Klik daarna op het pijltje om te raden wat het is.',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Audun
 */
$messages['no'] = array(
	'photopop' => 'Tåkebilde-spill',
	'photopop-desc' => 'Lager en side hvor Tåkebilde-spillet kan spilles i HTML5 + Canvas. Det vil være tilgjengelig via Nirvanas APIer',
	'photopop-score' => 'Poengsum: <span>$1</span>',
	'photopop-progress' => 'Bilder: <span>$1</span>',
	'photopop-continue-correct' => 'RIKTIG!',
	'photopop-continue-timeup' => 'TIDEN ER UTE!',
);

/** Swedish (Svenska)
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'photopop-finished-heading' => 'klart!',
	'photopop-endgame-score-summary' => 'poäng: $1',
	'photopop-tutorial-text' => 'Tryck på skärmen för att avslöja en dold bild. Tryck sedan på pilen för att gissa vad den är.',
);
