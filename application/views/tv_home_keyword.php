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
		#back-to-top {
			position: fixed;
			bottom: 20px;
			right: 20px;
			z-index: 9999;
			cursor: pointer;
			/* transition: opacity 0.2s ease-out; */
			/* opacity: 0; */
			display: none;
		}
		#back-to-top.show {
			/* opacity: 1; */
			display: block;
		}
		#content {
			height: 2000px;
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
				<h1 class="page-header"><?php echo get_phrase('keyword');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<ul class="nav nav-pills">
						<?php foreach ($clients_keyword as $client) { ?>
							<li><a data-toggle="tab"><i class="fa fa-users fa-fw"></i> <?php echo $client['name']?></a></li>
						<?php } ?>
					</ul>
					<div class="panel-body">
						<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-key fa-fw"></i><?php echo $keyword_selected;?></div>
								<div class="panel-body">
									<?php
										$divcount = 0;
										$icount=0;
										foreach ($keyword_texts->response->docs as $story) {
											$divcount++;
											$icount++;
											$shash = $story->hash_s;
											$staskidcreator = $story->taskidcreator_l;
											$sstartdate = $story->startdate_l;
											$senddate = $story->enddate_l;
											$stext = $story->text_t[0];
											$ssource = $story->source_s;
											$sname = $story->name_s;
											$sststartdate = date("d/m/Y H:i:s",($sstartdate/1000));
											$sstenddate = date("d/m/Y H:i:s",($senddate/1000)); ?>
											<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
												<div class="panel-heading text-center">
													<?php if (!empty($keyword_selected)) { ?>
													<label>
														<i class="fa fa-search fa-fw"></i>
														<span id="<?php echo 'qtkwfid'.$divcount;?>"></span>
														&nbsp;&nbsp;&nbsp;&nbsp;
														<i class="fa fa-television fa-fw"></i> 
														<?php echo $ssource.": ".$sname." | ".$sststartdate." - ".$sstenddate;?>
													</label>
													<?php } else {?>
													<i class="fa fa-television fw"></i> <?php echo $ssource.": ".$sname." | ".$sststartdate." - ".$sstenddate; ?>
													<?php } ?>
												</div>
												<p class="text-center"></p>
												<div class="panel-body" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
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
						</div>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
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