<?php
/**
 * Dialog for reviewing conditions from 4.0.x to 4.1.0 and over.
 *
 * @package Hustle
 * @since 4.0.0
 */

$banner_1x    = esc_url( self::$plugin_url . 'assets/images/review-condition.png' );
$banner_2x    = esc_url( self::$plugin_url . 'assets/images/review-condition@2x.png' );
$banner_class = 'sui-image sui-image-center';

$version     = Opt_In_Utils::_is_free() ? '7.1' : '4.1';
$support_url = Opt_In_Utils::_is_free() ? 'https://wordpress.org/support/plugin/wordpress-popup/' : 'https://wpmudev.com/hub/support/#wpmud-chat-pre-survey-modal';
?>
<div class="sui-modal sui-modal-md">

	<div
		role="dialog"
		id="hustle-dialog--review_conditions"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="hustle-dialog--review_conditions-title"
		aria-describedby="hustle-dialog--review_conditions-description"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_dismiss_notification' ) ); ?>"
	>

		<div class="sui-box">

			<div class="sui-box-header sui-flatten sui-content-center">

				<button class="sui-button-icon sui-button-white sui-button-float--right" data-modal-close>
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this dialog window', 'hustle' ); ?></span>
				</button>

				<figure class="sui-box-banner" role="banner" aria-hidden="true">
					<?php echo $this->render_image_markup( $banner_1x, $banner_2x, $banner_class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</figure>

				<h3 id="hustle-dialog--review_conditions-title" class="sui-box-title sui-lg"><?php esc_html_e( "We've fixed visibility conditions!", 'hustle' ); ?></h3>

				<?php /* translators: current version */ ?>
				<p id="hustle-dialog--review_conditions-description" class="sui-description"><?php printf( esc_html__( "Prior to Hustle 4.1, the visibility engine would require you to set rules for each and every post type your theme used, not just the ones you specified. We've updated this behaviour to only display modules based on the post types explicitly defined in your conditions.", 'hustle' ), esc_attr( $version ) ); ?></p>

			</div>

			<div class="sui-box-body">

				<h4 style="margin: 0 0 5px; font-size: 13px; line-height: 22px;"><?php esc_html_e( 'Examples', 'hustle' ); ?></h4>

				<p class="sui-description" style="margin-bottom: 20px;"><?php esc_html_e( 'Let\'s take a couple of examples of "Pages" condition to understand how visibility behavior has changed with this bug fix:', 'hustle' ); ?></p>

				<p class="sui-description" style="margin-bottom: 10px;">
					<?php
					printf(
						/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. opening 'u' tag, 4. closing 'u' tag, 5. line-break 'br' tag, 6. current version */
						esc_html__( '%1$s1. %3$sPages -> Only 2%4$s%2$s%5$sIn %6$s, your module with the above condition will appear on the two selected pages only. Whereas, before %1$s, it would have appeared on the two chosen pages and other post types (such as posts, categories, tags) as well, unless you individually add a condition to not show your module on them.', 'hustle' ),
						'<strong color="#666666">',
						'</strong>',
						'<u>',
						'</u>',
						'<br>',
						esc_attr( $version )
					);
					?>
				</p>

				<p class="sui-description" style="margin-bottom: 30px;">
					<?php
					printf(
						/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. opening 'u' tag, 4. closing 'u' tag, 5. line-break 'br' tag, 6. current version */
						esc_html__( '%1$s2. %3$sPages -> All except 2%4$s%2$s%5$sIn %6$s, your module will appear on all pages except the two selected pages, and it won\'t appear on other post types such as posts, categories, or tags unless you explicitly add a condition for them. Whereas, before %1$s, this would have appeared across your website except on the two selected pages.', 'hustle' ),
						'<strong color="#666666">',
						'</strong>',
						'<u>',
						'</u>',
						'<br>',
						esc_attr( $version )
					);
					?>
				</p>

				<h4 style="margin: 0 0 5px; font-size: 13px; line-height: 22px;"><?php esc_html_e( 'Recommended Actions', 'hustle' ); ?></h4>

				<p class="sui-description" style="margin: 0 0 5px;"><?php esc_html_e( '1. Review all your active modules\' visibility behavior to ensure that they appear on correct pages.', 'hustle' ); ?></p>

				<?php /* translators: 1. opening 'a' tag to support, 2. closing 'a' tag to support */ ?>
				<p class="sui-description" style="margin: 0 0 5px;"><?php printf( esc_html__( '2. Unable to make the visibility conditions work correctly? %1$sContact Support%2$s.' ), '<a href="' . esc_url( $support_url ) . '" target="_blank">', '</a>' ); ?></p>

				<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag, 3. v4.0.4 or v7.0.4 */ ?>
				<p class="sui-description" style="margin: 0;"><?php printf( esc_html__( '3. Not yet ready for the new visibility behavior? Go to the Plugins page and use the "%1$sRollback to %3$s%2$s" link below Hustle to downgrade Hustle to %3$s', 'hustle' ), '<strong color="#666666">', '</strong>', Opt_In_Utils::_is_free() ? 'v7.0.4' : 'v4.0.4' ); ?></p>

			</div>

			<div class="sui-box-footer sui-flatten sui-content-right">

				<button class="sui-button hustle-review-conditions-dismiss" data-modal-close><?php esc_html_e( 'Review Modules', 'hustle' ); ?></button>

			</div>

		</div>

		<button class="sui-modal-skip hustle-review-conditions-dismiss" data-modal-close><?php esc_html_e( 'I\'ll check this later', 'hustle' ); ?></button>

	</div>

</div>