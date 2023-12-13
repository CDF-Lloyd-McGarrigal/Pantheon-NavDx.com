<?php 
/**
 Plugin Name: External Link Checker
Plugin URI: http://arteric.com
Description: Create a custom trigger for all External Links.
Network: false
Author: Joe Roxbury and Anthony Outeiral
Version: 3.5
Author URI: http://arteric.com/team/joe-roxbury.html
Text Domain: external-link-checker
*/

if (!defined( 'ABSPATH' ) ){ die; };


if( !defined( 'EXTERNAL_LINK_CHECKER_VERSION' ) ){
	define( 'EXTERNAL_LINK_CHECKER_VERSION', '3.5' );
}

define( 'EXTERNAL_LINK_CHECKER_DIR', plugin_dir_path( __FILE__ ) );

require_once EXTERNAL_LINK_CHECKER_DIR . 'inc/settings-page.php';
require_once EXTERNAL_LINK_CHECKER_DIR . 'inc/init.php';
