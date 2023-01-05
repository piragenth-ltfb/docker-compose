<?php
/**
 * Slidein widget in dashboard.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$capitalize_singular = esc_html__( 'Slide-in', 'hustle' );
$capitalize_plural   = esc_html__( 'Slide-ins', 'hustle' );
$smallcaps_singular  = esc_html__( 'slide-in', 'hustle' );
$smallcaps_plural    = esc_html__( 'slide-in', 'hustle' );

$this->render(
	'admin/dashboard/templates/widget-modules',
	array(
		'modules'             => $slideins,
		'widget_name'         => $capitalize_plural,
		'widget_type'         => Hustle_Module_Model::SLIDEIN_MODULE,
		'capability'          => $capability,
		'description'         => esc_html__( 'Slide-ins can be used to highlight promotions without covering the whole screen.', 'hustle' ),
		'smallcaps_singular'  => $smallcaps_singular,
		'capitalize_singular' => $capitalize_singular,
	)
);