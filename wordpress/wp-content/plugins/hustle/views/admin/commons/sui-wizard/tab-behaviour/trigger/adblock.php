<?php
/**
 * Ad-block trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<?php /* translators: module type smallcaps and in singular */ ?>
<p class="sui-description"><?php printf( esc_html__( 'Your %s will be triggered whenever an Ad-block is detected in your visitor\'s browser.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Delay', 'hustle' ); ?></label>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<input type="number"
				value="<?php echo esc_attr( $triggers['on_adblock_delay'] ); ?>"
				min="0"
				class="sui-form-control"
				name="triggers.on_adblock_delay"
				data-attribute="triggers.on_adblock_delay" />

		</div>

		<div class="sui-col-md-6">

			<select name="triggers.on_adblock_delay_unit" class="sui-select" data-attribute="triggers.on_adblock_delay_unit">

				<option value="seconds"
					<?php selected( $triggers['on_adblock_delay_unit'], 'seconds' ); ?>
				>
					<?php esc_html_e( 'seconds', 'hustle' ); ?>
				</option>

				<option value="minutes"
					<?php selected( $triggers['on_adblock_delay_unit'], 'minutes' ); ?>
				>
					<?php esc_html_e( 'minutes', 'hustle' ); ?>
				</option>

				<option value="hours"
					<?php selected( $triggers['on_adblock_delay_unit'], 'hours' ); ?>
				>
					<?php esc_html_e( 'hours', 'hustle' ); ?>
				</option>

			</select>

		</div>

	</div>

</div>