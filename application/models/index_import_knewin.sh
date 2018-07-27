#!/bin/bash

pidtv=$(ps aux| grep "index_import_knewin_tv.php" |grep "php /app/" | awk '{print $2}')
if [[ -z ${pidtv} ]] ; then
	php /app/application/models/index_import_knewin_tv.php >> /var/log/index_import_knewin_tv.log &
# else
	# echo "Tv script running"
fi

pidradio=$(ps aux| grep "index_import_knewin_radio.php" |grep "php /app/" | awk '{print $2}')
if [[ -z ${pidradio} ]] ; then
	php /app/application/models/index_import_knewin_radio.php >> /var/log/index_import_knewin_radio.log &
# else
	# echo "Radio script running"
fi