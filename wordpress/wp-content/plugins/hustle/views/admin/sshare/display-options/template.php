<?php
/**
 * Main template of the display options tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box" <?php echo 'display' !== $section ? 'style="display: none;"' : ''; ?> data-tab="display">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Display Options', 'hustle' ); ?></h2>

	</div>

	<div id="hustle-wizard-display" class="sui-box-body">

		<?php
		// SETTING: Floating Social.
		$this->render(
			'admin/sshare/display-options/tpl--floating-social',
			array( 'settings' => $settings )
		);

		// SETTING: Inline Content.
		$this->render(
			'admin/sshare/display-options/tpl--inline-content',
			array( 'settings' => $settings )
		);

		// SETTING: Widget.
		$this->render(
			'admin/sshare/display-options/tpl--widget',
			array( 'is_widget_enabled' => $settings['widget_enabled'] )
		);

		// SETTING: Shortcode.
		$this->render(
			'admin/sshare/display-options/tpl--shortcode',
			array(
				'shortcode_id'         => $shortcode_id,
				'is_shortcode_enabled' => $settings['shortcode_enabled'],
			)
		);
		?>

	</div>

	<div class="sui-box-footer">

		<button class="sui-button wpmudev-button-navigation" data-direction="prev">
			<span class="sui-icon-arrow-left" aria-hidden="true"></span> <?php esc_html_e( 'Services', 'hustle' ); ?>
		</button>

		<div class="sui-actions-right">

			<button class="sui-button sui-button-icon-right wpmudev-button-navigation" data-direction="next">
				<?php esc_html_e( 'Appearance', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>