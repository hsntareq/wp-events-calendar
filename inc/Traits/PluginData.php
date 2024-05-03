<?php
/**
 * Traits of the plugin.
 *
 * @package wordpress-plugin
 */

namespace EventsCalender\Traits;

trait PluginData {
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {

	}

	/**
	 * Get the plugin version.
	 *
	 * @param string $key key.
	 *
	 * @return string|array
	 */
	public static function get_data( $key = '' ) {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return ( ! empty( $key ) ) ? get_plugin_data( EC_PLUGIN_FILE )[ $key ] : get_plugin_data( EC_PLUGIN_FILE );
	}

	/**
	 * Plugin Name.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Plugin URI.
	 *
	 * @var string
	 */
	private $plugin_uri;

	/**
	 * Plugin Version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Plugin Author.
	 *
	 * @var string
	 */
	private $plugin_author;

	/**
	 * Plugin Author URI.
	 *
	 * @var string
	 */
	private $plugin_author_uri;

	/**
	 * Plugin License.
	 *
	 * @var string
	 */
	private $plugin_license;

	/**
	 * Plugin License URI.
	 *
	 * @var string
	 */
	private $plugin_license_uri;

	/**
	 * Plugin Text Domain.
	 *
	 * @var string
	 */
	private $plugin_text_domain;

	/**
	 * Plugin Description.
	 *
	 * @var string
	 */
	private $plugin_description;

	/**
	 * Get the plugin name.
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Get the plugin URI.
	 *
	 * @return string
	 */
	public function get_plugin_uri() {
		return $this->plugin_uri;
	}

	/**
	 * Get the plugin version.
	 *
	 * @return string
	 */
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	/**
	 * Get the plugin author.
	 *
	 * @return string
	 */
	public function get_plugin_author() {
		return $this->plugin_author;
	}

	/**
	 * Get the plugin author URI.
	 *
	 * @return string
	 */
	public function get_plugin_author_uri() {
		return $this->plugin_author_uri;
	}

	/**
	 * Get the plugin license.
	 *
	 * @return string
	 */
	public function get_plugin_license() {
		return $this->plugin_license;
	}

	/**
	 * Get the plugin license URI.
	 *
	 * @return string
	 */
	public function get_plugin_license_uri() {
		return $this->plugin_license_uri;
	}

	/**
	 * Get the plugin text domain.
	 *
	 * @return string
	 */
	public function get_plugin_text_domain() {
		return $this->plugin_text_domain;
	}

	/**
	 * Get the plugin description.
	 *
	 * @return string
	 */
	public function get_plugin_description() {
		return $this->plugin_description;
	}

}
