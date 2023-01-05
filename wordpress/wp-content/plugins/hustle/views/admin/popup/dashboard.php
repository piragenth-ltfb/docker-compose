<?php
/**
 * Social sharing widget in dashboard.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$capitalize_singular = esc_html__( 'Pop-up', 'hustle' );
$capitalize_plural   = esc_html__( 'Pop-ups', 'hustle' );
$smallcaps_singular  = esc_html__( 'pop-up', 'hustle' );
$smallcaps_plural    = esc_html__( 'pop-ups', 'hustle' );

$this->render(
	'admin/dashboard/templates/widget-modules',
	array(
		'modules'             => $popups,
		'widget_name'         => $capitalize_plural,
		'widget_type'         => Hustle_Module_Model::POPUP_MODULE,
		'capability'          => $capability,
		'description'         => esc_html__( 'Pop-ups show up over your page content automatically and can be used to highlight promotions and gain email subscribers.', 'hustle' ),
		'smallcaps_singular'  => $smallcaps_singular,
		'capitalize_singular' => $capitalize_singular,
	)
);