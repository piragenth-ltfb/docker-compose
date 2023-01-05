<?php
/**
 * Email section under the "general" tab.
 *
 * @package Hustle
 * @since 4.0.4
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'From Headers', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose the default sender name and sender email address for all of your outgoing emails from Hustle.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-sender-email" id="hustle-sender-email-label" class="sui-label"><?php esc_html_e( 'Sender email address', 'hustle' ); ?></label>

			<input
				type="email"
				name="sender_email_address"
				value="<?php echo isset( $settings['sender_email_address'] ) ? esc_attr( $settings['sender_email_address'] ) : ''; ?>"
				placeholder="admin@website.com"
				id="hustle-sender-email"
				class="sui-form-control"
				aria-labelledby="hustle-sender-email-label"
			/>

		</div>

		<div class="sui-form-field">

			<label for="hustle-sender-name" id="hustle-sender-name-label" class="sui-label"><?php esc_html_e( 'Sender name', 'hustle' ); ?></label>

			<input
				type="text"
				name="sender_email_name"
				value="<?php echo isset( $settings['sender_email_name'] ) ? esc_attr( $settings['sender_email_name'] ) : ''; ?>"
				placeholder="<?php esc_html_e( 'Website Title', 'hustle' ); ?>"
				id="hustle-sender-name"
				class="sui-form-control"
				aria-labelledby="hustle-sender-name-label"
			/>

		</div>

	</div>

</div>