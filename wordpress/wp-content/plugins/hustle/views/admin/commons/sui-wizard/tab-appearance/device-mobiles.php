<?php
/**
 * Mobiles appearance settings.
 *
 * @uses ./../../../global/sui-components/sui-notice
 * @uses ./rows/customize-elements
 * @uses ./rows/advanced
 *
 * @package Hustle
 * @since 4.3.0
 */

$device = 'mobile';

$notice = array(
	'alert'   => false,
	'type'    => 'info',
	'message' => esc_html__( 'Layout, Color Palette, Font Family from Typography settings, Vanilla Theme, and Custom CSS options are inherited from the Desktop settings.', 'hustle' ),
);

$vanilla_notice = array(
	'alert'   => false,
	'message' => esc_html__( 'There are no mobile settings available as you have enabled the "Vanilla theme" option under the desktop settings.', 'hustle' ),
);
?>

<div id="hustle-wizard-appearance-mobiles">

	<?php // ROW: Top notice. ?>

	<div data-toggle-content="use-vanilla">
		<?php $this->render( 'admin/global/sui-components/sui-notice', $notice ); ?>

		<hr aria-hidden="true" />
	</div>

	<?php
	// ROW: Customize Elements.
	$this->render(
		'admin/commons/sui-wizard/tab-appearance/rows/customize-elements',
		array(
			'settings'           => $settings,
			'is_optin'           => $is_optin,
			'device'             => $device,
			'smallcaps_singular' => $smallcaps_singular,
		)
	);
	?>

	<div data-toggle-content="not-use-vanilla">
		<?php $this->render( 'admin/global/sui-components/sui-notice', $vanilla_notice ); ?>
	</div>

	<?php
	// ROW: Typography.
	$this->render(
		'admin/commons/sui-wizard/tab-appearance/rows/typography',
		array(
			'settings'            => $settings,
			'is_optin'            => $is_optin,
			'device'              => $device,
			'smallcaps_singular'  => $smallcaps_singular,
			'capitalize_singular' => $capitalize_singular,
		)
	);

	// ROW: Advanced.
	$this->render(
		'admin/commons/sui-wizard/tab-appearance/rows/advanced',
		array(
			'settings'            => $settings,
			'is_optin'            => $is_optin,
			'device'              => $device,
			'smallcaps_singular'  => $smallcaps_singular,
			'capitalize_singular' => $capitalize_singular,
		)
	);
	?>

</div>