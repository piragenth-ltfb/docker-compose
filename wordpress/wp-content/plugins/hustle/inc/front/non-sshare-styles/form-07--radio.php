<?php
/**
 * Radio custom settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-form .hustle-radio';

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['checkbox_border_top'] ) ? $advanced['checkbox_border_top'] . $advanced['checkbox_border_unit'] : '0';
$border_right  = ( '' !== $advanced['checkbox_border_right'] ) ? $advanced['checkbox_border_right'] . $advanced['checkbox_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['checkbox_border_bottom'] ) ? $advanced['checkbox_border_bottom'] . $advanced['checkbox_border_unit'] : '0';
$border_left   = ( '' !== $advanced['checkbox_border_left'] ) ? $advanced['checkbox_border_left'] . $advanced['checkbox_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['checkbox_border_type'];

$border_color_default = $colors['optin_check_radio_bo'];
$border_color_checked = $colors['optin_check_radio_bo_checked'];

$mobile_border_top    = ( '' !== $advanced['checkbox_border_top_mobile'] ) ? $advanced['checkbox_border_top_mobile'] . $advanced['checkbox_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['checkbox_border_right_mobile'] ) ? $advanced['checkbox_border_right_mobile'] . $advanced['checkbox_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['checkbox_border_bottom_mobile'] ) ? $advanced['checkbox_border_bottom_mobile'] . $advanced['checkbox_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['checkbox_border_left_mobile'] ) ? $advanced['checkbox_border_left_mobile'] . $advanced['checkbox_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_style = ( ! $is_mobile_enabled && ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['checkbox_border_type_mobile'];

// SETTINGS: Background.
$background_color_default = $colors['optin_check_radio_bg'];
$background_color_checked = $colors['optin_check_radio_bg_checked'];

// SETTINGS: Font settings.
$color           = $colors['optin_mailchimp_labels_color'];
$font_family     = $typography['checkbox_font_family'];
$font_size       = $typography['checkbox_font_size'] . $typography['checkbox_font_size_unit'];
$font_weight     = $typography['checkbox_font_weight'];
$font_style      = 'normal';
$alignment       = $typography['checkbox_alignment'];
$line_height     = $typography['checkbox_line_height'] . $typography['checkbox_line_height_unit'];
$letter_spacing  = $typography['checkbox_letter_spacing'] . $typography['checkbox_letter_spacing_unit'];
$text_transform  = $typography['checkbox_text_transform'];
$text_decoration = $typography['checkbox_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['checkbox_custom_font_family'] ) ? $typography['checkbox_custom_font_family'] : 'inherit';
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

$mobile_font_size       = ( '' !== $typography['checkbox_font_size_mobile'] ) ? $typography['checkbox_font_size_mobile'] . $typography['checkbox_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['checkbox_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_alignment       = $typography['checkbox_alignment_mobile'];
$mobile_line_height     = ( '' !== $typography['checkbox_line_height_mobile'] ) ? $typography['checkbox_line_height_mobile'] . $typography['checkbox_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['checkbox_letter_spacing_mobile'] ) ? $typography['checkbox_letter_spacing_mobile'] . $typography['checkbox_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['checkbox_text_transform'];
$mobile_text_decoration = $typography['checkbox_text_decoration_mobile'];

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

// SETTINGS: Icon.
$icon = $colors['optin_check_radio_tick_color'];

// ==================================================
// Check if is an opt-in module.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' span[aria-hidden] {';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color_default . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color_default . ';' : '';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' span:not([aria-hidden]) {';
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

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $component . ' input:checked + span[aria-hidden] {';
			$style .= 'border-color: ' . $border_color_checked . ';';
			$style .= 'background-color: ' . $background_color_checked . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' input:checked + span[aria-hidden]:before {';
			$style .= 'background-color: ' . $icon . ';';
		$style     .= '}';

	}

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $component . ' span[aria-hidden] {';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
			$style     .= '}';
			$style     .= $prefix_desktop . $component . ' span:not([aria-hidden]) {';
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