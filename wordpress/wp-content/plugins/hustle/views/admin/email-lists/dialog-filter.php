<?php
/**
 * Title section.
 *
 * @var Hustle_Layout_Helper $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$count             = $this->admin->filtered_total_entries();
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

ob_start();
?>

<?php // FIELD: Keyword. ?>
<div class="sui-form-field">

	<label for="hustle-dialog-filter--keyword" class="sui-label"><?php esc_html_e( 'Email id has keyword', 'hustle' ); ?></label>

	<div class="sui-control-with-icon">

		<input
			type="text"
			name="search_email"
			value="<?php echo esc_attr( $search_email ); ?>"
			placeholder="<?php esc_html_e( 'E.g. gmail', 'hustle' ); ?>"
			id="hustle-dialog-filter--keyword"
			class="sui-form-control"
		/>

		<span class="sui-icon-magnifying-glass-search" aria-hidden="true"></span>

	</div>

</div>

<?php // FIELD: Sort by. ?>
<div class="sui-form-field">

	<label for="hustle-dialog-filter--sortby" class="sui-label"><?php esc_html_e( 'Sort by', 'hustle' ); ?></label>

	<select name="order_by" id="hustle-dialog-filter--sortby" class="sui-select">
		<?php foreach ( $order_by_array as $key => $name ) { ?>
			<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $order_by ); ?>><?php echo esc_html( $name ); ?></option>
		<?php } ?>
	</select>

</div>

<?php // FIELD: Date Range. ?>
<div class="sui-form-field has-calendar-inline" style="margin-bottom: 0;">

	<label for="hustle-dialog-filter--date" id="hustle-dialog-filter--date-label" class="sui-label"><?php esc_html_e( 'Submission date range', 'hustle' ); ?></label>

	<div class="sui-date">

		<span class="sui-icon-calendar" aria-hidden="true"></span>

		<input
			type="text"
			name="date_range"
			id="hustle-dialog-filter--date"
			value="<?php echo esc_attr( $date_range ); ?>"
			placeholder="<?php esc_html_e( 'Pick a date range', 'hustle' ); ?>"
			class="hustle-entries-filter-date sui-form-control"
			aria-labelledby="hustle-dialog-filter--date-label"
			autocomplete="off"
		/>

	</div>

</div>

<input type="hidden" name="page" value="hustle_entries" />
<input type="hidden" name="module_type" value="<?php echo esc_attr( $this->admin->get_module_type() ); ?>" />
<input type="hidden" name="module_id" value="<?php echo esc_attr( $this->admin->get_module_id() ); ?>" />

<?php
$body_content = ob_get_clean();

$attributes = array(
	'modal_id'        => 'filter-entries',
	'has_description' => false,
	'modal_size'      => 'lg',
	'sui_box_tag'     => 'form',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--40',
		'title'         => __( 'Filters', 'hustle' ),
		'title_classes' => 'sui-lg',
	),
	'body'            => array(
		'classes' => 'sui-content-center',
		'content' => $body_content,
	),
	'footer'          => array(
		'classes' => 'sui-content-separated',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost hustle-entries-clear-filter',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'is_submit' => true,
				'text'      => __( 'Apply', 'hustle' ),
			),
		),
	),
);

$this->render_modal( $attributes );
?>