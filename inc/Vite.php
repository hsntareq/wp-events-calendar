<?php
/**
 * Vite class.
 *
 * This class helps integrate Vite assets into your WordPress plugin.
 *
 * @package AmApi
 */

namespace EventsCalender;

use WpdbCrud\Singleton;

/**
 * Class Vite
 */
class Vite {
	use Traits\Singleton;
	/**
	 * Path to the manifest.json file.
	 *
	 * @var string
	 */
	private $manifest_path;

	/**
	 * Whether the environment is in development mode.
	 *
	 * @var bool
	 */
	private $is_dev;

	/**
	 * Vite development server URL (if available).
	 *
	 * @var string|null
	 */
	private $dev_server;

	/**
	 * Vite development server URL (if available).
	 *
	 * @var string|null
	 */
	private $dev_server_url;

	/**
	 * Vite constructor.
	 */
	public function __construct() {
		$this->is_dev = 'dev'; // prod .
		// Set the path to the manifest.json file.
		$this->manifest_path  = EC_PLUGIN_PATH . '/assets/dist/.vite/manifest.json';
		$this->dev_server     = 'http://localhost:3030/'; // Adjust the URL according to your Vite dev server configuration.
		$this->dev_server_url = $this->dev_server . 'assets/index.php'; // Adjust the URL according to your Vite dev server configuration.
		// Add actions to enqueue assets.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		// Consider adding admin assets with `add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );`.
	}

	/**
	 * Enqueue assets.
	 */
	public function enqueue_assets() {
		if ( $this->is_dev_server_running() ) {
			// Enqueue assets from Vite dev server.
			$this->enqueue_dev_assets();
		} else {
			// Enqueue production assets.
			// $this->enqueue_prod_assets();
		}
	}

	/**
	 * Check if the Vite development server is reachable.
	 *
	 * @return bool True if reachable, false otherwise.
	 */
	public function is_dev_server_running() {
		$response = wp_remote_get( $this->dev_server_url );
		if ( ! is_wp_error( $response ) ) {
			$status_code = wp_remote_retrieve_response_code( $response );
			return 200 === $status_code;
		}
		return false;
	}


	/**
	 * Enqueue development assets from Vite dev server.
	 */
	private function enqueue_dev_assets() {
		// echo '<pre>';
		// var_dump( $this->is_dev_server_running() );
		// die;

		if ( $this->is_dev_server_running() ) {
			wp_enqueue_script( 'vite-admin', $this->dev_server . 'assets/src/admin.js', array(), null, true );
			// Add type="module" attribute to the script tag
			add_filter( 'script_loader_tag', array( $this, 'add_module_type_to_script' ), 10, 3 );
		} else {
			// Handle case where dev server is not reachable (e.g., display a message)
			// wp_die( 'Vite development server is not reachable.' );
		}
	}

	public function add_module_type_to_script( $tag, $handle, $src ) {
		// if ( 'vite-main' === $handle ) {

		if ( in_array( $handle, array( 'vite-admin' ) ) ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}
		return $tag;
	}

	/**
	 * Enqueue production assets.
	 */
	private function enqueue_prod_assets() {
		// Get the manifest data.
		$manifest_data = $this->get_manifest_data();

		if ( $manifest_data ) {
			// Enqueue CSS and JS assets.
			$this->enqueue_css();
			$this->enqueue_js();
		}
	}

	/**
	 * Get the manifest data.
	 *
	 * @return array|false
	 */
	private function get_manifest_data() {
		// Read the manifest.json file.
		if ( file_exists( $this->manifest_path ) ) {
			$manifest_data = file_get_contents( $this->manifest_path );

			// Decode JSON data.
			$manifest_data = json_decode( $manifest_data, true );

			return $manifest_data;
		}

		return false;
	}

	/**
	 * Enqueue CSS assets.
	 */
	private function enqueue_css() {
		wp_enqueue_style( 'vite-admin-style', EC_PLUGIN_URL . '/assets/dist/admin.css', array(), '1.0.0' );
	}

	/**
	 * Enqueue JS assets.
	 */
	private function enqueue_js() {
		wp_enqueue_script( 'vite-admin-script', EC_PLUGIN_URL . '/assets/dist/admin.js', array(), '1.0.0', true );
	}
}
