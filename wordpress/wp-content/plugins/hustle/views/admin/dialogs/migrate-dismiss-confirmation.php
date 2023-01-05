<?php
/**
 * Modal to confirm when skipping 3.x to 4.x tracking migration.
 *
 * @package Hustle
 * @since 4.0.0
 */

$attributes = array(
	'modal_id'        => 'migrate-dismiss-confirmation',
	'has_description' => true,
	'modal_size'      => 'sm',

	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		'title'         => __( 'Dismiss Migrate Data Notice', 'hustle' ),
		'title_classes' => 'sui-lg',
	),
	'body'            => array(
		'classes'     => 'sui-content-center sui-spacing-top--20',
		'description' => __( "Are you sure you wish to dismiss this notice? Make sure you've already migrated data of your existing modules, and you don't need to migrate data anymore.", 'hustle' ),
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
				'id'         => 'hustle-dismiss-modal-button',
				'classes'    => 'sui-button-ghost sui-button-red',
				'is_close'   => true,
				'text'       => __( 'Dismiss Forever', 'hustle' ),
				'attributes' => array(
					'data-nonce' => wp_create_nonce( 'hustle_dismiss_notification' ),
					'data-name'  => Hustle_Dashboard_Admin::MIGRATE_NOTICE_NAME,
				),
			),
		),
	),
);

$this->render_modal( $attributes );