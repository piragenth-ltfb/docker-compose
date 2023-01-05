<?php
/**
 * Main template for the ssharing appearance tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

$is_widget_enabled = ! empty( $display_settings['inline_enabled'] )
	|| ! empty( $display_settings['widget_enabled'] )
	|| ! empty( $display_settings['shortcode_enabled'] );

$is_floating_enabled = ! empty( $display_settings['float_desktop_enabled'] )
	|| ! empty( $display_settings['float_mobile_enabled'] );

$is_empty = ( ! $is_floating_enabled && ! $is_widget_enabled );

$social_types = array(
	'floating' => array(
		'label'            => esc_html__( 'Floating Social', 'hustle' ),
		'description'      => esc_html__( 'Style the floating social module as per your liking.', 'hustle' ),
		'is_empty'         => $is_empty,
		'is_enabled'       => $is_floating_enabled,
		'display_settings' => $display_settings,
		'disabled_message' => esc_html__( 'Floating Social is disabled, enable it from the "Display Options".', 'hustle' ),
	),
	'widget'   => array(
		'label'            => esc_html__( 'Inline / Widget / Shortcode', 'hustle' ),
		'description'      => esc_html__( 'Style the inline module, widget and shortcode as per your liking.', 'hustle' ),
		'is_empty'         => $is_empty,
		'is_enabled'       => $is_widget_enabled,
		'display_settings' => $display_settings,
		'disabled_message' => esc_html__( 'Inline module, widget and shortcode is disabled, enable them from the "Display Options".', 'hustle' ),
	),
);
?>

<div class="sui-box" <?php echo 'appearance' !== $section ? 'style="display: none;"' : ''; ?> data-tab="appearance">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Appearance', 'hustle' ); ?></h2>

	</div>

	<div id="hustle-wizard-appearance" class="sui-box-body">

		<?php

		$this->render(
			'admin/sshare/appearance/tpl--empty-message',
			array( 'is_empty' => $is_empty )
		);


		$this->render(
			'admin/sshare/appearance/tpl--icons-style',
			array(
				'is_empty'   => $is_empty,
				'icon_style' => $settings['icon_style'],
			)
		);

		foreach ( $social_types as $skey => $social ) {

			$this->render(
				'admin/sshare/appearance/tpl--icons-appearance',
				array(
					'key'              => $skey,
					'label'            => $social['label'],
					'description'      => $social['description'],
					'preview'          => 'floating' === $skey ? 'sidenav' : 'content',
					'module'           => $module,
					'is_enabled'       => $social['is_enabled'],
					'is_empty'         => $social['is_empty'],
					'disabled_message' => $social['disabled_message'],
					'settings'         => $settings,
				)
			);
		}
		?>
	</div>

	<div class="sui-box-footer">

		<button class="sui-button wpmudev-button-navigation" data-direction="prev">
			<span class="sui-icon-arrow-left" aria-hidden="true"></span> <?php esc_html_e( 'Display Options', 'hustle' ); ?>
		</button>

		<div class="sui-actions-right">

			<button class="sui-button sui-button-icon-right wpmudev-button-navigation" data-direction="next">
				<?php esc_html_e( 'Visibility', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>