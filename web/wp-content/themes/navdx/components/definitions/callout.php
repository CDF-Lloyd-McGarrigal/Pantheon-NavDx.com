<?php
rtrc_register_component( 'Callout', array(
    [
        'id'    => 'column_text',
        'name'  => 'Column Text',
        'type'  => 'wysiwyg',
        'desc'  => 'Text for each column above the CTA'
    ],

    [
        'id'    => 'column_icon',
        'name'  => 'Column icon',
        'type'  => 'single_image',
        'desc'  => 'icon image'
    ],
    
    [
        'id' => 'links',
        'name' => 'Links/Buttons',
        'type' => 'group',
        'clone' => true,
        'sort_clone' => true,
        'max_clone' => 3,
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
), 'callout.php' );
