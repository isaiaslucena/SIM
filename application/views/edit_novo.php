<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($novodoc)) {
	$timezone = new DateTimeZone('UTC');
	$sd = new Datetime($novodoc->response->docs[0]->starttime_dt);
	$ed = new Datetime($novodoc->response->docs[0]->endtime_dt);
	$newtimezone = new DateTimeZone('America/Sao_Paulo');
	$sd->setTimezone($newtimezone);
	$ed->setTimezone($newtimezone);
	$sstartdate = $sd->format('d/m/Y H:i:s');
	$senddate = $ed->format('d/m/Y H:i:s');

	$idnovo = $novodoc->response->docs[0]->id_i;
	$rstartdate = $novodoc->response->docs[0]->starttime_dt;
	$renddate = $novodoc->response->docs[0]->endtime_dt;
	$ssource = $novodoc->response->docs[0]->source_s;
	$mediaurl = $novodoc->response->docs[0]->mediaurl_s;
	$content = $novodoc->response->docs[0]->content_t[0];
} else {
	$timezone = new DateTimeZone('UTC');
	$sd = new Datetime($starttime_dt);
	$ed = new Datetime($endtime_dt);
	$newtimezone = new DateTimeZone('America/Sao_Paulo');
	$sd->setTimezone($newtimezone);
	$ed->setTimezone($newtimezone);
	$sstartdate = $sd->format('d/m/Y H:i:s');
	$senddate = $ed->format('d/m/Y H:i:s');

	if (isset($idnovo)) {
		$idnovo = $id_i;
	} else {
		$idnovo = 0;
	}
	$rstartdate = $starttime_dt;
	$renddate = $endtime_dt;
	$ssource = $source_s;
	$mediaurl = $mediaurl_s;
	$content = $content_t;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Transcrição do Áudio</title>

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

			.kword{
				color: white;
				background-color: red;
				font-size: 110%;
			}

			.selectedt{
				color: white;
				background-color: darkblue;
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

			<div class="row">
				<div class="col-lg-8">
					<audio id="passage-audio" src="<?php echo $mediaurl; ?>" style="width: 100%" controls></audio>
				</div>

				<div class="col-lg-4">
					<div class="btn-group" role="group" aria-label="...">
						<a id="btnpbrate" type="button" class="btn btn-default" title="Aumentar velocidade"><i class="fa fa-angle-double-right"></i></a>
						<a id="btncstart" type="button" class="btn btn-default" title="Marcar início"><i class="fa fa-hourglass-start"></i></a>
						<a id="btncend" type="button" class="btn btn-default disabled" title="Marcar fim" disabled><i class="fa fa-hourglass-end"></i></a>
						<button id="btncrop" type="submit" form="cropnovo" class="btn btn-default disabled" title="Cortar" data-toggle="modal" disabled><i class="fa fa-scissors"></i></button>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<form id="cropnovo" action="<?php echo site_url('pages/crop_novo'); ?>" method="post" accept-charset="utf-8" style="display: none;">
						<input type="text" id="starttime" name="starttime">
						<input type="text" id="endtime" name="endtime">
						<input type="text" id="ssource" name="ssource" value="<?php echo $ssource; ?>">
						<input type="text" id="client_selected" name="client_selected" value="<?php echo $client_selected; ?>">
						<input type="text" id="id_keyword" name="id_keyword" value="<?php echo $id_keyword; ?>">
						<input type="text" id="id_client" name="id_client" value="<?php echo $id_client; ?>">
						<input type="text" id="id_doc" name="id_doc" value="<?php echo $idnovo; ?>">
						<input type="text" id="id_join_info" name="id_join_info" value="<?php echo isset($id_join_info) ? $id_join_info : '' ?>">
						<input type="text" id="keyword_selected" name="keyword_selected" value="<?php echo $keyword_selected; ?>">
						<input type="text" id="startdate" name="startdate" value="<?php echo $rstartdate; ?>">
						<input type="text" id="enddate" name="enddate" value="<?php echo $renddate; ?>">
						<input type="text" id="mediaurl" name="mediaurl" value="<?php echo $mediaurl; ?>">
						<textarea id="textseld" name="textseld" style="width: 700px; height: 100px"></textarea>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<p id="passage-text" class="text-justify center-block" style="overflow-y: auto; max-height: 400px"> 
						<?php echo $content; ?>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<small id="pageload" class="text-muted pull-right"></small>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			var starttimev, endtimev, indexstartv, indexendv, spantex, cropstart,
			cropend, cropstartss, cropendss, cropstarts, cropends, fulltext;
			var ccrops = false, ccrope = false, croptext = false;
			var result = $('#result');
			var audioel = $('#passage-audio');
			var count = 0;
			var ratec = 1;

			$('audio').bind('contextmenu', function() { return false; });

			$('#playback-rate').change(function(event) {
				this.nextElementSibling.textContent = String(Math.round(this.valueAsNumber * 10) / 10) + "\u00D7";
				audioel[0].playbackRate = event.target.value;
			});

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

			$(document).keypress(function(event) {
				if (event.which == 32) {
					playpauseaudio('passage-audio');
				}
			});

			$('#btncstart').click(function(event) {
				cropstartss = audioel[0].currentTime;
				cropstarts = (cropstartss * 100 / 100).toFixed(3);

				if (parseInt(cropendss) < parseInt(cropstartss) || parseInt(cropendss) == parseInt(cropstartss)) {
					swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
					$(this).text(null);
					$(this).append('<i class="fa fa-hourglass-start"></i>');
					$(this).removeClass('btn-success');
					$(this).addClass('btn-default');
					ccrope = false;
				} else {
					cropstartms = cropstarts.split(".")
					cropstartt = sectostring(cropstartss);
					cropstart = cropstartt.replace(":", "-");
					ccrops = true;

					$(this).text(null);
					$(this).append('<i class="fa fa-hourglass-start"></i>');
					$(this).removeClass('btn-default');
					$(this).addClass('btn-success');
					$(this).append(' '+cropstartt);

					$('#btncend').removeClass('disabled');
					$('#btncend').removeAttr('disabled');

					$('#starttime').val(cropstarts);

					console.log('crop starttime (string): '+cropstartt);
					console.log('crop starttime (seconds): '+cropstarts);
				}
			});

			$('#btncend').click(function(event) {
				console.log(croptext);
				cropendss = audioel[0].currentTime;
				cropends = (cropendss * 100 / 100).toFixed(3);

				if (ccrops) {
					if (parseInt(cropendss) < parseInt(cropstartss) || parseInt(cropendss) == parseInt(cropstartss)) {
						swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
						$(this).text(null);
						$(this).append('<i class="fa fa-hourglass-end"></i>');
						$(this).removeClass('btn-success');
						$(this).addClass('btn-default');
						ccrope = false;
					} else {
						time = $(this).text();

						if (time != '') {
							$(this).text(null);
							$(this).append('<i class="fa fa-hourglass-end"></i>');
						}

						cropendms = cropends.split(".");
						cropendt = sectostring(cropendss);
						cropend = cropendt.replace(":", "-");

						cropdurs = (cropends - cropstarts).toFixed(3);
						cropdurmm = ('0' + Math.floor(cropdurs / 60)).slice(-2);
						cropdurss = ('0' + Math.floor(cropdurs - cropdurmm * 60)).slice(-2);
						cropdur = '00-'+cropdurmm+'-'+cropdurss;
						ccrope = true;

						$(this).text(null);
						$(this).append('<i class="fa fa-hourglass-end"></i>');
						$(this).removeClass('btn-default');
						$(this).addClass('btn-success');
						$(this).append(' '+cropendt);

						$('#endtime').val(cropends);

						console.log('crop endtime (string): '+cropendt);
						console.log('crop endtime (seconds): '+cropends);
					}

					if (croptext) {
						$('#btncrop').removeClass('disabled');
						$('#btncrop').removeAttr('disabled');
					}
				} else {
					swal("Atenção!", "Você deve marcar primeiro o tempo inicial.", "error");
				}
			});

			$('#passage-text').mouseup(function(e) {
				// selection = window.getSelection().toString();
				// $('#textseld').val(selection.toString());
				// console.log(window. window.getSelection().getRangeAt(0));

				$(this).children().removeClass('selectedt');

				// ftext1 = $('#passage-text').html();
				// pattern = new RegExp('<span class="selectedt">', 'g');
				// replacement = '';
				// ftext2 = ftext1.replace(pattern, replacement);
				// pattern = new RegExp('<\/span>', 'g');
				// tresult = ftext2.replace(pattern, replacement);
				// console.log(tresult);
				// $(this).html(result);

				var selection = window.getSelection().getRangeAt(0);
				// console.log(selection);
				var selectedText = selection.extractContents();
				console.log(selectedText);
				var span = $('<span class="selectedt">'+selectedText.textContent+'</span>');
				// console.log(span);
				selection.insertNode(span[0]);

				// if (selectedText.childNodes[1] != undefined) {
				// 	childn = $(selectedText.childNodes[1]);
				// 	// console.log(childn);
				// 	childn.remove();
				// }

				var txt = $('#passage-text').html();
				$('#passage-text').html(txt.replace(/<\/span>(?:\s)*<span class="selectedt">/g, ''));

				if (document.selection) {
					document.selection.empty();
				} else if (window.getSelection) {
					window.getSelection().removeAllRanges();
				}

				$('#textseld').val(selectedText.textContent);

				croptext = true;

				if (ccrope) {
					$('#btncrop').removeClass('disabled');
					$('#btncrop').removeAttr('disabled');
				}
			});

			$('btncrop').click(function(event) {
				swal("Aguarde", "Carregando...", "warn");
			});

			$('#pageload').text("<?php echo get_phrase('page_generated_in').' '.$total_time.'s';?>");

			function sectostring(secs) {
				var sec_num = parseInt(secs, 10);
				var hours   = Math.floor(sec_num / 3600);
				var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
				var seconds = sec_num - (hours * 3600) - (minutes * 60);
				var mseconds = String(secs);
				var milliseconds =  mseconds.slice(-3);

				if (hours  < 10) {hours = "0" + hours;}
				if (minutes < 10) {minutes = "0" + minutes;}
				if (seconds < 10) {seconds = "0" + seconds;}
				return hours+':'+minutes+':'+seconds+'.'+milliseconds;
			}

			function playpauseaudio(audioelt) {
				aaudioelmt = $('#'+audioelt);
				if (aaudioelmt[0].paused) {
					aaudioelmt[0].play();
				} else {
					aaudioelmt[0].pause();
				}
			}

			$(document).ready(function() {
				keyword = '<?php echo $keyword_selected; ?>';

				pbodytext = $('#passage-text').text();
				rgx = new RegExp ('\\b'+keyword+'\\b', 'ig');
				pbodynewtext = pbodytext.replace(rgx, '<strong class="kword">'+keyword+'</strong>');
				$('#passage-text').html(null);
				$('#passage-text').html(pbodynewtext);
			});
		</script>
	</body>
</html>