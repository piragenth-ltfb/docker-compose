<?php
/**
 * Typography row.
 *
 * @uses ./typography/options-container
 * @uses admin/global/sui-components/sui-tabs
 * @uses admin/global/sui-components/sui-settings-row
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix = empty( $device ) ? '' : '_' . $device;

$options_args = array(
	'settings'            => $settings,
	'is_optin'            => $is_optin,
	'device'              => $device,
	'smallcaps_singular'  => $smallcaps_singular,
	'capitalize_singular' => $capitalize_singular,
);

$options_container = $this->render(
	'admin/commons/sui-wizard/tab-appearance/row-typography/options-container',
	$options_args,
	true
);

// Tabs wrapper.
$content = $this->render(
	'admin/global/sui-components/sui-tabs',
	array(
		'name'        => 'customize_typography' . $device_suffix,
		'saved_value' => $settings[ 'customize_typography' . $device_suffix ],
		'radio'       => true,
		'options'     => array(
			'default' => array(
				'value' => '0',
				'label' => esc_html__( 'Default', 'hustle' ),
			),
			'custom'  => array(
				'value'   => '1',
				'label'   => esc_html__( 'Custom', 'hustle' ),
				'content' => $options_container,
			),
		),
		'sidetabs'    => true,
		'content'     => true,
	),
	true
);

// Main wrapper.
$this->render(
	'admin/global/sui-components/sui-settings-row',
	array(
		'label'        => esc_html__( 'Typography', 'hustle' ),
		'vanilla_hide' => true,
		/* translators: module type in lowercase and singular */
		'description'  => sprintf( esc_html__( 'Your %s has default font styles. However, you can use Google Fonts and also customize the font styles.', 'hustle' ), esc_html( $smallcaps_singular ) ),
		'content'      => $content,
	)
);