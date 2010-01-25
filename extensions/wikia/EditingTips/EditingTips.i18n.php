<?php
$messages = array();

$messages['en'] = array(
	'tog-disableeditingtips' => 'Do not show editing tips',
	'tog-widescreeneditingtips' => 'Use widescreen editing',
	'editingtips_enter_widescreen' => 'Enter Widescreen',
	'editingtips_exit_widescreen' => 'Exit Widescreen',
	'editingtips_hide' => 'Hide Editing Tips',
	'editingtips_show' => 'Show Editing Tips',
	'editingTips' => 'Add your tips [[MediaWiki:EditingTips|here]]',
);

/** German (Deutsch) */
$messages['de'] = array(
	'tog-disableeditingtips' => 'Bearbeitungs-Tipps ausblenden',
	'tog-widescreeneditingtips' => 'Benutze den Editor auf voller Breite',
	'editingtips_enter_widescreen' => 'Volle Breite',
	'editingtips_exit_widescreen' => 'Normale Breite',
	'editingtips_hide' => 'Bearbeitungs-Tipps ausblenden',
	'editingtips_show' => 'Bearbeitungs-Tipps einblenden',
);

/** Spanish (Español)
 * @author Translationista
 */
$messages['es'] = array(
	'tog-disableeditingtips' => 'No mostrar consejos para editar',
	'tog-widescreeneditingtips' => 'Usar edición en Pantalla Extendida',
	'editingtips_enter_widescreen' => 'Editar en pantalla extendida',
	'editingtips_exit_widescreen' => 'Volver a modo de edición normal',
	'editingtips_hide' => 'Ocultar consejos para editar',
	'editingtips_show' => 'Mostrar consejos para editar',
	'editingTips' => 'Añade tus consejos [[MediaWiki:EditingTips|here]]',
);

/** Finnish (Suomi) */
$messages['fi'] = array(
	'tog-disableeditingtips' => 'Älä näytä muokkausvinkkejä',
	'tog-widescreeneditingtips' => 'Käytä laajakuvaeditoria',
	'editingtips_enter_widescreen' => 'Siirry laajakuvaeditoriin',
	'editingtips_exit_widescreen' => 'Poistu laajakuvaeditorista',
	'editingtips_hide' => 'Piilota muokkausvinkit',
	'editingtips_show' => 'Näytä muokkausvinkit',
	'editingTips' => 'Lisää vinkkisi [[MediaWiki:EditingTips|tänne]]',
);

/** French (Français)
 * @author Peter17
 */
$messages['fr'] = array(
	'tog-disableeditingtips' => "Ne pas montrer les astuces d'éditions",
	'tog-widescreeneditingtips' => "Utiliser une large fenêtre d'édition",
	'editingtips_enter_widescreen' => "Afficher la grande fenêtre d'édition",
	'editingtips_exit_widescreen' => "Masquer la grande fenêtre d'édition",
	'editingtips_hide' => "Masquer l'aide",
	'editingtips_show' => "Afficher l'aide",
	'editingTips' => 'Ajoutez vos astuces [[MediaWiki:EditingTips|ici]]',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'tog-disableeditingtips' => 'Non mostrar os consellos de edición',
	'tog-widescreeneditingtips' => 'Utilizar a edición na ventá estendida',
	'editingtips_enter_widescreen' => 'Mostrar a ventá de edición estendida',
	'editingtips_exit_widescreen' => 'Saír da ventá de edición estendida',
	'editingtips_hide' => 'Agochar os consellos de edición',
	'editingtips_show' => 'Mostrar os consellos de edición',
	'editingTips' => 'Engada os seus consellos [[MediaWiki:EditingTips|aquí]]',
);

/** Japanese (日本語)
 * @author Tommy6
 */
$messages['ja'] = array(
	'tog-disableeditingtips' => '編集時のヘルプを表示しない',
	'tog-widescreeneditingtips' => '大きな編集画面を使う',
	'editingtips_enter_widescreen' => '編集画面を大きくする',
	'editingtips_exit_widescreen' => '編集画面を元に戻す',
	'editingtips_hide' => 'ヘルプを隠す',
	'editingtips_show' => 'ヘルプを表示する',
	'editingTips' => "=テキストの装飾 =
wiki文法をつかうか、HTMLで装飾できます。
<br />
<span style=\"font-family: courier\"><nowiki>''斜体''</nowiki></span> => ''斜体''

<br />
<span style=\"font-family: courier\"><nowiki>'''太字'''</nowiki></span> => '''太字'''

<br />
<span style=\"font-family: courier\"><nowiki>'''''斜体および太字'''''</nowiki></span> => '''''斜体および太字'''''

----

<br />
<nowiki><s>取り消し線</s></nowiki> => <s>取り消し線</s>

<br />
<nowiki><u>アンダーライン</u></nowiki> => <u>アンダーライン</u>

<br />
<nowiki><span style=\"color:red;\">赤文字</span></nowiki> => <span style=\"color:red;\">赤文字</span>

=リンクの作り方 =
ブラケット(\"[\"または\"]\")をひとつかふたつ使うことでリンクを作ることができます。

<br />
'''単純な内部リンク:'''<br />
<nowiki>[[ページ名]]</nowiki>

<br />
'''テキスト付きの内部リンク:'''<br />
<nowiki>[[ページ名|表示したいテキスト]]</nowiki>

<br />
----

<br />
'''外部リンク(表示される際には、<nowiki>[1]</nowiki>と略されます):'''<br />
<nowiki>[http://www.example.com]</nowiki>

<br />
'''テキスト付きの外部リンク:'''
<nowiki>[http://www.example.com 表示したいテキスト]</nowiki>

= 見出し作成 =
見出しは、\"=\"で作ります。\"=\"が多いほど、見出しは小さくなります。
見出しの一番大きなものは、ページ名になります。

<br />
<span style=\"font-size: 1.6em\"><nowiki>==見出し2==</nowiki></span>

<br />
<span style=\"font-size: 1.3em\"><nowiki>===見出し3===</nowiki></span>

<br />
<nowiki>====見出し4====</nowiki>

=テキストのインデント=
単純なインデント、箇条書きによるインデント、数字付きのインデントができます。

<br />
<nowiki>: インデント</nowiki><br />
<nowiki>: インデント</nowiki><br />
<nowiki>:: もう一段インデント</nowiki><br />
<nowiki>::: 更にインデント</nowiki>

<br />
<nowiki>* 箇条書き</nowiki><br />
<nowiki>* 箇条書き</nowiki><br />
<nowiki>** 一段下の箇条書き</nowiki><br />
<nowiki>* 箇条書き</nowiki>

<br />
<nowiki># 数字付きリスト</nowiki><br />
<nowiki># 数字付きリスト</nowiki><br />
<nowiki>## 一段下の数字付きリスト</nowiki><br />
<nowiki># 数字付きリスト</nowiki>

=画像の挿入 =
リンクを貼るのに似た方法で画像を挿入することができます。

<br />
<nowiki>[[画像:画像ファイル.jpg]]</nowiki>

<br />
'''ブラウザ向けのaltテキストを入れる場合'''<br />
<nowiki>[[画像:画像ファイル.jpg|alt text]]</nowiki>

<br />
'''画像の縮小表示を行う場合'''<br />
<nowiki>[[画像:画像ファイル.jpg|thumb|]]</nowiki>

<br />
'''画像を特定のサイズにする場合'''<br />
<nowiki>[[画像:画像ファイル.jpg|200px|]]</nowiki>

<br />
'''画像を右寄せにする場合(デフォルトは左寄せ)'''<br />
<nowiki>[[画像:画像ファイル.jpg|right|]]</nowiki>

<br />
\"|\"をつかって、それぞれの値をつなげることができます。最後の\"|\"以後の値は、全て解説用のテキストになります。

<br />
----
----

<br />
この編集ガイドは[[MediaWiki:EditingTips]]で編集できます。",
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'tog-disableeditingtips' => 'Не прикажувај совети за уредување',
	'tog-widescreeneditingtips' => 'Користи широкоекранско уредување',
	'editingtips_enter_widescreen' => 'Влези на широк екран',
	'editingtips_exit_widescreen' => 'Излези од широк екран',
	'editingtips_hide' => 'Сокриј совети за уредување',
	'editingtips_show' => 'Прикажи совети за уредување',
	'editingTips' => 'Додајте ги вашите совети [[MediaWiki:EditingTips|овде]]',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'tog-disableeditingtips' => 'Geen bewerkingstips weergeven',
	'tog-widescreeneditingtips' => 'Bewerken in een breed scherm',
	'editingtips_enter_widescreen' => 'Breed bewerkingsscherm gebruiken',
	'editingtips_exit_widescreen' => 'Breed bewerkingsscherm sluiten',
	'editingtips_hide' => 'Bewerkingstips verbergen',
	'editingtips_show' => 'Bewerkingstips weergeven',
	'editingTips' => '[[MediaWiki:EditingTips|Tips toevoegen]]',
);

/** Norwegian Nynorsk (‪Norsk (nynorsk)‬)
 * @author Harald Khan
 */
$messages['nn'] = array(
	'tog-disableeditingtips' => 'Ikkje syn endringstips',
	'tog-widescreeneditingtips' => 'Nytt breiskjermendring',
	'editingtips_enter_widescreen' => 'Nytt breiskjerm',
	'editingtips_exit_widescreen' => 'Gå ut av breiskjermmodus',
	'editingtips_hide' => 'Løyn endringstips',
	'editingtips_show' => 'Syn endringstips',
	'editingTips' => 'Legg til tipsa dine [[MediaWiki:EditingTips|her]]',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 */
$messages['pms'] = array(
	'tog-disableeditingtips' => 'Mostré pa ij consèj ëd modìfica',
	'tog-widescreeneditingtips' => "Dovré l'editor a scren largh",
	'editingtips_enter_widescreen' => 'Intré an modalità scren gròss',
	'editingtips_exit_widescreen' => 'Seurte da la modalità Scren gròss',
	'editingtips_hide' => 'Stërmé ij Consèj ëd Modìfica',
	'editingtips_show' => 'Mostré ij Consèj ëd Modìfica',
	'editingTips' => "Ch'a gionta ij sò consèj [[MediaWiki:EditingTips|ambelessì]]",
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Daemorris
 */
$messages['pt-br'] = array(
	'tog-disableeditingtips' => 'Não mostrar dicas de edição',
	'tog-widescreeneditingtips' => 'Usar edição de tela larga',
	'editingtips_enter_widescreen' => 'Iniciar Tela Larga',
	'editingtips_exit_widescreen' => 'Terminar Tela Larga',
	'editingtips_hide' => 'Ocultar Dicas de Edição',
	'editingtips_show' => 'Mostrar Dicas de Edição',
	'editingTips' => 'Adicione suas dicas [[MediaWiki:EditingTips|aqui]]',
);

/** Russian (Русский)
 * @author Lockal
 */
$messages['ru'] = array(
	'tog-disableeditingtips' => 'Не показывать подсказки во время редактирования',
	'tog-widescreeneditingtips' => 'Поле при редактировании во всю ширину экрана',
	'editingtips_enter_widescreen' => 'Перейти в широкоэкранный режим',
	'editingtips_exit_widescreen' => 'Выйти из широкоэкранного режима',
	'editingtips_hide' => 'Скрыть советы по редактированию',
	'editingtips_show' => 'Показать советы по редактированию',
	'editingTips' => 'Добавьте свои советы [[MediaWiki:EditingTips|здесь]]',
);

/** Chinese (中文) */
$messages['zh'] = array(
	'editingtips_enter_widescreen' => '放大編輯',
	'editingtips_exit_widescreen' => '退出放大編輯',
	'editingtips_hide' => '隱藏編輯小技巧',
);

/** Chinese (China) (‪中文(中国大陆)‬) */
$messages['zh-cn'] = array(
	'editingtips_enter_widescreen' => '放大编辑',
	'editingtips_exit_widescreen' => '退出放大编辑',
	'editingtips_hide' => '隐藏编辑小技巧',
);

/** Simplified Chinese (‪中文(简体)‬) */
$messages['zh-hans'] = array(
	'editingtips_enter_widescreen' => '放大编辑',
	'editingtips_exit_widescreen' => '退出放大编辑',
	'editingtips_hide' => '隐藏编辑小技巧',
);

/** Traditional Chinese (‪中文(繁體)‬) */
$messages['zh-hant'] = array(
	'editingtips_enter_widescreen' => '放大編輯',
	'editingtips_exit_widescreen' => '退出放大編輯',
	'editingtips_hide' => '隱藏編輯小技巧',
);

/** Chinese (Hong Kong) (‪中文(香港)‬) */
$messages['zh-hk'] = array(
	'editingtips_enter_widescreen' => '放大編輯',
	'editingtips_exit_widescreen' => '退出放大編輯',
	'editingtips_hide' => '隱藏編輯小技巧',
);

/** Chinese (Singapore) (‪中文(新加坡)‬) */
$messages['zh-sg'] = array(
	'editingtips_enter_widescreen' => '放大编辑',
	'editingtips_exit_widescreen' => '退出放大编辑',
	'editingtips_hide' => '隐藏编辑小技巧',
);

/** Chinese (Taiwan) (‪中文(台灣)‬) */
$messages['zh-tw'] = array(
	'editingtips_enter_widescreen' => '放大編輯',
	'editingtips_exit_widescreen' => '退出放大編輯',
	'editingtips_hide' => '隱藏編輯小技巧',
);

