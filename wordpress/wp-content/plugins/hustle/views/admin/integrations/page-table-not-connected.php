<?php
/**
 * List of the providers that aren't globally connected.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
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