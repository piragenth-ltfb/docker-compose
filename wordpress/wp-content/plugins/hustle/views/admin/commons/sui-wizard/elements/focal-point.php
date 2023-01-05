<?php
/**
 * Unused file.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-focal">

	<div class="hustle-focal-point-position-item">

		<span class="sui-description"><?php esc_html_e( 'Adjust the position of your feature image within the image container.', 'hustle' ); ?></span>

		<div class="sui-focal-position-x">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Horizontal', 'hustle' ); ?></label>

				<div class="sui-side-tabs" style="margin-top: 5px;">

					<div class="sui-tabs-menu">

						<label
							for="hustle-in-container-image-positionX--left"
							class="sui-tab-item <?php echo 'left' === $settings['feature_image_horizontal'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_horizontal"
								data-attribute="feature_image_horizontal"
								value="left"
								id="hustle-in-container-image-positionX--left"
								<?php checked( $settings['feature_image_horizontal'], 'left' ); ?>
							/>
							<span class="hui-tab-icon-position-left" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Left', 'hustle' ); ?></span>
						</label>

						<label
							for="hustle-in-container-image-positionX--center"
							class="sui-tab-item <?php echo 'center' === $settings['feature_image_horizontal'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_horizontal"
								data-attribute="feature_image_horizontal"
								value="center"
								id="hustle-in-container-image-positionX--center"
								<?php checked( $settings['feature_image_horizontal'], 'center' ); ?>
							/>
							<span class="hui-tab-icon-position-center" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Center', 'hustle' ); ?></span>
						</label>

						<label
							for="hustle-in-container-image-positionX--right"
							class="sui-tab-item <?php echo 'right' === $settings['feature_image_horizontal'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_horizontal"
								data-attribute="feature_image_horizontal"
								value="right"
								id="hustle-in-container-image-positionX--right"
								<?php checked( $settings['feature_image_horizontal'], 'right' ); ?>
							/>
							<span class="hui-tab-icon-position-right" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Right', 'hustle' ); ?></span>
						</label>

						<label
							for="hustle-in-container-image-positionX--custom"
							class="sui-tab-item <?php echo 'custom' === $settings['feature_image_horizontal'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_horizontal"
								data-attribute="feature_image_horizontal"
								value="custom"
								id="hustle-in-container-image-positionX--custom"
								<?php checked( $settings['feature_image_horizontal'], 'custom' ); ?>
							/>
							<?php esc_html_e( 'Custom', 'hustle' ); ?>
						</label>

					</div>

				</div>

			</div>

			<div class="sui-form-field">

				<label class="sui-label" for="hustle-image-custom-position-horizontal">
					<span class="sui-label-note"><?php esc_html_e( 'In px', 'hustle' ); ?></span>
				</label>

				<input
					type="number"
					placeholder="E.g. 50"
					data-attribute="feature_image_horizontal_px"
					value="<?php echo esc_attr( $settings['feature_image_horizontal_px'] ); ?>"
					class="sui-form-control"
					id="hustle-image-custom-position-horizontal"
					<?php disabled( ( 'custom' !== $settings['feature_image_horizontal'] ) ); ?>
				/>

				<span class="sui-error-message" style="display: none;"><?php esc_html_e( 'Invalid', 'hustle' ); ?></span>

			</div>

		</div>

		<div class="sui-focal-position-y">

			<div class="sui-form-field">

				<label class="sui-label"><?php esc_html_e( 'Vertical', 'hustle' ); ?></label>

				<div class="sui-side-tabs" style="margin-top: 5px;">

					<div class="sui-tabs-menu">

						<label
							for="hustle-in-container-image-positionY--top"
							class="sui-tab-item <?php echo 'top' === $settings['feature_image_vertical'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_vertical"
								data-attribute="feature_image_vertical"
								value="top"
								id="hustle-in-container-image-positionY--top"
								<?php checked( $settings['feature_image_vertical'], 'top' ); ?>
							/>
							<span class="hui-tab-icon-position-top" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Top', 'hustle' ); ?></span>
						</label>

						<label
							for="hustle-in-container-image-positionY--middle"
							class="sui-tab-item <?php echo 'center' === $settings['feature_image_vertical'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_vertical"
								data-attribute="feature_image_vertical"
								value="center"
								id="hustle-in-container-image-positionY--middle"
								<?php checked( $settings['feature_image_vertical'], 'top' ); ?>
							/>
							<span class="hui-tab-icon-position-middle" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Middle', 'hustle' ); ?></span>
						</label>

						<label
							for="hustle-in-container-image-positionY--bottom"
							class="sui-tab-item <?php echo 'bottom' === $settings['feature_image_vertical'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_vertical"
								data-attribute="feature_image_vertical"
								value="bottom"
								id="hustle-in-container-image-positionY--bottom"
								<?php checked( $settings['feature_image_vertical'], 'bottom' ); ?>
							/>
							<span class="hui-tab-icon-position-bottom" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Bottom', 'hustle' ); ?></span>
						</label>

						<label
							for="hustle-in-container-image-positionY--custom"
							class="sui-tab-item <?php echo 'custom' === $settings['feature_image_vertical'] ? 'active' : ''; ?>"
						>
							<input
								type="radio"
								name="feature_image_vertical"
								data-attribute="feature_image_vertical"
								value="custom"
								id="hustle-in-container-image-positionY--custom"
								<?php checked( $settings['feature_image_vertical'], 'custom' ); ?>
							/>
							<?php esc_html_e( 'Custom', 'hustle' ); ?>
						</label>

					</div>

				</div>

			</div>

			<div class="sui-form-field">

				<label class="sui-label" for="hustle-image-custom-position-vertical">
					<span class="sui-label-note"><?php esc_html_e( 'In px', 'hustle' ); ?></span>
				</label>

				<input
					type="number"
					value="<?php echo esc_attr( $settings['feature_image_vertical_px'] ); ?>"
					data-attribute="feature_image_vertical_px"
					placeholder="E.g. 50"
					class="sui-form-control"
					id="hustle-image-custom-position-vertical"
					<?php disabled( ( 'custom' !== $settings['feature_image_vertical'] ) ); ?>
				/>

				<span class="sui-error-message" style="display: none;"><?php esc_html_e( 'Invalid', 'hustle' ); ?></span>

			</div>

		</div>

	</div>

</div>