<?php
// @codingStandardsIgnoreFile
// @todo: JRox needs to ensure that this plugin passes coding standards.
// Create the setting page where you can enter links to exclude.
// JROX October 22, 2016
if (!defined('ABSPATH')) {
    die;
};

class elcPageOptions
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private static $_instance;
    static $PLUGIN_DIR;
    private $createdMetaboxes = false;
    private $option_name = 'elc_page_settings_admin';
    private $settings_page = 'elc-page-settings-admin';

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }//end getInstance()


    /**
     * Start up
     */
    public function __construct()
    {
        self::$PLUGIN_DIR = plugin_dir_path(__FILE__);

        add_action( 'plugins_loaded', array($this, 'init') );

        add_filter( 'mb_settings_pages', array( $this, 'add_settings_page' ) );
        add_filter('rwmb_meta_boxes', array( $this, 'add_repeatable_metabox' ) );
    }//end __construct()

    public function init(){

        $this->localize_option_name();

        // Set class property
        $this->options = get_option( $this->option_name );
    }

    public function localize_option_name(){

        // Check if the WPML language code is set up and not english.
        if( defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE != 'en' ){

            // If it is, different options for the language!
            $this->option_name .= '_' . ICL_LANGUAGE_CODE;
        }
    }//end localize_option_name()

    /**
     * Create the additional settings page for metabox
     * @param array $settings_pages The settings pages
     *
     * @return array The settings pages
     */     
    public function add_settings_page( $settings_pages ){

        $settings_pages[] = array(
            'id'            => $this->settings_page,
            'option_name'   => $this->option_name,
            'menu_title'    => 'External Link Checker Settings',
            'style'         => 'no-boxes',
            'columns'       => 1,
            'parent'        => 'options-general.php'
        );

        return $settings_pages;

    }//end add_settings_page()

    /**
     * Create the repeatable field to do the "link specific" trigger
     * @param array $meta_boxes the metaboxes
     *
     * @return array The metaboxes
     */     
    public function add_repeatable_metabox( $meta_boxes ) {

        $meta_boxes[] = array(
            'id'    => 'external_link_checker_metabox',
            'title' => 'External Link Checker Seetings',
            'settings_pages' => $this->settings_page,
            'fields'    => array(

                [
                    'type'  => 'custom_html',
                    'std'   => '<a href="#howToImplement">Jump to usage notes</a>'
                ],

                [
                    'type'  => 'heading',
                    'name'  => 'Default Modal Settings'
                ],

                [
                    'id'    => 'default_modal_id',
                    'name'  => 'Default Modal ID',
                    'type'  => 'text',
                    'desc'  => '<strong>Required.</strong> The ID of the modal, for javascript
<br>If somehow unset, defaults to "exitmodal"',
                    'std'   => 'exitmodal',
                    'attributes'    => [
                        'required'  => true
                    ]
                ],

                [
                    'id'    => 'default_content',
                    'name'  => 'Content',
                    'type'  => 'wysiwyg',
                    'desc'  => 'The content of the modal',
                    'options'   => [
                        'textarea_rows' => 6
                    ]
                ],

                [
                    'id'    => 'excluded_domains',
                    'name'  => 'Excluded Domains',
                    'type'  => 'textarea',
                    'rows'  => 3,
                    'desc'  => '<strong>Comma separated domains to exclude.</strong>
<br>Enter without www. prefix.
<br>Example: test.com,test.co.uk
<br>Enter * to ignore all domains'
                ],

                [
                    'id'    => 'excluded_urls',
                    'name'  => 'Excluded URLs',
                    'type'  => 'textarea',
                    'rows'  => 3,
                    'desc'  => '<strong>Comma separated URLs to exclude. This should match the HREF</strong>'
                ],

                [
                    'type'  => 'heading',
                    'name'  => 'Specific Modal Triggers'
                ],

                [
                    'id'    => 'modal_triggers',
                    'name'  => '',
                    'type'  => 'group',
                    'clone' => true,
                    'sort_clone' => true,
                    'add_button' => '+ Add Trigger',
                    'fields'    => array(
                        [
                            'id'    => 'modal',
                            'name'  => 'Modal ID',
                            'type'  => 'text',
                            'desc'  => 'The ID of the modal, for javascript.
<br><strong>Leave blank to use the same modal ID as the default.</strong>'
                        ],

                        [
                            'id'    => 'domains',
                            'name'  => 'Domains',
                            'type'  => 'textarea',
                            'rows'  => 3,
                            'desc'  => '<strong>Comma separated links to include.</strong>
<br>Enter without www. prefix.
<br>Example: test.com,test.co.uk'
                        ],

                        [
                            'id'    => 'urls',
                            'name'  => 'URLs',
                            'type'  => 'textarea',
                            'rows'  => 3,
                            'desc'  => '<strong>Comma separated URLs to include. This should match the HREF</strong>'
                        ],

                        [
                            'id'    => 'content',
                            'name'  => 'Content',
                            'type'  => 'wysiwyg',
                            'desc'  => 'The content of the modal
<br><strong>Leave blank to use the same modal content as the default.</strong>',
                            'options'   => [
                                'textarea_rows' => 6
                            ]
                        ]
                    )
                ],

                [
                    'type'  => 'heading',
                    'name'  => 'Usage Notes'
                ],

                [
                    'type'  => 'custom_html',
                    'std'   => $this->howToImplement()
                ]
            )
        );

        // Flag that we've created the metaboxes
        $this->createdMetaboxes = true;

        // Return them
        return $meta_boxes;
    }//end add_repeatable_metabox()

    /**
     * Sitewide usable function to simplify grabbing a site option
     *
     * @param string $the_option The option name to grab.
     *
     * @return mixed              The option value or false if not found.
     */
    public function get_option($the_option)
    {
        // Grab the settings field
        $settings = get_option( $this->option_name );

        // Check if the option we're requesting exists
        if (isset($settings[ $the_option ])) {
            // If it does, send it back
            return $settings[ $the_option ];
        } else {
            // If it doesn't, send false
            return false;
        }

        return false; // Fail-safe
    }//end get_advanced_option()

    public function howToImplement(){
        ob_start();
        ?><div id="howToImplement"></div>
<h4>Javascript</h4>
<p>This plugin only attaches javascript click events to the appropriate links. It does not handle the actual dispaly of the modals, that is left to the theme. To implement, create a listener on the DOM as so:</p>
<pre>
    $( document ).on( 'externalLink', function( event, theExternalLink, modalID, modalContent ){
        console.log( theExternalLink );
        console.log( modalID );
        console.log( modalContent );
    });
</pre>
<p>The variables are as follows:</p>
<p>
    <strong>theExternalLink:</strong>   <span>The link that was clicked.</span></br>
    <strong>modalID:</strong>   <span>The ID of the modal. This could be an element ID, class, or some other unique identifier.</span></br>
    <strong>modalContent:</strong>   <span>The content of the modal. Can be used to swap out the content of the modal rather than generating a new modal for each link.</span></br>
</p>

<h4>Triggering a modal on individual links</h4>
<p>To create an anchor tag that triggers its own modal, separate of the configuration of these modals, create an anchor tag as follows:</p>
<pre>
    &lt;a class="js-forceModal" data-modal-id="[ID]" data-modal-content="[CONTENT]" href="[URL]"&gt;Link&lt;/a&gt;
</pre>

<p>The attributes are as follows:</p>
<p>
    <strong>js-forceModal:</strong> <span>This is the class the javscript looks for to set up the modals.</span></br>
    <strong>data-modal-id:</strong> <span>The modal ID. Defaults to the same ID as the default modal</span><br>
    <strong>data-modal-content:</strong>    <span>The modal content. Defaults to the same content as the default modal</span>
</p>

<h4>Excluding links from Triggering the Modal</h4>
<p>To prevent an anchor tag from triggering the exit modal, simply add the following class <strong>to the anchor tag</strong> or <strong>to the element directly wrapping the anchor tag</strong></p>
<pre>
    js-noTriggerExitModal
</pre>
        <?php
        return ob_get_clean();
    }

}//end class

global $ac_elc;
$ac_elc = elcPageOptions::getInstance();
