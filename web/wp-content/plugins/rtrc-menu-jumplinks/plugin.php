<?php
/**
 * Plugin Name: Menu Jump Links
 * Description: Adds jump links to the menus
 * Plugin URI: https://arteric.com
 * Version: 0.1
 * Author: Anthony Outeiral
 * Author URI: https://arteric.com
 *
 * The "text domain" of the plugin
 * Text Domain: rtrcMenuJumplniks
 *
 * The directory to look for translation files
 * Domain Path: languages
 *
 * Set this to true if you want the plugin to be only active on the network
 * Network: false
 */

use rtrcMenuJumplniks\App as App;

include __DIR__ . '/vendor/autoload.php';

global $wpdb;
App::init($wpdb);
