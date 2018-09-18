<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<style type="text/css">
		#joindiv {
			position: fixed;
			bottom: 0px;
			left: 260px;
			z-index: 9999;
			display: none;
		}

		#content {
			height: 2000px;
		}

		.fkword,.kword{
			color: white;
			background-color: red;
			border: solid;
			border-color: red;
			border-width: 2px;
			border-radius: 8px;
			padding: 1px;
		}

		span[data-begin]:focus,
		span[data-begin]:hover {
			background-color: yellow;
			border-radius: 8px;
		}
		span[data-begin].speaking {
			background-color: yellow;
			border-radius: 8px;
			z-index: 900;
		}
		span[data-begin] {
			cursor: pointer;
		}
	</style>

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
				$epochstartdate = $sd->format('U');
				$epochenddate = $ed->format('U');

				$stext = $found->content_t[0];
				$ssource = $found->source_s; ?>
				<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default collapse in">
					<div class="panel-heading text-center">
						<label class="pull-left" style="font-weight: normal">
							<input type="checkbox" class="cbjoinfiles"
							id="<?php echo 'cb'.$divcount;?>" data-iddoc="<?php echo $sid?>"
							data-idsource="<?php echo $sidsource?>" data-source="<?php echo $ssource?>"
							data-startdate="<?php echo $sstartdate; ?>" data-enddate="<?php echo $senddate; ?>"
							data-idclient="<?php echo $id_client;?>" data-idkeyword="<?php echo $id_keyword;?>">
							 <?php echo get_phrase('join');?>
						</label>

						<label class="labeltitle">
							<i class="fa fa-search fa-fw"></i>
							<span id="<?php echo 'tkeyfound'.$divcount;?>"></span>
							<span class="sqtkwf" id="<?php echo 'qtkwfid'.$divcount;?>"></span>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<i class="fa fa-bullhorn fa-fw"></i>
							<?php echo $ssource." | ".$sstartdate." - ".$senddate;?>
						</label>

						<div class="btn-toolbar pull-right">
							<button class="btn btn-warning btn-xs loadprevious" data-iddiv="<?php echo 'div'.$divcount;?>" data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $found->starttime_dt?>" data-enddate="<?php echo $found->endtime_dt?>" data-position="previous">
								<i id="<?php echo 'iload'.$icount;?>" style="display: none" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('previous');
								$icount++; ?>
							</button>

							<button class="btn btn-warning btn-xs loadnext" data-iddiv="<?php echo 'div'.$divcount;?>" data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $found->starttime_dt?>" data-enddate="<?php echo $found->endtime_dt?>" data-position="next">
								<i id="<?php echo 'iload'.$icount;?>" style="display: none;" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('next'); ?>
							</button>

							<button type="button" class="btn btn-danger btn-xs discarddoc" data-iddiv="<?php echo 'div'.$divcount;?>" data-iddoc="<?php echo $sid?>" data-idkeyword="<?php echo $id_keyword;?>" data-idclient="<?php echo $id_client;?>" data-toggle="collapse" data-target="<?php echo '#div'.$divcount;?>">
								<i style="display: none" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('discard');?>
							</button>

							<button type="submit" form="<?php echo 'form'.$divcount;?>" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
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

							<button type="submit" form="<?php echo 'form_temp'.$divcount;?>" class="btn btn-default btn-xs pull-right">Editar_Temp</button>
							<form id="<?php echo 'form_temp'.$divcount;?>" style="all: unset;" action="<?php echo base_url('pages/edit_novo_temp');?>" target="_blank" method="POST">
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
						<div class="col-lg-12">
							<p class="paudio"><audio id="<?php echo 'paudio'.$divcount;?>" class="pfaudio" style="width: 100%" src="<?php echo $smediaurl; ?>" controls preload="metadata"></audio></p>
							<p id="<?php echo 'ptext'.$divcount;?>" class="text-justify ptext noscrolled" style="height: 300px; overflow-y: hidden">
								<?php
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
			<?php } ?>
			<input id="autofocus-current-word" class="autofocus-current-word" type="checkbox" checked style="display: none;">
			<span class="text-muted center-block text-center" id="loadmore" style="opacity: 0;">
				<i class="fa fa-refresh fa-spin"></i> Carregando...
			</span>
		</div>

		<div class="well well-sm" id="joindiv">
			<span id="wsource" class="center-block text-center"></span>
			<div class="list-group" style="max-height:  150px ; overflow: auto;">
				<small id="fileslist"></small>
			</div>
			<button id="joinbtn" class="btn btn-default btn-block btn-sm disabled" disabled><?php echo get_phrase('join')?></button>
			<form id="joinform" style="all: unset;" action="<?php echo base_url('pages/join_radio_novo');?>" target="_blank" method="POST">
				<input type="hidden" id="jids_doc" name="ids_doc">
				<input type="hidden" id="jid_client" name="id_client">
				<input type="hidden" id="jid_keyword" name="id_keyword">
			</form>
		<div>
	</div>

	<script src="<?php echo base_url('assets/readalong/readalong.js');?>"></script>
	<script type="text/javascript">
		var newdivid = 0, cksource = 0, totalpanels, pstart, pcstart,
		totalpanelsd = 0, joinfiles = false, filestojoin = [],
		keyword = '<?php echo $keyword_selected; ?>';
		keywordarr = keyword.split(" ");
		keywcount = keywordarr.length - 1;
		rgx = new RegExp('\\b'+keyword+'\\b', 'ig');

		jQuery.fn.scrollTo = function(elem) {
			$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
			return this;
		};

		function loadpn(flow, clbtn) {
			loadp = $(clbtn);
			loadp.children('i').css('display', 'inline-block');

			iddiv = loadp.attr('data-iddiv');
			iddivn = Number(iddiv.replace('div', ''));
			idsource = loadp.attr('data-idsource');

			if (flow == 'previous') {
				position = 'after';
				startdate = loadp.attr('data-startdate');
			} else if (flow == 'next'){
				position = 'before';
				startdate = loadp.attr('data-enddate');
			}

			$.get('<?php echo base_url('pages/get_radio_novo/')?>'+idsource+'/'+encodeURI(startdate)+'/'+flow, function(data) {
				loadp.children('i').css('display', 'none');
				numfound = data.response.numFound;
				if (numfound == 0) {
					warnhtml =	'<div class="alert alert-warning" role="alert">'+
												'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
												'<span class="sr-only">Error:</span>'+
												'<?php echo get_phrase('no_more_files'); ?>'+'!'+
											'</div>';
					eval('$("#'+iddiv+'")'+'.'+position+'(warnhtml)');

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
					dtimes = JSON.parse(data.response.docs[0].times_t[0]);
					// console.log(dtimes);

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
					newdividn = iddiv+'-'+newdivid;

					divclone = $('#'+iddiv).clone(true);

					divclone.removeClass('panel-default');
					divclone.addClass('panel-info');
					divclone.attr('id', newdividn);
					divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> ' + dsource + ' | ' + dfstartdate + ' - ' + dfenddate);
					divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
					divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
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
					// divclone.children('.panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
					divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('src', dmediaurl);
					divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('id', iddiv.replace('div', 'paudio')+'-'+newdivid);
					divclone.children('.panel-body').children('.col-lg-12').children('.ptext').addClass('noscrolled');
					divclone.children('.panel-body').children('.col-lg-12').children('.ptext').attr('id', iddiv.replace('div', 'ptext')+'-'+newdivid);
					divclone.children('.panel-body').children('.col-lg-12').children('.ptext').html(null);

					eval('$("#'+iddiv+'")'+'.'+position+'(divclone)');

					addtimes('#'+iddiv.replace('div', 'ptext')+'-'+newdivid, dtimes);

					scrolltokeyword();
				}
			});
		};

		function addtimes(idptext, times) {
			$.each(times, function(index, val1) {
				$.each(val1.words, function(index, val2) {
					wbegin = parseFloat(val2.begin);
					wend = parseFloat(val2.end);
					wdur = String(wend - wbegin).slice(0, 5);
					wspan = '<span data-dur="'+wdur+'" data-begin="'+val2.begin+'">'+val2.word+'</span> ';
					$(idptext).append(wspan);
				});
			});
		};

		function scrolltokeyword() {
			ptexts = $('.ptext.text-justify.noscrolled');
			ptextsl = ptexts.length;
			$.each(ptexts, function(index, val) {
				cpid = $(val).attr('id');
				scpid = '#'+cpid;

				keywordxarr = [];
				kc = 0;
				$.each(keywordarr, function(index, valk) {
					str = '<span[^>]+>'+valk+'<\/span> ';
					keywordxarr.push(str);
					kc++;
				});
				keywordrgx = keywordxarr.join('');
				rgxkw = new RegExp(keywordrgx, "ig");

				pbodyhtml = $(val).html();
				found = pbodyhtml.match(rgxkw);

				if (found != null) {
					cfound = found.length;
					$.each(found, function(index, val) {
						strreplace = val.replace(/<span /, '<span class="fkword" ');

						strreplace = strreplace.replace(/<span data-dur/g, '<span class="kword" data-dur');
						pbodyhtml = pbodyhtml.replace(val, strreplace);
					});
					$(val).html(pbodyhtml);

					keywfound = $(scpid+' > .fkword');
					qtkwf = keywfound.length;
					idnumb = cpid.replace(/[a-zA-Z]/g, '');
					$('#tkeyfound'+idnumb).text(qtkwf);
					$(val).scrollTo(keywfound);
					$(val).removeClass('noscrolled');

					fkeywfound = keywfound[0];
					fkeywfoundtime = parseInt($(fkeywfound).attr('data-begin')) - 0.3;
					$('#paudio'+idnumb)[0].currentTime = fkeywfoundtime;
				}
			});
		};

		function startread(idpaudio, idptext, audiotime = 0) {
			$('#'+idpaudio)[0].currentTime = audiotime;

			var args = {
				text_element: document.getElementById(idptext),
				audio_element: document.getElementById(idpaudio),
				autofocus_current_word: document.getElementById('autofocus-current-word').checked
			};

			// console.log(args);

			ReadAlong.init(args);
		};

		$('audio').bind('contextmenu', function() { return false; });

		if ($('#back-to-top').length) {
			var scrollTrigger = 1000,
			backToTop = function() {
				var scrollTop = $(window).scrollTop();
				if (scrollTop > scrollTrigger) {
					$('#back-to-top').fadeIn('fast');
				} else {
					$('#back-to-top').fadeOut('fast');
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
		};

		$(document).on('click', '.loadprevious', function(event) {
			loadpn('previous', $(this));

			// loadp = $(this);
			// loadp.children('i').css('display', 'inline-block');

			// iddiv = $(this).attr('data-iddiv');
			// iddivn = Number(iddiv.replace('div', ''));
			// idsource = $(this).attr('data-idsource');
			// startdate = $(this).attr('data-startdate');

			// $.get('<?php echo base_url('pages/get_radio_novo/')?>' + idsource + '/' + encodeURI(startdate) +'/previous', function(data) {
			// 	loadp.children('i').css('display', 'none');
			// 	numfound = data.response.numFound;
			// 	if (numfound == 0) {
			// 		warnhtml =	'<div class="alert alert-warning" role="alert">'+
			// 						'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
			// 						'<span class="sr-only">Error:</span>'+
			// 						'<?php echo get_phrase('no_more_files'); ?>'+'!'+
			// 					'</div>';
			// 		$('#'+iddiv).after(warnhtml);

			// 		setTimeout(function() {
			// 			$('div.alert.alert-warning').fadeOut('slow');
			// 		}, 3000);
			// 	} else {
			// 		did = data.response.docs[0].id_i;
			// 		dsourceid = data.response.docs[0].source_id_i;
			// 		dsource = data.response.docs[0].source_s;
			// 		dmediaurl = data.response.docs[0].mediaurl_s;
			// 		dstartdate = data.response.docs[0].starttime_dt;
			// 		denddate = data.response.docs[0].endtime_dt;
			// 		dcontent = data.response.docs[0].content_t[0];
			// 		dtimes = JSON.parse(data.response.docs[0].times_t[0]);
			// 		console.log(dtimes);

			// 		var sd = new Date(dstartdate);
			// 		var sday = sd.getDate();
			// 		var sday = ('0' + sday).slice(-2);
			// 		var smonth = (sd.getMonth() + 1);
			// 		var smonth = ('0' + smonth).slice(-2);
			// 		var syear = sd.getFullYear();
			// 		var shour = sd.getHours();
			// 		var shour = ('0' + shour).slice(-2);
			// 		var sminute = sd.getMinutes();
			// 		var sminute = ('0' + sminute).slice(-2);
			// 		var ssecond = sd.getSeconds();
			// 		var ssecond = ('0' + ssecond).slice(-2);
			// 		var dfstartdate = sday+'/'+smonth+'/'+syear+' '+shour+':'+sminute+':'+ssecond;

			// 		var ed = new Date(denddate);
			// 		var eday = ed.getDate();
			// 		var eday = ('0' + eday).slice(-2);
			// 		var emonth = (ed.getMonth() + 1);
			// 		var emonth = ('0' + emonth).slice(-2);
			// 		var eyear = ed.getFullYear();
			// 		var ehour = ed.getHours();
			// 		var ehour = ('0' + ehour).slice(-2);
			// 		var eminute = ed.getMinutes();
			// 		var eminute = ('0' + eminute).slice(-2);
			// 		var esecond = ed.getSeconds();
			// 		var esecond = ('0' + esecond).slice(-2);
			// 		var dfenddate = eday+'/'+emonth+'/'+eyear+' '+ehour+':'+eminute+':'+esecond;

			// 		newdivid += 1;
			// 		newdividn = iddiv+'-'+newdivid;
			// 		console.log(newdividn);

			// 		divclone = $('#'+iddiv).clone(true);
			// 		console.log(divclone);

			// 		divclone.removeClass('panel-default');
			// 		divclone.addClass('panel-info');
			// 		divclone.attr('id', newdividn);
			// 		divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> ' + dsource + ' | ' + dfstartdate + ' - ' + dfenddate);
			// 		divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
			// 		divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
			// 		divclone.children('panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-startdate', dstartdate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-enddate', denddate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-startdate', dstartdate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-enddate', denddate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-iddoc', did);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb') + '-' + newdivid);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-iddoc', did);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-startdate', dfstartdate);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-enddate', dfenddate);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('src', dmediaurl);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('id', iddiv.replace('div', 'paudio')+'-'+newdivid);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.ptext').addClass('noscrolled');
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.ptext').attr('id', iddiv.replace('div', 'ptext')+'-'+newdivid);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.ptext').html(null);

			// 		$('#'+iddiv).after(divclone);

			// 		addtimes('#'+iddiv.replace('div', 'ptext')+'-'+newdivid, dtimes);

			// 		scrolltokeyword();
			// 	}
			// });
		});

		$(document).on('click', '.loadnext', function(event) {
			loadpn('next', $(this));

			// loadp = $(this);
			// loadp.children('i').css('display', 'inline-block');

			// iddiv = $(this).attr('data-iddiv');
			// iddivn = Number(iddiv.replace('div', ''));
			// idsource = $(this).attr('data-idsource');
			// startdate = $(this).attr('data-enddate');

			// $.get('<?php echo base_url('pages/get_radio_novo/')?>'+idsource+'/'+encodeURI(startdate)+'/next', function(data) {
			// 	loadp.children('i').css('display', 'none');
			// 	numfound = data.response.numFound;
			// 	if (numfound == 0) {
			// 		warnhtml =	'<div class="alert alert-warning" role="alert">'+
			// 						'<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> '+
			// 						'<span class="sr-only">Error:</span>'+
			// 						'<?php echo get_phrase('no_more_files'); ?>'+'!'+
			// 					'</div>';
			// 		$('#'+iddiv).before(warnhtml);

			// 		setTimeout(function() {
			// 			$('div.alert.alert-warning').fadeOut('slow');
			// 		}, 3000);
			// 	} else {
			// 		did = data.response.docs[0].id_i;
			// 		dsourceid = data.response.docs[0].source_id_i;
			// 		dsource = data.response.docs[0].source_s;
			// 		dmediaurl = data.response.docs[0].mediaurl_s;
			// 		dstartdate = data.response.docs[0].starttime_dt;
			// 		denddate = data.response.docs[0].endtime_dt;
			// 		dcontent = data.response.docs[0].content_t[0];
			// 		dtimes = JSON.parse(data.response.docs[0].times_t[0]);

			// 		var sd = new Date(dstartdate);
			// 		var sday = sd.getDate();
			// 		var sday = ('0' + sday).slice(-2);
			// 		var smonth = (sd.getMonth() + 1);
			// 		var smonth = ('0' + smonth).slice(-2);
			// 		var syear = sd.getFullYear();
			// 		var shour = sd.getHours();
			// 		var shour = ('0' + shour).slice(-2);
			// 		var sminute = sd.getMinutes();
			// 		var sminute = ('0' + sminute).slice(-2);
			// 		var ssecond = sd.getSeconds();
			// 		var ssecond = ('0' + ssecond).slice(-2);
			// 		var dfstartdate = sday+'/'+smonth+'/'+syear+' '+shour+':'+sminute+':'+ssecond;

			// 		var ed = new Date(denddate);
			// 		var eday = ed.getDate();
			// 		var eday = ('0' + eday).slice(-2);
			// 		var emonth = (ed.getMonth() + 1);
			// 		var emonth = ('0' + emonth).slice(-2);
			// 		var eyear = ed.getFullYear();
			// 		var ehour = ed.getHours();
			// 		var ehour = ('0' + ehour).slice(-2);
			// 		var eminute = ed.getMinutes();
			// 		var eminute = ('0' + eminute).slice(-2);
			// 		var esecond = ed.getSeconds();
			// 		var esecond = ('0' + esecond).slice(-2);
			// 		var dfenddate = eday+'/'+emonth+'/'+eyear+' '+ehour+':'+eminute+':'+esecond;

			// 		newdivid += 1;
			// 		newdividn = iddiv+'-'+newdivid;

			// 		divclone = $('#'+iddiv).clone(true);

			// 		divclone.removeClass('panel-default');
			// 		divclone.addClass('panel-info');
			// 		divclone.attr('id', newdividn);
			// 		divclone.children('.panel-heading').children('.labeltitle').html('<i class="fa fa-bullhorn fa-fw"></i> ' + dsource + ' | ' + dfstartdate + ' - ' + dfenddate);
			// 		divclone.children('.panel-heading').children('.labeltitle').children('.fa.fa-search.fa-fw').detach();
			// 		divclone.children('.panel-heading').children('.labeltitle').children('.sqtkwf').detach();
			// 		divclone.children('panel-body').children('.row').children('.pbody').attr('id', iddiv.replace('div', 'pbody') + '-' + newdivid);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-iddiv', newdividn);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-startdate', dstartdate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadprevious').attr('data-enddate', denddate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-iddiv', newdividn);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-startdate', dstartdate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.loadnext').attr('data-enddate', denddate);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('data-iddoc', did);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').attr('disabled', true);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-danger').addClass('disabled');
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').attr('disabled', true);
			// 		divclone.children('.panel-heading').children('.btn-toolbar').children('.btn-primary').addClass('disabled');
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('id', iddiv.replace('div', 'cb') + '-' + newdivid);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-iddoc', did);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-startdate', dfstartdate);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').attr('data-enddate', dfenddate);
			// 		divclone.children('.panel-heading').children('label.pull-left').children('.cbjoinfiles').prop("checked", false);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('src', dmediaurl);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.paudio').children('audio').attr('id', iddiv.replace('div', 'paudio')+'-'+newdivid);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.ptext').addClass('noscrolled');
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.ptext').attr('id', iddiv.replace('div', 'ptext')+'-'+newdivid);
			// 		divclone.children('.panel-body').children('.col-lg-12').children('.ptext').html(null);

			// 		$('#'+iddiv).before(divclone);

			// 		addtimes('#'+iddiv.replace('div', 'ptext')+'-'+newdivid, dtimes);

			// 		scrolltokeyword();
			// 	}
			// });
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
					$('#joindiv').fadeIn('fast');
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
				if (filestojoin.length == 1) {
					$('#joinbtn').addClass('disabled');
					$('#joinbtn').attr('disabled', true);
					joinfiles = false;
				} else if (filestojoin.length == 0) {
					$('#joinbtn').addClass('disabled');
					$('#joinbtn').attr('disabled', true);
					$('#fileslist').empty();
					$('#joindiv').fadeOut('fast');
					joinfiles = false;
				}
			}
		});

		$(document).on('click', '#joinbtn', function(event) {
			jbtn = $(this);
			jidclient = jbtn.attr('data-idclient');
			jidkeyword = jbtn.attr('data-idkeyword');

			$('#jids_doc').val(filestojoin);
			$('#jid_client').val(jidclient);
			$('#jid_keyword').val(jidkeyword);

			if (joinfiles) {
				document.getElementById('joinform').submit();
				$('#joindiv').fadeOut('fast');
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

			$.post('<?php echo base_url("pages/discard_doc_radio_novo")?>',
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
						window.location = '<?php echo base_url("pages/index_radio_novo")?>';
					}
				}
			);
		});

		$(document).on('click', '.desativado', function() {
			$(this).css('overflowY', 'auto');
		})

		$(document).on('click', 'span', function(){
			ptextid = $(this).parent('.ptext').attr('id');
			paudioid = 'paudio'+ptextid.replace(/[a-zA-Z]/g, '');
			spantime = $(this).attr('data-begin');

			startread(paudioid, ptextid, spantime);
			$('#'+paudioid)[0].play();
			// $('#'+ptextid).css('overflowY', 'auto');
		});

		$(document).on('mouseleave', '.ptext', function() {
			// $(this).css('overflowY', 'hidden');

			ptextid = $(this).attr('id');
			paudioid = 'paudio'+ptextid.replace(/[a-zA-Z]/g, '');
			// $('#'+ptextid).css('overflowY', 'hidden');
			$('#'+paudioid)[0].pause();
		});

		// $(document).on('click', 'audio', function(event) {
		// 	// console.log($(this));
		// 	audioid = $(this).attr('id');
		// 	idn = audioid.replace(/[a-zA-Z]/g, '');
		// 	textid = 'ptext'+idn;
		// 	if ($(this)[0].paused) {
		// 		// console.log('audio playing!');
		// 		startread(audioid, textid);
		// 	} else {
		// 		// console.log('audio paused!');
		// 	}
		// });

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
						$.post('get_radio_novo_keyword_texts',
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
		});
	</script>
