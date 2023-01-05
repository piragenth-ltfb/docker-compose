<?php

/**
 * Class Hustle_E_Newsletter_Form_Hooks
 * Define the form hooks that are used by E_Newsletter
 *
 * @since 4.0
 */
class Hustle_E_Newsletter_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add Activecampaign data to entry.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	public function add_entry_fields( $submitted_data ) {

		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                    $submitted_data
		 * @param int                                      $module_id                current module_id
		 * @param Hustle_E_Newsletter_Form_Settings        $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_e_newsletter_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$addon_setting_values = $form_settings_instance->get_form_settings_values();

		try {
			$addon = $this->addon;

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$groups        = $addon_setting_values['list_id'];
			$double_opt_in = empty( $addon_setting_values['auto_optin'] ) || 'pending' === $addon_setting_values['auto_optin'];
			$subscribe     = $double_opt_in ? '' : 1;

			$submitted_data = $this->check_legacy( $submitted_data );

			$_data['member_email'] = $submitted_data['email'];

			if ( isset( $submitted_data['first_name'] ) ) {
				$_data['member_fname'] = $submitted_data['first_name'];
			}

			if ( isset( $submitted_data['last_name'] ) ) {
				$_data['member_lname'] = $submitted_data['last_name'];
			}

			$_data['is_hustle'] = true;
			$e_newsletter       = $addon->get_enewsletter_instance();

			$err = new WP_Error();

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
				'hustle_provider_e_newsletter_before_add_subscriber',
				$module_id,
				$submitted_data,
				$form_settings_instance
			);

			$insert_data = $e_newsletter->create_update_member_user( '', $_data, $subscribe );

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
				'hustle_provider_e_newsletter_after_add_subscriber',
				$module_id,
				$submitted_data,
				$insert_data,
				$form_settings_instance
			);

			if ( isset( $insert_data['results'] ) && in_array( 'member_inserted', (array) $insert_data['results'], true ) ) {
				$e_newsletter->add_members_to_groups( $insert_data['member_id'], $groups );

				if ( isset( $e_newsletter->settings['subscribe_newsletter'] ) && $e_newsletter->settings['subscribe_newsletter'] ) {
					$send_details = $e_newsletter->add_send_email_info( $e_newsletter->settings['subscribe_newsletter'], $insert_data['member_id'], 0, 'waiting_send' );
					$e_newsletter->send_email_to_member( $send_details['send_id'] );
				}

				// $subscribe should only be false when double opt-in is enabled
				if ( ! $subscribe ) {
					$status = $e_newsletter->do_double_opt_in( $insert_data['member_id'] );
				}

				$entry_fields = array(
					array(
						'name'  => 'status',
						'value' => array(
							'is_sent'       => true,
							'description'   => __( 'Successfully added or updated member on e-Newsletter list', 'hustle' ),
							'data_sent'     => $_data,
							'data_received' => (array) $insert_data['results'],
							'member_status' => 'subscribed' === $addon_setting_values['auto_optin'] ? __( 'Subscribed', 'hustle' ) : __( 'Pending', 'hustle' ),
							'list_name'     => $addon_setting_values['list_name'],
						),
					),
				);

			} else {

				$entry_fields = array(
					array(
						'name'  => 'status',
						'value' => array(
							'is_sent'       => false,
							'description'   => __( 'Something went wrong. Unable to add subscriber', 'hustle' ),
							'data_sent'     => $_data,
							'data_received' => array(),
							'member_status' => __( 'Member could not be subscribed.', 'hustle' ),
							'list_name'     => $addon_setting_values['list_name'],
						),
					),
				);
			}
		} catch ( Exception $e ) {
			$entry_fields = $this->exception( $e );
		}

		$entry_fields = apply_filters(
			'hustle_provider_e_newsletter_entry_fields',
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
			 * @param Hustle_E_Newsletter_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_e_newsletter_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			$existing_email = $this->get_subscriber( $addon, $submitted_data['email'] );
			// triggers exception if not found.
			if ( $existing_email ) {
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
		 * @param Hustle_E_Newsletter_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_e_newsletter_form_submitted_data_after_validation',
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
			$this->_subscriber[ md5( $data ) ] = $api->is_member( $data );
		}
		return $this->_subscriber[ md5( $data ) ];
	}

}