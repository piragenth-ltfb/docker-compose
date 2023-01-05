<?php
/**
 * Form – Extra Options Container custom settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-form .hustle-form-options';

$is_mobile_enabled  = ( '1' === $design['enable_mobile_settings'] );
$is_mobile_disabled = ( '1' !== $design['enable_mobile_settings'] );

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['form_extras_margin_top'] ) ? $advanced['form_extras_margin_top'] . $advanced['form_extras_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['form_extras_margin_right'] ) ? $advanced['form_extras_margin_right'] . $advanced['form_extras_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['form_extras_margin_bottom'] ) ? $advanced['form_extras_margin_bottom'] . $advanced['form_extras_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['form_extras_margin_left'] ) ? $advanced['form_extras_margin_left'] . $advanced['form_extras_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['form_extras_margin_top_mobile'] ) ? $advanced['form_extras_margin_top_mobile'] . $advanced['form_extras_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['form_extras_margin_right_mobile'] ) ? $advanced['form_extras_margin_right_mobile'] . $advanced['form_extras_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['form_extras_margin_bottom_mobile'] ) ? $advanced['form_extras_margin_bottom_mobile'] . $advanced['form_extras_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['form_extras_margin_left_mobile'] ) ? $advanced['form_extras_margin_left_mobile'] . $advanced['form_extras_margin_unit_mobile'] : $margin_left;

$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['form_extras_padding_top'] ) ? $advanced['form_extras_padding_top'] . $advanced['form_extras_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['form_extras_padding_right'] ) ? $advanced['form_extras_padding_right'] . $advanced['form_extras_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['form_extras_padding_bottom'] ) ? $advanced['form_extras_padding_bottom'] . $advanced['form_extras_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['form_extras_padding_left'] ) ? $advanced['form_extras_padding_left'] . $advanced['form_extras_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['form_extras_padding_top_mobile'] ) ? $advanced['form_extras_padding_top_mobile'] . $advanced['form_extras_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['form_extras_padding_right_mobile'] ) ? $advanced['form_extras_padding_right_mobile'] . $advanced['form_extras_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['form_extras_padding_bottom_mobile'] ) ? $advanced['form_extras_padding_bottom_mobile'] . $advanced['form_extras_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['form_extras_padding_left_mobile'] ) ? $advanced['form_extras_padding_left_mobile'] . $advanced['form_extras_padding_unit_mobile'] : $padding_left;

$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['form_extras_border_top'] ) ? $advanced['form_extras_border_top'] . $advanced['form_extras_border_unit'] : '0';
$border_right  = ( '' !== $advanced['form_extras_border_right'] ) ? $advanced['form_extras_border_right'] . $advanced['form_extras_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['form_extras_border_bottom'] ) ? $advanced['form_extras_border_bottom'] . $advanced['form_extras_border_unit'] : '0';
$border_left   = ( '' !== $advanced['form_extras_border_left'] ) ? $advanced['form_extras_border_left'] . $advanced['form_extras_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['form_extras_border_type'];
$border_color = $colors['form_extras_border'];

$mobile_border_top    = ( '' !== $advanced['form_extras_border_top_mobile'] ) ? $advanced['form_extras_border_top_mobile'] . $advanced['form_extras_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['form_extras_border_right_mobile'] ) ? $advanced['form_extras_border_right_mobile'] . $advanced['form_extras_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['form_extras_border_bottom_mobile'] ) ? $advanced['form_extras_border_bottom_mobile'] . $advanced['form_extras_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['form_extras_border_left_mobile'] ) ? $advanced['form_extras_border_left_mobile'] . $advanced['form_extras_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_style = ( $is_mobile_enabled ) ? $advanced['form_extras_border_type_mobile'] : $border_style;

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['submit_button_radius_top_left'] ) ? $advanced['submit_button_radius_top_left'] . $advanced['submit_button_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['submit_button_radius_top_right'] ) ? $advanced['submit_button_radius_top_right'] . $advanced['submit_button_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['submit_button_radius_bottom_right'] ) ? $advanced['submit_button_radius_bottom_right'] . $advanced['submit_button_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['submit_button_radius_bottom_left'] ) ? $advanced['submit_button_radius_bottom_left'] . $advanced['submit_button_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['submit_button_radius_top_left_mobile'] ) ? $advanced['submit_button_radius_top_left_mobile'] . $advanced['submit_button_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['submit_button_radius_top_right_mobile'] ) ? $advanced['submit_button_radius_top_right_mobile'] . $advanced['submit_button_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['submit_button_radius_bottom_right_mobile'] ) ? $advanced['submit_button_radius_bottom_right_mobile'] . $advanced['submit_button_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['submit_button_radius_bottom_left_mobile'] ) ? $advanced['submit_button_radius_bottom_left_mobile'] . $advanced['submit_button_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['form_extras_drop_shadow_x'] ) ? $advanced['form_extras_drop_shadow_x'] . $advanced['form_extras_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['form_extras_drop_shadow_y'] ) ? $advanced['form_extras_drop_shadow_y'] . $advanced['form_extras_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['form_extras_drop_shadow_blur'] ) ? $advanced['form_extras_drop_shadow_blur'] . $advanced['form_extras_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['form_extras_drop_shadow_spread'] ) ? $advanced['form_extras_drop_shadow_spread'] . $advanced['form_extras_drop_shadow_unit'] : '0';
$shadow_color    = $colors['form_extras_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['form_extras_drop_shadow_x_mobile'] ) ? $advanced['form_extras_drop_shadow_x_mobile'] . $advanced['form_extras_drop_shadow_unit_mobile'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['form_extras_drop_shadow_y_mobile'] ) ? $advanced['form_extras_drop_shadow_y_mobile'] . $advanced['form_extras_drop_shadow_unit_mobile'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['form_extras_drop_shadow_blur_mobile'] ) ? $advanced['form_extras_drop_shadow_blur_mobile'] . $advanced['form_extras_drop_shadow_unit_mobile'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['form_extras_drop_shadow_spread_mobile'] ) ? $advanced['form_extras_drop_shadow_spread_mobile'] . $advanced['form_extras_drop_shadow_unit_mobile'] : $shadow_spread;

$mobile_box_shadow = $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;

// SETTINGS: Background.
$background_color = $colors['custom_section_bg'];

// ==================================================
// Check if module is opt-in.
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
		$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
	$style     .= '}';

	// Desktop styles.
	$style         .= $breakpoint . ' {';
		$style     .= $prefix_desktop . $component . ' {';
			$style .= 'margin: ' . $margin . ';';
			$style .= 'padding: ' . $padding . ';';
			$style .= 'border-width: ' . $border_width . ';';
			$style .= 'border-style: ' . $border_style . ';';
			$style .= 'border-radius: ' . $border_radius . ';';
			$style .= 'box-shadow: ' . $box_shadow . ';';
			$style .= '-moz-box-shadow: ' . $box_shadow . ';';
			$style .= '-webkit-box-shadow: ' . $box_shadow . ';';
		$style     .= '}';
	$style         .= '}';

}