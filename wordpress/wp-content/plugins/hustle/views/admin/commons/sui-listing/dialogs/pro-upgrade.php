<?php
/**
 * Modal for promoting the upgrade from Hustle Free to Hustle Pro.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-modal sui-modal-lg">

	<div
		role="dialog"
		id="hustle-modal--upgrade-to-pro"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="dialogTitle"
		aria-describedby="dialogDescription"
	>

		<div class="sui-box" role="document">

			<div class="sui-box-header">

				<h3 id="dialogTitle" class="sui-box-title"><?php esc_html_e( 'Upgrade to Pro', 'hustle' ); ?></h3>

				<div class="sui-actions-right" aria-hidden="true">
					<button class="sui-button-icon hustle-modal-close" data-modal-close>
						<i class="sui-icon-close sui-md" aria-hidden="true"></i>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this modal', 'hustle' ); ?></span>
					</button>
				</div>

			</div>

			<div class="sui-box-body sui-box-body-slim">

				<p id="dialogDescription"><?php esc_html_e( 'Here’s what you’ll get by upgrading to Hustle Pro.', 'hustle' ); ?></p>

				<p class="hustle-upsell-benefit"><i class="sui-icon-check sui-lg" aria-hidden="true"></i> <strong><?php esc_html_e( 'Unlimited modules', 'hustle' ); ?></strong><br>
					<span class="sui-description"><?php esc_html_e( 'Create unlimited pop-ups, slide-ins, embeds, and social sharing modules with Hustle Pro. You can run any number of marketing campaigns on your website and generate more leads.', 'hustle' ); ?></span>
				</p>
				<p class="hustle-upsell-benefit"><i class="sui-icon-check sui-lg" aria-hidden="true"></i> <strong><?php esc_html_e( 'Smush Pro and Hummingbird Pro - the ultimate site optimization package', 'hustle' ); ?></strong><br>
					<span class="sui-description"><?php esc_html_e( 'Smush’s award-winning image optimization + Hummingbird’s performance optimization gives you the fastest possible WordPress site that your customers will love.', 'hustle' ); ?></span>
				</p>
				<p class="hustle-upsell-benefit"><i class="sui-icon-check sui-lg" aria-hidden="true"></i> <strong><?php esc_html_e( '24/7 live WordPress support', 'hustle' ); ?></strong><br>
					<span class="sui-description"><?php esc_html_e( 'We can’t stress this enough: Our outstanding WordPress support is available with live chat 24/7, and we’ll help you with absolutely any WordPress issue – not just our products. ', 'hustle' ); ?></span>
				</p>
				<p class="hustle-upsell-benefit"><i class="sui-icon-check sui-lg" aria-hidden="true"></i> <strong><?php esc_html_e( 'Everything WPMU DEV', 'hustle' ); ?></strong><br>
					<span class="sui-description"><?php esc_html_e( 'Additionally, WPMU DEV membership comes with other premium plugins ranging from marketing and SEO to performance and security, the Hub to manage unlimited websites, and our WPMU DEV guarantee.', 'hustle' ); ?></span>
				</p>
			</div>

			<div class="sui-box-footer sui-flatten sui-content-center">

				<a
					target="_blank"
					id="hustle-button--upgrade-to-pro"
					href="<?php echo esc_url( Opt_In_Utils::get_link( 'plugin', 'hustle_modal_upsell_notice' ) ); ?>"
					class="sui-button sui-button-purple"
				>
					<?php esc_html_e( 'Try Pro for Free Today!', 'hustle' ); ?>
				</a>

			</div>

			<img
				src="<?php echo esc_url( self::$plugin_url . 'assets/images/dev-team.png' ); ?>"
				srcset="<?php echo esc_url( self::$plugin_url . 'assets/images/dev-team.png' ); ?> 1x, <?php echo esc_url( self::$plugin_url . 'assets/images/dev-team@2x.png' ); ?> 2x"
				alt="<?php esc_html_e( 'Upgrade to Hustle Pro!', 'hustle' ); ?>"
				class="sui-image sui-image-center"
				aria-hidden="true"
			/>

		</div>

	</div>

</div>