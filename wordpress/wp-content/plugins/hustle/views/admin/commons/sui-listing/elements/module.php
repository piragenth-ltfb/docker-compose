<?php
/**
 * Displays the tracking charts in the listing page.
 *
 * @package Hustle
 * @since 4.0.0
 */

$is_tracking_disabled = empty( $module->get_tracking_types() );

$module_tag_class = $module->active ? ' sui-tag-blue' : '';
$tooltip_message  = '';
$schedule_icon    = '';
$is_scheduled     = false;

// Let's handle the icons and messages for the Schedule settings.
// Social sharing modules don't have schedules. And drafted modules disregard it.
if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {

	$settings       = $module->get_settings();
	$settings_array = $settings->to_array();
	$schedule       = $settings_array['schedule'];
	$is_scheduled   = '1' === $settings_array['is_schedule'];

	// If a schedule isn't set for the module, no need to add schedule icons.
	if ( $is_scheduled ) {

		$schedule_icon = '<span class="sui-icon-clock sui-sm" aria-hidden="true"></span>';

		/* translators: module type capitalized and in singular */
		$tooltip_message = sprintf( __( "%s schedule has not started, so your visitors can't see it yet.", 'hustle' ), $capitalize_singular );

		$module_tag_class .= ' hui-tag-scheduled sui-tooltip sui-tooltip-constrained sui-tooltip-top-left-mobile';

		// Notify the admin that the module won't be shown again because of the schedule.
		if ( ! $settings->will_be_shown_again() ) {

			$module_tag_class .= ' hui-scheduled-error';

			/* translators: 1. module type capitalized and in singular, 2. module type in lowercase and in singular. */
			$tooltip_message = sprintf(
				__( "%1\$s schedule is over and your visitors can't see this %2\$s anymore.", 'hustle' ),
				$capitalize_singular,
				$smallcaps_singular
			);

			// Notify the admin that this module is currently being displayed according to the schedule.
		} elseif ( $settings->is_currently_scheduled() ) {

			$module_tag_class .= ' hui-scheduled-success';

			$tooltip_message = sprintf(
				/* translators: 1. module type capitalized and in singular, 2. module type in lowercase and in singular. */
				__( '%1$s schedule is active now and the %2$s is visible to your visitors.', 'hustle' ),
				$capitalize_singular,
				$smallcaps_singular
			);
		}
	}
}

?>
<div class="sui-accordion-item">

	<?php
	$module_id       = $module->module_id;
	$can_edit        = Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $module_id );
	$tracking_model  = Hustle_Tracking_Model::get_instance();
	$last_entry_time = $tracking_model->get_latest_conversion_time_by_module_id( $module_id );
	$view_stats      = filter_input( INPUT_GET, 'view_stats', FILTER_VALIDATE_INT );

	if ( $view_stats && intval( $module_id ) === $view_stats ) {
		$display_chart_class = ' hustle-display-chart hustle-scroll-to';
	} else {
		$display_chart_class = '';
	}

	// START: Item header.
	?>
	<div
		class="sui-accordion-item-header<?php echo esc_attr( $display_chart_class ); ?>"
		data-id="<?php echo esc_attr( $module->id ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'module_get_tracking_data' . $module->id ) ); ?>"
	>

		<?php // This should have sui-trim-title, but that class prevents the schedule tooltip from showing up. ?>
		<div class="sui-accordion-item-title">

			<label for="hustle-module-<?php echo esc_html( $module_id ); ?>" class="sui-checkbox sui-accordion-item-action">
				<input
					type="checkbox"
					value="<?php echo esc_html( $module_id ); ?>"
					id="hustle-module-<?php echo esc_html( $module_id ); ?>"
					class="hustle-listing-checkbox"
				/>
				<span aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Select this module', 'hustle' ); ?></span>
			</label>

			<span class="sui-trim-text"><?php echo esc_html( $module->module_name ); ?></span>

			<span
				class="sui-tag<?php echo esc_attr( $module_tag_class ); ?>"
				<?php echo ! empty( $tooltip_message ) ? 'data-tooltip="' . esc_html( $tooltip_message ) . '"' : ''; ?>
			>

				<span class="hustle-toggle-status-button-description<?php echo $module->active ? ' sui-hidden' : ''; ?>">
					<?php esc_html_e( 'Draft', 'hustle' ); ?>
				</span>

				<span class="hustle-toggle-status-button-description<?php echo $module->active ? '' : ' sui-hidden'; ?>">
					<?php esc_html_e( 'Published', 'hustle' ); ?>
					<?php echo $schedule_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>

			</span>

			<span class="sui-tag sui-tag-disabled hustle-analytics-disabled-tag<?php echo ( $module->active && $is_tracking_disabled ) ? '' : ' sui-hidden'; ?>">
				<?php esc_html_e( 'Tracking Disabled', 'hustle' ); ?>
			</span>

		</div>

		<div class="sui-accordion-item-date">
			<strong><?php esc_html_e( 'Last conversion', 'hustle' ); ?></strong>
			<?php echo esc_html( $last_entry_time ); ?>
		</div>

		<div class="sui-accordion-col-auto">

			<?php if ( $can_edit ) { ?>
				<a
					href="<?php echo esc_url( $module->get_edit_url() ); ?>"
					class="sui-button sui-button-ghost sui-accordion-item-action sui-desktop-visible"
				>
					<span class="sui-icon-pencil" aria-hidden="true"></span> <?php esc_attr_e( 'Edit', 'hustle' ); ?>
				</a>

				<a
					href="<?php echo esc_url( $module->get_edit_url() ); ?>"
					class="sui-button-icon sui-accordion-item-action sui-mobile-visible"
				>
					<span class="sui-icon-pencil" aria-hidden="true"></span>
					<span class="sui-screen-reader-text"><?php esc_attr_e( 'Edit', 'hustle' ); ?></span>
				</a>
			<?php } ?>

			<div class="sui-dropdown sui-accordion-item-action">

				<?php
				// ELEMENT: Actions.
				$this->render(
					'admin/commons/sui-listing/elements/actions',
					array(
						'module'               => $module,
						'smallcaps_singular'   => $smallcaps_singular,
						'capitalize_singular'  => $capitalize_singular,
						'is_tracking_disabled' => $is_tracking_disabled,
					)
				);
				?>

			</div>

			<button class="sui-button-icon sui-accordion-open-indicator">
				<span class="sui-icon-chevron-down" aria-hidden="true"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'View module stats', 'hustle' ); ?></span>
			</button>

		</div>

	</div>

	<?php // START: Item body. ?>
	<div class="sui-accordion-item-body">

		<?php
			$render_arguments = array(
				'module'                   => $module,
				'total_module_views'       => 0,
				'total_module_conversions' => 0,
				'tracking_types'           => $tracking_types,
				'last_entry_time'          => esc_html__( 'Never', 'hustle' ),
				'rate'                     => 0,
			);

			// ELEMENT: Tracking data.
			$this->render(
				'admin/commons/sui-listing/elements/tracking-data',
				array(
					'render_arguments' => $render_arguments,
					'multiple_charts'  => false,
				)
			);
			?>

	</div>

</div>