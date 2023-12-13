<?php
/*
 Plugin Name: Arteric Head Code Plugin
 Plugin URI: http://arteric.com
 Description: Provides an area in the CMS for adding code into the head tag.
 Network: false
 Author: Anthony Outeiral
 Version: 1.0
 Author URI: http://arteric.com
 Text Domain: ac-head-code
 */

 class AC_Head_Code
 {
 	private static $SETTINGS_ADMIN = 'ac-head-code';
 	private static $OPTION_GROUP= 'ac-head-code-group';
 	private static $OPTION_NAME= 'ac-head-code';
 	private static $PAGE_TITLE = 'Head Code Editor';
 	private static $PAGE_SUBTITLE = 'Head Code';
 	public static $HEAD_CODE = 'head-code';

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	private static $_instance;

	public static function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Start up
	 */
	private function __construct()
	{
		add_action('init',  array($this, 'init'));
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );

	}

	public function init() {
		add_action('wp_head',array($this, 'add_head_code'), 90);
	}

	function add_head_code() {
		$head_code = get_option(self::$OPTION_NAME);
		if (false !== $head_code && isset($head_code[self::$HEAD_CODE])) {
			echo $head_code[self::$HEAD_CODE];
		}
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {

		// This page will be under "Settings"
		add_options_page(
			__(self::$PAGE_TITLE, 'ac-head-code'),
			__(self::$PAGE_TITLE, 'ac-head-code'),
			'manage_options',
			self::$SETTINGS_ADMIN,
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( self::$OPTION_NAME );
		?>
		<div class="wrap">
			<h2><?php _e(self::$PAGE_TITLE, 'ac-head-code'); ?></h2>           
			<form method="post" action="options.php">
				<?php
	            
	            // This prints out all hidden setting fields
				settings_fields( self::$OPTION_GROUP );   
	            
	            // Print the head code field 
				$this->head_code_callback();

				// Print the submit button
				submit_button(); 
				?>
			</form>
		</div>
		<?php
	}

    /**
     * Register and add settings
     */
    public function page_init()
    {        
    	register_setting(
            self::$OPTION_GROUP, // Option group
            self::$OPTION_NAME, // Option name
            array( $this, 'sanitize' ), // Sanitize
            self::$SETTINGS_ADMIN
        );

    	add_settings_section(
            'head-code-section', // ID
            '', //self::$PAGE_SUBTITLE, // Title
            array( $this, 'print_section_info' ), // Callback
            self::$SETTINGS_ADMIN // Page
        );  

    	add_settings_field(
            self::$HEAD_CODE, // ID
            '', // Title 
            array( $this, 'head_code_callback' ), // Callback
            self::$SETTINGS_ADMIN, // Page
            'head-code-section' // Section           
        );            
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
    	$new_input = array();

    	$new_input[ self::$HEAD_CODE ] = $input[ self::$HEAD_CODE ];

		return $new_input;
	}

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
    	_e('Enter your head code below. This will be printed in the head tags on the front-end pages:', 'ac-head-code');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function head_code_callback()
    {
    	printf(
    		'<textarea id="'.self::$HEAD_CODE.'" name="'.self::$OPTION_NAME.'['.self::$HEAD_CODE.']" class="large-text code" rows="20">%s</textarea>',
    		isset( $this->options[self::$HEAD_CODE] ) ? esc_html( $this->options[self::$HEAD_CODE]) : ''
    		);
    }
}

$ac_head_code_settings_page = AC_Head_Code::getInstance();
