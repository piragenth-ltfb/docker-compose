<?php
/**
 * Wizard status bar.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box sui-box-sticky">

	<div class="sui-box-status">

		<div class="sui-status">

			<div class="sui-status-module">

				<?php esc_html_e( 'Status', 'hustle' ); ?>

				<?php if ( $is_active ) : ?>
					<span class="sui-tag sui-tag-blue"><?php esc_html_e( 'Published', 'hustle' ); ?></span>
				<?php else : ?>
					<span class="sui-tag"><?php esc_html_e( 'Draft', 'hustle' ); ?></span>
				<?php endif; ?>

			</div>

			<div id="hustle-unsaved-changes-status" class="sui-status-changes sui-hidden">
				<span class="sui-icon-update" aria-hidden="true"></span>
				<?php esc_html_e( 'Unsaved changes', 'hustle' ); ?>
			</div>

			<div id="hustle-saved-changes-status" class="sui-status-changes">
				<span class="sui-icon-check-tick" aria-hidden="true"></span>
				<?php esc_html_e( 'Saved', 'hustle' ); ?>
			</div>

		</div>

		<div class="sui-actions">

			<button class="sui-button sui-button-ghost hustle-action-save" data-active="0" style="border-color: transparent;">
				<span id="hustle-draft-button-save-draft-text" class="sui-loading-text<?php echo $is_active ? ' sui-hidden-important' : ''; ?>">
					<span class="sui-icon-save" aria-hidden="true"></span>
					<span class="button-text"><?php esc_html_e( 'Save draft', 'hustle' ); ?></span>
				</span>
				<span id="hustle-draft-button-unpublish-text" class="sui-loading-text <?php echo $is_active ? '' : ' sui-hidden-important'; ?>">
					<span class="sui-icon-unpublish" aria-hidden="true"></span>
					<span class="button-text"><?php esc_html_e( 'Unpublish', 'hustle' ); ?></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

			<?php if ( 'social_sharing' !== $module_type ) : ?>

				<button id="hustle-preview-module" class="sui-button">
					<span class="sui-loading-text">
						<span class="sui-icon-eye" aria-hidden="true"></span>
						<span class="button-text"><?php esc_html_e( 'Preview', 'hustle' ); ?></span>
					</span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>

			<?php endif; ?>

			<button
				class="hustle-publish-button sui-button sui-button-blue hustle-action-save"
				data-publish="<?php esc_attr_e( 'Publish', 'hustle' ); ?>"
				data-update="<?php esc_attr_e( 'Update', 'hustle' ); ?>"
				data-active="1">
				<span class="sui-loading-text">
					<span class="sui-icon-web-globe-world" aria-hidden="true"></span>
					<span class="button-text"><?php $is_active ? esc_html_e( 'Update', 'hustle' ) : esc_html_e( 'Publish', 'hustle' ); ?></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>