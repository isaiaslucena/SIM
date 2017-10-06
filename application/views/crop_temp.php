<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Arquivo Cortado</title>
	<script src="<?php echo base_url('assets/jquery/jquery-3.1.1.min.js');?>"></script>
	<link rel="stylesheet" href="<?php echo base_url('assets/readalong/style_temp.css');?>"/>

	<style type="text/css">
		.form-control {
			/* display: block; */
			width: 98%;
			height: 250px;
			padding: 6px 12px;
			font-size: 14px;
			line-height: 1.42857143;
			color: #555;
			background-color: #fff;
			background-image: none;
			border: 1px solid #ccc;
			border-radius: 4px;
				-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
			box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
				-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
				-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
			transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
		}
		audio::-internal-media-controls-download-button {
			display:none;
		}

		audio::-webkit-media-controls-enclosure {
			overflow:hidden;
		}

		audio::-webkit-media-controls-panel {
			width: calc(100% + 30px); /* Adjust as needed */
		}
	</style>
</head>
<body>
	<article>
		<center>
			<h2>
				<?php
					$temppath = base_url('assets/temp/');
					echo get_phrase('client').": ".$client_selected." - ";
					echo get_phrase('keyword').': '.$keyword_selected.'<br>';
					echo $state.' - '.$radio.' - '.date('d/m/Y - H:i:s',$timestamp);
					$filename = mb_substr($mp3pathfilename, 74);
				?>
			</h2>
		</center>

			<p hidden class="loading"><em><img src="<?php echo base_url('assets/imgs/loader.gif');?>" alt="Initializing audio"><?php echo get_phrase('loading_audio_file')?>…</em></p>

			<p class="passage-audio">
				<audio id="passage-audio" class="passage" controls>
					<source src="<?php echo $finalfile ?>" type="audio/mp3">
					<em class="error"><strong>Error:</strong> <?php echo get_phrase('your_browser_do_not_support_html5_audio')?>.</em>
				</audio>
				<br>
				<a href="<?php echo $finalfile;?>" download="<?php echo  mb_strtoupper(date('d-m-Y',$timestamp).'_'.$radio.'_'.$state.'_'.date('H\hi\m',$timestamp).'_'.$client_selected);?>"><span><?php echo get_phrase('download');?></span></a>
				<small><span style="display: inline-block; text-align: right;" id="pageload"></span></small>
			</p>
			<br>

			<p class="passage-audio-unavailable" hidden>
				<em class="error">
					<strong>Error:</strong>
					You will not be able to do the read-along audio because your browser is not able to play MP3 audio formats.
				</em>
			</p>

			<p class="playback-rate" hidden title="Note that increaseing the reading rate will decrease accuracy of word highlights">
				<label for="playback-rate">Reading rate:</label>
				<input id="playback-rate" type="range" min="0.5" max="2.0" value="1.0" step="0.1" disabled onchange='this.nextElementSibling.textContent = String(Math.round(this.valueAsNumber * 10) / 10) + "\u00D7";'>
				<output>1&times;</output>
			</p>

			<p class="playback-rate-unavailable" hidden>
				<em>(It seems your browser does not support
				<code>HTMLMediaElement.playbackRate</code>,
				so you will not be able to change the speech rate.)
				</em>
			</p>

			<p class="autofocus-current-word" hidden>
				<input type="checkbox" id="autofocus-current-word">
				<label for="autofocus-current-word">Auto-focus/auto-scroll</label>
			</p>

			<noscript>
				<p class="error">
					<em><strong>Notice:</strong> You must have JavaScript enabled/available to try this HTML5 Audio read along.</em>
				</p>
			</noscript>

			<div id="passage-text" class="passage">
				<textarea style="text-align: justify; white-space: normal;" class="form-control"><?php echo $text_crop;?></textarea>
			</div>

		<script type="text/javascript">
			$('audio').bind('contextmenu',function() { return false; });
			$('#pageload').text("<?php echo get_phrase('page_generated_in').' '.$total_time.'s';?>");
		</script>

		<footer class="credits">
			<a rel="author" href="http://www.dataclip.com.br/" target="_blank">DataClip</a><br>
			Business Inteligence
		</footer>
	</article>
</body>
</html>