<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="<?php echo base_url('assets/imgs/favicon.ico');?>" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url('assets/imgs/favicon.ico');?>" type="image/x-icon">
		<title>Sistema Integrado de Monitoramento</title>

		<script src="<?php echo base_url('assets/jquery/jquery-3.1.1.min.js');?>"></script>
		<style type="text/css">
			audio::-internal-media-controls-download-button {
				display:none;
			}

			audio::-webkit-media-controls-enclosure {
				overflow:hidden;
			}

			audio::-webkit-media-controls-panel {
				width: calc(100% + 30px); /* Adjust as needed */
			}
		</style>
		<link rel="stylesheet" href="<?php echo base_url('assets/datatable/dataTables.bootstrap.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/datatable/buttons.bootstrap.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap-theme.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/clockpicker-gh-pages/dist/jquery-clockpicker.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/metisMenu/metisMenu.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/dist/css/sb-admin-2.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/material-design/material-icons.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-tagsinput/dist/bootstrap-tagsinput.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/typeahead-0111/typeahead-tags.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/morrisjs/morris.css');?>">

		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/metisMenu/metisMenu.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/raphael/raphael.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/dist/js/sb-admin-2.min.js');?>"></script>
		<script src="<?php echo base_url('assets/datatable/jquery.dataTables.min.js');?>"></script>
		<script src="<?php echo base_url('assets/datatable/dataTables.bootstrap.min.js');?>"></script>
		<script src="<?php echo base_url('assets/datatable/date-euro.js');?>"></script>
		<script src="<?php echo base_url('assets/datatable/dataTables.buttons.min.js');?>"></script>
		<script src="<?php echo base_url('assets/datatable/buttons.bootstrap.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/clockpicker-gh-pages/dist/jquery-clockpicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-tagsinput/dist/bootstrap-tagsinput.js');?>"></script>
		<script src="<?php echo base_url('assets/typeahead-0111/typeahead.bundle.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/morrisjs/morris.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/raphael/raphael.min.js');?>"></script>
	</head>