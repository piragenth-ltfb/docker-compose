<?php
/**
 * Modal for editing global and per-module integrations.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<script type="text/template" id="hustle-integration-dialog-tpl">

	<div class="sui-modal sui-modal-sm">

		<div
			class="sui-modal-content"
			role="dialog"
			id="hustle-integration-dialog"
			aria-labelledby="dialogTitle"
			aria-describedby="dialogDescription"
		>

			<div class="sui-box">

			<!-- content -->

				<form style="margin: 0;">

					<div class="sui-box-header sui-flatten sui-content-center sui-spacing-bottom--0 sui-spacing-top--60">

						<button type="button" class="sui-button-icon sui-button-float--right hustle-modal-close">
							<span class="sui-icon-close sui-md" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
						</button>

						<figure class="sui-box-logo" aria-hidden="true">
							<img
								src="{{ image }}"
								alt="{{ title }}"
							/>
						</figure>

						<div class="sui-box-content integration-header"></div>

					</div>

					<div class="sui-box-body"></div>

				</form>

				<div class="sui-box-footer sui-flatten sui-spacing-top--30"></div>

			<!-- /content -->

			</div>

		</div>

	</div>

</script>

<script type="text/template" id="hustle-dialog-loader-tpl">

	<p class="fui-loading-dialog" aria-label="Loading content">

		<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>

	</p>

</script>