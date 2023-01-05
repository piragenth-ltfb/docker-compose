<?php
/**
 * Modal for when migrating ConstantContact.
 * We're not using this yet.
 *
 * @package Hustle
 * @since 4.0.3
 */

$ctct = Hustle_ConstantContact::get_instance();
$api  = $ctct->api();
?>

<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="hustle-dialog-migrate--constantcontact"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog-migrate--constantcontact-title"
		aria-describedby="hustle-dialog-migrate--constantcontact-description"
	>

		<div class="sui-box">

			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">

				<button class="sui-button-icon sui-button-float--right" data-modal-close>
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
				</button>

				<figure class="sui-box-logo" aria-hidden="true">
					<img src="<?php echo esc_url( $ctct->get_logo_2x() ); ?>" alt="">
				</figure>

				<h3 id="hustle-dialog-migrate--constantcontact-title" class="sui-box-title sui-lg"><?php esc_html_e( 'Migrate Constant Contact?', 'hustle' ); ?></h3>

			</div>

			<div class="sui-box-body sui-content-center sui-spacing-top--20">

				<p id="hustle-dialog-migrate--constantcontact-description" class="sui-description">
					<?php esc_html_e( 'Click on the re-authenticate button below and authorize Hustle to retrieve access tokens for v3.0 API to update your integration to the latest API version.', 'hustle' ); ?>
				</p>

			</div>
			<?php
			if ( method_exists( $api, 'get_migrate_authorization_uri' ) ) :
				?>
				<div class="sui-box-footer sui-flatten sui-content-center">
					<a class="sui-button hustle-ctct-migrate" href="<?php echo esc_url( $api->get_migrate_authorization_uri() ); ?>">
						<span class="sui-loading-text"><?php esc_html_e( 'Re-Authenticate', 'hustle' ); ?></span>
						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
					</a>
				</div>
			<?php else : ?>
				<div class="sui-box-footer sui-flatten sui-content-center">

					<button type="button" class="sui-button hustle-ctct-migrate hustle-onload-icon-action"><?php esc_html_e( 'Re-Authenticate', 'hustle' ); ?>

						<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>

					</button>

				</div>
			<?php endif; ?>
		</div>

	</div>

</div>