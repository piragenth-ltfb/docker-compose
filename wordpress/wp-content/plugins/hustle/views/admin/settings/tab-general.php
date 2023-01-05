<?php
/**
 * General tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div id="general-box" class="sui-box" data-tab="general" <?php echo 'general' !== $section ? 'style="display: none;"' : ''; ?>>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'General', 'hustle' ); ?></h2>
	</div>

	<form id="hustle-general-settings-form" class="sui-box-body">

		<?php
		// SETTINGS: IP Tracking.
		$this->render(
			'admin/settings/general/dashboard',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// SETTINGS: From Headers.
		$this->render(
			'admin/settings/general/from-headers',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// SETTINGS: Responsive Options.
		$this->render(
			'admin/settings/general/responsiveness',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// SETTINGS: Pagination.
		$this->render(
			'admin/settings/general/pagination',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// SETTINGS: Debug Mode.
		$this->render(
			'admin/settings/general/debug-mode',
			array( 'settings' => $settings )
		);
		?>

	</form>

	<div class="sui-box-footer">

		<div class="sui-actions-right">

			<button
				class="sui-button sui-button-blue hustle-settings-save"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle-settings' ) ); ?>"
				data-form-id="hustle-general-settings-form"
				data-target="general"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>