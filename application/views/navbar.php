<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body>

<div id="entirepage">
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo base_url();?>">
			Sistema Integrado de Monitoramento v1.5
		</a>
	</div><!-- /.navbar-header -->

	<ul class="nav navbar-top-links navbar-right">
		 <li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<sup><span id="msgbnum" class="badge-notification" style="display: none"></span></sup> 
				<i class="fa fa-envelope fa-fw"></i>
				<!-- <i class="fa fa-caret-down"></i> -->
			</a>
			<ul id="msglist" class="dropdown-menu dropdown-messages"></ul>
		</li>
		
		<!-- <li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-tasks">
			</ul> --><!-- /.dropdown-tasks -->
		<!-- </li> --><!-- /.dropdown -->
		
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<sup><span id="alertbnum" class="badge-notification" style="display: none"></span></sup> 
				<i class="fa fa-bell"></i>
				<!-- <i class="fa fa-caret-down"></i> -->
			</a>
			<ul id="alertlist" class="dropdown-menu dropdown-alerts">
				<li>
					<a class="text-center" href="#">
						<strong>Nenhum alerta! </strong>
					</a>
				</li>
			</ul> <!--/.dropdown-alerts-->
		</li> <!-- /.dropdown-->

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
		</li> <!-- /.dropdown -->
	</ul> <!-- /.navbar-top-links -->

	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav in" id="side-menu">
				<!--<li class="sidebar-search">
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" placeholder="Pesquisar...">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button">
								<i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</li>-->
				<li>
					<center><img src="<?php echo base_url('assets/imgs/dataclip_logo.png');?>" style="width: 80%" alt="Logo"></center>
				</li>
				<?php
				$id_user = $this->session->userdata('id_user');
				$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
				if ($id_group == 1) { ?>
				<li>
					<a href="<?php echo base_url('pages/index_admin'); ?>" <?php if ($selected_page == "home") {echo 'class="active"';} ?>><i style="font-size: 15px" class="material-icons">radio</i> <?php echo get_phrase('radio');?></a>
				</li>
				<?php } else { ?>
				<li>
					<a href="<?php echo base_url(''); ?>" <?php if ($selected_page == "home") {echo 'class="active"';} ?>><i style="font-size: 15px" class="material-icons">radio</i> <?php echo get_phrase('radio');?></a>
				</li>
				<?php } ?>
				<li>
					<a href="<?php echo base_url('pages/index_tv'); ?>" <?php if ($selected_page == "home_tv") {echo 'class="active"';} ?>><i class="fa fa-television fa-fw"></i> <?php echo get_phrase('television');?></a>
				</li>
				<?php 
				$id_user = $this->session->userdata('id_user');
				$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
				if ($id_group == 1) { ?>
				<li>
					<a href="<?php echo base_url('pages/dashboard'); ?>" <?php if ($selected_page == "dashboard") {echo 'class="active"';} ?>><i class="fa fa-dashboard fa-fw"></i> <?php echo get_phrase('dashboard');?></a>
				</li>
				<?php } ?>
				<li>
					<a href="<?php echo base_url('pages/search'); ?>" <?php if ($selected_page == "search") {echo 'class="active"';} ?>><i class="fa fa-search fa-fw"></i> <?php echo get_phrase('search');?></a>
				</li>
				<?php
				$id_user = $this->session->userdata('id_user');
				$id_group = $this->db->get_where('user',array('id_user' => $id_user))->row()->id_group;
				if ($id_group == 1) { ?>
				<li>
					<a href="<?php echo base_url('pages/clients'); ?>" <?php if ($selected_page == "clients") {echo 'class="active"';} ?>><i class="fa fa-users fa-fw"></i> <?php echo get_phrase('clients');?></a>
				</li>
				<li>
					<a href="<?php echo base_url('pages/keywords'); ?>" <?php if ($selected_page == "keywords") {echo 'class="active"';} ?>><i class="fa fa-key fa-fw"></i> <?php echo get_phrase('keywords');?></a>
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
					}
					else {
						$seldate = $selected_date;
						} ?>
					<div id="datepicker_static" data-date="<?php echo $seldate;?>"></div>
					<input  style="opacity: 0" type="text" id="selected_date" name="selected_date">
				</li>
			</ul>
		</div> <!-- /.sidebar-collapse -->
	</div> <!-- /.navbar-static-side -->
</nav>

<script>
	$('#datepicker_static').datepicker({
		format: "dd/mm/yyyy",
		language: 'pt-BR',
		todayBtn: true,
		todayHighlight: true
	});

	$('#datepicker_static').on("changeDate", function() {
		$('#selected_date').val($('#datepicker_static').datepicker('getFormattedDate'))
		var sdate = $('#selected_date').val()
		var selecteddate = sdate.replace(/\//g,"-")
		window.location="<?php echo base_url('pages/calendar/');?>" + selecteddate
	});

	$(function() { getradios(); });

	function getradios() {
		setTimeout(getradios, 60000);
		$.post('/pages/proxy', {address: '<?php echo str_replace('sim.','radio.',base_url('index.php/radios/getstopradios'))?>'}, function(data, textStatus, xhr) {
			radiocount = 0;
			$('#msglist').html(null);
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
									'<div>'+ffmpeglastmsg+'</div>'+
								'</a>'+
							'</li>'+
							'<li class="divider"></li>';
					$('#msglist').append(html);
				});
				radiocount += 1;
			});
			fhtml = '<li>'+
						'<a class="text-center" href="#">'+
							'<strong>Ver todas as mensagens </strong>'+
							'<i class="fa fa-angle-right"></i>'+
						'</a>'+
					'</li>';
			$('#msglist').append(fhtml);
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




