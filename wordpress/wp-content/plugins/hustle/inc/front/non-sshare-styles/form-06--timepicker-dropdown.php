<?php
/**
 * Select Dropdown.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-timepicker .ui-timepicker';
$component = $container . ' .ui-timepicker-viewport a';

// SETTINGS: Colors.
$container_background = $colors['optin_dropdown_background'];

$option_color_default = $colors['optin_dropdown_option_color'];
$option_color_hover   = $colors['optin_dropdown_option_color_hover'];

$option_background_default = 'transparent';
$option_background_hover   = $colors['optin_dropdown_option_bg_hover'];

// Check if module is an opt-in.
if ( $is_optin ) {

	// Check if vanilla theme is not enabled.
	if ( ! $is_vanilla ) {

		$style .= '';

		// STATE: Default.
		$style     .= $prefix_mobile . $container . ' {';
			$style .= 'background-color: ' . $container_background . ';';
		$style     .= '}';

		$style     .= $prefix_mobile . $component . ' {';
			$style .= 'color: ' . $option_color_default . ';';
			$style .= 'background-color: ' . $option_background_default . ';';
		$style     .= '}';

		// STATE: Hover.
		$style     .= $prefix_mobile . $component . ':hover,';
		$style     .= $prefix_mobile . $component . ':focus {';
			$style .= 'color: ' . $option_color_hover . ';';
			$style .= 'background-color: ' . $option_background_hover . ';';
		$style     .= '}';

	}
}