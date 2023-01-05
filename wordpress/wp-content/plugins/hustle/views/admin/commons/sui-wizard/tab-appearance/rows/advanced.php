<?php
/**
 * Advanced row.
 *
 * @package Hustle
 * @since 4.3.0
 */

$is_desktop_tab = empty( $device );

// SECTION: Border, Spacing and Shadow.
$content = $this->render(
	'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow',
	array(
		'settings'            => $settings,
		'is_optin'            => $is_optin,
		'device'              => ! empty( $device ) ? $device : false,
		'smallcaps_singular'  => $smallcaps_singular,
		'capitalize_singular' => $capitalize_singular,
		'vanilla_hide'        => true,
	),
	true
);

// SECTION: Module Size.
$content .= $this->render(
	'admin/commons/sui-wizard/tab-appearance/row-advanced/module-size',
	array(
		'settings'            => $settings,
		'is_optin'            => $is_optin,
		'device'              => isset( $device ) ? $device : '',
		'smallcaps_singular'  => $smallcaps_singular,
		'capitalize_singular' => $capitalize_singular,
		'vanilla_hide'        => true,
	),
	true
);

// SECTION: Vanilla Theme.
if ( $is_desktop_tab ) {

	$content .= $this->render(
		'admin/commons/sui-wizard/tab-appearance/row-advanced/vanilla-theme',
		array(
			'settings'           => $settings,
			'is_optin'           => $is_optin,
			'smallcaps_singular' => $smallcaps_singular,
		),
		true
	);
}

// Main wrapper.
$this->render(
	'admin/global/sui-components/sui-settings-row',
	array(
		'label'        => esc_html__( 'Advanced', 'hustle' ),
		/* translators: module type in lowercase and singular */
		'description'  => sprintf( esc_html__( 'Have granular control over the appearance of your %s with these advanced customization options.', 'hustle' ), esc_html( $smallcaps_singular ) ),
		'content'      => $content,
		'vanilla_hide' => ! $is_desktop_tab,
	)
);