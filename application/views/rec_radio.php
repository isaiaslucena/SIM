<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row page-header">
			<div class="col-lg-12">
				<h1><?php echo get_phrase('radios');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1">Rádio</th>
										<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1">URL stream</th>
										<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1">Opções</th>
									</tr>
								</thead>
								<tbody id="tablebody">
									<?php 
									// var_dump($rec_radios);
									foreach ($rec_radios->ESTADO as $estado) {
										// var_dump($estado);
										// var_dump(key($estado));
										$estadoname = key($estado);
										foreach ($estado as $radios) { 
											foreach ($radios as $radio) {
											// var_dump($radio->radio);
											// echo "<br>";
											// echo "<br>";
											$radioname = $radio->radio;
											$urlstream = $radio->stream; ?>
											<tr>
												<td class="text-center"><?php echo $radioname;?></td>
												<td><?php echo $urlstream;?></td>
												<td class="text-center"><?php echo "cont coluna3";?></td>
											</tr>	
											<?php }
										}
									 } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			// $(document).ready(function() {
				// recdtable = $('#'+'<?php e//cho $datatablename;?>').DataTable();
				// $.post('/pages/proxy',
				// 	{address: '<?php //echo str_replace('sim.','radio.',base_url('index.php/radios/getradios'))?>'},
				// 	function(data, textStatus, xhr) {
				// 		$.each(data.ESTADO, function(index, estados) {
				// 			$.each(estados, function(index, radios) {
				// 				$.each(radios, function(index, radio) {
				// 					// $('#tablebody').append(
				// 					// 	'<tr>'+
				// 					// 	'<td class="text-left">'+radio.radio+'</td>'+
				// 					// 	'<td class="text-left">'+radio.stream+'</td>'+
				// 					// 	'</tr>'
				// 					// );
				// 					recdtable.row.add([
				// 						radio.radio,
				// 						radio.stream
				// 					]).draw();
				// 				});
				// 			});
				// 		});
				// 	}
				// );
			// });
		</script>