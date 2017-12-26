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
		
		.kwfound{
			color: white;
			background-color: red;
			font-size: 110%;
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
					<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
						<div class="panel-heading text-center">
							<label class="pull-left" style="font-weight: normal"><input type="checkbox" id="<?php echo 'cb'.$divcount;?>"> <?php echo get_phrase('join');?></label>
							
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

								<button type="button" class="btn btn-danger btn-xs" data-iddoc="<?php echo $sid?>" data-idkeyword="<?php echo $id_keyword;?>" data-idclient="<?php echo $id_client;?>"><?php echo get_phrase('discard');?></button>

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
							<div class="row">
								<div class="col-lg-12">
									<audio class="center-block" style="width: 100%" src="<?php echo $smediaurl; ?>" controls></audio>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12 pbody" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
									<?php
									if (!empty($keyword_selected)) {
										$fulltext = (string)$stext;
										//$fulltext = preg_replace("/\w*?".preg_quote($keyword_selected)."\w*/i", " <strong class=\"str$divcount\" style=\"color: white; background-color: red; font-size: 110%;\">$keyword_selected</strong>", $fulltext);
										$fulltext = preg_replace("/\w*?".preg_quote($keyword_selected)."\w*/i", ' <strong class="kwfound">'.$keyword_selected.'</strong>', $fulltext); ?>
										<p id="<?php echo 'ptext'.$divcount; ?>" class="ptext text-justify"><?php echo (string)$fulltext;?></p>
									<?php } else { ?>
										<p id="<?php echo 'ptext'.$divcount; ?>" class="ptext text-justify"><?php echo (string)$stext;?></p>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<script type="text/javascript">
				var newdivid = 0;

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

				$('.loadprevious').click(function(event) {
					loadp = $(this);
					loadp.children('i').css('display', 'block');
					idsource = $(this).attr('data-idsource');
					startdate = $(this).attr('data-startdate');
					$.get('<?php echo base_url('pages/getnext_radio_knewin/')?>' + idsource + '/' + encodeURI(startdate) +'/previous', function(data) {
						loadp.children('i').css('display', 'none');
						console.log(data);
					});
				});
				
				$('.loadnext').click(function(event) {
					loadp = $(this);
					loadp.children('i').css('display', 'inline-block');

					iddiv = $(this).attr('data-iddiv');
					// newdivid = Number(iddiv.replace('div', '')) + 1;
					idsource = $(this).attr('data-idsource');
					startdate = $(this).attr('data-enddate');
					
					$.get('<?php echo base_url('pages/get_radio_knewin/')?>' + idsource + '/' + encodeURI(startdate) +'/next', function(data) {
						console.log(data);
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
						loadp.children('i').css('display', 'none');

						divclone = $('#'+iddiv).clone();
						console.log(divclone);
						
						// divclone[0].classList.remove = 'panel-default';
						// divclone[0].classList[1] = 'panel-info';
						divclone.removeClass('panel-default');
						divclone.addClass('panel-info');
						divclone[0].id = iddiv + '-' + newdivid;
						divclone[0].children[1].children[1].children[0].id = iddiv.replace('div', 'pbody') + '-' + newdivid;
						// divclone[0].children[0].children[1].innerText = dsource + ' | ' + dfstartdate + ' - ' + dfenddate;
						divclone.children('.panel-heading').children('label').text('some text');
						// divclone[0].children[0].children[1].innerHTML =  '<i class="fa fa-television fa-fw"></i> ' + dsource + ' | ' + dfstartdate + ' - ' + dfenddate;
						divclone[0].children[1].children[0].children[0].children[0].src = dmediaurl;
						divclone[0].children[1].children[1].children[0].children[0].innerText = dcontent;
						console.log(divclone)

						$('#'+iddiv).before(divclone);
					});
				});
				
				$(document).ready(function() {
					jQuery.fn.scrollTo = function(elem) {
						$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
						return this;
					}
					
					if($('.<?php echo 'str'.$divcount;?>').length != 0) {
						$('.pbody').scrollTo('.kwfound');
					}
					
					$('.pbody').css('overflowY', 'hidden');
					
					$('.pbody').click(function() {
						$(this).css('overflowY', 'auto');
					})
					
					$('.pbody').hover(function() {
						/*do nothing*/
					}, function() {
						$(this).css('overflowY', 'hidden');
					});
					
					ptexts = $('.ptext.text-justify')
					$.each(ptexts, function(index, val) {
						cpid = $(val).attr('id');
						
						qtkwf = $('#'+cpid+'> .kwfound').length
						// console.log(qtkwf);
						$(val)[0].parentElement.parentElement.parentElement.parentElement.children[0].children[1].children[1].innerText = qtkwf;
					});
					

				});
			</script>
		</div>