<?php
/*
Plugin Name: Arteric Section Builder
Plugin URI: http://arteric.com
Description: Provides an interface for splitting a page into seperately managable sections
Author: Anthony Outeiral
Version: 1.6
Author URI: http://arteric.com
*/

$pluginPath = plugin_dir_path( __FILE__ );
$pluginUrl = plugin_dir_url( __FILE__ );

// Autoload everything in vendor and src
require __DIR__ . '/vendor/autoload.php';

use ArtericSectionBuilder\SectionBuilder as SectionBuilder;
use ArtericSectionBuilder\Shortcode as Shortcode;

global $_rtrc_SectionBuilder;
$_rtrc_SectionBuilder = new SectionBuilder( $pluginPath, $pluginUrl );

global $_rtrc_SectionBuilderShortcode;
$_rtrc_SectionBuilderShortcode = new Shortcode();


if( !function_exists( 'rtrc_build_sections' ) ){

	/**
	 * Helper function for the section builder, so we don't have to instatiate the class
	 * @param  string  $template The template name
	 * @param  integer $postID   The ID of the post to grab sections from
	 * 
	 * @return void
	 */
	function rtrc_build_sections( $postID = 0 ){

		// Invoke the true funciton
		global $_rtrc_SectionBuilder;
		$_rtrc_SectionBuilder->buildSections( $postID );
	}
}
else {
	add_action( 'admin_notices', function(){
		echo '<div class="notice notice-error"><p>The Arteric Component Builder conflicts with another function named "rtrc_build_sections()"</p></div>';
	});
}

if( !function_exists( 'rtrc_render_section' ) ){

	/**
	 * Helper function for the section builder, so we don't have to instatiate the class
	 * @param  string  $template The template name
	 * @param  integer $postID   The ID of the post to grab sections from
	 * 
	 * @return void
	 */
	function rtrc_render_section( $sectionID ){

		// Invoke the true funciton
		global $_rtrc_SectionBuilder;
		$_rtrc_SectionBuilder->renderSection( $sectionID );
	}
}
else {
	add_action( 'admin_notices', function(){
		echo '<div class="notice notice-error"><p>The Arteric Component Builder conflicts with another function named "rtrc_build_sections()"</p></div>';
	});
}

if( !function_exists( 'rtrc_register_section' ) ){

	function rtrc_register_section( $name, array $fields = [], $template = '' ){
		global $_rtrc_SectionBuilder;
		$_rtrc_SectionBuilder->registerSection( $name, $fields, $template );
	}
}
else {
	add_action( 'admin_notices', function(){
		echo '<div class="notice notice-error"><p>The Arteric Component Builder conflicts with another function named "rtrc_register_section()"</p></div>';
	});
}