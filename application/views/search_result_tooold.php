<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-key fa-fw"></i> <?php echo $keyword;?></div>
								<div class="panel-body">
									<?php

									//print_r($searchresult);
									echo "Status: ".(string)$searchresult->responseHeader->status."<br>";
									echo "Tempo de pesquisa: ".(string)$searchresult->responseHeader->QTime."s<br>";
									echo "Encontrados: ".(string)$searchresult->response->numFound."<br>";
									$texts = $searchresult->response->docs;
									var_dump($texts);
										$divcount = 0;
										foreach ($texts as $text) {
											$divcount++;
											$mp3fileid = $text['id_file'] - 1;
											$mp3pathorig = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->path;
											$mp3pathnew = mb_substr($mp3pathorig, 31);
											$mp3filename = $this->db->get_where('file',array('id_file' => $mp3fileid))->row()->filename; ?>
											<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default slider">
												<div class="panel-heading text-center">
													<i class="fa fa-bullhorn"></i> <?php echo $text['state']." - ".$text['radio']." - ".date("d/m/Y - H:i:s",$text['timestamp']);?>
													<form id="form_edit" style="all: unset;" action="<?php echo site_url('pages/edit_temp');?>" target="_blank" method="POST">
														<input type="hidden" name="mp3pathfilename" value="<?php echo site_url("assets/".$mp3pathnew."/".$mp3filename);?>">
														<input type="hidden" name="xmlpathfilename" value="<?php echo site_url($text['path']."/".$text['filename']);?>">
														<input type="hidden" name="state" value="<?php echo $text['state'];?>">
														<input type="hidden" name="radio" value="<?php echo $text['radio'];?>">
														<input type="hidden" name="timestamp" value="<?php echo $text['timestamp'];?>">
														<!-- <input type="hidden" name="id_keyword" value="<?php //echo $id_keyword;?>"> -->
														<button type="submit" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
													</form>
													<!-- <button onclick="btnclick(<?php //echo '\'div'.$divcount.'\','.$text['id_text'];?>)" class="btn btn-danger btn-xs pull-right"><?php echo get_phrase('discard');?></button> -->
												</div>
												<p class="text-center"><audio style="width: 100%;" src="<?php echo site_url("assets/".$mp3pathnew."/".$mp3filename);?>" autobuffer controls></audio></p>
												<div class="panel-body" style="padding:10px;border:5px;height:300px;overflow-y:auto">
													<?php
													if (!empty($text)) {
														$textkeywordbold = str_ireplace($keyword, '<strong style="color: white; background-color: red; font-size: 110%;">'.$keyword.'</strong>', $text['text_content']);
													}
													?>
													<p class="text-justify"><?php echo $textkeywordbold;?></p>
												</div>
											</div>
										<?php } ?>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- div row -->
	</div>
