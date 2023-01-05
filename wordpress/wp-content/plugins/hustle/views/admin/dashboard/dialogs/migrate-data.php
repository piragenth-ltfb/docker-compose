<?php
/**
 * Dialog for migrating tracking from 3.x to 4.x.
 *
 * @package Hustle
 * @since 4.0.0
 */

$slide_one_1x = self::$plugin_url . 'assets/images/onboard-welcome.png';
$slide_one_2x = self::$plugin_url . 'assets/images/onboard-welcome@2x.png';

$slide_two_1x = self::$plugin_url . 'assets/images/onboard-migrate.png';
$slide_two_2x = self::$plugin_url . 'assets/images/onboard-migrate@2x.png';

$slide_three_1x = self::$plugin_url . 'assets/images/onboard-create.png';
$slide_three_2x = self::$plugin_url . 'assets/images/onboard-create@2x.png';

$is_first_time_opening = empty( filter_input( INPUT_GET, 'show-migrate', FILTER_VALIDATE_BOOLEAN ) );
$support_link          = Opt_In_Utils::get_link( 'support' );

$user     = wp_get_current_user();
$username = ! empty( $user->user_firstname ) ? $user->user_firstname : $user->user_login;
?>

<div class="sui-modal sui-modal-md">

	<div
		role="dialog"
		id="hustle-dialog--migrate"
		class="sui-modal-content"
		aria-modal="true"
		aria-label="<?php esc_html_e( 'Modal for migrating your existing tracking data to the latest Hustle version.', 'hustle' ); ?>"
		aria-live="polite"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_dismiss_notification' ) ); ?>"
		data-is-first="<?php echo $is_first_time_opening ? '1' : '0'; ?>"
	>

		<?php // SLIDE 1: Welcome. ?>
		<div id="hustle-dialog--migrate-slide-1" class="sui-modal-slide">

			<div class="sui-box" role="document">

				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

					<?php if ( ! $this->is_branding_hidden ) : ?>
						<figure class="sui-box-banner" role="banner" aria-hidden="true">
							<?php echo $this->render_image_markup( $slide_one_1x, $slide_one_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</figure>
					<?php endif; ?>

					<h3 class="sui-box-title sui-lg">
						<?php /* translators: username. */ ?>
						<?php printf( esc_html__( 'Hey, %s', 'hustle' ), esc_html( $username ) ); ?>
					</h3>

					<p class="sui-description"><?php esc_html_e( "Welcome to Hustle, the only plugin you'll ever need to turn your visitors into loyal subscribers, leads and customers.", 'hustle' ); ?></p>

				</div>

				<div class="sui-box-body sui-content-center sui-spacing-bottom--60">

					<button
						id="hustle-migrate-get-started"
						class="sui-button sui-button-blue sui-button-icon-right"
						data-modal-slide="hustle-dialog--migrate-slide-2"
						data-modal-slide-focus="hustle-migrate-start"
						data-modal-slide-intro="next"
					>
						<?php esc_html_e( 'Get Started', 'hustle' ); ?>
						<span class="sui-icon-chevron-right" aria-hidden="true"></span>
					</button>

				</div>

			</div>

		</div>

		<?php // SLIDE 2: Migrate. ?>
		<div id="hustle-dialog--migrate-slide-2" class="sui-modal-slide">

			<div class="sui-box" role="document">

				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

					<figure class="sui-box-banner" role="banner" aria-hidden="true">
						<?php echo $this->render_image_markup( $slide_two_1x, $slide_two_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</figure>

					<h3
						id="hustle-dialog--migrate-slide-2-title"
						class="sui-box-title sui-lg"
						data-done-text="<?php esc_html_e( 'Migration complete', 'hustle' ); ?>"
					>
						<?php esc_html_e( 'Migrate Data', 'hustle' ); ?>
					</h3>

					<p
						id="hustle-dialog--migrate-slide-2-description"
						class="sui-description"
						style="margin-bottom: 0;"
						data-default-text="<?php esc_html_e( 'Nice work on updating the Hustle! All your modules are already in place. However, you need to migrate the data of your existing modules such as tracking data and email list manually.', 'hustle' ); ?>"
						data-migrate-text="<?php esc_html_e( 'Data migration is in progress. It can take anywhere from a few seconds to a couple of hours depending upon the data of your existing modules and traffic on your site.', 'hustle' ); ?>"
						data-done-text="<?php esc_html_e( "We've successfully migrated your existing data. You're good to continue using Hustle!", 'hustle' ); ?>"
					>
						<?php esc_html_e( 'Nice work on updating the Hustle! All your modules are already in place. However, you need to migrate the data of your existing modules such as tracking data and email list manually.', 'hustle' ); ?>
					</p>

				</div>


				<div class="sui-box-body sui-content-center sui-spacing-bottom--60" data-migrate-start>

					<button
						id="hustle-migrate-start"
						class="sui-button sui-button-icon-right"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle-migrate-tracking-and-subscriptions' ) ); ?>"
					>
						<span class="sui-loading-text">
							<?php esc_html_e( 'Begin Migration', 'hustle' ); ?>
							<span class="sui-icon-chevron-right" aria-hidden="true"></span>
						</span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>

				</div>

				<div class="sui-box-body sui-content-center sui-spacing-bottom--60" style="display:none;" aria-hidden="true" hidden data-migrate-progress>

					<div class="sui-progress-block">

						<div class="sui-progress">

							<span class="sui-progress-icon" aria-hidden="true">
								<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
							</span>

							<span class="sui-progress-text">
								<span>0%</span>
							</span>

							<div class="sui-progress-bar" aria-hidden="true">
								<span style="width: 0%"></span>
							</div>

						</div>

					</div>

					<div class="sui-progress-state">
						<?php /* translators: html tags. */ ?>
						<span><?php printf( esc_html__( 'Rows migrated: %1$s%3$s/%2$s%3$s' ), '<span id="hustle-partial-rows" style="display: inline;">', '<span id="hustle-total-rows" style="display: inline;">', '</span>' ); ?></span>
					</div>

				</div>

				<div class="sui-box-body sui-content-center" style="display:none;" aria-hidden="true" hidden data-migrate-failed>

					<?php
					$notice_options = array(
						array(
							'type'       => 'inline_notice',
							'id'         => 'hustle-dialog--migrate-error-notice',
							'value'      => '',
							'attributes' => array(
								/* translators: 1. opening 'a' tag to support link, 2. closing 'a' tag */
								'data-message' => sprintf( esc_html__( 'There was an error while migrating your data. Please retry again or contact our %1$ssupport%2$s team for help.', 'hustle' ), '<a href="' . esc_url( $support_link ) . '" target="_blank">', '</a>' ),
							),
						),
					);
					$this->get_html_for_options( $notice_options );
					?>

					<button
						id="hustle-migrate-start"
						class="sui-button sui-button-icon-right"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle-migrate-tracking-and-subscriptions' ) ); ?>"
					>
						<span class="sui-loading-text">
							<?php esc_html_e( 'Retry Migration', 'hustle' ); ?>
						</span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>

					<span class="sui-description" style="margin: 10px 0 0;"><?php esc_html_e( 'The migration will continue from where it failed in the last attempt.', 'hustle' ); ?></span>

				</div>

				<div class="sui-box-body sui-content-center sui-spacing-bottom--60" style="display:none;" aria-hidden="true" hidden data-migrate-done>

					<button
						class="sui-button"
						data-modal-slide="hustle-dialog--migrate-slide-3"
						data-modal-slide-focus="hustle-new-popup"
						data-modal-slide-intro="next"
					>
						<?php esc_html_e( 'Continue', 'hustle' ); ?>
					</button>

				</div>

			</div>

			<button class="sui-modal-skip hustle-dialog-migrate-skip" data-modal-close><?php esc_html_e( "Skip this, I'll migrate data later", 'hustle' ); ?></button>

		</div>

		<?php // SLIDE 3: Create. ?>
		<div id="hustle-dialog--migrate-slide-3" class="sui-modal-slide">

			<div class="sui-box" role="document">

				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

					<button class="sui-button-icon sui-button-float--right" data-modal-close>
						<span class="sui-icon-close sui-md" aria-hidden="true"></span>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this modal', 'hustle' ); ?></span>
					</button>

					<figure class="sui-box-banner" role="banner" aria-hidden="true">
						<?php echo $this->render_image_markup( $slide_three_1x, $slide_three_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</figure>

					<h3 class="sui-box-title sui-lg"><?php esc_html_e( 'Create Module', 'hustle' ); ?></h3>

					<span id="dialogDescription" class="sui-description"><?php esc_html_e( 'Choose a module to get started on converting your visitors into subscribers, generate more leads and grow your social following.', 'hustle' ); ?></span>

				</div>

				<div class="sui-box-selectors sui-box-selectors-col-2">

					<ul>

						<li><label for="hustle-new-popup" class="sui-box-selector">
							<input type="radio" name="hustle-create-new" id="hustle-new-popup" value="<?php echo esc_attr( Hustle_Module_Model::POPUP_MODULE ); ?>" />
							<span>
								<span class="sui-icon-popup" aria-hidden="true"></span>
								<?php esc_html_e( 'Pop-up', 'hustle' ); ?>
							</span>
						</label></li>

						<li><label for="hustle-new-slidein" class="sui-box-selector">
							<input type="radio" name="hustle-create-new" id="hustle-new-slidein" value="<?php echo esc_attr( Hustle_Module_Model::SLIDEIN_MODULE ); ?>" />
							<span>
								<span class="sui-icon-slide-in" aria-hidden="true"></span>
								<?php esc_html_e( 'Slide-in', 'hustle' ); ?>
							</span>
						</label></li>

						<li><label for="hustle-new-embed" class="sui-box-selector">
							<input type="radio" name="hustle-create-new" id="hustle-new-embed" value="<?php echo esc_attr( Hustle_Module_Model::EMBEDDED_MODULE ); ?>" />
							<span>
								<span class="sui-icon-embed" aria-hidden="true"></span>
								<?php esc_html_e( 'Embed', 'hustle' ); ?>
							</span>
						</label></li>

						<li><label for="hustle-new-sshare" class="sui-box-selector">
							<input type="radio" name="hustle-create-new" id="hustle-new-sshare" value="<?php echo esc_attr( Hustle_Module_Model::SOCIAL_SHARING_MODULE ); ?>" />
							<span>
								<span class="sui-icon-share" aria-hidden="true"></span>
								<?php esc_html_e( 'Social Share', 'hustle' ); ?>
							</span>
						</label></li>

					</ul>

				</div>

				<div class="sui-box-body sui-content-center sui-spacing-bottom--60 sui-spacing-top--0">

					<button
						id="hustle-create-new-module"
						class="sui-button sui-button-blue sui-button-icon-right"
						disabled="disabled"
					>
						<span class="sui-loading-text"><?php esc_html_e( 'Create', 'hustle' ); ?></span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</button>

				</div>

			</div>

			<button class="sui-modal-skip" data-modal-close><?php esc_html_e( "Skip this, I'll create a module later", 'hustle' ); ?></button>

		</div>

	</div>

</div>