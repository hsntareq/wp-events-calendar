<?php
/**
 * Enqueue Class
 *
 * @package wp-plugin
 * @since 1.0
 */

namespace EventsCalender;

/**
 * Enqueue Class.
 */
class Enqueue {
	/**
	 * $instance
	 *
	 * @var null
	 */
	private static $instance = null;
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	/**
	 * The instance of this class.
	 *
	 * @return Enqueue
	 */
	public static function get_instance(): Enqueue {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance ?? new self();
	}

	/**
	 * Enqueue style and script for the plugin.
	 *
	 * @return void
	 */
	public function enqueue() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		wp_enqueue_style( 'learn-plugin-style', RRP_PLUGIN_URL . '/assets/css/style.css', array(), RRP_PLUGIN_VERSION, 'all' );
		wp_enqueue_script( 'learn-plugin-script', RRP_PLUGIN_URL . '/assets/js/main.js', array( 'jquery' ), RRP_PLUGIN_VERSION, true );
	}
}
