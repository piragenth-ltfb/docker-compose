<?php
/**
 * Main wrapper for the 'Appearance' tab.
 *
 * @uses ../tab-appearance/
 *
 * @package Hustle
 * @since 4.0.0
 */

$args = array(
	'settings'            => $settings,
	'is_optin'            => $is_optin,
	'smallcaps_singular'  => $smallcaps_singular,
	'capitalize_singular' => $capitalize_singular,
	'module_type'         => $module_type,
);
?>
<div id="hustle-wizard-appearance" class="sui-box" <?php echo 'appearance' !== $section ? 'style="display: none;"' : ''; ?> data-tab="appearance">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Appearance', 'hustle' ); ?></h2>

		<div class="sui-actions-right">

			<div class="hui-header-checkbox">

				<div
					class="sui-tooltip sui-tooltip-bottom sui-tooltip-bottom-left-mobile sui-tooltip-constrained"
					data-tooltip="<?php esc_html_e( 'By default, we automatically adjust the mobile appearance of your module. However, you can enable this option to have manual control on how your module looks on mobile.', 'hustle' ); ?>"
				>
					<span class="sui-icon-info sui-sm" aria-hidden="true"></span>
				</div>

				<label id="hustle-enable-mobile-label" class="sui-label"><?php esc_html_e( 'Custom Mobile Settings', 'hustle' ); ?></label>

				<label for="hustle-enable-mobile" class="sui-toggle">

					<input
						type="checkbox"
						id="hustle-enable-mobile"
						aria-labelledby="hustle-enable-mobile-label"
						data-attribute="enable_mobile_settings"
						<?php checked( $settings['enable_mobile_settings'], '1' ); ?>
					/>

					<span class="sui-toggle-slider" aria-hidden="true"></span>

				</label>

			</div>

			<!-- <div class="sui-form-field hui-device-selection" data-orientation="horizontal">

				<label for="hustle-device-select" id="hustle-device-select-label" class="sui-label">
					<span class="sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_html_e( 'You can adjust the appearance of your pop-up for both desktop and mobile by switching between them here.', 'hustle' ); ?>">
						<i class="sui-icon-info" aria-hidden="true"></i>
					</span>
					<?php esc_html_e( 'Device', 'hustle' ); ?>
				</label>

				<select id="hustle-device-select" class="sui-width-100 sui-height-30" aria-labelledby="hustle-device-select-label">
					<option value="appearance-desktop"><?php esc_html_e( 'Desktop', 'hustle' ); ?></option>
					<option value="appearance-mobiles"><?php esc_html_e( 'Mobile', 'hustle' ); ?></option>
				</select>

			</div> -->

		</div>

	</div>

	<div class="sui-box-body">

		<?php
		self::$dont_init_selects = true;
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'id'      => 'hustle-device_settings-tabs',
				'name'    => 'device_settings',
				'flushed' => true,
				'content' => true,
				'options' => array(
					'desktop' => array(
						'label'   => esc_html__( 'Desktop', 'hustle' ),
						'content' => $this->render( 'admin/commons/sui-wizard/tab-appearance/device-desktop', $args, true ),
					),
					'mobile'  => array(
						'label'   => esc_html__( 'Mobile', 'hustle' ),
						'content' => $this->render( 'admin/commons/sui-wizard/tab-appearance/device-mobiles', $args, true ),
					),
				),
			)
		);
		self::$dont_init_selects = false;
		?>

	</div>

	<div class="sui-box-footer">

		<button class="sui-button wpmudev-button-navigation" data-direction="prev">
			<span class="sui-icon-arrow-left" aria-hidden="true"></span> <?php echo $is_optin ? esc_html__( 'Integrations', 'hustle' ) : esc_html__( 'Content', 'hustle' ); ?>
		</button>

		<div class="sui-actions-right">

			<button class="sui-button sui-button-icon-right wpmudev-button-navigation" data-direction="next">
				<?php echo 'embedded' === $module_type ? esc_html_e( 'Display Options', 'hustle' ) : esc_html_e( 'Visibility', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>
