<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
						<?php
							if (isset($message)) { ?>
							<div class="text-center alert alert-danger fade in" id="success-alert" style="display: none;">
								<?php echo $message ?>!
							</div>
						<?php } ?>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-panel panel panel-default">
						<div class="panel-heading"><h3 class="panel-title text-center">Login</h3></div>
						<div class="panel-body">
							<form action="<?php echo base_url('login/signin')?>" method="POST" role="form">
								<fieldset>
									<div class="form-group">
										<img class="img-responsive center-block" src="<?php echo base_url('assets/imgs/dataclip_logo.png');?>" alt="logo">
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input  required id="username"  name="username" type="text" style="text-transform: lowercase;" class="form-control" placeholder="<?php echo get_phrase('username');?>" autocomplete="off" autofocus="">
									</div>
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input required id="password"  name="password" type="password" class="form-control" placeholder="<?php echo get_phrase('password');?>">
									</div>
									<div class="checkbox">
										<label><input name="remember" type="checkbox" value="rememberme"><?php echo get_phrase('rememberme');?></label>
									</div>
									<button class="btn btn-lg btn-success btn-block"><?php echo get_phrase('login');?></button>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			// $(document).ready(function() {
				//$(".alert-danger").alert();
				window.setTimeout(function(){
					$(".alert-danger").fadeIn();
				}, 200);
				window.setTimeout(function(){
					$(".alert-danger").alert('close');
				}, 2500);
			// });
		</script>
	</body>
</html>