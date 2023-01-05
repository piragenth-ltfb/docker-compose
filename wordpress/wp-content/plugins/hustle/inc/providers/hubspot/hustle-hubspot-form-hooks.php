<?php

/**
 * Class Hustle_HubSpot_Form_Hooks
 * Define the form hooks that are used by HubSpot
 *
 * @since 4.0
 */
class Hustle_HubSpot_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add HubSpot data to entry.
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
		$res                    = array();

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                    $submitted_data
		 * @param int                                      $module_id                current module_id
		 * @param Hustle_Hubspot_Form_Settings             $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_hubspot_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$addon_setting_values = $form_settings_instance->get_form_settings_values();

		try {
			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$api            = $addon->api();
			$list_id        = $addon_setting_values['list_id'];
			$submitted_data = $this->check_legacy( $submitted_data );

			$is_sent       = false;
			$member_status = __( 'Member could not be subscribed.', 'hustle' );
			$details       = __( 'Unable to add this subscriber', 'hustle' );

			if ( ! $api || $api->is_error ) {
				throw new Exception( __( 'Wrong API credentials', 'hustle' ) );
			}

			// Extra fields
			$extra_data = array_diff_key(
				$submitted_data,
				array(
					'email'      => '',
					'first_name' => '',
					'last_name'  => '',
				)
			);

			if ( ! empty( $extra_data ) ) {
				$custom_fields = array();
				$module        = new Hustle_Module_Model( $module_id );
				$form_fields   = $module->get_form_fields();
				foreach ( $extra_data as $key => $value ) {
					$type = isset( $form_fields[ $key ] ) ? $this->get_field_type( $form_fields[ $key ]['type'] ) : 'text';

					if ( 'date' === $type && isset( $submitted_data[ $key ] ) && ! empty( $submitted_data[ $key ] ) ) {
						// hubspot needs date in milisecond unix time.
						$submitted_data[ $key ] = strtotime( $submitted_data[ $key ] ) * 1000;
					}

					$custom_fields[] = array(
						'name'  => $key,
						'label' => $key,
						'type'  => $type,
					);
				}

				$addon->add_custom_fields( $custom_fields );
			}

			$email_exist = $this->get_subscriber( $api, $submitted_data['email'] );

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
				'hustle_provider_hubspot_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);

			if ( $email_exist && ! empty( $email_exist->vid ) ) {
				// Add to list
				$contact_id = '';

				if ( ! empty( $email_exist->{'list-memberships'} ) ) {
					$lists = wp_list_pluck( $email_exist->{'list-memberships'}, 'static-list-id' );
					if ( ! in_array( absint( $list_id ), $lists, true ) ) {
						$contact_id = $email_exist->vid;
					}
				}

				$res = $api->update_contact( $email_exist->vid, $submitted_data );

				if ( is_wp_error( $res ) ) {
					$details = $res->get_error_message();
				} elseif ( true !== $res ) {
					$details = __( 'Unable to update this contact to contact list.', 'hustle' );
				} else {
					$is_sent       = true;
					$member_status = __( 'OK', 'hustle' );
					$details       = __( 'Successfully updated member on HubSpot list', 'hustle' );
				}
			} else {
				$contact_id = $api->add_contact( $submitted_data );

				if ( is_wp_error( $contact_id ) ) {
					$details = $contact_id->get_error_message();
				} elseif ( isset( $contact_id->status ) && 'error' === $contact_id->status ) {
					$details = $contact_id->message;
				}
			}

			// Add contact to contact list
			if ( ! empty( $contact_id ) && ! is_object( $contact_id ) && (int) $contact_id > 0 ) {
				$res = $api->add_to_contact_list( $contact_id, $submitted_data['email'], $list_id );

				if ( is_wp_error( $res ) ) {
					$details = $res->get_error_message();
				} elseif ( true !== $res ) {
					$details = __( 'Unable to add this contact to contact list.', 'hustle' );
				} else {
					$is_sent       = true;
					$member_status = __( 'OK', 'hustle' );
					$details       = __( 'Successfully added or updated member on HubSpot list', 'hustle' );
				}
			}

			/**
			 * Fires after adding subscriber
			 *
			 * @since 4.0.2
			 *
			 * @param int    $module_id
			 * @param array  $submitted_data
			 * @param mixed  $res
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_hubspot_after_add_subscriber',
				$module_id,
				$submitted_data,
				$res,
				$form_settings_instance
			);

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
			'hustle_provider_' . $addon->get_slug() . '_entry_fields',
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
		$form_settings_instance = $this->form_settings_instance;
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
			 * @param Hustle_HubSpot_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_hubspot_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$api             = $addon->api();
			$list_id         = $addon_setting_values['list_id'];
			$existing_member = false;
			$member          = $this->get_subscriber( $api, $submitted_data['email'] );
			$existing_member = false;
			if ( $member && ! empty( $member->vid ) && ! empty( $member->{'list-memberships'} ) ) {
				$lists = wp_list_pluck( $member->{'list-memberships'}, 'static-list-id' );

				$existing_member = in_array( absint( $list_id ), $lists, true );
			}

			if ( false !== $existing_member ) {
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
		 * @param Hustle_Hubspot_Form_Settings             $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_hubspot_form_submitted_data_after_validation',
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
			$this->_subscriber[ md5( $data ) ] = $api->email_exists( $data );
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
				$type = 'number';
				break;
			default:
				$type = 'text';
				break;
		}

		return $type;
	}

}