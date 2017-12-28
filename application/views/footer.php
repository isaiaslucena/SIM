<?php 
	defined('BASEPATH') OR exit('No direct script access allowed'); 
	$id_user = $this->session->userdata('id_user');
	$id_group = $this->db->get_where('user', array('id_user' => $id_user))->row()->id_group;

	if ($selected_page == 'edit') { ?>
		<script src="<?php echo base_url('assets/readalong/read-along.js');?>"></script>
		<script src="<?php echo base_url('assets/readalong/main.js');?>"></script>
	<?php }
	
	if (preg_match('/^home/', $selected_page) == 0) {
		//div id=page_wrapper
		echo "</div>";
		if ($id_group == 1) {
			echo '</div>';
		}
		echo "</body>";
	} ?>
</html>