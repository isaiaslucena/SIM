<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<style type="text/css">
		#joindiv {
			position: fixed;
			bottom: 0px;
			left: 260px;
			z-index: 500;
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
				foreach ($keyword_texts as $text) {
					$divcount++;
					$icount++;
					$mp3fileid = $text['id_file'] - 1;
					$mp3pathorig = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->path;
					$id_radio = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->id_radio;
					$mp3pathnew = mb_substr($mp3pathorig, 16);
					$mp3filename = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->filename; ?>
					<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
						<div class="panel-heading text-center">
							<label class="pull-left" style="font-weight: normal">
								<input type="checkbox" id="<?php echo 'cb'.$divcount;?>" onclick="checkbox_join(<?php echo $divcount.','.$text['timestamp'].',\''.$text['state'].' - '.$text['radio'].' - '.date('d/m/Y - H:i:s',$text['timestamp']).'\','.$id_radio.','.$id_client.','.$id_keyword.','.$text['id_file'].','.$mp3fileid.','.$text['id_text'];?>)"> <?php echo get_phrase('join');?>
							</label>
							<label>
								<i class="fa fa-search fa-fw"></i>
								<span id="<?php echo 'qtkwfid'.$divcount;?>"></span>&nbsp;&nbsp;&nbsp;&nbsp;
								<i class="fa fa-bullhorn fa-fw"></i>
								<?php echo $text['state']." - ".$text['radio']." - ".date("d/m/Y - H:i:s",$text['timestamp']);?>
							</label>
							
							<form id="form_edit" style="all: unset;" action="<?php echo base_url('pages/edit_temp');?>" target="_blank" method="POST">
								<input type="hidden" name="mp3pathfilename" value="<?php echo base_url("assets".$mp3pathnew."/".$mp3filename);?>">
								<input type="hidden" name="xmlpathfilename" value="<?php echo $text['path']."/".$text['filename'];?>">
								<input type="hidden" name="state" value="<?php echo $text['state'];?>">
								<input type="hidden" name="radio" value="<?php echo $text['radio'];?>">
								<input type="hidden" name="timestamp" value="<?php echo $text['timestamp'];?>">
								<input type="hidden" name="id_keyword" value="<?php echo $id_keyword;?>">
								<input type="hidden" name="id_client" value="<?php echo $id_client;?>">
								<input type="hidden" name="id_file" value="<?php echo $text['id_file'];?>">
								<input type="hidden" name="id_text" value="<?php echo $text['id_text'];?>">
								<input type="hidden" name="client_selected" value="<?php echo $client_selected;?>">
								<button type="submit" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
							</form>
							
							<span class="pull-right">&nbsp;</span>
							<button onclick="discard_text(<?php echo '\'div'.$divcount.'\','.$text['id_text'].','.$id_client.','.$id_keyword.','.$id_user;?>)" class="btn btn-danger btn-xs pull-right"><?php echo get_phrase('discard');?></button>
							<span class="pull-right">&nbsp;</span>
							
							<button onclick="return load_file(<?php echo '\'next\','.$id_client.','.$id_keyword.','.$text['timestamp'].','.$text['id_radio'].',\'div'.$divcount.'\',\'iload'.$icount.'\'';?>) ; return false;" class="btn btn-warning btn-xs pull-right">
								<i style="display: none" class="fa fa-refresh fa-spin" id="<?php echo 'iload'.$icount;?>"></i>
								<?php echo get_phrase('next');
								$icount++; ?>
							</button>
							<span class="pull-right">&nbsp;</span>
							<button onclick="return load_file(<?php echo '\'previous\','.$id_client.','.$id_keyword.','.$text['timestamp'].','.$text['id_radio'].',\'div'.$divcount.'\',\'iload'.$icount.'\'';?>) ; return false;" class="btn btn-warning btn-xs pull-right">
								<i style="display: none" class="fa fa-refresh fa-spin" id="<?php echo 'iload'.$icount;?>"></i>
								<?php echo get_phrase('previous');?>
							</button>
						</div>
						
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<audio id="audiotext" style="width: 100%;" src="<?php echo base_url("assets".$mp3pathnew."/".$mp3filename);?>" controls></audio>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
									<?php
										$fulltext = $text['text_content'];
										$fulltext = preg_replace("/\w*?".preg_quote($keyword_selected)."\w*/i", " <strong class=\"str$divcount\" style=\"color: white; background-color: red; font-size: 110%;\">$keyword_selected</strong>", $fulltext);
									?>
									<p class="text-justify"><?php echo $fulltext;?></p>
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

						var qtkwf = $('<?php echo '.str'.$divcount?>').length;
						$('<?php echo '#qtkwfid'.$divcount;?>').text(qtkwf);
					</script>
				<?php } ?>
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

			<script type="text/javascript">
				var firstidxml, firstidmp3, firstidtext;
				
				$('#audiotext').bind('contextmenu',function() { return false; });
				
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
				
				$('#joinbtn').click(function(event) {
					var frm = $('#filesform').submit();
					$('#filesform').closest('form').find("input[type=hidden], textarea").val('');
					$('input[type=checkbox]').prop('checked',false);
					$('#fileslist').empty();
					$('#joindiv').removeClass('show');
					return false;
				});
				
				function discard_text(divid, idtext, idclient, idkeyword, iduser) {
					$.ajax({
						url: '<?php echo base_url('pages/discard_keyword')?>',
						type: 'POST',
						data: {
							idtext: idtext,
							idclient: idclient,
							idkeyword: idkeyword,
							iduser: iduser
						},
						success: function(result) {
							// console.log('Discarded id_text = ' + idtext);
							// alert('<?php echo get_phrase('discarded')?>')
						}
					})
					document.getElementById(divid).classList.toggle('closed');
				}

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

				function checkbox_join(id, timestamp, radio, idradio, idclient, idkeyword, idfilexml, idfilemp3, idtext) {
					var previdsfilesxml;
					var previdsfilesmp3;
					var previdstexts;
					var previdradio = $('#ff_id_radio').val();
					if (previdradio !== '') {
						if (idradio != previdradio) {
							alert('A rádio não pode ser diferente!');
							$('#filesform').closest('form').find("input[type=hidden], textarea").val('');
							$('input[type=checkbox]').prop('checked', false);
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
						$('#ff_id_keyword').val(idkeyword);
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
			</script>
		</div>