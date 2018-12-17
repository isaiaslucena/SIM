<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<div class="row">
		<div class="col-lg-12">
			<h1 id="headerhome" class="page-header"><?php echo get_phrase('radio'); ?></h1>
			<h1 id="headerclient" class="page-header" style="display: none;"></h1>
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
		</div>

		<div class="well well-sm" id="joindiv">
			<div class="list-group" style="max-height:  150px ; overflow: auto;">
				<small id="fileslist"></small>
			</div>
			<form id="filesform" style="all: unset;" action="<?php echo base_url('pages/join');?>" target="_blank" method="POST">
				<input type="hidden" name="id_radio" id="ff_id_radio">
				<input type="hidden" name="id_client" id="ff_id_client">
				<input type="hidden" name="id_keyword" id="ff_id_keyword">
				<input type="hidden" name="ff_ids_files_xml" id="ff_ids_files_xml">
				<input type="hidden" name="ff_ids_files_mp3" id="ff_ids_files_mp3">
				<input type="hidden" name="ff_ids_texts" id="ff_ids_texts">
				<input type="hidden" name="timestamp" id="ff_timestamp">
			</form>
			<button id="joinbtn" class="btn btn-default btn-block btn-sm"><?php echo get_phrase('join')?></button>
		<div>
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
		var cbtnspin, clickedbtn, pageYpos, tempScrollTop, didclient, didkeyword, dclientselected, dkeywordselected;
		var newdivid = 0;

		var d = new Date();
		var day = d.getDate();
		var month = (d.getMonth() + 1);
		var month = ('0' + month).slice(-2);
		var year = d.getFullYear();
		var todaydate = day+'-'+month+'-'+year;

		jQuery.fn.scrollTo = function(elem) {
			$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
			return this;
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
			$('.pull-right.text-muted.pageload').css('display', 'none');
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
			$('.pull-right.text-muted.pageload').css('display', 'none');

			clickedbtn = $(this);
			btnform = clickedbtn.parent('form');
			cbtnspin = clickedbtn.children('i');

			tempScrollTop = $(window).scrollTop();

			cbtnspin.css('display', 'inline-block');
			$('#keywordrow > .col-lg-12 > .panel').detach();

			$.post('<?php echo base_url("pages/home_radio_keyword")?>',
				btnform.serialize(),
				function(data, textStatus, xhr) {
					// console.log(data);
					cbtnspin.css('display', 'none');

					didclient = data.id_client;
					dclientselected = data.client_selected;
					didkeyword = data.id_keyword;
					dkeywordselected = data.keyword_selected;
					divcount = 1;

					$('#headerclient').text(dclientselected);
					$('#headerclient').append('<small id="headerkeyword"> - '+dkeywordselected+'</small>');

					dtexts = data.keyword_texts;
					$.each(dtexts, function(index, val) {
						xmlfpath = val.path.replace('/app/application/', '');
						xmlfilename = val.filename;
						xmlidfile = val.id_file;
						xmlstate = val.state;
						xmlradio = val.radio;
						xmlidradio = val.id_radio;

						xts = new Date(val.timestamp * 1000);
						xtsday = xts.getDate();
						xtsday = ('0' + xtsday).slice(-2);
						xtsmonth = (xts.getMonth() + 1);
						xtsmonth = ('0' + xtsmonth).slice(-2);
						xtsyear = xts.getFullYear();
						xtshour = xts.getHours();
						xtshour = ('0'+xtshour).slice(-2);
						xtsminutes = xts.getMinutes();
						xtsminutes = ('0'+xtsminutes).slice(-2);
						xtsseconds = xts.getSeconds();
						xtsseconds = ('0'+xtsseconds).slice(-2);
						xtsstartf = xtsday+'/'+xtsmonth+'/'+xtsyear+' '+xtshour+':'+xtsminutes+':'+xtsseconds;;

						xte = new Date(val.timestamp * 1000);
						xte.setMinutes(xte.getMinutes()+10);
						xteday = xte.getDate();
						xteday = ('0' + xteday).slice(-2);
						xtemonth = (xte.getMonth() + 1);
						xtemonth = ('0' + xtemonth).slice(-2);
						xteyear = xte.getFullYear();
						xtehour = xte.getHours();
						xtehour = ('0'+xtehour).slice(-2);
						xteminutes = xte.getMinutes();
						xteminutes = ('0'+xteminutes).slice(-2);
						xteseconds = xte.getSeconds();
						xteseconds = ('0'+xteseconds).slice(-2);
						xtendf = xteday+'/'+xtemonth+'/'+xteyear+' '+xtehour+':'+xteminutes+':'+xteseconds;

						xmltimestamp = val.timestamp;
						xmlid = val.id_text;
						xmltext = val.text_content;

						audiofpath = xmlfpath;
						audioidfile = xmlidfile - 1;
						str = (parseInt(xmlfilename, 16) - 1).toString(16);
						audiofilename = "0".repeat(3) + str.toUpperCase();
						audiofpath = xmlfpath;

						divhtml =	'<div id="div'+divcount+'" class="panel panel-default">'+
										'<div class="panel-heading text-center">'+
											'<label class="pull-left" style="font-weight: normal">'+
												'<input type="checkbox" class="cbjoinfiles" id="cb'+divcount+'" '+
												'data-iddiv="div'+divcount+'" data-idclient="'+didclient+'" data-idkeyword="'+didkeyword+'" '+
												'data-idradio="'+xmlidradio+'" data-timestamp="'+xmltimestamp+'"> <?php echo get_phrase("join"); ?>'+
											'</label>'+

											'<label class="labeltitle">'+
												'<i class="fa fa-search fa-fw"></i>'+
												'<span class="sqtkwf" id="qtkwfid'+divcount+'"></span>'+
												'&nbsp;&nbsp;&nbsp;&nbsp;'+
												'<i class="fa fa-bullhorn fa-fw"></i> '+
												xmlradio+' - '+xmlstate+ ' | ' +xtsstartf+' - '+xtendf+
											'</label>'+

											'<div class="btn-toolbar pull-right">'+
												'<button class="btn btn-warning btn-xs loadprevious" '+
												'data-iddiv="div'+divcount+'" data-idclient="'+didclient+'" data-idkeyword="'+didkeyword+'" '+
												'data-idradio="'+xmlidradio+'" data-timestamp="'+xmltimestamp+'" data-position="previous">'+
													'<i id="iload'+divcount+'" style="display: none" class="fa fa-refresh fa-spin"></i> '+
													'<?php echo get_phrase("previous"); ?>'+
												'</button>'+

												'<button class="btn btn-warning btn-xs loadnext" '+
												'data-iddiv="div'+divcount+'" data-idclient="'+didclient+'" data-idkeyword="'+didkeyword+'" '+
												'data-idradio="'+xmlidradio+'" data-timestamp="'+xmltimestamp+'" data-position="next">'+
													'<i id="iload'+divcount+'" style="display: none" class="fa fa-refresh fa-spin"></i> '+
													'<?php echo get_phrase("next"); ?>'+
												'</button>'+

												'<button type="button" class="btn btn-danger btn-xs discarddoc" '+
												'data-iddiv="div'+divcount+'" data-idtext="'+xmlid+'" '+
												'data-idkeyword="'+didkeyword+'" data-idclient="'+didclient+'" '+
												'data-toggle="collapse" data-target="#div'+divcount+'">'+
													'<i style="display: none" class="fa fa-refresh fa-spin"></i>'+
													'<?php echo get_phrase('discard'); ?>'+
												'</button>'+

												'<button type="submit" form="form_edit_'+divcount+'" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase("edit"); ?></button>'+

												'<form id="form_edit_'+divcount+'" action="<?php echo base_url("pages/edit_temp"); ?>" target="_blank" method="POST">'+
													'<input type="hidden" name="mp3pathfilename" value="<?php echo base_url("assets/");?>'+audiofpath+'/'+audiofilename+'">'+
													'<input type="hidden" name="xmlpathfilename" value="<?php echo base_url("assets/");?>'+xmlfpath+'/'+xmlfilename+'">'+
													'<input type="hidden" name="state" value="'+xmlstate+'">'+
													'<input type="hidden" name="radio" value="'+xmlradio+'">'+
													'<input type="hidden" name="timestamp" value="'+xmltimestamp+'">'+
													'<input type="hidden" name="id_keyword" value="'+didkeyword+'">'+
													'<input type="hidden" name="id_client" value="'+didclient+'">'+
													'<input type="hidden" name="id_file" value="'+xmlidfile+'">'+
													'<input type="hidden" name="id_text" value="'+xmlid+'">'+
													'<input type="hidden" name="client_selected" value="'+dclientselected+'">'+
												'</form>'+
											'</div>'+
										'</div>'+
										'<div class="panel-body">'+
											'<div class="row audioel">'+
												'<div class="col-lg-12">'+
													'<audio class="center-block" style="width: 100%" src="<?php echo base_url("assets/");?>'+audiofpath+'/'+audiofilename+'" controls></audio>'+
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
									'</div>';
						$('#keywordrow > .col-lg-12').append(divhtml);

						divcount += 1;
					});

					$('#headerhome').fadeOut(200, function() {
						$('#headerclient').fadeIn(300);
					});

					$('#mainrow').fadeOut(200, function() {
						$('#keywordrow').fadeIn(300, function() {
							$('#back-to-home').fadeIn('fast');

							totalpanels = $('div.panel.panel-default.collapse.in').length;
							ptexts = $('.ptext.text-justify');
							$.each(ptexts, function(index, val) {
								// console.log(val);
								cpid = $(val).attr('id');
								scpid = '#'+cpid;
								keywfound = '#'+cpid+' > .kwfound';
								keyword = dkeywordselected;

								pbodytext = $(val).text();
								rgx = new RegExp ('\\b'+keyword+'\\b', 'ig');
								pbodynewtext = pbodytext.replace(rgx, '<strong class="kwfound">'+keyword+'</strong>');
								$(scpid).html(null);
								$(scpid).html(pbodynewtext);

								qtkwf = $(keywfound).length;
								$(val)[0].parentElement.parentElement.parentElement.parentElement.children[0].children[1].children[1].innerText = qtkwf;
								$(val).scrollTo(keywfound);
							});

							$('.ptext').css('overflowY', 'hidden');
							$(window).scrollTop(0);
						});
					});
				}
			);
		});

		$(document).on('click', '.ptext', function(event) {
			// $(this).addClass('pulse');
			$(this).css({
				'box-shadow': '0px 0px 15px #949494',
				'overflowY': 'auto'
			});
		});

		$(document).on('mouseleave', '.ptext', function(event) {
			// console.log(event);
			// $(this).removeClass('pulse');
			$(this).scrollTo('#'+event.target.id + ' > .kwfound');
			$(this).css({
				'box-shadow': '0px 0px 0px #FFFFFF',
				'overflowY': 'hidden'
			});
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

			$('#headerclient').fadeOut(200, function() {
				$('#headerhome').fadeIn(300);
			});

			$('#keywordrow').fadeOut(200, function() {
				$('#mainrow').fadeIn(300, function() {
					$(window).scrollTop(tempScrollTop)
				});
			});
		});

		$(document).on('click', '.loadprevious', function(event) {
			loadp = $(this);
			loadp.children('i').css('display', 'inline-block');

			iddiv = loadp.attr('data-iddiv');
			iddivn = Number(iddiv.replace('div', ''));
			idradio = loadp.attr('data-idradio');
			startdate = loadp.attr('data-timestamp');

			$.get('<?php echo base_url('pages/get_radio/')?>'+idradio+'/'+startdate+'/previous', function(data) {
				console.log(data);
				loadp.children('i').css('display', 'none');
				numfound = data.length;
				if (numfound == 0) {
					warnhtml =	'<div class="alert alert-warning" role="alert">'+
									'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
									'<span class="sr-only">Error:</span>'+
									'<?php echo get_phrase('no_more_files'); ?>'+'!'+
								'</div>';
					$('#'+iddiv).after(warnhtml);

					setTimeout(function() {
						$('div.alert.alert-warning').fadeOut('slow');
					}, 3000);
				} else {
					xmlfpath = data.path.replace('/app/application/', '/assets/');
					xmlfilename = data.filename;
					xmlidfile = data.id_file;
					xmlstate = data.state;
					xmlradio = data.radio;
					xmlidradio = data.id_radio;

					st = new Date(data.timestamp * 1000);
					stday = st.getDate();
					stday = ('0' + stday).slice(-2);
					stmonth = (st.getMonth() + 1);
					stmonth = ('0' + stmonth).slice(-2);
					styear = st.getFullYear();
					sthour = st.getHours();
					sthour = ('0'+sthour).slice(-2);
					stminutes = st.getMinutes();
					stminutes = ('0'+stminutes).slice(-2);
					stseconds = st.getSeconds();
					stseconds = ('0'+stseconds).slice(-2);
					stfull = stday+'/'+stmonth+'/'+styear+' '+sthour+':'+stminutes+':'+stseconds;

					ed = new Date(data.timestamp * 1000);
					ed.setMinutes(ed.getMinutes()+10);
					edday = ed.getDate();
					edday = ('0' + edday).slice(-2);
					edmonth = (ed.getMonth() + 1);
					edmonth = ('0' + edmonth).slice(-2);
					edyear = ed.getFullYear();
					edhour = ed.getHours();
					edhour = ('0'+edhour).slice(-2);
					edminutes = ed.getMinutes();
					edminutes = ('0'+edminutes).slice(-2);
					edseconds = st.getSeconds();
					edseconds = ('0'+edseconds).slice(-2);
					edfull = edday+'/'+edmonth+'/'+edyear+' '+edhour+':'+edminutes+':'+edseconds;;

					xmltimestamp = data.timestamp;
					idtext = data.id_text;
					xmltext = data.text_content;

					audioidfile = xmlidfile - 1;
					str = (parseInt(xmlfilename, 16) - 1).toString(16);
					audiofilename = "0".repeat(3) + str.toUpperCase();
					// audiofilename = str.toUpperCase();

					currurl = window.location.origin;

					audiourl = currurl+xmlfpath+'/'+audiofilename;

					newdivid += 1;
					newdividn = iddiv+'-'+newdivid;

					divclone = $('#'+iddiv).clone(true);
					console.log(divclone);

					divclone.removeClass('panel-default');
					divclone.addClass('panel-info');
					divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> '+xmlradio+' - '+xmlstate+' | '+stfull+' - '+edfull);
					divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
					divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
					divclone.children('panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
					divclone.attr('id', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-timestamp', xmltimestamp);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-timestamp', xmltimestamp);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-idtext', idtext);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb') + '-' + newdivid);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-idtext', idtext);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-timestamp', xmltimestamp);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);
					divclone.children('.panel-body').children('.textel').children('.pbody').children('.ptext').attr('id', 'id', iddiv.replace('div', 'ptext')+'-'+newdivid);
					divclone[0].children[1].children[0].children[0].children[0].src = audiourl;
					divclone[0].children[1].children[1].children[0].children[0].innerText = xmltext;

					$('#'+iddiv).after(divclone);
				}
			});
		});

		$(document).on('click', '.loadnext', function(event) {
			loadp = $(this);
			loadp.children('i').css('display', 'inline-block');

			iddiv = loadp.attr('data-iddiv');
			iddivn = Number(iddiv.replace('div', ''));
			idradio = loadp.attr('data-idradio');
			startdate = loadp.attr('data-timestamp');

			$.get('<?php echo base_url('pages/get_radio/')?>'+idradio+'/'+startdate+'/next', function(data) {
				console.log(data);
				loadp.children('i').css('display', 'none');
				numfound = data.length;
				if (numfound == 0) {
					warnhtml =	'<div class="alert alert-warning" role="alert">'+
									'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
									'<span class="sr-only">Error:</span>'+
									'<?php echo get_phrase('no_more_files'); ?>'+'!'+
								'</div>';
					$('#'+iddiv).after(warnhtml);

					setTimeout(function() {
						$('div.alert.alert-warning').fadeOut('slow');
					}, 3000);
				} else {
					xmlfpath = data.path.replace('/app/application/', '/assets/');
					xmlfilename = data.filename;
					xmlidfile = data.id_file;
					xmlstate = data.state;
					xmlradio = data.radio;
					xmlidradio = data.id_radio;

					st = new Date(data.timestamp * 1000);
					stday = st.getDate();
					stday = ('0' + stday).slice(-2);
					stmonth = (st.getMonth() + 1);
					stmonth = ('0' + stmonth).slice(-2);
					styear = st.getFullYear();
					sthour = st.getHours();
					sthour = ('0'+sthour).slice(-2);
					stminutes = st.getMinutes();
					stminutes = ('0'+stminutes).slice(-2);
					stseconds = st.getSeconds();
					stseconds = ('0'+stseconds).slice(-2);
					stfull = stday+'/'+stmonth+'/'+styear+' '+sthour+':'+stminutes+':'+stseconds;

					ed = new Date(data.timestamp * 1000);
					ed.setMinutes(ed.getMinutes()+10);
					edday = ed.getDate();
					edday = ('0' + edday).slice(-2);
					edmonth = (ed.getMonth() + 1);
					edmonth = ('0' + edmonth).slice(-2);
					edyear = ed.getFullYear();
					edhour = ed.getHours();
					edhour = ('0'+edhour).slice(-2);
					edminutes = ed.getMinutes();
					edminutes = ('0'+edminutes).slice(-2);
					edseconds = st.getSeconds();
					edseconds = ('0'+edseconds).slice(-2);
					edfull = edday+'/'+edmonth+'/'+edyear+' '+edhour+':'+edminutes+':'+edseconds;;

					xmltimestamp = data.timestamp;
					idtext = data.id_text;
					xmltext = data.text_content;

					audioidfile = xmlidfile - 1;
					str = (parseInt(xmlfilename, 16) - 1).toString(16);
					audiofilename = "0".repeat(3) + str.toUpperCase();
					// audiofilename = str.toUpperCase();

					currurl = window.location.origin;

					audiourl = currurl+xmlfpath+'/'+audiofilename;

					newdivid += 1;
					newdividn = iddiv+'-'+newdivid;

					divclone = $('#'+iddiv).clone(true);
					// console.log(divclone);

					divclone.removeClass('panel-default');
					divclone.addClass('panel-info');
					divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> '+xmlradio+' - '+xmlstate+' | '+stfull+' - '+edfull);
					divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
					divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
					divclone.children('panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
					divclone.attr('id', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-timestamp', xmltimestamp);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-timestamp', xmltimestamp);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-idtext', idtext);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb') + '-' + newdivid);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-idtext', idtext);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-timestamp', xmltimestamp);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);
					divclone.children('.panel-body').children('.textel').children('.pbody').children('.ptext').attr('id', 'id', iddiv.replace('div', 'ptext')+'-'+newdivid);
					divclone[0].children[1].children[0].children[0].children[0].src = audiourl;
					divclone[0].children[1].children[1].children[0].children[0].innerText = xmltext;

					$('#'+iddiv).before(divclone);
				}
			});
		});

		$('#joinbtn').click(function(event) {
			var frm = $('#filesform').submit();
			$('#filesform').closest('form').find("input[type=hidden], textarea").val('');
			$('input[type=checkbox]').prop('checked',false);
			$('#fileslist').empty();
			$('#joindiv').removeClass('show');
			return false;
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