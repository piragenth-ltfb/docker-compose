<?php
/**
 * SUI Footer.
 *
 * @uses ./footer/cross-sell
 * @uses ./footer/navigation
 * @uses ./footer/social-media
 *
 * @package Hustle
 * @since 4.3.0
 */

$is_free = ( isset( $is_free ) && ! empty( $is_free ) ) ? $is_free : Opt_In_Utils::_is_free();

/* translators: heart icon */
$footer_text = sprintf( __( 'Made with %s by WPMU DEV', 'hustle' ), ' <span class="sui-icon-heart" aria-hidden="true"></span>' );

// TODO: Check if the user is member to apply these filters.
$hide_footer = apply_filters( 'wpmudev_branding_change_footer', false );
$footer_text = apply_filters( 'wpmudev_branding_footer_text', $footer_text );

// Display cross-sell row when it's free and the footer type is "large".
if ( $is_free && ! empty( $is_large ) && ! $hide_footer ) :
	$this->render( 'admin/global/sui-components/footer/cross-sell' );
endif;
?>

<div class="sui-footer"><?php echo $footer_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

<?php
if ( ! $hide_footer ) {
	// FOOTER: Navigation.
	$this->render( 'admin/global/sui-components/footer/navigation' );

	// FOOTER: Social.
	$this->render( 'admin/global/sui-components/footer/social' );
}