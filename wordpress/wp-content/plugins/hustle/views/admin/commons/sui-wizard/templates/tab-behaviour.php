<?php
/**
 * Main wrapper for the 'Behavior' tab.
 *
 * @uses ../tab-behaviour/
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box" <?php echo 'behavior' !== $section ? 'style="display: none;"' : ''; ?> data-tab="behavior">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Behavior', 'hustle' ); ?></h2>

	</div>

	<div id="hustle-wizard-behaviour" class="sui-box-body">

		<?php

		// DIALOG: Schedule.
		$this->render(
			'admin/commons/sui-wizard/tab-behaviour/schedule',
			array(
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		if ( Hustle_Module_Model::EMBEDDED_MODULE !== $module_type ) {

			// SETTING: Trigger.
			$this->render(
				'admin/commons/sui-wizard/tab-behaviour/trigger',
				array(
					'triggers'            => $settings['triggers'],
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'capitalize_plural'   => $capitalize_plural,
					'smallcaps_singular'  => $smallcaps_singular,
					'shortcode_id'        => $shortcode_id,
				)
			);
		}

		if ( Hustle_Module_Model::SLIDEIN_MODULE === $module_type ) {

			// SETTING: Position.
			$this->render(
				'admin/commons/sui-wizard/tab-behaviour/position',
				array(
					'display_position'    => $settings['display_position'],
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'smallcaps_singular'  => $smallcaps_singular,
				)
			);
		}

		if ( Hustle_Module_Model::SLIDEIN_MODULE !== $module_type ) {

			// SETTING: Animation Settings.
			$this->render(
				'admin/commons/sui-wizard/tab-behaviour/animation-settings',
				array(
					'settings'            => $settings,
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'smallcaps_singular'  => $smallcaps_singular,
				)
			);
		}

		if ( Hustle_Module_Model::EMBEDDED_MODULE !== $module_type ) {

			// SETTING: Additional Closing Methods.
			$this->render(
				'admin/commons/sui-wizard/tab-behaviour/closing-methods',
				array(
					'settings'           => $settings,
					'module_type'        => $module_type,
					'smallcaps_singular' => $smallcaps_singular,
				)
			);

			// SETTING: Closing Behavior.
			$this->render(
				'admin/commons/sui-wizard/tab-behaviour/closing-behaviour',
				array(
					'settings'            => $settings,
					'module_type'         => $module_type,
					'capitalize_singular' => $capitalize_singular,
					'smallcaps_singular'  => $smallcaps_singular,
				)
			);
		}

		// SETTING: Additional Settings.
		$this->render(
			'admin/commons/sui-wizard/tab-behaviour/additional-settings',
			array(
				'settings'           => $settings,
				'module_type'        => $module_type,
				'is_optin'           => $is_optin,
				'smallcaps_singular' => $smallcaps_singular,
			)
		);

		?>

	</div>

	<div class="sui-box-footer">

		<button class="sui-button wpmudev-button-navigation" data-direction="prev"><span class="sui-icon-arrow-left" aria-hidden="true"></span> <?php esc_html_e( 'Visibility', 'hustle' ); ?></button>

		<div class="sui-actions-right">

			<button
				class="hustle-publish-button sui-button sui-button-blue hustle-action-save"
				data-active="1">
				<span class="sui-loading-text">
					<span class="sui-icon-web-globe-world" aria-hidden="true"></span>
					<span class="button-text"><?php $is_active ? esc_html_e( 'Save changes', 'hustle' ) : esc_html_e( 'Publish', 'hustle' ); ?></span>
				</span>
				<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
			</button>

		</div>

	</div>

</div>

<?php
if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module_type ) {

	// SETTINGS: schedule section template.
	$this->render(
		'admin/commons/sui-wizard/tab-behaviour/schedule-tpl',
		array(
			'smallcaps_singular' => $smallcaps_singular,
		)
	);
}
?>