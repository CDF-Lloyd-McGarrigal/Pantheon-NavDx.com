<?php

namespace ArtericSectionBuilder;

class SectionBuilder {

	// The ID of the section builder metabox
	private $_metaboxID = 'arteric_sections';
	private $_filterNamespace = 'rtrc_sections_';
	private $_postType	= 'sb_section';
	private $_pluginPath = '';
	private $_pluginUrl = '';

	// Some variables used throughout
	private $sectionSelectorList = array();
	private $sectionMetaboxList = array();
	private $fieldsList = array();
	private $templateList = array();
	private $templateDirectory = '/';
	private $allowedPostTypes = [ 'page' ];
	private $disallowedTemplates = [];
	private $defaultSupports = [ 'title', 'editor', 'revisions' ];

	/**
	 * Construct the class
	 */
	function __construct( $pluginPath = '', $pluginUrl = '' ){

		$this->_pluginPath = $pluginPath;
		$this->_pluginUrl = $pluginUrl;

		// Check for metabox plugins and set up some stuff
		add_action( 'init', function(){

			// Confirm metabox
			$this->confirmMetabox();
			
			// Load the post type
			$this->createPostType();
		}, 99);

		add_action( 'after_setup_theme', function(){
			// Run setup
			$this->setup();	
		});

		// Add a filter to the search
		add_filter( 'posts_clauses', array( $this, 'addSectionsToSearch' ) );

		add_action('admin_enqueue_scripts', array( $this, 'queueUpAdminScripts' ) );
		

		// Hook in to create the component builder metabox
		add_filter('rwmb_meta_boxes', array( $this, 'generateSectionSelectorMetabox' ) );
		add_filter('rwmb_meta_boxes', array( $this, 'generateSectionTemplateMetabox' ) );
		add_filter('rwmb_meta_boxes', array( $this, 'generateSectionOnMetabox' ) );
		add_filter('rwmb_meta_boxes', array( $this, 'generateSectionReminderMetabox' ) );
		add_filter('rwmb_meta_boxes', array( $this, 'generateRegisteredMetaboxes' ) );
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
	 * Create the post types used within this plugin
	 * @return void
	 */
	function createPostType()
	{

	    register_post_type( $this->_postType, array(
	        'labels' => array (
	            'name' => __('Sections', 'ArtericSectionBuilder'),
	            'singular_name' => __('Section', 'ArtericSectionBuilder')
	        ),
	        'has_archive'   =>  false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-media-text',
            'delete_with_user' => false,
	        'supports' => $this->defaultSupports
	    ));
	}//end createPostType()

	/**
	 * Queue up the admin javascript
	 * @return void
	 */
	function queueUpAdminScripts() {

		if( $this->_pluginUrl != '' ){
			// Queue up scripts to handle panel "edit panel" link
			wp_enqueue_script( $this->_filterNamespace . 'admin_scripts', $this->_pluginUrl . 'js/admin.js', array( 'jquery' ), false);
			
			// Localize the admin url so that it can be used in the js
			$admin_url = [
				'url' => admin_url()
			];
			wp_localize_script($this->_filterNamespace . 'admin_scripts', $this->_filterNamespace . 'admin_url', $admin_url  );
		}
	}

	/**
	 * Setup some class variables
	 * 
	 * @return void
	 */
	private function setup(){

		// Make any changes to the template directory if we're filtering it
		$this->templateDirectory = apply_filters( $this->_filterNamespace . 'template_directory', $this->templateDirectory );

		// Make any changes to the allowed post types if we're filtering it
		$this->allowedPostTypes = apply_filters( $this->_filterNamespace . 'allowed_posttypes', $this->allowedPostTypes );

		// Setup which templates aren't allowed
		$this->disallowedTemplates = apply_filters( $this->_filterNamespace . 'disallowed_templates', $this->disallowedTemplates );

		$this->defaultSupports = apply_filters( $this->_filterNamespace . 'section_supports', $this->defaultSupports );
	}

	/**
	 * Create the component builder metabox
	 * @param  array $meta_boxes Metaboxes already registered
	 * @return array             Metaboxes
	 */
	public function generateSectionSelectorMetabox( $meta_boxes ){

		// Standard Options
		$addedBox = array(
			'id'            => 'arteric_section_selector',
			'title'         => __('Sections', 'ArtericSectionBuilder'),
			'context'       => 'advanced',
			'priority'      => 'high',
		);

		// Get registered post types
		$addedBox['post_types'] = $this->allowedPostTypes;

		// Set the new-post path relative to the admin url
		$adminUrl = admin_url('/post-new.php?post_type=sb_section');
		

		$addNew = array(
			'type'	=> 'custom_html',
			'std'	=> "<a target='_blank' href='$adminUrl'>Create Section</a>",
			'desc'	=> 'Remember to refresh this page after creating any new sections to select those sections.'
		);

		$sectionFields = array(

            // Selector for section
            array(
                'name'          => __('Section Selector', 'ArtericSectionBuilder'),
                'id'            => 'section_selector',
                'type'          => 'post',
                'field_type'    => 'select_advanced',
                'post_type'     => $this->_postType,
            ),

            // Helper field for 'jump to section' link
            array(
                'name'          => '&nbsp;',
                'id'            => 'section_jump_to',
                'type'          => 'custom_html',
                'std'           => '<span class="jumpToLink">Edit section</span>'
            ),
		);

		$sectionFields = apply_filters( $this->_filterNamespace . 'section_fields', $sectionFields );

		// Section repeater
		$sectionGroup = array(
            'name' => 	__('Section', 'ArtericSectionBuilder'),
            'id'        => $this->_metaboxID,
            'type'      => 'group',
            'clone'     => true,
            'sort_clone'=> true,
            'add_button'=> '+ Add Section',
            'fields'    => $sectionFields
        );

        // And now create the field group that will drive all of this
		$addedBox['fields'] = array(
			$addNew,
			$sectionGroup
		);

		// Conditional Logic for page templates
		// Hide the component builder when we're not on these templates
		// @TODO: add support for excluding page templates
		$addedBox['exclude'] = array(
		    'template' => $this->disallowedTemplates,
		);

		// Add revision support
		$addedBox[ 'revision' ] = true;
		 
		$addedBox = apply_filters( $this->_filterNamespace . 'metabox_filter', $addedBox );

		// Add this huge box to the rest of the registered metaboxes
		$meta_boxes[] = $addedBox;

		return $meta_boxes;
	}

	/**
	 * Generate the metabox for the section template selector
	 * @param  array $meta_boxes existing metaboxes
	 * 
	 * @return array             metaboxes
	 */
	public function generateSectionTemplateMetabox( $meta_boxes ){

		$addedBox = array(
			'id'            => 'arteric_section_template_selector',
			'title'         => __('Template', 'ArtericSectionBuilder'),
			'context'       => 'side',
			'priority'      => 'high',
			'post_types'	=> $this->_postType,
			'fields'		=> array(

				array(
					'id'		=> 'selected_template',
					'name'		=> '',
					'type'		=> 'select',
				'placeholder'	=> __( 'Select a Template', 'ArtericSectionBuilder' ),
					'options'	=> $this->sectionSelectorList
				)
			)
		);

		$meta_boxes[] = $addedBox;

		return $meta_boxes;
	}

	/**
	 * Generate a metabox that reminds the user to pick a section template
	 * @param  array $meta_boxes existing metaboxes
	 * 
	 * @return array             metaboxes
	 */
	public function generateSectionReminderMetabox( $meta_boxes ){

		$addedBox = array(
			'id'            => 'arteric_section_template_reminder',
			'title'         => __('Attention!', 'ArtericSectionBuilder'),
			'priority'      => 'high',
			'post_types'	=> $this->_postType,
			'fields'		=> array(

				array(
					'type'	=> 'custom_html',
					'std'	=> '<h3>Please select a section template.</h3><p>If a template is not selected, this section will not render on page.</p>'
				)
			),
			'visible'	=> [ 'selected_template', '' ]
		);

		$meta_boxes[] = $addedBox;

		return $meta_boxes;
	}

	/**
	 * Generate the metabox to display what pages the current section is on
	 * @param  array $meta_boxes existing metaboxes
	 * 
	 * @return array             metaboxes
	 */
	public function generateSectionOnMetabox( $meta_boxes ){

		// We only want to do any of this if we're editing a section post
		if( isset( $_GET[ 'post' ] ) && get_post_type( $_GET[ 'post' ] ) == $this->_postType ){

			// To make things easy - just output buffer
			ob_start();

			// Get all the posts containing our section
			$postsWith = $this->getPostsWith( $_GET[ 'post' ] );

			// Save the admin url
			$adminUrl = admin_url();

			// For each one, generate a link to it
			if( $postsWith && is_array($postsWith) && count($postsWith) > 0 ){
				printf( '<ul>' );
				foreach( $postsWith as $thePost ){
					printf( "<li><a href='%spost.php?post=%s&action=edit' target='_blank'>%s</a></li>", $adminUrl, $thePost->ID, $thePost->post_title);
				}
				printf( '</ul>' );
			}

			// Then add the metabox
			$addedBox = array(
				'id'            => 'arteric_section_posts_on',
				'title'         => __('Posts Containing this Section', 'ArtericSectionBuilder'),
				'context'       => 'side',
				'priority'      => 'high',
				'post_types'	=> $this->_postType,
				'fields'		=> array(

					array(
						'type'	=> 'custom_html',
						'std'	=> ob_get_clean(),
					)
				)
			);

			$meta_boxes[] = $addedBox;

		}

		return $meta_boxes;
	}

	/**
	 * Register a new section template
	 * @param  string $name     The name, will be slugified
	 * @param  array  $fields   The fields, as per metabox
	 * @param  string $template Template file
	 * 
	 * @return void
	 */
	public function registerSection( $name, array $fields = [], $template = '' ){

		// Make a slug from the name
		$slug = sanitize_title_with_dashes( $name );

		// Add that to our component selector list if it wasn't already
		if( !isset( $this->sectionSelectorList[ $slug ] ) ){

			// Check if we have tabs
			$tabList = array_unique( array_column( $fields, 'tab' ) );

			$tabs = [];
			if( count( $tabList ) > 0 ){
				foreach( $tabList as $theTab ){
					$tabs[ $theTab ] = str_replace( '_', ' ', $theTab );
				}
			}

			$metabox = array(
				'id'			=> 'arteric-section-fields-' . $slug,
				'title'			=> $name . __( ' Fields', 'ArtericSectionBuilder' ),
				'context'       => 'advanced',
				'priority'      => 'high',
				'post_types'	=> $this->_postType,
				'tabs'			=> $tabs,
				'fields'		=> $fields,

				// Include only when the template is selected
				'visible'		=> array(
					'selected_template', $slug
				),
			);

			// Register the fields for that
			$this->sectionMetaboxList[ $slug ] = $metabox;

			// Register the list being used for a dropdown later
			$this->sectionSelectorList[ $slug ] = $name;

			// Get only IDs
			$ids = array_flip(array_column( $fields, 'id' ));

			// There we go
			$this->fieldsList[ $slug ] = array_fill_keys(array_keys($ids), '');

			// Register template
			$this->templateList[ $slug ] = $template;
				
		}
	}

	/**
	 * Generate the registered metaboxes
	 * @param  array $meta_boxes existing metaboxes
	 * 
	 * @return array             metaboxes
	 */
	public function generateRegisteredMetaboxes( $meta_boxes ){

		foreach( $this->sectionMetaboxList as $theMetabox ){

			$meta_boxes[] = $theMetabox;
		}

		return $meta_boxes;
	}

	/**
	 * Helper function for the section builder, so we don't have to instatiate the class
	 * @param  integer $postID   The ID of the post to grab sections from
	 * 
	 * @return void
	 */
	public function buildSections( $postID = 0 ){
		
		// If we weren't passed an ID, default to the current post
		if( $postID == 0 ){
			$postID = get_the_id();
		}

		// var_dump( get_post_meta( $postID, $this->_metaboxID, true ) );

		$sections = get_post_meta( $postID, $this->_metaboxID, true );

		if( is_array( $sections ) ){

			foreach( $sections as $section ){

				$this->renderSection( $section[ 'section_selector' ], $section );
			}
		}
	}

	public function renderSection($sectionID, $section = array() ){
		// This variable is going to be exposed to the template
		$section[ 'post' ] = get_post( $sectionID );

		// Get the template slug first
		$templateSlug = get_post_meta( $sectionID, 'selected_template', true );

		// Not set, or false-y? Next.
		if( !$templateSlug ){
			return;
		}

		// Now, do we have a template to load?
		if (locate_template( $this->templateDirectory . $this->templateList[ $templateSlug ] ) ) {
			
			$sectionFields = $this->fieldsList[ $templateSlug ];

			// For safety, set defaults
            $section = array_merge($sectionFields, $section);

            // Set up the meta data
			foreach($sectionFields as $field => $content){

				// Get the meta for the provided field.
				$section[ $field ] = get_post_meta( $sectionID, $field, true);
			}

			// Include the template that we're building.
            include( locate_template( $this->templateDirectory . $this->templateList[ $templateSlug ] ) );
		}
	}

	/**
	 * Get all posts using a certain section
	 * @param  integer $postID The Post ID
	 * @return array 	The results
	 */
	private function getPostsWith( $postID ){

		// Get WPDB
		global $wpdb;

		// create our prepare statement
		// We want all posts who have our current section in their postmeta for arteric sections
		$stmt = 'SELECT * FROM ' . $wpdb->posts . ' p
INNER JOIN ' . $wpdb->postmeta . ' pm ON p.ID = pm.post_id
WHERE p.post_status = \'publish\'
AND meta_key = \'' . $this->_metaboxID . '\'
AND meta_value LIKE \'%%"%s"%%\'';

		return $wpdb->get_results( $wpdb->prepare( $stmt, $postID ) );
	}


	/**
	 * Add the section data into search
	 * @param  array 	Array of query clauses
	 * @return array 	Updated array of clauses
	 */

	public function addSectionsToSearch( $clauses ){

		// If we're searching the frontend
		if( is_search() && !is_admin() ){

			// We need WPDB
			global $wpdb;

			if(!strpos($clauses['join'], $wpdb->postmeta  )) {
				// Get the post meta table involved
				$clauses[ 'join' ] .= " LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ";
			}			
			
			// And we want distinct posts, no doubling up
			$clauses[ 'distinct' ] = 'DISTINCT';

			/**
			 * PANEL SEARCHING CODE:
			 * AFO - July 6th, 2017
			 * The logic below handles searching on panels
			 */

			// Get the search terms
			$search_terms = get_query_var( 'search_terms' );

			if( is_array( $search_terms ) ){

				// Get whether this is an exact search
				$exact = get_query_var( 'exact' );
				$n = ( empty( $exact ) ) ? '%%' : '';

				// Prepare a big statement of all the search terms
				$sectionPrepare = '';
				foreach( $search_terms as $index => $the_term ){

					if( $index !== 0 ){
						$sectionPrepare .= ' AND ';
					}

					$sectionPrepare .= $wpdb->prepare( 
						"(p.post_content LIKE '{$n}%s{$n}'
						OR p.post_title LIKE '{$n}%s{$n}'
						OR pm.meta_value LIKE '{$n}%s{$n}'
					)",
					$the_term,
					$the_term,
					$the_term );
				}

				 // Get all of the panels that contain our search term
				$stmt = "SELECT DISTINCT p.ID from {$wpdb->posts} AS p
					INNER JOIN {$wpdb->postmeta} as pm ON p.ID = pm.post_id  
					WHERE p.post_type = '{$this->_postType}'
					AND {$sectionPrepare}";

				// Perform the query above
				$relatedSections = $wpdb->get_results( $stmt );

				if( count( $relatedSections ) > 0 ){
					// Now, turn the related sections above into a string we can query some postmeta on
					$sectionSearch = '';
					foreach( $relatedSections as $index => $theSection ){
						
						// If this isn't the first one, we need to OR the statement
						if( $index !== 0 ){
							$sectionSearch .= ' OR ';
						}

						// We want to search within a serialized array. Strings are going to start with :" and end with ". This should differentiate strings in the array from IDs
						$sectionSearch .= $wpdb->postmeta . '.meta_value like \'%:"' . $theSection->ID . '"%\'';
					}

					// Now insert our new clause into the where statement
					$clauses[ 'where' ] = preg_replace( 
						'/AND \(\(/', 
						"AND (({$wpdb->postmeta}.meta_key = '{$this->_metaboxID}' AND ( {$sectionSearch} ) ) OR (",
						$clauses[ 'where' ],
						1 // limit
					);
				}
			}
		}

		return $clauses;
	}

}