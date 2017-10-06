<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row page-header">
			<div class="col-lg-12">
				<h1><?php echo get_phrase('reports');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<?php
							if ($page == 'all_discard') {
								echo "Descartados no período: ";
							} else if ($page == 'all_crop') {
								echo "Cortados no período: ";
							}
							echo date('d/m/Y',$startdate).' - '.date('d/m/Y',$enddate);
						?>
					</div>
					<div class="panel-body">
						<div id="mchart"></div>
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>
	</div>

	<script type="text/javascript">
		<?php if ($page == 'all_discard') { ?>
		Morris.Line({
			element: 'mchart',
			data: [
				<?php
					$ykeysarr = array();
					$labelsarr = array();
					$ccount = 0;
					$alldays = $this->pages_model->reports_queries($page,'day','',$startdate,$enddate);
					$carrcount = count($alldays);
					foreach ($alldays as $day) {
						$usersline = null;
						$ccount++;
						$allusers = $this->pages_model->reports_queries($page,'users','',$day['date'],$day['date']);
						$uarrcount = count($allusers);
						$ucount = 0;
						foreach ($allusers as $user) {
							$ucount++;
							$userinfo = $this->pages_model->reports_queries($page,'user',$user['id_user'],$day['date'],$day['date']);
							if ($ucount == $uarrcount) {
								$usersline .= $userinfo[0]['username'].': '.$userinfo[0]['discard_count'];
								if (in_array($userinfo[0]['username'], $ykeysarr) == FALSE) {
									array_push($ykeysarr, $userinfo[0]['username']);
								}
								if (in_array($userinfo[0]['username'], $labelsarr) == FALSE) {
									array_push($labelsarr, $userinfo[0]['username']);
								}
							} else {
								$usersline .= $userinfo[0]['username'].': '.$userinfo[0]['discard_count'].', ';
								if (in_array($userinfo[0]['username'], $ykeysarr) == FALSE) {
									array_push($ykeysarr, $userinfo[0]['username']);
								}
								if (in_array($userinfo[0]['username'], $labelsarr) == FALSE) {
									array_push($labelsarr, $userinfo[0]['username']);
								}
							}
						}
						if ($ccount == $carrcount) {
							echo '{ day: \''.$day['date'].'\', '.$usersline.' }'."\r\n";
						} else {
							echo '{ day: \''.$day['date'].'\', '.$usersline.' },'."\r\n";
						}
					}
					$ykeysline = implode('\' , \'', $ykeysarr);
					$labelsline = implode('\' , \'', $labelsarr);
					// echo 'ykeys: [ \''.$ykeysline.'\' ],'."<br>";
					// echo 'labels: [ \''.$labelsline.'\' ]'."<br>";
				?>
			],
			xkey: 'day',
			ykeys: [<?php echo '\''.$ykeysline.'\'' ?>],
			labels: [<?php echo '\''.$labelsline.'\'' ?>]
		});
		<?php } else if ($page == 'all_crop') { ?>
		Morris.Line({
			element: 'mchart',
			data: [
				<?php
					$ykeysarr = array();
					$labelsarr = array();
					$ccount = 0;
					$alldays = $this->pages_model->reports_queries($page,'day','',$startdate,$enddate);
					$carrcount = count($alldays);
					foreach ($alldays as $day) {
						$usersline = null;
						$ccount++;
						$allusers = $this->pages_model->reports_queries($page,'users','',$day['date'],$day['date']);
						$uarrcount = count($allusers);
						$ucount = 0;
						foreach ($allusers as $user) {
							$ucount++;
							$userinfo = $this->pages_model->reports_queries($page,'user',$user['id_user'],$day['date'],$day['date']);
							if ($ucount == $uarrcount) {
								$usersline .= $userinfo[0]['username'].': '.$userinfo[0]['crop_count'];
								if (in_array($userinfo[0]['username'], $ykeysarr) == FALSE) {
									array_push($ykeysarr, $userinfo[0]['username']);
								}
								if (in_array($userinfo[0]['username'], $labelsarr) == FALSE) {
									array_push($labelsarr, $userinfo[0]['username']);
								}
							} else {
								$usersline .= $userinfo[0]['username'].': '.$userinfo[0]['crop_count'].', ';
								if (in_array($userinfo[0]['username'], $ykeysarr) == FALSE) {
									array_push($ykeysarr, $userinfo[0]['username']);
								}
								if (in_array($userinfo[0]['username'], $labelsarr) == FALSE) {
									array_push($labelsarr, $userinfo[0]['username']);
								}
							}
						}
						if ($ccount == $carrcount) {
							echo '{ day: \''.$day['date'].'\', '.$usersline.' }'."\r\n";
						} else {
							echo '{ day: \''.$day['date'].'\', '.$usersline.' },'."\r\n";
						}
					}
					$ykeysline = implode('\' , \'', $ykeysarr);
					$labelsline = implode('\' , \'', $labelsarr);
					// echo 'ykeys: [ \''.$ykeysline.'\' ],'."<br>";
					// echo 'labels: [ \''.$labelsline.'\' ]'."<br>";
				?>
			],
			xkey: 'day',
			ykeys: [<?php echo '\''.$ykeysline.'\'' ?>],
			labels: [<?php echo '\''.$labelsline.'\'' ?>]
		});
		<?php }?>
	</script>