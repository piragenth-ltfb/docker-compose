<?php

/**
 * Class Hustle_InfusionSoft_Form_Hooks
 * Define the form hooks that are used by InfusionSoft
 *
 * @since 4.0
 */
class Hustle_InfusionSoft_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add InfusionSoft data to entry.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 *
	 * @return array
	 */
	public function add_entry_fields( $submitted_data ) {

		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;

		$response = array(
			'is_sent'       => false,
			'description'   => '',
			'data_sent'     => '',
			'data_received' => '',
			'url_request'   => '',
		);

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                    $submitted_data
		 * @param int                                      $module_id                current module_id
		 * @param Hustle_InfusionSoft_Form_Settings        $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_infusionsoft_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$addon_setting_values = $form_settings_instance->get_form_settings_values();

		$tags = $addon_setting_values['list_name'];

		try {
			$addon           = $this->addon;
			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$api_key         = $addon->get_setting( 'api_key', '', $global_multi_id );
			$account_name    = $addon->get_setting( 'account_name', '', $addon_setting_values['selected_global_multi_id'] );
			$api             = Hustle_Infusion_Soft::api( $api_key, $account_name );
			$message         = __( 'Successfully added or updated member on Infusionsoft list', 'hustle' );

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$submitted_data = $this->check_legacy( $submitted_data );

			if ( isset( $submitted_data['email'] ) ) {
				$submitted_data['Email'] = $submitted_data['email'];
			}
			if ( isset( $submitted_data['first_name'] ) ) {
				$submitted_data['FirstName'] = $submitted_data['first_name'];
			}
			if ( isset( $submitted_data['last_name'] ) ) {
				$submitted_data['LastName'] = $submitted_data['last_name'];
			}
			$submitted_data = array_diff_key(
				$submitted_data,
				array(
					'email'      => '',
					'first_name' => '',
					'last_name'  => '',
				)
			);

			$module = new Hustle_Module_Model( $module_id );
			if ( is_wp_error( $module ) ) {
				throw new Exception( $module->get_error_message() );
			}

			$utils         = Hustle_Provider_Utils::get_instance();
			$custom_fields = $api->get_custom_fields();

			// If there were errors when connecting to the api.
			if ( isset( $custom_fields->errors ) ) {

				$response = array(
					'is_sent'     => false,
					'description' => '',
				);

			} else { // If there weren't errors.

				$extra_custom_fields = array_diff_key( $submitted_data, array_fill_keys( $custom_fields, 1 ) );
				$found_extra         = array();
				$not_added_fields    = array();

				$unmatched_custom_fields_datatypes = $this->find_unmatched_custom_fields_datatypes( $submitted_data, $api->custom_fields_with_data_type );

				if ( ! empty( $extra_custom_fields ) ) {

					foreach ( $extra_custom_fields as $key => $value ) {

						// Remove underscores since this is how Infusionsoft stores custom fields.
						$name_from_key = str_replace( '_', '', $key );

						if ( in_array( $name_from_key, $custom_fields, true ) ) {
							$submitted_data[ $name_from_key ] = $value;
							unset( $submitted_data[ $key ] );
						} else {
							$found_extra[ $name_from_key ] = $value;
						}
					}

					// Add new custom fields.
					if ( ! empty( $found_extra ) ) {
						foreach ( $found_extra as $name => $value ) {
							$added_field = $api->add_custom_field( $name );

							if ( is_wp_error( $added_field ) || empty( $added_field ) ) {
								$not_added_fields[] = $name;
								// We coulnd't create the field - let not submit it.
								unset( $submitted_data[ $name ] );
							}
						}
					}
				}

				if ( ! empty( $not_added_fields ) ) {
					$message = __( "The contact was subscribed but these custom fields couldn't be added: ", 'hustle' ) . implode( ', ', $not_added_fields );
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
					'hustle_provider_infusionsoft_before_add_subscriber',
					$module_id,
					$submitted_data,
					$form_settings_instance
				);

				$email_exists = $api->email_exist( $submitted_data['Email'] );

				if ( $email_exists ) {
					$contact_id = $api->update_contact( $submitted_data );
				} else {
					$contact_id = $api->add_contact( $submitted_data );
				}

				/**
				 * Fires after adding subscriber
				 *
				 * @since 4.0.2
				 *
				 * @param int    $module_id
				 * @param array  $submitted_data
				 * @param mixed  $contact_id
				 * @param object $form_settings_instance
				 */
				do_action(
					'hustle_provider_infusionsoft_after_add_subscriber',
					$module_id,
					$submitted_data,
					$contact_id,
					$form_settings_instance
				);

				if ( is_wp_error( $contact_id ) ) {
					throw new Exception( $contact_id->get_error_message() );
				}

				$tag_id  = ! empty( $addon_setting_values['list_id'] ) ? $addon_setting_values['list_id'] : null;
				$tag_res = $api->add_tag_to_contact( $contact_id, $tag_id );

				if ( is_wp_error( $tag_res ) ) {
					// The tag id isn't selected, its value type isn't the correct type, or other errors.
					throw new Exception( $tag_res->get_error_message() );

				} elseif ( '0' === $tag_res ) {
					// The contact was added but couldn't be tagged. Happens when the selected tag doesn't exist in IS, for example.
					throw new Exception( __( "The contact was subscribed but it couldn't be tagged. Please make sure the selected tag exists.", 'hustle' ) );
				}

				$is_sent = true;

				if ( $unmatched_custom_fields_datatypes ) {
					$is_sent = false;
					$message = __( "The contact was subscribed but these custom fields' might not have been saved: ", 'hustle' ) . implode( ', ', $unmatched_custom_fields_datatypes );
				}

				$response = array(
					'is_sent'       => $is_sent,
					'description'   => $message,
					'tags_names'    => $tags,
					'data_sent'     => $utils->get_last_data_sent(),
					'data_received' => $utils->get_last_data_received(),
					'url_request'   => $utils->get_last_url_request(),
				);

			}
		} catch ( Exception $e ) {
			$entry_fields = $this->exception( $e );
		}

		if ( ! isset( $entry_fields ) ) {
			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => $response,
				),
			);
		}

		return apply_filters(
			'hustle_provider_infusionsoft_entry_fields',
			$entry_fields,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);
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
			 * @param Hustle_InfusionSoft_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_infusionsoft_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$api_key         = $addon->get_setting( 'api_key', '', $global_multi_id );
			$account_name    = $addon->get_setting( 'account_name', '', $addon_setting_values['selected_global_multi_id'] );
			$api             = Hustle_Infusion_Soft::api( $api_key, $account_name );
			$existing_member = $this->get_subscriber( $api, $submitted_data['email'] );

			if ( $existing_member ) {
				$is_success = self::ALREADY_SUBSCRIBED_ERROR;
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
		 * @param Hustle_InfusionSoft_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_infusionsoft_form_submitted_data_after_validation',
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
			$this->_subscriber[ md5( $data ) ] = $api->email_exist( $data );
		}

		return $this->_subscriber[ md5( $data ) ];
	}

	/**
	 * Check datatype of custom fields
	 *
	 * @param array $submitted_data Form data submitted by user.
	 * @param array $custom_fields Custom fields with their datatype.
	 *
	 * @return array
	 */
	private function find_unmatched_custom_fields_datatypes( $submitted_data, $custom_fields ) {
		$unmatched_fields = array();

		// 1  => phone. 15 => text. 16 => textarea. 18 => url. 19 => email.
		// 4 => percent. 7 => year. 11 => decimal number. 12 => whole number.
		$flexible_fields = array( 1, 15, 16, 18, 19, 4, 7, 11, 12 );

		foreach ( $submitted_data as $key => $value ) {
			if ( isset( $custom_fields[ $key ] ) && ! in_array( $custom_fields[ $key ], $flexible_fields, true ) ) {
				$unmatched_fields[] = $key;
			}
		}

		return $unmatched_fields;
	}

}