#!/bin/bash

tmppath="/tmp"
queuefile=${tmppath}"/queue_crop.json"
cropinfofile=${tmppath}"/crop_info.json"
cropprogressfile=${tmppath}"/crop_progress.json"

w=1
while [[ ${w} -eq 1 ]] ; do
	#get the queue
	curl -s -o "${queuefile}" "http://sim.intranet.dataclip/api/get_queue_crop_todo"

	queuefilec=$(jq --raw-output ".queue | length" "${queuefile}")
	if [[ ${queuefilec} -eq 0 ]] ; then
		echo "No files in queue!"
	else
		echo "${queuefilec}" "files in queue!"

		qid=$(jq --raw-output ".queue[0].id" "${queuefile}")
		qid_user=$(jq --raw-output ".queue[0].id_user" "${queuefile}")
		qfilename=$(jq --raw-output ".queue[0].filename" "${queuefile}")
		qsource=$(jq --raw-output ".queue[0].source" "${queuefile}")
		qcropstart=$(jq --raw-output ".queue[0].crop_start" "${queuefile}")
		qcropend=$(jq --raw-output ".queue[0].crop_end" "${queuefile}")
		qtsadd=$(jq --raw-output ".queue[0].ts_add" "${queuefile}")
		qtsstart=$(jq --raw-output ".queue[0].ts_start" "${queuefile}")
		qtsend=$(jq --raw-output ".queue[0].ts_end" "${queuefile}")
		qcropfilename=$(jq --raw-output ".[][0].crop_filename" "${queuefile}")
		qcropid=$(jq --raw-output ".[][0].crop_id" "${queuefile}")

		qfinalfilename=${qsource}_${qfilename}
		qcstmp=$(date -u -d @$qcropstart +'%H-%M-%S.%N')
		qcropstartstr=$(echo ${qcstmp:0:12})
		qdur=$(echo "${qcropend}" - "${qcropstart}" | bc)

		echo "Crop file" "${qfinalfilename}..."
		#send to crop
		curl -s -o "${cropinfofile}" "http://video.intranet.dataclip/video/cropvideo/"${qfinalfilename}"/"${qcropstartstr}"/"${qdur}
		sleep 1

		cid=$(jq --raw-output ".id" "${cropinfofile}")
		cfilename=$(jq --raw-output ".cropfilename" "${cropinfofile}")

		now=$(date +%s)
		curl -s "http://sim.intranet.dataclip/api/update_queue_crop/crop_id/"${cid}"/"${qid}
		curl -s "http://sim.intranet.dataclip/api/update_queue_crop/ts_start/"${now}"/"${qid}
		croppercent=0
		while [[ "${croppercent}" -lt 99 ]] ; do
			cropprogress=$(curl -s "http://video.intranet.dataclip/video/cropprogress/"${cid}"/"${qdur})
			croppercent=$(echo "${cropprogress}" | jq --raw-output ".percent")
			sleep 1
		done
		echo "Done!"
		now=$(date +%s)
		curl -s "http://sim.intranet.dataclip/api/update_queue_crop/ts_end/"${now}"/"${qid}
		sleep 3
		curl -s "http://sim.intranet.dataclip/api/update_queue_crop/crop_filename/"${cfilename}"/"${qid}
	fi
	sleep 5
done
