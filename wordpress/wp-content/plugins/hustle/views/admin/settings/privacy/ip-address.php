<?php
/**
 * IP addresses handling section under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'IP Address', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose how you want to handle the IP address of your visitors.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		// Submission retention.
		$this->render(
			'admin/settings/privacy/ip-tracking',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// Submission retention.
		$this->render(
			'admin/settings/privacy/ip-retention',
			array( 'settings' => $settings )
		);
		?>

		<?php
		// Remove IPs From Database.
		$this->render( 'admin/settings/privacy/ip-delete' );
		?>

	</div>

</div>