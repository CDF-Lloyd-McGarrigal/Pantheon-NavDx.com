<?php
/**
 * Text with Image
 */
rtrc_register_component('Text with Image', [
    [
        'id' => 'content',
        'name' => 'Content',
        'type' => 'wysiwyg',
    ],
    [
        'id' => 'desktop_image',
        'name' => 'Desktop Image',
        'type' => 'single_image'
    ],
    [
        'id' => 'mobile_image',
        'name' => 'Mobile Image',
        'type' => 'single_image'
    ],
    [
        'id' => 'caption',
        'name' => 'Image Caption',
        'type' => 'text',
    ],
    [
        'id' => 'image_position',
        'name' => 'Image Position',
        'type' => 'select',
        'select_all_none' => false,
        'multiple' => false,
        'options' => [
            'left' => 'Image to the left of text',
            'right' => 'Image to the right of text'
        ]
    ],
    [
        'id' => 'image_class',
        'name' => 'Image Class',
        'type' => 'text',
    ],
    [
        'id' => 'cta',
        'name' => 'Call To Action',
        'type' => 'wysiwyg',
        'options' => [
            'media_buttons' => false,
            'textarea_rows' => 3,
        ],
    ],
    [
        'id' => 'links',
        'name' => 'Links/Buttons',
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
                'id' => 'class',
                'name' => 'Link Class',
                'type' => 'text',
            ],
        ]
    ],
], 'text-with-image.php');