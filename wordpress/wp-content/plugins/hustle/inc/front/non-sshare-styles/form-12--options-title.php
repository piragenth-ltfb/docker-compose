<?php
/**
 * Title custom settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-form .hustle-form-options .hustle-group-title';

// SETTINGS: Font settings.
$color           = $colors['optin_mailchimp_title_color'];
$font_family     = $typography['form_extras_font_family'];
$font_size       = $typography['form_extras_font_size'] . $typography['form_extras_font_size_unit'];
$font_weight     = $typography['form_extras_font_weight'];
$font_style      = 'normal';
$alignment       = $typography['form_extras_alignment'];
$line_height     = $typography['form_extras_line_height'] . $typography['form_extras_line_height_unit'];
$letter_spacing  = $typography['form_extras_letter_spacing'] . $typography['form_extras_letter_spacing_unit'];
$text_transform  = $typography['form_extras_text_transform'];
$text_decoration = $typography['form_extras_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['error_message_custom_font_family'] ) ? $typography['error_message_custom_font_family'] : 'inherit';
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

$mobile_font_size       = ( '' !== $typography['form_extras_font_size_mobile'] ) ? $typography['form_extras_font_size_mobile'] . $typography['form_extras_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['form_extras_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_alignment       = $typography['form_extras_alignment_mobile'];
$mobile_line_height     = ( '' !== $typography['form_extras_line_height_mobile'] ) ? $typography['form_extras_line_height_mobile'] . $typography['form_extras_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['form_extras_letter_spacing_mobile'] ) ? $typography['form_extras_letter_spacing_mobile'] . $typography['form_extras_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['form_extras_text_transform'];
$mobile_text_decoration = $typography['form_extras_text_decoration_mobile'];

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
// Check if is an opt-in module.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'display: block;';
		$style .= 'margin: 0 0 20px;';
		$style .= 'padding: 0;';
		$style .= 'border: 0;';
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
	$style         .= $breakpoint . ' {';
		$style     .= $prefix_desktop . $component . ' {';
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