<?php
/**
 * GDPR Checkbox custom settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-layout-form .hustle-checkbox.hustle-gdpr';

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['gdpr_margin_top'] ) ? $advanced['gdpr_margin_top'] . $advanced['gdpr_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['gdpr_margin_right'] ) ? $advanced['gdpr_margin_right'] . $advanced['gdpr_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['gdpr_margin_bottom'] ) ? $advanced['gdpr_margin_bottom'] . $advanced['gdpr_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['gdpr_margin_left'] ) ? $advanced['gdpr_margin_left'] . $advanced['gdpr_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['gdpr_margin_top_mobile'] ) ? $advanced['gdpr_margin_top_mobile'] . $advanced['gdpr_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['gdpr_margin_right_mobile'] ) ? $advanced['gdpr_margin_right_mobile'] . $advanced['gdpr_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['gdpr_margin_bottom_mobile'] ) ? $advanced['gdpr_margin_bottom_mobile'] . $advanced['gdpr_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['gdpr_margin_left_mobile'] ) ? $advanced['gdpr_margin_left_mobile'] . $advanced['gdpr_margin_unit_mobile'] : $margin_left;

$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;
$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['gdpr_border_top'] ) ? $advanced['gdpr_border_top'] . $advanced['gdpr_border_unit'] : '0';
$border_right  = ( '' !== $advanced['gdpr_border_right'] ) ? $advanced['gdpr_border_right'] . $advanced['gdpr_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['gdpr_border_bottom'] ) ? $advanced['gdpr_border_bottom'] . $advanced['gdpr_border_unit'] : '0';
$border_left   = ( '' !== $advanced['gdpr_border_left'] ) ? $advanced['gdpr_border_left'] . $advanced['gdpr_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['gdpr_border_type'];

$border_color_default = $colors['gdpr_chechbox_border_static'];
$border_color_checked = $colors['gdpr_chechbox_border_active'];
$border_color_error   = $colors['gdpr_checkbox_border_error'];

$mobile_border_top    = ( '' !== $advanced['gdpr_border_top_mobile'] ) ? $advanced['gdpr_border_top_mobile'] . $advanced['gdpr_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['gdpr_border_right_mobile'] ) ? $advanced['gdpr_border_right_mobile'] . $advanced['gdpr_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['gdpr_border_bottom_mobile'] ) ? $advanced['gdpr_border_bottom_mobile'] . $advanced['gdpr_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['gdpr_border_left_mobile'] ) ? $advanced['gdpr_border_left_mobile'] . $advanced['gdpr_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;

$mobile_border_style = ( $is_mobile_enabled ) ? $advanced['gdpr_border_type_mobile'] : $border_style;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $mobile_border_style;

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['gdpr_radius_top_left'] ) ? $advanced['gdpr_radius_top_left'] . $advanced['gdpr_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['gdpr_radius_top_right'] ) ? $advanced['gdpr_radius_top_right'] . $advanced['gdpr_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['gdpr_radius_bottom_right'] ) ? $advanced['gdpr_radius_bottom_right'] . $advanced['gdpr_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['gdpr_radius_bottom_left'] ) ? $advanced['gdpr_radius_bottom_left'] . $advanced['gdpr_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['gdpr_radius_top_left_mobile'] ) ? $advanced['gdpr_radius_top_left_mobile'] . $advanced['gdpr_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['gdpr_radius_top_right_mobile'] ) ? $advanced['gdpr_radius_top_right_mobile'] . $advanced['gdpr_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['gdpr_radius_bottom_right_mobile'] ) ? $advanced['gdpr_radius_bottom_right_mobile'] . $advanced['gdpr_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['gdpr_radius_bottom_left_mobile'] ) ? $advanced['gdpr_radius_bottom_left_mobile'] . $advanced['gdpr_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;
$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_border_radius;

// SETTINGS: Background.
$background_color_default = $colors['gdpr_chechbox_background_static'];
$background_color_checked = $colors['gdpr_checkbox_background_active'];
$background_color_error   = $colors['gdpr_checkbox_background_error'];

// SETTINGS: Icon.
$icon = $colors['gdpr_checkbox_icon'];

// SETTINGS: Font settings.
$color           = $colors['gdpr_content'];
$font_family     = $typography['gdpr_font_family'];
$font_size       = $typography['gdpr_font_size'] . $typography['gdpr_font_size_unit'];
$font_weight     = $typography['gdpr_font_weight'];
$font_style      = 'normal';
$alignment       = $typography['gdpr_alignment'];
$line_height     = $typography['gdpr_line_height'] . $typography['gdpr_line_height_unit'];
$letter_spacing  = $typography['gdpr_letter_spacing'] . $typography['gdpr_letter_spacing_unit'];
$text_transform  = $typography['gdpr_text_transform'];
$text_decoration = $typography['gdpr_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['gdpr_custom_font_family'] ) ? $typography['gdpr_custom_font_family'] : 'inherit';
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

$mobile_font_size       = ( '' !== $typography['gdpr_font_size_mobile'] ) ? $typography['gdpr_font_size_mobile'] . $typography['gdpr_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['gdpr_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_alignment       = $typography['gdpr_alignment_mobile'];
$mobile_line_height     = ( '' !== $typography['gdpr_line_height_mobile'] ) ? $typography['gdpr_line_height_mobile'] . $typography['gdpr_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['gdpr_letter_spacing_mobile'] ) ? $typography['gdpr_letter_spacing_mobile'] . $typography['gdpr_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['gdpr_text_transform'];
$mobile_text_decoration = $typography['gdpr_text_decoration_mobile'];

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

// SETTINGS: Link colors.
$link_default = $colors['gdpr_content_link'];
$link_hover   = $colors['gdpr_content_link'];
$link_focus   = $colors['gdpr_content_link'];

// ==================================================
// Check if is an opt-in module.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'margin: ' . $mobile_margin . ';';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' span[aria-hidden] {';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color_default . ';' : '';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color_default . ';' : '';
	$style     .= '}';

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $component . ' span[aria-hidden]:before {';
			$style .= 'color: ' . $icon . ';';
		$style     .= '}';

	}

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

		$style     .= $prefix_mobile . $component . ' span:not([aria-hidden]) a {';
			$style .= 'color: ' . $link_default . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' span:not([aria-hidden]) a:hover {';
			$style .= 'color: ' . $link_hover . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' span:not([aria-hidden]) a:focus {';
			$style .= 'color: ' . $link_focus . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' input:checked + span[aria-hidden] {';
			$style .= 'border-color: ' . $border_color_checked . ';';
			$style .= 'background-color: ' . $background_color_checked . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . '.hustle-field-error + span[aria-hidden] {';
			$style .= 'border-color: ' . $border_color_error . ' !important;';
			$style .= 'background-color: ' . $background_color_error . ' !important;';
		$style     .= '}';

	}

	// Desktop styles.
	$style         .= $breakpoint . ' {';
		$style     .= $prefix_desktop . $component . ' {';
			$style .= 'margin: ' . $margin . ';';
		$style     .= '}';
		$style     .= $prefix_desktop . $component . ' span[aria-hidden] {';
			$style .= 'border-width: ' . $border_width . ';';
			$style .= 'border-style: ' . $border_style . ';';
			$style .= 'border-radius: ' . $border_radius . ';';
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