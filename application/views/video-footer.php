		</script>
		<?php
			if (isset($sid)) {
				$adata = array(
					'keyword' => $keyword,
					'start' => 0,
					'rows' => 10,
					'ktfound' => $found->response->numFound,
					'id_keyword' => $id_keyword,
					'id_client' => $id_client,
					'id_source' => $id_source,
					'client_selected' => $client_selected,
					'startdate' => $startdate,
					'enddate' => $enddate,
					'msc' => 'local',
					'mtype' => 'video',
					'pagesrc' => $pagesrc
				);

				$jdata = base64_encode(json_encode($adata));
			?>

			<script src="<?php echo base_url('assets/readalong/readalong.js');?>"></script>
			<script src="<?php echo base_url('pages/hkw_functions');?>"></script>
			<script src="<?php echo base_url('pages/hkw_listeners/').$jdata;?>"></script>
			<?php }
		?>
	</body>
</html>