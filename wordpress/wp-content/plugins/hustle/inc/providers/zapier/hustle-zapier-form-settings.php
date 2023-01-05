<?php
if ( ! class_exists( 'Hustle_Zapier_Form_Settings' ) ) :

	/**
	 * Class Hustle_Zapier_Form_Settings
	 * Form Settings Zapier Process
	 */
	class Hustle_Zapier_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

		/**
		 * For settings Wizard steps
		 *
		 * @since 3.0.5
		 * @return array
		 */
		public function form_settings_wizards() {
			// already filtered on Abstract
			// numerical array steps
			return array(
				// 0
				array(
					'callback'     => array( $this, 'first_step_callback' ),
					'is_completed' => array( $this, 'first_step_is_completed' ),
				),
			);
		}

		/**
		 * Check if step is completed
		 *
		 * @since 3.0.5
		 * @return bool
		 */
		public function first_step_is_completed( $submitted_data ) {

			$is_connected = ! empty( $submitted_data['api_key'] ) && filter_var( $submitted_data['api_key'], FILTER_VALIDATE_URL );

			return $is_connected;
		}

		/**
		 * Returns all settings and conditions for 1st step of Zapier settings
		 *
		 * @since 3.0.5
		 * @since 4.0 param $validate removed.
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function first_step_callback( $submitted_data ) {
			$this->addon_form_settings = $this->get_form_settings_values();
			$current_data              = array(
				'name'    => '',
				'api_key' => '',
			);

			$current_data = $this->get_current_data( $current_data, $submitted_data );

			$is_submit = ! empty( $submitted_data['hustle_is_submit'] );

			if ( $is_submit ) {
				$sent = $this->validate_and_send_sample( $submitted_data );
				if ( true !== $sent ) {
					$error_message = $sent;
				}
			}

			$options = $this->get_first_step_options( $current_data );

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Setup Webhook', 'hustle' ), __( 'Put your ZAP Webhook URL below.', 'hustle' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			if ( ! isset( $error_message ) ) {
				$has_errors = false;
			} else {
				$step_html .= '<span class="sui-error-message">' . $error_message . '</span>';
				$has_errors = true;
			}

			$notice_message = sprintf(
				/* translators: ... */
				esc_html__( 'Please go %1$shere%2$s if you do not have any ZAP created. Remember to choose %3$s as Trigger App.', 'hustle' ),
				'<a href="https://zapier.com/app/editor/" target="_blank">',
				'</a>',
				'<strong>Webhooks by Zapier</strong>'
			);

			$notice_options = array(
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-top: 30px;margin-bottom: 0;',
					'elements' => array(
						'notice' => array(
							'type'  => 'notice',
							'icon'  => 'info',
							'class' => 'sui-notice-warning',
							'value' => $notice_message,
						),
					),
				),
			);

			$step_html .= Hustle_Provider_Utils::get_html_for_options( $notice_options );

			$buttons = array();
			if ( $this->first_step_is_completed( $current_data ) ) {
				$buttons['disconnect'] = array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Disconnect', 'hustle' ),
						'sui-button-ghost',
						'disconnect_form',
						true
					),
				);
			}

			$buttons['save'] = array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup(
					__( 'Save', 'hustle' ),
					'',
					'next',
					true
				),
			);

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => $has_errors,
			);

			// Save only after the step has been validated and there are no errors.
			if ( $is_submit && ! $has_errors ) {
				$this->save_form_multi_id_settings_values( $submitted_data );
			}

			return $response;
		}

		/**
		 * Sending test sample to zapier webhook URL
		 * Data sent will be used on zapier to map fields on their zap action
		 *
		 * @param array $submitted_data Submitted data.
		 * @return boolean|string
		 */
		private function validate_and_send_sample( $submitted_data ) {
			if ( ! isset( $submitted_data['api_key'] ) ) {
				return esc_html__( 'Please put a valid Webhook URL.', 'hustle' );
			}

			// must be this prefix.
			if ( stripos( $submitted_data['api_key'], 'https://hooks.zapier.com/' ) !== 0 ) {
				return esc_html__( 'Please put a valid Webhook URL.', 'hustle' );
			}

			// must not be in silent mode.
			if ( stripos( $submitted_data['api_key'], 'silent' ) !== false ) {
				return esc_html__( 'Please disable Silent Mode on Webhook URL.', 'hustle' );
			}

			$endpoint = wp_http_validate_url( $submitted_data['api_key'] );
			if ( false === $endpoint ) {
				return esc_html__( 'Please put a valid Webhook URL.', 'hustle' );
			}

			// Build sample data.
			$sample_data = $this->get_sample_data();

			// If has sample data then send test request.
			if ( $sample_data ) {
				Hustle_Zapier_API::make_request( $endpoint, $sample_data );
			}

			return true;
		}

		/**
		 * Get module sample data with all fields
		 *
		 * @return array|false
		 */
		private function get_sample_data() {
			// Get default form fields.
			$module   = new Hustle_Module_Model( $this->module_id );
			$elements = $module->get_form_fields();

			if ( ! $elements ) {
				return false;
			}

			// Loop through form elements.
			foreach ( $elements as $element ) {
				$value = '';

				if ( ! empty( $element['placeholder'] ) ) {
					$value = $element['placeholder'];
				} elseif ( $element['label'] ) {
					$value = $element['label'];
				}

				$sample_data[ $element['name'] ] = $value;
			}

			// Remove recaptcha and submit fields.
			unset( $sample_data['recaptcha'], $sample_data['submit'] );

			return $sample_data;
		}

		/**
		 * Return an array of options used to display the settings of the 1st step.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		private function get_first_step_options( $submitted_data ) {
			$webhook = ! empty( $submitted_data['api_key'] ) ? $submitted_data['api_key'] : '';
			$name    = ! empty( $submitted_data['name'] ) ? $submitted_data['name'] : '';

			$options = array(
				array(
					'type'     => 'wrapper',
					'elements' => array(
						'label'   => array(
							'for'   => 'friendly-name',
							'type'  => 'label',
							'value' => __( 'Integration Name', 'hustle' ),
						),
						'webhook' => array(
							'type'        => 'text',
							'name'        => 'name',
							'value'       => $name,
							'placeholder' => __( 'Friendly Name', 'hustle' ),
							'id'          => 'friendly-name',
							'icon'        => 'web-globe-world',
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						'label'   => array(
							'for'   => 'webhook',
							'type'  => 'label',
							'value' => __( 'Webhook URL', 'hustle' ),
						),
						'webhook' => array(
							'type'        => 'url',
							'name'        => 'api_key',
							'value'       => $webhook,
							'placeholder' => __( 'Webhook URL', 'hustle' ),
							'id'          => 'webhook',
							'icon'        => 'link',
						),
					),
				),
			);

			return $options;
		}

		/**
		 * Get the first found aactive connection of the provider.
		 *
		 * @since 4.0
		 *
		 * @param string $multi_id
		 * @param array  $settings
		 * @return boolean
		 */
		public function is_multi_form_settings_complete( $multi_id, $settings ) {

			if ( true === $this->first_step_is_completed( $settings ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Disconnect multi_id instance of a provider from a module.
		 *
		 * @since 4.0
		 * @param array $submitted_data
		 */
		public function disconnect_form( $submitted_data ) {

			// only execute if the multi_id is provided on the submitted data.
			if ( isset( $submitted_data['multi_id'] ) && ! empty( $submitted_data['multi_id'] ) ) {
				$addon_form_settings = $this->get_form_settings_values();
				unset( $addon_form_settings[ $submitted_data['multi_id'] ] );
				$this->save_form_settings_values( $addon_form_settings, true );
			}
		}

	} // Class end.

endif;