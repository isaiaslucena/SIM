<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<style type="text/css">
		#joindiv {
			position: fixed;
			bottom: 0px;
			left: 260px;
			z-index: 9999;
			display: none;
		}
		#joindiv.show {
			/* opacity: 1; */
			display: block;
		}
		#content {
			height: 2000px;
		}
		
		.kwfound{
			color: white;
			background-color: red;
			font-size: 110%;
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
			$icount=0;
			foreach ($keyword_texts->response->docs as $found) {
				$divcount++;
				$icount++;
				$sid = $found->id_i;
				$sidsource = $found->id_source_i;
				$smediaurl = $found->mediaurl_s;

				$timezone = new DateTimeZone('UTC');
				$sd = new Datetime($found->starttime_dt, $timezone);
				$ed = new Datetime($found->endtime_dt, $timezone);
				
				$newtimezone = new DateTimeZone('America/Sao_Paulo');
				$sd->setTimezone($newtimezone);
				$ed->setTimezone($newtimezone);
				$sstartdate = $sd->format('d/m/Y H:i:s');
				$senddate = $ed->format('d/m/Y H:i:s');
				
				$stext = $found->content_t[0];
				$ssource = $found->source_s; ?>
				<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default collapse in">
					<div class="panel-heading text-center">
						<label class="pull-left" style="font-weight: normal">
							<input type="checkbox" class="cbjoinfiles" id="<?php echo 'cb'.$divcount;?>" data-iddoc="<?php echo $sid?>" data-idsource="<?php echo $sidsource?>" data-source="<?php echo $ssource?>" data-startdate="<?php echo $sstartdate; ?>" data-enddate="<?php echo $senddate; ?>" data-idclient="<?php echo $id_client;?>" data-idkeyword="<?php echo $id_keyword;?>"> <?php echo get_phrase('join');?>
						</label>
						
						<label class="labeltitle">
							<i class="fa fa-search fa-fw"></i>
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
							<form id="<?php echo 'form'.$divcount;?>" style="all: unset;" action="<?php echo base_url('pages/edit_knewin');?>" target="_blank" method="POST">
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
						<div class="row audioel">
							<div class="col-lg-12">
								<audio class="center-block" style="width: 100%" src="<?php echo $smediaurl; ?>" controls></audio>
							</div>
						</div>

						<div class="row textel">
							<div class="col-lg-12 pbody" id="<?php echo 'pbody'.$divcount;?>">
								<p id="<?php echo 'ptext'.$divcount; ?>" class="text-justify ptext" style="height: 300px; overflow-y: auto">
									<?php echo (string)$stext; ?>
								</p>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>

		<div class="well well-sm" id="joindiv">
			<span id="wsource" class="center-block text-center"></span>
			<div class="list-group" style="max-height:  150px ; overflow: auto;">
				<small id="fileslist"></small>
			</div>
			<button id="joinbtn" class="btn btn-default btn-block btn-sm disabled" disabled><?php echo get_phrase('join')?></button>
			<form id="joinform" style="all: unset;" action="<?php echo base_url('pages/join_radio_knewin');?>" target="_blank" method="POST">
				<input type="hidden" id="jids_doc" name="ids_doc">
				<input type="hidden" id="jid_client" name="id_client">
				<input type="hidden" id="jid_keyword" name="id_keyword">
			</form>
		<div>

		<script type="text/javascript">
			var newdivid = 0, cksource = 0, totalpanels, totalpanelsd = 0,
			joinfiles = false, filestojoin = [];

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
			}
			
			$('.loadprevious').click(function(event) {
				loadp = $(this);
				loadp.children('i').css('display', 'inline-block');

				iddiv = $(this).attr('data-iddiv');
				iddivn = Number(iddiv.replace('div', ''));
				idsource = $(this).attr('data-idsource');
				startdate = $(this).attr('data-startdate');
				
				$.get('<?php echo base_url('pages/get_radio_knewin/')?>' + idsource + '/' + encodeURI(startdate) +'/previous', function(data) {
					// console.log(data);
					loadp.children('i').css('display', 'none');
					numfound = data.response.numFound;
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
						// newdivid = iddivn + 1;
						newdividn = iddiv + '-' + newdivid;
						// newdividn = 'div' + newdivid;

						divclone = $('#'+iddiv).clone(true);
						// console.log(divclone);
						
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
						divclone.children('.panel-body').children('.textel').children('.pbody').children('.ptext').attr('id', 'id', iddiv.replace('div', 'ptext') + '-' + newdivid);
						divclone[0].children[1].children[0].children[0].children[0].src = dmediaurl;
						divclone[0].children[1].children[1].children[0].children[0].innerText = dcontent;

						$('#'+iddiv).after(divclone);
					}
				});
			});
			
			$('.loadnext').click(function(event) {
				loadp = $(this);
				loadp.children('i').css('display', 'inline-block');

				iddiv = $(this).attr('data-iddiv');
				iddivn = Number(iddiv.replace('div', ''));
				idsource = $(this).attr('data-idsource');
				startdate = $(this).attr('data-enddate');
				
				$.get('<?php echo base_url('pages/get_radio_knewin/')?>' + idsource + '/' + encodeURI(startdate) +'/next', function(data) {
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
						// newdivid = iddivn + 1;
						newdividn = iddiv + '-' + newdivid;
						// newdividn = 'div' + newdivid;

						divclone = $('#'+iddiv).clone(true);
						// console.log(divclone);
						
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
						divclone.children('.panel-body').children('.textel').children('.pbody').children('.ptext').attr('id', 'id', iddiv.replace('div', 'ptext') + '-' + newdivid);
						divclone[0].children[1].children[0].children[0].children[0].src = dmediaurl;
						divclone[0].children[1].children[1].children[0].children[0].innerText = dcontent;

						$('#'+iddiv).before(divclone);
					}
				});
			});
			
			$('.cbjoinfiles').click(function(event) {
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
			
			$('.discarddoc').click(function(event) {
				discardbtn = $(this);
				discardbtn.children('i').css('display', 'inline-block');
				
				iddoc = discardbtn.attr('data-iddoc');
				iddiv = discardbtn.attr('data-iddiv');
				idkeyword = discardbtn.attr('data-idkeyword');
				idclient = discardbtn.attr('data-idclient');
				iduser = '<?php echo $this->session->userdata("id_user");?>';
				
				$.post('<?php echo base_url("pages/discard_doc_radio_knewin")?>',
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
							window.location = '<?php echo base_url("pages/index_tv_knewin")?>';
						}
					}
				);
			});
			
			$('.ptext').click(function() {
				$(this).css('overflowY', 'auto');
			})
			
			$('.ptext').hover(function() {
				/*do nothing*/
			}, function() {
				$(this).css('overflowY', 'hidden');
			});

			$(document).ready(function() {
				totalpanels = $('div.panel.panel-default.collapse.in').length;

				jQuery.fn.scrollTo = function(elem) {
					$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
					return this;
				}
				
				ptexts = $('.ptext.text-justify');
				$.each(ptexts, function(index, val) {
					// console.log(event);
					cpid = $(val).attr('id');
					scpid = '#'+cpid;
					keywfound = '#'+cpid+' > .kwfound';
					keyword = '<?php echo $keyword_selected; ?>';
					// idkeyword = event.target.dataset.idkeyword;
					// idpbodyt = event.target.dataset.pbodyt;
					
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
			});
		</script>
	</div>