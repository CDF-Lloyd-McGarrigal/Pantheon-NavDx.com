<?php

/**
 * The hero sections
 */

rtrc_register_component( 'Hero Component', [
    [
        'id' => 'desktop_image',
        'name' => 'Desktop Hero Image',
        'type' => 'single_image',
    ],
    [
        'id' => 'mobile_image',
        'name' => 'Mobile Hero Image',
        'type' => 'single_image',
    ],
    [
        'id' => 'eyebrow',
        'name' => 'Eyebrow Text',
        'type' => 'wysiwyg',
        'options' => [
            'media_buttons' => false,
            'textarea_rows' => 3,
        ],
    ],
    [
        'id' => 'headline',
        'name' => 'Headline',
        'type' => 'wysiwyg',
        'options' => [
            'media_buttons' => false,
        ]
    ],

], 'hero.php' );