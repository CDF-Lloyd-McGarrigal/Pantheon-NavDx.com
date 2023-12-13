<?php
/**
 * This file contains the main settings page and all the custom fields associated with it.
 *
 * @package navdx
 */

/**
 * Filter for Metabox to create the site settings page.
 *
 * @param array $settings_pages The currently defined settings pages.
 *
 * @return array                 The full array of defined settings pages.
 */
function navdx_site_options_page(array $settings_pages)
{
    // Main site settings page
    $settings_pages[] = array(
        'id'            => 'navdx-site-options',
        'option_name'   => 'navdx_site_options',
        'menu_title'    => __('Site Options', 'navdx'),
        'position'      => '2',
        'icon_url'      => 'dashicons-admin-settings',
        'tabs'          => array(
            'header'        =>  __('Header Settings', 'navdx'),
            'footer'        =>  __('Footer Settings', 'navdx'),
            'page404'       => __( '404 Page Settings', 'navdx' ),
            'social'    => __('Social Media Links', 'navdx'),
            'registration'    => __('Registration Form(s)', 'navdx'),
        ),
    );

    return $settings_pages;
}//end navdx_site_options_page()

add_filter('mb_settings_pages', 'navdx_site_options_page');

/**
 * Sitewide usable function to simplify grabbing a site option
 *
 * @param string $the_option The option name to grab.
 *
 * @return mixed              The option value or false if not found.
 */
function get_theme_option($the_option)
{
    // Grab the settings field
    $settings = get_option('navdx_site_options');

    // Check if the option we're requesting exists
    if (isset($settings[ $the_option ])) {
        // If it does, send it back
        return $settings[ $the_option ];
    } else {
        // If it doesn't, send false
        return false;
    }

    return false; // Fail-safe
}//end get_theme_option()
