<?php
/**
 * Text with Chart
 */
rtrc_register_component('Text with Chart', [
    [
        'id' => 'content',
        'name' => 'Content',
        'type' => 'wysiwyg',
    ],
    [
        'id' => 'desktop_image',
        'name' => 'Desktop Version',
        'type' => 'single_image'
    ],
    [
        'id' => 'mobile_image',
        'name' => 'Mobile Version',
        'type' => 'single_image'
    ],
    [
        'id' => 'footnote',
        'name' => 'Footnote',
        'type' => 'wysiwyg',
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
], 'text-with-chart.php');