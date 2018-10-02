<?php defined('BASEPATH') OR exit('No direct script access allowed');

							$time = microtime();
							$time = explode(' ', $time);
							$time = $time[1] + $time[0];
							$start = $time;

							$timezone = new DateTimeZone('UTC');
							$sd = new Datetime($startdate, $timezone);
							$ed = new Datetime($enddate, $timezone);
							$newtimezone = new DateTimeZone('America/Sao_Paulo');
							$sd->setTimezone($newtimezone);
							$ed->setTimezone($newtimezone);
							$sstartdate = $sd->format('Y-m-d\TH:i:s\Z');
							$senddate = $ed->format('Y-m-d\TH:i:s\Z');
							$epochstartdate = $sd->format('U');
							$epochenddate = $ed->format('U');

							$sd1 = new Datetime($startdate);
							$ed1 = new Datetime($enddate);
							$sstartdate1 = $sd->format('Y-m-d\TH:i:s\Z');
							$senddate1 = $ed->format('Y-m-d\TH:i:s\Z');
							$epochstartdate1 = $sd->format('U');
							$epochenddate1 = $ed->format('U');

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
													$data_discard['startdate'] = $epochstartdate1;
													$data_discard['enddate'] = $epochenddate1;
													$data_discard['id_client'] = $client['id_client'];
													$data_discard['id_keyword'] = $keyword['id_keyword'];

													$discardeddocs = $this->pages_model->discarded_docs_novo_radio($data_discard);
													$croppeddocs = $this->pages_model->cropped_docs_novo_radio($data_discard);
													$keyword_found = $this->pages_model->docs_byid_radio_novo($discardeddocs, $croppeddocs, $keyword['keyword'], $startdate, $enddate);
													$keyword_foundc = $keyword_found->response->numFound;
													$allkeyword_found = $this->pages_model->radio_knewin_text_keyword_solr($startdate, $enddate, $keyword['keyword']);
													$allkeyword_foundc = $allkeyword_found->response->numFound;

													$ic = null;
													if ($keyword_foundc != 0) { ?>
														<form style="all: unset;" action="<?php echo base_url('pages/radio_novo_home_keyword');?>" method="post">
															<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
															<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
															<input type="hidden" name="startdate" value="<?php echo $startdate;?>">
															<input type="hidden" name="enddate" value="<?php echo $enddate;?>">
															<button type="submit" class="btn <?php echo $keyword['keyword_priority'] == 1 ? 'btn-danger' : 'btn-info' ?> btn-sm"><?php echo $keyword['keyword'];?>
																<span class="badge"><?php echo $keyword_foundc;?> </span>
															</button>
														</form>

														<?php
														array_push($keywordquant, $keyword_foundc);
														array_push($allkeywordquant, $allkeyword_foundc);
														$client_keywords++;
													}
												} ?>
												<input type="text" class="allkeyword_foundc" name="allkeyword_foundc" id="<?php echo $client['id_client'];?>-allkeyword_foundc" value="<?php echo array_sum($allkeywordquant);?>" style="display: none;">
												<input type="text" class="client_keywords" name="client_keywords" id="<?php echo $client['id_client'];?>-client_keywords" value="<?php echo $client_keywords;?>" style="display: none;">
												<script type="text/javascript">
													$('#ikeywordquant').val("<?php echo array_sum($keywordquant) ;?>");
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