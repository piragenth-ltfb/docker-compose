<?php
/**
 * Feature Image Size.
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

// CONDITIONALS: Check if module has only image.
$no_title       = '' === $content['title'];
$no_subtitle    = '' === $content['sub_title'];
$no_content     = '' === $content['main_content'];
$no_cta         = '0' === $content['show_cta'] || '' === $content['cta_label'] || '' === $content['cta_url'];
$has_only_image = $no_title && $no_subtitle && $no_content && $no_cta;

if ( ! $is_optin ) {

	// LAYOUT: Default.
	if ( 'minimal' === $layout_info ) {
		$has_only_image = $no_content;
	}

	// LAYOUT: Stacked.
	if ( 'cabriolet' === $layout_info ) {
		$has_only_image = ( $no_content && $no_cta );
	}
}

// SETTINGS: Image.
$image = $content['feature_image'];

// SETTINGS: Colors.
$background_color = $colors['image_container_bg'];

// SETTINGS: Size.
$image_size = ( '' !== $design['feature_image_width'] ) ? $design['feature_image_width'] : '0';
$image_unit = $design['feature_image_width_unit'];
$image_size = $image_size . $image_unit;

if ( $is_optin ) {

	// LAYOUT: Default.
	if ( 'one' === $layout_optin ) {

		if ( 'above' === $design['feature_image_position'] || 'below' === $design['feature_image_position'] ) {
			$image_size = ( '' !== $design['feature_image_height'] ) ? $design['feature_image_height'] : '0';
			$image_unit = $design['feature_image_height_unit'];
			$image_size = $image_size . $image_unit;
		} else {

			if ( $has_only_image ) {
				$image_size = ( '' !== $design['feature_image_height'] ) ? $design['feature_image_height'] : '0';
				$image_unit = $design['feature_image_height_unit'];
				$image_size = $image_size . $image_unit;
			}
		}
	}

	// LAYOUT: Opt-in Focus.
	if ( 'three' === $layout_optin ) {
		$image_size = ( '' !== $design['feature_image_height'] ) ? $design['feature_image_height'] : '0';
		$image_unit = $design['feature_image_height_unit'];
		$image_size = $image_size . $image_unit;
	}

	// LAYOUT: Content Focus.
	if ( 'four' === $layout_optin ) {
		$sidebar_size = $image_size;

		$image_size = ( '' !== $design['feature_image_height'] ) ? $design['feature_image_height'] : '0';
		$image_unit = $design['feature_image_height_unit'];
		$image_size = $image_size . $image_unit;
	}
} else {

	// LAYOUT: Default.
	if ( 'minimal' === $layout_info ) {

		if ( $has_only_image ) {
			$image_size = ( '' !== $design['feature_image_height'] ) ? $design['feature_image_height'] : '0';
			$image_unit = $design['feature_image_height_unit'];
			$image_size = $image_size . $image_unit;
		}
	}

	// LAYOUT: Compact.
	if ( 'simple' === $layout_info ) {

		if ( $has_only_image ) {
			$image_size = ( '' !== $design['feature_image_height'] ) ? $design['feature_image_height'] : '0';
			$image_unit = $design['feature_image_height_unit'];
			$image_size = $image_size . $image_unit;
		}
	}
}

$mobile_image_size = ( '' !== $design['feature_image_height_mobile'] ) ? $design['feature_image_height_mobile'] : '150';
$mobile_image_unit = ( '' !== $design['feature_image_height_mobile'] ) ? $design['feature_image_height_unit_mobile'] : 'px';
$mobile_image_size = $mobile_image_size . $mobile_image_unit;

// SETTINGS: Fitting.
$fitting        = $design['feature_image_fit'];
$mobile_fitting = ( $is_mobile_enabled ) ? $design['feature_image_fit_mobile'] : $fitting;

// ==================================================
// Check if feature image exists.
if ( '' !== $image ) {

	// Mobile styles.
	if ( ! $is_vanilla ) {

		$style     .= $prefix_mobile . $container . ' {';
			$style .= 'background-color: ' . $background_color . ';';
		$style     .= '}';

	}

	// Check if is an opt-in module.
	if ( $is_optin ) {

		// LAYOUT: Default.
		if ( 'one' === $layout_optin ) {

			// Mobile styles.
			if ( ( $is_mobile_disabled && 'none' !== $fitting ) || ( $is_mobile_enabled && 'none' !== $mobile_fitting ) ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			}

			// Desktop styles.
			if ( 'none' !== $fitting ) {

				// POSITION: Left and Right.
				if ( 'left' === $design['feature_image_position'] || 'right' === $design['feature_image_position'] ) {

					if ( $has_only_image ) {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $container . ' {';
								$style .= 'min-width: 1px;';
								$style .= 'height: ' . $image_size . ';';
								$style .= 'min-height: 0;';
								$style .= 'flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= '-webkit-box-flex: 1;';
							$style     .= '}';
						$style         .= '}';
					} else {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $container . ' {';
								$style .= 'max-width: ' . $image_size . ';';
								$style .= 'height: auto;';
								$style .= 'min-height: 0;';
								$style .= 'flex: 0 0 ' . $image_size . ';';
								$style .= '-ms-flex: 0 0 ' . $image_size . ';';
								$style .= '-webkit-box-flex: 0;';
							$style     .= '}';
						$style         .= '}';
					}
				}

				// POSITION: Above and Below.
				if ( 'above' === $design['feature_image_position'] || 'below' === $design['feature_image_position'] ) {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . $container . ' {';
							$style .= 'height: ' . $image_size . ';';
						$style     .= '}';
					$style         .= '}';
				}
			} else {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= 'height: auto;';
					$style     .= '}';
				$style         .= '}';

				// POSITION: Left and Right.
				if ( 'left' === $design['feature_image_position'] || 'right' === $design['feature_image_position'] ) {

					if ( $has_only_image ) {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $container . ' {';
								$style .= 'width: 100%;';
							$style     .= '}';
						$style         .= '}';
					} else {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $container . ' {';
								$style .= 'max-width: 50%;';
								$style .= 'min-height: 0;';
								$style .= 'flex: 0 0 auto;';
								$style .= '-ms-flex: 0 0 auto;';
								$style .= '-webkit-box-flex: 0;';
							$style     .= '}';
						$style         .= '}';
					}
				}
			}
		}

		// LAYOUT: Compact.
		if ( 'two' === $layout_optin ) {

			// Mobile styles.
			if ( 'none' !== $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			}

			// Desktop styles.
			if ( 'none' !== $fitting ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= 'max-width: ' . $image_size . ';';
						$style .= 'height: auto;';
						$style .= 'overflow: hidden;';
						$style .= 'flex: 0 0 ' . $image_size . ';';
						$style .= '-ms-flex: 0 0 ' . $image_size . ';';
						$style .= '-webkit-box-flex: 0;';
					$style     .= '}';
				$style         .= '}';
			} else {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= 'max-width: 50%;';
						$style .= 'height: auto;';
						$style .= 'flex: 0 0 auto;';
						$style .= '-ms-flex: 0 0 auto;';
						$style .= '-webkit-box-flex: 0;';
					$style     .= '}';
				$style         .= '}';
			}
		}

		// LAYOUT: Opt-in Focus.
		if ( 'three' === $layout_optin ) {

			// Mobile styles.
			if ( 'none' !== $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			} else {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: auto;';
				$style     .= '}';
			}

			// Desktop styles.
			if ( 'none' !== $fitting ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= 'height: ' . $image_size . ';';
						$style .= 'overflow: hidden;';
					$style     .= '}';
				$style         .= '}';
			} else {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= 'height: auto;';
					$style     .= '}';
				$style         .= '}';
			}
		}

		// LAYOUT: Content Focus.
		if ( 'four' === $layout_optin ) {

			// Mobile styles.
			if ( 'none' !== $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			}

			// Desktop styles.
			if ( 'none' !== $fitting ) {

				if ( $has_only_image ) {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . ' .hustle-layout .hustle-layout-sidebar {';
							$style .= 'width: 100%;';
							$style .= 'min-height: 0;';
							$style .= 'flex: 0 0 auto;';
							$style .= '-ms-flex: 0 0 100%;';
							$style .= '-webkit-box-flex: 0;';
						$style     .= '}';
						$style     .= $prefix_desktop . $container . ' {';
							$style .= 'height: ' . $image_size . ';';
							$style .= 'overflow: hidden;';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . ' .hustle-layout .hustle-layout-sidebar {';
							$style .= 'max-width: ' . $sidebar_size . ';';
							$style .= 'min-height: 0;';
							$style .= 'display: flex;';
							$style .= 'display: -ms-flexbox;';
							$style .= 'display: -webkit-box;';
							$style .= 'flex: 0 0 ' . $sidebar_size . ';';
							$style .= '-ms-flex: 0 0 ' . $sidebar_size . ';';
							$style .= '-webkit-box-flex: 0;';
							$style .= 'flex-direction: column;';
							$style .= '-ms-flex-direction: column;';
							$style .= '-webkit-box-orient: vertical;';
							$style .= '-webkit-box-direction: normal;';
							$style .= 'justify-content: center;';
							$style .= '-ms-flex-pack: center;';
						$style     .= '}';
						$style     .= $prefix_desktop . $container . ' {';
							$style .= 'height: ' . $image_size . ';';
							$style .= 'overflow: hidden;';
						$style     .= '}';
					$style         .= '}';
				}
			} else {

				if ( $has_only_image ) {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . ' .hustle-layout .hustle-layout-sidebar {';
							$style .= 'width: 100%;';
						$style     .= '}';
						$style     .= $prefix_desktop . $container . ' {';
							$style .= 'height: auto;';
						$style     .= '}';
					$style         .= '}';
				} else {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . ' .hustle-layout .hustle-layout-sidebar {';
							$style .= 'max-width: 50%;';
							$style .= 'min-height: 0;';
							$style .= 'display: flex;';
							$style .= 'display: -ms-flexbox;';
							$style .= 'display: -webkit-box;';
							$style .= 'flex: 0 0 auto;';
							$style .= '-ms-flex: 0 0 auto;';
							$style .= '-webkit-box-flex: 0;';
							$style .= 'flex-direction: column;';
							$style .= '-ms-flex-direction: column;';
							$style .= '-webkit-box-orient: vertical;';
							$style .= '-webkit-box-direction: normal;';
							$style .= 'justify-content: center;';
							$style .= '-ms-flex-pack: center;';
						$style     .= '}';
						$style     .= $prefix_desktop . $container . ' {';
							$style .= 'height: auto;';
						$style     .= '}';
					$style         .= '}';
				}
			}
		}
	} else {

		// LAYOUT: Default.
		if ( 'minimal' === $layout_info ) {

			// Mobile styles.
			if ( 'none' !== $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			}

			if ( 'none' !== $fitting ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= ( $has_only_image ) ? 'width: auto;' : '';
						$style .= ( $has_only_image ) ? 'min-width: 1px;' : '';
						$style .= ( $has_only_image ) ? 'max-width: 100%;' : 'max-width: ' . $image_size . ';';
						$style .= ( $has_only_image ) ? 'height: ' . $image_size . ';' : 'height: auto;';
						$style .= 'min-height: 0;';
						$style .= ( $has_only_image ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
						$style .= ( $has_only_image ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 ' . $image_size . ';';
						$style .= ( $has_only_image ) ? 'flex: 1;' : 'flex: 0 0 ' . $image_size . ';';
					$style     .= '}';
				$style         .= '}';
			} else {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= ( $has_only_image ) ? 'min-width: 1px;' : '';
						$style .= ( $has_only_image ) ? 'min-width: 100%;' : 'max-width: 50%;';
						$style .= 'min-height: 0;';
						$style .= ( $has_only_image ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
						$style .= ( $has_only_image ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 auto;';
						$style .= ( $has_only_image ) ? 'flex: 1;' : 'flex: 0 0 auto;';
					$style     .= '}';
				$style         .= '}';
			}
		}

		// LAYOUT: Compact.
		if ( 'simple' === $layout_info ) {

			// Mobile styles.
			if ( 'none' !== $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			}

			// Desktop styles.
			if ( 'none' !== $fitting ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= ( $has_only_image ) ? 'min-width: 1px;' : '';
						$style .= ( $has_only_image ) ? 'max-width: 100%;' : 'max-width: ' . $image_size . ';';
						$style .= ( $has_only_image ) ? 'height: ' . $image_size . ';' : 'height: auto;';
						$style .= ( $has_only_image ) ? '' : 'min-height: 0;';
						$style .= ( $has_only_image ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
						$style .= ( $has_only_image ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 ' . $image_size . ';';
						$style .= ( $has_only_image ) ? 'flex: 1;' : 'flex: 0 0 ' . $image_size . ';';
					$style     .= '}';
				$style         .= '}';
			} else {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= ( $has_only_image ) ? 'min-width: 1px;' : '';
						$style .= ( $has_only_image ) ? 'max-width: 100%;' : 'max-width: 50%;';
						$style .= 'min-height: 0;';
						$style .= ( $has_only_image ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
						$style .= ( $has_only_image ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 auto;';
						$style .= ( $has_only_image ) ? 'flex: 1;' : 'flex: 0 0 auto;';
					$style     .= '}';
				$style         .= '}';
			}
		}

		// LAYOUT: Stacked.
		if ( 'cabriolet' === $layout_info ) {

			// Mobile styles.
			if ( 'none' !== $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'height: ' . $mobile_image_size . ';';
					$style .= 'overflow: hidden;';
				$style     .= '}';
			}

			// Desktop styles.
			if ( 'none' !== $fitting ) {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= ( $has_only_image ) ? 'min-width: 1px;' : '';
						$style .= ( $has_only_image ) ? 'max-width: 100%;' : 'max-width: ' . $image_size . ';';
						$style .= ( $has_only_image ) ? 'height: ' . $image_size . ';' : 'height: auto;';
						$style .= 'min-height: 0;';
						$style .= ( $has_only_image ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
						$style .= ( $has_only_image ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 ' . $image_size . ';';
						$style .= ( $has_only_image ) ? 'flex: 1;' : 'flex: 0 0 ' . $image_size . ';';
					$style     .= '}';
				$style         .= '}';
			} else {
				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $container . ' {';
						$style .= ( $has_only_image ) ? 'min-width: 1px;' : '';
						$style .= ( $has_only_image ) ? 'max-width: 100%;' : 'max-width: 50%;';
						$style .= 'min-height: 0;';
						$style .= ( $has_only_image ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
						$style .= ( $has_only_image ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 auto;';
						$style .= ( $has_only_image ) ? 'flex: 1;' : 'flex: 0 0 auto;';
					$style     .= '}';
				$style         .= '}';
			}
		}
	}
}