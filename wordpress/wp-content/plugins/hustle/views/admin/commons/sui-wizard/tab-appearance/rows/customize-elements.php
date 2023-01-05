<?php
/**
 * Markup the Customize Elements section for both mobile and desktop.
 *
 * @uses ../row-customize-elements/background-image.php
 * @uses ../row-customize-elementsclose-icon.php
 * @uses ../row-customize-elements/cta-buttons.php
 * @uses ../row-customize-elements/feature-image.php
 * @uses ../row-customize-elements/optin-form.php
 *
 * @since 4.3.0
 * @package Hustle
 */

$customize_elements = array(
	array(
		'show'        => true,
		'label'       => esc_html__( 'Feature Image', 'hustle' ),
		'file'        => 'feature-image',
		'prop_prefix' => 'feature_image',
		'row_name'    => empty( $device ) ? '' : 'feature_image',
	),
	array(
		'show'        => true,
		'label'       => esc_html__( 'Background Image', 'hustle' ),
		'file'        => 'background-image',
		'prop_prefix' => 'background_image',
		'row_name'    => 'background_image',
	),
	array(
		'show'        => true,
		'label'       => esc_html__( 'Call to Action', 'hustle' ),
		'file'        => 'cta-buttons',
		'prop_prefix' => 'cta_buttons',
		'row_name'    => 'show_cta',
	),
	array(
		'show'        => $is_optin,
		'label'       => esc_html__( 'Opt-in Form', 'hustle' ),
		'file'        => 'optin-form',
		'prop_prefix' => 'optin_form',
		'row_name'    => 'optin_form',
	),
);
?>

<div class="sui-box-settings-row hustle-appearance-customize-elements-row">

	<div class="sui-box-settings-col-1">

		<h3 class="sui-settings-label"><?php esc_html_e( 'Customize Elements', 'hustle' ); ?></h3>

		<p class="sui-description"><?php esc_html_e( 'Adjust the appearance of your pop-up elements as per your liking.', 'hustle' ); ?></p>

	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-accordion">

			<?php foreach ( $customize_elements as $element ) { ?>

				<?php if ( true === $element['show'] ) { ?>

					<div class="sui-accordion-item" data-name="<?php echo esc_attr( $element['row_name'] ); ?>">

						<div class="sui-accordion-item-header">
							<div class="sui-accordion-item-title">
								<?php echo esc_html( $element['label'] ); ?>
								<button class="sui-button-icon sui-accordion-open-indicator" aria-label="<?php esc_html_e( 'Click to open or close the item content' ); ?>">
									<i class="sui-icon-chevron-down" aria-hidden="true"></i>
								</button>
							</div>
						</div><?php // END .sui-accordion-item-header. ?>

						<div class="sui-accordion-item-body">

							<?php
							$this->render(
								'admin/commons/sui-wizard/tab-appearance/row-customize-elements/' . $element['file'],
								array(
									'settings'           => $settings,
									'key'                => $element['prop_prefix'],
									'is_optin'           => $is_optin,
									'device'             => isset( $device ) ? $device : false,
									'smallcaps_singular' => $smallcaps_singular,
								)
							);
							?>

						</div><?php // END .sui-accordion-item-body. ?>

					</div>

				<?php } ?>

			<?php } ?>

		</div>

	</div>

</div>