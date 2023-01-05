<?php
/**
 * CTA Design.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = ( $is_optin ) ? '.hustle-layout .hustle-layout-footer' : '.hustle-nsa-link';
$component = ( $is_optin ) ? '.hustle-layout .hustle-nsa-link' : '.hustle-nsa-link';

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['nsa_link_margin_top'] ) ? $advanced['nsa_link_margin_top'] . $advanced['nsa_link_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['nsa_link_margin_right'] ) ? $advanced['nsa_link_margin_right'] . $advanced['nsa_link_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['nsa_link_margin_bottom'] ) ? $advanced['nsa_link_margin_bottom'] . $advanced['nsa_link_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['nsa_link_margin_left'] ) ? $advanced['nsa_link_margin_left'] . $advanced['nsa_link_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['nsa_link_margin_top_mobile'] ) ? $advanced['nsa_link_margin_top_mobile'] . $advanced['nsa_link_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['nsa_link_margin_right_mobile'] ) ? $advanced['nsa_link_margin_right_mobile'] . $advanced['nsa_link_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['nsa_link_margin_bottom_mobile'] ) ? $advanced['nsa_link_margin_bottom_mobile'] . $advanced['nsa_link_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['nsa_link_margin_left_mobile'] ) ? $advanced['nsa_link_margin_left_mobile'] . $advanced['nsa_link_margin_unit_mobile'] : $margin_left;

$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;

// SETTINGS: Font settings.
$color_default = $colors['never_see_link_static'];
$color_hover   = $colors['never_see_link_hover'];
$color_focus   = $colors['never_see_link_active'];

$font_family     = $typography['never_see_link_font_family'];
$font_size       = $typography['never_see_link_font_size'] . $typography['never_see_link_font_size_unit'];
$font_weight     = $typography['never_see_link_font_weight'];
$font_style      = 'normal';
$alignment       = $typography['never_see_link_alignment'];
$line_height     = $typography['never_see_link_line_height'] . $typography['never_see_link_line_height_unit'];
$letter_spacing  = $typography['never_see_link_letter_spacing'] . $typography['never_see_link_letter_spacing_unit'];
$text_transform  = $typography['never_see_link_text_transform'];
$text_decoration = $typography['never_see_link_text_decoration'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['never_see_link_custom_font_family'] ) ? $typography['never_see_link_custom_font_family'] : 'inherit';
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

$mobile_font_size       = ( '' !== $typography['never_see_link_font_size_mobile'] ) ? $typography['never_see_link_font_size_mobile'] . $typography['never_see_link_font_size_unit_mobile'] : $font_size;
$mobile_font_weight     = $typography['never_see_link_font_weight_mobile'];
$mobile_font_style      = 'normal';
$mobile_alignment       = $typography['never_see_link_alignment_mobile'];
$mobile_line_height     = ( '' !== $typography['never_see_link_line_height_mobile'] ) ? $typography['never_see_link_line_height_mobile'] . $typography['never_see_link_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing  = ( '' !== $typography['never_see_link_letter_spacing_mobile'] ) ? $typography['never_see_link_letter_spacing_mobile'] . $typography['never_see_link_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform  = $typography['never_see_link_text_transform'];
$mobile_text_decoration = $typography['never_see_link_text_decoration_mobile'];

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
// Check if "Never see this again" link is enabled.
if ( '1' === $content['show_never_see_link'] ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $container . ' {';
		$style .= 'margin: ' . $mobile_margin . ';';
		$style .= 'text-align: ' . $mobile_alignment . ';';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'font-size: ' . $mobile_font_size . ';';
		$style .= 'line-height: ' . $mobile_line_height . ';';
		$style .= ( 'inherit' !== $font_family ) ? 'font-family: ' . $font_family . ';' : '';
		$style .= 'letter-spacing: ' . $mobile_letter_spacing . ';';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' a,';
	$style     .= $prefix_mobile . $component . ' a:visited {';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $color_default . ';' : '';
		$style .= 'font-weight: ' . $mobile_font_weight . ';';
		$style .= 'font-style: ' . $mobile_font_style . ';';
		$style .= 'text-transform: ' . $mobile_text_transform . ';';
		$style .= 'text-decoration: ' . $mobile_text_decoration . ';';
	$style     .= '}';

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . ' a:hover {';
			$style .= 'color: ' . $color_hover . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . ' a:focus,';
		$style     .= $prefix_mobile . ' a:active {';
			$style .= 'color: ' . $color_focus . ';';
		$style     .= '}';

	}

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $container . ' {';
				$style .= 'margin: ' . $margin . ';';
				$style .= 'text-align: ' . $alignment . ';';
			$style     .= '}';
			$style     .= $prefix_desktop . $component . ' {';
				$style .= 'font-size: ' . $font_size . ';';
				$style .= 'line-height: ' . $line_height . ';';
				$style .= 'letter-spacing: ' . $letter_spacing . ';';
			$style     .= '}';
			$style     .= $prefix_desktop . $component . ' a {';
				$style .= 'font-weight: ' . $font_weight . ';';
				$style .= 'font-style: ' . $font_style . ';';
				$style .= 'text-transform: ' . $text_transform . ';';
				$style .= 'text-decoration: ' . $text_decoration . ';';
			$style     .= '}';
		$style         .= '}';

	}
}