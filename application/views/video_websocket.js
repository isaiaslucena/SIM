//websocket listeners
socket.on('get_queue_crop', function(data) {
	if ($('#queuecroplist').hasClass('noitems') == false) {
		qcroplist = $('#queuecroplist').children();
		qcroplistd = $('#queuecroplistdone').children();

		if (data.queue.length > 0) {
			lastqueuelist = qcroplist[qcroplist.length - 1];
			lastqueuelistd = qcroplistd[qcroplistd.length - 1];
			lastdata = data.queue[data.queue.length - 1];
			lastdataarr = [lastdata];

			lastqueuelistdid = parseInt($(lastqueuelistd).attr('id').replace(/[a-z]/g,''));
			lastdataid = parseInt(lastdata.id);

		 	if (lastqueuelistdid != lastdataid) {
				queuecropdata(lastdataarr);
			}
		}
	}
});

socket.on('get_stopped_channels', function(data) {
	stoppedchannels(data);
});