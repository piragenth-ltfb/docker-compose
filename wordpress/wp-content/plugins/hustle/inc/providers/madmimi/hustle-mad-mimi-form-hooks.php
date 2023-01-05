<?php

/**
 * Class Hustle_Mad_Mimi_Form_Hooks
 * Define the form hooks that are used by Mad Mimi
 *
 * @since 4.0
 */
class Hustle_Mad_Mimi_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add Mad Mimi data to entry.
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
		 * @param Hustle_Mad_Mimi_Form_Settings            $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_mad_mimi_form_submitted_data',
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
			$username        = $addon->get_setting( 'username', '', $global_multi_id );
			$api             = $addon::api( $username, $api_key );

			$list_id                 = $addon_setting_values['list_id'];
			$submitted_data          = $this->check_legacy( $submitted_data );
			$subscribe_data          = array();
			$subscribe_data['email'] = $submitted_data['email'];

			$is_sent       = false;
			$member_status = __( 'Member could not be subscribed.', 'hustle' );

			$name = array();

			if ( ! empty( $submitted_data['first_name'] ) ) {
				$submitted_data['firstName'] = $submitted_data['first_name'];
			}
			if ( ! empty( $submitted_data['last_name'] ) ) {
				$submitted_data['lastName'] = $submitted_data['last_name'];
			}

			unset( $submitted_data['first_name'] );
			unset( $submitted_data['last_name'] );

			$exisiting_fields = array(
				'email',
				'firstName',
				'lastName',
				'city',
				'phone',
				'company',
				'title',
				'address',
				'state',
				'zip',
				'country',
			);
			// Remove unwanted fields
			foreach ( $submitted_data as $key => $sub_d ) {

				if ( in_array( $key, $exisiting_fields, true ) ) {
					continue;
				}

				$_fields[ $key ] = $sub_d;
				unset( $submitted_data[ $key ] );
			}

			if ( ! empty( $_fields ) ) {
				$submitted_data['auxData'] = $_fields;
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
				'hustle_provider_mad_mimi_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);

			// update if email exisits
			if ( ! empty( $email_exist->subscribers ) ) {
				$existing_member = $email_exist->subscribers[0];
				$exisiting_list  = $existing_member->subscriberLists; //phpcs:ignore
				$is_member       = $this->_is_memeber_on_list( $exisiting_list, $list_id );
				$lists           = array();

				if ( true !== $is_member ) {
					$lists   = $is_member;
					$lists[] = $list_id;
				}

				$res = $api->update_subscriber( $existing_member->id, $submitted_data, $lists );
			} else {
				$res = $api->subscribe( $list_id, $submitted_data );
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
				'hustle_provider_mad_mimi_after_add_subscriber',
				$module_id,
				$submitted_data,
				$res,
				$form_settings_instance
			);

			if ( is_wp_error( $res ) ) {
				$details = $res->get_error_message();
			} else {

				$is_sent       = true;
				$member_status = __( 'OK', 'hustle' );
				$details       = __( 'Successfully added or updated member on Mad Mimi list', 'hustle' );
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
		$global_multi_id        = $addon_setting_values['selected_global_multi_id'];
		$key                    = $addon->get_setting( 'api_key', '', $global_multi_id );
		$user                   = $addon->get_setting( 'username', '', $global_multi_id );
		$api                    = $addon::api( $user, $key );
		$list_id                = $addon_setting_values['list_id'];

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
				'hustle_provider_mad_mimi_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			try {
				// triggers exception if not found.
				$existing_member = $this->get_subscriber( $api, $submitted_data['email'] );
				// var_dump($existing_member);
				// if member exisits check if member is on a current list
				if ( ! empty( $existing_member->subscribers ) ) {
					$existing_member = $existing_member->subscribers[0];
					$exisiting_list  = $existing_member->subscriberLists; //phpcs:ignore

					if ( true === $this->_is_memeber_on_list( $exisiting_list, $list_id ) ) {
						$is_success = self::ALREADY_SUBSCRIBED_ERROR;
					}
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
		 * @param Hustle_Local_List_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_mad_mimi_form_submitted_data_after_validation',
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
	 * Check whether the member is on a list
	 *
	 * @since 4.0.2
	 *
	 * @param $existing_lists
	 * @param $list current list
	 * @return bool
	 */
	private function _is_memeber_on_list( $existing_lists, $list ) {
		$lists = array();
		foreach ( $existing_lists as $key => $exisiting_list ) {
			$lists[] = $exisiting_list->id;
			if ( absint( $list ) === $exisiting_list->id ) {
				return true;
			}
		}
		return $lists;
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
			$this->_subscriber[ md5( $data ) ] = $api->get_subscriber( array( 'query' => $data ) );
		}

		return $this->_subscriber[ md5( $data ) ];
	}
}