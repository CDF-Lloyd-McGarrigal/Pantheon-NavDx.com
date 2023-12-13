<?php

function navdx_options_registration_form($meta_boxes){
    $meta_boxes[] = [
        'id' => 'reg_form',
        'title' => __('Registration Form(s)', 'navdx'),
        'settings_pages' => 'navdx-site-options',
        'tab' => 'registration',
        'fields' => [
            [
                'id' => 'modal_form',
                'name' => 'Modal Form',
                'descr' => 'The form that appears in the modal pop-up',
                'type' => 'wysiwyg',
            ],
        ]
    ];
    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'navdx_options_registration_form');