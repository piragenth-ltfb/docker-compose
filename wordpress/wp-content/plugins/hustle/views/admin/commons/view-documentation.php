<?php
/**
 * Title section.
 *
 * @package Hustle
 * @since 4.3.1
 */

$hide   = apply_filters( 'wpmudev_branding_hide_doc_link', false );
$unwrap = isset( $unwrap ) ? $unwrap : false;

if ( ! $hide ) : ?>

	<?php if ( $unwrap ) { ?>

		<a
			href="<?php echo esc_url( Opt_In_Utils::get_link( 'docs' ) . '#' . $docs_section ); ?>"
			target="_blank"
			class="sui-button sui-button-ghost"
		>
			<span class="sui-icon-academy" aria-hidden="true"></span> <?php esc_html_e( 'View Documentation', 'hustle' ); ?>
		</a>

	<?php } else { ?>

		<div class="sui-actions-right">
			<a
				href="https://wpmudev.com/docs/wpmu-dev-plugins/hustle/#<?php echo esc_attr( $docs_section ); ?>"
				target="_blank"
				class="sui-button sui-button-ghost"
			>
				<span class="sui-icon-academy" aria-hidden="true"></span> <?php esc_html_e( 'View Documentation', 'hustle' ); ?>
			</a>
		</div>

	<?php } ?>

<?php endif; ?>