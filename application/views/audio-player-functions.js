//PLAYER
function getradios() {
	$.post('/pages/proxy', {address: '<?php echo str_replace('sim.','radio.', base_url('index.php/radio/getstopradios'))?>'},
		function(data, textStatus, xhr) {
		radiocount = 0;
		$('#alertradiolist').html(null);
		datac = (data.length - 1);

		$.each(data, function(index, val) {
			radionamekey = Object.keys(val)[0];
			radioname = radionamekey.replace("_", " - ");
			ffmpeglastmsg = val[radionamekey].ffmpeg_last_log
			ffmpegpid = val[radionamekey].ffmpeg_PID
			html = 	'<li>'+
						'<a href="#">'+
							'<div>'+
								'<strong class="navnotstrg">'+radioname+'</strong>'+
									'<span class="pull-right text-muted"><em class="navnotem">'+ffmpegpid+'</em></span>'+
								'</div>'+
							'<div class="rruname">'+ffmpeglastmsg+'</div>'+
						'</a>'+
					'</li>';
			$('#alertradiolist').append(html);
			if (radiocount < datac) {
				$('#alertradiolist').append('<li class="divider"></li>');
			}
			radiocount += 1;
		});

		if (radiocount > 0) {
			$('#alertradiobnum').text(radiocount);
			$('#alertradiobnum').fadeIn('fast');
		} else {
			$('#alertradiobnum').fadeOut('fast');
			$('#alertradiobnum').text(radiocount);
			fhtml = '<li>'+
								'<a class="text-center" href="#">'+
									'<strong>Nenhum alerta de rádio! </strong>'+
								'</a>'+
							'</li>';
			$('#alertradiolist').append(fhtml);
		}
	});
};

function selecteddate(seldddate) {
	$.post('proxy',
		{address: '<?php echo str_replace('sim.','video.', base_url('video/getlist/'))?>' + vsource + '/' + seldddate + '/' + channel + '/' + state},
		function(data, textStatus, xhr) {
			lastvideo = data[0].replace(".mp3", "");
			lastvarray = data[data.length-1].replace(".mp3","");
			audioel.removeAttr('loop');
			audioel.attr({
				src: '<?php echo str_replace("sim.","video.",base_url())?>video/getvideo/'+vsource+'_'+lastvideo
			});

			videotitle.text(lastvideo);
			videotitle.css('font-size', '30px');
			nextvideo.html(null);
			$.each(data, function(index, val) {
				file = val.replace(".mp3","");
				if (file == lastvideo) {
					html =	'<a id="vbtn'+index+'" class="list-group-item active">'+
										'<div class="checkbox checkbox-warning pull-left">'+
											'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
											'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
										'</div>'+
										'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
									'</a>';
				} else {
					html =	'<a id="vbtn'+index+'" class="list-group-item">'+
										'<div class="checkbox checkbox-warning pull-left">'+
											'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
											'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
										'</div>'+
										'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
									'</a>';
				}

				nextvideo.append(html);
			});
		}
	);
};

function selectchannel(date) {
	tvch.html(null);
	tvch.selectpicker({title: 'Aguarde...'}).selectpicker('render');
	tvch.selectpicker('refresh');

	$.post('proxy',
		{address: '<?php echo str_replace('sim.','radio.',base_url('index.php/radio/getchannels/'))?>' + date},
		function(data, textStatus, xhr) {
			tvch.html(null);
			$.each(data, function(elo, indexo) {
				if (elo.replace(/[0-9]/g, '') == "cagiva") {
					indexo.forEach(function(ela, indexa) {
						switch (ela) {
							case "FUTURA_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "NBR_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "GLOBO_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "RECORD_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "TV-JUSTICA_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "TV-BRASIL_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "TV-GAZETA_SP":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "RURAL_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "BAND_SP":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "GLOBO_SP":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "TV-ALESP_SP":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "TV-BRASIL_SP":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "RECORD_SP":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "SPORTV_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
							case "AVULSO_RJ":
								html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
								tvch.append(html);
								break;
						}
					});
				} else {
					indexo.forEach(function(ela, indexa) {
						html = '<option data-vsrc="' + elo + '" data-vchn="' + ela + '">' + elo +":" + ela + '</option>';
						tvch.append(html);
					});
				}
			});

			tvch.selectpicker({title: 'Selecione um veículo'}).selectpicker('render');
			tvch.prop('disabled', false)
			tvch.selectpicker('refresh');
		}
	);
};

function getlistchannel(selglvsource, selgldate, selglchannel, selglstate) {
	$.post('proxy',
		{address: '<?php echo str_replace('sim.','radio.',base_url('index.php/radio/getfiles/')); ?>?source='+selglvsource+'&date='+selgldate+'&radio='+selglchannel+'_'+selglstate},
		function(data, textStatus, xhr) {
			if (data.length == 1) {
				firstvideo = data[0].replace(".mp3", "");
				lastvideo = firstvideo;
				lastvarray = data[0].replace(".mp3","");
			} else {
				firstvideo = data[0].replace(".mp3", "");
				lastvideo = data[data.length-2].replace(".mp3", "");
				lastvarray = data[data.length-1].replace(".mp3","");
			}

			$('.vbutton').css('display', 'none');
			$('.vbutton').removeClass('paused');

			$('#btnplay').removeClass('disabled');
			$('#btnstop').removeClass('disabled');
			$('#btnrn').removeClass('disabled');
			$('#btnrs').removeClass('disabled');
			$('#btnrf').removeClass('disabled');
			$('#btncstart').removeClass('disabled');
			$('#btncend').removeClass('disabled');
			$('#btnvol').removeClass('disabled');
			$('#btnvolm').removeClass('disabled');
			$('#btnvolp').removeClass('disabled');
			$('#btnfull').removeClass('disabled');

			$('#btnplay').removeAttr('disabled');
			$('#btnstop').removeAttr('disabled');
			$('#btnrn').removeAttr('disabled');
			$('#btnrs').removeAttr('disabled');
			$('#btnrf').removeAttr('disabled');
			$('#btncstart').removeAttr('disabled');
			$('#btncend').removeAttr('disabled');
			$('#btnvol').removeAttr('disabled');
			$('#btnvolm').removeAttr('disabled');
			$('#btnvolp').removeAttr('disabled');
			$('#btnfull').removeAttr('disabled');
			$('#checkaplay').bootstrapToggle('enable');

			$('#btntran').removeClass('disabled');
			$('#btntran').removeAttr('disabled');
			$('#checkjoincrop').bootstrapToggle('enable');

			if (todaydatesel === false) {
				// console.log('Data selecionada menor que hoje');

				audioel.attr({
					src: '<?php echo str_replace("sim.","radio.",base_url())?>index.php/radio/getmp3?source='+vsource+'&file='+firstvideo
				});

				audioel[0].play();

				videotitle.text(firstvideo);
				videotitle.attr('data-vsrc', selglvsource);
				videotitle.css('font-size', '30px');
				nextvideo.html(null);
				$.each(data, function(index, val) {
					file = val.replace(".mp3","");
					if (file == firstvideo) {
						html =	'<a id="vbtn'+index+'" class="list-group-item active">'+
											'<div class="checkbox checkbox-warning pull-left">'+
												'<input id="chbx'+index+'" name="checkbxx" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<button class="btn btn-sm btn-default"><i class="fa fa-book"></i></button>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					} else {
						html =	'<a id="vbtn'+index+'" class="list-group-item">'+
											'<div class="checkbox checkbox-warning pull-left">'+
												'<input id="chbx'+index+'" name="checkbxx" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<button class="btn btn-sm btn-default"><i class="fa fa-book"></i></button>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					}
					nextvideo.append(html);
				});
			} else {
				audioel.attr({
					src: '<?php echo str_replace("sim.","radio.",base_url())?>index.php/radio/getmp3?source='+vsource+'&file='+lastvideo
				});

				audioel[0].play();

				videotitle.text(lastvideo);
				videotitle.attr('data-vsrc', selglvsource);
				videotitle.css('font-size', '30px');
				nextvideo.html(null);
				$.each(data, function(index, val) {
					file = val.replace(".mp3","");
					if (file == lastvideo) {
						html =	'<a id="vbtn'+index+'" class="list-group-item active">'+
											'<div class="checkbox checkbox-warning pull-left">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<button class="btn btn-sm btn-default"><i class="fa fa-book"></i></button>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					} else if (file == lastvarray) {
						html =	'<a id="vbtn'+index+'" class="list-group-item disabled">'+
											'<div class="checkbox checkbox-warning pull-left">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<button class="btn btn-sm btn-default"><i class="fa fa-book"></i></button>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'">'+file+'</span>'+
										'</a>';
					} else {
						html =	'<a id="vbtn'+index+'" class="list-group-item">'+
											'<div class="checkbox checkbox-warning pull-left">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<button class="btn btn-sm btn-default"><i class="fa fa-book"></i></button>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					}
					nextvideo.append(html);
				});
			}

			mobileconf();
		}
	);
};

jQuery.fn.scrollTo = function(elem) {
	$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
	return this;
};

function mobileconf() {
	if (isTouchDevice()) {
		$('#vtitle').css('font-size', '18px');
		$('#vnext').css('max-height', '150px');
	}
};

function isTouchDevice() {
	return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
};

function updatebar(x) {
	maxduration = audioel[0].duration;
	position = x - progressbar.offset().left;
	percentage = (100 * position) / progressbar.width();

	if (percentage > 100) {
		percentage = 100;
	}
	if (percentage < 0) {
		percentage = 0;
	}

	videotime = (maxduration * percentage) / 100;
	videotimesec = Math.floor(videotime);
	audioel[0].currentTime = videotime.toFixed(3);
	$('.timeBar').css('width', percentage+'%');

	if (joinvideos) {
		filenarr = filesjoined[0].file.split("_");
		thnsfilename = filenarr[0];
		if (thnsfilename.replace(/[0-9]/g, '') != 'cagiva') {
			var ttime = 0;
			var thumbnnf;
			var thnvdfilename;

			$.each(filesjoined, function(index, filer) {
				ttime = ttime + filer.time;
				if (videotimesec <= ttime) {
					timedif = ttime - videotimesec;
					thumbnnf = ('00' + (filer.time - timedif)).slice(-3);
					thnvdfilename = filer.file.replace(thnsfilename+"_","");
					// uptadevThumb(thnsfilename, thnvdfilename, thumbnnf);
					return false;
				}
			});
		}
	} else {
		vdfilename = videotitle.text();
		sfilename = $("span:contains('"+vdfilename+"')").data('vsrc');
	}
};

function updatebarkeyb(sec) {
	var maxduration = audioel[0].duration;
	// var position = sec - time.offset().left;
	var position = sec;
	var percentage = (position * 100) / maxduration;

	if (percentage > 100) {
		percentage = 100;
	}
	if (percentage < 0) {
		percentage = 0;
	}

	videotime = sec;
	videotimesec = Math.floor(videotime);
	thumbnum = ('00' + videotimesec).slice(-3)
	audioel[0].currentTime = videotime.toFixed(3);

	vcurrtime.text(sectostring(videotime));

	$('.timeBar').css('width', percentage+'%');
	audioelBuffer();

	if (joinvideos) {
		filenarr = filesjoined[0].file.split("_");
		thnsfilename = filenarr[0];

		var ttime = 0;
		var thumbnnf;
		var thnvdfilename;

		$.each(filesjoined, function(index, filer) {
			ttime = ttime + filer.time;
			if (videotimesec <= ttime) {
				timedif = ttime - videotimesec;
				thumbnnf = ('00' + (filer.time - timedif)).slice(-3);
				thnvdfilename = filer.file.replace(thnsfilename+"_","");
				// uptadevThumb(thnsfilename, thnvdfilename, thumbnnf);
				return false;
			}
		});
	} else {
		vdfilename = videotitle.text();
		sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
		// uptadevThumb(sfilename, vdfilename, thumbnum);
	}
};

function updateTimetooltip(x) {
	vdfilename = videotitle.text();
	sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
	var maxduration = audioel[0].duration;
	var position = x - progressbar.offset().left;
	var percentage = (100 * position) / progressbar.width();

	if (percentage > 100) {
		percentage = 100;
	}
	if (percentage < 0) {
		percentage = 0;
	}

	videotime = ((maxduration * percentage) / 100).toFixed(3);
	videotimesec = Math.floor(videotime);
	thumbnum = ('0' + videotimesec).slice(-3)
	vtooltiptime.text(sectostring(videotime));
	audioelBuffer();
};

function sectostring(secs) {
	var sec_num = parseInt(secs, 10);
	var hours   = Math.floor(sec_num / 3600);
	// var hours = (sec_num / 3600).toFixed(2);
	var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
	// var minutes = ((sec_num - (hours * 3600)) / 60).toFixed(2);
	var seconds = sec_num - (hours * 3600) - (minutes * 60);
	var mseconds = String(secs);
	var milliseconds =  mseconds.slice(-3);

	if (hours  < 10) {hours = "0" + hours;}
	if (minutes < 10) {minutes = "0" + minutes;}
	if (seconds < 10) {seconds = "0" + seconds;}
	return hours+':'+minutes+':'+seconds+'.'+milliseconds;
}

function audioelBuffer() {
	// var startBuffer = audioel[0].buffered.start(0);
	if (audioel[0].buffered.length > 0) {
		var maxduration = audioel[0].duration;
		var startBuffer = audioel[0].buffered.start(0);
		var endBuffer = audioel[0].buffered.end(0);
		// var percentageBuffer = (100 * endBuffer) / maxduration;
		var percentageBuffer = (endBuffer / maxduration) * 100;
		$('.bufferBar').css('width', percentageBuffer+'%');

		// if (endBuffer < maxduration) {
		// 	// setTimeout(startBuffer(), 500);
			// setTimeout(audioelBuffer(),500);
		// }
	}
};

function playpausevideo(videoelt) {
	vvideoelmt = $('#'+videoelt);
	if (vvideoelmt[0].paused) {
		$("#iplay").addClass('hidden');
		$("#ipause").removeClass('hidden');
		vvideoelmt[0].play();
	} else {
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		vvideoelmt[0].pause();
	}
}

function vfullscreen(videoelt) {
	vvideoelmt = $('#'+videoelt);
	if (document.webkitFullscreenEnabled) {
		if (document.webkitFullscreenElement) {
			document.webkitExitFullscreen();
		} else {
			vvideoelmt[0].webkitRequestFullscreen();
		}
	} else {
		if (!document.fullscreenElement) {
			document.documentElement.requestFullscreen();
		} else {
			if (document.exitFullscreen) {
				document.exitFullscreen();
			}
		}
	}
}

function loadingthumbs() {
	$('body').css('cursor', 'progress');

	swal({
		onOpen: () => {
			swal.showLoading()
		},
		title: "Carregando...",
		animation: false,
		allowEscapeKey: false,
		allowOutsideClick: false,
		showCancelButton: false,
		showConfirmButton: false,
	});
}

function closeloadingthumbs() {
	$('body').css('cursor', 'default');

	swal.close();

	$('#vnext').scrollTo('.active');
}