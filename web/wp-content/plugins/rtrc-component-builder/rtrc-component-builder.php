<?php
/*
Plugin Name: Arteric Component Builder
Plugin URI: http://arteric.com
Description: Provides an interface for registering and display components
Author: Anthony Outeiral
Version: 1.3
Author URI: http://arteric.com
*/

$pluginPath = plugin_dir_path( __FILE__ );
$pluginUrl = plugin_dir_url( __FILE__ );

// Autoload everything in vendor and src
require __DIR__ . '/vendor/autoload.php';

use ArtericComponentBuilder\ComponentRegister as ComponentRegister;

global $_rtrc_componentRegister;
$_rtrc_componentRegister = new ComponentRegister( $pluginPath, $pluginUrl );

if( !function_exists( 'rtrc_register_component' ) ){

	function rtrc_register_component( $name, array $fields = [], $template = '', $noWrapper = null ){
		global $_rtrc_componentRegister;
		$_rtrc_componentRegister->registerComponent( $name, $fields, $template, $noWrapper );
	}
}
else {
	add_action( 'admin_notices', function(){
		echo '<div class="notice notice-error"><p>The Arteric Component Builder conflicts with another function named "rtrc_register_component()"</p></div>';
	});
}

if( !function_exists( 'rtrc_build_components' ) ){

	function rtrc_build_components( $id = 0 ){
		
		if( $id == 0 ){
			$id = get_the_id();
		}

		global $_rtrc_componentRegister;
		$_rtrc_componentRegister->buildComponents( $id );
	}
}
else {
	add_action( 'admin_notices', function(){
		echo '<div class="notice notice-error"><p>The Arteric Component Builder conflicts with another function named "rtrc_build_components()"</p></div>';
	});
}