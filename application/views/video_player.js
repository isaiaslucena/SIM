//PLAYER

//Variables
var lastvideo, lastvarray, lastvarraytm, vsource, channel, state, cropstarts, cropends,
selectedformdate, selformdate, cropstart, cropend, cropdurs, cropdur, jvsource,
cropfmonth, cropfday, cropfch, cropfst, cropfpr, cropfcl, videourlmcrop, vintfile, cfilesource,
cfiletimestampt, cfiletstamp, cfiletstampst, cfiletstampet, loadingthumbs, vnextthumbf, refreshclist;

var vvideosrcsearch = "colorbar.jpg";

var ccrops = false, ccrope = false, joinvideos = false, joinvideosclk = false, selvinheta = false;
joincropvideos = false, nightmode = false, todaydatesel = false, videotransc = false;
var frompost = <?php echo isset($ssource) ? 'true' : 'false';?>;

var cropstartss = null, cropendss = null;

var filestojoin = [], filesjoined = [], cropfilestojoin = [], vbtnjoin = [], nimage = [];

var fileseq = 0, queuecroplenth = 0, queuecroplentha = 0;

var tvch = $('#selchannels'), tvdate = $('#seldate'), videoel = $('#vvideo'), videomel = $('#mvvideo'),
videoelth = $('#thvideo'), videojcmel = $('#mcjvvideo'), vvideosrc = videoel[0].currentSrc, videotitle = $('#vtitle'),
nextvideo = $('#vnext'), progressbar = $('.progressBar'), timebar = $('.timeBar'), vcurrtime = $('#currtime'),
vdurtime = $('#durtime'), vtooltiptime = $('.tooltiptime'), timerslider = $('#timeslider');

var d = new Date();
var day = d.getDate();
var day = ('0' + day).slice(-2);
var month = (d.getMonth() + 1);
var month = ('0' + month).slice(-2);
var year = d.getFullYear();
var todaydate = year+'-'+month+'-'+day;

//Functions
function getchannels() {
	$.get('<?php echo str_replace("sim.","video.", base_url("video/getstopchannels"))?>',
		function(data) {
			radiocount = 0;
			$('#alerttvlist').html(null);
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
				$('#alerttvlist').append(html);
				if (radiocount < datac) {
					$('#alerttvlist').append('<li class="divider"></li>');
				}
				radiocount += 1;
			});

			fhtml = '<li>'+
								'<a class="text-center" href="#">'+
									'<strong>Ver todos os alertas </strong>'+
									'<i class="fa fa-angle-right"></i>'+
								'</a>'+
							'</li>';
			// $('#alerttvlist').append(fhtml);

			if (radiocount > 0) {
				$('#alerttvbnum').text(radiocount);
				$('#alerttvbnum').fadeIn('fast');
			} else {
				$('#alerttvbnum').fadeOut('fast');
				$('#alerttvbnum').text(radiocount);
				fhtml = '<li>'+
									'<a class="text-center" href="#">'+
										'<strong>Nenhum alerta de tv! </strong>'+
									'</a>'+
								'</li>';
				$('#alerttvlist').append(fhtml);
			}
		}
	);
};

function channelname(name) {
	switch (name) {
		case "CULTURA-HD":
			chlname = "TV Cultura";
			break;
		case "ESPN-BRASIL":
			chlname = "ESPN Brasil";
			break;
		case "GLOBONEWS":
			chlname = "Globo News";
			break;
		case "GLOBONEWS-HD":
			chlname = "Globo News";
			break;
		case "RECORDNEWS":
			chlname = "Record News";
			break;
		case "REDETV":
			chlname = "Rede TV";
			break;
		case "SBT-HD":
			chlname = "SBT";
			break;
		case "SPORTV":
			chlname = "Sportv";
			break;
		case "TVALERJ":
			chlname = "TV ALERJ";
			break;
		case "BANDNEWS":
			chlname = "TV Band News";
			break;
		case "BAND":
			chlname = "TV Bandeirantes";
			break;
		case "BAND-HD":
			chlname = "TV Bandeirantes";
			break;
		case "TVBRASIL":
			chlname = "TV Brasil";
			break;
		case "TV-BRASIL":
			chlname = "TV Brasil";
			break;
		case "TV-CAMARA":
			chlname = "TV Camara";
			break;
		case "CULTURA":
			chlname = "TV Cultura";
			break;
		case "FORSPORTS":
			chlname = "TV Fox Sports";
			break;
		case "FUTURA":
			chlname = "TV Futura";
			break;
		case "TVGAZETA":
			chlname = "TV Gazeta";
			break;
		case "GLOBO":
			chlname = "TV Globo";
			break;
		case "GLOBO-HD":
			chlname = "TV Globo";
			break;
		case "TVJUSTICA":
			chlname = "TV Justica";
			break;
		case "NBR":
			chlname = "TV NBR";
			break;
		case "RECORD":
			chlname = "TV Record";
			break;
		case "TV-SENADO":
			chlname = "TV Senado";
			break;
		default:
			chlname = name;
	}
	return chlname;
};

function selecteddate(seldddate) {
	$.post('proxy',
		{address: '<?php echo str_replace('sim.','video.', base_url('video/getlist/'))?>' + vsource + '/' + seldddate + '/' + channel + '/' + state},
		function(data, textStatus, xhr) {
			lastvideo = data[0].replace(".mp4", "");
			lastvarray = data[data.length-1].replace(".mp4","");
			// videoel.removeAttr('loop');

			srcposter = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+vsource+'_'+lastvideo+'/001';
			srcvideo = '<?php echo str_replace("sim.","video.",base_url())?>video/getvideo/'+vsource+'_'+lastvideo;
			videoel.attr({
				poster: srcposter,
				src: srcvideo
			});

			videotitle.text(lastvideo);
			videotitle.css('font-size', '30px');
			nextvideo.html(null);
			$.each(data, function(index, val) {
				file = val.replace(".mp4","");
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

	$.get('<?php echo str_replace('sim.','video.',base_url("video/getchannels/"))?>'+date,
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

function getlistchannel(selglvsource, selgldate, selglchannel, selglstate, play) {
	$.get('<?php echo str_replace('sim.','video.',base_url("video/getlist/"))?>'+selglvsource+'/'+selgldate+'/'+selglchannel+'/'+selglstate,
		function(data, textStatus, xhr) {
			if (data.length == 1) {
				firstvideo = data[0].replace(".mp4", "");
				lastvideo = firstvideo;
				lastvarray = data[0].replace(".mp4","");
			} else if (data.length == 0) {
				nextvideo.html(null);
				for (var i = 0; i <= 15; i++) {
					if (i == 5) {
						nextvideo.append('<a class="list-group-item active">Nenhum arquivo!</a>');
					} else {
						nextvideo.append('<a class="list-group-item" style="color: white">Nenhum arquivo!</a>');
					}
				}
				return;
			} else {
				firstvideo = data[0].replace(".mp4", "");
				lastvideo = data[data.length-2].replace(".mp4", "");
				lastvarray = data[data.length-1].replace(".mp4","");
			}

			if (todaydatesel === false) {
				// console.log('Data selecionada menor que hoje');
				cvideo = firstvideo;

				videotitle.text(firstvideo);
				videotitle.attr('data-vsrc', selglvsource);
				videotitle.css('font-size', '30px');
				nextvideo.html(null);
				nextvideo.css('overflow-x', 'auto');

				$.each(data, function(index, val) {
					file = val.replace(".mp4","");
					var srcposter = '<?php echo str_replace("sim.","video.", base_url())?>video/getthumb/'+vsource+'_'+file+'/001';

					var testeimg = new Image();
					testeimg.src = srcposter;

					testeimg.onerror = function(e) {
						srcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
						$('#vnttb'+index).attr('src', srcposter);
					};

					if (file == firstvideo) {
						html =	'<a id="vbtn'+index+'" class="list-group-item active" style="height: 105px;">'+
											'<div class="pull-left vnextthumb" data-tbid="vnttb'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<img id="vnttb'+index+'" src="'+srcposter+'" style="max-height:80px">'+
											'</div>'+
											'<div class="checkbox checkbox-warning">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div><br>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					} else {
						html =	'<a id="vbtn'+index+'" class="list-group-item" style="height: 105px;">'+
											'<div class="pull-left vnextthumb" data-tbid="vnttb'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<img id="vnttb'+index+'" src="'+srcposter+'" style="max-height:80px">'+
											'</div>'+
											'<div class="checkbox checkbox-warning">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div><br>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					}
					nextvideo.append(html);
				});
			} else {
				cvideo = lastvideo;

				videotitle.text(lastvideo);
				videotitle.attr('data-vsrc', selglvsource);
				videotitle.css('font-size', '30px');
				nextvideo.html(null);

				$.each(data, function(index, val) {
					file = val.replace(".mp4","");
					filearr = file.split('_');

					var srcposter = '<?php echo str_replace("sim.","video.", base_url())?>video/getthumb/'+vsource+'_'+file+'/001';
					var testeimg = new Image();
					testeimg.src = srcposter;

					testeimg.onerror = function(e) {
						srcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
						$('#vnttb'+index).attr('src', srcposter);
					};

					if (file == lastvideo && filearr[2] != 'AVULSO') {
						html =	'<a id="vbtn'+index+'" class="list-group-item active" style="height: 105px;">'+
											'<div class="pull-left vnextthumb" data-tbid="vnttb'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<img id="vnttb'+index+'" src="'+srcposter+'" style="max-height:80px">'+
											'</div>'+
											'<div class="checkbox checkbox-warning">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
										'</a>';
					} else if (file == lastvarray && filearr[2] != 'AVULSO') {
						html =	'<a id="vbtn'+index+'" class="list-group-item disabled" style="height: 105px;">'+
											'<div class="pull-left vnextthumb" data-tbid="vnttb'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<img id="vnttb'+index+'" src="<?php echo base_url("assets/imgs/colorbar.jpg")?>" style="max-height:80px">'+
											'</div>'+
											'<div class="checkbox checkbox-warning">'+
												'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
												'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
											'</div>'+
											'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'">'+file+'</span>'+
										'</a>';
					} else {
						if (file == firstvideo && filearr[2] == 'AVULSO') {
							cvideo = firstvideo;
							html =	'<a id="vbtn'+index+'" class="list-group-item active" style="height: 105px;">'+
												'<div class="pull-left vnextthumb" data-tbid="vnttb'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<img id="vnttb'+index+'" src="'+srcposter+'" style="max-height:80px">'+
												'</div>'+
												'<div class="checkbox checkbox-warning">'+
													'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
											'</a>';
						} else {
							html =	'<a id="vbtn'+index+'" class="list-group-item" style="height: 105px;">'+
												'<div class="pull-left vnextthumb" data-tbid="vnttb'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<img id="vnttb'+index+'" src="'+srcposter+'" style="max-height:80px">'+
												'</div>'+
												'<div class="checkbox checkbox-warning">'+
													'<input id="chbx'+index+'" type="checkbox" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<label for="chbx'+index+'" data-aid="vbtn'+index+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+index+'" data-aid="vbtn'+index+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
											'</a>';
						}
					}
					nextvideo.append(html);
				});
			}

			if (play) {
				// console.log('play is true!!!!');

				csrcvideo = '<?php echo str_replace("sim.","video.",base_url())?>video/getvideo/'+vsource+'_'+cvideo

				arr = lastvideo.split('_');
				channel = arr[2];

				if (channel != 'AVULSO') {
					if (vsource.replace(/[0-9]/g, '') != 'cagiva') {
						var csrcposter = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+vsource+'_'+cvideo+'/001';

						var testeimg = new Image();
						testeimg.src = csrcposter;

						testeimg.onerror = function() {
							csrcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
						};
					} else {
						csrcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
					}
				} else {
					csrcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
				}

				$('#vnext').scrollTo('a.active');

				videoel.attr({
					poster: csrcposter,
					src: csrcvideo
				});

				if (channel != 'AVULSO') {
					if (vsource.replace(/[0-9]/g, '') != 'cagiva') {
						videoel[0].pause();

						loadingthumbs();
					} else {
						videoel[0].play();
					}
				} else {
					videoel[0].play();
				}
			}

			// getdocbymurl(vsource, cvideo);

			enablebtns();
			mobileconf();
		}
	);
};

function refreshlist(rvsource, rdate, rchannel, rstate) {
	$.get('<?php echo str_replace('sim.','video.',base_url("video/getlist/"))?>'+rvsource+'/'+rdate+'/'+rchannel+'/'+rstate,
		function(data, textStatus, xhr) {
			playlistv = $('.list-group').children();
			lastvplaylist = playlistv[playlistv.length-1].lastChild.innerText;
			lastvplaylistsrc = playlistv[playlistv.length-1].lastChild.dataset.vsrc;
			lastvplaylistid = playlistv[playlistv.length-1].lastChild.id;
			lastvplaylistidn = Number(lastvplaylistid.replace('vspan', '')) + 1;
			lastvarraytm = data[data.length-1].replace(".mp4","");

			srcposter = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
			lastsrcposter = '<?php echo str_replace("sim.","video.", base_url())?>video/getthumb/'+rvsource+'_'+lastvarraytm+'/001';

			if (lastvplaylist != lastvarraytm) {
				$('#'+lastvplaylistid).parent().removeClass('disabled');
				$('#vnttb'+lastvplaylistidn).attr('src', lastsrcposter);
				$('#'+lastvplaylistid).css('cursor', 'pointer');
				html =	'<a id="vbtn'+lastvplaylistidn+'" class="list-group-item disabled" style="height: 105px;">'+
									'<div class="pull-left vnextthumb" data-tbid="vnttb'+lastvplaylistidn+'" data-vsrc="'+rvsource+'" data-vfile="'+lastvarraytm+'">'+
										'<img id="vnttb'+lastvplaylistidn+'" src="'+srcposter+'" style="max-height:80px">'+
									'</div>'+
									'<div class="checkbox checkbox-warning">'+
										'<input id="chbx'+lastvplaylistidn+'" type="checkbox" data-aid="vbtn'+lastvplaylistidn+'" data-vsrc="'+rvsource+'" data-vfile="'+lastvarraytm+'">'+
										'<label for="chbx'+lastvplaylistidn+'" data-aid="vbtn'+lastvplaylistidn+'">Juntar</label>'+
									'</div>'+
									'<span id="vspan'+lastvplaylistidn+'" data-aid="vbtn'+lastvplaylistidn+'" data-vsrc="'+rvsource+'" style="cursor: pointer;">'+lastvarraytm+'</span>'+
								'</a>';
				nextvideo.append(html);
			}
		}
	);
};

function enablebtns() {
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

	$('#vnext').css('overflow-y', 'auto');
};

function disablebtns() {
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

	$('#vnext').css('overflow-y', 'hidden');
};

jQuery.fn.scrollTo = function(elem) {
	$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
	return this;
};

function mobileconf() {
	if (isTouchDevice()) {
		$('#vtitle').css('font-size', '18px');
		$('#vnext').css('max-height', '150px');
		$('#thvideo').attr({
			'width': '427',
			'height': '240'
		});
	}
};

function isTouchDevice() {
	return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
};

function load_vihts() {
	$('#selvinheta').selectpicker({title: 'Aguarde...'}).selectpicker('render');
	$.post('proxy',
		{address: '<?php echo str_replace('sim.','video.', base_url('video/getvinhetas/'))?>'},
		function(data, textStatus, xhr) {
			$('#selvinheta').html(null);
			data.map(function(index, elem) {
				filename = index.replace('.mp4','');
				html = '<option val="'+filename+'">'+filename+'</option>';
				$('#selvinheta').append(html);
			})
			$('#selvinheta').selectpicker({title: 'Selecione uma vinheta...'}).selectpicker('render');
			$('#selvinheta').selectpicker('refresh');
		}
	);
};

function enablenovo() {
	nextvideo.css('display', 'none');
	$('#vptext').css('display', 'block');
};

function getdocsbydate(idsouce, startdate, enddate) {
	$.get('tv_novo_docs_bydate/'+idsouce+'/'+encodeURI(startdate)+'/'+encodeURI(enddate), function(data) {
		// console.log(data);

		$.each(data.response.docs, function(index, val) {
			viddoc = val.id_i;
			vidsource = val.id_source_i;
			vsource = val.source_s.replace(' ', '-');
			vstartdate = val.starttime_dt;
			venddate = val.endtime_dt;

			d = new Date(vstartdate);
			day = d.getDate();
			day = ('0' + day).slice(-2);
			month = (d.getMonth() + 1);
			month = ('0' + month).slice(-2);
			year = d.getFullYear();
			hour = d.getHours();
			hour = ('0'+hour).slice(-2);
			min = d.getMinutes();
			min = ('0'+min).slice(-2);
			sec = d.getSeconds();
			sec = ('0'+sec).slice(-2);
			vsstdate = year+'-'+month+'-'+day+'_'+hour+'-'+min+'-'+sec;

			e = new Date(venddate);
			eday = e.getDate();
			eday = ('0' + eday).slice(-2);
			emonth = (e.getMonth() + 1);
			emonth = ('0' + month).slice(-2);
			eyear = e.getFullYear();
			ehour = e.getHours();
			ehour = ('0'+ehour).slice(-2)
			emin = e.getMinutes();
			emin = ('0'+emin).slice(-2)
			esec = e.getSeconds();
			esec = ('0'+esec).slice(-2);
			vseddate = eyear+'-'+emonth+'-'+eday+'_'+ehour+'-'+emin+'-'+esec;

			html = '<option data-docid="'+viddoc+'" data-idsource="'+vidsource+'">'+vsstdate+'_'+vsource+'</option>';
			$('#selvnext').append(html);
		});

		$('#selvnext').selectpicker('render');
		$('#selvnext').selectpicker('refresh');
	});
};

function videosetctime(pfp) {
	if (pfp) {
		// console.log('page from post');

		sidsource = '<?php echo isset($sidsource) ? $sidsource : null ?>';
		sstartdate = '<?php echo isset($sstartdate) ? $sstartdate : null ?>';
		senddate = '<?php echo isset($senddate) ? $senddate : null ?>';

		videoel[0].currentTime = <?php echo isset($ifkwfound) ? $ifkwfound : 0 ;?>;

		videoel[0].play();
		enablebtns();
		enablenovo();
		getdocsbydate(sidsource, sstartdate, senddate);
	} else {
		// console.log('normal page');
		$('#selvnext').selectpicker('hide');
	}
};

function getdocbymurl(vsource, vvideo) {
	var mediaurls = vsource+"_"+vvideo
	var gurl = window.location.origin+'/api/get_doc_bymurl/local/video/'+mediaurls
	$.get(gurl, function(data, xhr, status) {
		// console.log(data);
		// console.log(status.status);
		if (status.status == 200) {
			videotransc = true;
			var contentt = data.response.docs[0].content_t[0];
			var timest = JSON.parse(data.response.docs[0].times_t[0]);
			$('#vptext').text();

			$('#vnext').animate({'max-height': '100px', 'overflow-y': 'hidden'},
			'fast', function() {
				$('#vnext').scrollTo('a.active');
				$('#vptext').css('max-height', '360px');
				$('#vptext').slideDown('fast', function() {
					console.log('slide down done!');

					addtimes('#vptext', timest);
					startread('vvideo', 'vptext', 1, false);
				});
			});
		} else if (status.status == 404) {
			videotransc = false;
			console.log('error 404 onload');
		}
	});
};

function loadimgvnthumb(tvimg, tvsrc, tvfile, number) {
	strn = ("000"+number).slice(-3);
	tcsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+tvsrc+'_'+tvfile+'/'+strn;

	var testeimg = new Image();
	testeimg.src = tcsrc;

	testeimg.onerror = function(e) {
		tcsrc = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
		$('#'+tvimg).attr('src', tcsrc);
		return;
	};

	$('#'+tvimg).attr('src', tcsrc);
};

function setcookie(cname, cvalue) {
	document.cookie = cname+'='+cvalue;
};

function getcookies() {
	return document.cookie.split(';');
};

function setlocalstorage(cname, cvalue) {
	window.localStorage.setItem(cname, cvalue);
};

function getlocalstorage(cname) {
	window.localStorage.getItem(cname);
};

function browservendor() {
	if (navigator.vendor == 'Google Inc.') {
		return 'chrome';
	} else {
		return 'other';
	}
};

function sectostring(secs) {
	sec_num = parseInt(secs, 10);
	hours   = Math.floor(sec_num / 3600);
	minutes = Math.floor((sec_num - (hours * 3600)) / 60);
	seconds = sec_num - (hours * 3600) - (minutes * 60);
	mseconds = String(secs);
	milliseconds =  mseconds.slice(-3);

	if (hours  < 10) {hours = "0" + hours;}
	if (minutes < 10) {minutes = "0" + minutes;}
	if (seconds < 10) {seconds = "0" + seconds;}
	return hours+':'+minutes+':'+seconds+'.'+milliseconds;
};

function videoelBuffer() {
	if (videoel[0].buffered.length > 0) {
		maxduration = videoel[0].duration;
		startBuffer = videoel[0].buffered.start(0);
		endBuffer = videoel[0].buffered.end(0);
		percentageBuffer = (endBuffer / maxduration) * 100;
		$('.bufferBar').css('width', percentageBuffer+'%');
	}
};

function playpausevideo(videoelt) {
	vvideoelmt = $('#'+videoelt);
	if (vvideoelmt[0].paused) {
		$("#iplay").addClass('hidden');
		$("#ipause").removeClass('hidden');
		$('.vbutton').removeClass('paused');
		$('.vbutton').css('display', 'block');
		setTimeout(function() {$('.vbutton').fadeOut('fast')}, 1500);
		vvideoelmt[0].play();
		setlocalstorage('videoplaying', true);
	} else {
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		$('.vbutton').addClass('paused');
		$('.vbutton').css('display', 'block');
		vvideoelmt[0].pause();
		setlocalstorage('videoplaying', false);
	}
};

function vfullscreen(videoelt) {
	var elem = document.getElementById(videoelt);
	if (elem.requestFullscreen) {
		elem.requestFullscreen();
	} else if (elem.msRequestFullscreen) {
		elem.msRequestFullscreen();
	} else if (elem.mozRequestFullScreen) {
		if (document.fullscreenElement) {
			elem.exitFullscreen();
		} else {
			elem.mozRequestFullScreen();
		}
	} else if (elem.webkitRequestFullscreen) {
		if (document.webkitFullscreenElement) {
			document.webkitExitFullscreen();
		} else {
			elem.webkitRequestFullscreen();
		}
	}
};

function videoselect(cfilename, cfilevsource) {
	joinvideosclk = false;
	joinvideos = false;

	$('.vbutton').css('display', 'none');
	$('.vbutton').removeClass('paused');

	videoel.attr({
		poster: '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+cfilevsource+'_'+cfilename+'/001',
		src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + cfilevsource + '_' + cfilename
	});

	arr = lastvideo.split('_');
	channel = arr[2];
	if (channel != 'AVULSO') {
		if (cfilevsource.replace(/[0-9]/g, '') != 'cagiva') {
			videoel[0].pause();

			loadingthumbs();
		} else {
			videoel[0].play();
		}
	} else {
		videoel[0].play();
	}

	videotitle.text(cfilename);
	videotitle.attr('data-vsrc', cfilevsource);
	videotitle.css('font-size', '30px');
	mobileconf();

	$('.list-group').children().removeClass('active');
	$('span:contains('+cfilename+')').parent().addClass('active');

	// getdocbymurl(cfilevsource, cfilename);
};

function updatebar(x) {
	maxduration = videoel[0].duration;
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
	videoel[0].currentTime = videotime.toFixed(3);
	thumbnum = ('00' + videotimesec).slice(-3);
	$('.timeBar').css('width', percentage+'%');

	if (joinvideos) {
		filenarr = filesjoined[0].file.split("_");
		thnsfilename = filenarr[0];
		if (thnsfilename.replace(/[0-9]/g, '') != 'cagiva') {
			ttime = 0;
			var thumbnnf, thnvdfilename;

			$.each(filesjoined, function(index, filer) {
				ttime = ttime + filer.time;
				if (videotimesec <= ttime) {
					timedif = ttime - videotimesec;
					thumbnnf = ('00' + (filer.time - timedif)).slice(-3);
					thnvdfilename = filer.file.replace(thnsfilename+"_","");
					uptadevThumb(thnsfilename, thnvdfilename, thumbnnf);
					return false;
				}
			});
		}
	} else {
		vdfilename = videotitle.text();
		sfilename = $("span:contains('"+vdfilename+"')").data('vsrc');
		if (vvideosrc.match(vvideosrcsearch) == null && vvideosrc.match('media.resources.s3.amazonaws.com') == null) {
			if (sfilename.replace(/[0-9]/g, '') != 'cagiva') {
				uptadevThumb(sfilename, vdfilename, thumbnum);
			}
		}
	}
};

function updatebarkeyb(sec) {
	maxduration = videoel[0].duration;
	position = sec;
	percentage = (position * 100) / maxduration;

	if (percentage > 100) {
		percentage = 100;
	}
	if (percentage < 0) {
		percentage = 0;
	}

	videotime = sec;
	videotimesec = Math.floor(videotime);
	thumbnum = ('00' + videotimesec).slice(-3)
	videoel[0].currentTime = videotime.toFixed(3);

	vcurrtime.text(sectostring(videotime));

	$('.timeBar').css('width', percentage+'%');
	videoelBuffer();

	if (joinvideos) {
		filenarr = filesjoined[0].file.split("_");
		thnsfilename = filenarr[0];

		ttime = 0;
		var thumbnnf, thnvdfilename;

		$.each(filesjoined, function(index, filer) {
			ttime = ttime + filer.time;
			if (videotimesec <= ttime) {
				timedif = ttime - videotimesec;
				thumbnnf = ('00' + (filer.time - timedif)).slice(-3);
				thnvdfilename = filer.file.replace(thnsfilename+"_","");
				uptadevThumb(thnsfilename, thnvdfilename, thumbnnf);
				return false;
			}
		});
	} else {
		vdfilename = videotitle.text();
		sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
		uptadevThumb(sfilename, vdfilename, thumbnum);
	}
};

function uptadevThumb(utsfilename, utvdfilename, utthumbnum) {
	videoelth.attr('src', '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+utsfilename+'_'+utvdfilename+'/'+utthumbnum);
};

function updateTimetooltip(x) {
	vdfilename = videotitle.text();
	sfilename = $( "span:contains('"+vdfilename+"')" ).data('vsrc');
	maxduration = videoel[0].duration;
	position = x - progressbar.offset().left;
	percentage = (100 * position) / progressbar.width();

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
	videoelBuffer();
};

function loadingthumbs() {
	$('body').css('cursor', 'progress');

	swal({
		onOpen: () => {
			swal.showLoading()
		},
		title: "Carregando imagens...",
		animation: true,
		allowEscapeKey: false,
		allowOutsideClick: false,
		showCancelButton: false,
		showConfirmButton: false,
	});
};

function closeloadingthumbs() {
	$('body').css('cursor', 'default');

	swal.close();

	if (videotransc == false) {
		videoel[0].play();
	}
};

//Listeners
videoel.click(function(event) {
	videoelid = event.target.id;
	vvideosrc = event.target.src;
	if (vvideosrc.length != 0) {
		playpausevideo(videoelid);
	}
});

videomel.click(function(event) {
	videoelid = event.target.id;
	vvideosrc = event.target.src;
	if (vvideosrc.length != 0) {
		playpausevideo(videoelid);
	}
});

videojcmel.click(function(event) {
	videoelid = event.target.id;
	vvideosrc = event.target.src;
	if (vvideosrc.length != 0) {
		playpausevideo(videoelid);
	}
});

videoel.dblclick(function(event) {
	videoelid = event.target.id;
	// videofulls = document.webkitFullscreenEnabled;
	// videofullsel = document.webkitFullscreenElement;
	vvideosrc = event.target.src;
	if (vvideosrc.length != 0) {
		vfullscreen(videoelid);
	}
});

videomel.dblclick(function(event) {
	videoelid = event.target.id;
	// videofulls = document.webkitFullscreenEnabled;
	vvideosrc = event.target.src;
	if (vvideosrc.length != 0) {
		vfullscreen(videoelid);
	}
});

videojcmel.dblclick(function(event) {
	videoelid = event.target.id;
	// videofulls = document.webkitFullscreenEnabled;
	vvideosrc = event.target.src;
	if (vvideosrc.length != 0) {
		vfullscreen(videoelid);
	}
});

$('#checkaplay').change(function(event) {
	if ($(this).prop('checked')) {
		setlocalstorage('videoautoplay', 'true');
	} else {
		setlocalstorage('videoautoplay', 'false');
	}
});

$('.vbutton').click(function(event) {
	playpausevideo('vvideo');
});

$("#btnplay").click(function() {
	playpausevideo('vvideo');
});

$("#btnstop").click(function() {
	videoel[0].pause();
	videoel[0].currentTime = 0;
	$("#ipause").addClass('hidden');
	$("#iplay").removeClass('hidden');
});

$("#btnrn").click(function() {
	videoel[0].playbackRate = 1;
	setlocalstorage('videoprate', videoel[0].playbackRate);
});

$("#btnrs").click(function() {
	videoel[0].playbackRate -= 0.1;
	setlocalstorage('videoprate', videoel[0].playbackRate);
});

$("#btnrf").click(function() {
	videoel[0].playbackRate += 0.65;
	setlocalstorage('videoprate', videoel[0].playbackRate);
});

$("#btnvol").click(function() {
	if (videoel[0].muted) {
		$("#btnvol").removeClass('btn-danger');
		$("#btnvol").addClass('btn-default');
		videoel[0].muted = false;
		setlocalstorage('videomuted', false);
	} else {
		$("#btnvol").removeClass('btn-default');
		$("#btnvol").addClass('btn-danger');
		videoel[0].muted = true;
		setlocalstorage('videomuted', true);
	}
});

$("#btnvolm").click(function() {
	videoel[0].volume -= 0.5;
});

$("#btnvolp").click(function() {
	videoel[0].volume += 0.5;
});

$('#btnfull').click(function(event) {
	vfullscreen('vvideo');
});

videoel.on('loadedmetadata', function() {
	vvideosrc = videoel[0].currentSrc;
	if (vvideosrc.match('media.resources.s3.amazonaws.com') == null) {
		vduration = videoel[0].duration;
		vdurtime.text(sectostring(vduration));

		nimage = [];
		if (joinvideosclk) {
			vdfilename = videotitle.text();
			srcarr = vdfilename.split("_");
			srcfilename = srcarr[0];
			channel = srcarr[6];
			if (channel != 'AVULSO') {
				if (srcfilename.replace(/[0-9]/g, '') != 'cagiva') {
					fjoinedquant = filesjoined.length;
					fjoinedcount = 0;
					$.each(filesjoined, function(index, file) {
						fjoinedcount++;
						maxthumb = file.time;
						vdfilename = file.file;
						for (thumbn = 1 ; thumbn <= maxthumb; thumbn++) {
							nthumbn = ("00" + thumbn).slice(-3);
							nimage[thumbn] = new Image();
							imgsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+vdfilename+'/'+nthumbn;
							nimage[thumbn].src = imgsrc;

							if (fjoinedquant === fjoinedcount) {
								nimage[thumbn].onload = function(e) {
									if (navigator.vendor == 'Google Inc.') {
										loadedsrc = e.path[0].src;
									} else {
										loadedsrc = e.target.src;
									}

									urlload = window.location.origin;
									urlload = urlload.replace('sim.', 'video.');
									ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
									ldtmbn = parseInt(ldtmbnarr[1]);
									if (ldtmbn === maxthumb) {
										closeloadingthumbs();
										videoel[0].play();
										//$('#vnext').scrollTo('a.active');
									}
								};

								nimage[thumbn].onerror = function(e) {
									if (navigator.vendor == 'Google Inc.') {
										loadedsrc = e.path[0].src;
									} else {
										loadedsrc = e.target.src;
									}

									urlload = window.location.origin;
									urlload = urlload.replace('sim.', 'video.');
									ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
									ldtmbn = parseInt(ldtmbnarr[1]);
									if (ldtmbn === maxthumb) {
										closeloadingthumbs();
										videoel[0].play();
										//$('#vnext').scrollTo('a.active');
									}
								};
							}
						}
					});
				}
			}
		} else {
			vdfilename = videotitle.text();
			arr = vdfilename.split('_');
			channel = arr[2];
			if (channel != 'AVULSO') {
				srcfilename = $("span:contains('"+vdfilename+"')").data('vsrc');
				if (srcfilename.replace(/[0-9]/g, '') != 'cagiva') {
					maxthumb = Math.floor(videoel[0].duration);
					for (thumbn = 1 ; thumbn <= maxthumb; thumbn++) {
						nthumbn = ("00" + thumbn).slice(-3);
						nimage[thumbn] = new Image();
						imgsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+srcfilename+'_'+vdfilename+'/'+nthumbn;
						nimage[thumbn].src = imgsrc;
						nimage[thumbn].onload = function(e) {
							if (navigator.vendor == 'Google Inc.') {
								loadedsrc = e.path[0].src;
							} else {
								loadedsrc = e.target.src;
							}

							urlload = window.location.origin;
							urlload = urlload.replace('sim.', 'video.');
							ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
							ldtmbn = parseInt(ldtmbnarr[1]);
							if (ldtmbn === maxthumb) {
								closeloadingthumbs();
							}
						};

						nimage[thumbn].onerror = function(e) {
							if (navigator.vendor == 'Google Inc.') {
								loadedsrc = e.path[0].src;
							} else {
								loadedsrc = e.target.src;
							}

							urlload = window.location.origin;
							urlload = urlload.replace('sim.', 'video.');
							ldtmbnarr = loadedsrc.replace(urlload+'/video/getthumb/', '').split('/');
							ldtmbn = parseInt(ldtmbnarr[1]);
							if (ldtmbn === maxthumb) {
								closeloadingthumbs();
							}
						};
					}
				}
			}
		}

		setlocalstorage('joinvideosclk', joinvideosclk);
		setlocalstorage('videofile', vdfilename);
		setlocalstorage('videosrc', srcfilename);
	}
});

videoel.on('timeupdate', function() {
	if (vvideosrc.match(vvideosrcsearch) == null) {
		currentPos = videoel[0].currentTime;
		maxduration = videoel[0].duration;
		percentage = 100 * currentPos / maxduration;

		setlocalstorage('videoctime', currentPos);

		currentPosh = ('0' + Math.floor(currentPos / 60 / 60)).slice(-2);
		currentPosm = ('0' + Math.floor(currentPos - currentPosh * 60)).slice(-2);
		currentPoss = ('0' + Math.floor(currentPos - currentPosm * 60)).slice(-2);
		currentPossmss = (currentPos * 100 / 100).toFixed(3);
		currentPossms = currentPossmss.split(".");

		$('.timeBar').css('width', percentage+'%');
		vcurrtime.text(sectostring(currentPos));

		videoelBuffer();
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

				videoel.attr({
					poster: '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+nextvideosrc+'_'+nextvideoname+'/001',
					src: '<?php echo str_replace("sim.", "video.", base_url())?>video/getvideo/' + nextvideosrc + '_' + nextvideoname
				});
				videotitle.text(nextvideoname);

				$('.list-group').children().removeClass('active');
				$('#'+nactivevideoid).addClass('active');
				// joinvideos = false;
			}
		}
	}
});

var timeDrag = false;
$('.progressBar').mousedown(function(e) {
	timeDrag = true;

	if (joinvideos) {
		filenarr = filesjoined[0].file.split("_");
		vsourcefile = filenarr[0];
	} else {
		vfile = videotitle.text()
		vsourcefile = $("span:contains('"+vfile+"')").data('vsrc');
	}

	videoel[0].pause();
	if (vvideosrc.match(vvideosrcsearch) == null && vvideosrc.match('media.resources.s3.amazonaws.com') == null) {
		if (vsourcefile.replace(/[0-9]/g, '') != 'cagiva') {
			videoel.css('display', 'none');
			videoelth.css('display', 'block');
		}
	}

	$("#ipause").addClass('hidden');
	$("#iplay").removeClass('hidden');
	updatebar(e.pageX);
});

$(document).mouseup(function(e) {
	if (timeDrag) {
		vfile = videotitle.text()
		vsourcefile = videotitle.attr('data-vsrc');
		if (vvideosrc.match(vvideosrcsearch) == null && vvideosrc.match('media.resources.s3.amazonaws.com') == null) {
			if (vsourcefile.replace(/[0-9]/g, '') != 'cagiva') {
				videoelth.css('display', 'none');
				videoel.css('display', 'block');
			}
		}
		timeDrag = false;
		$('.vbutton').css('dusplay', 'block');
		$('.vbutton').removeClass('paused');
		setTimeout(function() {$('.vbutton').fadeOut('fast')}, 1500);
		$("#iplay").addClass('hidden');
		$("#ipause").removeClass('hidden');
		updatebar(e.pageX);
		videoel[0].play();
	}
});

$(document).mousemove(function(e) {
	if (timeDrag) {
		updatebar(e.pageX);
	}
});

progressbar.hover(function(event) {
	var vtitlen = $('#vtitle').text();
	if (vtitlen.match('Nenhuma Seleção') == null) {
		$('.tooltiptime').fadeIn("fast");
	}
}, function() {
	var vtitlen = $('#vtitle').text();
	if (vtitlen.match('Nenhuma Seleção') == null) {
		$('.tooltiptime').fadeOut("fast");
	}
}).mousemove(function(event) {
	barHeight = progressbar.height();
	barPosition = progressbar.position();
	barPositionoff = progressbar.offset();
	maxduration = videoel[0].duration;
	// thumbleft = event.pageX - 106;
	// thumbtop = barPositionoff.top - 155;
	ttimeleft = event.pageX - 52;
	ttimetop = barPositionoff.top - barHeight + 10;
	$('.tooltiptime').css({
		'top': ttimetop+"px",
		'left':  ttimeleft+"px"
	});
	updateTimetooltip(event.pageX);
});

$(document).keypress(function(event) {
	if (event.which == 32) {
		playpausevideo('vvideo');
	}
});

$(document).keydown(function(event) {
	if(event.ctrlKey && event.which == 37) {
		// console.log("Control+left pressed!");
		// videoel[0].currentTime-=1;
		seektime = videoel[0].currentTime-1;
		// seektime = videoel[0].currentTime-0.04;
		videoel[0].currentTime = seektime;
		videoel.css('display', 'none');
		videoelth.css('display', 'block');
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		updatebarkeyb(seektime);
	} else if(event.ctrlKey && event.which == 39){
		// console.log("Control+right pressed!");
		seektime = videoel[0].currentTime+1;
		videoel[0].currentTime = seektime;
		videoel.css('display', 'none');
		videoelth.css('display', 'block');
		$("#ipause").addClass('hidden');
		$("#iplay").removeClass('hidden');
		updatebarkeyb(seektime);
	} else if (event.which == 37) {
		// console.log("Left pressed!");
		// videoel[0].currentTime-=1;
		// seektime = videoel[0].currentTime-1;
		seektime = videoel[0].currentTime-0.04;
		videoel[0].currentTime = seektime;
		// videoel.css('display', 'none');
		// videoelth.css('display', 'block');
		// $("#ipause").addClass('hidden');
		// $("#iplay").removeClass('hidden');
		updatebarkeyb(seektime);
	} else if (event.which == 39) {
		// console.log("Right pressed!");
		// videoel[0].currentTime+=1;
		// seektime = videoel[0].currentTime+1;
		seektime = videoel[0].currentTime+0.04;
		videoel[0].currentTime = seektime;
		// videoel.css('display', 'none');
		// videoelth.css('display', 'block');
		// $("#ipause").addClass('hidden');
		// $("#iplay").removeClass('hidden');
		updatebarkeyb(seektime);
	}
});

$(document).keyup(function(event) {
	if (event.ctrlKey && event.which == 37 || event.ctrlKey && event.which == 39) {
		videoelth.css('display', 'none');
		videoel.css('display', 'block');
		// videoel[0].play();
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
			cfilename = event.target.innerText;
			cfilevsource = event.target.dataset.vsrc;
			videoselect(cfilename, cfilevsource);
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
				// console.log(' ');
				// console.log('joinvideos');
				// console.log(joinvideos);
				// console.log(' ');
				// console.log('joinvideosclk');
				// console.log(joinvideosclk);
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
});

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

	getlistchannel(vsource, selformdate, channel, state, true);

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
	clearInterval(refreshclist);
	if (todaydatesel) {
		var refreshclist = setInterval(function() {
			refreshlist(vsource, selformdate, channel, state);
		}, 30000);
	}
});

window.addEventListener("orientationchange", function() {
	videoplay = $('#vvideo');
	worientation = window.orientation;
	if (worientation == 90 || worientation == -90) {
		// videoplay[0].webkitRequestFullscreen();
		vfullscreen('vvideo');
	} else {
		// document.webkitExitFullscreen();
		vfullscreen('vvideo');
	}
}, false);

$('#miclient').blur(function(event) {
	cropfpr = $('#miprogram').val();
	cropfcl = $('#miclient').val();
	downfilename = cropfday+'.'+cropfmonth+'-'+cropfch+'-'+cropfst+'-'+cropfpr+'-'+cropfcl+'.mp4';
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
			// console.log(data);
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

$('#btndownimgs').click(function(event) {
	swal({
		onOpen: () => {
			swal.showLoading()
		},
		title: "Aguarde...",
		animation: false,
		allowEscapeKey: false,
		allowOutsideClick: false,
		showCancelButton: false,
		showConfirmButton: false,
	});

	createzip = {
		"source": jvsource,
		"files": filestojoin
	}

	$psettings = {
		url: "<?php echo str_replace('sim.','video.', base_url('video/createzip'))?>",
		type: "POST",
		data: createzip
	}
	$.ajax($psettings)
	.done(function(data) {
		downf = document.createElement('a');
		downf.href = "<?php echo str_replace('sim.','video.', base_url('video/downloadzip'))?>"+"/"+data.zipfile;
		document.body.appendChild(downf);
		downf.click();

		swal.close();
	})
	.fail(function() {
		// console.log("error");
		swal.close();
	})
	.always(function() {
		// console.log("complete");
	});
});

$(document).on('mouseover', '.vnextthumb', function() {
	// console.log('mouse over image thumb on vnext!');

	timgid = $(this).attr('data-tbid');
	tsrc = $(this).attr('data-vsrc');
	tfile = $(this).attr('data-vfile');

	if ($(this).parent('a').hasClass('disabled') == false) {
		nn = 1;
		vnextthumbf = setInterval(function() {
			if (nn > 20) {
				clearInterval(vnextthumbf);
			} else {
				loadimgvnthumb(timgid, tsrc, tfile, nn);
				nn = nn + 1;
			}
		}, 200);
	}
});

$(document).on('mouseleave', '.vnextthumb', function() {
	// console.log('mouse leave image thumb on vnext!');

	tsrc = $(this).attr('data-vsrc');
	tfile = $(this).attr('data-vfile');
	ttvntb = $(this);

	if ($(this).parent('a').hasClass('disabled') == false) {
		clearInterval(vnextthumbf);

		tcsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+tsrc+'_'+tfile+'/001';
		$(this).children('img').attr('src', tcsrc);

		var testeimg = new Image();
		testeimg.src = tcsrc;

		testeimg.onerror = function(e) {
			tcsrc = '<?php echo base_url("assets/imgs/colorbar.jpg")?>';
			ttvntb.children('img').attr('src', tcsrc);
			return;
		};
	}
});

$(document).on('click', '.vnextthumb', function() {
	timgid = $(this).attr('data-tbid');
	tsrc = $(this).attr('data-vsrc');
	tfile = $(this).attr('data-vfile');
	videoselect(tfile, tsrc);
});