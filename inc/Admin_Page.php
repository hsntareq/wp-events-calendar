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
		add_action( 'wp_ajax_monthly_calendar_data', array( $this, 'monthly_calendar_data' ) );
		add_action( 'wp_ajax_nopriv_monthly_calendar_data', array( $this, 'monthly_calendar_data' ) );
	}


	/**
	 * Monthly calendar generation by year and month.
	 *
	 * @param string $year Year.
	 * @param string $month Month.
	 *
	 * @return void
	 */
	public function monthly_calendar_data( $year = '', $month = '' ) {

		// Nonce and user capabilities verification.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ec_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You are not allowed to perform this action.' );
		}

		$_post = isset( $_POST['data'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['data'] ) ), true ) : array();
		$year  = isset( $_post['year'] ) ? intval( $_post['year'] ) : $year;
		$month = isset( $_post['month'] ) ? intval( $_post['month'] ) : $month;

		$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$date          = 1;
		$calendar_html = ''; // Initialize variable to store calendar HTML.

		for ( $i = 0; $i < 6; $i++ ) {
			$calendar_html .= '<tr>';
			for ( $j = 0; $j < 7; $j++ ) {
				if ( 0 === $i && gmdate( 'w', mktime( 0, 0, 0, $month, 1, $year ) ) > $j ) {
					$calendar_html .= '<td></td>';
				} elseif ( $date > $days_in_month ) {
					break;
				} else {
					$cell_date   = $year . '-' . $month . '-' . $date;
					$today_date  = gmdate( 'd' );
					$today_month = gmdate( 'm' );
					$today_year  = gmdate( 'Y' );

					// Add today class if it is today.
					$today_class    = ( $date === $today_date && $month === $today_month && $year === $today_year )
						? 'today' : '';
					$calendar_html .= '<td class="' . esc_attr( $today_class ) . '">';
					$calendar_html .= '<div><span>' . esc_attr( $date ) . '</span>';
					$calendar_html .= self::ec_event_by_date( $cell_date );
					$calendar_html .= '</div>';
					$calendar_html .= '</td>';
					$date++;
				}
			}
			$calendar_html .= '</tr>';
		}

		// Return the generated calendar HTML.
		wp_send_json( wp_kses_post( $calendar_html ) );
	}


	/**
	 * Generate monthly calendar for layout.
	 *
	 * @param mixed $year Year.
	 * @param mixed $month Month.
	 *
	 * @return void
	 */
	public function generate_monthly_calendar( $year, $month ) {
		// Initialize date objects.
		$today         = new \DateTime();
		$event_year    = $year;
		$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $event_year );
		$date          = 1;

		// Generate calendar HTML.
		$calendar_html = '';
		for ( $i = 0; $i < 6; $i++ ) {
			$calendar_html .= '<tr>';
			for ( $j = 0; $j < 7; $j++ ) {
				if ( 0 === $i && gmdate( 'w', mktime( 0, 0, 0, $month, 1, $event_year ) ) > $j ) {
					$calendar_html .= '<td></td>';
				} elseif ( $date > $days_in_month ) {
					break;
				} else {
					$cell_date = $event_year . '-' . $month . '-' . $date;
					// Add today class if it is today.
					$today_date     = $today->format( 'd' );
					$today_month    = $today->format( 'm' );
					$today_year     = $today->format( 'Y' );
					$today_class    = ( $date === $today_date && $month === $today_month && $event_year === $today_year )
						? 'today' : '';
					$calendar_html .= '<td class="' . esc_attr( $today_class ) . '">';
					$calendar_html .= '<div><span>' . esc_attr( $date ) . '</span>';
					$calendar_html .= self::ec_event_by_date( $cell_date );
					$calendar_html .= '</div>';
					$calendar_html .= '</td>';
					$date++;
				}
			}
			$calendar_html .= '</tr>';
		}

		echo wp_kses_post( $calendar_html );
	}

	/**
	 * Retrive events by date.
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

		$html = '';
		// Check if there are any events for the given date.
		if ( $events_query->have_posts() ) {
			$html .= '<ul>';
			while ( $events_query->have_posts() ) {
				$events_query->the_post();
				$event_time = get_post_meta( get_the_ID(), 'event_time', true );
				$event_time = gmdate( 'g:i A', strtotime( $event_time ) );

				// Display event title or other relevant information.
				$html .= '<li><a href="' . get_the_permalink() . '">' . esc_attr( $event_time ) . ': ' . get_the_title() . '</a>';
				$html .= '<a class="edit" href="' . get_edit_post_link( get_the_ID() ) . '">✎</a>';
				$html .= '</li>';
			}
			$html .= '</ul>';
			wp_reset_postdata(); // Restore global post data.
		}

		return $html;
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page( __( 'Events Calendar' ), __( 'Events Calendar' ), 'manage_options', 'events-calendar', array( $this, 'admin_page' ), 'dashicons-database' );
	}

	/**
	 * Admin page.
	 *
	 * @return void
	 */
	public function admin_page() {
		self::render( 'layout' );
	}
}
