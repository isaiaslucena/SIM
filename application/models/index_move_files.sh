#!/bin/bash

n=1
#i=0
while [ $n -eq 1 ] ; do
	docker exec -it sim php /app/application/models/index_move_files.php
	echo
	/applications/SIM/application/models/timecount.sh 60
	#sleep 300
	echo
done
