<?php
/**
 * Include the 404 fields in the site options
 */

function navdx_options_404( $meta_boxes ){
	
	// 404 Tab
	$meta_boxes[] = array(
	    'id'                => 'page404',
	    'title'                 => __('404 Page', 'navdx'),
	    'settings_pages'    =>  'navdx-site-options',
	    'tab'               => 'page404',
	    'fields'            =>  array(

	    	// Title
	    	[
	    		'id'	=> '404_title',
	    		'name'	=> 'Page Title',
	    		'type'	=> 'text',
	    		'size' 	=> 80
	    	],

	    	// Body
	    	[
	    		'id'	=> '404_body',
	    		'name'	=> 'Page Body',
	    		'type'	=> 'wysiwyg'
	    	],
	    )
	);

    return $meta_boxes;
}//end navdx_options_header()
add_filter('rwmb_meta_boxes', 'navdx_options_404');