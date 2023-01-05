<?php
/**
 * Main wrapper for the 'Integrations' tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

/* translators: module type in small caps and in singular */
$connect_msg = sprintf( esc_html__( 'Send this %s’s data to a third party applications.' ), esc_html( $smallcaps_singular ) );
if ( current_user_can( 'hustle_edit_integrations' ) ) {

	$integrations_url = add_query_arg( 'page', Hustle_Data::INTEGRATIONS_PAGE, 'admin.php' );
	$connect_msg     .= sprintf(
		/* translators: 1. opening 'a' tag to the integrations page, 2. closing 'a' tag */
		esc_html__( ' Connect to more 3rd party applications via the %1$sIntegrations%2$s page.', 'hustle' ),
		'<a href="' . esc_url( $integrations_url ) . '">',
		'</a>'
	);
}

ob_start();
?>
<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Error Message', 'hustle' ); ?></label>

	<input type="text"
		name="disallow_submission_message"
		data-attribute="disallow_submission_message"
		value="<?php echo esc_attr( $settings['disallow_submission_message'] ); ?>"
		class="sui-form-control" />

</div>
<?php $disallow_tab_content = ob_get_clean(); ?>

<div id="hustle-box-section-integrations" class="sui-box" <?php echo ( 'integrations' !== $section ) ? 'style="display: none;"' : ''; ?> data-tab="integrations">

	<div class="sui-box-header">

		<h2 class="sui-box-title"><?php esc_html_e( 'Integrations', 'hustle' ); ?></h2>

	</div>

	<div class="sui-box-body">

		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-1">

				<span class="sui-settings-label"><?php esc_html_e( 'Applications', 'hustle' ); ?></span>

				<span class="sui-description"><?php echo $connect_msg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

			</div>

			<div class="sui-box-settings-col-2">

				<div class="sui-form-field">

					<label class="sui-label"><?php esc_html_e( 'Active apps', 'hustle' ); ?></label>

					<div id="hustle-connected-providers-section">

						<div class="hustle-integrations-display"></div>

					</div>

				</div>

				<div class="sui-form-field">

					<label class="sui-label"><?php esc_html_e( 'Connected apps', 'hustle' ); ?></label>

					<div id="hustle-not-connected-providers-section">

						<div class="hustle-integrations-display"></div>

					</div>

				</div>

			</div>

		</div>

		<div class="sui-box-settings-row">

			<div class="sui-box-settings-col-1">

				<span class="sui-settings-label"><?php esc_html_e( 'Integrations Behavior', 'hustle' ); ?></span>

				<span class="sui-description"><?php esc_html_e( 'Have more control over the integrations behavior of your active apps as per your liking.', 'hustle' ); ?></span>

			</div>

			<div class="sui-box-settings-col-2">

				<label for="hustle-integrations-allow-subscribed-users" class="sui-settings-label"><?php esc_html_e( 'Allow already subscribed user to submit the form', 'hustle' ); ?></label>

				<span class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Choose whether you want to submit the form and subscribe the user to the rest of the active apps when the user is already subscribed to one of the active apps or want the form submission to fail.', 'hustle' ); ?></span>

				<?php $allowed = '1' === $settings['allow_subscribed_users']; ?>

				<?php
				$this->render(
					'admin/global/sui-components/sui-tabs',
					array(
						'name'        => 'allow_subscribed_users',
						'radio'       => true,
						'saved_value' => $settings['allow_subscribed_users'],
						'sidetabs'    => true,
						'content'     => true,
						'options'     => array(
							'1' => array(
								'value' => '1',
								'label' => __( 'Allow Submission', 'hustle' ),
							),
							'0' => array(
								'value'   => '0',
								'label'   => __( 'Disallow Submission', 'hustle' ),
								'content' => $disallow_tab_content,
							),
						),
					)
				);
				?>

				<input
					type="hidden"
					id="hustle-integrations-active-integrations"
					data-attribute="active_integrations"
					name="active_integrations"
					value="<?php echo esc_html( $settings['active_integrations'] ); ?>"
				/>
				<input
					type="hidden"
					id="hustle-integrations-active-count"
					data-attribute="active_integrations_count"
					name="active_integrations_count"
					value="<?php echo esc_html( $settings['active_integrations_count'] ); ?>"
				/>
			</div>

		</div>

	</div>

	<div class="sui-box-footer">

		<button class="sui-button wpmudev-button-navigation"
			data-direction="prev">
			<span class="sui-icon-arrow-left" aria-hidden="true"></span> <?php esc_html_e( 'Emails', 'hustle' ); ?>
		</button>

		<div class="sui-actions-right">
			<button class="sui-button sui-button-icon-right wpmudev-button-navigation"
				data-direction="next">
				<?php esc_html_e( 'Appearance', 'hustle' ); ?> <span class="sui-icon-arrow-right" aria-hidden="true"></span>
			</button>
		</div>

	</div>

</div>