<?php
/**
 * Mailster's form settings class.
 *
 * @package hustle
 *
 * @since 4.4.0
 */

/**
 * Class Hustle_Mailster_Form_Settings
 *
 * @since 4.4.0
 */
class Hustle_Mailster_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

	/**
	 * Options that must be set in order to consider the integration as "connected" to the form.
	 *
	 * @since 4.4.0
	 * @var array
	 */
	protected $form_completion_options = array( 'list_id', 'list_name', 'fields_map' );

	/**
	 * For settings Wizard steps
	 *
	 * @since 4.4.0
	 * @return array
	 */
	public function form_settings_wizards() {
		return array(
			array(
				'callback'     => array( $this, 'first_step_callback' ),
				'is_completed' => array( $this, 'first_step_is_completed' ),
			),
			array(
				'callback'     => array( $this, 'map_fields_step' ),
				'is_completed' => array( $this, 'map_fields_step_is_completed' ),
			),
		);
	}

	/**
	 * Check if the first step is completed.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The submitted data.
	 * @return bool
	 */
	public function first_step_is_completed( $submitted_data ) {
		$saved_settings = $this->get_form_settings_values();
		return empty( $this->validate_first_step( $saved_settings ) );
	}

	/**
	 * Returns all settings and conditions for 1st step of e-Newsletter settings
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The submitted data.
	 * @param bool  $is_submit Whether the request is a submission.
	 *
	 * @return array
	 */
	public function first_step_callback( $submitted_data, $is_submit ) {
		$defaults = array(
			'list_id'      => '',
			'single_optin' => '0',
		);

		$this->addon_form_settings = $this->get_current_data( $defaults, $submitted_data );

		$is_connected = $this->provider->is_form_connected( $this->module_id );

		// Save only after the step has been validated and there are no errors.
		if ( $is_submit ) {
			$error_message = $this->validate_first_step( $submitted_data );
		}

		$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Mailster List', 'hustle' ),
			__( "Choose the subscriber's list to which you want to send form data.", 'hustle' )
		);

		if ( ! empty( $error_message ) ) {
			$step_html .= '<span class="sui-error-message">' . $error_message . '</span>';
		}

		$step_html .= $this->get_first_step_html( $submitted_data );

		$buttons = array();
		if ( $is_connected ) {
			$buttons['disconnect'] = array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup(
					__( 'Disconnect', 'hustle' ),
					'sui-button-ghost',
					'disconnect_form',
					true
				),
			);
		}

		$buttons['continue'] = array(
			'markup' => Hustle_Provider_Utils::get_provider_button_markup(
				__( 'Continue', 'hustle' ),
				$is_connected ? '' : 'sui-button-right',
				'next',
				true
			),
		);

		if ( $is_submit && empty( $error_message ) ) {
			$this->save_first_step_data( $submitted_data );
		}

		$response = array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => ! empty( $error_message ),
		);

		return $response;
	}

	/**
	 * Return an array of options used to display the settings of the 1st step.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The data submitted.
	 *
	 * @return array
	 */
	private function get_first_step_html( $submitted_data ) {
		$lists = array();

		$fetched_lists = mailster( 'lists' )->get();

		if ( is_array( $fetched_lists ) && ! empty( $fetched_lists ) ) {
			$lists = wp_list_pluck( $fetched_lists, 'name', 'ID' );
		}

		$options = array(
			array(
				'type'     => 'wrapper',
				'elements' => array(
					array(
						'type'  => 'label',
						'value' => __( 'Email List', 'hustle' ),
						'for'   => 'hustle-email-provider-lists',
					),
					array(
						'type'     => 'select',
						'name'     => 'list_id',
						'id'       => 'hustle-email-provider-lists',
						'selected' => $this->addon_form_settings['list_id'],
						'options'  => $lists,
					),
				),
			),
			array(
				'type'     => 'wrapper',
				'style'    => 'margin-bottom: 0;',
				'elements' => array(
					'label'     => array(
						'type'  => 'label',
						'value' => __( 'Extra Options', 'hustle' ),
					),
					'new_users' => array(
						'type'       => 'checkbox',
						'name'       => 'single_optin',
						'value'      => '1',
						'id'         => 'single_optin',
						'class'      => 'sui-checkbox-sm sui-checkbox-stacked',
						'attributes' => array(
							'checked' => '1' === $this->addon_form_settings['single_optin'],
						),
						'label'      => __( 'Automatically opt-in new users to the mailing list', 'hustle' ),
					),
				),
			),
		);

		return Hustle_Provider_Utils::get_html_for_options( $options );
	}

	/**
	 * Validates the data submitted on the first step.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The submitted data.
	 * @return string|false Error message on failed validation. False on success.
	 */
	private function validate_first_step( $submitted_data ) {
		if ( ! isset( $submitted_data['list_id'] ) ) {
			return __( 'The email list is required.', 'hustle' );
		}

		return false;
	}

	/**
	 * Saves the data of the first step.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data Submitted data.
	 */
	private function save_first_step_data( $submitted_data ) {
		$selected_list = mailster( 'lists' )->get( $submitted_data['list_id'] );

		$this->addon_form_settings['list_id']   = $submitted_data['list_id'];
		$this->addon_form_settings['list_name'] = $selected_list->name;

		$this->save_form_settings_values( $this->addon_form_settings );
	}

	/**
	 * Checks whether the fields mapping step was completed.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The submitted data.
	 *
	 * @return bool
	 */
	public function map_fields_step_is_completed( $submitted_data ) {
		return empty( $this->validate_map_fields_step( $submitted_data ) );
	}

	/**
	 * Handles the step for mapping the fields.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data Data sent in the form submission when the request was a form submission.
	 * @param bool  $is_submit Whether the request was a form submission.
	 *
	 * @return array
	 */
	public function map_fields_step( $submitted_data, $is_submit ) {
		if ( $is_submit ) {
			$error_message = $this->validate_map_fields_step( $submitted_data );

			// Save only after the step has been validated and there are no errors.
			if ( empty( $error_message ) ) {
				$this->save_map_fields_step( $submitted_data );
			}
		}

		$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Map Fields', 'hustle' ),
			__( 'Map your Hustle fields to Mailster’s List fields below. Unmapped fields will be skipped.', 'hustle' )
		);

		if ( ! empty( $error_message ) ) {
			$step_html .= '<span class="sui-error-message">' . $error_message . '</span>';
		}

		$step_html .= $this->get_map_fields_step_html();

		$buttons = array(
			'back' => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup(
					__( 'Back', 'hustle' ),
					'sui-button-ghost',
					'prev',
					true
				),
			),
			'save' => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup(
					__( 'Save', 'hustle' ),
					'',
					'next',
					true
				),
			),
		);

		return array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => ! empty( $error_message ),
			'size'       => 'large',
		);
	}

	/**
	 * Validates the submission for mapping the fields.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The data submitted.
	 * @return string|false
	 */
	private function validate_map_fields_step( $submitted_data ) {
		if ( empty( $submitted_data['fields_map'] ) ) {
			return __( 'Mapping the fields is required.', 'hustle' );
		}

		// This is the only required field in Mailster.
		if ( empty( $submitted_data['fields_map']['email'] ) ) {
			return __( 'The following fields are required: Email', 'hustle' );
		}

		return false;
	}

	/**
	 * Saves the values from the step for mapping the fields.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The data submitted.
	 */
	private function save_map_fields_step( $submitted_data ) {
		$data_to_save = $this->get_form_settings_values();

		$data_to_save['fields_map'] = array_filter( $submitted_data['fields_map'] );

		$this->save_form_settings_values( $data_to_save );
	}

	/**
	 * Returns the markup for the step to map the fields.
	 *
	 * @since 4.4.0
	 *
	 * @return string
	 */
	private function get_map_fields_step_html() {
		// Base Mailster's fields. Couldn't find a method to retrieve them. Thus, they're hardcoded.
		$base_fields = array(
			'email'     => array(
				'type' => 'textfield',
				'name' => __( 'Email', 'hustle' ),
			),
			'firstname' => array(
				'type' => 'textfield',
				'name' => __( 'First name', 'hustle' ),
			),
			'lastname'  => array(
				'type' => 'textfield',
				'name' => __( 'Last name', 'hustle' ),
			),
		);

		$mailster_fields = $base_fields + mailster()->get_custom_fields();

		$saved_settings = $this->get_form_settings_values();
		$module_fields  = $this->get_form_fields_for_map_step();

		$html  = '<table class="sui-table">';
		$html .= '<thead><tr>
			<th>' . esc_html__( 'Provider Field', 'hustle' ) . '</th>
			<th>' . esc_html__( 'Hustle Field', 'hustle' ) . '</th>
		</tr></thead>';
		$html .= '<tbody>';

		$selected = '';

		foreach ( $mailster_fields as $field_id => $field ) {

			$selected = ! empty( $saved_settings['fields_map'][ $field_id ] ) ?
				$saved_settings['fields_map'][ $field_id ] :
				'';

			$field_label = $field['name'];

			// This is the subscriber's email field. Map it to the static one.
			if ( 'email' === $field_id ) {
				$field_label   .= '<span class="integrations-required-field">*</span>';
				$fields_options = $this->get_main_email_field_for_map_step();
				$selected       = 'email';

			} else {
				$fields_options = $module_fields;
			}

			$field['id'] = $field_id;

			$html .= '<tr>';
			$html .= '<td>' . $field_label . '</td>';
			$html .= '<td>' . $this->get_form_field_select( $field, $fields_options, $selected ) . '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';

		$message = sprintf(
			/* translators: 1. opening 'b' tag, 2. closing 'b' tag, 3. closing and opening 'p' tag. */
			esc_html__( "%1\$sImportant!%2\$s If you've just added new fields in your hustle module, you need to first save your changes for the fields to show up in the dropdown above.%3\$sAlso, when a field’s name is changed in hustle, its mapping will be lost, and you’ll need to re-map it in the integration." ),
			'<b>',
			'</b>',
			'</p><p>'
		);

		$notice_options = array(
			array(
				'type'  => 'inline_notice',
				'value' => $message,
				'icon'  => 'info',
				'class' => 'sui-notice-info',
			),
		);

		$html .= $this->get_renderer()->get_html_for_options( $notice_options, true );

		return $html;
	}

	/**
	 * Gets the markup for the fields' select.
	 *
	 * @since 4.4.0
	 *
	 * @param array  $mailster_field Mailster field to display the options for.
	 * @param array  $module_fields List of the module's saved fields to choose from.
	 * @param string $selected Currently selected value.
	 *
	 * @return array
	 */
	private function get_form_field_select( $mailster_field, $module_fields, $selected ) {
		$options = array(
			array(
				'type'        => 'select',
				'name'        => 'fields_map[' . $mailster_field['id'] . ']',
				'class'       => 'sui-select',
				'placeholder' => 'email' !== $mailster_field['id'] ? __( 'None', 'hustle' ) : false,
				'options'     => $module_fields,
				'selected'    => $selected,
				'id'          => 'select-fields_map_' . $mailster_field['id'],
			),
		);
		return $this->get_renderer()->get_html_for_options( $options, true );
	}
}