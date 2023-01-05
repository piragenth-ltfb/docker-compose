<?php
/**
 * Title section.
 *
 * @var Hustle_Layout_Helper $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$total             = $this->admin->filtered_total_entries();
$is_filter_enabled = $this->admin->is_filter_box_enabled();
$date_range        = '';
$date_created      = isset( $this->admin->filters['date_created'] ) ? $this->admin->filters['date_created'] : '';

if ( is_array( $date_created ) && isset( $date_created[0] ) && isset( $date_created[1] ) ) {
	$date_created[0] = date( 'm/d/Y', strtotime( $date_created[0] ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	$date_created[1] = date( 'm/d/Y', strtotime( $date_created[1] ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
	$date_range      = implode( ' - ', $date_created );
}

$search_email = isset( $this->admin->filters['search_email'] ) ? $this->admin->filters['search_email'] : '';
$order_by     = isset( $this->admin->order['order_by'] ) ? $this->admin->order['order_by'] : '';

$order_by_array = array(
	'entries.entry_id'     => esc_html__( 'Id', 'hustle' ),
	'entries.date_created' => esc_html__( 'Date submitted', 'hustle' ),
);
?>

<div class="hui-box-actions
<?php
if ( isset( $actions_class ) ) {
	echo ' ' . esc_attr( $actions_class );}
?>
">

	<div class="hui-actions-bar">

		<?php // ELEMENT: Bulk Actions. ?>
		<form method="post" class="hustle-bulk-actions-container hui-bulk-actions">

			<select
				name="hustle_action"
				id="hustle-select-bulk-actions-<?php echo $is_bottom ? 'bottom' : 'top'; ?>"
				class="sui-select sui-select-sm"
				data-placeholder="<?php esc_html_e( 'Bulk actions', 'hustle' ); ?>"
			>
				<option></option>
				<option value="delete-all"><?php esc_html_e( 'Delete', 'hustle' ); ?></option>
			</select>

			<input
				type="hidden"
				name="hustle_nonce"
				value="<?php echo esc_attr( wp_create_nonce( 'hustle_entries_request' ) ); ?>"
			/>

			<button
				class="hustle-bulk-apply-button sui-button"
				data-title="<?php esc_html_e( 'Delete Entries', 'hustle' ); ?>"
				data-description="<?php esc_html_e( 'Are you sure you wish to permanently delete these entries?', 'hustle' ); ?>"
				<?php disabled( true ); ?>
			>
				<?php esc_html_e( 'Apply', 'hustle' ); ?>
			</button>

		</form>

		<?php // ELEMENT: Pagination (Desktop). ?>
		<div class="hui-pagination hui-pagination-desktop">

			<?php
			$entries_per_page = $this->admin->get_per_page();

			$this->render(
				'admin/commons/pagination',
				array(
					'total'            => $total,
					'entries_per_page' => $entries_per_page,
					'filterclass'      => 'hustle-open-inline-filter',
					'filter'           => array(),
				)
			);
			?>

		</div>

	</div>

	<div class="sui-pagination-filter">

		<form method="get">

			<input type="hidden" name="page" value="hustle_entries">
			<input type="hidden" name="module_type" value="<?php echo esc_attr( $this->admin->get_module_type() ); ?>">
			<input type="hidden" name="module_id" value="<?php echo esc_attr( $this->admin->get_module_id() ); ?>">

			<div class="sui-row">

				<div class="sui-col-md-6">

					<div class="sui-form-field">
						<label class="sui-label"><?php esc_html_e( 'Email id has keyword', 'hustle' ); ?></label>
						<div class="sui-control-with-icon">
							<input type="text"
								name="search_email"
								placeholder="<?php esc_html_e( 'E.g. gmail', 'hustle' ); ?>"
								class="sui-form-control"
								value="<?php echo esc_attr( $search_email ); ?>" />
							<span class="sui-icon-magnifying-glass-search" aria-hidden="true"></span>
						</div>
					</div>

				</div>

				<div class="sui-col-md-6">

					<div class="sui-form-field">
						<label class="sui-label"><?php esc_html_e( 'Sort by', 'hustle' ); ?></label>
						<select name="order_by" id="hustle-select-order-by-<?php echo $is_bottom ? 'bottom' : 'top'; ?>">
							<?php foreach ( $order_by_array as $key => $name ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $order_by ); ?>><?php echo esc_html( $name ); ?></option>
							<?php } ?>
						</select>
					</div>

				</div>

			</div>

			<div class="sui-row">

				<div class="sui-col-md-6">

					<div class="sui-form-field">
						<label class="sui-label"><?php esc_html_e( 'Conversion date range', 'hustle' ); ?></label>
						<div class="sui-date">
							<span class="sui-icon-calendar" aria-hidden="true"></span>
							<input type="text"
								name="date_range"
								value="<?php echo esc_attr( $date_range ); ?>"
								placeholder="<?php esc_html_e( 'Pick a date range', 'hustle' ); ?>"
								class="hustle-entries-filter-date sui-form-control"
								autocomplete="off"
								/>
						</div>
					</div>

				</div>

			</div>

			<div class="sui-filter-footer">

				<button type="button" class="sui-button sui-button-ghost hustle-entries-clear-filter">
					<?php esc_html_e( 'Clear Filters', 'hustle' ); ?>
				</button>

				<button class="sui-button">
					<?php esc_html_e( 'Apply', 'hustle' ); ?>
				</button>

			</div>

		</form>

	</div>

	<?php
	$get_order_by = filter_input( INPUT_GET, 'order_by', FILTER_SANITIZE_STRING );
	$ordered      = ! is_null( $get_order_by ) && key_exists( $get_order_by, $order_by_array );

	if ( $ordered || $search_email || $date_range ) {
		?>

		<div class="sui-pagination-filters-list">

			<label class="sui-label"><?php esc_html_e( 'Active filters', 'hustle' ); ?></label>

			<div class="sui-pagination-active-filters">

				<?php if ( $search_email ) { ?>
					<span class="sui-active-filter">
						<?php esc_html_e( 'Has keyword:', 'hustle' ); ?> <?php echo esc_html( $search_email ); ?>
					<span class="sui-active-filter-remove" data-filter="search_email" role="button"><span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this filter', 'hustle' ); ?></span></span></span>
				<?php } ?>

				<?php if ( $ordered ) { ?>
					<span class="sui-active-filter">
						<?php esc_html_e( 'Sort by:', 'hustle' ); ?> <?php echo esc_html( $order_by_array[ $get_order_by ] ); ?>
					<span class="sui-active-filter-remove" data-filter="order_by" role="button"><span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this filter', 'hustle' ); ?></span></span></span>
				<?php } ?>

				<?php if ( $date_range ) { ?>
					<?php $date_range_to = str_replace( ' - ', __( ' to ', 'hustle' ), $date_range ); ?>
					<span class="sui-active-filter">
						<?php esc_html_e( 'Submission date range:', 'hustle' ); ?> <?php echo esc_html( $date_range_to ); ?>
					<span class="sui-active-filter-remove" data-filter="date_range" role="button"><span class="sui-screen-reader-text"><?php esc_html_e( 'Remove this filter', 'hustle' ); ?></span></span></span>
				<?php } ?>

			</div>

		</div>

	<?php } ?>

</div>