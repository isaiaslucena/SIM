	<?php
		defined('BASEPATH') OR exit('No direct script access allowed');

		$query = base64_encode($searchresult->responseHeader->params->json);
		$jquery = json_decode($searchresult->responseHeader->params->json)->query;
		$searchkwquery = strpos($jquery, 'text_t:');
		$searchikwquery = strpos($jquery, 'content_t:');
		if (is_int($searchkwquery)) {
			$querykw = substr($jquery, 8, -1);
			$keyword = $querykw;
		} else if (is_int($searchikwquery)) {
			$querykw = substr($jquery, 11, -1);
			$keyword = $querykw;
		} else if (!isset($keyword)) {
			$keyword = '';
		}

		$searchtime = (int)$searchresult->responseHeader->QTime;
		$totalfound = (int)$searchresult->response->numFound;
		$totalpages = ceil($totalfound/10);
		$firstpage = (int)$searchresult->response->start;
		if ($totalpages >= 4 ) {
			$pageselectedend = $pageselected + 3;
		} else {
			$pageselectedend = $pageselected;
		}
	?>

		<style type="text/css">
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

		<!-- pagination -->
		<div id="rowpagination" class="row">
			<div class="col-sm-6 col-md-6 col-lg-6">
				<br>
				<span id="searchrstart" class="text-muted"><?php echo get_phrase('search_time').': '.$searchtime ?>ms</span><br>
				<?php if ($totalfound > 10) { ?>
					<span class="text-muted"><?php echo get_phrase('showing').' 10 '.get_phrase('of').' '.$totalfound.' '.get_phrase('found') ?></span>
				<?php } else { ?>
					<span class="text-muted"><?php echo get_phrase('showing').' '.$totalfound.' '.get_phrase('found') ?></span>
				<?php }?>
			</div>
			<div class="col-sm-6 col-md-6 col-lg-6">
				<?php if ($totalfound > 10) { ?>
					<ul class="pagination pull-right">
						<?php
							if ($firstpage == 0) { ?>
								<li class="disabled">
									<a aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
							<?php } else {
								$totalpagesstart = $firstpage / 10;
								$startff = $firstpage;
								$startf = $startff - 10; ?>
								<li>
									<a href="<?php echo base_url('pages/search_result/radio_knewin/'.$totalpagesstart.'/'.$query.'/'.$startf)?>" aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
							<?php } ?>
							</li>
						<?php
						if ($pageselected == $totalpages) { ?>
							<li>
								<a href="<?php echo base_url('pages/search_result/radio_knewin/1/'.$query.'/0')?>">1</a>
							</li>
							<li class="disabled">
								<a>...<span class="sr-only"></span></a>
							</li>
							<?php $pageselectedend = $pageselected - 1;
							$pageselectstart = $pageselected - 3;
							$startf = $firstpage;
							for ($page=$pageselectstart; $page <= $pageselectedend ; $page++) {
								$startff = $page.'0';
								$startf = $startff - 10;
								 if ($pageselected == $page) { ?>
								<li class="active">
									<a><?php echo $page; ?></a>
								<?php } else { ?>
								<li>
									<a href="<?php echo base_url('pages/search_result/radio_knewin/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
								<?php } ?>
								</li>
							<?php }
						} else {
							$startf = $firstpage;
							for ($page=$pageselected; $page <= $pageselectedend ; $page++) {
								if ($page == $totalpages) { ?>
									<li class="active">
									<?php break 1;
								} else {
									$startff = $page.'0';
									$startf = $startff - 10;
									if ($pageselected == $page) { ?>
										<li class="active">
										<a><?php echo $page; ?></a>
									<?php } else { ?>
										<li>
										<a href="<?php echo base_url('pages/search_result/radio_knewin/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
									<?php }
								} ?>
								</li>
							<?php }
						} ?>
						<?php
							if ($pageselected == $totalpages) { ?>
							<li class="active">
							<a><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } else {
							$totalpagesend = ($totalpages * 10) - 10; ?>
							<li class="disabled">
								<a>...<span class="sr-only"></span></a>
							</li>
							<li>
							<a href="<?php echo base_url('pages/search_result/radio_knewin/'.$totalpages.'/'.$query.'/'.$totalpagesend)?>"><?php echo $totalpages?><span class="sr-only"></span></a>
							<?php } ?>
							</li>
						<?php
						if ($pageselected == $totalpages) { ?>
							<li class="disabled">
							<a aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
						<?php } else {
							$page = $pageselected + 1;
							$startff = $firstpage;
							$startf = $startff + 10; ?>
							<li>
							<a href="<?php echo base_url('pages/search_result/radio_knewin/'.$page.'/'.$query.'/'.$startf)?>" aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
						<?php }
						?>
						</li>
					</ul>
				<?php }  ?>
			</div>
		</div>

		<!-- content -->
		<div id="rowcontent" class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<?php if (!empty($keyword)) { ?>
					<div class="panel panel-default">
				<?php } ?>
				<?php if (!empty($keyword)) { ?>
					<div class="panel-heading">
						<i class="fa fa-key fa-fw"></i> <?php echo $keyword;?>
					</div>
				<?php } ?>
				<?php if (!empty($keyword)) { ?>
					<div class="panel-body">
				<?php } ?>
				<?php if (empty($keyword)) {
					if (isset($searchresult->response->docs[0]->hash_s)) {
						$stories = $searchresult->response->docs;
						$countf = 0;
						foreach ($stories as $story) {
							$shash = $story->hash_s;
							$staskidcreator = $story->taskidcreator_l;
							$sdate = $story->date_l;
							$sinsertion = $story->insertiondate_l;
							// $sstartdate = $story->startdate_l;
							// $senddate = $story->enddate_l;
							$ssource = $story->source_s;
							$sname = $story->name_s;

							$timezone = new DateTimeZone('UTC');
							$sd = new Datetime($found->starttime_dt, $timezone);
							$ed = new Datetime($found->endtime_dt, $timezone);
							$newtimezone = new DateTimeZone('America/Sao_Paulo');
							$sd->setTimezone($newtimezone);
							$ed->setTimezone($newtimezone);
							$sstartdate = $sd->format('d/m/Y H:i:s');
							$senddate = $ed->format('d/m/Y H:i:s');

							if (isset($story->text_t[0])) {
								$stext = $story->text_t[0];
							} ?>

							<div class="panel panel-default">
								<div class="panel-heading text-center">
									<i class="fa fa-bullhorn fw"></i>
									 <?php echo $ssource." | ".$sstartdate." - ".$senddate;?>
								</div>
								<!-- <p class="text-center"></p> -->
								<div class="panel-body" style="height: 300px; overflow-y: hidden;">
									<p class="text-justify">
										<?php
											if (isset($stext)) {
												echo $stext;
											} else {
												echo "No text.";
											}
										?>
									</p>
								</div>
							</div>
						<?php } ?>
						<script type="text/javascript">
							$('.panel-body').click(function() {
								$(this).css('overflowY', 'auto');
							});
							$('.panel-body').hover(function() {
								/*do nothing*/
							}, function() {
								$('.panel-body').css('overflowY', 'hidden');
							});
						</script>
					<?php } else {
						$stories = $searchresult->response->docs;
						$countf = 0;
						$divcount = 0;
						$icount = 0;
						foreach ($stories as $story) {
							$divcount++;
							$sid = $story->id_i;
							$sidsource = $story->id_source_i;
							$smediaurl = $story->mediaurl_s;
							$ssource = $story->source_s;

							$timezone = new DateTimeZone('UTC');
							$sd = new Datetime($story->starttime_dt, $timezone);
							$ed = new Datetime($story->endtime_dt, $timezone);
							$newtimezone = new DateTimeZone('America/Sao_Paulo');
							$sd->setTimezone($newtimezone);
							$ed->setTimezone($newtimezone);
							$sstartdate = $sd->format('d/m/Y H:i:s');
							$senddate = $ed->format('d/m/Y H:i:s');

							if (isset($story->content_t[0])) {
								$stext = $story->content_t[0];
							} ?>

							<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
								<div class="panel-heading text-center">
									<label class="pull-left" style="font-weight: normal">
										<input type="checkbox" class="cbjoinfiles" id="<?php echo 'cb'.$divcount;?>" data-iddoc="<?php echo $sid?>" data-idsource="<?php echo $sidsource?>" data-source="<?php echo $ssource?>" data-startdate="<?php echo $sstartdate; ?>" data-enddate="<?php echo $senddate; ?>"> <?php echo get_phrase('join');?>
									</label>

									<i class="fa fa-bullhorn fw"></i> <?php echo $ssource." | ".$sstartdate." - ".$senddate; ?>

									<div class="btn-toolbar pull-right">
										<button class="btn btn-warning btn-xs loadprevious" data-iddiv="<?php echo 'div'.$divcount;?>" data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $story->starttime_dt?>" data-enddate="<?php echo $story->endtime_dt?>" data-position="previous">
											<i id="<?php echo 'iload'.$icount;?>" style="display: none" class="fa fa-refresh fa-spin"></i>
											<?php echo get_phrase('previous');
											$icount++; ?>
										</button>

										<button class="btn btn-warning btn-xs loadnext" data-iddiv="<?php echo 'div'.$divcount;?>" data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $story->starttime_dt?>" data-enddate="<?php echo $story->endtime_dt?>" data-position="next">
											<i id="<?php echo 'iload'.$icount;?>" style="display: none;" class="fa fa-refresh fa-spin"></i>
											<?php echo get_phrase('next'); ?>
										</button>

										<button type="button" disabled class="btn btn-danger btn-xs discarddoc disabled">
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
											<input type="hidden" name="id_keyword" value="">
											<input type="hidden" name="id_client" value="<?php echo $id_client;?>">
											<input type="hidden" name="client_selected" value="">
										</form>
									</div>
								</div>

								<div class="panel-body">
									<div class="row audioel">
										<div class="col-sm-12 col-md-12 col-lg-12">
											<audio class="center-block" style="width: 100%" src="<?php echo $smediaurl; ?>" controls></audio>
										</div>
									</div>
									<div class="row textel">
										<div class="col-sm-12 col-md-12 col-lg-12 pbody" id="<?php echo 'pbody'.$divcount;?>">
											<p id="<?php echo 'ptext'.$divcount; ?>" class="text-justify ptext" style="height: 300px; overflow-y: auto">
												<?php echo (string)$stext; ?>
											</p>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						<script type="text/javascript">
							$('.ptext').click(function() {
								$(this).css('overflowY', 'auto');
							});
							$('.ptext').hover(function() {
								/*do nothing*/
							}, function() {
								$('.ptext').css('overflowY', 'hidden');
							});
						</script>
					<?php }
				} else {
					if (isset($searchresult->response->docs[0]->hash_s)) {
						$stories = $searchresult->response->docs;
						$divcount = 0;
						$icount=0;
						foreach ($stories as $story) {
							$divcount++;
							$icount++;

							$shash = $story->hash_s;
							$staskidcreator = $story->taskidcreator_l;

							$stext = $story->text_t[0];
							$ssource = $story->source_s;
							$sname = $story->name_s;

							?>
							<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
								<div class="panel-heading text-center">
									<?php if (!empty($keyword)) { ?>
									<label class="pull-left" style="font-weight: normal">
										<input type="checkbox" class="cbjoinfiles" id="<?php echo 'cb'.$divcount;?>" data-iddoc="<?php echo $sid?>" data-idsource="<?php echo $sidsource?>" data-source="<?php echo $ssource?>" data-startdate="<?php echo $sstartdate; ?>" data-enddate="<?php echo $senddate; ?>" data-idclient="<?php echo $id_client;?>" data-idkeyword="<?php echo $id_keyword;?>"> <?php echo get_phrase('join');?>
									</label>

									<label>
										<i class="fa fa-search fa-fw"></i>
										<span id="<?php echo 'qtkwfid'.$divcount;?>"></span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<i class="fa fa-television fa-fw"></i>
										<?php echo $ssource.": ".$sname." | ".$sststartdate." - ".$sstenddate;?>
									</label>
									<?php } else {?>
									<i class="fa fa-television fw"></i> <?php echo $ssource.": ".$sname." | ".$sststartdate." - ".$sstenddate;?>
									<?php } ?>
								</div>
								<p class="text-center">
									<!--<audio id="audiotext" style="width: 100%;" src="<?php //echo base_url("assets/dir/video");?>" controls></audio>-->
								</p>
								<div class="panel-body" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
									<?php
									if (!empty($keyword)) {
										$fulltext = (string)$stext;
										$fulltext = preg_replace("/\w*?".preg_quote($keyword)."\w*/i", " <strong class=\"str$divcount\" style=\"color: white; background-color: red; font-size: 110%;\">$keyword</strong>", $fulltext); ?>
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

								<?php if (!empty($keyword)) { ?>
								var qtkwf = $('<?php echo '.str'.$divcount?>').length;
								$('<?php echo '#qtkwfid'.$divcount;?>').text(qtkwf);
								<?php } ?>
							</script>
						<?php }
					} else {
						$stories = $searchresult->response->docs;
						$divcount = 0;
						$icount=0;
						foreach ($stories as $story) {
							$divcount++;
							$icount++;

							$sid = $story->id_i;
							$sidsource = $story->id_source_i;
							$smediaurl = $story->mediaurl_s;
							$ssource = $story->source_s;

							$timezone = new DateTimeZone('UTC');
							$sd = new Datetime($story->starttime_dt, $timezone);
							$ed = new Datetime($story->endtime_dt, $timezone);
							$newtimezone = new DateTimeZone('America/Sao_Paulo');
							$sd->setTimezone($newtimezone);
							$ed->setTimezone($newtimezone);
							$sstartdate = $sd->format('d/m/Y H:i:s');
							$senddate = $ed->format('d/m/Y H:i:s');

							if (isset($story->content_t[0])) {
								$stext = $story->content_t[0];
							}

							if (!isset($id_client)) {
								$id_client = 0;
							}

							?>

							<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
								<div class="panel-heading text-center">
									<label class="pull-left" style="font-weight: normal">
										<input type="checkbox" class="cbjoinfiles" id="<?php echo 'cb'.$divcount;?>" data-iddoc="<?php echo $sid?>" data-idsource="<?php echo $sidsource?>" data-source="<?php echo $ssource?>" data-startdate="<?php echo $sstartdate; ?>" data-enddate="<?php echo $senddate; ?>" data-idclient="<?php echo $id_client;?>"> <?php echo get_phrase('join');?>
									</label>

									<label class="labeltitle">
										<i class="fa fa-search fa-fw"></i>
										<span id="<?php echo 'qtkwfid'.$divcount;?>"></span>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<i class="fa fa-bullhorn fa-fw"></i>
										<?php echo $ssource." | ".$sstartdate." - ".$senddate;?>
									</label>

									<div class="btn-toolbar pull-right">
										<button class="btn btn-warning btn-xs loadprevious" data-iddiv="<?php echo 'div'.$divcount;?>" data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $story->starttime_dt?>" data-enddate="<?php echo $story->endtime_dt?>" data-position="previous">
											<i id="<?php echo 'iload'.$icount;?>" style="display: none" class="fa fa-refresh fa-spin"></i>
											<?php echo get_phrase('previous');
											$icount++; ?>
										</button>

										<button class="btn btn-warning btn-xs loadnext" data-iddiv="<?php echo 'div'.$divcount;?>" data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $story->starttime_dt?>" data-enddate="<?php echo $story->endtime_dt?>" data-position="next">
											<i id="<?php echo 'iload'.$icount;?>" style="display: none;" class="fa fa-refresh fa-spin"></i>
											<?php echo get_phrase('next'); ?>
										</button>

										<button type="submit" form="<?php echo 'form'.$divcount;?>" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
										<form id="<?php echo 'form'.$divcount;?>" style="all: unset;" action="<?php echo base_url('pages/edit_knewin');?>" target="_blank" method="POST">
											<input type="hidden" name="sid" value="<?php echo $sid;?>">
											<input type="hidden" name="mediaurl" value="<?php echo $smediaurl;?>">
											<input type="hidden" name="ssource" value="<?php echo $ssource;?>">
											<input type="hidden" name="sstartdate" value="<?php echo $sstartdate;?>">
											<input type="hidden" name="senddate" value="<?php echo $senddate;?>">
											<input type="hidden" name="id_keyword" value="">
											<input type="hidden" name="id_client" value="">
											<input type="hidden" name="client_selected" value="">
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
										<div class="col-lg-12" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
											<?php if (!empty($keyword)) {
												$fulltext = (string)$stext;
												$fulltext = preg_replace("/".preg_quote($keyword)."/i", "<strong class=\"str$divcount\" style=\"color: white; background-color: red; font-size: 110%;\">$keyword</strong>", $fulltext); ?>
												<p class="text-justify"><?php echo $fulltext; ?></p>
											<?php } else { ?>
												<p class="text-justify">No Text</p>
											<?php } ?>
										</div>
									</div>
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

								<?php if (!empty($keyword)) { ?>
								var qtkwf = $('<?php echo '.str'.$divcount?>').length;
								$('<?php echo '#qtkwfid'.$divcount;?>').text(qtkwf);
								<?php } ?>
							</script>
						<?php }
					}
				} ?>
				<?php if (!empty($keyword)) { ?>
					</div>s
				<?php } ?>
				<?php if (!empty($keyword)) { ?>
					</div>
				<?php } ?>
			</div>
		</div>

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

		$('#audiotext').bind('contextmenu',function() { return false; });

		$('.ptext').css('overflowY', 'hidden');

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
					console.log(divclone);

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

		$('#joinbtn').click(function(event) {
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

		var pagclone = $('#rowpagination').clone(true);
		$('#rowcontent').after(pagclone);
	</script>
