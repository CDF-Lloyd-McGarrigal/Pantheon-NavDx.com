<?php

function navdx_options_social($meta_boxes){
    $meta_boxes[] = [
        'id' => 'social',
        'title' => __('Social Media Links', 'navdx'),
        'settings_pages' => 'navdx-site-options',
        'tab' => 'social',
        'fields' => [
            [
                'id' => 'sites',
                'name' => 'Social Link',
                'type' => 'group',
                'clone' => true,
                'sort_clone' => true,
                'fields' => [
                    [
                        'id' => 'social_icon',
                        'name' => 'Link Icon',
                        'type' => 'single_image'
                    ],
                    [
                        'id' => 'url',
                        'name' => 'Page URL',
                        'type' => 'url'
                    ]
                ]
            ]
        ]
    ];
    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'navdx_options_social');