<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
		<link rel="stylesheet" href="<?php echo base_url('assets/readalong/style_temp.css');?>"/>
		<script src="<?php echo base_url('assets/jquery/jquery-3.1.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>
		<style type="text/css">
			audio::-internal-media-controls-download-button { display:none; }
			audio::-webkit-media-controls-enclosure { overflow:hidden; }
			audio::-webkit-media-controls-panel { width: calc(100% + 30px); }
		</style>
	</head>

	<body>
	<article>
		<div id="sticky-anchor"></div>

		<div id="sticky">
			<center>
				<h2>
					<?php
						if (!empty($client_selected)) {
							echo get_phrase('client').": ".$client_selected." - ";
						}
						if (!empty($keyword_selected)) {
							echo get_phrase('keyword').": ".$keyword_selected."<br>";
						} else {
							$keyword_selected = null;
						}
						echo $state." - ".$radio." - ".date("d/m/Y - H:i:s",$timestamp);
					?>
				</h2>
			</center>
			<p class="loading">
				<em>
					<img src="<?php echo base_url('assets/imgs/loader.gif');?>" alt="Initializing audio"><?php echo get_phrase('loading_audio_file')?>…
				</em>
			</p>

			<p class="passage-audio" hidden>
				<audio id="passage-audio" class="passage" controls>
					<?php echo '<source src="'.$mp3pathfilename.'" type="audio/mp3">'."\n"; ?>
					<em class="error"><strong><?php echo get_phrase('error')?>:</strong> <?php echo get_phrase('your_browser_do_not_support_html5_audio')?>.</em>
				</audio>
			</p>

			<p class="passage-audio-unavailable" hidden>
				<em class="error">
					<strong>Error:</strong>
					You will not be able to do the read-along audio because your browser is not able to play MP3 audio formats.
				</em>
			</p>

			<div id="readingrate">
				<span class="playback-rate" hidden title="Note that increaseing the reading rate will decrease accuracy of word highlights">
					<label for="playback-rate">Reading rate:</label>
					<input id="playback-rate" type="range" min="0.5" max="2.0" value="1.0" step="0.1" disabled onchange='this.nextElementSibling.textContent = String(Math.round(this.valueAsNumber * 10) / 10) + "\u00D7";'>
					<output>1&times;</output>
				</span>
				<span class="playback-rate-unavailable" hidden>
					<em>
						(It seems your browser does not support
						<code>HTMLMediaElement.playbackRate</code>,
						so you will not be able to change the speech rate.)
					</em>
				</span>
				<span class="autofocus-current-word" hidden>
					<input type="checkbox" id="autofocus-current-word">
					<label for="autofocus-current-word">Auto-scroll</label>
				</span>
				<noscript>
					<span class="error">
						<em><strong>Notice:</strong> You must have JavaScript enabled/available to try this HTML5 Audio read along.</em>
					</span>
				</noscript>
			</div>

			<div id="crop">
				<form action="<?php echo site_url('pages/crop_temp')?>" method="post" accept-charset="utf-8">
					<label for="starttime" id="lstarttime"><?php echo get_phrase('start');?></label>
					<input style="opacity: 0" type="text" value="<?php echo get_phrase('click_to_select');?>" id="starttime" name="starttime"></input>
					<label for="endtime" id="lendtime"><?php echo get_phrase('end');?></label>
					<input style="opacity: 0" type="text" value="<?php echo get_phrase('click_to_select');?>" id="endtime" name="endtime"></input>
					<button disabled id="btncrop" type="submit" class="btn btn-primary"><i class="fa fa-scissors"> <?php echo get_phrase('crop');?></i></button>
					<input style="opacity: 0" type="text" id="indexstart" name="indexstart"></input>
					<input style="opacity: 0" type="text" id="indexend" name="indexend"></input>
					<div id="crop2" hidden>
						<input type="text" id="radio" name="radio" value="<?php echo $radio;?>"></input>
						<input type="text" id="state" name="state" value="<?php echo $state;?>"></input>
						<input type="text" id="client_selected" name="client_selected" value="<?php echo $client_selected;?>"></input>
						<input type="text" id="id_keyword" name="id_keyword" value="<?php echo $id_keyword;?>"></input>
						<input type="text" id="id_client" name="id_client" value="<?php echo $id_client;?>"></input>
						<input type="text" id="id_file" name="id_file" value="<?php echo $id_file;?>"></input>
						<input type="text" id="id_text" name="id_text" value="<?php echo $id_text;?>"></input>
						<input type="text" id="keyword_selected" name="keyword_selected" value="<?php echo $keyword_selected;?>"></input>
						<input type="text" id="timestamp" name="timestamp" value="<?php echo $timestamp;?>"></input>
						<input type="text" id="mp3pathfilename" name="mp3pathfilename" value="<?php echo $mp3pathfilename;?>"></input>
						<textarea id="result" name="result" style="width: 700px; height: 100px"></textarea>
					</div>
					<br>
				</form>
			</div>

			<div id="pageloaddiv">
				<small><span id="pageload"></span></small>
			</div>
		</div>

		<div id="passage-text" class="passage">
			<p>
				<?php
					if (is_array($xmlpathfilename)) {
						$countxmlpath = 0;
						foreach ($xmlpathfilename as $xmlpath) {
							$countxmlpath++;
							// $xmlpathfilenamephp = mb_substr($xmlpathfilename, 39);
							$xmldata = simplexml_load_file($xmlpath);
							$keyword_selected_array = explode(' ',$keyword_selected);
							$ckeyword_selected_array = count($keyword_selected_array);
							$wid = 0;
							$nn=0;
							foreach ($xmldata->StorySegment as $storyseg) {
								foreach ($storyseg->TranscriptSegment as $transcseg) {
									$frstwordstart	= $transcseg->TranscriptWordList->Word[0]['start']-1;
									$guid 			= (string)$transcseg->TranscriptGUID;
									$type 			= $transcseg->AudioType;
									$typestr 		= $type[0];
									if ($countxmlpath > 1) {
										$typestartms 	= $lastwordendms+$type['start'];
										$typestarts 	= $typestartms/100;
										$typeendms 	= $lastwordendms+$type['end'];
										$typeends 		= $typeendms/100;
										$typeends2	= $frstwordstart/100;
									} else {
										$typestartms 	= $type['start'];
										$typestarts 	= $typestartms/100;
										$typeendms 	= $type['end'];
										$typeends 		= $typeendms/100;
										$typeends2	= $frstwordstart/100;
									}
									$typeduration 	= number_format($typeends-$typestarts,3,"."," ");
									$typeduration2 = number_format($typeends2-$typestarts,3,"."," ");

									$speaker		= $transcseg->Speaker;
									$speakerg		= $speaker['name'];

									if (!isset($transcseg->TranscriptWordList->Word)){
										echo '<span data-dur="'.$typeduration.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>'."\r\n";
									}
									else if (isset($transcseg->TranscriptWordList->Word)) {
										echo '<span data-dur="'.$typeduration2.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>'."\r\n";
										foreach ($transcseg->TranscriptWordList->Word as $transcword) {
											if (empty($transcword->Word['norm'])) {
												$word = $transcword;
											}
											else {
												$word = $transcword['norm'];
											}
											if ($countxmlpath > 1) {
												$wordstartms = $lastwordendms+$transcword['start'];
												$wordstarts = $wordstartms/100;
												$wordendms = $lastwordendms+$transcword['end'];
												$wordends = $wordendms/100;
												$wordduration	= number_format($wordends-$wordstarts,3,"."," ");
												$wordpunct = $transcword['punct'];
											} else {
												$wordstartms = $transcword['start'];
												$wordstarts = $wordstartms/100;
												$wordendms = $transcword['end'];
												$wordends = $wordendms/100;
												$wordduration	= number_format($wordends-$wordstarts,3,"."," ");
												$wordpunct = $transcword['punct'];
											}

											$wid++;

											$keyfound = 0;
											for ($n=$nn ; $n < $ckeyword_selected_array; $n++ ) {
												if (strcasecmp($keyword_selected_array[$nn],$word) == 0) {
													echo '<span class="kw" data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>'."\r\n";
													$keyfound = 1;
													$nn++;
													break;
												} else {
													$nn=0;
													break;
												}
											}
											if ($keyfound == 0) {
												echo '<span data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>'."\r\n";
											 }
										}
									}
								}
							}
							$lastwordendms = $wordendms;
						}
					} else {
						// $xmlpathfilenamephp = mb_substr($xmlpathfilename, 39);
						// $xmldata = simplexml_load_file($xmlpathfilenamephp);
						$xmldata = simplexml_load_file($xmlpathfilename);
						$keyword_selected_array = explode(' ',$keyword_selected);
						$ckeyword_selected_array = count($keyword_selected_array);
						$cksa = $ckeyword_selected_array-1;
						$text_array = array();
						$wid = null;
						$nn=0;
						foreach ($xmldata->StorySegment as $storyseg) {
							foreach ($storyseg->TranscriptSegment as $transcseg) {
								$frstwordstart	= $transcseg->TranscriptWordList->Word[0]['start']-1;
								$guid = (string)$transcseg->TranscriptGUID;
								$type = $transcseg->AudioType;
								$typestr = $type[0];
								$typestartms = $type['start'];
								$typestarts = $typestartms/100;
								$typeendms = $type['end'];
								$typeends = $typeendms/100;

								$typeends2 = $frstwordstart/100;
								$typeduration = number_format($typeends-$typestarts,3,"."," ");
								$typeduration2 = number_format($typeends2-$typestarts,3,"."," ");

								$speaker = $transcseg->Speaker;
								$speakerg = $speaker['name'];

								if (!isset($transcseg->TranscriptWordList->Word)){
									echo '<span data-dur="'.$typeduration.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>'."\r\n";
								}
								else if (isset($transcseg->TranscriptWordList->Word)) {
									echo '<span data-dur="'.$typeduration2.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>'."\r\n";
									foreach ($transcseg->TranscriptWordList->Word as $transcword) {
										if (empty($transcword->Word['norm'])) {
											$word = $transcword;
										}
										else {
											$word = $transcword['norm'];
										}
										$wordstartms = $transcword['start'];
										$wordstarts = $wordstartms/100;
										$wordendms = $transcword['end'];
										$wordends = $wordendms/100;
										$wordduration	= number_format($wordends-$wordstarts,3,"."," ");
										$wordpunct = $transcword['punct'];
										$wid++;

										$keyfound = 0;
										for ($n=$nn ; $n < $ckeyword_selected_array; $n++ ) {
											if (strcasecmp($keyword_selected_array[$nn],$word) == 0) {
												echo '<span class="kw" data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>'."\r\n";
												$keyfound = 1;
												$nn++;
												break;
											} else {
												$nn=0;
												break;
											}
										}
										if ($keyfound == 0) {
											echo '<span data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>'."\r\n";
										 }
									}
								}
							}
						}
					}
				?>
			</p>
		</div>

		<script type="text/javascript">
			var elementsToTrack = $('span', $('#passage-text'));
			var texts = [];
			var starttimev;
			var endtimev;
			var indexstartv;
			var indexendv;
			var spantext;
			var result = $('#result');
			var fulltext;

			$('audio').bind('contextmenu', function() { return false; });

			$('#pageload').text("<?php echo get_phrase('page_generated_in').' '.$total_time.'s';?>");

			$('.kw').css({
				'color': 'white',
				'background-color': 'red',
				'font-size': '110%',
				'font-weight': 'bold'
			})

			elementsToTrack.each(function() {
				texts.push($(this).text());
			});

			console.log(texts);

			$('span', $('#passage-text')).mousedown(function() {
				$('#lstarttime').css({
					color: 'black',
					'font-weight': 'normal'
				});
				$('#lstarttime').text('<?php echo get_phrase('start');?>');
				$('#lendtime').css({
					color: 'black',
					'font-weight': 'normal'
				});
				$('#lendtime').text('<?php echo get_phrase('end');?>');
				$('#starttime').val('');
				$('#endtime').val('');
				$('#indexstart').val('');
				$('#indexend').val('');
				result.text('');
				var datatime = $(this).attr('data-begin');
				// var datatimestart = Math.round(datatime).toFixed(3);
				// var datatimestart = datatime.toFixed(2);
				var dataduration = $(this).attr('data-dur');
				var dataindex = $(this).attr('data-index');
				$('#starttime').val(datatime);
				$('#indexstart').val(dataindex);
				$('#lstarttime').prepend('&#10004;')
				$('#lstarttime').css({
					'color': 'green',
					'font-weight': 'bold'
				});
			});

			$('span', $('#passage-text')).mouseup(function() {
				var datatime = $(this).attr('data-begin');
				var dataduration = $(this).attr('data-dur');
				var dataindex = $(this).attr('data-index');
				var	datatime02 = +datatime + +dataduration;
				// var datatimeend = Math.round(datatime02).toFixed(3);
				var datatimeend = datatime02.toFixed(2);
				$('#endtime').val(datatimeend);
				$('#indexend').val(dataindex);
				var starttimev = $('#starttime').val();
				var endtimev = $('#endtime').val();
				var indexstartv = $('#indexstart').val();
				var indexendv = $('#indexend').val();
				if (indexstartv === indexendv) {
					$('#lstarttime').css({
						color: 'black',
						'font-weight': 'normal'
					});
					$('#lstarttime').text('<?php echo get_phrase('start');?>');
					$('#lendtime').css({
						color: 'black',
						'font-weight': 'normal'
					});
					$('#lendtime').text('<?php echo get_phrase('end');?>');
					$('#btncrop').prop('disabled', 'true');
					$('#starttime').val('');
					$('#endtime').val('');
					$('#indexstart').val('');
					$('#indexend').val('');
					result.text('');
					return;
				}
				if (+starttimev > +endtimev) {
					$('#lstarttime').text('<?php echo get_phrase('start');?>');
					$('#lendtime').text('<?php echo get_phrase('end');?>');
					$('#lstarttime').css({
						color: 'red',
						'font-weight': 'bold'
					});
					$('#lendtime').css({
						color: 'red',
						'font-weight': 'bold'
					});
					$('#lstarttime').prepend('&times;');
					$('#lendtime').prepend('&times;');
					$('#btncrop').prop('disabled', 'true');
					$('#starttime').val('');
					$('#endtime').val('');
					$('#indexstart').val('');
					$('#indexend').val('');
					result.text('');
					swal("Atenção!", "O tempo final deve ser maior que o inicial.", "error");
					return;
				}
				for (var i = +indexstartv; i <= +indexendv; i++){
					if (texts[i] == '|Clean|') {
						spantext = null;
					}
					else if (texts[i]  == '|Noise|') {
						spantext = null;
					}
					else if (texts[i] == '|Music|') {
						spantext = null;
					}
					else {
						spantext = texts[i];
						// console.log(spantext);
						result.append(spantext + ' ');
					}
				}
				$('#lendtime').prepend('&#10004;')
				$('#lendtime').css({
					color: 'green',
					'font-weight': 'bold'
				});
				if ($('#result').text() == null || $('#result').text() == '') {
					$('#lstarttime').text('<?php echo get_phrase('start');?>');
					$('#lendtime').text('<?php echo get_phrase('end');?>');
					$('#lstarttime').css({
						color: 'red',
						'font-weight': 'bold'
					});
					$('#lendtime').css({
						color: 'red',
						'font-weight': 'bold'
					});
					$('#lstarttime').prepend('&times;');
					$('#lendtime').prepend('&times;');
					$('#btncrop').prop('disabled', 'true');
					$('#starttime').val('');
					$('#endtime').val('');
					$('#indexstart').val('');
					$('#indexend').val('');
					alert('Por favor, selecione o texto novamente!');
				} else {
					$('#btncrop').removeAttr('disabled')
				}
			});

			function sticky_relocate() {
				var window_top = $(window).scrollTop();
				var div_top = $('#sticky-anchor').offset().top;
				if (window_top > div_top) {
					$('#sticky').addClass('stick');
					$('#sticky-anchor').height($('#sticky').outerHeight());
				} else {
					$('#sticky').removeClass('stick');
					$('#sticky-anchor').height(0);
				}
			}

			$(function() {
				$(window).scroll(sticky_relocate);
				sticky_relocate();
			});
		</script>

		<footer class="credits">
			<a rel="author" href="http://www.dataclip.com.br/" target="_blank">DataClip</a><br>
			Business Inteligence
		</footer>
	</article>
	<script src="<?php echo base_url('assets/readalong/read-along.js');?>"></script>
	<script src="<?php echo base_url('assets/readalong/main.js');?>"></script>
	</body>
</html>