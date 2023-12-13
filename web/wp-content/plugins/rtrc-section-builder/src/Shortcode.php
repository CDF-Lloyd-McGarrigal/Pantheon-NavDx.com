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

namespace ArtericSectionBuilder;

class Shortcode {

	protected $_name = 'insert-section';

	function __construct(){

		$classInfo = new \ReflectionClass($this);
		
		// Assign all the hooks and filters
		$this->js_file = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', dirname( $classInfo->getFileName() )) . '/' . $this->_name . '/wysiwyg.js';
		$this->js_hook = '_' . $this->_name . '_shortcode';
		
		add_shortcode( $this->_name, [ $this, 'shortcode' ] );
		add_action( 'init', [ $this, 'mce_hooks' ] );
	}

	function shortcode( $atts, $content = "" ) {
		
		// [Step 2] Get input
		$atts = shortcode_atts( array(
			'id' => ''
		), $atts, $this->_name);

		// [Step 3] Return the markup 
		$this->markup($atts['id']);
	}

	function markup($sectionID){

		// [Step 4] Render the selected section
		rtrc_render_section($sectionID);
	}

	// This TinyMCE part shamelessly cribbed from http://code.tutsplus.com/tutorials/guide-to-creating-your-own-wordpress-editor-buttons--wp-30182

	function mce_hooks() {
		add_filter( 'mce_external_plugins', [ $this, 'register_plugin' ] );
	    add_filter( 'mce_buttons', [ $this, 'register_buttons' ] );
	}

	function register_plugin( $plugin_array ) {
		
	    $plugin_array[ $this->js_hook ] = $this->js_file;
	    return $plugin_array;
	}

	function register_buttons( $buttons ) {
	    array_push( $buttons, $this->_name . '_shortcode' );
	    return $buttons;
	}
}
