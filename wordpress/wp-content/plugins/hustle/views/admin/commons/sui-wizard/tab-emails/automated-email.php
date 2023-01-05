<?php
/**
 * Automated email section.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Tinymce editor styles.
ob_start();
require Opt_In::$plugin_path . 'assets/css/sui-editor.min.css';
$editor_css = ob_get_clean();
$editor_css = '<style>' . $editor_css . '</style>';

// Delay tab content.
ob_start();
?>
<div class="sui-row" >

	<div class="sui-col-md-6">
		<input type="number"
			name="auto_email_time"
			data-attribute="auto_email_time"
			value="<?php echo esc_attr( $settings['auto_email_time'] ); ?>"
			placeholder="0"
			min="0"
			class="sui-form-control" />
	</div>

	<div class="sui-col-md-6">
		<select name="auto_email_unit" class="sui-select" data-attribute="auto_email_unit">
			<option value="seconds" <?php selected( $settings['auto_email_unit'], 'seconds' ); ?>><?php esc_html_e( 'seconds', 'hustle' ); ?></option>
			<option value="minutes" <?php selected( $settings['auto_email_unit'], 'minutes' ); ?>><?php esc_html_e( 'minutes', 'hustle' ); ?></option>
			<option value="hours" <?php selected( $settings['auto_email_unit'], 'hours' ); ?>><?php esc_html_e( 'hours', 'hustle' ); ?></option>
			<option value="days" <?php selected( $settings['auto_email_unit'], 'days' ); ?>><?php esc_html_e( 'days', 'hustle' ); ?></option>
		</select>
	</div>

</div>
<?php
$delay_content = ob_get_clean();

// Schedule tab content.
ob_start();
?>
<?php /* translators: 1. opening 'b' tag, 2. closing 'b' tag */ ?>
<label class="sui-description"><?php printf( esc_html__( 'Choose a fixed date and time for your email or select %1$sDatepicker and Timepicker%2$s fields of your form to schedule this email dynamically based on user input.', 'hustle' ), '<b>', '</b>' ); ?></label>

<div class="sui-form-field">

	<label for="hustle-email-day" class="sui-label"><?php esc_html_e( 'Day', 'hustle' ); ?></label>

	<div class="sui-insert-variables">

		<div class="sui-control-with-icon">

			<input type="text"
				name="day"
				value="<?php echo esc_attr( $settings['day'] ); ?>"
				placeholder="Datepicker {date-1}"
				id="hustle-email-day"
				class="sui-form-control"
				data-attribute="day"
			/>

			<span class="sui-icon-calendar" aria-hidden="true"></span>

		</div>

		<select class="sui-variables hustle-field-options" data-type="datepicker"></select>

	</div>

</div>

<div class="sui-form-field">

	<label for="hustle-email-time" class="sui-label"><?php esc_html_e( 'Time of Day', 'hustle' ); ?></label>

	<div class="sui-insert-variables hustle-field">

		<div class="sui-control-with-icon">

			<input type="text"
				name="time"
				value="<?php echo esc_attr( $settings['time'] ); ?>"
				<?php /* translators: timepicker name */ ?>
				placeholder="<?php printf( esc_attr__( 'Timepicker %s', 'hustle' ), '{time-1}' ); ?>"
				id="hustle-email-time"
				class="sui-form-control"
				data-attribute="time"
			/>

			<span class="sui-icon-clock" aria-hidden="true"></span>

		</div>

		<select class="sui-variables hustle-field-options" data-type="timepicker"></select>

	</div>

</div>
<div class="sui-form-field">
	<label for="auto_email_time" class="sui-label"><?php esc_html_e( 'Delay', 'hustle' ); ?></label>

	<div class="sui-row" >
		<div class="sui-col-md-6">
			<input type="number"
				name="schedule_auto_email_time"
				data-attribute="schedule_auto_email_time"
				value="<?php echo esc_attr( $settings['schedule_auto_email_time'] ); ?>"
				placeholder="0"
				class="sui-form-control" />
		</div>

		<div class="sui-col-md-6">
			<select name="schedule_auto_email_unit" class="sui-select" data-attribute="schedule_auto_email_unit">
				<option value="seconds" <?php selected( $settings['schedule_auto_email_unit'], 'seconds' ); ?>><?php esc_html_e( 'seconds', 'hustle' ); ?></option>
				<option value="minutes" <?php selected( $settings['schedule_auto_email_unit'], 'minutes' ); ?>><?php esc_html_e( 'minutes', 'hustle' ); ?></option>
				<option value="hours" <?php selected( $settings['schedule_auto_email_unit'], 'hours' ); ?>><?php esc_html_e( 'hours', 'hustle' ); ?></option>
				<option value="days" <?php selected( $settings['schedule_auto_email_unit'], 'days' ); ?>><?php esc_html_e( 'days', 'hustle' ); ?></option>
			</select>
		</div>

	</div>
</div>
<?php
$schedule_content = ob_get_clean();

$options = array(
	'instant'  => array(
		'value' => 'instant',
		'label' => esc_html__( 'Instant', 'hustle' ),
	),
	'delay'    => array(
		'value'   => 'delay',
		'label'   => esc_html__( 'Delay', 'hustle' ),
		'boxed'   => true,
		'content' => $delay_content,
	),
	'schedule' => array(
		'value'   => 'schedule',
		'label'   => esc_html__( 'Schedule', 'hustle' ),
		'boxed'   => true,
		'content' => $schedule_content,
	),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php esc_html_e( 'Automated Email', 'hustle' ); ?></span>

		<span class="sui-description"><?php esc_html_e( "Send an automated email to the subscribers after they've subscribed.", 'hustle' ); ?></span>

	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-automated-email" class="sui-toggle hustle-toggle-with-container" data-toggle-on="automated-email">
				<input type="checkbox"
					name="automated_email"
					data-attribute="automated_email"
					id="hustle-automated-email"
					aria-labelledby="hustle-automated-email-label"
					<?php checked( $settings['automated_email'], '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-automated-email-label" class="sui-toggle-label"><?php esc_html_e( 'Send an automated email to the user', 'hustle' ); ?></span>
			</label>

			<div class="sui-border-frame sui-toggle-content" data-toggle-content="automated-email">

					<div class="sui-form-field">

						<label class="sui-label"><?php esc_html_e( 'Email time', 'hustle' ); ?></label>

						<?php
						$this->render(
							'admin/global/sui-components/sui-tabs',
							array(
								'name'        => 'email_time',
								'radio'       => true,
								'saved_value' => $settings['email_time'],
								'sidetabs'    => true,
								'content'     => true,
								'options'     => $options,
							)
						);
						?>

					</div>

					<div class="sui-form-field">

						<label for="hustle-email-recipient" class="sui-label"><?php esc_html_e( 'Recipient', 'hustle' ); ?></label>

						<div class="sui-insert-variables">

							<input type="text"
								name="recipient"
								value="<?php echo esc_attr( $settings['recipient'] ); ?>"
								placeholder="Email {email-1}"
								id="hustle-email-recipient"
								class="sui-form-control"
								data-attribute="recipient"
							/>

							<select class="sui-variables" data-type="email"></select>

						</div>

					</div>

					<div class="sui-form-field">

						<label for="hustle-email-copy-subject" class="sui-label"><?php esc_html_e( 'Subject', 'hustle' ); ?></label>

						<input type="text"
							placeholder="<?php esc_html_e( 'Email copy subject', 'hustle' ); ?>"
							name="email_subject"
							data-attribute="email_subject"
							value="<?php echo esc_attr( $settings['email_subject'] ); ?>"
							id="hustle-email-copy-subject"
							class="sui-form-control" />

					</div>

					<div class="sui-form-field">

						<label class="sui-label sui-label-editor"><?php esc_html_e( 'Email body', 'hustle' ); ?></label>

						<?php
						wp_editor(
							$settings['email_body'],
							'email_body',
							array(
								'media_buttons'    => false,
								'textarea_name'    => 'email_body',
								'editor_css'       => $editor_css,
								'tinymce'          => array(
									'content_css' => self::$plugin_url . 'assets/css/sui-editor.min.css',
								),
								'editor_height'    => 192,
								'drag_drop_upload' => false,
							)
						);
						?>

					</div>

			</div>

		</div>

	</div>

</div>