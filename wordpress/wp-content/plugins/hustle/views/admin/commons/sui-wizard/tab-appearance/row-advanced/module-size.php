<?php
/**
 * Module size settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$size_option       = ( isset( $device ) && '' !== $device ) ? 'customize_size_' . $device : 'customize_size';
$size_width_unit   = ( isset( $device ) && '' !== $device ) ? 'custom_width_unit_' . $device : 'custom_width_unit';
$size_width_value  = ( isset( $device ) && '' !== $device ) ? 'custom_width_' . $device : 'custom_width';
$size_height_unit  = ( isset( $device ) && '' !== $device ) ? 'custom_height_unit_' . $device : 'custom_height_unit';
$size_height_value = ( isset( $device ) && '' !== $device ) ? 'custom_height_' . $device : 'custom_height';
$vanilla_hide      = ( isset( $vanilla_hide ) ) ? $vanilla_hide : false;

$units = array(
	'px' => 'px',
	'%'  => '%',
	'vw' => 'vw',
	'vh' => 'vh',
);
?>

<?php
printf(
	'<div class="sui-form-field"%s>',
	$vanilla_hide ? ' data-toggle-content="use-vanilla"' : ''
);
?>

	<?php /* translators: module name capitalized in singular */ ?>
	<h4 class="sui-settings-label"><?php printf( esc_html__( '%s size', 'hustle' ), esc_html( $capitalize_singular ) ); ?></h4>

	<p class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( 'Choose whether you want to use the default pop-up size or a custom size.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

	<div class="sui-tabs sui-side-tabs">

		<input
			type="radio"
			value="0"
			name="<?php echo esc_attr( $size_option ); ?>"
			id="hustle-<?php echo esc_attr( $size_option ); ?>--default"
			class="sui-screen-reader-text hustle-tabs-option"
			data-attribute="<?php echo esc_attr( $size_option ); ?>"
			aria-hidden="true"
			tabindex="-1"
			<?php checked( $settings[ $size_option ], '0' ); ?>
		/>

		<input
			type="radio"
			value="1"
			name="<?php echo esc_attr( $size_option ); ?>"
			id="hustle-<?php echo esc_attr( $size_option ); ?>--custom"
			class="sui-screen-reader-text hustle-tabs-option"
			data-attribute="<?php echo esc_attr( $size_option ); ?>"
			aria-hidden="true"
			tabindex="-1"
			<?php checked( $settings[ $size_option ], '1' ); ?>
		/>

		<div role="tablist" class="sui-tabs-menu">

			<button
				type="button"
				role="tab"
				id="tab-<?php echo esc_attr( $size_option ); ?>--default"
				class="sui-tab-item active"
				data-label-for="hustle-<?php echo esc_attr( $size_option ); ?>--default"
				aria-selected="true"
			>
				<?php esc_html_e( 'Default Size', 'hustle' ); ?>
			</button>

			<button
				type="button"
				role="tab"
				id="tab-<?php echo esc_attr( $size_option ); ?>--custom"
				class="sui-tab-item"
				data-label-for="hustle-<?php echo esc_attr( $size_option ); ?>--custom"
				aria-controls="tab-content-<?php echo esc_attr( $size_option ); ?>--custom"
				aria-selected="false"
				tabindex="-1"
			>
				<?php esc_html_e( 'Custom', 'hustle' ); ?>
			</button>

		</div>

		<div class="sui-tabs-content">

			<div
				role="tabpanel"
				tabindex="0"
				id="tab-content-<?php echo esc_attr( $size_option ); ?>--custom"
				class="sui-tab-content sui-tab-boxed"
				aria-labelledby="tab-<?php echo esc_attr( $size_option ); ?>--custom"
				hidden
			>

				<p class="sui-description" style="margin-bottom: 20px;"><?php esc_html_e( 'We recommend using responsive units such as %, vh or vw. This custom size will be followed up to the mobile break-point.', 'hustle' ); ?></p>

				<div class="sui-row">

					<?php // FIELD: Width. ?>
					<div class="sui-col-md-6">

						<div class="sui-form-field">

							<label class="sui-label">
								<?php esc_html_e( 'Width', 'hustle' ); ?>
								<?php
								Hustle_Layout_Helper::get_html_for_options(
									array(
										array(
											'type'       => 'select',
											'name'       => $size_width_unit,
											'options'    => $units,
											'id'         => 'hustle-' . $size_width_unit,
											'selected'   => $settings[ $size_width_unit ],
											'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
											'attributes' => array(
												'data-width'     => '50',
												'data-attribute' => $size_width_unit,
												'aria-label'     => esc_html__( 'Choose value unit from the options', 'hustle' ),
											),
										),
									)
								);
								?>
							</label>

							<label for="hustle-<?php echo esc_attr( $size_width_value ); ?>" id="hustle-<?php echo esc_attr( $size_width_value ); ?>-label" class="sui-screen-reader-text">
								<?php /* translators: module name in lowercase and singular */ ?>
								<?php printf( esc_html__( 'Insert %s custom width value in the selected unit.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
							</label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'number',
										'name'       => $size_width_value,
										'min'        => '0',
										'value'      => $settings[ $size_width_value ],
										'id'         => 'hustle-' . $size_width_value,
										'attributes' => array(
											'data-attribute'  => $size_width_value,
											'aria-labelledby' => 'hustle-' . $size_width_value . '-label',
										),
									),
								)
							);
							?>

						</div>

					</div>

					<?php // FIELD: Height. ?>
					<div class="sui-col-md-6">

						<div class="sui-form-field">

							<label class="sui-label">
								<?php esc_html_e( 'Height', 'hustle' ); ?>
								<?php
								// Height has 'auto' option.
								$units['auto'] = __( 'auto', 'hustle' );
								Hustle_Layout_Helper::get_html_for_options(
									array(
										array(
											'type'       => 'select',
											'name'       => $size_height_unit,
											'options'    => $units,
											'id'         => 'hustle-' . $size_height_unit,
											'selected'   => $settings[ $size_height_unit ],
											'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel hustle-select-with-dependency-to-disable',
											'attributes' => array(
												'data-width'     => '70',
												'data-attribute' => $size_height_unit,
												'aria-label'     => esc_html__( 'Choose value unit from the options', 'hustle' ),
												'data-disable'   => esc_attr( $size_height_unit ),
											),
										),
									)
								);
								?>
							</label>

							<label for="hustle-<?php echo esc_attr( $size_height_value ); ?>" id="hustle-<?php echo esc_attr( $size_height_value ); ?>-label" class="sui-screen-reader-text">
								<?php /* translators: module name in lowercase and singular */ ?>
								<?php printf( esc_html__( 'Insert %s custom height value in the selected unit.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
							</label>

							<?php
							Hustle_Layout_Helper::get_html_for_options(
								array(
									array(
										'type'       => 'number',
										'name'       => $size_height_value,
										'min'        => '0',
										'value'      => $settings[ $size_height_value ],
										'id'         => 'hustle-' . $size_height_value,
										'attributes' => array(
											'data-attribute'  => $size_height_value,
											'aria-labelledby' => 'hustle-' . $size_height_value . '-label',
											'data-disable-content' => $size_height_unit,
											'data-disable-on'      => 'auto',
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

</div>