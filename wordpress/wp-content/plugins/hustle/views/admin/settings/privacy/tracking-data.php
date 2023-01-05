<?php
/**
 * Tracking data section under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

ob_start();
?>
<div class="sui-row">
	<div class="sui-col-md-6">
		<input type="number"
			name="tracking_retention_number"
			value="<?php echo esc_attr( $settings['tracking_retention_number'] ); ?>"
			placeholder="0"
			class="sui-form-control" />
	</div>
	<div class="sui-col-md-6" >
		<select name="tracking_retention_number_unit" id="hustle-select-tracking_retention_number_unit">
			<option value="days" <?php selected( 'days', $settings['tracking_retention_number_unit'] ); ?>><?php esc_html_e( 'day(s)', 'hustle' ); ?></option>
			<option value="weeks"  <?php selected( 'weeks', $settings['tracking_retention_number_unit'] ); ?>><?php esc_html_e( 'week(s)', 'hustle' ); ?></option>
			<option value="months" <?php selected( 'months', $settings['tracking_retention_number_unit'] ); ?>><?php esc_html_e( 'month(s)', 'hustle' ); ?></option>
			<option value="years" <?php selected( 'years', $settings['tracking_retention_number_unit'] ); ?>><?php esc_html_e( 'year(s)', 'hustle' ); ?></option>
		</select>
	</div>
</div>
<?php $custom_tab_content = ob_get_clean(); ?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Tracking Data Privacy', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose how you want to handle the tracking data (views and conversions) of modules.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<label class="sui-settings-label"><?php esc_html_e( 'Tracking Data Retention', 'hustle' ); ?></label>
		<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose how long to retain the tracking data of your modules.', 'hustle' ); ?></span>

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'        => 'retain_tracking_forever',
				'radio'       => true,
				'saved_value' => $settings['retain_tracking_forever'],
				'sidetabs'    => true,
				'content'     => true,
				'options'     => array(
					'1' => array(
						'value' => '1',
						'label' => esc_html__( 'Forever', 'hustle' ),
					),
					'0' => array(
						'value'   => '0',
						'label'   => esc_html__( 'Custom', 'hustle' ),
						'boxed'   => true,
						'content' => $custom_tab_content,
					),
				),
			)
		);
		?>

	</div>

</div>