<?php
/**
 * IP delete row under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<fieldset class="sui-form-field">

	<label class="sui-settings-label"><?php esc_html_e( 'Delete IP Addresses', 'hustle' ); ?></label>

	<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Use this setting to delete selected or all the IP addresses from your database. Note that this will only delete the IP addresses from the database leaving rest of the tracking data and submissions intact.', 'hustle' ); ?></span>

	<button type="button" id="hustle-dialog-open--delete-ips" class="sui-button sui-button-ghost sui-button-red">
		<span class="sui-icon-trash" aria-hidden="true"></span>
		<?php esc_html_e( 'Delete IP Addresses', 'hustle' ); ?>
	</button>

</fieldset>