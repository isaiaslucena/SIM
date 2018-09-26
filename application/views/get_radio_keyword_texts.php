<?php
			$divcount = $start;
			$icount = $start;
			foreach ($keyword_texts->response->docs as $found) {
				$divcount++;
				$icount++;
				$sid = $found->id_i;
				$sidsource = $found->id_source_i;
				$smuarr = explode("_", $found->mediaurl_s);
				$smediaurl = str_replace("sim", "radio", base_url())."index.php/radio/getmp3?source=".$smuarr[0]."&file=".str_replace($smuarr[0]."_", "", $found->mediaurl_s);
				if (isset($found->times_t)) {
					$stimes = json_decode(str_replace('\u0000', '', $found->times_t[0]), true);
				}

				$sd = new Datetime($found->starttime_dt);
				$ed = new Datetime($found->endtime_dt);
				$sstartdate = $sd->format('d/m/Y H:i:s');
				$senddate = $ed->format('d/m/Y H:i:s');
				$epochstartdate = $sd->format('U');
				$epochenddate = $ed->format('U');

				$stext = $found->content_t[0];
				$ssource = $found->source_s; ?>
				<div id="<?php echo 'div'.$divcount;?>" class="panel panel-default collapse in">
					<div class="panel-heading text-center">
						<label class="pull-left" style="font-weight: normal">
							<input type="checkbox" class="cbjoinfiles"
							id="<?php echo 'cb'.$divcount;?>" data-iddoc="<?php echo $sid?>"
							data-idsource="<?php echo $sidsource?>" data-source="<?php echo $ssource?>"
							data-startdate="<?php echo $sstartdate; ?>" data-enddate="<?php echo $senddate; ?>"
							data-idclient="<?php echo $id_client;?>" data-idkeyword="<?php echo $id_keyword;?>">
							 <?php echo get_phrase('join');?>
						</label>

						<label class="labeltitle">
							<i class="fa fa-search fa-fw"></i>
							<span id="<?php echo 'tkeyfound'.$divcount;?>"></span>
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
							<form id="<?php echo 'form'.$divcount;?>" style="all: unset;" action="<?php echo base_url('pages/edit_novo');?>" target="_blank" method="POST">
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
						<div class="col-lg-12">
							<p class="paudio"><audio id="<?php echo 'paudio'.$divcount;?>" class="pfaudio" style="width: 100%" src="<?php echo $smediaurl; ?>" controls preload="metadata"></audio></p>
							<p id="<?php echo 'ptext'.$divcount;?>" class="text-justify ptext noscrolled" style="height: 300px; overflow-y: hidden">
								<?php
								if (isset($found->times_t)) {
									foreach ($stimes as $stime) {
										if (isset($stime['words'])) {
											foreach ($stime['words'] as $word) {
												$wbegin = (float)substr((string)$word['begin'], 0, 5);
												$wend = (float)substr((string)$word['end'], 0, 5);
												$wdur = (float)substr(($wend - $wbegin), 0, 5);
												$wspan = '<span data-dur="'.$wdur.'" data-begin="'.$wbegin.'">'.$word['word'].'</span> ';
												print (string)$wspan;
											}
										}
									}
								} else {
									print (string)$stext;
								}
								?>
							</p>
						</div>
					</div>
				</div>
			<?php } ?>