		</script>
		<?php
				$adata = array(
					'keyword' => 0,
					'start' => 0,
					'rows' => 10,
					'ktfound' => 0,
					'id_keyword' => 0,
					'id_client' => 0,
					'id_source' => 0,
					'client_selected' => 0,
					'startdate' => date('Y-m-d H:i:s'),
					'enddate' => date('Y-m-d H:i:s'),
					'msc' => 'local',
					'mtype' => 'video',
					'pagesrc' => null
				);

				$jdata = base64_encode(json_encode($adata));
			?>
			<script src="<?php echo base_url('assets/readalong/readalong.js');?>"></script>
			<script src="<?php echo base_url('pages/hkw_functions');?>"></script>
			<script src="<?php echo base_url('pages/hkw_listeners/').$jdata;?>"></script>
	</body>
</html>