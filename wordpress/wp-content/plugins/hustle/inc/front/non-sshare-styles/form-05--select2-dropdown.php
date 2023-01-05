<?php
/**
 * Select Dropdown.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$container = '.hustle-module-' . $this->module->module_id . '.hustle-dropdown';
$component = $container . ' .select2-results .select2-results__options .select2-results__option';

// SETTINGS: Colors.
$container_background = $colors['optin_dropdown_background'];

$option_color_default  = $colors['optin_dropdown_option_color'];
$option_color_hover    = $colors['optin_dropdown_option_color_hover'];
$option_color_selected = $colors['optin_dropdown_option_color_active'];

$option_background_default  = 'transparent';
$option_background_hover    = $colors['optin_dropdown_option_bg_hover'];
$option_background_selected = $colors['optin_dropdown_option_bg_active'];

// Check if module is an opt-in.
if ( $is_optin ) {

	// Check if vanilla theme is disabled.
	if ( ! $is_vanilla ) {

		$style .= '';

		// STATE: Default.
		$style     .= $container . ' {';
			$style .= 'background-color: ' . $container_background . ';';
		$style     .= '}';

		$style     .= $component . ' {';
			$style .= 'color: ' . $option_color_default . ';';
			$style .= 'background-color: ' . $option_background_default . ';';
		$style     .= '}';

		// STATE: Hover.
		$style     .= $component . '.select2-results__option--highlighted {';
			$style .= 'color: ' . $option_color_hover . ';';
			$style .= 'background-color: ' . $option_background_hover . ';';
		$style     .= '}';

		// STATE: Selected.
		$style     .= $component . '[aria-selected="true"] {';
			$style .= 'color: ' . $option_color_selected . ';';
			$style .= 'background-color: ' . $option_background_selected . ';';
		$style     .= '}';
	}
}