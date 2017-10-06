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

									</div>
								</div>
							</div>
						</div> <!-- div row -->
						<div class="col-lg-12 text-justify" id="passage-text">
							<p>
								<?php echo $text_crop;?>
							</p>
						</div> <!-- div id passage-text -->
						</div> <!-- div row (inside panel-body) -->
					</div> <!-- div panel-body -->
				</div> <!-- div panel-default -->
			</div> <!-- div col-lg-12 -->
		</div> <!-- div row -->



