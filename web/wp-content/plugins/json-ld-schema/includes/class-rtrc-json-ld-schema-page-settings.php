<?php

if (! defined('ABSPATH')) {
    exit;
}

class rtrc_json_ld_schema_Page_Settings
{

    /**
     * The single instance of rtrc_json_ld_schema_Page_Settings.
     *
     * @var    object
     * @access private
     * @since  1.0.0
     */
    private static $_instance = null;

    /**
     * The main plugin object.
     *
     * @var    object
     * @access public
     * @since  1.0.0
     */
    public $parent = null;

    /**
     * Prefix for plugin settings.
     *
     * @var    string
     * @access public
     * @since  1.0.0
     */
    public $base = '';

    /**
     * Available settings for plugin.
     *
     * @var    array
     * @access public
     * @since  1.0.0
     */
    public $settings = array();

    public function __construct($parent)
    {
        $this->parent = $parent;

        $this->base = 'rtrc_json_ld_';

        // Load Post fields
        add_action('add_meta_boxes', array( $this, 'register_page_fields') );
        add_action('save_post', array( $this, 'save_post_meta_boxes' ), 10, 1);

    }//end __construct()


    /**
     * Register a JSON-LD field for pages
     * @author  Rob Szpila <robert@arteric.com>
     */
    public function register_page_fields( $post_type ) {
        $allowed_post_types = apply_filters('rtrc_json_ld_schema_post_types', array( 'page', 'post' ));

        if ( in_array( $post_type, $allowed_post_types ) ) {
            add_meta_box(
                $this->base . 'page_content',
                __( 'JSON LD', $this->base),
                array( $this, 'render_json_meta_box' ),
                $post_type,
                'advanced',
                'low'
            );
        }
    }// end register_page_fields()

    /**
     * Save the meta when the post is saved.
     * @author  Rob Szpila <robert@arteric.com>
     */
    public function save_post_meta_boxes( $post_id ) {
        /*
        * We need to verify this came from the our screen and with proper authorization,
        * because save_post can be triggered at other times.
        */

        // Check if our nonce is set.
        if ( ! isset( $_POST[$this->base . 'page_content_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST[$this->base . 'page_content_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, $this->base . 'page_content' ) ) {
            return $post_id;
        }

        /*
        * If this is an autosave, our form has not been submitted,
        * so we don't want to do anything.
        */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $mydata = sanitize_text_field( $_POST[$this->base . "page_content"] );

        // Update the meta field.
        update_post_meta( $post_id, $this->base . "page_content", $mydata );
    }// end save_post_meta_boxes()

    /**
     * Output the HTML for the JSON-LD page field
     * @author  Rob Szpila <robert@arteric.com>
     */
    public function render_json_meta_box( $post ) {
        // Add an nonce field so we can check for it later.
        wp_nonce_field( $this->base . 'page_content', $this->base . 'page_content_nonce' );
        // Fetch any existing values and setup and store the field ID
        $value = get_post_meta( $post->ID, $this->base . 'page_content', true );
        $field_id = $this->base . "page_content"
        ?>
         <label for="<?php echo $field_id; ?>">
            <?php _e( 'This JSON-LD data is <strong>optional</strong> and will be output in addition to any global JSON-LD data', $this->base ); ?>
        </label>
        <textarea class="widefat" id="<?php echo $field_id; ?>" name="<?php echo $field_id; ?>" cols="50"><?php echo esc_attr( $value ); ?></textarea>
        <?php
    }//end render_json_meta_box()

    /**
     * Main rtrc_json_ld_schema_Page_Settings Instance
     *
     * Ensures only one instance of rtrc_json_ld_schema_Settings is loaded or can be loaded.
     *
     * @since  1.0.0
     * @static
     * @see    rtrc_json_ld_schema()
     * @return Main rtrc_json_ld_schema_Settings instance
     */
    public static function instance($parent)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
        }
        return self::$_instance;
    }//end instance()
 // End instance()
    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->parent->_version);
    }//end __clone()
 // End __clone()
    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->parent->_version);
    }//end __wakeup()
 // End __wakeup()
}//end class
