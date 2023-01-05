<?php
/**
 * Click trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<?php /* translators: module type smallcaps and in singular */ ?>
<p class="sui-description" style="margin-bottom: 20px;"><?php printf( esc_html__( 'By default, the %1$s appears as soon as the page finishes loading. However, you can delay triggering your %1$s below.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

<label class="sui-label"><?php esc_html_e( 'Delay', 'hustle' ); ?></label>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<input
				type="number"
				min="0"
				name="triggers.on_time_delay"
				value="<?php echo esc_attr( $triggers['on_time_delay'] ); ?>"
				class="sui-form-control"
				data-attribute="triggers.on_time_delay"
			/>

		</div>

		<div class="sui-col-md-6">

			<select name="triggers.on_time_unit" class="sui-select" data-attribute="triggers.on_time_unit">

				<option value="seconds"
					<?php selected( $triggers['on_time_unit'], 'seconds' ); ?>
				>
					<?php esc_html_e( 'seconds', 'hustle' ); ?>
				</option>

				<option value="minutes"
					<?php selected( $triggers['on_time_unit'], 'minutes' ); ?>
				>
					<?php esc_html_e( 'minutes', 'hustle' ); ?>
				</option>

				<option value="hours"
					<?php selected( $triggers['on_time_unit'], 'hours' ); ?>
				>
					<?php esc_html_e( 'hours', 'hustle' ); ?>
				</option>

			</select>

		</div>

	</div>