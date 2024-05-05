<?php
/**
 * Plugin Name: Events Calender
 * Plugin URI: https://www.events-calendar.com
 * Version: 1.0.0
 * Author: Hasan Tareq
 * Author URI: https://github.com/hsntareq
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: events-calender
 * Description: This plugin will show related random posts under each post.
 *
 * @package wordpress-plugin
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'EC_PLUGIN_FILE' ) ) {
	define( 'EC_PLUGIN_FILE', __FILE__ );
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * FILEPATH: /wp-content/plugins/related-random-posts/related-random-posts.php
 *
 * Initializes the AM API plugin by creating an instance of the PluginMain class.
 *
 * @since 1.0.0
 */
EventsCalender\Plugin_Main::get_instance();
