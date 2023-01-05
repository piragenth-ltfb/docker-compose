<?php
/**
 * Error message custom settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-layout .hustle-error-message';

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['error_message_margin_top'] ) ? $advanced['error_message_margin_top'] . $advanced['error_message_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['error_message_margin_right'] ) ? $advanced['error_message_margin_right'] . $advanced['error_message_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['error_message_margin_bottom'] ) ? $advanced['error_message_margin_bottom'] . $advanced['error_message_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['error_message_margin_left'] ) ? $advanced['error_message_margin_left'] . $advanced['error_message_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['error_message_margin_top_mobile'] ) ? $advanced['error_message_margin_top_mobile'] . $advanced['error_message_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['error_message_margin_right_mobile'] ) ? $advanced['error_message_margin_right_mobile'] . $advanced['error_message_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['error_message_margin_bottom_mobile'] ) ? $advanced['error_message_margin_bottom_mobile'] . $advanced['error_message_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['error_message_margin_left_mobile'] ) ? $advanced['error_message_margin_left_mobile'] . $advanced['error_message_margin_unit_mobile'] : $margin_left;

$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;
$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin;

// SETTINGS: Colors.
$border_color     = $colors['optin_error_text_border'];
$background_color = $colors['optin_error_text_bg'];
$font_color       = $colors['optin_error_text_color'];

// SETTINGS: Font settings.
$color           = $colors['optin_error_text_color'];
$font_family     = $typography['error_message_font_family'];
$font_size       = $typography['error_message_font_size'] . $typography['error_message_font_size_unit'];
$font_weight     = $typography['error_message_font_weight'];
$font_style      = 'normal';
$alignment       = $typography['error_message_alignment'];
$line_height     = $typography['error_message_line_height'] . $typography['error_message_line_height_unit'];
$letter_spacing  = $typography['error_message_letter_spacing'] . $typography['error_message_letter_spacing_unit'];
$text_transform  = $typography['error_message_text_transform'];
$text_decoration = $typography['error_message_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['error_message_custom_font_family'] ) ? $typography['error_message_custom_font_family'] : 'inherit';
}

if ( 'regular' === $font_weight ) {
	$font_weight = 'normal';
} else {

	// Check if font weight is italic.
	if ( preg_match( '/(italic)/', $font_weight ) ) {
		$font_weight = str_replace( 'italic', '', $font_weight );
		$font_style  = 'italic';
	}
}

$mobile_font_size       = ( '' !== $typography['error_message_font_size_mobile'] ) ? $typography['error_message_font_size_mobile'] . $typography['error_message_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['error_message_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_alignment       = $typography['error_message_alignment_mobile'];
$mobile_line_height     = ( '' !== $typography['error_message_line_height_mobile'] ) ? $typography['error_message_line_height_mobile'] . $typography['error_message_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['error_message_letter_spacing_mobile'] ) ? $typography['error_message_letter_spacing_mobile'] . $typography['error_message_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['error_message_text_transform'];
$mobile_text_decoration = $typography['error_message_text_decoration_mobile'];

if ( 'regular' === $mobile_font_weight ) {
	$mobile_font_weight = 'normal';
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
// Check if is an opt-in module.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'margin: ' . $mobile_margin . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'box-shadow: inset 4px 0 0 0 ' . $border_color . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: inset 4px 0 0 0 ' . $border_color . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: inset 4px 0 0 0 ' . $border_color . ';' : '';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' p {';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $font_color . ';' : '';
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
	$style         .= $breakpoint . ' {';
		$style     .= $prefix_desktop . $component . ' {';
			$style .= 'margin: ' . $margin . ';';
		$style     .= '}';
		$style     .= $prefix_desktop . $component . ' p {';
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