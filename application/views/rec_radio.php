<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

		<div class="row page-header">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-4">
						<h1><?php echo get_phrase('radios');?></h1>
					</div>
					<div class="col-lg-4">
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
					<div class="col-lg-2">
						<h1>
							<button id="btnschanges" type="button" class="btn btn-success pull-right" style="display: none">
								<i class="fa fa-check"></i>
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
							$rrestados = array();

							foreach ($rec_radios as $estado => $st) {
								$estadoname = $estado;
								array_push($rrestados, $estadoname);
								foreach ($st as $radio) {
										$radioname = $radio->name;
										$radiouf = $radio->state;
										$urlstream = $radio->stream;
										$disk = $radio->disk;
										$transcmachine = $radio->transc_machine;
										$transc = $radio->transc;
										$until = $radio->until;
										$trid++; ?>
										<tr id="<?php echo 'tr'.$trid; ?>">
											<td><?php echo $estadoname; ?></td>
											<td id="<?php echo 'trname'.$trid; ?>" class="text-center rrntable"><?php echo $radioname;?></td>
											<td id="<?php echo 'trurl'.$trid; ?>" class="rrutable"><?php echo $urlstream;?></td>
											<td class="text-center">
												<button id="<?php echo 'trbtn'.$trid; ?>" class="btn btn-default btn-xs"
													data-trid="<?php echo 'tr'.$trid; ?>" data-idname="<?php echo 'trname'.$trid; ?>"
													data-name="<?php echo $radioname;?>" data-idurl="<?php echo 'trurl'.$trid; ?>"
													data-url="<?php echo $urlstream;?>" data-uf="<?php echo $radiouf;?>" data-disk="<?php echo $disk;?>"
													data-transcmachine="<?php echo $transcmachine;?>" data-transc="<?php echo $transc;?>"
													data-until="<?php echo $until;?>"
													data-toggle="modal" data-target=".edit_modal">
													<i class="fa fa-edit"></i>
													<?php echo get_phrase('edit');?>
												</button>
												<button class="btn btn-danger btn-xs" data-trid="<?php echo 'tr'.$trid; ?>"
													data-toggle="modal" data-target=".delete_modal">
													<i class="fa fa-times"></i>
													<?php echo get_phrase('delete');?>
												</button>
											</td>
										</tr>
								<?php }
							}
							?>
						</tbody>
					</table>
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
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label"><?php echo get_phrase('state');?></label>
								<div class="col-lg-8">
									<select required id="state_add_modal" name="state_add_modal" class="form-control">
										<!-- <option value="1">1</option> -->
										<?php foreach ($rrestados as $rrestado) { ?>
												<option value="<?php echo $rrestado; ?>"><?php echo $rrestado; ?></option>
											<?php } ?>
									</select>
								</div>
							</div>
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
						<button disabled id="addbtnsave" type="button" class="btn btn-primary disabled"><?php echo get_phrase('save');?></button>
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
									<input type="text" class="form-control" id="trid_edit_modal" name="trid_edit_modal" autocomplete="off" style="display: none">
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
						<input id="trid_delete_modal" name="trid_delete_modal" style="display: none;"></input>
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php echo get_phrase('no');?></button>
						<button id="delbtnyes" type="button" class="btn btn-primary btn-sm"><?php echo get_phrase('yes');?></button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			var dttable, table;

			function checkradioname(radioname) {
				var pattern = new RegExp(/[0-9A-Z\-]{4,}[\_A-Z]{2}./g);
				if (pattern.test(radioname)) {
					return true;
				} else {
					return false;
				}
			};

			$('#add_modal').on('shown.bs.modal', function() {
				$('#name_add_modal').val(null);
				$('#url_add_modal').val(null);
				$('#name_add_modal').focus();
				$('#name_add_modal').blur(function(event) {
					rdname = $('#name_add_modal').val();
					rdurl = $('#url_add_modal').val();
					 if (checkradioname(rdname)) {
						$('#addbtnsave').removeClass('disabled')
						$('#addbtnsave').attr('disabled', false);
					} else if (rdname == "") {
						console.log('no name');
					} else {
						swal("Atenção!", "O nome da rádio deve seguir o padrão: NOME_UF.", "error");
						$('#name_add_modal').val(null);
						$('#name_add_modal').focus();
					}
				});
			});

			$('#addbtnsave').click(function(event) {
				rstate = $('#state_add_modal').val();
				rname = $('#name_add_modal').val();
				rurl = $('#url_add_modal').val();
				btns = 	'<button disabled class="btn btn-default btn-xs disabled" data-toggle="modal" data-target=".edit_modal">'+
							'<i class="fa fa-edit"></i> '+
							'<?php echo get_phrase('edit');?>'+
						'</button> '+
						'<button disabled class="btn btn-danger btn-xs disabled" data-toggle="modal" data-target=".delete_modal">'+
							'<i class="fa fa-times"></i> '+
							'<?php echo get_phrase('delete');?>'+
						'</button>'

				// dttable.row.add([rstate, rname, rurl, btns]).draw().nodes().to$().addClass('rrntable text-center');
				// dttable.row.add([rstate, rname, rurl, btns]).invalidate().draw();

				var rowNode = dttable.row.add([rstate,rname,rurl,btns]).draw().node();

				$( rowNode ).find('td').eq(0).addClass('rrntable text-center');
				$( rowNode ).find('td').eq(1).addClass('rrutable');
				$( rowNode ).find('td').eq(2).addClass('text-center');

				$('#add_modal').modal('hide');
				$('#btnschanges').fadeIn('slow');
			});

			$('#edit_modal').on('shown.bs.modal', function(event) {
				button = $(event.relatedTarget);
				trid = $(button).attr('data-trid');
				radiobtnid = button[0].id;
				radionameid = button.attr('data-idname');
				radioname = button.attr('data-name');
				radiourlid = button.attr('data-idurl');
				radiourl = button.attr('data-url');

				modal = $(this);
				modal.find('.modal-body [name="trid_edit_modal"]').val(trid);
				modal.find('.modal-body [name="idbtn_edit_modal"]').val(radiobtnid);
				modal.find('.modal-body [name="idname_edit_modal"]').val(radionameid);
				modal.find('.modal-body [name="name_edit_modal"]').val(radioname);
				modal.find('.modal-body [name="idurl_edit_modal"]').val(radiourlid);
				modal.find('.modal-body [name="url_edit_modal"]').val(radiourl);
				$('#name_edit_modal').focus();
			});

			$('#editbtnsave').click(function(event) {
				// var DTable = $('#table_rec_radios').dataTable();
				// var DTable = $('#table_rec_radios').DataTable();
				// DTable.fnDestroy();

				trid = $('#trid_edit_modal').val();
				btnid = $('#idbtn_edit_modal').val();
				nameid = $('#idname_edit_modal').val();
				newname = $('#name_edit_modal').val();
				urlid = $('#idurl_edit_modal').val();
				newurl = $('#url_edit_modal').val();

				// $('#'+nameid).text(newname);
				// $('#'+urlid).text(newurl);
				// $('#'+btnid).attr('data-name', newname);
				// $('#'+btnid).attr('data-url', newurl);

				dttable.cell('#'+nameid).data(newname).draw();
				dttable.cell('#'+urlid).data(newurl).draw();

				// var DTable = $('#table_rec_radios').dataTable();
				// DTable.fnDraw();
				// tlines = DTable.fnGetData();
				// console.log(tlines);

				$('#edit_modal').modal('hide');
				$('#btnschanges').fadeIn('slow');
			});

			$('#btnschanges').click(function(event) {
				curstate = null;
				$('#btnschanges').fadeOut('slow');
				// $('#<?php echo $datatablename;?>').dataTable().fnDraw();
				// tlines = $('#<?php echo $datatablename;?>').dataTable().fnGetData();

				// var DTable = $('#table_rec_radios').dataTable();
				// DTable.fnDraw();
				// tlines = DTable.fnGetData();

				table.fnDraw();
				tlines = table.fnGetData();

				tlines.sort();
				radios = {'ESTADO':[]};
				indx = -1;
				$.each(tlines, function(index, lineval) {
					state = lineval[0];
					radio = lineval[1];
					url = lineval[2];
					curradio = {'radio': radio, 'stream': url};
					if (curstate != state) {
						indx += 1;
						newstate = {};
						newstate[state] = [];
						newstate[state].push(curradio);
						radios['ESTADO'].push(newstate);
					} else {
						radios['ESTADO'][indx][state].push(curradio);
					}
					curstate = state;
				});

				console.log(radios);

				$.ajax({
					url: '<?php echo str_replace("sim.","radio.",base_url())?>index.php/radio/updateradios/',
					type: 'POST',
					dataType: 'json',
					data: JSON.stringify(radios),
				})
				.done(function(ddata) {
					console.log("success");
					// console.log(ddata);
					window.location = '<?php echo base_url("pages/rec_radio/update"); ?>';
				})
				.fail(function(fdata) {
					console.log("error");
				})
				.always(function(adata) {
					console.log("complete");
					// console.log(adata);
				});
			});

			$('#delete_modal').on('shown.bs.modal', function(event) {
				dbutton = $(event.relatedTarget);
				bdtrid = dbutton.attr('data-trid');
				$('#trid_delete_modal').val(bdtrid);
			});

			$('#delbtnyes').click(function(event) {
				dtrid = $('#trid_delete_modal').val();
				drow = dttable.row( $('#'+dtrid) );
				drow.remove().draw();

				$('#delete_modal').modal('hide');
				$('#btnschanges').fadeIn('slow');
			});

			$('#<?php echo $datatablename;?>').on('click', 'tr.group', function() {
				var currentOrder = dttable.order()[0];
				if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
					dttable.order([0, 'desc']).draw();
				} else {
					dttable.order([0, 'asc']).draw();
				}
			});
		</script>