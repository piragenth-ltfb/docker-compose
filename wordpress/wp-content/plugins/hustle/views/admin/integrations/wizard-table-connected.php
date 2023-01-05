<?php
/**
 * List of the integrations that are connected to the module.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<?php
if ( 0 === count( $providers ) ) :

	$notice_message = esc_html__( "You need at least one active app to send your opt-in's submissions to. If you don't want to use any 3rd party app, you can always use the Local Hustle List to save the submissions.", 'hustle' );
	$notice_options = array(
		array(
			'type'  => 'inline_notice',
			'class' => 'sui-notice-error',
			'icon'  => 'info',
			'value' => $notice_message,
		),
	);
	$this->get_html_for_options( $notice_options );

else :
	?>

<table class="sui-table hui-table--apps hui-connected" style="margin-bottom: 10px;">

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

<span class="sui-description"><?php esc_html_e( 'These applications are collecting data of your popup.', 'hustle' ); ?></span>

<?php endif; ?>