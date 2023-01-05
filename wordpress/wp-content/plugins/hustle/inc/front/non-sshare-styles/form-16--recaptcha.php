<?php
/**
 * Recaptcha custom styles.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

// Check if module has recaptcha and its badge is hidden.
if ( ! empty( $form_fields['recaptcha'] ) ) {

	$recaptcha       = $form_fields['recaptcha'];
	$is_badge_hidden = false;
	if ( 'v2_invisible' === $recaptcha['version'] ) {
		$is_badge_hidden = '1' !== $recaptcha['v2_invisible_show_badge'];
	} elseif ( 'v3_recaptcha' === $recaptcha['version'] ) {
		$is_badge_hidden = '1' !== $recaptcha['v3_recaptcha_show_badge'];
	}

	if ( $is_badge_hidden ) {
		// SETTINGS: Margin.
		$margin_top    = ( '' !== $advanced['recaptcha_margin_top'] ) ? $advanced['recaptcha_margin_top'] . $advanced['recaptcha_margin_unit'] : '0';
		$margin_right  = ( '' !== $advanced['recaptcha_margin_right'] ) ? $advanced['recaptcha_margin_right'] . $advanced['recaptcha_margin_unit'] : '0';
		$margin_bottom = ( '' !== $advanced['recaptcha_margin_bottom'] ) ? $advanced['recaptcha_margin_bottom'] . $advanced['recaptcha_margin_unit'] : '0';
		$margin_left   = ( '' !== $advanced['recaptcha_margin_left'] ) ? $advanced['recaptcha_margin_left'] . $advanced['recaptcha_margin_unit'] : '0';

		$margin = $margin_top . ' ' . $margin_right . ' ' . $margin_bottom . ' ' . $margin_left;

		$mobile_margin_top    = ( '' !== $advanced['recaptcha_margin_top_mobile'] ) ? $advanced['recaptcha_margin_top_mobile'] . $advanced['recaptcha_margin_unit_mobile'] : $margin_top;
		$mobile_margin_right  = ( '' !== $advanced['recaptcha_margin_right_mobile'] ) ? $advanced['recaptcha_margin_right_mobile'] . $advanced['recaptcha_margin_unit_mobile'] : $margin_right;
		$mobile_margin_bottom = ( '' !== $advanced['recaptcha_margin_bottom_mobile'] ) ? $advanced['recaptcha_margin_bottom_mobile'] . $advanced['recaptcha_margin_unit_mobile'] : $margin_bottom;
		$mobile_margin_left   = ( '' !== $advanced['recaptcha_margin_left_mobile'] ) ? $advanced['recaptcha_margin_left_mobile'] . $advanced['recaptcha_margin_unit_mobile'] : $margin_left;

		$mobile_margin = $mobile_margin_top . ' ' . $mobile_margin_right . ' ' . $mobile_margin_bottom . ' ' . $mobile_margin_left;
		$mobile_margin = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $margin : $mobile_margin;

		// SETTINGS: Padding.
		$padding_top    = ( '' !== $advanced['recaptcha_padding_top'] ) ? $advanced['recaptcha_padding_top'] . $advanced['recaptcha_padding_unit'] : '0';
		$padding_right  = ( '' !== $advanced['recaptcha_padding_right'] ) ? $advanced['recaptcha_padding_right'] . $advanced['recaptcha_padding_unit'] : '0';
		$padding_bottom = ( '' !== $advanced['recaptcha_padding_bottom'] ) ? $advanced['recaptcha_padding_bottom'] . $advanced['recaptcha_padding_unit'] : '0';
		$padding_left   = ( '' !== $advanced['recaptcha_padding_left'] ) ? $advanced['recaptcha_padding_left'] . $advanced['recaptcha_padding_unit'] : '0';

		$padding = $padding_top . ' ' . $padding_right . ' ' . $padding_bottom . ' ' . $padding_left;

		$mobile_padding_top    = ( '' !== $advanced['recaptcha_padding_top_mobile'] ) ? $advanced['recaptcha_padding_top_mobile'] . $advanced['recaptcha_padding_unit_mobile'] : $padding_top;
		$mobile_padding_right  = ( '' !== $advanced['recaptcha_padding_right_mobile'] ) ? $advanced['recaptcha_padding_right_mobile'] . $advanced['recaptcha_padding_unit_mobile'] : $padding_right;
		$mobile_padding_bottom = ( '' !== $advanced['recaptcha_padding_bottom_mobile'] ) ? $advanced['recaptcha_padding_bottom_mobile'] . $advanced['recaptcha_padding_unit_mobile'] : $padding_bottom;
		$mobile_padding_left   = ( '' !== $advanced['recaptcha_padding_left_mobile'] ) ? $advanced['recaptcha_padding_left_mobile'] . $advanced['recaptcha_padding_unit_mobile'] : $padding_left;

		$mobile_padding = $mobile_padding_top . ' ' . $mobile_padding_right . ' ' . $mobile_padding_bottom . ' ' . $mobile_padding_left;
		$mobile_padding = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $padding : $mobile_padding;

		// SETTINGS: Border.
		$border_top    = ( '' !== $advanced['recaptcha_border_top'] ) ? $advanced['recaptcha_border_top'] . $advanced['recaptcha_border_unit'] : '0';
		$border_right  = ( '' !== $advanced['recaptcha_border_right'] ) ? $advanced['recaptcha_border_right'] . $advanced['recaptcha_border_unit'] : '0';
		$border_bottom = ( '' !== $advanced['recaptcha_border_bottom'] ) ? $advanced['recaptcha_border_bottom'] . $advanced['recaptcha_border_unit'] : '0';
		$border_left   = ( '' !== $advanced['recaptcha_border_left'] ) ? $advanced['recaptcha_border_left'] . $advanced['recaptcha_border_unit'] : '0';

		$border_width = $border_top . ' ' . $border_right . ' ' . $border_bottom . ' ' . $border_left;
		$border_style = $advanced['recaptcha_border_type'];
		$border_color = $colors['recaptcha_copy_border'];

		$mobile_border_top    = ( '' !== $advanced['recaptcha_border_top_mobile'] ) ? $advanced['recaptcha_border_top_mobile'] . $advanced['recaptcha_border_unit_mobile'] : $border_top;
		$mobile_border_right  = ( '' !== $advanced['recaptcha_border_right_mobile'] ) ? $advanced['recaptcha_border_right_mobile'] . $advanced['recaptcha_border_unit_mobile'] : $border_right;
		$mobile_border_bottom = ( '' !== $advanced['recaptcha_border_bottom_mobile'] ) ? $advanced['recaptcha_border_bottom_mobile'] . $advanced['recaptcha_border_unit_mobile'] : $border_bottom;
		$mobile_border_left   = ( '' !== $advanced['recaptcha_border_left_mobile'] ) ? $advanced['recaptcha_border_left_mobile'] . $advanced['recaptcha_border_unit_mobile'] : $border_left;

		$mobile_border_width = $mobile_border_top . ' ' . $mobile_border_right . ' ' . $mobile_border_bottom . ' ' . $mobile_border_left;
		$mobile_border_width = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_width : $mobile_border_width;
		$mobile_border_style = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_style : $advanced['recaptcha_border_type_mobile'];

		// SETTINGS: Border Radius.
		$radius_topleft     = ( '' !== $advanced['recaptcha_radius_top_left'] ) ? $advanced['recaptcha_radius_top_left'] . $advanced['recaptcha_radius_unit'] : '0';
		$radius_topright    = ( '' !== $advanced['recaptcha_radius_top_right'] ) ? $advanced['recaptcha_radius_top_right'] . $advanced['recaptcha_radius_unit'] : '0';
		$radius_bottomright = ( '' !== $advanced['recaptcha_radius_bottom_right'] ) ? $advanced['recaptcha_radius_bottom_right'] . $advanced['recaptcha_radius_unit'] : '0';
		$radius_bottomleft  = ( '' !== $advanced['recaptcha_radius_bottom_left'] ) ? $advanced['recaptcha_radius_bottom_left'] . $advanced['recaptcha_radius_unit'] : '0';

		$border_radius = $radius_topleft . ' ' . $radius_topright . ' ' . $radius_bottomright . ' ' . $radius_bottomleft;

		$mobile_radius_topleft     = ( '' !== $advanced['recaptcha_radius_top_left_mobile'] ) ? $advanced['recaptcha_radius_top_left_mobile'] . $advanced['recaptcha_radius_unit_mobile'] : $radius_topleft;
		$mobile_radius_topright    = ( '' !== $advanced['recaptcha_radius_top_right_mobile'] ) ? $advanced['recaptcha_radius_top_right_mobile'] . $advanced['recaptcha_radius_unit_mobile'] : $radius_topright;
		$mobile_radius_bottomright = ( '' !== $advanced['recaptcha_radius_bottom_right_mobile'] ) ? $advanced['recaptcha_radius_bottom_right_mobile'] . $advanced['recaptcha_radius_unit_mobile'] : $radius_bottomright;
		$mobile_radius_bottomleft  = ( '' !== $advanced['recaptcha_radius_bottom_left_mobile'] ) ? $advanced['recaptcha_radius_bottom_left_mobile'] . $advanced['recaptcha_radius_unit_mobile'] : $radius_bottomleft;

		$mobile_border_radius = $mobile_radius_topleft . ' ' . $mobile_radius_topright . ' ' . $mobile_radius_bottomright . ' ' . $mobile_radius_bottomleft;
		$mobile_border_radius = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $border_radius : $mobile_border_radius;

		// SETTINGS: Box Shadow.
		$shadow_offset_x = ( '' !== $advanced['recaptcha_drop_shadow_x'] ) ? $advanced['recaptcha_drop_shadow_x'] . $advanced['recaptcha_drop_shadow_unit'] : '0';
		$shadow_offset_y = ( '' !== $advanced['recaptcha_drop_shadow_y'] ) ? $advanced['recaptcha_drop_shadow_y'] . $advanced['recaptcha_drop_shadow_unit'] : '0';
		$shadow_blur     = ( '' !== $advanced['recaptcha_drop_shadow_blur'] ) ? $advanced['recaptcha_drop_shadow_blur'] . $advanced['recaptcha_drop_shadow_unit'] : '0';
		$shadow_spread   = ( '' !== $advanced['recaptcha_drop_shadow_spread'] ) ? $advanced['recaptcha_drop_shadow_spread'] . $advanced['recaptcha_drop_shadow_unit'] : '0';
		$shadow_color    = $colors['recaptcha_copy_drop_shadow'];

		$box_shadow = $shadow_offset_x . ' ' . $shadow_offset_y . ' ' . $shadow_blur . ' ' . $shadow_spread . ' ' . $shadow_color;

		$mobile_shadow_offset_x = ( '' !== $advanced['recaptcha_drop_shadow_x_mobile'] ) ? $advanced['recaptcha_drop_shadow_x_mobile'] . $advanced['recaptcha_drop_shadow_unit_mobile'] : $shadow_offset_x;
		$mobile_shadow_offset_y = ( '' !== $advanced['recaptcha_drop_shadow_y_mobile'] ) ? $advanced['recaptcha_drop_shadow_y_mobile'] . $advanced['recaptcha_drop_shadow_unit_mobile'] : $shadow_offset_y;
		$mobile_shadow_blur     = ( '' !== $advanced['recaptcha_drop_shadow_blur_mobile'] ) ? $advanced['recaptcha_drop_shadow_blur_mobile'] . $advanced['recaptcha_drop_shadow_unit_mobile'] : $shadow_blur;
		$mobile_shadow_spread   = ( '' !== $advanced['recaptcha_drop_shadow_spread_mobile'] ) ? $advanced['recaptcha_drop_shadow_spread_mobile'] . $advanced['recaptcha_drop_shadow_unit_mobile'] : $shadow_spread;

		$mobile_box_shadow = $mobile_shadow_offset_x . ' ' . $mobile_shadow_offset_y . ' ' . $mobile_shadow_blur . ' ' . $mobile_shadow_spread . ' ' . $shadow_color;
		$mobile_box_shadow = ( ! $is_mobile_enabled || ( $is_mobile_enabled && $default_advanced ) ) ? $box_shadow : $mobile_box_shadow;

		// SETTINGS: Colors.
		$text_color   = $colors['recaptcha_copy_text'];
		$link_default = $colors['recaptcha_copy_link_default'];
		$link_hover   = $colors['recaptcha_copy_link_hover'];
		$link_focus   = $colors['recaptcha_copy_link_focus'];

		// SETTINGS: Font settings.
		$font_family     = $typography['recaptcha_font_family'];
		$font_size       = $typography['recaptcha_font_size'] . $typography['recaptcha_font_size_unit'];
		$font_weight     = $typography['recaptcha_font_weight'];
		$font_style      = 'normal';
		$alignment       = $typography['recaptcha_alignment'];
		$line_height     = $typography['recaptcha_line_height'] . $typography['recaptcha_line_height_unit'];
		$letter_spacing  = $typography['recaptcha_letter_spacing'] . $typography['recaptcha_letter_spacing_unit'];
		$text_transform  = $typography['recaptcha_text_transform'];
		$text_decoration = $typography['recaptcha_text_decoration'];

		if ( 'custom' === $font_family ) {
			$font_family = ( '' !== $typography['recaptcha_custom_font_family'] ) ? $typography['recaptcha_custom_font_family'] : 'inherit';
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

		$mobile_font_size       = ( '' !== $typography['recaptcha_font_size_mobile'] ) ? $typography['recaptcha_font_size_mobile'] . $typography['recaptcha_font_size_unit_mobile'] : $font_size;
		$mobile_font_weight     = $typography['recaptcha_font_weight_mobile'];
		$mobile_font_style      = 'normal';
		$mobile_alignment       = $typography['recaptcha_alignment_mobile'];
		$mobile_line_height     = ( '' !== $typography['recaptcha_line_height_mobile'] ) ? $typography['recaptcha_line_height_mobile'] . $typography['recaptcha_line_height_unit_mobile'] : $line_height;
		$mobile_letter_spacing  = ( '' !== $typography['recaptcha_letter_spacing_mobile'] ) ? $typography['recaptcha_letter_spacing_mobile'] . $typography['recaptcha_letter_spacing_unit_mobile'] : $letter_spacing;
		$mobile_text_transform  = $typography['recaptcha_text_transform'];
		$mobile_text_decoration = $typography['recaptcha_text_decoration_mobile'];

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

		$component = '.hustle-layout .hustle-recaptcha-copy';

		$style .= '';

		$style     .= $prefix_mobile . $component . ' {';
			$style .= 'margin: ' . $mobile_margin . ';';
			$style .= 'padding: ' . $mobile_padding . ';';
			$style .= 'border-width: ' . $mobile_border_width . ';';
			$style .= 'border-style: ' . $mobile_border_style . ';';
			$style .= ( ! $is_vanilla ) ? 'border-color: ' . $border_color . ';' : '';
			$style .= 'border-radius: ' . $mobile_border_radius . ';';
			$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $mobile_box_shadow . ';' : '';
			$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $mobile_box_shadow . ';' : '';
			$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $mobile_box_shadow . ';' : '';
			$style .= ( ! $is_vanilla ) ? 'color: ' . $text_color . ';' : '';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' p {';
			$style .= ( ! $is_vanilla ) ? 'color: ' . $text_color . ';' : '';
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
					$style .= 'border-radius: ' . $border_radius . ';';
					$style .= ( ! $is_vanilla ) ? 'box-shadow: ' . $box_shadow . ';' : '';
					$style .= ( ! $is_vanilla ) ? '-moz-box-shadow: ' . $box_shadow . ';' : '';
					$style .= ( ! $is_vanilla ) ? '-webkit-box-shadow: ' . $box_shadow . ';' : '';
				$style     .= '}';
			$style         .= '}';

			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' p {';
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
}