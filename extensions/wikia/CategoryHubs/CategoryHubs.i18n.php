<?php
/**
 * Author: Sean Colombo
 * Date: 20091123
 *
 * Internationalization file for CategoryHub extension.
 */

$messages = array();

$messages['en'] = array(
	'cathub-desc' => 'Extension for turning category pages into a view of the category as a hub of activity. Designed for [http://answers.wikia.com answers.wikia.com]',
	'cathub-progbar-mouseover-answered'     => '$1% answered',
	'cathub-progbar-mouseover-not-answered' => '$1% not answered yet',
	'cathub-progbar-label-answered'         => 'Answered',
	'cathub-progbar-label-unanswered'       => 'Unanswered',
	'cathub-progbar-none-done'              => 'No questions answered yet',
	'cathub-progbar-all-done'               => 'All questions answered!',
	'cathub-progbar-allmost-done'           => '$1 unanswered {{PLURAL:$1|question|questions}} left!',

	// Keep in mind that there may be questions either unanswer/answered that just don't show up in this pagination so it would be wrong to say there are none in the category at all.
	'cathub-no-unanswered-questions'        => 'There are no unanswered questions to see right now.',
	'cathub-no-answered-questions'          => 'There are no answered questions to see right now.',

	'cathub-top-contributors'               => 'Top contributors to this category',
	'cathub-top-contribs-all-time'          => 'Of all time',
	'cathub-top-contribs-recent'            => 'In the last $1 {{PLURAL:$1|day|days}}',
	'cathub-question-asked-ago'             => 'asked $1 $2',
	'cathub-question-answered-ago'          => 'answered $1 $2',
	'cathub-question-asked-by'              => 'by $1',
	'cathub-anon-username'                  => 'a curious user',
	'cathub-answer-heading'                 => 'Answer',
	'cathub-button-answer'                  => 'Answer',
	'cathub-button-improve-answer'          => 'Improve answer',
	'cathub-button-rephrase'                => 'rephrase',
	'cathub-edit-success'                   => 'Your answer has been saved',
	'cathub-prev'                           => '&larr; Previous',
	'cathub-next'                           => 'Next &rarr;',
);

/** Breton (Brezhoneg)
 * @author Y-M D
 */
$messages['br'] = array(
	'cathub-progbar-mouseover-answered' => '$1% respontet',
	'cathub-progbar-mouseover-not-answered' => "$1% direspontet evit c'hoazh",
	'cathub-progbar-label-answered' => 'Respontet',
	'cathub-progbar-label-unanswered' => 'Direspontet',
	'cathub-progbar-none-done' => "N'eus goulenn ebet bet respontet outo evit poent",
	'cathub-progbar-all-done' => "Ur respont a zo d'an holl goulennoù !",
	'cathub-progbar-allmost-done' => '$1 goulenn{{PLURAL:$1||}} direspontet a chom !',
	'cathub-no-unanswered-questions' => "N'eus goulenn hep respont ebet da welet er mare-mañ.",
	'cathub-no-answered-questions' => "N'eus goulenn bet respontet outo ebet da welet er mare-mañ.",
	'cathub-top-contributors' => 'Ar re a gemer perzh ar muiañ er rummad-mañ',
	'cathub-top-contribs-all-time' => 'A holl viskoaz',
	'cathub-top-contribs-recent' => 'E-pad ar $1 devezh{{PLURAL:$1||}} ziwezhañ',
	'cathub-question-asked-ago' => 'goulennet $1 $2',
	'cathub-question-answered-ago' => 'en deus respontet $1 $2',
	'cathub-question-asked-by' => 'gant $1',
	'cathub-anon-username' => 'un implijer fri-furch',
	'cathub-answer-heading' => 'Respont',
	'cathub-button-answer' => 'Respont',
	'cathub-button-improve-answer' => 'Gwelaat ar respont',
	'cathub-button-rephrase' => 'adfrazenniñ',
	'cathub-edit-success' => 'Enrollet eo bet ho respont',
	'cathub-prev' => '&larr; A-raok',
	'cathub-next' => 'Da-heul &rarr;',
);

/** Finnish (Suomi)
 * @author Centerlink
 * @author Crt
 */
$messages['fi'] = array(
	'cathub-progbar-mouseover-answered' => '$1 % vastattu',
	'cathub-progbar-mouseover-not-answered' => '$1 % ei ole vielä vastattu',
	'cathub-progbar-label-answered' => 'Vastattu',
	'cathub-progbar-label-unanswered' => 'Vastaamaton',
	'cathub-progbar-none-done' => 'Kysymyksiin ei ole vielä vastattu',
	'cathub-progbar-all-done' => 'Kaikkiin kysymyksiin on vastattu.',
	'cathub-progbar-allmost-done' => '$1 {{PLURAL:$1|vastaamaton kysymys|vastaamatonta kysymystä}} jäljellä.',
	'cathub-no-unanswered-questions' => 'Tällä hetkellä ei ole yhtään vastaamatonta kysymystä nähtävänä.',
	'cathub-no-answered-questions' => 'Tällä hetkellä ei ole yhtään vastattua kysymystä nähtävänä.',
	'cathub-top-contributors' => 'Ahkerimmat muokkaajat tässä luokassa',
	'cathub-answer-heading' => 'Vastaus',
	'cathub-button-answer' => 'Vastaus',
	'cathub-button-improve-answer' => 'Paranna vastausta',
	'cathub-edit-success' => 'Vastauksesi on tallennettu',
	'cathub-prev' => '&larr; Edellinen',
	'cathub-next' => 'Seuraava &rarr;',
);

/** French (Français)
 * @author IAlex
 */
$messages['fr'] = array(
	'cathub-desc' => "Extension pour utiliser les pages de catégories en tant que centre d'activité. Conçu pour [http://answers.wikia.com answers.wikia.com]",
	'cathub-progbar-mouseover-answered' => '$1 % répondu',
	'cathub-progbar-mouseover-not-answered' => '$1 % sans réponse',
	'cathub-progbar-label-answered' => 'Répondu',
	'cathub-progbar-label-unanswered' => 'Sans réponse',
	'cathub-progbar-none-done' => "Aucune question n'a encore de réponse",
	'cathub-progbar-all-done' => 'Toutes les questions ont un réponse !',
	'cathub-progbar-allmost-done' => 'Il reste $1 {{PLURAL:$1|question|questions}} sans réponse !',
	'cathub-no-unanswered-questions' => "Il n'y a pas de questions sans réponse à voir en ce moment.",
	'cathub-no-answered-questions' => "Il n'y a pas de questions répondues à voir en ce moment.",
	'cathub-top-contributors' => 'Meilleurs contributeurs à cette catégorie',
	'cathub-top-contribs-all-time' => 'De tous les temps',
	'cathub-top-contribs-recent' => 'Dans {{PLURAL:$1|le dernier jour|les $1 derniers jours}}',
	'cathub-question-asked-ago' => 'a demandé $1 $2',
	'cathub-question-answered-ago' => 'a répondu $1 $2',
	'cathub-question-asked-by' => 'par $1',
	'cathub-anon-username' => 'un utilisateur curieux',
	'cathub-answer-heading' => 'Réponse',
	'cathub-button-answer' => 'Répondre',
	'cathub-button-improve-answer' => 'Améliorer la réponse',
	'cathub-button-rephrase' => 'reformuler',
	'cathub-edit-success' => 'Votre réponse a été sauvegardée',
	'cathub-prev' => '&larr; Précédent',
	'cathub-next' => 'Suivant &rarr;',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'cathub-desc' => 'Extensión para transformar as páxinas de categoría nunha vista da categoría como un centro de actividades. Deseñado para [http://answers.wikia.com answers.wikia.com]',
	'cathub-progbar-mouseover-answered' => '$1% está respondida',
	'cathub-progbar-mouseover-not-answered' => '$1% aínda non está respondida',
	'cathub-progbar-label-answered' => 'Respondidas',
	'cathub-progbar-label-unanswered' => 'Sen responder',
	'cathub-progbar-none-done' => 'Ningunha pregunta recibiu resposta aínda',
	'cathub-progbar-all-done' => 'Todas as preguntas están respondidas!',
	'cathub-progbar-allmost-done' => '{{PLURAL:$1|Queda unha pregunta|Quedan $1 preguntas}} por responder!',
	'cathub-no-unanswered-questions' => 'Nestes intres non hai ningunha pregunta sen responder.',
	'cathub-no-answered-questions' => 'Nestes intres non hai ningunha pregunta respondida.',
	'cathub-top-contributors' => 'Maior número de contribuíntes nesta categoría',
	'cathub-top-contribs-all-time' => 'Sempre',
	'cathub-top-contribs-recent' => '{{PLURAL:$1|No último día|Nos últimos $1 días}}',
	'cathub-question-asked-ago' => 'preguntou $1 $2',
	'cathub-question-answered-ago' => 'respondeu $1 $2',
	'cathub-question-asked-by' => 'por $1',
	'cathub-anon-username' => 'un usuario curioso',
	'cathub-answer-heading' => 'Resposta',
	'cathub-button-answer' => 'Responder',
	'cathub-button-improve-answer' => 'Mellorar a resposta',
	'cathub-button-rephrase' => 'reformular',
	'cathub-edit-success' => 'Gardouse a súa resposta',
	'cathub-prev' => '&larr; Anterior',
	'cathub-next' => 'Seguinte &rarr;',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'cathub-desc' => 'Extension pro transformar paginas de categoria in un vista del categoria como centro de activitate. Designate pro [http://answers.wikia.com answers.wikia.com].',
	'cathub-progbar-mouseover-answered' => '$1% respondite',
	'cathub-progbar-mouseover-not-answered' => '$1% non ancora respondite',
	'cathub-progbar-label-answered' => 'Respondite',
	'cathub-progbar-label-unanswered' => 'Non respondite',
	'cathub-progbar-none-done' => 'Nulle question ancora respondite',
	'cathub-progbar-all-done' => 'Tote le questiones respondite!',
	'cathub-progbar-allmost-done' => 'Il resta $1 {{PLURAL:$1|question|questiones}} a responder!',
	'cathub-no-unanswered-questions' => 'Il non ha questiones non respondite a vider in iste momento.',
	'cathub-no-answered-questions' => 'Il non ha questiones respondite a vider in iste momento.',
	'cathub-top-contributors' => 'Principal contributores a iste categoria',
	'cathub-top-contribs-all-time' => 'De tote le tempores',
	'cathub-top-contribs-recent' => 'In le ultime $1 {{PLURAL:$1|die|dies}}',
	'cathub-question-asked-ago' => 'demandava $1 $2',
	'cathub-question-answered-ago' => 'respondeva $1 $2',
	'cathub-question-asked-by' => 'per $1',
	'cathub-anon-username' => 'un usator curiose',
	'cathub-answer-heading' => 'Responsa',
	'cathub-button-answer' => 'Responder',
	'cathub-button-improve-answer' => 'Meliorar responsa',
	'cathub-button-rephrase' => 'reformular',
	'cathub-edit-success' => 'Tu responsa ha essite salveguardate',
	'cathub-prev' => '&larr; Precedente',
	'cathub-next' => 'Sequente &rarr;',
);

/** Japanese (日本語)
 * @author Tommy6
 */
$messages['ja'] = array(
	'cathub-desc' => 'http://answers.wikia.com/ の編集者や閲覧者のためにデザインされた、カテゴリページを活動のハブとするための拡張機能。',
	'cathub-progbar-mouseover-answered' => '$1%が回答済み',
	'cathub-progbar-mouseover-not-answered' => '$1%が回答待ち',
	'cathub-progbar-label-answered' => '回答済み',
	'cathub-progbar-label-unanswered' => '回答待ち',
	'cathub-progbar-none-done' => '回答済みの質問はまだ一つもありません',
	'cathub-progbar-all-done' => '全ての質問が回答済みです！',
	'cathub-progbar-allmost-done' => '回答待ちの質問は残り$1件です！',
	'cathub-no-unanswered-questions' => '回答待ちの質問は一つもありません。',
	'cathub-no-answered-questions' => '回答済みの質問は一つもありません。',
	'cathub-top-contributors' => 'このカテゴリのベスト回答者',
	'cathub-top-contribs-all-time' => '全期間',
	'cathub-top-contribs-recent' => '最近$1日間',
	'cathub-question-asked-ago' => '$1に質問 - $2',
	'cathub-question-answered-ago' => '$1に回答 - $2',
	'cathub-question-asked-by' => 'by $1',
	'cathub-anon-username' => '匿名ユーザー',
	'cathub-answer-heading' => '回答',
	'cathub-button-answer' => '回答する',
	'cathub-button-improve-answer' => '回答を更新する',
	'cathub-button-rephrase' => '言い回しを変える',
	'cathub-edit-success' => '回答が保存されました',
	'cathub-prev' => '&larr; 前',
	'cathub-next' => '次 &rarr;',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'cathub-desc' => 'Додаток со кој категориските страници се прикажуваат како средишта на активност. Изготвено за [http://answers.wikia.com answers.wikia.com]',
	'cathub-progbar-mouseover-answered' => '$1% одговорени',
	'cathub-progbar-mouseover-not-answered' => '$1% сè уште неодговорени',
	'cathub-progbar-label-answered' => 'Одговорени',
	'cathub-progbar-label-unanswered' => 'Неодговорени',
	'cathub-progbar-none-done' => 'Сè уште нема одговори на прашањата',
	'cathub-progbar-all-done' => 'Сите прашања се одговорени!',
	'cathub-progbar-allmost-done' => 'Има уште $1 {{PLURAL:$1|неодговорено прашање|неодговорени прашања}}!',
	'cathub-no-unanswered-questions' => 'Моментално нема неодговорени прашања.',
	'cathub-no-answered-questions' => 'Моментално нема одговорени прашања.',
	'cathub-top-contributors' => 'Најкотирани учесници во оваа категорија',
	'cathub-top-contribs-all-time' => 'На сите времиња',
	'cathub-top-contribs-recent' => '{{PLURAL:$1|Во последниот $1 ден|Во последните $1 дена}}',
	'cathub-question-asked-ago' => 'прашано $1 $2',
	'cathub-question-answered-ago' => 'одговорено $1 $2',
	'cathub-question-asked-by' => 'од $1',
	'cathub-anon-username' => 'љубопитен корисник',
	'cathub-answer-heading' => 'Одговор',
	'cathub-button-answer' => 'Одговори',
	'cathub-button-improve-answer' => 'Дај подобар одговор',
	'cathub-button-rephrase' => 'искажи поинаку',
	'cathub-edit-success' => 'Вашиот одговор е зачуван',
	'cathub-prev' => '&larr; Претходно',
	'cathub-next' => 'Следно &rarr;',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'cathub-desc' => "Uitbreiding die categoriepagina's omvormt tot activiteitenpagina.
Ontworpen voor [http://answers.wikia.com answers.wikia.com]",
	'cathub-progbar-mouseover-answered' => '$1% beantwoord.',
	'cathub-progbar-mouseover-not-answered' => '$1% nog niet beantwoord.',
	'cathub-progbar-label-answered' => 'Beantwoord',
	'cathub-progbar-label-unanswered' => 'Onbeantwoord',
	'cathub-progbar-none-done' => 'Nog geen vragen beantwoord.',
	'cathub-progbar-all-done' => 'Alle vragen beantwoord!',
	'cathub-progbar-allmost-done' => '$1 onbeantwoorde {{PLURAL:$1|vraag|vragen}} over!',
	'cathub-no-unanswered-questions' => 'Er zijn op dit moment geen onbeantwoorde vragen.',
	'cathub-no-answered-questions' => 'Er zijn op dit moment geen beantwoorde vragen.',
	'cathub-top-contributors' => 'Gebruikers met de meeste bijdragen aan deze categorie',
	'cathub-top-contribs-all-time' => 'Aller tijden',
	'cathub-top-contribs-recent' => 'In de {{PLURAL:$1|afgelopen dag|afgelopen $1 dagen}}',
	'cathub-question-asked-ago' => 'gevraagd $1 $2',
	'cathub-question-answered-ago' => 'beantwoord $1 $2',
	'cathub-question-asked-by' => 'door $1',
	'cathub-anon-username' => 'een nieuwsgierige gebruiker',
	'cathub-answer-heading' => 'Antwoord',
	'cathub-button-answer' => 'Antwoorden',
	'cathub-button-improve-answer' => 'Antwoord verbeteren',
	'cathub-button-rephrase' => 'herformuleren',
	'cathub-edit-success' => 'Uw antwoord is opgeslagen',
	'cathub-prev' => '← Vorige',
	'cathub-next' => 'Volgende →',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'cathub-desc' => 'Utvidelse for å gjøre om kategorisider til en visning av kategorien som et knutepunkt for aktivitet. Laget for [http://answers.wikia.com answers.wikia.com]',
	'cathub-progbar-mouseover-answered' => '$1% besvart',
	'cathub-progbar-mouseover-not-answered' => '$1% ikke besvart ennå',
	'cathub-progbar-label-answered' => 'Besvart',
	'cathub-progbar-label-unanswered' => 'Ubesvart',
	'cathub-progbar-none-done' => 'Ingen spørsmål besvart ennå',
	'cathub-progbar-all-done' => 'Alle spørsmål besvart!',
	'cathub-progbar-allmost-done' => '$1 {{PLURAL:$1|ubesvart|ubesvarte}} spørsmål igjen!',
	'cathub-no-unanswered-questions' => 'Det er ingen ubesvarte spørsmål å se akkurat nå.',
	'cathub-no-answered-questions' => 'Det er ingen besvarte spørsmål å se akkurat nå.',
	'cathub-top-contributors' => 'Topp bidragsytere i denne kategorien',
	'cathub-top-contribs-all-time' => 'Gjennom tidene',
	'cathub-top-contribs-recent' => 'I løpet av {{PLURAL:$1|dagen|de siste $1 dagene}}',
	'cathub-question-asked-ago' => 'spurt $1 $2',
	'cathub-question-answered-ago' => 'besvart $1 $2',
	'cathub-question-asked-by' => 'av $1',
	'cathub-anon-username' => 'en nysgjerrig bruker',
	'cathub-answer-heading' => 'Svar',
	'cathub-button-answer' => 'Svar',
	'cathub-button-improve-answer' => 'Svar bedre',
	'cathub-button-rephrase' => 'omformuler',
	'cathub-edit-success' => 'Svaret ditt har blitt lagret',
	'cathub-prev' => '← Forrige',
	'cathub-next' => 'Neste →',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 */
$messages['pms'] = array(
	'cathub-desc' => "Estension për rapresenté pàgine ëd categorìa ant na vista ëd categorìa com n'ansema d'atività. Progetà për [http://answers.wikia.com answers.wikia.com]",
	'cathub-progbar-mouseover-answered' => '$1% arspondù',
	'cathub-progbar-mouseover-not-answered' => '$1% pa ancó arspondù',
	'cathub-progbar-label-answered' => 'Arspondù',
	'cathub-progbar-label-unanswered' => 'Pa arspondù',
	'cathub-progbar-none-done' => "Gnun-a chestion a l'ha al moment na rispòsta",
	'cathub-progbar-all-done' => "Tute le chestion a l'han na rispòsta!",
	'cathub-progbar-allmost-done' => 'Ancó $1 {{PLURAL:$1|chestion|chestion}} pa arspondùe!',
	'cathub-no-unanswered-questions' => 'A-i é gnun-e custion pa arspondùe da vardé adess.',
	'cathub-no-answered-questions' => 'A-i é pa gnun-e custion arspondùe da vardé adess.',
	'cathub-top-contributors' => 'Mej contribudor a sta categorìa',
	'cathub-top-contribs-all-time' => 'Ëd tùit ij temp',
	'cathub-top-contribs-recent' => "Ant j'ùltim $1 {{PLURAL:$1|di|di}}",
	'cathub-question-asked-ago' => 'ciamà $1 $2',
	'cathub-question-answered-ago' => 'arspondù $1 $2',
	'cathub-question-asked-by' => 'da $1',
	'cathub-anon-username' => "n'utent curios",
	'cathub-answer-heading' => 'Arspòsta',
	'cathub-button-answer' => 'Arspòsta',
	'cathub-button-improve-answer' => 'Amelioré la rispòsta',
	'cathub-button-rephrase' => 'riformolé',
	'cathub-edit-success' => "Toa arspòsta a l'é stàita salvà",
	'cathub-prev' => '&larr; Prima',
	'cathub-next' => '&rarr; dapress',
);

/** Russian (Русский)
 * @author Александр Сигачёв
 */
$messages['ru'] = array(
	'cathub-desc' => 'Расширение для включения на страниц категорий представления, показывающего центры деятельности. Разработан для [http://answers.wikia.com answers.wikia.com]',
	'cathub-progbar-mouseover-answered' => '$1% получили ответы',
	'cathub-progbar-mouseover-not-answered' => '$1% ещё не получили ответов',
	'cathub-progbar-label-answered' => 'С ответами',
	'cathub-progbar-label-unanswered' => 'Без ответов',
	'cathub-progbar-none-done' => 'Пока на вопросы нет ответов',
	'cathub-progbar-all-done' => 'На все вопросы даны ответы!',
	'cathub-progbar-allmost-done' => '$1 {{PLURAL:$1|вопрос|вопроса|вопросов}} остаются без ответа!',
	'cathub-no-unanswered-questions' => 'Сейчас нет вопросов без ответов.',
	'cathub-no-answered-questions' => 'Пока ещё нет вопросов, имеющих ответы.',
	'cathub-top-contributors' => 'Основные участники этой категории',
	'cathub-top-contribs-all-time' => 'Всех времён',
	'cathub-top-contribs-recent' => 'За {{PLURAL:$1|последний $1 день|последние $1 дня|последние $1 дней}}',
	'cathub-question-asked-ago' => 'вопрос задан $1 $2',
	'cathub-question-answered-ago' => 'ответ получен $1 $2',
	'cathub-question-asked-by' => 'участником $1',
	'cathub-anon-username' => 'любопытным пользователем',
	'cathub-answer-heading' => 'Ответ',
	'cathub-button-answer' => 'Ответить',
	'cathub-button-improve-answer' => 'Улучшить ответ',
	'cathub-button-rephrase' => 'перефразировать',
	'cathub-edit-success' => 'Ваш ответ сохранён',
	'cathub-prev' => '← Предыдущая',
	'cathub-next' => 'Следующая →',
);

