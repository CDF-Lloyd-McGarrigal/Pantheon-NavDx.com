<?php
/**
 * Plugin Name: Meta Box AIO
 * Plugin URI:  https://metabox.io/pricing/
 * Description: All Meta Box extensions in one package.
 * Version:     1.15.2
 * Author:      MetaBox.io
 * Author URI:  https://metabox.io
 * License:     GPL2+
 * Text Domain: meta-box-aio
 * Domain Path: /languages/
 *
 * @package    Meta Box
 * @subpackage Meta Box AIO
 */

defined( 'ABSPATH' ) || die;

define( 'MBAIO_DIR', __DIR__ );

require __DIR__ . '/src/Loader.php';
require __DIR__ . '/src/Settings.php';
require __DIR__ . '/src/Plugin.php';

new MBAIO\Loader;
new MBAIO\Settings;
new MBAIO\Plugin;