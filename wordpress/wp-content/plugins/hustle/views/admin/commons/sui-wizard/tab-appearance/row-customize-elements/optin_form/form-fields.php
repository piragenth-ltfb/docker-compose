<?php
$device_suffix = $device ? '_' . $device : '';

$icon       = 'form_fields_icon' . $device_suffix;
$icons_type = array(
	'none'     => esc_html__( 'No icon', 'hustle' ),
	'static'   => esc_html__( 'Static icon', 'hustle' ),
	'animated' => esc_html__( 'Animated icon', 'hustle' ),
);

$customize_proximity = 'customize_form_fields_proximity' . $device_suffix;
$proximity_unit      = 'form_fields_proximity_unit' . $device_suffix;
$proximity_value     = 'form_fields_proximity_value' . $device_suffix;

$proximity_list = array(
	'0' => esc_html__( 'Default', 'hustle' ),
	'1' => esc_html__( 'Custom', 'hustle' ),
);

$units = array(
	'px'  => 'px',
	'%'   => '%',
	'em'  => 'em',
	'rem' => 'rem',
	'vw'  => 'vw',
	'vh'  => 'vh',
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php echo esc_html_e( 'Form Fields', 'hustle' ); ?></h5>

		<p class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Customize the following form field options as per your liking.', 'hustle' ); ?></p>

		<?php // FIELD: Field icon. ?>
		<?php if ( empty( $device ) ) : ?>

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Field icon', 'hustle' ); ?></label>

				<div class="sui-tabs sui-side-tabs">

					<?php foreach ( $icons_type as $key => $name ) { ?>

						<input
							type="radio"
							name="<?php echo esc_attr( $icon ); ?>"
							value="<?php echo esc_attr( $key ); ?>"
							id="hustle-<?php echo esc_attr( $icon ); ?>--<?php echo esc_attr( $key ); ?>"
							class="sui-screen-reader-text hustle-tabs-option"
							data-attribute="<?php echo esc_attr( $icon ); ?>"
							aria-hidden="true"
							tabindex="-1"
							<?php checked( $settings[ $icon ], $key ); ?>
						/>

					<?php } ?>

					<div role="tablist" class="sui-tabs-menu">

						<?php foreach ( $icons_type as $key => $name ) { ?>

							<button
								role="tab"
								type="button"
								id="tab-<?php echo esc_attr( $icon ); ?>--<?php echo esc_attr( $key ); ?>"
								class="sui-tab-item"
								data-label-for="hustle-<?php echo esc_attr( $icon ); ?>--<?php echo esc_attr( $key ); ?>"
								aria-selected="false"
								tabindex="-1"
							><?php echo esc_html( $name ); ?></button>

						<?php } ?>

					</div>

				</div>

			</div>

		<?php endif; ?>

		<?php // FIELD: Field's proximity. ?>
		<div class="sui-form-field">

			<label class="sui-label"><?php esc_html_e( "Field's proximity", 'hustle' ); ?></label>

			<div class="sui-tabs sui-side-tabs hui-content-right">

				<?php foreach ( $proximity_list as $key => $name ) { ?>

					<input
						type="radio"
						name="<?php echo esc_attr( $customize_proximity ); ?>"
						value="<?php echo esc_attr( $key ); ?>"
						id="hustle-<?php echo esc_attr( $customize_proximity ); ?>--<?php echo esc_attr( $key ); ?>"
						class="sui-screen-reader-text hustle-tabs-option"
						data-attribute="<?php echo esc_attr( $customize_proximity ); ?>"
						aria-hidden="true"
						tabindex="-1"
						<?php checked( $settings[ $customize_proximity ], $key ); ?>
					/>

				<?php } ?>

				<div role="tablist" class="sui-tabs-menu">

					<?php foreach ( $proximity_list as $key => $name ) { ?>

						<button
							role="tab"
							type="button"
							id="tab-<?php echo esc_attr( $customize_proximity ); ?>--<?php echo esc_attr( $key ); ?>"
							class="sui-tab-item"
							data-label-for="hustle-<?php echo esc_attr( $customize_proximity ); ?>--<?php echo esc_attr( $key ); ?>"
							<?php echo ( 1 === $key ) ? ' aria-controls="tab-content-' . esc_attr( $customize_proximity ) . '-settings"' : ''; ?>
							aria-selected="false"
							tabindex="-1"
						><?php echo esc_html( $name ); ?></button>

					<?php } ?>

				</div>

				<div class="sui-tabs-content" style="margin-top: -27px;">

					<div
						role="tabpanel"
						tabindex="0"
						id="tab-content-<?php echo esc_attr( $customize_proximity ); ?>-settings"
						class="sui-tab-content"
						aria-label="<?php esc_html_e( 'Fields separation settings', 'hustle' ); ?>"
						hidden
					>

						<?php
						$this->render(
							'admin/global/components/select-units',
							array(
								'label'         => __( 'Gap', 'hustle' ),
								'name'          => $proximity_unit,
								'class'         => 'sui-select sui-select-inline sui-select-sm sui-inlabel',
								'selected'      => $settings[ $proximity_unit ],
								'exclude_units' => array( 'vh', 'vw' ),
								'attributes'    => array(
									'data-width' => '50',
								),
							)
						);
						?>

						<?php
						Hustle_Layout_Helper::get_html_for_options(
							array(
								array(
									'type'       => 'number',
									'name'       => $proximity_value,
									'min'        => 0,
									'value'      => $settings[ $proximity_value ],
									'id'         => 'hustle-' . $proximity_value,
									'attributes' => array(
										'data-attribute' => $proximity_value,
										'aria-label'     => '',
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

</div>