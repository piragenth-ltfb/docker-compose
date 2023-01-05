<?php
/**
 * Content main wrapper for opt-ins.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-layout .hustle-content';
$component = $container . ' .hustle-content-wrap';

$has_title      = ( '' !== $content['title'] );
$has_subtitle   = ( '' !== $content['sub_title'] );
$has_content    = ( '' !== $content['main_content'] );
$has_cta_button = ( '1' === $content['show_cta'] && '' !== $content['cta_label'] && '' !== $content['cta_url'] );

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['content_wrap_margin_top'] ) ? $advanced['content_wrap_margin_top'] . $advanced['content_wrap_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['content_wrap_margin_right'] ) ? $advanced['content_wrap_margin_right'] . $advanced['content_wrap_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['content_wrap_margin_bottom'] ) ? $advanced['content_wrap_margin_bottom'] . $advanced['content_wrap_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['content_wrap_margin_left'] ) ? $advanced['content_wrap_margin_left'] . $advanced['content_wrap_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['content_wrap_margin_top_mobile'] ) ? $advanced['content_wrap_margin_top_mobile'] . $advanced['content_wrap_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['content_wrap_margin_right_mobile'] ) ? $advanced['content_wrap_margin_right_mobile'] . $advanced['content_wrap_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['content_wrap_margin_bottom_mobile'] ) ? $advanced['content_wrap_margin_bottom_mobile'] . $advanced['content_wrap_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['content_wrap_margin_left_mobile'] ) ? $advanced['content_wrap_margin_left_mobile'] . $advanced['content_wrap_margin_unit_mobile'] : $margin_left;

$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;
$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin;

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['content_wrap_padding_top'] ) ? $advanced['content_wrap_padding_top'] . $advanced['content_wrap_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['content_wrap_padding_right'] ) ? $advanced['content_wrap_padding_right'] . $advanced['content_wrap_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['content_wrap_padding_bottom'] ) ? $advanced['content_wrap_padding_bottom'] . $advanced['content_wrap_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['content_wrap_padding_left'] ) ? $advanced['content_wrap_padding_left'] . $advanced['content_wrap_padding_unit'] : '0';

$mobile_padding_top    = ( '' !== $advanced['content_wrap_padding_top_mobile'] ) ? $advanced['content_wrap_padding_top_mobile'] . $advanced['content_wrap_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['content_wrap_padding_right_mobile'] ) ? $advanced['content_wrap_padding_right_mobile'] . $advanced['content_wrap_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['content_wrap_padding_bottom_mobile'] ) ? $advanced['content_wrap_padding_bottom_mobile'] . $advanced['content_wrap_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['content_wrap_padding_left_mobile'] ) ? $advanced['content_wrap_padding_left_mobile'] . $advanced['content_wrap_padding_unit_mobile'] : $padding_left;

if ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) {
	$mobile_padding_top    = $padding_top;
	$mobile_padding_right  = $padding_right;
	$mobile_padding_bottom = $padding_bottom;
	$mobile_padding_left   = $padding_left;
}

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['content_wrap_border_top'] ) ? $advanced['content_wrap_border_top'] . $advanced['content_wrap_border_unit'] : '0';
$border_right  = ( '' !== $advanced['content_wrap_border_right'] ) ? $advanced['content_wrap_border_right'] . $advanced['content_wrap_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['content_wrap_border_bottom'] ) ? $advanced['content_wrap_border_bottom'] . $advanced['content_wrap_border_unit'] : '0';
$border_left   = ( '' !== $advanced['content_wrap_border_left'] ) ? $advanced['content_wrap_border_left'] . $advanced['content_wrap_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['content_wrap_border_type'];
$border_color = $colors['content_wrap_border'];

$mobile_border_top    = ( '' !== $advanced['content_wrap_border_top_mobile'] ) ? $advanced['content_wrap_border_top_mobile'] . $advanced['content_wrap_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['content_wrap_border_right_mobile'] ) ? $advanced['content_wrap_border_right_mobile'] . $advanced['content_wrap_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['content_wrap_border_bottom_mobile'] ) ? $advanced['content_wrap_border_bottom_mobile'] . $advanced['content_wrap_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['content_wrap_border_left_mobile'] ) ? $advanced['content_wrap_border_left_mobile'] . $advanced['content_wrap_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['content_wrap_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['content_wrap_radius_top_left'] ) ? $advanced['content_wrap_radius_top_left'] . $advanced['content_wrap_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['content_wrap_radius_top_right'] ) ? $advanced['content_wrap_radius_top_right'] . $advanced['content_wrap_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['content_wrap_radius_bottom_right'] ) ? $advanced['content_wrap_radius_bottom_right'] . $advanced['content_wrap_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['content_wrap_radius_bottom_left'] ) ? $advanced['content_wrap_radius_bottom_left'] . $advanced['content_wrap_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['content_wrap_radius_top_left_mobile'] ) ? $advanced['content_wrap_radius_top_left_mobile'] . $advanced['content_wrap_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['content_wrap_radius_top_right_mobile'] ) ? $advanced['content_wrap_radius_top_right_mobile'] . $advanced['content_wrap_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['content_wrap_radius_bottom_right_mobile'] ) ? $advanced['content_wrap_radius_bottom_right_mobile'] . $advanced['content_wrap_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['content_wrap_radius_bottom_left_mobile'] ) ? $advanced['content_wrap_radius_bottom_left_mobile'] . $advanced['content_wrap_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;

// SETTINGS: Background.
$background_color = $colors['content_wrap_bg'];

// ==================================================
// Check if is an opt-in layout.
if ( $has_title || $has_subtitle || $has_content || $has_cta_button ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= ' ';
	$style     .= $prefix_mobile . $container . ' {';
		$style .= 'margin: ' . $mobile_margin . ';';
		$style .= 'padding: 0 ' . $mobile_padding_right . ' 0 ' . $mobile_padding_left . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background_color . ';' : '';
	$style     .= '}';
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'padding: ' . $mobile_padding_top . ' 0 ' . $mobile_padding_bottom . ' 0;';
	$style     .= '}';

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $container . ' {';
				$style .= 'margin: ' . $margin . ';';
				$style .= 'padding: 0 ' . $padding_right . ' 0 ' . $padding_left . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
				$style .= 'border-radius: ' . $border_radius . ';';
			$style     .= '}';
			$style     .= $prefix_desktop . $component . ' {';
				$style .= 'padding: ' . $padding_top . ' 0 ' . $padding_bottom . ' 0;';
			$style     .= '}';
		$style         .= '}';

	}
}