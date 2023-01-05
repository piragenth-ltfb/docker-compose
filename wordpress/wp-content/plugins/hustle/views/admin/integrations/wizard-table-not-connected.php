<?php
/**
 * List of available integrations (already connected globally) to connect the module to.
 *
 * @package Hustle
 * @since 4.0.0
 */

if ( 0 === count( $providers ) ) :

	$module_type       = Hustle_Module_Model::get_module_type_by_module_id( $module_id );
	$display_type_name = Opt_In_Utils::get_module_type_display_name( $module_type );

	if ( current_user_can( 'hustle_edit_integrations' ) ) {
		$integrations_url    = add_query_arg( 'page', Hustle_Data::INTEGRATIONS_PAGE, 'admin.php' );
		$empty_providers_msg = sprintf(
			/* translators: 1. opening 'a' tag to the global integrations page, 2. closing 'a' tag */
			esc_html__( 'Connect to more third party apps via %1$sIntegrations%2$s page and activate them to collect the data of this %3$s here.', 'hustle' ),
			'<a href="' . esc_url( $integrations_url ) . '">',
			'</a>',
			esc_html( $display_type_name )
		);
	} else {
		$empty_providers_msg = sprintf(
			/* translators: module type in small caps and singular */
			esc_html__( 'Ask your site admin to connect more third-party apps to activate them for this %s', 'hustle' ),
			esc_html( $display_type_name )
		);
	}

	$notice_options = array(
		array(
			'type'  => 'inline_notice',
			'icon'  => 'info',
			'value' => $empty_providers_msg,
		),
	);
	$this->get_html_for_options( $notice_options );

else :
	?>

	<table class="sui-table hui-table--apps" style="margin-bottom: 10px;">

		<tbody>

			<?php foreach ( $providers as $provider ) : ?>

				<?php
				$this->render(
					'admin/integrations/integration-row',
					array(
						'provider'  => $provider,
						'module_id' => $module_id,
					)
				);
				?>

			<?php endforeach; ?>

		</tbody>

	</table>

	<span class="sui-description"><?php esc_html_e( 'You are connected to these applications via their APIs.', 'hustle' ); ?></span>

<?php endif; ?>