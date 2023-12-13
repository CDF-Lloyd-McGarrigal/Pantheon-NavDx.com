<?php
/**
 * Metaboxes for the section builder page templage
 * @package navdx
 */

function all_pages($meta_boxes) {

    $meta_boxes[] = array(
        'id'    => 'page_box',
        'title' => __('All Page Options', 'navdx'),
        'post_types'    => ['page'],
        'context'   => 'advanced',
        'priority'  => 'high',
        'fields'    => array(
            [
                'id' => 'page_background_color',
                'name' => 'Background Color',
                'type' => 'select',
                'multiple' => false,
                'options' => [
                    'white' => 'White',
                    'teal' => 'Teal',
                    'navy' => 'Navy',
                    'red' => 'Red',
                ]
            ],
            [
                'id'    => 'change_patent_code',
                'name'  => 'Change Patent Code',
                'type'  => 'text',
                'desc'  => 'If filled in this code will replace the default patent code in the footer.'
            ],
        )
    );
    return $meta_boxes;

}
add_filter('rwmb_meta_boxes', 'all_pages');