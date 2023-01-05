<?php
/**
 * Displays the action options that can be performed per module in the listing page.
 *
 * @package Hustle
 * @since 4.0.0
 */

$is_embedded_or_social = Hustle_Module_Model::EMBEDDED_MODULE === $module->module_type || Hustle_Module_Model::SOCIAL_SHARING_MODULE === $module->module_type;
$free_limit_reached    = Hustle_Data::was_free_limit_reached( $module->module_type );

$can_edit   = Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $module->id );
$can_create = current_user_can( 'hustle_create' );
$can_emails = current_user_can( 'hustle_access_emails' );

$is_tracking_disabled = empty( $module->get_tracking_types() );

// BUTTON: Open dropdown list. ?>
<button class="sui-button-icon sui-dropdown-anchor" aria-expanded="false">
	<span class="sui-loading-text">
		<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
	</span>
	<span class="sui-screen-reader-text"><?php esc_html_e( 'More options', 'hustle' ); ?></span>
	<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
</button>

<?php // Start dropdown options. ?>

<ul>

	<?php
	// Edit module.
	if ( ! empty( $dashboard ) && $can_edit ) :
		?>

		<li><a href="<?php echo esc_url( $module->get_edit_url() ); ?>" class="hustle-onload-icon-action">
			<span class="sui-icon-pencil" aria-hidden="true"></span>
			<?php esc_html_e( 'Edit', 'hustle' ); ?>
		</a></li>

		<?php
	endif;
	?>

	<?php
	// Preview module.
	if ( empty( $edit_page ) && Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) :
		?>

		<li><button
			class="hustle-preview-module-button"
			data-id="<?php echo esc_attr( $module->id ); ?>"
			data-type="<?php echo esc_attr( $module->module_type ); ?>"
			data-name="<?php echo esc_attr( $module->module_name ); ?>"
		>
			<span class="sui-icon-eye" aria-hidden="true"></span>
			<?php esc_html_e( 'Preview', 'hustle' ); ?>
		</button></li>

		<?php
	endif;
	?>

	<?php
	// Copy shortcode.
	if ( $is_embedded_or_social ) :
		?>

		<li><button
			class="hustle-copy-shortcode-button"
			data-shortcode='[wd_hustle id="<?php echo esc_attr( $module->get_shortcode_id() ); ?>" type="<?php echo esc_attr( $module->module_type ); ?>"/]'>
			<span class="sui-icon-code" aria-hidden="true"></span>
			<?php esc_html_e( 'Copy Shortcode', 'hustle' ); ?>
		</button></li>

	<?php endif; ?>

	<?php
	// Toggle Status button.
	if ( $can_edit && empty( $edit_page ) ) :
		?>
		<li><button
			class="hustle-single-module-button-action hustle-onload-icon-action"
			data-module-id="<?php echo esc_attr( $module->id ); ?>"
			data-hustle-action="toggle-status"
			data-active="0"
		>
			<span class="hustle-toggle-status-button-description<?php echo $module->active || ! empty( $edit_page ) ? '' : ' sui-hidden'; ?>">
				<span class="sui-icon-unpublish" aria-hidden="true"></span>
				<?php esc_html_e( 'Unpublish', 'hustle' ); ?>
			</span>
			<span class="hustle-toggle-status-button-description <?php echo $module->active || ! empty( $edit_page ) ? ' sui-hidden' : ''; ?>">
				<span class="sui-icon-web-globe-world" aria-hidden="true"></span>
				<?php esc_html_e( 'Publish', 'hustle' ); ?>
			</span>
		</button></li>
	<?php endif; ?>

<?php
// View Email List.
if (
		Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type
		&& $can_emails
		&& 'optin' === $module->module_mode
	) {
	$url = add_query_arg(
		array(
			'page'        => Hustle_Data::ENTRIES_PAGE,
			'module_type' => $module->module_type,
			'module_id'   => $module->module_id,
		),
		admin_url( 'admin.php' )
	);
	printf( '<li><a href="%s" class="hustle-onload-icon-action">', esc_url( $url ) );
	echo '<span class="sui-icon-community-people" aria-hidden="true"></span> ';
	esc_html_e( 'View Email List', 'hustle' );
	echo '</a></li>';
}
?>

<?php
// Duplicate.
if ( empty( $dashboard ) && $can_create ) :
	?>
	<li><button
		class="<?php echo ! $free_limit_reached ? 'hustle-single-module-button-action hustle-onload-icon-action' : 'hustle-upgrade-modal-button'; ?>"
		data-module-id="<?php echo esc_attr( $module->id ); ?>"
		data-hustle-action="clone"
	>
		<span class="sui-icon-copy" aria-hidden="true"></span>
		<?php esc_html_e( 'Duplicate', 'hustle' ); ?>
	</button></li>
<?php endif; ?>

<?php
// Tracking.
if ( empty( $dashboard ) && $can_edit ) :
	if ( empty( $edit_page ) ) :
		?>

	<li>
		<?php if ( ! $is_embedded_or_social ) : ?>

			<button
				class="hustle-single-module-button-action hustle-onload-icon-action"
				data-module-id="<?php echo esc_attr( $module->id ); ?>"
				data-hustle-action="toggle-tracking"
			>
				<span class="hustle-toggle-tracking-button-description<?php echo $is_tracking_disabled ? ' sui-hidden' : ''; ?>">
					<span class="sui-icon-tracking-disabled" aria-hidden="true"></span>
					<?php esc_html_e( 'Disable Tracking', 'hustle' ); ?>
				</span>
				<span class="hustle-toggle-tracking-button-description<?php echo $is_tracking_disabled ? '' : ' sui-hidden'; ?>">
					<span class="sui-icon-graph-line" aria-hidden="true"></span>
					<?php esc_html_e( 'Enable Tracking', 'hustle' ); ?>
				</span>
			</button>
			<?php
		else :

			$trackings         = $module->get_tracking_types();
			$enabled_trackings = $trackings ? implode( ',', array_keys( $trackings ) ) : '';
			?>
			<button
				class="hustle-manage-tracking-button"
				data-module-id="<?php echo esc_attr( $module->id ); ?>"
				data-tracking-types="<?php echo esc_attr( $enabled_trackings ); ?>"
			>
				<span class="sui-icon-graph-line" aria-hidden="true"></span>
				<?php esc_html_e( 'Manage Tracking', 'hustle' ); ?>
			</button>
		<?php endif; ?>
	</li>

	<?php endif; ?>

	<li>
		<button class="hustle-module-tracking-reset-button"
				data-module-id="<?php echo esc_attr( $module->id ); ?>"
				data-title="<?php esc_attr_e( 'Reset Tracking Data', 'hustle' ); ?>"
				data-description="<?php esc_attr_e( 'Are you sure you wish reset the tracking data of this module?', 'hustle' ); ?>"
			>
			<span class="sui-icon-undo" aria-hidden="true"></span> <?php esc_html_e( 'Reset Tracking Data', 'hustle' ); ?>
		</button>
	</li>

<?php endif; ?>

	<?php // Export. ?>
	<li>
		<form method="post">
			<input type="hidden" name="hustle_action" value="export">
			<input type="hidden" name="id" value="<?php echo esc_attr( $module->id ); ?>">
			<input type="hidden" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'hustle_module_export' ) ); ?>">
			<button>
				<span class="sui-icon-cloud-migration" aria-hidden="true"></span>
				<?php esc_html_e( 'Export', 'hustle' ); ?>
			</button>
		</form>
	</li>

	<?php
	// Import.
	if ( empty( $edit_page ) && empty( $dashboard ) && $can_edit ) :
		?>

		<li><button
			class="hustle-import-module-button"
			data-module-id="<?php echo esc_attr( $module->id ); ?>"
			data-module-mode="<?php echo esc_attr( $module->module_mode ); ?>"
		>
			<span>
				<span class="sui-icon-upload-cloud" aria-hidden="true"></span>
				<?php esc_html_e( 'Import', 'hustle' ); ?>
			</span>
		</button></li>

		<?php
	endif;
	?>

	<?php
	// Delete module.
	if ( $can_create ) :
		?>
		<li><button class="sui-option-red hustle-delete-module-button"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_listing_request' ) ); ?>"
			data-type="<?php echo esc_attr( $module->module_type ); ?>"
			data-id="<?php echo esc_attr( $module->id ); ?>"
			<?php /* translators: module type capitalized and in singular */ ?>
			data-title="<?php printf( esc_attr__( 'Delete %s', 'hustle' ), esc_attr( $capitalize_singular ) ); ?>"
			<?php /* translators: module type in small caps and in singular */ ?>
			data-description="<?php printf( esc_attr__( 'Are you sure you wish to permanently delete this %s? Its additional data, like subscriptions and tracking data, will be deleted as well.', 'hustle' ), esc_attr( $smallcaps_singular ) ); ?>"
		>
			<span class="sui-icon-trash" aria-hidden="true"></span> <?php esc_html_e( 'Delete', 'hustle' ); ?>
		</button></li>
	<?php endif; ?>

</ul>