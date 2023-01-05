<?php
/**
 * Social sharing wizard.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$module_type         = $module->module_type;
$module_name         = $module->module_name;
$appearance_settings = $module->get_design()->to_array();
$display_settings    = $module->get_display()->to_array();
$content_settings    = $module->get_content()->to_array();
$visibility_settings = $module->get_visibility()->to_array();

$capitalize_singular = esc_html__( 'Social Share', 'hustle' );
$capitalize_plural   = esc_html__( 'Social Shares', 'hustle' );
$smallcaps_singular  = esc_html__( 'social share', 'hustle' );
$smallcaps_plural    = esc_html__( 'social shares', 'hustle' );

$this->render(
	'admin/commons/sui-wizard/wizard',
	array(
		'page_id'             => 'hustle-module-wizard-view',
		'page_tab'            => $section,
		'module'              => $module,
		'module_id'           => $module_id,
		'module_name'         => $module->module_name,
		'module_status'       => $is_active,
		'module_type'         => $module_type,
		'capitalize_singular' => $capitalize_singular,
		'smallcaps_singular'  => $smallcaps_singular,
		'wizard_tabs'         => array(
			'services'   => array(
				'name'     => esc_html__( 'Services', 'hustle' ),
				'template' => 'admin/sshare/services/template',
				'support'  => array(
					'settings' => $content_settings,
					'section'  => $section,
				),
			),
			'display'    => array(
				'name'     => esc_html__( 'Display Options', 'hustle' ),
				'template' => 'admin/sshare/display-options/template',
				'support'  => array(
					'section'      => $section,
					'shortcode_id' => $module->get_shortcode_id(),
					'settings'     => $display_settings,
				),
			),
			'appearance' => array(
				'name'     => esc_html__( 'Appearance', 'hustle' ),
				'template' => 'admin/sshare/appearance/template',
				'support'  => array(
					'section'             => $section,
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'smallcaps_singular'  => $smallcaps_singular,
					'display_settings'    => $display_settings,
					'settings'            => $appearance_settings,
					'module'              => $module,
				),
			),
			'visibility' => array(
				'name'     => esc_html__( 'Visibility', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-visibility',
				'support'  => array(
					'section'            => $section,
					'is_active'          => $is_active,
					'module_type'        => $module_type,
					'settings'           => $visibility_settings,
					'smallcaps_singular' => $smallcaps_singular,
				),
			),
		),
	)
);

// Row: Platform row template.
$this->render( 'admin/sshare/services/platform-row', array() );

// Row: Platform item template.
$this->render( 'admin/commons/sui-wizard/dialogs/add-platform-li', array() );