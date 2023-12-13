<?php

add_filter( 'rtrc_sections_template_directory', function( $templates ){

    // Don't change this - it tells the section builder to look here for templates
    return '/sections/templates/';
});

// add_filter( 'rtrc_sections_disallowed_templates', function( $templates ){

//     return [
//         '', // The default template
//         'default' // Alternate for the default template
//     ];
// });

add_filter( 'rtrc_sections_section_fields', function( $fields ){

    $fields[] = array(
            'id'    => 'custom_classes',
            'name'  => 'Custom Classes',
            'type'  => 'text',
            'size'  => 50
        );

    $fields[] = array(
            'id'    => 'jumpLinkName',
            'name'  => 'Jump Link Name',
            'type'  => 'text',
            'size'  => 50
        );

    return $fields;
});

add_filter( 'rtrc_sections_section_supports', function( $supports ){

    // Removing the "editor" from the list
    return [ 'title', 'revisions' ];
});

if( function_exists( 'rtrc_register_section' ) ){
    include_once get_template_directory() . '/sections/definitions/one-column.php';
    include_once get_template_directory() . '/sections/definitions/padded.php';
    include_once get_template_directory() . '/sections/definitions/carousel.php';
}
