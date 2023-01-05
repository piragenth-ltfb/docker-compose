<?php
/**
 * Modal for a heads up when removing a global integration when it's in use in a module.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="hustle-dialog--remove-active"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog--remove-active-title"
		aria-describedby="hustle-dialog--remove-active-description"
	>

		<div class="sui-box">

			<div class="sui-box-header sui-content-center sui-flatten sui-spacing-top--60">

				<button class="sui-button-icon sui-button-float--right hustle-modal-close" data-modal-close>
					<span class="sui-icon-close sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
				</button>

				<button id="hustle-remove-active-integration-back" class="sui-button-icon sui-button-float--left">
					<span class="sui-icon-chevron-left sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Return to previous step', 'hustle' ); ?></span>
				</button>

				<figure class="sui-box-logo" aria-hidden="true"></figure>

				<h3 id="hustle-dialog--remove-active-title" class="sui-box-title sui-lg"></h3>

				<p id="hustle-dialog--remove-active-description" class="sui-description"></p>

			</div>

			<div class="sui-box-body sui-content-center">

				<div id="hustle-integration-active-modules" class="hustle-active-module-list">

					<span class="sui-label" style="padding-left: 10px;"><?php esc_html_e( 'Modules', 'hustle' ); ?></span>

					<table class="sui-table hui-table--apps-off">

						<tbody></tbody>

					</table>

				</div>

			</div>

			<div class="sui-box-footer sui-flatten sui-content-separated">

				<button class="sui-button sui-button-ghost" data-modal-close>
					<?php esc_html_e( 'Cancel', 'hustle' ); ?>
				</button>

				<button id="hustle-remove-active-button" class="sui-button sui-button-ghost sui-button-red">
					<span class="sui-loading-text"><?php esc_html_e( 'Disconnect anyway', 'hustle' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>

			</div>

		</div>

	</div>

</div>

<script id="hustle-modules-active-integration-tpl" type="text/template">

	<tr>

		<td class="sui-table-item-title">

			<span class="hui-app--wrap">
				<span class="hui-app--title"><span class="sui-icon-{{type}}" aria-hidden="true"></span> {{name}}</span>
				<span class="hui-app--link"><a href="{{editUrl}}" target="_blank" class="sui-button-icon">
					<span class="sui-icon-pencil" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Edit your module integrations', 'hustle' ); ?></span>
				</a></span>
			</span>

		</td>

	</tr>

</script>

<script id="hustle-modules-active-integration-img-tpl" type="text/template">

	<?php // Image. The sample in SUI has srcet. ?>
	<img
		src="{{ image }}"
		alt="{{ title }}"
	/>

</script>

<script id="hustle-modules-active-integration-header-tpl" type="text/template">

	<?php // Title. ?>
	<?php esc_html_e( 'Disconnect ', 'hustle' ); ?> {{ title }}

</script>

<script id="hustle-modules-active-integration-desc-tpl" type="text/template">

	<?php // Description. ?>
	{{title}}<?php esc_html_e( " is active (collecting data) on the following modules. Are you sure you wish to disconnect it? Note that if disconnecting this app results into modules without an active app, we'll activate the Hustle's Local List for those modules.", 'hustle' ); ?>

</script>