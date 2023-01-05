<?php

/**
 * Class Hustle_Sendy_Form_Hooks
 * Define the form hooks that are used by Sendy
 *
 * @since 4.0
 */
class Hustle_Sendy_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add Sendy data to entry.
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

		/**
		 * @since 4.0
		 */
		$submitted_data = apply_filters( 'hustle_provider_' . $this->addon->get_slug() . '_form_submitted_data', $submitted_data, $module_id, $form_settings_instance );

		$addon_setting_values = $form_settings_instance->get_form_settings_values();

		try {
			/**
			 * @var $addon Hustle_Sendy
			 */
			$addon = $this->addon;

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$submitted_data = $this->check_legacy( $submitted_data );

			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$api             = $addon->get_api( $global_multi_id );

			$first_name = $this->get_value( $submitted_data, 'first_name' );
			$last_name  = $this->get_value( $submitted_data, 'last_name' );
			$email      = $this->get_value( $submitted_data, 'email' );
			$name       = $this->combine_name_parts( $first_name, $last_name );
			$_data      = array(
				'name'  => $name,
				'email' => $email,
			);

			// Add extra fields
			$extra_fields = array_diff_key(
				$submitted_data,
				array(
					'email'      => '',
					'first_name' => '',
					'last_name'  => '',
					'gdpr'       => '',
				)
			);

			$extra_fields = array_filter( $extra_fields );

			if ( ! empty( $extra_fields ) ) {
				$_data = array_merge( $_data, $extra_fields );
			}

			$_data['gdpr'] = ( isset( $submitted_data['gdpr'] ) && 'on' === $submitted_data['gdpr'] ? 'true' : '' );

			$_data = apply_filters( 'hustly_sendy_subscribe_api_data', $_data );

			$api_response = $api->subscribe( $_data );
			if ( is_wp_error( $api_response ) ) {
				$entry_fields = $this->get_status( false, $api_response->get_error_message() );
			} else {
				$entry_fields = $this->get_status( true, __( 'Successfully added or updated member on Sendy list', 'hustle' ) );
			}
		} catch ( Exception $e ) {
			$entry_fields = $this->exception( $e );
		}

		return apply_filters(
			'hustle_provider_' . $addon->get_slug() . '_entry_fields',
			$entry_fields,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);
	}

	private function get_value( $data, $field ) {
		return empty( $data[ $field ] ) ? '' : $data[ $field ];
	}

	private function get_status( $status, $message ) {
		return array(
			array(
				'name'  => 'status',
				'value' => array(
					'is_sent'     => $status,
					'description' => $message,
				),
			),
		);
	}

	/**
	 * @param $first_name
	 * @param $last_name
	 *
	 * @return string
	 */
	private function combine_name_parts( $first_name, $last_name ) {
		return implode( ' ', array_filter( array( $first_name, $last_name ) ) );
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
			 * @param Hustle_Sendy_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_sendy_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$global_multi_id = $addon_setting_values['selected_global_multi_id'];
			$api             = $addon->get_api( $global_multi_id );
			$api_response    = $api->subscriber_status( $submitted_data['email'] );
			$existing_member = $api_response->get_error_message();

			if ( 'Subscribed' === $existing_member ) {
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
		 * @param Hustle_Sendy_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_sendy_form_submitted_data_after_validation',
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
}