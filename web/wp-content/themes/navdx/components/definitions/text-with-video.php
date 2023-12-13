<?php
/**
 * Text with Video
 */
rtrc_register_component('Text with Video', [
    [
        'id' => 'content',
        'name' => 'Content',
        'type' => 'wysiwyg',
    ],
    [
        'id' => 'video_url',
        'name' => 'YouTube Video ID',
        'type' => 'text',
        'descr' => 'https://www.youtube.com/embed/VIDEO_ID'
    ],
    [
        'id' => 'video_position',
        'name' => 'Video Position',
        'type' => 'select',
        'select_all_none' => false,
        'multiple' => false,
        'options' => [
            'left' => 'Video to the left of text',
            'right' => 'Video to the right of text'
        ]
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
], 'text-with-video.php');