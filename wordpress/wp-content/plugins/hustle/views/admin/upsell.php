<?php
/**
 * Hustle PRO upgrade page.
 *
 * @since 4.3.0
 * @package Hustle
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="sui-upgrade-page">
	<div class="sui-upgrade-page-header">
		<div class="sui-upgrade-page__container">
			<div class="sui-upgrade-page-header__content">
				<h1><?php esc_html_e( 'Upgrade to Hustle Pro', 'hustle' ); ?></h1>
				<?php /* translators: 1. opening 'strong' tag, 2. closing 'strong' tag */ ?>
				<p><?php printf( esc_html__( 'Build %1$sunlimited%2$s pop-ups, slide-ins, embeds, and social sharing modules with Hustle Pro. No limits on your marketing efforts = more leads and increased sales. Nice!', 'hustle' ), '<strong>', '</strong>' ); ?></p>
				<p><?php esc_html_e( 'Plus – you’ll get WPMU DEV membership, which includes our award-winning Smush Pro plugin for image optimization, 24/7 live WordPress support, and unlimited usage of all our premium plugins.', 'hustle' ); ?></p>
				<a href="<?php echo esc_url( Opt_In_Utils::get_link( 'plugin', 'hustle_propage_topbutton' ) ); ?>" class="sui-button sui-button-lg sui-button-purple" target="_blank">
					<?php esc_html_e( 'Try Hustle Pro for Free Today', 'hustle' ); ?>
				</a>
				<div class="sui-reviews">
					<span class="sui-reviews__stars"></span>
					<div class="sui-reviews__rating"><span class="sui-reviews-rating">-</span> / <?php esc_html_e( '5.0 rating from', 'hustle' ); ?> <span class="sui-reviews-customer-count">-</span> <?php esc_html_e( 'customers', 'hustle' ); ?></div>
					<a class="sui-reviews__link" href="https://www.reviews.io/company-reviews/store/wpmudev-org" target="_blank">
						Reviews.io<i class="sui-icon-arrow-right" aria-hidden="true"></i>
					</a>
				</div>
			</div>

			<div class="sui-upgrade-page-header__image"></div>
		</div>
	</div>
	<div class="sui-upgrade-page-features">
		<div class="sui-upgrade-page-features__header">
			<h2><?php esc_html_e( 'Pro Features', 'hustle' ); ?></h2>
			<p><?php esc_html_e( 'Upgrading to Pro will get you the following benefits.', 'hustle' ); ?></p>
		</div>
	</div>
	<div class="sui-upgrade-page__container">
		<div class="sui-upgrade-page-features__items">
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-unlock" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Unlimited modules', 'hustle' ); ?></h3>
				<p><?php esc_html_e( 'Hustle Pro allows you to create unlimited pop-ups, slide-ins, embeds, and social sharing modules. You can run any number of marketing campaigns on your website and generate more leads.', 'hustle' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-photo-picture" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'White label plugin branding', 'hustle' ); ?></h3>
				<p><?php esc_html_e( 'Even though we love our superhero branding, but it’s not for everyone. With Pro membership, you can remove our superhero branding or replace it with your own.', 'hustle' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-smush" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Smush Pro and Hummingbird Pro - the ultimate site optimization & performance package', 'hustle' ); ?></h3>
				<p><?php esc_html_e( 'Smush’s award-winning image optimization + Hummingbird’s performance optimization gives you the fastest possible WordPress site. It’s a powerful combination that your visitors, customers, and search engines will love.', 'hustle' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-gdpr" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Premium WordPress plugins', 'hustle' ); ?></h3>
				<p><?php esc_html_e( 'In addition to Hustle Pro, you’ll get our full suite of premium WordPress plugins, making sure from Security to Backups to Marketing and SEO you’ve got all the WordPress solutions you can need. You get unlimited usage on unlimited sites and can join the millions using our plugins.', 'hustle' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-hub" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'The Hub - Manage unlimited WordPress sites', 'hustle' ); ?></h3>
				<p><?php esc_html_e( 'You can manage unlimited WordPress sites with automated updates, backups, security, and performance checks, all in one place. All of this can be white labeled for your clients, and you even get our 24/7 live WordPress support.', 'hustle' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-help-support" aria-hidden="true"></i>
				<h3><?php esc_html_e( '24/7 live WordPress support', 'hustle' ); ?></h3>
				<p><?php esc_html_e( 'We can’t stress this enough: our outstanding WordPress support is available with live chat 24/7, and we’ll help you with absolutely any WordPress issue – not just our products. It’s an expert WordPress team on call for you, whenever you need them.', 'hustle' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-wpmudev-logo" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'The WPMU DEV Guarantee', 'hustle' ); ?></h3>
				<p><?php esc_html_e( "You'll be delighted with Hustle Pro and everything else included in your membership 😁 You can trial the plugin first with a WPMU DEV Membership, and if you continue but change your mind, you can cancel any time.", 'hustle' ); ?></p>
			</div>
		</div>
	</div>
	<div class="sui-upgrade-page-cta">
		<div class="sui-upgrade-page-cta__inner">
			<h2><?php esc_html_e( 'Join 771,093 Happy Members', 'hustle' ); ?></h2>
			<p><?php esc_html_e( "97% of customers are happy with WPMU DEV's service, and it’s a great time to join them: as a Hustle user you’ll get a free trial period, so you can see what all the fuss is about. ", 'hustle' ); ?></p>
			<a href="<?php echo esc_url( Opt_In_Utils::get_link( 'plugin', 'hustle_propage_bottombutton' ) ); ?>" class="sui-button sui-button-lg sui-button-purple" target="_blank">
				<?php esc_html_e( 'Get Hustle Pro and get a better WordPress', 'hustle' ); ?>
			</a>
			<button type="button" class="sui-button sui-button-lg sui-button-purple sui-hidden-desktop">
				<?php esc_html_e( 'Get Hustle Pro and Get a Better WordPress', 'hustle' ); ?>
			</button>
			<a href="<?php echo esc_url( Opt_In_Utils::get_link( 'plugin', 'hustle_propage_bottombutton' ) ); ?>" target="_blank">
				<?php esc_html_e( 'Try Pro for Free Today', 'hustle' ); ?>
			</a>
		</div>
	</div>
</div>

<?php
	// Global Footer.
	$this->render( 'admin/footer/footer' );
?>