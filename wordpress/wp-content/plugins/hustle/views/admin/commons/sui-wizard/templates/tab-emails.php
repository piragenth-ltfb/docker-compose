<?php
/**
 * Main wrapper for the 'Emails' tab.
 *
 * @uses ../tab-emails/
 *
 * @package Hustle
 * @since 4.0.0
 */

?>
<div class="sui-box" <?php echo 'emails' !== $section ? 'style="display: none;"' : ''; ?> data-tab="emails">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Emails', 'hustle' ); ?></h2>

	</div>

	<div id="hustle-wizard-emails" class="sui-box-body">

		<?php
		// SETTING: Opt-in Form Fields.
		$this->render(
			'admin/commons/sui-wizard/tab-emails/form-fields',
			array( 'elements' => $settings['form_elements'] )
		);

		// SETTING: Submission Behavior.
		$this->render(
			'admin/commons/sui-wizard/tab-emails/submission-behaviour',
			array( 'settings' => $settings )
		);

		// SETTING: Automated Email.
		$this->render(
			'admin/commons/sui-wizard/tab-emails/automated-email',
			array( 'settings' => $settings )
		);
		?>

	</div>

	<div class="sui-box-footer">

		<button class="sui-button wpmudev-button-navigation" data-direction="prev"><span class="sui-icon-arrow-left" aria-hidden="true"></span> <?php esc_html_e( 'Content', 'hustle' ); ?></button>

		<div class="sui-actions-right">
			<button class="sui-button sui-button-icon-right wpmudev-button-navigation" data-direction="next"><?php esc_html_e( 'Integrations', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span></button>
		</div>

	</div>

</div>