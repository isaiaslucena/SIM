<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$id_user = $this->session->userdata('id_user');
	$id_group = $this->db->get_where('user', array('id_user' => $id_user))->row()->id_group;
	// var_dump($selected_page);

	if ($selected_page == 'edit') { ?>
		<script src="<?php echo base_url('assets/readalong/read-along.js');?>"></script>
		<script src="<?php echo base_url('assets/readalong/main.js');?>"></script>
	<?php } ?>
		<!-- page-wrapper -->
		</div>
		<!-- page-wrapper -->

		<!-- wrapper -->
		</div>
		<!-- wrapper -->

	<!-- body -->
	</body>
	<!-- body -->
</html>