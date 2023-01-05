<?php
/**
 * Mailster's form hooks class.
 *
 * @package hustle
 *
 * @since 4.4.0
 */

/**
 * Class Hustle_Mailster_Form_Hooks.
 * Define the form hooks that are used by Mailster.
 *
 * @since 4.4.0
 */
class Hustle_Mailster_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {

	/**
	 * Check whether the email is already subscribed.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The submitted data.
	 * @param bool  $allow_subscribed Whether to allow already subscribed users.
	 * @return bool
	 */
	public function on_form_submit( $submitted_data, $allow_subscribed = true ) {
		if ( empty( $submitted_data['email'] ) ) {
			return __( 'Required Field "email" was not filled by the user.', 'hustle' );
		}

		$is_success = true;

		if ( ! $allow_subscribed ) {

			/**
			 * Filter submitted form data to be processed
			 *
			 * @since 4.4.0
			 *
			 * @param array                         $submitted_data
			 * @param int                           $module_id Current module_id
			 * @param Hustle_Mailster_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_mailster_form_submitted_data_before_validation',
				$submitted_data,
				$this->module_id,
				$this->form_settings_instance
			);

			if ( $this->is_subscribed( $submitted_data['email'] ) ) {
				$is_success = self::ALREADY_SUBSCRIBED_ERROR;
			}
		}

		/**
		 * Return `true` if success, or **(string) error message** on failure.
		 *
		 * @since 4.4.0
		 *
		 * @param bool                          $is_success
		 * @param int                           $module_id                current module_id
		 * @param array                         $submitted_data
		 * @param Hustle_Mailster_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_mailster_form_submitted_data_after_validation',
			$is_success,
			$this->module_id,
			$submitted_data,
			$this->form_settings_instance
		);

		// Only update `_submit_form_error_message` when $is_success is not empty nor 'true'.
		if ( true !== $is_success && ! empty( $is_success ) ) {
			$this->_submit_form_error_message = (string) $is_success;
		}

		return $is_success;
	}

	/**
	 * Checks whether the email is already subscribed to the saved list.
	 *
	 * @since 4.4.0
	 *
	 * @param string $email Subscriber email.
	 * @return boolean
	 */
	private function is_subscribed( $email ) {
		$subscriber = $this->get_subscriber_by_email( $email );
		if ( ! $subscriber ) {
			return false;
		}

		$assigned_lists = mailster( 'subscribers' )->get_lists( $subscriber->ID, true );
		$saved_settings = $this->form_settings_instance->get_form_settings_values();

		return in_array( $saved_settings['list_id'], $assigned_lists, true );
	}

	/**
	 * Returns the subscriber by the email.
	 *
	 * @since 4.4.0
	 *
	 * @param string $email Subscriber email to look for.
	 *
	 * @return false|object False when the subscriber doesn't exist. The subscriber data otherwise.
	 */
	private function get_subscriber_by_email( $email ) {
		return mailster( 'subscribers' )->get_by_mail( $email );
	}


	/**
	 * Add the provider's data to the created entry.
	 *
	 * @since 4.4.0
	 *
	 * @param array $submitted_data The submitted data.
	 * @throws Exception When the data wasn't sent to the integration for some reason.
	 * @return array
	 */
	public function add_entry_fields( $submitted_data ) {
		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.4.0
		 *
		 * @param array                         $submitted_data
		 * @param int                           $module_id                current module_id
		 * @param Hustle_Mailster_Form_Settings $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_mailster_form_submitted_data',
			$submitted_data,
			$this->module_id,
			$this->form_settings_instance
		);

		try {
			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$saved_settings = $this->form_settings_instance->get_form_settings_values();

			$existing_subscriber = $this->get_subscriber_by_email( $submitted_data['email'] );

			// Keep the subscriber's status if it exists.
			$status_code = $saved_settings['single_optin'];
			if ( $existing_subscriber ) {
				$status_code = $existing_subscriber->status;
			}

			/**
			 * Fires before adding subscriber
			 *
			 * @since 4.4.0
			 *
			 * @param int    $module_id
			 * @param array  $submitted_data
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_mailster_before_add_subscriber',
				$this->module_id,
				$submitted_data,
				$this->form_settings_instance
			);

			$subscription_data = $this->map_fields( $saved_settings, $submitted_data );

			$subscription_data['status'] = $status_code;

			// Add a subscriber, overwrite its fields if it exists.
			$subscriber_id = mailster( 'subscribers' )->add( $subscription_data, true, true );

			if ( is_wp_error( $subscriber_id ) ) {
				throw new Exception( 'The subscriber could not be created. ' . $subscriber_id->get_error_message(), $subscriber_id->get_error_code() );
			}

			// The subscriber was successfully added. Now assign it to a list.
			$list_id = array( $saved_settings['list_id'] );
			mailster( 'subscribers' )->assign_lists( $subscriber_id, $list_id );

			/**
			 * Fires after adding subscriber
			 *
			 * @since 4.4.0
			 *
			 * @param int    $module_id
			 * @param array  $subcription_data
			 * @param mixed  $subscriber
			 * @param object $form_settings_instance
			 */
			do_action(
				'hustle_provider_mailster_after_add_subscriber',
				$this->module_id,
				$subcription_data,
				$subscriber,
				$this->form_settings_instance
			);

			$successful_subscription_message = empty( $existing_subscriber ) ?
				__( 'User successfully added', 'hustle' ) :
				__( 'User successfully updated', 'hustle' );

			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'       => true,
						'description'   => $successful_subscription_message,
						'data_sent'     => $subcription_data,
						'data_received' => array(),
						'member_status' => mailster( 'subscribers' )->get_status( $status_code, true ),
						'list_name'     => $saved_settings['list_name'] . ' (' . $saved_settings['list_id'] . ')',
					),
				),
			);

		} catch ( Exception $e ) {
			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'       => false,
						'description'   => $e->getMessage(),
						'data_sent'     => $subcription_data,
						'data_received' => array( 'Code: ' . $e->getCode() . ' - ' . $e->getMessage() ),
						'member_status' => __( 'Member could not be subscribed.', 'hustle' ),
						'list_name'     => $saved_settings['list_name'] . ' (' . $saved_settings['list_id'] . ')',
					),
				),
			);
		}

		$entry_fields = apply_filters(
			'hustle_provider_mailster_entry_fields',
			$entry_fields,
			$this->module_id,
			$submitted_data,
			$this->form_settings_instance
		);
		return $entry_fields;
	}

	/**
	 * Maps Mailster's fields with Hustle's fields.
	 *
	 * @since 4.4.0
	 *
	 * @param array $saved_settings The integration's configs for the module.
	 * @param array $submitted_data The submitted data.
	 * @return array
	 */
	private function map_fields( $saved_settings, $submitted_data ) {
		$subcription_data = array();

		foreach ( $saved_settings['fields_map'] as $mailster_field => $hustle_field ) {
			// Skip if hustle's fields changed but the provider's map wasn't updated.
			if ( ! isset( $submitted_data[ $hustle_field ] ) ) {
				continue;
			}

			$subscription_data[ $mailster_field ] = $submitted_data[ $hustle_field ];
		}

		return $subscription_data;
	}
}