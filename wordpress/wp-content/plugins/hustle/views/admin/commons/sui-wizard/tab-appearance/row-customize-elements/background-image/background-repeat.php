<?php
/**
 * Background repeat.
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix = $device ? '_' . $device : '';

$name = $key . '_repeat' . $device_suffix;

$options = array(
	'repeat'    => array(
		'label' => __( 'repeat', 'hustle' ),
	),
	'repeat-x'  => array(
		'label' => __( 'repeat-x', 'hustle' ),
	),
	'repeat-y'  => array(
		'label' => __( 'repeat-y', 'hustle' ),
	),
	'no-repeat' => array(
		'label' => __( 'no-repeat', 'hustle' ),
	),
);
?>

<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Background Repeat', 'hustle' ); ?></h5>

<p class="sui-description"><?php esc_html_e( 'Choose if/how you want your background image repeated.', 'hustle' ); ?></p>

<?php
$this->render(
	'admin/global/sui-components/sui-tabs',
	array(
		'name'        => $name,
		'radio'       => true,
		'saved_value' => $settings[ $name ],
		'sidetabs'    => true,
		'options'     => $options,
	)
);