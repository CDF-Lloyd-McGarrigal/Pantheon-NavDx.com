<?php
/**
 * This file for adding general use hooks and filters
 */

/**
 * Define templates which shouldn't load the editor
 */
if( !defined( 'TEMPLATES_WITHOUT_EDITOR' ) ){

	define( 'TEMPLATES_WITHOUT_EDITOR', serialize([
		'page-components.php',
		'page-sections.php'
	]));
}

if( !function_exists('wpbase_hide_editor_from_templates') ){
	function wpbase_hide_editor_from_templates() {

		$template_file = basename( get_page_template() );

		if( in_array( $template_file, unserialize(TEMPLATES_WITHOUT_EDITOR) ) ){ // template
			remove_post_type_support('page', 'editor');
		}
	}
}
add_action( 'admin_head', 'wpbase_hide_editor_from_templates' );

function allow_upload_svg($mimes = array()) {

	// Add a key and value for the CSV file type
	$mimes['svg'] = "image/svg+xml";

	return $mimes;
}
add_filter('upload_mimes', 'allow_upload_svg');

// adds a jumplink / anchor to the form action
// add_filter( 'gform_confirmation_anchor', '__return_true' );

/**
 * There are some pages with different color backgrounds
 *
 * @param array $classes
 * @return array
 */
function add_body_color(array $classes){
	$classes[] = this_post_meta('page_background_color');
	return $classes;
}
add_filter('body_class', 'add_body_color');