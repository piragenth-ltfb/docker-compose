<?php
/**
 * Palette tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div id="palettes-box" class="sui-box" data-tab="palettes" <?php echo 'palettes' !== $section ? 'style="display: none;"' : ''; ?>>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Color Palettes', 'hustle' ); ?></h2>
	</div>

	<div class="sui-box-body">

		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-1">
				<span class="sui-settings-label"><?php esc_html_e( 'Custom Color Palettes', 'hustle' ); ?></span>
				<span class="sui-description"><?php esc_html_e( 'Create custom color palettes and apply them directly on your pop-ups, slide-ins, and embeds.', 'hustle' ); ?></span>
			</div>

			<div class="sui-box-settings-col-2">

				<label class="sui-label"><?php esc_html_e( 'Custom Palettes', 'hustle' ); ?></label>

				<?php if ( ! empty( $palettes ) ) : ?>

					<ul id="hustle-palettes-container" class="hui-palette-list">

						<?php foreach ( $palettes as $slug => $data ) : ?>
							<li>

								<span class="sui-icon-paint-bucket hui-palette-icon" aria-hidden="true"></span>

								<span class="hui-palette-name" aria-hidden="true"><?php echo esc_attr( $data['name'] ); ?></span>

								<button
									class="hustle-create-palette sui-button-icon sui-tooltip"
									data-slug="<?php echo esc_attr( $slug ); ?>"
									data-name="<?php echo esc_attr( $data['name'] ); ?>"
									data-hustle-action="go-to-step"
									data-step="2"
									data-tooltip="<?php esc_attr_e( 'Edit Palette', 'hustle' ); ?>"
								>
									<span class="sui-loading-text">
										<span class="sui-icon-pencil" aria-hidden="true"></span>
									</span>
									<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
									<span class="sui-screen-reader-text"><?php echo esc_html( $data['name'] ); ?></span>
								</button>

								<button
									class="sui-button-icon sui-button-red sui-tooltip hustle-delete-button"
									data-id="<?php echo esc_attr( $slug ); ?>"
									data-title="<?php esc_attr_e( 'Delete Color Palette', 'hustle' ); ?>"
									<?php /* translators: palette name */ ?>
									data-description="<?php printf( esc_html__( 'Are you sure you want to delete the %s color palette permanently? Note that the modules using this color palette will fallback to the default color palette.', 'hustle' ), esc_attr( $data['name'] ) ); ?>"
									data-tooltip="<?php esc_attr_e( 'Delete Palette', 'hustle' ); ?>"
								>
									<span class="sui-icon-trash" aria-hidden="true"></span>
									<span class="sui-screen-reader-text"><?php echo esc_attr( $data['name'] ); ?></span>
								</button>

							</li>

						<?php endforeach; ?>

					</ul>

				<?php else : ?>

					<?php
					$this->get_html_for_options(
						array(
							array(
								'type'  => 'inline_notice',
								'icon'  => 'info',
								/* translators: 1: opening 'strong' tag, 2: closing 'strong' tag */
								'value' => sprintf( esc_html__( 'You have not created any custom color palette yet. Click on the %1$s“Create Custom Palette”%2$s button to create your first custom palette.', 'hustle' ), '<strong>', '</strong>' ),
							),
						)
					);
					?>

				<?php endif; ?>

				<button class="hustle-create-palette sui-button sui-button-ghost">
					<span class="sui-icon-plus" aria-hidden="true"></span> <?php esc_html_e( 'Create custom palette', 'hustle' ); ?>
				</button>

			</div>

		</div>

	</div>

</div>