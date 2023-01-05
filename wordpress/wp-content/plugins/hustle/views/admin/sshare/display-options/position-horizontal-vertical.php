<?php
/**
 * Positioning - Horizontal and Vertical sections.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>
<div class="sui-form-field">

	<?php // SETTINGS: Horizontal Position. ?>
	<?php if ( 'inline' !== $prefix ) : ?>

		<label class="sui-settings-label"><?php esc_html_e( 'Horizontal Position', 'hustle' ); ?></label>
		<span class="sui-description"><?php esc_html_e( 'Choose the horizontal position of the Floating Social.', 'hustle' ); ?></span>

	<?php else : ?>

		<label class="sui-settings-label"><?php esc_html_e( 'Position', 'hustle' ); ?></label>
		<span class="sui-description"><?php esc_html_e( 'Choose the position for the Floating Social.', 'hustle' ); ?></span>

	<?php endif; ?>

	<?php if ( ! empty( $positions ) ) : ?>

		<div style="margin-top: 10px;">

			<?php foreach ( $positions as $pkey => $position ) : ?>

				<label
					for="hustle-position-<?php echo esc_html( $prefix ); ?>-<?php echo esc_html( $pkey ); ?>"
					class="sui-radio-image"
				>

					<?php
					echo $this->render_image_markup( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						self::$plugin_url . 'assets/images/' . $position['image1x'],
						self::$plugin_url . 'assets/images/' . $position['image2x']
					);
					?>

					<span class="sui-radio">
						<input
							type="radio"
							name="<?php echo esc_html( $prefix ); ?>_position"
							data-attribute="<?php echo esc_html( $prefix ); ?>_position"
							value="<?php echo esc_html( $pkey ); ?>"
							id="hustle-position-<?php echo esc_html( $prefix ); ?>-<?php echo esc_html( $pkey ); ?>"
							<?php checked( $settings[ $prefix . '_position' ], $pkey ); ?>
						/>
						<span aria-hidden="true"></span>
						<span><?php echo esc_html( $position['label'] ); ?></span>
					</span>

				</label>

			<?php endforeach; ?>

		</div>

	<?php endif; ?>

</div>

<?php // SETTINGS: Vertical Position. ?>
<?php if ( isset( $offset_y ) && ( true === $offset_y ) ) : ?>

	<div class="sui-form-field">

		<label class="sui-settings-label"><?php esc_html_e( 'Vertical Position', 'hustle' ); ?></label>
		<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose the vertical position of the Floating Social.', 'hustle' ); ?></span>

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'        => $prefix . '_position_y',
				'radio'       => true,
				'saved_value' => $settings[ $prefix . '_position_y' ],
				'sidetabs'    => true,
				'content'     => false,
				'options'     => array(
					'top'    => array(
						'value' => 'top',
						'label' => __( 'Top', 'hustle' ),
					),
					'bottom' => array(
						'value' => 'bottom',
						'label' => __( 'Bottom', 'hustle' ),
					),
				),
			)
		);
		?>

	</div>

<?php endif; ?>