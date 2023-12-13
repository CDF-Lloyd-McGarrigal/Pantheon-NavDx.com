<?php

namespace rtrcMenuJumplniks;

use League\Event\Emitter;

use rtrcMenuJumplniks\Admin;
use rtrcMenuJumplniks\Shortcode;

class App
{
	const TEXT_DOMAIN = '[Plugin Text Domain]';

	protected static $registry = [];

	public static function init($wpdb)
	{
		// Bind an event emitter.
	    static::bind('events', new Emitter);

	    // Fire off an event that our plugin has started loading
	    static::get('events')->emit('plugin.init.start');

	    // Bind the "admin" class
	    static::bind('admin', new Admin());

	    // Fire off an event that our plugin has finished loading
	    static::get('events')->emit('plugin.init.finish');
	}

	/**
	 * Binds a value to the provided key in the App container.
	 * This does not check if the value is currently bound. It
	 * will just "overwrite" the existing value.
	 * @param  String $key   The key to bind the value to.
	 * @param  mixed $value  The value to bind.
	 * @return mixed         Given value.
	 */
	public static function bind($key, $value)
	{
	    static::$registry[$key] = $value;
	}

	/**
	 * Returns value from the app container.
	 * If the key is not registered, looks to the Admin object
	 * to load the value from stored DB options
	 * @param  String $key The key to lookup
	 * @return mixed The value or false if none found
	 */
	public static function get($key)
	{
	    if(!array_key_exists($key, static::$registry)) {
	        return static::loadFromAdminOptions($key);
	    }
	    return static::$registry[$key];
	}

	/**
	 * Attemps to load an option from the DB.
	 * Memorizes the value if found.
	 * @param  String $key The key to lookup
	 * @return mixed       The value or false if none found.
	 */
	private static function loadFromAdminOptions($key)
	{
	    static::$registry[$key] = Admin::getOption( $key );

	    return static::$registry[$key];
	}
}
