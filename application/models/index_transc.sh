#!/bin/bash

scpid=$(ps -C php -f | grep index_transc.php | awk '{print $2}')
if [[ -z ${scpid} ]] ; then
	echo 'Script not running!'
	php /app/application/models/index_transc.php >> /var/log/index_transc.log
else
	echo 'Script already running...'
fi