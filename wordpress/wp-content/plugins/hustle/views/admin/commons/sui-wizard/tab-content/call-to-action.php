<?php
/**
 * CTA section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Call to Action', 'hustle' ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( 'Add a call to action button on your pop-up to take your visitors to another web page on your site or any other site.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-show-cta" class="sui-toggle hustle-toggle-with-container" data-toggle-on="show-cta">
				<input type="checkbox"
					id="hustle-show-cta"
					aria-labelledby="hustle-show-cta-label"
					data-attribute="show_cta"
					<?php checked( $settings['show_cta'], '1' ); ?>
				/>
				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-show-cta-label" class="sui-toggle-label"><?php esc_html_e( 'Add Call to Action', 'hustle' ); ?></span>
			</label>


			<div class="sui-border-frame sui-toggle-content" data-toggle-content="show-cta">

				<div class="sui-row">

					<div class="sui-col-md-6">

						<div class="sui-form-field">

							<label for="hustle-cta-label" class="sui-label"><?php esc_html_e( 'Button label', 'hustle' ); ?></label>
							<input type="text"
								name="cta_label"
								value="<?php echo esc_attr( $settings['cta_label'] ); ?>"
								placeholder="<?php esc_attr_e( 'Vote Now', 'hustle' ); ?>"
								id="hustle-cta-label"
								class="sui-form-control"
								data-attribute="cta_label" />
							<span class="sui-error-message" style="display: none;"><?php esc_html_e( "You can't have a button without text.", 'hustle' ); ?></span>

						</div>

					</div>

					<div class="sui-col-md-6">

						<div class="sui-form-field">

							<label class="sui-label"><?php esc_html_e( 'Open link in', 'hustle' ); ?></label>

							<?php
							$this->render(
								'admin/global/sui-components/sui-tabs',
								array(
									'name'        => 'cta_target',
									'radio'       => true,
									'saved_value' => $settings['cta_target'],
									'sidetabs'    => true,
									'content'     => false,
									'options'     => array(
										'blank' => array(
											'value' => 'blank',
											'label' => __( 'New Tab', 'hustle' ),
										),
										'self'  => array(
											'value' => 'self',
											'label' => __( 'Same Tab', 'hustle' ),
										),
									),
								)
							);
							?>

						</div>

					</div>

				</div>

				<div class="sui-form-field">

					<label for="hustle-cta-url" class="sui-label"><?php esc_html_e( 'Redirect URL', 'hustle' ); ?></label>

					<input type="url"
						name="cta_url"
						value="<?php echo esc_attr( $settings['cta_url'] ); ?>"
						placeholder="<?php esc_attr_e( 'E.g. https://website.com', 'hustle' ); ?>"
						id="hustle-cta-url"
						class="sui-form-control"
						data-attribute="cta_url" />

					<span class="sui-error" style="display: none;"><?php esc_html_e( "That's not a valid URL. Please, try again.", 'hustle' ); ?></span>

				</div>

			</div>

		</div>

	</div>

</div>