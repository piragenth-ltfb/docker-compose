<?php
/**
 * Scroll selector settings.
 *
 * @package Hustle
 * @since 4.4.1
 */

?>

<div class="sui-form-field">

	<label for="hustle-trigger-scroll--selector-name" class="sui-label"><?php esc_html_e( 'Element’s CSS selector', 'hustle' ); ?></label>

	<input
		type="text"
		name="trigger_on_scroll_css_selector"
		value="<?php echo esc_attr( $triggers['on_scroll_css_selector'] ); ?>"
		placeholder="<?php esc_html_e( 'E.g. .element-class or #element-id', 'hustle' ); ?>"
		id="hustle-trigger-scroll--selector-name"
		class="sui-form-control"
		data-attribute="triggers.on_scroll_css_selector"
	/>

	<span class="sui-description"><?php esc_html_e( 'Enter the class starting with a “.” and id with a “#”.', 'hustle' ); ?></span>

</div>