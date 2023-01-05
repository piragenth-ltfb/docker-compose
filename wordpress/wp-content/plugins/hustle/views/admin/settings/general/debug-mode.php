<?php
/**
 * Debug section under the "general" tab.
 *
 * @package Hustle
 * @since 4.0.4
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Debug Mode', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Debug mode can help you troubleshoot any issues with your Hustle modules.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label
				for="hustle-debug-enabled"
				class="sui-toggle hustle-toggle-with-container"
				data-toggle-on="debug-enabled"
			>

				<input
					type="checkbox"
					name="debug_enabled"
					value="1"
					id="hustle-debug-enabled"
					data-attribute="debug_enabled"
					aria-labelledby="hustle-debug-enabled-label"
					aria-describedby="hustle-debug-enabled-description"
					<?php checked( $settings['debug_enabled'] ); ?>
				/>

				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-debug-enabled-label" class="sui-toggle-label"><?php esc_html_e( 'Enable Hustle debug mode', 'hustle' ); ?></span>

				<span id="hustle-debug-enabled-description" class="sui-description"><?php esc_html_e( 'Choose whether you want to enable the debug mode or not. It’s recommended to keep it enabled while troubleshooting any issues. When enabled, Hustle will write all the logs in the debug.log file.', 'hustle' ); ?></span>

			</label>

			<div tabindex="0" class="sui-toggle-content" data-toggle-content="debug-enabled">

				<?php
				$this->get_html_for_options(
					array(
						array(
							'type'  => 'inline_notice',
							'icon'  => 'info',
							/* translators: 1: opening 'strong' tag, 2: closing 'strong' tag. */
							'value' => sprintf( esc_html__( 'Hustle debug mode requires WordPress debugging to be enabled. So, make sure you have the %1$sWP_DEBUG%2$s, and %1$sWP_DEBUG_LOG%2$s defines set to %1$strue%2$s in your wp-config file.', 'hustle' ), '<strong>', '</strong>' ),
						),
					)
				);
				?>

			</div>

		</div>

	</div>

</div>