<?php
/**
 * Connected integrtaions list wrapper.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><i class="sui-icon-plug-connected" aria-hidden="true"></i> <?php esc_html_e( 'Connected Apps', 'hustle' ); ?></h2>

	</div>

	<div class="sui-box-body">

		<p><?php esc_html_e( "These are the apps you've connected to using their APIs. To activate any of these to collect emails and other data, go to Integrations section of your popups, embeds or slide-ins.", 'hustle' ); ?></p>


		<div id="hustle-connected-providers-section">
			<div class="hustle-integrations-display"></div>
		</div>

	</div>

</div>