<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo get_phrase('edit');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<p class="pull-left"><i class="fa fa-key fa-fw"></i> <?php echo $keyword_selected;?></p>
						<p><i class="fa fa-cut fa-fw"></i> <?php echo $state." - ".$radio." - ".date("d/m/Y - H:i:s",$timestamp);?></p>
					</div>
					<div class="panel-body">

						<div class="row">
							<p class="loading"><em><img src="<?php echo base_url('assets/imgs/loader.gif');?>" alt="<?php echo get_phrase('initializing_audio');?>"><?php echo get_phrase('initializing_audio');?></em></p>
							<p class="passage-audio" hidden>
								<audio style="width: 100%;" id="passage-audio" src="<?php echo $mp3pathfilename;?>" autobuffer controls>
									<em class="error"><strong>Error:</strong> Your browser doesn't appear to support HTML5 Audio.</em>
								</audio>
							</p>
							<div class="col-lg-12">
								<div class="row">
									<div class="col-lg-4">
										<p class="passage-audio-unavailable" hidden>
											<em class="error">
												<strong>Error:</strong>
												You will not be able to do the read-along audio because your browser is not able to play MP3 audio formats.
											</em>
										</p>
										<p class="playback-rate" hidden title="Note: that increaseing the reading rate will decrease accuracy of word highlights">
											<label for="playback-rate"><?php echo get_phrase('reading-rate');?>:</label>
											<input style="width: 30%;" id="playback-rate" type="range" min="0.5" max="2.0" value="1.0" step="0.1" onchange='this.nextElementSibling.textContent = String(Math.round(this.valueAsNumber * 10) / 10) + "\u00D7";'>
											<output>1&times;</output>
										</p>
										<p class="playback-rate-unavailable" hidden>
											<em>(It seems your browser does not support
											<code>HTMLMediaElement.playbackRate</code>,
											so you will not be able to change the speech rate.)
											</em>
										</p>
										<p lass="autofocus-current-word" hidden>
											<input type="checkbox" id="autofocus-current-word">
											<label for="autofocus-current-word"><?php echo get_phrase('auto-scroll');?></label>
										</p>
										<noscript>
											<p class="error">
												<em><strong>Notice:</strong> You must have JavaScript enabled/available to try this HTML5 Audio read along.</em>
											</p>
										</noscript>
									</div>
									<div class="col-lg-8">
										<form class="form-horizontal" action="<?php echo site_url('pages/crop')?>" method="post">
											<div class="form-group form-group-sm" id="startselect">
												<label class="col-sm-2 control-label" for="starttime"><?php echo get_phrase('start');?>: </label>
												<div class="col-sm-4">
													<input class="form-control input-sm" type="text" value="<?php echo get_phrase('click_to_select');?>" id="starttime" name="starttime"></input>
												</div>
												<div class="col-sm-2">
													<input class="form-control input-sm" type="text" id="indexstart" name="indexstart"></input>
												</div>
												<div class="col-sm-2">
													<input class="form-control input-sm" type="text" id="mp3pathfilename" name="mp3pathfilename" value="<?php echo $mp3pathfilename;?>"></input>
												</div>
											</div>
											<div class="form-group form-group-sm" id="endselect">
												<label class="col-sm-2 control-label" for="endtime"><?php echo get_phrase('end');?>: </label>
												<div class="col-sm-4">
													<input class="form-control input-sm" type="text" value="<?php echo get_phrase('click_to_select');?>" id="endtime" name="endtime"></input>													</div>
												<div class="col-sm-2">
													<input class="form-control input-sm" type="text" id="indexend" name="indexend"></input>
												</div>
												<div class="col-sm-2">
													<input class="form-control input-sm" type="text" id="keyword_selected" name="keyword_selected" value="<?php echo $keyword_selected;?>"></input>
												</div>
												<div class="col-sm-2">
													<input class="form-control input-sm" type="text" id="radio" name="radio" value="<?php echo $radio;?>"></input>
													<input class="form-control input-sm" type="text" id="state" name="state" value="<?php echo $state;?>"></input>
													<input class="form-control input-sm" type="text" id="timestamp" name="timestamp" value="<?php echo $timestamp;?>"></input>
												</div>
											</div>
											<div class="form-group form-group-sm">
												<div class="col-sm-offset-2 col-sm-2">
													<button class="btn btn-primary btn-sm" type="submit" ><?php echo get_phrase('crop');?></button>
												</div>
											</div>
											<div><textarea id="result" name="result"></textarea></div>
										</form>
										<script>
											$(document).ready(function () {
												var elementsToTrack = $('span');
												var attributes = [];
												var values01 = [];
												var spantext = null;

												for (var i = 0; i != elementsToTrack.length; i++){
													var currentAttr = elementsToTrack[i].attributes;
													var currentValue = elementsToTrack[i];
													attributes.push(currentAttr);
													values01.push(currentValue);
												}

												// console.log(attributes);
												// console.log(values01);

												$("#starttime").focus(function() {
													var timeid01;
													var indexid01;
													$(this).attr('value', '<?php echo get_phrase('select_the_start_word');?>...');
													window.timeid01 = 'starttime';
													window.indexid01 = 'indexstart';
												});

												$("#endtime").focus(function() {
													var timeid01;
													var indexid01;
													$(this).attr('value', '<?php echo get_phrase('select_the_end_word');?>...');
													window.timeid01 = 'endtime';
													window.indexid01 = 'indexend';
												});

												$(document).on('click','span',function() {
													var timeid02 = window.timeid01;
													var indexid02 = window.indexid01;
													var datatime = $(this).attr('data-begin');
													var dataduration = $(this).attr('data-dur');
													var dataindex = $(this).attr('data-index');
													if (timeid02 == 'endtime') {
														var datatime02 = +datatime + +dataduration;
													} else {
														var datatime02 = datatime;
													}
													if (timeid02 != null) {
														document.getElementById(timeid02).value = datatime02;
														document.getElementById(indexid02).value = dataindex;
														if (timeid02 == 'endtime') {
															document.getElementById('indexend').focus();
														}
													}
												});

												$("#indexend").focus(function() {
													var starttimev = document.getElementById('starttime').value;
													var endtimev = document.getElementById('endtime').value;
													var indexstartv = document.getElementById('indexstart').value;
													var indexendv = document.getElementById('indexend').value;

													if (endtimev != null || endtimev != "") {
														if (starttimev > endtimev) {
															alert("O tempo inicial não pode ser maior que o final!");
															document.getElementById('starttime').value = null;
															document.getElementById('starttime').focus();
														}
													}

													var result = document.getElementById('result');
													for (var i = +indexstartv; i <= +indexendv; i++){
														var spantext = values01[i].innerText;
														result.innerHTML += spantext + ' ';
														// console.log(spantext);
													}
												});
											});
										</script>
									</div>
								</div>
							</div>
						</div> <!-- div row -->
						<div class="col-lg-12 text-justify" id="passage-text">
							<p>
							<?php
								$xmlpathfilenamephp = mb_substr($xmlpathfilename, 39);
								$xmldata = simplexml_load_file($xmlpathfilenamephp);
								//$keyword_selected_array = explode(" ", $keyword_selected);
								//$text_array = array();
								$wid  = 0;
								foreach ($xmldata->StorySegment as $storyseg) {
									foreach ($storyseg->TranscriptSegment as $transcseg) {
										$frstwordstart	= $transcseg->TranscriptWordList->Word[0]['start']-1;
										$guid 			= (string)$transcseg->TranscriptGUID;
										$type 			= $transcseg->AudioType;
										$typestr 		= $type[0];
										$typestartms 	= $type['start'];
										$typestarts 	= $typestartms/100;
										$typeendms 	= $type['end'];
										$typeends 		= $typeendms/100;

										$typeends2	= $frstwordstart/100;
										$typeduration 	= number_format($typeends-$typestarts,3,"."," ");
										$typeduration2 = number_format($typeends2-$typestarts,3,"."," ");

										$speaker		= $transcseg->Speaker;
										$speakerg		= $speaker['name'];

										if (!isset($transcseg->TranscriptWordList->Word)){
											echo '<span data-dur="'.$typeduration.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>'."\r\n";
											//array_push($text_array,'<span data-dur="'.$typeduration.'" data-begin="'.$typestarts.'" data-index="'.$wid.'">|'.$typestr.'|</span>');
											// array_push($text_array,'<span data-dur="'.$typeduration.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>');
										}
										else if (isset($transcseg->TranscriptWordList->Word)) {
											echo '<span data-dur="'.$typeduration2.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>'."\r\n";
											//array_push($text_array,'<span data-dur="'.$typeduration2.'" data-begin="'.$typestarts.'" data-index="'.$wid.'">|'.$typestr.'|</span>');
											// array_push($text_array,'<span data-dur="'.$typeduration2.'" data-begin="'.$typestarts.'">|'.$typestr.'|</span>');
											foreach ($transcseg->TranscriptWordList->Word as $transcword) {
												if (empty($transcword['norm'])) {
													$word = $transcword;
												}
												else {
													$word = $transcword['norm'];
												}
												$wordstartms	= $transcword['start'];
												$wordstarts	= $wordstartms/100;
												$wordendms	= $transcword['end'];
												$wordends		= $wordendms/100;
												$wordduration	= number_format($wordends-$wordstarts,3,"."," ");
												$wordpunct	= $transcword['punct'];
												$wid++;
												if (strcasecmp($key,$word) == 0) {
													echo '<span style="color: white; background-color: red; font-size: 110%; font-weight: bold;" data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>'."\r\n";
												} else {
													echo '<span data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>'."\r\n";
												}
												//array_push($text_array,'<span data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'" data-index="'.$wid.'">'.$word.$wordpunct.'</span>');
												//array_push($text_array,'<span data-dur="'.$wordduration.'" data-begin="'.$wordstarts.'">'.$word.$wordpunct.'</span>');
												$wid++;
											}
										}
									}
								}
								//var_dump($keyword_selected_array)."<br>";
								// foreach ($text_array as $row) {
								// 	echo $row."\r\n";
								// }
							?>
							</p>
						</div> <!-- div id passage-text -->
						</div> <!-- div row (inside panel-body) -->
					</div> <!-- div panel-body -->
				</div> <!-- div panel-default -->
			</div> <!-- div col-lg-12 -->
		</div> <!-- div row -->



