<?php
/**
 * Metrics tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div id="top-metrics-box" class="sui-box" data-tab="metrics" <?php echo 'metrics' !== $section ? 'style="display: none;"' : ''; ?>>

	<div class="sui-box-header">
		<h2 class="sui-box-title"><?php esc_html_e( 'Top 3 Metrics', 'hustle' ); ?></h2>
	</div>

	<form id="hustle-top-metrics-settings-form" class="sui-box-body">

		<p><?php esc_html_e( 'Choose the top 3 metrics which are most relevant to your goals. These metrics will be visible on the Hustle’s main dashboard area.', 'hustle' ); ?></p>

		<div class="sui-form-field">
			<label for="hustle-metrics-rate" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="average_conversion_rate"
					id="hustle-metrics-rate"
					<?php checked( in_array( 'average_conversion_rate', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( 'Average Conversion Rate', 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The average conversion rate is the total number of conversions divided by the total number of views on all the modules.', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-today" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="today_conversions"
					id="hustle-metrics-today"
					<?php checked( in_array( 'today_conversions', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( "Today's Conversion", 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The total number of conversions happened today from each module.', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-week" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="last_week_conversions"
					id="hustle-metrics-week"
					<?php checked( in_array( 'last_week_conversions', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( "Last 7 Day's Conversion", 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The total number of conversions happened in the last 7 days from each module', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-month" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="last_month_conversions"
					id="hustle-metrics-month"
					<?php checked( in_array( 'last_month_conversions', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( "Last 1 Month's conversion", 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The total number of conversions happened in the last month from each module.', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-total" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="total_conversions"
					id="hustle-metrics-total"
					<?php checked( in_array( 'total_conversions', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( 'Total Conversions', 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The sum of all the conversion happened up to today from each module.', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-most" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="most_conversions"
					id="hustle-metrics-most"
					<?php checked( in_array( 'most_conversions', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( 'Most Conversions', 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The module which has the highest number of conversions.', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-inactive-modules" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="inactive_modules_count"
					id="hustle-metrics-inactive-modules"
					<?php checked( in_array( 'inactive_modules_count', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( 'Inactive Modules', 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The total number of modules which are currently inactive. This will include all the drafts and unpublished modules.', 'hustle' ); ?></span>
		</div>

		<div class="sui-form-field">
			<label for="hustle-metrics-total-modules" class="sui-checkbox">
				<input type="checkbox"
					name="metrics[]"
					value="total_modules_count"
					id="hustle-metrics-total-modules"
					<?php checked( in_array( 'total_modules_count', $stored_metrics, true ) ); ?> />
				<span aria-hidden="true"
					data-tooltip="<?php esc_html_e( 'You can only select up to 3 metrics', 'hustle' ); ?>"></span>
				<span><?php esc_html_e( 'Total Modules', 'hustle' ); ?></span>
			</label>
			<span class="sui-description sui-checkbox-description"><?php esc_html_e( 'The total number of modules regardless of their status.', 'hustle' ); ?></span>
		</div>

	</form>

	<div class="sui-box-footer">
		<div class="sui-actions-right">
			<div class="sui-tooltip-top-right" data-tooltip="<?php esc_html_e( 'Please select 3 metrics to save settings.', 'hustle' ); ?>">
				<button
					class="sui-button sui-button-blue hustle-settings-save"
					data-form-id="hustle-top-metrics-settings-form"
					data-target="top_metrics"
				>
					<span class="sui-loading-text"><?php esc_html_e( 'Save Settings', 'hustle' ); ?></span>
					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>

</div>