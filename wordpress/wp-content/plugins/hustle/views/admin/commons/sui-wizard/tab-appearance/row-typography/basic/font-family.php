<?php
/**
 * Font family settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$font_name            = $property_key . '_font_family';
$font_weight_property = $property_key . '_font_weight';

$custom_font_name     = $property_key . '_custom_font_family';
$selected_font_family = $settings[ $font_name ];

$available_families = $this->admin->get_font_families();
?>

<div class="sui-form-field">

	<label id="hustle-<?php echo esc_attr( $font_name ); ?>-label" class="sui-label"><?php esc_html_e( 'Font Family', 'hustle' ); ?></label>

	<select
		id="hustle-select-<?php echo esc_attr( $font_name ); ?>"
		class="sui-select hustle-font-family-select sui-disabled"
		name="<?php echo esc_attr( $font_name ); ?>"
		data-attribute="<?php echo esc_attr( $font_name ); ?>"
		data-weight="<?php echo esc_attr( $font_weight_property ); ?>"
		data-custom="<?php echo esc_attr( $custom_font_name ); ?>"
		data-fonts-loaded="false"
		aria-labelledby="<?php echo esc_attr( $font_name ); ?>"
		tabindex="-1"
		aria-hidden="true"
		disabled
	>
		<option value="<?php echo esc_attr( $selected_font_family ); ?>" selected>
			<?php echo esc_html( $available_families[ $selected_font_family ]['label'] ); ?>
		</option>
	</select>

</div>

<div class="sui-form-field" <?php echo 'custom' !== $selected_font_family ? 'style="display: none;"' : ''; ?>>

	<label id="hustle-<?php echo esc_attr( $custom_font_name ); ?>-label" class="sui-label"><?php esc_html_e( 'Custom Font Family', 'hustle' ); ?></label>

	<?php
	Hustle_Layout_Helper::get_html_for_options(
		array(
			array(
				'type'        => 'text',
				'name'        => $custom_font_name,
				'value'       => $settings[ $custom_font_name ],
				'placeholder' => __( 'E.g. Arial, sans-serif', 'hustle' ),
				'id'          => 'hustle-' . $custom_font_name,
				'attributes'  => array(
					'data-attribute'  => $custom_font_name,
					'aria-labelledby' => 'hustle-' . $custom_font_name . '-label',
				),
			),
		)
	);
	?>

</div>