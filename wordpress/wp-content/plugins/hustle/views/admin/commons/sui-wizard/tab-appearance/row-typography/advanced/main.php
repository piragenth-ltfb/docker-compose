<?php
/**
 * Advanced typography settings.
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

		<h4 class="sui-settings-label sui-dark" style="margin-bottom: 10px; font-size: 13px;"><?php esc_html_e( 'Advanced', 'hustle' ); ?></h4>

		<?php
		echo '<div class="sui-row">';

			// SETTINGS: Line Height.
			echo '<div class="sui-col-md-6">';
				$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/line-height', $args );
			echo '</div>';

			// SETTINGS: Letter Spacing.
			echo '<div class="sui-col-md-6">';
				$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/letter-spacing', $args );
			echo '</div>';

		echo '</div>';

		echo '<div class="sui-row">';

			// SETTINGS: Text Transform.
			echo '<div class="sui-col-md-6">';
				$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/text-transform', $args );
			echo '</div>';

			// SETTINGS: Text Decoration.
			echo '<div class="sui-col-md-6">';
				$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/text-decoration', $args );
			echo '</div>';

		echo '</div>';

		if ( isset( $spacing ) && true === $spacing ) {

			echo '<div class="sui-row">';

				// SETTINGS: Top Spacing.
				echo '<div class="sui-col-md-6">';
					$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/spacing-top', $args );
				echo '</div>';

				// SETTINGS: Bottom Spacing.
				echo '<div class="sui-col-md-6">';
					$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/spacing-bottom', $args );
				echo '</div>';

			echo '</div>';

		}
		?>

	</div>

</div>