<?php
/**
 * Embedded wizard.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$module_type         = $module->module_type;
$module_name         = $module->module_name;
$appearance_settings = $module->get_design()->to_array();
$content_settings    = $module->get_content()->to_array();
$email_settings      = $module->get_emails()->to_array();
$visibility_settings = $module->get_visibility()->to_array();
$form_elements       = ! empty( $email_settings['form_elements'] ) ? $email_settings['form_elements'] : array();

$capitalize_singular = esc_html__( 'Embed', 'hustle' );
$capitalize_plural   = esc_html__( 'Embeds', 'hustle' );
$smallcaps_singular  = esc_html__( 'embed', 'hustle' );
$smallcaps_plural    = esc_html__( 'embeds', 'hustle' );

$this->render(
	'admin/commons/sui-wizard/wizard',
	array(
		'page_id'             => 'hustle-module-wizard-view',
		'page_tab'            => $section,
		'module'              => $module,
		'module_id'           => $module_id,
		'module_name'         => $module->module_name,
		'module_mode'         => $is_optin,
		'module_status'       => $is_active,
		'module_type'         => $module_type,
		'capitalize_singular' => $capitalize_singular,
		'smallcaps_singular'  => $smallcaps_singular,
		'form_elements'       => $form_elements,
		'wizard_tabs'         => array(
			'content'      => array(
				'name'     => esc_html__( 'Content', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-content',
				'support'  => array(
					'section'             => $section,
					'settings'            => $content_settings,
					'is_optin'            => $is_optin,
					'module_type'         => $module_type,
					'smallcaps_singular'  => $smallcaps_singular,
					'capitalize_singular' => $capitalize_singular,
				),
			),
			'emails'       => array(
				'name'     => esc_html__( 'Emails', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-emails',
				'support'  => array(
					'section'  => $section,
					'settings' => $email_settings,
					'module'   => $module,
				),
				'is_optin' => true,
			),
			'integrations' => array(
				'name'     => esc_html__( 'Integrations', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-integrations',
				'support'  => array(
					'section'            => $section,
					'settings'           => $module->get_integrations_settings()->to_array(),
					'smallcaps_singular' => $smallcaps_singular,
				),
				'is_optin' => true,
			),
			'appearance'   => array(
				'name'     => esc_html__( 'Appearance', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-appearance',
				'support'  => array(
					'section'             => $section,
					'is_optin'            => $is_optin,
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'smallcaps_singular'  => $smallcaps_singular,
					'feature_image'       => $content_settings['feature_image'],
					'settings'            => $appearance_settings,
				),
			),
			'display'      => array(
				'name'     => esc_html__( 'Display Options', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-display',
				'support'  => array(
					'settings'     => $module->get_display()->to_array(),
					'section'      => $section,
					'shortcode_id' => $module->get_shortcode_id(),
				),
			),
			'visibility'   => array(
				'name'     => esc_html__( 'Visibility', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-visibility',
				'support'  => array(
					'section'            => $section,
					'module_type'        => $module_type,
					'settings'           => $visibility_settings,
					'smallcaps_singular' => $smallcaps_singular,
				),
			),
			'behavior'     => array(
				'name'     => esc_html__( 'Behavior', 'hustle' ),
				'template' => 'admin/commons/sui-wizard/templates/tab-behaviour',
				'support'  => array(
					'section'             => $section,
					'settings'            => $module->get_settings()->to_array(),
					'is_optin'            => $is_optin,
					'is_active'           => $is_active,
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'capitalize_plural'   => $capitalize_plural,
					'smallcaps_singular'  => $smallcaps_singular,
					'shortcode_id'        => $module->get_shortcode_id(),
					'show_cta'            => $content_settings['show_cta'],
				),
			),
		),
	)
);