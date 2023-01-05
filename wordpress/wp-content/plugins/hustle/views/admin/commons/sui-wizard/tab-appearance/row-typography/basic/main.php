<?php
/**
 * Basic typography settings.
 *
 * @uses ./font-family
 * @uses ./font-size
 * @uses ./font-weight
 * @uses ./alignment
 *
 * @package Hustle
 * @since 4.3.0
 */

$args = array(
	'settings'      => $settings,
	'property_key'  => $property_key,
	'device_suffix' => $device_suffix,
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h4 class="sui-settings-label sui-dark" style="margin-bottom: 10px; font-size: 13px;"><?php esc_html_e( 'Basic', 'hustle' ); ?></h4>

		<?php
		// SETTINGS: Font Family.
		// Desktop only.
		if ( empty( $device_suffix ) ) :
			$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/basic/font-family', $args );
		endif;
		?>

		<div class="sui-row">

			<?php // SETTINGS: Font Size. ?>
			<div class="sui-col-md-6">
				<?php $this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/basic/font-size', $args ); ?>
			</div>

			<?php // SETTINGS: Font Weight. ?>
			<div class="sui-col-md-6">
				<?php $this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/basic/font-weight', $args ); ?>
			</div>

		</div>

		<?php
		// SETTINGS: Alignment.
		if ( isset( $alignment ) && true === $alignment ) {
			$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/basic/alignment', $args );
		}
		?>

	</div>

</div>