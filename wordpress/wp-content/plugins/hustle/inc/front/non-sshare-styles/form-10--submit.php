<?php
/**
 * Form Submit.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-form button.hustle-button-submit';

// Component States.
$state_default = $component;
$state_hover   = $component . ':hover';
$state_focus   = $component . ':focus';

// SETTINGS: Background.
$background_default = $colors['optin_submit_button_static_bg'];
$background_hover   = $colors['optin_submit_button_hover_bg'];
$background_focus   = $colors['optin_submit_button_active_bg'];

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['submit_button_padding_top'] ) ? $advanced['submit_button_padding_top'] . $advanced['submit_button_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['submit_button_padding_right'] ) ? $advanced['submit_button_padding_right'] . $advanced['submit_button_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['submit_button_padding_bottom'] ) ? $advanced['submit_button_padding_bottom'] . $advanced['submit_button_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['submit_button_padding_left'] ) ? $advanced['submit_button_padding_left'] . $advanced['submit_button_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['submit_button_padding_top_mobile'] ) ? $advanced['submit_button_padding_top_mobile'] . $advanced['submit_button_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['submit_button_padding_right_mobile'] ) ? $advanced['submit_button_padding_right_mobile'] . $advanced['submit_button_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['submit_button_padding_bottom_mobile'] ) ? $advanced['submit_button_padding_bottom_mobile'] . $advanced['submit_button_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['submit_button_padding_left_mobile'] ) ? $advanced['submit_button_padding_left_mobile'] . $advanced['submit_button_padding_unit_mobile'] : $padding_left;

$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;
$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding;

// SETTINGS: Border.
$border_color_default = $colors['optin_submit_button_static_bo'];
$border_color_hover   = $colors['optin_submit_button_hover_bo'];
$border_color_focus   = $colors['optin_submit_button_active_bo'];

$border_top    = ( '' !== $advanced['submit_button_border_top'] ) ? $advanced['submit_button_border_top'] . $advanced['submit_button_border_unit'] : '0';
$border_right  = ( '' !== $advanced['submit_button_border_right'] ) ? $advanced['submit_button_border_right'] . $advanced['submit_button_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['submit_button_border_bottom'] ) ? $advanced['submit_button_border_bottom'] . $advanced['submit_button_border_unit'] : '0';
$border_left   = ( '' !== $advanced['submit_button_border_left'] ) ? $advanced['submit_button_border_left'] . $advanced['submit_button_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = ( '' !== $advanced['submit_button_border_type'] ) ? $advanced['submit_button_border_type'] : 'solid';

$mobile_border_top    = ( '' !== $advanced['submit_button_border_top_mobile'] ) ? $advanced['submit_button_border_top_mobile'] . $advanced['submit_button_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['submit_button_border_right_mobile'] ) ? $advanced['submit_button_border_right_mobile'] . $advanced['submit_button_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['submit_button_border_bottom_mobile'] ) ? $advanced['submit_button_border_bottom_mobile'] . $advanced['submit_button_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['submit_button_border_left_mobile'] ) ? $advanced['submit_button_border_left_mobile'] . $advanced['submit_button_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['submit_button_border_type_mobile'];

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
$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_border_radius;

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['submit_button_drop_shadow_x'] ) ? $advanced['submit_button_drop_shadow_x'] . $advanced['submit_button_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['submit_button_drop_shadow_y'] ) ? $advanced['submit_button_drop_shadow_y'] . $advanced['submit_button_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['submit_button_drop_shadow_blur'] ) ? $advanced['submit_button_drop_shadow_blur'] . $advanced['submit_button_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['submit_button_drop_shadow_spread'] ) ? $advanced['submit_button_drop_shadow_spread'] . $advanced['submit_button_drop_shadow_unit'] : '0';
$shadow_color    = $colors['submit_button_static_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['submit_button_drop_shadow_x'] ) ? $advanced['submit_button_drop_shadow_x'] . $advanced['submit_button_drop_shadow_unit'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['submit_button_drop_shadow_y'] ) ? $advanced['submit_button_drop_shadow_y'] . $advanced['submit_button_drop_shadow_unit'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['submit_button_drop_shadow_blur'] ) ? $advanced['submit_button_drop_shadow_blur'] . $advanced['submit_button_drop_shadow_unit'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['submit_button_drop_shadow_spread'] ) ? $advanced['submit_button_drop_shadow_spread'] . $advanced['submit_button_drop_shadow_unit'] : $shadow_spread;

$mobile_box_shadow = $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;
$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_box_shadow;

// SETTINGS: Font settings.
$color_default = $colors['optin_submit_button_static_color'];
$color_hover   = $colors['optin_submit_button_hover_color'];
$color_focus   = $colors['optin_submit_button_active_color'];

$font_family     = $typography['submit_button_font_family'];
$font_size       = $typography['submit_button_font_size'] . $typography['submit_button_font_size_unit'];
$font_weight     = $typography['submit_button_font_weight'];
$font_style      = 'normal';
$line_height     = $typography['submit_button_line_height'] . $typography['submit_button_line_height_unit'];
$letter_spacing  = $typography['submit_button_letter_spacing'] . $typography['submit_button_letter_spacing_unit'];
$text_transform  = $typography['submit_button_text_transform'];
$text_decoration = $typography['submit_button_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['submit_custom_font_family'] ) ? $typography['submit_custom_font_family'] : 'inherit';
}

if ( 'regular' === $font_weight ) {
	$font_weight = 'normal';
} elseif ( 'italic' === $font_weight ) {
	$font_weight = 'normal';
	$font_style  = 'italic';
} else {

	// Check if font weight is italic.
	if ( preg_match( '/(italic)/', $font_weight ) ) {
		$font_weight = str_replace( 'italic', '', $font_weight );
		$font_style  = 'italic';
	}
}

$mobile_font_size       = ( '' !== $typography['submit_button_font_size_mobile'] ) ? $typography['submit_button_font_size_mobile'] . $typography['submit_button_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['submit_button_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_line_height     = ( '' !== $typography['submit_button_line_height_mobile'] ) ? $typography['submit_button_line_height_mobile'] . $typography['submit_button_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['submit_button_letter_spacing_mobile'] ) ? $typography['submit_button_letter_spacing_mobile'] . $typography['submit_button_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['submit_button_text_transform'];
$mobile_text_decoration = $typography['submit_button_text_decoration_mobile'];

if ( 'regular' === $mobile_font_weight ) {
	$mobile_font_weight = 'normal';
} elseif ( 'italic' === $font_weight ) {
	$font_weight = 'normal';
	$font_style  = 'italic';
} else {

	// Check if font weight is italic.
	if ( preg_match( '/(italic)/', $mobile_font_weight ) ) {
		$mobile_font_weight = str_replace( 'italic', '', $mobile_font_weight );
		$mobile_font_style  = 'italic';
	}
}

if ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_typography ) ) {
	$mobile_font_size       = $font_size;
	$mobile_font_weight     = $font_weight;
	$mobile_font_style      = $font_style;
	$mobile_line_height     = $line_height;
	$mobile_letter_spacing  = $letter_spacing;
	$mobile_text_transform  = $text_transform;
	$mobile_text_decoration = $text_decoration;
}

// ==================================================
// Check if "Call to Action" button is enabled.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $state_default . ' {';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color_default . ';' : '';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_default . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $color_default . ';' : '';
		$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $mobile_font_weight . ' ' . $mobile_font_size . '/' . $mobile_line_height . ' ' . $font_family . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $mobile_font_size . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $mobile_line_height . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $mobile_font_weight . ';' : '';
		$style .= 'font-style: ' . $mobile_font_style . ';';
		$style .= 'letter-spacing: ' . $mobile_letter_spacing . ';';
		$style .= 'text-transform: ' . $mobile_text_transform . ';';
		$style .= 'text-decoration: ' . $mobile_text_decoration . ';';
	$style     .= '}';

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $state_hover . ' {';
			$style .= 'border-color: ' . $border_color_hover . ';';
			$style .= 'background-color: ' . $background_hover . ';';
			$style .= 'color: ' . $color_hover . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $state_focus . ' {';
			$style .= 'border-color: ' . $border_color_focus . ';';
			$style .= 'background-color: ' . $background_focus . ';';
			$style .= 'color: ' . $color_focus . ';';
		$style     .= '}';

	}

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $state_default . ' {';
				$style .= 'padding: ' . $padding . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
				$style .= 'border-radius: ' . $border_radius . ';';
				$style .= 'box-shadow: ' . $box_shadow . ';';
				$style .= '-moz-box-shadow: ' . $box_shadow . ';';
				$style .= '-webkit-box-shadow: ' . $box_shadow . ';';
				$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $font_weight . ' ' . $font_size . '/' . $line_height . ' ' . $font_family . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $font_size . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $line_height . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $font_weight . ';' : '';
				$style .= 'font-style: ' . $font_style . ';';
				$style .= 'letter-spacing: ' . $letter_spacing . ';';
				$style .= 'text-transform: ' . $text_transform . ';';
				$style .= 'text-decoration: ' . $text_decoration . ';';
			$style     .= '}';
		$style         .= '}';

	}
}