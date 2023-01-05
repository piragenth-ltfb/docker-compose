<?php
/**
 * Responsive Options section under the "general" tab.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Responsive Options', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Customize the options to control the responsiveness of your modules.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-mobile-breakpoint" id="hustle-mobile-breakpoint-label" class="sui-settings-label"><?php esc_html_e( 'Mobile Breakpoint', 'hustle' ); ?></label>

			<span id="hustle-mobile-breakpoint-description" class="sui-description" style="margin-bottom: 10px;">
				<?php esc_html_e( "Choose how small the visitors' browser screen should be to kick in the mobile-specific styling and layout. The default value is 782px.", 'hustle' ); ?>
			</span>

			<input
				type="number"
				name="mobile_breakpoint"
				min="1"
				value="<?php echo esc_attr( $settings['mobile_breakpoint'] ); ?>"
				id="hustle-mobile-breakpoint"
				class="sui-form-control sui-input-sm sui-field-has-suffix"
				aria-labelledby="hustle-mobile-breakpoint-label"
				aria-describedby="hustle-mobile-breakpoint-description"
			/>

			<span class="sui-field-suffix" aria-hidden="true"><?php esc_html_e( 'px', 'hustle' ); ?></span>

		</div>

	</div>

</div>