<?php
/**
 * Admin Page.
 *
 * @package wordpress-plugin
 */

namespace EventsCalender;

/**
 * Admin Page.
 */
class Admin_Page {
	use Traits\Singleton, Traits\Render, Traits\GetPost;

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
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'wp_ajax_monthly_calendar_data', array( $this, 'get_monthly_calendar_data' ) );
		add_action( 'wp_ajax_nopriv_monthly_calendar_data', array( $this, 'get_monthly_calendar_data' ) );
	}

	// AJAX handler for fetching calendar data
	function get_monthly_calendar_data() {
		$year  = isset( $_POST['year'] ) ? intval( $_POST['year'] ) : date( 'Y' );
		$month = isset( $_POST['month'] ) ? intval( $_POST['month'] ) : date( 'n' );

		// Call the monthly_calendar_data function to generate calendar HTML
		$calendar_data = $this->monthly_calendar_data( $year, $month );

		// Send the calendar data as JSON response
		wp_send_json_success( $calendar_data );
	}

	public function monthly_calendar_data( $year, $month ) {
		// Your PHP code for generating the calendar goes here

		$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$date          = 1;

		$calendar_html = ''; // Initialize variable to store calendar HTML

		for ( $i = 0; $i < 6; $i++ ) {
			$calendar_html .= '<tr>';
			for ( $j = 0; $j < 7; $j++ ) {
				if ( 0 === $i && gmdate( 'w', mktime( 0, 0, 0, $month, 1, $year ) ) > $j ) {
					$calendar_html .= '<td></td>';
				} elseif ( $date > $days_in_month ) {
					break;
				} else {
					$cell_date = $year . '-' . $month . '-' . $date;
					// Add today class if it is today.
					$today_date    = date( 'd' );
					$today_month   = date( 'm' );
					$today_year    = date( 'Y' );
					$today_class   = ( $date == $today_date && $month == $today_month && $year == $today_year )
						? 'today' : '';
					$calendar_html .= '<td class="' . esc_attr( $today_class ) . '">';
					$calendar_html .= '<div><span>' . esc_attr( $date ) . '</span>';
					// $calendar_html .= self::ec_event_by_date($cell_date); // Assuming this function is defined elsewhere
					$calendar_html .= '</div>';
					$calendar_html .= '</td>';
					$date++;
				}
			}
			$calendar_html .= '</tr>';
		}

		// Return the generated calendar HTML
		return $calendar_html;
	}


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

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page( 'Events Calendar', 'Events Calendar', 'manage_options', 'wpdb-crud', array( $this, 'admin_page' ), 'dashicons-database' );
	}

	/**
	 * Admin page.
	 *
	 * @return void
	 */
	public function admin_page() {
		global $wpdb;
		$total_rows = Database::get_instance()->get_wpdb_data_count();
		$results    = Database::get_instance()->get_wpdb_data();

		// On edit url load data to form.
		if ( isset( $_GET['action'] ) && 'edit' === $_GET['action'] ) { // phpcs:ignore
			if ( isset( $_GET['id'] ) ) { // phpcs:ignore
				$id   = sanitize_text_field( wp_unslash( $_GET['id'] ) ); // phpcs:ignore
				$post = Database::get_instance()->get_post_by_id( $id );
				self::render( 'layout', compact( 'results', 'total_rows', 'post' ) );
			} else {
				return;
			}
		} else {
			self::render( 'layout', compact( 'results', 'total_rows' ) );
		}
	}


}

