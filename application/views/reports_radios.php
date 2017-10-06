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
						<div class="table-responsive" id=tablecontent>
						</div><!-- /.table-responsive -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>
	</div>

	<script type="text/javascript">
		function load_table() {
			// $('#' + iloadid).css('display', 'inline-block');
			$.ajax({
				url: '<?php echo base_url('pages/reports_radios_table/table_report_radios')?>',
				type: 'GET',
				complete: function (response) {
					// console.log("loading content...");
					$('#tablecontent').html(response.responseText);
					// $('#' + iloadid).css('display', 'none');
					// console.log('loaded!');
				},
				error: function (response) {
					alert(response.responseText)
				},
			})
			return false;
		}

		function startRefresh() {
			setTimeout(startRefresh,10000);
			$.get('<?php echo base_url('pages/reports_radios_table/table_report_radios')?>', function(data) {
				$('#tablecontent').html(data);
			});
		}

		load_table();
		$(function() {
			startRefresh();
		});
	</script>