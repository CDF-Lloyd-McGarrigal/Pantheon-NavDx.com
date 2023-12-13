<?php

// Accordion
rtrc_register_component( 'Accordion', array(



    [
        'name'  => 'Settings',
        'type'  => 'heading'
    ],

    [
        'id'    => 'only_one_open',
        'name'  => 'Open 1 Item, at most?',
        'type'  => 'checkbox',
        'desc'  => 'If checked, only one accordion item will be open at a time'
    ],

    [
        'id'    => 'accordion_items',
        'name'  => 'Accordion Items',
        'type'  => 'group',
        'clone' => true,
        'sort_clone'    => true,
        'fields' => array(

            [
                'id'    => 'title_content',
                'name'  => 'Title Content',
                'type'  => 'wysiwyg',
                'options' => [
                    'textarea_rows' => 2,
                    'media_buttons' => false,
                ]
            ],

            [
                'id'    => 'body_content',
                'name'  => 'Body Content',
                'type'  => 'wysiwyg',
                'rows'  => 4
            ]
        )
    ]


), 'accordion.php' );
