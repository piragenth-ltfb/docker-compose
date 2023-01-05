<?php
/**
 * Feature Image Position.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-layout .hustle-image';
$component = $container . ' img';

// CONDITIONALS: Check if module has mobile appearance settings enabled.
$is_mobile_enabled  = ( '1' === $design['enable_mobile_settings'] );
$is_mobile_disabled = ( '1' !== $design['enable_mobile_settings'] );

// SETTINGS: Image.
$image = $content['feature_image'];

// SETTINGS: Fitting.
$fitting        = $design['feature_image_fit'];
$mobile_fitting = ( $is_mobile_enabled ) ? $design['feature_image_fit_mobile'] : $fitting;

// SETTINGS: Horizontal Position.
$horizontal       = $design['feature_image_horizontal_position'];
$horizontal_value = ( '' !== $design['feature_image_horizontal_value'] ) ? $design['feature_image_horizontal_value'] . $design['feature_image_horizontal_unit'] : '0';
$horizontal_value = ( 'custom' === $horizontal ) ? $horizontal_value : $horizontal;

$mobile_horizontal       = $design['feature_image_horizontal_position_mobile'];
$mobile_horizontal_value = ( '' !== $design['feature_image_horizontal_value_mobile'] ) ? $design['feature_image_horizontal_value_mobile'] . $design['feature_image_horizontal_unit_mobile'] : '0';
$mobile_horizontal_value = ( 'custom' === $mobile_horizontal ) ? $mobile_horizontal_value : $mobile_horizontal;

// SETTINGS: Vertical Position.
$vertical       = $design['feature_image_vertical_position'];
$vertical_value = ( '' !== $design['feature_image_vertical_value'] ) ? $design['feature_image_vertical_value'] . $design['feature_image_vertical_unit'] : '0';
$vertical_value = ( 'custom' === $vertical ) ? $vertical_value : $vertical;

$mobile_vertical       = $design['feature_image_vertical_position_mobile'];
$mobile_vertical_value = ( '' !== $design['feature_image_vertical_value_mobile'] ) ? $design['feature_image_vertical_value_mobile'] . $design['feature_image_vertical_unit_mobile'] : '0';
$mobile_vertical_value = ( 'custom' === $mobile_vertical ) ? $mobile_vertical_value : $mobile_vertical;

// ==================================================
// Check if feature image exists.
if ( '' !== $image ) {

	if ( $is_mobile_enabled ) {

		// Mobile styles.
		if ( 'cover' === $mobile_fitting || 'contain' === $mobile_fitting ) {
			$style     .= $prefix_mobile . $component . ' {';
				$style .= 'object-position: ' . $mobile_horizontal_value . ' ' . $mobile_vertical_value . ';';
				$style .= '-o-object-position: ' . $mobile_horizontal_value . ' ' . $mobile_vertical_value . ';';
			$style     .= '}';

			if ( 'left' === $mobile_horizontal ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'left: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'right' === $mobile_horizontal ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'right: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'center' === $mobile_horizontal ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'left: 50%;';
					$style     .= '}';
				$style         .= '}';

				if ( 'center' === $mobile_vertical ) {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translate(-50%,-50%);';
							$style .= '-ms-transform: translate(-50%,-50%);';
							$style .= '-webkit-transform: translate(-50%,-50%);';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translateX(-50%);';
							$style .= '-ms-transform: translateX(-50%);';
							$style .= '-webkit-transform: translateX(-50%);';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'left: ' . $mobile_vertical_value . ';';
					$style     .= '}';
				$style         .= '}';
			}

			if ( 'top' === $mobile_vertical ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'top: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'bottom' === $mobile_vertical ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'bottom: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'center' === $mobile_vertical ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'top: 50%;';
					$style     .= '}';
				$style         .= '}';

				if ( 'center' === $mobile_horizontal ) {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translate(-50%,-50%);';
							$style .= '-ms-transform: translate(-50%,-50%);';
							$style .= '-webkit-transform: translate(-50%,-50%);';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translateY(-50%);';
							$style .= '-ms-transform: translateY(-50%);';
							$style .= '-webkit-transform: translateY(-50%);';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'top: ' . $mobile_vertical_value . ';';
					$style     .= '}';
				$style         .= '}';
			}
		}

		// Desktop styles.
		if ( 'cover' === $fitting || 'contain' === $fitting ) {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'object-position: ' . $horizontal_value . ' ' . $vertical_value . ';';
					$style .= '-o-object-position: ' . $horizontal_value . ' ' . $vertical_value . ';';
				$style     .= '}';
			$style         .= '}';

			if ( 'left' === $horizontal ) {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'left: 0;';
						$style .= 'right: auto;';
						$style .= 'transform: unset;';
						$style .= '-ms-transform: unset;';
						$style .= '-webkit-transform: unset;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'right' === $horizontal ) {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'right: 0;';
						$style .= 'left: auto;';
						$style .= 'transform: unset;';
						$style .= '-ms-transform: unset;';
						$style .= '-webkit-transform: unset;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'center' === $horizontal ) {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'left: 50%;';
						$style .= 'right: auto;';
					$style     .= '}';
				$style         .= '}';

				if ( 'center' === $vertical ) {
					$style         .= $breakpoint_ie . ' {';
						$style     .= $prefix_desktop . $component . ' {';
							$style .= 'transform: translate(-50%,-50%);';
							$style .= '-ms-transform: translate(-50%,-50%);';
							$style .= '-webkit-transform: translate(-50%,-50%);';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $breakpoint_ie . ' {';
						$style     .= $prefix_desktop . $component . ' {';
							$style .= 'transform: translateX(-50%);';
							$style .= '-ms-transform: translateX(-50%);';
							$style .= '-webkit-transform: translateX(-50%);';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'left: ' . $vertical_value . ';';
						$style .= 'right: auto;';
					$style     .= '}';
				$style         .= '}';
			}

			if ( 'top' === $vertical ) {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'top: 0;';
						$style .= 'bottom: auto;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'bottom' === $vertical ) {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'top: auto;';
						$style .= 'bottom: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'center' === $vertical ) {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'top: 50%;';
						$style .= 'bottom: auto;';
					$style     .= '}';
				$style         .= '}';

				if ( 'center' === $horizontal ) {
					$style         .= $breakpoint_ie . ' {';
						$style     .= $prefix_desktop . $component . ' {';
							$style .= 'transform: translate(-50%,-50%);';
							$style .= '-ms-transform: translate(-50%,-50%);';
							$style .= '-webkit-transform: translate(-50%,-50%);';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $breakpoint_ie . ' {';
						$style     .= $prefix_desktop . $component . ' {';
							$style .= 'transform: translateY(-50%);';
							$style .= '-ms-transform: translateY(-50%);';
							$style .= '-webkit-transform: translateY(-50%);';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $breakpoint_ie . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= 'top: ' . $vertical_value . ';';
						$style .= 'bottom: auto;';
					$style     .= '}';
				$style         .= '}';
			}
		}
	} else {

		if ( 'cover' === $fitting || 'contain' === $fitting ) {
			$style     .= $prefix_mobile . $component . ' {';
				$style .= 'object-position: ' . $horizontal_value . ' ' . $vertical_value . ';';
				$style .= '-o-object-position: ' . $horizontal_value . ' ' . $vertical_value . ';';
			$style     .= '}';

			if ( 'left' === $horizontal ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'left: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'right' === $horizontal ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'right: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'center' === $horizontal ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'left: 50%;';
					$style     .= '}';
				$style         .= '}';

				if ( 'center' === $vertical ) {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translate(-50%,-50%);';
							$style .= '-ms-transform: translate(-50%,-50%);';
							$style .= '-webkit-transform: translate(-50%,-50%);';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translateX(-50%);';
							$style .= '-ms-transform: translateX(-50%);';
							$style .= '-webkit-transform: translateX(-50%);';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'left: ' . $vertical_value . ';';
					$style     .= '}';
				$style         .= '}';
			}

			if ( 'top' === $vertical ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'top: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'bottom' === $vertical ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'bottom: 0;';
					$style     .= '}';
				$style         .= '}';
			} elseif ( 'center' === $vertical ) {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'top: 50%;';
					$style     .= '}';
				$style         .= '}';

				if ( 'center' === $horizontal ) {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translate(-50%,-50%);';
							$style .= '-ms-transform: translate(-50%,-50%);';
							$style .= '-webkit-transform: translate(-50%,-50%);';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $support_ie . ' {';
						$style     .= $prefix_mobile . $component . ' {';
							$style .= 'transform: translateY(-50%);';
							$style .= '-ms-transform: translateY(-50%);';
							$style .= '-webkit-transform: translateY(-50%);';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $support_ie . ' {';
					$style     .= $prefix_mobile . $component . ' {';
						$style .= 'top: ' . $vertical_value . ';';
					$style     .= '}';
				$style         .= '}';
			}
		}
	}
}