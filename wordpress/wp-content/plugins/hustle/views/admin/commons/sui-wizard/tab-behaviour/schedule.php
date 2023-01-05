<?php
/**
 * Schedule section.
 *
 * @package Hustle
 * @since 4.2.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Schedule', 'hustle' ); ?></span>
		<span class="sui-description"><?php printf( esc_html__( "By default, your %1\$s starts appearing as soon as it's published. However, you can schedule your %1\$s for better targeting.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<p class="sui-settings-label"><?php esc_html_e( 'Set schedule', 'hustle' ); ?></p>

		<?php /* translators: %s: module type name */ ?>
		<p class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( 'Schedule your %s to start and stop displaying it on a specific date/time automatically. Additionally, you can choose whether it shows every day or on particular weekdays, and during what time of day.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

		<div id="hustle-schedule-row"></div>

	</div>

</div>