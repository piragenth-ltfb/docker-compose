<?php
if ( ! class_exists( 'Hustle_Activecampaign_Form_Settings' ) ) :

	/**
	 * Class Hustle_Activecampaign_Form_Settings
	 * Form Settings ActiveCampaign Process
	 */
	class Hustle_Activecampaign_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

		/**
		 * Whether the lists are empty.
		 *
		 * @since unknown
		 * @var boolean
		 */
		private $is_empty_lists = false;

		/**
		 * Whether to retrieve forms or lists.
		 *
		 * @since unknwon
		 * @var bool|string
		 */
		public $list_type = false;

		/**
		 * Options that must be set in order to consider the integration as "connected" to the form.
		 *
		 * @since 4.2.0
		 * @var array
		 */
		protected $form_completion_options = array( 'selected_global_multi_id' );

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
				// 1
				array(
					'callback'     => array( $this, 'second_step_callback' ),
					'is_completed' => array( $this, 'second_step_is_completed' ),
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
			return true;
		}

		/**
		 * Check if step is completed
		 *
		 * @since 4.0
		 * @return bool
		 */
		public function second_step_is_completed() {
			$this->addon_form_settings = $this->get_form_settings_values();
			$is_form                   = isset( $this->addon_form_settings['sign_up_to'] ) && 'form' === $this->addon_form_settings['sign_up_to'];

			if ( $is_form ) {
				if ( empty( $this->addon_form_settings['form_id'] ) ) {
					// preliminary value
					$this->addon_form_settings['form_id'] = 0;

					return false;
				}
			} else {
				if ( empty( $this->addon_form_settings['list_id'] ) ) {
					// preliminary value
					$this->addon_form_settings['list_id'] = 0;

					return false;
				}
			}

			return true;
		}

		/**
		 * Returns all settings and conditions for 1st step of Activecampaign settings
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
				'sign_up_to' => '',
			);
			$current_data              = $this->get_current_data( $current_data, $submitted_data );

			$is_submit = ! empty( $submitted_data['hustle_is_submit'] );
			$options   = $this->get_first_step_options( $current_data );

			$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'ActiveCampaign Forms or Lists', 'hustle' ), '' );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

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
						__( 'Continue', 'hustle' ),
						'',
						'next',
						true
					),
				),
			);

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => false,
			);

			// Save only after the step has been validated
			if ( $is_submit ) {
				$this->save_form_settings_values( $current_data );
			}

			return $response;
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
			$sign_up_to = $submitted_data['sign_up_to'];

			$options = array(
				array(
					'type'     => 'wrapper',
					'elements' => array(
						'opt_in' => array(
							'type'       => 'checkbox',
							'name'       => 'sign_up_to',
							'value'      => 'form',
							'id'         => 'sign_up_to',
							'class'      => 'sui-checkbox-sm',
							'attributes' => array(
								'checked' => 'form' === $sign_up_to ? 'checked' : '',
							),
							'label'      => __( 'Enable to choose from your existing forms instead of your existing lists.', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						array(
							'type'  => 'notice',
							'icon'  => 'info',
							'value' => esc_html__( 'Double opt-in is only available when using forms.', 'hustle' ),
							'class' => 'sui-notice-warning',
						),
					),
				),
			);

			return $options;
		}


		/**
		 * Returns all settings and conditions for 2st step of Activecampaign settings
		 *
		 * @since 3.0.5
		 * @since 4.0 param $validate removed.
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function second_step_callback( $submitted_data ) {
			$this->addon_form_settings = $this->get_form_settings_values( false );
			$sign_up_to                = isset( $this->addon_form_settings['sign_up_to'] ) ? $this->addon_form_settings['sign_up_to'] : '';
			$form                      = 'form' === $sign_up_to;
			$list_id                   = $form ? 'form_id' : 'list_id';
			$list_name                 = $form ? 'form_name' : 'list_name';
			$current_data              = array(
				$list_id => '',
			);

			$current_data = $this->get_current_data( $current_data, $submitted_data );
			$is_submit    = ! empty( $submitted_data['hustle_is_submit'] );

			if ( $is_submit && empty( $submitted_data[ $list_id ] ) ) {
				$error_message = $form ? __( 'The form is required.', 'hustle' ) : __( 'The email list is required.', 'hustle' );
			}

			$options = $this->get_second_step_options( $current_data, $form );

			$step_html  = $form
			? Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Choose your form', 'hustle' ), __( 'Choose the form you want to send form data to.', 'hustle' ) )
			: Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Choose your list', 'hustle' ), __( 'Choose the list you want to send form data to.', 'hustle' ) );
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			if ( ! isset( $error_message ) ) {
				$has_errors = false;
			} else {
				$step_html .= '<span class="sui-error-message">' . $error_message . '</span>';
				$has_errors = true;
			}

			$buttons = array(
				'cancel' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Back', 'hustle' ),
						'',
						'prev',
						true
					),
				),
				'save'   => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Save', 'hustle' ),
						'',
						'next',
						true,
						$this->is_empty_lists
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
				if ( ! empty( $current_data[ $list_id ] ) ) {
					$current_data[ $list_name ] = ! empty( $this->lists[ $current_data[ $list_id ] ]['label'] )
						? $this->lists[ $current_data[ $list_id ] ]['label'] . ' (' . $current_data[ $list_id ] . ')' : $current_data[ $list_id ];
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
			$api_url = $provider->get_setting( 'api_url', '', $global_multi_id );
			$api_key = $provider->get_setting( 'api_key', '', $global_multi_id );
			$api     = $provider::api( $api_url, $api_key );

			$lists = array();

			// Retrieve lists if "sign_up_to" is not set to "forms".
			if ( ! $this->list_type || 'forms' !== $this->list_type ) {
				$_lists = $api->get_lists();
			} else {
				// Retrieve forms otherwise
				$_lists = $api->get_forms();
			}

			if ( ! empty( $_lists ) ) {
				$lists = wp_list_pluck( $_lists, 'name', 'id' );
			}

			return $lists;
		}

		/**
		 * Return an array of options used to display the settings of the 2st step.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data
		 * @param bool  $is_form
		 * @return array
		 */
		private function get_second_step_options( $submitted_data, $is_form ) {
			$this->list_type = $is_form ? 'forms' : false;
			$id              = ! $is_form ? 'list_id' : 'form_id';
			$cache_key       = ! $is_form ? 'lists' : 'forms';
			$lists           = $this->get_global_multi_lists( false, false, $cache_key );
			$this->lists     = $lists;
			$selected_list   = $this->get_selected_list( $submitted_data, $id );

			$this->is_empty_lists = empty( $lists );

			if ( empty( $lists ) ) {

				$empty_list_error = ! $is_form ? esc_html__( "You can't sync this provider because your account doesn't have any email list added. Please, go to your ActiveCampaign account to add one before retrying.", 'hustle' )
					: esc_html__( "You don't have any form added to your account to sync here.", 'hustle' );

				$options = array(
					array(
						'type'     => 'wrapper',
						'style'    => 'margin-bottom: 0;',
						'elements' => array(
							'options' => array(
								'type'  => 'notice',
								'icon'  => 'info',
								'class' => 'sui-notice-error',
								'value' => $empty_list_error,
							),
						),
					),
				);
			} else {
				$options = array(
					array(
						'type'     => 'wrapper',
						'style'    => 'margin-bottom: 0;',
						'elements' => array(
							'label'   => array(
								'type'  => 'label',
								'for'   => $id,
								'value' => ! $is_form ? __( 'Email List', 'hustle' ) : __( 'Choose Form', 'hustle' ),
							),
							'wrapper' => array(
								'type'                 => 'wrapper',
								'class'                => 'hui-select-refresh',
								'is_not_field_wrapper' => true,
								'elements'             => array(
									'lists'   => array(
										'type'     => 'select',
										'id'       => $id,
										'class'    => 'sui-select',
										'name'     => $id,
										'value'    => $selected_list,
										'options'  => $lists,
										'selected' => $selected_list,
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
			}

			return $options;
		}

		/**
		 * Get the options that should be completed for the form.
		 *
		 * @since 4.2.0
		 * @return array
		 */
		public function get_form_completion_options( $saved_form_settings ) {

			if ( 'form' === $saved_form_settings['sign_up_to'] ) {
				$this->form_completion_options[] = 'form_id';
				$this->form_completion_options[] = 'form_name';
			} else {
				$this->form_completion_options[] = 'list_id';
				$this->form_completion_options[] = 'list_name';
			}

			return $this->form_completion_options;
		}

	} // Class end.

endif;