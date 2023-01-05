<?php
/**
 * Close Button.
 *
 * @package Hustle
 * @since 4.3.0
 */

// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect

$component = 'button.hustle-button-close';

// SETTINGS: Colors.
$color_default = $colors['close_button_static_color'];
$color_hover   = $colors['close_button_hover_color'];
$color_focus   = $colors['recaptcha_copy_link_hover'];

if ( ! $is_embed && ! $is_vanilla ) {

	$style .= '';

	$style     .= $prefix_mobile . $component . ' {';
		$style .= 'color: ' . $color_default . ';';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ':hover {';
		$style .= 'color: ' . $color_hover . ';';
	$style     .= '}';

	$style     .= $prefix_mobile . $component . ':focus,';
	$style     .= $prefix_mobile . $component . ':active {';
		$style .= 'color: ' . $color_focus . ';';
	$style     .= '}';

}