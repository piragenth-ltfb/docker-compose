<?php
/**
 * Counter section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Counter', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Display the number of clicks or shares on the social platforms.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">
			<label for="hustle-settings--counter-enable" class="sui-toggle hustle-toggle-with-container" data-toggle-on="counter-enabled">
				<input
					type="checkbox"
					name="counter_enabled"
					data-attribute="counter_enabled"
					id="hustle-settings--counter-enable"
					aria-labelledby="hustle-settings--counter-enable-label"
					aria-describedby="hustle-settings--counter-enable-description"
					<?php checked( $counter_enabled, '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-settings--counter-enable-label" class="sui-toggle-label"><?php esc_html_e( 'Enable counter', 'hustle' ); ?></span>

				<span id="hustle-settings--counter-enable-description" class="sui-description"><?php esc_html_e( "You can either show the number of times a social icon has been clicked or retrieve the number of shares from each network's API when available.", 'hustle' ); ?></span>
			</label>
		</div>

	</div>

</div>