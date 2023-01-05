<?php
/**
 * Title custom settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-layout .hustle-title';

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['title_margin_top'] ) ? $advanced['title_margin_top'] . $advanced['title_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['title_margin_right'] ) ? $advanced['title_margin_right'] . $advanced['title_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['title_margin_bottom'] ) ? $advanced['title_margin_bottom'] . $advanced['title_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['title_margin_left'] ) ? $advanced['title_margin_left'] . $advanced['title_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['title_margin_top_mobile'] ) ? $advanced['title_margin_top_mobile'] . $advanced['title_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['title_margin_right_mobile'] ) ? $advanced['title_margin_right_mobile'] . $advanced['title_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['title_margin_bottom_mobile'] ) ? $advanced['title_margin_bottom_mobile'] . $advanced['title_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['title_margin_left_mobile'] ) ? $advanced['title_margin_left_mobile'] . $advanced['title_margin_unit_mobile'] : $margin_left;

$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['title_padding_top'] ) ? $advanced['title_padding_top'] . $advanced['title_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['title_padding_right'] ) ? $advanced['title_padding_right'] . $advanced['title_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['title_padding_bottom'] ) ? $advanced['title_padding_bottom'] . $advanced['title_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['title_padding_left'] ) ? $advanced['title_padding_left'] . $advanced['title_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['title_padding_top_mobile'] ) ? $advanced['title_padding_top_mobile'] . $advanced['title_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['title_padding_right_mobile'] ) ? $advanced['title_padding_right_mobile'] . $advanced['title_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['title_padding_bottom_mobile'] ) ? $advanced['title_padding_bottom_mobile'] . $advanced['title_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['title_padding_left_mobile'] ) ? $advanced['title_padding_left_mobile'] . $advanced['title_padding_unit_mobile'] : $padding_left;

$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['title_border_top'] ) ? $advanced['title_border_top'] . $advanced['title_border_unit'] : '0';
$border_right  = ( '' !== $advanced['title_border_right'] ) ? $advanced['title_border_right'] . $advanced['title_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['title_border_bottom'] ) ? $advanced['title_border_bottom'] . $advanced['title_border_unit'] : '0';
$border_left   = ( '' !== $advanced['title_border_left'] ) ? $advanced['title_border_left'] . $advanced['title_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = ( '' !== $advanced['title_border_type'] ) ? $advanced['title_border_type'] : 'solid';
$border_color = $colors['title_border'];

$mobile_border_top    = ( '' !== $advanced['title_border_top_mobile'] ) ? $advanced['title_border_top_mobile'] . $advanced['title_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['title_border_right_mobile'] ) ? $advanced['title_border_right_mobile'] . $advanced['title_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['title_border_bottom_mobile'] ) ? $advanced['title_border_bottom_mobile'] . $advanced['title_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['title_border_left_mobile'] ) ? $advanced['title_border_left_mobile'] . $advanced['title_border_unit_mobile'] : $border_left;

$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['title_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['title_radius_top_left'] ) ? $advanced['title_radius_top_left'] . $advanced['title_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['title_radius_top_right'] ) ? $advanced['title_radius_top_right'] . $advanced['title_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['title_radius_bottom_right'] ) ? $advanced['title_radius_bottom_right'] . $advanced['title_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['title_radius_bottom_left'] ) ? $advanced['title_radius_bottom_left'] . $advanced['title_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['title_radius_top_left_mobile'] ) ? $advanced['title_radius_top_left_mobile'] . $advanced['title_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['title_radius_top_right_mobile'] ) ? $advanced['title_radius_top_right_mobile'] . $advanced['title_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['title_radius_bottom_right_mobile'] ) ? $advanced['title_radius_bottom_right_mobile'] . $advanced['title_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['title_radius_bottom_left_mobile'] ) ? $advanced['title_radius_bottom_left_mobile'] . $advanced['title_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;

// SETTINGS: Background.
$background = $colors['title_bg'];

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['title_drop_shadow_x'] ) ? $advanced['title_drop_shadow_x'] . $advanced['title_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['title_drop_shadow_y'] ) ? $advanced['title_drop_shadow_y'] . $advanced['title_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['title_drop_shadow_blur'] ) ? $advanced['title_drop_shadow_blur'] . $advanced['title_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['title_drop_shadow_spread'] ) ? $advanced['title_drop_shadow_spread'] . $advanced['title_drop_shadow_unit'] : '0';
$shadow_color    = $colors['title_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['title_drop_shadow_x'] ) ? $advanced['title_drop_shadow_x'] . $advanced['title_drop_shadow_unit'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['title_drop_shadow_y'] ) ? $advanced['title_drop_shadow_y'] . $advanced['title_drop_shadow_unit'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['title_drop_shadow_blur'] ) ? $advanced['title_drop_shadow_blur'] . $advanced['title_drop_shadow_unit'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['title_drop_shadow_spread'] ) ? $advanced['title_drop_shadow_spread'] . $advanced['title_drop_shadow_unit'] : $shadow_spread;

$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;

// SETTINGS: Font settings.
$color           = $is_optin ? $colors['title_color'] : $colors['title_color_alt'];
$font_family     = $typography['title_font_family'];
$font_size       = $typography['title_font_size'] . $typography['title_font_size_unit'];
$font_weight     = $typography['title_font_weight'];
$font_style      = 'normal';
$alignment       = $typography['title_alignment'];
$line_height     = $typography['title_line_height'] . $typography['title_line_height_unit'];
$letter_spacing  = $typography['title_letter_spacing'] . $typography['title_letter_spacing_unit'];
$text_transform  = $typography['title_text_transform'];
$text_decoration = $typography['title_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['title_custom_font_family'] ) ? $typography['title_custom_font_family'] : 'inherit';
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

$mobile_font_size       = ( '' !== $typography['title_font_size_mobile'] ) ? $typography['title_font_size_mobile'] . $typography['title_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['title_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_alignment       = $typography['title_alignment_mobile'];
$mobile_line_height     = ( '' !== $typography['title_line_height_mobile'] ) ? $typography['title_line_height_mobile'] . $typography['title_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['title_letter_spacing_mobile'] ) ? $typography['title_letter_spacing_mobile'] . $typography['title_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['title_text_transform'];
$mobile_text_decoration = $typography['title_text_decoration_mobile'];

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
	$mobile_alignment       = $alignment;
	$mobile_line_height     = $line_height;
	$mobile_letter_spacing  = $letter_spacing;
	$mobile_text_transform  = $text_transform;
	$mobile_text_decoration = $text_decoration;
}

// ==================================================
// Check if title is not empty and exists.
if ( '' !== $content['title'] ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'display: block;';
		$style .= 'margin: ' . $mobile_margin . ';';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $color . ';' : '';
		$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $mobile_font_weight . ' ' . $mobile_font_size . '/' . $mobile_line_height . ' ' . $font_family . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $mobile_font_size . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $mobile_line_height . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $mobile_font_weight . ';' : '';
		$style .= 'font-style: ' . $mobile_font_style . ';';
		$style .= 'letter-spacing: ' . $mobile_letter_spacing . ';';
		$style .= 'text-transform: ' . $mobile_text_transform . ';';
		$style .= 'text-decoration: ' . $mobile_text_decoration . ';';
		$style .= 'text-align: ' . $mobile_alignment . ';';
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
				$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $box_shadow . ';' : '';
				$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $box_shadow . ';' : '';
				$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $box_shadow . ';' : '';
				$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $font_weight . ' ' . $font_size . '/' . $line_height . ' ' . $font_family . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $font_size . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $line_height . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $font_weight . ';' : '';
				$style .= 'font-style: ' . $font_style . ';';
				$style .= 'letter-spacing: ' . $letter_spacing . ';';
				$style .= 'text-transform: ' . $text_transform . ';';
				$style .= 'text-decoration: ' . $text_decoration . ';';
				$style .= 'text-align: ' . $alignment . ';';
			$style     .= '}';
		$style         .= '}';

	}
}