<?php
/**
 * Reset settings on uninstal section under the "data" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

$options = array(
	'0' => array(
		'value' => '0',
		'label' => esc_html__( 'Preserve', 'hustle' ),
	),
	'1' => array(
		'value'   => '1',
		'label'   => esc_html__( 'Reset', 'hustle' ),
		'content' => $this->get_html_for_options(
			array(
				array(
					'type'  => 'inline_notice',
					'icon'  => 'info',
					'value' => esc_html__( 'This will delete all the modules and their data - submissions, conversion data, and plugin settings when the plugin is uninstalled.', 'hustle' ),
				),
			),
			true
		),
	),
);

$reset_settings_uninstall = '1' === $settings['reset_settings_uninstall']; ?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Uninstallation', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'When you uninstall this plugin, what do you want to do with your plugin’s settings and data?', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'        => 'reset_settings_uninstall',
				'radio'       => true,
				'saved_value' => $settings['reset_settings_uninstall'],
				'sidetabs'    => true,
				'content'     => true,
				'options'     => $options,
			)
		);
		?>

	</div>

</div>