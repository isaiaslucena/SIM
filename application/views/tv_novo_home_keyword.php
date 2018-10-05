<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/dataclip/home_keyword.css")?>">

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php echo $client_selected; ?>
				<small> - <?php echo $keyword_selected; ?></small>
			</h1>
		</div>
	</div>

	<div class="row">
		<div id="divcolc" class="col-lg-12">
			<?php
			$divcount = 0;
			$icount = 0;
			foreach ($keyword_texts->response->docs as $found) {
				$divcount++;
				$icount++;
				$sid = $found->id_i;
				$sidsource = $found->id_source_i;
				$smediaurl = $found->mediaurl_s;
				if (isset($found->times_t)) {
					$stimes = json_decode(str_replace('\u0000', '', $found->times_t[0]), true);
				}

				$timezone = new DateTimeZone('UTC');
				$sd = new Datetime($found->starttime_dt, $timezone);
				$ed = new Datetime($found->endtime_dt, $timezone);
				$newtimezone = new DateTimeZone('America/Sao_Paulo');
				$sd->setTimezone($newtimezone);
				$ed->setTimezone($newtimezone);
				$sstartdate = $sd->format('d/m/Y H:i:s');
				$senddate = $ed->format('d/m/Y H:i:s');
				$sendtime = $ed->format('H:i:s');
				$dstartdate = $sd->format('Y-m-d_H-i-s');
				$denddate = $ed->format('Y-m-d_H-i-s');
				$epochstartdate = $sd->format('U');
				$epochenddate = $ed->format('U');

				$stext = $found->content_t[0];
				$ssource = $found->source_s; ?>
				<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default collapse in">
					<div class="panel-heading text-center">
						<label class="pull-left disabled" style="font-weight: normal">
							<input type="checkbox" class="cbjoinfiles disabled" id="<?php echo 'cb'.$divcount;?>"
							 data-iddoc="<?php echo $sid?>" data-idsource="<?php echo $sidsource?>"
							  data-source="<?php echo $ssource?>" data-startdate="<?php echo $sstartdate; ?>"
							   data-enddate="<?php echo $senddate; ?>" data-idclient="<?php echo $id_client;?>"
							    data-idkeyword="<?php echo $id_keyword;?>" disabled> <?php echo get_phrase('join');?>
						</label>

						<label class="labeltitle">
							<i class="fa fa-search fa-fw"></i>
							<span class="sqtkwf" id="<?php echo 'qtkwfid'.$divcount;?>"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<i class="fa fa-bullhorn fa-fw"></i>
							<?php echo $ssource." | ".$sstartdate." - ".$sendtime;?>
						</label>

						<div class="btn-toolbar pull-right">
							<button class="btn btn-warning btn-xs loadprevious" data-iddiv="<?php echo 'div'.$divcount;?>"
							 data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $found->starttime_dt?>"
							  data-enddate="<?php echo $found->endtime_dt?>" data-position="previous">
								<i id="<?php echo 'iload'.$icount;?>" style="display: none" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('previous');
								$icount++; ?>
							</button>

							<button class="btn btn-warning btn-xs loadnext" data-iddiv="<?php echo 'div'.$divcount;?>"
							 data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $found->starttime_dt?>"
							  data-enddate="<?php echo $found->endtime_dt?>" data-position="next">
								<i id="<?php echo 'iload'.$icount;?>" style="display: none;" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('next'); ?>
							</button>

							<button type="button" class="btn btn-danger btn-xs discarddoc" data-iddiv="<?php echo 'div'.$divcount;?>"
							 data-iddoc="<?php echo $sid?>" data-idkeyword="<?php echo $id_keyword;?>"
							  data-idclient="<?php echo $id_client;?>" data-toggle="collapse"
							   data-target="<?php echo '#div'.$divcount;?>">
								<i style="display: none" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('discard');?>
							</button>

							<button disabled type="submit" form="<?php echo 'form'.$divcount;?>" class="btn btn-primary btn-xs pull-right disabled"><?php echo get_phrase('edit');?></button>
							<form id="<?php echo 'form'.$divcount;?>" style="all: unset;" action="<?php echo base_url('pages/edit_novo');?>" target="_blank" method="POST">
								<input type="hidden" name="sid" value="<?php echo $sid;?>">
								<input type="hidden" name="mediaurl" value="<?php echo $smediaurl;?>">
								<input type="hidden" name="ssource" value="<?php echo $ssource;?>">
								<input type="hidden" name="sstartdate" value="<?php echo $sstartdate;?>">
								<input type="hidden" name="senddate" value="<?php echo $senddate;?>">
								<input type="hidden" name="id_keyword" value="<?php echo $id_keyword;?>">
								<input type="hidden" name="id_client" value="<?php echo $id_client;?>">
								<input type="hidden" name="client_selected" value="<?php echo $client_selected;?>">
							</form>
						</div>

					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-5">
								<video class="center-block img-thumbnail noloaded" src="<?php echo $smediaurl; ?>" controls preload="metadata" poster="<?php echo base_url('assets/imgs/colorbar.jpg')?>"></video>
								<a class="btn btn-default btn-sm" target="_blank" href="<?php echo $smediaurl; ?>" download="<?php echo str_replace(' ','_', $ssource).'_'.$dstartdate.'_'.$denddate.'.mp4'; ?>"><i class="fa fa-download"></i> Baixar</a>
							</div>
							<div class="col-lg-7 pbody" id="<?php echo 'pbody'.$divcount;?>">
								<p id="<?php echo 'ptext'.$divcount; ?>" class="text-justify ptext" style="height: 300px; overflow-y: hidden">
									<?php
									// echo (string)$stext;

									if (isset($found->times_t)) {
										foreach ($stimes as $stime) {
											if (isset($stime['words'])) {
												foreach ($stime['words'] as $word) {
													$wbegin = (float)$word['begin'];
													$wend = (float)$word['end'];
													$wdur = substr((string)($wend - $wbegin), 0, 5);
													$wspan = '<span data-dur="'.$wdur.'" data-begin="'.$word['begin'].'">'.$word['word'].'</span> ';
													echo $wspan;
												}
											}
										}
									} else {
										echo (string)$stext;
									}
									?>
								</p>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<span class="text-muted center-block text-center" id="loadmore" style="opacity: 0;">
				<i class="fa fa-refresh fa-spin"></i> Carregando...
			</span>
		</div>
	</div>

	<script type="text/javascript">
		var newdivid = 0, cksource = 0, totalpanels, pstart, pcstart,
		totalpanelsd = 0, videoels, joinfiles = false, filestojoin = [];

		jQuery.fn.scrollTo = function(elem) {
			$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
			return this;
		}

		function scrolltokeyword() {
			ptexts = $('.ptext.text-justify');
			ptextsl = ptexts.length;
			$.each(ptexts, function(index, val) {
				cpid = $(val).attr('id');
				scpid = '#'+cpid;
				keywfound = '#'+cpid+' > .kword';
				keyword = '<?php echo $keyword_selected; ?>';

				pbodytext = $(val).text();
				rgx = new RegExp ('\\b'+keyword+'\\b', 'ig');
				pbodynewtext = pbodytext.replace(rgx, '<span class="kword">'+keyword+'</span>');
				$(scpid).html(null);
				$(scpid).html(pbodynewtext);

				qtkwf = $(keywfound).length;
				$(val)[0].parentElement.parentElement.parentElement.parentElement.children[0].children[1].children[1].innerText = qtkwf;
				$(val).scrollTo(keywfound);
			});
		}

		$(document).ready(function() {
			totalpanels = $('div.panel.panel-default.collapse.in').length;

			scrolltokeyword();

			pstart = <?php echo $start;?>;
			pcstart = <?php echo $rows;?>;
			pfound = <?php echo $keyword_texts->response->numFound;?>;
			$(window).scroll(function() {
				winscrollToph = ($(window).scrollTop() + $(window).height());
				winheight = $(document).height();
				if (winscrollToph == winheight) {
					pstart = pstart + pcstart;
					if (pstart <= pfound) {
						$('#loadmore').animate({'opacity': 100}, 500);
						$.post('get_tv_novo_keyword_texts',
							{
								'id_keyword': <?php echo $id_keyword;?>,
								'id_client': <?php echo $id_client;?>,
								'keyword_selected': '<?php echo $keyword_selected;?>',
								'client_selected': '<?php echo $client_selected;?>',
								'startdate': '<?php echo $startdate;?>',
								'enddate': '<?php echo $enddate;?>',
								'start': pstart,
								'rows': <?php echo $rows;?>
							},
							function(data, textStatus, xhr) {
								$('#loadmore').before(data);
								totalpanels = $('div.panel.panel-default.collapse.in').length;
								scrolltokeyword();
								$('#loadmore').animate({'opacity': 0}, 500);
						});
					}
				}
			});

			// fvideo = $('video[preload="none"]');
			// $(fvideo[0]).attr('preload', 'metadata');
			// $(fvideo[0]).removeClass('noloaded');
			// $(fvideo[0]).removeAttr('poster');

			// $(this).scroll(function(event) {
			// 	var windowh = $(window).height() - 200;
			// 	// console.log('Window Height:')
			// 	// console.log(windowh);

			// 	// videoels = document.querySelectorAll('video[preload="none"]');
			// 	// videoels = document.querySelectorAll('.noloaded');
			// 	// videoels = $('.noloaded');
			// 	videoels = $('video[preload="none"]');
			// 	videoel = $(videoels[0]);

			// 	// console.log('Video elements:');
			// 	// console.log(videoels);
			// 	// console.log(videoels.length);
			// 	videoelo = videoel.offset().top;
			// 	// console.log('Video element 0 offset top:');
			// 	// console.log(videoelo);
			// 	// console.log('');
			// 	if (videoelo <= windowh) {
			// 		videoel.attr('preload', 'metadata');
			// 		videoel.removeAttr('poster');
			// 		videoel.removeClass('noloaded');

			// 		// videoel.setAttribute('preload', 'metadata');
			// 		// videoel.removeAttribute('poster');
			// 		// videoel.classList.remove('noloaded');

			// 		// videoels = $('.noloaded');
			// 	}
			// });
		});

		$('video').bind('contextmenu', function() { return false; });

		if ($('#back-to-top').length) {
			var scrollTrigger = 1000,
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

		$(document).on('click', '.loadprevious', function(event) {
			loadp = $(this);
			loadp.children('i').css('display', 'inline-block');

			iddiv = $(this).attr('data-iddiv');
			iddivn = Number(iddiv.replace('div', ''));
			idsource = $(this).attr('data-idsource');
			startdate = $(this).attr('data-startdate');

			$.get('<?php echo base_url('pages/get_tv_novo/')?>' + idsource + '/' + encodeURI(startdate) +'/previous', function(data) {
				// console.log(data);
				loadp.children('i').css('display', 'none');
				numfound = data.response.numFound;
				if (numfound == 0) {
					warnhtml =	'<div class="alert alert-warning" role="alert">'+
									'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
									'<span class="sr-only">Error:</span>'+
									'<?php echo get_phrase('no_more_files'); ?>'+'!'+
								'</div>';
					$('#'+iddiv).before(warnhtml);

					setTimeout(function() {
						$('div.alert.alert-warning').fadeOut('slow');
					}, 3000);
				} else {
					did = data.response.docs[0].id_i;
					dsourceid = data.response.docs[0].source_id_i;
					dsource = data.response.docs[0].source_s;
					dmediaurl = data.response.docs[0].mediaurl_s;
					dstartdate = data.response.docs[0].starttime_dt;
					denddate = data.response.docs[0].endtime_dt;
					dcontent = data.response.docs[0].content_t[0];

					var sd = new Date(dstartdate);
					var sday = sd.getDate();
					var sday = ('0' + sday).slice(-2);
					var smonth = (sd.getMonth() + 1);
					var smonth = ('0' + smonth).slice(-2);
					var syear = sd.getFullYear();
					var shour = sd.getHours();
					var shour = ('0' + shour).slice(-2);
					var sminute = sd.getMinutes();
					var sminute = ('0' + sminute).slice(-2);
					var ssecond = sd.getSeconds();
					var ssecond = ('0' + ssecond).slice(-2);
					var dfstartdate = sday+'/'+smonth+'/'+syear+' '+shour+':'+sminute+':'+ssecond;

					var ed = new Date(denddate);
					var eday = ed.getDate();
					var eday = ('0' + eday).slice(-2);
					var emonth = (ed.getMonth() + 1);
					var emonth = ('0' + emonth).slice(-2);
					var eyear = ed.getFullYear();
					var ehour = ed.getHours();
					var ehour = ('0' + ehour).slice(-2);
					var eminute = ed.getMinutes();
					var eminute = ('0' + eminute).slice(-2);
					var esecond = ed.getSeconds();
					var esecond = ('0' + esecond).slice(-2);
					var dfenddate = eday+'/'+emonth+'/'+eyear+' '+ehour+':'+eminute+':'+esecond;

					newdivid += 1;
					newdividn = iddiv + '-' + newdivid;

					divclone = $('#'+iddiv).clone(true);

					divclone.removeClass('panel-default');
					divclone.addClass('panel-info');
					divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> ' + dsource + ' | ' + dfstartdate + ' - ' + dfenddate);
					divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
					divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
					divclone.children('panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
					divclone.attr('id', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-startdate', dstartdate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-enddate', denddate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-startdate', dstartdate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-enddate', denddate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-iddoc', did);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb') + '-' + newdivid);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-iddoc', did);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-startdate', dfstartdate);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-enddate', dfenddate);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);
					divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').attr('id', 'id', iddiv.replace('div', 'ptext') + '-' + newdivid);
					divclone.children('.panel-body').children('.row').children('.col-lg-5').children('video').attr('src', dmediaurl);
					divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').text(dcontent);

					$('#'+iddiv).after(divclone);
				}
			});
		});

		$(document).on('click', '.loadnext', function(event) {
			loadp = $(this);
			loadp.children('i').css('display', 'inline-block');

			iddiv = $(this).attr('data-iddiv');
			iddivn = Number(iddiv.replace('div', ''));
			idsource = $(this).attr('data-idsource');
			startdate = $(this).attr('data-enddate');

			$.get('<?php echo base_url('pages/get_tv_novo/')?>' + idsource + '/' + encodeURI(startdate) +'/next', function(data) {
				// console.log(data);
				loadp.children('i').css('display', 'none');
				numfound = data.response.numFound;
				if (numfound == 0) {
					warnhtml =	'<div class="alert alert-warning" role="alert">'+
									'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
									'<span class="sr-only">Error:</span>'+
									'<?php echo get_phrase('no_more_files'); ?>'+'!'+
								'</div>';
					$('#'+iddiv).before(warnhtml);

					setTimeout(function() {
						$('div.alert.alert-warning').fadeOut('slow');
					}, 3000);
				} else {
					did = data.response.docs[0].id_i;
					dsourceid = data.response.docs[0].source_id_i;
					dsource = data.response.docs[0].source_s;
					dmediaurl = data.response.docs[0].mediaurl_s;
					dstartdate = data.response.docs[0].starttime_dt;
					denddate = data.response.docs[0].endtime_dt;
					dcontent = data.response.docs[0].content_t[0];

					var sd = new Date(dstartdate);
					var sday = sd.getDate();
					var sday = ('0' + sday).slice(-2);
					var smonth = (sd.getMonth() + 1);
					var smonth = ('0' + smonth).slice(-2);
					var syear = sd.getFullYear();
					var shour = sd.getHours();
					var shour = ('0' + shour).slice(-2);
					var sminute = sd.getMinutes();
					var sminute = ('0' + sminute).slice(-2);
					var ssecond = sd.getSeconds();
					var ssecond = ('0' + ssecond).slice(-2);
					var dfstartdate = sday+'/'+smonth+'/'+syear+' '+shour+':'+sminute+':'+ssecond;

					var ed = new Date(denddate);
					var eday = ed.getDate();
					var eday = ('0' + eday).slice(-2);
					var emonth = (ed.getMonth() + 1);
					var emonth = ('0' + emonth).slice(-2);
					var eyear = ed.getFullYear();
					var ehour = ed.getHours();
					var ehour = ('0' + ehour).slice(-2);
					var eminute = ed.getMinutes();
					var eminute = ('0' + eminute).slice(-2);
					var esecond = ed.getSeconds();
					var esecond = ('0' + esecond).slice(-2);
					var dfenddate = eday+'/'+emonth+'/'+eyear+' '+ehour+':'+eminute+':'+esecond;

					newdivid += 1;
					newdividn = iddiv + '-' + newdivid;

					divclone = $('#'+iddiv).clone(true);

					divclone.removeClass('panel-default');
					divclone.addClass('panel-info');
					divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> ' + dsource + ' | ' + dfstartdate + ' - ' + dfenddate);
					divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
					divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
					divclone.children('panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
					divclone.attr('id', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-startdate', dstartdate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-enddate', denddate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-startdate', dstartdate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-enddate', denddate);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-iddoc', did);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
					divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb') + '-' + newdivid);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-iddoc', did);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-startdate', dfstartdate);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-enddate', dfenddate);
					divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);
					divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').attr('id', 'id', iddiv.replace('div', 'ptext') + '-' + newdivid);
					divclone.children('.panel-body').children('.row').children('.col-lg-5').children('video').attr('src', dmediaurl);
					divclone.children('.panel-body').children('.row').children('.pbody').children('.ptext').text(dcontent);

					$('#'+iddiv).before(divclone);
				}
			});
		});

		$(document).on('click', '.cbjoinfiles', function(event) {
			ciddoc = $(this).attr('data-iddoc');
			cidsource = $(this).attr('data-idsource');
			csource = $(this).attr('data-source');
			cstartdate = $(this).attr('data-startdate');
			cenddate = $(this).attr('data-enddate');
			cidclient = $(this).attr('data-idclient');
			cidkeyword = $(this).attr('data-idkeyword');

			checked = event.target.checked;
			if (checked) {
				if (cidsource == cksource || cksource == 0) {
					$('#wsource').text(csource);
					$('#fileslist').append('<a id="acb'+ciddoc+'" class="list-group-item">' + cstartdate + ' - '+ cenddate + '</a>');
					filestojoin.push(ciddoc);
					$('#joindiv').addClass('show');
					cksource = cidsource;
					if (filestojoin.length >= 2) {
						$('#joinbtn').attr({
							'data-idclient': cidclient,
							'data-idkeyword': cidkeyword
						});
						$('#joinbtn').removeClass('disabled');
						$('#joinbtn').removeAttr('disabled');
						joinfiles = true;
					}
				} else {
					swal("Atenção!", "A rádios devem ser iguais!", "error");
					$(this).prop("checked", false);
					$('#acb'+ciddoc).detach();
					cksource = 0;
				}
			} else {
				fileindex = filestojoin.indexOf(ciddoc);
				filestojoin.splice(fileindex,1);
				$('#acb'+ciddoc).detach();
				if (filestojoin.length < 2) {
					$('#joinbtn').addClass('disabled');
					$('#joinbtn').attr('disabled', true);
					joinfiles = false;
				} else if (filestojoin.length < 1) {
					$('#joindiv').removeClass('show');
				}
			}
		});

		$('#joinbtn').click(function(event) {
			jbtn = $(this);
			jidclient = jbtn.attr('data-idclient');
			jidkeyword = jbtn.attr('data-idkeyword');

			$('#jids_doc').val(filestojoin);
			$('#jid_client').val(jidclient);
			$('#jid_keyword').val(jidkeyword);

			swal({
				title: "Carregando...",
				// text: "Aguarde...",
				imageUrl: "<?php echo base_url('assets/imgs/loading.gif'); ?>",
				showCancelButton: false,
				showConfirmButton: false
			});

			if (joinfiles) {
				document.getElementById('joinform').submit();
				$('#joindiv').removeClass('show');
				$('#joinbtn').addClass('disabled');
				$('#joinbtn').attr('disabled', true);
				$('#fileslist').empty();
				$('input[type="checkbox"]').prop("checked", false);
				$('.panel-info').detach();
				filestojoin = [];
				joinfiles = false;
				cksource = 0;
				swal.close();
			}
		});

		$(document).on('click', '.discarddoc', function(event) {
			discardbtn = $(this);
			discardbtn.children('i').css('display', 'inline-block');

			iddoc = discardbtn.attr('data-iddoc');
			iddiv = discardbtn.attr('data-iddiv');
			idkeyword = discardbtn.attr('data-idkeyword');
			idclient = discardbtn.attr('data-idclient');
			iduser = '<?php echo $this->session->userdata("id_user");?>';

			$.post('<?php echo base_url("pages/discard_doc_tv_novo")?>',
				{
					'iddoc': iddoc,
					'idkeyword': idkeyword,
					'idclient': idclient,
					'iduser': iduser
				},
				function(data, textStatus, xhr) {
					// console.log(data);
					discardbtn.children('i').css('display', 'none');
					$('#'+iddiv).removeClass('panel-default');
					$('#'+iddiv).addClass('panel-danger');
					totalpanelsd += 1;
					// console.log('total descarted panels = ' + $('div.panel.panel-danger.collapse').length);

					if (totalpanelsd == totalpanels) {
						console.log('no more panels!');
						window.location = '<?php echo base_url("pages/index_tv")?>';
					}
				}
			);
		});

		$(document).on('click', '.ptext', function() {
			$(this).css('overflowY', 'auto');
		})

		$(document).on('mouseleave' ,'.ptext', function() {
			$(this).css('overflowY', 'hidden');
		});
	</script>