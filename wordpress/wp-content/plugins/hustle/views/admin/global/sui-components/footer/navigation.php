<?php
/**
 * Footer links.
 *
 * @package Hustle
 * @since 4.0.0
 */

if ( Opt_In_Utils::_is_free() ) { ?>

	<ul class="sui-footer-nav">
		<li><a href="https://profiles.wordpress.org/wpmudev#content-plugins" target="_blank"><?php esc_html_e( 'Free Plugins', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/features/" target="_blank"><?php esc_html_e( 'Membership', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/roadmap/" target="_blank"><?php esc_html_e( 'Roadmap', 'hustle' ); ?></a></li>
		<li><a href="https://wordpress.org/support/plugin/wordpress-popup" target="_blank"><?php esc_html_e( 'Support', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/docs/" target="_blank"><?php esc_html_e( 'Docs', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/hub-welcome/" target="_blank"><?php esc_html_e( 'The Hub', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/terms-of-service/" target="_blank"><?php esc_html_e( 'Terms of Service', 'hustle' ); ?></a></li>
		<li><a href="https://incsub.com/privacy-policy/" target="_blank"><?php esc_html_e( 'Privacy Policy', 'hustle' ); ?></a></li>
	</ul>

<?php } else { ?>

	<ul class="sui-footer-nav">
		<li><a href="https://wpmudev.com/hub2/" target="_blank"><?php esc_html_e( 'The Hub', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/projects/category/plugins/" target="_blank"><?php esc_html_e( 'Plugins', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/roadmap/" target="_blank"><?php esc_html_e( 'Roadmap', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/hub/support/" target="_blank"><?php esc_html_e( 'Support', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/docs/" target="_blank"><?php esc_html_e( 'Docs', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/hub2/community/" target="_blank"><?php esc_html_e( 'Community', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/academy/" target="_blank"><?php esc_html_e( 'Academy', 'hustle' ); ?></a></li>
		<li><a href="https://wpmudev.com/terms-of-service/" target="_blank"><?php esc_html_e( 'Terms of Service', 'hustle' ); ?></a></li>
		<li><a href="https://incsub.com/privacy-policy/" target="_blank"><?php esc_html_e( 'Privacy Policy', 'hustle' ); ?></a></li>
	</ul>

<?php } ?>