<?php
/**
 * Dialog for editing form fields under the "Emails" tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();
?>

<div class="sui-tabs sui-tabs-flushed">

	<div data-tabs>

		<div id="hustle-data-tab--labels" class="hustle-data-pane"><?php esc_html_e( 'Labels', 'hustle' ); ?></div>
		<div id="hustle-data-tab--settings" class="hustle-data-pane"><?php esc_html_e( 'Settings', 'hustle' ); ?></div>
		<div id="hustle-data-tab--styling" class="hustle-data-pane"><?php esc_html_e( 'Styling', 'hustle' ); ?></div>

	</div>

	<div id="field-settings-container" data-panes>

		<?php // TAB: Labels. ?>
		<div id="hustle-data-pane--labels"></div>

		<?php // TAB: Settings. ?>
		<div id="hustle-data-pane--settings"></div>

		<?php // TAB: Styling. ?>
		<div id="hustle-data-pane--styling"></div>

	</div>

</div>

<?php
$body_content = ob_get_clean();

$attributes = array(
	'modal_id'        => 'edit-field',
	'has_description' => false,
	'modal_size'      => 'lg',

	'header'          => array(
		'title' => __( 'Edit Field', 'hustle' ),
	),
	'body'            => array(
		'content' => $body_content,
	),
	'footer'          => array(
		'classes' => 'sui-content-separated',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost hustle-modal-close',
				'text'     => __( 'Discard Changes', 'hustle' ),
				'icon'     => 'undo',
				'is_close' => true,
			),
			array(
				'id'       => 'hustle-apply-changes',
				'text'     => __( 'Apply', 'hustle' ),
				'icon'     => 'check',
				'has_load' => true,
				'is_close' => true,
			),
		),
	),
);

$this->render_modal( $attributes );
?>

<script id="hustle-common-field-labels-tpl" type="text/template">

	<?php // TAB: Labels. ?>
	<div class="sui-row">

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Label', 'hustle' ); ?></label>

				<input type="text"
					name="label"
					value="{{ label }}"
					placeholder="{{ label_placeholder }}"
					class="sui-form-control" />

			</div>

		</div>

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Name', 'hustle' ); ?></label>

				<input type="text"
					name="name"
					value="{{ name }}"
					{{ ( 'email' === type && 'email' === name ) ? ' readonly="readonly"' : '' }}
					placeholder="{{ name_placeholder }}"
					class="sui-form-control" />

				<span class="sui-description">
					<?php esc_html_e( 'Do not use any spaces in the name to ensure this field is submitted successfully.', 'hustle' ); ?>
				</span>

			</div>

		</div>

	</div>

	<div class="sui-form-field">

		<label class="sui-label"><?php esc_html_e( 'Placeholder (optional)', 'hustle' ); ?></label>

		<input type="text"
			name="placeholder"
			value="{{ placeholder }}"
			placeholder="{{ placeholder_placeholder }}"
			class="sui-form-control" />

	</div>

</script>

<script id="hustle-common-field-settings-tpl" type="text/template">

	<?php // TAB: Settings. ?>
	<div class="sui-box-settings-row">
		<div class="sui-form-field" style="width: 100%">

			<label class="sui-settings-label"><?php esc_html_e( 'Require', 'hustle' ); ?></label>

			<# if ( 'email' === type && 'email' === name ) { #>

				<span class="sui-description"><?php esc_html_e( 'The default email field is always required to ensure there is always an email field in your opt-in form. Choose the error message for this field below.', 'hustle' ); ?></span>

			<# } else { #>

				<span class="sui-description"><?php esc_html_e( 'Force users to fill out this field, otherwise it will be optional.', 'hustle' ); ?></span>

				<div class="sui-side-tabs" style="margin-top: 10px;">

					<div class="sui-tabs-menu">

						<label class="sui-tab-item">
							<input type="radio" name="required" value="false" data-attribute="required"{{ _.checked( 'false', required ) }}>
							<?php esc_html_e( 'Optional', 'hustle' ); ?></label>

						<label class="sui-tab-item">
							<input type="radio" name="required" value="true" data-attribute="required" {{ _.checked( 'true', required ) }} data-tab-menu="required">
							<?php esc_html_e( 'Required', 'hustle' ); ?></label>

					</div>

					<div class="sui-tabs-content">

						<div class="sui-tab-boxed{{ 'true' === required ? ' active' : '' }}" data-tab-content="required">

			<# } #>

							<div class="sui-form-field">

								<label class="sui-label"><?php esc_html_e( 'Error Message', 'hustle' ); ?></label>

								<input type="text"
									name="required_error_message"
									data-attribute="required_error_message"
									value="{{ required_error_message }}"
									class="sui-form-control" />

							</div>


			<# if ( 'email' !== type || 'email' !== name ) { #>

						</div>

					</div>

				</div>

			<# } #>

		</div>

	</div>

	<# if ( [ 'timepicker', 'datepicker', 'email', 'url' ].includes( type ) ) { #>

		<div class="sui-box-settings-row">

			<div class="sui-form-field" style="width: 100%">

				<label class="sui-settings-label"><?php esc_html_e( 'Validate', 'hustle' ); ?></label>
				<span class="sui-description"><?php esc_html_e( 'Make sure the user has filled out this field correctly and warn them when they haven\'t.', 'hustle' ); ?></span>

				<# if ( 'email' !== type ) { #>

					<div class="sui-side-tabs" style="margin-top: 10px;">

						<div class="sui-tabs-menu">

							<label class="sui-tab-item">
								<input type="radio" name="validate" value="false" data-attribute="validate"{{ _.checked( validate, 'false' ) }}>
								<?php esc_html_e( 'No Validation', 'hustle' ); ?></label>

							<label class="sui-tab-item">
								<input type="radio" name="validate" value="true" data-attribute="validate"{{ _.checked( validate, 'true' ) }} data-tab-menu="validate">
								<?php esc_html_e( 'Validate Field', 'hustle' ); ?></label>

						</div>

						<div class="sui-tabs-content">

							<div class="sui-tab-boxed{{ 'true' === validate ? ' active' : '' }}" data-tab-content="validate">
				<# } #>

								<div class="sui-form-field">

									<label class="sui-label"><?php esc_html_e( 'Validation message', 'hustle' ); ?></label>

									<input type="text"
										name="validation_message"
										data-attribute="validation_message"
										value="{{ validation_message }}"
										class="sui-form-control" />

								</div>

				<# if ( 'email' !== type ) { #>

							</div>

						</div>

					</div>

				<# } #>

			</div>

		</div>

	<# } #>

</script>

<script id="hustle-common-field-styling-tpl" type="text/template">

	<?php // TAB: Styling. ?>
	<div class="sui-box-settings-row">

		<div class="sui-box-settings-col-1">
			<span class="sui-settings-label"><?php esc_html_e( 'Additional CSS Classes', 'hustle' ); ?></span>
			<span class="sui-description"><?php esc_html_e( 'Add classes that will be output on this field’s container to aid your theme’s default styling.', 'hustle' ); ?></span>
		</div>

		<div class="sui-box-settings-col-2">
			<input type="text"
				name="css_classes"
				value="{{ css_classes }}"
				placeholder="<?php esc_html_e( 'E.g. form-field', 'hustle' ); ?>"
				class="sui-form-control" >
			<span class="sui-description"><?php esc_html_e( 'These will be output as you see them here.', 'hustle' ); ?></span>
		</div>

	</div>

</script>

<?php // TAB: reCAPTCHA Settings. ?>
<script id="hustle-recaptcha-field-settings-tpl" type="text/template">

	<?php // ROW: reCAPTCHA Type. ?>
	<div class="sui-box-settings-row">

		<div class="sui-box-settings-col-2">

			<label class="sui-settings-label sui-dark"><?php esc_html_e( 'reCAPTCHA Type', 'hustle' ); ?></label>
			<?php /* translators: 1. opening 'a' tag to more info about recaptcha, 2. closing 'a' tag */ ?>
			<span class="sui-description"><?php printf( esc_html__( 'Choose the reCAPTCHA type you want to use in your opt-in form. You can read more about the different reCAPTCHA types %1$shere%2$s and then choose the one which suits you the best.', 'hustle' ), '<a href="https://developers.google.com/recaptcha/docs/versions" target="_blank">', '</a>' ); ?></span>

			<div class="sui-side-tabs" style="margin-top: 10px;">

				<div class="sui-tabs-menu">

					<label for="hustle-recaptcha-type--v2-checkbox" class="sui-tab-item">
						<input type="radio"
							name="version"
							data-attribute="version"
							value="v2_checkbox"
							id="hustle-recaptcha-type--v2-checkbox"
							data-tab-menu="recaptcha-version-v2-checkbox-tab"
							{{ _.checked( 'v2_checkbox' === version, true ) }}
						/>
						<?php esc_html_e( 'V2 Checkbox', 'hustle' ); ?>
					</label>

					<label for="hustle-recaptcha-type--v2-invisible" class="sui-tab-item">
						<input type="radio"
							name="version"
							data-attribute="version"
							value="v2_invisible"
							id="hustle-recaptcha-type--v2-invisible"
							data-tab-menu="recaptcha-version-v2-invisible-tab"
							{{ _.checked( 'v2_invisible' === version, true ) }}
						/>
						<?php esc_html_e( 'V2 Invisible', 'hustle' ); ?>
					</label>

					<label for="hustle-recaptcha-type--v3-recaptcha" class="sui-tab-item">
						<input type="radio"
							name="version"
							data-attribute="version"
							value="v3_recaptcha"
							id="hustle-recaptcha-type--v3-recaptcha"
							data-tab-menu="recaptcha-version-v3-recaptcha-tab"
							{{ _.checked( 'v3_recaptcha' === version, true ) }}
						/>
						<?php esc_html_e( 'V3 reCAPTCHA', 'hustle' ); ?>
					</label>
				</div>

				<?php
				$url = add_query_arg(
					array(
						'page'    => Hustle_Data::SETTINGS_PAGE,
						'section' => 'recaptcha',
					),
					'admin.php'
				);

				$message_string = sprintf(
					/* translators: 1: opening 'a' tag, 2: closing 'a' tag */
					esc_html__( 'You haven\'t added API keys for this reCAPTCHA type in your global settings. Add your API keys %1$shere%2$s and then come back to configure this field.', 'hustle' ),
					'<a href="' . esc_url_raw( $url ) . '" target="_blank">',
					'</a>'
				);

				$unavailable_message = $this->get_html_for_options(
					array(
						array(
							'type'  => 'inline_notice',
							'class' => 'sui-notice-error',
							'icon'  => 'info',
							'value' => $message_string,
						),
					),
					true
				);
				?>

				<div class="sui-tabs-content">

					<div class="sui-tab-content<?php echo ( in_array( 'v2_checkbox', $available_recaptchas, true ) ) ? ' sui-tab-boxed' : ''; ?>" data-tab-content="recaptcha-version-v2-checkbox-tab">

						<?php if ( in_array( 'v2_checkbox', $available_recaptchas, true ) ) : ?>

							<div class="sui-row">

								<?php
								// SECTION: Size.
								?>
								<div class="sui-col-md-6">

									<div class="sui-form-field">

										<label for="hustle-recaptcha-size" class="sui-label"><?php esc_html_e( 'Size', 'hustle' ); ?></label>

										<select id="hustle-recaptcha-size" data-attribute="recaptcha_type" name="recaptcha_type">
											<option value="compact" {{ _.selected( 'compact' === recaptcha_type, true ) }} ><?php esc_attr_e( 'Compact', 'hustle' ); ?></option>
											<option value="full" {{ _.selected( 'full' === recaptcha_type, true ) }} ><?php esc_attr_e( 'Full size', 'hustle' ); ?></option>
										</select>

									</div>

								</div>

								<?php
								// SECTION: Theme.
								?>
								<div class="sui-col-md-6">

									<div class="sui-form-field">

										<label for="hustle-recaptcha-v2-checkbox-theme" class="sui-label"><?php esc_html_e( 'Theme', 'hustle' ); ?></label>

										<select id="hustle-recaptcha-v2-checkbox-theme" data-attribute="recaptcha_theme" name="recaptcha_theme">
											<option value="dark" {{ _.selected( 'dark' === recaptcha_theme, true ) }} ><?php esc_attr_e( 'Dark', 'hustle' ); ?></option>
											<option value="light" {{ _.selected( 'light' === recaptcha_theme, true ) }} ><?php esc_attr_e( 'Light', 'hustle' ); ?></option>
										</select>

									</div>

								</div>

							</div>

						<?php else : ?>

							<?php echo $unavailable_message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

						<?php endif; ?>

					</div>

					<div class="sui-tab-content<?php echo ( in_array( 'v2_invisible', $available_recaptchas, true ) ) ? ' sui-tab-boxed' : ''; ?>" data-tab-content="recaptcha-version-v2-invisible-tab">

						<?php if ( in_array( 'v2_invisible', $available_recaptchas, true ) ) : ?>

							<?php
							// SECTION: reCAPTCHA Badge.
							?>
							<div class="sui-form-field">

								<label class="sui-label"><?php esc_html_e( 'reCAPTCHA Badge', 'hustle' ); ?></label>

								<div class="sui-side-tabs" style="margin-top: 10px;">

									<div class="sui-tabs-menu">

										<label class="sui-tab-item{{ _.class( ( '1' === v2_invisible_show_badge ), ' active' ) }}">
											<input
												type="radio"
												name="v2_invisible_show_badge"
												data-attribute="v2_invisible_show_badge"
												data-tab-menu="v2-invisible-show-badge-tab"
												value="1"
												{{ _.checked( '1' === v2_invisible_show_badge, true ) }}
											/>
											<?php esc_html_e( 'Show', 'hustle' ); ?>
										</label>

										<label class="sui-tab-item{{ _.class( ( '0' === v2_invisible_show_badge ), ' active' ) }}">
											<input
												type="radio"
												name="v2_invisible_show_badge"
												data-attribute="v2_invisible_show_badge"
												data-tab-menu="v2-invisible-hide-badge-tab"
												value="0"
												{{ _.checked( '0' === v2_invisible_show_badge, true ) }}
											/>
											<?php esc_html_e( 'Hide', 'hustle' ); ?>
										</label>

									</div>

									<div class="sui-tabs-content">

										<div class="sui-tab-content{{ _.class( ( '0' !== v2_invisible_show_badge ), ' active' ) }}" data-tab-content="v2-invisible-show-badge-tab">

											<p class="sui-description" style="margin-bottom: 20px;"><?php esc_html_e( 'Choose whether you want to show the reCAPTCHA badge or not.', 'hustle' ); ?></p>

											<div class="sui-form-field">

												<label for="hustle-recaptcha-v2-invisible-theme" class="sui-label"><?php esc_html_e( 'Theme', 'hustle' ); ?></label>

												<div style="width: 100%; max-width: 240px;">

													<select id="hustle-recaptcha-v2-invisible-theme" data-attribute="v2_invisible_theme" name="v2_invisible_theme">
														<option value="dark" {{ _.selected( 'dark' === v2_invisible_theme, true ) }} ><?php esc_attr_e( 'Dark', 'hustle' ); ?></option>
														<option value="light" {{ _.selected( 'light' === v2_invisible_theme, true ) }} ><?php esc_attr_e( 'Light', 'hustle' ); ?></option>
													</select>

												</div>

											</div>

										</div>

										<div class="sui-tab-content{{ _.class( ( '0' === v2_invisible_show_badge ), ' active' ) }}" data-tab-content="v2-invisible-hide-badge-tab">

											<p class="sui-description" style="margin-bottom: 30px;"><?php esc_html_e( 'Choose whether you want to show the reCAPTCHA badge or not.', 'hustle' ); ?></p>

											<div class="sui-form-field">

												<label class="sui-label sui-label-editor"><?php esc_html_e( 'reCAPTCHA Branding Text', 'hustle' ); ?></label>

												<textarea name="v2_invisible_badge_replacement" id="v2_invisible_badge_replacement">{{v2_invisible_badge_replacement}}</textarea>
												<?php /* translators: 1. opening 'a' tag to guidelines for when hiding recaptcha, 2. closing 'a' tag */ ?>
												<span class="sui-description" style="margin-top: 10px;"><?php printf( esc_html__( 'Note that the above reCAPTCHA branding text will replace the reCAPTCHA badge on your module to comply with %1$sGoogle\'s policy%2$s of hiding the reCAPTCHA badge.', 'hustle' ), '<a href="https://developers.google.com/recaptcha/docs/faq#id-like-to-hide-the-recaptcha-badge.-what-is-allowed" target="_blank" rel="nofollow">', '</a>' ); ?></span>

											</div>

										</div>

									</div>

								</div>

							</div>

						<?php else : ?>

							<?php echo $unavailable_message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

						<?php endif; ?>

					</div>

					<div class="sui-tab-content<?php echo ( in_array( 'v3_recaptcha', $available_recaptchas, true ) ) ? ' sui-tab-boxed' : ''; ?>" data-tab-content="recaptcha-version-v3-recaptcha-tab">

						<?php if ( in_array( 'v3_recaptcha', $available_recaptchas, true ) ) : ?>

							<div class="sui-form-field">

								<label for="hustle-recaptcha-threshold" class="sui-label"><?php esc_html_e( 'Score Threshold', 'hustle' ); ?></label>

								<div style="width: 100%; max-width: 120px;">

									<select id="hustle-recaptcha-threshold" data-attribute="threshold" name="threshold">

										<?php for ( $i = 0; $i <= 1; $i += 0.1 ) : ?>
											<option value="<?php echo esc_attr( $i ); ?>" {{ _.selected( ( '<?php echo esc_attr( $i ); ?>' === threshold ), true ) }}><?php echo esc_html( $i ); ?></option>
										<?php endfor; ?>

									</select>

								</div>

								<span class="sui-description" style="margin-top: 10px;"><?php esc_html_e( 'reCAPTCHA v3 returns a score (1 is very likely a good interaction, 0 is very likely a bot) based on user interactions. Choose the score below which the verification should fail.', 'hustle' ); ?></span>

							</div>

							<div class="sui-form-field">

								<div class="sui-row">

									<div class="sui-col-md-12">

										<label class="sui-label"><?php esc_html_e( 'reCAPTCHA Badge', 'hustle' ); ?></label>

										<div class="sui-side-tabs" style="margin-top: 10px;">

											<div class="sui-tabs-menu">

												<label class="sui-tab-item{{ _.class( ( '1' === v3_recaptcha_show_badge ), ' active' ) }}">
													<input type="radio"
														name="v3_recaptcha_show_badge"
														data-attribute="v3_recaptcha_show_badge"
														data-tab-menu="v3-recaptcha-show-badge-tab"
														value="1"
														{{ _.checked( '1' === v3_recaptcha_show_badge, true ) }}
													/>
													<?php esc_html_e( 'Show', 'hustle' ); ?>
												</label>

												<label class="sui-tab-item{{ _.class( ( '0' === v3_recaptcha_show_badge ), ' active' ) }}">
													<input type="radio"
														name="v3_recaptcha_show_badge"
														data-attribute="v3_recaptcha_show_badge"
														data-tab-menu="v3-recaptcha-hide-badge-tab"
														value="0"
														{{ _.checked( '0' === v3_recaptcha_show_badge, true ) }}
													/>
													<?php esc_html_e( 'Hide', 'hustle' ); ?>
												</label>

											</div>

											<div class="sui-tabs-content">

												<div class="sui-tab-content{{ _.class( ( '0' !== v3_recaptcha_show_badge ), ' active' ) }}" data-tab-content="v3-recaptcha-show-badge-tab">

													<p class="sui-description"><?php esc_html_e( 'Choose whether you want to show the reCAPTCHA badge or not.', 'hustle' ); ?></p>

												</div>

												<div class="sui-tab-content{{ _.class( ( '0' === v3_recaptcha_show_badge ), ' active' ) }}" data-tab-content="v3-recaptcha-hide-badge-tab">

													<p class="sui-description" style="margin-bottom: 30px;"><?php esc_html_e( 'Choose whether you want to show the reCAPTCHA badge or not.', 'hustle' ); ?></p>

													<div class="sui-form-field">

														<label class="sui-label sui-label-editor"><?php esc_html_e( 'reCAPTCHA Branding Text', 'hustle' ); ?></label>

														<textarea name="v3_recaptcha_badge_replacement" id="v3_recaptcha_badge_replacement">{{v3_recaptcha_badge_replacement}}</textarea>
														<?php /* translators: 1. opening 'a' tag to guidelines when hiding recaptcha, 2. closing 'a' tag */ ?>
														<span class="sui-description" style="margin-top: 10px;"><?php printf( esc_html__( 'Note that the above reCAPTCHA branding text will replace the reCAPTCHA badge on your module to comply with %1$sGoogle\'s policy%2$s of hiding the reCAPTCHA badge.', 'hustle' ), '<a href="https://developers.google.com/recaptcha/docs/faq#id-like-to-hide-the-recaptcha-badge.-what-is-allowed" target="_blank" rel="nofollow">', '</a>' ); ?></span>

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

							</div>

						<?php else : ?>

							<?php echo $unavailable_message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

						<?php endif; ?>

					</div>

				</div>

				<input
					type="hidden"
					id="available_recaptchas"
					value="<?php echo esc_attr( implode( ',', $available_recaptchas ) ); ?>"
				/>
			</div>

		</div>

	</div>

	<?php // ROW: Language. ?>
	<div class="sui-box-settings-row">

		<div class="sui-box-settings-col-2">

			<span class="sui-settings-label sui-dark"><?php esc_html_e( 'Language', 'hustle' ); ?></span>

			<span class="sui-description"><?php esc_html_e( 'By default, we’ll use the language selected in the global reCAPTCHA settings. However, you can choose a different language for this reCAPTCHA.', 'hustle' ); ?></span>

			<div style="width: 100%; max-width: 240px; margin-top: 10px;">

				<select id="hustle-recaptcha-language" data-attribute="recaptcha_language" class="sui-select" name="recaptcha_language">
					<option value="automatic" {{ _.selected( 'automatic' === recaptcha_language, true ) }} ><?php esc_attr_e( 'Automatic', 'hustle' ); ?></option>
					<?php
					$languages = Opt_In_Utils::get_captcha_languages();
					foreach ( $languages as $key => $language ) {
						?>
						<option value="<?php echo esc_attr( $key ); ?>" {{ _.selected( '<?php echo esc_attr( $key ); ?>' === recaptcha_language, true ) }} ><?php echo esc_attr( $language ); ?></option>
					<?php } ?>
				</select>

			</div>

		</div>

	</div>

	<?php // ROW: Verification Error. ?>
	<div class="sui-box-settings-row">

		<div class="sui-box-settings-col-2">

			<div class="sui-form-field">

				<label for="recaptcha-verification-error" id="recaptcha-verification-error-label" class="sui-settings-label sui-dark"><?php esc_html_e( 'Verification Error', 'hustle' ); ?></label>

				<span id="recaptcha-verification-error-desc" class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose the error message you want to display when reCAPTCHA verification fails.', 'hustle' ); ?></span>

				<input
					type="text"
					name="validation_message"
					value="{{ validation_message }}"
					id="recaptcha-verification-error"
					class="sui-form-control"
					aria-labelledby="recaptcha-verification-error-label"
					aria-describedby="recaptcha-verification-error-desc"
				/>

			</div>

		</div>

	</div>

</script>

<script id="hustle-gdpr-field-settings-tpl" type="text/template">

	<?php // TAB: GDPR Settings. ?>
	<div class="sui-form-field">

		<label class="sui-label"><?php esc_html_e( 'Label', 'hustle' ); ?></label>

		<input type="text"
			name="label"
			value="{{ label }}"
			placeholder="{{ label_placeholder }}"
			class="sui-form-control" />

	</div>

	<div class="sui-form-field">

		<textarea name="gdpr_message" id="gdpr_message">{{gdpr_message}}</textarea>

	</div>

	<div class="sui-form-field">

		<label class="sui-label"><?php esc_html_e( 'Error Message', 'hustle' ); ?></label>

		<input type="text"
			name="required_error_message"
			data-attribute="required_error_message"
			value="{{ required_error_message }}"
			class="sui-form-control" />

		<span class="sui-description">
			<?php esc_html_e( 'The form will not submit until the user has accepted the terms. Choose the error message to display when the user is trying to submit the form without accepting the terms.', 'hustle' ); ?>
		</span>

	</div>

</script>

<script id="hustle-datepicker-field-labels-tpl" type="text/template">

	<?php // TAB: Datepicker Labels. ?>
	<div class="sui-row">

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Label', 'hustle' ); ?></label>

				<input type="text"
					name="label"
					value="{{ label }}"
					placeholder="{{ label_placeholder }}"
					class="sui-form-control" />

			</div>

		</div>

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Name', 'hustle' ); ?></label>

				<input type="text"
					name="name"
					value="{{ name }}"
					placeholder="{{ name_placeholder }}"
					class="sui-form-control" />

			</div>

		</div>

	</div>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Placeholder (optional)', 'hustle' ); ?></label>

				<input type="text"
					name="placeholder"
					value="{{ placeholder }}"
					placeholder="{{ placeholder_placeholder }}"
					class="sui-form-control" />

			</div>

		</div>

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Format', 'hustle' ); ?></label>

				<select id="hustle-date-format" class="sui-select" data-attribute="date_format" name="date_format">
					<?php
						$formats = Hustle_Time_Helper::get_date_formats();
					foreach ( $formats as $key => $format ) {
						?>
							<option value="<?php echo esc_attr( $key ); ?>" {{ _.selected( '<?php echo esc_attr( $key ); ?>' === date_format, true ) }} ><?php echo esc_attr( $format ); ?></option>
					<?php } ?>
				</select>

			</div>

		</div>

	</div>

</script>

<script id="hustle-timepicker-field-labels-tpl" type="text/template">

	<?php // TAB: Timepicker Labels. ?>
	<div class="sui-row">

		<div class="sui-col-md-12">

			<label class="sui-label"><?php esc_html_e( 'Format', 'hustle' ); ?></label>

			<div class="sui-side-tabs">

				<div class="sui-tabs-menu">

					<label for="hustle-time-format--12" class="sui-tab-item">
						<input type="radio"
							name="time_format"
							data-attribute="time_format"
							value="12"
							id="hustle-time-format--12"
							{{ _.checked( '12' === time_format, true ) }} />
						<?php esc_html_e( '12 hour', 'hustle' ); ?>
					</label>

					<label for="hustle-time-format--24" class="sui-tab-item">
						<input type="radio"
							name="time_format"
							data-attribute="time_format"
							value="24"
							id="hustle-time-format--24"
							{{ _.checked( '24' === time_format, true ) }} />
						<?php esc_html_e( '24 hour', 'hustle' ); ?>
					</label>

				</div>

				<div class="sui-tabs-content">
				</div>

			</div>

		</div>

	</div>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Label', 'hustle' ); ?></label>

				<input type="text"
					name="label"
					value="{{ label }}"
					placeholder="{{ label_placeholder }}"
					class="sui-form-control" />

			</div>

		</div>

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Name', 'hustle' ); ?></label>

				<input type="text"
					name="name"
					value="{{ name }}"
					placeholder="{{ name_placeholder }}"
					class="sui-form-control" />

			</div>

		</div>

	</div>

	<label class="sui-label"><?php esc_html_e( 'Default time', 'hustle' ); ?></label>
	<div class="sui-row">

		<div class="sui-col-md-2">
			<div class="sui-form-field">
				<input type="number"
					min="{{ '12' === time_format ? 1 : 0 }}"
					max="{{ '12' === time_format ? 12 : 23 }}"
					name="time_hours"
					value="{{ time_hours }}"
					class="sui-form-control" />
			</div>
		</div>

		<div class="sui-col-md-2">
			<div class="sui-form-field">
				<input type="number"
					min="0"
					max="59"
					name="time_minutes"
					value="{{ time_minutes }}"
					class="sui-form-control" />
			</div>
		</div>

		<div class="sui-col-md-3">
			<div class="sui-form-field{{'24' === time_format ? ' sui-hidden' : ''}}">
				<select id="hustle-date-format" class="sui-select" data-attribute="time_period" name="time_period" data-width="100">
					<?php
						$periods = Hustle_Time_Helper::get_meridiam_periods();
					foreach ( $periods as $key => $period ) {
						?>
							<option value="<?php echo esc_attr( $key ); ?>" {{ _.selected( '<?php echo esc_attr( $key ); ?>' === time_period, true ) }} ><?php echo esc_attr( $period ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="sui-col-md-5">
		</div>

	</div>

</script>

<script id="hustle-submit-field-settings-tpl" type="text/template">

	<?php // TAB: Submit Settings. ?>
	<div class="sui-row">

		<div class="sui-col-md-12">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Button Text', 'hustle' ); ?></label>

				<input type="text"
					name="label"
					value="{{ label }}"
					class="sui-form-control" />

			</div>

		</div>

	</div>

	<div class="sui-row">

		<div class="sui-col-md-12">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Error Message', 'hustle' ); ?></label>

				<input type="text"
					name="error_message"
					value="{{ error_message }}"
					class="sui-form-control" />

				<span class="sui-description">
					<?php esc_html_e( 'This error will appear when there is an unknown error while submitting the form.', 'hustle' ); ?>
				</span>

			</div>

		</div>

	</div>

</script>

<script id="hustle-hidden-field-labels-tpl" type="text/template">

	<?php // TAB: Labels. ?>
	<div class="sui-row">

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label for="hustle-field-hidden--input" id="hustle-field-hidden--input-label" class="sui-label"><?php esc_html_e( 'Label', 'hustle' ); ?></label>

				<input
					type="text"
					value="{{ label }}"
					name="label"
					placeholder="<?php esc_html_e( 'Enter label', 'hustle' ); ?>"
					id="hustle-field-hidden--input"
					class="sui-form-control"
					aria-labelledby="hustle-field-hidden--input-label"
				/>

			</div>

		</div>

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Name', 'hustle' ); ?></label>

				<input type="text"
					name="name"
					value="{{ name }}"
					class="sui-form-control" />

				<span class="sui-description">
					<?php esc_html_e( 'Do not use any spaces in the name to ensure this field is submitted successfully.', 'hustle' ); ?>
				</span>

			</div>

		</div>

	</div>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<div class="sui-form-field">

				<label for="hustle-field-hidden--default" id="hustle-field-hidden--default-label" class="sui-label"><?php esc_html_e( 'Default Value', 'hustle' ); ?></label>

				<select
					name="default_value"
					id="hustle-field-hidden--default"
					class="sui-select"
					data-content-on="custom_value"
					aria-labelledby="hustle-field-hidden--default-label"
				>
					<option value="user_ip" {{ _.selected( 'user_ip' === default_value, true ) }}><?php esc_html_e( 'User IP Address', 'hustle' ); ?></option>
					<option value="date_mdy" {{ _.selected( 'date_mdy' === default_value, true ) }}><?php esc_html_e( 'Date (mm/dd/yyyy)', 'hustle' ); ?></option>
					<option value="date_dmy" {{ _.selected( 'date_dmy' === default_value, true ) }}><?php esc_html_e( 'Date (dd/mm/yyyy)', 'hustle' ); ?></option>
					<option value="embed_id" {{ _.selected( 'embed_id' === default_value, true ) }}><?php esc_html_e( 'Embed Post/Page ID', 'hustle' ); ?></option>
					<option value="embed_title" {{ _.selected( 'embed_title' === default_value, true ) }}><?php esc_html_e( 'Embed Post/Page Title', 'hustle' ); ?></option>
					<option value="embed_url" {{ _.selected( 'embed_url' === default_value, true ) }}><?php esc_html_e( 'Embed URL', 'hustle' ); ?></option>
					<option value="user_agent" {{ _.selected( 'user_agent' === default_value, true ) }}><?php esc_html_e( 'HTTP User Agent', 'hustle' ); ?></option>
					<option value="refer_url" {{ _.selected( 'refer_url' === default_value, true ) }}><?php esc_html_e( 'HTTP Refer URL', 'hustle' ); ?></option>
					<option value="user_id" {{ _.selected( 'user_id' === default_value, true ) }}><?php esc_html_e( 'User ID', 'hustle' ); ?></option>
					<option value="user_name" {{ _.selected( 'user_name' === default_value, true ) }}><?php esc_html_e( 'User Display Name', 'hustle' ); ?></option>
					<option value="user_email" {{ _.selected( 'user_email' === default_value, true ) }}><?php esc_html_e( 'User Email', 'hustle' ); ?></option>
					<option value="user_login" {{ _.selected( 'user_login' === default_value, true ) }}><?php esc_html_e( 'User Login', 'hustle' ); ?></option>
					<option value="custom_value" {{ _.selected( 'custom_value' === default_value, true ) }}><?php esc_html_e( 'Custom Value', 'hustle' ); ?></option>
				</select>

			</div>

		</div>

		<div class="sui-col-md-6">

			<div class="sui-form-field" data-field-content="default_value">

				<label for="hustle-field-hidden--custom" id="hustle-field-hidden--custom-label" class="sui-label"><?php esc_html_e( 'Custom Value', 'hustle' ); ?></label>

				<input
					type="text"
					name="custom_value"
					value="{{ custom_value }}"
					placeholder="<?php esc_html_e( 'Enter custom value', 'hustle' ); ?>"
					id="hustle-field-hidden--custom"
					class="sui-form-control"
					aria-labelledby="hustle-field-hidden--custom-label"
				/>

			</div>

		</div>

	</div>

</script>