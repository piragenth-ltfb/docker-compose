<?php
/**
 * Main template for the services (content) tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box" <?php echo 'services' !== $section ? ' style="display: none;"' : ''; ?> data-tab="services">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Services', 'hustle' ); ?></h2>

	</div>

	<div id="hustle-wizard-content" class="sui-box-body">

		<?php
		// SETTING: Counter.
		$this->render(
			'admin/sshare/services/tpl--counter',
			array( 'counter_enabled' => $settings['counter_enabled'] )
		);

		// SETTING: Social Services.
		$this->render( 'admin/sshare/services/tpl--social-services' );
		?>

	</div>

	<div class="sui-box-footer">

		<div class="sui-actions-right">
			<button class="sui-button sui-button-icon-right wpmudev-button-navigation" data-direction="next">
				<span class="sui-loading-text">
					<?php esc_html_e( 'Display Options', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		</div>

	</div>

</div>