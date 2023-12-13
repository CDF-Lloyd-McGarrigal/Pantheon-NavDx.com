<?php

namespace BaselinePlugin;

class Loader {

	function __construct() {

		add_action( 'admin_notices', [ $this, 'sample' ]);
	}

	function sample(){
		?>
		<div class="notice notice-success is-dismissible">
	        <p><?php _e( 'Loaded baseline!', '[Plugin Text Domain]' ); ?></p>
	    </div>
	    <?php
	}
}