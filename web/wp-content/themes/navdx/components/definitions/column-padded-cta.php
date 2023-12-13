<?php

// Three Column CTA
rtrc_register_component( 'Column Padded CTA', array(

    [
		'id'	=> 'callouts',
		'name'	=> 'Callouts',
		'type'	=> 'group',
        'clone'	=> true,
        'max_clone' => 3,
        'clone_default' => true,
		'sort_clone'	=> true,
		'fields'	=> [
            [
                'id'    => 'column_text',
                'name'  => 'CTA Text',
                'type'  => 'wysiwyg',
                'desc'  => 'Text for each column above the CTA'
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
                'id'    => 'column_icon',
                'name'  => 'CTA Icon',
                'type'  => 'single_image',
                'desc'  => 'icon image'
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
                        'id'    => 'button_class',
                        'name'  => 'button class',
                        'type'  => 'text',
                        'desc'  => 'class name download, button, circle'
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

), 'column-padded-cta.php' );
