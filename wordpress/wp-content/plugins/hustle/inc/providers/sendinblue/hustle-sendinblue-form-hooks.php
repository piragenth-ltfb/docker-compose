<?php

/**
 * Class Hustle_SendinBlue_Form_Hooks
 * Define the form hooks that are used by SendinBlue
 *
 * @since 4.0
 */
class Hustle_SendinBlue_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {

	/**
	 * Add SendinBlue data to entry.
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
		$addon_setting_values   = $form_settings_instance->get_form_settings_values();

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                         $submitted_data
		 * @param int                                           $module_id                current Form ID
		 * @param Hustle_Sendinblue_Form_Settings               $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_sendinblue_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		try {

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$addon_setting_values = $form_settings_instance->get_form_settings_values();
			$is_sent              = false;
			$global_multi_id      = $addon_setting_values['selected_global_multi_id'];
			$list_id              = (int) $addon_setting_values['list_id'];
			$submitted_data       = $this->check_legacy( $submitted_data );
			$api                  = $addon::api( $addon->get_setting( 'api_key', '', $global_multi_id ) );

			$email         = $submitted_data['email'];
			$is_sent       = false;
			$member_status = __( 'Member could not be subscribed.', 'hustle' );
			$merge_vals    = array();

			if ( isset( $submitted_data['first_name'] ) && ! empty( $submitted_data['first_name'] ) ) {
				$submitted_data['FIRSTNAME'] = $submitted_data['first_name'];
			}
			if ( isset( $submitted_data['last_name'] ) && ! empty( $submitted_data['last_name'] ) ) {
				$submitted_data['LASTNAME'] = $submitted_data['last_name'];
			}

			// unset this as we don't need it
			unset( $submitted_data['first_name'] );
			unset( $submitted_data['last_name'] );

			foreach ( $submitted_data as $key => $sub_d ) {

				if ( 'email' === $key ) {
					continue;
				}

				$custom_fields[] = array(
					'name' => strtoupper( $key ),
				);

				$merge_vals[ strtoupper( $key ) ] = $sub_d;
			}

			// currently only supports text fields
			if ( ! empty( $merge_vals ) ) {

				// get custom fields
				$result     = $api->get_attributes();
				$api_fields = array();

				if ( ! empty( $result ) ) {
					$api_fields = wp_list_pluck( $result->attributes, 'name' );
				}

				$module      = new Hustle_Module_Model( $module_id );
				$_fields     = wp_list_pluck( $custom_fields, 'name' );
				$new_fields  = array_udiff( $_fields, $api_fields, 'strcasecmp' );
				$form_fields = $module->get_form_fields();
				$form_fields = array_change_key_case( $form_fields, CASE_UPPER );

				foreach ( $new_fields as $custom_field ) {
					// create custom fields
					$type = isset( $form_fields[ $custom_field ] ) ? $this->get_field_type( $form_fields[ $custom_field ]['type'] ) : 'text';
					$api->create_attributes( $custom_field, 'normal', array( 'type' => $type ) );
				}
			}

			// check if email exists
			try {
				$email_exists = $this->get_subscriber( $api, $email );
			} catch ( Exception $e ) {
				$email_exists = false;
			}

			$subscribe_data = array(
				'email'               => $email,
				'listIds'             => array( $list_id ),
				'smtpBlacklistSender' => array(),
				'updateEnabled'       => true,
			);

			if ( ! empty( $merge_vals ) ) {
				$subscribe_data['attributes'] = $merge_vals;
			}

			/**
			 * Fires before adding subscriber
			 *
			 * @since 4.0.2
			 *
			 * @param int    $module_id
			 * @param array  $submitted_data
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_sendinblue_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);

			// update contact if email exists
			if ( false === $email_exists ) {
				$res = $api->create_contact( $subscribe_data );
			} else {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
				$subscribe_data['listIds'] = array_unique( array_merge( $email_exists->listIds, $subscribe_data['listIds'] ) );
				$res                       = $api->update_contact( $subscribe_data );
			}

			/**
			 * Fires before adding subscriber
			 *
			 * @since 4.0.2
			 *
			 * @param int    $module_id
			 * @param array  $submitted_data
			 * @param mixed  $res
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_sendinblue_after_add_subscriber',
				$module_id,
				$submitted_data,
				$res,
				$form_settings_instance
			);

			if ( is_wp_error( $res ) ) {
				$details = $res->get_error_message();
			} else {
				$is_sent       = true;
				$details       = __( 'Successfully added or updated member on SendinBlue list', 'hustle' );
				$member_status = __( 'OK', 'hustle' );
			}

			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'       => $is_sent,
						'description'   => $details,
						'member_status' => $member_status,
					),
				),
			);
		} catch ( Exception $e ) {
			$entry_fields = $this->exception( $e );
		}

		if ( ! empty( $addon_setting_values['list_name'] ) ) {
			$entry_fields[0]['value']['list_name'] = $addon_setting_values['list_name'];
		}

		$entry_fields = apply_filters(
			'hustle_provider_sendinblue_entry_fields',
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
		$list_id                = (int) $addon_setting_values['list_id'];

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
			 * @param Hustle_Sendinblue_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_sendinblue_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			try {
				// triggers exception if not found.
				$existing_user = $this->get_subscriber( $api, $submitted_data['email'] );

				if ( ! empty( $existing_user ) && in_array( $list_id, $existing_user->listIds, true ) ) { //phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
					$is_success = self::ALREADY_SUBSCRIBED_ERROR;
				}
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
		 * @param Hustle_Sendinblue_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_sendinblue_form_submitted_data_after_validation',
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
		if ( empty( $this->_subscriber ) && ! isset( $this->_subscriber[ md5( $data ) ] ) ) {
			$this->_subscriber[ md5( $data ) ] = $api->get_contact( $data );
		}

		return $this->_subscriber[ md5( $data ) ];
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
				$type = 'date';
				break;
			case 'number':
			case 'phone':
				$type = 'float';
				break;
			default:
				$type = 'text';
				break;
		}

		return $type;
	}

}