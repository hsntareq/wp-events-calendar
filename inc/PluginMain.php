<?php
/**
 * Plugin File: AM API
 * Description: This plugin will show related random posts under each post.
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace EventsCalender;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PluginMain Class
 */
final class PluginMain {
	use Traits\Singleton, Traits\PluginData;

	/**
	 * Class constructor (private to enforce singleton pattern).
	 *
	 * @return void
	 */
	private function __construct() {
		// All the initialization tasks.
		$this->register_hooks();
	}

	/**
	 * Initialize classes to the plugin.
	 * This method will run after the plugins_loaded action has been fired.
	 * This is a good place to include files and instantiate classes.
	 * This method is called by the register_hooks method.
	 *
	 * @return void
	 */
	public function init_plugin() {
		// Defining plugin constants.
		$this->define_constants();
		Vite::get_instance();
		Admin_Page::get_instance();

	}
	/**
	 * Register hooks and do other setup tasks.
	 */
	private function register_hooks() {
		register_activation_hook( EC_PLUGIN_FILE, array( $this, 'plugin_activation' ) );
		register_deactivation_hook( EC_PLUGIN_FILE, array( $this, 'plugin_deactivation' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}


	/**
	 * Function to define all constants.
	 */
	private function define_constants() {
		// This EC_PLUGIN_VERSION constant is defined 'PLUGIN_VERSION' property of the PluginMain class.
		if ( ! defined( 'EC_PLUGIN_VERSION' ) ) {
			define( 'EC_PLUGIN_VERSION', self::get_data( 'Version' ) );
		}

		// It is defined as the plugin directory path without the trailing slash.
		if ( ! defined( 'EC_PLUGIN_PATH' ) ) {
			define( 'EC_PLUGIN_PATH', untrailingslashit( plugin_dir_path( EC_PLUGIN_FILE ) ) );
		}

		// EC_PLUGIN_URL is defined as the URL for the plugin directory.
		if ( ! defined( 'EC_PLUGIN_URL' ) ) {
			define( 'EC_PLUGIN_URL', untrailingslashit( plugin_dir_url( EC_PLUGIN_FILE ) ) );
		}

		// EC_PLUGIN_ASSETS is the URL for the assets directory of the Learn Plugin.
		if ( ! defined( 'EC_PLUGIN_ASSETS' ) ) {
			define( 'EC_PLUGIN_ASSETS', EC_PLUGIN_URL . '/assets' );
		}
	}

	/**
	 * Run code when the plugin is activated
	 */
	public function plugin_activation() {

		$installed = get_option( 'rrp_plugin_installed' );

		if ( ! $installed ) {
			update_option( 'rrp_plugin_installed', time() );
		}

		update_option( 'rrp_plugin_version', EC_PLUGIN_VERSION );
	}
	/**
	 * Run code when the plugin is activated
	 */
	public function plugin_deactivation() {

		delete_option( 'rrp_plugin_installed' );
		delete_option( 'rrp_plugin_version' );
	}
}
