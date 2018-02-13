<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Live Channels</title>
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
		<link rel="stylesheet" href="<?php echo base_url('assets/video/live.css');?>"/>
	</head>
	<body>
		<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<video id="video"></video>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
			if(Hls.isSupported()) {
				var video = document.getElementById('video');
				var hls = new Hls();
				hls.loadSource('http://virtua.dataclip.com.br:8081/indian02/tv/MIDIA/02-February/2018-02-13/GLOBO_RJ/playlist.m3u8');
				hls.attachMedia(video);
				hls.on(Hls.Events.MANIFEST_PARSED,function() {
					video.play();
				});
			}
			// hls.js is not supported on platforms that do not have Media Source Extensions (MSE) enabled.
			// When the browser has built-in HLS support (check using `canPlayType`), we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video element throught the `src` property.
			// This is using the built-in support of the plain video element, without using hls.js.
			else if (video.canPlayType('application/vnd.apple.mpegurl')) {
				video.src = 'http://virtua.dataclip.com.br:8081/indian02/tv/MIDIA/02-February/2018-02-13/GLOBO_RJ/playlist.m3u8';
				video.addEventListener('canplay',function() {
					video.play();
				});
			}
		</script>
	</body>
</html>
