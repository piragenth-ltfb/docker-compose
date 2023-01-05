<?php
if ( ! class_exists( 'Hustle_Local_List_Form_Settings' ) ) :

	/**
	 * Class Hustle_Local_List_Form_Settings
	 * Form Settings Local List Process
	 */
	class Hustle_Local_List_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

		/**
		 * Options that must be set in order to consider the integration as "connected" to the form.
		 *
		 * @since 4.2.0
		 * @var array
		 */
		protected $form_completion_options = array( 'local_list_name' );

		/**
		 * For settings Wizard steps
		 *
		 * @since 4.0
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
		 * @since 4.0
		 * @return bool
		 */
		public function first_step_is_completed() {
			$this->addon_form_settings = $this->get_form_settings_values();

			return ! empty( $this->addon_form_settings['local_list_name'] );
		}

		/**
		 * Returns all settings and conditions for 1st step of Local List settings
		 *
		 * @since 3.0.5
		 * @since 4.0 param $validate removed.
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function first_step_callback( $submitted_data ) {

			$this->addon_form_settings = $this->get_form_settings_values();

			$current_data = array(
				'local_list_name' => '',
			);

			$is_submit = ! empty( $submitted_data['hustle_is_submit'] );

			$current_data = $this->get_current_data( $current_data, $submitted_data );

			if ( $is_submit && empty( $submitted_data['local_list_name'] ) ) {
				$error_message = __( 'Please add a valid list name.', 'hustle' );
			}

			$options = $this->get_first_step_options( $current_data );

			$entries_page = add_query_arg(
				array(
					'page' => Hustle_Data::ENTRIES_PAGE,
				),
				get_admin_url( get_current_blog_id(), 'admin.php' )
			);
			$step_html    = Hustle_Provider_Utils::get_integration_modal_title_markup(
				__( 'Hustle\'s Local List', 'hustle' ),
				sprintf(
					__( 'Save the submissions in your database so you can access them or export them from the %1$sEmail Lists%2$s page. Local list (when active) also stores the status of active third party apps for each submission.', 'hustle' ),
					'<a href="' . $entries_page . '" target="_blank">',
					'</a>'
				)
			);

			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			if ( ! isset( $error_message ) ) {
				$has_errors = false;
			} else {
				$step_html .= '<span class="sui-error-message">' . $error_message . '</span>';
				$has_errors = true;
			}

			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Disconnect', 'hustle' ), 'sui-button-ghost', 'disconnect_form', true ),
				),
				'save'       => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Save', 'hustle' ), '', 'connect', true ),
				),
			);

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => $has_errors,
			);

			// Save only after the step has been validated and there are no errors
			if ( $is_submit && ! $has_errors ) {
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
			$name = ! empty( $submitted_data['local_list_name'] ) ? $submitted_data['local_list_name'] : '';

			$options = array(
				'list_id_setup' => array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						'label'           => array(
							'type'  => 'label',
							'for'   => 'local_list_name',
							'value' => __( 'Email List name', 'hustle' ),
						),
						'local_list_name' => array(
							'type'        => 'text',
							'name'        => 'local_list_name',
							'value'       => $name,
							'id'          => 'local_list_name',
							'placeholder' => __( 'Choose a name for this email list', 'hustle' ),
						),
						'description'     => array(
							'type'  => 'description',
							'value' => __( 'This will be visible to the visitors while unsubscribing.', 'hustle' ),
						),
					),
				),
			);

			return $options;
		}

	} // Class end.

endif;