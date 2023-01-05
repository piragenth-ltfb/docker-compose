<?php
/**
 * Confirmation modal for when deleting things.
 *
 * It's used in:
 * -Main dashboard page => for deleting modules.
 * -All listing pages   => for deleting modules and for deleting their tracking data.
 * -Emails lists page   => for deleting submission entries.
 * -Settings page       => for deleting palettes and IPs.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-modal sui-modal-sm">

<div
	role="dialog"
	id="hustle-dialog--delete"
	class="sui-modal-content"
	aria-modal="true"
	aria-labelledby="hustle-dialog--delete-title"
	aria-describedby="hustle-dialog--delete-description"
>

		<div class="sui-box">

			<div class="sui-box-header sui-content-center sui-flatten sui-spacing-top--60">

				<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
				</button>

				<h3 id="hustle-dialog--delete-title" class="sui-box-title sui-lg"></h3>

			</div>

			<div id="hustle-delete-dialog-content"></div>

		</div>

	</div>

</div>

<script type="text/template" id="hustle-dialog--delete-tpl">

	<form id="hustle-delete-form" method="post">

		<div class="sui-box-body sui-content-center sui-spacing-top--20 sui-spacing-bottom--0">

			<p id="hustle-dialog--delete-description" class="sui-description">
				<# if ( 'undefined' !== typeof description ) { #>
					{{ description }}
				<# } #>
			</p>

				<# if ( 'undefined' !== typeof action ) { #>
					<input type="hidden" name="hustle_action" value="{{ action }}" />
				<# } #>

				<# if ( 'undefined' !== typeof id ) { #>
					<input type="hidden" name="id" value="{{ id }}" />
					<input type="hidden" name="moduleId" value="{{ id }}" />
				<# } #>

				<?php // Used in Entries -> bulk actions. ?>
				<# if ( 'undefined' !== typeof ids ) { #>
					<input type="hidden" name="ids" value="{{ ids }}" />
				<# } #>

				<# if ( 'undefined' !== typeof nonce ) { #>
					<input type="hidden" id="hustle_nonce" name="hustle_nonce" value="{{ nonce }}" />
				<# } #>

		</div>

		<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--40">

			<button type="button" class="sui-button sui-button-ghost hustle-cancel-button">
				<?php esc_attr_e( 'Cancel', 'hustle' ); ?>
			</button>

			<button
				class="sui-button sui-button-ghost sui-button-red hustle-delete-confirm {{ 'undefined' === typeof actionClass ? 'hustle-single-module-button-action' : actionClass }}"
				data-hustle-action="{{ 'undefined' === typeof action ?  'delete' : action }}"
				data-form-id="hustle-delete-form"
				data-modal-close
			>
				<span class="sui-loading-text">
					<span class="sui-icon-trash" aria-hidden="true"></span> <?php esc_attr_e( 'Delete', 'hustle' ); ?>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</form>

</script>