<?php
/**
 * Displays the bulk action options that can be performed in the listing page.
 *
 * @package Hustle
 * @since 4.0.0
 */

$can_create = current_user_can( 'hustle_create' );
?>
<form class="hustle-bulk-actions-container sui-bulk-actions">

	<label class="sui-checkbox">
		<input type="checkbox" class="hustle-check-all">
		<span aria-hidden="true"></span>
		<span class="sui-screen-reader-text"><?php esc_html_e( 'Select all', 'hustle' ); ?></span>
	</label>

	<select
		name="hustle_action"
		id="hustle-bulk-action-select<?php echo $is_bottom ? '-bottom' : '-top'; ?>"
		class="sui-select sui-select-sm sui-select-inline"
		data-width="200"
		data-theme="icon"
		data-placeholder="<?php esc_html_e( 'Bulk actions', 'hustle' ); ?>"
	>
		<option></option>
		<option value="publish" data-icon="upload-cloud"><?php esc_html_e( 'Publish', 'hustle' ); ?></option>
		<option value="unpublish" data-icon="unpublish"><?php esc_html_e( 'Unpublish', 'hustle' ); ?></option>
		<?php if ( $can_create ) : ?>
			<option value="clone" data-icon="copy"><?php esc_html_e( 'Duplicate', 'hustle' ); ?></option>
		<?php endif; ?>
		<option value="reset-tracking" data-icon="undo"><?php esc_html_e( 'Reset Tracking Data', 'hustle' ); ?></option>
		<option value="enable-tracking" data-icon="graph-line"><?php esc_html_e( 'Enable Tracking', 'hustle' ); ?></option>
		<option value="disable-tracking" data-icon="tracking-disabled"><?php esc_html_e( 'Disable Tracking', 'hustle' ); ?></option>
		<?php if ( $can_create ) : ?>
			<option value="delete" data-icon="trash"><?php esc_html_e( 'Delete', 'hustle' ); ?></option>
		<?php endif; ?>
	</select>

	<button
		type="button"
		class="hustle-bulk-apply-button sui-button"
		data-type="<?php echo esc_attr( $module_type ); ?>"
		data-delete-title="<?php esc_html_e( 'Are you sure?', 'hustle' ); ?>"
		data-delete-description="<?php esc_html_e( 'Are you sure to delete selected modules? Their additional data, like subscriptions and tracking data, will be deleted as well.', 'hustle' ); ?>"
		data-reset-title="<?php esc_html_e( 'Reset Tracking Data', 'hustle' ); ?>"
		data-reset-description="<?php esc_html_e( 'Are you sure you wish to reset the tracking data of these modules?', 'hustle' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle-bulk-action' ) ); ?>"
		<?php disabled( true ); ?>
	>
		<span class="sui-loading-text">
			<?php esc_html_e( 'Apply', 'hustle' ); ?>
		</span>
		<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
	</button>
</form>
