<?php
/**
 * Welcome dialog for fresh installs.
 *
 * @package Hustle
 * @since 4.0.0
 */

$banner_img_1x = self::$plugin_url . 'assets/images/release-highlight-header.png';
$banner_img_2x = self::$plugin_url . 'assets/images/release-highlight-header@2x.png';
?>

<div class="sui-modal sui-modal-md">

	<div
		role="dialog"
		id="hustle-dialog--release-highlight"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog--release-highlight-title"
		aria-describedby="hustle-dialog--release-highlight-description"
		data-name="<?php echo esc_attr( Hustle_Dashboard_Admin::HIGHLIGHT_MODAL_NAME ); ?>"
	>

		<div class="sui-box" style="margin-bottom: 10px;">

			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-right--30  sui-spacing-left--30">

				<button class="sui-button-icon sui-button-float--right hustle-modal-close" style="z-index: 2;" data-modal-close>
					<span class="sui-icon-close sui-md" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
				</button>

				<figure role="banner" class="sui-box-banner" aria-hidden="true">
				<?php echo $this->render_image_markup( $banner_img_1x, $banner_img_2x, 'sui-image sui-image-center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</figure>

				<?php /* translators: current user's name */ ?>
				<h3 id="hustle-dialog--release-highlight-title" class="sui-box-title sui-lg"><?php esc_html_e( 'New! Multiple Pop-up Triggers', 'hustle' ); ?></h3>

				<p id="hustle-dialog--release-highlight-description" class="sui-description">
					<?php esc_html_e( "In the newest Hustle 4.4.1 release, we've added the support for multiple pop-up triggers, so that you can choose two or more actions to trigger your pop-up. You can also set the respective delay durations for each trigger.", 'hustle' ); ?>
				</p>

				<p class="sui-description">
					<?php
					printf(
						/* translators: 1. opening 'b' tag, 2. closing 'b' tag. */
						esc_html__( 'We have also added %1$sValentine%2$s and %1$sChinese New Year%2$s Templates to boost your sales and leads during the Love season and the Chinese New Year festivities.', 'hustle' ),
						'<b>',
						'</b>'
					);
					?>
				</p>

			</div>

			<div class="sui-box-footer sui-flatten sui-content-center sui-spacing-bottom--50">

				<button id="hustle-release-highlight-action-button" class="sui-button" data-modal-close>
					<?php esc_html_e( 'Got It', 'hustle' ); ?>
				</button>

			</div>

		</div>

		<button class="sui-modal-skip" data-modal-close><?php esc_html_e( "I'll check this later", 'hustle' ); ?></button>

	</div>

</div>