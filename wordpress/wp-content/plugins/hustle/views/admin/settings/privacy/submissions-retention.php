<?php
/**
 * Submission retention row under the "privacy" tab.
 *
 * @package Hustle
 * @since 4.0.3
 */

ob_start();
?>
<div class="sui-row">
	<div class="sui-col-md-6">
		<input type="number"
			name="submissions_retention_number"
			value="<?php echo esc_attr( $settings['submissions_retention_number'] ); ?>"
			placeholder="0"
			class="sui-form-control" />
	</div>
	<div class="sui-col-md-6" >
		<select name="submissions_retention_number_unit" id="hustle-select-submissions_retention_number_unit">
			<option value="days" <?php selected( 'days', $settings['submissions_retention_number_unit'] ); ?>><?php esc_html_e( 'day(s)', 'hustle' ); ?></option>
			<option value="weeks"  <?php selected( 'weeks', $settings['submissions_retention_number_unit'] ); ?>><?php esc_html_e( 'week(s)', 'hustle' ); ?></option>
			<option value="months" <?php selected( 'months', $settings['submissions_retention_number_unit'] ); ?>><?php esc_html_e( 'month(s)', 'hustle' ); ?></option>
			<option value="years" <?php selected( 'years', $settings['submissions_retention_number_unit'] ); ?>><?php esc_html_e( 'year(s)', 'hustle' ); ?></option>
		</select>
	</div>
</div>
<?php $custom_tab_content = ob_get_clean(); ?>

<fieldset class="sui-form-field">

	<label class="sui-settings-label"><?php esc_html_e( 'Submissions Retention', 'hustle' ); ?></label>

	<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose how long to retain each module’s submissions for.', 'hustle' ); ?></span>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'        => 'retain_submission_forever',
			'radio'       => true,
			'saved_value' => $settings['retain_submission_forever'],
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

</fieldset>