<?php
/**
 * Embedded widget in dashboard.
 *
 * @var Opt_In $this
 *
 * @package Hustle
 * @since 4.0.0
 */

$capitalize_singular = esc_html__( 'Embed', 'hustle' );
$capitalize_plural   = esc_html__( 'Embeds', 'hustle' );
$smallcaps_singular  = esc_html__( 'embed', 'hustle' );
$smallcaps_plural    = esc_html__( 'embeds', 'hustle' );

$this->render(
	'admin/dashboard/templates/widget-modules',
	array(
		'modules'             => $embeds,
		'widget_name'         => $capitalize_plural,
		'widget_type'         => Hustle_Module_Model::EMBEDDED_MODULE,
		'capability'          => $capability,
		'description'         => esc_html__( 'Embeds allow you to insert promotions or newsletter signups directly into your content automatically or with shortcodes.', 'hustle' ),
		'smallcaps_singular'  => $smallcaps_singular,
		'capitalize_singular' => $capitalize_singular,
	)
);