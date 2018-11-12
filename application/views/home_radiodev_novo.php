<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo get_phrase('radio');?></h1>
				<small><span class="pull-right text-muted pageload"></span></small>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-key fa-fw"></i>
						<?php
						$timezone = new DateTimeZone('America/Sao_Paulo');
						$sd = new Datetime($startdate, $timezone);
						$ed = new Datetime($enddate, $timezone);
						$newtimezone = new DateTimeZone('UTC');
						$sd->setTimezone($newtimezone);
						$ed->setTimezone($newtimezone);
						$sstartdate = $sd->format('Y-m-d\TH:i:s');
						$senddate = $ed->format('Y-m-d\TH:i:s');
						$epochstartdate = $sd->format('U');
						$epochenddate = $ed->format('U');

						$epochstartdate1 = strtotime($startdate);
						$sstartdate1 = date('Y-m-d\TH:i:s', $epochstartdate1);
						$epochenddate1 = strtotime($enddate);
						$senddate1 = date('Y-m-d\TH:i:s', $epochenddate1);

						echo get_phrase('kewords_found').' '.get_phrase('since').' '.str_replace('T', ' ', $startdate); ?>

						<span class="pull-right" id="allkeywordsquant"></span>
						<span class="pull-right"><?php echo  get_phrase('all').':'?>&nbsp;</span>
						<span class="pull-right">&nbsp;&brvbar;&nbsp;</span>
						<span class="pull-right" id="keywordsquant"></span>
						<span class="pull-right"><?php echo  get_phrase('with_discard').':'?>&nbsp;</span>
					</div>
					<div id="timelinebody" class="panel-body">
						<ul class="timeline" id="client-ul">
							<?php

							// var_dump($epochstartdate1);
							// echo "<br>";
							// var_dump($sstartdate1);
							// echo "<br><br>";
							// var_dump($epochenddate1);
							// echo "<br>";
							// var_dump($senddate1);
							// echo "<br><br>";
							// echo "<br><br>";
							// var_dump($epochstartdate);
							// echo "<br>";
							// var_dump($sstartdate);
							// echo "<br><br>";
							// var_dump($epochenddate);
							// echo "<br>";
							// var_dump($senddate);

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
									<div class="timeline-badge danger"><i class="fa fa-exclamation"></i></div>
								 	<div class="timeline-panel-high">
								<?php }
								else { ?>
									<div class="timeline-badge"><i class="fa fa-tag"></i></div>
									<div class="timeline-panel">
								<?php } ?>
									<div class="timeline-heading"><h4 class="timeline-title"><?php echo $client['name'];?></h4></div>
									<div class="timeline-body">
										<p class="text-center">
											<?php
											$keywords = $this->pages_model->keywords_client($client['id_client']);
											$client_keywords = 0;
											foreach ($keywords as $keyword) {
												$data_discard['startdate'] = $epochstartdate1;
												$data_discard['enddate'] = $epochenddate1;
												$data_discard['id_client'] = $client['id_client'];
												$data_discard['id_keyword'] = $keyword['id_keyword'];

												$discardeddocs = $this->pages_model->discarded_docs_novo_radio($data_discard);
												$croppeddocs = $this->pages_model->cropped_docs_novo_radio($data_discard);
												$keyword_found = $this->pages_model->docs_byid_radio_novo($discardeddocs, $croppeddocs, $keyword['keyword'], $sstartdate, $senddate);
												$keyword_foundc = $keyword_found->response->numFound;
												$allkeyword_found = $this->pages_model->radio_knewin_text_keyword_solr($sstartdate, $senddate, $keyword['keyword']);
												$allkeyword_foundc = $allkeyword_found->response->numFound;

												$ic = null;
												if ($keyword_foundc != 0) { ?>
													<form style="all: unset;" action="<?php echo base_url('pages/radio_novo_home_keyword');?>" method="post">
														<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
														<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
														<input type="hidden" name="startdate" value="<?php echo $startdate;?>">
														<input type="hidden" name="enddate" value="<?php echo $enddate;?>">
														<button type="submit" class="btn <?php echo $keyword['keyword_priority'] == 1 ? 'btn-danger' : 'btn-info' ?> btn-sm">
															<?php echo $keyword['keyword'];?>
															<span class="badge"><?php echo $keyword_foundc;?> </span>
														</button>
													</form>

													<?php
													array_push($keywordquant, $keyword_foundc);
													array_push($allkeywordquant, $allkeyword_foundc);
													$client_keywords++;
												}
											} ?>
											<input type="text" class="allkeyword_foundc" name="allkeyword_foundc" id="<?php echo $client['id_client'];?>-allkeyword_foundc" value="<?php echo array_sum($allkeywordquant);?>" style="display: none;">
											<input type="text" class="keyword_foundc" name="keyword_foundc" id="<?php echo $client['id_client'];?>-allkeyword_foundc" value="<?php echo array_sum($keywordquant);?>" style="display: none;">
											<input type="text" class="client_keywords" name="client_keywords" id="<?php echo $client['id_client'];?>-client_keywords" value="<?php echo $client_keywords;?>" style="display: none;">
										</p>
									</div>
									</div>
								</li>
								<?php $invert++;
							}
							?>
						</ul>
						<input type="text" id="ikeywordquant" style="display: none;">
						<input type="text" id="iallkeywordquant" style="display: none;">
						<input type="text" name="loadcf" id="loadcf" value="2" style="display: none;">
						<span class="text-muted center-block text-center" id="loadmore" style="display: none;">
							<i class="fa fa-refresh fa-spin"></i> Carregando...
						</span>
						<button id="btnloadmore" type="button" class="btn btn-primary btn-sm center-block" style="display: none;">Carregar mais</button>
					</div>
				</div>
			</div>
		</div>

		<?php $time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4); ?>

		<script type="text/javascript">
			var clientspm = '<?php echo $clientsmp?>';
			var selected_date = '<?php echo $selected_date?>';

			var d = new Date();
			var day = d.getDate();
			var month = (d.getMonth() + 1);
			var month = ('0' + month).slice(-2);
			var year = d.getFullYear();
			var todaydate = day+'-'+month+'-'+year;

			$('#searchclient').on('keyup click input', function () {
				if (this.value.length > 0) {
					$('#client-ul > li').hide().filter(function () {
						return $(this).text().toLowerCase().indexOf($('#searchclient').val().toLowerCase()) != -1;
					}).show();
				} else {
					$('#client-ul > li').show();
				}
			});

			if ($('#back-to-top').length) {
				var scrollTrigger = 1000,
				backToTop = function () {
					var scrollTop = $(window).scrollTop();
					if (scrollTop > scrollTrigger) {
						// $('#back-to-top').addClass('show')
						$('#back-to-top').fadeIn('fast');
					} else {
						// $('#back-to-top').removeClass('show')
						$('#back-to-top').fadeOut('fast');
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
									upkeyw = parseInt($('#keywordsquant').text());
									upallkeyw = parseInt($('#allkeywordsquant').text());
									dowkeyw = parseInt($('#ikeywordquant').val());
									downallkeyw = parseInt($('#iallkeywordquant').val());
									upnkeyw = (upkeyw + dowkeyw);
									upnallkeyw = (upallkeyw + downallkeyw);
									$('#keywordsquant').text(upnkeyw);
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
						upkeyw = parseInt($('#keywordsquant').text());
						upallkeyw = parseInt($('#allkeywordsquant').text());
						dowkeyw = parseInt($('#ikeywordquant').val());
						downallkeyw = parseInt($('#iallkeywordquant').val());
						upnkeyw = (upkeyw + dowkeyw);
						upnallkeyw = (upallkeyw + downallkeyw);
						$('#keywordsquant').text(upnkeyw);
						$('#allkeywordsquant').text(upnallkeyw);
						if (upnallkeyw > 0) {
							$('#btnloadmore').fadeOut('fast');
						}
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
				if (timelinebody.height() < 420) {
					$('#btnloadmore').fadeIn('fast');
				} else {
					$('#btnloadmore').fadeOut('fast');
				}
			});

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
		</script>