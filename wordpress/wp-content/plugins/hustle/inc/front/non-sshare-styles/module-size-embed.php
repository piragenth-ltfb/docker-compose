<?php
/**
 * Embedded custom size settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-inline-content';

$is_desktop_custom = ( '1' === $design['customize_size'] );
$is_mobile_custom  = ( '1' === $design['customize_size_mobile'] );

// SETTINGS: Width.
$desktop_width = $design['custom_width'];
$desktop_width = ( '' !== $desktop_width ) ? $desktop_width . $design['custom_width_unit'] : '100%';
$desktop_width = ( 'auto' !== $design['custom_width_unit'] ) ? $desktop_width : '';

$mobile_width = $design['custom_width_mobile'];
$mobile_width = ( '' !== $mobile_width ) ? $mobile_width . $design['custom_width_unit_mobile'] : '';
$mobile_width = ( 'auto' !== $design['custom_width_unit_mobile'] ) ? $mobile_width : '';

// SETTINGS: Height.
$desktop_height = $design['custom_height'];
$desktop_height = ( '' !== $desktop_height ) ? $desktop_height . $design['custom_height_unit'] : '';
$desktop_height = ( 'auto' !== $design['custom_height_unit'] ) ? $desktop_height : '';

$mobile_height = $design['custom_height_mobile'];
$mobile_height = ( '' !== $mobile_height ) ? $mobile_height . $design['custom_height_unit_mobile'] : '';
$mobile_height = ( 'auto' !== $design['custom_height_unit_mobile'] ) ? $mobile_height : '';

// Check if module is inline.
if ( $is_embed ) {

	if ( ! $is_vanilla ) {

		$style .= '';

		// Mobile styles.
		if ( $is_mobile_enabled && $is_mobile_custom ) {

			if ( '' !== $mobile_width || '' !== $mobile_height ) {
				$style     .= $prefix_mobile . $component . ' {';
					$style .= ( '' !== $mobile_width ) ? 'max-width: ' . $mobile_width . ';' : '';
					$style .= ( '' !== $mobile_height ) ? 'max-height: ' . $mobile_height . ';' : '';
					$style .= ( '' !== $mobile_height ) ? 'overflow-y: auto;' : '';
				$style     .= '}';

				if ( '' !== $mobile_height ) {

					if ( $is_optin ) {
						$style     .= $prefix_mobile . $component . ' .hustle-layout {';
							$style .= 'max-height: calc(' . $mobile_height . ' - 30px);';
						$style     .= '}';
					} else {
						$style     .= $prefix_mobile . $component . ' .hustle-info {';
							$style .= 'max-height: calc(100% - 30px);';
						$style     .= '}';
					}
				}
			}
		}

		// Desktop styles.
		if ( $is_desktop_custom ) {

			if ( '' !== $desktop_width || '' !== $desktop_height ) {

				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $component . ' {';
						$style .= ( '' !== $desktop_width ) ? 'max-width: ' . $desktop_width . ';' : '';
						$style .= ( '' !== $desktop_height ) ? 'max-height: ' . $desktop_height . ';' : '';
						$style .= ( '' !== $desktop_height ) ? 'overflow-y: auto;' : '';
					$style     .= '}';
				$style         .= '}';

				if ( '' !== $desktop_height ) {

					if ( $is_optin ) {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout {';
								$style .= 'max-height: calc(' . $desktop_height . ' - 30px);';
							$style     .= '}';
						$style         .= '}';
					} else {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-info {';
								$style .= 'max-height: calc(100% - 30px);';
							$style     .= '}';
						$style         .= '}';
					}
				} else {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . $component . ' {';
							$style .= ( '' !== $mobile_height ) ? 'max-height: none;' : '';
							$style .= ( '' !== $mobile_height ) ? 'max-height: unset;' : '';
							$style .= ( '' !== $mobile_height ) ? 'overflow-y: initial;' : '';
						$style     .= '}';
						$style     .= ( $is_optin ) ? $prefix_desktop . $component . ' .hustle-layout {' : $prefix_desktop . $component . ' .hustle-info {';
							$style .= 'max-height: none;';
							$style .= 'max-height: unset;';
						$style     .= '}';
					$style         .= '}';
				}
			} else {

				// Check if mobile settings and custom size for mobiles are enabled.
				if ( $is_mobile_enabled && $is_mobile_custom ) {

					if ( '' !== $mobile_width || '' !== $mobile_height ) {

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' {';
								$style .= ( '' !== $mobile_width ) ? 'max-width: 100%;' : '';
								$style .= ( '' !== $mobile_height ) ? 'max-height: none;' : '';
								$style .= ( '' !== $mobile_height ) ? 'max-height: unset;' : '';
								$style .= ( '' !== $mobile_height ) ? 'overflow-y: initial;' : '';
							$style     .= '}';
						$style         .= '}';

						if ( '' !== $mobile_height ) {
							$style         .= $breakpoint . ' {';
								$style     .= ( $is_optin ) ? $prefix_desktop . $component . ' .hustle-layout {' : $prefix_desktop . $component . ' .hustle-info {';
									$style .= 'max-height: none;';
									$style .= 'max-height: unset;';
								$style     .= '}';
							$style         .= '}';
						}
					}
				}
			}
		} else {

			// Check if mobile settings and custom size for mobiles are enabled.
			if ( $is_mobile_enabled && $is_mobile_custom ) {

				if ( '' !== $mobile_width || '' !== $mobile_height ) {

					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . $component . ' {';
							$style .= ( '' !== $mobile_width ) ? 'max-width: 100%;' : '';
							$style .= ( '' !== $mobile_height ) ? 'max-height: unset;' : '';
							$style .= ( '' !== $mobile_height ) ? 'overflow-y: initial;' : '';
						$style     .= '}';
					$style         .= '}';

					if ( '' !== $mobile_height ) {
						$style         .= $breakpoint . ' {';
							$style     .= ( $is_optin ) ? $prefix_desktop . $component . ' .hustle-layout {' : $prefix_desktop . $component . ' .hustle-info {';
								$style .= 'max-height: unset;';
							$style     .= '}';
						$style         .= '}';
					}
				}
			}
		}
	}
}