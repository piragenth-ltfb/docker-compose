<?php
/**
 * Exit intent trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<p class="sui-description" style="margin: 0 0 30px;">
	<?php /* translators: 1. module type smallcaps and in singular, 2. 'br' and opening 'b' tags, 3. closing 'b' tag */ ?>
	<?php printf( esc_html__( 'Trigger your %1$s when a visitor intent to exit your website. %2$sNote:%3$s This doesn\'t work on mobile and tablet because we use mouse movements to detect the exit intent.', 'hustle' ), esc_html( $smallcaps_singular ), '<br/><strong>', '</strong>' ); ?>
</p>

<?php // SETTINGS: Trigger once per session. ?>
<div class="sui-form-field">

	<?php
	$this->render(
		'admin/global/sui-components/sui-checkbox',
		array(
			'name'        => 'triggers.on_exit_intent_per_session',
			'saved_value' => $triggers['on_exit_intent_per_session'],
			'small'       => true,
			'stacked'     => true,
			'label'       => esc_html__( 'Trigger once per session', 'hustle' ),
		)
	);
	?>

	<?php /* translators: module type smallcaps and in singular */ ?>
	<p class="sui-description" style="margin-top: -5px; margin-left: 26px;"><?php printf( esc_html__( 'Enabling this will trigger the %s only for the first time user tries to leave your website in a session.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

</div>

<hr />

<?php // SETTINGS: Add delay. ?>
<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Delay', 'hustle' ); ?></label>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<input
				type="number"
				min="0"
				value="<?php echo esc_attr( $triggers['on_exit_intent_delayed_time'] ); ?>"
				name="triggers.on_exit_intent_delayed_time"
				class="sui-form-control"
				data-attribute="triggers.on_exit_intent_delayed_time"
			/>

		</div>

		<div class="sui-col-md-6">

			<select name="triggers.on_exit_intent_delayed_unit" class="sui-select" data-attribute="triggers.on_exit_intent_delayed_unit">

				<option value="seconds"
					<?php selected( $triggers['on_exit_intent_delayed_unit'], 'seconds' ); ?>
				>
					<?php esc_html_e( 'seconds', 'hustle' ); ?>
				</option>

				<option value="minutes"
					<?php selected( $triggers['on_exit_intent_delayed_unit'], 'minutes' ); ?>
				>
					<?php esc_html_e( 'minutes', 'hustle' ); ?>
				</option>

				<option value="hours"
					<?php selected( $triggers['on_exit_intent_delayed_unit'], 'hours' ); ?>
				>
					<?php esc_html_e( 'hours', 'hustle' ); ?>
				</option>

			</select>

		</div>

	</div>

</div>