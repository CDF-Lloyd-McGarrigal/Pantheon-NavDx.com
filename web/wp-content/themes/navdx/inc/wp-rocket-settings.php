<?php
/**
 * Exclude scripts from WP Rocket’s defer JS option.
 *
 * @author Rob Szpila <robert@arteric.com>
 * @param  array  $excluded_files   Array of script URLs to be excluded
 * @return array                    Extended array script URLs to be excluded
 * https://docs.wp-rocket.me/article/976-exclude-files-from-defer-js
 */
function exclude_files( $excluded_files = array() ) {
	/**
	 * EDIT THIS:
	 * Replace, or multiply and edit below line as needed to exclude files.
	 */
	$excluded_files[] = '/ajax/libs/jquery/1.12.4/jquery.min.js';
	// STOP EDITING
	return $excluded_files;
}

add_filter( 'rocket_exclude_defer_js', 'exclude_files' );
