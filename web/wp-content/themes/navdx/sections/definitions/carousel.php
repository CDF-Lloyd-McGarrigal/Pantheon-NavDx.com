<?php
/**
 * Home page carousel
 */
rtrc_register_section( 'Carousel Section', [
    [
        'id' => 'carousel',
        'name' => 'Slides',
        'type' => 'group',
        'clone' => true,
        'sort_clone' => true,
        'max_clones' => 6,
        'fields' => [
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
            [
                'id' => 'links',
                'name' => 'Buttons/Links',
                'type' => 'group',
                'clone' => true,
                'sort_clone' => true,
                'fields' => [
                    [
                        'id' => 'text',
                        'name' => 'Text',
                        'type' => 'text',
                    ],
                    [
                        'id' => 'url',
                        'name' => 'URL',
                        'type' => 'text',
                        'descr' => 'If this is a jumplink, include the \'#\'',
                    ],
                    [
                        'id' => 'target',
                        'name' => 'Open in a new tab?',
                        'type' => 'checkbox',
                    ],
                    [
                        'id'    => 'button_class',
                        'name'  => 'button class',
                        'type'  => 'text',
                        'desc'  => 'class name download, button, circle'
                    ],
                ]
            ]
        ]
    ]
], 'carousel.php' );