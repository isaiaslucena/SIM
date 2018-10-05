function backToTop() {
	var scrollTop = $(window).scrollTop();
	if (scrollTop > scrollTrigger) {
		$('#back-to-top').fadeIn('fast');
	} else {
		$('#back-to-top').fadeOut('fast');
	}
}

function loadpn(flow, clbtn, sc, type) {
	loadp = $(clbtn);
	loadp.children('i').css('display', 'inline-block');

	iddiv = loadp.attr('data-iddiv');
	iddivn = Number(iddiv.replace('div', ''));
	idsource = loadp.attr('data-idsource');

	if (flow == 'previous') {
		position = 'after';
		startdate = loadp.attr('data-startdate');
		slidep = 'slideDown';
	} else if (flow == 'next') {
		position = 'before';
		startdate = loadp.attr('data-enddate');
		slidep = 'slideUp';
	}

	if (sc == 'local') {
		gurl = window.location.origin+'/pages/get_radio/'+idsource+'/'+encodeURI(startdate)+'/'+flow;
	} else if (sc == 'novo') {
		gurl = window.location.origin+'/pages/get_radio_novo/'+idsource+'/'+encodeURI(startdate)+'/'+flow;
	}

	$.get(gurl, function(data) {
		loadp.children('i').css('display', 'none');
		numfound = data.response.numFound;
		if (numfound == 0) {
			warnhtml =	'<div class="alert alert-warning" role="alert">'+
										'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
										'<span class="sr-only">Error:</span>'+
										'<?php echo get_phrase("no_more_files"); ?>'+'!'+
									'</div>';
			eval('$("#'+iddiv+'")'+'.'+position+'(warnhtml)');

			setTimeout(function() {
				$('div.alert.alert-warning').fadeOut('slow');
			}, 3000);
		} else {
			did = data.response.docs[0].id_i;
			dsourceid = data.response.docs[0].source_id_i;
			dsource = data.response.docs[0].source_s;
			dmuarr = data.response.docs[0].mediaurl_s.split('_');
			dmedia = data.response.docs[0].mediaurl_s;
			dstartdate = data.response.docs[0].starttime_dt;
			denddate = data.response.docs[0].endtime_dt;
			dcontent = data.response.docs[0].content_t[0];
			dtimes = JSON.parse(data.response.docs[0].times_t[0]);

			if (sc == 'local' && type == 'audio') {
				dmediaurl = window.location.origin.replace('sim.', 'radio.')+'/index.php/radio/getmp3?source='+dmuarr[0]+'&file='+dmedia.replace(dmuarr[0]+'_', '');
				var sd = new Date(dstartdate.replace('Z',''));
				var ed = new Date(denddate.replace('Z',''));
			} else if (sc == 'novo' && type == 'audio') {
				dmediaurl = dmedia;
				var sd = new Date(dstartdate);
				var ed = new Date(denddate);
			} else if (sc == 'local' && type == 'video') {
				var sd = new Date(dstartdate.replace('Z',''));
				var ed = new Date(denddate.replace('Z',''));
				dmediaurl = window.location.origin.replace('sim.', 'video.')+'/video/getvideo?source='+dmuarr[0]+'&file='+dmedia.replace(dmuarr[0]+'_', '');
			} else if (sc == 'novo' && type == 'video') {
				dmediaurl = dmedia;
				var sd = new Date(dstartdate);
				var ed = new Date(denddate);
			}

			var sday = sd.getDate();
			var sday = ('0' + sday).slice(-2);
			var smonth = (sd.getMonth() + 1);
			var smonth = ('0' + smonth).slice(-2);
			var syear = sd.getFullYear();
			var shour = sd.getHours();
			var shour = ('0' + shour).slice(-2);
			var sminute = sd.getMinutes();
			var sminute = ('0' + sminute).slice(-2);
			var ssecond = sd.getSeconds();
			var ssecond = ('0' + ssecond).slice(-2);
			var dfstartdate = sday+'/'+smonth+'/'+syear+' '+shour+':'+sminute+':'+ssecond;

			var eday = ed.getDate();
			var eday = ('0' + eday).slice(-2);
			var emonth = (ed.getMonth() + 1);
			var emonth = ('0' + emonth).slice(-2);
			var eyear = ed.getFullYear();
			var ehour = ed.getHours();
			var ehour = ('0' + ehour).slice(-2);
			var eminute = ed.getMinutes();
			var eminute = ('0' + eminute).slice(-2);
			var esecond = ed.getSeconds();
			var esecond = ('0' + esecond).slice(-2);
			var dfenddate = eday+'/'+emonth+'/'+eyear+' '+ehour+':'+eminute+':'+esecond;
			var dfendtime = ehour+':'+eminute+':'+esecond;

			newdivid += 1;
			newdividn = iddiv+'-'+newdivid;

			divclone = $('#'+iddiv).clone(true);

			divclone.removeClass('panel-default');
			// divclone.removeClass('collapse');
			divclone.addClass('panel-info');
			// divclone.addClass('collapse');
			// divclone.addClass('in');
			divclone.css('display', 'none');
			divclone.attr('id', newdividn);
			divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> '+dsource+' | '+dfstartdate+' - '+dfendtime);
			divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
			divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
			divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-startdate', dstartdate);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-enddate', denddate);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-startdate', dstartdate);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-enddate', denddate);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-iddoc', did);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
			divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
			divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
			divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb')+'-'+newdivid);
			divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-iddoc', did);
			divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-startdate', dfstartdate);
			divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-enddate', dfenddate);
			divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);

			if (type == 'audio') {
				divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('src', dmediaurl);
				divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('id', iddiv.replace('div', 'paudio')+'-'+newdivid);
				divclone.children('.panel-body').children('.col-lg-12').children('.ptext').addClass('noscrolled');
				divclone.children('.panel-body').children('.col-lg-12').children('.ptext').attr('id', iddiv.replace('div', 'ptext')+'-'+newdivid);
				divclone.children('.panel-body').children('.col-lg-12').children('.ptext').html(null);
			} else if (type == 'video') {
				divclone.children('.panel-body').children('.row').children('.col-lg-5').children('video').attr('src', dmediaurl);
				divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').addClass('noscrolled');
				divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').attr('id', iddiv.replace('div', 'ptext')+'-'+newdivid);
				divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').text(dcontent);
			}

			eval('$("#'+iddiv+'").'+position+'(divclone);');

			addtimes("#"+iddiv.replace("div", "ptext")+'-'+newdivid, dtimes);
			scrolltokeyword();
			$("#"+newdividn).slideDown("slow");
		}
	});
};

function addtimes(idptext, times) {
	$.each(times, function(index, val1) {
		$.each(val1.words, function(index, val2) {
			wbegin = parseFloat(String(val2.begin).slice(0, 5));
			wend = parseFloat(val2.end);
			wdur = String(wend - wbegin).slice(0, 5);
			wspan = '<span data-dur="'+wdur+'" data-begin="'+wbegin+'">'+val2.word+'</span> ';
			$(idptext).append(wspan);
		});
	});
};

function scrolltokeyword(mtype) {
	if (mtype == 'audio') {
		imtype = 'paudio';
	} else {
		imtype = 'pvideo';
	}

	ptexts = $('.ptext.text-justify.noscrolled');
	ptextsl = ptexts.length;
	$.each(ptexts, function(index, val) {
		cpid = $(val).attr('id');
		scpid = '#'+cpid;

		keywordxarr = [];
		kc = 0;
		$.each(keywordarr, function(index, valk) {
			str = '<span[^>]+>'+valk+'<\/span> ';
			keywordxarr.push(str);
			kc++;
		});
		keywordrgx = keywordxarr.join('');
		rgxkw = new RegExp(keywordrgx, "ig");

		pbodyhtml = $(val).html();
		found = pbodyhtml.match(rgxkw);

		if (found != null) {
			cfound = found.length;
			$.each(found, function(index, val) {
				strreplace = val.replace(/<span /, '<span class="fkword" ');

				strreplace = strreplace.replace(/<span data-dur/g, '<span class="kword" data-dur');
				pbodyhtml = pbodyhtml.replace(val, strreplace);
			});
			$(val).html(pbodyhtml);

			keywfound = $(scpid+' > .fkword');
			qtkwf = keywfound.length;
			idnumb = cpid.replace(/[a-zA-Z]/g, '');
			$('#tkeyfound'+idnumb).text(qtkwf);
			$(val).scrollTo(keywfound);
			$(val).removeClass('noscrolled');

			fkeywfound = keywfound[0];
			fkeywfoundtime = parseInt($(fkeywfound).attr('data-begin')) - 0.3;
			$('#'+imtype+idnumb).on('loadedmetadata', function() {
					// setTimeout(function() {
					// 	setTimeout(function() {
					// 		$('#'+imtype+idnumb)[0].pause();
					// 		$('#'+imtype+idnumb)[0].mute = false;
					// 	}, 300);
					// 	$('#'+imtype+idnumb)[0].currentTime = fkeywfoundtime;
					// 	$('#'+imtype+idnumb)[0].mute = true;
					// 	$('#'+imtype+idnumb)[0].play();
					// }, 200);
					$('#'+imtype+idnumb)[0].currentTime = fkeywfoundtime;
			});
		}
	});
};

function startread(idpmedia, idptext, starttime = 0, mediatime = false) {
	if (mediatime) {
		$('#'+idpmedia)[0].currentTime = starttime;
	}

	var args = {
		text_element: document.getElementById(idptext),
		audio_element: document.getElementById(idpmedia),
		autofocus_current_word: document.getElementById('autofocus-current-word').checked
	};

	// console.log(args);

	ReadAlong.init(args);
};