<?php
/**
 * Customize palette field.
 *
 * @package Hustle
 * @since 4.3.0
 */

$options_args = array(
	'is_optin'            => $is_optin,
	'module_type'         => $module_type,
	'settings'            => $settings,
	'is_settings_page'    => false,
	'capitalize_singular' => $capitalize_singular,
);

if ( $is_optin ) {

	$options_container = $this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'     => 'colors_type',
			'overflow' => true,
			'content'  => true,
			'options'  => array(
				'general' => array(
					'label'   => esc_html__( 'General', 'hustle' ),
					'content' => $this->render(
						'admin/commons/sui-wizard/elements/palette-general',
						$options_args,
						true
					),
				),
				'optin'   => array(
					'label'   => esc_html__( 'Opt-in', 'hustle' ),
					'content' => $this->render(
						'admin/commons/sui-wizard/elements/palette-optin',
						$options_args,
						true
					),
				),
			),
		),
		true
	);
} else {

	$options_container = $this->render(
		'admin/commons/sui-wizard/elements/palette-general',
		$options_args,
		true
	);
}
?>

<div id="hustle-palette-colors" class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Customize the color palette', 'hustle' ); ?></label>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'        => 'customize_colors',
			'radio'       => true,
			'saved_value' => $settings['customize_colors'],
			'sidetabs'    => true,
			'content'     => true,
			'options'     => array(
				'default' => array(
					'value' => '0',
					'label' => esc_html__( 'Default', 'hustle' ),
				),
				'custom'  => array(
					'value'   => '1',
					'label'   => esc_html__( 'Custom', 'hustle' ),
					'content' => $options_container,
				),
			),
		)
	);
	?>

</div>