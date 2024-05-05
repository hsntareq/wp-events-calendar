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
	use Traits\Singleton, Traits\PluginData;
	/**
	 * Path to the manifest.json file.
	 *
	 * @var string
	 */
	private $manifest_path;

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
			$this->enqueue_prod_assets();
		}

		wp_localize_script(
			'vite-admin-script',
			'ec_data',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ec_nonce' ),
			)
		);
	}

	/**
	 * Check if the Vite development server is reachable.
	 *
	 * @return bool True if reachable, false otherwise.
	 */
	public function is_dev_server_running() {
		try {
			// Check if the Vite development server is reachable.
			$response = wp_remote_get( $this->dev_server_url );

			if ( ( is_wp_error( $response ) ) && ( 200 !== wp_remote_retrieve_response_code( $response ) ) ) {
				return false;
			}
			return true;

		} catch ( Exception $ex ) {
			WP_Error( 'error', $ex->getMessage() );
		}
	}


	/**
	 * Enqueue development assets from Vite dev server.
	 */
	private function enqueue_dev_assets() {
		if ( $this->is_dev_server_running() ) {
			wp_enqueue_script( 'vite-admin-script', $this->dev_server . 'assets/src/admin.js', array( 'jquery' ), self::get_data( 'Version' ), true );
			// Add type="module" attribute to the script tag.
			add_filter( 'script_loader_tag', array( $this, 'add_module_type_to_script' ), 10, 3 );
		}
	}

	/**
	 * Add type="module" to development scripts.
	 *
	 * @param mixed $tag tag.
	 * @param mixed $handle handle.
	 * @param mixed $src src.
	 *
	 * @return string|void
	 */
	public function add_module_type_to_script( $tag, $handle, $src ) {
		if ( in_array( $handle, array( 'vite-admin-script' ), true ) ) {
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
			$manifest_data = file_get_contents( $this->manifest_path ); // phpcs:ignore

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
		wp_enqueue_style( 'vite-admin-style', EC_PLUGIN_URL . '/assets/dist/admin.css', array(), self::get_data( 'Version' ) );
	}

	/**
	 * Enqueue JS assets.
	 */
	private function enqueue_js() {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'vite-admin-script', EC_PLUGIN_URL . '/assets/dist/admin.js', array( 'jquery' ), self::get_data( 'Version' ), true );
	}
}
