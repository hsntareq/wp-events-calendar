<?php
/**
 * WPDB CRUD layout.
 *
 * @package wordpress-plugin
 */

?>
<div class="wrap events-calendar-wrap">
	<div class="ec-row">
		<div class="left">
			<h1 class="wp-heading-inline"><?php esc_attr_e( 'Events Calendar', 'events-calendar' ); ?></h1>
			<a href="https://wplugins.test/wp-admin/post-new.php" class="page-title-action">Add New Event</a>
		</div>
		<div class="right ec-row">
			<button class="button button-secondary ec-prev" id="next-button">Prev</button>
			<h2>April, 2024</h2>
			<button class="button button-secondary ec-next" id="previous-button">Next</button>
		</div>
	</div>
	<hr class="wp-header-end">
	<div class="events-calendar-body">
		<table>
			<thead>
				<tr>
					<th><?php esc_attr_e( 'Sun', 'events-calendar' ); ?></th>
					<th><?php esc_attr_e( 'Mon', 'events-calendar' ); ?></th>
					<th><?php esc_attr_e( 'Tue', 'events-calendar' ); ?></th>
					<th><?php esc_attr_e( 'Wed', 'events-calendar' ); ?></th>
					<th><?php esc_attr_e( 'Thu', 'events-calendar' ); ?></th>
					<th><?php esc_attr_e( 'Fri', 'events-calendar' ); ?></th>
					<th><?php esc_attr_e( 'Sat', 'events-calendar' ); ?></th>
				</tr>
			</thead>
			<tbody id="calendar-body">
				<!-- Calendar dates will be inserted here dynamically using JavaScript -->
				<!-- Calendar code using php  -->
				<?php

				$today         = new DateTime();
				$event_year    = $today->format( 'Y' );
				$month         = $today->format( 'm' );
				$days_in_month = cal_days_in_month( CAL_GREGORIAN, $month, $event_year );
				$date          = 1;

				// echo gmdate( 'w', mktime( 0, 0, 0, $month, 4, $event_year ) );

				for ( $i = 0; $i < 6; $i++ ) {
					echo '<tr>';
					for ( $j = 0; $j < 7; $j++ ) {
						if ( 0 === $i && gmdate( 'w', mktime( 0, 0, 0, $month, 1, $event_year ) ) > $j ) {
							echo '<td></td>';
						} elseif ( $date > $days_in_month ) {
							break;
						} else {
							$cell_date = $event_year . '-' . $month . '-' . $date;
							// Add today class if it is today.
							$today_date  = $today->format( 'd' );
							$today_month = $today->format( 'm' );
							$today_year  = $today->format( 'Y' );
							$today_class = ( $date == $today_date && $month == $today_month && $event_year == $today_year )
								? 'today' : '';
							echo '<td class="' . esc_attr( $today_class ) . '">';
							echo '<div><span>' . esc_attr( $date ) . '</span>';
							// echo $cell_date;
							echo self::ec_event_by_date( $cell_date ); //.
							echo '</div>';
							echo '</td>';
							$date++;
						}
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	</div>

	<script>
		// Function to generate calendar


		// Call the function to generate the calendar
		// generateCalendar();
	</script>
</div>
