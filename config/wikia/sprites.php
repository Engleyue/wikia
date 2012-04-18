<?php
/*
  Configuration file for automatically generated sprites.

  To regenerate the chosen sprite issue this command on your devbox (replace SPRITE_NAME with one of the configured below):
    SERVER_ID=177 php /usr/wikia/source/wiki/maintenance/wikia/generateSprites.php --conf /usr/wikia/docroot/wiki.factory/LocalSettings.php --sprite SPRITE_NAME
  However if you skip the sprite parameter the script will go through entire configuration file and regenerate them.

*/


$config = array();

$config['oasis'] = array(
);

$config['EditPageLayout'] = array(
        'name'   => 'edit-page',
        'source' => "$IP/extensions/wikia/EditPageLayout/images/sprite-edit-page/",
        'sprite' => "$IP/extensions/wikia/EditPageLayout/images/sprite-edit-page.png",
        'scss'   => "$IP/extensions/wikia/EditPageLayout/css/mixins/_sprite-edit-page.scss",
);


$config['PageLayoutEditor'] = array(
        'name'   => 'page-layout-builder',
        'source' => "$IP/extensions/wikia/PageLayoutBuilder/images/sprite-page-layout-builder/",
        'sprite' => "$IP/extensions/wikia/PageLayoutBuilder/images/sprite-page-layout-builder.png",
        'scss'   => "$IP/extensions/wikia/PageLayoutBuilder/css/_sprite-page-layout-builder.scss",

);

$config['MiniEditor'] = array(
        'name'   => 'MiniEditor',
        'source' => "$IP/extensions/wikia/MiniEditor/images/sprite-MiniEditor/",
        'sprite' => "$IP/extensions/wikia/MiniEditor/images/sprite-MiniEditor.png",
        'scss'   => "$IP/extensions/wikia/MiniEditor/css/mixins/_sprite-MiniEditor.scss"

);

$config['Chat2'] = array(
        'name'   => 'Chat',
        'source' => "$IP/extensions/wikia/Chat2/images/sprite-Chat/",
        'sprite' => "$IP/extensions/wikia/Chat2/images/sprite-Chat.png",
        'scss'   => "$IP/extensions/wikia/Chat2/css/mixins/_sprite-Chat.scss"

);
