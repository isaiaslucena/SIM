<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Edição de vídeo temp</title>

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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.dev.js"></script>
		<script type="text/javascript">
			var siourl = 'http://'+window.location.hostname+':8037'
			const socket = io(siourl);
		</script>

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.18.0/sweetalert2.min.css"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
		<link rel="stylesheet" href="<?php echo base_url('assets/dataclip/audiovideoedit.css')?>"/>
		<link rel="stylesheet" href="<?php echo base_url("assets/dataclip/home_keyword.css")?>">
	</head>
	<body>
		<span class="tooltipthumb">
			<img id="vthumb" src="" style="width: 180px">
			<span id="thumbtime"></span>
		</span>
		<span class="tooltiptime"></span>

		<div class="container-fluid center-block text-center">
			<div class="row">
				<div class="col-md-8">
					<h2 id="vtitle" class="center-block">Nenhuma Seleção</h2>
				</div>

				<div class="col-md-4">
					<h2>
						<button id="btnqueuecrop" class="btn btn-default" title="Fila de cortes">
							<sup><span id="queuecroplistq" class="navnotification" style="display: none"></span></sup>
							<i class="fa fa-list"></i>
						</button>
						<button id="btnqueuetrans" class="btn btn-default" title="Fila de transcrição">
							<sup><span id="queuetranslistq" class="navnotification" style="display: none"></span></sup>
							<i class="fa fa-list"></i>
						</button>
						<input id="checkaplay" class="selectpicker" type="checkbox" data-toggle="toggle" data-size="normal" data-on="Autoplay" data-off="Autoplay" title="Autoplay" disabled>
					</h2>
				</div>
			</div>

			<!-- video, thumb, and list of files -->
			<div class="row">
				<div id="divvideo" class="col-md-8">
					<div id="vvideobtn" class='vbutton' style="display: none"></div>
					<video id="vvideo" class="center-block"
						poster="<?php echo base_url('assets/imgs/colorbar.jpg')?>"
						width="854" height="480" preload="metadata"></video>
					<img id="thvideo" class="center-block" style="display: none; max-height: 480px">
				</div>

				<div id="vnextdiv" class="col-md-4">
					<div id="vnext" class="list-group center-block" style="overflow-y: hidden; height: 480px">
						<?php for ($i = 0; $i < 15; $i++) {
							if ($i == 5) {
								echo '<a class="list-group-item">Nenhuma seleção</a>';
							} else {
								echo '<a class="list-group-item" style="color: white">Nenhuma seleção</a>';
							} ?>
						<?php } ?>
					</div>

					<p id="vptext" class="text-justify ptext noscrolled" data-mediaid="vvideo"
					 style="overflow-y: auto; max-height: 480px; display: none;">
						<?php
							if (isset($sid)) {
								if (isset($found->response->docs[0]->times_t)) {
									$times = $found->response->docs[0]->times_t[0];
									$stimes = json_decode($times, TRUE);
									foreach ($stimes as $stime) {
										if (isset($stime['words'])) {
											foreach ($stime['words'] as $word) {
												$wbegin = (float)$word['begin'];
												$wend = (float)$word['end'];
												$wdur = substr((string)($wend - $wbegin), 0, 5);
												$wspan = '<span data-dur="'.$wdur.'" data-begin="'.$word['begin'].'">'.$word['word'].'</span> ';
												echo $wspan;
											}
										}
									}
								} else {
									echo (string)$found->response->docs[0]->content_t[0];
								}
							}
						?>
					</p>
				</div>
			</div>

			<!-- progress bar, current time and total time -->
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

			<div class="row" style="margin-top: 10px">
				<div id="controls" class="col-md-7">
					<input id="autofocus-current-word" class="autofocus-current-word" type="checkbox" checked style="display: none;">
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
					<a id="btndownimgs" type="button" class="btn btn-default disabled" title="Baixar imagens" disabled><i class="fa fa-download"></i></a>

					<!-- <div class="btn-group" role="group" aria-label="...">
						<a id="btnvol" type="button" class="btn btn-default disabled" title="Mudo" disabled><i class="fa fa-volume-off"></i></a>
						<a id="btnvolm" type="button" class="btn btn-default disabled" title="Reduzir volume" disabled><i class="fa fa-volume-down"></i></a>
						<a id="btnvolp" type="button" class="btn btn-default disabled" title="Aumentar volume" disabled><i class="fa fa-volume-up"></i></a>
					</div> -->

					<!-- <div class="btn-group" role="group" aria-label="...">
						<a id="btnfull" type="button" class="btn btn-default disabled" title="Tela cheia" disabled><i class="fa fa-arrows-alt"></i></a>
					</div> -->
				</div>

				<div class="col-md-5">
					<div class="btn-toolbar">
						<div class="input-group date" style="width: 26%">
							<input id="seldate" type="text" class="form-control">
							<div class="input-group-addon">
								<span class="fa fa-calendar"></span>
							</div>
						</div>

						<select id="selchannels" class="selectpicker pull-left"
						data-size="10" data-width="200" data-live-search="true"
						data-windowPadding="top" title="Selecione uma data" disabled></select>

						<div class="btn-group">
							<div class="dropup">
								<button type="button" class="btn btn-default dropdown-toggle"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<sup><span id="alerttvbnum" class="navnotification" style="display: none"></span></sup>
									<i class="fa fa-bell"></i>
								</button>
								<ul id="alerttvlist" class="dropdown-menu dropdown-menu-right" style="max-height: 600px; width: 350px;overflow-y: auto;">
									<li>
										<a class="text-center" href="#">
											<strong>Nenhum alerta de tv!</strong>
										</a>
									</li>
								</ul>
							</div>
						</div>

						<a href="<?php echo base_url('pages/index_tv')?>" id="btnback" type="button" class="btn btn-default" title="Voltar"><i class="fa fa-arrow-left"></i></a>
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger" title="Sair"><i class="fa fa-sign-out"></i></a>
					</div>
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

		<div class="modal fade queuecropmodal" tabindex="-1" role="dialog" aria-labelledby="queuecropmodal">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<!-- <div class="modal-header text-center">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="gridSystemModalLabel">Fila de cortes</h4>
					</div> -->
					<div class="modal-body center-block text-center">
						<div class="row">
							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Para cortar</h3>
									</div>
									<div id="queuecroplist" class="list-group center-block noitems" style="overflow-y: auto; max-height: 450px">
										<?php for ($i = 0; $i < 20; $i++) {
											if ($i == 5) {
												echo '<a class="list-group-item">Nenhum arquivo na fila!</a>';
											} else {
												echo '<a class="list-group-item" style="color: white">Nenhum arquivo na fila!</a>';
											} ?>
										<?php } ?>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title">Cortados</h3>
									</div>
									<div id="queuecroplistdone" class="list-group center-block noitems" style="overflow-y: auto; max-height: 450px">
										<?php for ($i = 0; $i < 20; $i++) {
											if ($i == 5) {
												echo '<a class="list-group-item">Nenhum arquivo!</a>';
											} else {
												echo '<a class="list-group-item" style="color: white">Nenhum arquivo!</a>';
											} ?>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="<?php echo base_url('pages/video_player'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('pages/video_editor'); ?>"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				// if (window.Worker) {
				// 	imgworker = new Worker('/assets/imgworker.js');
				// }

				// imgworker.onmessage = function(event) {
					// console.log(event.data);
				// }

				videoel.bind('contextmenu', function() { return false; });
				videomel.bind('contextmenu', function() { return false; });

				videosetctime(frompost);

				// if (document.cookie.indexOf('videofile') != -1 ) {
				// 	console.log('videofile exist @ cookie!');
				// } else {
				// 	console.log('videofile not exist @ cookie!');
				// }

				if (window.localStorage.getItem('videoautoplay')) {
					// console.log(window.localStorage.getItem('videoautoplay'));
					if (window.localStorage.getItem('videoautoplay') == 'true') {
						$('#checkaplay').bootstrapToggle('enable').bootstrapToggle('on').bootstrapToggle('disable');
					}
				}

				// if (window.localStorage.getItem('videofile')) {
				// 	vsource = window.localStorage.getItem('videosrc');
				// 	vfile = window.localStorage.getItem('videofile');
				// 	// console.log(vfile);
				// 	vfarr = vfile.split('_');
				// 	selformdate = vfarr[0];
				// 	channel = vfarr[2];
				// 	state = vfarr[3];

				// 	vctime = window.localStorage.getItem('videoctime');
				// 	vplaying = window.localStorage.getItem('videoplaying');

				// 	setTimeout(function() {
				// 		$('.list-group').children().removeClass('active');

				// 		$('.input-group.date').datepicker('setDate', new Date(selformdate+' 00:00:00'));
				// 		$('#selchannels').selectpicker('val', vsource+':'+channel+'_'+state);

				// 		spanid = $("span:contains('"+vfile+"')").attr('id');
				// 		$('#'+spanid).parent().addClass('active');

				// 		$('#vnext').scrollTo('a.active');

				// 		videotitle.text(vfile);
				// 		videotitle.attr('data-vsrc', vsource);

				// 		vsrc = '<?php echo str_replace("sim.","video.",base_url())?>video/getvideo/'+vsource+'_'+vfile
				// 		vposter = '<?php echo str_replace("sim.","video.",base_url())?>video/getthumb/'+vsource+'_'+vfile+'/001';
				// 		console.log(vsource);
				// 		videoel.attr({
				// 			poster: vposter,
				// 			src: vsrc
				// 		});

				// 		loadingthumbs();

				// 		videoel[0].currentTime = vctime;
				// 		// if (vplaying == 'false') {
				// 		// 	videoel[0].pause();
				// 		// } else {
				// 		// 	videoel[0].play();
				// 		// }
				// 	}, 300);

				// 	selectchannel(selformdate);
				// 	getlistchannel(vsource, selformdate, channel, state, false);
				// }
			});
		</script>
	</body>
</html>