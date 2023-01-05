<?php
/**
 * Colors row.
 *
 * @uses admin/global/sui-components/sui-settings-row
 *
 * @package Hustle
 * @since 4.3.0
 */

$custom_pallete_url = add_query_arg(
	array(
		'page'    => 'hustle_settings',
		'section' => 'palettes',
	),
	esc_url( admin_url( 'admin.php' ) )
);

$options_args = array(
	'settings'            => $settings,
	'is_optin'            => $is_optin,
	'device'              => $device,
	'module_type'         => $module_type,
	'custom_pallete_url'  => $custom_pallete_url,
	'capitalize_singular' => $capitalize_singular,
);

// FIELD: Select color palette.
$content = $this->render(
	'admin/commons/sui-wizard/tab-appearance/row-colors/select-palette',
	$options_args,
	true
);

// FIELD: Customize palette.
$content .= $this->render(
	'admin/commons/sui-wizard/tab-appearance/row-colors/customize-palette',
	$options_args,
	true
);

// Main wrapper.
$this->render(
	'admin/global/sui-components/sui-settings-row',
	array(
		'label'             => esc_html__( 'Colors', 'hustle' ),
		'vanilla_hide'      => true,
		'multi_description' => array(
			sprintf(
				/* translators: module type in lowercase and singular */
				esc_html__( 'Choose a pre-made palette for your %s and further customize it.', 'hustle' ),
				esc_html( $smallcaps_singular )
			),
			sprintf(
				/* translators: 1. Opening 'a' tag to custom palettes, 2. Closing 'a' tag */
				esc_html__( 'You can also %1$screate custom palettes%2$s and reuse them on your modules.', 'hustle' ),
				'<a href="' . esc_url( $custom_pallete_url ) . '">',
				'</a>'
			),
		),
		'content'           => $content,
	)
);