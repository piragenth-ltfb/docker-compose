<?php
/**
 * Modal that shows up after publishing a module.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();

/* translators: module type in small caps and in singular */
$notice_message = sprintf( esc_html__( 'Note that once the schedule is over, your visitors will stop seeing this %s' ), esc_html( $smallcaps_singular ) );
$notice_options = array(
	array(
		'id'         => 'hustle-published-notice-with-schedule-end',
		'type'       => 'inline_notice',
		'icon'       => 'info',
		'value'      => $notice_message,
		'attributes' => array(
			'style' => 'display: none;',
		),
	),
);
$this->get_html_for_options( $notice_options );

$body_content = ob_get_clean();

$attributes = array(
	'modal_id'        => 'publish-flow',
	'has_description' => true,
	'modal_size'      => 'sm',
	'sui_box_attr'    => array(
		/* translators: module type capitalized and in singular */
		'data-loading-title'  => sprintf( __( 'Publishing %s', 'hustle' ), esc_html( $capitalize_singular ) ),
		'data-loading-icon'   => 'loader',
		/* translators: module type in small caps and in singular */
		'data-loading-desc'   => sprintf( __( 'Great work! Please hold tight a few moments while we publish your %s to the world.', 'hustle' ), esc_html( $smallcaps_singular ) ),
		'data-ready-icon'     => 'check',
		'data-ready-title'    => __( 'Ready to go!', 'hustle' ),
		/* translators: module type in small caps and in singular */
		'data-ready-desc'     => sprintf( __( 'Your %s is now published and will start appearing on your site based on the visibility conditions you’ve defined.', 'hustle' ), esc_html( $smallcaps_singular ) ),
		/* translators: module type in small caps and in singular */
		'data-ready-desc-alt' => sprintf( __( 'Your %s is now published and will start appearing on your site based on the visibility conditions you’ve defined and the schedule you have set.', 'hustle' ), esc_html( $smallcaps_singular ) ),
	),
	'header'          => array(
		'classes'       => 'sui-flatten sui-content-center sui-spacing-top--60',
		'title'         => '',
		'title_classes' => 'sui-lg',
	),
	'body'            => array(
		'classes'     => 'sui-content-center sui-spacing-top--20',
		'content'     => $body_content,
		'description' => ' ', // We'll fill this via js according to some selected settings.
	),
);

if ( ! $this->is_branding_hidden ) {
	$image_1x = self::$plugin_url . 'assets/images/hustle-summary.png';
	$image_2x = self::$plugin_url . 'assets/images/hustle-summary@2x.png';

	$attributes['after_body_content'] = $this->render_image_markup( $image_1x, $image_2x, 'sui-image sui-image-center', 'auto', '120px' );
}

$this->render_modal( $attributes );