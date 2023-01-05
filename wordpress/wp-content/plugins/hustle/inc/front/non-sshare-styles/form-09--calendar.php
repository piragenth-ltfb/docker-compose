<?php
/**
 * Calendar.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = '.hustle-module-' . $this->module->module_id . '.hustle-calendar';

// SETTINGS: Colors.
$container_background = $colors['optin_calendar_background'];
$container_title      = $colors['optin_calendar_title'];

$navigation_default = $colors['optin_calendar_arrows'];
$navigation_hover   = $colors['optin_calendar_arrows_hover'];
$navigation_focus   = $colors['optin_calendar_arrows_active'];

$cell_title = $colors['optin_calendar_thead'];

$cell_background_default = $colors['optin_calendar_cell_background'];
$cell_background_hover   = $colors['optin_calendar_cell_bg_hover'];
$cell_background_focus   = $colors['optin_calendar_cell_bg_active'];

$cell_color_default = $colors['optin_calendar_cell_color'];
$cell_color_hover   = $colors['optin_calendar_cell_color_hover'];
$cell_color_focus   = $colors['optin_calendar_cell_color_active'];

// Check if opt-in is enabled.
if ( $is_optin ) {

	// Check if vanilla theme is disabled.
	if ( ! $is_vanilla ) {

		$style .= '';

		$style     .= $component . ':before {';
			$style .= 'background-color: ' . $container_background . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-header .ui-datepicker-title {';
			$style .= 'color: ' . $container_title . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-header .ui-corner-all,';
		$style     .= $component . ' .ui-datepicker-header .ui-corner-all:visited {';
			$style .= 'color: ' . $navigation_default . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-header .ui-corner-all:hover {';
			$style .= 'color: ' . $navigation_hover . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-header .ui-corner-all:focus,';
		$style     .= $component . ' .ui-datepicker-header .ui-corner-all:active {';
			$style .= 'color: ' . $navigation_focus . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-calendar thead th {';
			$style .= 'color: ' . $cell_title . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-calendar tbody tr td a,';
		$style     .= $component . ' .ui-datepicker-calendar tbody tr td a:visited {';
			$style .= 'background-color: ' . $cell_background_default . ';';
			$style .= 'color: ' . $cell_color_default . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-calendar tbody tr td a:hover {';
			$style .= 'background-color: ' . $cell_background_hover . ';';
			$style .= 'color: ' . $cell_color_hover . ';';
		$style     .= '}';

		$style     .= $component . ' .ui-datepicker-calendar tbody tr td a:focus,';
		$style     .= $component . ' .ui-datepicker-calendar tbody tr td a:active {';
			$style .= 'background-color: ' . $cell_background_focus . ';';
			$style .= 'color: ' . $cell_color_focus . ';';
		$style     .= '}';

	}
}