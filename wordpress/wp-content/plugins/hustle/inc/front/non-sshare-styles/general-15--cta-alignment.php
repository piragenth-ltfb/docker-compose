<?php
/**
 * CTA Button Alignment.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-layout .hustle-cta-container';
$component = $container . ' .hustle-button-cta';

// CONDITIONALS: Check if module has mobile appearance settings enabled.
$is_mobile_enabled  = ( '1' === $design['enable_mobile_settings'] );
$is_mobile_disabled = ( '1' !== $design['enable_mobile_settings'] );

// CONDITIONALS: Has button.
$has_cta = ( '1' === $content['show_cta'] && ( '' !== $content['cta_label'] && '' !== $content['cta_url'] ) );

// SETTINGS: Alignment.
$alignment        = $design['cta_buttons_alignment'];
$mobile_alignment = ( $is_mobile_enabled ) ? $design['cta_buttons_alignment_mobile'] : $alignment;

// ==================================================
// Check if call to action button exists.
if ( $has_cta ) {

	$style .= ' ';

	// Mobile styles.
	if ( 'full' === $mobile_alignment ) {
		$style     .= $prefix_mobile . $component . ' {';
			$style .= 'width: 100%;';
			$style .= 'display: block;';
		$style     .= '}';
	} else {
		$style     .= $prefix_mobile . $container . ' {';
			$style .= 'text-align: ' . $mobile_alignment . ';';
		$style     .= '}';
		$style     .= $prefix_mobile . $component . ' {';
			$style .= 'width: auto;';
			$style .= 'display: inline-block;';
		$style     .= '}';
	}

	// Desktop styles.
	if ( $is_mobile_enabled ) {

		if ( 'full' === $alignment ) {
			$style         .= $breakpoint . ' {';
				$style     .= ( 'full' !== $mobile_alignment ) ? $prefix_desktop . $container . ' {' : '';
					$style .= ( 'full' !== $mobile_alignment ) ? 'text-align: center;' : '';
				$style     .= ( 'full' !== $mobile_alignment ) ? '}' : '';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: 100%;';
					$style .= 'display: block;';
				$style     .= '}';
			$style         .= '}';
		} else {
			$style         .= $breakpoint . ' {';
				$style     .= $prefix_desktop . $container . ' {';
					$style .= 'text-align: ' . $alignment . ';';
				$style     .= '}';
				$style     .= $prefix_desktop . $component . ' {';
					$style .= 'width: auto;';
					$style .= 'display: inline-block;';
				$style     .= '}';
			$style         .= '}';
		}
	}
}