<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

$startdate = strtotime('today 00:00:00');
$enddate = strtotime('today 23:59:59');
?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo get_phrase('dashboard');?></h1>
			</div>
		</div>

		<!-- Other Infos -->
		<div class="row">
			<!-- Connected users -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-users fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php echo count($connected_users); ?>
								</div>
								<div><?php echo get_phrase('users');?></div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<!-- Radios and TV Channels -->
		<div class="row">
			<!-- Audimus Radios -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<!-- <i class="fa fa-tasks fa-5x"></i> -->
								<i style="font-size: 72px" class="material-icons">radio</i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
										echo count($total_radios);
									?>
								</div>
								<div>Rádios Audimus</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/radios')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- novo Radios -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<!-- <i class="fa fa-tasks fa-5x"></i> -->
								<i style="font-size: 72px" class="material-icons">radio</i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
										echo count($novo_radios);
									?>
								</div>
								<div>Rádios novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/radios')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- novo TV Channels -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-television fa-5x"></i>
								<!-- <i style="font-size: 72px" class="material-icons">tv</i> -->
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
										echo count($novo_tvcns);
									?>
								</div>
								<div>TV novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/radios')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<!-- Today discarded -->
		<div class="row">
			<!-- Audimus -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-trash-o fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$discardsum = array();
									$todaydiscardinfo = $this->pages_model->report_users('discard',$startdate,$enddate);
									foreach ($todaydiscardinfo as $dinfo) {
										array_push($discardsum, (int)$dinfo['discard_count']);
									}
									echo array_sum($discardsum);
									?>
								</div>
								<div>Audimus</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- Radio novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-trash-o fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$discardsum = array();
									$todaydiscardinfo = $this->pages_model->report_users('discard_radio_knewin',$startdate,$enddate);
									foreach ($todaydiscardinfo as $dinfo) {
										array_push($discardsum, (int)$dinfo['discard_count']);
									}
									echo array_sum($discardsum);
									?>
								</div>
								<div>Radio novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- Radio novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-trash-o fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$discardsum = array();
									$todaydiscardinfo = $this->pages_model->report_users('discard_tv_knewin',$startdate,$enddate);
									foreach ($todaydiscardinfo as $dinfo) {
										array_push($discardsum, (int)$dinfo['discard_count']);
									}
									echo array_sum($discardsum);
									?>
								</div>
								<div>TV novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>	
		</div>

		<!-- Today Joinned -->
		<div class="row">	
			<!-- Audimus -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-plus-circle fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('join',$startdate,$enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['join_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>Audimus</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- Radio novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-plus-circle fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('join_radio_knewin', $startdate, $enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['join_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>Rádio novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- TV novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-plus-circle fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('join_tv_knewin', $startdate, $enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['join_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>TV novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<!-- Today cropped -->
		<div class="row">	
			<!-- Audimus -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-cut fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('crop',$startdate,$enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['crop_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>Audimus</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- Radio novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-cut fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('crop_radio_knewin', $startdate, $enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['crop_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>Rádio novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- TV novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-cut fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('crop_tv_knewin', $startdate, $enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['crop_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>TV novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<!-- Today download cropped -->
		<div class="row">
			<!-- Audimus -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-download fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('crop_down', $startdate,$enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['crop_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>Audimus</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- Radio novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-download fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('crop_down_radio_knewin', $startdate, $enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['crop_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>Rádio novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<!-- TV novo -->
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-download fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
									$cropsum = array();
									$todaycropinfo = $this->pages_model->report_users('crop_down_tv_knewin', $startdate, $enddate);
									foreach ($todaycropinfo as $cinfo) {
										array_push($cropsum, (int)$cinfo['crop_count']);
									}
									echo array_sum($cropsum);
									?>
								</div>
								<div>TV novo</div>
							</div>
						</div>
					</div>
					<a href="<?php echo base_url('pages/reports/users')?>">
						<div class="panel-footer">
							<span class="pull-left"><?php echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<div class="row">
			<!-- <div class="col-lg-3 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-warning fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">04</div>
								<div>Teste04</div>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer">
							<span class="pull-left"><?php //echo get_phrase('details')?></span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div> -->
		</div>