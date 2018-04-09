	<?php
		defined('BASEPATH') OR exit('No direct script access allowed');

		$query = base64_encode($searchresult->responseHeader->params->json);
		$jquery = json_decode($searchresult->responseHeader->params->json)->query;
		$searchkwquery = strpos($jquery, '_text_:');
		if (is_int($searchkwquery)) {
			$querykw = substr($jquery, 8, -1);
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
			<div class="col-lg-6">
				<br>
				<span class="text-muted"><?php echo get_phrase('search_time').': '.$searchtime ?>ms</span><br>
				<?php if ($totalfound > 10) { ?>
					<span class="text-muted"><?php echo get_phrase('showing').' 10 '.get_phrase('of').' '.$totalfound.' '.get_phrase('found') ?></span>
				<?php } else { ?>
					<span class="text-muted"><?php echo get_phrase('showing').' '.$totalfound.' '.get_phrase('found') ?></span>
				<?php }?>
			</div>
			<div class="col-lg-6">
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
								<a href="<?php echo base_url('pages/search_result/radio/'.$totalpagesstart.'/'.$query.'/'.$startf)?>" aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a>
							<?php } ?>
							</li>
						<?php
						if ($pageselected == $totalpages) { ?>
							<li>
								<a href="<?php echo base_url('pages/search_result/radio/1/'.$query.'/0')?>">1</a>
							</li>
							<li class="disabled">
								<a>...<span class="sr-only"></span></a>
							</li>
							<?php
							$pageselectedend = $pageselected - 1;
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
									<a href="<?php echo base_url('pages/search_result/radio/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
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
								<a href="<?php echo base_url('pages/search_result/radio/'.$page.'/'.$query.'/'.$startf)?>"><?php echo $page; ?></a>
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
							<a href="<?php echo base_url('pages/search_result/radio/'.$totalpages.'/'.$query.'/'.$totalpagesend)?>"><?php echo $totalpages?><span class="sr-only"></span></a>
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
						<a href="<?php echo base_url('pages/search_result/radio/'.$page.'/'.$query.'/'.$startf)?>" aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a>
						<?php }
						?>
						</li>
					</ul>
				<?php }  ?>
			</div>
		</div>

		<!-- content -->
		<div id="rowcontent" class="row">
			<div class="col-lg-12">
				<?php if (!empty($keyword)) { ?><div class="panel panel-default"><?php } ?>
				<?php if (!empty($keyword)) { ?><div class="panel-heading"><i class="fa fa-key fa-fw"></i> <?php echo $keyword;?></div><?php } ?>
					<?php if (!empty($keyword)) { ?><div class="panel-body"><?php } ?>
						<?php
							$texts = $searchresult->response->docs;
							$divcount = 0;
							$icount=0;
							foreach ($texts as $text) {
								$divcount++;
								$icount++;
								$xmlfileid = $text->id_file_i;
								$mp3fileid = $text->id_file_mp3_i;
								if ($mp3fileid == 0) {
									$mp3fileid = $xmlfileid - 1;
								}
								$mp3 = $this->db->get_where('file',array('id_file' => $mp3fileid))->result_array();
								$xml = $this->db->get_where('file',array('id_file' => $xmlfileid))->result_array();
								$radio = $this->db->get_where('radio',array('id_radio' => $mp3[0]['id_radio']))->result_array();
								$mp3pathorig = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->path;
								$mp3pathnew = mb_substr($mp3pathorig, 16);
								$mp3filename = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->filename;
								$textinfo = $this->pages_model->text_keyword_id($xmlfileid);
								?>
								<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
									<div class="panel-heading text-center">
									<?php if (!empty($keyword)) { ?>
									<label class="pull-left" style="font-weight: normal"><input type="checkbox" id="<?php echo 'cb'.$divcount;?>" onclick="checkbox_join(<?php echo $divcount.','.$textinfo[0]['timestamp'].',\''.$textinfo[0]['state'].' - '.$textinfo[0]['radio'].' - '.date('d/m/Y - H:i:s',$textinfo[0]['timestamp']).'\','.$radio[0]['id_radio'].',0,\''.$keyword.'\','.$textinfo[0]['id_file'].','.$mp3fileid.','.$textinfo[0]['id_text'];?>)"> <?php echo get_phrase('join');?></label>
									<label><i class="fa fa-search fa-fw"></i><span id="<?php echo 'qtkwfid'.$divcount;?>"></span>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-bullhorn fa-fw"></i><?php echo $textinfo[0]['state']." - ".$textinfo[0]['radio']." - ".date("d/m/Y - H:i:s",$textinfo[0]['timestamp']);?></label>
									<?php } else {?>
										<i class="fa fa-bullhorn"></i> <?php echo $radio[0]['state']." - ".$radio[0]['name']." - ".date("d/m/Y - H:i:s",$mp3[0]['timestamp']);?>
									<?php } ?>
										<form id="form_edit" style="all: unset;" action="<?php echo base_url('pages/edit_temp');?>" target="_blank" method="POST">
											<input type="hidden" name="mp3pathfilename" value="<?php echo base_url("assets".$mp3pathnew."/".$mp3filename);?>">
											<input type="hidden" name="xmlpathfilename" value="<?php echo $xml[0]['path']."/".$xml[0]['filename'];?>">
											<input type="hidden" name="state" value="<?php echo $radio[0]['state'];?>">
											<input type="hidden" name="radio" value="<?php echo $radio[0]['name'];?>">
											<input type="hidden" name="timestamp" value="<?php echo $mp3[0]['timestamp'];?>">
											<?php if (!empty($keyword)) { ?><input type="hidden" name="keyword" value="<?php echo $keyword;?>"><?php } ?>
											<button type="submit" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
										</form>
										<?php if (!empty($keyword)) { ?>
										<span class="pull-right">&nbsp;</span>
										<button onclick="return load_file(<?php echo '\'next\',0,0,'.$textinfo[0]['timestamp'].','.$textinfo[0]['id_radio'].',\'div'.$divcount.'\',\'iload'.$icount.'\'';?>) ; return false;" class="btn btn-warning btn-xs pull-right">
											<i style="display: none" class="fa fa-refresh fa-spin" id="<?php echo 'iload'.$icount;?>"></i>
											<?php echo get_phrase('next');
											$icount++;?>
										</button>
										<span class="pull-right">&nbsp;</span>
										<button onclick="return load_file(<?php echo '\'previous\',0,0,'.$textinfo[0]['timestamp'].','.$textinfo[0]['id_radio'].',\'div'.$divcount.'\',\'iload'.$icount.'\'';?>) ; return false;" class="btn btn-warning btn-xs pull-right">
											<i style="display: none" class="fa fa-refresh fa-spin" id="<?php echo 'iload'.$icount;?>"></i>
											<?php echo get_phrase('previous');?>
										</button>
										<?php } ?>
									</div>
									<p class="text-center"><audio id="audiotext" style="width: 100%;" src="<?php echo base_url("assets/".$mp3pathnew."/".$mp3filename);?>" controls></audio></p>
									<div class="panel-body" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
										<?php
										if (!empty($keyword)) {
											$fulltext = (string)$text->text_content_txt[0];
											$fulltext = preg_replace("/\w*?".preg_quote($keyword)."\w*/i", " <strong class=\"str$divcount\" style=\"color: white; background-color: red; font-size: 110%;\">$keyword</strong>", $fulltext); ?>
										<p class="text-justify"><?php echo $fulltext;?></p>
										<?php } else { ?>
										<p class="text-justify"><?php echo (string)$text->text_content_txt[0];?></p>
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
							<?php } ?>
					<?php if (!empty($keyword)) { ?></div><?php } ?>
				<?php if (!empty($keyword)) { ?></div><?php } ?>
			</div>
		</div>
	</div>

	<div class="well well-sm" id="joindiv">
		<div class="list-group" style="max-height:  150px ; overflow:  auto;">
			<small id="fileslist"></small>
		</div>
		<form id="filesform" style="all: unset;" action="<?php echo base_url('pages/join');?>" target="_blank" method="POST">
			<input type="hidden" name="id_radio" id="ff_id_radio">
			<input type="hidden" name="id_client" id="ff_id_client">
			<input type="hidden" name="id_keyword" id="ff_id_keyword">
			<input type="hidden" name="keyword" id="ff_keyword">
			<input type="hidden" name="ff_ids_files_xml" id="ff_ids_files_xml">
			<input type="hidden" name="ff_ids_files_mp3" id="ff_ids_files_mp3">
			<input type="hidden" name="ff_ids_texts" id="ff_ids_texts">
			<input type="hidden" name="timestamp" id="ff_timestamp">
		</form>
		<button id="joinbtn" class="btn btn-default btn-block btn-sm"><?php echo get_phrase('join')?></a>
	<div>

	<script type="text/javascript">
		$('#audiotext').bind('contextmenu',function() { return false; });

		function load_file(position,idclient,idkeyword,timestamp,idradio,divid,iloadid) {
			$('#' + iloadid).css('display', 'inline-block');
			$.ajax({
				url: '<?php echo base_url('pages/load_file')?>',
				type: 'POST',
				data: {
					position: position,
					idclient: idclient,
					idkeyword: idkeyword,
					timestamp: timestamp,
					idradio: idradio,
					divid: divid
				},
				complete: function (response) {
					if (position == 'next') {
						$('#' + divid).before(response.responseText);
					}
					if (position == 'previous') {
						$('#' + divid).after(response.responseText);
					}
					$('#' + iloadid).css('display', 'none');
				},
				error: function (response) {
					alert(response.responseText)
				},
			})
			return false;
		}

		var firstidxml, firstidmp3, firstidtext;
		function checkbox_join(id,timestamp,radio,idradio,idclient,keyword,idfilexml,idfilemp3,idtext) {
			var previdsfilesxml;
			var previdsfilesmp3;
			var previdstexts;
			var previdradio = $('#ff_id_radio').val();
			if (previdradio !== '') {
				if (idradio != previdradio) {
					alert('A rádio não pode ser diferente!');
					$('#filesform').closest('form').find("input[type=hidden], textarea").val('');
					$('input[type=checkbox]').prop('checked',false);
					$('#fileslist').empty();
					$('#joindiv').removeClass('show');
					return;
				}
			}

			var aquant = $(".list-group-item").length;
			if (aquant >= 1) {
				$('#joindiv').addClass('show');
			}

			var checkboxes = $('#cb'+id);
			var verify = checkboxes.is(":checked");
			var checkboxesload = $('#cbload'+id);
			var verifyload = checkboxesload.is(":checked");
			if (verify || verifyload) {
				previdsfilesxml = $('#ff_ids_files_xml').val();
				previdsfilesmp3 = $('#ff_ids_files_mp3').val();
				previdstexts = $('#ff_ids_texts').val();
				$('#fileslist').append('<a id=acb'+ id +' class="list-group-item">' + radio +'</a>');
				$('#ff_id_radio').val(idradio);
				$('#ff_timestamp').val(timestamp);
				$('#ff_id_client').val(idclient);
				if (keyword == 0) {
					//do nothing
				} else {
					$('#ff_keyword').val(keyword);
				}
				if (previdsfilesxml === '') {
					$('#ff_ids_files_xml').val(idfilexml);
					firstidxml = idfilexml;
				} else {
					$('#ff_ids_files_xml').val(previdsfilesxml + ',' + idfilexml);
				}
				if (previdsfilesmp3 === '') {
					$('#ff_ids_files_mp3').val(idfilemp3);
					firstidmp3 = idfilemp3;
				} else {
					$('#ff_ids_files_mp3').val(previdsfilesmp3 + ',' + idfilemp3);
				}
				if (previdstexts === '') {
					$('#ff_ids_texts').val(idtext);
					firstidtext = idtext;
				} else {
					$('#ff_ids_texts').val(previdstexts + ',' + idtext);
				}
			} else if (!verify || !verifyload) {
				var removedidxml;
				var removedidmp3;
				var removedidtext;
				previdsfilesxml = $('#ff_ids_files_xml').val();
				previdsfilesmp3 = $('#ff_ids_files_mp3').val();
				previdstexts = $('#ff_ids_texts').val();
				$('#acb'+id).detach();
				if (idfilexml == firstidxml) {
					removedidxml = previdsfilesxml.replace(idfilexml+',','');
					$('#ff_ids_files_xml').val(removedidxml);
				} else {
					removedidxml = previdsfilesxml.replace(','+idfilexml,'');
					$('#ff_ids_files_xml').val(removedidxml);
				}
				if (idfilemp3 == firstidmp3) {
					removedidmp3 = previdsfilesmp3.replace(idfilemp3+',','');
					$('#ff_ids_files_mp3').val(removedidmp3);
				} else {
					removedidmp3 = previdsfilesmp3.replace(','+idfilemp3,'');
					$('#ff_ids_files_mp3').val(removedidmp3);
				}
				if (idtext == firstidtext) {
					removedidtext = previdstexts.replace(idtext+',','');
					$('#ff_ids_texts').val(removedidtext);
				} else {
					removedidtext = previdstexts.replace(','+idtext,'');
					$('#ff_ids_texts').val(removedidtext);
				}
				aquant = $(".list-group-item").length;
				if (aquant === 0) {
					$('#joindiv').removeClass('show');
				}
			}
		}

		$('#joinbtn').click(function(event) {
			var frm = $('#filesform').submit();
			$('#filesform').closest('form').find("input[type=hidden], textarea").val('');
			$('input[type=checkbox]').prop('checked',false);
			$('#fileslist').empty();
			$('#joindiv').removeClass('show');
			return false;
		});

		$(document).ready(function() {
			var pagclone = $('#rowpagination').clone(true);
			$('#rowcontent').after(pagclone);
		});
	</script>
