<?php


if( basename(__FILE__) === $_SERVER['SCRIPT_FILENAME'] ) {
	die();
}

/**
*	Arteric Customized wp-config.php
*	There should be nothing that has to be changed in this config file
*	All the site-specific configuration options should be in the local-config.php file
*	If you change something here, keep in mind that you intend it to be included in the repository and greater codebase
*	Don't change something here if you can't verify that every single installation of this site wants it
* 
* @package WordPress
*
*/

/**
 * The default theme, if you change the name of the navdx theme, please change this too.
 */

define( 'WP_DEFAULT_THEME', 'navdx' );

/**
 * The following is required reading if you've opened this file for editting:
 * http://codex.wordpress.org/Editing_wp-config.php
 *
 * Another good resource on the wp-config file:
 * http://premium.wpmudev.org/blog/wordpress-wp-config-file-guide/
 *
 * It is strongly suggested you take a look at that one too.
 */

if ( __FILE__ == $_SERVER['SCRIPT_FILENAME'] ){
	die();
}

/**
 * Anthony Outeiral - Taken from Lloyd McGarrigal's implementation on OPro Original
 * 9/13/2016
 *
 * The wp-config.php needs to be stored in an NFS and will be symlinked.
 * In order to get the correct path to the content folder we will use the
 * ABSPATH constant defined in wp-load.php.  But because we put the
 * WordPress core in its own directory we need to get the parent directory
 * of ABSPATH.
 */

define('DOCROOT', dirname(ABSPATH));

/**
 * If you're looking for configurable things like DB config and whatnot, its not here
 * Check the local-config.php file instead
 */

$local_config_file = DOCROOT . '/local-config.php';
if (! file_exists($local_config_file)) {
    die('Error: missing local configuration file');
}

require($local_config_file);

/**
 * These bits set a whole pile of URLs internally used by WordPress, don't touch these.
 * Unless you're fixing something and understand the consequences.
 */

define( 'DEFAULT_HOST', $default_host );
define( 'WPTOOLS_URL', 'https://' . DEFAULT_HOST );
define( 'DEFAULT_URL', 'http://'  . DEFAULT_HOST );

$current_host = DEFAULT_HOST;
if ( isset( $_SERVER['HTTP_HOST'] ) && ! empty( $_SERVER['HTTP_HOST'] ) ){
	$current_host = $_SERVER['HTTP_HOST'];
}

define( 'CURRENT_HOST', $current_host );

$protocol = 'http://';
if ( isset( $_SERVER['HTTPS'] ) && ! empty( $_SERVER['HTTPS'] ) ){
	$protocol = 'https://';
}

define( 'WP_SITEURL', $protocol . CURRENT_HOST . '/wordpress');
define( 'WP_HOME',    $protocol . CURRENT_HOST );

// set content directory path and URL
if ( is_dir( DOCROOT . '/wp-content' ) ) {
	$local_wp_content_path = DOCROOT . '/wp-content';
	define( 'WP_CONTENT_DIR', $local_wp_content_path );
	define( 'PLUGINDIR', 'content/plugins' );
	define( 'MUPLUGINDIR', 'content/mu-plugins' );
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	define( 'WPMU_PUGIN_DIR', WP_CONTENT_DIR . '/mu-plugins' );

	$local_wp_content_url =  WP_HOME . '/wp-content';
	define( 'WP_CONTENT_URL', $local_wp_content_url );
	define( 'WP_PLUGIN_URL', $local_wp_content_url . '/plugins' );
}

/**
 * This turns off ALL automatic updates.
 * See http://codex.wordpress.org/Configuring_Automatic_Background_Updates for
 * information on just turning off some of the automatic updates.
 */

define( 'AUTOMATIC_UPDATER_DISABLED', true );

/**
 * This disable the plugin and theme editor.
 * I have included it for reference but will leave it commented out by default.
 * This is because the stricter configuration below makes it redundant
 *
 * See http://codex.wordpress.org/Editing_wp-config.php#Disable_the_Plugin_and_Theme_Editor
 * for a fuller explanation
 */

//define( 'DISALLOW_FILE_EDIT', true );

/**
 * This prevents editting of plugins and themes as well as the installation
 * and updating of themes and plugins.
 *
 * See http://codex.wordpress.org/Editing_wp-config.php#Disable_the_Plugin_and_Theme_Editor
 * for a fuller explanation
 */

define( 'DISALLOW_FILE_MODS', true );

/**
 * http://blog.cloudfour.com/javascript-gzip-compression-in-wordpress-whats-possible-and-what-hurts/
 * Forces WordPress scripts to be GZIP'd up to save space, and also to load faster
 */

define( 'ENFORCE_GZIP',       true );

/**
 * Just some helpful REGEX to get the src of an image.
 */
define( 'IMG_SRC_REGEX',      '/<img.*?src=["\'](.*?)["\'][^>]*>/i' );

/**
 * Multisite is set to off by default
 * To activate multisite remove all the SINGLE LINE COMMENTS below
 * i.e. the double slashes //
 *
 * Keep in mind it is set to a subdomain install
 */

// /* Multisite */
// define( 'WP_ALLOW_MULTISITE', true );

// define('MULTISITE', true);
// define('SUBDOMAIN_INSTALL', true);
// define('DOMAIN_CURRENT_SITE', $default_host);
// define('PATH_CURRENT_SITE', '/');
// define('SITE_ID_CURRENT_SITE', 1);
// define('BLOG_ID_CURRENT_SITE', 1);
// define( 'NOBLOGREDIRECT', $default_host );

/**
 * Turn this on if we have domain mapping happening
 * This is required for domain mapping to work
 */

// define( 'SUNRISE', 'on' );

/** Absolute path to the WordPress directory. */
if ( ! defined('ABSPATH') ){
	define( 'ABSPATH', DOCROOT . '/' );
}

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */

define( 'WPLANG', '' );

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );