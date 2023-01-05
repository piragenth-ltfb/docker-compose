<?php
/**
 * "Main Layout" settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = ( ! $is_optin && ( 'cabriolet' !== $layout_info ) ) ? '.hustle-layout' : '.hustle-layout .hustle-layout-body';

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['module_cont_padding_top'] ) ? $advanced['module_cont_padding_top'] . $advanced['module_cont_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['module_cont_padding_right'] ) ? $advanced['module_cont_padding_right'] . $advanced['module_cont_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['module_cont_padding_bottom'] ) ? $advanced['module_cont_padding_bottom'] . $advanced['module_cont_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['module_cont_padding_left'] ) ? $advanced['module_cont_padding_left'] . $advanced['module_cont_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['module_cont_padding_top_mobile'] ) ? $advanced['module_cont_padding_top_mobile'] . $advanced['module_cont_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['module_cont_padding_right_mobile'] ) ? $advanced['module_cont_padding_right_mobile'] . $advanced['module_cont_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['module_cont_padding_bottom_mobile'] ) ? $advanced['module_cont_padding_bottom_mobile'] . $advanced['module_cont_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['module_cont_padding_left_mobile'] ) ? $advanced['module_cont_padding_left_mobile'] . $advanced['module_cont_padding_unit_mobile'] : $padding_left;

$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['module_cont_border_top'] ) ? $advanced['module_cont_border_top'] . $advanced['module_cont_border_unit'] : '0';
$border_right  = ( '' !== $advanced['module_cont_border_right'] ) ? $advanced['module_cont_border_right'] . $advanced['module_cont_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['module_cont_border_bottom'] ) ? $advanced['module_cont_border_bottom'] . $advanced['module_cont_border_unit'] : '0';
$border_left   = ( '' !== $advanced['module_cont_border_left'] ) ? $advanced['module_cont_border_left'] . $advanced['module_cont_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['module_cont_border_type'];
$border_color = $colors['module_cont_border'];

$mobile_border_top    = ( '' !== $advanced['module_cont_border_top_mobile'] ) ? $advanced['module_cont_border_top_mobile'] . $advanced['module_cont_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['module_cont_border_right_mobile'] ) ? $advanced['module_cont_border_right_mobile'] . $advanced['module_cont_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['module_cont_border_bottom_mobile'] ) ? $advanced['module_cont_border_bottom_mobile'] . $advanced['module_cont_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['module_cont_border_left_mobile'] ) ? $advanced['module_cont_border_left_mobile'] . $advanced['module_cont_border_unit_mobile'] : $border_left;

$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['module_cont_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['module_cont_radius_top_left'] ) ? $advanced['module_cont_radius_top_left'] . $advanced['module_cont_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['module_cont_radius_top_right'] ) ? $advanced['module_cont_radius_top_right'] . $advanced['module_cont_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['module_cont_radius_bottom_right'] ) ? $advanced['module_cont_radius_bottom_right'] . $advanced['module_cont_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['module_cont_radius_bottom_left'] ) ? $advanced['module_cont_radius_bottom_left'] . $advanced['module_cont_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['module_cont_radius_top_left_mobile'] ) ? $advanced['module_cont_radius_top_left_mobile'] . $advanced['module_cont_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['module_cont_radius_top_right_mobile'] ) ? $advanced['module_cont_radius_top_right_mobile'] . $advanced['module_cont_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['module_cont_radius_bottom_right_mobile'] ) ? $advanced['module_cont_radius_bottom_right_mobile'] . $advanced['module_cont_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['module_cont_radius_bottom_left_mobile'] ) ? $advanced['module_cont_radius_bottom_left_mobile'] . $advanced['module_cont_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;

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

// SETTINGS: Background.
$background_color      = $colors['main_bg_color'];
$background_image      = $content['background_image'];
$background_size       = $design['background_image_fit'];
$background_width      = ( '' !== $design['background_image_width'] ) ? $design['background_image_width'] . $design['background_image_width_unit'] : 'auto';
$background_height     = ( '' !== $design['background_image_height'] ) ? $design['background_image_height'] . $design['background_image_height_unit'] : 'auto';
$background_size       = ( 'custom' === $background_size ) ? $background_width . ' ' . $background_height : $background_size;
$background_repeat     = $design['background_image_repeat'];
$background_position_x = $design['background_image_horizontal_position'];
$background_position_y = $design['background_image_vertical_position'];

if ( 'custom' === $background_position_x ) {
	$horizontal_value      = $design['background_image_horizontal_value'];
	$horizontal_unit       = $design['background_image_horizontal_unit'];
	$background_position_x = ( '' !== $horizontal_value ) ? $horizontal_value . $horizontal_unit : '0';
}

if ( 'custom' === $background_position_y ) {
	$vertical_value        = $design['background_image_vertical_value'];
	$vertical_unit         = $design['background_image_vertical_unit'];
	$background_position_y = ( '' !== $vertical_value ) ? $vertical_value . $vertical_unit : '0';
}

$mobile_background_size       = $design['background_image_fit_mobile'];
$mobile_background_width      = $design['background_image_width_mobile'];
$mobile_background_width      = ( '' !== $mobile_background_width ) ? $mobile_background_width . $design['background_image_width_unit_mobile'] : 'auto';
$mobile_background_height     = $design['background_image_height_mobile'];
$mobile_background_height     = ( '' !== $mobile_background_height ) ? $mobile_background_height . $design['background_image_height_unit_mobile'] : 'auto';
$mobile_background_size       = ( 'custom' === $mobile_background_size ) ? $mobile_background_width . ' ' . $mobile_background_height : $mobile_background_size;
$mobile_background_repeat     = $design['background_image_repeat_mobile'];
$mobile_background_position_x = $design['background_image_horizontal_position_mobile'];
$mobile_background_position_y = $design['background_image_vertical_position_mobile'];
$mobile_background_position_y = $mobile_background_position_y;

if ( 'custom' === $mobile_background_position_x ) {
	$mobile_horizontal_value      = $design['background_image_horizontal_value_mobile'];
	$mobile_horizontal_unit       = $design['background_image_horizontal_unit_mobile'];
	$mobile_background_position_x = ( '' !== $mobile_horizontal_value ) ? $mobile_horizontal_value . $mobile_horizontal_unit : '0';
}

if ( 'custom' === $mobile_background_position_y ) {
	$mobile_vertical_value        = $design['background_image_vertical_value_mobile'];
	$mobile_vertical_unit         = $design['background_image_vertical_unit_mobile'];
	$mobile_background_position_y = ( '' !== $mobile_vertical_value ) ? $mobile_vertical_value . $mobile_vertical_unit : '0';
}

if ( ! $is_mobile_enabled ) {
	$mobile_background_size       = $background_size;
	$mobile_background_width      = $background_width;
	$mobile_background_height     = $background_height;
	$mobile_background_size       = $background_size;
	$mobile_background_repeat     = $background_repeat;
	$mobile_background_position_x = $background_position_x;
	$mobile_background_position_y = $background_position_y;
}

// ==================================================
// Mobile styles.
$style     .= ' ';
$style     .= $prefix_mobile . $component . ' {';
	$style .= 'padding: ' . $mobile_padding . ';';
	$style .= 'border-width: ' . $mobile_border_width . ';';
	$style .= 'border-style: ' . $mobile_border_style . ';';
	$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
	$style .= 'border-radius: ' . $mobile_border_radius . ';';
	$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color . ';' : '';
	$style .= ( ! $is_slidein && ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
	$style .= ( ! $is_slidein && ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
	$style .= ( ! $is_slidein && ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
	$style .= ( '' !== $background_image ) ? 'background-image: url(' . $background_image . ');' : '';
	$style .= ( '' !== $background_image ) ? 'background-repeat: ' . $mobile_background_repeat . ';' : '';
	$style .= ( '' !== $background_image ) ? 'background-size: ' . $mobile_background_size . ';' : '';
	$style .= ( '' !== $background_image ) ? 'background-position: ' . $mobile_background_position_x . ' ' . $mobile_background_position_y . ';' : '';
$style     .= '}';

// Desktop styles.
if ( $is_mobile_enabled ) {
	$style         .= $breakpoint . ' {';
		$style     .= $prefix_desktop . $component . ' {';
			$style .= 'padding: ' . $padding . ';';
			$style .= 'border-width: ' . $border_width . ';';
			$style .= 'border-style: ' . $border_style . ';';
			$style .= 'border-radius: ' . $border_radius . ';';
			$style .= ( ! $is_slidein && ! $is_vanilla ) ? '-moz-box-shadow: ' . $box_shadow . ';' : '';
			$style .= ( ! $is_slidein && ! $is_vanilla ) ? '-webkit-box-shadow: ' . $box_shadow . ';' : '';
			$style .= ( ! $is_slidein && ! $is_vanilla ) ? 'box-shadow: ' . $box_shadow . ';' : '';
			$style .= ( '' !== $background_image ) ? 'background-repeat: ' . $background_repeat . ';' : '';
			$style .= ( '' !== $background_image ) ? 'background-size: ' . $background_size . ';' : '';
			$style .= ( '' !== $background_image ) ? 'background-position: ' . $background_position_x . ' ' . $background_position_y . ';' : '';
		$style     .= '}';
	$style         .= '}';
}