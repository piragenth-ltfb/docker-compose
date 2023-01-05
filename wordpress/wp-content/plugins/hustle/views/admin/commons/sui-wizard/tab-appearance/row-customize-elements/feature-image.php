<?php
/**
 * Markup the Feature Image options under the Customize Elements section for both mobile and desktop.
 *
 * @since 4.3.0
 * @package Hustle
 */

?>
<?php
	// Template args.
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
		if ( ! $device ) {

			// ROW: Alignment.
			$this->render(
				'admin/commons/sui-wizard/tab-appearance/row-customize-elements/feature_image/alignment',
				$args
			);
		} else {

			// Mobile section.
			// ROW: Visibility.
			$this->render(
				'admin/commons/sui-wizard/tab-appearance/row-customize-elements/feature_image/visibility',
				$args
			);
		}

		// ROW: Size.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/feature_image/size',
			$args
		);

		// ROW: Fitting.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/row-customize-elements/feature_image/fitting-position',
			$args
		);
		?>

	</div>

</div>