<?php
/**
 * Social sharing widget in dashboard.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$this->render(
	'admin/dashboard/templates/widget-modules',
	array(
		'modules'             => $social_sharings,
		'widget_name'         => Opt_In_Utils::get_module_type_display_name( Hustle_Module_Model::SOCIAL_SHARING_MODULE, true, true ),
		'widget_type'         => Hustle_Module_Model::SOCIAL_SHARING_MODULE,
		'capability'          => $capability,
		'smallcaps_singular'  => Opt_In_Utils::get_module_type_display_name( Hustle_Module_Model::SOCIAL_SHARING_MODULE ),
		'capitalize_singular' => Opt_In_Utils::get_module_type_display_name( Hustle_Module_Model::SOCIAL_SHARING_MODULE, false, true ),
		'description'         => esc_html__( 'Make it easy for your visitors to share your content by adding floating or inline social sharing prompts.', 'hustle' ),
	)
);