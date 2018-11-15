<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/dataclip/home_keyword.css")?>">

	<?php if (isset($client_selected)) { ?>

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<?php echo $client_selected; ?>
				<small> - <?php echo $keyword; ?></small>
			</h1>
		</div>
	</div>

	<?php } else {
		$client_selected = 0;
		$id_keyword = 0;
		$id_client = 0;
	}

	if (!isset($keyword)) {
		$keyword = null;
	}

	?>

	<div class="row">
		<div class="col-lg-12">
			<?php

			$divcount = 0;
			$icount = 0;
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
				$sendtime = $ed->format('H:i:s');
				$epochstartdate = $sd->format('U');
				$epochenddate = $ed->format('U');

				// $dtstarttime = substr_replace($found->starttime_dt, '00', 17, 2);
				// $dtendtime = substr_replace($found->endtime_dt, '00', 17, 2);

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
							<?php echo $ssource." | ".$sstartdate." - ".$sendtime;?>
						</label>

						<div class="btn-toolbar pull-right">
							<button class="btn btn-warning btn-xs loadprevious" data-sc="local"
							data-type="audio" data-iddiv="<?php echo 'div'.$divcount;?>"
							data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $found->starttime_dt;?>"
							data-enddate="<?php echo $found->endtime_dt;?>" data-position="previous">
								<i id="<?php echo 'iload'.$icount;?>" style="display: none" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('previous');
								$icount++; ?>
							</button>

							<button class="btn btn-warning btn-xs loadnext" data-sc="local"
							data-type="audio" data-iddiv="<?php echo 'div'.$divcount;?>"
							data-idsource="<?php echo $sidsource?>" data-startdate="<?php echo $found->starttime_dt;?>"
							data-enddate="<?php echo $found->endtime_dt;?>" data-position="next">
								<i id="<?php echo 'iload'.$icount;?>" style="display: none;" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('next'); ?>
							</button>

							<button type="button" class="btn btn-danger btn-xs discarddoc"
							data-sc="local" data-type="audio" data-iddiv="<?php echo 'div'.$divcount;?>"
							data-iddoc="<?php echo $sid?>" data-idkeyword="<?php echo $id_keyword;?>"
							data-idclient="<?php echo $id_client;?>">
								<i style="display: none" class="fa fa-refresh fa-spin"></i>
								<?php echo get_phrase('discard');?>
							</button>

							<button type="submit" form="<?php echo 'form'.$divcount;?>" class="btn btn-primary btn-xs pull-right"><?php echo get_phrase('edit');?></button>
							<form id="<?php echo 'form'.$divcount;?>" style="all: unset;" action="<?php echo base_url('pages/edit');?>" target="_blank" method="POST">
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
							<p class="paudio"><audio id="<?php echo 'paudio'.$divcount;?>" data-sc="local" data-type="audio" class="pfaudio" style="width: 100%" src="<?php echo $smediaurl; ?>" controls preload="metadata"></audio></p>
							<p id="<?php echo 'ptext'.$divcount;?>" class="text-justify ptext noscrolled"
								data-mediaid="<?php echo 'paudio'.$divcount;?>" style="height: 300px; overflow-y: hidden">
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
			<input id="autofocus-current-word" class="autofocus-current-word" type="checkbox" checked style="display: none;">
			<span class="text-muted center-block text-center" id="loadmore" style="opacity: 0;">
				<i class="fa fa-refresh fa-spin"></i> Carregando...
			</span>
		</div>

		<div class="well well-sm" id="joindiv">
			<span id="wsource" class="center-block text-center"></span>
			<div class="list-group" style="max-height:  150px ; overflow: auto;">
				<small id="fileslist"></small>
			</div>
			<button id="joinbtn" class="btn btn-default btn-block btn-sm disabled" disabled><?php echo get_phrase('join')?></button>
			<form id="joinform" style="all: unset;" action="<?php echo base_url('pages/join_radio');?>" target="_blank" method="POST">
				<input type="hidden" id="jids_doc" name="ids_doc">
				<input type="hidden" id="jid_client" name="id_client">
				<input type="hidden" id="jid_keyword" name="id_keyword">
			</form>
		</div>
	</div>

	<?php
		$adata = array(
			'keyword' => $keyword,
			'start' => $start,
			'rows' => $rows,
			'ktfound' => $keyword_texts->response->numFound,
			'id_keyword' => $id_keyword,
			'id_client' => $id_client,
			'client_selected' => $client_selected,
			'startdate' => $startdate,
			'enddate' => $enddate,
			'msc' => 'local',
			'mtype' => 'audio',
			'pagesrc' => $pagesrc
		);

		$jdata = base64_encode(json_encode($adata));
	?>

	<script src="<?php echo base_url('assets/readalong/readalong.js');?>"></script>
	<script src="<?php echo base_url('pages/hkw_functions');?>"></script>
	<script src="<?php echo base_url('pages/hkw_listeners/').$jdata;?>"></script>