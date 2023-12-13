<?php
/**
 * Call To Action with Chevron
 */
rtrc_register_component('CTA - Chevron', [
    [
        'id' => 'cta',
        'name' => 'Call to Action',
        'type' => 'group',
        'clone' => true,
        'sort_clone' => true,
        'max_clone' => 3,
        'fields' => [
            [
                'id' => 'heading',
                'name' => 'Heading',
                'type' => 'text',
            ],
            [
                'id' => 'body',
                'name' => 'Content',
                'type' => 'wysiwyg',
                'options' => [
                    'textarea_rows' => 4,
                ]
            ],
            [
                'id' => 'color',
                'name' => 'Color',
                'type' => 'select',
                'multiple' => false,
                'select_all_none' => true,
                'placeholder' => 'Select a color',
                'options' => [
                    'teal' => 'Teal',
                    'navy' => 'Navy',
                    'red' => 'Red',
                ]
            ],
            [
                'id' => 'links',
                'name' => 'Links/Buttons',
                'type' => 'group',
                'clone' => true,
                'sort_clone' => true,
                'max_clone' => 4,
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
                        'id' => 'class',
                        'name' => 'Link Class',
                        'type' => 'text',
                    ],
                    [
                        'id' => 'image',
                        'name' => 'Image',
                        'type' => 'single_image'
                    ]
                ]
            ]
        ]
    ]
], 'cta-chevron.php');