<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					Editar áudio
				</h1>
			</div>
		</div>

		<div class="row">
			<div id="divbigimg" class="col-lg-12 text-center center-block">
				<div id="wellbigimg" class="well">
					<i id="iconimg" class="fa fa-file-audio-o fw" style="font-size: 4em"></i>
					<audio id="audiofile" style="display: none; width: 100%;" controls></audio>
					<div id="waitimg" style="display: none;">
						<img src="<?php echo base_url('assets/imgs/loading.gif')?>" alt="Carregando" width="60">
						<h3 id="waitmsg">Carregando...</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-3">
				<label class="btn btn-default">
					<i class="fa fa-upload"></i> Selecionar arquivos
					<input id="btnupload" type="file" multiple style="display: none;">
				</label>
			</div>

			<div class="col-lg-9">
				<a id="btnpbrate" type="button" class="btn btn-default disabled" title="Aumentar velocidade" disabled><i class="fa fa-angle-double-right"></i> Velocidade</a>
				<a id="btncstart" type="button" class="btn btn-default disabled" title="Marcar início" disabled><i class="fa fa-hourglass-start"></i> Início</a>
				<a id="btncend" type="button" class="btn btn-default disabled" title="Marcar fim" disabled><i class="fa fa-hourglass-end"></i> Fim</a>
				<a id="btncrop" type="button" class="btn btn-default disabled" title="Cortar" data-joinid="0" disabled><i class="fa fa-scissors"></i> Cortar</a>
				<a id="btndown" type="button" class="btn btn-default disabled" title="Baixar" data-cropid="null" disabled><i class="fa fa-download"></i> Baixar</a>
			</div>
		</div>

		<script type="text/javascript">
			var count = 0, fcount, ratec = 1,
			audioel = $('#audiofile'),
			ccrops = false, ccrope = false, joinfiles = false, fileerr = false, lastfile,
			audiofiles, cropstart, cropend, cropstartss, cropendss, cropstarts, cropends,
			audiofilestojoin = [], uploadaudiofiles = [];

			$('#btnupload').change(function(event) {
				audiofilestojoin = [];
				uploadaudiofiles = [];

				audioel.css('display', 'none');
				$('#iconimg').css('display', 'none');
				$('#wellbigimg').css('vertical-align', 'center');
				$('#wellbigimg').addClass('center-block');
				$('#waitmsg').text('Aguarde...');
				$('#waitimg').css('display', 'block');

				var audiofiles = document.querySelector('#btnupload').files;
				audiofilesc = audiofiles.length;

				if (audiofilesc > 1) {
					fcount = 1;
					$.each(audiofiles, function(index, val) {
						// console.log(val);
						var filelastmod = val.lastModified;
						var filename = val.name;
						var filesize = val.size;
						var filetype = val.type;
						var fileb64;

						if (filetype != 'audio/mp3' && filetype != 'audio/mpeg') {
							swal('Atenção', 'O arquivo "' + filename + '" não é um arquivo de áudio.\n\nTodos os arquivos devem ser do tipo mp3!', 'error');
							joinfiles = false;
							fileerr = true;
							return false;
						} else {
							audiofilestojoin.push(filename);

							filereader = new FileReader();
							filereader.readAsDataURL(val);
							filereader.onload = function(e) {
								// console.log(e);
								fileb64 = e.target.result.replace(/^data:audio\/(mp3|mpeg);base64,/, "");
								uploadaudiofiles.push(
									$.post('<?php echo base_url("pages/upload_join_edit_audio")?>',
										{'audioname': filename,'audiofile': fileb64}
									)
								);
								if (fcount == audiofilesc) {
									lastfile = filename;
								}
								fcount += 1;
							}
						}
					});

					if (fileerr) {
						$('#waitimg').css('display', 'none');
						$('#iconimg').css('display', 'block');
						fileerr = false;
						lastfile = null;
						uploadaudiofiles = [];
						audiofilestojoinc = [];
						return false;
					}
				}

				audiofilestojoinc = audiofilestojoin.length;
				console.log(audiofilestojoinc);
				if (audiofilestojoinc > 1) {
					$(document).ajaxComplete(function(event, xhr, settings) {
						// console.log(xhr);
						fdata = settings.data;
						rgx = new RegExp ('\\b'+lastfile+'\\b', 'ig');
						if (settings.url === '<?php echo base_url("pages/upload_join_edit_audio")?>' && rgx.test(fdata)) {
							join_files(audiofilestojoin);
							lastfile = null;
							audiofilestojoinc = [];
							uploadaudiofiles = [];
						}
					});
				} else {
					joinfiles = false;
					var filereader = new FileReader();
					filereader.readAsDataURL(audiofiles[0]);

					filereader.onload = function(e) {
						// console.log(e);
						enable_editor(e.target.result);
					}
				}
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
						$(this).html('<i class="fa fa-angle-double-right"></i> Velocidade');
						$(this).removeClass('btn-danger');
						$(this).addClass('btn-default');
						count = 0;
						ratec = 1;
						break;
				}
			});

			$(document).keypress(function(event) {
				if (event.which == 32) {
					playpauseaudio('audiofile');
				}
			});

			$(document).keydown(function(event) {
				if (event.which == 37) {
					//Left
					seektime = audioel[0].currentTime - 0.1;
					audioel[0].currentTime = seektime;
				} else if (event.which == 39) {
					//Right
					seektime = audioel[0].currentTime + 0.1;
					audioel[0].currentTime = seektime;
				}
			});

			$('#btncstart').click(function(event) {
				cropstartss = audioel[0].currentTime;
				cropstarts = (cropstartss * 100 / 100).toFixed(3);

				if (parseInt(cropendss) < parseInt(cropstartss) || parseInt(cropendss) == parseInt(cropstartss)) {
					swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
					$(this).text(null);
					$(this).append('<i class="fa fa-hourglass-start"></i> Início');
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

					console.log('crop starttime (string): '+cropstartt);
					console.log('crop starttime (seconds): '+cropstarts);
				}
			});

			$('#btncend').click(function(event) {
				cropendss = audioel[0].currentTime;
				cropends = (cropendss * 100 / 100).toFixed(3);

				if (ccrops) {
					if (parseInt(cropendss) < parseInt(cropstartss) || parseInt(cropendss) == parseInt(cropstartss)) {
						swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
						$(this).text(null);
						$(this).append('<i class="fa fa-hourglass-end"></i> Fim');
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

						$('#btncrop').removeClass('disabled');
						$('#btncrop').removeAttr('disabled');

						console.log('crop endtime (string): '+cropendt);
						console.log('crop endtime (seconds): '+cropends);
					}
				} else {
					swal("Atenção!", "Você deve marcar primeiro o tempo inicial.", "error");
				}
			});

			$('#btncrop').click(function(event) {
				playpauseaudio('audiofile');
				audioel.css('display', 'none');
				cjoinid = $(this).attr('data-joinid');
				$('#waitmsg').text('Carregando...');
				$('#waitimg').css('display', 'block');

				$.post('<?php echo base_url("pages/crop_edit_audio")?>',
					{
						audiofile: joinfiles ? audioel.attr('src').replace('<?php echo base_url("assets/temp/")?>', '') : audioel.attr('src').replace(/^data:audio\/(mp3|mp4);base64,/, ""),
						starttime: cropstarts,
						endtime: cropends,
						joinid: cjoinid,
						join: joinfiles
					},
					function(data, textStatus, xhr) {
						console.log(data);
						afileurl = data.cropfileurl;
						audioel.attr('src', afileurl);
						acropid = data.crop_inserted_id;
						$('#btndown').attr('data-cropid', acropid);
						$('#btndown').attr('href', afileurl);
						$('#btndown').attr('download', 'audio_editado_'+Date.now());
						$('#waitimg').css('display', 'none');
						audioel.css('display', 'block');

						disable_editor();
					}
				);
			});

			$('#btndown').click(function(event) {
				cropid = $(this).attr('data-cropid');
				$.get('<?php echo base_url("pages/crop_info_edit_audio_down/"); ?>'+cropid, function(data) {
					console.log(data);
				});
			});

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

			function join_files(jaudiofilestojoin) {
				$('#waitmsg').text('Aguarde, juntando arquivos...');

				$.post('<?php echo base_url("pages/join_edit_audio"); ?>', {adfiles: jaudiofilestojoin},
					function(data, textStatus, xhr) {
						// console.log(data);
						console.log('Joined files!')
						joinfiles = true;
						joinned_id = data.id_join_info;
						$('#btncrop').attr('data-joinid', joinned_id);
						enable_editor(data.finalurl);
					}
				);
			}

			function enable_editor(audiodata) {
				audioel.attr('src', audiodata);
				audioel.css('display', 'block');

				$('#btncstart').text(null);
				$('#btncstart').append('<i class="fa fa-hourglass-start"></i> Início');
				$('#btncstart').removeClass('btn-success');
				$('#btncstart').addClass('btn-default');

				$('#btncend').text(null);
				$('#btncend').append('<i class="fa fa-hourglass-end"></i> Fim');
				$('#btncend').removeClass('btn-success');
				$('#btncend').addClass('btn-default');

				ccrops = false;
				ccrope = false;
				cropstart = null;
				cropend = null;
				cropstartss = null;
				cropendss = null;
				cropstarts = null;
				cropends = null;

				$('#btnpbrate').removeClass('disabled');
				$('#btnpbrate').removeAttr('disabled');
				$('#btncstart').removeClass('disabled');
				$('#btncstart').removeAttr('disabled');
				$('#btndown').addClass('disabled');
				$('#btndown').attr('disabled', true);

				$('#waitimg').css('display', 'none');
			}

			function disable_editor() {
				$('#btncstart').text(null);
				$('#btncstart').append('<i class="fa fa-hourglass-start"></i> Início');
				$('#btncstart').removeClass('btn-success');
				$('#btncstart').addClass('btn-default');

				$('#btncend').text(null);
				$('#btncend').append('<i class="fa fa-hourglass-end"></i> Fim');
				$('#btncend').removeClass('btn-success');
				$('#btncend').addClass('btn-default');
				ccrops = false;
				ccrope = false;
				joinfiles = false;
				cropstart = null;
				cropend = null;
				cropstartss = null;
				cropendss = null;
				cropstarts = null;
				cropends = null;

				$('#btncstart').addClass('disabled');
				$('#btncstart').attr('disabled', true);
				$('#btncend').addClass('disabled');
				$('#btncend').attr('disabled', true);
				$('#btncrop').addClass('disabled');
				$('#btncrop').attr('disabled', true);
				$('#btndown').removeClass('disabled');
				$('#btndown').removeAttr('disabled');
			}
		</script>