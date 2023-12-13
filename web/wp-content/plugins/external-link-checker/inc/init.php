<?php

// JROX October 22, 2016
// Take Exclude Urls from the Settings page and passing it to javascript file.
if (!defined('ABSPATH')) {
    die;
};

/**
 * Load the external link checker scripts
 *
 * @return void
 */
function elc_load_scripts()
{

    // Global the class
    global $ac_elc;

    // Get the list of excluded domains
    $excluded_domains = $ac_elc->get_option( 'excluded_domains' );

    // Break it open on comma
    $excluded_domains = explode(",", $excluded_domains );

    // Get the list of excluded domains
    $excluded_urls = $ac_elc->get_option( 'excluded_urls' );

    // Break it open on comma
    $excluded_urls = explode(",", $excluded_urls );

    // Get the array of modal triggers from the plugin settings page.
    $modal_triggers = $ac_elc->get_option( 'modal_triggers' );

    if( $modal_triggers ){

        // Parse the domains
        foreach( $modal_triggers as &$trigger ){

            if( !isset($trigger['domains']) ){
                $trigger['domains'] = '';
            }

            // Split string by , into array.
            $exclude_domain_array = explode(",", $trigger['domains'] );

            $trigger['domains'] = $exclude_domain_array;

            if( !isset($trigger['urls']) ){
                $trigger['urls'] = '';
            }

            // Split string by , into array.
            $exclude_url_array = explode(",", $trigger['urls'] );

            $trigger['urls'] = $exclude_url_array;
        }
    }

    if (!is_admin()) {
        wp_register_script('elc-script', plugin_dir_url(__FILE__) . '../js/elc-script.js', array('jquery'));

        wp_localize_script('elc-script', 'exclude', array(

            'default_modal_id'          => $ac_elc->get_option( 'default_modal_id' ),
            'excluded_domains'          => $excluded_domains,
            'excluded_urls'             => $excluded_urls,
            'default_modal_content'     => $ac_elc->get_option( 'default_content' ),
            'specific_modal_triggers'   => $modal_triggers

        ));

        wp_enqueue_script('elc-script');
    }
}//end elc_load_scripts()


add_action('wp_enqueue_scripts', 'elc_load_scripts');