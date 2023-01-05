<?php

/**
 * Class Hustle_MauticForm_Hooks
 * Define the form hooks that are used by Mautic
 *
 * @since 4.0
 */
class Hustle_Mautic_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add Mautic data to entry.
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
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                     $submitted_data
		 * @param int                                       $module_id                current module_id
		 * @param Hustle_Mautic_Form_Settings               $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_mautic_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$addon_setting_values = $form_settings_instance->get_form_settings_values();

		try {

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$list_id = $addon_setting_values['list_id'];

			$submitted_data  = $this->check_legacy( $submitted_data );
			$global_multi_id = $addon_setting_values['selected_global_multi_id'];

			$url      = $addon->get_setting( 'url', '', $global_multi_id );
			$username = $addon->get_setting( 'username', '', $global_multi_id );
			$password = $addon->get_setting( 'password', '', $global_multi_id );

			if ( isset( $submitted_data['first_name'] ) ) {
				$submitted_data['firstname'] = $submitted_data['first_name'];
				unset( $submitted_data['first_name'] );
			}
			if ( isset( $submitted_data['last_name'] ) ) {
				$submitted_data['lastname'] = $submitted_data['last_name'];
				unset( $submitted_data['last_name'] );
			}

			$is_sent = false;
			$updated = false;

			$member_status = __( 'Member could not be subscribed.', 'hustle' );

			$api = $addon::api( $url, $username, $password );

			$existing_member = $this->get_subscriber( $api, $submitted_data['email'] );

			// Add extra fields
			$extra_data = array_diff_key(
				$submitted_data,
				array(
					'email'     => '',
					'firstname' => '',
					'lastname'  => '',
				)
			);
			$extra_data = array_filter( $extra_data );
			if ( ! empty( $extra_data ) ) {
				$module        = new Hustle_Module_Model( $module_id );
				$form_fields   = $module->get_form_fields();
				$custom_fields = array();
				foreach ( $extra_data as $key => $value ) {
					$type = isset( $form_fields[ $key ] ) ? $this->get_field_type( $form_fields[ $key ]['type'] ) : 'text';

					if ( 'date' === $type && 'Y-m-d' !== $form_fields[ $key ]['date_format'] && ! empty( $submitted_data[ $key ] ) ) {
						$submitted_data[ $key ] = date( 'Y-m-d', strtotime( $submitted_data[ $key ] ) );
					}
					$custom_fields[] = array(
						'label' => $key,
						'name'  => $key,
						'type'  => $type,
					);

					// Make the fields' names lowercase so they match the "alias" from Mautic's side.
					if ( strtolower( $key ) !== $key ) {
						$submitted_data[ strtolower( $key ) ] = $submitted_data[ $key ];
						unset( $submitted_data[ $key ] );
					}
				}
				$addon->add_custom_fields( $custom_fields, $api );
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
				'hustle_provider_mautic_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);
			$submitted_data['ipAddress'] = Opt_In_Geo::get_user_ip();

			if ( false !== $existing_member && ! is_wp_error( $existing_member ) ) {
				$contact_id = $api->update_contact( $existing_member, $submitted_data );
				$updated    = true;
			} else {
				$contact_id = $api->add_contact( $submitted_data );
			}

			if ( is_wp_error( $contact_id ) ) {
				// Remove ipAddress
				unset( $submitted_data['ipAddress'] );
				$error_code = $contact_id->get_error_code();
				$details    = $contact_id->get_error_message( $error_code );
			} elseif ( $updated ) {
				$is_sent       = true;
				$details       = __( 'Successfully updated member on Mautic list', 'hustle' );
				$member_status = __( 'OK', 'hustle' );
			} elseif ( ! $updated ) {
				$api->add_contact_to_segment( $list_id, $contact_id );

				$is_sent       = true;
				$details       = __( 'Successfully added member on Mautic list', 'hustle' );
				$member_status = __( 'Added', 'hustle' );
			} else {

				$is_sent       = true;
				$details       = __( 'Successfully updated member on Mautic list', 'hustle' );
				$member_status = __( 'Updated', 'hustle' );
			}

			/**
			 * Fires before adding subscriber
			 *
			 * @since 4.0.2
			 *
			 * @param int    $module_id
			 * @param array  $submitted_data
			 * @param mixed  $contact_id
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_mailerlite_after_add_subscriber',
				$module_id,
				$submitted_data,
				$contact_id,
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
			 * @param Hustle_Mautic_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_mautic_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$url             = $addon->get_setting( 'url', '', $global_multi_id );
			$username        = $addon->get_setting( 'username', '', $global_multi_id );
			$password        = $addon->get_setting( 'password', '', $global_multi_id );
			$api             = $addon::api( $url, $username, $password );
			$existing_member = $this->get_subscriber( $api, $submitted_data['email'] );

			if ( false !== $existing_member && ! is_wp_error( $existing_member ) ) {
				$is_success = self::ALREADY_SUBSCRIBED_ERROR;
			}
		}

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                    $submitted_data
		 * @param int                                      $module_id                current module_id
		 * @param Hustle_Mautic_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_mautic_form_submitted_data_after_validation',
			$is_success,
			$module_id,
			$submitted_data,
			$module_id,
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
				break;
			case 'phone':
				break;
			case 'url':
				break;
			case 'time':
				break;
			default:
				$type = 'text';
				break;
		}

		return $type;
	}
}