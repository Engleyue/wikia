#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd /tmp
php /usr/wikia/source/wiki/maintenance/wikia/getDatabase.php -c B -f muppets
php /usr/wikia/source/wiki/maintenance/wikia/getDatabase.php -i muppets.sql.gz
rm muppets.sql.gz

cd $DIR
sudo -u www-data SERVER_ID=831 php videoReset.php --conf /usr/wikia/docroot/wiki.factory/LocalSettings.php | tee ST_muppets.log || exit
echo ""
sudo -u www-data SERVER_ID=831 php videoSanitize.php --conf /usr/wikia/docroot/wiki.factory/LocalSettings.php | tee ST_muppets.log || exit
echo ""
