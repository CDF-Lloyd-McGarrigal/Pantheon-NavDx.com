<?php
/**
 * This file contains commonly used functions throughout this theme.
 */

/**
 * IMAGE HELPER FUNCTIONS
 */
if( !function_exists( 'img_from_id' ) ){

	/**
	 * Helper function that just echoes out "get_img_from_id", wordpress style
	 * @param int $id	The ID of the image
	 * @param string $size The image size to grab
	 * @param string $class Optional, Classes for the image tag
	 *
	 * @return void
	 */
	function img_from_id( $id, $class = '', $size = '', $only_url = false ){

		// This helper function just echoes the output of the get function
		echo get_img_from_id( $id, $class, $size, $only_url );
	}
}

if( !function_exists( 'get_img_from_id' ) ){

	/**
	 * Generate an image tag from an image ID
	 * @param int $id	The ID of the image
	 * @param string $size The image size to grab
	 * @param string $class Optional, Classes for the image tag
	 *
	 * @return string   The image tag
	 */
	function get_img_from_id( $id, $class = '', $size = ''){

		$imageProps = get_img_props_from_id($id, $size);

		if( $imageProps === false ){
			return false;
		}

		// Get the image size. The default is "full"
		$url = $imageProps['url'];
		if( isset( $imageProps['sizes'][$size] ) ){
		   $url = $imageProps['sizes'][$size]['url'];
		}

		// Construct the image tag.
		return sprintf( '<img loading="lazy" src="%s" alt="%s" title="%s" class="%s" />',
			$url,
			$imageProps[ 'alt' ],
			$imageProps[ 'title' ],
			$class
		);
	}
}

if( !function_exists( 'img_url_from_id' ) ){

	/**
	 * Helper function that returns a URL given an ID
	 * @param int $id	The ID of the image
	 * @param string $size The image size to grab
	 *
	 * @return string thr URL of the image
	 */
	function img_url_from_id( $id, $size = '' ){

		// Extract all the relevant info
		$imageProps = get_img_props_from_id( $id );

		// Get the image size. The default is "full"
		$url = $imageProps['url'];
		if( isset( $imageProps['sizes'][$size] ) ){
			$url = $imageProps['sizes'][$size]['url'];
		}

	   return $url;
	}
}

if( !function_exists( 'get_img_props_from_id' ) ){

	/**
	 * Helper function that returns a URL given an ID
	 * @param int $id	The ID of the image
	 * @param string $size The image size to grab
	 *
	 * @return string thr URL of the image
	 */
	function get_img_props_from_id( $id ){

		 // Metabox likes giving us arrays. Lets just handle that
		if( is_array( $id ) ){
			$id = reset( $id );
		}

		// If we weren't provided a number, bail
		if( ! ctype_digit(strval($id)) ){
			return false;
		}

		// Extract all the relevant info
		$imageProps = wp_prepare_attachment_for_js( $id );

	   return $imageProps;
	}
}

if( !function_exists( 'display_img_plus' ) ){

	/**
	 * Returns img with added functionality of Data attribute.
	 * @param int $id	The ID of the image
	 * @param string $size The image size to grab
	 *
	 * @return string thr URL of the image
	 */
	function display_img_plus( $id, $class = '', $dataID = '' ){

		 // Metabox likes giving us arrays. Lets just handle that
		if( is_array( $id ) ){
			$id = reset( $id );
		}

		// If we weren't provided a number, bail
		if( ! ctype_digit(strval($id)) ){
			return false;
		}

		// Extract all the relevant info
		$imageProps = wp_prepare_attachment_for_js( $id );

		return sprintf( '<img loading="lazy" src="%s" alt="%s" title="%s" class="%s" data-id="%s"  />',
			$imageProps['url'],
			$imageProps[ 'alt' ],
			$imageProps[ 'title' ],
			$class,
			$dataID
		);
	}
}

if( !function_exists( 'picture_tag' ) ){

	/**
	 * Echo out an array of images provided an array of media query to srcset
	 * @param  array  $images Array of arrays containing [media=>'', srcset=>'']
	 *
	 * array(
	 *  	[media=>'', srcset=>''],
	 *  	[media=>'', srcset=>'']
	 *  ) 
	 * 
	 * @return echo
	 */
	function picture_tag( array $images ){

		echo get_picture_tag($images);
	}
}

if( !function_exists( 'get_picture_tag' ) ){

	/**
	 * Return an array of images provided an array of media query to srcset
	 * @param  array  $images Array of arrays containing 
	 * 
	 *  array(
	 *  	[media=>'', srcset=>''],
	 *  	[media=>'', srcset=>'']
	 *  )                      
	 * 
	 * @return string	The picture tag
	 */
	function get_picture_tag( array $images ){

		if( empty($images) ){
			return '';
		}

		$outputString = '<picture>';

		$lastKey = -1;
		foreach( $images as $key => $theImage ){

			if( !isset($theImage['media']) || !isset( $theImage['srcset'] ) ){
				continue;
			}

			$imageUrl = img_url_from_id($theImage['srcset']);

			if( $imageUrl == '' ){
				continue;
			}

			$outputString .= sprintf(
				"\n<source media=\"%s\" srcset=\"%s\">",
				$theImage['media'],
				$imageUrl
			);

			$lastKey = $key;
		}

		if( $lastKey !== -1 ){
			$outputString .= get_img_from_id($images[$lastKey]['srcset']);
		}
		else {
			return '';
		}

		$outputString .= "\n</picture>";

		return $outputString;
	}
}
/**
 * END IMAGE HELPER FUNCTIONS
 */

if( !function_exists('array_default' ) ){

	/**
	 * Set up array defaults in place
	 * @param  &array &$array  The array to set defaults for
	 * @param  array $default An array of defaults
	 * 
	 * @return void
	 */
	function array_default( &$array, $default ){
		$array = array_merge( $default, $array );
	}
}

if( !function_exists( 'get_gravityforms_list' ) ){

	function get_gravityforms_list() {

		// For later - get gravityforms
		static $forms = null;
		if( class_exists('GFAPI') && is_null( $forms ) ) {
		                                // active, trashed
		    $formList = GFAPI::get_forms(true, false);
		    foreach ($formList as $theForm) {
		        $forms[ $theForm['id'] ] = $theForm[ 'title' ];
		    }
		}
		elseif( is_null( $forms ) ) {
			$forms = array();
		}

		return $forms;
	}
}

if( !function_exists( 'this_post_meta' ) ){

	function this_post_meta( $meta_key = '', $single = true ){

		return get_post_meta( get_the_ID(), $meta_key, $single );
	}
}

if( !function_exists( 'rtrc_build_components' ) ){

	function rtrc_build_components(){

		echo 'Please install the component builder';
	}
}

if( !function_exists( 'rtrc_build_sections' ) ){

	function rtrc_build_sections(){

		echo 'Please install the section builder';
	}
}

if( !function_exists( 'nav_menu_contains_page' ) ) {
	/**
	 * Check if post is in a menu
	 * Found online
	 *
	 * @param $menu menu name, id, or slug
	 * @param $object_id int post object id of page
	 * @return bool true if object is in menu
	 */
	function nav_menu_contains_page( $menu = null, $object_id = null ) {

	    // get menu object
	    $menu_object = wp_get_nav_menu_items( esc_attr( $menu ) );

	    // stop if there isn't a menu
	    if( ! $menu_object )
	        return false;

	    // get the object_id field out of the menu object
	    $menu_items = wp_list_pluck( $menu_object, 'object_id' );

	    // use the current post if object_id is not specified
	    if( !$object_id ) {
	        global $post;
	        $object_id = get_queried_object_id();
	    }

	    // test if the specified page is in the menu or not. return true or false.
	    return in_array( (int) $object_id, $menu_items );

	}
}

if (!function_exists('get_array_of_menus')) {

    /**
     * Gets the menus and returns an arary of slug => name.
     *
     * @return array An array of all the menus as slug->name.
     */
    function get_array_of_menus()
    {

        // This will be our output array. Using static, we can only have it set once.
        // We'll take advantage of this to not have to query every time this function is called.
        static $menu_slugs_names = null;

        // Using static, we can store the value of this function and never need to requery.
        if (is_null($menu_slugs_names)) {
            // Set to array. This is the true typing.
            $menu_slugs_names = array();

            // Get the menu terms
            $menus = get_terms('nav_menu');

            foreach ($menus as $the_menu) {
                $menu_slugs_names[ $the_menu->slug ] = $the_menu->name;
            }
        }

        return $menu_slugs_names;
    }//end get_array_of_menus()

}//end if

if( !function_exists( 'get_url' ) ){

	/**
	 * Get URL based on whether we were provided a number or string
	 * @param  mixed $url The URL or a post ID
	 * 
	 * @return string      The URL
	 */
	function get_url( $url ) {

		// If we were provided a number...
		if( ctype_digit(strval($url)) ){

			return get_the_permalink( $url );
		}

		return $url;
	}
}

if( !function_exists('add_x_ua_compatability_meta' ) ) {
    function add_x_ua_compatability_meta() {

        $header_tag = '<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>' . "\n";

        if( defined('WP_ROCKET_VERSION') && !defined( 'DONOTROCKETOPTIMIZE' ) ){
            add_filter( 'rocket_buffer', function($buffer) use ($header_tag){
                $buffer = preg_replace( '/<head(.*)>/U', '<head$1>' . $header_tag, $buffer, 1 );

                return $buffer;
            }, 999);
        }
        else {
            echo $header_tag;
        }

    }
}

if( !function_exists( 'wysiwyg_format' ) ){
	/**
	 * Format a string with the WYSIWYG filters that we normally disable
	 * 
	 * @param  string $string The string to filter
	 * 
	 * @return string         A filtered string
	 */
    function wysiwyg_format( $string ){
        return apply_filters( 'the_content', wpautop($string) );
    }
}

if(!function_exists('dd')){
	/**
	 * Dump and die
	 *
	 * @param mixed $item
	 * @return void
	 */
	function dd($item){
		die('<pre>'.var_export($item, true).'</pre>');
	}
}