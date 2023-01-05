<?php
/**
 * Modal for adding/editing the module's schedule.
 *
 * @package Hustle
 * @since 4.0.0
 */

$smallcaps_singular  = Opt_In_Utils::get_module_type_display_name( $module_type );
$capitalize_singular = Opt_In_Utils::get_module_type_display_name( $module_type, false, true );

$wp_gmt_min    = 60 * get_option( 'gmt_offset' );
$wp_gmt_sign   = $wp_gmt_min < 0 ? '-' : '+';
$wp_gmt_absmin = abs( $wp_gmt_min );
$wp_gmt_offset = sprintf( '%s%02d:%02d', $wp_gmt_sign, $wp_gmt_absmin / 60, $wp_gmt_absmin % 60 );

$hour_options = array();
for ( $h = 1; $h < 13; $h++ ) {
	$ho                  = sprintf( '%02d', $h );
	$hour_options[ $ho ] = $ho;
}

$minute_options = array();
for ( $h = 0; $h < 60; $h++ ) {
	$mi                    = sprintf( '%02d', $h );
	$minute_options[ $mi ] = $mi;
}

$meridiem_options = array(
	'am' => __( 'AM', 'hustle' ),
	'pm' => __( 'PM', 'hustle' ),
);
?>

<div class="sui-modal sui-modal-lg">

	<div
		role="dialog"
		id="hustle-dialog--add-schedule"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog--add-schedule-title"
		aria-describedby="hustle-dialog--add-schedule-description"
	>

		<div role="document" id="hustle-schedule-dialog-content" class="sui-box"></div>

	</div>

</div>

<script type="text/template" id="hustle-schedule-dialog-content-tpl">

	<!-- Close button for screenreader only -->
	<button class="sui-screen-reader-text hustle-schedule-cancel"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></button>

	<!-- Dialog description for screenreader only -->
	<p id="hustle-dialog--add-schedule-description" class="sui-screen-reader-text"><?php esc_html_e( 'Use the form in this dialog window to schedule when your the module you are creating is going to start and stop showing automatically.', 'hustle' ); ?></p>

	<div class="sui-box-header">

		<h3 id="hustle-dialog--add-schedule-title" class="sui-box-title"><?php esc_html_e( 'Set Schedule', 'hustle' ); ?></h3>

		<div class="sui-actions-right">

			<button class="sui-button-icon hustle-schedule-cancel">
				<span class="sui-icon-close sui-md" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
			</button>

		</div>

	</div>

	<form id="hustle-edit-schedule-form" class="sui-box-body">

		<!-- ROW: Schedule Between -->
		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-2">

				<h4 class="sui-settings-label"><?php esc_html_e( 'Schedule Between', 'hustle' ); ?></h4>

				<?php /* translators: module type in small caps and in singular */ ?>
				<p class="sui-description"><?php printf( esc_html__( 'Choose when should your %s start and stop displaying to your visitors.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

				<div class="sui-form-field">

					<div class="sui-row" style="margin-bottom: 10px;">

						<div class="sui-col-md-6">

							<label class="sui-label"><?php esc_html_e( 'Start Date', 'hustle' ); ?></label>

							<div class="sui-date">

								<span class="sui-icon-calendar" aria-hidden="true"></span>

								<?php
								$this->get_html_for_options(
									array(
										array(
											'is_template' => true,
											'type'        => 'text',
											'name'        => 'start_date',
											'class'       => 'hustle-datepicker-field',
											'attributes'  => array(
												'data-checkbox-content' => 'not-schedule-start',
											),
										),
									)
								);
								?>

							</div>

						</div>

						<div class="sui-col-md-6">

							<label class="sui-label"><?php esc_html_e( 'Start Time', 'hustle' ); ?></label>

							<div class="hui-select-time">

								<?php
								$this->get_html_for_options(
									array(
										array(
											'type'        => 'select',
											'is_template' => true,
											'name'        => 'start_hour',
											'options'     => $hour_options,
											'class'       => 'sui-select sui-select-inline',
											'attributes'  => array(
												'data-search' => 'true',
												'data-checkbox-content' => 'not-schedule-start',
											),
										),
										array(
											'type'        => 'select',
											'is_template' => true,
											'name'        => 'start_minute',
											'options'     => $minute_options,
											'class'       => 'sui-select sui-select-inline',
											'attributes'  => array(
												'data-search' => 'true',
												'data-checkbox-content' => 'not-schedule-start',
											),
										),
										array(
											'type'        => 'select',
											'is_template' => true,
											'name'        => 'start_meridiem_offset',
											'options'     => $meridiem_options,
											'class'       => 'sui-select sui-select-inline',
											'attributes'  => array(
												'data-checkbox-content' => 'not-schedule-start',
											),
										),
									)
								);
								?>

							</div>

						</div>

					</div>

					<?php
					$this->get_html_for_options(
						array(
							array(
								'is_template' => true,
								'type'        => 'checkbox',
								'name'        => 'not_schedule_start',
								'class'       => 'sui-checkbox-sm sui-checkbox-stacked hustle-checkbox-with-dependencies',
								'value'       => '1',
								'label'       => __( 'Start immediately after publishing', 'hustle' ),
								'attributes'  => array(
									'data-disable-on' => 'not-schedule-start',
								),
							),
						)
					);
					?>

				</div>

				<div class="sui-form-field">

					<div class="sui-row" style="margin-bottom: 10px;">

						<div class="sui-col-md-6">

							<label class="sui-label"><?php esc_html_e( 'End Date', 'hustle' ); ?></label>

							<div class="sui-date">

								<span class="sui-icon-calendar" aria-hidden="true"></span>

								<?php
								$this->get_html_for_options(
									array(
										array(
											'is_template' => true,
											'type'        => 'text',
											'name'        => 'end_date',
											'class'       => 'hustle-datepicker-field',
											'attributes'  => array(
												'data-checkbox-content' => 'not-schedule-end',
											),
										),
									)
								);
								?>

							</div>

						</div>

						<div class="sui-col-md-6">

							<label class="sui-label"><?php esc_html_e( 'End Time', 'hustle' ); ?></label>

							<div class="hui-select-time">

								<?php
								$this->get_html_for_options(
									array(
										array(
											'type'        => 'select',
											'is_template' => true,
											'name'        => 'end_hour',
											'options'     => $hour_options,
											'class'       => 'sui-select sui-select-inline',
											'attributes'  => array(
												'data-checkbox-content' => 'not-schedule-end',
											),
										),
										array(
											'type'        => 'select',
											'is_template' => true,
											'name'        => 'end_minute',
											'options'     => $minute_options,
											'class'       => 'sui-select sui-select-inline',
											'attributes'  => array(
												'data-checkbox-content' => 'not-schedule-end',
											),
										),
										array(
											'type'        => 'select',
											'is_template' => true,
											'name'        => 'end_meridiem_offset',
											'options'     => $meridiem_options,
											'class'       => 'sui-select sui-select-inline',
											'attributes'  => array(
												'data-checkbox-content' => 'not-schedule-end',
											),
										),
									)
								);
								?>

							</div>

						</div>

					</div>

					<?php
					$this->get_html_for_options(
						array(
							array(
								'is_template' => true,
								'type'        => 'checkbox',
								'name'        => 'not_schedule_end',
								'class'       => 'sui-checkbox-sm hustle-checkbox-with-dependencies',
								'value'       => '1',
								'label'       => __( 'Never end the schedule', 'hustle' ),
								'attributes'  => array(
									'data-disable-on' => 'not-schedule-end',
								),
							),
						)
					);
					?>

				</div>

			</div>

		</div>

		<!-- ROW: Active On -->
		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-2">

				<h4 class="sui-settings-label"><?php esc_html_e( 'Active On', 'hustle' ); ?></h4>

				<p class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose whether your pop-up should show up every day or on selected weekdays within your scheduled date/time above, and during what time of day it should appear.', 'hustle' ); ?></p>

				<div class="sui-tabs sui-side-tabs">

					<input
						type="radio"
						value="all"
						name="active_days"
						id="input-schedule-everyday"
						class="sui-screen-reader-text"
						aria-hidden="true"
						<?php checked( $settings['active_days'], 'all' ); ?>
					/>

					<input
						type="radio"
						name="active_days"
						value="week_days"
						id="input-schedule-somedays"
						data-tab-menu="week-days"
						class="sui-screen-reader-text"
						aria-hidden="true"
						<?php checked( $settings['active_days'], 'week_days' ); ?>
					/>

					<div role="tablist" class="sui-tabs-menu">

						<button
							type="button"
							role="tab"
							id="tab-schedule-everyday"
							class="sui-tab-item<?php echo 'all' === $settings['active_days'] ? ' active' : ''; ?>"
							aria-selected="true"
						>
							<?php esc_html_e( 'Every day', 'hustle' ); ?>
						</button>

						<button
							type="button"
							role="tab"
							id="tab-schedule-somedays"
							class="sui-tab-item<?php echo 'all' !== $settings['active_days'] ? ' active' : ''; ?>"
							aria-controls="tab-content-schedule-somedays"
							aria-selected="false"
							tabindex="-1"
						>
							<?php esc_html_e( 'Selected weekdays', 'hustle' ); ?>
						</button>

					</div>

					<div class="sui-tabs-content">

						<div
							role="tabpanel"
							tabindex="0"
							id="tab-content-schedule-somedays"
							class="sui-tab-content sui-tab-boxed<?php echo 'all' !== $settings['active_days'] ? ' active' : ''; ?>"
							aria-labelledby="tab-schedule-somedays"
							hidden
						>

							<div class="sui-form-field">

								<?php
								$this->get_html_for_options(
									array(
										array(
											'is_template' => true,
											'type'        => 'checkboxes',
											'name'        => 'week_days',
											'class'       => 'sui-checkbox-sm sui-checkbox-stacked',
											'options'     => Hustle_Time_Helper::get_week_days( 'full' ),
										),
									)
								);
								?>

							</div>

						</div>

					</div>

				</div>

				<?php
				$this->get_html_for_options(
					array(
						array(
							'is_template' => true,
							'id'          => 'hustle-schedule-active-on-all-day',
							'type'        => 'checkbox',
							'name'        => 'is_active_all_day',
							'class'       => 'sui-checkbox-sm sui-checkbox-stacked hustle-checkbox-with-dependencies',
							'value'       => '1',
							'label'       => '<span>' . esc_html__( 'All day', 'hustle' ) . '</span><span class="sui-tooltip sui-tooltip-right sui-tooltip-top-left-mobile" data-tooltip="' . esc_html__( 'Show your pop-up for 24hrs on each scheduled day', 'hustle' ) . '"><span class="sui-icon-info sui-sm" aria-hidden="true"></span></span>',
							'attributes'  => array(
								'data-hide-on' => 'is-active-all-day',
							),
						),
					)
				);
				?>

				<div data-checkbox-content="is-active-all-day">

					<div class="sui-form-field">

						<label class="sui-label"><?php esc_html_e( 'From', 'hustle' ); ?></label>

						<div class="hui-select-time">

							<?php
							$this->get_html_for_options(
								array(
									array(
										'type'        => 'select',
										'is_template' => true,
										'name'        => 'day_start_hour',
										'options'     => $hour_options,
									),
									array(
										'type'        => 'select',
										'is_template' => true,
										'name'        => 'day_start_minute',
										'options'     => $minute_options,
									),
									array(
										'type'        => 'select',
										'is_template' => true,
										'name'        => 'day_start_meridiem_offset',
										'options'     => $meridiem_options,
									),
								)
							);
							?>

						</div>

					</div>

					<div class="sui-form-field">

						<label class="sui-label"><?php esc_html_e( 'To', 'hustle' ); ?></label>

						<div class="hui-select-time">

							<?php
							$this->get_html_for_options(
								array(
									array(
										'type'        => 'select',
										'is_template' => true,
										'name'        => 'day_end_hour',
										'options'     => $hour_options,
									),
									array(
										'type'        => 'select',
										'is_template' => true,
										'name'        => 'day_end_minute',
										'options'     => $minute_options,
									),
									array(
										'type'        => 'select',
										'is_template' => true,
										'name'        => 'day_end_meridiem_offset',
										'options'     => $meridiem_options,
									),
								)
							);
							?>

						</div>

					</div>

				</div>

			</div>

		</div>

		<!-- ROW: Schedule Timezone -->
		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-2">

				<h4 class="sui-settings-label"><?php esc_html_e( 'Schedule Timezone', 'hustle' ); ?></h4>

				<?php /* translators: module type in small caps and in singular */ ?>
				<p class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( "You can schedule your %s based on your server's timezone or a custom timezone.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

				<div class="sui-tabs sui-side-tabs">

					<input
						type="radio"
						name="time_to_use"
						value="server"
						id="input-timezone-server"
						class="sui-screen-reader-text"
						aria-hidden="true"
						data-tab-menu="server-timezone"
						<?php checked( $settings['time_to_use'], 'server' ); ?>
					/>

					<input
						type="radio"
						name="time_to_use"
						value="custom"
						id="input-timezone-custom"
						class="sui-screen-reader-text"
						aria-hidden="true"
						data-tab-menu="custom-timezone"
						<?php checked( $settings['time_to_use'], 'custom' ); ?>
					/>

					<div role="tablist" class="sui-tabs-menu">

						<button
							type="button"
							role="tab"
							id="tab-timezone-server"
							class="sui-tab-item<?php echo 'server' === $settings['time_to_use'] ? ' active' : ''; ?>"
							aria-controls="tab-content-timezone-server"
							aria-selected="true"
						>
							<?php esc_html_e( 'Server', 'hustle' ); ?>
						</button>

						<button
							type="button"
							role="tab"
							id="tab-timezone-custom"
							class="sui-tab-item<?php echo 'server' !== $settings['time_to_use'] ? ' active' : ''; ?>"
							aria-controls="tab-content-timezone-custom"
							aria-selected="false"
							tabindex="-1"
						>
							<?php esc_html_e( 'Custom', 'hustle' ); ?>
						</button>

					</div>

					<div class="sui-tabs-content">

						<div
							role="tabpanel"
							tabindex="0"
							id="tab-content-timezone-server"
							class="sui-tab-content sui-tab-boxed<?php echo 'server' === $settings['time_to_use'] ? ' active' : ''; ?>"
							aria-labelledby="tab-timezone-server"
						>

							<label class="sui-label" style="margin-bottom: 5px;"><?php esc_html_e( 'Timezone', 'hustle' ); ?></label>

							<?php
							$timezone = str_replace( '_', ' ', get_option( 'timezone_string' ) );

							$notice_message = sprintf(
								esc_html__( "Your server's timezone is %1\$s(GMT %3\$s) %4\$s%2\$s and the current time on your server is %1\$s%5\$s%2\$s. ", 'hustle' ),
								'<strong>',
								'</strong>',
								esc_html( $wp_gmt_offset ),
								esc_html( $timezone ),
								'{{ serverCurrentTime }}'
							);
							$notice_options = array(
								array(
									'type'       => 'inline_notice',
									'class'      => 'sui-notice-info',
									'icon'       => 'info',
									'value'      => $notice_message,
									'attributes' => array(
										'style' => 'margin-top: 5px;',
									),
								),
							);
							$this->get_html_for_options( $notice_options );
							?>

						</div>

						<div
							role="tabpanel"
							tabindex="0"
							id="tab-content-timezone-custom"
							class="sui-tab-content sui-tab-boxed<?php echo 'server' !== $settings['time_to_use'] ? ' active' : ''; ?>"
							aria-labelledby="tab-timezone-custom"
							hidden
						>

							<div class="sui-form-field">

								<label class="sui-label"><?php esc_html_e( 'Choose Timezone', 'hustle' ); ?></label>

								<select name="custom_timezone" id="hustle-select-custom_timezone" class="sui-select">
									<?php echo wp_timezone_choice( $settings['custom_timezone'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</select>

								<?php /* translators: time in the selected timezone */ ?>
								<span class="sui-description"><?php printf( esc_html__( 'Current time in the selected timezone is %s.', 'hustle' ), '<strong style="color: #666;"><span id="hustle-custom-timezone-current-time">{{ customCurrentTime }}</span></strong>' ); ?></span>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</form>

	<div class="sui-box-footer sui-content-separated">

		<# if ( '1' === is_schedule ) { #>

			<button
				class="sui-button sui-button-red sui-button-ghost hustle-schedule-delete"
				<?php /* translators: module type capitalized and in singular */ ?>
				data-title="<?php printf( esc_html__( 'Delete %s Schedule', 'hustle' ), esc_html( $capitalize_singular ) ); ?>"
				<?php /* translators: module type in small caps and in singular */ ?>
				data-description="<?php printf( esc_html__( 'Are you sure you wish to delete the %s schedule?', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>"
			>
				<span class="sui-icon-trash" aria-hidden="true"></span>
				<?php esc_html_e( 'Delete', 'hustle' ); ?>
			</button>

			<button id="hustle-schedule-save" class="sui-button">
				<span class="sui-loading-text">
					<span class="sui-icon-save" aria-hidden="true"></span>
					<?php esc_html_e( 'Save Schedule', 'hustle' ); ?>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		<# } else { #>

			<button class="sui-button sui-button-ghost hustle-schedule-cancel"><?php esc_html_e( 'Cancel', 'hustle' ); ?></button>

			<button id="hustle-schedule-save" class="sui-button"><span class="sui-icon-clock" aria-hidden="true"></span> <?php esc_html_e( 'Schedule', 'hustle' ); ?></button>

		<# } #>

	</div>

</script>

<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="hustle-dialog--delete-schedule"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog--delete-schedule-title"
		aria-describedby="hustle-dialog--delete-schedule-description"
	>

		<div role="document" class="sui-box">

			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

				<button class="sui-button-icon sui-button-float--right" data-modal-close="">
					<span class="sui-icon-close sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog.', 'hustle' ); ?></span>
				</button>

				<h3 id="hustle-dialog--delete-schedule-title" class="sui-box-title sui-lg"></h3>

				<p id="hustle-dialog--delete-schedule-description" class="sui-description"></p>

			</div>

			<form id="hustle-delete-schedule-dialog-content" class="sui-box-footer sui-flatten sui-content-center">

				<button type="button" class="sui-button sui-button-ghost" data-modal-close=""><?php esc_attr_e( 'Cancel', 'hustle' ); ?></button>

			</form>

		</div>

	</div>

</div>

<script type="text/template" id="hustle-delete-schedule-dialog-content-tpl">

	<button
		class="sui-button sui-button-ghost sui-button-red hustle-delete-confirm {{ 'undefined' === typeof actionClass ? 'hustle-single-module-button-action' : actionClass }}"
		data-hustle-action="{{ 'undefined' === typeof action ?  'delete' : action }}"
		data-form-id="hustle-delete-form"
	>

		<span class="sui-loading-text">
			<span class="sui-icon-trash" aria-hidden="true"></span> <?php esc_attr_e( 'Delete', 'hustle' ); ?>
		</span>

		<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>

	</button>

	<# if ( 'undefined' !== typeof action ) { #>
		<input type="hidden" name="hustle_action" value="{{ action }}" />
	<# } #>

	<# if ( 'undefined' !== typeof id ) { #>
		<input type="hidden" name="id" value="{{ id }}" />
		<input type="hidden" name="moduleId" value="{{ id }}" />
	<# } #>

	<# if ( 'undefined' !== typeof nonce ) { #>
		<input type="hidden" id="hustle_nonce" name="hustle_nonce" value="{{ nonce }}" />
	<# } #>

</script>