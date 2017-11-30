<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php if ($selected_page == 'edit') { ?>
			<script src="<?php echo base_url('assets/readalong/read-along.js');?>"></script>
			<script src="<?php echo base_url('assets/readalong/main.js');?>"></script>
	<?php } 
	if (preg_match('/^home/', $selected_page) == 0) {
		//div id=page_wrapper
		echo "</div>";
		echo "</body>";
	}
	?>
</html>