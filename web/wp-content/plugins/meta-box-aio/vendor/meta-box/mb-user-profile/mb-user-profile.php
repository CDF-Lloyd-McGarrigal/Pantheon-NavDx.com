<?php
/**
 * Plugin Name: MB User Profile
 * Plugin URI:  https://metabox.io/plugins/mb-user-profile/
 * Description: Register, edit user profiles with custom fields on the front end.
 * Version:     1.8.0
 * Author:      MetaBox.io
 * Author URI:  https://metabox.io
 * License:     GPL2+
 * Text Domain: mb-user-profile
 * Domain Path: /languages/
 *
 * @package    Meta Box
 * @subpackage MB User Profile
 */

// Prevent loading this file directly.
defined( 'ABSPATH' ) || die;

if ( ! function_exists( 'mb_user_profile_load' ) ) {
	if ( file_exists( __DIR__ . '/vendor' ) ) {
		require __DIR__ . '/vendor/autoload.php';
		require __DIR__ . '/vendor/meta-box/mb-user-meta/mb-user-meta.php';
	} else {
		require dirname( __DIR__ ) . '/mb-user-meta/mb-user-meta.php';
	}

	/**
	 * Hook to 'init' with priority 5 to make sure all actions are registered before Meta Boxes runs.
	 */
	add_action( 'init', 'mb_user_profile_load', 5 );

	/**
	 * Load plugin files after Meta Box is loaded
	 */
	function mb_user_profile_load() {
		if ( ! defined( 'RWMB_VER' ) ) {
			return;
		}

		list( , $url ) = RWMB_Loader::get_path( __DIR__ );
		define( 'MBUP_URL', $url );
		define( 'MBUP_VER', '1.8.0' );
		define( 'MBUP_DIR', __DIR__ );

		$languages_dir = trim( str_replace( wp_normalize_path( WP_PLUGIN_DIR ), '', wp_normalize_path( __DIR__ . '/languages' ) ), '/' );
		load_plugin_textdomain( 'mb-user-profile', false, $languages_dir );

		new MBUP\DefaultFields;
		new MBUP\UserFields;
		new MBUP\Email;
		new MBUP\Shortcodes\Info;
		new MBUP\Shortcodes\Register;
		new MBUP\Shortcodes\Login;
	}
}
