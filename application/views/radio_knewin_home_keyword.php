<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<style type="text/css">
		.slider {
			max-height: 400px
			-webkit-transition-property: all;
			-webkit-transition-duration: .5s;
			-webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
			-moz-transition-property: all;
			-moz-transition-duration: .5s;
			-moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
			-ms-transition-property: all;
			-ms-transition-duration: .5s;
			-ms-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
			transition-property: all;
			transition-duration: .5s;
			transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
			height: 400px;
		}
		.slider.closed {
			display: none;
		}
		.affix {
			top: 0;
			width: 100%;
		}
		.affix + .container-fluid {
			padding-top: 70px;
		}

		#joindiv {
			position: fixed;
			bottom: 0px;
			left: 260px;
			z-index: 9999;
			cursor: pointer;
			/* transition: opacity 0.2s ease-out; */
			/* opacity: 0; */
			display: none;
		}
		#joindiv.show {
			/* opacity: 1; */
			display: block;
		}
		#content {
			height: 2000px;
		}
	</style>
	<button href="#" id="back-to-top" class="btn btn-danger btn-circle btn-lg" title="<?php echo get_phrase('back_to_top')?>"><i class="fa fa-arrow-up"></i></button>

	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<?php echo $client_selected; ?>
					<small> - <?php echo $keyword_selected; ?></small>
				</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<?php
				$divcount = 0;
				$icount=0;
				foreach ($keyword_texts->response->docs as $found) {
					$divcount++;
					$icount++;
					$sid = $found->id_i;
					$sidsource = $found->id_source_i;
					$smediaurl = $found->mediaurl_s;
					
					// $sstartdate = $found->starttime_dt;
					// $senddate = $found->endtime_dt;
					
					// $startdateepoch = strtotime(str_replace("Z", "", str_replace("T", " ", $found->starttime_dt)));
					// $enddateepoch = strtotime(str_replace("Z", "", str_replace("T", " ", $found->endtime_dt)));
					// var_dump($startdateepoch);
					// var_dump($enddateepoch);
					
					$timezone = new DateTimeZone('UTC');
					// $sd = new Datetime('@'.$startdateepoch,  $timezone);
					// $ed = new Datetime('@'.$enddateepoch, $timezone);
					$sd = new Datetime($found->starttime_dt, $timezone);
					$ed = new Datetime($found->endtime_dt, $timezone);
					
					$newtimezone = new DateTimeZone('America/Sao_Paulo');
					$sd->setTimezone($newtimezone);
					$ed->setTimezone($newtimezone);
					$sstartdate = $sd->format('d/m/Y H:i:s');
					$senddate = $ed->format('d/m/Y H:i:s');
					
					// $sstartdate = date('d/m/Y H:i:s', $startdateepoch);
					// $senddate = date('d/m/Y H:i:s', $enddateepoch);
					
					$stext = $found->content_t[0];
					$ssource = $found->source_s; ?>
					<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
						<div class="panel-heading text-center">
							<?php if (!empty($keyword_selected)) { ?>
								<label>
									<i class="fa fa-search fa-fw"></i>
									<span id="<?php echo 'qtkwfid'.$divcount;?>"></span>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<i class="fa fa-television fa-fw"></i> 
									<?php echo $ssource." | ".$sstartdate." - ".$senddate;?>
								</label>
							<?php } else {?>
								<i class="fa fa-television fa-fw"></i> <?php echo $ssource." | ".$sstartdate." - ".$senddate; ?>
							<?php } ?>
							<form id="form_edit" style="all: unset;" action="<?php echo base_url('pages/edit_knewin');?>" target="_blank" method="POST">
								<input type="hidden" name="sid" value="<?php echo $sid;?>">
								<input type="hidden" name="mediaurl" value="<?php echo $smediaurl;?>">
								<input type="hidden" name="ssource" value="<?php echo $ssource;?>">
								<input type="hidden" name="sstartdate" value="<?php echo $sstartdate;?>">
								<input type="hidden" name="senddate" value="<?php echo $senddate;?>">
								<input type="hidden" name="id_keyword" value="<?php echo $id_keyword;?>">
								<input type="hidden" name="id_client" value="<?php echo $id_client;?>">
								<input type="hidden" name="client_selected" value="<?php echo $client_selected;?>">
								<button type="submit" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
							</form>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<audio class="center-block" style="width: 100%" src="<?php echo $smediaurl; ?>" controls></audio>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
									<?php
									if (!empty($keyword_selected)) {
										$fulltext = (string)$stext;
										$fulltext = preg_replace("/\w*?".preg_quote($keyword_selected)."\w*/i", " <strong class=\"str$divcount\" style=\"color: white; background-color: red; font-size: 110%;\">$keyword_selected</strong>", $fulltext); ?>
										<p class="text-justify"><?php echo $fulltext;?></p>
									<?php } else { ?>
										<p class="text-justify"><?php echo (string)$stext;?></p>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						jQuery.fn.scrollTo = function(elem) {
							$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
							return this;
						}

						if($('.<?php echo 'str'.$divcount;?>').length != 0) {
							$('#<?php echo 'pbody'.$divcount;?>').scrollTo('.<?php echo 'str'.$divcount;?>');
						}
						$('#<?php echo 'pbody'.$divcount;?>').css('overflowY', 'hidden');
						$('#<?php echo 'pbody'.$divcount;?>').click(function() {
							$(this).css('overflowY', 'auto');
						})
						$('#<?php echo 'pbody'.$divcount;?>').hover(function() {
							/*do nothing*/
						}, function() {
							$('#<?php echo 'pbody'.$divcount;?>').css('overflowY', 'hidden');
						});

						<?php if (!empty($keyword_selected)) { ?>
						var qtkwf = $('<?php echo '.str'.$divcount?>').length;
						$('<?php echo '#qtkwfid'.$divcount;?>').text(qtkwf);
						<?php } ?>
					</script>
				<?php } ?>
			</div>

			<script type="text/javascript">
				$('audio').bind('contextmenu', function() { return false; });
				
				if ($('#back-to-top').length) {
					var scrollTrigger = 1000, // px
					backToTop = function() {
						var scrollTop = $(window).scrollTop();
						if (scrollTop > scrollTrigger) {
							$('#back-to-top').addClass('show');
						} else {
							$('#back-to-top').removeClass('show');
						}
					}
					backToTop();
					$(window).on('scroll', function() {
						backToTop();
					})
					$('#back-to-top').on('click', function (e) {
						e.preventDefault();
						$('html,body').animate({scrollTop: 0}, 700);
					})
				}
			</script>
		</div>