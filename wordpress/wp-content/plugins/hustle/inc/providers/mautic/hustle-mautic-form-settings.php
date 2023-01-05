<?php
if ( ! class_exists( 'Hustle_Mautic_Form_Settings' ) ) :

	/**
	 * Class Hustle_Mautic_Form_Settings
	 * Form Settings Mautic Process
	 */
	class Hustle_Mautic_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

		/**
		 * Options that must be set in order to consider the integration as "connected" to the form.
		 *
		 * @since 4.2.0
		 * @var array
		 */
		protected $form_completion_options = array( 'list_id' );

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
		public function first_step_is_completed() {
			$this->addon_form_settings = $this->get_form_settings_values();
			if ( ! isset( $this->addon_form_settings['list_id'] ) ) {
				// preliminary value
				$this->addon_form_settings['list_id'] = 0;

				return false;
			}

			if ( empty( $this->addon_form_settings['list_id'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Returns all settings and conditions for 1st step of Mautic settings
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
				'list_id' => '',
			);
			$current_data              = $this->get_current_data( $current_data, $submitted_data );

			$is_submit = ! empty( $submitted_data['hustle_is_submit'] );
			if ( $is_submit && empty( $submitted_data['list_id'] ) ) {
				$error_message = __( 'The segment is required.', 'hustle' );
			}

			$options = $this->get_first_step_options( $current_data );

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Choose Segment', 'hustle' ), __( 'Choose the segment you want to send form data to.', 'hustle' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			if ( ! isset( $error_message ) ) {
				$has_errors = false;
			} else {
				$step_html .= '<span class="sui-error-message">' . $error_message . '</span>';
				$has_errors = true;
			}

			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Disconnect', 'hustle' ),
						'sui-button-ghost',
						'disconnect_form',
						true
					),
				),
				'save'       => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Save', 'hustle' ),
						'',
						'next',
						true
					),
				),
			);

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => $has_errors,
			);

			// Save only after the step has been validated and there are no errors
			if ( $is_submit && ! $has_errors ) {
				// Save additional data for submission's entry
				if ( ! empty( $current_data['list_id'] ) ) {
					$current_data['list_name'] = ! empty( $this->lists[ $current_data['list_id'] ] )
						? $this->lists[ $current_data['list_id'] ] . ' (' . $current_data['list_id'] . ')' : $current_data['list_id'];
				}
				$this->save_form_settings_values( $current_data );
			}

			return $response;
		}

		/**
		 * Refresh list array via API
		 *
		 * @param object $provider
		 * @param string $global_multi_id
		 * @return array
		 */
		public function refresh_global_multi_lists( $provider, $global_multi_id ) {
			$url      = $provider->get_setting( 'url', '', $global_multi_id );
			$username = $provider->get_setting( 'username', '', $global_multi_id );
			$password = $provider->get_setting( 'password', '', $global_multi_id );
			$api      = $provider::api( $url, $username, $password );

			$lists  = array();
			$limit  = 30;
			$offset = 0;

			do {
				$_lists = $api->get_segments( $offset );
				$total  = ! empty( $_lists->total ) ? $_lists->total : 0;

				if ( ! empty( $_lists->lists ) ) {
					$_list_values = $_lists->lists;
					$lists       += wp_list_pluck( $_list_values, 'name', 'id' );
				}

				$offset += $limit;
			} while ( $total > $offset );

			return $lists;
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
			$lists         = $this->get_global_multi_lists();
			$this->lists   = $lists;
			$selected_list = $this->get_selected_list( $submitted_data );

			$options = array(
				'list_id_setup' => array(
					'type'     => 'wrapper',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'list_id',
							'value' => __( 'Choose List', 'hustle' ),
						),
						'wrapper' => array(
							'type'                 => 'wrapper',
							'class'                => 'hui-select-refresh',
							'is_not_field_wrapper' => true,
							'elements'             => array(
								'lists'   => array(
									'type'     => 'select',
									'id'       => 'list_id',
									'name'     => 'list_id',
									'class'    => 'sui-select',
									'value'    => $selected_list,
									'selected' => $selected_list,
									'options'  => $lists,
								),
								'refresh' => array(
									'type'  => 'raw',
									'value' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Refresh', 'hustle' ), '', 'refresh_list', true ),
								),
							),
						),
					),
				),
			);

			return $options;
		}

	} // Class end.

endif;