<?php
/**
 * Shortcode section under the "unsubscribe" tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Shortcode', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Use shortcode to display unsubscribe form anywhere you want to.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-with-button sui-with-button-inside">
			<input type="text"
				value='[wd_hustle_unsubscribe id="" ]'
				class="sui-form-control"
				readonly="readonly">
			<button class="sui-button-icon hustle-copy-shortcode-button">
				<span class="sui-icon-copy" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Copy shortcode', 'hustle' ); ?></span>
			</button>
		</div>

		<span class="sui-description">
			<?php esc_html_e( 'By default, the Unsubscribe form works for all the modules. If you want to let visitors unsubscribe from specific modules only, add the comma separated module ids in the id attribute.', 'hustle' ); ?>
		</span>
		<span class="sui-description">
			<?php /* translators: example of where to find the id in the url */ ?>
			<?php printf( esc_html__( 'You can find the module\'s ID in the URL of the module\'s wizard page: %s.', 'hustle' ), '/wp-admin/admin.php?page=hustle_popup&<b>id=58</b>' ); ?>
		</span>

	</div>

</div>