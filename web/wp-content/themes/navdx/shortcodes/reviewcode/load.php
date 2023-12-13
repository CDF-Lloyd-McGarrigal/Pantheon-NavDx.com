<?php
/**
 * ========================
 * == SHORTCODE BASELINE ==
 * ========================
 * Usage instructions
 * 	1. replace every instance of "sampleshortcode" in this file with the name of your shortcode. This is what will go in the brackets. Normal WordPress convention is that this is lowercase
 * 	2. In the "shortcode function", change the array passed to shortcode_atts to match all the fields of the shortcode. 
 * 	3. Then pass those fields into the sprintf that's being returned by that function.
 * 	4. Write the markup within the markup function. Note the provided example uses ob_start() and ob_get_clean() to write raw HTML out, even with the replacement variables within.
 * 	5. Edit shortcode.js (continued insturctions within that file)
 */

if( !shortcode_exists( 'reviewcode' ) ){

	class reviewcodeShortcode {

		// [Step 1] The name of the shortcode.
		private $_name = 'reviewcode';

		function __construct(){
			
			
			// Not adding buttons to the mce editor currently 
			// Assign all the hooks and filters 
			// $this->js_file = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', dirname( __FILE__ )) . '/wysiwyg.js';
			// $this->js_hook = '_' . $this->_name . '_shortcode';

			add_shortcode( $this->_name, [ $this, 'shortcode' ] );
			// add_action( 'init', [ $this, 'mce_hooks' ] );
		}

		function shortcode( $atts, $content = "" ) {
			$pact_code = get_theme_option('global_review_code') ?: '';
			$meta['change_review_code'] = this_post_meta('change_review_code') ?: '';

			if($meta['change_review_code'] != "") {
				return $meta['change_review_code'];
			}
			else {
				return $pact_code;
			}
		}

		// This TinyMCE part shamelessly cribbed from http://code.tutsplus.com/tutorials/guide-to-creating-your-own-wordpress-editor-buttons--wp-30182

		// function mce_hooks() {
		// 	add_filter( 'mce_external_plugins', [ $this, 'register_plugin' ] );
		//     add_filter( 'mce_buttons', [ $this, 'register_buttons' ] );
		// }

		function register_plugin( $plugin_array ) {
			
		    $plugin_array[ $this->js_hook ] = $this->js_file;
		    return $plugin_array;
		}

		// function register_buttons( $buttons ) {
		//     array_push( $buttons, $this->_name . '_shortcode' );
		//     return $buttons;
		// }
	}

	$_reviewcodeShortcode = new reviewcodeShortcode();
}