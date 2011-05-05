<?php

$messages = array();

$messages['en'] = array(
	'follow-desc' => 'Improvements for the watchlist functionality',
	'prefs-basic' => 'Basic options',
	'wikiafollowedpages-special-heading-category' => "Categories ($1)",
	'wikiafollowedpages-special-heading-article' => "Articles ($1)",
	'wikiafollowedpages-special-heading-blogs' => "Blogs and posts ($1)",
	'wikiafollowedpages-special-heading-forum' => 'Forum threads ($1)',
	'wikiafollowedpages-special-heading-project' => 'Project pages ($1)',
	'wikiafollowedpages-special-heading-user' => 'User pages ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Templates pages ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki pages ($1)',
	'wikiafollowedpages-special-heading-media' => 'Images and videos ($1)',
	'wikiafollowedpages-special-namespace' => "($1 page)",
	'wikiafollowedpages-special-empty' => "Your followed pages list is empty.
Add pages to this list by clicking \"{{int:watch}}\" at the top of a page.",
	'wikiafollowedpages-special-anon' => 'Please [[Special:Signup|log in]] to create or view your followed pages list.',


    'oasis-wikiafollowedpages-special-seeall' => 'See all >',
    'wikiafollowedpages-special-seeall' => 'See all >',
    'wikiafollowedpages-special-showall' => 'Show all >',
    'wikiafollowedpages-special-showmore' => 'Show more',
	'wikiafollowedpages-special-title' => 'Followed pages',
	'wikiafollowedpages-special-delete-tooltip' => 'Remove this page',

	'wikiafollowedpages-special-hidden' => 'This user has chosen to hide {{GENDER:$1|his|her|their}} followed pages list from public view.',
	'wikiafollowedpages-special-hidden-unhide' => 'Unhide this list.',
	'wikiafollowedpages-special-blog-by' => 'by $1',
	'wikiafollowedpages-masthead' => 'Followed pages',
	'wikiafollowedpages-following' => 'Following',
	'wikiafollowedpages-special-title-userbar' => 'Followed pages',

	'tog-enotiffollowedpages' => 'E-mail me when a page I am following is changed',
	'tog-enotiffollowedminoredits' => 'E-mail me for minor edits to pages I am following',

	'prefs-wikiafollowedpages-prefs-advanced' => 'Advanced options',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Watchlist only',

	'tog-hidefollowedpages' => 'Make my followed pages lists private',
	'follow-categoryadd-summary' => "Page added to category", //TODO check this
	'follow-bloglisting-summary' => "Blog posted on blog page",

	'wikiafollowedpages-userpage-heading' => "Pages I am following",
        'wikiafollowedpages-userpage-hide-tooltip' => "Hide your followed pages lists from public view",
	'wikiafollowedpages-userpage-more' => 'More',
	'wikiafollowedpages-userpage-hide' => 'hide',
	'wikiafollowedpages-userpage-empty' => "This user's followed pages list is empty.
Add pages to this list by clicking \"{{int:watch}}\" at the top of a page.",

	'enotif_subject_categoryadd' => '{{SITENAME}} page $PAGETITLE has been added to $CATEGORYNAME by $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Dear $WATCHINGUSERNAME,

A page has been added to a category you are following on {{SITENAME}}.

See "$PAGETITLE_URL" for the new page.

Please visit and edit often...

{{SITENAME}}

___________________________________________
* Check out our featured wikis! http://www.wikia.com

* Want to control which e-mails you receive?
Go to: {{fullurl:{{ns:special}}:Preferences}}.',

	'enotif_body_categoryadd-html' => '<p>
Dear $WATCHINGUSERNAME,
<br /><br />
A page has been added to a category you are following on {{SITENAME}}.
<br /><br />
See <a href="$PAGETITLE_URL">$PAGETITLE</a> for the new page.
<br /><br />
Please visit and edit often...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Check out our featured wikis!</a></li>
<li>Want to control which e-mails you receive? Go to <a href="{{fullurl:{{ns:special}}:Preferences}}">User preferences</a></li>
</ul>
</p>',

	'enotif_subject_blogpost' => '{{SITENAME}} page $PAGETITLE has been posted to $BLOGLISTINGNAME by $PAGEEDITOR',
	'enotif_body_blogpost' => 'Dear $WATCHINGUSERNAME,

There has been an edit to a blog listing page you are following on {{SITENAME}}.

See "$PAGETITLE_URL" for the new post.

Please visit and edit often...

{{SITENAME}}

___________________________________________
* Check out our featured wikis! http://www.wikia.com

* Want to control which e-mails you receive?
Go to: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Dear $WATCHINGUSERNAME,
<br /><br />
There has been an edit to a blog listing page you are following on {{SITENAME}}.
<br /><br />
See <a href="$PAGETITLE_URL">$PAGETITLE</a> for the new post.
<br /><br />
Please visit and edit often...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Check out our featured wikis!</a></li>
<li>Want to control which e-mails you receive? Go to <a href="{{fullurl:{{ns:special}}:Preferences}}">User preferences</a></li>
</ul>
</p>'
);

/** Afrikaans (Afrikaans)
 * @author Naudefj
 */
$messages['af'] = array(
	'wikiafollowedpages-special-showmore' => 'Wys meer',
	'wikiafollowedpages-special-blog-by' => 'deur $1',
	'wikiafollowedpages-userpage-more' => 'Meer',
	'wikiafollowedpages-userpage-hide' => 'versteek',
);

/** Arabic (العربية)
 * @author Achraf94
 * @author Loya
 * @author OsamaK
 */
$messages['ar'] = array(
	'follow-desc' => 'تحسينات لخاصية الصفحات المراقبة',
	'wikiafollowedpages-special-heading-category' => 'التصنيفات ($1)',
	'wikiafollowedpages-special-heading-article' => 'المقالات ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'المدونات ($1)',
	'wikiafollowedpages-special-heading-forum' => 'مواضيع المنتدى ($1)',
	'wikiafollowedpages-special-heading-project' => 'صفحات المشاريع ($1)',
	'wikiafollowedpages-special-heading-user' => 'صفحات المستخدمين ($1)',
	'wikiafollowedpages-special-heading-templates' => 'صفحات القوالب ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'صفحات الميدياويكي ($1)',
	'wikiafollowedpages-special-heading-media' => 'الصور و الفيديو ($1)',
	'wikiafollowedpages-special-namespace' => '($1 صفحة)',
	'wikiafollowedpages-special-empty' => 'قائمة الصفحات المتابعة لهذا المستخدم فارغة.
أضف صفحات لهذه القائمة عبر النقر على "{{int:watch}}" في أعلى الصفحة.',
	'wikiafollowedpages-special-anon' => 'يرجى [[خاص:Signup|تسجيل الدخول]] من أجل رؤية قائمة الصفحات التي تتابعها.',
	'wikiafollowedpages-special-showall' => 'اعرض الكل >',
	'wikiafollowedpages-special-title' => 'الصفحات المتابعة',
	'wikiafollowedpages-special-delete-tooltip' => 'إزالة هذه الصفحة',
	'wikiafollowedpages-special-hidden' => 'هذا المستخدم إختار أن يخفي {{GENDER:$1|صفحاته|صفحاتها}} المتابعة.',
	'wikiafollowedpages-special-hidden-unhide' => 'إظهار هذه القائمة.',
	'wikiafollowedpages-special-blog-by' => 'من قبل $1',
	'wikiafollowedpages-masthead' => 'الصفحات المتابعة',
	'wikiafollowedpages-following' => 'متابعة',
	'wikiafollowedpages-special-title-userbar' => 'الصفحات المتابعة',
	'tog-enotiffollowedpages' => 'أرسل لي رسالة إلكترونية عندما يتم تغيير صفحة في قائمة متابعتي',
	'tog-enotiffollowedminoredits' => 'أرسل لي رسالة إلكترونية عند حدوث تغييرات طفيفة لصفحة أتابعها',
	'tog-hidefollowedpages' => 'جعل صفحات متابعتي خاصة بي فقط',
	'follow-categoryadd-summary' => 'تمت إضافة الصفحة للتصنيف',
	'follow-bloglisting-summary' => 'تمت إضافة مدونة في صفحة المدونات',
	'wikiafollowedpages-userpage-heading' => 'صفحات أتابعها',
	'wikiafollowedpages-userpage-hide-tooltip' => 'إخفاء قائمة صفحاتك المتابعة عن الآخرين',
	'wikiafollowedpages-userpage-more' => 'المزيد',
	'wikiafollowedpages-userpage-hide' => 'إخفاء',
	'wikiafollowedpages-userpage-empty' => 'قائمة الصفحات المتابعة لهذا المستخدم فارغة.
أضف صفحات لهذه القائمة عبر النقر على "{{int:watch}}" في أعلى الصفحة.',
	'enotif_subject_categoryadd' => 'تمت إضافة صفحة $PAGETITLE في {{SITENAME}} لتصنيف $CATEGORYNAME من قبل $PAGEEDITOR',
	'enotif_body_categoryadd' => 'عزيزي $WATCHINGUSERNAME,

لقد تمت إضافة صفحة لتصنيف تتابعه أنت في {{SITENAME}}.

أنظر "$PAGETITLE_URL" وهي الصفحة الجديدة

أرجو أن تزورنا و تقوم بتعديلات أكثر....

{{SITENAME}}

___________________________________________
* أنظر أيضا الويكيات المختارة في! http://www.wikia.com

* هل تريد أن تتحكم في الرسئل الإلكترونية التي تتلقاها منا?
زر: {{fullurl:{{ns:special}}:تفضيلات}}.',
	'enotif_body_categoryadd-html' => ' <p>
عزيزي $WATCHINGUSERNAME,
<br /><br />
لقد تمت إضافة صفحة لتصنيف تتابعه أنت في {{SITENAME}}.
<br /><br />
أنظر <a href="$PAGETITLE_URL">$PAGETITLE</a> وهي الصفحة الجديدة.
<br /><br />
أرجو أن تزورنا و تقوم بتعديلات أكثر....
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">أنظر أيضا الويكيات المختارة!</a></li>
<li> هل تريد أن تتحكم في الرسئل الإلكترونية التي تتلقاها منا؟ زر: <a href="{{fullurl:{{ns:special}}:Preferences}}">User preferences</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'تم عرض صفحة $PAGETITLE في {{SITENAME}} $BLOGLISTINGNAME من قبل $PAGEEDITOR في',
	'enotif_body_blogpost' => ' عزيزي $WATCHINGUSERNAME,

لقد تمت إضافة تعديل لصفحة قائمة المدونة تتابعها أنت في {{SITENAME}}.

أنظر "$PAGETITLE_URL" وهي المدونة الجديدة

أرجو أن تزورنا و تقوم بتعديلات أكثر....

{{SITENAME}}

___________________________________________
* أنظر أيضا الويكيات المختارة في! http://www.wikia.com

* هل تريد أن تتحكم في الرسئل الإلكترونية التي تتلقاها منا?
زر: {{fullurl:{{ns:special}}:تفضيلات}}.',
	'enotif_body_blogpost-HTML' => '<p>
عزيزي $WATCHINGUSERNAME,
<br /><br />
لقد تمت إضافة تعديل لصفحة قائمة المدونة تتابعها أنت في {{SITENAME}}.
<br /><br />
أنظر <a href="$PAGETITLE_URL">$PAGETITLE</a> وهي المدونة الجديدة.
<br /><br />
أرجو أن تزورنا و تقوم بتعديلات أكثر....
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">أنظر أيضا الويكيات المختارة!</a></li>
<li> هل تريد أن تتحكم في الرسئل الإلكترونية التي تتلقاها منا؟ زر: <a href="{{fullurl:{{ns:special}}:Preferences}}">User preferences</a></li>
</ul>
</p>',
);

/** Azerbaijani (Azərbaycanca)
 * @author Cekli829
 */
$messages['az'] = array(
	'wikiafollowedpages-special-heading-category' => 'Kateqoriyalar ($1)',
	'wikiafollowedpages-special-heading-article' => 'Məqalələr ($1)',
	'wikiafollowedpages-special-namespace' => '($1 səhifə)',
	'oasis-wikiafollowedpages-special-seeall' => 'Hamısına bax >',
	'wikiafollowedpages-special-showall' => 'Hamısını göstər >',
);

/** Bulgarian (Български)
 * @author DCLXVI
 */
$messages['bg'] = array(
	'wikiafollowedpages-special-heading-category' => 'Категории ($1)',
	'wikiafollowedpages-special-heading-article' => 'Статии ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Блогове и публикации ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Нишки от форумите ($1)',
	'wikiafollowedpages-special-heading-project' => 'Страници на проекта ($1)',
	'wikiafollowedpages-special-heading-user' => 'Потребителски страници ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Шаблони ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'МедияУики страници ($1)',
	'wikiafollowedpages-special-heading-media' => 'Снимки и видео ($1)',
	'wikiafollowedpages-special-blog-by' => 'от $1',
	'wikiafollowedpages-userpage-heading' => 'Страниците, които следя',
	'wikiafollowedpages-userpage-more' => 'Повече',
	'wikiafollowedpages-userpage-hide' => 'скриване',
);

/** Breton (Brezhoneg)
 * @author Fulup
 * @author Y-M D
 */
$messages['br'] = array(
	'follow-desc' => 'Gwelladennoù evit ar roll evezhiañ',
	'prefs-basic' => 'Dibarzhioù diazez',
	'wikiafollowedpages-special-heading-category' => 'Rummadoù ($1)',
	'wikiafollowedpages-special-heading-article' => 'Pennadoù ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogoù ha kemennadennoù ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Sujedoù ar foromoù ($1)',
	'wikiafollowedpages-special-heading-project' => 'Pajennoù raktres ($1)',
	'wikiafollowedpages-special-heading-user' => 'Pajennoù implijer ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Pajennoù patromoù ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Pajennoù MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Skeudennoù ha videoioù ($1)',
	'wikiafollowedpages-special-namespace' => '(pajenn $1)',
	'wikiafollowedpages-special-empty' => 'Goullo eo ho roll evezhiañ.
Ouzhpennit pajennoù d\'ar roll-mañ en ur glikañ war "{{int:watch}}" e laez ur bajenn.',
	'wikiafollowedpages-special-anon' => 'Mar plij [[Special:Signup|kevreit]] evit krouiñ pe sellout ouzh ho roll evezhiañ.',
	'oasis-wikiafollowedpages-special-seeall' => 'Gwelet pep tra >',
	'wikiafollowedpages-special-seeall' => 'Gwelet pep tra >',
	'wikiafollowedpages-special-showall' => 'Diskouez pep tra >',
	'wikiafollowedpages-special-showmore' => "Diskouez muioc'h",
	'wikiafollowedpages-special-title' => 'Pajennoù heuliet',
	'wikiafollowedpages-special-delete-tooltip' => 'Dilemel ar skeudenn-mañ',
	'wikiafollowedpages-special-hidden' => "An {{GENDER:$1|implijer|implijerez|implijerien}}-mañ {{GENDER:$1|en|he|o}} deus dibabet kuzhat {{GENDER:$1|en|he|o}} roll evezhiañ d'an dud all.",
	'wikiafollowedpages-special-hidden-unhide' => 'Diguzhat ar roll-mañ.',
	'wikiafollowedpages-special-blog-by' => 'gant $1',
	'wikiafollowedpages-masthead' => 'Pajennoù heuliet',
	'wikiafollowedpages-following' => 'O Heuliañ',
	'wikiafollowedpages-special-title-userbar' => 'Pajennoù heuliet',
	'tog-enotiffollowedpages' => 'Kas ur postel din pa vez degaset kemmoù war ur bajenn evezhiet ganin',
	'tog-enotiffollowedminoredits' => "Kelaouiñ ac'hanon dre postel pa vez degaset kemmoù dister d'ar pajennoù a heulian",
	'prefs-wikiafollowedpages-prefs-advanced' => 'Dibarzhioù araokaet',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Rollad evezhiañ hepken',
	'tog-hidefollowedpages' => 'Lakaat va roll evezhiañ da vezañ prevez',
	'follow-categoryadd-summary' => "Pajenn bet ouzhpennet d'ar rummad",
	'follow-bloglisting-summary' => 'Blog postet war pajennoù ar blogoù',
	'wikiafollowedpages-userpage-heading' => 'Pajennoù heuliet ganin',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Kuzhat ho rollad evezhiañ ouzh sell an dud',
	'wikiafollowedpages-userpage-more' => "Muioc'h",
	'wikiafollowedpages-userpage-hide' => 'kuzhat',
	'wikiafollowedpages-userpage-empty' => 'Goullo eo roll evezhiañ an implijer-mañ.
Ouzhpennit pajennoù d\'ar roll-mañ en ur glikañ war "{{int:watch}}" e laez ur bajenn.',
	'enotif_subject_categoryadd' => 'Ar bajenn $PAGETITLE eus al lec\'hienn {{SITENAME}} a zo bet ouzhpennet da $CATEGORYNAME gant $PAGEEDITOR',
	'enotif_subject_blogpost' => 'Ar bajenn $PAGETITLE eus al lec\'hienn {{SITENAME}} a zo bet postet war $BLOGLISTINGNAME gant $PAGEEDITOR',
);

/** Chechen (Нохчийн)
 * @author Sasan700
 */
$messages['ce'] = array(
	'wikiafollowedpages-special-heading-templates' => 'Куцкепаш ($1)',
	'wikiafollowedpages-userpage-more' => 'Кхин',
);

/** Czech (Česky)
 * @author Darth Daron
 * @author Dontlietome7
 */
$messages['cs'] = array(
	'follow-desc' => 'Vylepšení funkce seznamu sledovaných stránek',
	'prefs-basic' => 'Základní možnosti',
	'wikiafollowedpages-special-heading-category' => 'Kategorie ($1)',
	'wikiafollowedpages-special-heading-article' => 'Články ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogy a příspěvky ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Vlákna fóra ($1)',
	'wikiafollowedpages-special-heading-project' => 'Stránky projektu ($1)',
	'wikiafollowedpages-special-heading-user' => 'Uživatelské stránky ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Stránky šablon ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Stránky MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Obrázky a videa ($1)',
	'wikiafollowedpages-special-namespace' => '($1 stránka)',
	'wikiafollowedpages-special-empty' => 'Seznam sledovaných stránek tohoto uživatele je prázdný.
Přidejte stránky do tohoto seznamu klepnutím na tlačítko "{{int:watch}}" v horní části stránky.',
	'wikiafollowedpages-special-anon' => 'Chcete-li vytvořit nebo zobrazit seznam sledovaných stránek, [[Special:Signup|přihlašte se]].',
	'oasis-wikiafollowedpages-special-seeall' => 'Zobrazit vše >',
	'wikiafollowedpages-special-seeall' => 'Zobrazit vše >',
	'wikiafollowedpages-special-showall' => 'Zobrazit vše >',
	'wikiafollowedpages-special-showmore' => 'Zobrazit více',
	'wikiafollowedpages-special-title' => 'Sledované stránky',
	'wikiafollowedpages-special-delete-tooltip' => 'Odstranit tuto stránku',
	'wikiafollowedpages-special-hidden' => 'Tento uživatel se rozhodl nezobrazit veřejně {{GENDER:$1|s|s|s}}vůj seznam stránek.',
	'wikiafollowedpages-special-hidden-unhide' => 'Zobrazit tento seznam.',
	'wikiafollowedpages-special-blog-by' => 'od $1',
	'wikiafollowedpages-masthead' => 'Sledované stránky',
	'wikiafollowedpages-following' => 'Sledováno',
	'wikiafollowedpages-special-title-userbar' => 'Sledované stránky',
	'tog-enotiffollowedpages' => 'Poslat mi e-mail, pokud je změněna sledovaná stránky',
	'tog-enotiffollowedminoredits' => 'Poslat mi e-mail, i pokud je editace sledované stránky drobná',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Pokročilé možnosti',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Pouze seznam sledovaných stránek',
	'tog-hidefollowedpages' => 'Udělat můj seznam sledovaných stránek soukromý',
	'follow-categoryadd-summary' => 'Stránka přidána do kategorie',
	'follow-bloglisting-summary' => 'Blog přidán na stránku blogu',
	'wikiafollowedpages-userpage-heading' => 'Stránky, které sleduji',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Skrýt sledované stránky z veřejného zobrazení',
	'wikiafollowedpages-userpage-more' => 'Více',
	'wikiafollowedpages-userpage-hide' => 'Skrýt',
	'wikiafollowedpages-userpage-empty' => 'Seznam sledovaných stránek tohoto uživatele je prázdný.
Přidejte do tohoto seznamu kliknutím na "{{int:watch}} na vrcholu stránky.',
	'enotif_subject_categoryadd' => '{{SITENAME}} stránka $PAGETITLE byla přidána do $CATEGORYNAME uživatelem $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Drahý(á) $WATCHINGUSERNAME,

Do kategorie, kterou sledujete, byla přidána stránka na {{SITENAME}}.

Viz "$PAGETITLE_URL".

{{SITENAME}}

___________________________________________
* Koukněte se na dobré wiki! http://www.wikia.com

* Chcete nastavit, jaké e-maily dostanete? Jděte na: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>Drahý(á) $WATCHINGUSERNAME,<br /><br />
Do kategorie, kterou sledujete, byla přidána stránka na {{SITENAME}}.<br /> <br />
Viz <a href="$PAGETITLE_URL">$PAGETITLE</a>.<br /><br />
{{SITENAME}}<br /><hr />
<li><a href="http://www.wikia.com">Koukněte se na dobré wiki!</a></li>
<li>Chcete nastavit, jaké e-maily dostanete? Jděte do <a href="{{fullurl:{{ns:special}}:Preferences}}">Nastavení</a></li></ul></p>',
	'enotif_subject_blogpost' => '{{SITENAME}} stránka $PAGETITLE byla vložena do $BLOGLISTINGNAME uživatelem $PAGEEDITOR',
	'enotif_body_blogpost' => 'Drahý $WATCHINGUSERNAME,

Byla vytvořena nová stránka na seznamu blogů, který sledujete, na {{SITENAME}}.

Viz "$PAGETITLE_URL".

{{SITENAME}}

___________________________________________
* Koukněte se na dobré wiki! http://www.wikia.com

* Chcete nastavit, jaké e-maily dostanete? Jděte na: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>Drahý(á) $WATCHINGUSERNAME,<br /><br />
Seznam blogů na {{SITENAME}}, který sledujete, byl upraven.<br /> <br />
Viz <a href="$PAGETITLE_URL">$PAGETITLE</a>.<br /><br />
{{SITENAME}}<br /><hr />
<li><a href="http://www.wikia.com">Koukněte se na dobré wiki!</a></li>
<li>Chcete nastavit, jaké e-maily dostanete? Jděte do <a href="{{fullurl:{{ns:special}}:Preferences}}">Nastavení</a></li></ul></p>',
);

/** German (Deutsch)
 * @author Avatar
 * @author Kjell
 * @author LWChris
 * @author Lyzzy
 * @author The Evil IP address
 */
$messages['de'] = array(
	'follow-desc' => 'Verbesserungen an der Beobachtungsliste',
	'prefs-basic' => 'Grundlegende Optionen',
	'wikiafollowedpages-special-heading-category' => 'Kategorien ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artikel ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogs und Einträge ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Forum-Diskussionsstränge ($1)',
	'wikiafollowedpages-special-heading-project' => 'Projektseiten ($1)',
	'wikiafollowedpages-special-heading-user' => 'Benutzerseiten ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Vorlagenseiten ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki-Seiten ($1)',
	'wikiafollowedpages-special-heading-media' => 'Bilder und Videos ($1)',
	'wikiafollowedpages-special-namespace' => '($1 Seite)',
	'wikiafollowedpages-special-empty' => 'Die Liste deiner beobachteten Seiten ist leer.
Füge Seiten zu dieser Liste hinzu indem du auf "{{int:watch}}" klickst.',
	'wikiafollowedpages-special-anon' => 'Bitte [[Special:Signup|anmelden]] um deine Beobachtungsliste zu erstellen oder betrachten.',
	'oasis-wikiafollowedpages-special-seeall' => 'Zeige alle >',
	'wikiafollowedpages-special-seeall' => 'Sehe alle >',
	'wikiafollowedpages-special-showall' => 'Alle anzeigen',
	'wikiafollowedpages-special-showmore' => 'Zeige mehr',
	'wikiafollowedpages-special-title' => 'Beobachtete Seiten',
	'wikiafollowedpages-special-delete-tooltip' => 'Diese Seite entfernen',
	'wikiafollowedpages-special-hidden' => 'Dieser {{GENDER:$1|Benutzer|Benutzerin|Benutzer}} hat sich dazu entschieden, {{GENDER:$1|seine|ihre|seine}} Beobachtungsliste von der Öffentlichkeit zu verstecken.',
	'wikiafollowedpages-special-hidden-unhide' => 'Diese Liste nicht mehr verstecken.',
	'wikiafollowedpages-special-blog-by' => 'von $1',
	'wikiafollowedpages-masthead' => 'Beobachtete Seiten',
	'wikiafollowedpages-following' => 'Folgende',
	'wikiafollowedpages-special-title-userbar' => 'Beobachtete Seiten',
	'tog-enotiffollowedpages' => 'Bei Änderungen an beobachteten Seiten E-Mails senden',
	'tog-enotiffollowedminoredits' => 'Auch bei kleinen Änderungen an beobachteten Seiten E-Mails senden',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Erweiterte Optionen',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Nur Beobachtungsliste',
	'tog-hidefollowedpages' => 'Halte meine Beobachtungsliste privat',
	'follow-categoryadd-summary' => 'Seite zu Kategorie hinzugefügt',
	'follow-bloglisting-summary' => 'Blog auf Blogseite gepostet',
	'wikiafollowedpages-userpage-heading' => 'Seiten, die ich beobachte',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Liste verfolgter Seiten vor öffentlicher Einsicht schützen',
	'wikiafollowedpages-userpage-more' => 'Mehr',
	'wikiafollowedpages-userpage-hide' => 'verstecken',
	'wikiafollowedpages-userpage-empty' => 'Die Liste der beobachteten Seiten dieses Benutzers ist leer.
Du kannst durch Klicken des {{int:watch}}-Buttons Seiten dieser Liste hinzufügen.',
	'enotif_subject_categoryadd' => '[{{SITENAME}}] Die Seite „$PAGETITLE“ wurde von $PAGEEDITOR in die Kategorie $CATEGORYNAME hinzugefügt',
	'enotif_body_categoryadd' => 'Hallo $WATCHINGUSERNAME,

Eine Seite, die du auf {{SITENAME}} beobachtest, wurde einer Kategorie hinzugefügt.

Siehe „$PAGETITLE_URL“ für die neue Seite.

Schau doch mal rein und bearbeite sie weiter...

{{SITENAME}}

___________________________________________
* Schau dir unsere exzellenten Wikis an! http://www.wikia.com

* Willst du kontrollieren, welche E-Mails du erhältst?
Gehe auf: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Hallo $WATCHINGUSERNAME,
<br /><br />
Eine Seite, die du auf {{SITENAME}} beobachtest, wurde einer Kategorie hinzugefügt.
<br /><br />
Siehe <a href="$PAGETITLE_URL">$PAGETITLE</a> für die neue Seite
<br /><br />
Schau doch mal rein und bearbeite sie weiter...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Schau dir unsere exzellenten Wikis an!</a></li>
<li>Willst du kontrollieren, welche E-Mails du erhältst? Gehe auf: <a href="{{fullurl:{{ns:special}}:Preferences}}">Benutzer-Einstellungen</a></li>
</p>',
	'enotif_subject_blogpost' => '[{{SITENAME}}] Die Seite $PAGETITLE wurde von $PAGEEDITOR auf $BLOGLISTINGNAME gepostet',
	'enotif_body_blogpost' => 'Hallo $WATCHINGUSERNAME,

Es gab eine Bearbeitung an einem Blog, den du auf {{SITENAME}} beobachtest.

Siehe „$PAGETITLE_URL“ für den neuen Post.

Schau doch mal rein und bearbeite sie weiter...

{{SITENAME}}

___________________________________________

* Schau dir unsere exzellenten Wikis an! http://www.wikia.com

* Willst du kontrollieren, welche E-Mails du erhältst?
Gehe auf: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Hallo $WATCHINGUSERNAME,
<br /><br />
Es gab eine Bearbeitung an einem Blog, den du auf {{SITENAME}} beobachtest.
<br /><br />
Siehe <a href="$PAGETITLE_URL">$PAGETITLE</a> für den neuen Post.
<br /><br />
Schau doch mal rein und bearbeite sie weiter...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Schau dir unsere exzellenten Wikis an!</a></li>
<li>Willst du kontrollieren, welche E-Mails du erhältst? Gehe auf: <a href="{{fullurl:{{ns:special}}:Preferences}}">Benutzer-Einstellungen</a>.</li>
</ul>
</p>',
);

/** German (formal address) (‪Deutsch (Sie-Form)‬)
 * @author Claudia Hattitten
 * @author The Evil IP address
 */
$messages['de-formal'] = array(
	'wikiafollowedpages-special-empty' => 'Ihre Liste verfolgter Seiten ist leer.
Sie können durch Klicken des {{int:watch}}-Knopfes Seiten dieser Liste hinzufügen.',
	'wikiafollowedpages-special-anon' => 'Bitte [[Special:Signup|anmelden]] um Ihre Beobachtungsliste zu erstellen oder betrachten.',
	'wikiafollowedpages-userpage-empty' => 'Die Liste der beobachteten Seiten dieses Benutzers ist leer.
Sie können durch Klicken des {{int:watch}}-Buttons Seiten dieser Liste hinzufügen.',
	'enotif_body_categoryadd' => 'Hallo $WATCHINGUSERNAME,

Eine Seite, die Sie auf {{SITENAME}} beobachten, wurde einer Kategorie hinzugefügt.

Siehe „$PAGETITLE_URL“ für die neue Seite.

Schauen Sie doch mal rein und bearbeiten Sie sie weiter...

{{SITENAME}}

___________________________________________
* Schauen Sie sich unsere exzellenten Wikis an! http://www.wikia.com

* Wollen Sie kontrollieren, welche E-Mails Sie erhalten?
Gehen Sie auf: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Hallo $WATCHINGUSERNAME,
<br /><br />
Eine Seite, die Sie auf {{SITENAME}} beobachten, wurde einer Kategorie hinzugefügt.
<br /><br />
Siehe <a href="$PAGETITLE_URL">$PAGETITLE</a> für die neue Seite
<br /><br />
Schauen Sie doch mal rein und bearbeiten Sie sie weiter...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Schauen Sie sich unsere exzellenten Wikis an!</a></li>
<li>Wollen Sie kontrollieren, welche E-Mails Sie erhalten? Gehen Sie auf: <a href="{{fullurl:{{ns:special}}:Preferences}}">Benutzer-Einstellungen</a></li>
</p>',
	'enotif_body_blogpost' => 'Hallo $WATCHINGUSERNAME,

Es gab eine Bearbeitung an einem Blog, den Sie auf {{SITENAME}} beobachten.

Siehe „$PAGETITLE_URL“ für den neuen Post.

Schauen Sie doch mal rein und bearbeiten Sie sie weiter...

{{SITENAME}}

___________________________________________

* Schauen Sie sich unsere exzellenten Wikis an! http://www.wikia.com

* Wollen Sie kontrollieren, welche E-Mails Sie erhalten?
Gehen Sie auf: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Hallo $WATCHINGUSERNAME,
<br /><br />
Es gab eine Bearbeitung an einem Blog, den Sie auf {{SITENAME}} beobachten.
<br /><br />
Siehe <a href="$PAGETITLE_URL">$PAGETITLE</a> für den neuen Post.
<br /><br />
Schauen Sie doch mal rein und bearbeiten Sie sie weiter...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Schauen Sie sich unsere exzellenten Wikis an!</a></li>
<li>Wollen Sie kontrollieren, welche E-Mails Sie erhalten? Gehen Sie auf: <a href="{{fullurl:{{ns:special}}:Preferences}}">Benutzer-Einstellungen</a>.</li>
</ul>
</p>',
);

/** Greek (Ελληνικά)
 * @author Evropi
 */
$messages['el'] = array(
	'wikiafollowedpages-special-heading-category' => 'Κατηγορίες ($1)',
	'wikiafollowedpages-special-heading-article' => 'Άρθρα ($1)',
	'wikiafollowedpages-special-showall' => 'Προβολή όλων >',
	'wikiafollowedpages-userpage-more' => 'Περισσότερα',
	'wikiafollowedpages-userpage-hide' => 'απόκρυψη',
);

/** Spanish (Español)
 * @author Bola
 * @author Crazymadlover
 * @author Danke7
 * @author Sanbec
 * @author VegaDark
 */
$messages['es'] = array(
	'follow-desc' => 'Mejoras para la funcionalidad de la lista de vigilancia',
	'prefs-basic' => 'Opciones básicas',
	'wikiafollowedpages-special-heading-category' => 'Categorías ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artículos ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogs y mensajes ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Hilos del foro ($1)',
	'wikiafollowedpages-special-heading-project' => 'Páginas de proyecto ($1)',
	'wikiafollowedpages-special-heading-user' => 'Páginas de usuario ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Páginas de plantillas ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Páginas de MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Imágenes y videos ($1)',
	'wikiafollowedpages-special-namespace' => '($1 página)',
	'wikiafollowedpages-special-empty' => 'La lista de páginas seguidas por este usuario está vacía.
Agregar páginas a esta lista haciendo click en "{{int:watch}}" arriba de una página.',
	'wikiafollowedpages-special-anon' => 'Por favor [[Special:Signup|inicia sesión]] para crear o ver tu lista de páginas seguidas.',
	'oasis-wikiafollowedpages-special-seeall' => 'Ver todo >',
	'wikiafollowedpages-special-seeall' => 'Ver todo >',
	'wikiafollowedpages-special-showall' => 'Mostrar todo >',
	'wikiafollowedpages-special-showmore' => 'Mostrar más',
	'wikiafollowedpages-special-title' => 'Páginas seguidas',
	'wikiafollowedpages-special-delete-tooltip' => 'remover esta página',
	'wikiafollowedpages-special-hidden' => 'Este usuario ha elegido ocultar {{GENDER:$1|su|su|su}} lista de páginas seguidas a la vista del público.',
	'wikiafollowedpages-special-hidden-unhide' => 'Dejar de ocultar esta lista.',
	'wikiafollowedpages-special-blog-by' => 'por $1',
	'wikiafollowedpages-masthead' => 'Páginas seguidas',
	'wikiafollowedpages-following' => 'Siguiendo',
	'wikiafollowedpages-special-title-userbar' => 'Páginas seguidas',
	'tog-enotiffollowedpages' => 'Enviarme un correo electrónico cuando una página que estoy siguiendo es cambiada',
	'tog-enotiffollowedminoredits' => 'Enviarme un correo electrónico por ediciones menores a las páginas que estoy siguiendo',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Opciones avanzadas',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Solo lista de seguimiento',
	'tog-hidefollowedpages' => 'Hacer privada mi lista de páginas seguidas',
	'follow-categoryadd-summary' => 'Página agregada a categoría',
	'follow-bloglisting-summary' => 'Blog publicado en la página de blog',
	'wikiafollowedpages-userpage-heading' => 'Páginas que estoy siguiendo',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Ocultar tus listas de páginas seguidas  de la vista del público',
	'wikiafollowedpages-userpage-more' => 'Más',
	'wikiafollowedpages-userpage-hide' => 'ocultar',
	'wikiafollowedpages-userpage-empty' => 'La lista de páginas seguidas de este usuario está vacía.
Agregar páginas a esta lista haciendo click en "{{int:watch}}" en la parte superior de una página.',
	'enotif_subject_categoryadd' => 'Página {{SITENAME}} $PAGETITLE ha sido agregada a $CATEGORYNAME por $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Querido $WATCHINGUSERNAME,

Una página ha sido agregada a una categoría que estás siguiendo en {{SITENAME}}.

Ver "$PAGETITLE_URL" para la nueva página.

Por favor visita y edita frecuentemente...

{{SITENAME}}

___________________________________________
* Verifica nuestros wikis destacados! http://www.wikia.com

* Deseas controlar los correos que recibes?
Ve a: {{fullurl:{{ns:special}}:Preferencias}}.',
	'enotif_body_categoryadd-html' => '<p>
Querido $WATCHINGUSERNAME,
<br /><br />
Una página ha sido agregada a una categoría que estás siguiendo en {{SITENAME}}.
<br /><br />
Ver <a href="$PAGETITLE_URL">$PAGETITLE</a> para la nueva página.
<br /><br />
Por favor visita y edita frecuentemente...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Verifica nuestros wikis destacados!</a></li>
<li>Deseas controlar los correos que recibes? Ve a <a href="{{fullurl:{{ns:special}}:Preferenciass}}">Preferencias de usuario</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Página {{SITENAME}} $PAGETITLE ha sido publicada en $BLOGLISTINGNAME por $PAGEEDITOR',
	'enotif_body_blogpost' => 'Querido $WATCHINGUSERNAME,

Hubo una edición a una página de listado de blogs que estás siguiendo en {{SITENAME}}.

Ver "$PAGETITLE_URL" para el nuevo mensaje.

Por favor visita y edita frecuentemente...

{{SITENAME}}

___________________________________________
* Verifica nuestros wikis destacados! http://www.wikia.com

* Deseas controlar los correos que recibes?
Ve a: {{fullurl:{{ns:special}}:Preferencias}}.',
	'enotif_body_blogpost-HTML' => '<p>
Querido $WATCHINGUSERNAME,
<br /><br />
Hubo una edición a una página de listado de blogs que estás siguiendo en {{SITENAME}}.
<br /><br />
Ver <a href="$PAGETITLE_URL">$PAGETITLE</a> para el nuevo mensaje.
<br /><br />
Por favor visita y edita frecuentemente...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Verifica nuestros wikis destacados!</a></li>
<li>Deseas controlar los correos que recibes? Ve a <a href="{{fullurl:{{ns:special}}:Preferencias}}">Preferencias de usuario</a></li>
</ul>
</p>',
);

/** Basque (Euskara)
 * @author An13sa
 */
$messages['eu'] = array(
	'wikiafollowedpages-userpage-more' => 'Gehiago',
	'wikiafollowedpages-userpage-hide' => 'ezkutatu',
);

/** Persian (فارسی)
 * @author BlueDevil
 */
$messages['fa'] = array(
	'wikiafollowedpages-special-heading-article' => 'مقالات ( $1 )',
	'wikiafollowedpages-special-heading-mediawiki' => 'مدیاویکی صفحات ($1)',
	'wikiafollowedpages-special-showall' => 'نمایش همه >',
	'wikiafollowedpages-special-showmore' => 'نمایش بیشتر',
	'wikiafollowedpages-special-blog-by' => 'توسط $1',
	'wikiafollowedpages-userpage-more' => 'بیشتر',
	'wikiafollowedpages-userpage-hide' => 'پنهان کردن',
);

/** Finnish (Suomi)
 * @author Nike
 * @author Tofu II
 */
$messages['fi'] = array(
	'wikiafollowedpages-special-heading-category' => 'Luokat ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artikkelit ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogit ja viestit ($1)',
	'wikiafollowedpages-special-heading-user' => 'Käyttäjäsivut ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki-sivut ($1)',
	'wikiafollowedpages-special-heading-media' => 'Kuvat ja videot ($1)',
	'wikiafollowedpages-special-namespace' => '($1 sivu)',
	'wikiafollowedpages-special-title' => 'Seuratut sivut',
);

/** French (Français)
 * @author Peter17
 * @author Sherbrooke
 * @author Urhixidur
 * @author Wyz
 */
$messages['fr'] = array(
	'follow-desc' => 'Améliorations pour la liste de suivi',
	'prefs-basic' => 'Préférences de base',
	'wikiafollowedpages-special-heading-category' => 'Catégories ($1)',
	'wikiafollowedpages-special-heading-article' => 'Articles ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogs et billets ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Sujets de forums ($1)',
	'wikiafollowedpages-special-heading-project' => 'Pages de projet ($1)',
	'wikiafollowedpages-special-heading-user' => 'Pages utilisateur ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Pages de modèles ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Pages MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Images et vidéos ($1)',
	'wikiafollowedpages-special-namespace' => '(page $1)',
	'wikiafollowedpages-special-empty' => 'Votre liste de pages suivies est vide.
Ajoutez des pages à cette liste en cliquant sur « {{int:watch}} » en bas d’une page.',
	'wikiafollowedpages-special-anon' => 'Veuillez [[Special:Signup|vous identifier]] pour créer ou voir votre liste de suivi.',
	'oasis-wikiafollowedpages-special-seeall' => 'Tout voir >',
	'wikiafollowedpages-special-seeall' => 'Tout voir >',
	'wikiafollowedpages-special-showall' => 'Tout afficher >',
	'wikiafollowedpages-special-showmore' => 'Voir davantage',
	'wikiafollowedpages-special-title' => 'Pages suivies',
	'wikiafollowedpages-special-delete-tooltip' => 'Retirer cette page',
	'wikiafollowedpages-special-hidden' => 'Cet{{GENDER:$1||te|}} {{GENDER:$1|utilisateur|utilisatrice|utilisateur}} a choisi de cacher sa liste de suivi au public.',
	'wikiafollowedpages-special-hidden-unhide' => 'Afficher cette liste.',
	'wikiafollowedpages-special-blog-by' => 'par $1',
	'wikiafollowedpages-masthead' => 'Pages suivies',
	'wikiafollowedpages-following' => 'Suivi',
	'wikiafollowedpages-special-title-userbar' => 'Pages suivies',
	'tog-enotiffollowedpages' => 'M’avertir par courriel lorsqu’une page de ma liste de suivi est modifiée',
	'tog-enotiffollowedminoredits' => 'M’avertir par courriel lorsque des modifications mineures sont effectuées sur des pages que je suis',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Préférences avancées',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Liste de suivi uniquement',
	'tog-hidefollowedpages' => 'Rendre privée ma liste de suivi',
	'follow-categoryadd-summary' => 'Page ajoutée à la catégorie',
	'follow-bloglisting-summary' => 'Blog posté sur la page de blog',
	'wikiafollowedpages-userpage-heading' => 'Pages que je suis',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Cacher votre liste de suivi de la vue du public',
	'wikiafollowedpages-userpage-more' => 'Plus',
	'wikiafollowedpages-userpage-hide' => 'masquer',
	'wikiafollowedpages-userpage-empty' => 'La liste de pages suivies de cet utilisateur est vide.
Ajoutez des pages à cette liste en cliquant sur « {{int:watch}} » en bas d’une page.',
	'enotif_subject_categoryadd' => 'La page $PAGETITLE de {{SITENAME}} a été ajoutée à $CATEGORYNAME par $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Bonjour $WATCHINGUSERNAME,

Une page a été ajoutée à une catégorie que vous suivez sur {{SITENAME}}.

Voyez « $PAGETITLE_URL » pour la nouvelle page.

Merci de visiter ce site et de le modifier fréquemment...

{{SITENAME}}

___________________________________________
* Jetez un œil à nos wikis vedettes ! http://www.wikia.com

* Vous voulez contrôler l’envoi des courriers électroniques ?
Allez sur : {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Bonjour $WATCHINGUSERNAME,
<br /><br />
Une page a été ajoutée à une catégorie que vous suivez sur {{SITENAME}}.
<br /><br />
Voyez <a href="$PAGETITLE_URL">$PAGETITLE</a> pour la nouvelle page.
<br /><br />
Merci de visiter ce site et de le modifier fréquemment...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Jetez un œil à nos wikis vedettes !</a></li>
<li>Vous voulez contrôler l’envoi des courriers électroniques ? Allez sur <a href="{{fullurl:{{ns:special}}:Preferences}}">vos préférences utilisateur</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'La page $PAGETITLE de {{SITENAME}} a été postée sur $BLOGLISTINGNAME par $PAGEEDITOR',
	'enotif_body_blogpost' => 'Bonjour $WATCHINGUSERNAME,

Une modification a été apportée à l’une des pages de liste de blogs que vous suivez sur {{SITENAME}}.

Voyez « $PAGETITLE_URL » pour ce nouveau post.

Merci de visiter ce site et de le modifier fréquemment...

{{SITENAME}}

___________________________________________
* Jetez un œil à nos wikis vedettes ! http://www.wikia.com

* Vous voulez contrôler l’envoi des courriers électroniques ?
Allez sur : {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Bonjour $WATCHINGUSERNAME,
<br /><br />
Une modification a été apportée à l’une des pages de liste de blogs que vous suivez sur {{SITENAME}}.
<br /><br />
Voyez <a href="$PAGETITLE_URL">$PAGETITLE</a> pour ce nouveau post.
<br /><br />
Merci de visiter ce site et de le modifier fréquemment...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Jetez un œil à nos wikis vedettes !</a></li>
<li>Vous voulez contrôler l’envoi des courriers électroniques ? Allez sur <a href="{{fullurl:{{ns:special}}:Preferences}}">vos préférences utilisateur</a></li>
</ul>
</p>',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'follow-desc' => 'Melloras para a lista de vixilancia',
	'wikiafollowedpages-special-heading-category' => 'Categorías ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artigos ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogues e publicacións ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Fíos no foro ($1)',
	'wikiafollowedpages-special-heading-project' => 'Páxinas do proxecto ($1)',
	'wikiafollowedpages-special-heading-user' => 'Páxinas de usuario ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Páxinas de modelo ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Páxinas de MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Imaxes e vídeos ($1)',
	'wikiafollowedpages-special-namespace' => '(páxina $1)',
	'wikiafollowedpages-special-empty' => 'A lista de vixilancia deste usuario está baleira.
Engada páxinas a esta lista premendo no botón "{{int:watch}}" que aparecerá na parte superior das páxinas.',
	'wikiafollowedpages-special-anon' => '[[Special:Signup|Acceda ao sistema]] para crear ou ollar a súa lista de vixilancia.',
	'oasis-wikiafollowedpages-special-seeall' => 'Ollar todos >',
	'wikiafollowedpages-special-seeall' => 'Ollar todos >',
	'wikiafollowedpages-special-showall' => 'Mostrar todo >',
	'wikiafollowedpages-special-showmore' => 'Mostrar máis',
	'wikiafollowedpages-special-title' => 'Páxinas vixiadas',
	'wikiafollowedpages-special-delete-tooltip' => 'Eliminar esta páxina',
	'wikiafollowedpages-special-hidden' => '{{GENDER:$1|Este usuario|Esta usuaria|Este usuario}} optou por agochar a súa lista de vixilancia da vista dos demais.',
	'wikiafollowedpages-special-hidden-unhide' => 'Descubrir esta lista.',
	'wikiafollowedpages-special-blog-by' => 'por $1',
	'wikiafollowedpages-masthead' => 'Páxinas vixiadas',
	'wikiafollowedpages-following' => 'Vixiando',
	'wikiafollowedpages-special-title-userbar' => 'Páxinas vixiadas',
	'tog-enotiffollowedpages' => 'Enviádeme unha mensaxe de correo electrónico cando unha páxina da miña lista de vixilancia cambie',
	'tog-enotiffollowedminoredits' => 'Enviádeme unha mensaxe de correo electrónico cando fagan unha edición pequena nalgunha páxina que vixío',
	'tog-hidefollowedpages' => 'Facer privada a miña lista de vixilancia',
	'follow-categoryadd-summary' => 'Páxina engadida á categoría',
	'follow-bloglisting-summary' => 'Blogue publicado na páxina do blogue',
	'wikiafollowedpages-userpage-heading' => 'Páxinas que vixío',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Agochar as listas de páxinas que segue da vista pública',
	'wikiafollowedpages-userpage-more' => 'Máis',
	'wikiafollowedpages-userpage-hide' => 'agochar',
	'wikiafollowedpages-userpage-empty' => 'A lista de vixilancia deste usuario está baleira.
Engada páxinas a esta lista premendo no botón "{{int:watch}}" que aparecerá na parte superior das páxinas.',
	'enotif_subject_categoryadd' => '$PAGEEDITOR engadiu a páxina "$PAGETITLE" de {{SITENAME}} a $CATEGORYNAME',
	'enotif_body_categoryadd' => 'Estimado $WATCHINGUSERNAME:

Engadiron unha páxina a unha categoría que vixía en {{SITENAME}}.

Olle "$PAGETITLE_URL" para ver a nova páxina.

Volva e edite a miúdo...

{{SITENAME}}

___________________________________________
* Visite os nosos wikis destacados! http://www.wikia.com

* Quere controlar os correos que lle chegan?
Vaia a: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Estimado $WATCHINGUSERNAME:
<br /><br />
Engadiron unha páxina a unha categoría que vixía en {{SITENAME}}.
<br /><br />
Olle <a href="$PAGETITLE_URL">$PAGETITLE</a> para ver a nova páxina.
<br /><br />
Volva e edite a miúdo...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Visite os nosos wikis destacados!</a></li>
<li>Quere controlar os correos que lle chegan? Vaia ás <a href="{{fullurl:{{ns:special}}:Preferences}}">preferencias de usuario</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => '$PAGEEDITOR publicou a páxina "$PAGETITLE" de {{SITENAME}} en $BLOGLISTINGNAME',
	'enotif_body_blogpost' => 'Estimado $WATCHINGUSERNAME:

Fixeron unha edición nunha das páxinas da lista de bloques que vixía en {{SITENAME}}.

Olle "$PAGETITLE_URL" para ver a nova entrada.

Volva e edite a miúdo...

{{SITENAME}}

___________________________________________
* Visite os nosos wikis destacados! http://www.wikia.com

* Quere controlar os correos que lle chegan?
Vaia a: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Estimado $WATCHINGUSERNAME:
<br /><br />
Fixeron unha edición nunha das páxinas da lista de bloques que vixía en {{SITENAME}}.
<br /><br />
Olle <a href="$PAGETITLE_URL">$PAGETITLE</a> para ver a nova entrada.
<br /><br />
Volva e edite a miúdo...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Visite os nosos wikis destacados!</a></li>
<li>Quere controlar os correos que lle chegan? Vaia ás <a href="{{fullurl:{{ns:special}}:Preferences}}">preferencias de usuario</a></li>
</ul>
</p>',
);

/** Hebrew (עברית)
 * @author Amire80
 * @author Rotemliss
 * @author YaronSh
 * @author שומבלע
 */
$messages['he'] = array(
	'follow-desc' => 'שיפורים לתכונת רשימת המעקב',
	'prefs-basic' => 'אפשרויות בסיסיות',
	'wikiafollowedpages-special-heading-category' => 'קטגוריות ($1)',
	'wikiafollowedpages-special-heading-article' => 'ערכים ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'בלוגים ורשומות ($1)',
	'wikiafollowedpages-special-heading-forum' => 'שרשורים בפורומים ($1)',
	'wikiafollowedpages-special-heading-project' => 'דפי מיזם ($1)',
	'wikiafollowedpages-special-heading-user' => 'דפי משתמש ($1)',
	'wikiafollowedpages-special-heading-templates' => 'דפי תבניות ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'דפי מדיה־ויקי ($1)',
	'wikiafollowedpages-special-heading-media' => 'תמונות וקטעי וידאו ($1)',
	'wikiafollowedpages-special-namespace' => '(דף $1)',
	'wikiafollowedpages-special-empty' => 'רשימת הדפים שאחריהם אתם עוקבים ריקה.
נא להוסיף דפים לרשימה זו על ידי לחיצה על "{{int:watch}}" בראש הדף.',
	'wikiafollowedpages-special-anon' => 'נא [[Special:Signup|להיכנס]] כדי ליצור או לצפות ברשימת הדפים שבמעקב.',
	'oasis-wikiafollowedpages-special-seeall' => 'צפייה בכולם >',
	'wikiafollowedpages-special-seeall' => 'צפייה בכולם >',
	'wikiafollowedpages-special-showall' => 'להציג הכול >',
	'wikiafollowedpages-special-showmore' => 'להציג עוד',
	'wikiafollowedpages-special-title' => 'דפים במעקב',
	'wikiafollowedpages-special-delete-tooltip' => 'הסרת דף זה',
	'wikiafollowedpages-special-hidden' => 'המשתמש{{GENDER:$1||ת|ים|ות}} בחר להסתיר את רשימת המעקב {{GENDER:$1|של|שלה|שלהם|שלהן}} מפני צפייה ציבורית.',
	'wikiafollowedpages-special-hidden-unhide' => 'הצגת רשימה זו.',
	'wikiafollowedpages-special-blog-by' => 'על ידי $1',
	'wikiafollowedpages-masthead' => 'דפים במעקב',
	'wikiafollowedpages-following' => 'במעקב אחר',
	'wikiafollowedpages-special-title-userbar' => 'דפים במעקב',
	'tog-enotiffollowedpages' => 'יש לשלוח לי דוא״ל כאשר דף שאחריו אני עוקב משתנה',
	'tog-enotiffollowedminoredits' => 'יש לשלוח לי דוא״ל על עריכות משניות בדפים שאחריהם אני עוקב',
	'prefs-wikiafollowedpages-prefs-advanced' => 'אפשרויות מתקדמות',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'רשימת מעקב בלבד',
	'tog-hidefollowedpages' => 'הפיכת רשימות דפי המעקב שלי לפרטיות',
	'follow-categoryadd-summary' => 'נוסף דף לקטגוריה',
	'follow-bloglisting-summary' => 'פורסם בלוג בדף הבלוגים',
	'wikiafollowedpages-userpage-heading' => 'דפים שאחריהם אני עוקב',
	'wikiafollowedpages-userpage-hide-tooltip' => 'הסתרת רשימות הדפים שבמעקב מצפייה ציבורית',
	'wikiafollowedpages-userpage-more' => 'עוד',
	'wikiafollowedpages-userpage-hide' => 'הסתרה',
	'wikiafollowedpages-userpage-empty' => 'רשימת הדפים אחריהם עקב המשתמש ריקה.
ניתן להוסיף דפים לרשימה זו על ידי לחיצה על "{{int:watch}}" בראש דף.',
	'enotif_subject_categoryadd' => 'הדף $PAGETITLE ב{{grammar:תחילית|{{SITENAME}}}} נוסף ל$CATEGORYNAME על־ידי $PAGEEDITOR',
	'enotif_body_categoryadd' => 'לכבוד $WATCHINGUSERNAME,

נוסף דף לקטגוריה שאתם עוקבים אחריה ב{{grammar:תחילית|{{SITENAME}}}}.

ראו "$PAGETITLE_URL" לדף החדש.

אנא בקרו וערכו לעיתים קרובות...

{{SITENAME}}

___________________________________________
* Check out our featured wikis! http://www.wikia.com

* רוצים לקבוע אילו הודעות דוא"ל לקבל?
ראו: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
לכבוד $WATCHINGUSERNAME,
<br /><br />
נוסף דף לקטגוריה שאתם עוקבים אחריה ב{{grammar:תחילית|{{SITENAME}}}}.
<br /><br />
ראו <a href="$PAGETITLE_URL">$PAGETITLE</a> לדף החדש.
<br /><br />
אנא בקרו וערכו לעיתים קרובות...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Check out our featured wikis!</a></li>
<li>רוצים לקבוע אילו הודעות דוא"ל לקבל? ראו <a href="{{fullurl:{{ns:special}}:Preferences}}">העדפות המשתמש</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'הדף ב{{grammar:תחילית|SITENAME}} בשם $PAGETITLE פורסם בבלוג $BLOGLISTINGNAME על ידי $PAGEEDITOR',
	'enotif_body_blogpost' => 'שלום $WATCHINGUSERNAME,

דף הרישום של אחד הבלוגים שאחריו יש לך מעקב באתר {{SITENAME}} נערך.

ניתן לבקר בכתובת "$PAGETITLE_URL" לצפייה ברשומה החדשה.

נא לבקר ולערוך לעתים קרובות...

{{SITENAME}}

___________________________________________
* בואו לעיין באתרי הוויקי המומלצים שלנו! http://www.wikia.com

* מעוניינים לשלוט בהודעות הדוא״ל המגיעות לתיבה שלכם?
היכנסו אל: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p style="direction:rtl;">
שלום $WATCHINGUSERNAME,
<br /><br />
דף הרישום של אחד הבלוגים שאחריו יש לך מעקב באתר {{SITENAME}} נערך.
<br /><br />
ניתן לבקר בכתובת "$PAGETITLE_URL" לצפייה ברשומה החדשה.
<br /><br />
נא לבקר ולערוך לעתים קרובות...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">בואו לעיין באתרי הוויקי המומלצים שלנו!</a></li>
<li>מעוניינים לשלוט בהודעות הדוא״ל המגיעות לתיבה שלכם? היכנסו אל: <a href="{{fullurl:{{ns:special}}:Preferences}}">העדפות המשתמש</a></li>
</ul>
</p>',
);

/** Hungarian (Magyar)
 * @author Dani
 */
$messages['hu'] = array(
	'prefs-basic' => 'Alapbeállítások',
	'wikiafollowedpages-special-heading-category' => 'Kategóriák ($1)',
	'wikiafollowedpages-special-heading-article' => 'Szócikkek ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogok és bejegyzések ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Fórumtémák ($1)',
	'wikiafollowedpages-special-heading-project' => 'Projektlapok ($1)',
	'wikiafollowedpages-special-heading-user' => 'Felhasználói lapok ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Sablonok ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki-lapok ( $1 )',
	'wikiafollowedpages-special-heading-media' => 'Képek és videók ($1)',
	'wikiafollowedpages-special-namespace' => '($1 lap)',
	'oasis-wikiafollowedpages-special-seeall' => 'Összes >',
	'wikiafollowedpages-special-seeall' => 'Összes >',
	'wikiafollowedpages-special-showall' => 'Összes >',
	'wikiafollowedpages-special-showmore' => 'Továbbiak',
	'wikiafollowedpages-special-title' => 'Követett lapok',
	'wikiafollowedpages-special-delete-tooltip' => 'Lap eltávolítása',
	'wikiafollowedpages-special-blog-by' => 'írta: $1',
	'wikiafollowedpages-masthead' => 'Követett lapok',
	'wikiafollowedpages-following' => 'Követés',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Haladó beállítások',
	'wikiafollowedpages-userpage-more' => 'Tovább',
	'wikiafollowedpages-userpage-hide' => 'elrejtés',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'follow-desc' => 'Meliorationes pro le functionalitate del observatorio',
	'prefs-basic' => 'Optiones de base',
	'wikiafollowedpages-special-heading-category' => 'Categorias ($1)',
	'wikiafollowedpages-special-heading-article' => 'Articulos ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogs e articulos ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Filos de discussion in foros ($1)',
	'wikiafollowedpages-special-heading-project' => 'Paginas de projecto ($1)',
	'wikiafollowedpages-special-heading-user' => 'Paginas de usator ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Paginas de patronos ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Paginas de MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Imagines e videos ($1)',
	'wikiafollowedpages-special-namespace' => '(pagina $1)',
	'wikiafollowedpages-special-empty' => 'Tu lista de paginas sub observation es vacue.
Adde paginas a iste lista con un clic super "{{int:watch}}" in alto de un pagina.',
	'wikiafollowedpages-special-anon' => 'Per favor [[Special:Signup|aperi un session]] pro crear e vider tu lista de paginas sub observation.',
	'oasis-wikiafollowedpages-special-seeall' => 'Vider totes >',
	'wikiafollowedpages-special-seeall' => 'Vider totes >',
	'wikiafollowedpages-special-showall' => 'Monstrar totes >',
	'wikiafollowedpages-special-showmore' => 'Monstrar plus',
	'wikiafollowedpages-special-title' => 'Paginas sub observation',
	'wikiafollowedpages-special-delete-tooltip' => 'Remover iste pagina',
	'wikiafollowedpages-special-hidden' => 'Iste {{GENDER:$1|usator|usatrice|usator}} ha optate pro absconder su lista de paginas sub observation al vista del publico.',
	'wikiafollowedpages-special-hidden-unhide' => 'Revelar iste lista.',
	'wikiafollowedpages-special-blog-by' => 'per $1',
	'wikiafollowedpages-masthead' => 'Paginas sub observation',
	'wikiafollowedpages-following' => 'Sub observation',
	'wikiafollowedpages-special-title-userbar' => 'Paginas sub observation',
	'tog-enotiffollowedpages' => 'Notificar me via e-mail quando un pagina que io observa es modificate',
	'tog-enotiffollowedminoredits' => 'Notificar me via e-mail de minor modificationes a paginas que io observa',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Optiones avantiate',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Observatorio solmente',
	'tog-hidefollowedpages' => 'Render mi listas de paginas sub observation private',
	'follow-categoryadd-summary' => 'Pagina addite a categoria',
	'follow-bloglisting-summary' => 'Articulo publicate in pagina de blog',
	'wikiafollowedpages-userpage-heading' => 'Paginas que io observa',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Celar tu lista de paginas sub observation al publico',
	'wikiafollowedpages-userpage-more' => 'Plus',
	'wikiafollowedpages-userpage-hide' => 'celar',
	'wikiafollowedpages-userpage-empty' => 'Le lista de paginas sub observation de iste usator es vacue.
Adde paginas a iste lista cliccante super "Observar" in alto de un pagina.',
	'enotif_subject_categoryadd' => 'Le pagina $PAGETITLE de {{SITENAME}} ha essite addite a $CATEGORYNAME per $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Car $WATCHINGUSERNAME,

Un pagina ha essite addite a un categoria que tu observa in {{SITENAME}}.

Vide "$PAGETITLE_URL" pro le nove pagina.

Per favor visita e modifica frequentemente...

{{SITENAME}}

___________________________________________
* Reguarda nostre wikis in evidentia! http://www.wikia.com

* Vole determinar qual e-mails tu recipe?
Visita: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Car $WATCHINGUSERNAME,
<br /><br />
Un pagina ha essite addite a un categoria que tu observa in {{SITENAME}}.
<br /><br />
Vide <a href="$PAGETITLE_URL">$PAGETITLE</a> pro le nove pagina.
<br /><br />
Per favor visita e modifica frequentemente...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Reguarda nostre wikis in evidentia!</a></li>
<li>Vole determinar qual e-mails tu recipe? Visita <a href="{{fullurl:{{ns:special}}:Preferences}}">Preferentias de usator</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Le pagina $PAGETITLE de {{SITENAME}} ha essite publicate in $BLOGLISTINGNAME per $PAGEEDITOR',
	'enotif_body_blogpost' => 'Car $WATCHINGUSERNAME,

Il ha un modification in un pagina de lista de blog que tu observa in {{SITENAME}}.

Vide "$PAGETITLE_URL" pro le nove articulo.

Per favor visita e modifica frequentemente...

{{SITENAME}}

___________________________________________
* Reguarda nostre wikis in evidentia! http://www.wikia.com

* Vole determinar qual e-mails tu recipe?
Visita: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Car $WATCHINGUSERNAME,
<br /><br />
Il ha un modification in un pagina de lista de blog que tu observa in {{SITENAME}}.
<br /><br />
Vide <a href="$PAGETITLE_URL">$PAGETITLE</a> pro le nove articulo.
<br /><br />
Per favor visita e modifica frequentemente...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Reguarda nostre wikis in evidentia!</a></li>
<li>Vole determinar qual e-mails tu recipe? Visita <a href="{{fullurl:{{ns:special}}:Preferences}}">Preferentias de usator</a></li>
</ul>
</p>',
);

/** Indonesian (Bahasa Indonesia)
 * @author Irwangatot
 */
$messages['id'] = array(
	'follow-desc' => 'Perbaharuan untuk fungsi daftar pantauan',
	'wikiafollowedpages-special-heading-category' => '($1) Kategori',
	'wikiafollowedpages-special-heading-article' => '($1) Artikel',
	'wikiafollowedpages-special-heading-blogs' => '($1) Blog dan posting',
	'wikiafollowedpages-special-heading-forum' => '($1) untaian Forum',
	'wikiafollowedpages-special-heading-project' => '($1) Halaman proyek',
	'wikiafollowedpages-special-heading-user' => '($1) Halaman pengguna',
	'wikiafollowedpages-special-heading-templates' => '($1) Halaman templat',
	'wikiafollowedpages-special-heading-mediawiki' => '($1) Halaman MediaWiki',
	'wikiafollowedpages-special-heading-media' => '($1) Berkas dan video',
	'wikiafollowedpages-special-namespace' => '($1 halaman)',
	'wikiafollowedpages-special-blog-by' => 'oleh $1',
	'wikiafollowedpages-userpage-hide' => 'sembunyikan',
);

/** Igbo (Igbo)
 * @author Ukabia
 */
$messages['ig'] = array(
	'wikiafollowedpages-special-heading-category' => 'Ébéanọr ($1)',
	'wikiafollowedpages-special-heading-project' => 'Ihü cẹdolu ($1)',
	'wikiafollowedpages-special-heading-user' => "Ihü ọ'bànifé ($1)",
	'wikiafollowedpages-special-namespace' => '(ihü $1)',
	'wikiafollowedpages-special-blog-by' => 'shí $1',
	'wikiafollowedpages-userpage-more' => 'Nà nké ozór',
	'wikiafollowedpages-userpage-hide' => 'zofù',
);

/** Japanese (日本語)
 * @author Tommy6
 * @author 青子守歌
 */
$messages['ja'] = array(
	'follow-desc' => 'ウォッチリストの機能を改善する',
	'prefs-basic' => '基本設定',
	'wikiafollowedpages-special-heading-category' => 'カテゴリ（$1件）',
	'wikiafollowedpages-special-heading-article' => '記事（$1件）',
	'wikiafollowedpages-special-heading-blogs' => 'ブログとブログの記事（$1件）',
	'wikiafollowedpages-special-heading-forum' => 'フォーラムスレッド（$1件）',
	'wikiafollowedpages-special-heading-project' => 'プロジェクトページ（$1件）',
	'wikiafollowedpages-special-heading-user' => '利用者ページ（$1件）',
	'wikiafollowedpages-special-heading-templates' => 'テンプレートページ（$1件）',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWikiメッセージページ（$1件）',
	'wikiafollowedpages-special-heading-media' => '画像と動画（$1件）',
	'wikiafollowedpages-special-namespace' => '（$1件）',
	'wikiafollowedpages-special-empty' => 'あなたのフォローページリストは空です。このリストにページを追加するには、各ページにある「{{int:watch}}」リンクをクリックしてください。',
	'wikiafollowedpages-special-anon' => '自分のフォローページリストを作成・閲覧するには、[[Special:Signup|ログイン]]してください。',
	'oasis-wikiafollowedpages-special-seeall' => '全て見る &gt;',
	'wikiafollowedpages-special-seeall' => '全て見る &gt;',
	'wikiafollowedpages-special-showall' => '全て表示 &gt;',
	'wikiafollowedpages-special-showmore' => 'さらに表示',
	'wikiafollowedpages-special-title' => 'フォローしているページ',
	'wikiafollowedpages-special-delete-tooltip' => 'このページを外す',
	'wikiafollowedpages-special-hidden' => 'このユーザーは、{{GENDER:$1|自身}}がフォローしているページのリストを公開していません。',
	'wikiafollowedpages-special-hidden-unhide' => 'このリストを公開する',
	'wikiafollowedpages-special-blog-by' => 'by $1',
	'wikiafollowedpages-masthead' => 'フォローしているページ',
	'wikiafollowedpages-following' => 'フォローしている',
	'wikiafollowedpages-special-title-userbar' => 'フォローしているページ',
	'tog-enotiffollowedpages' => 'フォローしているページが編集されたらメールで通知する',
	'tog-enotiffollowedminoredits' => '細部の編集でもメールを受け取る',
	'prefs-wikiafollowedpages-prefs-advanced' => '高度な設定',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'ウォッチリストのみ',
	'tog-hidefollowedpages' => 'フォローしているページのリストを非公開にする',
	'follow-categoryadd-summary' => 'カテゴリへのページの追加',
	'follow-bloglisting-summary' => 'ブログリストへのブログ記事の追加',
	'wikiafollowedpages-userpage-heading' => 'フォローしているページ',
	'wikiafollowedpages-userpage-hide-tooltip' => 'あなたがフォローしているページのリストを非公開にします',
	'wikiafollowedpages-userpage-more' => 'もっと詳しく',
	'wikiafollowedpages-userpage-hide' => '非公開にする',
	'wikiafollowedpages-userpage-empty' => 'このユーザーのフォローページリストは空です。リストにページを追加するには、ページのトップにある"{{int:watch}}"リンクをクリックしてください。',
	'enotif_subject_categoryadd' => '{{SITENAME}} のカテゴリ「$CATEGORYNAME」にページ「$PAGETITLE」が $PAGEEDITOR によって追加されました',
	'enotif_body_categoryadd' => '$WATCHINGUSERNAMEさん、

{{SITENAME}}のフォローしているカテゴリに新しいページが追加されました。
新しいページを見るには次のURLにアクセスしてください:
$PAGETITLE_URL

                         {{SITENAME}} 通知システム

--
設定を変更する:
{{fullurl:Special:Preferences}}',
	'enotif_body_categoryadd-html' => '<p>$WATCHINGUSERNAMEさん、<br /><br />

{{SITENAME}}のフォローしているカテゴリに新しいページが追加されました。<br />
新しいページを見るには次のURLにアクセスしてください:<br />
<a href="$PAGETITLE_URL">$PAGETITLE</a><br /><br />

                         {{SITENAME}} 通知システム<br /><br />

<hr />
ウォッチリストの設定を変更する:<br />
<a href="{{fullurl:Special:Preferences}}">{{fullurl:Special:Preferences}}</a></p>',
	'enotif_subject_blogpost' => '{{SITENAME}} のブログリスト「$BLOGLISTINGNAME」にブログ記事「$PAGETITLE」が $PAGEEDITOR によって投稿されました',
	'enotif_body_blogpost' => '$WATCHINGUSERNAMEさん、

{{SITENAME}}のフォローしているブログリストに新しい記事が追加されました。
新しい記事を見るには次のURLにアクセスしてください:
$PAGETITLE_URL

                         {{SITENAME}} 通知システム

--
ウォッチリストの設定を変更する:
{{fullurl:Special:Preferences}}',
	'enotif_body_blogpost-HTML' => '<p>$WATCHINGUSERNAMEさん、<br /><br />

{{SITENAME}}のフォローしているブログリストに新しい記事が追加されました。<br />
新しい記事を見るには次のURLにアクセスしてください:<br />
<a href="$PAGETITLE_URL">$PAGETITLE</a><br /><br />

                         {{SITENAME}} 通知システム<br /><br />

<hr />
ウォッチリストの設定を変更する:<br />
<a href="{{fullurl:Special:Preferences}}">{{fullurl:Special:Preferences}}</a></p>',
);

/** Kurdish (Latin) (Kurdî (Latin))
 * @author George Animal
 */
$messages['ku-latn'] = array(
	'wikiafollowedpages-special-heading-category' => 'Kategoriyan ($1)',
	'wikiafollowedpages-special-heading-article' => 'Gotaran ($1)',
	'wikiafollowedpages-userpage-hide' => 'veşêre',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'follow-desc' => 'Verbesserunge vun der Iwwerwaachungslëscht',
	'wikiafollowedpages-special-heading-category' => 'Kategorien ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artikelen ($1)',
	'wikiafollowedpages-special-heading-project' => 'Projetssäiten ($1)',
	'wikiafollowedpages-special-heading-user' => 'Benotzersäiten ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Schabloune-Säiten ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki-Säiten ($1)',
	'wikiafollowedpages-special-heading-media' => 'Biller a Videoen ($1)',
	'wikiafollowedpages-special-namespace' => '($1-Säit)',
	'wikiafollowedpages-special-showall' => 'All weisen >',
	'wikiafollowedpages-special-showmore' => 'Méi weisen',
	'wikiafollowedpages-special-title' => 'Iwwerwaachte Säiten',
	'wikiafollowedpages-special-delete-tooltip' => 'Dës Säit ewechhuelen',
	'wikiafollowedpages-special-hidden-unhide' => 'Dës Lëscht net méi verstoppen.',
	'wikiafollowedpages-masthead' => 'Iwwerwaachte Säiten',
	'wikiafollowedpages-special-title-userbar' => 'Iwwerwaachte Säiten',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Erweidert Optiounen',
	'follow-categoryadd-summary' => "Säit gouf bäi d'Kategorie derbäigesat",
	'wikiafollowedpages-userpage-heading' => 'Säiten, déi ech iwwerwaachen',
	'wikiafollowedpages-userpage-more' => 'Méi',
	'wikiafollowedpages-userpage-hide' => 'verstoppen',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'follow-desc' => 'Збогатени можности на списокот на набљудувања',
	'prefs-basic' => 'Основни поставки',
	'wikiafollowedpages-special-heading-category' => 'Категории ($1)',
	'wikiafollowedpages-special-heading-article' => 'Статии ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Блогови и написи ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Форумски разговори ($1)',
	'wikiafollowedpages-special-heading-project' => 'Проектни страници ($1)',
	'wikiafollowedpages-special-heading-user' => 'Кориснички страници ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Шаблонски страници ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'МедијаВики-страници ($1)',
	'wikiafollowedpages-special-heading-media' => 'Слики и видеоснимки ($1)',
	'wikiafollowedpages-special-namespace' => '($1 страница)',
	'wikiafollowedpages-special-empty' => 'Вашиот список на следени страници е празен.
Додавајте страници на списокот стисјаќи на „{{int:watch}}“ на врвот од страницата.',
	'wikiafollowedpages-special-anon' => '[[Special:Signup|Најавете се]] за да создадете или прегледате ваш список на следени страници.',
	'oasis-wikiafollowedpages-special-seeall' => 'Сите >',
	'wikiafollowedpages-special-seeall' => 'Сите >',
	'wikiafollowedpages-special-showall' => 'Сите >',
	'wikiafollowedpages-special-showmore' => 'Повеќе',
	'wikiafollowedpages-special-title' => 'Следени страници',
	'wikiafollowedpages-special-delete-tooltip' => 'Отстранување на оваа страница',
	'wikiafollowedpages-special-hidden' => 'Овој корисник решил да го скрие {{GENDER:$1|неговиот|неговиот|неговиот}} список на следени страници.',
	'wikiafollowedpages-special-hidden-unhide' => 'Прикажи го списоков.',
	'wikiafollowedpages-special-blog-by' => 'од $1',
	'wikiafollowedpages-masthead' => 'Следени страници',
	'wikiafollowedpages-following' => 'Следени',
	'wikiafollowedpages-special-title-userbar' => 'Следени страници',
	'tog-enotiffollowedpages' => 'Извести ме по е-пошта кога ќе се измени страница што ја следам',
	'tog-enotiffollowedminoredits' => 'Известувај ме по е-пошта за ситни промени во страниците што ги следам',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Напредни поставки',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Само Списокот на набљудувања',
	'tog-hidefollowedpages' => 'Скриј ги од други корисници моите списоци на следени страници',
	'follow-categoryadd-summary' => 'Страницата е додадена во категоријата',
	'follow-bloglisting-summary' => 'Блогот е објавен на страницата за блогови',
	'wikiafollowedpages-userpage-heading' => 'Страници што ги следам',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Сокривање на вашиот список на следени страници од јавноста',
	'wikiafollowedpages-userpage-more' => 'Повеќе',
	'wikiafollowedpages-userpage-hide' => 'скриј',
	'wikiafollowedpages-userpage-empty' => 'Списокот на следени страници на овој корисник е празен.
Додавајте страници на списокот со стискање на „Следи“ на врвот од страницата.',
	'enotif_subject_categoryadd' => 'Страницата $PAGETITLE на {{SITENAME}} е додадена во $CATEGORYNAME од $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Почитуван/а $WATCHINGUSERNAME,

Во категоријата што ја следите на {{SITENAME}} е додадена страница.

Погледајте ја новата страница на „$PAGETITLE_URL“.

Посетувајте нè и уредувајте често...

{{SITENAME}}

___________________________________________
* Погледајте ги одбраните викија! http://www.wikia.com

* Сакате да изберете кои пораки ги добивате по е-пошта?
Одете на: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Драг/а $WATCHINGUSERNAME,
<br /><br />
Во категоријата што ја следите на {{SITENAME}} е додадена страница.
<br /><br />
Погледајте ја новата страница на <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Посетувајте нè и уредувајте често...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Погледајте ги одбраните викија!</a></li>
<li>Сакате да изберете кои пораки ги добивате по е-пошта? Одете на <a href="{{fullurl:{{ns:special}}:Preferences}}">Корисничките нагодувања</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Страницата $PAGETITLE на {{SITENAME}} е објавена на $BLOGLISTINGNAME од $PAGEEDITOR',
	'enotif_body_blogpost' => 'Драг/а $WATCHINGUSERNAME,

На страницата со блогови што ја следите на {{SITENAME}} има ново уредување.

Погледајте го новиот запис на „$PAGETITLE_URL“.

Посетувајте нè и уредувајте често...

{{SITENAME}}

___________________________________________
* Погледајте ги одбраните викија! http://www.wikia.com

* Сакате да изберете кои пораки ги добивате по е-пошта?
Одете на: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Драг/а $WATCHINGUSERNAME,
<br /><br />
На страницата со блогови што ја следите на {{SITENAME}} има ново уредување.
<br /><br />
Погледајте го новиот запис на <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Посетувајте нè и уредувајте често...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Погледајте ги одбраните викија!</a></li>
<li>Сакате да изберете кои пораки ги добивате по е-пошта? Одете на <a href="{{fullurl:{{ns:special}}:Preferences}}">Корисничките нагодувања</a></li>
</ul>
</p>',
);

/** Malayalam (മലയാളം)
 * @author Praveenp
 */
$messages['ml'] = array(
	'prefs-basic' => 'അടിസ്ഥാന ഐച്ഛികങ്ങൾ',
	'wikiafollowedpages-special-heading-category' => 'വർഗ്ഗങ്ങൾ ($1)',
	'wikiafollowedpages-special-heading-article' => 'ലേഖനങ്ങൾ ($1)',
	'wikiafollowedpages-special-heading-project' => 'പദ്ധതി താളുകൾ ($1)',
	'wikiafollowedpages-special-heading-user' => 'ഉപയോക്തൃ താളുകൾ ($1)',
	'wikiafollowedpages-special-heading-templates' => 'ഫലകം താളുകൾ ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'മീഡിയവിക്കി താളുകൾ ($1)',
	'wikiafollowedpages-special-heading-media' => 'ചിത്രങ്ങളും വീഡിയോകളും ($1)',
	'wikiafollowedpages-special-namespace' => '($1 താൾ)',
	'oasis-wikiafollowedpages-special-seeall' => 'എല്ലാം കാണുക >',
	'wikiafollowedpages-special-seeall' => 'എല്ലാം കാണുക >',
	'wikiafollowedpages-special-showall' => 'എല്ലാം പ്രദർശിപ്പിക്കുക >',
	'wikiafollowedpages-special-showmore' => 'കൂടുതൽ പ്രദർശിപ്പിക്കുക',
	'wikiafollowedpages-special-delete-tooltip' => 'ഈ താൾ നീക്കം ചെയ്യുക',
	'wikiafollowedpages-userpage-more' => 'കൂടുതൽ',
	'wikiafollowedpages-userpage-hide' => 'മറയ്ക്കുക',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'follow-desc' => 'Peningkatan kefungsian senarai pantau',
	'prefs-basic' => 'Pilihan asas',
	'wikiafollowedpages-special-heading-category' => 'Kategori ($1)',
	'wikiafollowedpages-special-heading-article' => 'Rencana ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blog dan kiriman ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Tred forum ($1)',
	'wikiafollowedpages-special-heading-project' => 'Laman projek ($1)',
	'wikiafollowedpages-special-heading-user' => 'Laman pengguna ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Laman templat ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Laman MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Gambar dan video ($1)',
	'wikiafollowedpages-special-namespace' => '($1 laman)',
	'wikiafollowedpages-special-empty' => 'Senarai pantau anda kosong.
Senaraikan laman yang ingin dipantau dengan mengklik "{{int:watch}}" di bahagian atas laman.',
	'wikiafollowedpages-special-anon' => 'Sila [[Special:Signup|log masuk]] untuk mencipta atau melihat senarai pantau anda.',
	'oasis-wikiafollowedpages-special-seeall' => 'Lihat semua >',
	'wikiafollowedpages-special-seeall' => 'Lihat semua >',
	'wikiafollowedpages-special-showall' => 'Paparkan semua >',
	'wikiafollowedpages-special-showmore' => 'Paparkan lagi',
	'wikiafollowedpages-special-title' => 'Laman yang dipantau',
	'wikiafollowedpages-special-delete-tooltip' => 'Gugurkan laman ini',
	'wikiafollowedpages-special-hidden' => 'Pengguna ini memilih untuk menyorok senarai pantau{{GENDER:$1|nya|nya|nya}} daripada khalayak umum.',
	'wikiafollowedpages-special-hidden-unhide' => 'Dedahkan seranai ini.',
	'wikiafollowedpages-special-blog-by' => 'oleh $1',
	'wikiafollowedpages-masthead' => 'Laman yang dipantau',
	'wikiafollowedpages-following' => 'Memantau',
	'wikiafollowedpages-special-title-userbar' => 'Laman yang dipantau',
	'tog-enotiffollowedpages' => 'E-mel saya apabila berlaku perubahan pada laman yang dipantau',
	'tog-enotiffollowedminoredits' => 'E-mel saya untuk suntingan kecil dalam laman pantauan saya',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Pilihan lanjutan',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Senarai pantau sahaja',
	'tog-hidefollowedpages' => 'Privasikan senarai laman pantauan saya',
	'follow-categoryadd-summary' => 'Laman ditambahkan ke dalam kategori',
	'follow-bloglisting-summary' => 'Blog dikirim pada laman blog',
	'wikiafollowedpages-userpage-heading' => 'Laman-laman pantauan saya',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Sorokkan senarai pantau anda daripada tatapan umum',
	'wikiafollowedpages-userpage-more' => 'Lagi',
	'wikiafollowedpages-userpage-hide' => 'sorokkan',
	'wikiafollowedpages-userpage-empty' => 'Senarai pantau pengguna ini kosong.
Senaraikan laman yang ingin dipantau dengan mengklik "{{int:watch}}" di bahagian atas laman.',
	'enotif_subject_categoryadd' => 'Laman $PAGETITLE dalam $PAGETITLE telah disenaraikan dalam $CATEGORYNAME oleh $PAGEEDITOR',
	'enotif_body_categoryadd' => '$WATCHINGUSERNAME,

Satu laman telah ditambahkan dalam kategori yang anda ikuti di {{SITENAME}}.

Pergi ke "$PAGETITLE_URL" untuk melihat laman baru itu.

Sila datang selalu untuk menyunting...

{{SITENAME}}

___________________________________________
* Bacalah wiki pilihan kami! http://www.wikia.com

* Nak kawal e-mel yang anda terima?
Sila ke: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
$WATCHINGUSERNAME,
<br /><br />
Satu laman telah ditambahkan dalam kategori yang anda ikuti di {{SITENAME}}.
<br /><br />
Pergi ke <a href="$PAGETITLE_URL">$PAGETITLE_URL</a> untuk melihat laman baru itu.
<br /><br />
Sila datang selalu untuk menyunting...
<br /><br />
{{SITENAME}}
<br /><br />
<ul>
<li>  <a href="http://www.wikia.com">Bacalah wiki pilihan kami!</a></li>
<li> Nak kawal e-mel yang anda terima? Sila ke: <a href="{{fullurl:{{ns:special}}:Preferences}}">Keutamaan Pengguna</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Laman $PAGETITLE dalam $PAGETITLE telah dikirim kepada $BLOGLISTINGNAME oleh $PAGEEDITOR',
	'enotif_body_blogpost' => '$WATCHINGUSERNAME,

Terdapat suntingan pada laman senarai blog yang anda ikuti di {{SITENAME}}.

Pergi ke "$PAGETITLE_URL" untuk melihat laman baru itu.

Sila datang selalu untuk menyunting...

{{SITENAME}}

___________________________________________
* Bacalah wiki pilihan kami! http://www.wikia.com

* Nak kawal e-mel yang anda terima?
Sila ke: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
$WATCHINGUSERNAME,
<br /><br />
Terdapat suntingan pada laman senarai blog yang anda ikuti di {{SITENAME}}.
<br /><br />
Pergi ke <a href="$PAGETITLE_URL">$PAGETITLE_URL</a> untuk melihat laman baru itu.
<br /><br />
Sila datang selalu untuk menyunting...
<br /><br />
{{SITENAME}}
<br /><br />
<ul>
<li>  <a href="http://www.wikia.com">Bacalah wiki pilihan kami!</a></li>
<li> Nak kawal e-mel yang anda terima? Sila ke: <a href="{{fullurl:{{ns:special}}:Preferences}}">Keutamaan Pengguna</a></li>
</ul>
</p>',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'follow-desc' => 'Verbeteringen voor de volglijstfunctie',
	'prefs-basic' => 'Basisinstellingen',
	'wikiafollowedpages-special-heading-category' => 'Categorieën ($1)',
	'wikiafollowedpages-special-heading-article' => "Inhoudspagina's ($1)",
	'wikiafollowedpages-special-heading-blogs' => 'Blogs en blogberichten ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Forumberichten ($1)',
	'wikiafollowedpages-special-heading-project' => "Projectpagina's ($1)",
	'wikiafollowedpages-special-heading-user' => "Gebruikerspagina's ($1)",
	'wikiafollowedpages-special-heading-templates' => "Sjabloonpagina's ($1)",
	'wikiafollowedpages-special-heading-mediawiki' => "MediaWiki-pagina's ($1)",
	'wikiafollowedpages-special-heading-media' => "Afbeeldingen en video's ($1)",
	'wikiafollowedpages-special-namespace' => '($1 pagina)',
	'wikiafollowedpages-special-empty' => 'Uw volglijst is leeg.
Voeg pagina\'s toe aan deze lijst door te klikken op "{{int:watch}}" bovenaan pagina\'s.',
	'wikiafollowedpages-special-anon' => '[[Special:Signup|Meld u aan]] om uw volglijst te bewerken of te bekijken.',
	'oasis-wikiafollowedpages-special-seeall' => 'Allemaal bekijken >',
	'wikiafollowedpages-special-seeall' => 'Allemaal bekijken >',
	'wikiafollowedpages-special-showall' => 'Allemaal weergeven >',
	'wikiafollowedpages-special-showmore' => 'Meer weergeven',
	'wikiafollowedpages-special-title' => "Pagina's op volglijst",
	'wikiafollowedpages-special-delete-tooltip' => 'Deze pagina verwijderen',
	'wikiafollowedpages-special-hidden' => 'Deze gebruiker wil {{GENDER:$1|zijn|haar}} volglijst niet publiek maken.',
	'wikiafollowedpages-special-hidden-unhide' => 'Deze lijst zichtbaar maken.',
	'wikiafollowedpages-special-blog-by' => 'door $1',
	'wikiafollowedpages-masthead' => "Pagina's op volglijst",
	'wikiafollowedpages-following' => "Gevolgde pagina's",
	'wikiafollowedpages-special-title-userbar' => "Pagina's op volglijst",
	'tog-enotiffollowedpages' => 'Mij e-mailen als een pagina op mijn volglijst wijzigt',
	'tog-enotiffollowedminoredits' => 'Mij e-mailen bij kleine bewerkingen van pagina’s op mijn volglijst',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Gevorderde instellingen',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Alleen volglijst',
	'tog-hidefollowedpages' => "Pagina's op mijn volglijst niet publiek maken",
	'follow-categoryadd-summary' => 'Pagina aan een categorie toegevoegd',
	'follow-bloglisting-summary' => 'Blogbericht toegevoegd aan blogpagina',
	'wikiafollowedpages-userpage-heading' => "Pagina's op mijn volglijst",
	'wikiafollowedpages-userpage-hide-tooltip' => "Uw huidige gevolgde pagina's voor andere gebruikers verbergen",
	'wikiafollowedpages-userpage-more' => 'Meer',
	'wikiafollowedpages-userpage-hide' => 'verbergen',
	'wikiafollowedpages-userpage-empty' => 'Deze gebruiker volgt geen pagina\'s.
Voeg pagina\'s aan deze lijst toe door op "{{int:watch}}" te klikken bovenaan een pagina.',
	'enotif_subject_categoryadd' => 'Pagina $PAGETITLE is op {{SITENAME}} toegevoegd aan $CATEGORYNAME door $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Beste $WATCHINGUSERNAME,

Er is een pagina is toegevoegd aan een categorie die u volgt op {{SITENAME}}.

Zie "$PAGETITLE_URL" voor de nieuwe pagina.

Kom alstublieft vaak langs om bewerkingen te maken...

{{SITENAME}}

___________________________________________ 
* Kom kijken op onze uitgelichte wiki\'s! http://www.wikia.com 

 * Wilt u bepalen welke e-mails u ontvangt? 
Ga naar: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Beste $WATCHINGUSERNAME,
<br /><br />
Er is een pagina is toegevoegd aan een categorie die u volgt op {{SITENAME}}.
<br /><br />
Zie <a href="$PAGETITLE_URL">$PAGETITLE</a> voor de nieuwe pagina.
<br /><br />
Kom alstublieft vaak langs om bewerkingen te maken...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Kom kijken op onze uitgelichte wiki\'s</a></li>
<li>Wilt u bepalen welke e-mails u ontvangt? Ga naar uw <a href="{{fullurl:{{ns:special}}:Preferences}}">gebruikersvoorkeuren</a>.</li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Pagina $PAGETITLE op {{SITENAME}} is gemaakt op $BLOGLISTINGNAME door $PAGEEDITOR',
	'enotif_body_blogpost' => 'Beste $WATCHINGUSERNAME,

Er is een bewerking gemaakt aan een blog die u volgt op {{SITENAME}}.

Zie "$PAGETITLE_URL" voor het nieuwe blogbericht.

Kom alstublieft vaak langs om bewerkingen te maken...

{{SITENAME}}

___________________________________________ 
* Kom kijken op onze uitgelichte wiki\'s! http://www.wikia.com 

 * Wilt u bepalen welke e-mails u ontvangt?
Ga naar: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Beste $WATCHINGUSERNAME,
<br /><br />
Er is een bewerking gemaakt aan een blog die u volgt op {{SITENAME}}.
<br /><br />
Zie <a href="$PAGETITLE_URL">$PAGETITLE</a> voor het nieuwe blogbericht.
<br /><br />
Kom alstublieft vaak langs om bewerkingen te maken...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Kom kijken op onze uitgelichte wiki\'s</a></li>
<li>Wilt u bepalen welke e-mails u ontvangt? Ga naar uw <a href="{{fullurl:{{ns:special}}:Preferences}}">gebruikersvoorkeuren</a>.</li>
</ul>
</p>',
);

/** ‪Nederlands (informeel)‬ (‪Nederlands (informeel)‬)
 * @author Siebrand
 */
$messages['nl-informal'] = array(
	'wikiafollowedpages-special-empty' => 'Je volglijst is leeg.
Voeg pagina\'s toe aan deze lijst door te klikken op "{{int:watch}}" bovenaan pagina\'s.',
	'wikiafollowedpages-special-anon' => '[[Special:Signup|Meld je aan]] om je volglijst te bewerken of te bekijken.',
	'wikiafollowedpages-userpage-hide-tooltip' => "Je huidige gevolgde pagina's voor andere gebruikers verbergen",
	'enotif_body_categoryadd' => 'Hoi $WATCHINGUSERNAME,

Er is een pagina is toegevoegd aan een categorie die je volgt op {{SITENAME}}.

Zie "$PAGETITLE_URL" voor de nieuwe pagina.

Kom alsjeblieft vaak langs om bewerkingen te maken...

{{SITENAME}}

___________________________________________ 
* Kom kijken op onze uitgelichte wiki\'s! http://www.wikia.com 

 * Wil je bepalen welke e-mails je ontvangt? 
Ga naar: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>Hoi $WATCHINGUSERNAME,
<br /><br />
Er is een pagina is toegevoegd aan een categorie die je volgt op {{SITENAME}}.
<br /><br />
Zie <a href="$PAGETITLE_URL">$PAGETITLE</a> voor de nieuwe pagina.
<br /><br />
Kom alsjeblieft vaak langs om bewerkingen te maken...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Kom kijken op onze uitgelichte wiki\'s</a></li>
<li>Wil je bepalen welke e-mails je ontvangt? Ga naar je <a href="{{fullurl:{{ns:special}}:Preferences}}">gebruikersvoorkeuren</a>.</li>
</ul>
</p>',
	'enotif_body_blogpost' => 'Hoi $WATCHINGUSERNAME,

Er is een bewerking gemaakt aan een blog die je volgt op {{SITENAME}}.

Zie "$PAGETITLE_URL" voor het nieuwe blogbericht.

Kom alsjeblieft vaak langs om bewerkingen te maken...

{{SITENAME}}

___________________________________________ 
* Kom kijken op onze uitgelichte wiki\'s! http://www.wikia.com 

 * Wil je bepalen welke e-mails je ontvangt?
Ga naar: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>Hoi $WATCHINGUSERNAME,
<br /><br />
Er is een bewerking gemaakt aan een blog die je volgt op {{SITENAME}}.
<br /><br />
Zie <a href="$PAGETITLE_URL">$PAGETITLE</a> voor het nieuwe blogbericht.
<br /><br />
Kom alsjeblieft vaak langs om bewerkingen te maken...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Kom kijken op onze uitgelichte wiki\'s</a></li>
<li>Wil je bepalen welke e-mails je ontvangt? Ga naar je <a href="{{fullurl:{{ns:special}}:Preferences}}">gebruikersvoorkeuren</a>.</li>
</ul>
</p>',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Audun
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'follow-desc' => 'Forbedringer for overvåkningslistens funksjonalitet',
	'prefs-basic' => 'Grunnleggende valg',
	'wikiafollowedpages-special-heading-category' => 'Kategorier ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artikler ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogger og innlegg ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Forumtråder ($1)',
	'wikiafollowedpages-special-heading-project' => 'Prosjektsider ($1)',
	'wikiafollowedpages-special-heading-user' => 'Brukersider ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Maler ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki-sider ($1)',
	'wikiafollowedpages-special-heading-media' => 'Bilder og videoer ($1)',
	'wikiafollowedpages-special-namespace' => '($1-side)',
	'wikiafollowedpages-special-empty' => 'Listen din over fulgte sider er tom.
Legg til sider i listen ved å trykke «{{int:watch}}» øverst på siden.',
	'wikiafollowedpages-special-anon' => 'Vennligst [[Special:Signup|logg inn]] for å opprette eller vise din liste over fulgte sider.',
	'oasis-wikiafollowedpages-special-seeall' => 'Se alle >',
	'wikiafollowedpages-special-seeall' => 'Se alle >',
	'wikiafollowedpages-special-showall' => 'Vis alle >',
	'wikiafollowedpages-special-showmore' => 'Vis mer',
	'wikiafollowedpages-special-title' => 'Fulgte sider',
	'wikiafollowedpages-special-delete-tooltip' => 'Fjern denne siden',
	'wikiafollowedpages-special-hidden' => 'Denne brukeren har valgt å skjule {{GENDER:$1|hans|hennes|deres}} liste over fulgte sider for offentlig visning.',
	'wikiafollowedpages-special-hidden-unhide' => 'Vis denne listen.',
	'wikiafollowedpages-special-blog-by' => 'av $1',
	'wikiafollowedpages-masthead' => 'Fulgte sider',
	'wikiafollowedpages-following' => 'Følger',
	'wikiafollowedpages-special-title-userbar' => 'Fulgte sider',
	'tog-enotiffollowedpages' => 'Send meg en e-post når en side jeg følger blir redigert',
	'tog-enotiffollowedminoredits' => 'Send meg en e-post for mindre endringer på sider jeg følger',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Avanserte valg',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Kun overvåkningsliste',
	'tog-hidefollowedpages' => 'Gjør min liste over fulgte sider privat',
	'follow-categoryadd-summary' => 'Side lagt til kategori',
	'follow-bloglisting-summary' => 'Blogg lagt ut på bloggsiden',
	'wikiafollowedpages-userpage-heading' => 'Sider jeg følger',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Skjul dine fulgte sider-lister fra offentlig innsyn',
	'wikiafollowedpages-userpage-more' => 'Mer',
	'wikiafollowedpages-userpage-hide' => 'skjul',
	'wikiafollowedpages-userpage-empty' => 'Denne brukerens liste over fulgte sider er tom.
Legg til sider i listen ved å trykke «Følg» øverst på siden.',
	'enotif_subject_categoryadd' => '{{SITENAME}}-siden $PAGETITLE har blitt lagt til $CATEGORY av $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Kjære $WATCHINGUSERNAME,

En side har blitt lagt til i en kategori du følger på {{SITENAME}}.

Se «$PAGETITLE_URL» for den nye siden.

Vennligst kom på besøk og rediger ofte...

{{SITENAME}}

___________________________________________
* Sjekk ut våre utvalgte wikier! http://www.wikia.com

* Vil du kontrollere hva slags e-post du får?
Gå til: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Kjære $WATCHINGUSERNAME,
<br /><br />
En side har blitt lagt til i en kategori du følger på {{SITENAME}}.
<br /><br />
Se <a href="$PAGETITLE_URL">$PAGETITLE</a> for den nye siden.
<br /><br />
Vennligst kom på besøk og rediger ofte...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Sjekk ut våre utvalgte wikier!</a></li>
<li>Vil du kontrollere hva slags e-post du får? Gå til <a href="{{fullurl:{{ns:special}}:Preferences}}">Brukerinnstillinger</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => '{{SITENAME}}-siden $PAGETITLE har blitt postet i $BLOGLISTINGNAME av $PAGEEDITOR',
	'enotif_body_blogpost' => 'Kjære $WATCHINGUSERNAME,

En bloggoppføring du følger på {{SITENAME}} har blitt redigert.

Se «$PAGETITLE_URL» for det nye innlegget.

Vennligst kom på besøk og rediger ofte...

{{SITENAME}}

___________________________________________
* Sjekk ut våre utvalgte wikier! http://www.wikia.com

* Vil du kontrollere hva slags e-post du får?
Gå til: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Kjære $WATCHINGUSERNAME,
<br /><br />
En bloggoppføring du følger på {{SITENAME}} har blitt redigert.
<br /><br />
Se <a href="$PAGETITLE_URL">$PAGETITLE</a> for det nye innlegget.
<br /><br />
Vennligst kom på besøk og rediger ofte...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Sjekk ut våre utvalgte wikier!</a></li>
<li>Vil du kontrollere hva slags e-post du får? Gå til <a href="{{fullurl:{{ns:special}}:Preferences}}">Brukerinnstillinger</a></li>
</ul>
</p>',
);

/** Polish (Polski) */
$messages['pl'] = array(
	'wikiafollowedpages-special-heading-category' => 'Kategorie ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artykuły ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogi i posty ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Wątki na forum ($1)',
	'wikiafollowedpages-special-heading-project' => 'Strony projektu ($1)',
	'wikiafollowedpages-special-heading-user' => 'Strony użytkownika ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Szablony ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Strony MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Grafiki i filmy ($1)',
	'wikiafollowedpages-special-empty' => 'Lista stron obserwowanych przez tego użytkownika jest pusta.
Dodaj strony do tej listy klikając "{{int:watch}}" na górze strony.',
	'wikiafollowedpages-special-anon' => 'Proszę, [[Special:Signup|zaloguj się]] żeby stworzyć lub przeglądać swoją listę obserwowanych stron.',
	'oasis-wikiafollowedpages-special-seeall' => 'Pokaż wszystkie >',
	'wikiafollowedpages-special-seeall' => 'Pokaż wszystkie >',
	'wikiafollowedpages-special-showall' => 'Pokaż wszystkie >',
	'wikiafollowedpages-special-showmore' => 'Pokaż więcej',
	'wikiafollowedpages-special-title' => 'Obserwowane strony',
	'wikiafollowedpages-special-delete-tooltip' => 'Usuń tę stronę',
	'wikiafollowedpages-special-hidden' => 'Ten użytkownik zdecydował się ukryć {{GENDER:$1|swoją|swoją|ich}} listę stron obserwowanych przed widokiem publicznym.',
	'wikiafollowedpages-special-hidden-unhide' => 'Odkryj tę listę.',
	'wikiafollowedpages-special-blog-by' => 'przez $1',
	'wikiafollowedpages-masthead' => 'Obserwowane strony',
	'wikiafollowedpages-following' => 'Obserwowane',
	'wikiafollowedpages-special-title-userbar' => 'Obserwowane strony',
	'tog-enotiffollowedpages' => 'Wyślij do mnie e‐mail, jeśli strona z listy moich obserwowanych zostanie zmodyfikowana',
	'tog-enotiffollowedminoredits' => 'Wyślij do mnie e‐mail, w przypadku drobnych zmian na stronach z mojej listy obserwowanych',
	'wikiafollowedpages-userpage-heading' => 'Strony, które obserwuję',
	'wikiafollowedpages-userpage-more' => 'Więcej',
	'wikiafollowedpages-userpage-hide' => 'ukryj',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 */
$messages['pms'] = array(
	'follow-desc' => "Ameliorament për la funsionalità ëd lòn ch'as ten sot euj",
	'prefs-basic' => 'Opsion base',
	'wikiafollowedpages-special-heading-category' => 'Categorìe ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artìcoj ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Scartari e artìcoj ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Soget da piassa ëd discussion ($1)',
	'wikiafollowedpages-special-heading-project' => 'Pàgine ëd proget ($1)',
	'wikiafollowedpages-special-heading-user' => 'Pàgine utent ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Pàgine dë stamp ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Pàgine ëd MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Figure e filmà ($1)',
	'wikiafollowedpages-special-namespace' => '(Pàgina $1)',
	'wikiafollowedpages-special-empty' => 'La lista dle toe pàgine tnùe sot euj a l\'é veuida.
Ch\'a gionta dle pàgine a costa lista an sgnacand "{{int:watch}}" an cò dla pàgina.',
	'wikiafollowedpages-special-anon' => "Për piasì ch'a [[Special:Signup|intra ant ël sistema]] për creé o vardé soa lista dle pàgine tnùe sot euj.",
	'oasis-wikiafollowedpages-special-seeall' => 'Varda tut >',
	'wikiafollowedpages-special-seeall' => 'Varda tut >',
	'wikiafollowedpages-special-showall' => 'Smon-e tut >',
	'wikiafollowedpages-special-showmore' => 'Smon-e ëd pì',
	'wikiafollowedpages-special-title' => 'Pàgine tnùe sot euj',
	'wikiafollowedpages-special-delete-tooltip' => 'Gava sta pàgina-sì',
	'wikiafollowedpages-special-hidden' => "St'utent-sì a l'ha sernù dë stërmé {{GENDER:$1|soa|soa|soa}} lista dle pàgine ch'a ten sot euj da la vista pùblica.",
	'wikiafollowedpages-special-hidden-unhide' => 'Dëscoata sta lista-sì.',
	'wikiafollowedpages-special-blog-by' => 'da $1',
	'wikiafollowedpages-masthead' => 'Pàgine tnùe sot euj',
	'wikiafollowedpages-following' => 'Ròba tnùa sot euj',
	'wikiafollowedpages-special-title-userbar' => 'Pàgine tnùe sot euj',
	'tog-enotiffollowedpages' => "Mandeme un mëssagi an pòsta eletrònica quand che na pàgina ch'im ten-o sot euj a l'é modìficà",
	'tog-enotiffollowedminoredits' => "Mandeme un mëssagi an pòsta eletrònica për dle modìfiche cite a le pàgine ch'im ten-o sot euj",
	'prefs-wikiafollowedpages-prefs-advanced' => 'Opsion avansà',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Mach lista tnàa sot euj',
	'tog-hidefollowedpages' => "Rende privà le liste dle pàgine ch'im ten-o sot euj",
	'follow-categoryadd-summary' => 'Pàgine giontà a la categorìa',
	'follow-bloglisting-summary' => 'Scartari scrivù an sla pàgina djë scartari',
	'wikiafollowedpages-userpage-heading' => "Pàgine ch'im ten-o sot euj",
	'wikiafollowedpages-userpage-hide-tooltip' => 'Stërma toe liste dle pàgine tnùe sot euj da la vista pùblica',
	'wikiafollowedpages-userpage-more' => 'Pi',
	'wikiafollowedpages-userpage-hide' => 'stërma',
	'wikiafollowedpages-userpage-empty' => 'La lista dle pàgine tnùe sot euj ëd cost utent-sì a l\'é veuida.
Ch\'a gionta dle pàgine a costa lista an sgnacand "{{int:watch}}" an cò dla pàgina.',
	'enotif_subject_categoryadd' => 'La pàgina $PAGETITLE ëd {{SITENAME}} a l\'é staita giontà a $CATEGORYNAME da $PAGEEDITOR',
	'enotif_body_categoryadd' => "Car \$WATCHINGUSERNAME,

Na pàgina a l'é stàita giontà a na categorìa ch'a ten sot euj su {{SITENAME}}.

Ch'a vëdda \"\$PAGETITLE_URL\" për la pàgina neuva.

Për piasì, ch'a vìsita e modìfica ëd soens...

{{SITENAME}}

___________________________________________
* Ch'a contròla nòste bele wiki! http://www.wikia.com

* Veul-lo controlé che mëssagi a arsèiv?
Ch'a vada a: {{fullurl:{{ns:special}}:Preferences}}.",
	'enotif_body_categoryadd-html' => '<p>
Car $WATCHINGUSERNAME,
<br /><br />
Na pàgina a l\'é stàita giontà a na categorìa ch\'a ten sot euj su {{SITENAME}}.
<br /><br />
Ch\'a daga n\'ociada a <a href="$PAGETITLE_URL">$PAGETITLE</a> për la pàgina neuva.
<br /><br />
Për piasì, ch\'a vìsita e modìfica ëd soens...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Contròla nòste bele wiki!</a></li>
<li>Veul-lo controlé che mëssagi a arsèive? Ch\'a vada a <a href="{{fullurl:{{ns:special}}:Preferences}}">Gust ëd l\'utent</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'La pàgina $PAGETITLE ëd {{SITENAME}} a l\'é stàita mandà a $BLOGLISTINGNAME da $PAGEEDITOR',
	'enotif_body_blogpost' => "Car \$WATCHINGUSERNAME,

A l'é staje na modìfica a na pàgina ëd na lista dë scartari ch'a ten-e sot euj su {{SITENAME}}.

Ch'a bèica \"\$PAGETITLE_URL\" për la pàgina neuva.

Për piasì, ch'a vìsita e a modìfica ëd soens...

{{SITENAME}}

___________________________________________
* Ch'a contròla nòste bele wiki! http://www.wikia.com

* Veul-lo controlé che mëssagi a arsèiv?
Ch'a vada a: {{fullurl:{{ns:special}}:Preferences}}.",
	'enotif_body_blogpost-HTML' => '<p>
Car $WATCHINGUSERNAME,
<br /><br />
A l\'é staje na modìfica a na pàgina ëd na lista dë scartari ch\'a ten sot euj su {{SITENAME}}.
<br /><br />
Ch\'a bèica <a href="$PAGETITLE_URL">$PAGETITLE</a> për la pàgina neuva.
<br /><br />
Për piasì, ch\'a vìsita e a modìfica ëd soens...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Ch\'a contròla nòste bele wiki!</a></li>
<li>Veul-lo controlé che mëssagi a arsèiv? Ch\'a vada a <a href="{{fullurl:{{ns:special}}:Preferences}}">Gust ëd l\'utent</a></li>
</ul>
</p>',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'wikiafollowedpages-special-heading-category' => 'وېشنيزې ($1)',
	'wikiafollowedpages-special-heading-article' => 'ليکنې ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'بلاګونه او ليکنې ($1)',
	'wikiafollowedpages-special-heading-project' => 'د پروژې مخونه ($1)',
	'wikiafollowedpages-special-heading-user' => 'د کارن مخونه ($1)',
	'wikiafollowedpages-special-heading-templates' => 'د کينډۍ مخونه ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'د مېډياويکي مخونه ($1)',
	'wikiafollowedpages-special-heading-media' => 'انځورونه او ويډيوګانې ($1)',
	'wikiafollowedpages-special-namespace' => '($1 مخ)',
	'oasis-wikiafollowedpages-special-seeall' => 'ټول کتل >',
	'wikiafollowedpages-special-seeall' => 'ټول کتل >',
	'wikiafollowedpages-special-showall' => 'ټول ښکاره کول >',
	'wikiafollowedpages-special-showmore' => 'نور ښکاره کول',
	'wikiafollowedpages-special-title' => 'څارلي مخونه',
	'wikiafollowedpages-masthead' => 'څارلي مخونه',
	'wikiafollowedpages-following' => 'د څار لاندې',
	'wikiafollowedpages-special-title-userbar' => 'څارلي مخونه',
	'wikiafollowedpages-userpage-heading' => 'هغه مخونه چې زه يې څارم',
	'wikiafollowedpages-userpage-more' => 'نور',
	'wikiafollowedpages-userpage-hide' => 'پټول',
);

/** Portuguese (Português)
 * @author Giro720
 * @author Hamilton Abreu
 * @author Luckas Blade
 */
$messages['pt'] = array(
	'follow-desc' => 'Melhorias da funcionalidade de páginas vigiadas',
	'prefs-basic' => 'Opções básicas',
	'wikiafollowedpages-special-heading-category' => 'Categorias ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artigos ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogues e publicações ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Tópicos de fóruns ($1)',
	'wikiafollowedpages-special-heading-project' => 'Páginas de projecto ($1)',
	'wikiafollowedpages-special-heading-user' => 'Páginas de utilizadores ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Páginas de predefinições ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Páginas MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Imagens e vídeos ($1)',
	'wikiafollowedpages-special-namespace' => '(página $1)',
	'wikiafollowedpages-special-empty' => 'A sua lista de páginas seguidas está vazia.
Adicione páginas à lista clicando "{{int:watch}}" no topo de uma página.',
	'wikiafollowedpages-special-anon' => '[[Special:Signup|Autentique-se]] para criar ou ver a sua lista de páginas seguidas, por favor.',
	'oasis-wikiafollowedpages-special-seeall' => 'Ver todas >',
	'wikiafollowedpages-special-seeall' => 'Ver todas >',
	'wikiafollowedpages-special-showall' => 'Mostrar todas >',
	'wikiafollowedpages-special-showmore' => 'Mostrar mais',
	'wikiafollowedpages-special-title' => 'Páginas seguidas',
	'wikiafollowedpages-special-delete-tooltip' => 'Remover esta página',
	'wikiafollowedpages-special-hidden' => '{{GENDER:$1|Este utilizador|Esta utilizadora|Este utilizador}} escondeu a sua lista de páginas seguidas do visionamento público.',
	'wikiafollowedpages-special-hidden-unhide' => 'Deixar de esconder esta lista.',
	'wikiafollowedpages-special-blog-by' => 'por $1',
	'wikiafollowedpages-masthead' => 'Páginas seguidas',
	'wikiafollowedpages-following' => 'A seguir',
	'wikiafollowedpages-special-title-userbar' => 'Páginas seguidas',
	'tog-enotiffollowedpages' => 'Notificar-me por correio electrónico quando uma página seguida for alterada',
	'tog-enotiffollowedminoredits' => 'Notificar-me por correio electrónico quando uma página seguida sofrer uma edição menor',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Opções avançadas',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Só a lista de páginas vigiadas',
	'tog-hidefollowedpages' => 'Tornar privada a minha lista de páginas seguidas',
	'follow-categoryadd-summary' => 'Página adicionada à categoria',
	'follow-bloglisting-summary' => 'Mensagem publicada numa página de blogue',
	'wikiafollowedpages-userpage-heading' => 'Páginas que estou a seguir',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Esconder a sua lista de páginas seguidas do visionamento público',
	'wikiafollowedpages-userpage-more' => 'Mais',
	'wikiafollowedpages-userpage-hide' => 'esconder',
	'wikiafollowedpages-userpage-empty' => 'A lista de páginas seguidas por este utilizador está vazia.
Adicione páginas à lista clicando "{{int:watch}}" no topo de uma página.',
	'enotif_subject_categoryadd' => 'Página $PAGETITLE do site {{SITENAME}} adicionada à categoria $CATEGORYNAME por $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Caro(a) $WATCHINGUSERNAME,

Foi adicionada uma página a uma categoria que está a seguir no site {{SITENAME}}.

A página nova é "$PAGETITLE_URL".

Visite-nos sempre e edite muito...

{{SITENAME}}

___________________________________________
* Visite as wikis em destaque! http://www.wikia.com

* Quer definir que notificações deseja receber?
Visite: {{fullurl:{{ns:special}}:Preferências}}.',
	'enotif_body_categoryadd-html' => '<p>
Caro(a) $WATCHINGUSERNAME,
<br /><br />
Foi adicionada uma página a uma categoria que está a seguir no site {{SITENAME}}.
<br /><br />
A página nova é <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Visite-nos sempre e edite muito...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Visite as wikis em destaque!</a></li>
<li>Quer definir que notificações deseja receber? Visite <a href="{{fullurl:{{ns:special}}:Preferences}}">Preferências do utilizador</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'A página $PAGETITLE da {{SITENAME}} foi publicada no blogue $BLOGLISTINGNAME por $PAGEEDITOR',
	'enotif_body_blogpost' => 'Caro(a) $WATCHINGUSERNAME,

Foi editada uma página de listagem de blogues que está a seguir no site {{SITENAME}}.

A mensagem nova é "$PAGETITLE_URL".

Visite-nos sempre e edite muito...

{{SITENAME}}

___________________________________________
* Visite as wikis em destaque! http://www.wikia.com

* Quer definir que notificações deseja receber?
Visite: {{fullurl:{{ns:special}}:Preferências}}.',
	'enotif_body_blogpost-HTML' => '<p>
Caro(a) $WATCHINGUSERNAME,
<br /><br />
Foi editada uma página de listagem de blogues que está a seguir no site {{SITENAME}}.
<br /><br />
A mensagem nova é <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Visite-nos sempre e edite muito...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Visite as wikis em destaque!</a></li>
<li>Quer definir que notificações deseja receber? Visite <a href="{{fullurl:{{ns:special}}:Preferences}}">Preferências do utilizador</a></li>
</ul>
</p>',
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Aristóbulo
 * @author Luckas Blade
 */
$messages['pt-br'] = array(
	'follow-desc' => 'Melhorias da funcionalidade de páginas vigiadas',
	'prefs-basic' => 'Opções básicas',
	'wikiafollowedpages-special-heading-category' => 'Categorias ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artigos ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Blogues e publicações ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Tópicos de fóruns ($1)',
	'wikiafollowedpages-special-heading-project' => 'Páginas de projeto ($1)',
	'wikiafollowedpages-special-heading-user' => 'Páginas de usuário ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Páginas de predefinições ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Páginas MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Imagens e vídeos ($1)',
	'wikiafollowedpages-special-namespace' => '(página $1)',
	'wikiafollowedpages-special-empty' => 'A lista de páginas seguidas por este utilizador está vazia.
Adicione páginas à lista clicando "{{int:watch}}" no topo de uma página.',
	'wikiafollowedpages-special-anon' => '[[Special:Signup|Autentique-se]] para criar ou ver a sua lista de páginas seguidas, por favor.',
	'oasis-wikiafollowedpages-special-seeall' => 'Ver todas >',
	'wikiafollowedpages-special-seeall' => 'Ver todas >',
	'wikiafollowedpages-special-showall' => 'Mostrar todas >',
	'wikiafollowedpages-special-showmore' => 'Mostrar mais',
	'wikiafollowedpages-special-title' => 'Páginas seguidas',
	'wikiafollowedpages-special-delete-tooltip' => 'Remover esta página',
	'wikiafollowedpages-special-hidden' => '{{GENDER:$1|Este utilizador|Esta utilizadora|Este utilizador}} escondeu a sua lista de páginas seguidas do visionamento público.',
	'wikiafollowedpages-special-hidden-unhide' => 'Deixar de esconder esta lista.',
	'wikiafollowedpages-special-blog-by' => 'por $1',
	'wikiafollowedpages-masthead' => 'Páginas seguidas',
	'wikiafollowedpages-following' => 'A seguir',
	'wikiafollowedpages-special-title-userbar' => 'Páginas seguidas',
	'tog-enotiffollowedpages' => 'Notificar-me por e-mail quando uma página seguida for alterada',
	'tog-enotiffollowedminoredits' => 'Notificar-me por e-mail quando uma página seguida sofrer uma edição menor',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Opções avançadas',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Só a lista de páginas vigiadas',
	'tog-hidefollowedpages' => 'Tornar privada a minha lista de páginas seguidas',
	'follow-categoryadd-summary' => 'Página adicionada à categoria',
	'follow-bloglisting-summary' => 'Mensagem publicada numa página de blogue',
	'wikiafollowedpages-userpage-heading' => 'Páginas que estou a seguir',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Esconder a sua lista de páginas seguidas do visionamento público',
	'wikiafollowedpages-userpage-more' => 'Mais',
	'wikiafollowedpages-userpage-hide' => 'esconder',
	'wikiafollowedpages-userpage-empty' => 'A lista de páginas seguidas por este utilizador está vazia.
Adicione páginas à lista clicando "{{int:watch}}" no topo de uma página.',
	'enotif_subject_categoryadd' => 'Página $PAGETITLE do site {{SITENAME}} adicionada à categoria $CATEGORYNAME por $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Caro(a) $WATCHINGUSERNAME,

Foi adicionada uma página a uma categoria que está a seguir no site {{SITENAME}}.

A página nova é "$PAGETITLE_URL".

Visite-nos sempre e edite muito...

{{SITENAME}}

___________________________________________
* Visite as wikis em destaque! http://www.wikia.com

* Quer definir que notificações deseja receber?
Visite: {{fullurl:{{ns:special}}:Preferências}}.',
	'enotif_body_categoryadd-html' => '<p>
Caro(a) $WATCHINGUSERNAME,
<br /><br />
Foi adicionada uma página a uma categoria que está a seguir no site {{SITENAME}}.
<br /><br />
A página nova é <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Visite-nos sempre e edite muito...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Visite as wikis em destaque!</a></li>
<li>Quer definir que notificações deseja receber? Visite <a href="{{fullurl:{{ns:special}}:Preferences}}">Preferências do utilizador</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'A página $PAGETITLE da {{SITENAME}} foi publicada no blogue $BLOGLISTINGNAME por $PAGEEDITOR',
	'enotif_body_blogpost' => 'Caro(a) $WATCHINGUSERNAME,

Foi editada uma página de listagem de blogues que está a seguir no site {{SITENAME}}.

A mensagem nova é "$PAGETITLE_URL".

Visite-nos sempre e edite muito...

{{SITENAME}}

___________________________________________
* Visite as wikis em destaque! http://www.wikia.com

* Quer definir que notificações deseja receber?
Visite: {{fullurl:{{ns:special}}:Preferências}}.',
	'enotif_body_blogpost-HTML' => '<p>
Caro(a) $WATCHINGUSERNAME,
<br /><br />
Foi editada uma página de listagem de blogues que está a seguir no site {{SITENAME}}.
<br /><br />
A mensagem nova é <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Visite-nos sempre e edite muito...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Visite as wikis em destaque!</a></li>
<li>Quer definir que notificações deseja receber? Visite <a href="{{fullurl:{{ns:special}}:Preferences}}">Preferências do utilizador</a></li>
</ul>
</p>',
);

/** Romanian (Română)
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'prefs-basic' => 'Opţiuni de bază',
	'wikiafollowedpages-special-heading-category' => 'Categorii ($1)',
	'wikiafollowedpages-special-heading-article' => 'Articole ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Bloguri şi postări ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Fire de discuţii pe forum ($1)',
	'wikiafollowedpages-special-heading-project' => 'Pagini de proiect ($1)',
	'wikiafollowedpages-special-heading-user' => 'Pagini de utilizatori ($1)',
	'wikiafollowedpages-userpage-more' => 'Mai multe',
	'wikiafollowedpages-userpage-hide' => 'ascunde',
);

/** Russian (Русский)
 * @author Eleferen
 * @author G0rn
 * @author Александр Сигачёв
 */
$messages['ru'] = array(
	'follow-desc' => 'Улучшения для функциональности списка наблюдения',
	'wikiafollowedpages-special-heading-category' => 'Категории ($1)',
	'wikiafollowedpages-special-heading-article' => 'Статьи ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Блоги и сообщения ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Темы форумов ($1)',
	'wikiafollowedpages-special-heading-project' => 'Страницы проектов ($1)',
	'wikiafollowedpages-special-heading-user' => 'Страницы участников ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Шаблоны ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Страницы MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Изображения и видео ($1)',
	'wikiafollowedpages-special-namespace' => '($1 cтраница)',
	'wikiafollowedpages-special-empty' => 'Список отслеживаемых этим пользователем статей пуст.
Для добавления страниц в этот список нажмите «{{int:watch}}» наверху этой страницы.',
	'wikiafollowedpages-special-anon' => 'Пожалуйста, [[Special:Signup|представьтесь]] для создания или просмотра своего списка отслеживаемых страниц.',
	'oasis-wikiafollowedpages-special-seeall' => 'Показать все >',
	'wikiafollowedpages-special-seeall' => 'Показать все >',
	'wikiafollowedpages-special-showall' => 'Показать всё >',
	'wikiafollowedpages-special-showmore' => 'Показать ещё',
	'wikiafollowedpages-special-title' => 'Отслеживаемые страницы',
	'wikiafollowedpages-special-delete-tooltip' => 'Удалить эту страницу',
	'wikiafollowedpages-special-hidden' => '{{GENDER:$1|Это участник предпочёл|Эта участница предпочла}} скрыть свой список отслеживаемых страниц от публичного просмотра.',
	'wikiafollowedpages-special-hidden-unhide' => 'Показать этот список.',
	'wikiafollowedpages-special-blog-by' => 'от $1',
	'wikiafollowedpages-masthead' => 'Отслеживаемые страницы',
	'wikiafollowedpages-following' => 'Отслеживание',
	'wikiafollowedpages-special-title-userbar' => 'Отслеживаемые страницы',
	'tog-enotiffollowedpages' => 'Уведомлять по эл. почте об изменениях страниц, которые я отслеживаю',
	'tog-enotiffollowedminoredits' => 'Уведомлять меня по эл. почте о малых правках в страницах, которые я отслеживаю',
	'tog-hidefollowedpages' => 'Спрятать мой список отслеживаемых страниц от публичного просмотра',
	'follow-categoryadd-summary' => 'Страница добавлена в категорию',
	'follow-bloglisting-summary' => 'Блог опубликован на странице блога',
	'wikiafollowedpages-userpage-heading' => 'Страницы, которые я отслеживаю',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Убрать ваши списки отслеживаемых страниц из общего доступа',
	'wikiafollowedpages-userpage-more' => 'Ещё',
	'wikiafollowedpages-userpage-hide' => 'скрыть',
	'wikiafollowedpages-userpage-empty' => 'Список отслеживаемых этим пользователем статей пуст.
Для добавления страниц в этот список нажмите «{{int:watch}}» наверху этой страницы.',
	'enotif_subject_categoryadd' => 'Страница проекта «{{SITENAME}}» $PAGETITLE была добавлена в категорию $CATEGORYNAME участником $PAGEEDITOR',
	'enotif_body_categoryadd' => 'Уважаемый $WATCHINGUSERNAME,
страница была добавлена в категорию, которую Вы отслеживаете в проекте «{{SITENAME}}».

Ознакомьтесь с новой страницей по адресу: $PAGETITLE_URL

Пожалуйста, посещайте и редактируйте часто…

{{SITENAME}}

___________________________________________ 
* Посмотрите наши избранные вики! http://wikia.com

* Хотите изменить параметры уведомления по электронной почте?
Пройдите по ссылке: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>Уважаемый $WATCHINGUSERNAME,
<br /><br />
страница была добавлена в категорию, которую Вы отслеживаете в проекте «{{SITENAME}}».
<br /><br />
Ознакомьтесь с новой страницей по адресу: <a href="$PAGETITLE_URL">$PAGETITLE</a>
<br /><br />
Пожалуйста, посещайте и редактируйте часто…
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Посмотрите наши избранные вики!</a>
<li>Хотите изменить параметры уведомления по электронной почте?
Пройдите в <a href="{{fullurl:{{ns:special}}:Preferences}}">настройки участника</a>.
</ul>
</p>',
	'enotif_subject_blogpost' => 'Страница $PAGETITLE проекта «{{SITENAME}}» была размещена в $BLOGLISTINGNAME участником $PAGEEDITOR',
	'enotif_body_blogpost' => 'Уважаемый $WATCHINGUSERNAME,
В проекте «{{SITENAME}}» была совершена правка на странице списка блогов, которую вы отслеживаете.

Ознакомьтесь с изменением по адресу: $PAGETITLE_URL

Пожалуйста, посещайте и редактируйте часто…

{{SITENAME}}

___________________________________________
* Посмотрите наши избранные вики! http://wikia.com

* Хотите изменить параметры уведомления по электронной почте?
Пройдите по ссылке: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Уважаемый $WATCHINGUSERNAME,
<br /><br />
В проекте «{{SITENAME}}» была совершена правка на странице списка блогов, которую вы отслеживаете.
<br /><br />
Ознакомьтесь с изменением по адресу: <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Пожалуйста, посещайте и редактируйте часто…
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Посмотрите наши избранные вики!</a>
<li>Хотите изменить параметры уведомления по электронной почте?
Пройдите в <a href="{{fullurl:{{ns:special}}:Preferences}}">настройки участника</a>.
</ul>
</p>',
);

/** Serbian Cyrillic ekavian (‪Српски (ћирилица)‬)
 * @author Rancher
 */
$messages['sr-ec'] = array(
	'prefs-basic' => 'Основне поставке',
	'wikiafollowedpages-special-heading-category' => 'Категорије ($1)',
	'wikiafollowedpages-special-heading-article' => 'Чланци ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Блогови и поруке ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Теме форума ($1)',
	'wikiafollowedpages-special-heading-project' => 'Странице пројекта ($1)',
	'wikiafollowedpages-special-heading-user' => 'Корисничке странице ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Странице шаблона ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Медијавики странице ($1)',
	'wikiafollowedpages-special-heading-media' => 'Слике и видео записи ($1)',
	'wikiafollowedpages-special-namespace' => '($1 страна)',
	'oasis-wikiafollowedpages-special-seeall' => 'Прикажи све >',
	'wikiafollowedpages-special-seeall' => 'Прикажи све >',
	'wikiafollowedpages-special-showall' => 'Прикажи све >',
	'wikiafollowedpages-special-showmore' => 'Прикажи више',
	'wikiafollowedpages-special-title' => 'Надгледане странице',
	'wikiafollowedpages-special-delete-tooltip' => 'Уклони ову страницу',
	'wikiafollowedpages-special-hidden-unhide' => 'Откриј овај списак.',
	'wikiafollowedpages-special-blog-by' => 'од $1',
	'wikiafollowedpages-masthead' => 'Праћене странице',
	'wikiafollowedpages-following' => 'Праћење',
	'wikiafollowedpages-special-title-userbar' => 'Праћене странице',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Напредне поставке',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Само списак надгледања',
	'wikiafollowedpages-userpage-more' => 'Више',
	'wikiafollowedpages-userpage-hide' => 'сакриј',
);

/** Swedish (Svenska)
 * @author Tobulos1
 */
$messages['sv'] = array(
	'follow-desc' => 'Förbättringar för bevakningslistans funktionalitet',
	'prefs-basic' => 'Grundläggande alternativ',
	'wikiafollowedpages-special-heading-category' => 'Kategorier ($1)',
	'wikiafollowedpages-special-heading-article' => 'Artiklar ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Bloggar och inlägg ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Forumtrådar ($1)',
	'wikiafollowedpages-special-heading-project' => 'Projektsidor ($1)',
	'wikiafollowedpages-special-heading-user' => 'Användarsidor ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Mallsidor ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'MediaWiki-sidor ($1)',
	'wikiafollowedpages-special-heading-media' => 'Bilder och videoklipp ($1)',
	'wikiafollowedpages-special-namespace' => '($1 sida)',
	'wikiafollowedpages-special-empty' => 'Den här användarens lista på bevakade sidor är tom.
Lägg till sidor i denna lista genom att klicka på "{{int:watch}}" överst på en sida.',
	'wikiafollowedpages-special-anon' => 'Vänligen [[Special:Signup|logga in]] för att skapa eller visa din lista på bevakade sidor.',
	'oasis-wikiafollowedpages-special-seeall' => 'Se alla >',
	'wikiafollowedpages-special-seeall' => 'Se alla >',
	'wikiafollowedpages-special-showall' => 'Visa alla >',
	'wikiafollowedpages-special-showmore' => 'Visa mer',
	'wikiafollowedpages-special-title' => 'Bevakade sidor',
	'wikiafollowedpages-special-delete-tooltip' => 'Ta bort denna sida',
	'wikiafollowedpages-special-hidden' => 'Den här användaren har valt att dölja {{GENDER:$1|hans|hennes|deras}} lista på bevakade sidor från allmänheten.',
	'wikiafollowedpages-special-hidden-unhide' => 'Ta fram den här listan.',
	'wikiafollowedpages-special-blog-by' => 'av $1',
	'wikiafollowedpages-masthead' => 'Bevakade sidor',
	'wikiafollowedpages-following' => 'Bevakar',
	'wikiafollowedpages-special-title-userbar' => 'Bevakade sidor',
	'tog-enotiffollowedpages' => 'Skicka ett e-post till mig när en sida på min bevakningslista ändras',
	'tog-enotiffollowedminoredits' => 'E-posta mig för mindre ändringar på sidor jag bevakar',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Avancerade alternativ',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Endast bevakningslista',
	'tog-hidefollowedpages' => 'Gör min lista över bevakade sidor privat',
	'follow-categoryadd-summary' => 'Sida lades till i kategori',
	'follow-bloglisting-summary' => 'Blogg postad på bloggsidan',
	'wikiafollowedpages-userpage-heading' => 'Sidor jag bevakar',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Dölj din lista över bevakade sidor från allmänheten',
	'wikiafollowedpages-userpage-more' => 'Mer',
	'wikiafollowedpages-userpage-hide' => 'göm',
	'wikiafollowedpages-userpage-empty' => 'Den här användarens lista på bevakade sidor är tom.
Lägg till sidor i denna lista genom att klicka på "{{int:watch}}" överst på en sida.',
	'enotif_body_categoryadd' => 'Kära $WATCHINGUSERNAME,

En sida har lagts till en kategori du bevakar, på {{SITENAME}}.

För att de den nya sidan, gå till "$PAGETITLE_URL".

Vänligen besök och redigera ofta...

{{SITENAME}}

___________________________________________
* Kolla in våra rekommenderade wikis! http://www.wikia.com

* Vill du kontrollera vilka mail du får?
Gå till: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Kära $WATCHINGUSERNAME,
<br /><br />
En sida har lagts till en kategori du bevakar, på {{SITENAME}}.
<br /><br />
För att de den nya sidan, gå till <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Vänligen besök och redigera ofta...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Kolla in våra rekommenderade wikis!</a></li>
<li>Vill du kontrollera vilka mail du får? Gå till <a href="{{fullurl:{{ns:special}}:Preferences}}">Användarinställningarna</a></li>
</ul>
</p>',
	'enotif_body_blogpost' => 'Kära $WATCHINGUSERNAME,

Det har skett en redigering på en blogglista som du följer, på {{SITENAME}}.

För att se förändringen, gå till "$PAGETITLE_URL".

Vänligen besök och redigera ofta...

{{SITENAME}}

___________________________________________
* Kolla in våra rekommenderade wikis! http://www.wikia.com

* Vill du kontrollera vilka mail du får?
Gå till: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Kära $WATCHINGUSERNAME,
<br /><br />
Det har skett en redigering på en blogglista som du följer, på {{SITENAME}}.
<br /><br />
För att se förändringen, gå till <a href="$PAGETITLE_URL">$PAGETITLE</a>.
<br /><br />
Vänligen besök och redigera ofta...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Kolla in våra rekommenderade wikis!</a></li>
<li>Vill du kontrollera vilka mail du får? Gå till <a href="{{fullurl:{{ns:special}}:Preferences}}">Användarinställningarna</a></li>
</ul>
</p>',
);

/** Swahili (Kiswahili) */
$messages['sw'] = array(
	'wikiafollowedpages-userpage-hide' => 'ficha',
);

/** Tamil (தமிழ்)
 * @author TRYPPN
 */
$messages['ta'] = array(
	'wikiafollowedpages-userpage-more' => 'மேலும்',
	'wikiafollowedpages-userpage-hide' => 'மறை',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'wikiafollowedpages-special-heading-category' => 'వర్గాలు ($1)',
	'wikiafollowedpages-special-heading-article' => 'వ్యాసాలు ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'బ్లాగులు మరియు టపాలు ($1)',
	'wikiafollowedpages-special-heading-project' => 'ప్రాజెక్టు పుటలు ($1)',
	'wikiafollowedpages-special-heading-user' => 'వాడుకరి పుటలు ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'మీడియావికీ పుటలు ($1)',
	'wikiafollowedpages-special-namespace' => '($1 పుట)',
	'wikiafollowedpages-special-showall' => 'అన్నిటినీ చూపించు >',
	'wikiafollowedpages-userpage-hide' => 'దాచు',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'follow-desc' => 'Mga pagpapainam para sa katungkulang-gawain ng talaan ng binabantayan',
	'wikiafollowedpages-special-heading-category' => 'Mga kategorya ($1)',
	'wikiafollowedpages-special-heading-article' => 'Mga artikulo ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Mga blog at mga pagpapaskil ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Mga sinulid ng poro ($1)',
	'wikiafollowedpages-special-heading-project' => 'Mga pahina ng proyekto ($1)',
	'wikiafollowedpages-special-heading-user' => 'Mga pahina ng tagagamit ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Mga pahina ng mga suleras ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Mga pahina ng MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Mga larawan at mga bidyo ($1)',
	'wikiafollowedpages-special-namespace' => '($1 pahina)',
	'wikiafollowedpages-special-empty' => 'Ang talaang ito ng sinusundan mga pahina ng tagagamit ay walang laman.
Idagdag ang mga pahina sa talaang ito sa pamamagitan ng pagpindot sa "{{int:watch}}" na nasa itaas ng isang pahina.',
	'wikiafollowedpages-special-anon' => 'Paki [[Special:Signup|lumagda]] upang makalikha o tanawin ang iyong talaan ng sinusundang mga pahina.',
	'oasis-wikiafollowedpages-special-seeall' => 'Tingnan lahat >',
	'wikiafollowedpages-special-seeall' => 'Tingnan lahat >',
	'wikiafollowedpages-special-showall' => 'Ipakitang lahat >',
	'wikiafollowedpages-special-showmore' => 'Magpakita pa',
	'wikiafollowedpages-special-title' => 'Sinusundang mga pahina',
	'wikiafollowedpages-special-delete-tooltip' => 'Tanggalin ang pahinang ito',
	'wikiafollowedpages-special-hidden' => 'Ang tagagamit na ito ay pumili na itago ang {{GENDER:$1|kanyang|kanyang|kanilang}} talaan ng sinusundang mga pahina mula sa pagtanaw ng madla.',
	'wikiafollowedpages-special-hidden-unhide' => 'Huwag itago ang talaang ito.',
	'wikiafollowedpages-special-blog-by' => 'ni $1',
	'wikiafollowedpages-masthead' => 'Sinusundang mga pahina',
	'wikiafollowedpages-following' => 'Sinusundan ang',
	'wikiafollowedpages-special-title-userbar' => 'Sinusundang mga pahina',
	'tog-enotiffollowedpages' => 'Padalhan ako ng e-liham kapag ang isang pahinang sinusundan ko ay nabago',
	'tog-enotiffollowedminoredits' => 'Padalhan ako ng e-liham para sa maliliit na mga pagbabago sa mga pahinang sinusundan ko',
	'tog-hidefollowedpages' => 'Gawing pribado ang aking mga talaan ng sinusundang mga pahina',
	'follow-categoryadd-summary' => 'Idinagdag ang pahina sa kategorya',
	'follow-bloglisting-summary' => 'Ipinaskil ang blog sa pahina ng blog',
	'wikiafollowedpages-userpage-heading' => 'Mga pahinang sinusundan ko',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Itago ang iyong mga talaan ng sinusundang mga pahina mula sa mata ng madla',
	'wikiafollowedpages-userpage-more' => 'Mas marami pa',
	'wikiafollowedpages-userpage-hide' => 'itago',
	'wikiafollowedpages-userpage-empty' => 'Walang laman ang talaan ng sinusundang mga pahina ng tagagamit na ito.
Idagdag ang mga pahina sa talaang ito sa pamamagitan ng pagpindot sa "{{int:watch}}" na nasa itaas ng isang pahina.',
	'enotif_subject_categoryadd' => 'Ang pahinang $PAGETITLE ng {{SITENAME}} ay naidagdag na ni $PAGEEDITOR sa $CATEGORYNAME',
	'enotif_body_categoryadd' => 'Mahal na $ WATCHINGUSERNAME, 

Isang pahina ang idinagdag sa isang kategoryang sinusundan mo sa {{SITENAME}}. 

Tingnan ang "$PAGETITLE_URL" para sa bagong pahina. 

Mangyaring dalawin at madalas na mamatnugot ... 

{{SITENAME}}

 ___________________________________________ 
 * Tingnan ang aming tampok na mga wiki! http://www.wikia.com 

 * Nais mo bang tabanan kung anong mga e-liham ang matatanggap mo? 
 Pumunta sa: {{fullurl:{{ns: special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>	
Mahal na $WATCHINGUSERNAME,
<br /><br />
Isang pahina ang nadagdag sa isang kategoryang tinutugaygayan mo sa {{SITENAME}}.
<br /><br />
Tingnan ang <a href="$PAGETITLE_URL">$PAGETITLE</a> para sa bagong pahina.
<br /><br />
Mangyaring dumalawa at madalas na mamatnugot...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Suriin ang aming tampok na mga wiki!</a></li>
<li>Nais mo bang tabanan ang mga e-liham na natatanggap mo? Pumunta sa <a href="{{fullurl:{{ns:special}}:Preferences}}">Mga nais ng tagagamit</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Ang pahinang $PAGETITLE ng {{SITENAME}} ay naipaskil na ni $PAGEEDITOR sa $BLOGLISTINGNAME',
	'enotif_body_blogpost' => 'Minamahal na $WATCHINGUSERNAME,

Nagkaroon ng isang pamamatnugot sa pahina ng talaan ng blog na iyong tinutugaygayan sa {{SITENAME}}.

Tingnan ang "$PAGETITLE_URL" para sa baong pagpapaskil.

Mangyaring gawing madalas ang pagdalaw at pamamatnugot...

{{SITENAME}}

___________________________________________
* Suriin ang aming tampok na mga wiki! http://www.wikia.com

* Nais mong tabanan ang kung anong mga e-liham ang tatanggapin mo?
Magpunta sa: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Minamahal na $WATCHINGUSERNAME,
<br /><br />
Nagkaroon ng isang pamamatnugot sa isang pahina ng talaan ng blog na iyong sinusundan sa {{SITENAME}}.
<br /><br />
Tingnan ang <a href="$PAGETITLE_URL">$PAGETITLE</a> para sa bagong pagpapaskil.
<br /><br />
Mangyaring bumisita at mamatnugot ng madalas...
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Suriin ang aming tampok na mga wiki!</a></li>
<li>Gusto mong tabanan kung aling mga e-liham ang tatanggapin mo? Pumunta sa <a href="{{fullurl:{{ns:special}}:Preferences}}">Mga nais</a></li>
</ul>
</p>',
);

/** Ukrainian (Українська)
 * @author Ast
 * @author Тест
 */
$messages['uk'] = array(
	'follow-desc' => 'Покращення для функціональності списку спостереження',
	'prefs-basic' => 'Основні параметри',
	'wikiafollowedpages-special-heading-category' => 'Категорії ($1)',
	'wikiafollowedpages-special-heading-article' => 'Статті ($1)',
	'wikiafollowedpages-special-heading-blogs' => 'Блоґи та повідомлення ($1)',
	'wikiafollowedpages-special-heading-forum' => 'Теми форуму ($1)',
	'wikiafollowedpages-special-heading-project' => 'Сторінки проектів ($1)',
	'wikiafollowedpages-special-heading-user' => 'Сторінки учасників ($1)',
	'wikiafollowedpages-special-heading-templates' => 'Шаблони ($1)',
	'wikiafollowedpages-special-heading-mediawiki' => 'Сторінки MediaWiki ($1)',
	'wikiafollowedpages-special-heading-media' => 'Зображення та відео ($1)',
	'wikiafollowedpages-special-namespace' => '($1 сторінка)',
	'wikiafollowedpages-special-empty' => 'Ваш список спостереження порожній.
Додавайте сторінки в список, натискаючи "{{int:watch}}" зверху сторінки.',
	'wikiafollowedpages-special-anon' => 'Будь ласка, [[Special:Signup|увійдіть до системи]] для створення або перегляду сторінок свого списку спостереження.',
	'oasis-wikiafollowedpages-special-seeall' => 'Переглянути всі >',
	'wikiafollowedpages-special-seeall' => 'Переглянути всі >',
	'wikiafollowedpages-special-showall' => 'Показати всі >',
	'wikiafollowedpages-special-showmore' => 'Показати більше',
	'wikiafollowedpages-special-title' => 'Сторінки спостереження',
	'wikiafollowedpages-special-delete-tooltip' => 'Видалити цю сторінку',
	'wikiafollowedpages-special-hidden' => '{{GENDER:$1|Цей користувач|Ця користувачка}} воліє зробити свій список спостереження прихованим від публічного перегляду.',
	'wikiafollowedpages-special-hidden-unhide' => 'Показати цей список.',
	'wikiafollowedpages-special-blog-by' => 'від $1',
	'wikiafollowedpages-masthead' => 'Сторінки спостереження',
	'wikiafollowedpages-following' => 'Спостереження',
	'wikiafollowedpages-special-title-userbar' => 'Сторінки спостереження',
	'tog-enotiffollowedpages' => 'Повідомляти по електронній пошті про зміну сторінок зі списку спостереження',
	'tog-enotiffollowedminoredits' => 'Повідомляти по електронній пошті про невеликі правки в сторінках зі списку спостереження',
	'prefs-wikiafollowedpages-prefs-advanced' => 'Додаткові параметри',
	'prefs-wikiafollowedpages-prefs-watchlist' => 'Тільки список спостереження',
	'tog-hidefollowedpages' => 'Зробити мій список спостереження прихованим від публічного перегляду',
	'follow-categoryadd-summary' => 'Сторінка добавлена до категорії',
	'follow-bloglisting-summary' => 'Блог опублікований на сторінці блогу',
	'wikiafollowedpages-userpage-heading' => 'Сторінки, за якими я спостерігаю',
	'wikiafollowedpages-userpage-hide-tooltip' => 'Сховайте ваші списки спостереження від публічного перегляду',
	'wikiafollowedpages-userpage-more' => 'Більше',
	'wikiafollowedpages-userpage-hide' => 'сховати',
	'wikiafollowedpages-userpage-empty' => 'Список статей спостереження цього користувача порожній.
Щоб додати сторінки в цей список, натисніть "{{int:watch}}" зверху цієї сторінки.',
	'enotif_subject_categoryadd' => 'У проекті {{SITENAME}} користувач $PAGEEDITOR додав до $CATEGORYNAME сторінку $PAGETITLE',
	'enotif_body_categoryadd' => 'Шановний $WATCHINGUSERNAME,

У проекті {{SITENAME}} в категорії, за якою Ви слідкуєте, була створена сторінка.

Дивіться нову сторінку за адресою: "$PAGETITLE_URL" 

Будь ласка, заходьте та редагуйте частіше... 

{{SITENAME}}

___________________________________________
* Ознайомтеся з нашими обраними вікі! http://www.wikia.com

* Бажаєте змінити параметри отримання повідомлень електронною поштою? 
Перейдіть до: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_categoryadd-html' => '<p>
Шановний $WATCHINGUSERNAME,
<br /><br />
У проекті {{SITENAME}} в категорії, за якою Ви слідкуєте, була створена сторінка.
<br /><br />
Дивіться нову сторінку за адресою: <a href="$PAGETITLE_URL">$PAGETITLE</a> 
<br /><br />
Будь ласка, заходьте та редагуйте частіше... 
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li><a href="http://www.wikia.com">Ознайомтеся з нашими обраними вікі!</a></li>
<li>Бажаєте змінити параметри отримання повідомлень електронною поштою? Перейдіть до <a href="{{fullurl:{{ns:special}}:Preferences}}">налаштувань користувача</a></li>
</ul>
</p>',
	'enotif_subject_blogpost' => 'Сторінка $PAGETITLE проекту {{SITENAME}} була розміщена у $BLOGLISTINGNAME користувачем $PAGEEDITOR',
	'enotif_body_blogpost' => 'Шановний, $WATCHINGUSERNAME,

У проекті {{SITENAME}} на сторінці списку блогів, за якою ви слідкуєте, була здійснена правка.

Дивіться зміни за адресою: "$PAGETITLE_URL"

Будь ласка, заходьте та редагуйте частіше... 

{{SITENAME}}

___________________________________________
* Ознайомтеся з нашими обраними вікі! http://www.wikia.com

* Бажаєте змінити параметри отримання повідомлень електронною поштою?
Перейдіть до: {{fullurl:{{ns:special}}:Preferences}}.',
	'enotif_body_blogpost-HTML' => '<p>
Шановний $WATCHINGUSERNAME,
<br /><br />
У проекті {{SITENAME}} на сторінці списку блогів, за якою ви слідкуєте, була здійснена правка. 
<br /><br />
Дивіться зміни за адресою: <a href="$PAGETITLE_URL">$ PAGETITLE</a> . 
<br /><br />
Будь ласка, заходьте та редагуйте частіше... 
<br /><br />
{{SITENAME}}
<br /><hr />
<ul>
<li> <a href="http://www.wikia.com">Ознайомтеся з нашими обраними вікі!</a></li>
<li>Бажаєте змінити параметри отримання повідомлень електронною поштою? Перейдіть до <a href="{{fullurl:{{ns:special}}:Preferences}}">налаштувань користувача</a></li>
</ul>
</p>',
);

/** Simplified Chinese (‪中文(简体)‬)
 * @author Hydra
 */
$messages['zh-hans'] = array(
	'wikiafollowedpages-special-heading-category' => '分类（$1）',
	'wikiafollowedpages-special-heading-article' => '条目（$1）',
	'oasis-wikiafollowedpages-special-seeall' => '显示全部 》',
	'wikiafollowedpages-special-seeall' => '显示全部 》',
	'wikiafollowedpages-special-showmore' => '显示更多',
	'wikiafollowedpages-userpage-more' => '更多',
	'wikiafollowedpages-userpage-hide' => '隐瞒',
);

