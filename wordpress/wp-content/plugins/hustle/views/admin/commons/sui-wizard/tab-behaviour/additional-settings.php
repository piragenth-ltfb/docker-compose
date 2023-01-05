<?php
/**
 * Additional settings section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Additional Settings', 'hustle' ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'These settings will add some extra control on your %s.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		if ( Hustle_Module_Model::POPUP_MODULE === $module_type ) :
			// SETTINGS: Allow page scrolling.
			?>
			<div class="sui-form-field">

				<label class="sui-settings-label"><?php esc_html_e( 'Page scrolling', 'hustle' ); ?></label>

				<?php /* translators: module type in small caps and in singular */ ?>
				<span class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( 'Choose whether to enable page scrolling in the background while the %s is visible to the users.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

				<?php
				$this->render(
					'admin/global/sui-components/sui-tabs',
					array(
						'name'        => 'allow_scroll_page',
						'radio'       => true,
						'saved_value' => $settings['allow_scroll_page'],
						'sidetabs'    => true,
						'content'     => false,
						'options'     => array(
							'1' => array(
								'value' => '1',
								'label' => __( 'Enable', 'hustle' ),
							),
							'0' => array(
								'value' => '0',
								'label' => __( 'Disable', 'hustle' ),
							),
						),
					)
				);
				?>

			</div>

		<?php endif; ?>

		<?php
		if ( $is_optin ) :
			// SETTINGS: Visibility after opt-in.
			?>
			<div class="sui-form-field">

				<label class="sui-settings-label"><?php esc_html_e( 'Visibility after opt-in', 'hustle' ); ?></label>

				<?php /* translators: module type in small caps and in singular */ ?>
				<span class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( "Choose the %s visibility once a visitor has opted-in Hustle's form.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

				<select class="sui-select" data-attribute="hide_after_subscription">
					<option value="keep_show" <?php selected( $settings['hide_after_subscription'], 'keep_show' ); ?>>
						<?php esc_html_e( 'Keep showing this module', 'hustle' ); ?>
					</option>
					<option value="no_show_all" <?php selected( $settings['hide_after_subscription'], 'no_show_all' ); ?>>
						<?php esc_html_e( 'No longer show this module across the site', 'hustle' ); ?>
					</option>
					<option value="no_show_on_post" <?php selected( $settings['hide_after_subscription'], 'no_show_on_post' ); ?>>
						<?php esc_html_e( 'No longer show this module on this post/page', 'hustle' ); ?>
					</option>
				</select>

			</div>

		<?php endif; ?>

		<?php // SETTINGS: Visibility after CTA conversion. ?>
		<div class="sui-form-field" data-toggle-content="show-cta">

			<label class="sui-settings-label"><?php esc_html_e( 'Visibility after CTA conversion', 'hustle' ); ?></label>

			<?php /* translators: module type in small caps and in singular */ ?>
			<span class="sui-description" style="margin-bottom: 10px;"><?php printf( esc_html__( 'Choose the %s visibility once a visitor has clicked on the CTA button.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

			<select class="sui-select" data-attribute="hide_after_cta">
				<option value="keep_show" <?php selected( $settings['hide_after_cta'], 'keep_show' ); ?>><?php esc_html_e( 'Keep showing this module', 'hustle' ); ?></option>
				<option value="no_show_all" <?php selected( $settings['hide_after_cta'], 'no_show_all' ); ?>><?php esc_html_e( 'No longer show this module across the site', 'hustle' ); ?></option>
				<option value="no_show_on_post" <?php selected( $settings['hide_after_cta'], 'no_show_on_post' ); ?>><?php esc_html_e( 'No longer show this module on this post/page', 'hustle' ); ?></option>
			</select>

		</div>

		<?php // SETTINGS: External form conversion behavior. ?>

		<div class="sui-form-field">

			<label class="sui-settings-label"><?php esc_html_e( 'External form conversion behavior', 'hustle' ); ?></label>

			<span class="sui-description"><?php printf( esc_html__( "If you have an external form in your %1\$s, choose how your %1\$s will behave on conversion of that form. Note that this doesn't affect your external form submission behavior.", 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>

			<div style="margin-top: 10px;">

				<div style="margin-bottom: 10px;">

					<select class="sui-select" data-attribute="on_submit" >

						<?php if ( 'embedded' !== $module_type ) { ?>
							<option value="close"
								<?php selected( $settings['on_submit'], 'close' ); ?>>
								<?php /* translators: module type in small caps and in singular */ ?>
								<?php printf( esc_html__( 'Close the %s', 'hustle' ), esc_html( $smallcaps_singular ) ); ?>
							</option>
						<?php } ?>

						<option value="redirect"
							<?php selected( $settings['on_submit'], 'redirect' ); ?>>
							<?php esc_html_e( 'Re-direct to form target URL', 'hustle' ); ?>
						</option>

						<option value="nothing"
							<?php selected( $settings['on_submit'], 'nothing' ); ?>>
							<?php esc_html_e( 'Do nothing (use for Ajax Forms)', 'hustle' ); ?>
						</option>

					</select>

				</div>

				<div id="hustle-on-submit-delay-wrapper" class="sui-border-frame <?php echo 'nothing' === $settings['on_submit'] ? 'sui-hidden' : ''; ?>">

					<label class="sui-label"><?php esc_html_e( 'Add delay', 'hustle' ); ?></label>

					<div class="sui-row">

						<div class="sui-col-md-6">

							<input
								type="number"
								value="<?php echo esc_attr( $settings['on_submit_delay'] ); ?>"
								min="0"
								class="sui-form-control"
								data-attribute="on_submit_delay"
							/>

						</div>

						<div class="sui-col-md-6">

							<select data-attribute="on_submit_delay_unit">

								<option
									value="seconds"
									<?php selected( $settings['on_submit_delay_unit'], 'seconds' ); ?>
								>
									<?php esc_html_e( 'seconds', 'hustle' ); ?>
								</option>

								<option
									value="minutes"
									<?php selected( $settings['on_submit_delay_unit'], 'minutes' ); ?>
								>
									<?php esc_html_e( 'minutes', 'hustle' ); ?>
								</option>

							</select>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>