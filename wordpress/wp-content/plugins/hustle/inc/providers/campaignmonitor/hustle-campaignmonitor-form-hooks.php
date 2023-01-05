<?php

/**
 * Class Hustle_Campaignmonitor_Form_Hooks
 * Define the form hooks that are used by Campaignmonitor
 *
 * @since 4.0
 */
class Hustle_Campaignmonitor_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {

	/**
	 * Add Campaignmonitor data to entry.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	public function add_entry_fields( $submitted_data ) {

		$addon                  = $this->addon;
		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;

		/**
		 * Filter Campaign Monitor submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                          $submitted_data
		 * @param int                                            $module_id                current Form ID
		 * @param Hustle_Campaignmonitor_Form_Settings $form_settings_instance Campaign Monitor Addon Form Settings instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_campaignmonitor_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		try {

			$addon_setting_values = $form_settings_instance->get_form_settings_values();
			$is_sent              = false;
			$global_multi_id      = $addon_setting_values['selected_global_multi_id'];
			$list_id              = $addon_setting_values['list_id'];
			$submitted_data       = $this->check_legacy( $submitted_data );
			$api                  = $addon::api( $addon->get_setting( 'api_key', '', $global_multi_id ) );

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$email         = $submitted_data['email'];
			$name          = array();
			$custom_fields = array();
			$update_fields = array();

			if ( isset( $submitted_data['first_name'] ) ) {
				$name['first_name'] = $submitted_data['first_name'];
			}
			if ( isset( $submitted_data['last_name'] ) ) {
				$name['last_name'] = $submitted_data['last_name'];
			}
			$name = implode( ' ', $name );

			// Remove unwanted fields
			foreach ( $submitted_data as $key => $sub_d ) {

				if ( 'email' === $key ||
					'first_name' === $key ||
					'last_name' === $key || 'gdpr' === $key ) {
					continue;
				}

				$custom_fields[] = array(
					'Key'   => $key,
					'Value' => $sub_d,
				);

				$_fields[ $key ] = $sub_d;
			}

			// currently only supports text fields
			if ( ! empty( $_fields ) ) {

				$result     = $api->get_list_custom_field( $list_id );
				$api_fields = array();

				if ( ! empty( $result ) ) {
					$api_fields = wp_list_pluck( $result, 'FieldName' );
				}

				$new_fields  = array_diff( array_keys( $_fields ), $api_fields );
				$module      = new Hustle_Module_Model( $module_id );
				$form_fields = $module->get_form_fields();

				foreach ( $new_fields as $custom_field ) {
					$type = isset( $form_fields[ $custom_field ] ) ? $this->get_field_type( $form_fields[ $custom_field ]['type'] ) : 'Text';
					$api->add_list_custom_field(
						$list_id,
						array(
							'FieldName' => $custom_field,
							'DataType'  => $type,
						)
					);
				}
			}

			try {
				$data = array(
					'list'  => $addon_setting_values['list_id'],
					'email' => $submitted_data['email'],
				);

				$email_exists = $this->get_subscriber( $api, $data );
			} catch ( Exception $e ) {
				$email_exists = false;
			}

			// ready the data to send
			$data_to_send = array(
				'EmailAddress'   => $email,
				'Name'           => $name,
				'Resubscribe'    => true,
				'CustomFields'   => $custom_fields,
				'ConsentToTrack' => 'unchanged',
			);

			if ( isset( $submitted_data['gdpr'] ) && 'on' === $submitted_data['gdpr'] ) {
				$data_to_send['ConsentToTrack'] = 'Yes';
			}

			/**
			 * Fires before adding subscriber to Campaign Monitor
			 *
			 * @since 4.0.2
			 *
			 * @param int                                            $form_id                current Form ID
			 * @param array                                          $submitted_data
			 * @param Hustle_Campaignmonitor_Form_Settings $form_settings_instance Campaign Monitor Addon Form Settings instance
			 */
			do_action( 'hustle_provider_campaignmonitor_before_add_subscriber', $module_id, $data_to_send, $form_settings_instance );

			if ( false === $email_exists ) {
				$api->add_subscriber( $list_id, $data_to_send );
			} else {
				$api->update_subscriber( $list_id, $data_to_send );
			}

			/**
			 * Fires before adding subscriber to Campaign Monitor
			 *
			 * @since 4.0.2
			 *
			 * @param int                                            $form_id                current Form ID
			 * @param array                                          $submitted_data
			 * @param Hustle_Campaignmonitor_Form_Settings $form_settings_instance Campaign Monitor Addon Form Settings instance
			 */
			do_action( 'hustle_provider_campaignmonitor_after_add_subscriber', $module_id, $data_to_send, $form_settings_instance );

			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'     => true,
						'description' => __( 'Successfully added or updated member on Campaign Monitor list', 'hustle' ),
						'list_name'   => $addon_setting_values['list_name'], // for delete reference
					),
				),
			);

		} catch ( Exception $e ) {
			$entry_fields = $this->exception( $e );
		}

		$entry_fields = apply_filters(
			'hustle_provider_campaignmonitor_entry_fields',
			$entry_fields,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);

		return $entry_fields;
	}


	/**
	 * Check whether the email is already subscribed.
	 *
	 * @since 4.0
	 *
	 * @param $submitted_data
	 * @return bool
	 */
	public function on_form_submit( $submitted_data, $allow_subscribed = true ) {

		$is_success             = true;
		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;
		$addon                  = $this->addon;
		$addon_setting_values   = $form_settings_instance->get_form_settings_values();
		$global_multi_id        = $addon_setting_values['selected_global_multi_id'];
		$api                    = $addon::api( $addon->get_setting( 'api_key', '', $global_multi_id ) );

		if ( empty( $submitted_data['email'] ) ) {
			return __( 'Required Field "email" was not filled by the user.', 'hustle' );
		}

		if ( ! $allow_subscribed ) {

			/**
			 * Filter submitted form data to be processed
			 *
			 * @since 4.0
			 *
			 * @param array                                    $submitted_data
			 * @param int                                      $module_id                current module_id
			 * @param Hustle_Local_List_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_campaignmonitor_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			try {
				$data = array(
					'list'  => $addon_setting_values['list_id'],
					'email' => $submitted_data['email'],
				);
				// triggers exception if not found set true there
				$this->get_subscriber( $api, $data );
				$is_success = self::ALREADY_SUBSCRIBED_ERROR;
			} catch ( Exception $e ) {
				$is_success = true;
			}
		}

		/**
		 * Return `true` if success, or **(string) error message** on fail
		 *
		 * @since 4.0
		 *
		 * @param bool                                     $is_success
		 * @param int                                      $module_id                current module_id
		 * @param array                                    $submitted_data
		 * @param Hustle_Local_List_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_campaignmonitor_form_submitted_data_after_validation',
			$is_success,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);

		// process filter
		if ( true !== $is_success ) {
			// only update `_submit_form_error_message` when not empty
			if ( ! empty( $is_success ) ) {
				$this->_submit_form_error_message = (string) $is_success;
			}

			return $is_success;
		}

		return true;
	}

	/**
	 * Get subscriber for providers
	 *
	 * This method is to be inherited
	 * And extended by child classes.
	 *
	 * Make use of the property `$_subscriber`
	 * Method to omit double api calls
	 *
	 * @since 4.0.2
	 *
	 * @param   object $api
	 * @param   mixed  $data
	 * @return  mixed   array/object API response on queried subscriber
	 */
	protected function get_subscriber( $api, $data ) {
		if ( empty( $this->_subscriber ) && ! isset( $this->_subscriber[ md5( $data['email'] ) ] ) ) {
			$this->_subscriber[ md5( $data['email'] ) ] = $api->get_subscriber( $data['list'], $data['email'] );
		}

		return $this->_subscriber[ md5( $data['email'] ) ];
	}

	/**
	 * Get supported fields
	 *
	 * This method is to be inherited
	 * and extended by child classes.
	 *
	 * List the fields supported by the
	 * provider
	 *
	 * @since 4.1
	 *
	 * @param string hustle field type
	 * @return string Api field type
	 */
	protected function get_field_type( $type ) {

		switch ( $type ) {
			case 'datepicker':
				$type = 'Date';
				break;
			case 'number':
			case 'phone':
				$type = 'Number';
				break;
			default:
				$type = 'Text';
				break;
		}

		return $type;
	}

}