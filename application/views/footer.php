<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	</div> <!-- div page-wrapper -->
</div> <!-- div entire page -->
<!-- 	<nav class="navbar navbar-default navbar-fixed-bottom">
		<div class="container">
			<p class="text-muted">
				<a href="http://www.dataclip.com.br" target="_blank">DATACLIP</a><br>
				Business Inteligence
			</p>
		</div>
	</nav> -->
	<?php
		if ($selected_page == 'edit') { ?>
			<script src="<?php echo base_url('assets/readalong/read-along.js');?>"></script>
			<script src="<?php echo base_url('assets/readalong/main.js');?>"></script>
	<?php } ?>
</body>
</html>