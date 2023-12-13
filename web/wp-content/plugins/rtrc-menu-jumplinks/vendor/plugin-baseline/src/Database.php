<?php

namespace BaselinePlugin;

class Database {

	/** @var string The name of the table */
	private $tableName = '';

	/** @var string The version number */
	private $version = '1.0.0';

	/** @var string A standardized version name for the schema */
	private $_VERSION_OPTION = '_db_schema_version';

	/**
	 * Define the database based on a table and version number
	 * 
	 * @param string $tableName The table's name
	 * @param string $version   A php standardized version number. Default 1.0.0
	 */
	public function __construct( $tableName, $version = '1.0.0' ){

		$this->tableName = $tableName;
		$this->version = $version;
	}

	/**
	 * Create a table based on the schema. This will only execute if the version is before the current version
	 * 
	 * @param  string $schema The schema
	 * @param boolean $force Whether to force the update even if schema exists
	 * 
	 * @return void         
	 */
	public function createTable( $schema, $force = false ){

		// Check to make sure we haven't created this schema yet
		// If we're not forcing, just check that we have a version already
		if( !$force && !$this->checkVersionExists() ){
			return;
		}
		// If we're forcing, check that we're up to date
		elseif( $force && $this->checkVersionUpToDate() ){
			return;
		}

		$this->doDbDelta( $this->version, $schema );
	}

	/**
	 * Updates the schema based on version number
	 * 
	 * @param  string $version The version to update on
	 * @param  string $schema  The new schema
	 * 
	 * @return void          
	 */
	public function updateSchema( $version, $schema ){

		// If our version is up to date, no need to continue
		if( $this->checkVersion( $version ) ){
			return;
		}

		// Run the schema
		$this->doDbDelta( $version, $schema );
	}

	/**
	 * Executes a callback function if the schema is under the version number
	 *
	 * @param  string $version The version to update on
	 * @param  string $schema  The new schema
	 * 
	 * @return void
	 */
	public function updateSchemaCallback( $version, $callback ){

		// If our version is up to date, no need to continue
		if( $this->checkVersion( $version ) ){
			return;
		}

		// If we can't call what we are provided, bail
		if( !is_callable( $callback ) ){

			return false;
		}

		// If our callback returned specifically false, assume something messed up and don't update the version 
		if( call_user_func( $callback ) === false ){
			return false;
		}

		// Otherwise, let's mark the version updated
		$this->updateVersion( $version );
	}

	/**
	 * Executes the DB delta on a schema, and then updates
	 *
	 * @param  string $version The version to update on
	 * @param  string $schema  The new schema
	 * 
	 * @return void
	 */
	private function doDbDelta( $version, $schema ){

		// Get WPDB
		global $wpdb;

		// Get the charset
		$charset_collate = $wpdb->get_charset_collate();

		// Get the full table name
		$prefixedName = $wpdb->prefix . $this->tableName;

		// Generate SQL for this
		$sql = "CREATE TABLE $prefixedName (
		  $schema
		) $charset_collate;";

		// And execute
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$result = dbDelta( $sql );

		$this->updateVersion( $version );
	}

	private function checkVersionExists(){

		// Get the option name
		$optionName = $this->getVersionOptionName();

		// Get the value
		$optionValue = \get_option( $optionName, null );

		return is_null( $optionValue );
	}

	/**
	 * Check the version number in the DB against the current version number
	 * 
	 * @return boolean True if our version is up to date, false otherwise
	 */
	private function checkVersionUpToDate() {

		return $this->checkVersion( $this->version );
	}

	/**
	 * Check if our registered version is greater or less than some other version
	 * 
	 * @param  string $version The version number
	 * 
	 * @return boolean True if our version is up to date, false otherwise
	 */
	private function checkVersion( $version ) {

		// Get the option name
		$optionName = $this->getVersionOptionName();

		// Get the value
		$optionValue = \get_option( $optionName, null );

		// If it's null, we don't have it. Return false.
		if( is_null( $optionValue ) ){
			return false;
		}

		// Otherwise, return on the compare
		return version_compare( $optionValue, $version, '>=' );
	}

	/**
	 * Update the version to the latest
	 * 
	 * @return void
	 */
	private function updateVersion( $version ){

		// Get the option name
		$optionName = $this->getVersionOptionName();

		// Set it to our new version
		\update_option( $optionName, $version );
	}

	/**
	 * Get the name of the version options
	 * 
	 * @return string TABLENAME . _VESRION_OPTION
	 */
	private function getVersionOptionName() {
		return $this->tableName . $this->_VERSION_OPTION;
	}
}