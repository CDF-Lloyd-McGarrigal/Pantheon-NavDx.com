<?php
/**
 * The purpose of this functions file is to hold all the functions that would normally be put in functions.php,
 * but should be used regardless of the theme
 *
 * @package navdx
 */

/**
 * This shortcode prints out the full URL of the current site, with no trailing slash.
 */
function arteric_wp_base_full_url_shortcode() {
	return get_bloginfo('url');
}
add_shortcode('full_url','arteric_wp_base_full_url_shortcode');

/*
 * Remove WordPress Logo From Header
 */

function arteric_wp_base_remove_admin_logo( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'wp-logo' );
}

add_action( 'admin_bar_menu', 'arteric_wp_base_remove_admin_logo', 11 );

/*
 * Modify Footer Branding - Remove version number
 * Version number is important for HDM, we'll leave commented for now
 */

function arteric_wp_base_remove_version_from_admin_footer() {
  remove_filter( 'update_footer', 'core_update_footer' );
}

add_action( 'admin_menu', 'arteric_wp_base_remove_version_from_admin_footer' );


/*
 * Modify Footer Branding - Replace "Thank you for creating with WordPress"
 */

function arteric_wp_base_modify_wp_message () {
  echo 'Created by <a href="http://arteric.com">Arteric</a>';
}

add_filter('admin_footer_text', 'arteric_wp_base_modify_wp_message');


/*
 * Remove Contextual Help Tab - the one in the upper right corner
 */

function arteric_wp_base_remove_contextual_help() {
  $screen = get_current_screen();
  $screen->remove_help_tabs();
}

add_action( 'admin_head', 'arteric_wp_base_remove_contextual_help' );

/*
 * Removing Dashboard Widgets
 */

function arteric_wp_base_remove_dashboard_widgets(){
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');   // Activity
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // Plugins
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts
    remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
    remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');   // Gravity Forms Widget
}
add_action('wp_dashboard_setup', 'arteric_wp_base_remove_dashboard_widgets');

/**
 * AFO - Feb 25, 2016 - Removing WordPress version from Javascript and CSS
 * Source: https://wordpress.org/support/topic/get-rid-of-ver-on-the-end-of-cssjs-files
 */
function arteric_wp_base_remove_cssjs_ver( $src ) {
  if( strpos( $src, 'ver=' ) ){
    $src = remove_query_arg( 'ver', $src );
  }

  //Cache busting - Adding the first 9 characters of the package-version.txt to the query string
  //for the js and css file. Fallback is date.
  $file = DOCROOT . '/package-version.txt';

  if ( file_exists( $file ) ) {
    $contents = file_get_contents(DOCROOT . '/package-version.txt');
    $cb = substr($contents, 0, 9);
  } else {
    $cb = date("Ymd");
  }

  $src = add_query_arg( 'cb', $cb, $src );

  return $src;
}
add_filter( 'style_loader_src', 'arteric_wp_base_remove_cssjs_ver', 99, 2 );
add_filter( 'script_loader_src', 'arteric_wp_base_remove_cssjs_ver', 99, 2 );

/**
 * AFO - Feb 25, 2016 - Ensuring no version numbers appear
 * This bit here removes the default version tag from the wp_styles and wp_scripts.
 * Some bits of JS and CSS don't go through the above filter, so we have to handle those.
 * 
 * I did a search on the entire core for where the default version is looked at, and 
 * its only in the load-scripts.php and load-styles.php echo statements. This should be safe.
 */
function arteric_wp_base_clear_default_version(){
  $wp_styles = wp_styles();
  $wp_styles->default_version = '';

  $wp_scripts = wp_scripts();
  $wp_scripts->default_version = '';
}
add_action( 'init', 'arteric_wp_base_clear_default_version' );

/**
 * AFO - Feb 25, 2016 - Removing Generator meta tag with version number
 * The redundancy ensures that it won't appear in something like an RSS feed
 * Source: https://wordpress.org/support/topic/remove-ltmeta-namegenerator-contentwordpress-25-gt
 */
function arteric_wp_base_hide_wp_vers()
{
    return '';
}
add_filter('the_generator','arteric_wp_base_hide_wp_vers');
remove_action('wp_head', 'wp_generator'); 

/**
 * AFO - Feb 25, 2016 - Removing the "Welcome" panel from the dashboard
 * Source: https://codex.wordpress.org/Plugin_API/Action_Reference/welcome_panel
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );

/**
 * Modifies the htaccess rules WordPress will try to shove in with the baseline defaults
 * @param  string $rules The rules to inject input
 * 
 * @return string        The rules to inject output
 */
function arteric_modified_base_htaccess( $rules )
{
  $rules = 'RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) wordpress/$2 [R=301,L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ wordpress/$2 [R=301,L]
RewriteRule . index.php [L]
';
  return $rules;
}
add_action( 'mod_rewrite_rules', 'arteric_modified_base_htaccess' );

/**
 * JROX - Sept 21, 2016 - maybe_redirect_404() function is causing the bad behavior of not directing 404 pages properly. It’s being called as an add_action, so in order for us to not have the function run we remove the add_action with remove_action.
 */
remove_action( 'template_redirect', 'maybe_redirect_404' );

/**
 * AFO - Sept 28, 2017 - Customizer is getting dangerous, we're removing it as per ASAP-136
 *
 * Code borrowed from https://github.com/parallelus/customizer-remove-all-parts/blob/master/wp-crap.php#L75
 */
function arteric_filter_to_remove_customize_capability( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {
    if ($cap == 'customize') {
      return array('nope'); // thanks @ScreenfeedFr, http://bit.ly/1KbIdPg
    }
    return $caps;
  }
add_filter( 'map_meta_cap', 'arteric_filter_to_remove_customize_capability', 10, 4 );

/**
 * AFO - Sept 25, 2018 - Remove the Yoast JSON LD that it comes with out-of-the-box
 */
add_filter( 'wpseo_json_ld_output', '__return_empty_array' );

/**
 * This fixes an issue with the domain mapping plugin using deprecated functions.
 */
if( defined( 'DOMAIN_MAPPING' ) ) {

  /**
   * Fix an issue where domain mapping forces wp_siteurl instead of respecting wp_home
   * @param  string $setting The setting
   * 
   * @return string     The fixed setting
   */
  function fix_domain_mapping_home( $setting ) {

    if( $setting == '' ) {
      return $setting;
    }

    $home = WP_HOME;
    $siteurl = WP_SITEURL;

    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', $setting, $matches_settings );
    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', WP_HOME, $matches_home );

    if( $matches_settings[1] != $matches_home[1] ) {
      $home = str_replace( $matches_home[1], $matches_settings[1], WP_HOME );
      $siteurl = str_replace( $matches_siteurl[1], $matches_settings[1], WP_SITEURL );
    }
    
    return str_replace( $siteurl, $home, $setting );
  }
  add_filter( 'pre_option_home', 'fix_domain_mapping_home', 11 );

  /**
   * Fix an issue where domain mapping forces wp_home instead of respecting wp_siteurl
   * @param  string $setting The setting
   * 
   * @return string     The fixed setting
   */
  function fix_domain_mapping_siteurl( $setting ) {
    
    if( $setting == '' ) {
      return $setting;
    }

    $siteurl = WP_SITEURL;

    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', $setting, $matches_settings );
    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', WP_SITEURL, $matches_siteurl );

    if( $matches_settings[1] != $matches_siteurl[1] ) {
      $siteurl = str_replace( $matches_siteurl[1], $matches_settings[1], WP_SITEURL );
    }
    
    return $siteurl;
  }
  add_filter( 'pre_option_siteurl', 'fix_domain_mapping_siteurl', 12 );

  /**
   * Fixes an issue where the plugin url uses WP_SITEURL instead of WP_home
   * @param  string $full_url The URL in question
   * @param  string $path     ? See domain_mapping.php
   * @param  string $plugin   ? See domain_mapping.php
   * 
   * @return string           The URL with a fixed home url
   */
  function fix_domain_mapping_plugins_url( $full_url, $path=NULL, $plugin=NULL ) {

    $home = WP_HOME;
    $siteurl = WP_SITEURL;

    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', $full_url, $matches_settings );
    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', WP_HOME, $matches_home );

    if( $matches_settings[1] != $matches_home[1] ) {
      $home = str_replace( $matches_home[1], $matches_settings[1], WP_HOME );
      $siteurl = str_replace( $matches_siteurl[1], $matches_settings[1], WP_SITEURL );
    }

    return str_replace( $siteurl, $home, $full_url );
  }
  add_filter( 'plugins_url', 'fix_domain_mapping_plugins_url', 2 );

  /**
   * Fixes an issue where the theme uri uses WP_SITEURL instead of WP_home
   * @param  string $full_url The URL in question
   * @param  string $path     ? See domain_mapping.php
   * @param  string $plugin   ? See domain_mapping.php
   * 
   * @return string           The URL with a fixed home url
   */
  function fix_domain_mapping_theme_root_uri( $full_url, $path=NULL, $plugin=NULL ) {

    $home = WP_HOME;
    $siteurl = WP_SITEURL;

    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', $full_url, $matches_settings );
    preg_match( '%(\/\/[^/]*?)(\/.*)?$%', WP_HOME, $matches_home );

    if( $matches_settings[1] != $matches_home[1] ) {
      $home = str_replace( $matches_home[1], $matches_settings[1], WP_HOME );
      $siteurl = str_replace( $matches_siteurl[1], $matches_settings[1], WP_SITEURL );
    }

    return str_replace( $siteurl, $home, $full_url );
  }
  add_filter( 'theme_root_uri', 'fix_domain_mapping_theme_root_uri', 2 );
}