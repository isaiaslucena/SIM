<?php
	defined('BASEPATH') OR exit('No direct script access allowed'); 
?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row page-header">
			<div class="col-lg-12">
				<h1><?php echo get_phrase('reports');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">

				<!-- <table>
					<tbody>
						<tr>
							<td></td>
							<td class="info" width="15" style="border:1px solid black"></td>
							<td class="text-muted">Menos de 15 minutos</td>
						</tr>
						<tr>
							<td></td>
							<td width="15" style="border:1px solid black"></td>
							<td class="text-muted">Entre 15 e 30 minutos</td>
						</tr>
						<tr>
							<td></td>
							<td width="15" style="border:1px solid black"></td>
							<td class="text-muted">Entre 30 minutos e 1 hora</td>
						</tr>
						<tr>
							<td></td>
							<td width="15" style="border:1px solid black"></td>
							<td class="text-muted">Entre 1 e 24 horas</td>
						</tr>
					</tbody>
				</table> -->

				<div class="panel panel-default">
					<div class="panel-heading text-center"><?php echo get_phrase('last_files');?></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;">#</th>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('radio');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('date');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('added');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$cc=0;
										foreach ($radiosinfo as $rinfo) {
											$cc++;
											$filesinfo =  $this->pages_model->report_byradio($rinfo['id_radio']);

											$filedate = date('Y-m-d H:i:s',$filesinfo[0]['timestamp']);
											$filedate15min = strtotime($filedate.' +15 minutes');
											$filedate30min = strtotime($filedate.' +30 minutes');
											$filedate1hour = strtotime($filedate.' +1 hour');
											$filedate1day = strtotime($filedate.' +1 day');
											$filedateadd = $filesinfo[0]['timestamp_add'];

											if ($filedateadd <= $filedate15min) { ?>
											<tr class="success">
											<?php }	else if ($filedateadd >= $filedate15min and $filedateadd <= $filedate30min) { ?>
											<tr class="info">
											<?php } else if ($filedateadd > $filedate30min and $filedateadd <= $filedate1hour) { ?>
											<tr class="warning">
											<?php } else if ($filedateadd > $filedate1hour and $filedateadd <= $filedate1day) { ?>
											<tr class="danger">
											<?php } ?>
												<td class="text-center"><?php echo $cc; ?></td>
												<td class="text-center"><?php echo $filesinfo[0]['radio']; ?></td>
												<td class="text-center"><?php echo date('d/m/Y H:i:s',$filesinfo[0]['timestamp']); ?></td>
												<td class="text-center"><?php echo date('d/m/Y H:i:s',$filesinfo[0]['timestamp_add']); ?></td>
											</tr>
										<?php
										} ?>
								</tbody>
							</table>
						</div><!-- /.table-responsive -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>
	</div>

	<script type="text/javascript">
		setTimeout(function(){
			window.location.reload(1);
		}, 60000);

		// function startRefresh() {
		// 	setTimeout(startRefresh,30000);
		// 	$.get('<?php echo base_url('pages/reports/radios')?>', function(data) {
		// 		$('#entirepage').html(data);
		// 	});
		// }

		// $(function() {
		// 	startRefresh();
		// });
	</script>