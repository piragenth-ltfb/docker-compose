<?php
/**
 * Modal for when an integration is removed in wizard but no other is left in a module.
 *
 * @package Hustle
 * @since 4.0.4
 */

$attributes = array(
	'modal_id'        => 'final-delete',
	'has_description' => true,
	'modal_size'      => 'sm',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		'title'         => __( 'Integration Required!', 'hustle' ),
		'title_classes' => 'sui-lg',
		'description'   => __( 'At least one integration should be connected in a opt-in module. If you choose to continue a local list will be enabled automatically.', 'hustle' ),
	),
	'footer'          => array(
		'classes' => 'sui-content-center sui-flatten',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'         => 'hustle-delete-final-button',
				'classes'    => 'sui-button-ghost sui-button-red',
				'has_load'   => true,
				'text'       => __( 'Continue', 'hustle' ),
				'attributes' => array(
					'data-nonce' => '33333',
				),
			),
		),
	),
);

$this->render_modal( $attributes );