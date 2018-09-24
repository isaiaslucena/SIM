<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Edição de áudio</title>

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<link rel="shortcut icon" href="<?php echo base_url('assets/imgs/favicon.ico');?>" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url('assets/imgs/favicon.ico');?>" type="image/x-icon">

		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-select/js/bootstrap-select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.js');?>"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.18.0/sweetalert2.min.js"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/progressbarjs/dist/progressbar.min.js');?>"></script>
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/wavesurfer.js/2.0.6/wavesurfer.min.js"></script> -->

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.18.0/sweetalert2.min.css"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/dataclip/audiovideoedit.css')?>"/>

		<style type="text/css">
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
				<div class="col-md-12">
					<h2 id="vtitle" class="center-block">Nenhuma seleção</h2>
				</div>
			</div>

			<div class="row">
				<div id="divvideo" class="col-md-8">
						<div>
							<div id="vvideobtn" class='vbutton' style="display: none"></div>
							<audio id="aaudio" class="center-block" poster="<?php echo base_url('assets/imgs/colorbar.jpg')?>" preload="metadata" autoplay="false"></audio>
							<img id="thvideo" class="center-block"  width="854" height="480" style="display: none;">
						</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-1 col-md-1 col-lg-1">
					<span id="currtime">--:--</span>
				</div>

				<div class="col-sm-10 col-md-10 col-lg-10">
					<div class="progressBar">
						<div class="bufferBar"></div>
						<div class="timeBar"></div>
						<div class="cropBar"></div>
					</div>
				</div>

				<div class="col-sm-1 col-md-1 col-lg-1">
					<span id="durtime">--:--</span>
				</div>
			</div>

			<div class="row">
				<p>
				<div id="controls" class="col-md-7">
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
					<a id="btnjoin" type="button" class="btn btn-default disabled" title="Juntar" disabled><i class="fa fa-plus"></i></a>
					<a id="btntran" type="button" class="btn btn-default disabled" title="Transcrição" disabled><i class="fa fa-commenting-o"></i></a>
					<div class="btn-group" role="group" aria-label="...">
						<a id="btnvol" type="button" class="btn btn-default disabled" title="Mudo" disabled><i class="fa fa-volume-off"></i></a>
						<a id="btnvolm" type="button" class="btn btn-default disabled" title="Reduzir volume" disabled><i class="fa fa-volume-down"></i></a>
						<a id="btnvolp" type="button" class="btn btn-default disabled" title="Aumentar volume" disabled><i class="fa fa-volume-up"></i></a>
					</div>
					<input id="checkaplay" type="checkbox" data-toggle="toggle" data-size="normal" data-on="Autoplay" data-off="Autoplay" title="Autoplay" disabled>
				</div>

				<div class="col-md-5">
					<div class="btn-toolbar">
						<div class="input-group date" style="width: 26%">
							<input id="seldate" type="text" class="form-control">
							<div class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</div>
						</div>
						<select id="selchannels" class="selectpicker pull-left" data-size="10" data-width="200" data-live-search="true" title="Selecione uma data" disabled></select>
						<div class="btn-group" aria-label="...">
							<button type="button" class="btn btn-default dropdown-toggle"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<sup><span id="alertradiobnum" class="navnotification" style="display: none"></span></sup>
								<i class="fa fa-bell"></i>
							</button>
							<ul id="alertradiolist" class="dropdown-menu dropdown-menu-right" style="max-height: 500px; max-width: 400px; overflow-y: auto;">
								<li>
									<a class="text-center" href="#">
										<strong>Nenhum alerta de rádio!</strong>
									</a>
								</li>
							</ul>
						</div>
						<a href="<?php echo base_url('pages/index_tv')?>" id="btnback" type="button" class="btn btn-default" title="Voltar"><i class="fa fa-arrow-left"></i></a>
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger" title="Sair"><i class="fa fa-sign-out"></i></a>
					</div>
				</div>
			</p>
			</div>

			<div class="row">
				<div class="col-md-offset-3"></div>

				<div id="vnextdiv" class="col-md-6">
					<p><div id="vnext" class="list-group center-block" style="overflow-y: auto; max-height: 480px"></div></p>
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
							<div class="col-sm-9 col-md-9 col-lg-9">
								<div id="progresscrop"></div>
								<div id="mdivvideo" class="embed-responsive embed-responsive-16by9" style="display: none;">
									<video id="mvvideo" class="embed-responsive-item" width="840" height="480" controls autoplay></video>
								</div>
							</div>
							<div class="col-sm-3 col-md-3 col-lg-3" style="height: 480px">
								<div class="form-group">
									<select id="selvinheta" class="selectpicker" data-size="15" data-width="250" data-live-search="true" title="Vinhetas"></select>
								</div>
								<!-- <div class="form-group">
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
								</div> -->
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<!-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-download"></i> DVD</button> -->
						<!-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-download"></i> Web</button> -->
						<!-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-download"></i> Baixar</button> -->
						<span id="cropvideoload" class="text-muted pull-left"></span>

						<button id="mbtnv2down" class="btn btn-sm btn-default">
							<i id="miconvdown" class="fa fa-download"></i>
							<i id="mspinvdown" class="fa fa-circle-o-notch fa-spin" style="display: none;"></i>
							Baixar Teste
						</button>

						<button id="mbtnvdown" class="btn btn-sm btn-default">
							<i id="miconvdown" class="fa fa-download"></i>
							<i id="mspinvdown" class="fa fa-circle-o-notch fa-spin" style="display: none;"></i>
							<?php echo get_phrase('download');?>
						</button>

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
							<div class="col-sm-12 col-md-12 col-lg-12">
								<div id="progressjcrop"></div>
								<div id="mcjdivvideo" class="embed-responsive embed-responsive-16by9" style="display: none;">
									<video id="mcjvvideo" class="embed-responsive-item" width="840" height="480" controls autoplay></video>
								</div>
								<span id="joincropvideoload" class="text-muted pull-right"></span>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<a id="mbtnjcdown" class="btn btn-sm btn-default" href="#" download>
							<i class="fa fa-download"></i>
							<?php echo get_phrase('download');?>
						</a>
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			// $(document).ready(function() {
				var lastvideo, lastvarray, lastvarraytm, vsource, channel, state, cropstarts, cropends,
				selectedformdate, selformdate, cropstart, cropend, cropdurs, cropdur, jvsource,
				cropfmonth, cropfday, cropfch, cropfst, cropfpr, cropfcl, videourlmcrop, vintfile,
				cfilesource, cfiletimestampt, cfiletstamp, cfiletstampst, cfiletstampet, loadingthumbs;
				var timeDrag = false, ccrops = false, ccrope = false, joinvideos = false, joinvideosclk = false,
				selvinheta = false, joincropvideos = false, nightmode = false, todaydatesel = false;
				var cropstartss = null, cropendss = null;
				var filestojoin = [], filesjoined = [], cropfilestojoin = [], vbtnjoin = [], nimage = [];
				var fileseq = 0;
				var tvch = $('#selchannels');
				var tvdate = $('#seldate');
				var audioel = $('#aaudio');
				var videomel = $('#mvvideo');
				var videojcmel = $('#mcjvvideo');
				var vvideosrc = audioel[0].currentSrc;
				var vvideosrcsearch = "colorbarstatic";
				var videotitle = $('#vtitle');
				var nextvideo = $('#vnext');
				var progressbar = $('.progressBar');
				var timebar = $('.timeBar')
				var vcurrtime = $('#currtime');
				var vdurtime = $('#durtime');
				var vtooltiptime = $('.tooltiptime');
				var timerslider = $('#timeslider');

				var d = new Date();
				var day = d.getDate();
				var day = ('0' + day).slice(-2);
				var month = (d.getMonth() + 1);
				var month = ('0' + month).slice(-2);
				var year = d.getFullYear();
				var todaydate = year+'-'+month+'-'+day;

				// audioel.bind('contextmenu', function() {return false;});

				// var progresscbar = new ProgressBar.Circle('#progresscrop', {
				// 	color: '#aaa',
				// 	// This has to be the same size as the maximum width to
				// 	// prevent clipping
				// 	strokeWidth: 4,
				// 	trailWidth: 1,
				// 	easing: 'easeInOut',
				// 	duration: 200,
				// 	text: { autoStyleContainer: false },
				// 	from: { color: '#aaa', width: 2 },
				// 	to: { color: '#333', width: 4 },
				// 	// Set default step function for all animate calls
				// 	step: function(state, circle) {
				// 		circle.path.setAttribute('stroke', state.color);
				// 		circle.path.setAttribute('stroke-width', state.width);

				// 		var value = Math.round(circle.value() * 100);
				// 		if (value === 0) {
				// 			circle.setText('0%');
				// 		} else {
				// 			circle.setText(value+'%');
				// 		}
				// 	}
				// });
				// progresscbar.text.style.fontFamily = 'Helvetica';
				// progresscbar.text.style.fontSize = '4rem';

				// var progressjbar = new ProgressBar.Circle('#progressjoin', {
				// 	color: '#aaa',
				// 	// This has to be the same size as the maximum width to
				// 	// prevent clipping
				// 	strokeWidth: 4,
				// 	trailWidth: 1,
				// 	easing: 'easeInOut',
				// 	duration: 200,
				// 	text: { autoStyleContainer: false },
				// 	from: { color: '#aaa', width: 2 },
				// 	to: { color: '#333', width: 4 },
				// 	// Set default step function for all animate calls
				// 	step: function(state, circle) {
				// 		circle.path.setAttribute('stroke', state.color);
				// 		circle.path.setAttribute('stroke-width', state.width);

				// 		var value = Math.round(circle.value() * 100);
				// 		if (value === 0) {
				// 			circle.setText('0%');
				// 		} else {
				// 			circle.setText(value+'%');
				// 		}
				// 	}
				// });
				// progressjbar.text.style.fontFamily = 'Helvetica';
				// progressjbar.text.style.fontSize = '4rem';

				// var progressjcbar = new ProgressBar.Circle('#progressjcrop', {
				// 	color: '#aaa',
				// 	// This has to be the same size as the maximum width to
				// 	// prevent clipping
				// 	strokeWidth: 4,
				// 	trailWidth: 1,
				// 	easing: 'easeInOut',
				// 	duration: 200,
				// 	text: { autoStyleContainer: false },
				// 	from: { color: '#aaa', width: 2 },
				// 	to: { color: '#333', width: 4 },
				// 	// Set default step function for all animate calls
				// 	step: function(state, circle) {
				// 		circle.path.setAttribute('stroke', state.color);
				// 		circle.path.setAttribute('stroke-width', state.width);

				// 		var value = Math.round(circle.value() * 100);
				// 		if (value === 0) {
				// 			circle.setText('0%');
				// 		} else {
				// 			circle.setText(value+'%');
				// 		}
				// 	}
				// });
				// progressjcbar.text.style.fontFamily = 'Helvetica';
				// progressjcbar.text.style.fontSize = '4rem';
		</script>

		<script type="text/javascript" src="<?php echo base_url('pages/audioplayerfunctions')?>"></script>
		<script type="text/javascript" src="<?php echo base_url('pages/audioplayerlisteners')?>"></script>
	</body>
</html>