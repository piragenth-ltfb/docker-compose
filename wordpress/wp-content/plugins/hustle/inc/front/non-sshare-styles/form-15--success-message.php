<?php
/**
 * Success Message.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-success';
$component = '.hustle-success-content';

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['success_message_padding_top'] ) ? $advanced['success_message_padding_top'] . $advanced['success_message_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['success_message_padding_right'] ) ? $advanced['success_message_padding_right'] . $advanced['success_message_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['success_message_padding_bottom'] ) ? $advanced['success_message_padding_bottom'] . $advanced['success_message_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['success_message_padding_left'] ) ? $advanced['success_message_padding_left'] . $advanced['success_message_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['success_message_padding_top_mobile'] ) ? $advanced['success_message_padding_top_mobile'] . $advanced['success_message_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['success_message_padding_right_mobile'] ) ? $advanced['success_message_padding_right_mobile'] . $advanced['success_message_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['success_message_padding_bottom_mobile'] ) ? $advanced['success_message_padding_bottom_mobile'] . $advanced['success_message_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['success_message_padding_left_mobile'] ) ? $advanced['success_message_padding_left_mobile'] . $advanced['success_message_padding_unit_mobile'] : $padding_left;

$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;
$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['success_message_border_top'] ) ? $advanced['success_message_border_top'] . $advanced['success_message_border_unit'] : '0';
$border_right  = ( '' !== $advanced['success_message_border_right'] ) ? $advanced['success_message_border_right'] . $advanced['success_message_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['success_message_border_bottom'] ) ? $advanced['success_message_border_bottom'] . $advanced['success_message_border_unit'] : '0';
$border_left   = ( '' !== $advanced['success_message_border_left'] ) ? $advanced['success_message_border_left'] . $advanced['success_message_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = $advanced['success_message_border_type'];
$border_color = $colors['optin_success_border'];

$mobile_border_top    = ( '' !== $advanced['success_message_border_top_mobile'] ) ? $advanced['success_message_border_top_mobile'] . $advanced['success_message_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['success_message_border_right_mobile'] ) ? $advanced['success_message_border_right_mobile'] . $advanced['success_message_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['success_message_border_bottom_mobile'] ) ? $advanced['success_message_border_bottom_mobile'] . $advanced['success_message_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['success_message_border_left_mobile'] ) ? $advanced['success_message_border_left_mobile'] . $advanced['success_message_border_unit_mobile'] : $border_left;

$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['success_message_border_type_mobile'];

// SETTINGS: Border Radius.
$radius_topleft     = ( '' !== $advanced['success_message_radius_top_left'] ) ? $advanced['success_message_radius_top_left'] . $advanced['success_message_radius_unit'] : '0';
$radius_topright    = ( '' !== $advanced['success_message_radius_top_right'] ) ? $advanced['success_message_radius_top_right'] . $advanced['success_message_radius_unit'] : '0';
$radius_bottomright = ( '' !== $advanced['success_message_radius_bottom_right'] ) ? $advanced['success_message_radius_bottom_right'] . $advanced['success_message_radius_unit'] : '0';
$radius_bottomleft  = ( '' !== $advanced['success_message_radius_bottom_left'] ) ? $advanced['success_message_radius_bottom_left'] . $advanced['success_message_radius_unit'] : '0';

$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

$mobile_radius_topleft     = ( '' !== $advanced['success_message_radius_top_left_mobile'] ) ? $advanced['success_message_radius_top_left_mobile'] . $advanced['success_message_radius_unit_mobile'] : $radius_topleft;
$mobile_radius_topright    = ( '' !== $advanced['success_message_radius_top_right_mobile'] ) ? $advanced['success_message_radius_top_right_mobile'] . $advanced['success_message_radius_unit_mobile'] : $radius_topright;
$mobile_radius_bottomright = ( '' !== $advanced['success_message_radius_bottom_right_mobile'] ) ? $advanced['success_message_radius_bottom_right_mobile'] . $advanced['success_message_radius_unit_mobile'] : $radius_bottomright;
$mobile_radius_bottomleft  = ( '' !== $advanced['success_message_radius_bottom_left_mobile'] ) ? $advanced['success_message_radius_bottom_left_mobile'] . $advanced['success_message_radius_unit_mobile'] : $radius_bottomleft;

$mobile_border_radius = $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;
$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_border_radius;

// SETTINGS: Box Shadow.
$shadow_offset_x = ( '' !== $advanced['success_message_drop_shadow_x'] ) ? $advanced['success_message_drop_shadow_x'] . $advanced['success_message_drop_shadow_unit'] : '0';
$shadow_offset_y = ( '' !== $advanced['success_message_drop_shadow_y'] ) ? $advanced['success_message_drop_shadow_y'] . $advanced['success_message_drop_shadow_unit'] : '0';
$shadow_blur     = ( '' !== $advanced['success_message_drop_shadow_blur'] ) ? $advanced['success_message_drop_shadow_blur'] . $advanced['success_message_drop_shadow_unit'] : '0';
$shadow_spread   = ( '' !== $advanced['success_message_drop_shadow_spread'] ) ? $advanced['success_message_drop_shadow_spread'] . $advanced['success_message_drop_shadow_unit'] : '0';
$shadow_color    = $colors['optin_success_drop_shadow'];

$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

$mobile_shadow_offset_x = ( '' !== $advanced['success_message_drop_shadow_x_mobile'] ) ? $advanced['success_message_drop_shadow_x_mobile'] . $advanced['success_message_drop_shadow_unit_mobile'] : $shadow_offset_x;
$mobile_shadow_offset_y = ( '' !== $advanced['success_message_drop_shadow_y_mobile'] ) ? $advanced['success_message_drop_shadow_y_mobile'] . $advanced['success_message_drop_shadow_unit_mobile'] : $shadow_offset_y;
$mobile_shadow_blur     = ( '' !== $advanced['success_message_drop_shadow_blur_mobile'] ) ? $advanced['success_message_drop_shadow_blur_mobile'] . $advanced['success_message_drop_shadow_unit_mobile'] : $shadow_blur;
$mobile_shadow_spread   = ( '' !== $advanced['success_message_drop_shadow_spread_mobile'] ) ? $advanced['success_message_drop_shadow_spread_mobile'] . $advanced['success_message_drop_shadow_unit_mobile'] : $shadow_spread;

$mobile_box_shadow = $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;
$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_box_shadow;

// SETTINGS: Colors.
$background = $colors['optin_success_background'];
$icon_color = $colors['optin_success_tick_color'];

$link_default = $colors['link_static_color'];
$link_hover   = $colors['link_hover_color'];
$link_focus   = $colors['link_active_color'];

$font_color = $colors['optin_success_content_color'];

// ==================================================
// Check if module is an opt-in.
if ( $is_optin ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $container . ' {';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= 'border-radius: ' . $mobile_border_radius . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'background-color: ' . $background . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $font_color . ';' : '';
	$style     .= '}';

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $container . ' [class*="hustle-icon-"] {';
			$style .= 'color: ' . $icon_color . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $container . ' a,';
		$style     .= $prefix_mobile . $container . ' a:visited {';
			$style .= 'color: ' . $link_default . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $container . ' a:hover {';
			$style .= 'color: ' . $link_hover . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $container . ' a:focus,';
		$style     .= $prefix_mobile . $container . ' a:active {';
			$style .= 'color: ' . $link_focus . ';';
		$style     .= '}';

	}

	$style     .= $prefix_mobile . $component . ' b,';
	$style     .= $prefix_mobile . $component . ' strong {';
		$style .= 'font-weight: bold;';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' blockquote {';
		$style .= 'margin-right: 0;';
		$style .= 'margin-left: 0;';
	$style     .= '}';

	// Desktop styles.
	if ( $is_mobile_enabled ) {
		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $container . ' {';
				$style .= 'padding: ' . $padding . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
				$style .= 'border-radius: ' . $border_radius . ';';
				$style .= 'box-shadow: ' . $box_shadow . ';';
				$style .= '-moz-box-shadow: ' . $box_shadow . ';';
				$style .= '-webkit-box-shadow: ' . $box_shadow . ';';
			$style     .= '}';
		$style         .= '}';
	}
}

// ==================================================
// Add styles for typography.
$typos = array(
	'p'  => 'paragraph',
	'h1' => 'heading_one',
	'h2' => 'heading_two',
	'h3' => 'heading_three',
	'h4' => 'heading_four',
	'h5' => 'heading_five',
	'h6' => 'heading_six',
	'li' => 'lists',
);

foreach ( $typos as $key => $prop ) {

	$prop_prefix = 'success_message';

	// DESKTOP: Basic.
	$font_family = $typography[ $prop_prefix . '_' . $prop . '_font_family' ];
	$font_size   = $typography[ $prop_prefix . '_' . $prop . '_font_size' ];
	$font_size   = ( '' !== $font_size ) ? $font_size . $typography[ $prop_prefix . '_' . $prop . '_font_size_unit' ] : '0';
	$font_weight = $typography[ $prop_prefix . '_' . $prop . '_font_weight' ];
	$font_weight = ( 'regular' === $font_weight ) ? 'normal' : $font_weight;

	// MOBILE: Basic.
	$mobile_font_size   = $typography[ $prop_prefix . '_' . $prop . '_font_size_mobile' ];
	$mobile_font_size   = ( '' !== $font_size ) ? $font_size . $typography[ $prop_prefix . '_' . $prop . '_font_size_unit_mobile' ] : $font_size;
	$mobile_font_weight = $typography[ $prop_prefix . '_' . $prop . '_font_weight_mobile' ];
	$mobile_font_weight = ( 'regular' === $mobile_font_weight ) ? 'normal' : $mobile_font_weight;

	if ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_typography ) ) {
		$mobile_font_size   = $font_size;
		$mobile_font_weight = $font_weight;
	}

	// DESKTOP: Advanced.
	$line_height     = $typography[ $prop_prefix . '_' . $prop . '_line_height' ];
	$line_height     = ( '' !== $line_height ) ? $line_height . $typography[ $prop_prefix . '_' . $prop . '_line_height_unit' ] : '0';
	$letter_spacing  = $typography[ $prop_prefix . '_' . $prop . '_letter_spacing' ];
	$letter_spacing  = ( '' !== $letter_spacing ) ? $letter_spacing . $typography[ $prop_prefix . '_' . $prop . '_letter_spacing_unit' ] : '0';
	$text_transform  = $typography[ $prop_prefix . '_' . $prop . '_text_transform' ];
	$text_decoration = $typography[ $prop_prefix . '_' . $prop . '_text_decoration' ];

	// MOBILE: Advanced.
	$mobile_line_height     = $typography[ $prop_prefix . '_' . $prop . '_line_height_mobile' ];
	$mobile_line_height     = ( '' !== $line_height ) ? $line_height . $typography[ $prop_prefix . '_' . $prop . '_line_height_unit_mobile' ] : $line_height;
	$mobile_letter_spacing  = $typography[ $prop_prefix . '_' . $prop . '_letter_spacing_mobile' ];
	$mobile_letter_spacing  = ( '' !== $letter_spacing ) ? $letter_spacing . $typography[ $prop_prefix . '_' . $prop . '_letter_spacing_unit_mobile' ] : $letter_spacing;
	$mobile_text_transform  = $typography[ $prop_prefix . '_' . $prop . '_text_transform_mobile' ];
	$mobile_text_decoration = $typography[ $prop_prefix . '_' . $prop . '_text_decoration_mobile' ];

	if ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_typography ) ) {
		$mobile_line_height     = $line_height;
		$mobile_letter_spacing  = $letter_spacing;
		$mobile_text_transform  = $text_transform;
		$mobile_text_decoration = $text_decoration;
	}

	// Check if success message is not empty.
	if ( '' !== $emails['success_message'] ) {

		if ( 'li' === $key ) {
			$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]),';
			$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]) {';
				$style .= 'margin: 0 0 10px;';
			$style     .= '}';
			$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]):last-child,';
			$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]):last-child {';
				$style .= 'margin-bottom: 0;';
			$style     .= '}';
		}

		if ( 'p' === $key ) {

			// Mobile styles.
			$style     .= $prefix_mobile . $component . ' {';
				$style .= ( ! $is_vanilla ) ? 'color: ' . $font_color . ';' : '';
				$style .= 'font-size: ' . $mobile_font_size . ';';
				$style .= 'line-height: ' . $mobile_line_height . ';';
				$style .= 'font-family: ' . $font_family . ';';
			$style     .= '}';

			// Desktop styles.
			if ( $is_mobile_enabled ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'font-size: ' . $font_size . ';';
						$style .= 'line-height: ' . $line_height . ';';
					$style     .= '}';
				$style         .= '}';
			}
		}

		// Mobile styles.
		$style     .= $prefix_mobile . $component . ' ' . $key . ':not([class*="forminator-"]) {';
			$style .= ( 'li' === $key ) ? 'margin: 0 0 5px;' : 'margin: 0 0 10px;';
			$style .= ( ! $is_vanilla ) ? 'color: ' . $font_color . ';' : '';
			$style .= 'font: ' . $mobile_font_weight . ' ' . $mobile_font_size . '/' . $mobile_line_height . ' ' . $font_family . ';';
			$style .= 'letter-spacing: ' . $mobile_letter_spacing . ';';
			$style .= 'text-transform: ' . $mobile_text_transform . ';';
			$style .= 'text-decoration: ' . $mobile_text_decoration . ';';
		$style     .= '}';
		$style     .= $prefix_mobile . $component . ' ' . $key . ':not([class*="forminator-"]):last-child {';
			$style .= 'margin-bottom: 0;';
		$style     .= '}';

		if ( 'li' === $key && ! $is_vanilla ) {
			$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]) ' . $key . ':before {';
				$style .= 'color: ' . $colors['ol_counter'];
			$style     .= '}';
			$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]) ' . $key . ':before {';
				$style .= 'color: ' . $colors['ul_bullets'];
			$style     .= '}';
		}

		// Desktop styles.
		if ( $is_mobile_enabled ) {

			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' ' . $key . ':not([class*="forminator-"]) {';
					$style .= ( 'li' !== $key ) ? 'margin-bottom: 20px;' : '';
					$style .= 'font: ' . $font_weight . ' ' . $font_size . '/' . $line_height . ' ' . $font_family . ';';
					$style .= 'letter-spacing: ' . $letter_spacing . ';';
					$style .= 'text-transform: ' . $text_transform . ';';
					$style .= 'text-decoration: ' . $text_decoration . ';';
				$style     .= '}';
				$style     .= ( 'li' !== $key ) ? $prefix_desktop . $component . ' ' . $key . ':not([class*="forminator-"]):last-child {' : '';
					$style .= ( 'li' !== $key ) ? 'margin-bottom: 0' : '';
				$style     .= ( 'li' !== $key ) ? '}' : '';
			$style         .= '}';

			if ( 'li' === $key ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]),';
					$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]) {';
						$style .= 'margin: 0 0 20px;';
					$style     .= '}';
					$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]):last-child,';
					$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]):last-child {';
						$style .= 'margin-bottom: 0;';
					$style     .= '}';
				$style         .= '}';
			}
		}
	}
}