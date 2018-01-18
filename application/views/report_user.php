<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row page-header">
			<div class="col-lg-12">
				<h1><?php echo get_phrase('reports');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading text-center">Detalhes do usuário: <?php echo $this->db->get_where('user',array('id_user' => $iduser))->row()->username;?></div>
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
								<?php
									if ($page == 'all') { ?>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;">#</th>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('client');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('keyword');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('date');?></th>
									</tr>
									<?php } else { ?>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;">#</th>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('client');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('keyword');?></th>
										<?php if ($page == 'crop') { ?>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px"><?php echo get_phrase('text');?></th>
										<?php } ?>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('date');?></th>
									</tr>
									<?php } ?>
								</thead>
								<tbody>
									<?php
										$cc=0;
										if ($page == 'all') {
											foreach ($userinfo as $uinfo) {
												$cc++;
												if (!empty($uinfo['id_discard'])) { ?>
													<tr class="warning">
													<td class="text-center"><?php echo $cc; ?></td>
													<td class="text-center"><?php echo $uinfo['client_discard']; ?></td>
													<td class="text-center"><?php echo $uinfo['keyword_discard']; ?></td>
													<td class="text-center"><?php echo date('d/m/Y H:i:s',$uinfo['timestamp_discard']); ?></td>
													</tr>
												<?php } else { ?>
													<tr class="info">
													<td class="text-center"><?php echo $cc; ?></td>
													<td class="text-center"><?php echo $uinfo['client_crop']; ?></td>
													<td class="text-center"><?php echo $uinfo['keyword_crop']; ?></td>
													<td class="text-center"><?php echo date('d/m/Y H:i:s',$uinfo['timestamp_crop']); ?></td>
													</tr>
												<?php } ?>
											<?php }
										} else {
											foreach ($userinfo as $uinfo) {
												$cc++; ?>
												<tr>
													<td class="text-center"><?php echo $cc; ?></td>
													<td class="text-center"><?php echo $uinfo['client']; ?></td>
													<td class="text-center"><?php echo $uinfo['keyword']; ?></td>
													<?php if ($page == 'crop') { ?>
													<td class="text-center">
														<button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="<?php echo $uinfo['content']; ?>" data-original-title="Texto" title="Texto Cortado" aria-describedby="popover">
														Detalhes
														</button>
													</td>
													<?php } ?>
													<td class="text-center"><?php echo date('d/m/Y H:i:s',$uinfo['timestamp']); ?></td>
												</tr>
											<?php }
										} ?>
								</tbody>
							</table>
						</div><!-- /.table-responsive -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div>
		</div>

		<script type="text/javascript">
			$('[data-toggle="popover"]').popover();

			$('#textview').on('shown.bs.modal', function (event) {
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

		</script>

		<div class="popover fade top in" role="tooltip" id="popover" style="top: 782px; left: 877.531px; display: none;">
			<div class="arrow" style="left: 50%;">	</div>
			<h3 class="popover-title" style="display: none;"></h3>
			<div class="popover-content">Vivamus sagittis lacus vel augue laoreet rutrum faucibus.</div>
		</div>