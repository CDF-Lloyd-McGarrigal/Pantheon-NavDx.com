<?php

namespace BaselinePlugin;

class Shortcode {

	protected $_name = 'shortcode';

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
			'slug' => ''
		), $atts, $this->_name);

		// [Step 3] Return the markup 
		return sprintf( 
			$this->markup(), 
			$content,			// %1$s
			$atts['slug']	// %2$s
		);
	}

	function markup(){

		// [Step 4] Get the markup, we're going to break out here so we can just write HTML straight. Note that since we're passing this to sprintf, we can use replacment variables.
		ob_start();
?>
<p property="%2$s">%1$s</p>
<?php
		return ob_get_clean();
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
