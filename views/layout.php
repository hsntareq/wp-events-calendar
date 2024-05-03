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
			<button class="button button-secondary ec-prev">Prev</button>
			<h2>April, 2024</h2>
			<button class="button button-secondary ec-next">Next</button>
		</div>
	</div>
	<hr class="wp-header-end">
	<style>
		.events-calendar-wrap {}

		.ec-row {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 30px;
		}

		.ec-row h2 {
			margin: 0;
		}

		.events-calendar-body {
			padding-block-start: 30px;
			height: calc(100vh - 300px);
		}

		table {
			border-collapse: collapse;
			width: 100%;
			height: 100%;
		}

		th,
		td {
			border: 1px solid black;
			padding: 10px;
			text-align: center;
		}

		td.today {
			background-color: #dfdfdf;
		}

		th {
			background-color: #f2f2f2;
		}
	</style>
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
				for ( $i = 0; $i < 6; $i++ ) {
					echo '<tr>';
					for ( $j = 0; $j < 7; $j++ ) {
						if ( 0 === $i && gmdate( 'w', mktime( 0, 0, 0, $month, 1, $event_year ) ) > $j ) {
							echo '<td></td>';
						} elseif ( $date > $days_in_month ) {
							break;
						} else {
							// Add today class if it is today.
							$today_date  = $today->format( 'd' );
							$today_month = $today->format( 'm' );
							$today_year  = $today->format( 'Y' );
							$today_class = ( $date == $today_date && $month == $today_month && $event_year == $today_year )
								? 'today' : '';
							echo '<td class="' . esc_attr( $today_class ) . '">' . esc_attr( $date ) . '</td>';
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
		function generateCalendar(inYear = '', inMonth = '') {
			const today = new Date();
			const year = inYear ?? today.getFullYear();
			const month = inMonth ?? today.getMonth();
			const daysInMonth = new Date(year, month + 1, 0).getDate();

			const calendarBody = document.getElementById("calendar-body");

			let date = 1;
			for (let i = 0; i < 6; i++) {
				const row = document.createElement("tr");
				for (let j = 0; j < 7; j++) {
					if (i === 0 && j < new Date(year, month, 1).getDay()) {
						const cell = document.createElement("td");
						row.appendChild(cell);
					} else if (date > daysInMonth) {
						break;
					} else {
						const cell = document.createElement("td");
						cell.textContent = date;
						// add class if it is today
						if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
							cell.classList.add("today");
						}
						row.appendChild(cell);
						date++;
					}
				}
				calendarBody.appendChild(row);
			}
		}

		// Call the function to generate the calendar
		// generateCalendar();
	</script>
</div>
