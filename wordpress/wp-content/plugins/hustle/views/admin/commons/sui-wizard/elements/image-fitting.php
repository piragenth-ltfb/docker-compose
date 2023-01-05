<?php
/**
 * Template for Feature image fitting options for both mobile and desktop.
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix = $device ? '_' . $device : '';

$fitting = $key . '_fit' . $device_suffix;

$horizontal_position = $key . '_horizontal_position' . $device_suffix;
$horizontal_value    = $key . '_horizontal_value' . $device_suffix;
$horizontal_unit     = $key . '_horizontal_unit' . $device_suffix;

$vertical_position = $key . '_vertical_position' . $device_suffix;
$vertical_value    = $key . '_vertical_value' . $device_suffix;
$vertical_unit     = $key . '_vertical_unit' . $device_suffix;

$fitting_options = array(
	'cover'   => array(
		'name'     => esc_html__( 'Cover', 'hustle' ),
		'settings' => true,
	),
	'fill'    => array(
		'name'     => esc_html__( 'Fit', 'hustle' ),
		'settings' => false,
	),
	'contain' => array(
		'name'     => esc_html__( 'Contain', 'hustle' ),
		'settings' => true,
	),
	'none'    => array(
		'name'     => esc_html__( 'None', 'hustle' ),
		'settings' => false,
	),
);

$horizontal_options = array(
	'left'   => array(
		'name' => esc_html__( 'Left', 'hustle' ),
	),
	'center' => array(
		'name' => esc_html__( 'Center', 'hustle' ),
	),
	'right'  => array(
		'name' => esc_html__( 'Right', 'hustle' ),
	),
	'custom' => array(
		'name' => esc_html__( 'Custom', 'hustle' ),
	),
);

$vertical_options = array(
	'top'    => array(
		'name' => esc_html__( 'Top', 'hustle' ),
	),
	'center' => array(
		'name' => esc_html__( 'Vertically centered', 'hustle' ),
	),
	'bottom' => array(
		'name' => esc_html__( 'Bottom', 'hustle' ),
	),
	'custom' => array(
		'name' => esc_html__( 'Custom', 'hustle' ),
	),
);

$units = array(
	'px' => 'px',
	'%'  => '%',
);
?>

<label class="sui-label"><?php esc_html_e( 'Fitting', 'hustle' ); ?></label>

<div class="sui-tabs sui-side-tabs">

	<?php foreach ( $fitting_options as $value => $option ) { ?>

		<input
			type="radio"
			name="<?php echo esc_attr( $fitting ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			id="hustle-<?php echo esc_attr( $fitting ); ?>--<?php echo esc_attr( $value ); ?>"
			class="sui-screen-reader-text hustle-tabs-option"
			data-attribute="<?php echo esc_attr( $fitting ); ?>"
			aria-hidden="true"
			tabindex="-1"
			<?php checked( $settings[ $fitting ], $value ); ?>
		/>

	<?php } ?>

	<div role="tablist" class="sui-tabs-menu">

		<?php foreach ( $fitting_options as $value => $option ) { ?>

			<button
				role="tab"
				type="button"
				id="tab-<?php echo esc_attr( $fitting ); ?>--<?php echo esc_attr( $value ); ?>"
				class="sui-tab-item"
				data-label-for="hustle-<?php echo esc_attr( $fitting ); ?>--<?php echo esc_attr( $value ); ?>"
				<?php echo ( true === $option['settings'] ) ? ' aria-controls="tab-content-' . esc_attr( $fitting ) . '-settings"' : ''; ?>
				aria-selected="false"
				tabindex="-1"
			><?php echo esc_html( $option['name'] ); ?></button>

		<?php } ?>

	</div>

	<div class="sui-tabs-content">

		<div
			role="tabpanel"
			tabindex="0"
			id="tab-content-<?php echo esc_attr( $fitting ); ?>-settings"
			class="sui-tab-content"
			style="margin-top: 20px;"
			aria-label="<?php esc_html_e( 'Image fitting and position settings', 'hustle' ); ?>"
		>

			<?php // FIELD: Horizontal Position. ?>
			<div class="sui-form-field" style="margin-bottom: 20px;">

				<label class="sui-label">
					<?php esc_html_e( 'Horizontal Position', 'hustle' ); ?>
					<?php
					Hustle_Layout_Helper::get_html_for_options(
						array(
							array(
								'type'       => 'select',
								'name'       => $horizontal_unit,
								'options'    => $units,
								'id'         => 'hustle-' . $horizontal_unit,
								'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
								'selected'   => $settings[ $horizontal_unit ],
								'attributes' => array(
									'data-width'     => '50',
									'data-attribute' => $horizontal_unit,
									'aria-label'     => esc_html__( 'Pick a unit from the options list', 'hustle' ),
								),
							),
						)
					);
					?>
				</label>

				<div class="sui-tabs sui-side-tabs hui-content-right">

					<?php foreach ( $horizontal_options as $value => $option ) { ?>

						<input
							type="radio"
							name="<?php echo esc_attr( $horizontal_position ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
							id="hustle-<?php echo esc_attr( $horizontal_position ); ?>--<?php echo esc_attr( $value ); ?>"
							class="sui-screen-reader-text hustle-tabs-option hustle-radio-with-dependency-to-disable"
							data-attribute="<?php echo esc_attr( $horizontal_position ); ?>"
							data-disable="<?php echo esc_attr( $horizontal_position ); ?>"
							aria-hidden="true"
							tabindex="-1"
							<?php checked( $settings[ $horizontal_position ], $value ); ?>
						/>

					<?php } ?>

					<div role="tablist" class="sui-tabs-menu">

						<?php foreach ( $horizontal_options as $value => $option ) { ?>

							<button
								role="tab"
								type="button"
								id="tab-<?php echo esc_attr( $horizontal_position ); ?>--<?php echo esc_attr( $value ); ?>"
								class="sui-tab-item"
								data-label-for="hustle-<?php echo esc_attr( $horizontal_position ); ?>--<?php echo esc_attr( $value ); ?>"
								aria-controls="tab-content-<?php echo esc_attr( $horizontal_position ); ?>-settings"
								aria-selected="false"
								tabindex="-1"
							>
								<?php if ( 'custom' === $value ) { ?>
									<?php echo esc_html( $option['name'] ); ?>
								<?php } else { ?>
									<span class="hui-tab-icon-position-<?php echo esc_attr( $value ); ?>" aria-hidden="true"></span>
									<span class="sui-screen-reader-text"><?php echo esc_html( $option['name'] ); ?></span>
								<?php } ?>
							</button>

						<?php } ?>

					</div>

					<div class="sui-tabs-content">

						<div
							role="tabpanel"
							tabindex="0"
							id="tab-content-<?php echo esc_attr( $horizontal_position ); ?>-settings"
							class="sui-tab-content"
							aria-label="<?php esc_html_e( 'Custom value for horizontal position', 'hustle' ); ?>"
						>

							<div class="sui-form-field">

								<label for="hustle-<?php echo esc_attr( $horizontal_value ); ?>" id="hustle-<?php echo esc_attr( $horizontal_value ); ?>-label" class="sui-screen-reader-text"><?php esc_html__( 'Insert a value in the selected unit for width.', 'hustle' ); ?></label>

								<?php
								$attributes = array(
									'data-attribute'       => $horizontal_value,
									'aria-labelledby'      => 'hustle-' . $horizontal_value . '-label',
									'data-disable-content' => $horizontal_position,
									'data-disable-off'     => 'custom',
								);

								Hustle_Layout_Helper::get_html_for_options(
									array(
										array(
											'type'       => 'number',
											'name'       => $horizontal_value,
											'value'      => $settings[ $horizontal_value ],
											'id'         => 'hustle-' . $horizontal_value,
											'attributes' => $attributes,
										),
									)
								);
								?>

							</div>

						</div>

					</div>

				</div>

			</div>

			<?php // FIELD: Vertical Position. ?>
			<div class="sui-form-field">

				<label class="sui-label">
					<?php esc_html_e( 'Vertical Position', 'hustle' ); ?>
					<?php
					Hustle_Layout_Helper::get_html_for_options(
						array(
							array(
								'type'       => 'select',
								'name'       => $vertical_unit,
								'options'    => $units,
								'id'         => 'hustle-' . $vertical_unit,
								'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
								'selected'   => $settings[ $vertical_unit ],
								'attributes' => array(
									'data-width'     => '50',
									'data-attribute' => $vertical_unit,
									'aria-label'     => esc_html__( 'Pick a unit from the options list', 'hustle' ),
								),
							),
						)
					);
					?>
				</label>

				<div class="sui-tabs sui-side-tabs hui-content-right">

					<?php foreach ( $vertical_options as $value => $option ) { ?>

						<input
							type="radio"
							name="<?php echo esc_attr( $vertical_position ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
							id="hustle-<?php echo esc_attr( $vertical_position ); ?>--<?php echo esc_attr( $value ); ?>"
							class="sui-screen-reader-text hustle-tabs-option hustle-radio-with-dependency-to-disable"
							data-attribute="<?php echo esc_attr( $vertical_position ); ?>"
							data-disable="<?php echo esc_attr( $vertical_position ); ?>"
							aria-hidden="true"
							tabindex="-1"
							<?php checked( $settings[ $vertical_position ], $value ); ?>
						/>

					<?php } ?>

					<div role="tablist" class="sui-tabs-menu">

						<?php foreach ( $vertical_options as $value => $option ) { ?>

							<button
								role="tab"
								type="button"
								id="tab-<?php echo esc_attr( $vertical_position ); ?>--<?php echo esc_attr( $value ); ?>"
								class="sui-tab-item"
								data-label-for="hustle-<?php echo esc_attr( $vertical_position ); ?>--<?php echo esc_attr( $value ); ?>"
								aria-controls="tab-content-<?php echo esc_attr( $vertical_position ); ?>-settings"
								aria-selected="false"
								tabindex="-1"
							>
								<?php if ( 'custom' === $value ) { ?>
									<?php echo esc_html( $option['name'] ); ?>
								<?php } else { ?>
									<span class="hui-tab-icon-position-<?php echo esc_attr( $value ); ?>" aria-hidden="true"></span>
									<span class="sui-screen-reader-text"><?php echo esc_html( $option['name'] ); ?></span>
								<?php } ?>
							</button>

						<?php } ?>

					</div>

					<div class="sui-tabs-content">

						<div
							role="tabpanel"
							tabindex="0"
							id="tab-content-<?php echo esc_attr( $vertical_position ); ?>-settings"
							class="sui-tab-content"
							aria-label="<?php esc_html_e( 'Custom value for vertical position', 'hustle' ); ?>"
						>

							<div class="sui-form-field">

								<label for="hustle-<?php echo esc_attr( $vertical_value ); ?>" id="hustle-<?php echo esc_attr( $vertical_value ); ?>-label" class="sui-screen-reader-text"><?php esc_html_e( 'Insert a value in the selected unit for the height.', 'hustle' ); ?></label>

								<?php
								$attributes = array(
									'data-attribute'       => $vertical_value,
									'aria-labelledby'      => 'hustle-' . $vertical_value . '-label',
									'data-disable-content' => $vertical_position,
									'data-disable-off'     => 'custom',
								);

								Hustle_Layout_Helper::get_html_for_options(
									array(
										array(
											'type'       => 'number',
											'name'       => $vertical_value,
											'value'      => $settings[ $vertical_value ],
											'id'         => 'hustle-' . $vertical_value,
											'attributes' => $attributes,
										),
									)
								);
								?>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>