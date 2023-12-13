<?php

namespace ArtericComponentBuilder;

class ComponentRegister {

	// The ID of the component builder metabox
	private $_metaboxID = 'arteric_components';

	// Some variables used throughout
	private $componentSelectorList = array();
	private $componentMetaboxList = array();
	private $templateList = array();
	private $fieldsList = array();
	private $noWrapperList = array();
	private $templateDirectory = '/';
	private $allowedPostTypes = [ 'page' ];
	private $disallowedTemplates = [];
	private $forceWrapperOff = false;

	/**
	 * Construct the class
	 */
	function __construct( $pluginPath = '', $pluginUrl = '' ){

		$this->_pluginPath = $pluginPath;
		$this->_pluginUrl = $pluginUrl;

		// Check for metabox plugins and set up some stuff
		add_action( 'init', function(){
			$this->confirmMetabox();
			
		}, 99);

		add_action( 'after_setup_theme', function(){
			// Run setup
			$this->setup();	
		});
		

		// Hook in to create the component builder metabox
		add_filter('rwmb_meta_boxes', array( $this, 'generateComponentBuilderMetabox' ) );

		// Hook in for custom styles and JS for admin
		add_action( 'admin_enqueue_scripts', array( $this, 'queueUpAdminScripts' ) );
	}

	public function queueUpAdminScripts(){
		if( $this->_pluginUrl != '' ){

			// Queue up scripts to handle component display
			wp_enqueue_script( $this->_metaboxID . '_admin_scripts', $this->_pluginUrl . 'js/admin.js', array( 'jquery' ), false);
			wp_enqueue_style( $this->_metaboxID . '_admin_styles', $this->_pluginUrl . 'css/admin.css');
		}
	}

	/**
	 * Confirm if metabox and extensions are installed
	 * @return boolean	True if they all are, false and an admin notice otherwise
	 */
	private function confirmMetabox(){

		// Check if core Metabox is installed
		if(!class_exists( '\RWMB_Loader' )){
			add_action( 'admin_notices', function(){
				echo '<div class="notice notice-error"><p>The Arteric Component Builder relies on MetaBox.io. Please install MetaBox.io</p></div>';
			});

			return false;
		}

		// Check if metabox groups are installed
		if(!class_exists( '\RWMB_Group' )){
			add_action( 'admin_notices', function(){
				echo '<div class="notice notice-error"><p>The Arteric Component Builder relies on MetaBox.io\'s "Group" extension. Please install it.</p></div>';
			});

			return false;
		}

		// Check if metabox conditional logic is installed
		if(!class_exists( '\MB_Conditional_Logic' )){
			add_action( 'admin_notices', function(){
				echo '<div class="notice notice-error"><p>The Arteric Component Builder relies on MetaBox.io\'s "Conditional Logic" extension. Please install it.</p></div>';
			});

			return false;
		}

		// Check if include/exclude is installed
		if(!class_exists( '\MB_Include_Exclude' )){
			add_action( 'admin_notices', function(){
				echo '<div class="notice notice-error"><p>The Arteric Component Builder relies on MetaBox.io\'s "Include Exclude" extension. Please install it.</p></div>';
			});

			return false;
		}

		return true;
	}

	/**
	 * Setup some class variables
	 * 
	 * @return void
	 */
	private function setup(){

		// Make any changes to the template directory if we're filtering it
		$this->templateDirectory = apply_filters( 'rtrc_cb_template_directory', $this->templateDirectory );

		// Make any changes to the allowed post types if we're filtering it
		$this->allowedPostTypes = apply_filters( 'rtrc_cb_allowed_posttypes', $this->allowedPostTypes );

		// Setup which templates aren't allowed
		$this->disallowedTemplates = apply_filters( 'rtrc_cb_disallowed_templates', $this->disallowedTemplates );
	}

	/**
	 * Create the component builder metabox
	 * @param  array $meta_boxes Metaboxes already registered
	 * @return array             Metaboxes
	 */
	public function generateComponentBuilderMetabox( $meta_boxes ){

		// Standard Options
		$addedBox = array(
			'id'            => 'arteric_component_builder',
			'title'         => __('Components', 'ArtericComponentBuilder'),
			'context'       => 'advanced',
			'priority'      => 'high',
		);

		// Get registered post types
		$addedBox['post_types'] = $this->allowedPostTypes;

		$headingField = array(
			'type'	=> 'heading',
			'name'	=> 'Component'
		);

		// Generate the component selector
		$componentSelector = array(
            'name'          => '',
            'id'            => 'selected_component',
            'type'          => 'select',
            'class'			=> 'js-componentSelector',
            'placeholder'   => __( 'Choose a component', 'ArtericComponentBuilder' ),
            'options' 		=> $this->componentSelectorList
        );

		// Add support for Custom classes
        $customClasses = array(
            'name'          => __('Custom Classes', 'ArtericComponentBuilder'),
            'id'            => 'custom_classes',
            'type'          => 'text',
            'size'          => 60,
            'desc'          => __( 'If set, custom classes here will be applied to the component', 'ArtericComponentBuilder' )
        );

		// Set up the metaboxes that are going to contain all of the compnent's metaboxes
		// Starting with the component selector and the custom Classes box!
        $componentMetaboxes = [ $headingField, $componentSelector, $customClasses ];

        // Now add each of our registered component's metaboxes to the builder
        foreach( $this->componentMetaboxList as $theMetabox ){
        	$componentMetaboxes[] = $theMetabox;
        }

        // And now create the field group that will drive all of this
		$addedBox['fields'] = array(

			// GROUP
			array(
			    'name'          => '',
			    'id'            => $this->_metaboxID,
			    'type'          => 'group',
			    'clone'         => true,
			    'sort_clone'    => true,
			    // 'collapsible'	=> true, // @TODO: figure out why metabox hates this/us :(
			    'group_title'	=> 'Component',
			    'add_button'	=> __( '+ Add Component', 'ArtericComponentBuilder' ),
			    'fields'        => $componentMetaboxes
			)
		);

		// Conditional Logic for page templates
		// Hide the component builder when we're not on these templates
		// @TODO: add support for excluding page templates
		$addedBox['exclude'] = array(
		    'template' => $this->disallowedTemplates,
		);

		// Enable revision support
		$addedBox[ 'revision' ] = true;
		 
		$addedBox = apply_filters( 'rtrc_cb_metabox_filter', $addedBox );

		// Add this huge box to the rest of the registered metaboxes
		$meta_boxes[] = $addedBox;

		return $meta_boxes;
	}

	/**
	 * Register a new component
	 * @param  string $name     The name of the component
	 * @param  array  $fields   The fields as per metabox.io standard
	 * @param  string $template The template file to load
	 * @param  boolean $noWrapper Set true to not wrap the field
	 * 
	 * @return void           
	 */
	public function registerComponent( $name, array $fields = [], $template = '', $noWrapper = null ){

		// If we weren't told to force or not force a wrapper, determine what the global default is
		if( is_null( $noWrapper ) ) {
			$noWrapper = apply_filters( 'rtrc_cb_wrapper_div_off', $this->forceWrapperOff );
		}

		// Make a slug from the name
		$slug = sanitize_title_with_dashes( $name );

		// Add that to our component selector list if it wasn't already
		if( !isset( $this->componentSelectorList[ $slug ] ) ){

			// Add the component to the selector list
			$this->componentSelectorList[ $slug ] = $name;
			
			// Set up the field names array for later
			$fieldNames = [];

			foreach( $fields as $theField ){

				if( isset( $theField['id'] ) ){
					// Set up field names and defaults
					$fieldNames[ $theField['id' ] ] = '';

					// Namespace the field to its component
					$theField[ 'id' ] = $slug . '__' . $theField[ 'id' ];
				}
					

				// Set up field visibility
				$theField[ 'class' ] = "rtrcComponent rtrcComponent-{$slug}";
/*
				if( !isset($theField[ 'visible' ]) ){
					$theField[ 'visible' ] = [ 'selected_component', '=', $slug ];
				} 
				else {
					$theField[ 'visible' ] = [
						$theField[ 'visible' ],
						[ 'selected_component', '=', $slug ]
					];
				}
*/

				// Add the field to the component list
				$this->componentMetaboxList[] = $theField;
			}
			
			// Also put our field names into the master list
			$this->fieldsList[ $slug ] = $fieldNames;

			// And register our template
			$this->templateList[ $slug ] = $template;

			// And register if we want to wrap it or not
			$this->noWrapperList[ $slug ] = $noWrapper;
		}

	}

	/**
	 * Hook to set up the component building
	 * @param  integer $id The ID of the page
	 *
	 * @return void      
	 */
	public function buildComponents( $id = 0 ){

		// If we weren't given an ID, build this page's components
		if( $id == 0 ){
			$id = get_the_id();
		}

		// Get the components for that page ID
		$components = get_post_meta( $id, $this->_metaboxID, true );

		// And get to work
		if( is_array( $components ) ){
			$this->doBuild( $components );
		}
		
	}

	/**
	 * Builds the component, using the components as defined in component_builder.php
	 * Uses the component templates found in builders/components/
	 *
	 * @param array $components The components
	 * 
	 * @return void
	 */
	private function doBuild( array $components = [] )
	{
	    foreach ($components as $the_component) {
	   		
	   		// We're expecting a specific layout for the fields.
	        // Check if "selected component" is set, and if not ignore this.
	        if (!isset($the_component[ 'selected_component' ])) {
	            continue;
	        }

	        // Now that we know we have a selected component, grab it.
	        $componentSlug = $the_component[ 'selected_component' ];

	        // If we have no field list, say because the field was deleted, ignore it.
	        if( !isset($this->fieldsList[$componentSlug] ) ){
	        	continue;
	        }

	        // Get the names of every field in the component
	        $fieldNames = array_keys( $this->fieldsList[ $componentSlug ] );

	        // Assemble our component based on what fields we know it has
	        $component = [];
	        foreach( $fieldNames as $theName ){
	        	if( isset( $the_component[ $componentSlug . '__' . $theName ] ) ){
	        		$component[ $theName ] = $the_component[ $componentSlug . '__' . $theName ];	
	        	}
	        }

	        // also set up this default
	        $component[ 'custom_classes' ] = '';

	        // Set up custom classes, if available
	        $output_classes = 'component component-' . $componentSlug;
	        if (isset($the_component['custom_classes'])) {
	            $output_classes .= ' ' . $the_component['custom_classes'];
	            $component[ 'custom_classes' ] = $the_component['custom_classes'];
	        }

	        // At this point we have a selected component, and some data stored for it, so lets build.
	        // Check if the template for it exists.
	        if (locate_template( $this->templateDirectory . $this->templateList[ $componentSlug ] ) ) {

	            // For safety, set defaults
	            $component = array_merge($this->fieldsList[ $componentSlug ], $component);

	            if( $this->noWrapperList[ $componentSlug ] !== true ){
	            	// A Wrapper div, for custom classes
	            	echo apply_filters('rtrc_cb_wrapper_div', '<div class="' . $output_classes . '">', $the_component);
	            }
	            

	            // Include the template that we're building.
	            include( locate_template( $this->templateDirectory . $this->templateList[ $componentSlug ] ) );

	            if( $this->noWrapperList[ $componentSlug ] !== true ){
	            	// End wrapper div
	            	echo '</div><!-- .end component -->';
	            }
	        }
	    }//end foreach
	}//end build()
}