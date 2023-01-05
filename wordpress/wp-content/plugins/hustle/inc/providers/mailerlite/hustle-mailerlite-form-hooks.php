<?php

/**
 * Class Hustle_MailerLite_Form_Hooks
 * Define the form hooks that are used by MailerLite
 *
 * @since 4.0
 */
class Hustle_MailerLite_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add MailerLite data to entry.
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
		 * @param array                                    $submitted_data
		 * @param int                                      $module_id                current module_id
		 * @param Hustle_MailerLite_Form_Settings          $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_mailerlite_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$addon_setting_values = $form_settings_instance->get_form_settings_values();

		try {
			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$api_key         = $addon->get_setting( 'api_key', '', $global_multi_id );
			$api             = $addon::api( $api_key );

			$list_id        = $addon_setting_values['list_id'];
			$submitted_data = $this->check_legacy( $submitted_data );
			$email          = $submitted_data['email'];
			$merge_vals     = array();

			if ( isset( $submitted_data['first_name'] ) ) {
				$merge_vals['name'] = $submitted_data['first_name'];
			}
			if ( isset( $submitted_data['last_name'] ) ) {
				$merge_vals['last_name'] = $submitted_data['last_name'];
			}

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
				$custom_fields = array();
				$module        = new Hustle_Module_Model( $module_id );
				$form_fields   = $module->get_form_fields();
				foreach ( $extra_data as $key => $value ) {
					$type            = isset( $form_fields[ $key ] ) ? $this->get_field_type( $form_fields[ $key ]['type'] ) : 'text';
					$custom_fields[] = array(
						'name' => $key,
						'type' => $type,
					);
				}
				$addon->add_custom_fields( $custom_fields, $api );
				$merge_vals = array_merge( $merge_vals, $extra_data );
			}

			$existing_member = $this->get_subscriber(
				$addon,
				array(
					'list_id' => $list_id,
					'email'   => $email,
					'api'     => $api,
				)
			);

			$is_sent       = false;
			$member_status = __( 'Member could not be subscribed.', 'hustle' );

			$subscriber_data = array(
				'email' => $email,
			);
			if ( ! empty( $merge_vals ) ) {
				$subscriber_data['fields'] = $merge_vals;
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
				'hustle_provider_mailerlite_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);

			if ( $existing_member ) {
				$details = $api->update_subscriber( $email, $subscriber_data, 1 );
				$res     = $details;
			} else {
				$res = $api->add_subscriber( $list_id, $subscriber_data, 1 );
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
				'hustle_provider_mailerlite_after_add_subscriber',
				$module_id,
				$submitted_data,
				$res,
				$form_settings_instance
			);

			if ( is_wp_error( $res ) ) {
				$details = $res->get_error_message();
			} elseif ( empty( $res ) ) {
				$details = __( 'Something went wrong.', 'hustle' );
			} else {
				$is_sent       = true;
				$member_status = $res['type'];
				$details       = __( 'Successfully added or updated member on MailerLite list', 'hustle' );
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
			'hustle_provider_mailerlite_entry_fields',
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
			 * @param Hustle_MailerLite_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_mailerlite_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$api_key         = $addon->get_setting( 'api_key', '', $global_multi_id );
			$api             = $addon::api( $api_key );
			$list_id         = $addon_setting_values['list_id'];
			$existing_member = $this->get_subscriber(
				$addon,
				array(
					'list_id' => $list_id,
					'email'   => $submitted_data['email'],
					'api'     => $api,
				)
			);

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
		 * @param Hustle_MailerLite_Form_Settings          $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_mailerlite_form_submitted_data_after_validation',
			$is_success,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);

		// process filter
		if ( true !== $is_success ) {
			// only update `_submit_form_error_message` when not empty
			if ( $existing_member ) {
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
			$this->_subscriber[ md5( $data['email'] ) ] = $api->_email_exists( $data['list_id'], $data['email'], $data['api'] );
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
				$type = 'DATE';
				break;
			case 'number':
			case 'phone':
				$type = 'NUMBER';
				break;
			default:
				$type = 'TEXT';
				break;
		}

		return $type;
	}
}