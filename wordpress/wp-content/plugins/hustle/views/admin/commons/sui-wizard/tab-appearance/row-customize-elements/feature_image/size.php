<?php
/**
 * Markup the Feature Image size in for both mobile and desktop.
 *
 * @since 4.3.0
 * @package Hustle
 */

$width_name = 'feature_image_width';
$width_unit = 'feature_image_width_unit';

$height_name = 'feature_image_height' . ( empty( $device ) ? '' : '_mobile' );
$height_unit = 'feature_image_height_unit' . ( empty( $device ) ? '' : '_mobile' );

$units = array(
	'px' => 'px',
	'%'  => '%',
	'vw' => 'vw',
	'vh' => 'vh',
);

$size_options = array(
	'custom' => esc_html__( 'Custom', 'hustle' ),
	'30'     => '30%',
	'40'     => '40%',
	'50'     => '50%',
	'60'     => '60%',
	'70'     => '70%',
);
?>

<div id="hustle-feature-image-size<?php echo empty( $device ) ? '' : '-mobile'; ?>-settings-row" class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Size', 'hustle' ); ?></h5>

		<?php if ( empty( $device ) ) : ?>
			<p id="hustle-feature-image-desktop-width-description" class="sui-description" style="margin-bottom: 0; display: none;">
				<?php esc_html_e( 'Choose one of the predefined size options or use the custom option for granular control over your feature image size.', 'hustle' ); ?>
			</p>
			<p id="hustle-feature-image-desktop-height-description" class="sui-description" style="margin-bottom: 0;">
				<?php esc_html_e( 'Use the custom option for granular control over your feature image size.', 'hustle' ); ?>
			</p>
		<?php else : ?>
			<p class="sui-description"><?php esc_html_e( 'The feature image takes 100% width on mobile. However, you can customize the height of your feature image below.', 'hustle' ); ?></p>
		<?php endif; ?>

		<?php if ( empty( $device ) ) : ?>
			<div id="hustle-<?php echo esc_attr( $width_name ); ?>-row" class="hui-fields-row" style="margin-top: 20px;">

				<div class="hui-fields-col">

					<div class="sui-form-field">

						<label for="hustle-feature_image_width_option" id="hustle-feature_image_width_option-label" class="sui-label"><?php esc_html_e( 'Width', 'hustle' ); ?></label>

						<?php
						Hustle_Layout_Helper::get_html_for_options(
							array(
								array(
									'type'       => 'select',
									'name'       => 'feature_image_width_option',
									'options'    => $size_options,
									'id'         => 'hustle-feature_image_width_option',
									'selected'   => $size_options['custom'],
									'attributes' => array(
										'aria-labelledby' => 'hustle-feature_image_width_option-label',
									),
								),
							)
						);
						?>

					</div>

				</div>

				<div class="hui-fields-col" data-size="100">

					<div class="sui-form-field hui-select-align--desktop-right" style="margin-bottom: 5px;">

						<label for="hustle-<?php echo esc_attr( $width_unit ); ?>" id="hustle-<?php echo esc_attr( $width_unit ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Pick a unit from the options list', 'hustle' ); ?></label>

						<?php
						Hustle_Layout_Helper::get_html_for_options(
							array(
								array(
									'type'       => 'select',
									'name'       => $width_unit,
									'options'    => $units,
									'id'         => 'hustle-' . $width_unit,
									'class'      => 'sui-inlabel sui-select sui-select-sm sui-select-inline',
									'selected'   => $settings[ $width_unit ],
									'attributes' => array(
										'data-width'      => 50,
										'data-attribute'  => $width_unit,
										'aria-labelledby' => 'hustle-' . $width_unit . '-label',
									),
								),
							)
						);
						?>

					</div>

					<div class="sui-form-field">

						<label class="sui-screen-reader-text"><?php esc_html_e( 'Insert a value in the selected unit for width.', 'hustle' ); ?></label>

						<?php
						Hustle_Layout_Helper::get_html_for_options(
							array(
								array(
									'type'       => 'number',
									'name'       => $width_name,
									'value'      => $settings[ $width_name ],
									'min'        => '0',
									'id'         => 'hustle-' . $width_name,
									'attributes' => array(
										'data-attribute'  => $width_name,
										'aria-labelledby' => '',
									),
								),
							)
						);
						?>

					</div>

				</div>

			</div>
		<?php endif; ?>

		<div id="hustle-<?php echo esc_attr( $height_name ); ?>-row" class="hui-fields-row" style="margin-top: 20px;">

			<div class="hui-fields-col">

				<div class="sui-form-field sui-input-sm" style="min-width: 100px;">

					<?php
					$this->render(
						'admin/global/components/select-units',
						array(
							'label'         => __( 'Height', 'hustle' ),
							'name'          => $height_unit,
							'selected'      => $settings[ $height_unit ],
							'exclude_units' => array( 'vh', 'vw', '%' ),
						)
					);
					?>

					<?php
					Hustle_Layout_Helper::get_html_for_options(
						array(
							array(
								'type'       => 'number',
								'name'       => $height_name,
								'min'        => '0',
								'value'      => $settings[ $height_name ],
								'id'         => 'hustle-' . $height_name,
								'attributes' => array(
									'data-attribute'  => $height_name,
									'aria-labelledby' => 'hustle-' . $height_name . '-label',
								),
							),
						)
					);
					?>

				</div>

			</div>

		</div>

	</div>

</div>