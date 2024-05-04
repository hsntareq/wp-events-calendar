<?php
/**
 * The Meta_Box Class.
 *
 * @package wordpress-plugin
 */

namespace EventsCalender;

/**
 * Meta Box Class
 */
class Meta_Box {
	use Traits\Singleton;

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 *
	 * @param string $post_type The post type.
	 */
	public function add_meta_box( $post_type ) {
		// Limit meta box to certain post types.
		$post_types = array( 'event' );

		if ( in_array( $post_type, $post_types ) ) {
			add_meta_box(
				'events-date-time',
				__( 'Events Date & Time', 'events-calendar' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'side',
				'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['ec_post_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['ec_post_custom_box_nonce'] ) );

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'ec_post_custom_box' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		// Sanitize the user input.
		$field_date = ! empty( $_POST['event_date'] ) ? sanitize_text_field( wp_unslash( $_POST['event_date'] ) ) : '';
		$field_time = ! empty( $_POST['event_time'] ) ? sanitize_text_field( wp_unslash( $_POST['event_time'] ) ) : '';

		// Check the value of the meta key and set it.
		$event_date = ! empty( $field_date ) ? $field_date : date_i18n( 'Y-m-d', 'events-calendar' );
		$event_time = ! empty( $field_time ) ? $field_time : date_i18n( 'H:i', 'events-calendar' );

		// Update the meta field.
		update_post_meta( $post_id, 'event_date', $event_date );
		update_post_meta( $post_id, 'event_time', $event_time );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param \WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'ec_post_custom_box', 'ec_post_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$meta_date = get_post_meta( $post->ID, 'event_date', true );
		$meta_time = get_post_meta( $post->ID, 'event_time', true );

		// Check the value of the meta key and set it.
		$event_date = ! empty( $meta_date ) ? $meta_date : date_i18n( 'Y-m-d', 'events-calendar' );
		$event_time = ! empty( $meta_time ) ? $meta_time : date_i18n( 'H:i', 'events-calendar' );

		// Display the form, using the current value.
		?>
		<p>
			<?php esc_html_e( 'Select the date and time for your event.', 'events-calendar' ); ?>
		</p>
		<p>
			<input type="date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" />
		</p>
		<p>
			<input type="time" name="event_time" value="<?php echo esc_attr( $event_time ); ?>" />
		</p>
		<?php
	}
}
