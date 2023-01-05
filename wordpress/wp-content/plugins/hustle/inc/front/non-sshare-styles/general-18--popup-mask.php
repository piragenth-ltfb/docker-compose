<?php
/**
 * Pop-up Mask.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-popup-mask';

// SETTINGS: Colors.
$background_color = $colors['overlay_bg'];

if ( $is_popup ) {

	$style .= '';

	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'background-color: ' . $background_color . ';';
	$style     .= '}';

}