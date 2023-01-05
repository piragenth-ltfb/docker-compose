<?php
/**
 * Form fields settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-form .hustle-form-fields';
$component = $container . ' .hustle-field';

// SETTINGS: Layout.
$layout        = $design['optin_form_layout'];
$mobile_layout = ( $is_mobile_enabled ) ? $design['optin_form_layout_mobile'] : $layout;

// SETTINGS: Desktop proximity.
$custom_proximity = $design['customize_form_fields_proximity'];
$gap_value        = ( '' !== $design['form_fields_proximity_value'] ) ? $design['form_fields_proximity_value'] : '0';
$gap_unit         = $design['form_fields_proximity_unit'];

$gap_full = ( '1' === $custom_proximity ) ? $gap_value . $gap_unit : '1px';
$gap_half = ( '1' === $custom_proximity ) ? ( $gap_value / 2 ) . $gap_unit : '0.5px';

// SETTINGS: Mobile proximity.
$mobile_custom_proximity = $design['customize_form_fields_proximity_mobile'];
$mobile_gap_value        = ( '' !== $design['form_fields_proximity_value_mobile'] ) ? $design['form_fields_proximity_value_mobile'] : $gap_value;
$mobile_gap_unit         = $design['form_fields_proximity_unit_mobile'];

$mobile_gap_full = ( '1' === $mobile_custom_proximity ) ? $mobile_gap_value . $mobile_gap_unit : '1px';
$mobile_gap_half = ( '1' === $mobile_custom_proximity ) ? ( $mobile_gap_value / 2 ) . $mobile_gap_unit : '0.5px';

if ( ! $is_mobile_enabled ) {
	$mobile_gap_full = $gap_full;
	$mobile_gap_half = $gap_half;
}

// ==================================================
// Check if is an opt-in module.
if ( $is_optin ) {

	if ( 'inline' === $mobile_layout ) {
		$style     .= $prefix_mobile . $container . ' {';
			$style .= 'display: -webkit-box;';
			$style .= 'display: -ms-flex;';
			$style .= 'display: flex;';
			$style .= '-ms-flex-wrap: wrap;';
			$style .= 'flex-wrap: wrap;';
			$style .= '-webkit-box-align: center;';
			$style .= '-ms-flex-align: center;';
			$style .= 'align-items: center;';
			$style .= 'margin-top: -' . $mobile_gap_half . ';';
			$style .= 'margin-bottom: -' . $mobile_gap_half . ';';
		$style     .= '}';
		$style     .= $prefix_mobile . $component . ' {';
			$style .= 'min-width: 100px;';
			$style .= '-webkit-box-flex: 1;';
			$style .= '-ms-flex: 1;';
			$style .= 'flex: 1;';
			$style .= 'margin-top: ' . $mobile_gap_half . ';';
			$style .= 'margin-right: ' . $mobile_gap_full . ';';
			$style .= 'margin-bottom: ' . $mobile_gap_half . ';';
		$style     .= '}';
		$style     .= $prefix_mobile . $container . ' .hustle-button {';
			$style .= 'width: auto;';
			$style .= '-webkit-box-flex: 0;';
			$style .= '-ms-flex: 0 0 auto;';
			$style .= 'flex: 0 0 auto;';
			$style .= 'margin-top: ' . $mobile_gap_half . ';';
			$style .= 'margin-bottom: ' . $mobile_gap_half . ';';
		$style     .= '}';
	} else {
		$style     .= $prefix_mobile . $container . ' {';
			$style .= 'display: block;';
		$style     .= '}';
		$style     .= $prefix_mobile . $component . ' {';
			$style .= 'margin-bottom: ' . $mobile_gap_full . ';';
		$style     .= '}';
		$style     .= $prefix_mobile . $container . ' .hustle-button {';
			$style .= 'width: 100%;';
		$style     .= '}';
	}

	if ( $is_mobile_enabled ) {

		if ( 'inline' === $layout ) {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $container . ' {';
					$style .= ( 'inline' !== $mobile_layout ) ? 'display: -webkit-box;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? 'display: -ms-flex;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? 'display: flex;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-ms-flex-wrap: wrap;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? 'flex-wrap: wrap;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-webkit-box-align: center;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-ms-flex-align: center;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? 'align-items: center;' : '';
					$style .= 'margin-top: -' . $gap_half . ';';
					$style .= 'margin-bottom: -' . $gap_half . ';';
				$style     .= '}';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= ( 'inline' !== $mobile_layout ) ? 'min-width: 100px;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-webkit-box-flex: 1;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-ms-flex: 1;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? 'flex: 1;' : '';
					$style .= 'margin-top: ' . $gap_half . ';';
					$style .= 'margin-right: ' . $gap_full . ';';
					$style .= 'margin-bottom: ' . $gap_half . ';';
				$style     .= '}';
				$style     .= $prefix_desktop . $container . ' .hustle-button {';
					$style .= ( 'inline' !== $mobile_layout ) ? 'width: auto;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-webkit-box-flex: 0;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? '-ms-flex: 0 0 auto;' : '';
					$style .= ( 'inline' !== $mobile_layout ) ? 'flex: 0 0 auto;' : '';
					$style .= 'margin-top: ' . $gap_half . ';';
					$style .= 'margin-bottom: ' . $gap_half . ';';
				$style     .= '}';
			$style         .= '}';
		} else {
			$style         .= $breakpoint . ' {';
				$style     .= ( 'inline' === $mobile_layout ) ? $prefix_desktop . $container . ' {' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'display: block;' : '';
				$style     .= ( 'inline' === $mobile_layout ) ? '}' : '';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= ( 'inline' === $mobile_layout ) ? 'min-width: none;' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'min-width: unset;' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'margin-top: 0;' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'margin-right: 0;' : '';
					$style .= 'margin-bottom: ' . $gap_full . ';';
				$style     .= '}';
				$style     .= ( 'inline' === $mobile_layout ) ? $prefix_desktop . $container . ' .hustle-button {' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'width: 100%;' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'margin-top: 0;' : '';
					$style .= ( 'inline' === $mobile_layout ) ? 'margin-bottom: 0;' : '';
				$style     .= ( 'inline' === $mobile_layout ) ? '}' : '';
			$style         .= '}';
		}
	}
}