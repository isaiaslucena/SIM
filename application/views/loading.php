<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
	<div id="divback-to">
		<button href="#" id="back-to-top" class="btn btn-danger btn-circle btn-lg" style="display: none" title="<?php echo get_phrase('back_to_top')?>"><i class="fa fa-arrow-up"></i></button>
		<button href="#" id="back-to-home" class="btn btn-danger btn-circle btn-lg" style="display: none" title="Voltar"><i class="fa fa-arrow-left"></i></button>
	</div>
	
	<div id="page-wrapper">
		<div class="spinner" id="loading_spinner">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
	</div>

	<?php
		if (preg_match('/calendar_index/', $page) == 1) {
			$spage = base_url($page."/".$vtype);
		} else {
			$spage = base_url($page);
		}

		if (!isset($ff_ids_files_xml)) {
			$ff_id_radio = 'none';
			$ff_id_client = 'none';
			$ff_id_keyword = 'none';
			$ff_ids_files_xml = 'none';
			$ff_ids_files_mp3 = 'none';
			$ff_timestamp = 'none';
			$client_selected = 'none';
			$keyword_selected = 'none';
			$radio = 'none';
			$state = 'none';
		}

		if ($changepass == 1) { ?>
			<div id="changepass_modal" class="modal fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="changepass_modal" aria-hidden="true" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h2 class="modal-title text-center" id="edit_modal">Atenção!</h2>
						</div>
						<div class="modal-body text-center">
							<form id="passwd_modal_form" class="form-horizontal" action="<?php echo base_url('pages/changepasswd')?>" method="POST">
								<h4>É necessário alterar sua senha para poder continuar.</h4>
								<br>
								<input type="text" id="user_id_modal" name="user_id_modal" autocomplete="off" value="<?php echo $changepass_id ?>" style="display: none">
								<div class="form-group">
									<label class="col-lg-4 control-label"><?php echo get_phrase('new_password');?></label>
									<div class="col-lg-5">
										<input required type="password" class="form-control" id="user_passwd_modal" name="user_passwd_modal" autocomplete="off">
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-4 control-label">Confirmação</label>
									<div class="col-lg-5">
										<input required type="password" class="form-control" id="user_passwd2_modal" name="user_passwd2_modal" autocomplete="off">
									</div>
								</div>
								<p class="help-block" style="display: none;"><small><em class="text-danger">As senhas digitadas não conferem!</em></small></p>
							</form>
						</div>
						<div class="modal-footer">
							<button disabled id="btnsave" type="submit" form="passwd_modal_form" class="btn btn-primary disabled">Alterar</button>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				$('#user_passwd2_modal').blur(function(event) {
					pass1 = $('#user_passwd_modal').val();
					pass2 = $('#user_passwd2_modal').val();
					if (pass1 == pass2) {
						$('.form-group').removeClass('has-error');
						$('.form-group').addClass('has-success');
						$('.help-block').fadeOut('fast');
						$('#btnsave').attr('disabled', false);
						$('#btnsave').removeClass('disabled');
					} else {
						$('.form-group').removeClass('has-success');
						$('.form-group').addClass('has-error');
						$('.help-block').fadeIn('fast');
						$('#btnsave').attr('disabled', true);
						$('#btnsave').addClass('disabled');
					}
				});

				$('#changepass_modal').modal({
					show: true,
					backdrop: 'static',
					keyboard: false
				});
			</script>
		<?php } else { ?>
			<script type="text/javascript">
				var bpage = '<?php echo $page; ?>';
				var pagejoin = '<?php echo base_url("pages/join"); ?>';
				var sallkeywordquant, skeywordquant;
				var changepassword = <?php echo $changepass; ?>;
				var plimit = 0;
				var poffset = 5;
				var selected_date = '<?php echo $selected_date; ?>';
				var page = '<?php echo $spage; ?>';

				starttime = new Date();
				if (page == pagejoin) {
					$.ajax({
						url: page,
						type: 'POST',
						data: {
							ff_id_radio: <?php echo $ff_id_radio ?>,
							ff_id_client: <?php echo $ff_id_client ?>,
							id_keyword: <?php echo $ff_id_keyword ?>,
							ff_ids_files_xml: '<?php echo $ff_ids_files_xml ?>',
							ff_ids_files_mp3: '<?php echo $ff_ids_files_mp3 ?>',
							timestamp: <?php echo $ff_timestamp ?>,
							client_selected: '<?php echo $client_selected ?>',
							keyword_selected: '<?php echo $keyword_selected ?>',
							radio: '<?php echo $radio ?>',
							state: '<?php echo $state ?>',
							page: page
						},
						success: function(data) {
							$('#fullpage').html(data);
							$('#loading_spinner').css('display', 'none');
							$('#fullpage').css('display', 'block');
						},
						error: function() {
							// alert("Error!")
							swal("Erro! Tente novamente atualizando a página!","error");
						}
					})
				} else {
					$.ajax({
						url: page+'/'+selected_date+'/'+plimit+'/'+poffset,
						success: function(data) {
							$('#loading_spinner').css('display', 'none');
							$('#page-wrapper').append(data);
							// $('#page-wrapper').css('display', 'block');
							endtime = new Date();
							timediff = ((endtime.getTime() - starttime.getTime()) / 1200).toFixed(3);
							$('.pageload').text('<?php echo get_phrase('page_generated_in')?> '+timediff+'s');
						},
						error: function() {
							swal("Erro!","Tente novamente atualizando a página!","error");
						}
					})
				}
			</script>
		<?php } ?>
</body>