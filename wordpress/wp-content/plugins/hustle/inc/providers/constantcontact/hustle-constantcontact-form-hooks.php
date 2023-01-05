<?php

/**
 * Class Hustle_ConstantContact_Form_Hooks
 * Define the form hooks that are used by ConstantContact
 *
 * @since 4.0
 */
class Hustle_ConstantContact_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add ConstantContact data to entry.
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
		 * Filter Campaign Monitor submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                          $submitted_data
		 * @param int                                            $module_id                current Form ID
		 * @param Hustle_ConstantContact_Form_Settings           $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_constantcontact_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		try {
			$api = $addon->api();

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$list_id = $addon_setting_values['list_id'];

			$submitted_data = $this->check_legacy( $submitted_data );

			$is_authorize = (bool) $api->get_token( 'access_token' );

			if ( ! $is_authorize ) {
				throw new Exception( __( 'Wrong API credentials', 'hustle' ) );
			}

			// check exists
			$exists = $this->get_subscriber(
				$api,
				array(
					'email'   => $submitted_data['email'],
					'list_id' => $addon_setting_values['list_id'],
				)
			);

			$is_sent = false;
			$details = __( 'Something went wrong.', 'hustle' );

			$first_name = isset( $submitted_data['first_name'] ) ? $submitted_data['first_name'] : '';
			$last_name  = isset( $submitted_data['last_name'] ) ? $submitted_data['last_name'] : '';

			$custom_fields = array_diff_key(
				$submitted_data,
				array(
					'email'      => '',
					'first_name' => '',
					'last_name'  => '',
				)
			);

			$custom_fields = array_filter( $custom_fields );
			$data_to_send  = $submitted_data;

			/**
			 * Fires before adding subscriber to Constant Contact
			 *
			 * @since 4.0.2
			 *
			 * @param int                                            $form_id                current Form ID
			 * @param array                                          $submitted_data
			 * @param Hustle_ConstantContact_Form_Settings           $form_settings_instance
			 */
			do_action(
				'hustle_provider_constantcontact_before_add_subscriber',
				$module_id,
				$data_to_send,
				$form_settings_instance
			);

			if ( $exists ) {
				$response = $api->updateSubscription( $exists, $first_name, $last_name, $list_id, $custom_fields );
			} else {
				$response = $api->subscribe( $submitted_data['email'], $first_name, $last_name, $list_id, $custom_fields );
			}

			if ( isset( $response ) ) {
				$is_sent = true;
				$details = __( 'Successfully added or updated member on Constant Contact list', 'hustle' );
			}

			/**
			 * Fires after adding subscriber to Constant Contact
			 *
			 * @since 4.0.2
			 *
			 * @param int                                            $form_id                current Form ID
			 * @param array                                          $submitted_data
			 * @param Hustle_ConstantContact_Form_Settings           $form_settings_instance
			 * @param mixed                                          $response
			 */
			do_action(
				'hustle_provider_constantcontact_after_add_subscriber',
				$module_id,
				$data_to_send,
				$form_settings_instance,
				$response
			);

			$contact       = $api->get_contact( $submitted_data['email'] );
			$member_status = $contact->status;

			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'       => $is_sent,
						'description'   => $details,
						'member_status' => $member_status,
						'data_member'   => $contact,
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
			'hustle_provider_constantcontact_entry_fields',
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
			 * @param Hustle_ConstantContact_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_constantcontact_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$api    = $addon->api();
			$exists = $this->get_subscriber(
				$api,
				array(
					'email'   => $submitted_data['email'],
					'list_id' => $addon_setting_values['list_id'],
				)
			);

			if ( $exists ) {
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
		 * @param Hustle_ConstantContact_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_constantcontact_form_submitted_data_after_validation',
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
			$this->_subscriber[ md5( $data['email'] ) ] = $api->get_contact( $data['email'] );
		}
		return $this->_subscriber[ md5( $data['email'] ) ];
	}

}