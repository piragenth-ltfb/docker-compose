<?php
/**
 * Social sharing listing.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$module_type         = Hustle_Module_Model::SOCIAL_SHARING_MODULE;
$multiple_charts     = Hustle_SShare_Model::get_sshare_types( true );
$capitalize_singular = Opt_In_Utils::get_module_type_display_name( $module_type, false, true );
$capitalize_plural   = Opt_In_Utils::get_module_type_display_name( $module_type, true, true );
$smallcaps_singular  = Opt_In_Utils::get_module_type_display_name( $module_type );
$smallcaps_plural    = Opt_In_Utils::get_module_type_display_name( $module_type, true, false );

$this->render(
	'admin/commons/sui-listing/listing',
	array(
		'page_title'          => $capitalize_singular,
		'page_message'        => esc_html__( 'Make it easy for your visitors to share your content by adding floating or inline social sharing prompts.', 'hustle' ),
		'total'               => $total,
		'active'              => $active,
		'modules'             => $modules,
		'module_type'         => $module_type,
		'is_free'             => $is_free,
		'capability'          => $capability,
		'capitalize_singular' => $capitalize_singular,
		'capitalize_plural'   => $capitalize_plural,
		'smallcaps_singular'  => $smallcaps_singular,
		'multiple_charts'     => $multiple_charts,
		'entries_per_page'    => $entries_per_page,
		'message'             => $message,
		'sui'                 => $sui,
	)
);