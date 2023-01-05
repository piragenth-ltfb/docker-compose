<?php
/**
 * Data tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

$settings = Hustle_Settings_Admin::get_data_settings();
?>
<div id="data-box" class="sui-box hustle-settings-tab-data" data-tab="data"
<?php
if ( $section && 'data' !== $section ) {
	echo 'style="display: none;"';}
?>
>


	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Data', 'hustle' ); ?></h2>
	</div>

	<form id="hustle-data-settings-form" class="sui-box-body">

		<?php
		// SECTION: Uninstallation.
		$this->render(
			'admin/settings/data/uninstallation-settings',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// SECTION: Reset.
		$this->render( 'admin/settings/data/reset-data-settings' );
		?>

	</form>

	<div class="sui-box-footer">

		<div class="sui-actions-right">

			<button
				class="sui-button sui-button-blue hustle-settings-save"
					data-form-id="hustle-data-settings-form"
					data-target="data"
			>
				<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>


		</div>

	</div>

</div>