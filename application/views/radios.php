<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<body>
	<div id="page-wrapper" style="height: 100%; min-height: 400px;">
		<div class="row page-header">
			<div class="col-lg-12">
				<h1><?php echo get_phrase('radios');?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table class="table table-hover" id="<?php echo $datatablename;?>">
						<thead>
							<tr>
								<th class="sorting text-center" tabindex="0" rowspan="1" colspan="1" style="width: 20px;"><?php echo get_phrase('id');?></th>
								<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('radio');?></th>
								<th class="sorting_desc text-center" tabindex="0" rowspan="1" colspan="1" style="width: 100px;"><?php echo get_phrase('state');?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($allradios as $radio) { ?>
								<tr>
									<td class="text-center"><?php echo $radio['id_radio']; ?></td>
									<td class="text-center"><?php echo $radio['name']; ?></td>
									<td class="text-center"><?php echo $radio['state']; ?></td>
								</tr>
								<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>