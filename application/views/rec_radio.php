<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row page-header">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-8">
						<h1><?php echo get_phrase('radios');?></h1>
					</div>
					<div class="col-lg-2">
						<h1>
							<button id="btnschanges" type="button" class="btn btn-success pull-right" style="display: none">
								<i class="fa fa-check"></i>
								<?php //echo get_phrase('save');?>
								Salvar alterações
							</button>	
						</h1>
					</div>
					<div class="col-lg-2">
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
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 20px">Estado</th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 20px">Rádio</th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 20px">URL stream</th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 20px">Opções</th>
									</tr>
								</thead>
								<tbody id="tablebody">
									<?php 
									$trid = 0;
									$trgid = 0;
									foreach ($rec_radios->ESTADO as $estado) {
										$estadoname = key($estado);
										foreach ($estado as $radios) {
											foreach ($radios as $radio) {
												$radioname = $radio->radio;
												$urlstream = $radio->stream; 
												$trid++;?>
												<tr id="<?php echo 'tr'.$trid; ?>">
													<td><?php echo $estadoname; ?></td>
													<td id="<?php echo 'trname'.$trid; ?>" class="text-center" style="font-size: 12px"><?php echo $radioname;?></td>
													<td id="<?php echo 'trurl'.$trid; ?>" style="font-size: 10px"><?php echo $urlstream;?></td>
													<td class="text-center">
														<button id="<?php echo 'trbtn'.$trid; ?>" class="btn btn-default btn-xs" data-trid="<?php echo 'tr'.$trid; ?>" data-idname="<?php echo 'trname'.$trid; ?>" data-name="<?php echo $radioname;?>" data-idurl="<?php echo 'trurl'.$trid; ?>" data-url="<?php echo $urlstream;?>" data-toggle="modal" data-target=".edit_modal">
															<i class="fa fa-edit"></i>
															<?php echo get_phrase('edit');?>
														</button>
														<button class="btn btn-danger btn-xs" data-trid="<?php echo 'tr'.$trid; ?>" data-toggle="modal" data-target=".delete_modal">
															<i class="fa fa-times"></i>
															<?php echo get_phrase('delete');?>
														</button>
													</td>
												</tr>	
											<?php }
										}
									} ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
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
						<div class="col-lg-12">
							<div class="form-group">
								<label class="col-lg-2 control-label"><?php echo get_phrase('name');?></label>
								<div class="col-lg-8">
									<input required type="text" class="form-control" id="name_add_modal" name="name_add_modal" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">URL</label>
								<div class="col-lg-8">
									<input required type="text" class="form-control" id="url_add_modal" name="url_add_modal" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="addbtncancel" type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
						<button id="addbtnsave" type="button" class="btn btn-primary disabled" disabled><?php echo get_phrase('save');?></button>
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
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label"><?php echo get_phrase('name');?></label>
								<div class="col-lg-8">
									<input type="text" class="form-control" id="idbtn_edit_modal" name="idbtn_edit_modal" autocomplete="off" style="display: none">
									<input type="text" class="form-control" id="idname_edit_modal" name="idname_edit_modal" autocomplete="off" style="display: none">
									<input required type="text" class="form-control" id="name_edit_modal" name="name_edit_modal" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">URL</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" id="idurl_edit_modal" name="idurl_edit_modal" autocomplete="off" style="display: none">
									<input required type="text" class="form-control" id="url_edit_modal" name="url_edit_modal" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="editbtncancel" type="button" class="btn btn-default" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
						<button id="editbtnsave" type="button" class="btn btn-primary"><?php echo get_phrase('save');?></button>
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
						<form id="delete_modal_form" action="<?php echo site_url('pages/delete_radio');?>" method="post">
							<input type="hidden" id="keywordid_delete_modal" name="keywordid_delete_modal"></input>
							<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo get_phrase('no');?></button>
							<button type="submit" class="btn btn-primary btn-sm"><?php echo get_phrase('yes');?></button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			function checkradioname(radioname) {
				var pattern = new RegExp(/[A-Z\-]{4,}[\_A-Z]{2}./g);
				if (pattern.test(radioname)) {
					return true;
				} else {
					return false;
				}
			}

			$('#add_modal').on('shown.bs.modal', function () {
				$('#name_add_modal').val(null);
				$('#name_add_modal').focus();
				$('#name_add_modal').blur(function(event) {
					rdname = $('#name_add_modal').val();
					 if (checkradioname(rdname)) {
						$('#addbtnsave').removeClass('disabled')
						$('#addbtnsave').attr('disabled', false);
					} else {
						swal("Atenção!", "O nome da rádio deve seguir o padrão: NOME_UF.", "error");
						$('#name_add_modal').val(null);
						$('#name_add_modal').focus();
					}
				});
			});

			$('#addbtnsave').click(function(event) {
				$('#btnschanges').fadeIn('slow');
			});

			$('#edit_modal').on('shown.bs.modal', function (event) {
				button = $(event.relatedTarget);
				radiobtnid = button[0].id;
				radionameid = button.data('idname');
				radioname = button.data('name');
				radiourlid = button.data('idurl');
				radiourl = button.data('url');
				modal = $(this);
				modal.find('.modal-body [name="idbtn_edit_modal"]').val(radiobtnid);
				modal.find('.modal-body [name="idname_edit_modal"]').val(radionameid);
				modal.find('.modal-body [name="name_edit_modal"]').val(radioname);
				modal.find('.modal-body [name="idurl_edit_modal"]').val(radiourlid);
				modal.find('.modal-body [name="url_edit_modal"]').val(radiourl);
				$('#name_edit_modal').focus();
			});

			$('#editbtnsave').click(function(event) {
				btnid = $('#idbtn_edit_modal').val();
				nameid = $('#idname_edit_modal').val();
				newname = $('#name_edit_modal').val();
				urlid = $('#idurl_edit_modal').val();
				newurl = $('#url_edit_modal').val();
				
				$('#'+nameid).text(newname);
				$('#'+urlid).text(newurl);
				$('#'+btnid).attr('data-name', newname);
				$('#'+btnid).attr('data-url', newurl);

				//$('#table_rec_radios').dataTable().fnDraw();

				$('#edit_modal').modal('hide');
				$('#btnschanges').fadeIn('slow'	);
			});

			$('#btnschanges').click(function(event) {
				$('#btnschanges').fadeOut('slow');

				// tlines = $('#table_rec_radios tr');
				tlines = $('#<?php echo $datatablename;?>').dataTable().fnGetData();

				console.log(tlines);
			});

			// var table = $('#table_rec_radios').DataTable();
			// $('#table_rec_radios tbody').on( 'click', 'tr', function () {
			// 	console.log( table.row( this ).data() );
			// } );

			// Order by the grouping
			$('#<?php echo $datatablename;?>').on('click', 'tr.group', function() {
				var currentOrder = dttable.order()[0];
				if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
					dttable.order([0, 'desc']).draw();
				} else {
					dttable.order([0, 'asc']).draw();
				}
			});

		</script>