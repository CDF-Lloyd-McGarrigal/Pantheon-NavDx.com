<?php
/**
 * AFO - Feb 23, 2016 - anthony@arteric.com
 * The purpose of this file is to include all the remove_filters, allowed_tags, remove_actions, etc.
 * The goal is to strip the annoying "hand holdy" parts of WordPress out
 */

// See: http://davidwalsh.name/disable-autop
// and https://codex.wordpress.org/Function_Reference/wpautop

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

/**
 * Remove wpautop from acf wysiwyg
 */
remove_filter( 'acf_the_content', 'wpautop' );

/**
 * Remove texturize because fancy quotes and fancy apostrophes suck
 */
remove_filter( 'the_title', 'wptexturize'); 
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'acf_the_content', 'wptexturize' );

/**
 * Remove the dumb auto-redirect from a bad URL to a good one that WordPress does
 */
remove_action('template_redirect', 'redirect_canonical');

/**
 * Do the JWPlayer filter on ACF fields
 */
if( class_exists( 'JWP6_Shortcode' ) ){
	add_filter('acf_the_content', array('JWP6_Shortcode', 'the_content_filter'), 11);
}

// afo - Feb 12, 2016 - Allow break tags in post titles and classes on i tags
$allowedtags['br'] = array(
    'class' => 1
);
$allowedtags['i']['class'] = 1;

//let's customize TinyMCE editor
// https://www.tinymce.com/docs/configure/content-filtering/
function my_Tiny_MCE($in) {

	$in['cleanup_callback'] = '';
	// $in['apply_source_formatting'] = false;
	// $in['allow_html_in_named_anchor'] = true;
	// $in['convert_fonts_to_spans'] = false;
	// $in['entity_encoding'] = 'raw';
	$in['verify_html'] = false;
	// $in['wpautop'] = false; // This seems to break things harder?
	$in['remove_linebreaks'] = false;
	$in['remove_redundant_brs'] = false;
	$in['invalid_elements'] = '';
	//	$in['forced_root_block'] = false;
	// Convert newline characters to BR tags
	// $in['convert_newlines_to_brs'] = false; 
	return $in;
}
add_filter('tiny_mce_before_init', 'my_Tiny_MCE');
add_filter('teeny_mce_before_init', 'my_Tiny_MCE'); 

// Remove all the wpautop filters everywhere
function remove_all_wpautop() {
    foreach( hooks_with_filter('wpautop') as $hook ) {
        remove_filter( $hook, 'wpautop' );
    }
}
add_action( 'admin_init', 'remove_all_wpautop' );


/**
 * Lloyd McGarrigal - 10/22/2015
 * Find all hooks using a particular filter
 */
function hooks_with_filter( $filter_to_find ) {
    global $wp_filter;
    $retval = array();
    // wp_filter_kses
    foreach( $wp_filter as $hook => $filters ) {
        foreach( $filters as $filter ) {
            foreach( $filter as $info ) {
                if( $info['function'] === $filter_to_find ) {
                    $retval[] = $hook;
                }
            }
        }
    }
    return $retval;
}

// Removing a huge breaking change from Meta Box, starting August 29 => sanitizes HTML out of metaboxes
remove_all_filters( 'rwmb_sanitize' );