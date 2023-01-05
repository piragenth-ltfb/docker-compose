<?php
/**
 * Modal for adding social network platforms in Social Sharing modules.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();
?>

<div class="sui-box-selectors sui-box-selectors-col-5" style="margin-bottom: 0;">

	<ul class="sui-spacing-slim" id="hustle_add_platforms_container"></ul>

</div>

<?php
$after_body_content = ob_get_clean();

$attributes = array(
	'modal_id'           => 'add-platforms',
	'has_description'    => true,
	'modal_size'         => 'lg',

	'header'             => array(
		'title' => __( 'Add Platform', 'hustle' ),
	),
	'body'               => array(
		'classes'     => 'sui-spacing-bottom--0',
		'description' => __( 'Choose the platforms to insert into your social sharing module.', 'hustle' ),
	),
	'after_body_content' => $after_body_content,
	'footer'             => array(
		'classes' => 'sui-content-separated sui-flatten sui-spacing-top--30',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'       => 'hustle-add-platforms',
				'classes'  => 'sui-button-blue',
				'has_load' => true,
				'text'     => __( 'Add Platform', 'hustle' ),
			),
		),
	),
);

$this->render_modal( $attributes );
