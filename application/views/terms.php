<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row page-header">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-4">
						<h1><?php echo get_phrase('terms');?></h1>
					</div>
					<div class="col-lg-5">
						<?php
							if (isset($success_msg)) { ?>
							<div class="text-center alert alert-success alert-dismissable fade in" id="success-alert">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?php echo $success_msg ?>!
							</div>
							<script type="text/javascript">
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
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table class="table table-hover" id="<?php echo $datatablename;?>">
						<thead>
							<tr>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('id');?></th>
								<th class="sorting_desc" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('keyword');?></th>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 50px;"><?php echo get_phrase('priority');?></th>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 80px;"><?php echo get_phrase('options');?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($terms as $term) { ?>
									<tr>
										<td class="text-center"><?php echo $keyword['id_term']; ?></td>
										<td><?php echo $keyword['keyword']; ?></td>
										<td class="text-center"><?php echo $keyword['priority']; ?></td>
										<td class="text-center">
											<button id="client_edit_button" class="btn btn-default btn-xs" data-keywordid="<?php echo $keyword['id_keyword']; ?>" data-keywordname="<?php echo $keyword['keyword']; ?>" data-keywordpriority="<?php echo $keyword['priority']; ?>" data-toggle="modal" data-target=".edit_modal">
												<i class="fa fa-edit"></i>
												<?php echo get_phrase('edit');?>
											</button>
											<button type="button"  class="btn btn-danger btn-xs" data-keywordid="<?php echo $keyword['id_keyword']; ?>" data-toggle="modal" data-target=".delete_modal">
												<i class="fa fa-times"></i>
												<?php echo get_phrase('delete');?>
											</button>
										</td>
									</tr>
								<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<div id="add_modal" class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="add_modal"><?php echo get_phrase('add');?></h4>
						</div>
						<div class="modal-body">
							<form id="add_modal_form" class="form-horizontal" action="<?php echo base_url('pages/create_keyword')?>" method="POST">
								<div class="form-group">
									<label class="col-lg-2 control-label"><?php echo get_phrase('keyword');?></label>
									<div class="col-lg-8">
										<input required type="text" class="form-control" id="keywordname_add_modal" name="keywordname_add_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label"><?php echo get_phrase('priority');?></label>
									<div class="col-lg-8">
										<select required id="keywordpriority_add_modal" name="keywordpriority_add_modal" class="form-control">
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
							<button id="btnaddmodal" type="submit" form="add_modal_form" class="btn btn-primary"><?php echo get_phrase('save');?></button>
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
							<form id="edit_modal_form" action="<?php echo base_url('pages/update_keyword')?>" method="POST" class="form-horizontal">
								<div class="form-group">
									<label class="col-lg-2 control-label"><?php echo get_phrase('id');?></label>
									<div class="col-lg-8">
										<input id="keywordid_edit_modal" name="keywordid_edit_modal" type="text" readonly class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label"><?php echo get_phrase('name');?></label>
									<div class="col-lg-8">
										<input id="keywordname_edit_modal" name="keywordname_edit_modal" type="text" class="form-control" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label"><?php echo get_phrase('priority');?></label>
									<div class="col-lg-8">
										<select id="keywordpriority_edit_modal" name="keywordpriority_edit_modal" class="form-control">
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
							<form id="delete_modal_form" action="<?php echo site_url('pages/delete_keyword');?>" method="post">
								<input type="hidden" id="keywordid_delete_modal" name="keywordid_delete_modal"></input>
								<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo get_phrase('no');?></button>
								<button type="submit" class="btn btn-primary btn-sm"><?php echo get_phrase('yes');?></button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				function checkUserName() {
					var keyword = $('#keywordname_add_modal').val();
					var pattern = new RegExp(/[~`!#$%\^&*+=\-\[\]\\';,\./{}\(\)|\\":<>\?]/);
					if (pattern.test(keyword)) {
						return false;
					}
					return true;
				}

				$('#add_modal').on('shown.bs.modal', function () {
					$('#keywordname_add_modal').val(null);
					$("select#keywordpriority_add_modal").prop('selectedIndex', 4);
					$('#keywordname_add_modal').focus();
					$('#keywordname_add_modal').blur(function(event) {
						if (checkUserName()) {
							console.log('Username OK');
						} else {
							swal("Atenção!", "A palavra-chave contém caracteres não permitidos.", "error");
							$('#keywordname_add_modal').val(null);
							$('#keywordname_add_modal').focus();
						}
					});
				});

				$('#edit_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var keywordid = button.data('keywordid');
					var keywordname = button.data('keywordname');
					var keywordpriority = button.data('keywordpriority');
					var modal = $(this);
					modal.find('.modal-body [name="keywordid_edit_modal"]').val(keywordid);
					modal.find('.modal-body [name="keywordname_edit_modal"]').val(keywordname);
					modal.find('.modal-body [name="keywordpriority_edit_modal"]').val(keywordpriority);
					$('#keywordname_edit_modal').focus();
				});

				$('#delete_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget);
					var clientid = button.data('keywordid');
					var modal = $(this);
					modal.find('.modal-footer [name="keywordid_delete_modal"]').val(clientid);
				});
			</script>
		</div>