#!/bin/bash

now=$(date '+%Y-%m-%d %H:%M:%S')
scpid=$(ps -C php -f | grep index_transc_tv.php | awk '{print $2}')
if [[ -z ${scpid} ]] ; then
	echo ${now} '- Script not running!'
	php /app/application/models/index_transc_tv.php >> /var/log/index_transc_tv.log
else
	echo ${now} '- Script already running...'
fi