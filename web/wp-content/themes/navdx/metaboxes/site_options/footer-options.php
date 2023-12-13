<?php
/**
 * Include the footer fields in the site options
 */

function navdx_options_footer( $meta_boxes ){

	// Footer Tab
	$meta_boxes[] = array(
		'id'                => 'footer',
		'title'             => __('Footer', 'navdx'),
		'settings_pages'    =>  'navdx-site-options',
		'tab'               => 'footer',
		'fields'            =>  array(

			[
				'name'	=> 'Bottom Footer',
				'type'	=> 'heading',
			],

			// Patent number
			[
				'id'	=> 'patent',
				'name'	=> 'Patent',
				'type'	=> 'text',
			],
			// Logo
			[
				'id'	=> 'product_logo',	
				'name'	=> 'Product Logo',
				'type'	=> 'single_image',
			],
			// Logo Link
			[
				'id'	=> 'product_logo_link',	
				'name'	=> 'Product Logo Link',
				'type'	=> 'text',
				'placeholder' => 'https://',
			],
			// Logo
			[
				'id'	=> 'manufacturer_logo',	
				'name'	=> 'Manufacturer Logo',
				'type'	=> 'single_image',
			],
			// Logo Link
			[
				'id'	=> 'manufacturer_logo_link',	
				'name'	=> 'Manufacturer Logo Link',
				'type'	=> 'text',
				'placeholder' => 'https://',
			],
			[
				'id' => 'copyright',
				'name' => 'Copyright Text',
				'type' => 'text',
			],
			[
				'id' => 'address',
				'name' => 'Full Address',
				'type' => 'wysiwyg',
				'options' => [
					'media_buttons' => false,
					'textarea_rows' => 3,
				]
				]
				
		)
	);

	return $meta_boxes;
}//end navdx_options_footer()
add_filter('rwmb_meta_boxes', 'navdx_options_footer');