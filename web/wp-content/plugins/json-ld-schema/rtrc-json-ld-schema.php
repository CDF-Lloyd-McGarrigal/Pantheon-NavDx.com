<?php
/*
 * Plugin Name: Arteric JSON-LD Schema Plugin
 * Version: 1.6.0
 * Plugin URI: http://www.arteric.com/
 * Description: Adds the JSON-LD schema to a WordPress site.
 * Author: Anthony Outeiral
 * Author URI: http://www.arteric.com/
 *
 * Text Domain: rtrc-json-ld-schema
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Anthony Outeiral <anthony@arteric.com>
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
    exit;
}

// Load plugin class files
require_once('includes/class-rtrc-json-ld-schema.php');
require_once('includes/class-rtrc-json-ld-schema-settings.php');
require_once('includes/class-rtrc-json-ld-schema-page-settings.php');

// Load plugin libraries
require_once('includes/lib/class-rtrc-json-ld-schema-admin-api.php');
require_once('includes/lib/class-rtrc-json-ld-schema-post-type.php');
require_once('includes/lib/class-rtrc-json-ld-schema-taxonomy.php');

/**
 * Returns the main instance of rtrc_json_ld_schema to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object rtrc_json_ld_schema
 */
function rtrc_json_ld_schema()
{
    $instance = rtrc_json_ld_schema::instance(__FILE__, '1.6.0');

    if (!property_exists( $instance, 'settings' ) || is_null($instance->settings)) {
        $instance->settings = rtrc_json_ld_schema_Settings::instance($instance);
    }

    if (!property_exists( $instance, 'page_settings' ) || is_null($instance->page_settings)) {
        $instance->page_settings = rtrc_json_ld_schema_Page_Settings::instance($instance);
    }

    return $instance;
}//end rtrc_json_ld_schema()


rtrc_json_ld_schema();
