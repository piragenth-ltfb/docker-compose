<?php
/**
 * Row for the integrations table for both global and wizard.
 *
 * @package Hustle
 * @since 4.0.0
 */

$empty_icon = self::$plugin_url . 'assets/images/hustle-empty-icon.png';

$module_id = isset( $module_id ) ? $module_id : 0;

$show_action = false;

$icon_class_action = 'sui-icon-plus';
$tooltip           = __( 'Configure Integration', 'hustle' );
$providers_action  = 'hustle_provider_settings';

$multi_id        = 0;
$global_multi_id = 0;
$multi_name      = false;

$advertising = false;

if ( ! empty( $module_id ) ) {

	// On wizards.
	$providers_action  = 'hustle_provider_form_settings';
	$show_action       = false;
	$icon_class_action = 'sui-icon-plus';

	if (
		isset( $provider['is_form_settings_available'] ) &&
		! empty( $provider['is_form_settings_available'] ) &&
		true === $provider['is_form_settings_available']
	) {

		$show_action = true;

		if ( $provider['is_allow_multi_on_form'] ) {

			if ( isset( $provider['multi_name'] ) ) {

				$icon_class_action = 'sui-icon-widget-settings-config';
				$tooltip           = __( 'Configure Integration', 'hustle' );
				$multi_id          = $provider['multi_id'];
				$multi_name        = $provider['multi_name'];

			} else {

				if ( isset( $provider['multi_id'] ) ) {
					$multi_id = $provider['multi_id'];
				}

				$icon_class_action = 'sui-icon-plus';
				$tooltip           = __( 'Add Integration', 'hustle' );

			}
		} else {

			if ( $provider['is_form_connected'] ) {

				$icon_class_action = 'sui-icon-widget-settings-config';
				$tooltip           = __( 'Configure Integration', 'hustle' );

			} else {

				$icon_class_action = 'sui-icon-plus';
				$tooltip           = __( 'Add Integration', 'hustle' );

			}
		}
	}
} else {

	// On integrations page.
	if (
		isset( $provider['is_settings_available'] ) &&
		! empty( $provider['is_settings_available'] ) &&
		true === $provider['is_settings_available']
	) {

		$show_action = true;

		if ( $provider['is_multi_on_global'] ) {

			if ( isset( $provider['multi_name'] ) ) {

				$icon_class_action = 'sui-icon-widget-settings-config';
				$tooltip           = __( 'Configure Integration', 'hustle' );
				$global_multi_id   = $provider['global_multi_id'];
				$multi_name        = $provider['multi_name'];

			} else {

				if ( isset( $provider['global_multi_id'] ) ) {
					$global_multi_id = $provider['global_multi_id'];
				}

				$icon_class_action = 'sui-icon-plus';
				$tooltip           = __( 'Add Integration', 'hustle' );

			}
		} else {

			if ( $provider['is_connected'] ) {

				$icon_class_action = 'sui-icon-widget-settings-config';
				$tooltip           = __( 'Configure Integration', 'hustle' );

			} else {

				$icon_class_action = 'sui-icon-plus';
				$tooltip           = __( 'Add Integration', 'hustle' );

				if ( 'zapier' === $provider['slug'] ) {
					$advertising = true;
				}
			}
		}
	}
} ?>

<tr
<?php
if ( true === $advertising ) {
	echo ' class="hui-app--promote"'; }
?>
>

	<td class="sui-table-item-title">

		<div class="hui-app--wrapper">

			<?php if ( true === $advertising ) { ?>

				<?php if ( ! empty( $provider['banner_1x'] ) || ! empty( $provider['banner_2x'] ) ) { ?>

					<div
						role="banner"
						class="hui-app--banner"
						data-app="<?php echo esc_attr( $provider['slug'] ); ?>"
						<?php ( ! empty( $provider['title'] ) ) ? '' : 'aria-hidden="true"'; ?>
					>

						<?php if ( ! empty( $provider['banner_1x'] ) && ! empty( $provider['banner_2x'] ) ) { ?>

							<img
								src="<?php echo esc_url( $provider['banner_1x'] ); ?>"
								srcset="<?php echo esc_url( $provider['banner_1x'] ); ?> 1x, <?php echo esc_url( $provider['banner_2x'] ); ?> 2x"
								alt="<?php echo esc_attr( $provider['title'] ); ?>"
								class="sui-image"
							/>

						<?php } else { ?>

							<?php
							$banner = '';

							if ( ! empty( $provider['banner_1x'] ) ) {
								$banner = $provider['banner_1x'];
							}

							if ( ! empty( $provider['banner_2x'] ) ) {
								$banner = $provider['banner_2x'];
							}
							?>

							<img
								src="<?php echo esc_url( $banner ); ?>"
								alt="<?php echo esc_attr( $provider['title'] ); ?>"
								class="sui-image"
							/>

						<?php } ?>

					</div>

				<?php } ?>

				<div class="hui-app--content">

					<div class="hui-app--title">

						<span><?php echo esc_html( $provider['title'] ) . ( ! empty( $provider['multi_name'] ) ? ' – ' . esc_html( $provider['multi_name'] ) : '' ); ?></span>

						<?php if ( ! empty( $provider['documentation_url'] ) ) { ?>
							<a href="<?php echo esc_url( $provider['documentation_url'] ); ?>" target="_blank"><?php esc_html_e( 'View Docs', 'hustle' ); ?></a>
						<?php } ?>

						<?php if ( $show_action ) : ?>

							<button class="sui-button-icon sui-tooltip sui-tooltip-top-right connect-integration"
								data-tooltip="<?php echo esc_html( $tooltip ); ?>"
								data-slug="<?php echo esc_attr( $provider['slug'] ); ?>"
								data-image="<?php echo esc_attr( $provider['logo_2x'] ); ?>"
								data-module_id="<?php echo esc_attr( $module_id ); ?>"
								data-multi_id="<?php echo esc_attr( $multi_id ); ?>"
								data-global_multi_id="<?php echo esc_attr( $global_multi_id ); ?>"
								data-action="<?php echo esc_attr( $providers_action ); ?>"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_provider_action' ) ); ?>">
								<span class="<?php echo esc_attr( $icon_class_action ); ?>" aria-hidden="true"></span>
							</button>

						<?php endif; ?>

					</div>

					<?php if ( ! empty( $provider['short_description'] ) ) { ?>
						<span class="hui-app--description"><?php echo wp_kses_post( $provider['short_description'] ); ?></span>
					<?php } ?>

				</div>

			<?php } else { ?>

				<?php
				if ( ! empty( $provider['icon_2x'] ) ) {
					echo $this->render_image_markup( $provider['icon_2x'], '', 'sui-image', '', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					echo '<span class="hui-noicon" aria-hidden="true">' . esc_html__( 'Icon', 'hustle' ) . '</span>';
				}
				?>

				<span><?php echo esc_html( $provider['title'] ) . ( ! empty( $provider['multi_name'] ) ? ' – ' . esc_html( $provider['multi_name'] ) : '' ); ?></span>

				<?php if ( $show_action ) : ?>

					<button class="sui-button-icon sui-tooltip sui-tooltip-top-right connect-integration"
						data-tooltip="<?php echo esc_html( $tooltip ); ?>"
						data-slug="<?php echo esc_attr( $provider['slug'] ); ?>"
						data-image="<?php echo esc_attr( $provider['logo_2x'] ); ?>"
						data-module_id="<?php echo esc_attr( $module_id ); ?>"
						data-multi_id="<?php echo esc_attr( $multi_id ); ?>"
						data-global_multi_id="<?php echo esc_attr( $global_multi_id ); ?>"
						data-action="<?php echo esc_attr( $providers_action ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_provider_action' ) ); ?>">
						<span class="<?php echo esc_attr( $icon_class_action ); ?>" aria-hidden="true"></span>
					</button>

				<?php endif; ?>

			<?php } ?>

		</div>

	</td>

</tr>