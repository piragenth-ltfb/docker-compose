<?php
/**
 * Shortcode display type section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Shortcode', 'hustle' ); ?></span>

		<span class="sui-description"><?php esc_html_e( 'Create a shortcode for your social bar and display it wherever you want.', 'hustle' ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-settings--shortcode-enable" class="sui-toggle hustle-toggle-with-container" data-toggle-on="shortcode-enabled">
				<input
					type="checkbox"
					name="shortcode_enabled"
					data-attribute="shortcode_enabled"
					id="hustle-settings--shortcode-enable"
					aria-labelledby="hustle-settings--shortcode-enable-label"
					<?php checked( $is_shortcode_enabled, '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-settings--shortcode-enable-label" class="sui-toggle-label"><?php esc_html_e( 'Enable shortcode module', 'hustle' ); ?></span>
			</label>

			<div id="hustle-shortcode-toggle-wrapper" class="sui-toggle-content" data-toggle-content="shortcode-enabled">

				<span class="sui-description"><?php esc_html_e( 'Just copy the shortcode and paste it wherever you want to render your social bar.', 'hustle' ); ?></span>

				<div class="sui-border-frame">

					<label class="sui-label"><?php esc_html_e( 'Shortcode to render your social bar', 'hustle' ); ?></label>

					<div class="sui-with-button sui-with-button-inside">
						<input
							type="text"
							value="[wd_hustle id='<?php echo esc_attr( $shortcode_id ); ?>' type='social_sharing'/]"
							class="sui-form-control"
							readonly="readonly"
						/>
						<button class="sui-button-icon hustle-copy-shortcode-button">
							<span class="sui-icon-copy" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Copy shortcode', 'hustle' ); ?></span>
						</button>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>