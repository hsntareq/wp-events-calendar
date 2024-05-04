<?php
/**
 * Get Post Trait
 *
 * @package wordpress-plugin
 */

namespace EventsCalender\Traits;

/**
 * Get Post Trait
 */
trait GetPost {

	public function meta_data() {
		global $wpdb;
		// Note that this will produce a "key only" query
// If you want a full one, add a meta_value and meta_compare array key/value pair
		$query_args = array(
			'meta_key' => 'event_date',
		);
		$meta_query = new \WP_Meta_Query();
		$meta_query->parse_query_vars( $query_args );
		$mq = $meta_query->get_sql(
			'post',
			$wpdb->posts,
			'ID',
			null
		);
		// Execute the SQL query
		// $results = $wpdb->get_results( $mq['query'] );

		// Return the results
		return $meta_query;
	}
	/**
	 * Get post by ID
	 *
	 * @param mixed $date Date.
	 *
	 * @return void
	 */
	public function ec_event_by_date( $date ) {
		// Convert the date to MySQL format
		$date_formatted = date( 'Y-m-d', strtotime( $date ) );

		// Custom query to fetch events for the given date
		$args = array(
			'post_type'      => 'event', // Replace 'event' with your custom post type slug
			'posts_per_page' => -1,
			'meta_key'       => 'event_date', // Replace 'event_date' with the meta key for your event date field
			'meta_query'     => array(
				array(
					'key'     => 'event_date', // Replace 'event_date' with the meta key for your event date field
					'value'   => $date_formatted,
					'compare' => '=', // Change the comparison operator as needed (e.g., '<=', '>=', etc.)
					'type'    => 'DATE',
				),
			),
			'orderby'        => 'meta_value', // Order by the event date meta field
			'order'          => 'ASC', // Order events by date in ascending order
		);

		$events_query = new \WP_Query( $args );

		$html = '';
		// Check if there are any events for the given date
		if ( $events_query->have_posts() ) {
			$html .= '<ul>';
			while ( $events_query->have_posts() ) {
				$events_query->the_post();
				$event_time = get_post_meta( get_the_ID(), 'event_time', true );
				$event_time = gmdate( 'g:i A', strtotime( $event_time ) );

				// Display event title or other relevant information
				$html .= '<li><a href="' . get_the_permalink() . '">' . $event_time . ': ' . get_the_title() . '</a>';
				$html .= '<a class="edit" href="' . get_edit_post_link( get_the_ID() ) . '">✎</a>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			wp_reset_postdata(); // Restore global post data
		}

		return $html;
	}
}
