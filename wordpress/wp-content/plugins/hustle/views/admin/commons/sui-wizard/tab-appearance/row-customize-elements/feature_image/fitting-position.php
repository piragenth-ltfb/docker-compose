<?php
/**
 * Customize elements -> Feature image fitting options.
 *
 * @uses ../../../elements/image-fitting
 *
 * @package Hustle
 * @version 4.3.0
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-2">

		<h5 class="sui-settings-label sui-dark" style="font-size: 13px;"><?php esc_html_e( 'Fitting & Position', 'hustle' ); ?></h5>

		<p class="sui-description"><?php esc_html_e( 'Choose the image fitting option for your feature image and adjust its position.', 'hustle' ); ?></p>

		<?php
		$this->render(
			'admin/commons/sui-wizard/elements/image-fitting',
			array(
				'key'      => $key,
				'device'   => $device,
				'settings' => $settings,
			)
		);
		?>

	</div>

</div>