<div class="wrap">
	<h2><?php __( 'AMP customizer', 'amp' ); ?></h2>
	<form method="post" action="options.php">
		<?php
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );
		submit_button();
		?>
	</form>
</div>