<?php
/**
 * Displays the actual tracking per module chart in the listing page.
 *
 * @package Hustle
 * @since 4.0.0
 */

// Labels and values for the options for the Optin tracking charts.
$cta_labels = array(
	'all'   => __( 'All', 'hustle' ),
	'cta'   => __( 'Call to Action', 'hustle' ),
	'optin' => __( 'Opt-in Form', 'hustle' ),
);

$chart_message_class = '';
$chart_message       = '';
$chart_sub_type      = empty( $module_sub_type ) ? 'overall' : $module_sub_type;
$is_tracking_enabled = ! empty( $tracking_types );
$smallcaps_singular  = Opt_In_Utils::get_module_type_display_name( $module->module_type );

$canvas_content = sprintf(
	'<canvas id="hustle-%1$s-%2$s-stats--%3$s"></canvas>',
	esc_attr( $module->module_type ),
	esc_attr( $module->id ),
	esc_attr( $chart_sub_type )
);

// For embeds and ssharing, check if the sub type (inline, shortcode, etc.) this chart
if ( ! empty( $module_sub_type ) ) {

	// And set the data of this sub type.
	$is_tracking_enabled      = isset( $tracking_types[ $module_sub_type ] );
	$last_entry_time          = $sub_type_data['last_entry_time'];
	$total_module_views       = $sub_type_data['views'];
	$total_module_conversions = $sub_type_data['conversions'];
	$rate                     = $sub_type_data['conversion_rate'];
}

if ( ! $module->active ) {

	if ( 0 === $total_module_views && 0 === $total_module_conversions ) {

		/* translators: 1: module type display name */
		$chart_message       = sprintf( esc_html__( "This %1\$s is still in draft state. You can test your %1\$s, but we won't start collecting conversion data until you publish it live.", 'hustle' ), esc_html( $smallcaps_singular ) );
		$chart_message_class = ' sui-chartjs-message--empty';
		$canvas_content      = '';

	} else {

		/* translators: 1: module type display name */
		$chart_message = sprintf( esc_html__( "This %1\$s is in draft state, so we've paused collecting data until you publish it live.", 'hustle' ), esc_html( $smallcaps_singular ) );
	}
} else {

	if ( ! $is_tracking_enabled ) {

		/* translators: 1: module type display name */
		$chart_message = sprintf( esc_html__( 'This %1$s has tracking disabled. Enable tracking from the settings dropdown to start collecting data.', 'hustle' ), esc_html( $smallcaps_singular ) );
	}
}

?>

<ul class="sui-accordion-item-data">

	<li data-col="large">
		<strong><?php esc_html_e( 'Last Conversion', 'hustle' ); ?></strong>
		<span><?php echo esc_html( $last_entry_time ); ?></span>
	</li>

	<li data-col="small">
		<strong><?php esc_html_e( 'Views', 'hustle' ); ?></strong>
		<span><?php echo esc_html( $total_module_views ); ?></span>
	</li>

	<li>
		<strong><?php esc_html_e( 'Conversions', 'hustle' ); ?></strong>
		<span class="hustle-tracking-<?php echo esc_attr( $chart_sub_type ); ?>-conversions-count"><?php echo esc_html( $total_module_conversions ); ?></span>
	</li>

	<li>
		<strong><?php esc_html_e( 'Conversion Rate', 'hustle' ); ?></strong>
		<span class="hustle-tracking-<?php echo esc_attr( $chart_sub_type ); ?>-conversions-rate"><?php echo esc_html( $rate ); ?>%</span>
	</li>

	<?php if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type && Hustle_Module_Model::OPTIN_MODE === $module->module_mode ) : ?>

		<li class="hustle-conversion-select" data-col="selector">

			<label class="hui-selector-label">
				<?php if ( ! empty( $notice_for_old_data ) ) { ?>
				<span class="hui-label-icon sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( 'We can distinguish the new conversions from the version 4.0.4 or above. Your older conversions will appear under All conversions only.', 'hustle' ); ?>">
					<span class="sui-icon-info sui-sm" aria-hidden="true"></span>
				</span>
				<?php } ?>
				<span class="hui-label-text"><?php esc_html_e( 'Show conversions for', 'hustle' ); ?></span>
			</label>

			<select
				class="sui-select sui-select-inline sui-select-sm hustle-conversion-type"
				data-width="120"
				data-module-type="<?php echo esc_attr( $chart_sub_type ); ?>"
			>
				<?php foreach ( $cta_labels as $key => $cta_label ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $cta_label ); ?></option>
				<?php } ?>
			</select>

		</li>

	<?php endif; ?>

</ul>

<div class="sui-chartjs sui-chartjs-animated">

	<div class="sui-chartjs-message sui-chartjs-message--loading">

		<p><span class="sui-icon-loader sui-loading" aria-hidden="true"></span> <?php esc_html_e( 'Loading data...', 'hustle' ); ?></p>

	</div>

	<?php if ( ! empty( $chart_message ) ) : ?>

		<div class="sui-chartjs-message<?php echo esc_attr( $chart_message_class ); ?>">

			<p><span class="sui-icon-info" aria-hidden="true"></span><?php echo $chart_message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

		</div>

	<?php endif; ?>

	<div class="sui-chartjs-canvas">

		<?php echo $canvas_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	</div>

</div>