<?php

namespace BaselinePlugin;

class AdminPage {

	protected $title = 'Admin Page';
	protected $menuTitle = 'Admin Page';
	protected $slug = 'admin-page';
	protected $optionGroup = 'admin-page';
	protected $settings = [];
	protected $fields = [];
	protected $requiredFields = array( 'title', 'slug' );

	protected $options;

	public function __construct( $args = array() ){

		// Check that all required fields are present
		foreach( $this->requiredFields as $theField ){
			if( !isset( $args[ $theField ] ) ){
				throw new \Exception( __CLASS__ . ': requires field "' . $theField . '" in declaration.' );
			}
		}

		// If we didn't explicity set a menu title, lets assume it matches our title
		if( isset( $args['title'] ) && !isset( $args['menuTitle'] ) ){
			$args['menuTitle'] = $args['title'];
		}

		// If we didn't set a location, default to 1
		if( !isset( $args[ 'location' ] ) ){
			$this->location = 1;
		}

		// If we didn't set an option group, default to the slug
		if( !isset( $args[ 'optionGroup' ] ) ){
			$this->optionGroup = $args['slug'];
		}

		// For each key provided in the args, set it up
		foreach( $args as $key => $setting ){

			$this->$key = $setting;
		}

		// And register some WP actions
		add_action( 'admin_menu', [$this, 'loadAdminPage'] );
		add_action( 'admin_init', [$this, 'loadSettings' ] );

		$this->options = get_option( $this->getOptionGroup() );
	}

	public function getOption( $optionName = '' ){

		if( $optionName == '' ){
			return $this->options;
		}

		if( isset($this->options[$optionName] ) ){
			return $this->options[$optionName];
		}

		return null;
	}

	public function loadAdminPage(){
		
		add_menu_page( 
			$this->getTitle(), // The title
			$this->getMenuTitle(), // The menu title
			'manage_options', // The permissions @TODO: make this configurable
			$this->getSlug(), // The slug
			[$this, 'renderBase'], // The function to output the markup
			'dashicons-dashboard', // The icon @TODO: make this configurable
			$this->getLocation() // Location @TODO: make this configurable
		);
	}

	public function loadSettings(){
		
		foreach( $this->settings as $settingName => $settingOptions ){

			\register_setting( 
				$this->getOptionGroup(),
				$this->getOptionGroup(),
				array( $this, 'sanitize' ),
				$this->getSlug()
			);
		}

		add_settings_section(
		    $this->getSlug() . '_section', // ID
		    '', //self::$PAGE_SUBTITLE, // Title
			[ $this, 'renderSection' ], // Callback
		    $this->getSlug() // Page
		);

		foreach( $this->fields as $fieldName => $fieldOptions ){

			$potentialMethod = 'render' . $fieldOptions['type'] . 'Field';

			if( method_exists( $this, $potentialMethod ) ){
				\add_settings_field(
					$fieldName,
					$fieldOptions['title'],
					[ $this, $potentialMethod ],
					$this->getSlug(),
					$this->getSlug() . '_section',
					array_merge(
						$fieldOptions,
						array( 'name'	=> $fieldName )
					)
				);
			}
		}
	}

	public function registerSetting( $settingName ){

		$this->settings[ $settingName ] = [];
	}

	protected function fieldCheck( $settingName ){

		// If we already registered this field, bail 
		if( isset( $this->fields[ $settingName ] ) ){
			throw new \Exception( __CLASS__ . ': Field ' . $settingName . ' already registered.' );
		}
		
		// If we didn't register the setting separately, register it
		if( !isset( $this->settings[ $settingName ] ) ){
			$this->registerSetting( $settingName );	
		}

	}

	protected function addField( $settingName, $fieldType, $args ){

		// First, let's make sure we're not doubling up
		$this->fieldCheck( $settingName );

		// Add this field to the fields
		$this->fields[ $settingName ] = [
			'type'	=> $fieldType,
		];

		// We don't want these to be overriden
		unset( $args[ 'name' ] );
		unset( $args[ 'type' ] );

		// Get any other settings we set in there
		$this->fields[ $settingName ] = array_merge( $args, $this->fields[ $settingName ] );
	}

	public function addTextField( $settingName, $args ){
		
		$this->addField( $settingName, 'Text', $args );
	}

	public function addNumberField( $settingName, $args ){

		$this->addField( $settingName, 'Number', $args );
	}

	public function addTextAreaField( $settingName, $args ){

		$this->addField( $settingName, 'TextArea', $args );
	}

	public function addCheckboxField( $settingName, $args ){

		$this->addField( $settingName, 'Checkbox', $args );
	}

	public function addDropdownField( $settingName, $args ){

		if( !isset( $args['values'] ) ){
			throw new \Exception( __CLASS__ . ': Field ' . $settingName . ' requires a "values" parameter.' );
		}

		if( !is_array( $args['values'] ) ){
			throw new \Exception( __CLASS__ . ': Field ' . $settingName . ' "values" parameter must be an array.' );
		}

		$this->addField( $settingName, 'Dropdown', $args );
	}

	public function addWYSIWYGField( $settingName, $args ){

		$this->addField( $settingName, 'WYSIWYG', $args );
	}

	public function setTitle( $new ){
		$this->title = $new;
	}

	public function setMenuTitle( $new ){
		$this->menuTitle = $new;
	}

	public function setSlug( $new ){
		$this->slug = $new;
	}

	public function setLocation( $new ){
		$this->location = $new;
	}

	public function setOptionGroup( $new ) {
		$this->optionGroup = $new;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getMenuTitle(){
		return $this->menuTitle;
	}

	public function getSlug(){
		return $this->slug;
	}

	public function getLocation(){
		return $this->location;
	}

	public function getOptionGroup() {
		return $this->optionGroup;
	}

	public function renderBase(){
		?>
		<div class="wrap">
			<h2><?php echo $this->getTitle(); ?></h2>
			<?php $this->renderBody(); ?>
			<?php $this->renderForm(); ?>
		</div>
		<?php
	}

	protected function renderBody(){}

	protected function renderForm(){
		?>
		<form method="post" action="options.php">
		<?php 
			\settings_fields( $this->getOptionGroup() ); 
			\do_settings_sections( $this->getSlug() );
			\submit_button(); 
		?>
		</form>
		<?php
	}

	public function renderSection(){
		echo '';
	}

	public function renderTextField( $args ){

		$fieldName = $args[ 'name' ];
		$fieldValue = $this->getOption($fieldName);

		printf(
			'<input type="text" id="%1$s" name="%2$s[%1$s]" class="large-text" value="%3$s"></input>',
			$fieldName,
			$this->getOptionGroup(),
			!is_null( $fieldValue ) ? esc_html( $fieldValue ) : ''
		);
	}

	public function renderNumberField( $args ){

		$fieldName = $args[ 'name' ];
		$fieldValue = $this->getOption($fieldName);

		printf(
			'<input type="number" id="%1$s" name="%2$s[%1$s]" class="regular-text" value="%3$s"></input>',
			$fieldName,
			$this->getOptionGroup(),
			!is_null( $fieldValue ) ? esc_html( $fieldValue ) : ''
		);
	}

	public function renderTextAreaField( $args ){

		$fieldName = $args[ 'name' ];
		$fieldValue = $this->getOption($fieldName);


		printf(
			'<textarea id="%1$s" name="%2$s[%1$s]" class="large-text code" rows="20">%3$s</textarea>',
			$fieldName,
			$this->getOptionGroup(),
			!is_null( $fieldValue ) ? esc_html( $fieldValue ) : ''
		);
	}

	public function renderCheckboxField( $args ){

		$fieldName = $args[ 'name' ];
		$fieldTitle = $args[ 'title' ];
		$fieldValue = $this->getOption($fieldName);

		printf(
			'<label for="%1$s"><input type="checkbox" name="%2$s[%1$s]" id="%1$s" value="1" %3$s>%4$s</label>',
			$fieldName,
			$this->getOptionGroup(),
			!is_null( $fieldValue ) ? 'checked="checked"' : '',
			$fieldTitle
		);
	}

	public function renderDropdownField( $args ){

		$fieldName = $args[ 'name' ];
		$fieldValue = $this->getOption($fieldName);
		$fieldSaved = !is_null( $fieldValue ) ? esc_html( $fieldValue ) : '';

		$options = '';

		// If we have a placeholder, create an option
		if( isset( $args[ 'placeholder' ] ) ){
			$options .= sprintf(
				'<option value="">%s</option>',
				$args[ 'placeholder' ]
			);
		}

		// If we have values, set them up
		foreach( $args[ 'values' ] as $value => $label ){

			$selected = $fieldSaved == $value ? 'selected="selected"' : '';

			$options .= sprintf( 
				'<option value="%s" %s>%s</option>',
				esc_html($value),
				$selected,
				$label
			);
		}

		// Return the select
		printf(
			'<select name="%2$s[%1$s]" id="%1$s">%3$s</select>',
			$fieldName,
			$this->getOptionGroup(),
			$options
		);
	}

	public function renderWYSIWYGField( $args ){

		$fieldName = $args[ 'name' ];
		$fieldTitle = $args[ 'title' ];
		$fieldValue = $this->getOption($fieldName);
		$fieldSettings = isset( $args[ 'settings' ] ) ? $args[ 'settings' ] : array();

		if( !isset( $fieldSettings[ 'textarea_name' ] ) ){
			$fieldSettings[ 'textarea_name' ] = sprintf( '%s[%s]', $this->getOptionGroup(), $fieldName );
		}

		$option = !is_null( $fieldValue ) ? esc_html( $fieldValue ) : '';

		wp_editor( $option, $fieldName, $fieldSettings );
	}

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {

        return $input;
    }
}