<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo get_phrase('dashboard');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<!-- <div class="panel panel-default"> -->
					<!-- <div class="panel-body"> -->

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
												<?php
													echo count($connected_users);
												?>
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

						<!-- Registered radios -->
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
											<div><?php echo get_phrase('radios')?></div>
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

						<?php
							$startdate = strtotime('today 00:00:00');
							$enddate = strtotime('today 23:59:59');
						?>
						<!-- Today discarded -->
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
											<div><?php echo get_phrase('discarded')?></div>
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

						<!-- Today cropped -->
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
											<div><?php echo get_phrase('cropped')?></div>
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
		</div>