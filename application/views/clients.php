<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row page-header">
			<div class="col-lg-12">
				<div class="col-lg-4">
					<h1><?php echo get_phrase('clients');?></h1>
				</div>
				<div class="col-lg-5">
				<?php
					if (isset($success_msg)) { ?>
					<div class="text-center alert alert-success alert-dismissable fade in" id="success-alert">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<?php echo $success_msg ?>!
					</div>
					<script type="text/javascript">
							//$(".alert-success").fadeIn(800);
							$(".alert-success").alert();
							window.setTimeout(function(){
								$(".alert-success").alert('close');
							}, 2000);
					</script>
				<?php } ?>
				</div>
				<div class="col-lg-3">
					<h1>
						<button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target=".add_modal">
							<i class="fa fa-plus-circle"></i>
							<?php echo get_phrase('add');?>
						</button>
					</h1>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 20px;"><?php echo get_phrase('id');?></th>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 60px;"><?php echo get_phrase('client');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('priority');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 60px;"><?php echo get_phrase('keywords');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 60px;"><?php echo get_phrase('radio');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 60px;"><?php echo get_phrase('television');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 60px;"><?php echo get_phrase('options');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									foreach ($clients as $client) { ?>
									<tr>
										<td class="text-center"><?php echo $client['id_client']; ?></td>
										<td class="text-center"><?php echo $client['name']; ?></td>
										<td class="text-center"><?php echo $client['priority']; ?></td>
										<td class="text-center">
											<?php
												$keywords = $this->pages_model->keywords_client($client['id_client']);
												$keywordslineid = null;
												$keywordslinekw = null;
												$count = 0;
												$countarr = count($keywords);
												foreach ($keywords as $keyword) {
													$count++;
													if ($count == $countarr) {
														$keywordslineid .= $keyword['id_keyword'];
														$keywordslinekw .= $keyword['keyword'];

													}else {
														$keywordslineid .= $keyword['id_keyword'].",";
														$keywordslinekw .= $keyword['keyword'].",";
													}
												} ?>
											<button id="keywords_edit_button" type="button" class="btn btn-default btn-xs" data-clientid="<?php echo $client['id_client']; ?>" data-clientname="<?php echo $client['name']; ?>" data-clientpriority="<?php echo $client['priority']; ?>" data-clientkeywordsid="<?php echo $keywordslineid; ?>" data-clientkeywordskw="<?php echo $keywordslinekw; ?>" data-toggle="modal" data-target=".keywords_modal">
												<i class="fa fa-edit"></i>
												<?php echo get_phrase('edit');?>
											</button>
										</td>
										<td class="text-center">
											<?php 
											if ($client['radio'] == 1) { ?>
												<input data-clientid="<?php echo $client['id_client']; ?>" data-vhtype="radio" type="checkbox" checked />
											<?php } else { ?>
												<input data-clientid="<?php echo $client['id_client']; ?>" data-vhtype="radio" type="checkbox"/>
											<?php }; ?>
										</td>
										<td class="text-center">
											<?php 
											if ($client['tv'] == 1) { ?>
												<input data-clientid="<?php echo $client['id_client']; ?>" data-vhtype="tv" type="checkbox" checked />
											<?php } else { ?>
												<input data-clientid="<?php echo $client['id_client']; ?>" data-vhtype="tv" type="checkbox"/>
											<?php }; ?>
										</td>
										<td class="text-center">
											<button id="client_edit_button" class="btn btn-default btn-xs" data-clientid="<?php echo $client['id_client']; ?>" data-clientname="<?php echo $client['name']; ?>" data-clientpriority="<?php echo $client['priority']; ?>" data-toggle="modal" data-target=".edit_modal">
												<i class="fa fa-edit"></i>
												<?php echo get_phrase('edit');?>
											</button>
											<button type="button" class="btn btn-danger btn-xs" data-clientid="<?php echo $client['id_client']; ?>" data-toggle="modal" data-target=".delete_modal">
												<i class="fa fa-times"></i>
												<?php echo get_phrase('delete');?>
											</button>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div><!-- /.table-responsive -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>

			<div id="add_modal" class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="add_modal"><?php echo get_phrase('add');?></h4>
						</div>
						<div class="modal-body">
							<form id="add_modal_form" class="form-horizontal" action="<?php echo base_url('pages/create_client')?>" method="POST">
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('client');?></label>
									<div class="col-lg-6">
										<input required type="text" class="form-control" id="clientname_add_modal" name="clientname_add_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('priority');?></label>
									<div class="col-lg-6">
										<select required id="clientpriority_add_modal" name="clientpriority_add_modal" class="form-control">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
										<p class="help-block"><small><em><?php echo get_phrase('number_lower_highest_the_priority');?></em></small></p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('keywords');?></label>
									<div class="col-lg-6">
										<input required id="clientkeywords_add_modal" name="clientkeywords_add_modal" type="text" placeholder="<?php echo get_phrase('type_to_search');?>">
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
							<button type="submit" form="add_modal_form" class="btn btn-primary"><?php echo get_phrase('save');?></button>
						</div>
					</div>
				</div>
			</div>

			<div id="keywords_modal" class="modal fade keywords_modal" tabindex="-1" role="dialog" aria-labelledby="keywords_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title"><?php echo get_phrase('keywords');?></h4>
						</div>
						<div class="modal-body">
							<form id="keywords_modal_form" action="<?php echo base_url('pages/update_client_keywords')?>" method="POST" class="form-horizontal">
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('client');?></label>
									<div class="col-lg-6">
										<input id="clientid_modal" name="clientid_modal" type="hidden" readonly>
										<input id="clientname_modal" name="clientname_modal" type="text" class="form-control" readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('keywords');?></label>
									<div class="col-lg-6">
										<input id="clientkeywordsold_modal" name="clientkeywordsold_modal" type="hidden">
										<input id="clientkeywords_modal" name="clientkeywords_modal" type="text" autocomplete="off">
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
							<button type="submit" form="keywords_modal_form" class="btn btn-primary"><?php echo get_phrase('save');?></button>
						</div>
					</div>
				</div>
			</div>

			<div id="edit_modal" class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="edit_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="edit_modal"><?php echo get_phrase('edit');?></h4>
						</div>
						<div class="modal-body">
							<form id="edit_modal_form" action="<?php echo base_url('pages/update_client')?>" method="POST" class="form-horizontal">
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('id');?></label>
									<div class="col-lg-6">
										<input id="clientid_edit_modal" name="clientid_edit_modal" type="text" readonly class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('name');?></label>
									<div class="col-lg-6">
										<input id="clientname_edit_modal" name="clientname_edit_modal" type="text" class="form-control" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?php echo get_phrase('priority');?></label>
									<div class="col-lg-6">
										<select id="clientpriority_edit_modal" name="clientpriority_edit_modal" class="form-control">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
										</select>
										<p class="help-block"><small><em><?php echo get_phrase('number_lower_highest_the_priority');?></em></small></p>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
							<button type="submit" form="edit_modal_form" class="btn btn-primary"><?php echo get_phrase('save');?></button>
						</div>
					</div>
				</div>
			</div>

			<div id="delete_modal" class="modal fade delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header-danger text-center">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h4 style="font-weight: bold;" class="modal-title" id="delete_modal"><?php echo mb_strtoupper(get_phrase('atention','UTF-8'))."!";?></h4>
						</div>
						<br>
						<p class="text-center"><?php echo get_phrase('are_you_sure_you_want_delete').'?';?></p>
						<br>
						<div class="modal-footer">
							<form id="delete_modal_form" action="<?php echo site_url('pages/delete_client');?>" method="post">
								<input type="hidden" id="clientid_delete_modal" name="clientid_delete_modal"></input>
								<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo get_phrase('no');?></button>
								<button type="submit" class="btn btn-primary btn-sm"><?php echo get_phrase('yes');?></button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<?php
				$allkeywords = $this->pages_model->keywords();
				$keywordslinevar = null;
				$acountarr = count($allkeywords);
				$acount = 0;
				foreach ($allkeywords as $keyword) {
					$acount++;
					if ($acount == $acountarr) {
						$keywordslinevar .= "{'id':".$keyword['id_keyword'].",";
						$keywordslinevar .= "'keyword':"."'".$keyword['keyword']."'}";

					}
					else {
						$keywordslinevar .= "{'id':".$keyword['id_keyword'].",";
						$keywordslinevar .= "'keyword':"."'".$keyword['keyword']."'},";
					}
				}
			?>

			<script type="text/javascript">
				function checkUserName() {
					//var username = document.getElementsByName("username").value;
					var username = document.getElementsById("clientkeywords_add_modal").value;
					var pattern = new RegExp(/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/); //unacceptable chars
					if (pattern.test(username)) {
						alert("Please only use standard alphanumerics");
						return false;
					}
					return true; //good user input
				}

				var keywords = [<?php echo $keywordslinevar; ?>];
				var keywordsblood = new Bloodhound({
					datumTokenizer: Bloodhound.tokenizers.obj.whitespace('keyword'),
					queryTokenizer: Bloodhound.tokenizers.whitespace,
					local: keywords
				});
				keywordsblood.initialize();

				var clientkwadd_modal = $('#clientkeywords_add_modal');
				var clientkw_modal = $('#clientkeywords_modal')
				clientkw_modal.tagsinput('destroy')
				clientkw_modal.tagsinput({
					itemValue: 'id',
					itemText: 'keyword',
					allowDuplicates: false,
					typeaheadjs: {
						name: 'keywords',
						displayKey: 'keyword',
						source: keywordsblood.ttAdapter()
					}
				});

				$('#add_modal').on('shown.bs.modal', function () {
					//console.log('Showing Modal Add Keyword!')
					$('#add_modal').bind("keypress", function(e) {
						if (e.keyCode == 13) {
							e.preventDefault();
							return false;
						}
					});
					$('#clientname_add_modal').val(null)
					var clientkwadd_modal = $('#clientkeywords_add_modal');
					clientkwadd_modal.tagsinput('destroy')
					clientkwadd_modal.tagsinput({
						itemValue: 'id',
						itemText: 'keyword',
						allowDuplicates: false,
						typeaheadjs: {
							name: 'keywords',
							displayKey: 'keyword',
							source: keywordsblood.ttAdapter()
						}
					})
					$("select#clientpriority_add_modal").prop('selectedIndex', 4)
					$('#clientname_add_modal').focus()
				});

				$('#keywords_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget)
					var clientid = button.data('clientid')
					var clientname = button.data('clientname')
					var clientkeywordsid = button.data('clientkeywordsid')
					var clientkeywordsidold = button.data('clientkeywordsid')
					var clientkeywordskw = button.data('clientkeywordskw')
					$(this).find('.modal-body [name="clientid_modal"]').val(clientid)
					$(this).find('.modal-body [name="clientname_modal"]').val(clientname)
					$(this).find('.modal-body [name="clientkeywordsold_modal"]').val(clientkeywordsidold)
					var clientkw_modal = $('#clientkeywords_modal')
					clientkw_modal.tagsinput('destroy')
					clientkw_modal.tagsinput({
						typeaheadjs: {
							name: 'keywords',
							displayKey: 'keyword',
							source: keywordsblood.ttAdapter()
						},
						itemValue: 'id',
						itemText: 'keyword',
						allowDuplicates: false,
						freeInput: true,
						confirmKeys: [13, 44]
					})

					var searchkw =  clientkeywordsid.toString().indexOf(',')
					if (searchkw != -1) {
						var arraykeywordsid = clientkeywordsid.split(',')
						var arraykeywordskw = clientkeywordskw.split(',')
						var arraykeywordsidlength = arraykeywordsid.length
						for (i = 0; i < arraykeywordsidlength; i++) {
							clientkw_modal.tagsinput('add', { id: arraykeywordsid[i] , keyword: arraykeywordskw[i] })
						}
					} else {
						clientkw_modal.tagsinput('add', { id: clientkeywordsid, keyword: clientkeywordskw })
					}

					$('#clientkeywords_modal').focus()
				});

				$('#edit_modal').on('shown.bs.modal', function (event) {
					//console.log('Showing Modal Edit Keyword!')
					var button = $(event.relatedTarget)
					var clientid = button.data('clientid')
					var clientname = button.data('clientname')
					var clientpriority = button.data('clientpriority')
					var clientkeywords = button.data('clientkeywords')
					var modal = $(this)
					modal.find('.modal-body [name="clientid_edit_modal"]').val(clientid)
					modal.find('.modal-body [name="clientname_edit_modal"]').val(clientname)
					modal.find('.modal-body [name="clientpriority_edit_modal"]').val(clientpriority)
					modal.find('.modal-body [name="clientkeywords_edit_modal"]').val(clientkeywords)
					$('#clientname_edit_modal').focus()
				});

				$('#delete_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget)
					var clientid = button.data('clientid')
					var modal = $(this)
					modal.find('.modal-footer [name="clientid_delete_modal"]').val(clientid)
				});

				$("input[type='checkbox']").click(function(event) {
					clientid = event.target.dataset.clientid;
					clientvhtype = event.target.dataset.vhtype;
					cbchecked = event.currentTarget.attributes[1].ownerElement.checked;
					console.log(cbchecked);
					if (cbchecked) {
						$.post('/pages/client_vhtype',
							{
								id_client: clientid,
								vhtype: clientvhtype,
								checked: 1
							}, 
							function(data, textStatus, xhr) {
							console.log(data);
						});
					} else {
						$.post('/pages/client_vhtype',
							{
								id_client: clientid,
								vhtype: clientvhtype,
								checked: 0
							}, 
							function(data, textStatus, xhr) {
							console.log(data);
						});
					}
				});
			</script>
		</div>
