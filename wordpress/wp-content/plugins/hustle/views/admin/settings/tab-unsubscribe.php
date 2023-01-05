<?php
/**
 * Unsubscribe tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div id="unsubscribe-box" class="sui-box" data-tab="unsubscribe" <?php echo 'unsubscribe' !== $section ? 'style="display: none;"' : ''; ?>>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Unsubscribe', 'hustle' ); ?></h2>
	</div>

	<form id="hustle-unsubscribe-settings-form" class="sui-box-body">

		<?php
		// SETTINGS: Shortcode.
		$this->render( 'admin/settings/unsubscribe/shortcode' );
		?>

		<?php
		// SETTINGS: Customize Unsubscribe Form.
		$this->render(
			'admin/settings/unsubscribe/customize',
			array(
				'messages' => Hustle_Settings_Admin::get_unsubscribe_messages(),
			)
		);
		?>

		<?php
		// SETTINGS: Unsubscribe Email Copy.
		$this->render(
			'admin/settings/unsubscribe/email-copy',
			array(
				'email' => Hustle_Settings_Admin::get_unsubscribe_email_settings(),
			)
		);
		?>

	</form>

	<div class="sui-box-footer">
		<div class="sui-actions-right">
			<button
				class="sui-button sui-button-blue hustle-settings-save"
				data-form-id="hustle-unsubscribe-settings-form"
				data-target="unsubscribe"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>
		</div>
	</div>

</div>