<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h1 class="page-header"><?php echo get_phrase('search');?></h1>
			</div>
		</div>

		<?php
			if (isset($keyword_texts)) {
				$querytime = $keyword_texts->responseHeader->QTime;
				$params = json_decode($keyword_texts->responseHeader->params->json, TRUE);
				$nfound = $keyword_texts->response->numFound;
				$pagesearchp = 'Encontrado: '.$nfound.' itens <br> Tempo da pesquisa: '.$querytime.'ms';
				// var_dump($keyword_texts->responseHeader);
			}

			if (isset($search)) {
				if ($vtype == 'radio') {
					if (preg_match("/\(/", $search) == 1) {
						$radios01 = explode('(', $search);
						$radios02 = explode(')', $radios01[1]);
						$id_radio = str_replace(' OR ', ',', $radios02[0]);
					}

					$dates = explode('[', $search);
					$datesonly = explode(' TO ',mb_substr($dates[1], 0, -3));

					$startdate = date('d/m/Y', $datesonly[0]);
					$enddate = date('d/m/Y', $datesonly[1]);

					if (preg_match("/_text_/", $search) == 1) {
						$keyword01 = explode('_text_', $search);
						$keyword02 = explode('","', $keyword01[1]);
						$keyword03 = explode(':\\"', $keyword02[0]);
						$keyword = mb_substr($keyword03[1],0,-2);
					}
				} else if ($vtype == 'tv') {
					// var_dump($search);
					if (preg_match('/starttime_dt/', $search) == 0) {
						// preg_match("/\"text_t:\\\"(.*?)\\\"\",/", $search, $fkeyword);
						// var_dump($fkeyword);
						$searcharr = explode(":", $search);
						$keywordarr = explode('"', $searcharr[2]);
						$keyword =  str_replace("\\", "", $keywordarr[1]);
						$datearr = explode(' TO ', $searcharr[4]);
						$startdatets = str_replace("[", "", $datearr[0]) / 1000;
						$enddatets = str_replace("]\"}", "", $datearr[1]) / 1000;
						$startdate = date("d/m/Y", $startdatets);
						$enddate = date("d/m/Y", $enddatets);
						// var_dump($datearr);
					}
				}
			}
		?>

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<form action="<?php echo base_url('pages/search_result')?>" method="POST" accept-charset="utf-8">
							<div class="col-sm-6 col-md-6 col-lg-6">
								<div class="form-group">
									<label><?php echo get_phrase('client');?></label>
									<input id="clientname" name="clientid" type="text"  class="form-control typeahead input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off" disabled>
								</div>
								<div id="scrollable-dropdown-menu" class="form-group">
									<label><?php echo get_phrase('keyword');?></label>
									<i id="tooltipkw" style="display: none;" class="fa fa-question-circle fa-fw" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ao deixar o campo em branco, pesquisará em todas as palavras-chave!"></i>
									<input disabled id="keywordid" name="clientkeywordid" type="text"  class="form-control input-sm" autocomplete="off">
								</div>

								<div class="row">
									<div class="col-sm-6 col-md-6 col-lg-6">
										<div id="radiosel" class="form-group">
											<label>Tipo de Veículo</label>
											<div class="radio">
												<label>
													<input type="radio" name="optionsRadios" id="optradio" value="radio" <?php if (isset($mtype) and ($mtype == 'audio')) {echo "checked";} ?> required>
													Rádio
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="optionsRadios" id="opttv" value="tv" <?php if (isset($mtype) and $mtype == 'video') {echo "checked";} ?> required>
													Televisão
												</label>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-md-6 col-lg-6">
										<div id="msrctype" class="form-group" style="<?php if (!isset($msc)) { echo "display: none"; }?>">
											<label>Fonte</label>
											<div class="radio">
												<label>
													<input type="radio" name="optionssrcadios" id="optradiotype1" value="local" <?php if (isset($msc) and $msc == 'local') {echo "checked";} ?> required>
													Local
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="optionssrcadios" id="optradiotype2" value="novo" <?php if (isset($msc) and $msc == 'novo') {echo "checked";} ?> required>
													Novo
												</label>
											</div>
										</div>
									</div>
								</div>

								<div id="radioidsel" class="form-group"
								style="<?php if (isset($msc) and ($msc == 'local' and $mtype == 'audio')) { echo "display: block;"; } else { echo "display: none;"; } ?>">
									<label><?php echo get_phrase('radio');?></label>
									<input id="radioid" name="radioid" type="text" class="form-control input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
								</div>

								<div id="radionovoidsel" class="form-group"
								style="<?php if (isset($msc) and ($msc == 'novo' and $mtype == 'audio')) { echo "display: block;"; } else { echo "display: none;"; } ?>">
									<label><?php echo get_phrase('radio');?></label>
									<input id="radionovoid" name="radionovoid" type="text" class="form-control input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
								</div>

								<div id="tvidsel" class="form-group"
								style="<?php if (isset($msc) and ($msc == 'local' and $mtype == 'video')) { echo "display: block;"; } else { echo "display: none;"; } ?>">
									<label><?php echo get_phrase('television');?></label>
									<input id="tvid" name="tvid" type="text" class="form-control input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
								</div>

								<div id="tvnovoidsel" class="form-group"
								style="<?php if (isset($msc) and ($msc == 'novo' and $mtype == 'video')) { echo "display: block;"; } else { echo "display: none;"; } ?>">
									<label><?php echo get_phrase('television');?></label>
									<input id="tvnovoid" name="tvnovoid" type="text" class="form-control input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
								</div>
							</div>

							<!-- Date, Time Selector and Text Input-->
							<div class="col-sm-6 col-md-6 col-lg-6">
								<?php
									if (isset($startdate) and isset($enddate)) {
										$sd = new Datetime($startdate);
										$ed = new Datetime($enddate);
										$sstartdate = $sd->format('d/m/Y H:i:s');
										$senddate = $ed->format('d/m/Y H:i:s');
										$estartdate = $sd->format('d/m/Y');
										$eenddate = $ed->format('d/m/Y');
										$estarttime = $sd->format('H:i');
										$eendtime = $ed->format('H:i');
									}
								?>
								<div class="form-group">
									<label><?php echo get_phrase('date');?></label>
									<div class="input-daterange input-group" id="datepicker">
										<input required type="text" class="input-sm form-control" id="startdate" name="startdate" placeholder="<?php echo get_phrase('start');?>" <?php if (isset($estartdate)) { echo 'value="'.$estartdate.'"'; } ?> autocomplete="off"/>
										<span class="input-group-addon"><?php echo get_phrase('until');?></span>
										<input required type="text" class="input-sm form-control" id="enddate" name="enddate" placeholder="<?php echo get_phrase('end');?>" <?php if (isset($eenddate)) { echo 'value="'.$eenddate.'"'; } ?> autocomplete="off"/>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo get_phrase('time');?></label>
									<div class="input-daterange input-group">
										<input required type="text" class="input-sm form-control clockpicker" id="starttime" name="starttime" placeholder="<?php echo get_phrase('start');?>" <?php if (isset($estarttime)) { echo 'value="'.$estarttime.'"'; } else { echo 'value="00:00"'; } ?> autocomplete="off"/>
										<span class="input-group-addon"><?php echo get_phrase('until');?></span>
										<input required type="text" class="input-sm form-control clockpicker" id="endtime" name="endtime" placeholder="<?php echo get_phrase('end');?>" <?php if (isset($eendtime)) { echo 'value="'.$eendtime.'"'; } else { echo 'value="23:59"'; } ?> autocomplete="off"/>
									</div>
								</div>
								<!-- Text Input -->
								<label><?php echo get_phrase('text');?></label>
								<div class="form-group input-group">
									<input type="text" id="keyword" name="keyword" class="form-control input-sm"  <?php if (isset($keyword)) { echo 'value="'.$keyword.'"'; } ?> autocomplete="off">
									<span class="input-group-btn">
										<button class="btn btn-default btn-sm" type="submit" name="text"><i class="fa fa-search"></i></button>
									</span>
								</div>
								<small><span id="pagesearchp" class="pull-right text-muted" style="<?php if (!isset($keyword_texts)) { echo 'display: none'; } ?>"><?php echo isset($pagesearchp) ? $pagesearchp : null ;?></span></small>

								<div id="searchtop"></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php
			$clientslinevar = null;
			$ccountarr = count($allclients);
			$ccount = 0;
			foreach ($allclients as $client) {
				$ccount++;
				if ($ccount == $ccountarr) {
					$clientslinevar .= "{'id':".$client['id_client'].",";
					$clientslinevar .= "'name':"."'".$client['name']."'}";
				} else {
					$clientslinevar .= "{'id':".$client['id_client'].",";
					$clientslinevar .= "'name':"."'".$client['name']."'},";
				}
			}

			$radioslinevar = null;
			$rcountarr = count($allradios);
			$rcount = 0;
			foreach ($allradios as $radio) {
				$rcount++;
				if ($rcount == $rcountarr) {
					$radioslinevar .= "{'id':".$radio['id_radio'].",";
					$radioslinevar .= "'name':"."'".$radio['name']."-".$radio['state']."'}";
				} else {
					$radioslinevar .= "{'id':".$radio['id_radio'].",";
					$radioslinevar .= "'name':"."'".$radio['name']."-".$radio['state']."'},";
				}
			}

			$novoradioslinevar = null;
			$rcountarr = count($allradios_novo);
			$rcount = 0;
			foreach ($allradios_novo as $radio) {
				$rcount++;
				if ($rcount == $rcountarr) {
					$novoradioslinevar .= "{'id':".$radio['id_source'].",";
					$novoradioslinevar .= "'name':"."'".$radio['source']."'}";
				} else {
					$novoradioslinevar .= "{'id':".$radio['id_source'].",";
					$novoradioslinevar .= "'name':"."'".$radio['source']."'},";
				}
			}

			$tvclinevar = null;
			$tcountarr = count($alltvs);
			$tvccount = 0;
			foreach ($alltvs as $tvc) {
				$tvccount++;
				if ($tvccount == $tcountarr) {
					$tvclinevar .= "{'id':".$tvc['id_radio'].",";
					$tvclinevar .= "'name':"."'".$tvc['name']."'}";
				} else {
					$tvclinevar .= "{'id':".$tvc['id_radio'].",";
					$tvclinevar .= "'name':"."'".$tvc['name']."'},";
				}
			}

			$novotvclinevar = null;
			$tcountarr = count($alltvs_novo);
			$tvccount = 0;
			foreach ($alltvs_novo as $tvc) {
				$tvccount++;
				if ($tvccount == $tcountarr) {
					$novotvclinevar .= "{'id':".$tvc['id_source'].",";
					$novotvclinevar .= "'name':"."'".$tvc['source']."'}";
				} else {
					$novotvclinevar .= "{'id':".$tvc['id_source'].",";
					$novotvclinevar .= "'name':"."'".$tvc['source']."'},";
				}
			}
		?>

		<script type="text/javascript">
			var rchecked, rsrcchecked;
			$('[data-toggle="tooltip"]').tooltip();

			$('#datepicker').datepicker({
				format: "dd/mm/yyyy",
				language: 'pt-BR',
				todayBtn: true,
				todayHighlight: true,
				autoclose: true
				}).on('change', function(){
					$('#enddate').focus();
			});

			$('#starttime').clockpicker({
				autoclose: true,
				}).on('change', function(){
					$('#endtime').focus();
			});

			$('#endtime').clockpicker({
				autoclose: true
			});

			$('input[name=optionsRadios]').on("click", function() {
				rchecked = $('input[name=optionsRadios]:checked').val();
				console.log(rchecked);
				$('#msrctype').show('fast');
			});

			$('input[name=optionssrcadios').on('click', function() {
				rsrcchecked = $('input[name=optionssrcadios]:checked').val();
				if (rchecked == 'radio') {
					$('#tvnovoidsel').hide('fast');
					$('#tvidsel').hide('fast');
					if (rsrcchecked == 'local') {
						$('#radionovoidsel').hide('fast');
						$('#radioidsel').show('fast');
					} else {
						$('#radionovoidsel').show('fast');
						$('#radioidsel').hide('fast');
					}
				} else if (rchecked == 'tv') {
					$('#radionovoidsel').hide('fast');
					$('#radioidsel').hide('fast');
					if (rsrcchecked == 'local') {
						$('#tvnovoidsel').hide('fast');
						$('#tvidsel').show('fast');
					} else {
						$('#tvnovoidsel').show('fast');
						$('#tvidsel').hide('fast');
					}
				}
			});

			//clients typeahead
			// var clients = [<?php echo $clientslinevar; ?>];
			// var clientsblood = new Bloodhound({
			// 	"datumTokenizer": Bloodhound.tokenizers.obj.whitespace('name'),
			// 	"queryTokenizer": Bloodhound.tokenizers.whitespace,
			// 	"identify": function(obj) {
			// 		return obj.id;
			// 	},
			// 	local: clients
			// });
			// $('#clientname').typeahead({
			// 	hint: true,
			// 	highlight: true,
			// 	minLength: 0,
			// 	limit: 20
			// }, {
			// 	name: 'clients',
			// 	value: 'id',
			// 	source: clientsblood,
			// 	display: 'name'
			// }).on('typeahead:select', function(ev, suggestion) {
			// 		$('#clientid').val(suggestion.id);
			// 		$('#keywordid').prop('disabled',false);
			// 		$('#keywordid').prop('placeholder','Digite para pesquisar');
			// 		$('#tooltipkw').css('display', 'inline');
			// 		var keywordid_input = $('#keywordid');
			// 		// keywordid_input.tagsinput('destroy');
			// 		// $('#keywordid').typeahead('val', '');
			// 		// instantiate the bloodhound suggestion engine
			// 		var keywordsblood = new Bloodhound({
			// 			datumTokenizer: function(keywordsblood) {
			// 				return Bloodhound.tokenizers.whitespace(keywordsblood.val);
			// 			},
			// 			queryTokenizer: Bloodhound.tokenizers.whitespace,
			// 			// prefetch: {
			// 				// url: "<?php //echo base_url('pages/keywords_client/')?>" + suggestion.id,
			// 				// filter: function(response) {
			// 				// 	// console.log(response);
			// 				// 	return response;
			// 				// }
			// 			// 	filter: function(response) {
			// 			// 		return $.map(response, function(data) {
			// 			// 			console.log({ keyword: data.keyword });
			// 			// 			return { keyword: data.keyword };
			// 			// 		});
			// 			// 	}
			// 			// }
			// 			remote: {
			// 				url: "<?php echo base_url('pages/keywords_client/')?>" + suggestion.id,
			// 				filter: function(response) {
			// 					// console.log(response);
			// 					return response;
			// 				}
			// 			}
			// 		});
			// 		keywordsblood.clearPrefetchCache();
			// 		// keywordsblood.clearRemoteCache();
			// 		keywordsblood.initialize();
			// 		keywordid_input.tagsinput({
			// 			itemValue: 'id_keyword',
			// 			itemText: 'keyword',
			// 			allowDuplicates: false,
			// 			typeaheadjs: {
			// 				limit: 100,
			// 				hint: false,
			// 				highlight: true,
			// 				minLength: 1,
			// 				name: 'keyword',
			// 				displayKey: function(keywordsblood) {
			// 					return keywordsblood.keyword;
			// 				},
			// 				source: keywordsblood.ttAdapter()
			// 			}
			// 		});
			// }).on('focus', function() {
			// 	// if (defaultOption.length) {
			// 		// $(this).data().ttTypeahead.input.trigger('queryChanged', nm);
			// 	// }
			// });

			//radios typeahead and tagsinput
			var radios = [<?php echo $radioslinevar; ?>];
			var radiosblood = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: radios
			});
			radiosblood.initialize();
			var radioid_input = $('#radioid');
			radioid_input.tagsinput({
				focusClass: 'form-control input-sm',
				itemValue: 'id',
				itemText: 'name',
				allowDuplicates: false,
				typeaheadjs: {
					name: 'radio',
					displayKey: 'name',
					hint: true,
					highlight: true,
					limit: 10,
					source: radiosblood.ttAdapter()
				}
			});

			//radios novo typeahead and tagsinput
			var radiosnovo = [<?php echo $novoradioslinevar; ?>];
			var radiosnovoblood = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: radiosnovo
			});
			radiosnovoblood.initialize();
			var radioid_input = $('#radionovoid');
			radioid_input.tagsinput({
				focusClass: 'form-control input-sm',
				itemValue: 'id',
				itemText: 'name',
				allowDuplicates: false,
				typeaheadjs: {
					name: 'radio',
					displayKey: 'name',
					hint: true,
					highlight: true,
					limit: 10,
					source: radiosnovoblood.ttAdapter()
				}
			});

			//tv channels typeahead and tagsinput
			var tvs = [<?php echo $tvclinevar; ?>];
			var tvblood = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: tvs
			});
			tvblood.initialize();
			var tvcid_input = $('#tvid');
			tvcid_input.tagsinput({
				focusClass: 'form-control input-sm',
				itemValue: 'id',
				itemText: 'name',
				allowDuplicates: false,
				typeaheadjs: {
					name: 'tv',
					displayKey: 'name',
					hint: true,
					highlight: true,
					limit: 10,
					source: tvblood.ttAdapter()
				}
			});

			var tvsnovo = [<?php echo $novotvclinevar; ?>];
			var tvnovoblood = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: tvsnovo
			});
			tvnovoblood.initialize();
			var tvcnovoid_input = $('#tvnovoid');
			tvcnovoid_input.tagsinput({
				focusClass: 'form-control input-sm',
				itemValue: 'id',
				itemText: 'name',
				allowDuplicates: false,
				typeaheadjs: {
					name: 'tv',
					displayKey: 'name',
					hint: true,
					highlight: true,
					limit: 10,
					source: tvnovoblood.ttAdapter()
				}
			});

			if ($('#back-to-top').length) {
				var scrollTrigger = 1000,
				backToTop = function () {
					var scrollTop = $(window).scrollTop();
					if (scrollTop > scrollTrigger) {
						$('#back-to-top').fadeIn('fast');
					} else {
						$('#back-to-top').fadeOut('fast');
					}
				}
				backToTop();
				$(window).on('scroll', function () {
					backToTop();
				})
				$('#back-to-top').on('click', function (e) {
					e.preventDefault();
					$('html,body').animate({scrollTop: 0}, 700)
				})
			};

			$(document).ready(function(){
				var vsearchresult = '<?php echo $vsr; ?>';
				var idsourcesel = '<?php echo isset($id_source) ? $id_source : 0 ?>';
				var mscsel = '<?php echo isset($msc) ? $msc : 'none' ?>';
				var mtypesel = '<?php echo isset($mtype) ? $mtype : 'none' ?>';

				if (vsearchresult == 'true') {
					$('html, body').animate({
						scrollTop: $("#searchtop").offset().top
					}, 200);
				}

				if (idsourcesel != '0') {
					var idsourcearr = idsourcesel.split(',');
					if (mtypesel == 'audio') {
						if (mscsel == 'local') {
							$('#radioid').tagsinput('add',{id: idsourcesel});
						} else {
							$.each(idsourcearr, function(index, val) {
								let obj = radiosnovo.find(obj => obj.id == val);
								$('#radionovoid').tagsinput('add', obj);
							});
						}
					} else {
						if (mscsel == 'novo') {
							$.each(idsourcearr, function(index, val) {
								let obj = tvsnovo.find(obj => obj.id == val);
								$('#tvnovoid').tagsinput('add', obj);
							});
						}
					}
				}
			});
		</script>