<?php
/**
 * Underscore template for the form fields' settings.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<script id="hustle-form-field-row-tpl" type="text/template">

	<div
		id="hustle-optin-field--{{ name }}"
		class="sui-builder-field sui-can-move ui-sortable-handle"
		data-field-id="{{ name }}"
	>

		<span class="sui-icon-drag" aria-hidden="true"></span>

		<div class="sui-builder-field-label">

			<span class="sui-icon-{{ icon }}" aria-hidden="true"></span>

			<span class="hustle-field-label"><span class="hustle-field-label-text">{{ label }}</span> <span class="sui-error"{{ ( _.isFalse( required ) ) ? 'style=display:none;' : '' }}>*</span></span>

		</div>

		<div class="sui-dropdown">

			<button class="sui-button-icon sui-dropdown-anchor">
				<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
				<span class="sui-screen-reader-text">{{ label }} <?php esc_html_e( 'field settings', 'hustle' ); ?></span>
			</button>

			<ul>

				<li><button class="hustle-optin-field--edit">
					<span class="sui-icon-pencil" aria-hidden="true"></span> <?php esc_html_e( 'Edit Field', 'hustle' ); ?>
				</button></li>

				<li><button class="hustle-optin-field--copy">
					<span class="sui-icon-copy" aria-hidden="true"></span> <?php esc_html_e( 'Duplicate', 'hustle' ); ?>
				</button></li>

				<# if ( 'undefined' !== typeof can_delete && ( true === can_delete || 'true' === can_delete ) ) { #>
					<li><button class="hustle-optin-field--delete">
						<span class="sui-icon-trash" aria-hidden="true"></span> <?php esc_html_e( 'Delete', 'hustle' ); ?>
					</button></li>
				<# } #>

			</ul>

		</div>

	</div>

</script>