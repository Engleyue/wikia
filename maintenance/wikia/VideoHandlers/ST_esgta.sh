#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd /tmp
php /usr/wikia/source/wiki/maintenance/wikia/getDatabase.php -f esgta
php /usr/wikia/source/wiki/maintenance/wikia/getDatabase.php -i esgta.sql.gz
rm esgta.sql.gz

cd $DIR
sudo -u www-data SERVER_ID=846 php videoReset.php --conf /usr/wikia/docroot/wiki.factory/LocalSettings.php | tee ST_esgta.log || exit
echo ""
sudo -u www-data SERVER_ID=846 php videoSanitize.php --conf /usr/wikia/docroot/wiki.factory/LocalSettings.php | tee ST_esgta.log || exit
echo ""
