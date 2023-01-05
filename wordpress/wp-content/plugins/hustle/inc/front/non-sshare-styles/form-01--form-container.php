<?php
/**
 * Form Container.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-layout .hustle-layout-form';

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['form_cont_margin_top'] ) ? $advanced['form_cont_margin_top'] . $advanced['form_cont_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['form_cont_margin_right'] ) ? $advanced['form_cont_margin_right'] . $advanced['form_cont_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['form_cont_margin_bottom'] ) ? $advanced['form_cont_margin_bottom'] . $advanced['form_cont_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['form_cont_margin_left'] ) ? $advanced['form_cont_margin_left'] . $advanced['form_cont_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['form_cont_margin_top_mobile'] ) ? $advanced['form_cont_margin_top_mobile'] . $advanced['form_cont_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['form_cont_margin_right_mobile'] ) ? $advanced['form_cont_margin_right_mobile'] . $advanced['form_cont_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['form_cont_margin_bottom_mobile'] ) ? $advanced['form_cont_margin_bottom_mobile'] . $advanced['form_cont_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['form_cont_margin_left_mobile'] ) ? $advanced['form_cont_margin_left_mobile'] . $advanced['form_cont_margin_unit_mobile'] : $margin_left;

$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;
$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin;

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['form_cont_padding_top'] ) ? $advanced['form_cont_padding_top'] . $advanced['form_cont_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['form_cont_padding_right'] ) ? $advanced['form_cont_padding_right'] . $advanced['form_cont_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['form_cont_padding_bottom'] ) ? $advanced['form_cont_padding_bottom'] . $advanced['form_cont_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['form_cont_padding_left'] ) ? $advanced['form_cont_padding_left'] . $advanced['form_cont_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['form_cont_padding_top_mobile'] ) ? $advanced['form_cont_padding_top_mobile'] . $advanced['form_cont_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['form_cont_padding_right_mobile'] ) ? $advanced['form_cont_padding_right_mobile'] . $advanced['form_cont_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['form_cont_padding_bottom_mobile'] ) ? $advanced['form_cont_padding_bottom_mobile'] . $advanced['form_cont_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['form_cont_padding_left_mobile'] ) ? $advanced['form_cont_padding_left_mobile'] . $advanced['form_cont_padding_unit_mobile'] : $padding_left;

$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;
$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['form_cont_border_top'] ) ? $advanced['form_cont_border_top'] . $advanced['form_cont_border_unit'] : '0';
$border_right  = ( '' !== $advanced['form_cont_border_right'] ) ? $advanced['form_cont_border_right'] . $advanced['form_cont_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['form_cont_border_bottom'] ) ? $advanced['form_cont_border_bottom'] . $advanced['form_cont_border_unit'] : '0';
$border_left   = ( '' !== $advanced['form_cont_border_left'] ) ? $advanced['form_cont_border_left'] . $advanced['form_cont_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = ( '' !== $advanced['form_cont_border_type'] ) ? $advanced['form_cont_border_type'] : 'solid';
$border_color = $colors['form_cont_border'];

$mobile_border_top    = ( '' !== $advanced['form_cont_border_top_mobile'] ) ? $advanced['form_cont_border_top_mobile'] . $advanced['form_cont_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['form_cont_border_right_mobile'] ) ? $advanced['form_cont_border_right_mobile'] . $advanced['form_cont_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['form_cont_border_bottom_mobile'] ) ? $advanced['form_cont_border_bottom_mobile'] . $advanced['form_cont_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['form_cont_border_left_mobile'] ) ? $advanced['form_cont_border_left_mobile'] . $advanced['form_cont_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['form_cont_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['form_cont_radius_top_left'] ) ? $advanced['form_cont_radius_top_left'] . $advanced['form_cont_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['form_cont_radius_top_right'] ) ? $advanced['form_cont_radius_top_right'] . $advanced['form_cont_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['form_cont_radius_bottom_right'] ) ? $advanced['form_cont_radius_bottom_right'] . $advanced['form_cont_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['form_cont_radius_bottom_left'] ) ? $advanced['form_cont_radius_bottom_left'] . $advanced['form_cont_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['form_cont_radius_top_left_mobile'] ) ? $advanced['form_cont_radius_top_left_mobile'] . $advanced['form_cont_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['form_cont_radius_top_right_mobile'] ) ? $advanced['form_cont_radius_top_right_mobile'] . $advanced['form_cont_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['form_cont_radius_bottom_right_mobile'] ) ? $advanced['form_cont_radius_bottom_right_mobile'] . $advanced['form_cont_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['form_cont_radius_bottom_left_mobile'] ) ? $advanced['form_cont_radius_bottom_left_mobile'] . $advanced['form_cont_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;
$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_border_radius;

// SETTINGS: Background.
$background_color = $colors['form_area_bg'];

// ==================================================
// Check if module is an opt-in.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'margin: ' . $mobile_margin . ';';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color . ';' : '';
	$style     .= '}';

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $component . ' {';
				$style .= 'margin: ' . $margin . ';';
				$style .= 'padding: ' . $padding . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
				$style .= 'border-radius: ' . $border_radius . ';';
			$style     .= '}';
		$style         .= '}';

	}
}