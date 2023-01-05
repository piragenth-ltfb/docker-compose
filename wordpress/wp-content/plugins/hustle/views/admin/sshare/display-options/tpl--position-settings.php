<?php
/**
 * Floating positioning section.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-form-field">

	<label for="hustle-settings--<?php echo esc_attr( $prefix ); ?>-enable" class="sui-toggle hustle-toggle-with-container" data-toggle-on="<?php echo esc_attr( $prefix ); ?>-enabled">
		<input
			type="checkbox"
			name="<?php echo esc_html( $prefix ); ?>_enabled"
			data-attribute="<?php echo esc_html( $prefix ); ?>_enabled"
			id="hustle-settings--<?php echo esc_html( $prefix ); ?>-enable"
			aria-labelledby="hustle-settings--<?php echo esc_html( $prefix ); ?>-enable-label"
			<?php checked( $settings[ $prefix . '_enabled' ], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<?php /* translators: position label */ ?>
		<span id="hustle-settings--<?php echo esc_html( $prefix ); ?>-enable-label" class="sui-toggle-label"><?php printf( esc_html__( 'Enable %s', 'hustle' ), esc_html( $label ) ); ?></span>
	</label>

	<div class="sui-toggle-content" data-toggle-content="<?php echo esc_attr( $prefix ); ?>-enabled">

		<span class="sui-description"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

		<div class="sui-border-frame">

			<?php
			// SETTINGS: Horizontal and Vertical Position.
			$this->render(
				'admin/sshare/display-options/position-horizontal-vertical',
				array(
					'prefix'    => $prefix,
					'settings'  => $settings,
					'offset_x'  => $offset_x,
					'offset_y'  => $offset_y,
					'positions' => $positions,
				)
			);
			?>

			<?php
			// SETTINGS: Offset.
			if ( ! empty( $offset_x ) || ! empty( $offset_y ) ) :
				$this->render(
					'admin/sshare/display-options/position-offset',
					array(
						'prefix'   => $prefix,
						'settings' => $settings,
					)
				);
			endif;
			?>

			<?php // SETTINGS: Alignment. ?>
			<?php if ( ! empty( $alignment ) ) : ?>

				<div class="sui-form-field">

					<label class="sui-settings-label"><?php esc_html_e( 'Alignment', 'hustle' ); ?></label>
					<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'You can choose between Left align, Middle or Right align. For example, choosing the left align will push the social bar to the left of the parent container.', 'hustle' ); ?></span>

					<?php
					$this->render(
						'admin/global/sui-components/sui-tabs',
						array(
							'name'        => $prefix . '_align',
							'radio'       => true,
							'saved_value' => $settings[ $prefix . '_align' ],
							'sidetabs'    => true,
							'content'     => false,
							'options'     => array(
								'left'   => array(
									'value'     => 'left',
									'label'     => __( 'Left', 'hustle' ),
									'sui-icon'  => 'align-left',
									'icon-size' => 'md',
								),
								'center' => array(
									'value'     => 'center',
									'label'     => __( 'Center', 'hustle' ),
									'sui-icon'  => 'align-center',
									'icon-size' => 'md',
								),
								'right'  => array(
									'value'     => 'right',
									'label'     => __( 'Right', 'hustle' ),
									'sui-icon'  => 'align-right',
									'icon-size' => 'md',
								),
							),
						)
					);
					?>

				</div>

			<?php endif; ?>

		</div>

	</div>

</div>