<?php
/**
 * Pagination section under the "general" tab.
 *
 * @package Hustle
 * @since 4.0.4
 */

?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Pagination', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Choose the number of items to show per page on your submissions or module listing pages.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-pagination-modules" id="hustle-pagination-modules-label" class="sui-settings-label"><?php esc_html_e( 'Modules', 'hustle' ); ?></label>

			<span id="hustle-pagination-modules-description" class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose the number of modules to show on each listing page.', 'hustle' ); ?></span>

			<input
				type="number"
				name="module_pagination"
				min="1"
				value="<?php echo isset( $settings['module_pagination'] ) ? esc_attr( $settings['module_pagination'] ) : ''; ?>"
				id="hustle-pagination-modules"
				class="sui-form-control sui-input-sm sui-field-has-suffix"
				aria-labelledby="hustle-pagination-modules-label"
				aria-describedby="hustle-pagination-modules-description"
			/>

			<span class="sui-field-suffix" aria-hidden="true"><?php esc_html_e( 'modules per page', 'hustle' ); ?></span>

		</div>

		<div class="sui-form-field">

			<label for="hustle-pagination-submissions" id="hustle-pagination-submissions-label" class="sui-settings-label"><?php esc_html_e( 'Submissions', 'hustle' ); ?></label>

			<span id="hustle-pagination-submissions-description" class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose the number of submissions to show per page.', 'hustle' ); ?></span>

			<input
				type="number"
				name="submission_pagination"
				min="1"
				value="<?php echo isset( $settings['submission_pagination'] ) ? esc_attr( $settings['submission_pagination'] ) : ''; ?>"
				id="hustle-pagination-submissions"
				class="sui-form-control sui-input-sm sui-field-has-suffix"
				aria-labelledby="hustle-pagination-submissions-label"
				aria-describedby="hustle-pagination-submissions-description"
			/>

			<span class="sui-field-suffix" aria-hidden="true"><?php esc_html_e( 'submissions per page', 'hustle' ); ?></span>

		</div>

	</div>

</div>