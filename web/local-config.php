<?php

if( basename(__FILE__) === $_SERVER['SCRIPT_FILENAME'] ) {
	die();
}

if ( ! file_exists( __DIR__ . '/../vendor/autoload.php' ) ){
    die( '<strong>Error:</strong> Missing composer autoload file. Did you run composer install/update?' );
}

require(__DIR__ . '/../vendor/autoload.php');

/**
 * Pantheon platform settings. Pulled this from Pantheon's standard config file. 
 * Loads things in a Pantheon way while we're on the platform
 */
if (file_exists(dirname(__FILE__) . '/wp-config-pantheon.php') && isset($_ENV['PANTHEON_ENVIRONMENT'])) {
	require_once(dirname(__FILE__) . '/wp-config-pantheon.php');

/**
 * Local configuration information.
 * Used by default, since the Pantheon setting should only apply on Pantheon
 *
 */
} else {

	/**
	 * This block defines what is being loaded and what is required from the .env file.
	 * If you have not set up a .env file yet for this environment, see the .env.example file
	 * Note: EVERYTHING is required
	 */
	$dotenv = new Dotenv\Dotenv(__DIR__);
	try{
		$dotenv->load();
	}
	catch( Exception $e ){
		die( "<strong>Error:</strong> No .env file detected or .env file invalid. Please check the .env file.");
	}

	try{
		$dotenv->required('default_host')->notEmpty();
		$dotenv->required('force_ssl_admin')->notEmpty();
		$dotenv->required('wp_debug')->notEmpty();
		$dotenv->required('db_name')->notEmpty();
		$dotenv->required('db_user')->notEmpty();
		$dotenv->required('db_password')->notEmpty();
		$dotenv->required('db_host')->notEmpty();
		$dotenv->required('db_charset')->notEmpty();
		$dotenv->required('auth_key')->notEmpty();
		$dotenv->required('secure_auth_key')->notEmpty();
		$dotenv->required('logged_in_key')->notEmpty();
		$dotenv->required('nonce_key')->notEmpty();
		$dotenv->required('auth_salt')->notEmpty();
		$dotenv->required('secure_auth_salt')->notEmpty();
		$dotenv->required('logged_in_salt')->notEmpty();
		$dotenv->required('nonce_salt')->notEmpty();
		$dotenv->required('table_prefix')->notEmpty();
	}
	catch( Exception $e ){
		die( "<strong>Error:</strong> Required field is missing in .env file. Please check the .env file.");
	}

	//  Yes.  Everything is required.


	// local config

	$default_host = getenv('default_host');

	define( 'FORCE_SSL_ADMIN', (getenv('force_ssl_admin') === 'true') ? true : false );

	define( 'WP_DEBUG', (getenv('wp_debug') === 'true') ? true : false );

	// ** MySQL settings - You can get this info from your web host ** //
	/** The name of the database for WordPress */
	define('DB_NAME', getenv('db_name'));

	/** MySQL database username */
	define('DB_USER', getenv('db_user'));

	/** MySQL database password */
	define('DB_PASSWORD', getenv('db_password'));

	/** MySQL hostname */
	define('DB_HOST', getenv('db_host'));

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', getenv('db_charset'));

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');


	/**#@+
	 * Authentication Unique Keys and Salts.
	 *
	 * Change these to different unique phrases!
	 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
	 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
	 *
	 * @since 2.6.0
	 */
	define('AUTH_KEY',         getenv('auth_key'));
	define('SECURE_AUTH_KEY',  getenv('secure_auth_key'));
	define('LOGGED_IN_KEY',    getenv('logged_in_key'));
	define('NONCE_KEY',        getenv('nonce_key'));
	define('AUTH_SALT',        getenv('auth_salt'));
	define('SECURE_AUTH_SALT', getenv('secure_auth_salt'));
	define('LOGGED_IN_SALT',   getenv('logged_in_salt'));
	define('NONCE_SALT',       getenv('nonce_salt'));

	/**#@-*/

	/**
	 * WordPress Database Table prefix.
	 *
	 * You can have multiple installations in one database if you give each a unique
	 * prefix. Only numbers, letters, and underscores please!
	 */
	$table_prefix  = getenv('table_prefix');

	// This will load balancer + SSL issues by telling the servers behind the load balancer
	// that the load balancer was accessed over SSL
	if ( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')  $_SERVER['HTTPS']='on';
}

// wp_lang has been put in wp-config.php