<?php
/**
 * Welcome dialog for fresh installs.
 *
 * @package Hustle
 * @since 4.0.0
 */

$slide_one_1x = self::$plugin_url . 'assets/images/onboard-welcome.png';
$slide_one_2x = self::$plugin_url . 'assets/images/onboard-welcome@2x.png';

$slide_two_1x = self::$plugin_url . 'assets/images/onboard-welcome.png';
$slide_two_2x = self::$plugin_url . 'assets/images/onboard-welcome@2x.png';

$user     = wp_get_current_user();
$username = ! empty( $user->user_firstname ) ? $user->user_firstname : $user->user_login;
?>

<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="hustle-dialog--welcome"
		class="sui-modal-content"
		aria-modal="true"
		aria-live="polite"
		aria-label="<?php esc_html_e( 'Welcome to Hustle.', 'hustle' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_dismiss_notification' ) ); ?>"
	>

		<div
			id="hustle-dialog--welcome-first"
			class="sui-modal-slide sui-active"
			data-modal-size="md"
		>

			<div class="sui-box">

				<div class="sui-box-header sui-flatten sui-content-center">

					<figure class="sui-box-banner" role="banner" aria-hidden="true">

						<?php
						if ( ! $this->is_branding_hidden ) :
							echo $this->render_image_markup( $slide_one_1x, $slide_one_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						endif;
						?>
					</figure>

					<button class="sui-button-icon sui-button-float--right hustle-button-dismiss-welcome" data-modal-close>
						<span class="sui-icon-close sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
					</button>

					<?php /* translators: current user's name */ ?>
					<h3 class="sui-box-title sui-lg"><?php printf( esc_html__( 'Hey, %s', 'hustle' ), esc_html( $username ) ); ?></h3>

					<p class="sui-description"><?php esc_html_e( "Welcome to Hustle, the only plugin you'll ever need to turn your visitors into loyal subscribers, leads and customers.", 'hustle' ); ?></p>

				</div>

				<div class="sui-box-body sui-content-center sui-spacing-bottom--60">

					<button
						id="getStarted"
						class="sui-button sui-button-blue sui-button-icon-right"
						data-modal-slide="hustle-dialog--welcome-second"
					>
						<?php esc_html_e( 'Get Started', 'hustle' ); ?>
						<span class="sui-icon-chevron-right" aria-hidden="true"></span>
					</button>

				</div>

			</div>

			<button class="sui-modal-skip" data-modal-close><?php esc_html_e( 'Skip this, I know my way around', 'hustle' ); ?></button>

		</div>

		<div
			id="hustle-dialog--welcome-second"
			class="sui-modal-slide sui-active"
			data-modal-size="md"
		>

			<div class="sui-box">

				<div class="sui-box-header sui-flatten sui-content-center">

					<figure class="sui-box-banner" role="banner" aria-hidden="true">
						<?php
						if ( ! $this->is_branding_hidden ) :
							echo $this->render_image_markup( $slide_two_1x, $slide_two_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						endif;
						?>
					</figure>

					<button class="sui-button-icon sui-button-float--left" data-modal-slide="hustle-dialog--welcome-first">
						<span class="sui-icon-chevron-left sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Return to previous slide', 'hustle' ); ?></span>
					</button>

					<button class="sui-button-icon sui-button-float--right hustle-button-dismiss-welcome" data-modal-close>
						<span class="sui-icon-close sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
					</button>

					<h3 id="dialogTitle" class="sui-box-title sui-lg"><?php esc_html_e( 'Create Module', 'hustle' ); ?></h3>

					<p class="sui-description"><?php esc_html_e( 'Choose a module to get started on converting your visitors into subscribers, generate more leads and grow your social following.', 'hustle' ); ?></p>

				</div>

				<div class="sui-box-selectors sui-box-selectors-col-2">

					<ul>
						<?php
							$module_types = array(
								'popup'          => array(
									'name' => __( 'Pop-up', 'hustle' ),
									'icon' => 'popup',
								),
								'slidein'        => array(
									'name' => __( 'Slide-in', 'hustle' ),
									'icon' => 'slide-in',
								),
								'embedded'       => array(
									'name' => __( 'Embed', 'hustle' ),
									'icon' => 'embed',
								),
								'social_sharing' => array(
									'name' => __( 'Social Share', 'hustle' ),
									'icon' => 'share',
								),
							);

							foreach ( $module_types as $key => $attr ) {
								?>

						<li><label for="hustle-create-<?php echo esc_attr( $key ); ?>" class="sui-box-selector">
							<input type="radio" name="hustle-create-welcome" id="hustle-create-<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>">
							<span>
								<span class="sui-icon-<?php echo esc_attr( $attr['icon'] ); ?>" aria-hidden="true"></span>
								<?php echo esc_html( $attr['name'] ); ?>
							</span>
						</label></li>

								<?php
							}
							?>

					</ul>

				</div>

				<div class="sui-box-body sui-content-center sui-spacing-bottom--60 sui-spacing-top--0">

					<button id="hustle-new-create-module" class="sui-button sui-button-blue sui-button-icon-right" disabled="disabled">
						<span class="sui-loading-text"><?php esc_html_e( 'Create', 'hustle' ); ?></span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>

				</div>

			</div>

			<button class="sui-modal-skip" data-modal-close><?php esc_html_e( "Skip this, I'll create a module later", 'hustle' ); ?></button>

		</div>

	</div>

</div>