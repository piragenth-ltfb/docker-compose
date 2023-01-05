<?php
/**
 * Font weight settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$name = $property_key . '_font_weight' . $device_suffix;

$font_family_property = $property_key . '_font_family';

$options = array();

$available_families = $this->admin->get_font_families();
$selected_family    = $settings[ $font_family_property ];

if ( ! empty( $available_families[ $selected_family ] ) ) {
	$select_options = $available_families[ $selected_family ]['variants'];

	// The retrieved array isn't an associative one. Create it from its values.
	$options = array_combine( $select_options, $select_options );
}

?>

<div class="sui-form-field">

	<label id="hustle-<?php echo esc_attr( $name ); ?>-label" class="sui-label"><?php esc_html_e( 'Font Weight', 'hustle' ); ?></label>

	<?php
	Hustle_Layout_Helper::get_html_for_options(
		array(
			array(
				'type'       => 'select',
				'name'       => $name,
				'options'    => $options,
				'id'         => 'hustle-' . $name,
				'class'      => 'sui-select hustle-font-weight',
				'selected'   => $settings[ $name ],
				'attributes' => array(
					'data-attribute'  => $name,
					'aria-labelledby' => 'hustle-' . $name . '-label',
				),
			),
		)
	);
	?>

</div>