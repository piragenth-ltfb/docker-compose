<?php
/**
 * Closing behavior section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Closing Behavior', 'hustle' ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Choose how your %1$s will behave after a visitor closes it. You can keep showing the %1$s or hide it for a set amount of time before it starts to reappear.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php // SETTINGS: Closed by. ?>
		<div class="sui-form-field">

			<label class="sui-settings-label"><?php esc_html_e( 'Closed by', 'hustle' ); ?></label>
			<span class="sui-description"><?php esc_html_e( 'Choose the methods of closing for which the following behavior should apply.', 'hustle' ); ?></span>

			<div style="margin-top: 10px;">

				<label id="hustle-closing-behaviour--icon-label" for="hustle-closing-behaviour--icon" class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked">
					<input type="checkbox"
						value="click_close_icon"
						id="hustle-closing-behaviour--icon"
						name="after_close_trigger"
						data-attribute="after_close_trigger"
						<?php checked( in_array( 'click_close_icon', $settings['after_close_trigger'], true ) ); ?> />
					<span aria-hidden="true"></span>
					<?php /* translators: module type capitalized and in singular */ ?>
					<span><?php printf( esc_html__( '%s closed by the visitor by clicking on “x” icon', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>
				</label>

				<label for="hustle-closing-behaviour--timer" class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked" data-toggle-content="auto-hide">
					<input type="checkbox"
						value="auto_hide"
						id="hustle-closing-behaviour--timer"
						name="after_close_trigger"
						data-attribute="after_close_trigger"
						<?php checked( in_array( 'auto_hide', $settings['after_close_trigger'], true ) ); ?> />
					<span aria-hidden="true"></span>
					<span><?php esc_html_e( 'Auto closed based on the auto close timer', 'hustle' ); ?></span>
				</label>

				<?php if ( Hustle_Module_Model::POPUP_MODULE === $module_type ) : ?>

					<label for="hustle-closing-behaviour--mask" class="sui-checkbox sui-checkbox-sm sui-checkbox-stacked" data-toggle-content="close-on-background-click">
						<input type="checkbox"
							value="click_outside"
							id="hustle-closing-behaviour--mask"
							name="after_close_trigger"
							data-attribute="after_close_trigger"
							<?php checked( in_array( 'click_outside', $settings['after_close_trigger'], true ) ); ?> />
						<span aria-hidden="true"></span>
						<?php /* translators: 1. module type capitalized and in singular */ ?>
						<span><?php printf( esc_html__( '%1$s closed by clicking outside of the %1$s', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>
					</label>

				<?php endif; ?>

			</div>

		</div>

		<?php // SETTINGS: Behavior. ?>
		<div class="sui-form-field">

			<label class="sui-settings-label"><?php esc_html_e( 'Behavior', 'hustle' ); ?></label>
			<?php /* translators: module type in small caps and in singular */ ?>
			<span class="sui-description"><?php printf( esc_html__( 'Choose what will happen when a visitor closes your %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

			<div style="margin: 10px 0;">

				<select name="after_close" data-attribute="after_close" class="sui-select hustle-select-with-container" data-content-on="no_show_on_post,no_show_all">

					<option value="no_show_on_post"
						<?php selected( $settings['after_close'], 'no_show_on_post' ); ?>>
						<?php /* translators: module type in small caps and in singular */ ?>
						<?php printf( esc_html__( 'Do not show this %s on this post / page', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
					</option>

					<option value="no_show_all"
						<?php selected( $settings['after_close'], 'no_show_all' ); ?>>
						<?php /* translators: module type in small caps and in singular */ ?>
						<?php printf( esc_html__( 'Do not show this %s across the site', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
					</option>

					<option value="keep_show"
						<?php selected( $settings['after_close'], 'keep_show' ); ?>>
						<?php /* translators: module type in small caps and in singular */ ?>
						<?php printf( esc_html__( 'Keep showing this %s', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
					</option>

				</select>

			</div>

			<div class="sui-border-frame" style="margin-bottom: 5px;" data-field-content="after_close">

				<label class="sui-label"><?php esc_html_e( 'Reset this after', 'hustle' ); ?></label>

				<div class="sui-row">

					<div class="sui-col-md-6">

						<input type="number"
							value="<?php echo esc_attr( $settings['expiration'] ); ?>"
							min="0"
							class="sui-form-control"
							data-attribute="expiration" />

					</div>

					<div class="sui-col-md-6">

						<select data-attribute="expiration_unit" >

							<option value="seconds"
								<?php selected( $settings['expiration_unit'], 'seconds' ); ?>>
								<?php esc_html_e( 'second(s)', 'hustle' ); ?>
							</option>

							<option value="minutes"
								<?php selected( $settings['expiration_unit'], 'minutes' ); ?>>
								<?php esc_html_e( 'minute(s)', 'hustle' ); ?>
							</option>

							<option value="hours"
								<?php selected( $settings['expiration_unit'], 'hours' ); ?>>
								<?php esc_html_e( 'hour(s)', 'hustle' ); ?>
							</option>

							<option value="days"
								<?php selected( $settings['expiration_unit'], 'days' ); ?>>
								<?php esc_html_e( 'day(s)', 'hustle' ); ?>
							</option>

							<option value="weeks"
								<?php selected( $settings['expiration_unit'], 'weeks' ); ?>>
								<?php esc_html_e( 'week(s)', 'hustle' ); ?>
							</option>

							<option value="months"
								<?php selected( $settings['expiration_unit'], 'months' ); ?>>
								<?php esc_html_e( 'month(s)', 'hustle' ); ?>
							</option>

						</select>

					</div>

					<div class="sui-col-md-12">
						<?php /* translators: module type capitalized and in singular */ ?>
						<span class="sui-description"><?php printf( esc_html__( '%s will be visible again after this much time has passed since it was closed.', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>