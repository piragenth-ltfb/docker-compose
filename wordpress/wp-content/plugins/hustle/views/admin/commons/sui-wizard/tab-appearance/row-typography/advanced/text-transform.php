<?php
/**
 * Text transform settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$name = $property_key . '_text_transform' . $device_suffix;

$options = array(
	'none'       => esc_html__( 'None', 'hustle' ),
	'capitalize' => esc_html__( 'Capitalize', 'hustle' ),
	'uppercase'  => esc_html__( 'Uppercase', 'hustle' ),
	'lowercase'  => esc_html__( 'Lowercase', 'hustle' ),
);
?>

<div class="sui-form-field">

	<label id="hustle-<?php esc_attr( $name ); ?>-label" class="sui-label"><?php esc_html_e( 'Text Transform', 'hustle' ); ?></label>

	<?php
	Hustle_Layout_Helper::get_html_for_options(
		array(
			array(
				'type'       => 'select',
				'name'       => $name,
				'options'    => $options,
				'id'         => 'hustle-' . $name,
				'class'      => 'sui-select',
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