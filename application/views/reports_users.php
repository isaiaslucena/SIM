<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<div class="row page-header">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<h1><?php echo get_phrase('reports');?></h1>
			</div>
			<div class="col-lg-3"></div>
			<div class="col-lg-5">
				<div class="form-group pull-right">
				<label><?php echo get_phrase('select_date');?></label>
					<div class="input-daterange input-group" id="datepicker">
						<input required type="text" class="input-sm form-control" id="startdate" name="startdate" placeholder="<?php echo get_phrase('start');?>" autocomplete="off"/>
						<span class="input-group-addon"><?php echo get_phrase('until');?></span>
						<input required type="text" class="input-sm form-control" id="enddate" name="enddate" placeholder="<?php echo get_phrase('end');?>" autocomplete="off"/>
						<span class="input-group-btn"><button class="btn btn-default btn-sm" id="btndate" type="button"><?php echo get_phrase('send')?></button></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<?php
	if (empty($pstartdate) || empty($penddate)) { 
		$startdate = strtotime('today 00:00:00');
		$enddate = strtotime('today 23:59:59'); ?>
		
		<!-- Today Discarded-->
		<div class="row">
			<!-- Audimus-->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading text-center">Audimus</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$todaydiscardinfo = $this->pages_model->report_users('discard', $startdate, $enddate);
										foreach ($todaydiscardinfo as $tdinfo) { ?>
											<tr>
												<td class="text-center"><a href="<?php echo base_url('pages/report_user/discard/'.$tdinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $tdinfo['username']; ?></a></td>
												<td class="text-center"><?php echo $tdinfo['discard_count']; ?></td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
						<div id="audimusdchart" class="donutchart" style="height: 250px;"></div>
					</div>
				</div>
			</div>
			<!-- Radio novo -->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading text-center">Radio novo</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$todaydiscardinfo = $this->pages_model->report_users('discard_radio_novo', $startdate, $enddate);
										foreach ($todaydiscardinfo as $tdinfo) { ?>
											<tr>
												<td class="text-center"><a href="<?php echo base_url('pages/report_user/discard/'.$tdinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $tdinfo['username']; ?></a></td>
												<td class="text-center"><?php echo $tdinfo['discard_count']; ?></td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
						<div id="rnovodchart" class="donutchart" style="height: 250px;"></div>
					</div>
				</div>
			</div>
			<!-- TV novo -->
			<div class="col-lg-4">
				<div class="panel panel-default">
					<div class="panel-heading text-center">TV novo</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$todaydiscardinfo = $this->pages_model->report_users('discard_tv_novo', $startdate, $enddate);
										foreach ($todaydiscardinfo as $tdinfo) { ?>
											<tr>
												<td class="text-center"><a href="<?php echo base_url('pages/report_user/discard/'.$tdinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $tdinfo['username']; ?></a></td>
												<td class="text-center"><?php echo $tdinfo['discard_count']; ?></td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</div>
						<div id="tnovodchart" class="donutchart" style="height: 250px;"></div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Cropped -->
		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading text-center">Textos Cortados</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$todaycropinfo = $this->pages_model->report_users('crop',$startdate,$enddate);
										foreach ($todaycropinfo as $tcinfo) { ?>
											<tr>
												<td class="text-center"><a href="<?php echo base_url('pages/report_user/crop/'.$tcinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $tcinfo['username']; ?></a></td>
												<td class="text-center"><?php echo $tcinfo['crop_count']; ?></td>
											</tr>
										<?php } ?>
								</tbody>
							</table>
						</div><!-- /.table-responsive -->
						<div id="tcropchart" style="height: 250px;"></div>
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>
		</div>

		<!-- This Week -->
		<!-- <div class="row">
			<?php
				$day = date('w');
				$weekstart = date('d-m-Y', strtotime('-'.$day.' days'));
				$weekend = date('d-m-Y', strtotime('+'.(6-$day).' days'));
				$startdate = strtotime($weekstart.' 00:00:00');
				$enddate = strtotime($weekend.' 23:59:59');
			?>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center"><?php echo get_phrase('this_week')?></div>
					<div class="panel-body">
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">Palavras-chave Descartadas
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all_discard/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$weekdiscardinfo = $this->pages_model->report_users('discard',$startdate,$enddate);
													foreach ($weekdiscardinfo as $wdinfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/discard/'.$wdinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $wdinfo['username']; ?></a></td>
															<td class="text-center"><?php echo $wdinfo['discard_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div>
									<div id="wdiscardchart" style="height: 250px;"></div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">Textos Cortados
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all_crop/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$weekcropinfo = $this->pages_model->report_users('crop',$startdate,$enddate);
													foreach ($weekcropinfo as $wcinfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/crop/'.$wcinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $wcinfo['username']; ?></a></td>
															<td class="text-center"><?php echo $wcinfo['crop_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div>
									<div id="wcropchart" style="height: 250px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> -->

		<!-- This Month -->
		<!-- <div class="row">
			<?php
				$fd = new DateTime('first day of this month');
				$monthstart = $fd->format('d-m-Y');
				$ld = new DateTime('last day of this month');
				$monthend = $ld->format('d-m-Y');
				$startdate = strtotime($monthstart.' 00:00:00');
				$enddate = strtotime($monthend.' 23:59:59');
			?>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center"><?php echo get_phrase('this_month')?></div>
					<div class="panel-body">
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">Palavras-chave Descartadas
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all_discard/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php

													$monthdiscardinfo = $this->pages_model->report_users('discard',$startdate,$enddate);
													foreach ($monthdiscardinfo as $mdinfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/discard/'.$mdinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $mdinfo['username']; ?></a></td>
															<td class="text-center"><?php echo $mdinfo['discard_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div>
									<div id="mdiscardchart" style="height: 250px;"></div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">Textos Cortados
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all_crop/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$monthcropinfo = $this->pages_model->report_users('crop',$startdate,$enddate);
													foreach ($monthcropinfo as $mcinfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/crop/'.$mcinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $mcinfo['username']; ?></a></td>
															<td class="text-center"><?php echo $mcinfo['crop_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div>
									<div id="mcropchart" style="height: 250px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> -->

	<!-- Selected Date -->
	<?php } else { ?>
		<div class="row">
			<?php
				$startdate = strtotime($pstartdate.' 00:00:00');
				$enddate = strtotime($penddate.' 23:59:59');
			?>
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<?php echo get_phrase('users')?>
					</div>
					<div class="panel-body">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading text-center">
									<?php echo get_phrase('total')?>
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$selectedallinfo = $this->pages_model->report_users('all',$startdate,$enddate);
													foreach ($selectedallinfo as $sainfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/all/'.$sainfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $sainfo['username']; ?></a></td>
															<td class="text-center"><?php echo $sainfo['total_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div><!-- /.table-responsive -->
									<div id="sallchart" style="height: 150px;width: 100%"></div>
								</div><!-- /.panel-body -->
							</div><!-- /.panel -->
						</div>

						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">Palavras-chave Descartadas
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all_discard/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$selecteddiscardinfo = $this->pages_model->report_users('discard',$startdate,$enddate);
													foreach ($selecteddiscardinfo as $sdinfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/discard/'.$sdinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $sdinfo['username']; ?></a></td>
															<td class="text-center"><?php echo $sdinfo['discard_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div><!-- /.table-responsive -->
									<div id="sdiscardchart" style="height: 250px;"></div>
								</div><!-- /.panel-body -->
							</div><!-- /.panel -->
						</div>

						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading text-center">Textos Cortados
									<div class="pull-right">
										<div class="btn-group">
											<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php echo get_phrase('options')?>
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<li><a href="<?php echo base_url('pages/reports/all_crop/'.$startdate.'/'.$enddate)?>">Detalhes</a></li>
												<li class="divider"></li>
												<li><a href="#">Exportar</a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-hover" id="<?php echo $datatablename;?>">
											<thead>
												<tr>
													<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('user');?></th>
													<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('quantity');?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$selectedcropinfo = $this->pages_model->report_users('crop',$startdate,$enddate);
													foreach ($selectedcropinfo as $scinfo) { ?>
														<tr>
															<td class="text-center"><a href="<?php echo base_url('pages/report_user/crop/'.$scinfo['id_user'].'/'.$startdate.'/'.$enddate)?>"><?php echo $scinfo['username']; ?></a></td>
															<td class="text-center"><?php echo $scinfo['crop_count']; ?></td>
														</tr>
													<?php } ?>
											</tbody>
										</table>
									</div><!-- /.table-responsive -->
									<div id="scropchart" style="height: 250px;"></div>
								</div><!-- /.panel-body -->
							</div><!-- /.panel -->
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<script src="<?php echo base_url('assets/sb-admin2/vendor/morrisjs/horizontal_bars.js');?>"></script>
	<script type="text/javascript">
		var nstartdate =  '<?php echo $pstartdate?>';
		var nenddate = '<?php echo $penddate?>';
		var npstartdate = nstartdate.replace(/-/g,'/');
		var npenddate = nenddate.replace(/-/g,'/');
		
		$('#startdate').val(npstartdate);
		$('#enddate').val(npenddate);

		$('#datepicker').datepicker({
			format: "dd/mm/yyyy",
			language: 'pt-BR',
			todayBtn: true,
			todayHighlight: true,
			autoclose: true,
			}).on('change', function(){
				$('#enddate').focus();
		});

		$('#btndate').click(function() {
			var startdate =  $('#startdate').val();
			var enddate = $('#enddate').val();
			var nstartdate = startdate.replace(/\//g,"-");
			var nenddate = enddate.replace(/\//g,"-");
			window.location = '<?php echo base_url("pages/reports/users/");?>' + nstartdate + '/' + nenddate;
		});

		<?php if (empty($pstartdate) || empty($penddate)) { ?>
			var donutscharts = $('.donutchart');
			$.each(donutscharts, function(index, val) {
				console.log(val);
				var elem = $(val).attr('id');
				
				Morris.Donut({
					element: elem,
					data: [
						<?php
						$startdate = strtotime('today 00:00:00');
						$enddate = strtotime('today 23:59:59');
						$todaydiscardinfo = $this->pages_model->report_users('discard', $startdate,$enddate);
						$dcount = 0;
						$darrcount = count($todaydiscardinfo);
						foreach ($todaydiscardinfo as $tdinfo) {
							$dcount++;
							if ($dcount == $darrcount) { ?>
								{label: "<?php echo $tdinfo['username']; ?>", value: <?php echo $tdinfo['discard_count']; ?>}
							<?php } else { ?>
								{label: "<?php echo $tdinfo['username']; ?>", value: <?php echo $tdinfo['discard_count']; ?>},
						<?php }
						} ?>
					]
				});	
			});
		<?php } else { ?>
			Morris.Bar({
				element: 'sallchart',
				data: [
					<?php
					$startdate = strtotime($pstartdate.' 00:00:00');
					$enddate = strtotime($penddate.' 23:59:59');
					$selectedallinfo = $this->pages_model->report_users('all',$startdate,$enddate);
					$dcount = 0;
					$darrcount = count($selectedallinfo);
					foreach ($selectedallinfo as $sainfo) {
						$dcount++;
						if ($dcount == $darrcount) { ?>
							{y: "<?php echo $sainfo['username']; ?>",a:<?php echo $sainfo['total_count']; ?>}
						<?php } else { ?>
							{y: "<?php echo $sainfo['username']; ?>",a:<?php echo $sainfo['total_count']; ?>},
					<?php }
					} ?>
				],
				xkey: 'y',
				ykeys: ['a'],
				labels: ['Total'],
				hideHover: 'auto',
				horizontal: true
			});

			Morris.Donut({
				element: 'sdiscardchart',
				data: [
					<?php
					$startdate = strtotime($pstartdate.' 00:00:00');
					$enddate = strtotime($penddate.' 23:59:59');
					$selecteddiscardinfo = $this->pages_model->report_users('discard',$startdate,$enddate);
					$dcount = 0;
					$darrcount = count($selecteddiscardinfo);
					foreach ($selecteddiscardinfo as $sdinfo) {
						$dcount++;
						if ($dcount == $darrcount) { ?>
							{label: "<?php echo $sdinfo['username']; ?>",value:<?php echo $sdinfo['discard_count']; ?>}
						<?php } else { ?>
							{label: "<?php echo $sdinfo['username']; ?>",value:<?php echo $sdinfo['discard_count']; ?>},
					<?php }
					} ?>
				]
			});

			Morris.Donut({
				element: 'scropchart',
				data: [
					<?php
					$selectedcropinfo = $this->pages_model->report_users('crop',$startdate,$enddate);
					$ccount = 0;
					$carrcount = count($selectedcropinfo);
					foreach ($selectedcropinfo as $scinfo) {
						$ccount++;
						if ($ccount == $carrcount) { ?>
							{label: "<?php echo $scinfo['username']; ?>",value:<?php echo $scinfo['crop_count']; ?>}
						<?php } else { ?>
							{label: "<?php echo $scinfo['username']; ?>",value:<?php echo $scinfo['crop_count']; ?>},
					<?php }
					} ?>
				]
			});
		<?php } ?>
	</script>