<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo get_phrase('search');?></h1>
			</div>
		</div>

		<?php
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
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<form action="<?php echo base_url('pages/search_result')?>" method="POST" accept-charset="utf-8">
							<div class="col-lg-6">
								<div class="form-group">
									<label><?php echo get_phrase('client');?></label>
									<input id="clientname" name="clientid" type="text"  class="form-control typeahead input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
									<input style="display: none" id="clientid" name="clientid" type="text" >
								</div>
								<div id="scrollable-dropdown-menu" class="form-group">
									<label><?php echo get_phrase('keyword');?></label>
									<i id="tooltipkw" style="display: none;" class="fa fa-question-circle fa-fw" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ao deixar o campo em branco, pesquisará em todas as palavras-chave!"></i>
									<input disabled id="keywordid" name="clientkeywordid" type="text"  class="form-control input-sm" autocomplete="off">
								</div>

								<div class="col-lg-3">
								<div id="radiosel" class="form-group">
									<label>Tipo de Veículo</label>
									<div class="radio">
										<label>
											<input type="radio" name="optionsRadios" id="optradio" value="radio" <?php if (isset($vtype) and $vtype == 'radio') {echo "checked";} ?> required>
											Rádio
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="optionsRadios" id="opttv" value="tv" <?php if (isset($vtype) and $vtype == 'tv') {echo "checked";} ?> required>
											Televisão
										</label>
									</div>
								</div>

								<div id="radioseltype" class="form-group">
									<label>Tipo da fonte</label>
									<div class="radio">
										<label>
											<input type="radio" name="optionsRadios" id="optradiotype1" value="radiotype" <?php if (isset($vtype) and $vsrctype == 'audimus') {echo "checked";} ?> required>
											Audimus
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="optionsRadios" id="optradiotype2" value="radiotype" <?php if (isset($vtype) and $vsrctype == 'knewin') {echo "checked";} ?> required>
											Knewin
										</label>
									</div>
								</div>
								</div>


								<div id="radioidsel" class="form-group" style="display: none;">
									<label><?php echo get_phrase('radio');?></label>
									<input id="radioid" name="radioid" type="text" class="form-control input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
								</div>

								<div id="tvidsel" class="form-group" style="display: none;">
									<label><?php echo get_phrase('television');?></label>
									<input id="tvchannel" name="tvchannel" type="text" class="form-control input-sm" placeholder="<?php echo get_phrase('type_to_search');?>" autocomplete="off">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label><?php echo get_phrase('date');?></label>
									<div class="input-daterange input-group" id="datepicker">
										<input required type="text" class="input-sm form-control" id="startdate" name="startdate" placeholder="<?php echo get_phrase('start');?>" <?php if (isset($startdate)) { echo 'value="'.$startdate.'"'; } ?> autocomplete="off"/>
										<span class="input-group-addon"><?php echo get_phrase('until');?></span>
										<input required type="text" class="input-sm form-control" id="enddate" name="enddate" placeholder="<?php echo get_phrase('end');?>" <?php if (isset($enddate)) { echo 'value="'.$enddate.'"'; } ?> autocomplete="off"/>
									</div>
								</div>
								<div class="form-group">
									<label><?php echo get_phrase('time');?></label>
									<div class="input-daterange input-group">
										<input required type="text" class="input-sm form-control clockpicker" id="starttime" name="starttime" placeholder="<?php echo get_phrase('start');?>" value="00:00" autocomplete="off"/>
										<span class="input-group-addon"><?php echo get_phrase('until');?></span>
										<input required type="text" class="input-sm form-control clockpicker" id="endtime" name="endtime" placeholder="<?php echo get_phrase('end');?>" value="23:59" autocomplete="off"/>
									</div>
								</div>
								<label><?php echo get_phrase('text');?></label>
								<div class="form-group input-group">
									<input type="text" id="keyword" name="keyword" class="form-control input-sm"  <?php if (isset($keyword)) { echo 'value="'.$keyword.'"'; } ?> autocomplete="off">
									<span class="input-group-btn">
										<button class="btn btn-default btn-sm" type="submit" name="text"><i class="fa fa-search"></i></button>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div id="searchtop"></div>

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

			$tvclinevar = null;
			$tcountarr = count($alltvc);
			$tvccount = 0;
			foreach ($alltvc as $tvc) {
				$tvccount++;
				if ($tvccount == $tcountarr) {
					$tvclinevar .= "{'id':".$tvc['id_source'].",";
					$tvclinevar .= "'name':"."'".$tvc['source']."'}";
				} else {
					$tvclinevar .= "{'id':".$tvc['id_source'].",";
					$tvclinevar .= "'name':"."'".$tvc['source']."'},";
				}
			}
		?>

		<script type="text/javascript">
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

			$( "input[type=radio]" ).on( "click", function() {
				rchecked = $( "input:checked" ).val();
				if (rchecked == 'radio') {
					$('#tvidsel').hide('fast');
					$('#radioidsel').show('fast');

				} else if (rchecked == 'tv') {
					$('#radioidsel').hide('fast');
					$('#tvidsel').show('fast');
				}
			});

			//clients typeahead
			var clients = [<?php echo $clientslinevar; ?>];
			var clientsblood = new Bloodhound({
				"datumTokenizer": Bloodhound.tokenizers.obj.whitespace('name'),
				"queryTokenizer": Bloodhound.tokenizers.whitespace,
				"identify": function(obj) {
					return obj.id;
				},
				local: clients
			});
			$('#clientname').typeahead({
				hint: true,
				highlight: true,
				minLength: 0,
				limit: 20
			}, {
				name: 'clients',
				value: 'id',
				source: clientsblood,
				display: 'name'
			}).on('typeahead:select', function(ev, suggestion) {
					$('#clientid').val(suggestion.id);
					$('#keywordid').prop('disabled',false);
					$('#keywordid').prop('placeholder','Digite para pesquisar');
					$('#tooltipkw').css('display', 'inline');
					var keywordid_input = $('#keywordid');
					// keywordid_input.tagsinput('destroy');
					// $('#keywordid').typeahead('val', '');
					// instantiate the bloodhound suggestion engine
					var keywordsblood = new Bloodhound({
						datumTokenizer: function(keywordsblood) {
							return Bloodhound.tokenizers.whitespace(keywordsblood.val);
						},
						queryTokenizer: Bloodhound.tokenizers.whitespace,
						// prefetch: {
							// url: "<?php //echo base_url('pages/keywords_client/')?>" + suggestion.id,
							// filter: function(response) {
							// 	// console.log(response);
							// 	return response;
							// }
						// 	filter: function(response) {
						// 		return $.map(response, function(data) {
						// 			console.log({ keyword: data.keyword });
						// 			return { keyword: data.keyword };
						// 		});
						// 	}
						// }
						remote: {
							url: "<?php echo base_url('pages/keywords_client/')?>" + suggestion.id,
							filter: function(response) {
								// console.log(response);
								return response;
							}
						}
					});
					keywordsblood.clearPrefetchCache();
					// keywordsblood.clearRemoteCache();
					keywordsblood.initialize();
					keywordid_input.tagsinput({
						itemValue: 'id_keyword',
						itemText: 'keyword',
						allowDuplicates: false,
						typeaheadjs: {
							limit: 100,
							hint: false,
							highlight: true,
							minLength: 1,
							name: 'keyword',
							displayKey: function(keywordsblood) {
								return keywordsblood.keyword;
							},
							source: keywordsblood.ttAdapter()
						}
					});
			}).on('focus', function() {
				// if (defaultOption.length) {
					// $(this).data().ttTypeahead.input.trigger('queryChanged', nm);
				// }
			});

			//radios typeahead and tagsinput
			var radios = [<?php echo $radioslinevar; ?>];
			var radiosblood = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: radios
			});
			radiosblood.initialize();
			var radioid_input = $('#radioid');
			//radioid_input.tagsinput('destroy')
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
					limit: 20,
					source: radiosblood.ttAdapter()
				}
			});

			//tv channels typeahead and tagsinput
			var tvc = [<?php echo $tvclinevar; ?>];
			var tvcblood = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: tvc
			});
			tvcblood.initialize();
			var tvcid_input = $('#tvchannel');
			//tvcid_input.tagsinput('destroy')
			tvcid_input.tagsinput({
				focusClass: 'form-control input-sm',
				// itemValue: 'id',
				itemValue: 'name',
				itemText: 'name',
				allowDuplicates: false,
				typeaheadjs: {
					name: 'tvc',
					displayKey: 'name',
					hint: true,
					highlight: true,
					limit: 20,
					source: tvcblood.ttAdapter()
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

			$( document ).ready(function(){
				vsearchresult = '<?php echo $vsr; ?>';

				if (vsearchresult == 'true') {
					$('html, body').animate({
						scrollTop: $("#searchtop").offset().top
					}, 400);
				}
			});
		</script>