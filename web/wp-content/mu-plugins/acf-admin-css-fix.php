<?php
/*
 Plugin Name: ACF CSS Fix
 Plugin URI: http://arteric.com
 Description:
 Author: Oleg, arteric.com
 Version: 1.1
 Author URI: http://arteric.com
 Text Domain: acf-css-fix
 */
add_action( 'init', function() {
	if (is_admin()) {
		add_action('admin_head', function() {
			echo '<style type="text/css">
					.acf-table td.acf-label {width:65px;}
					</style>';
		}, 90);
	}
});