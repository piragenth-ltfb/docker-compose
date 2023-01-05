<?php
/**
 * List of the providers that are globally connected.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<?php if ( 0 === count( $providers ) ) : ?>

	<div class="sui-notice sui-notice-error">

		<div class="sui-notice-content">

			<div class="sui-notice-message">

				<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
				<p><?php esc_html_e( "You need at least one active app to send your opt-in's submissions to. If you don't want to use any 3rd party app, you can always use the Local Hustle List to save the submissions.", 'hustle' ); ?></p>

			</div>
		</div>
	</div>

<?php else : ?>

	<table class="sui-table hui-table--apps">

		<tbody>

			<?php foreach ( $providers as $provider ) : ?>

				<?php
				$this->render(
					'admin/integrations/integration-row',
					array(
						'provider' => $provider,
					)
				);
				?>

			<?php endforeach; ?>

		</tbody>

	</table>
<?php endif; ?>