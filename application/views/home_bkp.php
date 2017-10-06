<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="page-wrapper" style="height: 100%; min-height: 400px;">
	<div class="row page-header">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<h1><?php echo get_phrase('home');?></h1>
			</div>
			<div class="col-lg-5">
			</div>
			<div class="col-lg-3" >
				<h1>
				<div class="input-group">
					<input id="myInput" class="form-control pull-right" onkeyup="myFunction()" placeholder="<?php echo get_phrase('search')?>"/>
					<span class="input-group-btn">
						<button class="btn btn-default" disabled type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
				</div>
				</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default ">
				<div class="panel-heading"><i class="fa fa-key fa-fw"></i><?php echo get_phrase('kewords_found').' '.get_phrase('since').' '.date('d/m/Y',$startdate).' - 00:00';?><strong><span class="pull-right" id="keywordsquant"></span></strong><span class="pull-right">Quantidade:</span></div>
					<div class="panel-body">
						<ul class="timeline" id="myUL">
							<?php
							$clientn = 0;
							$invert=0;
							$keywordquant = array();
							$keyword_found_arr = array();
							foreach ($clients as $client) {
								$clientn++;
								if($invert % 2 == 0){ ?>
									<li id="<?php echo 'li-'.$clientn; ?>">
								<?php } else { ?>
									<li class="timeline-inverted" id="<?php echo 'li-'.$clientn; ?>">
								<?php }
								if ($client['priority'] == 1) { ?>
								 	<div class="timeline-panel-high">
								<?php }
								else { ?>
									<div class="timeline-panel">
								<?php } ?>
									<div class="timeline-heading"><h4 class="timeline-title"><?php echo $client['name'];?></h4></div>
									<div class="timeline-body">
										<p class="text-center">
											<?php
												$keywords = $this->pages_model->keywords_client($client['id_client']);
												$client_keywords = null;
												foreach ($keywords as $keyword) {
													$keyworda = $keyword['keyword'];
													$keyword_found = $this->pages_model->text_keyword_solr($startdate,$enddate,$keyworda);
													$keyword_foundc = count($keyword_found->response->docs);
													$ids_file_xml = null;
													$ic = null;
													for ($i=0; $i < $keyword_foundc ; $i++) {
														$ic++;
														if ($ic == $keyword_foundc) {
															$ids_file_xml .= $keyword_found->response->docs[$i]->id_file_i;
														}
														else {
															$ids_file_xml .= $keyword_found->response->docs[$i]->id_file_i.",";
														}
													}
													if ($keyword_foundc != 0) { ?>
														<form style="all: unset;" action="<?php echo base_url('pages/home_keyword');?>" method="post">
															<input type="hidden" name="ids_file_xml" value="<?php echo $ids_file_xml;?>">
															<input type="hidden" name="id_keyword" value="<?php echo $keyword['id_keyword'];?>">
															<input type="hidden" name="id_client" value="<?php echo $client['id_client'];?>">
															<?php if ($keyword['keyword_priority'] == 1) { ?>
																<button type="submit" class="btn btn-danger btn-sm"><?php echo $keyword['keyword'];?> <span class="badge"><?php echo $keyword_foundc;?></span></button>
															<?php } else { ?>
																<button type="submit" class="btn btn-lightblue btn-sm"><?php echo $keyword['keyword'];?> <span class="badge"><?php echo $keyword_foundc;?></span></button>
															<?php } ?>
														</form>
														<?php
														array_push($keywordquant, $keyword_foundc);
														$client_keywords++;
													}
												}
												if ($client_keywords == 0) {
													echo '<script>';
														echo 'document.getElementById("li-'.$clientn.'").style.display = "none"';
													echo '</script>';
												}
											?>
										</p>
									</div>
								</div>
							</li>
							<?php $invert++;
							}
							?>
						</ul>
					</div>
			</div>
		</div>
	</div> <!-- div row clients timeline -->

	<script type="text/javascript">
		// $(document).ready(function(){
			function myFunction() {
				var input, filter, ul, li, a, i;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				ul = document.getElementById("myUL");
				li = ul.getElementsByTagName("li");
				for (i = 0; i < li.length; i++) {
					a = li[i].getElementsByTagName("h4")[0];
					if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
						li[i].style.display = "";
					} else {
						li[i].style.display = "none";
					}
				}
			};

			$('#keywordsquant').text(<?php echo array_sum($keywordquant);?>);
		// });
	</script>