<?php
/**
 * Text alignment settings.
 *
 * @uses admin/global/sui-components/sui-tabs
 *
 * @package Hustle
 * @since 4.3.0
 */

$name = $property_key . '_alignment' . $device_suffix;
?>

<div id="hustle-<?php echo esc_attr( $name ); ?>-form-field" class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Alignment', 'hustle' ); ?></label>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'        => $name,
			'radio'       => true,
			'saved_value' => $settings[ $name ],
			'options'     => array(
				'left'   => array(
					'label'     => esc_html__( 'Left', 'hustle' ),
					'sui-icon'  => 'align-left',
					'icon-size' => 'md',
				),
				'center' => array(
					'label'     => esc_html__( 'Center', 'hustle' ),
					'sui-icon'  => 'align-center',
					'icon-size' => 'md',
				),
				'right'  => array(
					'label'     => esc_html__( 'Right', 'hustle' ),
					'sui-icon'  => 'align-right',
					'icon-size' => 'md',
				),
			),
			'sidetabs'    => true,
		)
	);
	?>

</div>