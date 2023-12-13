<?php
/**
 * Include the header fields in the site options
 */

function navdx_options_header($meta_boxes) {

    // Header tab
    $meta_boxes[] = array(
        'id'    => 'header',
        'title' => __('Header', 'navdx'),
        'settings_pages'    => 'navdx-site-options',
        'tab'   => 'header',
        'fields' => array(

            [
                'id'    => 'header_image',
                'name'  => 'Header Image',
                'type'  => 'image_advanced',
                'max_file_uploads'  => 1
            ],

            [
                'id'    => 'header_image_link',
                'name'  => 'Header Image Link',
                'type'  => 'text',
                'std'   => '/'
            ],
            [
                'name'  => 'Favicon',
                'type'  => 'heading'
            ],
            [
                'id'    => 'header_favicon',
                'name'  => 'Favicon Image',
                'type'  => 'single_image',
            ],
        )
    );
    return $meta_boxes;
}//end navdx_options_header()
add_filter('rwmb_meta_boxes', 'navdx_options_header');