<?php

defined('BASEPATH') OR exit('No direct script access allowed'); 

$timezone = new DateTimeZone('UTC');
$sd = new Datetime($startdate);
$ed = new Datetime($enddate);
$newtimezone = new DateTimeZone('America/Sao_Paulo');
$sd->setTimezone($newtimezone);
$ed->setTimezone($newtimezone);
$sstartdate = $sd->format('d/m/Y H:i:s');
$dwstartdate = $sd->format('d-m-Y');
$dwstarttime = $sd->format('H\hi\m');
$senddate = $ed->format('d/m/Y H:i:s');

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Arquivo Cortado</title>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/material-design/material-icons.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap-theme.css');?>"/>

		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>

		<style type="text/css">
			audio::-internal-media-controls-download-button { display:none; }
			audio::-webkit-media-controls-enclosure { overflow:hidden; }
			audio::-webkit-media-controls-panel { width: calc(100% + 30px); }

			body {
				background-color: #FEFEFE;
			}
		</style>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row text-center">
				<div class="page-header">
					<h1><?php echo $client_selected; ?><small> - <?php echo $keyword_selected; ?></small></h1>
					<h3><?php echo $ssource?> | <?php echo $sstartdate." - ".$senddate;?></h3>
				</div>
			</div>

			<div class="row center-block text-center">
				<div class="col-lg-10">
					<audio id="passage-audio" src="<?php echo $finalfile; ?>" style="width: 100%" controls></audio>
				</div>
				
				<div class="col-lg-2">
					<div class="btn-group" role="group" aria-label="...">
						<a id="btnpbrate" type="button" class="btn btn-default" title="Aumentar velocidade"><i class="fa fa-angle-double-right"></i></a>
						<!-- <a id="btncstart" type="button" class="btn btn-default" title="Marcar início"><i class="fa fa-hourglass-start"></i></a> -->
						<!-- <a id="btncend" type="button" class="btn btn-default disabled" title="Marcar fim" disabled><i class="fa fa-hourglass-end"></i></a> -->
						<!-- <button id="btncrop" type="submit" form="cropknewin" class="btn btn-default disabled" title="Cortar" data-toggle="modal" disabled><i class="fa fa-scissors"></i></button> -->
						<a id="btndownload" type="button" class="btn btn-default" title="Baixar" data-cropid="<?php echo $crop_inserted_id; ?>" href="<?php echo $finalfile;?>" download="<?php echo mb_strtoupper($dwstartdate.'_'.$ssource.'_'.$dwstarttime.'_'.$client_selected);?>"><i class="fa fa-download"></i></a>
					</div>
				</div>
			</div>
	
			<div class="row">
				<div class="col-lg-12">
					<!-- <p id="passage-text" class="text-justify" style="overflow-y: auto"> <?php //echo $content; ?></p> -->
					<textarea class="form-control text-justify center-block" style="overflow-y: auto; max-width: 98%" rows="15"><?php echo $text_crop;?></textarea>
				</div>
			</div>
	
			<div class="row">
				<div class="col-lg-12">
					<small id="pageload" class="text-muted pull-right"></small>
				</div>
			</div>
	
			<script type="text/javascript">
				$('audio').bind('contextmenu', function() { return false; });
				
				$('#pageload').text("<?php echo get_phrase('page_generated_in').' '.$total_time.'s';?>");
				
				$('#btnpbrate').click(function(event) {
					count+=1;
					ratep = 0.65;
					
					switch (count) {
						case 1:
							audioel[0].playbackRate+=ratep;
							$(this).text((ratec+=ratep) + 'x ');
							$(this).removeClass('btn-default');
							$(this).addClass('btn-danger');
							break;
						case 2:
							audioel[0].playbackRate+=ratep;
							$(this).text((ratec+=ratep) + 'x ');
							$(this).removeClass('btn-default');
							$(this).addClass('btn-danger');
							break;
						case 3:
							audioel[0].playbackRate+=ratep;
							$(this).text((ratec+=ratep).toFixed(2) + 'x ');
							$(this).removeClass('btn-default');
							$(this).addClass('btn-danger');
							break;
						case 4:
							audioel[0].playbackRate=1;
							// $(this).text(1 + 'x ');
							$(this).html('<i class="fa fa-angle-double-right">');
							$(this).removeClass('btn-danger');
							$(this).addClass('btn-default');
							count = 0;
							ratec = 1;
							break;
					}
				});

				$('#btndownload').click(function(event) {
					cropid = $(this).attr('data-cropid');
					$.get('<?php echo base_url("pages/crop_info_radio_knewin_down/"); ?>'+cropid, function(data) {
						console.log(data);
					});
				});

				$(document).keypress(function(event) {
					if (event.which == 32) {
						playpauseaudio('passage-audio');
					}
				});
				
				function playpauseaudio(audioelt) {
					aaudioelmt = $('#'+audioelt);
					if (aaudioelmt[0].paused) {
						aaudioelmt[0].play();
					} else {
						aaudioelmt[0].pause();
					}
				}
			</script>
	</body>
</html>