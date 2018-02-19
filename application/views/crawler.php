<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>Pesquisar</title>

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/clockpicker-gh-pages/dist/jquery-clockpicker.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/dataclip/crawler.css');?>"/>

		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-select/js/bootstrap-select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/progressbarjs/dist/progressbar.min.js');?>"></script>
		<script src="<?php echo base_url('assets/clockpicker-gh-pages/dist/jquery-clockpicker.min.js');?>"></script>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 panel-heading">
					<div class="btn-toolbar">
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger pull-right" title="Sair"><i class="fa fa-sign-out"></i></a>
						<a href="<?php echo base_url('pages/index_print')?>" id="btnback" type="button" class="btn btn-default pull-right" title="Voltar"><i class="fa fa-arrow-left"></i></a>
					</div>
				</div>
			</div>

			<div class="row center-block text-center" style="margin-top: 8%">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<img class="img-responsive center-block text-center panel-heading" alt="Logo" src="<?php echo base_url('assets/imgs/dataclip_logo.png')?>">
				</div>
			</div>

			<div class="row center-block text-center">
				<form id="formsearch" action="<?php echo base_url('pages/crawler_result');?>" method="post" accept-charset="utf-8">
					<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
							<div class="form-group">
								<div class="input-group input-group-lg">
									<!-- <input id="search_text" name="search_text" type="text" class="form-control"> -->
									<input id="search_text" name="search_text" type="text" form="formsearch" class="form-control" autocomplete="off">
									<span class="input-group-btn">
										<button id="btnsubmit" class="btn btn-default" type="submit">
											<i class="fa fa-search fa-fw"></i>
										</button>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label><?php echo get_phrase('day');?></label>
								<div class="input-daterange input-group" id="datepicker">
									<input type="text" class="input form-control" id="startday" name="startday" placeholder="<?php echo get_phrase('start');?>" <?php if (isset($startdate)) { echo 'value="'.$startdate.'"'; } ?> autocomplete="off"/>
									<span class="input-group-addon"><?php echo get_phrase('until');?></span>
									<input type="text" class="input form-control" id="endday" name="endday" placeholder="<?php echo get_phrase('end');?>" <?php if (isset($enddate)) { echo 'value="'.$enddate.'"'; } ?> autocomplete="off"/>
								</div>
							</div>

							<div class="form-group">
								<label><?php echo get_phrase('time');?></label>
								<div class="input-daterange input-group">
									<input type="text" class="input form-control clockpicker" id="starttime" name="starttime" placeholder="<?php echo get_phrase('start');?>" value="00:00" autocomplete="off"/>
									<span class="input-group-addon"><?php echo get_phrase('until');?></span>
									<input type="text" class="input form-control clockpicker" id="endtime" name="endtime" placeholder="<?php echo get_phrase('end');?>" value="23:59" autocomplete="off"/>
								</div>
							</div>
					</div>
				</form>
			</div>

		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				// $('#searchtext').keyup(function(event) {
				// 	var stext = $(this).val();
				// 	$('#search_text').val(stext);
				// });

				$('#datepicker').datepicker({
					format: "dd/mm/yyyy",
					language: 'pt-BR',
					todayBtn: true,
					todayHighlight: true,
					autoclose: true
				}).on('change', function(){
						$('#endday').focus();
				});

				$('#starttime').clockpicker({
						autoclose: true,
						placement: 'top'
				}).on('change', function(){
					// $('#endtime').clockpicker('show');
					$('#endtime').focus();
				});

				$('#endtime').clockpicker({
					autoclose: true,
					placement: 'top'
				});
			});
		</script>
	</body>
</html>
