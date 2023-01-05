<?php
/**
 * Reset data modal for the "data" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

$attributes = array(
	'modal_id'        => 'reset-data-settings',
	'has_description' => true,
	'modal_size'      => 'sm',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--40',
		'title'         => __( 'Reset Plugin', 'hustle' ),
		'title_classes' => 'sui-lg',
	),
	'body'            => array(
		'classes'     => 'sui-content-center sui-spacing-top--20',
		'description' => __( 'Are you sure you want to reset the plugin to its default state?', 'hustle' ),
	),
	'footer'          => array(
		'classes' => 'sui-flatten sui-content-center sui-spacing-bottom--40',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'         => 'hustle-reset-settings',
				'classes'    => 'sui-button-red sui-button-ghost',
				'icon'       => 'undo',
				'has_load'   => true,
				'text'       => __( 'Reset', 'hustle' ),
				'attributes' => array(
					'data-notice' => 'hustle-notice-success--reset-settings',
					'data-nonce'  => wp_create_nonce( 'hustle_reset_settings' ),
				),
			),
		),
	),
);

$this->render_modal( $attributes );