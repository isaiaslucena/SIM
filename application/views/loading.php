<body>
	<style type="text/css">
		.spinner {
			margin: 100px auto 0;
			width: 70px;
			text-align: center;
			transition: 1s;
		}

		.spinner > div {
			width: 18px;
			height: 18px;
			background-color: #333;

			border-radius: 100%;
			display: inline-block;
			-webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
			animation: sk-bouncedelay 1.4s infinite ease-in-out both;
		}

		.spinner .bounce1 {
			-webkit-animation-delay: -0.32s;
			animation-delay: -0.32s;
		}

		.spinner .bounce2 {
			-webkit-animation-delay: -0.16s;
			animation-delay: -0.16s;
		}

		@-webkit-keyframes sk-bouncedelay {
			0%, 80%, 100% { -webkit-transform: scale(0) }
			40% { -webkit-transform: scale(1.0) }
		}

		@keyframes sk-bouncedelay {
			0%, 80%, 100% {
				-webkit-transform: scale(0);
				transform: scale(0);
			} 40% {
				-webkit-transform: scale(1.0);
				transform: scale(1.0);
			}
		}
		.progress {
			display: block;
			text-align: center;
			width: 0;
			height: 5px;
			background: black;
			transition: width .3s;
		}
		.progress.hide {
			opacity: 0;
			transition: opacity 1.3s;
		}
	</style>

	<div class="spinner" id="loading_spinner">
		<div class="bounce1"></div>
		<div class="bounce2"></div>
		<div class="bounce3"></div>
	</div>

	<div id="fullpage"></div>

	<?php
		if (!isset($ff_ids_files_xml)) {
			$ff_id_radio = 'none';
			$ff_id_client = 'none';
			$ff_id_keyword = 'none';
			$ff_ids_files_xml = 'none';
			$ff_ids_files_mp3 = 'none';
			$ff_timestamp = 'none';
			$client_selected = 'none';
			$keyword_selected = 'none';
			$radio = 'none';
			$state = 'none';
		}
	?>
	<script type="text/javascript">
		var page = '<?php echo base_url($page)?>';
		var pagejoin = '<?php echo base_url('pages/join')?>';
		var plimit = 0;
		var poffset = 5;
		var selected_date = 'today';

		$('#loading_spinner').show();

		if (page == pagejoin) {
			$.ajax({
				url: page,
				type: 'POST',
				data: {
					ff_id_radio: <?php echo $ff_id_radio ?>,
					ff_id_client: <?php echo $ff_id_client ?>,
					id_keyword: <?php echo $ff_id_keyword ?>,
					ff_ids_files_xml: '<?php echo $ff_ids_files_xml ?>',
					ff_ids_files_mp3: '<?php echo $ff_ids_files_mp3 ?>',
					timestamp: <?php echo $ff_timestamp ?>,
					client_selected: '<?php echo $client_selected ?>',
					keyword_selected: '<?php echo $keyword_selected ?>',
					radio: '<?php echo $radio ?>',
					state: '<?php echo $state ?>',
					page: page
				},
				success: function(data) {
					$('#fullpage').html(data)
					$('#loading_spinner').hide()
				},
				error: function() {
					alert("Error!")
				}
			})
		} else {
			$.ajax({
				url: page+'/'+selected_date+'/'+plimit+'/'+poffset,
				success: function(data) {
					$('#fullpage').html(data);
					$('#loading_spinner').hide();
				},
				error: function() {
					alert("Erro! Por favor, entre em contato com o administrador do sistema!");
				}
			})
		}
	</script>
</body>