<?php
/**
 * navdx functions and definitions
 *
 * @package navdx
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

require_once( get_template_directory() . '/custom-nav-walker.php' );

if ( ! function_exists( 'navdx_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function navdx_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on navdx, use a find and replace
	 * to change 'navdx' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'navdx', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'navdx' ),
		'eyebrow' => __( 'Action Menu', 'navdx' ),
		'footer' => __( 'Footer Menu', 'navdx' ),
		'contact' => __('Direct Links', 'navdx'),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'navdx_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // navdx_setup
add_action( 'after_setup_theme', 'navdx_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function navdx_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'navdx' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'navdx_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function navdx_scripts() {
	// Remove included jquery and use Google's version
	wp_deregister_script('jquery');
	wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4');
	wp_enqueue_script('jquery');

	wp_enqueue_style( 'navdx-style', get_stylesheet_uri() );
	wp_enqueue_style('navdx-theme-style', get_template_directory_uri().'/css/main.css');
	// Uncomment these if using front-end-build-tools
	wp_enqueue_script( 'navdx-vendor-script', get_template_directory_uri() . '/js/vendor.js');
	wp_enqueue_script( 'navdx-app-script', get_template_directory_uri() . '/js/app.js' );
	wp_enqueue_script( 'navdx-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

}
add_action( 'wp_enqueue_scripts', 'navdx_scripts' );


/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * AFO - Feb 23, 2016 - Removes unncessary filters and redirects
 */
require get_template_directory() . '/inc/remove-filters.php';

/**
 * AFO - Aug 11, 2015
 * Include the generic redirect handler.
 */
include_once get_template_directory() . '/inc/template-redirect-handler.php';

/**
 * AFO - Jan 23, 2019
 * Various hooks and filters
 */
include_once get_template_directory() . '/inc/hooks-and-filters.php';

/**
 * AFO - Jan 23, 2019
 * Include various helper functions for template building
 */
include_once get_template_directory() . '/inc/helper-functions.php';

/**
 * AFO - Jan 23, 2019
 * Include custom hooks for WP Rocket
 */
include_once get_template_directory() . '/inc/wp-rocket-settings.php';

/**
 * AFO - Aug 11, 2015
 * Include shortcodes. Uncomment this line to start including shortcodes
 */
include_once get_template_directory() . '/shortcodes/shortcodes.php';


/**
 * Jeremy McAllister - April 9, 2019
 * Include components
 */
include_once get_template_directory() . '/components/components.php';

/**
 * Jeremy McAllister - April 9, 2019
 * Include sections
 */
include_once get_template_directory() . '/sections/sections.php';

/**
 * AFO - Aug 5, 2021
 * Include Metabox.io settings page. Uncomment this line to start including
 */
include_once get_template_directory() . '/metaboxes/metaboxes.php';

/**
 * AW
 */
include_once get_template_directory() . '/inc/theme-functions.php';