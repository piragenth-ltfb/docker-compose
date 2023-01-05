<?php
/**
 * Icons appearance section.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();
?>
<div class="sui-form-field" id="hustle-<?php echo esc_attr( $key ); ?>-icons-custom-background">

	<label class="sui-label hustle-icon-border-color-label sui-hidden"><?php esc_html_e( 'Icon border', 'hustle' ); ?></label>
	<label class="sui-label hustle-icon-bg-color-label"><?php esc_html_e( 'Icon background', 'hustle' ); ?></label>

	<?php $this->sui_colorpicker( $key . '_icon_bg_color', $key . '_icon_bg_color', 'true', false, $settings[ $key . '_icon_bg_color' ] ); ?>

</div>

<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Icon color', 'hustle' ); ?></label>

	<?php $this->sui_colorpicker( $key . '_icon_color', $key . '_icon_color', 'true', false, $settings[ $key . '_icon_color' ] ); ?>

</div>

<?php
$custom_content = ob_get_clean();

$options = array(
	'0' => array(
		'value' => '0',
		'label' => __( 'Use default colors', 'hustle' ),
	),
	'1' => array(
		'value'   => '1',
		'label'   => __( 'Custom', 'hustle' ),
		'content' => $custom_content,
	),
);
?>
<div id="hustle-appearance-<?php echo esc_attr( $key ); ?>-icons-row" class="sui-box-settings-row" <?php echo ! $is_enabled ? ' style="display: none;"' : ''; ?>>

	<div class="sui-box-settings-col-1">

		<span class="sui-settings-label"><?php echo esc_html( $label ); ?></span>
		<span class="sui-description"><?php echo esc_html( $description ); ?></span>

		<?php if ( isset( $preview ) && 'sidenav' === $preview ) { ?>

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Preview module', 'hustle' ); ?></label>

				<div class="hui-preview-social" id="hui-preview-social-shares-floating"></div>

			</div>

		<?php } ?>

	</div>

	<div class="sui-box-settings-col-2">

		<?php // SETTINGS: Colors Scheme. ?>
		<div class="sui-form-field">

			<label class="sui-settings-label"><?php esc_html_e( 'Customize color scheme', 'hustle' ); ?></label>

			<span class="sui-description"><?php esc_html_e( 'Adjust the default color scheme of your social bar to match your theme styling.', 'hustle' ); ?></span>

			<div class="sui-accordion" style="margin-top: 10px;">

				<?php // COLORS: Social Icons. ?>
				<div class="sui-accordion-item">

					<div class="sui-accordion-item-header">

						<div class="sui-accordion-item-title">
							<?php esc_html_e( 'Social Icons', 'hustle' ); ?>
							<button
								class="sui-button-icon sui-accordion-open-indicator"
								aria-label="<?php esc_html_e( 'Open counter color options', 'hustle' ); ?>"
							>
								<span class="sui-icon-chevron-down" aria-hidden="true"></span>
							</button>
						</div>

					</div>

					<div class="sui-accordion-item-body">

						<div class="sui-box">

							<div class="sui-box-body">

								<label class="sui-label"><?php esc_html_e( 'Colors', 'hustle' ); ?></label>

								<?php
								$this->render(
									'admin/global/sui-components/sui-tabs',
									array(
										'name'          => $key . '_customize_colors',
										'radio'         => true,
										'saved_value'   => $settings[ $key . '_customize_colors' ],
										'sidetabs'      => true,
										'content'       => true,
										'content_class' => 'sui-tabs-content-lg',
										'options'       => $options,
									)
								);
								?>

							</div>

						</div>

					</div>

				</div>

				<?php // COLORS: Counter. ?>
				<div class="sui-accordion-item" data-toggle-content="counter-enabled">

					<div class="sui-accordion-item-header">
						<div class="sui-accordion-item-title">
							<?php esc_html_e( 'Counter', 'hustle' ); ?>
							<button
								class="sui-button-icon sui-accordion-open-indicator"
								aria-label="<?php esc_html_e( 'Open counter color options', 'hustle' ); ?>"
							>
								<span class="sui-icon-chevron-down" aria-hidden="true"></span>
							</button>
						</div>
					</div>

					<div class="sui-accordion-item-body">

						<div class="sui-box">

							<div class="sui-box-body">

								<div id="hustle-<?php echo esc_html( $key ); ?>-counter-border" class="sui-form-field">

									<label class="sui-label"><?php esc_html_e( 'Border', 'hustle' ); ?></label>

									<?php $this->sui_colorpicker( $key . '_counter_border', $key . '_counter_border', 'true', false, $settings[ $key . '_counter_border' ] ); ?>

								</div>

								<div class="sui-form-field">

									<label class="sui-label"><?php esc_html_e( 'Text', 'hustle' ); ?></label>

									<?php $this->sui_colorpicker( $key . '_counter_color', $key . '_counter_color', 'true', false, $settings[ $key . '_counter_color' ] ); ?>

								</div>

							</div>

						</div>

					</div>

				</div>

				<?php // COLORS: Container. ?>
				<div class="sui-accordion-item">

					<div class="sui-accordion-item-header">
						<div class="sui-accordion-item-title">
							<?php esc_html_e( 'Container', 'hustle' ); ?>
							<button
								class="sui-button-icon sui-accordion-open-indicator"
								aria-label="<?php esc_html_e( 'Open container color options', 'hustle' ); ?>"
							>
								<span class="sui-icon-chevron-down" aria-hidden="true"></span>
							</button>
						</div>
					</div>

					<div class="sui-accordion-item-body">

						<div class="sui-box">

							<div class="sui-box-body">

								<div class="sui-form-field">

									<label class="sui-label"><?php esc_html_e( 'Background color', 'hustle' ); ?></label>

									<?php $this->sui_colorpicker( $key . '_bg_color', $key . '_bg_color', 'true', false, $settings[ $key . '_bg_color' ] ); ?>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

		<?php // SETTINGS: Drop Shadow. ?>
		<div class="sui-form-field">

			<label for="hustle-icons--<?php echo esc_html( $key ); ?>-shadow" class="sui-toggle hustle-toggle-with-container" data-toggle-on="<?php echo esc_html( $key ); ?>-drop-shadow">
				<input
					type="checkbox"
					name="<?php echo esc_html( $key ); ?>_drop_shadow"
					data-attribute="<?php echo esc_html( $key ); ?>_drop_shadow"
					id="hustle-icons--<?php echo esc_html( $key ); ?>-shadow"
					aria-labelledby="hustle-icons--<?php echo esc_html( $key ); ?>-shadow-label"
					aria-describedby="hustle-icons--<?php echo esc_html( $key ); ?>-shadow-description"
					<?php checked( $settings[ $key . '_drop_shadow' ], '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-icons--<?php echo esc_html( $key ); ?>-shadow-label" class="sui-toggle-label"><?php esc_html_e( 'Drop shadow', 'hustle' ); ?></span>

				<span id="hustle-icons--<?php echo esc_html( $key ); ?>-shadow-description" class="sui-description"><?php esc_html_e( 'Add a shadow to the container.', 'hustle' ); ?></span>
			</label>

			<div class="sui-border-frame sui-toggle-content" data-toggle-content="<?php echo esc_html( $key ); ?>-drop-shadow">

				<div class="sui-row">

					<div class="sui-col-md-3">

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_html( $key ); ?>-shadow--x-offset" class="sui-label"><?php esc_html_e( 'X-offset', 'hustle' ); ?></label>

							<input
								type="number"
								name="<?php echo esc_html( $key ); ?>_drop_shadow_x"
								data-attribute="<?php echo esc_html( $key ); ?>_drop_shadow_x"
								value="<?php echo esc_attr( $settings[ $key . '_drop_shadow_x' ] ); ?>"
								placeholder="0"
								id="hustle-<?php echo esc_html( $key ); ?>-shadow--x-offset"
								class="sui-form-control"
							/>

						</div>

					</div>

					<div class="sui-col-md-3">

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_html( $key ); ?>-shadow--y-offset" class="sui-label"><?php esc_html_e( 'Y-offset', 'hustle' ); ?></label>

							<input
								type="number"
								name="<?php echo esc_html( $key ); ?>_drop_shadow_y"
								data-attribute="<?php echo esc_html( $key ); ?>_drop_shadow_y"
								value="<?php echo esc_attr( $settings[ $key . '_drop_shadow_y' ] ); ?>"
								placeholder="0"
								id="hustle-<?php echo esc_html( $key ); ?>-shadow--y-offset"
								class="sui-form-control"
							/>

						</div>

					</div>

					<div class="sui-col-md-3">

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_html( $key ); ?>-shadow--blur" class="sui-label"><?php esc_html_e( 'Blur', 'hustle' ); ?></label>

							<input
								type="number"
								name="<?php echo esc_html( $key ); ?>_drop_shadow_blur"
								data-attribute="<?php echo esc_html( $key ); ?>_drop_shadow_blur"
								value="<?php echo esc_attr( $settings[ $key . '_drop_shadow_blur' ] ); ?>"
								placeholder="0"
								id="hustle-<?php echo esc_html( $key ); ?>-shadow--blur"
								class="sui-form-control"
							/>

						</div>

					</div>

					<div class="sui-col-md-3">

						<div class="sui-form-field">

							<label for="hustle-<?php echo esc_html( $key ); ?>-shadow--spread" class="sui-label"><?php esc_html_e( 'Spread', 'hustle' ); ?></label>

							<input
								type="number"
								name="<?php echo esc_html( $key ); ?>_drop_shadow_spread"
								data-attribute="<?php echo esc_html( $key ); ?>_drop_shadow_spread"
								value="<?php echo esc_attr( $settings[ $key . '_drop_shadow_spread' ] ); ?>"
								placeholder="0"
								id="hustle-<?php echo esc_html( $key ); ?>-shadow--spread"
								class="sui-form-control"
							/>

						</div>

					</div>

				</div>

				<div class="sui-row">

					<div class="sui-col">

						<div class="sui-form-field">

							<label class="sui-label"><?php esc_html_e( 'Color', 'hustle' ); ?></label>

							<?php $this->sui_colorpicker( $key . '_drop_shadow_color', $key . '_drop_shadow_color', 'true', false, $settings[ $key . '_drop_shadow_color' ] ); ?>

						</div>

					</div>

				</div>

			</div>

		</div>

		<?php // SETTINGS: Inline Counter. ?>
		<div class="sui-form-field" data-toggle-content="counter-enabled">

			<label for="hustle-icons--<?php echo esc_html( $key ); ?>-inline-counter" class="sui-toggle">
				<input
					type="checkbox"
					name="<?php echo esc_html( $key ); ?>_inline_count"
					data-attribute="<?php echo esc_html( $key ); ?>_inline_count"
					id="hustle-icons--<?php echo esc_html( $key ); ?>-inline-counter"
					aria-labelledby="hustle-icons--<?php echo esc_html( $key ); ?>-inline-counter-label"
					aria-describedby="hustle-icons--<?php echo esc_html( $key ); ?>-inline-counter-description"
					<?php checked( $settings[ $key . '_inline_count' ], '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-icons--<?php echo esc_html( $key ); ?>-inline-counter-label" class="sui-toggle-label"><?php esc_html_e( 'Inline counter', 'hustle' ); ?></span>

				<span id="hustle-icons--<?php echo esc_html( $key ); ?>-inline-counter-description" class="sui-description"><?php esc_html_e( 'Enable this to make the counter text inline to the icon.', 'hustle' ); ?></span>
			</label>

		</div>

		<?php // SETTINGS: Animate Icons. ?>
		<div class="sui-form-field">

			<label for="hustle-icons--<?php echo esc_html( $key ); ?>-animate" class="sui-toggle">
				<input
					type="checkbox"
					name="<?php echo esc_html( $key ); ?>_animate_icons"
					data-attribute="<?php echo esc_html( $key ); ?>_animate_icons"
					id="hustle-icons--<?php echo esc_html( $key ); ?>-animate"
					aria-labelledby="hustle-icons--<?php echo esc_html( $key ); ?>-animate-label"
					aria-describedby="hustle-icons--<?php echo esc_html( $key ); ?>-animate-description"
					<?php checked( $settings[ $key . '_animate_icons' ], '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-icons--<?php echo esc_html( $key ); ?>-animate-label" class="sui-toggle-label"><?php esc_html_e( 'Animate icons', 'hustle' ); ?></span>

				<span id="hustle-icons--<?php echo esc_html( $key ); ?>-animate-description" class="sui-description"><?php esc_html_e( 'Animate the icons when visitor hovers over them.', 'hustle' ); ?></span>
			</label>

		</div>

		<?php if ( isset( $preview ) && 'content' === $preview ) { ?>

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Preview module', 'hustle' ); ?></label>

				<div class="hui-preview-social" id="hui-preview-social-shares-widget"></div>

			</div>

		<?php } ?>

	</div>

</div>

<div id="hustle-appearance-<?php echo esc_attr( $key ); ?>-icons-placeholder" class="sui-box-settings-row"<?php echo ( $is_enabled || $is_empty ) ? ' style="display: none;"' : ''; ?>>

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php echo esc_html( $label ); ?></span>
		<span class="sui-description"><?php echo esc_html( $description ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">
		<div class="sui-notice">

			<div class="sui-notice-content">

				<div class="sui-notice-message">

					<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
					<p><?php echo esc_html( $disabled_message ); ?></p>

				</div>
			</div>
		</div>
	</div>

</div>