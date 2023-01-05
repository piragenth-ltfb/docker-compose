<?php
/**
 * Upsell box for free users.
 *
 * @uses ./../../global/sui-components/sui-box-header
 *
 * @package Hustle
 * @since 4.3
 */

$header_args = array(
	'title'   => esc_html__( 'Hustle Pro', 'hustle' ),
	'icon'    => 'hustle',
	'pro_tag' => true,
);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sui-box hui-box-upsell">

	<?php $this->render( 'admin/global/sui-components/sui-box-header', $header_args ); ?>

	<div class="sui-box-body">

		<p><?php esc_html_e( 'Get Hustle Pro, our full lineup of WordPress marketing tools and more for free when you start your WPMU DEV membership.', 'hustle' ); ?></p>

		<ul>
			<li><span class="sui-icon-check sui-lg" aria-hidden="true"></span><?php esc_html_e( 'Unlimited Pop-ups, Slide-ins, Embeds, and Social Shares', 'hustle' ); ?></li>
			<li><span class="sui-icon-check sui-lg" aria-hidden="true"></span><?php esc_html_e( 'Smush Pro and Hummingbird Pro - the ultimate site optimization & performance package', 'hustle' ); ?></li>
			<li><span class="sui-icon-check sui-lg" aria-hidden="true"></span><?php esc_html_e( 'Full marketing suite including pro drag-and-drop form, poll and quiz builder, customizable analytics dashboards and WordPress white labeler.', 'hustle' ); ?></li>
			<li><span class="sui-icon-check sui-lg" aria-hidden="true"></span><?php esc_html_e( 'Manage unlimited WordPress sites from the Hub', 'hustle' ); ?></li>
			<li><span class="sui-icon-check sui-lg" aria-hidden="true"></span><?php esc_html_e( '24/7 live WordPress support', 'hustle' ); ?></li>
			<li><span class="sui-icon-check sui-lg" aria-hidden="true"></span><?php esc_html_e( 'The WPMU DEV Guarantee', 'hustle' ); ?></li>
		</ul>

		<p><a href="<?php echo esc_url( Opt_In_Utils::get_link( 'plugin', 'hustle_dashboard_upsellwidget_button' ) . '#choosing-a-template' ); ?>" class="sui-button sui-button-purple" target="_blank"><?php esc_html_e( 'Try Pro for FREE today!', 'hustle' ); ?></a></p>

	</div>

</div>