<?php
/**
 * Modal for when migrating Aweber.
 *
 * @package Hustle
 * @since 4.1.1
 */

$aweber = Hustle_Aweber::get_instance();
?>

<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="hustle-dialog-migrate--aweber"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog-migrate--aweber-title"
		aria-describedby="hustle-dialog-migrate--aweber-description"
	>

		<div class="sui-box">

			<div class="sui-box-header sui-content-center sui-flatten sui-spacing-top--60">

				<button class="sui-button-icon sui-button-float--right" data-modal-close>
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
				</button>

				<figure class="sui-box-logo" aria-hidden="true">
					<img src="<?php echo esc_url( $aweber->get_logo_2x() ); ?>" alt="Aweber " />
				</figure>

				<h3 id="hustle-dialog-migrate--aweber-title" class="sui-box-title sui-lg"><?php esc_html_e( 'Migrate Aweber', 'hustle' ); ?></h3>

				<?php /* translators: 1. opening 'b' tag, 2. closing 'b' tag */ ?>
				<p id="hustle-dialog-migrate--aweber-description" class="sui-description"><?php printf( esc_html__( 'Click on the %1$s"Get authorization code"%2$s link to generate your authorization code and paste it below to re-authenticate your Aweber integration via oAuth 2.0.', 'hustle' ), '<b>', '</b>' ); ?></p>

			</div>

			<form class="sui-box-body sui-content-center sui-spacing-top--20">

				<div class="sui-form-field">

					<label for="reuth-aweber" id="label-reuth-aweber" class="sui-label">

						<?php esc_html_e( 'Authorization code', 'hustle' ); ?>

						<?php
						$api      = $aweber->get_api();
						$auth_url = $api->get_authorization_uri( 0, true, Hustle_Data::INTEGRATIONS_PAGE );

						if ( $auth_url ) :
						?>

							<a
								href="<?php echo esc_url( $auth_url ); ?>"
								target="_blank"
								class="sui-label-link hustle-aweber-migrate-link"
								style="color: #17A8E3;"
								data-id=""
							>
								<?php esc_html_e( 'Get authorization code', 'hustle' ); ?>
							</a>

						<?php endif; ?>

					</label>

					<input
						id="reuth-aweber"
						name="api_key"
						placeholder="<?php printf( esc_html__( 'Enter authorization code here', 'hustle' ) ); ?>"
						class="sui-form-control"
						aria-labelledby="label-reuth-aweber"
						aria-describedby="error-reuth-aweber"
					/>

					<span id="error-reuth-aweber" class="sui-error-message sui-hidden"><?php esc_html_e( 'Please enter a valid Aweber authorization code', 'hustle' ); ?></span>

				</div>

			</form>

			<div class="sui-box-footer sui-flatten sui-content-center">

				<a
					href="#"
					id="integration-migrate"
					class="hustle-aweber-migrate sui-button"
					data-id=""
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_provider_action' ) ); ?>"
				>
					<span class="sui-loading-text"><?php esc_html_e( 'Re-Authenticate', 'hustle' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</a>

			</div>

		</div>

	</div>

</div>