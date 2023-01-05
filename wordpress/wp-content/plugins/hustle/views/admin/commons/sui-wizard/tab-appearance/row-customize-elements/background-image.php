<?php
/**
 * Customize elements -> Background image fitting options.
 *
 * @uses ./background-image/background-size.php
 * @uses ./background-image/background-position.php
 * @uses ./background-image/background-repeat.php
 * @uses admin/global/sui-components/sui-settings-row
 *
 * @package Hustle
 * @version 4.3.0
 */

$args = array(
	'key'                => $key,
	'settings'           => $settings,
	'is_optin'           => $is_optin,
	'device'             => $device,
	'smallcaps_singular' => $smallcaps_singular,
);
?>
<div class="sui-box">

	<div class="sui-box-body">

		<?php
		// SETTINGS: Background size.
		$this->render(
			'admin/global/sui-components/sui-settings-row',
			array(
				'content' => $this->render(
					'admin/commons/sui-wizard/tab-appearance/row-customize-elements/background-image/background-size',
					$args,
					true
				),
			)
		);

		// SETTINGS: Background position.
		$this->render(
			'admin/global/sui-components/sui-settings-row',
			array(
				'content' => $this->render(
					'admin/commons/sui-wizard/tab-appearance/row-customize-elements/background-image/background-position',
					$args,
					true
				),
			)
		);

		// SETTINGS: Background repeat.
		$this->render(
			'admin/global/sui-components/sui-settings-row',
			array(
				'content' => $this->render(
					'admin/commons/sui-wizard/tab-appearance/row-customize-elements/background-image/background-repeat',
					$args,
					true
				),
			)
		);
		?>

	</div>

</div>