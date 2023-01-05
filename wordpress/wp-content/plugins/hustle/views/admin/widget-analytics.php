<?php
/**
 * Dashboard Hustle analytics widget: Displayed on site dashboards with stats.
 *
 * @package Hustle
 * @since 4.1.0
 */

$array_days_ago = $this->admin->get_analytic_ranges();

$active_module_types = $settings['modules'];

$available_module_types = array(
	'overall'                                  => __( 'Overall', 'hustle' ),
	Hustle_Module_Model::POPUP_MODULE          => __( 'Pop-ups', 'hustle' ),
	Hustle_Module_Model::SLIDEIN_MODULE        => __( 'Slide-ins', 'hustle' ),
	Hustle_Module_Model::EMBEDDED_MODULE       => __( 'Embeds', 'hustle' ),
	Hustle_Module_Model::SOCIAL_SHARING_MODULE => __( 'Social Sharing', 'hustle' ),
);

?>
<div
	class="hustle-widget"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'hustle_update_wp_dashboard_chart' ) ); ?>"
>

	<form class="hustle-widget-header">

		<div class="hustle-form-field">

			<label for="hustle-analytics-show" id="hustle-analytics-show-label" class="hustle-label"  aria-labelledby="hustle-analytics-show-label"><?php esc_html_e( 'Show', 'hustle' ); ?></label>

			<select id="hustle-analytics-show" class="hustle-select">
				<option value="view" selected><?php esc_html_e( 'Views', 'hustle' ); ?></option>
				<option value="conversion"><?php esc_html_e( 'All Conversions', 'hustle' ); ?></option>
				<option value="cta_conversion"><?php esc_html_e( 'CTA Conversions', 'hustle' ); ?></option>
				<option value="optin_conversion"><?php esc_html_e( 'Optin Conversions', 'hustle' ); ?></option>
				<option value="rate"><?php esc_html_e( 'Conversion Rate', 'hustle' ); ?></option>
			</select>

		</div>

		<div class="hustle-form-field">

			<label for="hustle-analytics-data" id="hustle-analytics-data-label" class="hustle-label"><?php esc_html_e( 'from', 'hustle' ); ?></label>

			<select id="hustle-analytics-data" class="hustle-select" aria-labelledby="hustle-analytics-data-label">
				<?php foreach ( $array_days_ago as $val => $range_title ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>"<?php selected( $val, 7 ); ?>><?php echo esc_html( $range_title ); ?></option>
				<?php endforeach; ?>
			</select>

		</div>

		<button id="hustle-analytics-apply" class="button hustle-button"><?php esc_html_e( 'Apply', 'hustle' ); ?></button>

	</form>

	<div class="hustle-widget-body">

		<div class="hustle-options-embed" style="display: none;">

			<button role="tab" class="hustle-option hustle-active" aria-selected="true" data-display-type="total"><?php esc_html_e( 'Total', 'hustle' ); ?></button>

			<button role="tab" class="hustle-option" aria-selected="false" data-display-type="<?php echo esc_attr( Hustle_SShare_Model::FLOAT_MODULE ); ?>"><?php esc_html_e( 'Floating', 'hustle' ); ?></button>

			<button role="tab" class="hustle-option" aria-selected="false" data-display-type="<?php echo esc_attr( Hustle_Module_Model::INLINE_MODULE ); ?>"><?php esc_html_e( 'Inline', 'hustle' ); ?></button>

			<button role="tab" class="hustle-option" aria-selected="false" data-display-type="<?php echo esc_attr( Hustle_Module_Model::WIDGET_MODULE ); ?>"><?php esc_html_e( 'Widget', 'hustle' ); ?></button>

			<button role="tab" class="hustle-option" aria-selected="false" data-display-type="<?php echo esc_attr( Hustle_Module_Model::SHORTCODE_MODULE ); ?>"><?php esc_html_e( 'Shortcode', 'hustle' ); ?></button>

		</div>

		<div class="hustle-chart-wrap">

			<div class="hustle-options-chart">

				<?php foreach ( $active_module_types as $module_type ) : ?>

					<?php $is_selected = $module_type === $active_module_types[0]; ?>
					<button
						role="tab"
						class="hustle-option<?php echo $is_selected ? ' hustle-active' : ''; ?>"
						aria-selected="<?php echo $is_selected ? 'true' : 'false'; ?>"
						data-module-type="<?php echo esc_attr( $module_type ); ?>"
					>
						<span class="hustle-option--title"><?php echo esc_html( $available_module_types[ $module_type ] ); ?></span>
						<span class="hustle-option--value"></span>
						<span class="hustle-option--trend"></span>
					</button>

				<?php endforeach; ?>

			</div>

			<div class="hustle-chart-graph">

				<div class="hustle-message-empty" style="display:none;">
					<p class="hustle-title"><?php esc_html_e( "We haven't collected enough data yet.", 'hustle' ); ?></p>
					<p class="hustle-text"><?php esc_html_e( 'You will start viewing the performance statistics of your Hustle modules shortly. So feel free to check back soon', 'hustle' ); ?></p>
				</div>

				<canvas id="hustle-analytics-chart"></canvas>

			</div>

		</div>

	</div>

</div>