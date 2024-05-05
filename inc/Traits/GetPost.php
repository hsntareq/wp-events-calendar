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

	/**
	 * Get post by ID
	 *
	 * @param mixed $date Date.
	 *
	 * @return string|void
	 */
	public function ec_event_by_date( $date ) {
		// Custom query to fetch events for the given date.
		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => -1,
			'meta_key'       => 'event_date', // phpcs:ignore
			'meta_query'     => array( // phpcs:ignore
				array(
					'key'     => 'event_date',
					'value'   => $date,
					'compare' => '=',
					'type'    => 'DATE',
				),
			),
			'orderby'        => 'meta_value', // Order by the event date meta field.
			'order'          => 'ASC', // Order events by date in ascending order.
		);

		$events_query = new \WP_Query( $args );

		ob_start();
		$html = '';
		// Check if there are any events for the given date.
		if ( $events_query->have_posts() ) {
			$html .= '<ul>';
			while ( $events_query->have_posts() ) {
				$events_query->the_post();
				$event_time = get_post_meta( get_the_ID(), 'event_time', true );
				$event_time = gmdate( 'g:i A', strtotime( $event_time ) );

				// Display event title or other relevant information.
				$html .= '<li><a href="' . get_the_permalink() . '">' . $event_time . ': ' . get_the_title() . '</a>';
				// Add edit link.
				$html .= '<a class="edit" href="' . get_edit_post_link( get_the_ID() ) . '">✎</a>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			wp_reset_postdata(); // Restore global post data.
		}
		$html = ob_get_clean();
		return $html;
	}
}
