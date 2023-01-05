<?php
/**
 * Input Form Field.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-field .hustle-input';

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['input_padding_top'] ) ? $advanced['input_padding_top'] . $advanced['input_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['input_padding_right'] ) ? $advanced['input_padding_right'] . $advanced['input_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['input_padding_bottom'] ) ? $advanced['input_padding_bottom'] . $advanced['input_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['input_padding_left'] ) ? $advanced['input_padding_left'] . $advanced['input_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['input_padding_top_mobile'] ) ? $advanced['input_padding_top_mobile'] . $advanced['input_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['input_padding_right_mobile'] ) ? $advanced['input_padding_right_mobile'] . $advanced['input_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['input_padding_bottom_mobile'] ) ? $advanced['input_padding_bottom_mobile'] . $advanced['input_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['input_padding_left_mobile'] ) ? $advanced['input_padding_left_mobile'] . $advanced['input_padding_unit_mobile'] : $padding_left;

$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;
$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['input_border_top'] ) ? $advanced['input_border_top'] . $advanced['input_border_unit'] : '0';
$border_right  = ( '' !== $advanced['input_border_right'] ) ? $advanced['input_border_right'] . $advanced['input_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['input_border_bottom'] ) ? $advanced['input_border_bottom'] . $advanced['input_border_unit'] : '0';
$border_left   = ( '' !== $advanced['input_border_left'] ) ? $advanced['input_border_left'] . $advanced['input_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['input_border_type'];

$border_color_default = $colors['optin_input_static_bo'];
$border_color_hover   = $colors['optin_input_hover_bo'];
$border_color_focus   = $colors['optin_input_active_bo'];
$border_color_error   = $colors['optin_input_error_border'];

$mobile_border_top    = ( '' !== $advanced['input_border_top_mobile'] ) ? $advanced['input_border_top_mobile'] . $advanced['input_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['input_border_right_mobile'] ) ? $advanced['input_border_right_mobile'] . $advanced['input_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['input_border_bottom_mobile'] ) ? $advanced['input_border_bottom_mobile'] . $advanced['input_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['input_border_left_mobile'] ) ? $advanced['input_border_left_mobile'] . $advanced['input_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['input_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['input_radius_top_left'] ) ? $advanced['input_radius_top_left'] . $advanced['input_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['input_radius_top_right'] ) ? $advanced['input_radius_top_right'] . $advanced['input_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['input_radius_bottom_right'] ) ? $advanced['input_radius_bottom_right'] . $advanced['input_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['input_radius_bottom_left'] ) ? $advanced['input_radius_bottom_left'] . $advanced['input_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['input_radius_top_left_mobile'] ) ? $advanced['input_radius_top_left_mobile'] . $advanced['input_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['input_radius_top_right_mobile'] ) ? $advanced['input_radius_top_right_mobile'] . $advanced['input_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['input_radius_bottom_right_mobile'] ) ? $advanced['input_radius_bottom_right_mobile'] . $advanced['input_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['input_radius_bottom_left_mobile'] ) ? $advanced['input_radius_bottom_left_mobile'] . $advanced['input_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;
$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_border_radius;

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['input_drop_shadow_x'] ) ? $advanced['input_drop_shadow_x'] . $advanced['input_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['input_drop_shadow_y'] ) ? $advanced['input_drop_shadow_y'] . $advanced['input_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['input_drop_shadow_blur'] ) ? $advanced['input_drop_shadow_blur'] . $advanced['input_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['input_drop_shadow_spread'] ) ? $advanced['input_drop_shadow_spread'] . $advanced['input_drop_shadow_unit'] : '0';
$shadow_color    = $colors['optin_input_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['input_drop_shadow_x'] ) ? $advanced['input_drop_shadow_x'] . $advanced['input_drop_shadow_unit'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['input_drop_shadow_y'] ) ? $advanced['input_drop_shadow_y'] . $advanced['input_drop_shadow_unit'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['input_drop_shadow_blur'] ) ? $advanced['input_drop_shadow_blur'] . $advanced['input_drop_shadow_unit'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['input_drop_shadow_spread'] ) ? $advanced['input_drop_shadow_spread'] . $advanced['input_drop_shadow_unit'] : $shadow_spread;

$mobile_box_shadow = $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;
$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_box_shadow;

// SETTINGS: Background.
$background_default = $colors['optin_input_static_bg'];
$background_hover   = $colors['optin_input_hover_bg'];
$background_focus   = $colors['optin_input_active_bg'];
$background_error   = $colors['optin_input_error_background'];

// SETTINGS: Font Settings.
$color       = $colors['optin_form_field_text_static_color'];
$placeholder = $colors['optin_placeholder_color'];

$font_family    = $typography['input_font_family'];
$font_size      = $typography['input_font_size'] . $typography['input_font_size_unit'];
$font_weight    = $typography['input_font_weight'];
$font_style     = 'normal';
$alignment      = $typography['input_alignment'];
$line_height    = $typography['input_line_height'] . $typography['input_line_height_unit'];
$letter_spacing = $typography['input_letter_spacing'] . $typography['input_letter_spacing_unit'];
$text_transform = $typography['input_text_transform'];

if ( 'custom' === $font_family ) {
	$font_family = ( '' !== $typography['input_custom_font_family'] ) ? $typography['input_custom_font_family'] : 'inherit';
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

$mobile_font_size      = ( '' !== $typography['input_font_size_mobile'] ) ? $typography['input_font_size_mobile'] . $typography['input_font_size_unit_mobile'] : $font_size;
$mobile_font_weight    = $typography['input_font_weight_mobile'];
$mobile_font_style     = 'normal';
$mobile_alignment      = $typography['input_alignment_mobile'];
$mobile_line_height    = ( '' !== $typography['input_line_height_mobile'] ) ? $typography['input_line_height_mobile'] . $typography['input_line_height_unit_mobile'] : $line_height;
$mobile_letter_spacing = ( '' !== $typography['input_letter_spacing_mobile'] ) ? $typography['input_letter_spacing_mobile'] . $typography['input_letter_spacing_unit_mobile'] : $letter_spacing;
$mobile_text_transform = $typography['input_text_transform'];

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
	$mobile_font_size      = $font_size;
	$mobile_font_weight    = $font_weight;
	$mobile_font_style     = $font_style;
	$mobile_alignment      = $alignment;
	$mobile_line_height    = $line_height;
	$mobile_letter_spacing = $letter_spacing;
	$mobile_text_transform = $text_transform;
}

// SETTINGS: Icons.
$icon_color_default = $colors['optin_input_icon'];
$icon_color_hover   = $colors['optin_input_icon_hover'];
$icon_color_focus   = $colors['optin_input_icon_focus'];
$icon_color_error   = $colors['optin_input_icon_error'];

// ==================================================
// Check if module is an opt-in.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'margin: 0;';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= ( 'none' === $design['form_fields_icon'] ) ? '' : 'padding-left: calc(' . $mobile_padding_left . ' + 25px);';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color_default . ';' : '';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_default . ';' : '';
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
		$style .= 'text-align: ' . $mobile_alignment . ';';
	$style     .= '}';

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $component . ':hover {';
			$style .= 'border-color: ' . $border_color_hover . ';';
			$style .= 'background-color: ' . $background_hover . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ':focus {';
			$style .= 'border-color: ' . $border_color_focus . ';';
			$style .= 'background-color: ' . $background_focus . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . ' .hustle-field-error' . $component . ' {';
			$style .= 'border-color: ' . $border_color_error . ' !important;';
			$style .= 'background-color: ' . $background_error . ' !important;';
		$style     .= '}';

	}

	$style     .= $prefix_mobile . $component . ' + .hustle-input-label {';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: solid;';
		$style .= 'border-color: transparent;';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $placeholder . ';' : '';
		$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $mobile_font_weight . ' ' . $mobile_font_size . '/' . $mobile_line_height . ' ' . $font_family . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $mobile_font_size . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $mobile_line_height . ';' : '';
		$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $mobile_font_weight . ';' : '';
		$style .= 'font-style: ' . $mobile_font_style . ';';
		$style .= 'letter-spacing: ' . $mobile_letter_spacing . ';';
		$style .= 'text-transform: ' . $mobile_text_transform . ';';
		$style .= 'text-align: ' . $mobile_alignment . ';';
	$style     .= '}';

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $component . ' {';
				$style .= 'padding: ' . $padding . ';';
				$style .= ( 'none' === $design['form_fields_icon'] ) ? '' : 'padding-left: calc(' . $padding_left . ' + 25px);';
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
				$style .= 'text-align: ' . $alignment . ';';
			$style     .= '}';
		$style         .= '}';

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $component . ' + .hustle-input-label {';
				$style .= 'padding: ' . $padding . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $font_weight . ' ' . $font_size . '/' . $line_height . ' ' . $font_family . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $font_size . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $line_height . ';' : '';
				$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $font_weight . ';' : '';
				$style .= 'font-style: ' . $font_style . ';';
				$style .= 'letter-spacing: ' . $letter_spacing . ';';
				$style .= 'text-transform: ' . $text_transform . ';';
				$style .= 'text-align: ' . $alignment . ';';
			$style     .= '}';
		$style         .= '}';

	}
}