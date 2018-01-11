<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	$id_user = $this->session->userdata('id_user');
	$id_group = $this->db->get_where('user', array('id_user' => $id_user))->row()->id_group;
?>
<body>
	<?php if ($id_group == 1) { ?>
	<div id="wrapper">
	<?php } ?>
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo base_url();?>">
				Sistema Integrado de Monitoramento v1.8
			</a>
		</div>

		<ul class="nav navbar-top-links navbar-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<sup><span id="msgbnum" class="badge-notification" style="display: none"></span></sup> 
					<i class="fa fa-envelope fa-fw"></i>
					<!-- <i class="fa fa-caret-down"></i> -->
				</a>
				<ul id="msglist" class="dropdown-menu dropdown-messages" style="height: auto; max-height: 600px; overflow-x: hidden;">
					<li>
						<a class="text-center" href="#">
							<strong>Nenhuma mensagem!</strong>
						</a>
					</li>
				</ul>
			</li>

			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<sup><span id="taskbnum" class="badge-notification" style="display: none"></span></sup>
					<i class="fa fa-tasks fa-fw"></i>
					<!-- <i class="fa fa-caret-down"></i> -->
				</a>
				<ul id="tasklist" class="dropdown-menu dropdown-tasks">
					<li>
						<a class="text-center" href="#">
							<strong>Nenhuma tarefa!</strong>
						</a>
					</li>
				</ul>
			</li>
			
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<sup><span id="alertbnum" class="badge-notification" style="display: none"></span></sup> 
					<i class="fa fa-bell"></i>
					<!-- <i class="fa fa-caret-down"></i> -->
				</a>
				<ul id="alertlist" class="dropdown-menu dropdown-alerts">
					<li>
						<a class="text-center" href="#">
							<strong>Nenhum alerta!</strong>
						</a>
					</li>
				</ul>
			</li>

			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user fa-fw"></i><?php echo $this->session->userdata('username');?> <i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="#"><i class="fa fa-user fa-fw"></i> <?php echo get_phrase('profile');?></a></li>
					<li><a href="#"><i class="fa fa-gear fa-fw"></i> <?php echo get_phrase('settings');?></a></li>
					<li class="divider"></li>
					<li><a href="<?php echo base_url('login/signout')?>"><i class="fa fa-sign-out fa-fw"></i> <?php echo get_phrase('logout');?></a>
					</li>
				</ul> <!-- /.dropdown-user-->
			</li>
		</ul>

		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav in" id="side-menu">
					<li class="sidebar-search">
						<div class="input-group">
							<input id="searchclient" class="form-control" placeholder="<?php echo get_phrase('search')?>"/>
							<span class="input-group-btn">
								<button class="btn btn-default" disabled type="button">
									<i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</li>
					<!-- <li class="sidebar-search"> -->
						<!-- <img class="center-block" src="<?php //echo base_url('assets/imgs/dataclip_logo.png');?>" style="width: 60%" alt="Dataclip Logo"> -->
					<!-- </li> -->
					<?php
					if ($id_group == 1 or $id_group == 5) { ?>
						<li>
							<a href="<?php echo base_url('pages/index_radio'); ?>" <?php if ($selected_page == "home_radio") {echo 'class="active"';} ?>><i style="font-size: 15px" class="material-icons">radio</i> <?php echo get_phrase('radio');?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/index_radio_knewin'); ?>" <?php if ($selected_page == "home_radio_knewin") {echo 'class="active"';} ?>><i style="font-size: 15px" class="material-icons">radio</i> <?php echo get_phrase('radio')." (knewin)";?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/index_tv'); ?>" <?php if ($selected_page == "home_tv") {echo 'class="active"';} ?>><i class="fa fa-television fa-fw"></i> <?php echo get_phrase('television');?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/edit_audio'); ?>" <?php if ($selected_page == "edit_audio") {echo 'class="active"';} ?>><i class="fa fa-edit fa-fw"></i> Editar áudio</a>
						</li>
						<!-- <li> -->
							<!-- <a href="<?php echo base_url('pages/edit_video'); ?>" <?php if ($selected_page == "edit_video") {echo 'class="active"';} ?>><i class="fa fa-edit fa-fw"></i> Editar vídeo</a> -->
						<!-- </li> -->
					<?php }
					if ($id_group == 1 or $id_group == 4) { ?>
						<!-- <li> -->
							<!-- <a href="<?php echo base_url('pages/index_web'); ?>" <?php if ($selected_page == "home_web") {echo 'class="active"';} ?>><i class="fa fa-globe fa-fw"></i> <?php echo get_phrase('online');?></a> -->
						<!-- </li> -->
						<li>
							<a href="<?php echo base_url('pages/index_print'); ?>" <?php if ($selected_page == "home_print") {echo 'class="active"';} ?>><i class="fa fa-file-text-o fa-fw"></i> <?php echo get_phrase('print');?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/print_upload'); ?>" <?php if ($selected_page == "print_upload") {echo 'class="active"';} ?>><i class="fa fa-upload fa-fw"></i> OCR avulso</a>
						</li>
					<?php }
					if ($id_group == 1) { ?>
						<li>
							<a href="<?php echo base_url('pages/dashboard'); ?>" <?php if ($selected_page == "dashboard") {echo 'class="active"';} ?>><i class="fa fa-dashboard fa-fw"></i> <?php echo get_phrase('dashboard');?></a>
						</li>
					<?php } ?>
					<li>
						<a href="<?php echo base_url('pages/search'); ?>" <?php if ($selected_page == "search") {echo 'class="active"';} ?>><i class="fa fa-search fa-fw"></i> <?php echo get_phrase('search');?></a>
					</li>
					<?php
					if ($id_group == 1) { ?>
						<li>
							<a href="<?php echo base_url('pages/clients'); ?>" <?php if ($selected_page == "clients") {echo 'class="active"';} ?>><i class="fa fa-users fa-fw"></i> <?php echo get_phrase('clients');?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/keywords'); ?>" <?php if ($selected_page == "keywords") {echo 'class="active"';} ?>><i class="fa fa-key fa-fw"></i> <?php echo get_phrase('keywords');?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/terms'); ?>" <?php if ($selected_page == "terms") {echo 'class="active"';} ?>><i class="fa fa-commenting-o fa-fw"></i> <?php echo get_phrase('terms');?></a>
						</li>
						<li>
							<a href="<?php echo base_url('pages/radios'); ?>" <?php if ($selected_page == "radios") {echo 'class="active"';} ?>><i class="fa fa-bullhorn fa-fw"></i> <?php echo get_phrase('radios');?></a>
						</li>
						<li>
							<a href="#"><i class="fa fa-file-text fa-fw"></i> <?php echo get_phrase('reports');?><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="<?php echo base_url('pages/reports/radios'); ?>" <?php if ($selected_page == "reports_radios") {echo 'class="active"';} ?>><?php echo get_phrase('radios');?></a>
								</li>
								<li>
									<a href="<?php echo base_url('pages/reports/users'); ?>" <?php if ($selected_page == "reports_users") {echo 'class="active"';} ?>><?php echo get_phrase('users');?></a>
								</li>
							</ul>
						</li>
						<li>
							<a href="#"><i class="fa fa-fw  fa-gears"></i> <?php echo get_phrase('configurations');?><span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="#"></i> <?php echo get_phrase('recordings');?><span class="fa arrow"></span></a>
										<ul class="nav nav-third-level collapse">
											<li>
												<a href="<?php echo base_url('pages/rec_radio'); ?>" <?php if ($selected_page == "rec_radio") {echo 'class="active"';} ?>><?php echo get_phrase('radio');?></a>
											</li>
											<li>
												<a href="<?php echo base_url('pages/rec_tv'); ?>" <?php if ($selected_page == "rec_tv") {echo 'class="active"';} ?>><?php echo get_phrase('television');?></a>
											</li>
										</ul>
								</li>
								<li>
									<a href="<?php echo base_url('pages/system'); ?>" <?php if ($selected_page == "system") {echo 'class="active"';} ?>><?php echo get_phrase('system');?></a>
								</li>
								<li>
									<a href="<?php echo base_url('pages/users'); ?>" <?php if ($selected_page == "users") {echo 'class="active"';} ?>><?php echo get_phrase('users');?></a>
								</li>
								<li>
									<a href="<?php echo base_url('pages/groups'); ?>" <?php if ($selected_page == "groups") {echo 'class="active"';} ?>><?php echo get_phrase('groups');?></a>
								</li>
								<li>
									<a href="<?php echo base_url('pages/profile'); ?>" <?php if ($selected_page == "profile") {echo 'class="active"';} ?>><?php echo get_phrase('profile');?></a>
								</li>
							</ul>
						</li>
					<?php } ?>
					<li>
						<?php if (empty($selected_date)) {
							$seldate = date('d/m/Y');
						} else {
							$seldate = date('d/m/Y', strtotime($selected_date));
						} ?>
						<div id="datepicker_static" class="center-block datepicker-inline" data-date="<?php echo $seldate;?>"></div>
						<input  style="opacity: 0" type="text" id="selected_date" name="selected_date">
					</li>
				</ul>
			</div>
		</div>
		
		<script type="text/javascript">
			var idgroup = <?php echo $id_group; ?>;

			$('#datepicker_static').datepicker({
				format: "dd/mm/yyyy",
				language: 'pt-BR',
				todayBtn: true,
				todayHighlight: true
			});

			$('#datepicker_static').on("changeDate", function() {
				$('#selected_date').val($('#datepicker_static').datepicker('getFormattedDate'));
				var sdate = $('#selected_date').val();
				var selecteddate = sdate.replace(/\//g,'-');
				var vtype = '<?php 
							if (isset($vtype)) {
								echo $vtype;
							} else { 
								echo ($id_group == 1 ? "radio" : "print");
							} ?>';
				window.location ='<?php echo base_url("pages/calendar/"); ?>' + vtype + '/' + selecteddate;
			});

			if (idgroup == 1 || idgroup == 5 ) {
				$(function() { getradios(); });
			}

			function getradios() {
				setTimeout(getradios, 60000);
				$.post('/pages/proxy', {address: '<?php echo str_replace('sim.','radio.',base_url('index.php/radio/getstopradios'))?>'}, function(data, textStatus, xhr) {
					radiocount = 0;
					$('#msglist').html(null);
					datac = (data.length - 1);
					$.each(data, function(index, val) {
						valjson = JSON.parse(val);
						$.each(valjson, function(index, val) {
							radioname = index.replace("_", " - ");
							ffmpeglastmsg = val.ffmpeg_last_log;
							ffmpegpid = val.ffmpeg_PID;
							html = 	'<li>'+
										'<a href="#">'+
											'<div>'+
												'<strong>'+radioname+'</strong>'+
													'<span class="pull-right text-muted"><em>'+ffmpegpid+'</em></span>'+
												'</div>'+
											'<div class="rruname">'+ffmpeglastmsg+'</div>'+
										'</a>'+
									'</li>';
							$('#msglist').append(html);
							if (radiocount < datac) {
								$('#msglist').append('<li class="divider"></li>');
							}
							radiocount += 1;
						});
					});
					fhtml = '<li>'+
								'<a class="text-center" href="#">'+
									'<strong>Ver todas as mensagens </strong>'+
									'<i class="fa fa-angle-right"></i>'+
								'</a>'+
							'</li>';
					// $('#msglist').append(fhtml);
					if (radiocount > 0) {
						$('#msgbnum').text(radiocount);
						$('#msgbnum').fadeIn('fast');
					} else {
						$('#msgbnum').fadeOut('fast');
						$('#msgbnum').text(radiocount);
						fhtml = '<li>'+
									'<a class="text-center" href="#">'+
										'<strong>Nenhuma mensagem! </strong>'+
									'</a>'+
								'</li>';
						$('#msglist').append(fhtml);
					}
				});
			}
		</script>
	</nav>