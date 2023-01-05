<?php
/**
 * Slide-in custom size settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-slidein-content';

$is_desktop_custom = ( '1' === $design['customize_size'] );
$is_mobile_custom  = ( '1' === $design['customize_size_mobile'] );

$has_content = ( '' !== $content['title'] || '' !== $content['sub_title'] || '' !== $content['main_content'] || '1' === $content['show_cta'] );
$has_image   = ( '' !== $content['feature_image'] );

// SETTINGS: Width.
$desktop_width = $design['custom_width'];
$desktop_width = ( '' !== $desktop_width ) ? $desktop_width . $design['custom_width_unit'] : '800px';
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

// Check if module is slide-in.
if ( $is_slidein ) {

	if ( ! $is_vanilla ) {

		$style .= '';

		// Mobile styles.
		if ( $is_mobile_enabled && $is_mobile_custom ) {

			// Width.
			if ( '' !== $mobile_width ) {
				$style         .= $breakpoint_sm . ' {';
					$style     .= ( $is_optin ) ? $prefix_mobile . $component . ' .hustle-optin {' : $prefix_mobile . $component . ' .hustle-info {';
						$style .= 'max-width: ' . $mobile_width . ';';
					$style     .= '}';
				$style         .= '}';
			}

			// Height.
			if ( '' !== $mobile_height ) {
				$style         .= $breakpoint_sm . ' {';
					$style     .= ( $is_optin ) ? $prefix_mobile . $component . ' .hustle-optin {' : $prefix_mobile . $component . ' .hustle-info {';
						$style .= 'height: calc(' . $mobile_height . ' - 30px);';
					$style     .= '}';
				$style         .= '}';

				// Check if module is an opt-in.
				if ( $is_optin ) {

					// LAYOUT: Default.
					if ( 'one' === $layout_optin ) {
						$style         .= $breakpoint_sm . ' {';
							$style     .= $prefix_mobile . $component . ' .hustle-layout {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-body {';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-form {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
							$style     .= '}';
						$style         .= '}';
					}

					// LAYOUT: Compact.
					if ( 'two' === $layout_optin ) {
						$style         .= $breakpoint_sm . ' {';
							$style     .= $prefix_mobile . $component . ' .hustle-optin-content {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout,';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-body {';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-image {';
								$style .= '-webkit-box-flex: 0;';
								$style .= '-ms-flex: 0 0 auto;';
								$style .= 'flex: 0 0 auto;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-content {';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-content {';
								$style .= '-webkit-box-flex: 0;';
								$style .= '-ms-flex: 0 0 auto;';
								$style .= 'flex: 0 0 auto;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-form {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
							$style     .= '}';
						$style         .= '}';
					}

					// LAYOUT: Opt-in Focus.
					if ( 'three' === $layout_optin ) {
						$style         .= $breakpoint_sm . ' {';
							$style     .= $prefix_mobile . $component . ' .hustle-layout {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-body {';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-form {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
							$style     .= '}';
						$style         .= '}';
					}

					// LAYOUT: Content Focus.
					if ( 'four' === $layout_optin ) {
						$style         .= $breakpoint_sm . ' {';
							$style     .= $prefix_mobile . $component . ' .hustle-layout {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-body {';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-sidebar,';
							$style     .= $prefix_mobile . $component . ' .hustle-layout-content {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
							$style     .= '}';
						$style         .= '}';

						if ( ! $has_content ) {
							$style         .= $breakpoint_sm . ' {';
								$style     .= $prefix_mobile . $component . ' .hustle-layout-sidebar {';
									$style .= 'display: -webkit-box;';
									$style .= 'display: -ms-flexbox;';
									$style .= 'display: flex;';
									$style .= '-webkit-box-orient: vertical;';
									$style .= '-webkit-box-direction: normal;';
									$style .= '-ms-flex-direction: column;';
									$style .= 'flex-direction: column;';
								$style     .= '}';
								$style     .= $prefix_mobile . $component . ' .hustle-image {';
									$style .= '-webkit-box-flex: 0;';
									$style .= '-ms-flex: 0 0 auto;';
									$style .= 'flex: 0 0 auto;';
								$style     .= '}';
								$style     .= $prefix_mobile . $component . ' .hustle-layout-form {';
									$style .= '-webkit-box-flex: 1;';
									$style .= '-ms-flex: 1;';
									$style .= 'flex: 1;';
								$style     .= '}';
							$style         .= '}';
						}
					}
				} else {

					$style         .= $breakpoint_sm . ' {';
						$style     .= $prefix_mobile . $component . ' .hustle-layout {';
							$style .= 'min-height: 100%;';
						$style     .= '}';
					$style         .= '}';

				}
			}
		}

		// Desktop styles.
		if ( $is_desktop_custom ) {

			$style         .= $breakpoint . ' {';
				$style     .= ( $is_optin ) ? $prefix_desktop . $component . ' .hustle-optin {' : $prefix_desktop . $component . ' .hustle-info {';
					$style .= 'max-width: ' . $desktop_width . ';';
					$style .= ( '' !== $desktop_height ) ? 'height: calc(' . $desktop_height . ' - 30px);' : '';
				$style     .= '}';
			$style         .= '}';

			// Check if module is an opt-in.
			if ( $is_optin ) {

				// LAYOUT: Default.
				if ( 'one' === $layout_optin ) {

					if ( '' !== $desktop_height ) {

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout,';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-body {';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout {';
								$style .= 'height: 100%;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-body {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
								$style .= 'min-height: 1px;';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1 1 auto;';
								$style .= 'flex: 1 1 auto;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-content {';
								$style .= 'min-height: 0;';
								$style .= '-webkit-box-flex: 0;';
								$style .= '-ms-flex: 0 0 auto;';
								$style .= 'flex: 0 0 auto;';
							$style     .= '}';
						$style         .= '}';

					}
				}

				// LAYOUT: Compact.
				if ( 'two' === $layout_optin ) {

					if ( '' !== $desktop_height ) {

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-optin-content {';
								$style .= 'height: 100%;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-body {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1 1 auto;';
								$style .= 'flex: 1 1 auto;';
							$style     .= '}';
						$style         .= '}';

					}
				}

				// LAYOUT: Opt-in Focus.
				if ( 'three' === $layout_optin ) {

					if ( '' !== $desktop_height ) {

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-body {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1 1 auto;';
								$style .= 'flex: 1 1 auto;';
							$style     .= '}';
						$style         .= '}';

					}
				}

				// LAYOUT: Content Focus.
				if ( 'four' === $layout_optin ) {

					if ( '' !== $desktop_height ) {

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout {';
								$style .= 'min-height: 100%;';
								$style .= 'display: -webkit-box;';
								$style .= 'display: -ms-flexbox;';
								$style .= 'display: flex;';
								$style .= '-webkit-box-orient: vertical;';
								$style .= '-webkit-box-direction: normal;';
								$style .= '-ms-flex-direction: column;';
								$style .= 'flex-direction: column;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-body {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1 1 auto;';
								$style .= 'flex: 1 1 auto;';
							$style     .= '}';
						$style         .= '}';

						if ( $has_image ) {

							$style         .= $breakpoint . ' {';
								$style     .= $prefix_desktop . $component . ' .hustle-layout-sidebar {';
									$style .= ( ! $has_content ) ? 'max-width: 100%;' : '';
									$style .= ( ! $has_content ) ? 'display: -webkit-box;' : '';
									$style .= ( ! $has_content ) ? 'display: -ms-flexbox;' : '';
									$style .= ( ! $has_content ) ? 'display: flex;' : '';
									$style .= ( ! $has_content ) ? '-webkit-box-orient: vertical;' : '';
									$style .= ( ! $has_content ) ? '-webkit-box-direction: normal;' : '';
									$style .= ( ! $has_content ) ? '-ms-flex-direction: column;' : '';
									$style .= ( ! $has_content ) ? 'flex-direction: column;' : '';
									$style .= ( ! $has_content ) ? '-webkit-box-flex: 1;' : '';
									$style .= ( ! $has_content ) ? '-ms-flex: 1;' : '-ms-flex-negative: 1;';
									$style .= ( ! $has_content ) ? 'flex: 1;' : 'flex-shrink: 1;';
								$style     .= '}';
								$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
									$style .= ( $has_content ) ? 'height: 100%;' : 'height: auto;';
								$style     .= '}';
							$style         .= '}';

						} else {

							$style         .= $breakpoint . ' {';
								$style     .= $prefix_desktop . $component . ' .hustle-layout-sidebar {';
									$style .= ( ! $has_content ) ? 'width: 100%;' : 'width: 50%;';
									$style .= ( ! $has_content ) ? '-webkit-box-flex: 1;' : '-webkit-box-flex: 0;';
									$style .= ( ! $has_content ) ? '-ms-flex: 1;' : '-ms-flex: 0 0 auto;';
									$style .= ( ! $has_content ) ? 'flex: 1;' : 'flex: 0 0 auto;';
								$style     .= '}';
								$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
									$style .= 'height: 100%;';
								$style     .= '}';
							$style         .= '}';

						}

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
								$style .= '-webkit-box-flex: 1;';
								$style .= '-ms-flex: 1;';
								$style .= 'flex: 1;';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-footer {';
								$style .= '-webkit-box-flex: 0;';
								$style .= '-ms-flex: 0 0 auto;';
								$style .= 'flex: 0 0 auto;';
							$style     .= '}';
						$style         .= '}';

					} else {

						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-sidebar {';
								$style .= ( ! $has_content ) ? 'max-width: 100%;' : '';
								$style .= ( ! $has_content ) ? '-webkit-box-flex: 1;' : '';
								$style .= ( ! $has_content ) ? '-ms-flex: 1;' : '';
								$style .= ( ! $has_content ) ? 'flex: 1;' : '';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
								$style .= ( $has_content ) ? 'height: 100%;' : 'height: auto;';
							$style     .= '}';
						$style         .= '}';

					}
				}
			} else {

				$style         .= $breakpoint . ' {';
					$style     .= $prefix_desktop . $component . ' .hustle-layout {';
						$style .= 'min-height: 100%;';
					$style     .= '}';
				$style         .= '}';

			}
		} else {

			$style         .= $breakpoint . ' {';
				$style     .= ( $is_optin ) ? $prefix_desktop . $component . ' .hustle-optin {' : $prefix_desktop . $component . ' .hustle-info {';
					$style .= 'max-width: 800px;';
				$style     .= '}';
			$style         .= '}';

			// Check if module is an opt-in.
			if ( $is_optin ) {

				// LAYOUT: Content Focus.
				if ( 'four' === $layout_optin ) {

					if ( ! $has_image ) {
						$style         .= $breakpoint . ' {';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-sidebar {';
								$style .= ( $has_content ) ? 'width: 50%;' : 'width: 100%;';
								$style .= ( $has_content ) ? 'max-width: 320px;' : '';
								$style .= ( $has_content ) ? '-webkit-box-flex: 0;' : '';
								$style .= ( $has_content ) ? '-ms-flex: 0 0 auto;' : '';
								$style .= ( $has_content ) ? 'flex: 0 0 auto;' : '';
							$style     .= '}';
							$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
								$style .= 'height: 100%;';
							$style     .= '}';
						$style         .= '}';
					}
				}
			}
		}
	} else {

		$style         .= $breakpoint . ' {';
			$style     .= ( $is_optin ) ? $prefix_desktop . $component . ' .hustle-optin {' : $prefix_desktop . $component . ' .hustle-info {';
				$style .= 'max-width: 800px;';
			$style     .= '}';
		$style         .= '}';

		// Check if module is an opt-in.
		if ( $is_optin ) {

			// LAYOUT: Content Focus.
			if ( 'four' === $layout_optin ) {

				if ( ! $has_image ) {
					$style         .= $breakpoint . ' {';
						$style     .= $prefix_desktop . $component . ' .hustle-layout-sidebar {';
							$style .= ( $has_content ) ? 'width: 50%;' : 'width: 100%;';
							$style .= ( $has_content ) ? 'max-width: 320px;' : '';
							$style .= ( $has_content ) ? '-webkit-box-flex: 0;' : '';
							$style .= ( $has_content ) ? '-ms-flex: 0 0 auto;' : '';
							$style .= ( $has_content ) ? 'flex: 0 0 auto;' : '';
						$style     .= '}';
						$style     .= $prefix_desktop . $component . ' .hustle-layout-form {';
							$style .= 'height: 100%;';
						$style     .= '}';
					$style         .= '}';
				}
			}
		}
	}
}