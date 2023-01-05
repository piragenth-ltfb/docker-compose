<?php
/**
 * Email copy section under the "unsubscribe" tab.
 *
 * @package Hustle
 * @since 4.0.0
 */

ob_start();

require Opt_In::$plugin_path . 'assets/css/sui-editor.min.css';
$editor_css    = ob_get_clean();
$editor_css    = '<style>' . $editor_css . '</style>';
$email_enabled = isset( $email['enabled'] ) && '0' !== (string) $email['enabled'];
$email_subject = isset( $email['email_subject'] ) ? $email['email_subject'] : '';
$email_body    = isset( $email['email_body'] ) ? $email['email_body'] : '';
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Unsubscribe Email Copy', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'Customize the copy of the email that will be received by the visitors with the unsubscribe link.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<div class="sui-form-field">

			<label for="hustle-unsub-edit-email" class="sui-toggle hustle-toggle-with-container" data-toggle-on="unsub-email">

				<input
					type="checkbox"
					name="email_enabled"
					value="1"
					id="hustle-unsub-edit-email"
					aria-labelledby="hustle-unsub-edit-email-label"
					<?php checked( $email_enabled ); ?>
				>

				<span class="sui-toggle-slider" aria-hidden="true"></span>

				<span id="hustle-unsub-edit-email-label" class="sui-toggle-label"><?php esc_html_e( 'Enable custom email copy', 'hustle' ); ?></span>

			</label>

			<div class="sui-border-frame sui-toggle-content" data-toggle-content="unsub-email" style="display: none;">

				<!-- Email subject -->
				<div class="sui-form-field">

					<?php
					$email_subject = array(
						'email_subject_label' => array(
							'id'    => 'email-subject-label',
							'for'   => 'email-subject',
							'type'  => 'label',
							'value' => __( 'Email subject', 'hustle' ),
						),
						'email_subject'       => array(
							'id'          => 'email-subject',
							'name'        => 'email_subject',
							'value'       => $email_subject,
							'placeholder' => '',
							'type'        => 'text',
						),
					);

					foreach ( $email_subject as $key => $option ) {
						$this->render( 'general/option', $option );
					}
					?>

				</div>

				<!-- Email body -->
				<div class="sui-form-field">

					<label class="sui-label sui-label-editor" for="emailmessage"><?php esc_html_e( 'Email body', 'hustle' ); ?></label>

					<?php
					wp_editor(
						$email_body,
						'emailmessage',
						array(
							'media_buttons'    => false,
							'textarea_name'    => 'email_message',
							'editor_css'       => $editor_css,
							'tinymce'          => array(
								'content_css' => self::$plugin_url . 'assets/css/sui-editor.min.css',
							),
							'editor_height'    => 192,
							'drag_drop_upload' => false,
						)
					);
					?>

					<span class="sui-description">
						<?php /* translators: placeholder between 'strong' tags */ ?>
						<?php printf( esc_html__( 'Use the placeholder %s to insert the unsubscription link.', 'hustle' ), '<strong>{hustle_unsubscribe_link}</strong>' ); ?>
					</span>

				</div>

			</div>

		</div>

	</div>

</div>