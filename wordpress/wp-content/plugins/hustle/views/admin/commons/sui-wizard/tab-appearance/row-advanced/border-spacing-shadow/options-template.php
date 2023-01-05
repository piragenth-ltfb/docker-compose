<?php
/**
 * Template for Border, spacing, shadow options.
 *
 * @since 4.3.0
 * @package Hustle
 */

// Properties shared along the modules' elements.
// Radius and Shadow are set outside the loop.
$properties = array(
	'margin',
	'padding',
	'border',
);

// Options shared along the elements' properties.
// For example: margin left, padding left, border left, and so on.
$property_options = array(
	'are_sides_linked',
	'unit',
	'top',
	'right',
	'bottom',
	'left',
);

$is_desktop = empty( $device );

$device_suffix = $is_desktop ? '' : '_' . $device;

// Radius options. It shares only two options with other properties,
// so we're setting these right away instead of doing it in the loop.
$radius_are_sides_linked = $property_key . '_radius_are_sides_linked' . $device_suffix;
$radius_unit             = $property_key . '_radius_unit' . $device_suffix;
$radius_top_left         = $property_key . '_radius_top_left' . $device_suffix;
$radius_top_right        = $property_key . '_radius_top_right' . $device_suffix;
$radius_bottom_right     = $property_key . '_radius_bottom_right' . $device_suffix;
$radius_bottom_left      = $property_key . '_radius_bottom_left' . $device_suffix;

// Shadow options. It shares only one option with other properties,
// so we're setting these right away instead of doing it in the loop.
$shadow_unit   = $property_key . '_drop_shadow_unit' . $device_suffix;
$shadow_x      = $property_key . '_drop_shadow_x' . $device_suffix;
$shadow_y      = $property_key . '_drop_shadow_y' . $device_suffix;
$shadow_blur   = $property_key . '_drop_shadow_blur' . $device_suffix;
$shadow_spread = $property_key . '_drop_shadow_spread' . $device_suffix;

// Extra properties for Border.
$border_type = $property_key . '_border_type' . $device_suffix;

// Let's create the variables and module's property names dynamically.
foreach ( $properties as $property ) {

	foreach ( $property_options as $option ) {

		// We're using a variable for each combination of Property + Option. This will be its name.
		// For example: $margin_bottom, $padding_left, $border_right, and so on.
		$variable_name = $property . '_' . $option;

		// Take care if editing these. The properties names must match what's
		// defined in the default array under the Hustle_Meta_Base_Design class.
		$property_name = $property_key . '_' . $variable_name . $device_suffix;

		// Yep, a variable variable. Sorry. I'll refactor it. Promise.
		$$variable_name = $property_name;
	}
}

$properties_without_margin        = array( 'popup_cont', 'success_message', 'input', 'checkbox', 'layout_header', 'cta', 'layout_content', 'layout_footer', 'submit_button' );
$properties_without_padding       = array( 'embed_cont', 'error_message', 'nsa_link', 'checkbox', 'gdpr' );
$properties_without_border        = array( 'popup_cont', 'embed_cont', 'error_message', 'nsa_link' );
$properties_without_border_radius = array( 'popup_cont', 'embed_cont', 'error_message', 'nsa_link', 'main_content', 'cta_cont' );
$properties_without_shadow        = array( 'popup_cont', 'embed_cont', 'error_message', 'nsa_link', 'main_content', 'cta_cont', 'content_wrap', 'form_cont', 'gdpr', 'checkbox', 'layout_content' );

$units = array(
	'px' => 'px',
	'%'  => '%',
);
?>

<div class="sui-box">

	<div class="sui-box-body">

		<?php // ROW: Margin. ?>
		<?php if ( ! in_array( $property_key, $properties_without_margin, true ) ) : ?>

			<div class="sui-box-settings-row">

				<div class="sui-box-settings-col-2">

					<div class="hui-label-with-options">

						<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Margin', 'hustle' ); ?></h5>

						<div role="radiogroup" class="sui-form-field">

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Link all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--link" id="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--link-label" class="sui-screen-reader-text"><?php esc_html_e( 'Link margin values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $margin_are_sides_linked ); ?>"
									value="1"
									id="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--link"
									data-attribute="<?php echo esc_attr( $margin_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--link-label"
									data-link-fields
									<?php checked( $settings[ $margin_are_sides_linked ], '1' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-link sui-sm"></i></span>

							</label>

							<label class="sui-tooltip" data-tooltip="Unlink all corners">

								<span for="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--unlink" id="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--unlink-label" class="sui-screen-reader-text"><?php esc_html_e( 'Unlink margin values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $margin_are_sides_linked ); ?>"
									value="0"
									id="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--unlink"
									class="hustle-button-link-fields"
									data-attribute="<?php echo esc_attr( $margin_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $margin_are_sides_linked ); ?>--unlink-label"
									data-link-fields
									<?php checked( $settings[ $margin_are_sides_linked ], '0' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-unlink sui-sm"></i></span>

							</label>

						</div>

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_attr( $margin_unit ); ?>" id="hustle-<?php echo esc_attr( $margin_unit ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Pick container margin unit', 'hustle' ); ?></label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'select',
										'name'       => $margin_unit,
										'options'    => $units,
										'id'         => 'hustle-' . $margin_unit,
										'selected'   => $settings[ $margin_unit ],
										'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
										'attributes' => array(
											'data-width' => '50',
											'data-attribute' => $margin_unit,
											'aria-labelledby' => 'hustle-' . $margin_unit . '-label',
										),
									),
								)
							);
							?>

						</div>

					</div>

					<?php
					$this->render(
						'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/row-4-number-inputs',
						array(
							'settings'      => $settings,
							'linked_fields' => $margin_are_sides_linked,
							'is_desktop'    => $is_desktop,
							'options'       => array(
								array(
									'name' => __( 'Top', 'hustle' ),
									'slug' => $margin_top,
								),
								array(
									'name' => __( 'Right', 'hustle' ),
									'slug' => $margin_right,
								),
								array(
									'name' => __( 'Bottom', 'hustle' ),
									'slug' => $margin_bottom,
								),
								array(
									'name' => __( 'Left', 'hustle' ),
									'slug' => $margin_left,
								),

							),
						)
					);
					?>

				</div>

			</div>

		<?php endif; ?>

		<?php // ROW: Padding. ?>
		<?php if ( ! in_array( $property_key, $properties_without_padding, true ) ) : ?>

			<div class="sui-box-settings-row">

				<div class="sui-box-settings-col-2">

					<div class="hui-label-with-options">

						<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Padding', 'hustle' ); ?></h5>

						<div role="radiogroup" class="sui-form-field">

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Link all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--link" id="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--link-label" class="sui-screen-reader-text"><?php esc_html_e( 'Link padding values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $padding_are_sides_linked ); ?>"
									value="1"
									id="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--link"
									data-attribute="<?php echo esc_attr( $padding_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--link-label"
									data-link-fields
									<?php checked( $settings[ $padding_are_sides_linked ], '1' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-link sui-sm"></i></span>

							</label>

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Unlink all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--unlink" id="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--unlink-label" class="sui-screen-reader-text"><?php esc_html_e( 'Unlink padding values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $padding_are_sides_linked ); ?>"
									value="0"
									id="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--unlink"
									data-attribute="<?php echo esc_attr( $padding_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $padding_are_sides_linked ); ?>--unlink-label"
									data-link-fields
									<?php checked( $settings[ $padding_are_sides_linked ], '0' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-unlink sui-sm"></i></span>

							</label>

						</div>

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_attr( $padding_unit ); ?>" id="hustle-<?php echo esc_attr( $padding_unit ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Pick container padding unit', 'hustle' ); ?></label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'select',
										'name'       => $padding_unit,
										'options'    => $units,
										'id'         => 'hustle-' . $padding_unit,
										'selected'   => $settings[ $padding_unit ],
										'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
										'attributes' => array(
											'data-width' => '50',
											'data-attribute' => $padding_unit,
											'aria-labelledby' => 'hustle-' . $padding_unit . '-label',
										),
									),
								)
							);
							?>

						</div>

					</div>

					<?php
					$this->render(
						'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/row-4-number-inputs',
						array(
							'settings'      => $settings,
							'linked_fields' => $padding_are_sides_linked,
							'is_desktop'    => $is_desktop,
							'has_min'       => true,
							'options'       => array(
								array(
									'name' => __( 'Top', 'hustle' ),
									'slug' => $padding_top,
								),
								array(
									'name' => __( 'Right', 'hustle' ),
									'slug' => $padding_right,
								),
								array(
									'name' => __( 'Bottom', 'hustle' ),
									'slug' => $padding_bottom,
								),
								array(
									'name' => __( 'Left', 'hustle' ),
									'slug' => $padding_left,
								),

							),
						)
					);
					?>

				</div>

			</div>

		<?php endif; ?>

		<?php // ROW: Border. ?>
		<?php if ( ! in_array( $property_key, $properties_without_border, true ) ) : ?>

			<div class="sui-box-settings-row">

				<div class="sui-box-settings-col-2">

					<div class="hui-label-with-options">

						<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Border', 'hustle' ); ?></h5>

						<div role="radiogroup" class="sui-form-field">

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Link all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--link" id="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--link-label" class="sui-screen-reader-text"><?php esc_html_e( 'Link border values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $border_are_sides_linked ); ?>"
									value="1"
									id="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--link"
									data-attribute="<?php echo esc_attr( $border_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--link-label"
									data-link-fields
									<?php checked( $settings[ $border_are_sides_linked ], '1' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-link sui-sm"></i></span>

							</label>

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Unlink all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--unlink" id="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--unlink-label" class="sui-screen-reader-text"><?php esc_html_e( 'Unlink border values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $border_are_sides_linked ); ?>"
									value="0"
									id="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--unlink"
									data-attribute="<?php echo esc_attr( $border_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $border_are_sides_linked ); ?>--unlink-label"
									data-link-fields
									<?php checked( $settings[ $border_are_sides_linked ], '0' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-unlink sui-sm"></i></span>

							</label>

						</div>

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_attr( $border_unit ); ?>" id="hustle-<?php echo esc_attr( $border_unit ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Pick container border unit', 'hustle' ); ?></label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'select',
										'name'       => $border_unit,
										'options'    => $units,
										'id'         => 'hustle-' . $border_unit,
										'selected'   => $settings[ $border_unit ],
										'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
										'attributes' => array(
											'data-width' => '50',
											'data-attribute' => $border_unit,
											'aria-labelledby' => 'hustle-' . $border_unit . '-label',
										),
									),
								)
							);
							?>

						</div>

					</div>

					<?php
					$this->render(
						'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/row-4-number-inputs',
						array(
							'settings'      => $settings,
							'linked_fields' => $border_are_sides_linked,
							'is_desktop'    => $is_desktop,
							'has_min'       => true,
							'options'       => array(
								array(
									'name' => __( 'Top', 'hustle' ),
									'slug' => $border_top,
								),
								array(
									'name' => __( 'Right', 'hustle' ),
									'slug' => $border_right,
								),
								array(
									'name' => __( 'Bottom', 'hustle' ),
									'slug' => $border_bottom,
								),
								array(
									'name' => __( 'Left', 'hustle' ),
									'slug' => $border_left,
								),

							),
						)
					);
					?>

					<div class="sui-form-field" style="margin-bottom: 20px; margin-top: -10px;">

						<label for="hustle-<?php echo esc_attr( $border_type ); ?>" id="hustle-<?php echo esc_attr( $border_type ); ?>-label" class="sui-label"><?php esc_html_e( 'Border Type', 'hustle' ); ?></label>

						<?php
						Hustle_Layout_Helper::get_html_for_options(
							array(
								array(
									'type'       => 'select',
									'name'       => $border_type,
									'options'    => array(
										'solid'  => 'Solid',
										'dotted' => 'Dotted',
										'dashed' => 'Dashed',
										'double' => 'Double',
										'none'   => 'None',
									),
									'id'         => 'hustle-' . $border_type,
									'class'      => 'sui-select',
									'selected'   => $settings[ $border_type ],
									'attributes' => array(
										'data-attribute'  => $border_type,
										'aria-labelledby' => 'hustle-' . $border_type . '-label',
									),
								),
							)
						);
						?>

					</div>

					<span class="sui-description"><?php esc_html_e( 'Note: Set the color of the border in the Colors Palette area above.', 'hustle' ); ?></span>

				</div>

			</div>

		<?php endif; ?>

		<?php // ROW: Border Radius. ?>
		<?php if ( ! in_array( $property_key, $properties_without_border_radius, true ) ) : ?>

			<div class="sui-box-settings-row">

				<div class="sui-box-settings-col-2">

					<div class="hui-label-with-options">

						<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Border Radius', 'hustle' ); ?></h5>

						<div role="radiogroup" class="sui-form-field">

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Link all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--link" id="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--link-label" class="sui-screen-reader-text"><?php esc_html_e( 'Link border radius values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $radius_are_sides_linked ); ?>"
									value="1"
									id="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--link"
									data-attribute="<?php echo esc_attr( $radius_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--link-label"
									data-link-fields
									<?php checked( $settings[ $radius_are_sides_linked ], '1' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-link sui-sm"></i></span>

							</label>

							<label class="sui-tooltip" data-tooltip="<?php esc_html_e( 'Unlink all corners', 'hustle' ); ?>">

								<span for="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--unlink" id="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--unlink-label" class="sui-screen-reader-text"><?php esc_html_e( 'Unlink border radius values', 'hustle' ); ?></span>

								<input
									type="radio"
									name="<?php echo esc_attr( $radius_are_sides_linked ); ?>"
									value="0"
									id="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--unlink"
									data-attribute="<?php echo esc_attr( $radius_are_sides_linked ); ?>"
									aria-labelledby="hustle-<?php echo esc_attr( $radius_are_sides_linked ); ?>--unlink-label"
									data-link-fields
									<?php checked( $settings[ $radius_are_sides_linked ], '0' ); ?>
								/>

								<span aria-hidden="true"><i class="sui-icon-unlink sui-sm"></i></span>

							</label>

						</div>

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_attr( $radius_unit ); ?>" id="hustle-<?php echo esc_attr( $radius_unit ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Pick container border radius unit', 'hustle' ); ?></label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'select',
										'name'       => $radius_unit,
										'options'    => $units,
										'id'         => 'hustle-' . $radius_unit,
										'selected'   => $settings[ $radius_unit ],
										'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
										'attributes' => array(
											'data-width' => '50',
											'data-attribute' => $radius_unit,
											'aria-labelledby' => 'hustle-' . $radius_unit . '-label',
										),
									),
								)
							);
							?>

						</div>

					</div>

					<?php
					$this->render(
						'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/row-4-number-inputs',
						array(
							'settings'      => $settings,
							'linked_fields' => $radius_are_sides_linked,
							'is_desktop'    => $is_desktop,
							'has_min'       => true,
							'options'       => array(
								array(
									'name'         => __( 'Top Left', 'hustle' ),
									'slug'         => $radius_top_left,
									'label-nowrap' => true,
								),
								array(
									'name'         => __( 'Top Right', 'hustle' ),
									'slug'         => $radius_top_right,
									'label-nowrap' => true,
								),
								array(
									'name'         => __( 'Bottom Right', 'hustle' ),
									'slug'         => $radius_bottom_right,
									'label-nowrap' => true,
								),
								array(
									'name'         => __( 'Bottom Left', 'hustle' ),
									'slug'         => $radius_bottom_left,
									'label-nowrap' => true,
								),

							),
						)
					);
					?>

					<?php if ( 'checkbox' === $property_key ) { ?>
						<p class="sui-description"><?php esc_html_e( "Note: These settings won't affect radio field.", 'hustle' ); ?></p>
					<?php } ?>

				</div>

			</div>

		<?php endif; ?>

		<?php // ROW: Box Shadow. ?>
		<?php if ( ! in_array( $property_key, $properties_without_shadow, true ) ) : ?>

			<div class="sui-box-settings-row">

				<div class="sui-box-settings-col-2">

					<div class="hui-label-with-options">

						<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Box Shadow', 'hustle' ); ?></h5>

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_attr( $shadow_unit ); ?>" id="hustle-<?php echo esc_attr( $shadow_unit ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Pick container box shadow unit for values', 'hustle' ); ?></label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'select',
										'name'       => $shadow_unit,
										'options'    => $units,
										'id'         => 'hustle-' . $shadow_unit,
										'selected'   => $settings[ $shadow_unit ],
										'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
										'attributes' => array(
											'data-width' => '50',
											'data-attribute' => $shadow_unit,
											'aria-labelledby' => 'hustle-' . $shadow_unit . '-label',
										),
									),
								)
							);
							?>

						</div>

					</div>

					<?php
					$this->render(
						'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/row-4-number-inputs',
						array(
							'settings'   => $settings,
							'is_desktop' => $is_desktop,
							'options'    => array(
								array(
									'name' => __( 'X-offset', 'hustle' ),
									'slug' => $shadow_x,
								),
								array(
									'name' => __( 'Y-offset', 'hustle' ),
									'slug' => $shadow_y,
								),
								array(
									'name' => __( 'Blur', 'hustle' ),
									'slug' => $shadow_blur,
								),
								array(
									'name' => __( 'Spread', 'hustle' ),
									'slug' => $shadow_spread,
								),

							),
						)
					);
					?>

					<span class="sui-description" style="margin-top: -10px;"><?php esc_html_e( 'Note: Set the color of the shadow in the Colors Palette area above.', 'hustle' ); ?></span>

				</div>

			</div>

		<?php endif; ?>

	</div>

</div>