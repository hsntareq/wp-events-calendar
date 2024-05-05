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
			<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=event' ) ); ?>" class="page-title-action">
				<?php esc_attr_e( 'Add New Event', 'events-calendar' ); ?>
			</a>
		</div>
		<div class="right ec-row">
			<button class="button button-secondary ec-prev"
				id="previous-button"><?php esc_attr_e( 'Prev', 'events-calendar' ); ?></button>
			<h2 id="calendar-heading">
				<?php echo esc_attr( wp_date( 'M' ) . ', ' . wp_date( 'Y' ) ); ?>
			</h2>
			<button class="button button-secondary ec-next"
				id="next-button"><?php esc_attr_e( 'Next', 'events-calendar' ); ?></button>
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
			<!-- $this->generate_monthly_calendar( wp_date( 'Y' ), wp_date( 'm' ) ); -->
			<tbody id="calendar-body"></tbody>
		</table>
	</div>
</div>
