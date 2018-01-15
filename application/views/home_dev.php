<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<div class="row">
		<div class="col-lg-12">
			<h1 id="headerhome" class="page-header"><?php echo get_phrase('radio'); ?></h1>
			<h1 id="headerkeyword" class="page-header" style="display: none;"><?php echo get_phrase('radio'); ?></h1>
			<small><span class="pull-right text-muted pageload"></span></small>
		</div>
	</div>

	<div id="mainrow" class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-key fa-fw"></i>
					<?php echo get_phrase('kewords_found').' '.get_phrase('since').' '.date('d/m/Y',$startdate).' - 00:00';?>
					<span class="pull-right" id="allkeywordsquant"></span>
					<span class="pull-right"><?php echo  get_phrase('all').':'?>&nbsp;</span>
					<span class="pull-right">&nbsp;&brvbar;&nbsp;</span>
					<span class="pull-right" id="keywordsquant"></span>
					<span class="pull-right"><?php echo  get_phrase('with_discard').':'?>&nbsp;</span>
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
								<div class="timeline-badge danger"><i class="fa fa-exclamation"></i></div>
							 	<div class="timeline-panel-high">
							<?php }
							else { ?>
								<div class="timeline-badge"><i class="fa fa-tag"></i></div>
								<div class="timeline-panel">	
							<?php } ?>
								<div class="timeline-heading">
									<h4 class="timeline-title"><?php echo $client['name'];?></h4>
								</div>
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
												$ids_text = $this->pages_model->discarded_texts($data_discard);
												$keyword_found = $this->pages_model->texts_keyword_byid_solr($ids_text, $keyword['keyword'], $startdate, $enddate);
												$allkeyword_found = $this->pages_model->text_keyword_solr($startdate, $enddate, $keyword['keyword']);
												if (isset($keyword_found->response->docs)) {
													$keyword_foundc = count($keyword_found->response->docs);
												} else {
													$keyword_foundc = 0;
												}
												if ($allkeyword_found->response->docs) {
													$allkeyword_foundc = count($allkeyword_found->response->docs);
												} else {
													$allkeyword_foundc = 0;
												}
												
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
													<form style="all: unset;" action="<?php echo base_url('pages/home_keyword');?>" method="post">
														<input type="hidden" name="ids_file_xml" value="<?php echo $ids_file_xml;?>">
														<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
														<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
														<?php if ($keyword['keyword_priority'] == 1) { ?>
															<button type="button" class="btn btn-danger btn-sm">
																<i class="fa fa-refresh fa-spin" style="display: none;"></i>
																<?php echo $keyword['keyword'];?>
																<span class="badge"><?php echo $keyword_foundc;?> </span>
															</button>
														<?php } else { ?>
															<button type="button" class="btn btn-info btn-sm">
																<i class="fa fa-refresh fa-spin" style="display: none;"></i>
																<?php echo $keyword['keyword'];?>
																<span class="badge"><?php echo $keyword_foundc;?> </span>
															</button>
														<?php } ?>
													</form>

													<?php
													array_push($keywordquant, $keyword_foundc);
													array_push($allkeywordquant, $allkeyword_foundc);
													$client_keywords++;
												}
											} ?>
											<input type="text" class="keyword_foundc" name="keyword_foundc" id="<?php echo $client['id_client'];?>-keyword_foundc" value="<?php echo array_sum($keywordquant);?>" style="display: none;">
											<input type="text" class="allkeyword_foundc" name="allkeyword_foundc" id="<?php echo $client['id_client'];?>-allkeyword_foundc" value="<?php echo array_sum($allkeywordquant);?>" style="display: none;">
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
					<span class="text-muted center-block text-center" id="loadmore" style="opacity: 0;">
						<i class="fa fa-refresh fa-spin"></i> Carregando...
					</span>
					<button id="btnloadmore" type="button" class="btn btn-primary btn-sm center-block" style="display: none;">
						<span id="btnloadmfi">Carregar mais</span>
						<span id="btnloadmse" style="display: none;"><i class="fa fa-refresh fa-spin"></i> Carregando...</span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div id="keywordrow" class="row" style="display: none">
		<div class="col-lg-12">
			<!-- <button id="backmainrow" type="button" class="btn btn-default">Voltar</button> -->
		</div>
	</div>

	<?php
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $start), 4);
		//echo 'Page generated in '.$total_time.' seconds.';
	?>

	<script type="text/javascript">
		var clientsmp = '<?php echo $clientsmp?>';
		var selected_date = '<?php echo $selected_date?>';
		var cbtnspin, clickedbtn, pageYpos, tempScrollTop, didclient, didkeyword, dclientselected;

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
					// $('#back-to-top').css('display', 'inline-block');
					$('#back-to-top').fadeIn('fast');
					// $('#divback-to').addClass('show');
				} else {
					// $('#divback-to').addClass('show');
					// $('#back-to-top').css('display', 'none');
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
				
				mrstyle = $('#mainrow').css('display');
				if (mrstyle == 'block') {
					if (winscrollToph == winheight) {
						//mrstyle = $('#mainrow').css('display');
						//if (mrstyle == 'block') {
							dataconfi = $('#loadcf').val();
							if (dataconfi != 89) {
								console.log('loading next page...');
								$('#btnloadmore').fadeOut('fast');
								$('#loadmore').fadeTo('fast', 100);
								plimit = (plimit + 5);
								$.ajax({
									url: page+'/'+selected_date+'/'+plimit+'/'+poffset,
									success: function(data) {
										dataq = data.length;
										$('#loadcf').val(dataq);
										$('#loadmore').fadeTo('fast', 0);
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
										swal("Atenção!", "Tente novamente atualizando a página!", "error");
									}
								});
							}
						//}
					}
				}
			});
		}

		$('#btnloadmore').click(function(event) {
			$(this).attr('disabled', true);
			$(this).addClass('disabled');
			$('#btnloadmfi').css('display', 'none');
			$('#btnloadmse').css('display', 'block');
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
					$('#btnloadmore').removeAttr('disabled');
					$('#btnloadmore').removeClass('disabled');
					$('#btnloadmfi').css('display', 'block');
					$('#btnloadmse').css('display', 'none');
					// $('#btnloadmore').fadeOut('fast');
				},
				error: function() {
					swal("Atenção!", "Tente novamente atualizando a página!", "error");
				}
			});
		});

		$(document).on('click', '.btn.btn-info.btn-sm, .btn.btn-danger.btn-sm', function(event) {
			// console.log(event);
			btnform = $(this).parent('form');
			cbtnspin = $(this).children('i');
			clickedbtn = $(this);

			tempScrollTop = $(window).scrollTop();

			cbtnspin.css('display', 'inline-block');
			$('#keywordrow > .col-lg-12 > .panel').detach();
			
			$.post('<?php echo base_url("pages/home_radio_keyword")?>',
				btnform.serialize(),
				function(data, textStatus, xhr) {
					console.log(data);
					cbtnspin.css('display', 'none');

					didclient = data.id_client;
					dclientselected = data.client_selected;
					didkeyword = data.id_keyword;
					dkeywordselected = data.keyword_selected;
					divcount = 1;
					dtexts = data.keyword_texts;
					$.each(dtexts, function(index, val) {
						audiofpath = '<?php echo base_url("assets/temp/")?>';
						audiofilename = 'teste.mp3';
						
						xmlfpath = val.path;
						xmlfilename = val.filename;
						xmlidfile = val.id_file;
						xmlstate = val.state;
						xmlradio = val.radio;
						xmlidradio = val.id_radio;
						xts = new Date(val.timestamp * 1000);
						xmltimestamp = ('0'+xts.getDay()).slice(-2) + '/' +('0'+(xts.getMonth() + 1)).slice(-2) + '/' + xts.getFullYear() + ' - ' + ('0'+xts.getHours()).slice(-2) + ':' + ('0'+xts.getMinutes()).slice(-2) + ':' + ('0'+xts.getSeconds()).slice(-2);
						xmlid = val.id_text;
						xmltext = val.text_content;
						
						divhtml = '<div id="div'+divcount+'" class="panel panel-default">'+
										'<div class="panel-heading text-center">'+
											'<label class="pull-left" style="font-weight: normal">'+
												'<input type="checkbox" class="cbjoinfiles" id="cb'+divcount+'" '+
												'data-iddiv="div'+divcount+'" data-idclient="'+didclient+'" data-idkeyword="'+didkeyword+'" '+
												'data-idradio="'+xmlidradio+'" data-timestamp="'+xmltimestamp+'"><?php echo get_phrase("join"); ?>'+
											'</label>'+
											
											'<label class="labeltitle">'+
												'<i class="fa fa-search fa-fw"></i>'+
												'<span class="sqtkwf" id="qtkwfid'+divcount+'"></span>'+
												'&nbsp;&nbsp;&nbsp;&nbsp;'+
												'<i class="fa fa-bullhorn fa-fw"></i> '+
												xmlradio+ ' - ' +xmlstate+ ' | ' +xmltimestamp+
											'</label>'+
											
											'<div class="btn-toolbar pull-right">'+
												'<button class="btn btn-warning btn-xs loadprevious" '+
												'data-iddiv="div'+divcount+'" data-idclient="'+didclient+'" data-idkeyword="'+didkeyword+'" '+
												'data-idradio="'+xmlidradio+'" data-timestamp="'+xmltimestamp+'" data-position="previous">'+
													'<i id="iload'+divcount+'" style="display: none" class="fa fa-refresh fa-spin"></i>'+
													'<?php echo get_phrase("previous"); ?>'+
												'</button>'+

												'<button class="btn btn-warning btn-xs loadprevious" '+
												'data-iddiv="div'+divcount+'" data-idclient="'+didclient+'" data-idkeyword="'+didkeyword+'" '+
												'data-idradio="'+xmlidradio+'" data-timestamp="'+xmltimestamp+'" data-position="next">'+
													'<i id="iload'+divcount+'" style="display: none" class="fa fa-refresh fa-spin"></i>'+
													'<?php echo get_phrase("next"); ?>'+
												'</button>'+

												'<button type="button" class="btn btn-danger btn-xs discarddoc" '+
												'data-iddiv="div'+divcount+'" data-idtext="'+xmlid+'" '+
												'data-idkeyword="'+didkeyword+'" data-idclient="'+didclient+'" '+
												'data-toggle="collapse" data-target="#div'+divcount+'">'+
													'<i style="display: none" class="fa fa-refresh fa-spin"></i>'+
													'<?php echo get_phrase('discard'); ?>'+
												'</button>'+

												'<form id="form_edit" style="all: unset;" action="<?php echo base_url("pages/edit_temp"); ?>" target="_blank" method="POST">'+
													'<input type="hidden" name="mp3pathfilename" value="<?php echo base_url("assets/caminhodoarquivomp3"); ?>">'+
													'<input type="hidden" name="xmlpathfilename" value="<?php echo base_url("assets/caminhodoarquivoxml"); ?>">'+
													'<input type="hidden" name="state" value="'+xmlstate+'">'+
													'<input type="hidden" name="radio" value="'+xmlradio+'">'+
													'<input type="hidden" name="timestamp" value="'+xmltimestamp+'">'+
													'<input type="hidden" name="id_keyword" value="'+didkeyword+'">'+
													'<input type="hidden" name="id_client" value="'+didclient+'">'+
													'<input type="hidden" name="id_file" value="'+xmlidfile+'">'+
													'<input type="hidden" name="id_text" value="'+xmlid+'">'+
													'<input type="hidden" name="client_selected" value="'+dclientselected+'">'+
													'<button type="submit" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase("edit"); ?></button>'+
												'</form>'+
											'</div>'+
										'</div>'+
										'<div class="panel-body">'+
											'<div class="row audioel">'+
												'<div class="col-lg-12">'+
													'<audio class="center-block" style="width: 100%" src="'+audiofpath+audiofilename+'" controls></audio>'+
												'</div>'+
											'</div>'+
											'<div class="row textel">'+
												'<div class="col-lg-12 pbody" id="pbody'+divcount+'">'+
													'<p id="ptext'+divcount+'" class="text-justify ptext" style="height: 300px; overflow-y: auto">'+
														xmltext+
													'</p>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>'
						$('#keywordrow > .col-lg-12').append(divhtml);
						
						divcount += 1;
					});

					// $('#mainrow').hide('fast');
					// $('#keywordrow').fadeIn(600, function(){
						// $(window).scrollTop(0)
					// });
					//$('#mainrow').slideUp(400, function() {
					//	$('#keywordrow').slideDown(600, function() {
					//		$(window).scrollTop(0)
					//	});
					//});
				

					$('#mainrow').fadeOut(200, function() {
						$('#keywordrow').fadeIn(300, function() {
							$(window).scrollTop(0)
						});
					});
	


	
					// $('#back-to-home').css('display', 'inline-block');
					$('#back-to-home').fadeIn('fast');
				}
			);
		});

		$('#back-to-home').click(function(event) {
			$(this).css('display', 'none');
			$(this).fadeOut('fast');

			// $('#keywordrow').hide('fast');
			// $('#mainrow').fadeIn(600, function(){
				// $(window).scrollTop(tempScrollTop)
			// });
			//$('#keywordrow').slideUp(400, function() {
			//	$('#mainrow').slideDown(600, function() {
			//		$(window).scrollTop(tempScrollTop)
			//	});
			//});


			$('#keywordrow').fadeOut(200, function() {
				$('#mainrow').fadeIn(300, function() {
					$(window).scrollTop(tempScrollTop)
				});
			});



		});

		$(document).ready(function() {
			$('#keywordsquant').text("<?php echo array_sum($keywordquant) ;?>");
			$('#allkeywordsquant').text("<?php echo array_sum($allkeywordquant) ;?>");
			// console.log(clientsmp);
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
		}

		function hidegentime() {
			itens = $('.loadmoret');
			if (itens.length > 1) {
				itens.first().remove();
			}
		}
	</script>
