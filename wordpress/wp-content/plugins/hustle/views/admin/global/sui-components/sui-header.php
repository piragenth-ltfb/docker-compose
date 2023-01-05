<?php
/**
 * SUI Header.
 *
 * @uses ./sui-icon
 *
 * @package Hustle
 * @since 4.3.0
 */

$hide_docs     = apply_filters( 'wpmudev_branding_hide_doc_link', false );
$notifications = ( isset( $notifications ) && ! empty( $notifications ) ) ? $notifications : true;
?>

<div class="sui-header">

	<h1 class="sui-header-title"><?php echo esc_html( $title ); ?></h1>

	<?php if ( ! empty( $docs_section ) && ! $hide_docs ) : ?>

		<div class="sui-actions-right">

			<a
				href="<?php echo esc_url( Opt_In_Utils::get_link( 'docs' ) . '#' . $docs_section ); ?>"
				target="_blank"
				class="sui-button sui-button-ghost"
			>
				<?php $this->render( 'admin/global/sui-components/sui-icon', array( 'icon' => 'academy' ) ); ?>
				<?php esc_html_e( 'View Documentation', 'hustle' ); ?>
			</a>

		</div>

	<?php endif; ?>

</div>

<?php
// Show floating notifications wrapper.
if ( $notifications ) {
	echo '<div id="hustle-floating-notifications-wrapper" class="sui-floating-notices"></div>';
}