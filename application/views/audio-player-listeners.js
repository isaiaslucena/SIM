//PLAYER
$('#btnnight').click(function(event) {
	if (nightmode) {
		$('body').css('background-color', '#F6F6F6');
		$('h1, h2, h3, h4, h5, h6, h7').css('color', '#000000');
		$('.list-group-item').css('background-color','#FFFFFF');
		$('.list-group-item .active').css('background-color','##337ab7');
		$('span').css('color', '#000000');
		$('label').css('color', '#000000');
		nightmode = false;
	} else {
		$('body').css('background-color', '#222222');
		$('h1, h2, h3, h4, h5, h6, h7').css('color', '#EEEEEE');
		$('.list-group-item').css('background-color','#272727');
		$('.list-group-item .active').css('background-color','##337ab7');
		$('span').css('color', '#EEEEEE');
		$('label').css('color', '#EEEEEE');
		nightmode = true;
	}
});

$('.input-group.date').datepicker({
	todayBtn: "linked",
	language: "pt-BR",
	format: "dd/mm/yyyy",
	keyboardNavigation: false,
	todayHighlight: true,
	autoclose: true
});

$('.input-group.date').on("changeDate", function() {
	selecteddate = $('.input-group.date').datepicker('getDate');
	sday = selecteddate.getDate();
	sday = ('0' + sday).slice(-2);
	smonth = (selecteddate.getMonth() + 1);
	smonth = ('0' + smonth).slice(-2);
	syear = selecteddate.getFullYear();
	selectedformdate = syear+'-'+smonth+'-'+sday;

	seldatei = $('#seldate').val();
	seldateiarr = seldatei.split("/");
	selday = seldateiarr[0];
	selmonth = seldateiarr[1];
	selyear = seldateiarr[2];
	selformdate = selyear+'-'+selmonth+'-'+selday;

	selectchannel(selformdate);
});

$('#selchannels').change(function(event) {
	selvalue = event.target.value;
	selvalarr1 = selvalue.split(':');
	selvalarr2 = selvalarr1[1].split('_');
	vsource = selvalarr1[0];
	channel = selvalarr2[0];
	state = selvalarr2[1];

	getlistchannel(vsource, selformdate, channel, state);

	datetoday = new Date();
	tday = datetoday.getDate();
	tday = ('0'+tday).slice(-2);
	tmonth = (datetoday.getMonth() + 1);
	tmonth = ('0'+tmonth).slice(-2);
	tnmonharr = datetoday.toString().split(' ');
	tnmonth = tnmonharr[1];
	tyear = datetoday.getFullYear();
	thour = datetoday.getHours();
	thour = ('0'+thour).slice(-2);
	tminutes = datetoday.getMinutes();
	tminutes = ('0'+tminutes).slice(-2);
	tseconds = datetoday.getSeconds();
	tseconds = ('0'+tseconds).slice(-2);
	datetodayf = new Date(tyear+'-'+tmonth+'-'+tday+'T00:00:00');
	datasel = new Date(selformdate+'T00:00:00');

	if (datasel < datetodayf) {
		todaydatesel = false;
	} else {
		todaydatesel = true;
	}

	// console.log(todaydatesel);
	if (todaydatesel) {
		$(function() {
			function refreshlist(rvsource, rdate, rchannel, rstate) {
				$.post('proxy',
					{address: '<?php echo str_replace('sim.','video.',base_url('video/getlist/'))?>' + rvsource + '/' + rdate + '/' + rchannel + '/' + rstate},
					function(data, textStatus, xhr) {
						playlistv = $('.list-group').children();
						lastvplaylist = playlistv[playlistv.length-1].lastChild.innerText;
						lastvplaylistsrc = playlistv[playlistv.length-1].lastChild.dataset.vsrc;
						lastvplaylistid = playlistv[playlistv.length-1].lastChild.id;
						lastvplaylistidn = Number(lastvplaylistid.replace('vspan', '')) + 1;
						lastvarraytm = data[data.length-1].replace(".mp3","");

						if (lastvplaylist != lastvarraytm) {
							$('#'+lastvplaylistid).parent().removeClass('disabled');
							$('#'+lastvplaylistid).css('cursor', 'pointer');
							html =	'<a id="vbtn'+lastvplaylistidn+'" class="list-group-item disabled">'+
												'<div class="checkbox checkbox-warning pull-left">'+
													'<input id="chbx'+lastvplaylistidn+'" data-aid="vbtn'+lastvplaylistidn+'" type="checkbox">'+
													'<label for="chbx'+lastvplaylistidn+'" data-aid="vbtn'+lastvplaylistidn+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+lastvplaylistidn+'" data-aid="vbtn'+lastvplaylistidn+'" data-vsrc="'+vsource+'">'+lastvarraytm+'</span>'+
											'</a>';
							nextvideo.append(html);
						}
					}
				);
			}

			setInterval(function() {
				refreshlist(vsource, selformdate, channel, state);
			}, 30000);
		});
	}
});

$('#miclient').blur(function(event) {
	cropfpr = $('#miprogram').val();
	cropfcl = $('#miclient').val();
	downfilename = cropfday+'.'+cropfmonth+'-'+cropfch+'-'+cropfst+'-'+cropfpr+'-'+cropfcl+'.mp3';
	$('#mbtnvdown').attr('download', downfilename);
});

$('#btntran').click(function(event) {
	cfile = videotitle.text();
	cfilearr = cfile.split("_");
	cfiledate = cfilearr[0];
	cfiletime = cfilearr[1].replace(new RegExp("-", 'g'), ":");
	cfilechannel = cfilearr[2];

	jfilechann = channelname(cfilechannel);
	if (jfilechann == "TV Cultura") {
		cfilestate = "SP";
	} else {
		cfilestate = cfilearr[3];
	}
	cfilesource = "\"" + jfilechann + " - " + cfilestate + "\"";
	cfiledatetime = cfiledate + " " + cfiletime;
	cfilestimestamp = new Date(String(cfiledatetime));
	cfilesstimestamp = new Date(String(cfiledatetime));
	cfileetimestamp = new Date(String(cfiledatetime));
	cfiletimestampt = cfilestimestamp.getTime();
	// cfiletimestampt = cfileetimestamp.setMinutes(cfileetimestamp.getMinutes() - 5);
	cfiletstamp = cfileetimestamp.setMinutes(cfileetimestamp.getMinutes() + 10);
	cropstartts = cropstarts;
	cropendts = cropends;
	cfiletstampst = cfilestimestamp.setSeconds(cfilestimestamp.getSeconds() + Number(cropstartts));
	cfiletstampet = cfilesstimestamp.setSeconds(cfilesstimestamp.getSeconds() + Number(cropendts));

	hstorydates = new Date(cfiletimestampt);
	hstorydatee = new Date(cfiletstamp);
	hwordtimes = new Date(cfiletstampst);
	hwordtimee = new Date(cfiletstampet);

	// $('#transctpm').text(null);
	$('#stransctm').val(null);
	$('#ptransctm').val(null);
	$('#transctm').val(null);
	$.post('/pages/get_tvstories',
		{
			source: cfilesource,
			ststartdate: cfiletimestampt,
			stenddate: cfiletstamp,
		},
		function(data, textStatus, xhr) {
			console.log(data);
			if (data.response.docs.length > 0) {
				var textpw = "";

				$.each(data.response.docs, function(index, vald) {
					if (Array.isArray(vald.text_t)) {
						textpw += vald.text_t[0];
					}
				});

				// $('#transctpm').text(textpw);
				$('#stransctm').val(data.response.docs[0].source_s);
				$('#ptransctm').val(data.response.docs[0].name_s);
				$('#transctm').val(textpw);
			}
		}
	);

	$('.transcmodal').modal('show');
});

// window.addEventListener("orientationchange", function() {
// 	videoplay = $('#vvideo');
// 	worientation = window.orientation;
// 	if (worientation == 90 || worientation == -90) {
// 		// videoplay[0].webkitRequestFullscreen();
// 		vfullscreen('vvideo');
// 	} else {
// 		// document.webkitExitFullscreen();
// 		vfullscreen('vvideo');
// 	}
// }, false);

// audioel.click(function(event) {
// 	audioelid = event.target.id;
// 	vvideosrc = event.target.src;
// 	if (vvideosrc.length != 0) {
// 		playpausevideo(audioelid);
// 	}
// });

// videomel.click(function(event) {
// 	audioelid = event.target.id;
// 	vvideosrc = event.target.src;
// 	if (vvideosrc.length != 0) {
// 		playpausevideo(audioelid);
// 	}
// });

// videojcmel.click(function(event) {
// 	audioelid = event.target.id;
// 	vvideosrc = event.target.src;
// 	if (vvideosrc.length != 0) {
// 		playpausevideo(audioelid);
// 	}
// });

// audioel.dblclick(function(event) {
// 	audioelid = event.target.id;
// 	videofulls = document.webkitFullscreenEnabled;
// 	// videofullsel = document.webkitFullscreenElement;
// 	vvideosrc = event.target.src;
// 	if (vvideosrc.length != 0) {
// 		vfullscreen(audioelid);
// 	}
// });

// videomel.dblclick(function(event) {
// 	audioelid = event.target.id;
// 	videofulls = document.webkitFullscreenEnabled;
// 	vvideosrc = event.target.src;
// 	if (vvideosrc.length != 0) {
// 		vfullscreen(audioelid);
// 	}
// });

// videojcmel.dblclick(function(event) {
// 	audioelid = event.target.id;
// 	videofulls = document.webkitFullscreenEnabled;
// 	vvideosrc = event.target.src;
// 	if (vvideosrc.length != 0) {
// 		vfullscreen(audioelid);
// 	}
// });

// $('.vbutton').click(function(event) {
// 	playpausevideo('aaudio');
// });

$("#btnplay").click(function() {
	playpausevideo('aaudio');
});

$("#btnstop").click(function() {
	audioel[0].pause();
	audioel[0].currentTime = 0;
	$("#ipause").addClass('hidden');
	$("#iplay").removeClass('hidden');
});

$("#btnrn").click(function() {
	audioel[0].playbackRate=1;
});

$("#btnrs").click(function() {
	audioel[0].playbackRate-=0.1;
});

$("#btnrf").click(function() {
	audioel[0].playbackRate+=0.65;
});

$("#btnvol").click(function() {
	if (audioel[0].muted) {
		$("#btnvol").removeClass('btn-danger');
		$("#btnvol").addClass('btn-default');
		audioel[0].muted = false;
	} else {
		$("#btnvol").removeClass('btn-default');
		$("#btnvol").addClass('btn-danger');
		audioel[0].muted = true;
	}
});

$("#btnvolm").click(function() {
	audioel[0].volume-=0.5;
});

$("#btnvolp").click(function() {
	audioel[0].volume+=0.5;
});

$('#btnfull').click(function(event) {
	vfullscreen('vvideo');
});

audioel.on('loadedmetadata', function() {
	vvideosrc = audioel[0].currentSrc;
	if (vvideosrc.match(vvideosrcsearch) == null) {
		vduration = audioel[0].duration;

		vdurtime.text(sectostring(vduration));
	}
});

audioel.on('timeupdate', function() {
	if (vvideosrc.match(vvideosrcsearch) == null) {
		currentPos = audioel[0].currentTime;
		maxduration = audioel[0].duration;
		percentage = 100 * currentPos / maxduration;

		currentPosh = ('0' + Math.floor(currentPos / 60 / 60)).slice(-2);
		currentPosm = ('0' + Math.floor(currentPos - currentPosh * 60)).slice(-2);
		currentPoss = ('0' + Math.floor(currentPos - currentPosm * 60)).slice(-2);
		currentPossmss = (currentPos * 100 / 100).toFixed(3);
		currentPossms = currentPossmss.split(".");

		$('.timeBar').css('width', percentage+'%');
		vcurrtime.text(sectostring(currentPos));

		audioelBuffer();
		if (currentPos == maxduration) {
			cbautoplay = $('#checkaplay').prop('checked');
			if (cbautoplay) {
				videolist = $('.list-group').children();
				activevideo = videotitle.text();
				activevideol = $('.list-group-item.active');
				nactivevideoid = activevideol[0].nextElementSibling.id
				nextvideol = document.getElementById(nactivevideoid)
				nextvideoname = nextvideol.lastChild.innerText;
				nextvideosrc = nextvideol.lastChild.dataset.vsrc;

				audioel.attr({
					src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + nextvideosrc + '_' + nextvideoname
				});
				videotitle.text(nextvideoname);

				$('.list-group').children().removeClass('active');
				document.getElementById(nactivevideoid).className += ' active';
			}
		}
	}
});

$('.progressBar').mousedown(function(e) {
	timeDrag = true;

	if (joinvideos) {
		filenarr = filesjoined[0].file.split("_");
		vsourcefile = filenarr[0];
	} else {
		vfile = videotitle.text()
		vsourcefile = $("span:contains('"+vfile+"')").data('vsrc');
	}

	audioel[0].pause();

	$("#ipause").addClass('hidden');
	$("#iplay").removeClass('hidden');
	updatebar(e.pageX);
});

$(document).mouseup(function(e) {
	if (timeDrag) {
		vfile = videotitle.text();
		vsourcefile = videotitle.attr('data-vsrc');
		timeDrag = false;

		$("#iplay").addClass('hidden');
		$("#ipause").removeClass('hidden');
		audioel[0].play();
		updatebar(e.pageX);
	}
});

$(document).mousemove(function(e) {
	if (timeDrag) {
		updatebar(e.pageX);
	}
});

progressbar.hover(function(event) {
	$('.tooltiptime').fadeIn("fast");
}, function() {
	$('.tooltiptime').fadeOut("fast");
})
.mousemove(function(event) {
	var barHeight = progressbar.height();
	var barPosition = progressbar.position();
	var barPositionoff = progressbar.offset();
	var maxduration = audioel[0].duration;
	ttimeleft = event.pageX - 52;
	ttimetop = barPositionoff.top - barHeight + 10;
	$('.tooltiptime').css({
		'top': ttimetop+"px",
		'left':  ttimeleft + "px"
	});
	updateTimetooltip(event.pageX);
});

$(document).keypress(function(event) {
	if (event.which == 32) {
		playpausevideo('aaudio');
	}
});

$(document).keydown(function(event) {
	if(event.ctrlKey && event.which == 37) {
		seektime = audioel[0].currentTime-1;
		audioel[0].currentTime = seektime;
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		updatebarkeyb(seektime);
	} else if(event.ctrlKey && event.which == 39){
		seektime = audioel[0].currentTime+1;
		audioel[0].currentTime = seektime;
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		updatebarkeyb(seektime);
	} else if (event.which == 37) {
		seektime = audioel[0].currentTime-0.04;
		audioel[0].currentTime = seektime;
		updatebarkeyb(seektime);
	} else if (event.which == 39) {
		seektime = audioel[0].currentTime+0.04;
		audioel[0].currentTime = seektime;
		updatebarkeyb(seektime);
	}
});

$(document).keyup(function(event) {
	if (event.ctrlKey && event.which == 37 || event.ctrlKey && event.which == 39) {
		$("#iplay").addClass('hidden');
		$("#ipause").removeClass('hidden');
	}
});

$('.list-group').click(function(event) {
	cfileid = event.target.id;
	elclick = event.target.tagName;
	aid = $(event.target).attr('data-aid');
	disclass = $('#'+aid).hasClass('disabled');
	if (disclass == false) {
		if (elclick == "SPAN" || elclick == "H4") {
			joinvideosclk - false;
			cfilename = event.target.innerText;
			cfilevsource = event.target.dataset.vsrc;

			$('.vbutton').css('display', 'none');
			$('.vbutton').removeClass('paused');

			audioel.attr({
				poster: '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+cfilevsource+'_'+cfilename+'/001',
				src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + cfilevsource + '_' + cfilename
			});

			if (cfilevsource.replace(/[0-9]/g, '') != 'cagiva') {
				audioel[0].pause();

				loadingthumbs();
			} else {
				audioel[0].play();
			}

			videotitle.text(cfilename);
			videotitle.attr('data-vsrc', cfilevsource);
			videotitle.css('font-size', '30px');
			mobileconf();
			$('.list-group').children().removeClass('active');
			event.target.parentElement.className += ' active';
			joinvideos = false;
		} else if (elclick == "INPUT") {
			ccbjoincrop = $('#checkjoincrop').prop('checked');
			if (ccbjoincrop) {
				swal({
					title: 'Escolha somente uma opção!',
					closeOnClickOutside: false,
					closeOnEsc: false,
					buttons: {
						cancel: false,
						confirm: true,
					}
				});
				$('#'+cfileid).prop('checked', false);
				$('#checkjoincrop').bootstrapToggle('off');
			} else {
				cvbtnid = $(this).parent().attr('id');
				cfilenamei = event.target.dataset.vfile;
				cfilevsourcei = event.target.dataset.vsrc;
				vfilenamei = cfilenamei+'.mp4';

				joinfiles(cfileid, cfilevsourcei, vfilenamei, cvbtnid);
				console.log(joinvideos);
			}
		}
	}
});

$('#checkjoincrop').change(function() {
	joinchkbx = $('.list-group input:checked').length > 0;
	cgcbjoincrop = $('#checkjoincrop').prop('checked');
	if (joinchkbx) {
		swal({
			title: 'Escolha somente uma opção!',
			closeOnClickOutside: false,
			closeOnEsc: false,
			buttons: {
				cancel: false,
				confirm: true,
			}
		});
		$('#checkjoincrop').bootstrapToggle('off');
		$('input').prop("checked", false);
	} else if (!cgcbjoincrop) {
		joincropvideos = false;
		cropfilestojoin = [];
	} else if (cgcbjoincrop) {
		swal({
			title: 'Atenção!',
			text: 'A partir de agora os cortes serão armazenados.',
			buttons: {
				cancel: false,
				confirm: true,
			}
		});
	}
})

getradios();
setTimeout(function() {getradios()}, 10000);