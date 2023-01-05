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

<?php
$entries_per_page = $this->admin->get_per_page();

$this->render(
	'admin/commons/pagination',
	array(
		'total'            => $total,
		'entries_per_page' => $entries_per_page,
		'filterclass'      => 'hustle-open-dialog-filter',
		'filter'           => array(),
	)
);