<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Vídeo</title>
		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-select/js/bootstrap-select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/progressbarjs/dist/progressbar.min.js');?>"></script>

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>

		<style type="text/css">
			video::-internal-media-controls-download-button { display:none; }

			video::-webkit-media-controls-enclosure { overflow:hidden; }

			video::-webkit-media-controls-panel { width: calc(100% + 30px); }

			body { background-color: #F6F6F6 }

			.modal-lg-crop { width: 1200px; }
			.modal-md-join { height: 800px; }

			textarea { resize: none; }
			
			video { z-index: 1; }

			#vvideo {
				box-shadow: 0px 0px 20px #888888;
			}

			.pthvideo {
				width: 840px;
				height: 480px;
				padding-top: 4px;
				padding-bottom: 4px;
			}

			.vbutton {
				z-index: 2;
				position: absolute;
				top: 48%;
				left: 48%;
				box-sizing: border-box;
				width: 0;
				height: 74px;
				border-color: transparent transparent transparent #FEFEFE;
				transition: 100ms all ease;
				cursor: pointer;
				border-style: solid;
				border-width: 37px 0 37px 60px;
			}
			.vbutton.paused {
				border-style: double;
				border-width: 0px 0 0px 60px;
			}

			.vbutton:hover {
				border-color: transparent transparent transparent #7F7F7F;
			}

			.progressBar {
				position: relative;
				width: 100%;
				height: 40px;
				background-color: rgba(0, 0, 0, 1);
			}
			.timeBar {
				position: absolute;
				/* top: auto; */
				/* left: auto; */
				width: 0%;
				height: 100%;
				background-color: rgba(200, 0, 0, 1);
			}
			.bufferBar {
				position: absolute;
				/* top: 0; */
				/* left: 0; */
				width: 0%;
				height: 100%;
				background-color: rgba(50, 50, 50, 0.8);
			}
			.cropBar {
				position: absolute;
				/* top: auto; */
				/* left: auto; */
				margin-left: 0%;
				width: 0%;
				height: 100%;
				background-color: rgba(0, 0, 200, 1);
			}
			.tooltipthumb {
				position: absolute;
				/* padding: 10px 10px 10px 10px; */
				width: 210px;
				height: 150px;
				display: none;
				/* font-size: 12px; */
				color: #FFFFFF;
				background-color: #555;
				/* text-align: center; */
				vertical-align: center;
				text-align: center;
				padding-top: 5px;
				padding-bottom: 5px;
				border-radius: 6px;
				z-index: 2;
			}
			.tooltipthumb::after {
				content: "";
				position: absolute;
				top: 100%;
				left: 50%;
				margin-left: -5px;
				border-width: 5px;
				border-style: solid;
				border-color: #555 transparent transparent transparent;
			}
			.tooltiptime {
				position: absolute;
				/* padding: 10px 10px 10px 10px; */
				width: 100px;
				height: 22px;
				display: none;
				/* font-size: 12px; */
				color: #FFFFFF;
				background-color: #555;
				/* text-align: center; */
				vertical-align: center;
				text-align: center;
				border-radius: 6px;
				z-index: 1;
			}
			.tooltiptime::after {
				content: "";
				position: absolute;
				top: 100%;
				left: 50%;
				margin-left: -5px;
				border-width: 5px;
				border-style: solid;
				border-color: #555 transparent transparent transparent;
			}

			#progresscrop {
				width: 250px;
				height: 250px;
				position: relative;
				vertical-align: center;
				margin: auto;
				top: 120px;
			}
			#progressjoin {
				width: 250px;
				height: 250px;
				position: relative;
				vertical-align: center;
				margin: auto;
				top: 60px;
			}
			#progressjcrop {
				width: 250px;
				height: 250px;
				position: relative;
				vertical-align: center;
				margin: auto;
				top: 80px;	
			}
		</style>
	</head>
	<body>
		<span class="tooltipthumb">
			<img id="vthumb" src="" style="width: 180px">
			<span id="thumbtime"></span>
		</span>
		<span class="tooltiptime"></span>
		
		<div class="container-fluid center-block text-center">
			<div class="row">
				<div class="col-lg-7">
					<h2 id="vtitle">Nenhuma seleção</h2>
				</div>

				<div class="col-lg-5">
					<div class="btn-toolbar">
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger pull-right" title="Sair"><i class="fa fa-sign-out"></i></a>
						<a href="<?php echo base_url('pages/index_radio_knewin')?>" id="btnback" type="button" class="btn btn-default pull-right" title="Voltar"><i class="fa fa-arrow-left"></i></a>
						

						<div class="input-group date" style="width: 32%">
							<input id="seldate" type="text" class="form-control">
							<div class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</div>
						</div>

						<select id="selchannels" class="selectpicker" data-size="10" data-width="fit" title="Selecione uma data" disabled></select>
					</div>
				</div>
			</div>

			<div class="row">
				<div id="divvideo" class="col-lg-8">
					<div id="vvideobtn" class='vbutton' style="display: none"></div>
					<video id="vvideo" poster="<?php echo base_url('assets/imgs/colorbar.jpg')?>" width="840" height="480" preload="metadata" autoplay></video>
					<img id="thvideo" class="pthvideo" width="840" height="480" style="display: none;">
				</div>
				
				<div id="vnextdiv" class="col-lg-4">
					<!-- <h4>Vídeos</h4> -->
					<ul id="vnext" class="list-group" style="overflow-y: auto; height: 480px;"></ul>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-1">
					<span id="currtime">--:--</span>
				</div>

				<div class="col-lg-10">
					<div class="progressBar">
						<div class="bufferBar"></div>
						<div class="timeBar"></div>
						<div class="cropBar"></div>
					</div>
				</div>

				<div class="col-lg-1">
					<span id="durtime">--:--</span>
				</div>
			</div>

			<div class="row">
				<div id="controls" class="col-lg-12">
					<!-- <div class="btn-toolbar"> -->
						<div class="btn-group" role="group" aria-label="...">
							<a id="btnplay" type="button" class="btn btn-default disabled" title="Iniciar/Pausar" disabled>
								<i id="iplay" class="fa fa-play hidden"></i>
								<i id="ipause" class="fa fa-pause"></i>
							</a>
							<a id="btnstop" type="button" class="btn btn-default disabled" title="Parar"><i class="fa fa-stop"></i></a>
						</div>

						<div class="btn-group" role="group" aria-label="...">
							<a id="btnrn" type="button" class="btn btn-default disabled" title="Velocidade normal" disabled><i class="fa fa-angle-right"></i></a>
							<a id="btnrs" type="button" class="btn btn-default disabled" title="Reduzir velocidade" disabled><i class="fa fa-angle-double-left"></i></a>
							<a id="btnrf" type="button" class="btn btn-default disabled" title="Aumentar velocidade" disabled><i class="fa fa-angle-double-right"></i></a>
						</div>

						<div class="btn-group" role="group" aria-label="...">
							<a id="btncstart" type="button" class="btn btn-default disabled" title="Marcar início" disabled><i class="fa fa-hourglass-start"></i></a>
							<a id="btncend" type="button" class="btn btn-default disabled" title="Marcar fim" disabled><i class="fa fa-hourglass-end"></i></a>
							<a id="btncrop" type="button" class="btn btn-default disabled" title="Cortar" data-toggle="modal" disabled><i class="fa fa-scissors"></i></a>
							<input id="checkjoincrop" type="checkbox" data-toggle="toggle" title="Juntar cortes" data-size="normal" data-on="Juntar" data-off="Juntar" disabled>
						</div>

						<!-- <div class="btn-group" role="group" aria-label="Juntar cortes"> -->
							<!-- <a id="btnjcrop" type="button" class="btn btn-default" title="Juntar cortes" disabled><i class="fa fa-plus-circle"></i></a> -->
							<!-- <input id="checkjoincrop" type="checkbox" data-toggle="toggle" title="Juntar cortes" data-size="normal" data-on="<i class='fa fa-check'></i>" data-off="<i class='fa fa-close'></i>" disabled> -->
						<!-- </div> -->

						<a id="btnjoin" type="button" class="btn btn-default disabled" title="Juntar" disabled><i class="fa fa-plus"></i></a>

						<a id="btntran" type="button" class="btn btn-default disabled" title="Transcrição" disabled><i class="fa fa-commenting-o"></i></a>

						<div class="btn-group" role="group" aria-label="...">
							<a id="btnvol" type="button" class="btn btn-default disabled" title="Mudo" disabled><i class="fa fa-volume-off"></i></a>
							<a id="btnvolm" type="button" class="btn btn-default disabled" title="Reduzir volume" disabled><i class="fa fa-volume-down"></i></a>
							<a id="btnvolp" type="button" class="btn btn-default disabled" title="Aumentar volume" disabled><i class="fa fa-volume-up"></i></a>
						</div>

						<div class="btn-group" role="group" aria-label="...">
							<a id="btnfull" type="button" class="btn btn-default disabled" title="Tela cheia" disabled><i class="fa fa-arrows-alt"></i></a>
						</div>

						<input id="checkaplay" type="checkbox" data-toggle="toggle" data-size="normal" data-on="Autoplay" data-off="Autoplay" title="Autoplay" disabled>

						<!-- <a id="btnnight" type="button" class="btn btn-default" title="Modo noite"><i class="fa fa-moon-o"></i></a> -->
					<!-- </div> -->
				</div>
			</div>
		</div>

		<div class="modal fade cropmodal" tabindex="-1" role="dialog" aria-labelledby="cropmodal">
			<div class="modal-dialog modal-lg-crop" role="document">
				<div class="modal-content">
					<!-- <div class="modal-header text-center">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="gridSystemModalLabel">Inserção de matéria</h4>
					</div> -->
					<div class="modal-body center-block text-center">
						<div class="row">
							<div class="col-lg-9">
								<div id="progresscrop"></div>
								<div id="mdivvideo" class="embed-responsive embed-responsive-16by9" style="display: none;">
									<video id="mvvideo" class="embed-responsive-item" width="840" height="480" controls autoplay></video>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<input id="miprogram" type="text" class="form-control" placeholder="Programa"/>
								</div>
								<div class="form-group">
									<input id="miclient" type="text" class="form-control" placeholder="Cliente"/>
								</div>
								<div class="form-group">
									<input type="text" class="form-control" placeholder="Título"/>
								</div>
								<div class="form-group">
									<textarea id="subject" type="text" class="form-control text-justify" rows="5" placeholder="Resumo"></textarea>
								</div>
								<div class="form-group">
									<textarea id="transct" type="text" class="form-control text-justify" rows="10" placeholder="Transcrição"></textarea>
								</div>
								<span id="cropvideoload" class="text-muted"></span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-download"></i> DVD</button> -->
						<!-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-download"></i> Web</button> -->
						<!-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-download"></i> Baixar</button> -->
						<a id="mbtnvdown" class="btn btn-sm btn-default" href="#" download="tempname">
							<i class="fa fa-download"></i>
							<?php echo get_phrase('download');?> 
						</a>
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
						<button type="button" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> Salvar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade joinmodal" tabindex="-1" role="dialog" aria-labelledby="joinmodal">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="height: 400px">
					<div id="progressjoin"></div>
				</div>
			</div>
		</div>

		<div class="modal fade transcmodal" tabindex="-1" role="dialog" aria-labelledby="transcmodal">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header text-center">
						<h4 class="modal-title" id="gridSystemModalLabel">Transcrição</h4>
					</div>
					<div class="modal-body center-block text-center">
						<div class="form-group">
							<input id="stransctm" type="text" class="form-control"/>
						</div>
						<div class="form-group">
							<input id="ptransctm" type="text" class="form-control"/>
						</div>
						<div class="form-group">
							<textarea id="transctm" type="text" class="form-control text-justify" rows="20"></textarea>
						</div>
						<!-- <p id="transctpm" class="text-justify"></p> -->
					</div>
					<!-- <div class="modal-footer"> -->
					<!-- </div> -->
				</div>
			</div>
		</div>

		<div class="modal fade jcropmodal" tabindex="-1" role="dialog" aria-labelledby="jcropmodal">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-body center-block text-center" style="min-height: 500px">
						<div class="row">
							<div class="col-lg-12">
								<div id="progressjcrop"></div>
								<div id="mcjdivvideo" class="embed-responsive embed-responsive-16by9" style="display: none;">
									<video id="mcjvvideo" class="embed-responsive-item" width="840" height="480" controls autoplay></video>
								</div>
								<span id="joincropvideoload" class="text-muted pull-right"></span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<a id="mbtnjcdown" class="btn btn-sm btn-default" href="#" download="tempjcname">
							<i class="fa fa-download"></i>
							<?php echo get_phrase('download');?> 
						</a>
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				var lastvideo, lastvarray, lastvarraytm, vsource, channel, state, cropstarts, cropends, 
				selectedformdate, selformdate, cropstart, cropend, cropdurs, cropdur, jvsource,
				cropfmonth, cropfday, cropfch, cropfst, cropfpr, cropfcl,
				cfilesource, cfiletimestampt, cfiletstamp, cfiletstampst, cfiletstampet, loadthumbs;
				var filestojoin = [];
				var filesjoined = [];
				var fileseq = 0;
				var cropfilestojoin = [];
				var vbtnjoin = [];
				var nimage = [];
				var tvch = $('#selchannels');
				var tvdate = $('#seldate');
				var videoel = $('#vvideo');
				var videomel = $('#mvvideo');
				var videoelth = $('#thvideo');
				var videojcmel = $('#mcjvvideo');
				var vvideosrc = videoel[0].currentSrc;
				var vvideosrcsearch = "colorbarstatic";
				var videotitle = $('#vtitle');
				var nextvideo = $('#vnext');
				var progressbar = $('.progressBar');
				var timebar = $('.timeBar')
				var vcurrtime = $('#currtime');
				var vdurtime = $('#durtime');
				var vtooltiptime = $('.tooltiptime');
				var timerslider = $('#timeslider');
				var ccrops = false;
				var ccrope = false;
				var joinvideos = false;
				var joincropvideos = false;
				var nightmode = false;
				var cropstartss = null;
				var cropendss = null;

				var d = new Date();
				var day = d.getDate();
				var day = ('0' + day).slice(-2);
				var month = (d.getMonth() + 1);
				var month = ('0' + month).slice(-2);
				var year = d.getFullYear();
				var todaydate = year+'-'+month+'-'+day;

				function sleep(ms) {
					return new Promise(resolve => setTimeout(resolve, ms));
				}

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
				}

				videoel.bind('contextmenu', function() { return false; });
				videomel.bind('contextmenu', function() { return false; });

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
					// selecteddate = $('.input-group.date').datepicker('getUTCDate');
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

				$( "#selchannels" ).change(function(event) {
					selvalue = event.target.value;
					selvalarr1 = selvalue.split(':');
					selvalarr2 = selvalarr1[1].split('_');
					vsource = selvalarr1[0];
					channel = selvalarr2[0];
					state = selvalarr2[1];

					getlistchannel(vsource, selformdate, channel, state);
					
					setTimeout(scrollnextvideo, 1000);

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
									lastvarraytm = data[data.length-1].replace(".mp4","");

									if (lastvplaylist != lastvarraytm) {
										// html = '<a href="#" id="vbtn'+lastvplaylistidn+'" class="list-group-item" data-vsrc="'+vsource+'">'+lastvarraytm+'</a>';
										html =	'<li id="vbtn'+lastvplaylistidn+'" class="list-group-item">'+
													'<div class="checkbox checkbox-warning pull-left">'+
														'<input id="chbx'+lastvplaylistidn+'" type="checkbox">'+
														'<label for="chbx'+lastvplaylistidn+'">Juntar</label>'+
													'</div>'+
													'<span id="vspan'+lastvplaylistidn+'" data-vsrc="'+vsource+'" style="cursor: pointer;">'+lastvarraytm+'</span>'+
												'</li>';
										nextvideo.append(html);
									}
								}
							);
						}

						setInterval(function() {
							refreshlist(vsource, selformdate, channel, state);
						}, 60000);
					});
				});

				function selecteddate(seldddate) {
					$.post('proxy',
						{address: '<?php echo str_replace('sim.','video.',base_url('video/getlist/'))?>' + vsource + '/' + seldddate + '/' + channel + '/' + state},
						function(data, textStatus, xhr) {
							lastvideo = data[0].replace(".mp4", "");
							lastvarray = data[data.length-1].replace(".mp4","");
							videoel.removeAttr('loop');
							videoel.attr({
								poster: '<?php echo base_url('assets/imgs/videoloading.gif')?>',
								src: '<?php echo str_replace("sim.","video.",base_url())?>video/getvideo/' + vsource + '_' + lastvideo
							});

							videotitle.text(lastvideo);
							videotitle.css('font-size', '30px');
							nextvideo.html(null);
							$.each(data, function(index, val) {
								file = val.replace(".mp4","");
								if (file == lastvideo) {
									html =	'<li id="vbtn'+index+'" class="list-group-item active">'+
												'<div class="checkbox checkbox-warning pull-left">'+
													'<input id="chbx'+index+'" type="checkbox" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<label for="chbx'+index+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+index+'"  data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
											'</li>';
									nextvideo.append(html);
								} else {
									html =	'<li id="vbtn'+index+'" class="list-group-item">'+
												'<div class="checkbox checkbox-warning pull-left">'+
													'<input id="chbx'+index+'" type="checkbox" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<label for="chbx'+index+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+index+'"  data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
											'</li>';
									nextvideo.append(html);
								}	
							});

							scrollnextvideo();
						}
					);
				}

				function selectchannel(date) {
					// tvch.selectpicker({title: 'Selecione um veículo'}).selectpicker('render');
					// tvch.prop('disabled', false)
					tvch.html(null);
					tvch.selectpicker({title: 'Aguarde...'}).selectpicker('render');
					tvch.selectpicker('refresh');
					// tvch.html(null);
					$.post('proxy',
						{address: '<?php echo str_replace('sim.','video.',base_url('video/getchannels/'))?>' + date},
						function(data, textStatus, xhr) {
							tvch.html(null);
							$.each(data, function(elo, indexo) {
								if (elo == "indian03") {
									indexo.forEach(function(ela, indexa) {
										switch (ela) {
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
				}

				function getlistchannel(selglvsource,selgldate,selglchannel,selglstate) {
					$.post('proxy',
						{address: '<?php echo str_replace('sim.','video.',base_url('video/getlist/'))?>' + selglvsource + '/' + selgldate + '/' + selglchannel + '/' + selglstate},
						function(data, textStatus, xhr) {
							lastvideo = data[data.length-2].replace(".mp4", "");
							lastvarray = data[data.length-1].replace(".mp4","");

							$('.vbutton').css('display', 'none');
							$('.vbutton').removeClass('paused');

							videoel.removeAttr('loop');
							videoel.attr({
								poster: '<?php echo base_url('assets/imgs/videoloading.gif')?>',
								src: '<?php echo str_replace("sim.","video.",base_url())?>video/getvideo/' + vsource + '_' + lastvideo
							});

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

							videotitle.text(lastvideo);
							videotitle.css('font-size', '30px');
							nextvideo.html(null);
							$.each(data, function(index, val) {
								file = val.replace(".mp4","");
								if (file == lastvideo) {
									html =	'<li id="vbtn'+index+'" class="list-group-item active">'+
												'<div class="checkbox checkbox-warning pull-left">'+
													'<input id="chbx'+index+'" type="checkbox" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<label for="chbx'+index+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+index+'"  data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
											'</li>';
									nextvideo.append(html);
								} else {
									html =	'<li id="vbtn'+index+'" class="list-group-item">'+
												'<div class="checkbox checkbox-warning pull-left">'+
													'<input id="chbx'+index+'" type="checkbox" data-vsrc="'+vsource+'" data-vfile="'+file+'">'+
													'<label for="chbx'+index+'">Juntar</label>'+
												'</div>'+
												'<span id="vspan'+index+'"  data-vsrc="'+vsource+'" style="cursor: pointer;">'+file+'</span>'+
											'</li>';
									nextvideo.append(html);
								}	
							});
						}
					);
				}

				function scrollnextvideo() {
					jQuery.fn.scrollTo = function(elem) {
						$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
						return this;
					}

					$('#vnext').scrollTo('.active');
					// $('#vnextdiv').animate({scrollTop: $('.active').offset().top}, 100);
				}

				// $('#mbtnvdown').click(function(event) {
				// 	cropfpr = $('#miprogram').val();
				// 	cropfcl = $('#miclient').val();
				// 	console.log(cropfpr);
				// 	console.log(cropfcl);
				// 	if (cropfpr == '') {
				// 		alert('Por favor, preencha o nome do programa');
				// 		$('#miprogram').focus();
				// 		return false
				// 	}
				// 	if (cropfcl == '') {
				// 		alert('Por favor, preencha o nome do cliente');
				// 		$('#miclient').focus();
				// 		return false
				// 	}
				// });

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

				var progresscbar = new ProgressBar.Circle('#progresscrop', {
					color: '#aaa',
					// This has to be the same size as the maximum width to
					// prevent clipping
					strokeWidth: 4,
					trailWidth: 1,
					easing: 'easeInOut',
					duration: 200,
					text: { autoStyleContainer: false },
					from: { color: '#aaa', width: 2 },
					to: { color: '#333', width: 4 },
					// Set default step function for all animate calls
					step: function(state, circle) {
						circle.path.setAttribute('stroke', state.color);
						circle.path.setAttribute('stroke-width', state.width);

						var value = Math.round(circle.value() * 100);
						if (value === 0) {
							circle.setText('0%');
						} else {
							circle.setText(value+'%');
						}
					}
				});
				progresscbar.text.style.fontFamily = 'Helvetica';
				progresscbar.text.style.fontSize = '4rem';

				var progressjbar = new ProgressBar.Circle('#progressjoin', {
					color: '#aaa',
					// This has to be the same size as the maximum width to
					// prevent clipping
					strokeWidth: 4,
					trailWidth: 1,
					easing: 'easeInOut',
					duration: 200,
					text: { autoStyleContainer: false },
					from: { color: '#aaa', width: 2 },
					to: { color: '#333', width: 4 },
					// Set default step function for all animate calls
					step: function(state, circle) {
						circle.path.setAttribute('stroke', state.color);
						circle.path.setAttribute('stroke-width', state.width);

						var value = Math.round(circle.value() * 100);
						if (value === 0) {
							circle.setText('0%');
						} else {
							circle.setText(value+'%');
						}
					}
				});
				progressjbar.text.style.fontFamily = 'Helvetica';
				progressjbar.text.style.fontSize = '4rem';

				var progressjcbar = new ProgressBar.Circle('#progressjcrop', {
					color: '#aaa',
					// This has to be the same size as the maximum width to
					// prevent clipping
					strokeWidth: 4,
					trailWidth: 1,
					easing: 'easeInOut',
					duration: 200,
					text: { autoStyleContainer: false },
					from: { color: '#aaa', width: 2 },
					to: { color: '#333', width: 4 },
					// Set default step function for all animate calls
					step: function(state, circle) {
						circle.path.setAttribute('stroke', state.color);
						circle.path.setAttribute('stroke-width', state.width);

						var value = Math.round(circle.value() * 100);
						if (value === 0) {
							circle.setText('0%');
						} else {
							circle.setText(value+'%');
						}
					}
				});
				progressjcbar.text.style.fontFamily = 'Helvetica';
				progressjcbar.text.style.fontSize = '4rem';
