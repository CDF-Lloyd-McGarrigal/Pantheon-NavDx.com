<?php
/**
 * Call To Action with Chevron
 */
rtrc_register_component('Vertical CTA', [
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
                'id'    => 'column_icon',
                'name'  => 'Column icon',
                'type'  => 'single_image',
                'desc'  => 'icon image'
            ],
            [
                'id' => 'body_text',
                'name' => 'Body Text',
                'type' => 'wysiwyg',
            ],
            [
                'id' => 'callout',
                'name' => 'Call out',
                'type' => 'wysiwyg',
            ],
            [
                'id' => 'action',
                'name' => 'Action',
                'type' => 'wysiwyg',
            ],
            [
                'id' => 'jumplink',
                'name' => 'Jump Link',
                'type' => 'text',
                'descr' => 'This will insert a data-jumplink attribute if filled out'
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
            ]
            
        ]
    ]
], 'vertical-cta.php');