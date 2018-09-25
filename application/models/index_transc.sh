#!/bin/bash

scpid=$(ps aux | grep index_transc.php | tail -n 1 | awk '{print $2}')
if [[ -z ${scpid} ]] ; then
	echo 'Script not running!'
	php /app/application/models/index_transc.php >> /var/log/index_transc.log
else
	echo 'Script already running...'
fi