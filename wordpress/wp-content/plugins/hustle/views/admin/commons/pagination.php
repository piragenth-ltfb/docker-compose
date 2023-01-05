<?php
/**
 * Title section.
 *
 * @package Hustle
 * @since 4.0.0
 */
?>

<div class="sui-pagination-wrap">

	<span class="sui-pagination-results"><?php printf( esc_html( _n( '%d result', '%d results', $total, 'hustle' ) ), esc_html( $total ) ); ?></span>

	<?php
	$args = array(
		'total'            => $total,
		'entries_per_page' => $entries_per_page,
	);
	if ( ! empty( $section ) ) {
		$args['section'] = $section;
	}
	$this->render( 'admin/commons/sui-listing/elements/pagination-list', $args );

	if ( ! empty( $filterclass ) ) {
		?>
		<button class="sui-button-icon sui-button-outlined <?php echo esc_attr( $filterclass ); ?>">
			<i class="sui-icon-filter" aria-hidden="true"></i>
			<span class="sui-screen-reader-text"><?php echo esc_html__( 'Filter results', 'hustle' ); ?></span>
		</button>
	 <?php } ?>

</div>