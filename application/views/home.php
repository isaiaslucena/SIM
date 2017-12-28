<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style type="text/css">
	#back-to-top {
		position: fixed;
		display: none;
		bottom: 20px;
		right: 20px;
		z-index: 9999;
		cursor: pointer;
		/* transition: opacity 0.5s ease-out; */
		/* opacity: 0; */
		display: none;
	}
	#back-to-top.show {
		display: block;
		/* opacity: 1; */
	}
	#content {
		height: 2000px;
	}
</style>
<button href="#" id="back-to-top" class="btn btn-danger btn-circle btn-lg" title="<?php echo get_phrase('back_to_top')?>"><i class="fa fa-arrow-up"></i></button>
<div id="page-wrapper" style="height: 100%; min-height: 400px;">
	<div class="row page-header">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<h1><?php echo get_phrase('radio');?></h1>
			</div>
			<div class="col-lg-5">
			</div>
			<div class="col-lg-3" >
				<small><span class="pull-right text-muted" id="pageload"></span></small>
				<h1>
				<div class="input-group">
					<input id="myInput" class="form-control pull-right" onkeyup="clientsearch()" placeholder="<?php echo get_phrase('search')?>"/>
					<span class="input-group-btn">
						<button class="btn btn-default" disabled type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
				</div>
				</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default ">
				<div class="panel-heading">
					<i class="fa fa-key fa-fw"></i>
					<?php echo get_phrase('kewords_found').' '.get_phrase('since').' '.date('d/m/Y',$startdate).' - 00:00';?>
					<span class="pull-right" id="allkeywordsquant"></span>
					<span class="pull-right">&nbsp;&brvbar;&nbsp;</span>
					<span class="pull-right" id="keywordsquant"></span>
				</div>
					<div class="panel-body">
						<ul class="timeline" id="client-ul">
							<?php
							$clientn = 0;
							$invert=0;
							$keywordquant = array();
							$allkeywordquant = array();
							$keyword_found_arr = array();
							foreach ($clients as $client) {
								$clientn++;
								if($invert % 2 == 0){ ?>
									<li id="<?php echo 'li-'.$clientn; ?>">
								<?php } else { ?>
									<li class="timeline-inverted" id="<?php echo 'li-'.$clientn; ?>">
								<?php }
								if ($client['priority'] == 1) { ?>
								 	<div class="timeline-panel-high">
								 		<div class="timeline-badge danger"><i class="fa fa-exclamation"></i></div>
								<?php }
								else { ?>
									<div class="timeline-panel">
										<div class="timeline-badge"><i class="fa fa-tag"></i></div>
								<?php } ?>
									<div class="timeline-heading"><h4 class="timeline-title"><?php echo $client['name'];?></h4></div>
									<div class="timeline-body">
										<p class="text-center">
											<?php
												$keywords = $this->pages_model->keywords_client($client['id_client']);
												$client_keywords = null;
												foreach ($keywords as $keyword) {
													$data_discard['startdate'] = $startdate;
													$data_discard['enddate'] = $enddate;
													$data_discard['id_client'] = $client['id_client'];
													$data_discard['id_keyword'] = $keyword['id_keyword'];
													$ids_text = $this->pages_model->discarded_texts($data_discard);
													$keyword_found = $this->pages_model->texts_keyword_byid_solr($ids_text,$keyword['keyword'],$startdate,$enddate);
													$allkeyword_found = $this->pages_model->text_keyword_solr($startdate,$enddate,$keyword['keyword']);
													$keyword_foundc = count($keyword_found->response->docs);
													$allkeyword_foundc = count($allkeyword_found->response->docs);
													$ids_file_xml = null;
													$ic = null;
													for ($i=0; $i < $keyword_foundc ; $i++) {
														$ic++;
														if ($ic == $keyword_foundc) {
															$ids_file_xml .= $keyword_found->response->docs[$i]->id_file_i;
														}
														else {
															$ids_file_xml .= $keyword_found->response->docs[$i]->id_file_i.",";
														}
													}
													if ($keyword_foundc != 0) { ?>
														<form style="all: unset;" action="<?php echo base_url('pages/home_keyword/');?>" method="post">
															<input type="hidden" name="ids_file_xml" value="<?php echo $ids_file_xml;?>">
															<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
															<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
															<input type="hidden" name="selecteddate" value="<?php echo date('d-m-Y',$startdate);?>">
															<?php if ($keyword['keyword_priority'] == 1) { ?>
																<button type="submit" class="btn btn-danger btn-sm"><?php echo $keyword['keyword'];?> <span class="badge"><?php echo $keyword_foundc;?></span></button>
															<?php } else { ?>
																<button type="submit" class="btn btn-lightblue btn-sm"><?php echo $keyword['keyword'];?> <span class="badge"><?php echo $keyword_foundc;?></span></button>
															<?php } ?>
														</form>
														<?php
														array_push($keywordquant, $keyword_foundc);
														array_push($allkeywordquant, $allkeyword_foundc);
														$client_keywords++;
													}
												}
												if ($client_keywords == 0) {
													echo '<script>';
														echo 'document.getElementById("li-'.$clientn.'").style.display = "none"';
													echo '</script>';
												}
											?>
										</p>
									</div>
								</div>
							</li>
							<?php $invert++;
							}
							?>
						</ul>
						<span class="pull-right text-muted" id="pageload"></span>
					</div>
			</div>
		</div>
	</div> <!-- div row clients timeline -->

	<?php
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4);
	?>

	<script type="text/javascript">
		function clientsearch() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("myInput");
			filter = input.value.toUpperCase();
			ul = document.getElementById("client-ul");
			li = ul.getElementsByTagName("li");
			for (i = 0; i < li.length; i++) {
				a = li[i].getElementsByTagName("h4")[0];
				if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
					li[i].style.display = "";
				} else {
					li[i].style.display = "none";
				}
			}
		};

		// $('#allkeywordsquant').text("<?php //echo array_sum($allkeywordquant) ;?>");
		// $('#keywordsquant').text("<?php //echo array_sum($keywordquant) ;?>");
		// $('#pageload').text("<?php //echo get_phrase('page_generated_in').' '.$total_time.'s';?>");

		if ($('#back-to-top').length) {
			var scrollTrigger = 1000, // px
			backToTop = function () {
				var scrollTop = $(window).scrollTop();
				if (scrollTop > scrollTrigger) {
					$('#back-to-top').addClass('show')
				} else {
					$('#back-to-top').removeClass('show')
				}
			}
			backToTop();
			$(window).on('scroll', function () {
				backToTop();
			})
			$('#back-to-top').on('click', function (e) {
				e.preventDefault();
				$('html,body').animate({scrollTop: 0}, 700)
			})
		};
	</script>