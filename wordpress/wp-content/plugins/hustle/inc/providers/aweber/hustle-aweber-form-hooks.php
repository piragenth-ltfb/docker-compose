<?php

/**
 * Class Hustle_Aweber_Form_Hooks
 * Define the form hooks that are used by Aweber
 *
 * @since 4.0
 */
class Hustle_Aweber_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add Aweber data to entry.
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
		$utils                  = Hustle_Provider_Utils::get_instance();
		$addon_setting_values   = $form_settings_instance->get_form_settings_values();
		$global_multi_id        = $addon_setting_values['selected_global_multi_id'];

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                    $submitted_data
		 * @param int                                      $module_id                current module_id
		 * @param Hustle_Aweber_Form_Settings      $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_aweber_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		try {

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$api        = $addon->get_api( $global_multi_id );
			$account_id = $addon->get_account_id( $global_multi_id );

			$list_id = $addon_setting_values['list_id'];

			$submitted_data = $this->check_legacy( $submitted_data );
			$subscribe_data = $submitted_data;

			// Use the "name" field if set.
			if ( ! empty( $subscribe_data['name'] ) ) {
				$default_fields = array(
					'email' => '',
					'name'  => '',
				);

			} else {
				// Use "first_name" and "last_name" for "name" field.

				$default_fields = array();

				$name = array();

				if ( ! empty( $submitted_data['first_name'] ) ) {// Check first_name field first
					$name['first_name'] = $submitted_data['first_name'];
					unset( $subscribe_data['first_name'] );
				}
				if ( ! empty( $submitted_data['last_name'] ) ) { // Add last_name
					$name['last_name'] = $submitted_data['last_name'];
					unset( $subscribe_data['last_name'] );
				}
				$subscribe_data['name'] = implode( ' ', $name );

			}

			// Check/add custom fields.
			$custom_fields = $this->get_extra_fields( $submitted_data, $default_fields );

			if ( ! empty( $custom_fields ) ) {
				$subscribe_data['custom_fields'] = array();

				foreach ( $custom_fields as $key => $value ) {
					$subscribe_data['custom_fields'][ $key ] = $value;
					unset( $subscribe_data[ $key ] );
				}
			}

			if ( ! empty( $subscribe_data['custom_fields'] ) ) {
				$result        = $api->get_account_list_custom_fields( $account_id, $list_id );
				$custom_fields = array();
				if ( ! empty( $result->entries ) ) {
					$custom_fields = wp_list_pluck( $result->entries, 'name' );
				}

				// Add an underscore at the beginning of the field's name if it starts with "name"
				// since Aweber throws an error otherwise. It doesn't accept custom fields starting with "name".
				foreach ( $subscribe_data['custom_fields'] as $cf_name => $cf_val ) {
					if ( 0 === strpos( $cf_name, 'name' ) ) {
						$subscribe_data['custom_fields'][ '_' . $cf_name ] = $cf_val;
						unset( $subscribe_data['custom_fields'][ $cf_name ] );
					}
				}
				$new_fields = array_diff( array_keys( $subscribe_data['custom_fields'] ), $custom_fields );
				foreach ( $new_fields as $custom_field ) {
					$api->add_custom_field( $account_id, $list_id, array( 'name' => $custom_field ) );
				}
			}

			$search_data   = array( 'email' => $subscribe_data['email'] );
			$find_by_email = $api->find_account_list_subscriber( $account_id, $list_id, $search_data );

			$is_sent       = false;
			$member_status = __( 'Member could not be subscribed.', 'hustle' );

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
				'hustle_provider_aweber_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);

			// The user is already subscribed. Update it.
			if ( ! empty( $find_by_email ) && ! empty( $find_by_email->entries ) ) {
				$member_data = $find_by_email->entries[0];
				$subscriber  = $api->update_account_list_subscriber( $account_id, $list_id, $member_data->id, $subscribe_data );
				$action      = 'updated';

			} else {
				// Subscribe a new user.
				$subscriber = $api->add_account_list_subscriber( $account_id, $list_id, $subscribe_data );
				$action     = 'created';

			}

			/**
			 * Fires after adding subscriber
			 *
			 * @since 4.0.2
			 *
			 * @param int    $module_id
			 * @param array  $submitted_data
			 * @param mixed  $subscriber
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_aweber_after_add_subscriber',
				$module_id,
				$submitted_data,
				$subscriber,
				$form_settings_instance
			);

			$error_message = __( 'Something went wrong. Unable to add subscriber. ', 'hustle' );
			if ( empty( $subscriber ) || is_wp_error( $subscriber ) ) {
				throw new Exception( $error_message );
			}

			if ( 'created' === $action && ! empty( $subscriber->response ) && 400 <= intval( $subscriber->response['code'] ) ) {

				if ( ! empty( $subscriber->response['message'] ) && ! empty( $subscriber->response['code'] ) ) {
					$error_message = $error_message . $subscriber->response['code'] . ': ' . $subscriber->response['message'];
				}
				throw new Exception( $error_message );

			} elseif ( ! empty( $subscriber->error ) ) {

				$error_message = __( 'Something went wrong', 'hustle' );
				if ( is_string( $subscriber->error ) ) {
					$error_message = $subscriber->error;
				} elseif ( ! empty( $subscriber->error->message ) ) {
					$error_message = $subscriber->error->message;
				}

				throw new Exception( $error_message );
			}

			$is_sent = true;
			$details = __( 'Successfully added or updated member on Aweber list', 'hustle' );

			// Handle the response when adding a new subscriber and when updating one.
			if ( 'created' === $action ) {
				$member_status = __( 'Confirmation Pending ', 'hustle' );
				// We aren't retrieving the created subscriber so we don't know the current custom fields in order to check them.

			} else {
				$member_status            = $subscriber->status;
				$subscriber_custom_fields = $subscriber->custom_fields;

				if ( ! empty( $subscriber_custom_fields ) && ! empty( $subscribe_data['custom_fields'] ) ) {

					// Let's double check if all custom fields are successfully added
					$found_missing_field = array();
					foreach ( array_filter( $subscribe_data['custom_fields'] ) as $label => $field ) {

						// Check the custom field was actually updated.
						if ( empty( $subscriber_custom_fields->$label ) || $field !== $subscriber_custom_fields->$label ) {
							$found_missing_field[] = $label;
						}
					}

					if ( ! empty( $found_missing_field ) ) {
						$details = __( 'Some fields are not successfully added: ', 'hustle' ) . implode( ', ', $found_missing_field );
						throw new Exception( $details );
					}
				}
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
			'hustle_provider_aweber_entry_fields',
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
		$list_id                = $addon_setting_values['list_id'];
		$global_multi_id        = $addon_setting_values['selected_global_multi_id'];
		$api_key                = $addon->get_setting( 'api_key', null, $global_multi_id );

		$account_id = $addon->get_account_id( $global_multi_id );

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
			 * @param Hustle_Aweber_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_aweber_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			try {
				$api = $addon->get_api( $global_multi_id );

				$account_id = $addon->get_account_id( $global_multi_id );

				$list = $api->get_account_list( $account_id, $list_id );

				$search_data = array( 'email' => $submitted_data['email'] );

				$find_by_email = $api->find_account_list_subscriber( $account_id, $list_id, $search_data );

				if ( ! empty( $find_by_email ) && ! empty( $find_by_email->entries ) ) {
					$is_success = self::ALREADY_SUBSCRIBED_ERROR;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
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
		 * @param Hustle_Aweber_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_aweber_form_submitted_data_after_validation',
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
			$this->_subscriber[ md5( $data['email'] ) ] = $api->find_account_list_subscriber( $data['account_id'], $data['list_id'], array( 'email' => $data['email'] ) );
		}
		return $this->_subscriber[ md5( $data['email'] ) ];
	}

}