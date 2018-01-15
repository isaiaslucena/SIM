<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-lg-offset-4">
						<?php
							if (isset($message)) { ?>
							<div class="text-center alert alert-danger fade in" id="success-alert" style="display: none; position: absolute; width: 100%">
								<?php echo $message ?>!
							</div>
						<?php } ?>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-lg-offset-4">
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
									<input type="text" name="redirecturl" value="<?php echo $urlredirect?>" style="display: none;">
									<button class="btn btn-lg btn-success btn-block"><?php echo get_phrase('login');?></button>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			window.mobilecheck = function() {
				var check = false;
				(function(a){
					if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;
				})(navigator.userAgent||navigator.vendor||window.opera);
				return check;
			};

			if (mobilecheck()) {
				console.log('Mobile Agent!');
				url = "<?php echo base_url('login/mobile_index')?>";
				$(location).attr("href", url);
			} else {
				console.log('Desktop Agent!');
			}

			civersion="<?php echo CI_VERSION; ?>"
			console.debug("Code Igniter v"+civersion);

			$(document).ready(function() {
				//$(".alert-danger").alert();
				// window.setTimeout(function(){
					$(".alert-danger").fadeIn();
				// }, 200);
				window.setTimeout(function(){
					$(".alert-danger").alert('close');
				}, 2500);
			});
		</script>
	</body>
</html>