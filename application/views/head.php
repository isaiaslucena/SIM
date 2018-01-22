<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" href="<?php echo base_url('assets/imgs/favicon.ico');?>" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url('assets/imgs/favicon.ico');?>" type="image/x-icon">
		<title>Sistema Integrado de Monitoramento</title>

		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-tagsinput/dist/bootstrap-tagsinput.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/clockpicker-gh-pages/dist/jquery-clockpicker.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/datatable/dataTables.bootstrap.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/datatable/buttons.bootstrap.min.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<!-- <link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap-theme.css');?>"/> -->
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/metisMenu/metisMenu.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/dist/css/sb-admin-2.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/material-design/material-icons.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/typeahead-0111/typeahead-tags.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">

		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/metisMenu/metisMenu.min.js');?>"></script>
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
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>

		<style type="text/css">
			#divback-to {
				position: fixed;
				bottom: 20px;
				right: 20px;
				z-index: 500;
				cursor: pointer;
			}

			#joindiv {
				position: fixed;
				bottom: 0px;
				left: 260px;
				z-index: 9999;
				display: none;
			}

			#content {
				height: 2000px;
			}
			
			.kwfound{
				color: white;
				background-color: red;
				font-size: 110%;
			}

			audio::-webkit-media-controls-enclosure {overflow:hidden;}
			audio::-webkit-media-controls-panel {width: calc(100% + 30px);}
			
			video::-webkit-media-controls-enclosure {overflow:hidden;}
			video::-webkit-media-controls-panel {width: calc(100% + 30px);}

			#scrollable-dropdown-menu .tt-menu {
				max-height: 200px;
				overflow-y: auto;
			}

			.scrollable-menu {
				height: auto;
				max-height: 200px;
				overflow-y: auto;
				overflow-x: hidden;
			}

			tr.group,
			tr.group:hover {
				background-color: #ddd !important;
			}

			.rrntable {
				font-size: 12px
			}
			.rrutable {
				font-size: 10px
			}
			
			.spinner {
				margin: 100px auto 0;
				width: 70px;
				text-align: center;
				transition: 1s;
			}

			.spinner > div {
				width: 18px;
				height: 18px;
				background-color: #333;

				border-radius: 100%;
				display: inline-block;
				-webkit-animation: sk-bouncedelay 1.2s infinite ease-in-out both;
				animation: sk-bouncedelay 1.2s infinite ease-in-out both;
			}

			.spinner .bounce1 {
				-webkit-animation-delay: -0.25s;
				animation-delay: -0.25s;
			}

			.spinner .bounce2 {
				-webkit-animation-delay: -0.10s;
				animation-delay: -0.10s;
			}

			@-webkit-keyframes sk-bouncedelay {
				0%, 80%, 100% { -webkit-transform: scale(0) }
				40% { -webkit-transform: scale(1.0) }
			}

			@keyframes sk-bouncedelay {
				0%, 80%, 100% {
					-webkit-transform: scale(0);
					transform: scale(0);
				} 40% {
					-webkit-transform: scale(1.0);
					transform: scale(1.0);
				}
			}
			.progress {
				display: block;
				text-align: center;
				width: 0;
				height: 5px;
				background: black;
				transition: width .3s;
			}
			.progress.hide {
				opacity: 0;
				transition: opacity 1.3s;
			}
		</style>
	</head>