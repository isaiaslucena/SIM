<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row page-header">
			<div class="col-lg-12">
				<div class="col-lg-4">
					<h1><?php echo get_phrase('users');?></h1>
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

		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table class="table table-hover" id="<?php echo $datatablename;?>">
						<thead>
							<tr>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1"><?php echo get_phrase('id');?></th>
								<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1"><?php echo get_phrase('username');?></th>
								<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1"><?php echo get_phrase('logged_in');?></th>
								<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1"><?php echo get_phrase('session');?></th>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1"><?php echo get_phrase('email');?></th>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1"><?php echo get_phrase('options');?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($users as $user) {
									$sqlquery = 'SELECT * FROM ci_sessions WHERE data LIKE \'%;logged_in|b:1;%\' AND data LIKE \'%id_user|s:%:"'.$user['id_user'].'"%\' ORDER BY timestamp DESC LIMIT 1';
									$check_session = $this->db->query($sqlquery)->result_array();
									if (empty($check_session) or is_null($check_session)) {
										$sqlquery = 'SELECT * FROM ci_sessions WHERE data LIKE  \'%id_user|s:%:"'.$user['id_user'].'"%\' ORDER BY timestamp DESC LIMIT 1';
										$session_user = $this->db->query($sqlquery)->result_array();
										$logged_in = 0;
									} else {
										$session_user = $check_session;
										$logged_in = 1;
									} ?>
									<tr>
										<td class="text-center"><?php echo $user['id_user']; ?></td>
										<td class="text-center"><?php echo $user['username']; ?></td>
										<td class="text-center">
										<?php
											if ($logged_in === 0) { ?>
												<input type="checkbox" disabled>
										<?php } else if ($logged_in === 1) { ?>
												<input type="checkbox" disabled checked>
										<?php }	?>
										</td>
										<td class="text-center">
										<?php
										if (!isset($session_user[0])) {
											echo '-';
										} else {
											echo date('d/m/Y H:i:s',$session_user[0]['timestamp']);
										}
										?>
										</td>
										<td class="text-center"><?php echo $user['email']; ?></td>
										<td class="text-center">
											<button id="client_edit_button" type="button" class="btn btn-default btn-xs" data-userid="<?php echo $user['id_user']; ?>" data-username="<?php echo $user['username']; ?>" data-useremail="<?php echo $user['email']; ?>" data-usergroup="<?php echo $user['id_group']; ?>" data-toggle="modal" data-target=".edit_modal">
												<i class="fa fa-edit"></i>
												<?php echo get_phrase('edit');?>
											</button>
											<button id="client_passwd_button" type="button" class="btn btn-default btn-xs" data-userid="<?php echo $user['id_user']; ?>" data-username="<?php echo $user['username']; ?>" data-toggle="modal" data-target=".passwd_modal">
												<i class="fa fa-lock"></i>
												<?php echo get_phrase('password');?>
											</button>
											<button type="button"  class="btn btn-danger btn-xs" data-userid="<?php echo $user['id_user']; ?>" data-toggle="modal" data-target=".delete_modal">
												<i class="fa fa-times"></i>
												<?php echo get_phrase('delete');?>
											</button>
										</td>
									</tr>
								<?php } ?>
						</tbody>
					</table>
				</div><!-- /.table-responsive -->
			</div>

			<div id="add_modal" class="modal fade add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="add_modal"><?php echo get_phrase('add');?></h4>
						</div>
						<div class="modal-body">
							<form id="add_modal_form" class="form-horizontal" action="<?php echo base_url('pages/create_user')?>" method="POST">
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('username');?></label>
									<div class="col-lg-5">
										<input required type="text" class="form-control" id="username_add_modal" name="username_add_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('password');?></label>
									<div class="col-lg-5">
										<input required type="password" class="form-control" id="passwd_add_modal" name="passwd_add_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('email');?></label>
									<div class="col-lg-5">
										<input type="email" class="form-control" id="email_add_modal" name="email_add_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('group');?></label>
									<div class="col-lg-5">
										<select required id="group_add_modal" name="group_add_modal" class="form-control">
										<?php foreach ($groups as $group) { 
											if ($group['id_group'] == 2) { ?>
											 	<option selected="selected" value="<?php echo $group['id_group'] ?>"><?php echo $group['name'] ?></option>
											 <?php } else { ?>
												<option value="<?php echo $group['id_group'] ?>"><?php echo $group['name'] ?></option>
											<?php } 
										} ?>
										</select>
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

			<div id="edit_modal" class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="edit_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="edit_modal"><?php echo get_phrase('add');?></h4>
						</div>
						<div class="modal-body">
							<form id="edit_modal_form" class="form-horizontal" action="<?php echo base_url('pages/update_user')?>" method="POST">
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('id');?></label>
									<div class="col-lg-5">
										<input readonly type="text" class="form-control" id="userid_edit_modal" name="userid_edit_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('username');?></label>
									<div class="col-lg-5">
										<input required type="text" class="form-control" id="username_edit_modal" name="username_edit_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('email');?></label>
									<div class="col-lg-5">
										<input type="text" class="form-control" id="useremail_edit_modal" name="useremail_edit_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('group');?></label>
									<div class="col-lg-5">
										<select id="usergroup_edit_modal" name="usergroup_edit_modal" class="form-control">
										<?php foreach ($groups as $group) { ?>
											<option value="<?php echo $group['id_group'] ?>"><?php echo $group['name'] ?></option>
										<?php }?>
										</select>
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

			<div id="passwd_modal" class="modal fade passwd_modal" tabindex="-1" role="dialog" aria-labelledby="passwd_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="passwd_modal"><?php echo get_phrase('change').' '.get_phrase('password');?></h4>
						</div>
						<div class="modal-body">
							<form id="passwd_modal_form" class="form-horizontal" action="<?php echo base_url('pages/changepasswd_user')?>" method="POST">
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('id');?></label>
									<div class="col-lg-5">
										<input readonly type="text" class="form-control" id="userid_passwd_modal" name="userid_passwd_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('username');?></label>
									<div class="col-lg-5">
										<input readonly type="text" class="form-control" id="username_passwd_modal" name="username_passwd_modal" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('new_password');?></label>
									<div class="col-lg-5">
										<input required type="password" class="form-control" id="passwd_passwd_modal" name="passwd_passwd_modal" autocomplete="off">
									</div>
								</div>
								<div class="checkbox col-lg-offset-4">
									<label><input id="change_passwd_modal" name="change_passwd_modal" type="checkbox"><?php echo get_phrase('change_next_login');?></label>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
							<button type="submit" form="passwd_modal_form" class="btn btn-primary"><?php echo get_phrase('save');?></button>
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
							<form id="delete_modal_form" action="<?php echo site_url('pages/delete_user');?>" method="post">
								<input type="hidden" id="userid_delete_modal" name="userid_delete_modal"></input>
								<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo get_phrase('no');?></button>
								<button type="submit" class="btn btn-primary btn-sm"><?php echo get_phrase('yes');?></button>
							</form>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				$('#add_modal').on('shown.bs.modal', function () {
					$('#username_add_modal').val(null)
					$('#passwd_add_modal').val(null)
					$('#email_add_modal').val(null)
					// $("select#group_add_modal").prop('selectedIndex', 1)
					$('#username_add_modal').focus()
				});

				$('#edit_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget)
					var userid = button.data('userid')
					var username = button.data('username')
					var useremail = button.data('useremail')
					var usergroup = button.data('usergroup')
					var modal = $(this)
					modal.find('.modal-body [name="userid_edit_modal"]').val(userid)
					modal.find('.modal-body [name="username_edit_modal"]').val(username)
					modal.find('.modal-body [name="useremail_edit_modal"]').val(useremail)
					modal.find('.modal-body [name="usergroup_edit_modal"]').val(usergroup)
					$('#username_edit_modal').focus()
				});

				$('#passwd_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget)
					var userid = button.data('userid')
					var username = button.data('username')
					var modal = $(this)
					modal.find('.modal-body [name="userid_passwd_modal"]').val(userid)
					modal.find('.modal-body [name="username_passwd_modal"]').val(username)
					$('#passwd_passwd_modal').val(null)
					$("#change_passwd_modal").prop('checked',true)
					$('#passwd_passwd_modal').focus()
				});

				$('#delete_modal').on('shown.bs.modal', function (event) {
					var button = $(event.relatedTarget)
					var userid = button.data('userid')
					var modal = $(this)
					modal.find('.modal-footer [name="userid_delete_modal"]').val(userid)
				});
			</script>
		</div>