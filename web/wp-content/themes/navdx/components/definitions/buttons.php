<?php
/**
 * Plain-Jane Button component
 */
rtrc_register_component('Buttons', [
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
        ],
    ],
], 'buttons.php');