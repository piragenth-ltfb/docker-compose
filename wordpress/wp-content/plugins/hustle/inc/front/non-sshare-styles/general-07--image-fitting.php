<?php
/**
 * Feature Image Fitting.
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

// ==================================================
// Check if feature image exists.
if ( '' !== $image ) {

	if ( $is_mobile_enabled ) {

		// FITTING: Fill.
		if ( 'fill' === $mobile_fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: fill;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'max-height: unset;';
				$style     .= '}';
			$style         .= '}';
		}

		if ( 'fill' === $fitting ) {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: fill;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '}';
			$style         .= $breakpoint_ie . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'max-height: unset;';
				$style     .= '}';
			$style         .= '}';

			if ( 'none' === $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'display: block;';
				$style     .= '}';
			}
		}

		// FITTING: Contain.
		if ( 'contain' === $mobile_fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: contain;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'max-height: 100%;';
				$style     .= '}';
			$style         .= '}';
		}

		if ( 'contain' === $fitting ) {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: contain;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '}';
			$style         .= $breakpoint_ie . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'max-height: 100%;';
				$style     .= '}';
			$style         .= '}';

			if ( 'none' === $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'display: block;';
				$style     .= '}';
			}
		}

		// FITTING: Cover.
		if ( 'cover' === $mobile_fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: cover;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: unset;';
					$style .= 'height: auto;';
					$style .= 'max-height: unset;';
				$style     .= '}';
			$style         .= '}';
		}

		if ( 'cover' === $fitting ) {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'max-width: unset;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: cover;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '}';
			$style         .= $breakpoint_ie . ' {';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: unset;';
					$style .= 'height: auto;';
					$style .= 'max-height: unset;';
				$style     .= '}';
			$style         .= '}';

			if ( 'none' === $mobile_fitting ) {
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'display: block;';
				$style     .= '}';
			}
		}

		// FITTING: None.
		if ( 'none' === $mobile_fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'overflow: hidden;';
					$style .= 'display: flex;';
					$style .= 'display: -ms-flexbox;';
					$style .= 'display: -webkit-box;';
					$style .= 'flex-direction: column;';
					$style .= '-ms-flex-direction: column;';
					$style .= '-webkit-box-orient: vertical;';
					$style .= '-webkit-box-direction: normal;';
					$style .= 'justify-content: center;';
					$style .= '-ms-flex-pack: center;';
				$style     .= '}';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'display: block;';
					$style .= 'flex: 0 1 auto;';
					$style .= '-ms-flex: 0 1 auto;';
					$style .= '-webkit-box-flex: 0;';
					$style .= 'margin: 0 auto;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'display: block;';
				$style     .= '}';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= ( $is_optin && 'four' === $layout_optin ) ? 'flex-shrink: 0;' : '';
				$style     .= '}';
			$style         .= '}';
		}

		if ( 'none' === $fitting ) {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $container . ' {';
					$style .= 'overflow: hidden;';
					$style .= 'display: flex;';
					$style .= 'display: -ms-flexbox;';
					$style .= 'display: -webkit-box;';
					$style .= 'flex-direction: column;';
					$style .= '-ms-flex-direction: column;';
					$style .= '-webkit-box-orient: vertical;';
					$style .= '-webkit-box-direction: normal;';
					$style .= 'justify-content: center;';
					$style .= '-ms-flex-pack: center;';
				$style     .= '}';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'display: block;';
					$style .= 'flex: 0 1 auto;';
					$style .= '-ms-flex: 0 1 auto;';
					$style .= '-webkit-box-flex: 0;';
					$style .= 'position: unset;';
					$style .= 'margin: 0 auto;';
					$style .= 'object-fit: unset;';
					$style .= '-ms-interpolation-mode: unset;';
				$style     .= '}';
			$style         .= '}';
			$style         .= $breakpoint_ie . ' {';
				$style     .= $prefix_desktop . $container . ' {';
					$style .= 'display: block;';
				$style     .= '}';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'max-height: unset;';
					$style .= ( $is_optin && 'four' === $layout_optin ) ? 'flex-shrink: 0;' : '';
				$style     .= '}';
			$style         .= '}';
		}
	} else {

		// FITTING: Fill.
		if ( 'fill' === $fitting ) {
			$style     .= $prefix_mobile . $component . ' {';
				$style .= 'width: 100%;';
				$style .= 'height: 100%;';
				$style .= 'display: block;';
				$style .= 'position: absolute;';
				$style .= 'object-fit: fill;';
				$style .= '-ms-interpolation-mode: bicubic;';
			$style     .= '}';
		}

		// FITTING: Contain.
		if ( 'contain' === $fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: contain;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'max-height: 100%;';
				$style     .= '}';
			$style         .= '}';
		}

		// FITTING: Cover.
		if ( 'cover' === $fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'height: 100%;';
					$style .= 'display: block;';
					$style .= 'position: absolute;';
					$style .= 'object-fit: cover;';
					$style .= '-ms-interpolation-mode: bicubic;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'height: auto;';
				$style     .= '}';
			$style         .= '}';
		}

		// FITTING: None.
		if ( 'none' === $fitting ) {
			$style         .= '';
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'overflow: hidden;';
					$style .= 'display: flex;';
					$style .= 'display: -ms-flexbox;';
					$style .= 'display: -webkit-box;';
					$style .= 'flex-direction: column;';
					$style .= '-ms-flex-direction: column;';
					$style .= '-webkit-box-orient: vertical;';
					$style .= '-webkit-box-direction: normal;';
					$style .= 'justify-content: center;';
					$style .= '-ms-flex-pack: center;';
				$style     .= '}';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'max-width: 100%;';
					$style .= 'height: auto;';
					$style .= 'display: block;';
					$style .= 'flex: 0 1 auto;';
					$style .= '-ms-flex: 0 1 auto;';
					$style .= '-webkit-box-flex: 0;';
					$style .= 'margin: 0 auto;';
				$style     .= '}';
			$style         .= '';
			$style         .= $support_ie . ' {';
				$style     .= $prefix_mobile . $container . ' {';
					$style .= 'display: block;';
				$style     .= '}';
				$style     .= $prefix_mobile . $component . ' {';
					$style .= ( $is_optin && 'four' === $layout_optin ) ? 'flex-shrink: 0;' : '';
				$style     .= '}';
			$style         .= '}';
		}
	}
}