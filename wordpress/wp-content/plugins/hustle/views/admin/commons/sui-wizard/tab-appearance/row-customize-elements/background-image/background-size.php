<?php
/**
 * Background size.
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix = $device ? '_' . $device : '';

$width_label = $this->render(
	'admin/global/components/select-units',
	array(
		'label'         => esc_html__( 'Width', 'hustle' ),
		'name'          => $key . '_width_unit' . $device_suffix,
		'selected'      => $settings[ $key . '_width_unit' . $device_suffix ],
		'exclude_units' => array( 'vh', 'vw' ),
	),
	true
);

$width_size = Hustle_Layout_Helper::get_html_for_options(
	array(
		array(
			'type'       => 'number',
			'name'       => $key . '_width' . $device_suffix,
			'min'        => '0',
			'value'      => $settings[ $key . '_width' . $device_suffix ],
			'id'         => 'hustle-' . $key . '_width' . $device_suffix,
			'attributes' => array(
				'data-attribute'  => $key . '_width' . $device_suffix,
				'aria-labelledby' => 'hustle-' . $key . '_width' . $device_suffix . '-label',
			),
		),
	),
	true
);

$height_label = $this->render(
	'admin/global/components/select-units',
	array(
		'label'         => esc_html__( 'Height', 'hustle' ),
		'name'          => $key . '_height_unit' . $device_suffix,
		'selected'      => $settings[ $key . '_height_unit' . $device_suffix ],
		'exclude_units' => array( 'vh', 'vw' ),
	),
	true
);

$height_size = Hustle_Layout_Helper::get_html_for_options(
	array(
		array(
			'type'       => 'number',
			'name'       => $key . '_height' . $device_suffix,
			'min'        => '0',
			'value'      => $settings[ $key . '_height' . $device_suffix ],
			'id'         => 'hustle-' . $key . '_height' . $device_suffix,
			'attributes' => array(
				'data-attribute'  => $key . '_height' . $device_suffix,
				'aria-labelledby' => 'hustle-' . $key . '_height' . $device_suffix . '-label',
			),
		),
	),
	true
);

$left = array(
	'size'    => 6,
	'content' => '<div class="sui-form-field">' . $width_label . $width_size . '</div>',
);

$right = array(
	'size'    => 6,
	'content' => '<div class="sui-form-field">' . $height_label . $height_size . '</div>',
);

$content = $this->render(
	'admin/global/sui-components/sui-row',
	array(
		'columns' => array( $left, $right ),
	),
	true
);

$options = array(
	'auto'    => array(
		'label' => __( 'Auto', 'hustle' ),
	),
	'contain' => array(
		'label' => __( 'Contain', 'hustle' ),
	),
	'cover'   => array(
		'label' => __( 'Cover', 'hustle' ),
	),
	'custom'  => array(
		'label'   => __( 'Custom', 'hustle' ),
		'content' => $content,
	),
);
?>

<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Background Size', 'hustle' ); ?></h5>

<p class="sui-description"><?php esc_html_e( 'Choose the size of your background image.', 'hustle' ); ?></p>

<?php
$this->render(
	'admin/global/sui-components/sui-tabs',
	array(
		'name'        => $key . '_fit' . $device_suffix,
		'radio'       => true,
		'saved_value' => $settings[ $key . '_fit' . $device_suffix ],
		'sidetabs'    => true,
		'content'     => true,
		'options'     => $options,
	)
);