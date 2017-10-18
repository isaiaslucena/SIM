<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style type="text/css">
	#back-to-top {
		position: fixed;
		bottom: 20px;
		right: 20px;
		z-index: 9999;
		cursor: pointer;
		transition: opacity 0.2s ease-out;
		opacity: 0;
	}
	#back-to-top.show {
		opacity: 1;
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
				<h1><?php echo get_phrase('television');?></h1>
			</div>
			<div class="col-lg-5">
			</div>
			<div class="col-lg-3" >
				<small><span class="pull-right text-muted pageload"></span></small>
				<h1>
				<div class="input-group">
					<input id="searchclient" class="form-control pull-right" placeholder="<?php echo get_phrase('search')?>"/>
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
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-key fa-fw"></i>
					<?php echo get_phrase('kewords_found').' '.get_phrase('since').' '.date('d/m/Y',$startdate).' - 00:00';?>
					<span class="pull-right" id="allkeywordsquant"></span>
					<span class="pull-right"><?php echo  get_phrase('all').':'?>&nbsp;</span>
					<!-- <span class="pull-right">&nbsp;&brvbar;&nbsp;</span> -->
					<!-- <span class="pull-right" id="keywordsquant"></span> -->
					<!-- <span class="pull-right"><?php //echo  get_phrase('with_discard').':'?>&nbsp;</span> -->
				</div>
					<div id="timelinebody" class="panel-body">
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
									<li id="<?php echo 'li-'.$client['id_client'];?>">
								<?php } else { ?>
									<li class="timeline-inverted" id="<?php echo 'li-'.$client['id_client'];?>">
								<?php }
								if ($client['priority'] == 1) { ?>
								 	<div class="timeline-panel-high">
								<?php }
								else { ?>
									<div class="timeline-panel">
								<?php } ?>
									<div class="timeline-heading"><h4 class="timeline-title"><?php echo $client['name'];?></h4></div>
									<div class="timeline-body">
										<p class="text-center">
											<?php
												$keywords = $this->pages_model->keywords_client($client['id_client']);
												$client_keywords = 0;
												foreach ($keywords as $keyword) {
													$data_discard['startdate'] = $startdate;
													$data_discard['enddate'] = $enddate;
													$data_discard['id_client'] = $client['id_client'];
													$data_discard['id_keyword'] = $keyword['id_keyword'];
													// $ids_text = $this->pages_model->discarded_texts($data_discard);
													// $keyword_found = $this->pages_model->tv_texts_keyword_byid_solr(null,$keyword['keyword'],$startdate,$enddate);
													$allkeyword_found = $this->pages_model->tv_text_keyword_solr($startdate,$enddate,$keyword['keyword']);
													// var_dump($allkeyword_found);
													// $keyword_foundc = count($keyword_found->response->docs);
													$allkeyword_foundc = count($allkeyword_found->response->docs);
													$ids_file_xml = null;
													$ic = null;
													// for ($i=0; $i < $allkeyword_foundc ; $i++) {
													// 	$ic++;
													// 	if ($ic == $allkeyword_foundc) {
													// 		$ids_file_xml .= $allkeyword_found->response->docs[$i]->id_file_i;
													// 	}
													// 	else {
													// 		$ids_file_xml .= $allkeyword_found->response->docs[$i]->id_file_i.",";
													// 	}
													// }
													if ($allkeyword_foundc != 0) { ?>
														<form style="all: unset;" action="<?php echo base_url('pages/tv_home_keyword');?>" method="post">
															<input type="hidden" name="ids_file_xml" value="<?php echo $ids_file_xml;?>">
															<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
															<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
															<input type="hidden" name="startdate" value="<?php echo $startdate;?>">
															<input type="hidden" name="enddate" value="<?php echo $enddate;?>">
															<?php if ($keyword['keyword_priority'] == 1) { ?>
																<button type="submit" class="btn btn-danger btn-sm"><?php echo $keyword['keyword'];?>
																	<span class="badge"><?php echo $allkeyword_foundc;?> </span>
																</button>
															<?php } else { ?>
																<button type="submit" class="btn btn-lightblue btn-sm"><?php echo $keyword['keyword'];?>
																	<span class="badge"><?php echo $allkeyword_foundc;?> </span>
																</button>
															<?php } ?>
														</form>

														<?php
														array_push($allkeywordquant, $allkeyword_foundc);
														$client_keywords++;
													}
												} ?>
												<input type="text" class="allkeyword_foundc" name="allkeyword_foundc" id="<?php echo $client['id_client'];?>-allkeyword_foundc" value="<?php echo array_sum($keywordquant);?>" style="display: none;">
												<input type="text" class="client_keywords" name="client_keywords" id="<?php echo $client['id_client'];?>-client_keywords" value="<?php echo $client_keywords;?>" style="display: none;">
										</p>
									</div>
								</div>
							</li>
							<?php $invert++;
							}
							?>
						</ul>
						<input type="text" id="iallkeywordquant" style="display: none;">
						<input type="text" name="loadcf" id="loadcf" value="2" style="display: none;">
						<span class="text-muted center-block text-center" id="loadmore" style="display: none;">
							<i class="fa fa-refresh fa-spin"></i>
							 Carregando...
						</span>
						<button id="btnloadmore" type="button" class="btn btn-primary btn-sm center-block" style="display: none;">Carregar mais</button>
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
		//echo 'Page generated in '.$total_time.' seconds.';
	?>

	<script type="text/javascript">
		var clientspm = '<?php echo $clientsmp?>';
		var selected_date = '<?php echo $selected_date?>';
		var clientsmp = '<?php echo $clientsmp?>';

		var d = new Date();
		var day = d.getDate();
		var month = (d.getMonth() + 1);
		var month = ('0' + month).slice(-2);
		var year = d.getFullYear();
		var todaydate = day+'-'+month+'-'+year;

		function hidenokeyword() {
			clientkeywords = $('.client_keywords');
			clientkeywords.each( function(element, index) {
				keywordsq = index.value;
				id = index.id;
				idr = id.replace("-client_keywords","");
				if (keywordsq == 0) {
					$('#li-'+idr).remove();
				}
			});
		};

		function hidegentime() {
			itens = $('.loadmoret');
			if (itens.length > 1) {
				itens.first().remove();
			}
		}

		$('#searchclient').on('keyup click input', function () {
			if (this.value.length > 0) {
				$('#client-ul > li').hide().filter(function () {
					return $(this).text().toLowerCase().indexOf($('#searchclient').val().toLowerCase()) != -1;
				}).show();
			} else {
				$('#client-ul > li').show();
			}
		});

		$('.pageload').text("<?php echo get_phrase('page_generated_in').' '.$total_time.'s';?>");

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

		dataconfi = $('#loadcf').val();
		if (dataconfi > 1) {
			$(window).scroll(function() {
				dataconfi = $('#loadcf').val();
				winscrollToph = ($(window).scrollTop() + $(window).height());
				winheight = $(document).height();
				winheightm = (winheight - 70);
				winheightn = (winheight - 65);
				if (winscrollToph == winheight) {
					dataconfi = $('#loadcf').val();
					if (dataconfi != 89) {
						console.log('loading next page...');
						$('#btnloadmore').fadeOut('fast');
						$('#loadmore').fadeTo('fast',100);
						plimit = (plimit + 5);
						$.ajax({
							url: page+'/'+selected_date+'/'+plimit+'/'+poffset,
							success: function(data) {
								dataq = data.length;
								$('#loadcf').val(dataq);
								$('#loadmore').fadeTo('fast',0);
								$('#client-ul').append(data);
								hidenokeyword();
								hidegentime();
								// upkeyw = parseInt($('#keywordsquant').text());
								upallkeyw = parseInt($('#allkeywordsquant').text());
								// dowkeyw = parseInt($('#ikeywordquant').val());
								downallkeyw = parseInt($('#iallkeywordquant').val());
								// upnkeyw = (upkeyw + dowkeyw);
								upnallkeyw = (upallkeyw + downallkeyw);
								// $('#keywordsquant').text(upnkeyw);
								$('#allkeywordsquant').text(upnallkeyw);
							},
							error: function() {
								swal("Atenção!", "Tente novamente atualizando a página! (F5)", "error");
							}
						});
					}
				}
			});
		}

		$('#btnloadmore').click(function(event) {
			$('#loadmore').fadeTo('fast',100);
			plimit = (plimit + 5);
			$.ajax({
				url: page+'/'+selected_date+'/'+plimit+'/'+poffset,
				success: function(data) {
					dataq = data.length;
					$('#loadcf').val(dataq);
					$('#loadmore').fadeTo('fast',0);
					$('#client-ul').append(data);
					hidenokeyword();
					hidegentime();
					// upkeyw = parseInt($('#keywordsquant').text());
					upallkeyw = parseInt($('#allkeywordsquant').text());
					// dowkeyw = parseInt($('#ikeywordquant').val());
					downallkeyw = parseInt($('#iallkeywordquant').val());
					// upnkeyw = (upkeyw + dowkeyw);
					upnallkeyw = (upallkeyw + downallkeyw);
					// $('#keywordsquant').text(upnkeyw);
					$('#allkeywordsquant').text(upnallkeyw);
					$('#btnloadmore').fadeOut('fast');
				},
				error: function() {
					swal("Atenção!", "Tente novamente atualizando a página! (F5)", "error");
				}
			});
		});

		$(document).ready(function() {
			$('#allkeywordsquant').text("<?php echo array_sum($allkeywordquant) ;?>");
			$('#keywordsquant').text("<?php echo array_sum($keywordquant) ;?>");
			hidenokeyword();

			timelinebody = $('#timelinebody');
			// console.log(timelinebody.height());
			if (timelinebody.height() < 420) {
				$('#btnloadmore').fadeIn('fast');
			} else {
				$('#btnloadmore').fadeOut('fast');
			}
		});
	</script>