<div class="sui-box">

	<div class="sui-box-body">

		<?php
		// ROW: Position.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/' . $key . '/position',
			array(
				'key'                => $key,
				'settings'           => $settings,
				'is_optin'           => $is_optin,
				'device'             => $device,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// ROW: Alignment.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/' . $key . '/alignment',
			array(
				'key'                => $key,
				'settings'           => $settings,
				'is_optin'           => $is_optin,
				'device'             => $device,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// ROW: Icon Style.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/' . $key . '/icon-style',
			array(
				'key'                => $key,
				'settings'           => $settings,
				'is_optin'           => $is_optin,
				'device'             => $device,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);
		?>

	</div>

</div>