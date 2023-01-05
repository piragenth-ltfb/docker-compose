<?php
/**
 * Main content.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-layout .hustle-group-content';

// SETTINGS: Margin.
$margin_top    = ( '' !== $advanced['main_content_margin_top'] ) ? $advanced['main_content_margin_top'] . $advanced['main_content_margin_unit'] : '0';
$margin_right  = ( '' !== $advanced['main_content_margin_right'] ) ? $advanced['main_content_margin_right'] . $advanced['main_content_margin_unit'] : '0';
$margin_bottom = ( '' !== $advanced['main_content_margin_bottom'] ) ? $advanced['main_content_margin_bottom'] . $advanced['main_content_margin_unit'] : '0';
$margin_left   = ( '' !== $advanced['main_content_margin_left'] ) ? $advanced['main_content_margin_left'] . $advanced['main_content_margin_unit'] : '0';

$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

$mobile_margin_top    = ( '' !== $advanced['main_content_margin_top_mobile'] ) ? $advanced['main_content_margin_top_mobile'] . $advanced['main_content_margin_unit_mobile'] : $margin_top;
$mobile_margin_right  = ( '' !== $advanced['main_content_margin_right_mobile'] ) ? $advanced['main_content_margin_right_mobile'] . $advanced['main_content_margin_unit_mobile'] : $margin_right;
$mobile_margin_bottom = ( '' !== $advanced['main_content_margin_bottom_mobile'] ) ? $advanced['main_content_margin_bottom_mobile'] . $advanced['main_content_margin_unit_mobile'] : $margin_bottom;
$mobile_margin_left   = ( '' !== $advanced['main_content_margin_left_mobile'] ) ? $advanced['main_content_margin_left_mobile'] . $advanced['main_content_margin_unit_mobile'] : $margin_left;

$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;
$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin;

// SETTINGS: Padding.
$padding_top    = ( '' !== $advanced['main_content_padding_top'] ) ? $advanced['main_content_padding_top'] . $advanced['main_content_padding_unit'] : '0';
$padding_right  = ( '' !== $advanced['main_content_padding_right'] ) ? $advanced['main_content_padding_right'] . $advanced['main_content_padding_unit'] : '0';
$padding_bottom = ( '' !== $advanced['main_content_padding_bottom'] ) ? $advanced['main_content_padding_bottom'] . $advanced['main_content_padding_unit'] : '0';
$padding_left   = ( '' !== $advanced['main_content_padding_left'] ) ? $advanced['main_content_padding_left'] . $advanced['main_content_padding_unit'] : '0';

$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

$mobile_padding_top    = ( '' !== $advanced['main_content_padding_top_mobile'] ) ? $advanced['main_content_padding_top_mobile'] . $advanced['main_content_padding_unit_mobile'] : $padding_top;
$mobile_padding_right  = ( '' !== $advanced['main_content_padding_right_mobile'] ) ? $advanced['main_content_padding_right_mobile'] . $advanced['main_content_padding_unit_mobile'] : $padding_right;
$mobile_padding_bottom = ( '' !== $advanced['main_content_padding_bottom_mobile'] ) ? $advanced['main_content_padding_bottom_mobile'] . $advanced['main_content_padding_unit_mobile'] : $padding_bottom;
$mobile_padding_left   = ( '' !== $advanced['main_content_padding_left_mobile'] ) ? $advanced['main_content_padding_left_mobile'] . $advanced['main_content_padding_unit_mobile'] : $padding_left;

$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;
$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding;

// SETTINGS: Border.
$border_top    = ( '' !== $advanced['main_content_border_top'] ) ? $advanced['main_content_border_top'] . $advanced['main_content_border_unit'] : '0';
$border_right  = ( '' !== $advanced['main_content_border_right'] ) ? $advanced['main_content_border_right'] . $advanced['main_content_border_unit'] : '0';
$border_bottom = ( '' !== $advanced['main_content_border_bottom'] ) ? $advanced['main_content_border_bottom'] . $advanced['main_content_border_unit'] : '0';
$border_left   = ( '' !== $advanced['main_content_border_left'] ) ? $advanced['main_content_border_left'] . $advanced['main_content_border_unit'] : '0';

$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
$border_style = ( '' !== $advanced['main_content_border_type'] ) ? $advanced['main_content_border_type'] : 'solid';
$border_color = $colors['content_border'];

$mobile_border_top    = ( '' !== $advanced['main_content_border_top_mobile'] ) ? $advanced['main_content_border_top_mobile'] . $advanced['main_content_border_unit_mobile'] : $border_top;
$mobile_border_right  = ( '' !== $advanced['main_content_border_right_mobile'] ) ? $advanced['main_content_border_right_mobile'] . $advanced['main_content_border_unit_mobile'] : $border_right;
$mobile_border_bottom = ( '' !== $advanced['main_content_border_bottom_mobile'] ) ? $advanced['main_content_border_bottom_mobile'] . $advanced['main_content_border_unit_mobile'] : $border_bottom;
$mobile_border_left   = ( '' !== $advanced['main_content_border_left_mobile'] ) ? $advanced['main_content_border_left_mobile'] . $advanced['main_content_border_unit_mobile'] : $border_left;

$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['main_content_border_type_mobile'];

// SETTINGS: Colors.
$font_color   = $colors['content_color'];
$link_default = $colors['link_static_color'];
$link_hover   = $colors['link_hover_color'];
$link_focus   = $colors['link_active_color'];

// ==================================================
// Check if main content is not empty.
if ( '' !== $content['main_content'] ) {

	$style .= ' ';

	// Mobile styles.
	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'margin: ' . $mobile_margin . ';';
		$style .= 'padding: ' . $mobile_padding . ';';
		$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
		$style .= 'border-width: ' . $mobile_border_width . ';';
		$style .= 'border-style: ' . $mobile_border_style . ';';
		$style .= ( ! $is_vanilla ) ? 'color: ' . $font_color . ';' : '';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ' b,';
	$style     .= $prefix_mobile . $component . ' strong {';
		$style .= 'font-weight: bold;';
	$style     .= '}';

	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $component . ' a,';
		$style     .= $prefix_mobile . $component . ' a:visited {';
			$style .= 'color: ' . $link_default . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' a:hover {';
			$style .= 'color: ' . $link_hover . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' a:focus,';
		$style     .= $prefix_mobile . $component . ' a:active {';
			$style .= 'color: ' . $link_focus . ';';
		$style     .= '}';

	}

	// Desktop styles.
	if ( $is_mobile_enabled ) {
		$style         .= $breakpoint . ' {';
			$style     .= $prefix_desktop . $component . ' {';
				$style .= 'margin: ' . $margin . ';';
				$style .= 'padding: ' . $padding . ';';
				$style .= 'border-width: ' . $border_width . ';';
				$style .= 'border-style: ' . $border_style . ';';
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
	$font_family     = $typography[ 'main_content_' . $prop . '_font_family' ];
	$font_size       = ( '' !== $typography[ 'main_content_' . $prop . '_font_size' ] ) ? $typography[ 'main_content_' . $prop . '_font_size' ] . $typography[ 'main_content_' . $prop . '_font_size_unit' ] : '0';
	$font_weight     = $typography[ 'main_content_' . $prop . '_font_weight' ];
	$font_style      = 'normal';
	$line_height     = ( '' !== $typography[ 'main_content_' . $prop . '_line_height' ] ) ? $typography[ 'main_content_' . $prop . '_line_height' ] . $typography[ 'main_content_' . $prop . '_line_height_unit' ] : '0';
	$letter_spacing  = ( '' !== $typography[ 'main_content_' . $prop . '_letter_spacing' ] ) ? $typography[ 'main_content_' . $prop . '_letter_spacing' ] . $typography[ 'main_content_' . $prop . '_letter_spacing_unit' ] : '0';
	$text_transform  = $typography[ 'main_content_' . $prop . '_text_transform' ];
	$text_decoration = $typography[ 'main_content_' . $prop . '_text_decoration' ];

	if ( 'custom' === $font_family ) {
		$font_family = ( '' !== $typography[ 'main_content_' . $prop . '_custom_font_family' ] ) ? $typography[ 'main_content_' . $prop . '_custom_font_family' ] : 'inherit';
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

	$mobile_font_size       = ( '' !== $typography[ 'main_content_' . $prop . '_font_size_mobile' ] ) ? $typography[ 'main_content_' . $prop . '_font_size_mobile' ] . $typography[ 'main_content_' . $prop . '_font_size_unit_mobile' ] : $font_size;
	$mobile_font_weight     = $typography[ 'main_content_' . $prop . '_font_weight_mobile' ];
	$mobile_font_style      = 'normal';
	$mobile_line_height     = ( '' !== $typography[ 'main_content_' . $prop . '_line_height_mobile' ] ) ? $typography[ 'main_content_' . $prop . '_line_height_mobile' ] . $typography[ 'main_content_' . $prop . '_line_height_unit_mobile' ] : $line_height;
	$mobile_letter_spacing  = ( '' !== $typography[ 'main_content_' . $prop . '_letter_spacing_mobile' ] ) ? $typography[ 'main_content_' . $prop . '_letter_spacing_mobile' ] . $typography[ 'main_content_' . $prop . '_letter_spacing_unit_mobile' ] : $letter_spacing;
	$mobile_text_transform  = $typography[ 'main_content_' . $prop . '_text_transform_mobile' ];
	$mobile_text_decoration = $typography[ 'main_content_' . $prop . '_text_decoration_mobile' ];

	if ( 'regular' === $font_weight ) {
		$font_weight = 'normal';
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

	// Check if main content is not empty.
	if ( '' !== $content['main_content'] ) {

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
			$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $mobile_font_weight . ' ' . $mobile_font_size . '/' . $mobile_line_height . ' ' . $font_family . ';' : '';
			$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $mobile_font_size . ';' : '';
			$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $mobile_line_height . ';' : '';
			$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $mobile_font_weight . ';' : '';
			$style .= 'font-style: ' . $mobile_font_style . ';';
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
				$style .= 'background-color: ' . $colors['ul_bullets'];
			$style     .= '}';

		}

		// Desktop styles.
		if ( $is_mobile_enabled ) {

			if ( 'li' === $key ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]),';
					$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]) {';
						$style .= 'margin: 0 0 20px;';
					$style     .= '}';
					$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]):last-child,';
					$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]):last-child {';
						$style .= 'margin: 0;';
					$style     .= '}';
				$style         .= '}';
			}

			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' ' . $key . ':not([class*="forminator-"]) {';
					$style .= ( 'li' === $key ) ? 'margin: 0 0 5px;' : 'margin: 0 0 10px;';
					$style .= ( 'inherit' !== $font_family ) ? 'font: ' . $font_weight . ' ' . $font_size . '/' . $line_height . ' ' . $font_family . ';' : '';
					$style .= ( 'inherit' === $font_family ) ? 'font-size: ' . $font_size . ';' : '';
					$style .= ( 'inherit' === $font_family ) ? 'line-height: ' . $line_height . ';' : '';
					$style .= ( 'inherit' === $font_family ) ? 'font-weight: ' . $font_weight . ';' : '';
					$style .= 'font-style: ' . $font_style . ';';
					$style .= 'letter-spacing: ' . $letter_spacing . ';';
					$style .= 'text-transform: ' . $text_transform . ';';
					$style .= 'text-decoration: ' . $text_decoration . ';';
				$style     .= '}';
				$style     .= $prefix_desktop . $component . ' ' . $key . ':not([class*="forminator-"]):last-child {';
					$style .= 'margin-bottom: 0;';
				$style     .= '}';
			$style         .= '}';

		} else {

			if ( 'li' === $key ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]),';
					$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]) {';
						$style .= 'margin: 0 0 20px;';
					$style     .= '}';
					$style     .= $prefix_mobile . $component . ' ol:not([class*="forminator-"]):last-child,';
					$style     .= $prefix_mobile . $component . ' ul:not([class*="forminator-"]):last-child {';
						$style .= 'margin: 0;';
					$style     .= '}';
				$style         .= '}';
			}

			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' ' . $key . ':not([class*="forminator-"]) {';
					$style .= ( 'li' === $key ) ? 'margin: 0 0 5px;' : 'margin: 0 0 10px;';
				$style     .= '}';
				$style     .= $prefix_desktop . $component . ' ' . $key . ':not([class*="forminator-"]):last-child {';
					$style .= 'margin-bottom: 0;';
				$style     .= '}';
			$style         .= '}';

		}
	}
}

if ( '' !== $content['main_content'] ) {
	$style     .= $prefix_mobile . $component . ' blockquote {';
		$style .= 'margin-right: 0;';
		$style .= 'margin-left: 0;';
	$style     .= '}';
}