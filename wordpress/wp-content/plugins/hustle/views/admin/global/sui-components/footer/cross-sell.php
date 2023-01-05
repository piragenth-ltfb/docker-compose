<?php
/**
 * Cross Sell Content.
 *
 * This content should be displayed on footer for free users only.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<div id="sui-cross-sell-footer" class="sui-row">

	<div aria-hidden="true"><span class="sui-icon-plugin-2"></span></div>

	<h3><?php esc_html_e( 'Check out our other free wordpress.org plugins!', 'hustle' ); ?></h3>

</div>

<div class="sui-row sui-cross-sell-modules">

	<div class="sui-col-md-4">

		<div class="sui-cross-1"><span></span></div>

		<div class="sui-box">

			<div class="sui-box-body">

				<h3><?php esc_html_e( 'Hummingbird Page Speed Optimization', 'hustle' ); ?></h3>

				<p><?php esc_html_e( 'Performance Tests, File Optimization & Compression, Page, Browser  & Gravatar Caching, GZIP Compression, CloudFlare Integration & more.', 'hustle' ); ?></p>

				<a
					href="https://wordpress.org/plugins/hummingbird-performance/"
					target="_blank"
					class="sui-button sui-button-ghost"
				>
					<?php esc_html_e( 'View features', 'hustle' ); ?>&nbsp;&nbsp;&nbsp;<span aria-hidden="true" class="sui-icon-arrow-right"></span>
				</a>

			</div>

		</div>

	</div>

	<div class="sui-col-md-4">

		<div class="sui-cross-2" aria-hidden="true"><span></span></div>

		<div class="sui-box">

			<div class="sui-box-body">

				<h3><?php esc_html_e( 'Defender Security, Monitoring, and Hack Protection', 'hustle' ); ?></h3>

				<p><?php esc_html_e( 'Security Tweaks & Recommendations, File & Malware Scanning, Login & 404 Lockout Protection, Two-Factor Authentication & more.', 'hustle' ); ?></p>

				<a
					href="https://wordpress.org/plugins/defender-security/"
					target="_blank"
					class="sui-button sui-button-ghost"
				>
					<?php esc_html_e( 'View features', 'hustle' ); ?>&nbsp;&nbsp;&nbsp;<span aria-hidden="true" class="sui-icon-arrow-right"></span>
				</a>

			</div>

		</div>

	</div>

	<div class="sui-col-md-4">

		<div class="sui-cross-3" aria-hidden="true"><span></span></div>

		<div class="sui-box">

			<div class="sui-box-body">

				<h3><?php esc_html_e( 'SmartCrawl Search Engine Optimization', 'hustle' ); ?></h3>

				<p><?php esc_html_e( 'Customize Titles & Meta Data, OpenGraph, Twitter & Pinterest Support, Auto-Keyword Linking, SEO & Readability Analysis, Sitemaps, URL Crawler & more.', 'hustle' ); ?></p>

				<a
					href="https://wordpress.org/plugins/smartcrawl-seo/"
					target="_blank"
					class="sui-button sui-button-ghost"
				>
					<?php esc_html_e( 'View features', 'hustle' ); ?>&nbsp;&nbsp;&nbsp;<span aria-hidden="true" class="sui-icon-arrow-right"></span>
				</a>

			</div>

		</div>

	</div>

</div>

<div class="sui-cross-sell-bottom">

	<h3><?php esc_html_e( 'Your All-in-One WordPress Platform', 'hustle' ); ?></h3>

	<p><?php esc_html_e( 'Pretty much everything you need for developing and managing WordPress based websites, and then some.', 'hustle' ); ?></p>

	<a
		href="<?php echo esc_url( Opt_In_Utils::get_link( 'wpmudev', 'hustle_footer_upsell_notice' ) ); ?>"
		rel="dialog"
		id="dash-uptime-update-membership"
		class="sui-button sui-button-green"
	>
		<?php esc_html_e( 'Learn more', 'hustle' ); ?>
	</a>

	<img
		class="sui-image"
		src="<?php echo esc_url( self::$plugin_url . 'assets/images/dev-team.png' ); ?>"
		srcset="<?php echo esc_url( self::$plugin_url . 'assets/images/dev-team.png' ); ?> 1x, <?php echo esc_url( self::$plugin_url . 'assets/images/dev-team@2x.png' ); ?> 2x"
		alt="<?php esc_html_e( 'Try pro features for free!', 'hustle' ); ?>"
		aria-hidden="true"
	>

</div>