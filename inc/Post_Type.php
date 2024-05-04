<?php
/**
 * Post_Type Class
 *
 * @package wordpress-plugin
 */

namespace EventsCalender;

/**
 * Post_Type Class
 */
class Post_Type {
	use Traits\Singleton;

	/**
	 * Initialize the class
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		// Add Event Date column to the admin screen.
		add_filter( 'manage_event_posts_columns', array( $this, 'add_event_date_column' ) );
		// Add Event Date column to the admin screen.
		add_action( 'manage_event_posts_custom_column', array( $this, 'add_event_date_custom_column' ), 10, 2 );
		// Make the event date column sortable.
		add_filter( 'manage_edit-event_sortable_columns', array( $this, 'add_sortable_column' ) );

		Meta_Box::get_instance();
	}

	/**
	 * Make the event date column sortable.
	 *
	 * @param mixed $columns Columns.
	 *
	 * @return array
	 */
	public function add_sortable_column( $columns ) {
		$columns['event_date'] = 'event_date';

		return $columns;
	}

	/**
	 * Add Event Date column to the admin screen.
	 *
	 * @param mixed $column Column.
	 * @param mixed $post_id Post ID.
	 *
	 * @return void
	 */
	public function add_event_date_custom_column( $column, $post_id ) {
		if ( 'event_date' === $column ) {
			// Make the Y-m-d date format human readable.
			$event_date = gmdate( 'F j, Y', strtotime( get_post_meta( $post_id, 'event_date', true ) ) );
			$event_time = gmdate( 'g:i A', strtotime( get_post_meta( $post_id, 'event_time', true ) ) );

			echo esc_html( $event_date . ' at ' . $event_time );
		}
	}

	/**
	 * Add Event Date column to the admin screen.
	 *
	 * @param mixed $columns Columns.
	 *
	 * @return array
	 */
	public function add_event_date_column( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;
			if ( 'title' === $key ) {
				$new_columns['event_date'] = __( 'Event Date', 'events-calendar' );
			}
		}

		return $new_columns;
	}


	/**
	 * Register post type
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Events', 'post type general name', 'events-calendar' ),
			'singular_name'      => _x( 'Event', 'post type singular name', 'events-calendar' ),
			'menu_name'          => _x( 'Events', 'admin menu', 'events-calendar' ),
			'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'events-calendar' ),
			'add_new'            => _x( 'Add New', 'event', 'events-calendar' ),
			'add_new_item'       => __( 'Add New Event', 'events-calendar' ),
			'new_item'           => __( 'New Event', 'events-calendar' ),
			'edit_item'          => __( 'Edit Event', 'events-calendar' ),
			'view_item'          => __( 'View Event', 'events-calendar' ),
			'all_items'          => __( 'All Events', 'events-calendar' ),
			'search_items'       => __( 'Search Events', 'events-calendar' ),
			'parent_item_colon'  => __( 'Parent Events:', 'events-calendar' ),
			'not_found'          => __( 'No events found.', 'events-calendar' ),
			'not_found_in_trash' => __( 'No events found in Trash.', 'events-calendar' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'events-calendar' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'event' ),
			'capability_type'    => 'post',
			'menu_icon'          => 'dashicons-calendar',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		);

		register_post_type( 'event', $args );
	}



}

