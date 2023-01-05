<?php
/**
 * Mailpoet's form hooks class.
 *
 * @package hustle
 *
 * @since 4.4.0
 */

/**
 * Class Hustle_Mailpoet_Form_Hooks.
 * Define the form hooks that are used by Mailpoet.
 *
 * @since 4.4.0
 */
class Hustle_Mailpoet_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {

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
		 * @param Hustle_Mailpoet_Form_Settings $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_mailpoet_form_submitted_data',
			$submitted_data,
			$this->module_id,
			$this->form_settings_instance
		);

		try {
			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$saved_settings = $this->form_settings_instance->get_form_settings_values();

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
				'hustle_provider_mailpoet_before_add_subscriber',
				$this->module_id,
				$submitted_data,
				$this->form_settings_instance
			);

			$subcription_data = $this->map_fields( $saved_settings, $submitted_data );

			$subscriber = $this->get_subscriber( $this->addon->get_api(), $subcription_data['email'] );
			if ( empty( $subscriber ) ) {
				// Only new subscribers get the custom fields. This is a Mailpoet API's limitation.
				$subscriber = $this->add_new_subscriber( $subcription_data, $saved_settings['list_id'] );
			} else {
				$subscriber = $this->add_existing_subscriber_to_list( $subscriber['id'], $saved_settings['list_id'] );
			}

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
				'hustle_provider_mailpoet_after_add_subscriber',
				$this->module_id,
				$subcription_data,
				$subscriber,
				$this->form_settings_instance
			);

			if ( is_array( $subscriber ) ) {
				foreach ( $subscriber['subscriptions'] as $subscription ) {
					if ( $saved_settings['list_id'] === $subscription['segment_id'] ) {
						$subscription_status = $subscription['status'];
					}
				}

				if ( ! empty( $subscription_status ) ) {
					$entry_fields = array(
						array(
							'name'  => 'status',
							'value' => array(
								'is_sent'       => true,
								'description'   => __( 'Successfully added or updated member on Mailpoet list', 'hustle' ),
								'data_sent'     => $subcription_data,
								'data_received' => array(),
								'member_status' => $subscription_status,
								'list_name'     => $saved_settings['list_name'],
							),
						),
					);
				} else {
					throw new Exception( 'Subscriber not added to the list.' );
				}
			} else {
				throw new Exception( 'Invalid subscriber.' );
			}
		} catch ( Exception $e ) {
			// Error codes for when the user was subscribed but the
			// confirmation email (code 10) or the welcome email (code 17) failed to send.
			$member_status = 10 === $e->getCode() || 17 === $e->getCode() ?
				__( 'User added to the list but the emails could not be sent.', 'hustle' ) :
				__( 'The user could not be subscribed.', 'hustle' );

			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'       => false,
						'description'   => $e->getMessage(),
						'data_sent'     => $subcription_data,
						'data_received' => array( 'Code: ' . $e->getCode() . ' - ' . $e->getMessage() ),
						'member_status' => $member_status,
						'list_name'     => $saved_settings['list_name'],
					),
				),
			);
		}

		$entry_fields = apply_filters(
			'hustle_provider_mailpoet_entry_fields',
			$entry_fields,
			$this->module_id,
			$submitted_data,
			$this->form_settings_instance
		);
		return $entry_fields;
	}

	/**
	 * Maps the submitted data to Mailpoet's fields.
	 *
	 * @since 4.4.0
	 *
	 * @param array $saved_settings The module's saved settings.
	 * @param array $submitted_data The data submitted.
	 * @return array
	 */
	private function map_fields( $saved_settings, $submitted_data ) {
		$subcription_data = array();

		foreach ( $saved_settings['fields_map'] as $mailpoet_field => $hustle_field ) {
			// Skip if hustle's fields changed but the provider's map wasn't updated.
			if ( ! isset( $submitted_data[ $hustle_field ] ) ) {
				continue;
			}

			$subscription_data[ $mailpoet_field ] = $submitted_data[ $hustle_field ];
		}

		return $subscription_data;
	}

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
			 * @param Hustle_Mailpoet_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_mailpoet_form_submitted_data_before_validation',
				$submitted_data,
				$this->module_id,
				$this->form_settings_instance
			);

			$subscriber = $this->get_subscriber( $this->addon->get_api(), $submitted_data['email'] );

			if ( ! empty( $subscriber ) && ! empty( $subscriber['subscriptions'] ) ) {
				$saved_settings = $this->form_settings_instance->get_form_settings_values();

				// Check if the email is subscribed to the selected list, and that its status is 'subscribed'.
				foreach ( $subscriber['subscriptions'] as $subscription ) {
					if ( $saved_settings['list_id'] === $subscription['segment_id'] && 'subscribed' === $subscription['status'] ) {
						$is_success = self::ALREADY_SUBSCRIBED_ERROR;
						break;
					}
				}
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
		 * @param Hustle_Mailpoet_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_mailpoet_form_submitted_data_after_validation',
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
	 * Get the subscriber's data for the given email and selected list_id.
	 *
	 * @since 4.4.0
	 *
	 * @param \MailPoet\API\API $api Mailpoet API instance.
	 * @param string            $email Email address trying to subscribe.
	 *
	 * @return array|null Array with the subscriber data if the subscriber is found. Null otherwise.
	 * @throws Exception  When the subscriber is found.
	 */
	protected function get_subscriber( $api, $email ) {
		if ( empty( $this->_subscriber ) && ! is_null( $this->_subscriber ) ) {
			try {
				// This throws an Exception when no subscriber is found.
				$this->_subscriber = $api->getSubscriber( $email );

			} catch ( Exception $e ) {
				$this->_subscriber = null;
			}
		}
		return $this->_subscriber;
	}

	/**
	 * Adds an existing subscriber to a list.
	 *
	 * @since 4.4.0
	 *
	 * @see https://github.com/mailpoet/mailpoet/blob/master/doc/api_methods/AddSubscriber.md
	 *
	 * @param string $subscriber_id The existing subscriber ID.
	 * @param string $list_id ID of the list to subscribe to.
	 * @return bool
	 * @throws Exception When something goes wrong with subscribeToList().
	 */
	private function add_existing_subscriber_to_list( $subscriber_id, $list_id ) {
		// This could throw an exception that's caught by the method calling this.
		$subscriber = $this->addon->get_api()->subscribeToList( $subscriber_id, $list_id );

		return $subscriber;
	}

	/**
	 * Adds a new subscriber to Mailpoet.
	 * The subscriber is already added to the list by this method.
	 *
	 * @since 4.4.0
	 *
	 * @see https://github.com/mailpoet/mailpoet/blob/master/doc/api_methods/SubscribeToList.md
	 *
	 * @param array  $subscription_data The submitted data already mapped to match Mailpoet's fields.
	 * @param string $list_id ID of the list to subscribe to.
	 * @return bool
	 * @throws Exception When something goes wrong with addSubscriber().
	 */
	private function add_new_subscriber( $subscription_data, $list_id ) {
		// This could throw an exception that's caught by the method calling this.
		$subscriber = $this->addon->get_api()->addSubscriber( $subscription_data, array( $list_id ) );

		return $subscriber;
	}
}