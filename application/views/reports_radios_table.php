<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

							<table class="table table-hover" id="<?php echo $datatablename;?>">
								<thead>
									<tr>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;">#</th>
										<th class="sorting_asc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 40px;"><?php echo get_phrase('radio');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('date');?></th>
										<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('added');?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$cc=0;
										foreach ($radiosinfo as $rinfo) {
											$cc++;
											$filesinfo =  $this->pages_model->report_byradio($rinfo['id_radio']);

											$filedate = date('Y-m-d H:i:s',$filesinfo[0]['timestamp']);
											$filedate15min = strtotime($filedate.' +15 minutes');
											$filedate30min = strtotime($filedate.' +30 minutes');
											$filedate1hour = strtotime($filedate.' +1 hour');
											$filedate1day = strtotime($filedate.' +1 day');
											$filedateadd = $filesinfo[0]['timestamp_add'];

											if ($filedateadd <= $filedate15min) { ?>
											<tr class="success">
											<?php }	else if ($filedateadd >= $filedate15min and $filedateadd <= $filedate30min) { ?>
											<tr class="info">
											<?php } else if ($filedateadd > $filedate30min and $filedateadd <= $filedate1hour) { ?>
											<tr class="warning">
											<?php } else if ($filedateadd > $filedate1hour and $filedateadd <= $filedate1day) { ?>
											<tr class="danger">
											<?php } ?>
												<td class="text-center"><?php echo $cc; ?></td>
												<td class="text-center"><?php echo $filesinfo[0]['radio']; ?></td>
												<td class="text-center"><?php echo date('d/m/Y H:i:s',$filesinfo[0]['timestamp']); ?></td>
												<td class="text-center"><?php echo date('d/m/Y H:i:s',$filesinfo[0]['timestamp_add']); ?></td>
											</tr>
										<?php
										} ?>
								</tbody>
							</table>