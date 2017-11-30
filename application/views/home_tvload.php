<?php defined('BASEPATH') OR exit('No direct script access allowed');

							$time = microtime();
							$time = explode(' ', $time);
							$time = $time[1] + $time[0];
							$start = $time;
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
									<div class="timeline-heading"><h4 class="timeline-title"><?php echo $client['name'];?></h4></div>
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
													// $ids_text = $this->pages_model->discarded_texts($data_discard);
													// $keyword_found = $this->pages_model->tv_texts_keyword_byid_solr(null,$keyword['keyword'],$startdate,$enddate);
													$allkeyword_found = $this->pages_model->tv_text_keyword_solr($startdate,$enddate,$keyword['keyword']);
													// $keyword_foundc = count($keyword_found->response->docs);
													$allkeyword_foundc = count($allkeyword_found->response->docs);
													$ids_file_xml = null;
													$ic = null;
													// for ($i=0; $i < $keyword_foundc ; $i++) {
													// 	$ic++;
													// 	if ($ic == $keyword_foundc) {
													// 		$ids_file_xml .= $keyword_found->response->docs[$i]->id_file_i;
													// 	}
													// 	else {
													// 		$ids_file_xml .= $keyword_found->response->docs[$i]->id_file_i.",";
													// 	}
													// }
													if ($allkeyword_foundc != 0) { ?>
														<form style="all: unset;" action="<?php echo base_url('pages/tv_home_keyword');?>" method="post">
															<input type="hidden" name="ids_file_xml" value="<?php echo $ids_file_xml;?>">
															<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
															<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
															<input type="hidden" name="startdate" value="<?php echo $startdate;?>">
															<input type="hidden" name="enddate" value="<?php echo $enddate;?>">
															<?php if ($keyword['keyword_priority'] == 1) { ?>
																<button type="submit" class="btn btn-danger btn-sm"><?php echo $keyword['keyword'];?> <span class="badge"><?php echo $allkeyword_foundc;?></span></button>
															<?php } else { ?>
																<button type="submit" class="btn btn-info btn-sm"><?php echo $keyword['keyword'];?> <span class="badge"><?php echo $allkeyword_foundc;?></span></button>
															<?php } ?>
														</form>
														<?php
														array_push($allkeywordquant, $allkeyword_foundc);
														$client_keywords++;
													}
												} ?>
												<input type="text" class="allkeyword_foundc" name="allkeyword_foundc" id="<?php echo $client['id_client'];?>-allkeyword_foundc" value="<?php echo array_sum($allkeywordquant);?>" style="display: none;">
												<input type="text" class="client_keywords" name="client_keywords" id="<?php echo $client['id_client'];?>-client_keywords" value="<?php echo $client_keywords;?>" style="display: none;">
												<script type="text/javascript">
													$('#iallkeywordquant').val("<?php echo array_sum($allkeywordquant) ;?>");
												</script>
										</p>
									</div>
								</div>
							</li>
							<?php $invert++;
							}
							$time = microtime();
							$time = explode(' ', $time);
							$time = $time[1] + $time[0];
							$finish = $time;
							$total_time = round(($finish - $start), 4);
							// echo 'Page generated in '.$total_time.' seconds.';
							echo '<small><span class="pull-right text-muted loadmoret">Consulta gerada em '.$total_time.'s</span></small>'
						?>