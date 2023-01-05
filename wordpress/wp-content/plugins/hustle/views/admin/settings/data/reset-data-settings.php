<?php
/**
 * Reset data section under the "data" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Reset Plugin', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Needing to start fresh? Use this setting to roll back to the default plugin state.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<button
			id="hustle-dialog-open--reset-data-settings"
			class="sui-button sui-button-ghost"
		>
			<span class="sui-icon-undo" aria-hidden="true"></span> <?php esc_html_e( 'Reset', 'hustle' ); ?>
		</button>

		<span class="sui-description" style="margin-top: 10px;"><?php esc_html_e( 'Note: This will delete all the modules you currently have and their data - submissions, conversion data, and revert all settings to their default state.', 'hustle' ); ?></span>

	</div>

</div>