<?php
/**
 * Text decoration settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$name = $property_key . '_text_decoration' . $device_suffix;

$options = array(
	'none'               => esc_html__( 'None', 'hustle' ),
	'overline'           => esc_html__( 'Overline', 'hustle' ),
	'line-through'       => esc_html__( 'Line Through', 'hustle' ),
	'underline'          => esc_html__( 'Underline', 'hustle' ),
	'underline overline' => esc_html__( 'Underline Overline', 'hustle' ),
);
?>

<?php if ( 'input' !== $property_key ) : ?>

	<div class="sui-form-field">

		<label id="hustle-<?php esc_attr( $name ); ?>-label" class="sui-label"><?php esc_html_e( 'Text Decoration', 'hustle' ); ?></label>

		<?php
		Hustle_Layout_Helper::get_html_for_options(
			array(
				array(
					'type'       => 'select',
					'name'       => $name,
					'options'    => $options,
					'id'         => 'hustle-' . $name,
					'class'      => 'sui-select',
					'selected'   => $settings[ $name ],
					'attributes' => array(
						'data-attribute'  => $name,
						'aria-labelledby' => 'hustle-' . $name . '-label',
					),
				),
			)
		);
		?>

	</div>

<?php endif; ?>