<?php
/**
 * "Layout Header" settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-layout .hustle-layout-header';

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['layout_header_padding_top'] ) ? $advanced['layout_header_padding_top'] . $advanced['layout_header_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['layout_header_padding_right'] ) ? $advanced['layout_header_padding_right'] . $advanced['layout_header_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['layout_header_padding_bottom'] ) ? $advanced['layout_header_padding_bottom'] . $advanced['layout_header_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['layout_header_padding_left'] ) ? $advanced['layout_header_padding_left'] . $advanced['layout_header_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['layout_header_padding_top_mobile'] ) ? $advanced['layout_header_padding_top_mobile'] . $advanced['layout_header_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['layout_header_padding_right_mobile'] ) ? $advanced['layout_header_padding_right_mobile'] . $advanced['layout_header_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['layout_header_padding_bottom_mobile'] ) ? $advanced['layout_header_padding_bottom_mobile'] . $advanced['layout_header_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['layout_header_padding_left_mobile'] ) ? $advanced['layout_header_padding_left_mobile'] . $advanced['layout_header_padding_unit_mobile'] : $padding_left;

$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['layout_header_border_top'] ) ? $advanced['layout_header_border_top'] . $advanced['layout_header_border_unit'] : '0';
$border_right  = ( '' !== $advanced['layout_header_border_right'] ) ? $advanced['layout_header_border_right'] . $advanced['layout_header_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['layout_header_border_bottom'] ) ? $advanced['layout_header_border_bottom'] . $advanced['layout_header_border_unit'] : '0';
$border_left   = ( '' !== $advanced['layout_header_border_left'] ) ? $advanced['layout_header_border_left'] . $advanced['layout_header_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['layout_header_border_type'];
$border_color = $colors['layout_header_border'];

$mobile_border_top    = ( '' !== $advanced['layout_header_border_top_mobile'] ) ? $advanced['layout_header_border_top_mobile'] . $advanced['layout_header_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['layout_header_border_right_mobile'] ) ? $advanced['layout_header_border_right_mobile'] . $advanced['layout_header_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['layout_header_border_bottom_mobile'] ) ? $advanced['layout_header_border_bottom_mobile'] . $advanced['layout_header_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['layout_header_border_left_mobile'] ) ? $advanced['layout_header_border_left_mobile'] . $advanced['layout_header_border_unit_mobile'] : $border_left;

$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['layout_header_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['layout_header_radius_top_left'] ) ? $advanced['layout_header_radius_top_left'] . $advanced['layout_header_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['layout_header_radius_top_right'] ) ? $advanced['layout_header_radius_top_right'] . $advanced['layout_header_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['layout_header_radius_bottom_right'] ) ? $advanced['layout_header_radius_bottom_right'] . $advanced['layout_header_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['layout_header_radius_bottom_left'] ) ? $advanced['layout_header_radius_bottom_left'] . $advanced['layout_header_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['layout_header_radius_top_left_mobile'] ) ? $advanced['layout_header_radius_top_left_mobile'] . $advanced['layout_header_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['layout_header_radius_top_right_mobile'] ) ? $advanced['layout_header_radius_top_right_mobile'] . $advanced['layout_header_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['layout_header_radius_bottom_right_mobile'] ) ? $advanced['layout_header_radius_bottom_right_mobile'] . $advanced['layout_header_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['layout_header_radius_bottom_left_mobile'] ) ? $advanced['layout_header_radius_bottom_left_mobile'] . $advanced['layout_header_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['layout_header_drop_shadow_x'] ) ? $advanced['layout_header_drop_shadow_x'] . $advanced['layout_header_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['layout_header_drop_shadow_y'] ) ? $advanced['layout_header_drop_shadow_y'] . $advanced['layout_header_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['layout_header_drop_shadow_blur'] ) ? $advanced['layout_header_drop_shadow_blur'] . $advanced['layout_header_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['layout_header_drop_shadow_spread'] ) ? $advanced['layout_header_drop_shadow_spread'] . $advanced['layout_header_drop_shadow_unit'] : '0';
$shadow_color    = $colors['layout_header_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['layout_header_drop_shadow_x_mobile'] ) ? $advanced['layout_header_drop_shadow_x_mobile'] . $advanced['layout_header_drop_shadow_unit_mobile'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['layout_header_drop_shadow_y_mobile'] ) ? $advanced['layout_header_drop_shadow_y_mobile'] . $advanced['layout_header_drop_shadow_unit_mobile'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['layout_header_drop_shadow_blur_mobile'] ) ? $advanced['layout_header_drop_shadow_blur_mobile'] . $advanced['layout_header_drop_shadow_unit_mobile'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['layout_header_drop_shadow_spread_mobile'] ) ? $advanced['layout_header_drop_shadow_spread_mobile'] . $advanced['layout_header_drop_shadow_unit_mobile'] : $shadow_spread;

$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;

// SETTINGS: Colors.
$background_color = $colors['layout_header_bg'];

// ==================================================
// Check if is not informational compact layout.
if ( ! $is_optin && 'simple' !== $layout_info ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
	$style     .= '}';

	// Desktop styles.
	if ( $is_mobile_enabled ) {
		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $component . ' {';
				$style .= 'padding: ' . $padding . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
				$style .= 'border-radius: ' . $border_radius . ';';
				$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $box_shadow . ';' : '';
				$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $box_shadow . ';' : '';
				$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $box_shadow . ';' : '';
			$style     .= '}';
		$style         .= '}';
	}
}