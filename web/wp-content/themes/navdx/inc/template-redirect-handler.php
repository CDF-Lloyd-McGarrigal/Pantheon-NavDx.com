<?php
/**
 * Generic redirect handler for our WordPress installations.
 * Redirects potentially broken templates to 404 pages
 * AFO - updated Feb 17, 2016 to handle exceptions
 */

function bwp_template_redirect()
{
	// What we want to override the 404'ing
	if( 
		is_singular( 'attachment' ) 			||	// Singular attachment pages, like attachments not uploaded to a post
		// is_singular( 'post_type' )			||  // Copy and paste, rename for any custom post type
		false 										// Leaving this here so you can have || after each conditional
	){
		return;
	}

	// Otherwise, lets figure out what we want to 404 and what we don't.
	if (
		/* Comment out any line to reenable that page. */
		is_single() 							|| 	// Disable single post pages. 
		is_author() 							||	// [ARCHIVE] Disable author pages
		is_post_type_archive()					||	// [ARCHIVE] Disable post type archives
		is_category()							||	// [ARCHIVE] Disable category archive pages
		is_tag()								||	// [ARCHIVE] Disable tag pages
		is_tax()								||	// [ARCHIVE] Disable taxonomy pages
		is_date()								||	// [ARCHIVE] Disable date-based archives
		// is_archive()							||	// Disable all archive pages, denoted here with [ARCHIVE]
		// is_attachment() 						||	// Disable attachement pages
		false 										// Leaving this here so you can have || after each conditional

	)
	{
		// force 404
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		include( get_template_directory() . "/404.php");
		die;
	}
}
add_action('template_redirect', 'bwp_template_redirect');