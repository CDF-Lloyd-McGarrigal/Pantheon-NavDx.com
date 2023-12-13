<?php
/**
 * COMPONENT BUILDER SETTINGS
 */
add_filter( 'rtrc_cb_template_directory', function( $template_directory ){

    return '/components/templates/';
});

/*add_filter( 'rtrc_cb_disallowed_templates', function( $disallowed ){

    return [
        'page-homepage.php',
        'page-thankyou.php'
    ];

});*/

add_filter( 'rtrc_cb_metabox_filter', function( $metabox ){

    $metabox[ 'revision' ] = true;

    return $metabox;
});

add_filter( 'rtrc_cb_allowed_posttypes', function( $allowed_posttypes ){

    $allowed_posttypes = [
        'sb_section',
        'page'
    ];

    return $allowed_posttypes;
});

/**
 * This area is for defining the different component types
 */

if( function_exists( 'rtrc_register_component' ) ){
    // include_once get_template_directory() . '/components/definitions/accordion.php';
    include_once get_template_directory() . '/components/definitions/cta-chevron.php';
    include_once get_template_directory() . '/components/definitions/column-padded-cta.php';
    include_once get_template_directory() . '/components/definitions/wysiwyg.php';
    include_once get_template_directory() . '/components/definitions/text-with-image.php';
    include_once get_template_directory() . '/components/definitions/text-with-video.php';
    include_once get_template_directory() . '/components/definitions/text-with-chart.php';
    include_once get_template_directory() . '/components/definitions/hero.php';
    include_once get_template_directory() . '/components/definitions/column-cta.php';
    include_once get_template_directory() . '/components/definitions/vertical-cta.php';
    include_once get_template_directory() . '/components/definitions/callout.php';
    include_once get_template_directory() . '/components/definitions/buttons.php';
    include_once get_template_directory() . '/components/definitions/registration_form.php';
    include_once get_template_directory() . '/components/definitions/accordion.php';
}
