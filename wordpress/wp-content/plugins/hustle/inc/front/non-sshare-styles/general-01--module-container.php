<?php
/**
 * "Module Container" settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['popup_cont_padding_top'] ) ? $advanced['popup_cont_padding_top'] . $advanced['popup_cont_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['popup_cont_padding_right'] ) ? $advanced['popup_cont_padding_right'] . $advanced['popup_cont_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['popup_cont_padding_bottom'] ) ? $advanced['popup_cont_padding_bottom'] . $advanced['popup_cont_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['popup_cont_padding_left'] ) ? $advanced['popup_cont_padding_left'] . $advanced['popup_cont_padding_unit'] : '0';

$mobile_padding_top    = ( '' !== $advanced['popup_cont_padding_top_mobile'] ) ? $advanced['popup_cont_padding_top_mobile'] . $advanced['popup_cont_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['popup_cont_padding_right_mobile'] ) ? $advanced['popup_cont_padding_right_mobile'] . $advanced['popup_cont_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['popup_cont_padding_bottom_mobile'] ) ? $advanced['popup_cont_padding_bottom_mobile'] . $advanced['popup_cont_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['popup_cont_padding_left_mobile'] ) ? $advanced['popup_cont_padding_left_mobile'] . $advanced['popup_cont_padding_unit_mobile'] : $padding_left;

if ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) {
	$mobile_padding_top    = $padding_top;
	$mobile_padding_right  = $padding_right;
	$mobile_padding_bottom = $padding_bottom;
	$mobile_padding_left   = $padding_left;
}

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['module_cont_drop_shadow_x'] ) ? $advanced['module_cont_drop_shadow_x'] . $advanced['module_cont_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['module_cont_drop_shadow_y'] ) ? $advanced['module_cont_drop_shadow_y'] . $advanced['module_cont_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['module_cont_drop_shadow_blur'] ) ? $advanced['module_cont_drop_shadow_blur'] . $advanced['module_cont_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['module_cont_drop_shadow_spread'] ) ? $advanced['module_cont_drop_shadow_spread'] . $advanced['module_cont_drop_shadow_unit'] : '0';
$shadow_color    = $colors['module_cont_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['module_cont_drop_shadow_x_mobile'] ) ? $advanced['module_cont_drop_shadow_x_mobile'] . $advanced['module_cont_drop_shadow_unit_mobile'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['module_cont_drop_shadow_y_mobile'] ) ? $advanced['module_cont_drop_shadow_y_mobile'] . $advanced['module_cont_drop_shadow_unit_mobile'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['module_cont_drop_shadow_blur_mobile'] ) ? $advanced['module_cont_drop_shadow_blur_mobile'] . $advanced['module_cont_drop_shadow_unit_mobile'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['module_cont_drop_shadow_spread_mobile'] ) ? $advanced['module_cont_drop_shadow_spread_mobile'] . $advanced['module_cont_drop_shadow_unit_mobile'] : $shadow_spread;

$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;

// ==================================================
// Check if is pop-up.
if ( $is_popup ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . ' {';
		$style .= 'padding-right: ' . $mobile_padding_right . ';';
		$style .= 'padding-left: ' . $mobile_padding_left . ';';
	$style     .= '}';
	$style     .= $prefix_mobile . ' .hustle-popup-content .hustle-info,';
	$style     .= $prefix_mobile . ' .hustle-popup-content .hustle-optin {';
		$style .= 'padding-top: ' . $mobile_padding_top . ';';
		$style .= 'padding-bottom: ' . $mobile_padding_bottom . ';';
	$style     .= '}';

	// Desktop styles.
	if ( $is_mobile_enabled ) {
		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . ' {';
				$style .= 'padding-right: ' . $padding_right . ';';
				$style .= 'padding-left: ' . $padding_left . ';';
			$style     .= '}';
			$style     .= $prefix_desktop . ' .hustle-popup-content .hustle-info,';
			$style     .= $prefix_desktop . ' .hustle-popup-content .hustle-optin {';
				$style .= 'padding-top: ' . $padding_top . ';';
				$style .= 'padding-bottom: ' . $padding_bottom . ';';
			$style     .= '}';
		$style         .= '}';
	}
}

// Check if is slide-in.
if ( $is_slidein && ! $is_vanilla ) {

	$style     .= $prefix_mobile . ' .hustle-slidein-content {';
		$style .= '-moz-box-shadow: ' . $mobile_box_shadow . ';';
		$style .= '-webkit-box-shadow: ' . $mobile_box_shadow . ';';
		$style .= 'box-shadow: ' . $mobile_box_shadow . ';';
	$style     .= '}';

	if ( $is_mobile_enabled ) {
		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . ' .hustle-slidein-content {';
				$style .= '-moz-box-shadow: ' . $box_shadow . ';';
				$style .= '-webkit-box-shadow: ' . $box_shadow . ';';
				$style .= 'box-shadow: ' . $box_shadow . ';';
			$style     .= '}';
		$style         .= '}';
	}
}