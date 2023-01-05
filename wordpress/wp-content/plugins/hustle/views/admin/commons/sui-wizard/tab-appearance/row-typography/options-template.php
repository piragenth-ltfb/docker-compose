<?php
/**
 * Typography option settings.
 *
 * @uses ./basics/main
 * @uses ./advanced/main
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix      = $device ? '_' . $device : '';
$is_main_content    = 'main_content' === $property_key;
$is_success_message = 'success_message' === $property_key;

$allowed_tags = array(
	'paragraph'     => __( 'Paragraph', 'hustle' ),
	'heading_one'   => __( 'Heading 1', 'hustle' ),
	'heading_two'   => __( 'Heading 2', 'hustle' ),
	'heading_three' => __( 'Heading 3', 'hustle' ),
	'heading_four'  => __( 'Heading 4', 'hustle' ),
	'heading_five'  => __( 'Heading 5', 'hustle' ),
	'heading_six'   => __( 'Heading 6', 'hustle' ),
	'lists'         => __( 'Lists', 'hustle' ),
);

if ( $is_main_content || $is_success_message ) {

	$option  = array();
	$options = array();

	foreach ( $allowed_tags as $key => $label ) {

		$args = array(
			'settings'      => $settings,
			'property_key'  => $property_key . '_' . $key,
			'device_suffix' => $device_suffix,
			//'spacing'       => true,
		);

		$option['label']    = $label;
		$option['content']  = $this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/basic/main', $args, true );
		$option['content'] .= $this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/main', $args, true );

		$options[] = $option;
	}

	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'     => $property_key . $device_suffix,
			'options'  => $options,
			'content'  => true,
			'overflow' => true,
			'class'    => 'tab-inside-box',
		)
	);
} else {

	$args = array(
		'settings'      => $settings,
		'property_key'  => $property_key,
		'device_suffix' => $device_suffix,
		'alignment'     => true,
	);
	if ( 'submit_button' === $property_key ) {
		$args['alignment'] = false;
	}

	echo '<div class="sui-box">';

		echo '<div class="sui-box-body">';

			// SECTION: Basic.
			$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/basic/main', $args );

			// SECTION: Advanced.
			$this->render( 'admin/commons/sui-wizard/tab-appearance/row-typography/advanced/main', $args );

		echo '</div>';

	echo '</div>';

}