<?php
/**
 * Letter spacing settings.
 *
 * @uses admin/global/components/select-units
 *
 * @package Hustle
 * @since 4.3.0
 */

$value_name = $property_key . '_letter_spacing' . $device_suffix;
$unit_name  = $property_key . '_letter_spacing_unit' . $device_suffix;
?>

<div class="sui-form-field">

	<?php
	$this->render(
		'admin/global/components/select-units',
		array(
			'label'         => esc_html__( 'Letter Spacing', 'hustle' ),
			'name'          => $unit_name,
			'selected'      => $settings[ $unit_name ],
			'exclude_units' => array( 'vh', 'vw' ),
			'extra_units'   => array(
				'em'  => 'em',
				'rem' => 'rem',
			),
		)
	);
	?>

	<?php
	Hustle_Layout_Helper::get_html_for_options(
		array(
			array(
				'type'       => 'number',
				'name'       => $value_name,
				'class'      => 'hustle-required-field',
				'value'      => $settings[ $value_name ],
				'id'         => 'hustle-' . $value_name,
				'attributes' => array(
					'data-attribute'  => $value_name,
					'aria-labelledby' => 'hustle-' . $value_name . '-label',
				),
			),
		)
	);
	?>

</div>