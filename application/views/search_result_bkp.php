	<?php
		defined('BASEPATH') OR exit('No direct script access allowed');
		// print_r($searchresult);

		if (!isset($keyword)) {
			$keyword = '';
		}
		$searchtime = (string)$searchresult->responseHeader->QTime;
		$totalfound = (string)$searchresult->response->numFound;
		$totalpages = ceil($totalfound/10);
		$firstpage = (string)$searchresult->response->start;
		if ($totalpages >= 4 ) {
			$pageselectedend = $pageselected + 3;
		} else {
			$pageselectedend = $pageselected;
		}
		$query = base64_encode($searchresult->responseHeader->params->json);
	?>

		<div class="row">
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
					<li <?php if ($firstpage == 0) { ;?> class="disabled" <?php }?>><a href="#" aria-label="Previous"><span aria-hidden="true"><?php echo get_phrase('previous')?></span></a></li>
					<?php
					$selected = false;
					$start = $firstpage;
					for ($page=$pageselected; $page <= $pageselectedend ; $page++) {
						$start = $start + 10;
						 if ($pageselected == $page) { ?>
						<li class="active">
						<?php } else { ?>
						<li>
						<?php } ?>
						<a href="<?php echo base_url('pages/search_result/'.$page.'/'.$query.'/'.$start)?>"><?php echo $page ?> <span class="sr-only"></span></a></li>
					<?php } ?>
					<li class="disabled" ><a>...<span class="sr-only"></span></a></li>
					<li><a href="<?php echo base_url('pages/search_result/'.$totalpages.'/'.$query.'/'.$totalpages)?>"><?php echo $totalpages?> <span class="sr-only"></span></a></li>
					<li <?php if ($firstpage >= $totalpages) { ?> class="disabled" <?php }?>><a href="#" aria-label="Next"><span aria-hidden="true"><?php echo get_phrase('next')?></i></span></a></li>
				</ul>
				<?php }  ?>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default"><!--second div panel-->
				<?php if (!empty($keyword)) { ?><div class="panel-heading"><i class="fa fa-key fa-fw"></i> <?php echo $keyword;?></div><?php } ?>
					<div class="panel-body"><!--second div panel-body-->
						<?php
							$texts = $searchresult->response->docs;
							$divcount = 0;
							foreach ($texts as $text) {
								$divcount++;
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
								$mp3filename = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->filename; ?>
								<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default">
									<div class="panel-heading text-center">
										<i class="fa fa-bullhorn"></i> <?php echo $radio[0]['state']." - ".$radio[0]['name']." - ".date("d/m/Y - H:i:s",$mp3[0]['timestamp']);?>
										<form id="form_edit" style="all: unset;" action="<?php echo base_url('pages/edit_temp');?>" target="_blank" method="POST">
											<input type="hidden" name="mp3pathfilename" value="<?php echo base_url("assets".$mp3pathnew."/".$mp3filename);?>">
											<input type="hidden" name="xmlpathfilename" value="<?php echo $xml[0]['path']."/".$xml[0]['filename'];?>">
											<input type="hidden" name="state" value="<?php echo $radio[0]['state'];?>">
											<input type="hidden" name="radio" value="<?php echo $radio[0]['name'];?>">
											<input type="hidden" name="timestamp" value="<?php echo $mp3[0]['timestamp'];?>">
											<?php if (!empty($keyword)) { ?><input type="hidden" name="keyword" value="<?php echo $keyword;?>"><?php } ?>
											<button type="submit" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
										</form>
									</div>
									<p class="text-center"><audio id="audiotext" style="width: 100%;" src="<?php echo base_url("assets/".$mp3pathnew."/".$mp3filename);?>" controls></audio></p>
									<div class="panel-body" id="<?php echo 'pbody'.$divcount;?>" style="height: 300px; overflow-y: auto">
										<?php
										if (!empty($text)) {
											$textkeywordbold = str_ireplace($keyword, '<strong id="str'.$divcount.'" style="color: white; background-color: red; font-size: 110%;">'.$keyword.'</strong>', (string)$text->text_content_txt[0]);
										} ?>
										<p class="text-justify"><?php echo $textkeywordbold;?></p>
									</div>
								</div>
								<script type="text/javascript">
									jQuery.fn.scrollTo = function(elem) {
										$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
										return this;
									}
									if($('#<?php echo 'str'.$divcount;?>').length != 0) {
										$('#<?php echo 'pbody'.$divcount;?>').scrollTo('#<?php echo 'str'.$divcount;?>');
									}
									$('#<?php echo 'pbody'.$divcount;?>').css('overflowY','hidden');
									$('#<?php echo 'pbody'.$divcount;?>').click(function() {
										$(this).css('overflowY', 'auto');
									})
									$('#<?php echo 'pbody'.$divcount;?>').hover(function() {
										/*do nothing*/
									}, function() {
										$('#<?php echo 'pbody'.$divcount;?>').css('overflowY','hidden');
									})

									$('#audiotext').bind('contextmenu',function() { return false; });
								</script>
							<?php } ?>
					</div><!--second div panel-body-->
				</div><!--second div panel-->
			</div><!--div col-lg-12-->
		</div> <!-- div row -->
	</div>