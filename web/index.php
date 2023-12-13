<?php
/**
 * Welcome to the Arteric WordPress Baseline
 * This index.php customized to use WordPress in a separate directory.
 * Don't replace it with the standard one.
 * 
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */
/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
// Check to see if the wp-blog-header.php exists. If it does, require it
// require('./wordpress/wp-blog-header.php');

( 
	realpath('./wordpress/wp-blog-header.php') && 
	require('./wordpress/wp-blog-header.php') 
) 
// If it doesn't, die and inform the user.
|| die('<strong>Error:</strong> You forgot to install a core. Please run composer install/update to grab a core.');

