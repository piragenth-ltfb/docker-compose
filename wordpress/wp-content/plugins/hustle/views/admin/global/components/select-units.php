<?php
/**
 * Select Units.
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$html  = '';
$label = ( isset( $label ) && ! empty( $label ) ) ? $label : __( 'Pick a unit', 'hustle' );
$units = array(
	'px' => 'px',
	'%'  => '%',
	'vw' => 'vw',
	'vh' => 'vh',
);

if ( ! empty( $exclude_units ) ) {
	foreach ( $exclude_units as $unit ) {
		unset( $units[ $unit ] );
	}
}

if ( ! empty( $extra_units ) ) {
	$units = array_merge( $units, $extra_units );
}

$html .= '<label for="hustle-' . $name . '" id="hustle-' . $name . '-label" class="sui-label">';
$html .= esc_html( $label );
$html .= Hustle_Layout_Helper::get_html_for_options(
	array(
		array(
			'type'       => 'select',
			'name'       => $name,
			'options'    => $units,
			'id'         => 'hustle-' . $name,
			'selected'   => $selected,
			'class'      => 'sui-select sui-select-sm sui-select-inline sui-inlabel',
			'attributes' => array(
				'data-width'      => '50',
				'data-attribute'  => $name,
				'aria-labelledby' => 'hustle-' . $name . '-label',
			),
		),
	),
	true
);
$html .= '</label>';

echo $html;