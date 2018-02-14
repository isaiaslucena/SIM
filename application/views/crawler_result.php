<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>Crawler Result</title>
		<script src="<?php echo base_url('assets/jquery/jquery-3.2.1.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/js/bootstrap.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-select/js/bootstrap-select.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.js');?>"></script>
		<script src="<?php echo base_url('assets/sweetalert/dist/sweetalert.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
		<script src="<?php echo base_url('assets/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js');?>"></script>
		<script src="<?php echo base_url('assets/progressbarjs/dist/progressbar.min.js');?>"></script>

		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/bootstrap/css/bootstrap.css');?>"/>
		<link rel="stylesheet" href="<?php echo base_url('assets/sb-admin2/vendor/font-awesome/css/font-awesome.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-select/css/bootstrap-select.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-toggle/bootstrap-toggle.min.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/sweetalert/dist/sweetalert.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bscheckbox/bscheckbox.css');?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');?>"/>
	</head>
	<body>
		<div class="container-fluid">
			<div class="row panel-heading" style="background-color: #f4f4f4">
				<div class="col-lg-1">
					<img class="img-responsive" alt="Logo" src="<?php echo base_url('assets/imgs/dataclip_logo_only.png')?>">
				</div>
				<div class="col-lg-7">
					<div class="input-group input-group-lg" style="vertical-align: middle;">
						<input type="text" class="form-control" value="<?php echo $search_text;?>">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">
								<i class="fa fa-search fa-fw"></i>
							</button>
						</span>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="btn-toolbar pull-right">
						<a href="<?php echo base_url('login/signout')?>" id="btnlogout" type="button" class="btn btn-danger pull-right" title="Sair"><i class="fa fa-sign-out"></i></a>
						<a href="<?php echo base_url('pages/index_print')?>" id="btnback" type="button" class="btn btn-default pull-right" title="Voltar"><i class="fa fa-arrow-left"></i></a>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					Encontrado <?php echo $search_result->response->numFound;?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<?php
						// var_dump($search_result);
						foreach ($search_result->response->docs as $sresult) { ?>
								<div class="well" style="width: 50%">
									<span class="pull-right" style="cursor: pointer;">
										<?php echo $sresult->published_dt;?>
									</span>
									<a class="ntitle" id="<?php echo 'a_'.$sresult->id;?>" role="button" data-toggle="collapse" href="<?php echo '#r_'.$sresult->id;?>" aria-expanded="false" aria-controls="collapseExample">
										<h4 class="text-primary">
											<?php echo $sresult->title_t[0];?>
										</h4>
									</a>
									<a class="text-info" href="<?php echo $sresult->url_s;?>" target="_blank">
										<?php echo $sresult->url_s;?>
									</a>
									<div id="<?php echo 'r_'.$sresult->id;?>" class="collapse in rcontent" data-docid="<?php echo $sresult->id;?>">
										<?php echo substr(strip_tags($sresult->content_t[0]), 0, 200).'...';?>
									</div>
									<div id="<?php echo 'c_'.$sresult->id;?>" class="collapse content text-justify hdin" data-docid="<?php echo $sresult->id;?>">
										<?php echo strip_tags($sresult->content_t[0], '<br><p>');?>
									</div>
								</div>
						<?php }
					?>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function() {
				// $(document).on('click', 'a.ntitle', function(event) {
				// 	//console.log(event);

				// 	var curid = $(this).attr('data-docid');
				// 	//$('#r_'+curid).toggleClass('in');
				// 	//$('#c_'+curid).toggleClass('in');

				// 	if($('#r_'+curid).hasClass('hdin')) {
				// 		// $('#r_'+curid).toggleClass('in', function() {
				// 		// 	$('#c_'+curid).toggleClass('in');
				// 		// });

				// 		$('#r_'+curid).hide('fast', function() {
				// 			$('#c_'+curid).show('fast', function() {
				// 				console.log('showed');
				// 			});
				// 		});
				// 	} else {
				// 		// $('#c_'+curid).toggleClass('in', function() {
				// 		// 	$('#r_'+curid).toggleClass('in');
				// 		// });

				// 		$('#c_'+curid).hide('fast', function() {
				// 			$('#r_'+curid).show('fast', function() {
				// 				console.log('hided');
				// 			});
				// 		});
				// 	}
				// });

				$('.rcontent').on('hidden.bs.collapse', function () {
					var curid = $(this).attr('data-docid');

					$('#a_'+curid).attr('href', '#r_'+curid);
					$('#c_'+curid).collapse('show');
				})

				$('.content').on('hidden.bs.collapse', function () {
					var curid = $(this).attr('data-docid');

					$('#a_'+curid).attr('href', '#c_'+curid);
					$('#r_'+curid).collapse('show');
				})
			});
		</script>
	</body>
</html>
