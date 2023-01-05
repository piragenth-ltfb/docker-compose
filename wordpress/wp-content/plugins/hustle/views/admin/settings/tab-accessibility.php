<?php
/**
 * Accessibility tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

$accessibility_color = ! empty( $settings['accessibility_color'] ); ?>
<div id="accessibility-box" class="sui-box" data-tab="accessibility"
<?php
if ( 'accessibility' !== $section ) {
	echo 'style="display: none;"';}
?>
>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Accessibility', 'hustle' ); ?></h2>
	</div>

	<form id="hustle-accessibility-settings-form" class="sui-box-body">

		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-2">

				<p><?php esc_html_e( 'Enable support for any accessibility enhancements available in the plugin interface.', 'hustle' ); ?></p>

			</div>

		</div>

		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-1">
				<span class="sui-settings-label"><?php esc_html_e( 'High Contrast Mode', 'hustle' ); ?></span>
				<span class="sui-description"><?php esc_html_e( 'Increase the visibility and accessibility of the elements and components of the plugin to meet WCAG AAA requirements.', 'hustle' ); ?></span>
			</div>

			<div class="sui-box-settings-col-2">

				<div class="sui-form-field">

					<label for="hustle-accessibility-color" class="sui-toggle">

						<input
							type="checkbox"
							value="1"
							name="hustle-accessibility-color"
							id="hustle-accessibility-color"
							aria-labelledby="hustle-accessibility-color-label"
							<?php checked( $accessibility_color ); ?>
						>

						<span class="sui-toggle-slider" aria-hidden="true"></span>

						<span id="hustle-accessibility-color-label" class="sui-toggle-label"><?php esc_html_e( 'Enable high contrast mode', 'hustle' ); ?></span>

					</label>

				</div>

			</div>

		</div>

	</form>

	<div class="sui-box-footer">

		<div class="sui-actions-right">

			<button class="sui-button sui-button-blue hustle-settings-save"
				data-form-id="hustle-accessibility-settings-form"
				data-target="accessibility"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>