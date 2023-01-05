<?php
/**
 * Desktop appearance settings.
 *
 * @uses ./rows/layout
 * @uses ./rows/customize-elements
 * @uses ./rows/advanced
 * @uses ./rows/custom-css
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<div id="hustle-wizard-appearance-desktop">

<!--<div id="hustle-wizard-appearance-desktop" class="hui-preload-settings sui-active">

	<div class="hui-preload-preloader sui-active" data-preload-notice="<?php esc_html_e( 'Loading desktop appearance settings in a moment…', 'hustle' ); ?>" aria-live="assertive"></div>

	<div class="hui-preload-content" aria-hidden="true">-->

		<?php
		// ROW: Layout.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/rows/layout',
			array(
				'is_optin'           => $is_optin,
				'layout'             => $is_optin ? $settings['form_layout'] : $settings['style'],
				'device'             => '',
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// ROW: Customize Elements.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/rows/customize-elements',
			array(
				'settings'           => $settings,
				'is_optin'           => $is_optin,
				'device'             => '',
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		// ROW: Typography.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/rows/typography',
			array(
				'settings'            => $settings,
				'is_optin'            => $is_optin,
				'device'              => '',
				'smallcaps_singular'  => $smallcaps_singular,
				'capitalize_singular' => $capitalize_singular,
			)
		);

		// ROW: Colors.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/rows/colors',
			array(
				'settings'            => $settings,
				'is_optin'            => $is_optin,
				'device'              => '',
				'module_type'         => $module_type,
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
				'device'              => '',
				'smallcaps_singular'  => $smallcaps_singular,
				'capitalize_singular' => $capitalize_singular,
			)
		);

		// ROW: Custom CSS.
		$this->render(
			'admin/commons/sui-wizard/tab-appearance/rows/custom-css',
			array(
				'settings'    => $settings,
				'is_optin'    => $is_optin,
				'module_type' => $module_type,
			)
		);
		?>

	<!--</div>-->

</div>