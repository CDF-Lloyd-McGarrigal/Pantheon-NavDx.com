<?php

if (! defined('ABSPATH')) {
    exit;
}

class rtrc_json_ld_schema
{

    /**
     * The single instance of rtrc_json_ld_schema.
     *
     * @var    object
     * @access private
     * @since  1.0.0
     */
    private static $_instance = null;

    /**
     * Settings class object
     *
     * @var    object
     * @access public
     * @since  1.0.0
     */
    public $settings = null;

    /**
     * The version number.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $_version;

    /**
     * The token.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $_token;

    /**
     * The main plugin file.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $file;

    /**
     * The main plugin directory.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $dir;

    /**
     * The plugin assets directory.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $assets_dir;

    /**
     * The plugin assets URL.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $assets_url;

    /**
     * Suffix for Javascripts.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $script_suffix;

    /**
     * Constructor function.
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function __construct($file = '', $version = '1.1.0')
    {
        $this->_version = $version;
        $this->_token = 'rtrc_json_ld_schema';

        // Load plugin environment variables
        $this->file = $file;
        $this->dir = dirname($this->file);
        $this->assets_dir = trailingslashit($this->dir) . 'assets';
        $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));

        $this->script_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        register_activation_hook($this->file, array( $this, 'install' ));

        // Load frontend JS & CSS
        // add_action('wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10);
        // add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10);

        // Load admin JS & CSS
        // add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1);
        // add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1);

        // Load API for generic admin functions
        if (is_admin()) {
            $this->admin = new rtrc_json_ld_schema_Admin_API();
        }

        // Handle localisation
        $this->load_plugin_textdomain();
        add_action('init', array( $this, 'load_localisation' ), 0);

        // Determine if the schema should be output in the head or not
        $schemaInHead = (get_option('rtrc_json_ld_schema_in_header') === 'on');
        $registerLocation = $schemaInHead ? 'wp_head' : 'wp_footer';
        $registerPriority = $schemaInHead ? 11 : 0;

        // AFO - Output settings to footer
        add_action($registerLocation, array( $this, 'footer_output_schema' ), $registerPriority);
    }//end __construct()
 // End __construct ()
    /**
     * Wrapper function to register a new post type
     *
     * @param  string $post_type   Post type name
     * @param  string $plural      Post type item plural name
     * @param  string $single      Post type item single name
     * @param  string $description Description of post type
     * @return object              Post type class object
     */
    public function register_post_type($post_type = '', $plural = '', $single = '', $description = '', $options = array())
    {

        if (! $post_type || ! $plural || ! $single) {
            return;
        }

        $post_type = new rtrc_json_ld_schema_Post_Type($post_type, $plural, $single, $description, $options);

        return $post_type;
    }//end register_post_type()


    /**
     * Wrapper function to register a new taxonomy
     *
     * @param  string $taxonomy   Taxonomy name
     * @param  string $plural     Taxonomy single name
     * @param  string $single     Taxonomy plural name
     * @param  array  $post_types Post types to which this taxonomy applies
     * @return object             Taxonomy class object
     */
    public function register_taxonomy($taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array())
    {

        if (! $taxonomy || ! $plural || ! $single) {
            return;
        }

        $taxonomy = new rtrc_json_ld_schema_Taxonomy($taxonomy, $plural, $single, $post_types, $taxonomy_args);

        return $taxonomy;
    }//end register_taxonomy()

    /**
     * Load frontend CSS.
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function enqueue_styles()
    {
        wp_register_style($this->_token . '-frontend', esc_url($this->assets_url) . 'css/frontend.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-frontend');
    }//end enqueue_styles()
 // End enqueue_styles ()
    /**
     * Load frontend Javascript.
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version);
        wp_enqueue_script($this->_token . '-frontend');
    }//end enqueue_scripts()
 // End enqueue_scripts ()
    /**
     * Load admin CSS.
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function admin_enqueue_styles($hook = '')
    {
        wp_register_style($this->_token . '-admin', esc_url($this->assets_url) . 'css/admin.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-admin');
    }//end admin_enqueue_styles()
 // End admin_enqueue_styles ()
    /**
     * Load admin Javascript.
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function admin_enqueue_scripts($hook = '')
    {
        wp_register_script($this->_token . '-admin', esc_url($this->assets_url) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version);
        wp_enqueue_script($this->_token . '-admin');
    }//end admin_enqueue_scripts()
 // End admin_enqueue_scripts ()
    /**
     * Load plugin localisation
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function load_localisation()
    {
        load_plugin_textdomain('rtrc-json-ld-schema', false, dirname(plugin_basename($this->file)) . '/lang/');
    }//end load_localisation()
 // End load_localisation ()
    /**
     * Load plugin textdomain
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function load_plugin_textdomain()
    {
        $domain = 'rtrc-json-ld-schema';

        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, dirname(plugin_basename($this->file)) . '/lang/');
    }//end load_plugin_textdomain()
 // End load_plugin_textdomain ()
    /**
     * Main rtrc_json_ld_schema Instance
     *
     * Ensures only one instance of rtrc_json_ld_schema is loaded or can be loaded.
     *
     * @since  1.0.0
     * @static
     * @see    rtrc_json_ld_schema()
     * @return Main rtrc_json_ld_schema instance
     */
    public static function instance($file = '', $version = '1.0.0')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }//end instance()
 // End instance ()
    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }//end __clone()
 // End __clone ()
    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }//end __wakeup()
 // End __wakeup ()
    /**
     * Installation. Runs on activation.
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function install()
    {
        $this->_log_version_number();
    }//end install()
 // End install ()
    /**
     * Log the plugin version number.
     *
     * @access private
     * @since  1.0.0
     * @return void
     */
    private function _log_version_number()
    {
        update_option($this->_token . '_version', $this->_version);
    }//end _log_version_number()
 // End _log_version_number ()
    /**
     * Output schema content to the footer
     *
     * @access public
     * @since  1.0.0
     * @return void
     * @author Anthony Outeiral <anthony@arteric.com>
     */
    public function footer_output_schema()
    {
        // get the site and page-level JSON-LD content
        $siteJSON = get_option('rtrc_json_ld_schema_contents');
        $pageJSON = get_post_meta( get_the_ID(), 'rtrc_json_ld_page_content', true );
        // If we have any global content, output it
        if( $siteJSON ) {
            // Output the opening tag
            echo '<script type="application/ld+json">';
            // Output the page JSON-LD content
            echo $siteJSON;
            // Output the ending script tag
            echo '</script>';
        }
        // If we have any page-level content, ouput it
        if( $pageJSON ) {
            // Output the opening tag
            echo '<script type="application/ld+json">';
            // Output the page JSON-LD content
            echo $pageJSON;
            // Output the ending script tag
            echo '</script>';
        }
    }//end footer_output_schema()
}//end class
