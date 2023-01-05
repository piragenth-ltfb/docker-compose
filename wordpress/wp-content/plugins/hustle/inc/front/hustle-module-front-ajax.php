<?php

class Hustle_Module_Front_Ajax {

	private $_hustle;

	public function __construct() {

		// When module is viewed
		add_action( 'wp_ajax_hustle_module_viewed', array( $this, 'module_viewed' ) );
		add_action( 'wp_ajax_nopriv_hustle_module_viewed', array( $this, 'module_viewed' ) );

		// When module form is submitted
		add_action( 'wp_ajax_hustle_module_form_submit', array( $this, 'submit_form' ) );
		add_action( 'wp_ajax_nopriv_hustle_module_form_submit', array( $this, 'submit_form' ) );

		// Update the number of shares of each network
		add_action( 'wp_ajax_hustle_update_network_shares', array( $this, 'get_networks_native_shares' ) );
		add_action( 'wp_ajax_nopriv_hustle_update_network_shares', array( $this, 'get_networks_native_shares' ) );

		// When a conversion happens.
		add_action( 'wp_ajax_hustle_module_converted', array( $this, 'log_module_conversion' ) );
		add_action( 'wp_ajax_nopriv_hustle_module_converted', array( $this, 'log_module_conversion' ) );

		// Update the stored click counter.
		add_action( 'wp_ajax_hustle_sshare_click_counted', array( $this, 'update_sshare_click_counter' ) );
		add_action( 'wp_ajax_nopriv_hustle_sshare_click_counted', array( $this, 'update_sshare_click_counter' ) );

		// Handles unsubscribe form submisisons
		add_action( 'wp_ajax_hustle_unsubscribe_form_submission', array( $this, 'unsubscribe_submit_form' ) );
		add_action( 'wp_ajax_nopriv_hustle_unsubscribe_form_submission', array( $this, 'unsubscribe_submit_form' ) );
	}

	/**
	 * Replace value if it's a dinamic one based on submited fields
	 *
	 * @param string $value The current value
	 * @param array  $form_data The submitted form data
	 * @return string final value
	 */
	private function maybe_replace_to_field( $value, $form_data ) {
		if ( ! empty( $value ) && '{' === $value[0] && '}' === substr( $value, -1 ) ) {
			$field = trim( $value, '{}' );
			$value = ! empty( $form_data[ $field ] ) ? $form_data[ $field ] : $value;
		}

		return $value;
	}

	/**
	 * Replace common placeholders and current field placeholders
	 *
	 * @param int    $module_id Module ID
	 * @param string $body Email body
	 * @param array  $form_data Submitted data
	 * @return string Replaced text
	 */
	private function replace_email_body_placeholders( $module_id, $body, $form_data ) {
		preg_match_all( '/\{[^}]*\}/', $body, $matches );

		if ( ! empty( $matches[0] ) && is_array( $matches[0] ) ) {
			$site_placeholders   = array(
				'{site_url}'   => site_url(),
				'{site_title}' => get_bloginfo( 'name' ),
			);
			$module              = new Hustle_Module_Model( $module_id );
			$local_list_settings = ! is_wp_error( $module ) ? $module->get_provider_settings( 'local_list' ) : '';
			if ( ! empty( $local_list_settings['local_list_name'] ) ) {
				$site_placeholders['{local_list}'] = $local_list_settings['local_list_name'];
			}

			foreach ( $matches[0] as $placeholder ) {
				// find common placeholders
				if ( key_exists( $placeholder, $site_placeholders ) ) {
					$value = $site_placeholders[ $placeholder ];
				} else {
					// find field placeholders
					$value = $this->maybe_replace_to_field( $placeholder, $form_data );
				}

				if ( $value !== $placeholder ) {
					// replace if we found something
					$body = str_replace( $placeholder, $value, $body );
				}
			}
		}

		return $body;
	}


	/**
	 * Send auto email if it sets
	 *
	 * @param Hustle_Module_Model $module
	 * @param array               $response
	 */
	public function send_automated_email( Hustle_Module_Model $module ) {
		$module_id       = $module->module_id;
		$emails_settings = $module->get_emails()->to_array();

		if ( empty( $emails_settings['automated_email'] ) || '1' !== $emails_settings['automated_email']
				|| empty( $emails_settings['email_time'] ) ) {
			return;
		}

		$to      = ! empty( $emails_settings['recipient'] ) ? $emails_settings['recipient'] : '';
		$subject = ! empty( $emails_settings['email_subject'] ) ? $emails_settings['email_subject'] : '';
		$body    = ! empty( $emails_settings['email_body'] ) ? $emails_settings['email_body'] : '';

		$data = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		parse_str( $data['form'], $form_data );
		$to   = $this->maybe_replace_to_field( $to, $form_data );
		$body = $this->replace_email_body_placeholders( $module_id, $body, $form_data );

		// Send the email right away if it's set as 'instant'.
		if ( 'instant' === $emails_settings['email_time'] ) {
			Hustle_Mail::send_email( $to, $subject, $body );
			return;
		}

		// Adding the 4th parameter time() to prevent cron jobs from being ignored.
		// According to wp_schedule_single_event docs:
		// "Attempts to schedule an event after an event of the same name and $args will also be ignored".
		$args = array( $to, $subject, $body, time() );
		if ( 'delay' === $emails_settings['email_time'] ) {
			$time = ! empty( $emails_settings['auto_email_time'] ) ? (int) $emails_settings['auto_email_time'] : '';
			$unit = ! empty( $emails_settings['auto_email_unit'] ) ? $emails_settings['auto_email_unit'] : '';
			// get delay rate
			switch ( $unit ) {
				case 'days':
					$rate = DAY_IN_SECONDS;
					break;
				case 'hours':
					$rate = HOUR_IN_SECONDS;
					break;
				case 'minutes':
					$rate = MINUTE_IN_SECONDS;
					break;
				default:
					$rate = 1;
					break;
			}
			$schedule_time = time() + $time * $rate;
		} elseif ( 'schedule' === $emails_settings['email_time'] ) {
			// time settings
			$time = ! empty( $emails_settings['time'] ) ? $emails_settings['time'] : '';
			$day  = ! empty( $emails_settings['day'] ) ? $emails_settings['day'] : '';
			$time = $this->maybe_replace_to_field( $time, $form_data );
			$day  = $this->maybe_replace_to_field( $day, $form_data );

			// delay settings
			$delay_time = ! empty( $emails_settings['schedule_auto_email_time'] ) ? (int) $emails_settings['schedule_auto_email_time'] : '';
			$delay_unit = ! empty( $emails_settings['schedule_auto_email_unit'] ) ? $emails_settings['schedule_auto_email_unit'] : '';

			// get delay rate
			switch ( $delay_unit ) {
				case 'days':
					$delay_rate = DAY_IN_SECONDS;
					break;
				case 'hours':
					$delay_rate = HOUR_IN_SECONDS;
					break;
				case 'minutes':
					$delay_rate = MINUTE_IN_SECONDS;
					break;
				default:
					$delay_rate = 1;
					break;
			}
			// schedule time calculation
			$delay = $delay_time * $delay_rate;
			// convert from local time to GMT
			$schedule_time = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $day . ' ' . $time ) ), 'U' );
			$schedule_time = $schedule_time + $delay;
			if ( time() > $schedule_time ) {
				return;
			}
		}
		wp_schedule_single_event( $schedule_time, 'hustle_send_email', $args );
	}

	public function submit_form() {

		Hustle_Provider_Autoload::initiate_providers();

		$module_id = sanitize_text_field( $_POST['data']['module_id'] );

		// Action called before full form submit.
		do_action( 'hustle_form_before_handle_submit', $module_id );

		$response = $this->handle_form( $module_id );

		// Filter submit form response.
		$response = apply_filters( 'hustle_form_submit_response', $response, $module_id );

		// Action called after full form submit.
		do_action( 'hustle_form_after_handle_submit', $module_id, $response );

		if ( is_array( $response ) && ! empty( $response ) ) {
			if ( $response['success'] ) {
				wp_send_json_success( $response );
			}
		}

		wp_send_json_error( $response );
	}

	/**
	 * Handles the module's form submission process.
	 *
	 * @since 4.0
	 *
	 * @param int $module_id
	 * @return array
	 */
	private function handle_form( $module_id ) {

		$data = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		parse_str( $data['form'], $form_data );

		// Default error message response.
		$response = array(
			'message'  => __( 'Error saving form', 'hustle' ),
			'errors'   => array(),
			'success'  => false,
			'behavior' => array(),
		);
		$module   = new Hustle_Module_Model( $module_id );
		if ( is_wp_error( $module ) ) {
			return $response;
		}

		$fields             = $module->get_form_fields();
		$field_data_array   = array();
		$placeholder_array  = array();
		$submit_errors      = array();
		$required_fields    = array();
		$fields_to_validate = array();
		$entry              = new Hustle_Entry_Model();
		$entry->entry_type  = $module->module_type;
		$entry->module_id   = $module_id;

		if ( ! is_null( $fields ) ) {

			// Verify recaptcha first.
			if ( isset( $fields['recaptcha'] ) ) {

				$submit_errors = $this->validate_recaptcha( $form_data, $fields['recaptcha'] );

				if ( ! empty( $submit_errors ) ) {
					// Recaptcha failed. No need to check the other fields.
					$fields = array();
				}
			}

			foreach ( $fields as $field_name => $field_data ) {
				$ignored_field_types = Hustle_Entry_Model::ignored_fields();
				if ( in_array( $field_data['type'], $ignored_field_types, true ) ) {
					continue;
				}

				if ( 'true' === $field_data['required'] ) {
					$required_fields[] = $field_name;
				}

				if ( isset( $field_data['validate'] ) && 'true' === $field_data['validate'] ) {
					$fields_to_validate[] = $field_name;
				}

				if ( isset( $form_data[ $field_name ] ) ) {
					$value                             = sanitize_text_field( $form_data[ $field_name ] );
					$field_data_array[]                = array(
						'name'  => $field_name,
						'value' => $value,
					);
					$placeholder                       = '{' . $field_name . '}';
					$placeholder_array[ $placeholder ] = $value;
				}
			}
			if ( empty( $submit_errors ) ) {
				$submit_errors      = $this->validate_fields( $form_data, $required_fields, $fields_to_validate, $fields );
				$response['errors'] = $submit_errors;
			}
		}

		if ( ! empty( $field_data_array ) && empty( $submit_errors ) ) {

			// $_POST doesn't contain everything we need to pass. So do this merge instead.
			$submitted_data = array_merge( $data, $form_data );

			// Do a pre-flight validation with the providers before submitting any data.
			// As of 4.0 we're only checking whether the user is already subscribed if enabled.
			$integrations_settings = $module->get_integrations_settings()->to_array();

			$allow_subscribed = '1' === $integrations_settings['allow_subscribed_users'];

			$formatted_submitted_data = Hustle_Provider_Utils::format_submitted_data_for_addon( $submitted_data );
			// Do provider's validation.
			$provider_error = $this->attach_addons_on_form_submit( $module_id, $formatted_submitted_data, $allow_subscribed );
			if ( true !== $provider_error ) {
				$response['errors'][] = $provider_error;
				return $response;
			}

			$user_exists = Hustle_Entry_Model::is_email_subscribed_to_module_id( $module_id, $submitted_data['email'] );
			if ( $user_exists ) {
				$entry_id          = Hustle_Entry_Model::get_email_subscribed_to_module_id( $module_id, $submitted_data['email'] );
				$entry             = new Hustle_Entry_Model( $entry_id );
				$entry->entry_type = $module->module_type;
				$entry->module_id  = $module_id;
			}

			$active_integrations = array();

			if ( isset( $integrations_settings['active_integrations'] ) && ! empty( $integrations_settings['active_integrations'] ) ) {
				$active_integrations = explode( ',', $integrations_settings['active_integrations'] );
			}

			if ( in_array( 'local_list', $active_integrations, true ) ) {

				if ( $user_exists || $entry->save() ) {

					/**
					 * Check is tracking allowed
					 */
					$settings    = Hustle_Settings_Admin::get_privacy_settings();
					$ip_tracking = ! isset( $settings['ip_tracking'] ) || 'on' === $settings['ip_tracking'];
					if ( $ip_tracking ) {
						$field_data_array[] = array(
							'name'  => 'hustle_ip',
							'value' => Opt_In_Geo::get_user_ip(),
						);
					}

					$active_integrations = $this->get_module_active_integrations_to_store( $module_id );
					$field_data_array[]  = array(
						'name'  => 'active_integrations',
						'value' => $active_integrations,
					);

					// Filter data before saving to db.
					$field_data_array = apply_filters( 'hustle_form_submit_field_data', $field_data_array, $module_id );

					// Action before saving to db.
					do_action( 'hustle_form_submit_before_set_fields', $entry, $module_id, $field_data_array );

					$added_data_array = $this->attach_addons_add_entry_fields( $module_id, $module, $formatted_submitted_data, $field_data_array );
					$added_data_array = array_merge( $field_data_array, $added_data_array );

					$entry->set_fields( $added_data_array );

				}
			} else {

				$active_integrations = $this->get_module_active_integrations_to_store( $module_id );
				$field_data_array[]  = array(
					'name'  => 'active_integrations',
					'value' => $active_integrations,
				);

				// Filter data before saving to db.
				$field_data_array = apply_filters( 'hustle_form_submit_field_data', $field_data_array, $module_id );

				// Action before saving to db.
				do_action( 'hustle_form_submit_before_set_fields', $entry, $module_id, $field_data_array );

				$this->attach_addons_add_entry_fields( $module_id, $module, $formatted_submitted_data, $field_data_array );

			}

			$this->send_automated_email( $module );

			$post_id         = sanitize_text_field( $form_data['post_id'] );
			$module_sub_type = isset( $form_data['hustle_sub_type'] ) ? $form_data['hustle_sub_type'] : null;
			$this->maybe_log_conversion( $module, $post_id, $module_sub_type );

			$emails_settings = $module->get_emails()->to_array();
			$fields_array    = wp_list_pluck( $field_data_array, 'value', 'name' );
			$success_message = $this->parse_message_with_fields_placeholders( $emails_settings['success_message'], $placeholder_array );
			$success_message = apply_filters( 'hustle_parsed_success_message', $success_message, $module_id, $module_sub_type, $form_data );

			/**
			 * Filters the URL to redirect to on success.
			 *
			 * @since 4.4.1
			 *
			 * @param string $redirect_url    The URL to redirect to. Will be passed trough esc_url_raw().
			 * @param string $module_id       ID of the current module.
			 * @param string $module_sub_type Subtype of the current module.
			 * @param array  $form_data       The submitted data.
			 */
			$redirect_url = apply_filters( 'hustle_success_redirect_url', $emails_settings['redirect_url'], $module_id, $module_sub_type, $form_data );

			$response = array(
				'message'  => do_shortcode( wp_kses_post( $success_message ) ),
				'success'  => true,
				'errors'   => array(),
				'behavior' => array(
					'after_submit' => $emails_settings['after_successful_submission'],
					'url'          => esc_url_raw( $redirect_url ), // Using raw here to honor url params.
				),
			);
		}

		if ( ! empty( $submit_errors ) ) {
			$response = array(
				'message'  => $this->get_invalid_form_message( $fields ),
				'success'  => false,
				'errors'   => $submit_errors,
				'behavior' => array(),
			);
		}

		return $response;
	}

	/**
	 * Do recaptcha backend validation.
	 *
	 * @since 4.0
	 * @return array
	 */
	private function validate_recaptcha( $form_data, $recaptcha_field ) {

		$submit_errors      = array();
		$recaptcha_settings = Hustle_Settings_Admin::get_recaptcha_settings();
		$recaptcha_version  = empty( $recaptcha_field['version'] ) ? 'v2_checkbox' : $recaptcha_field['version'];
		$recaptcha_secret   = ! empty( $recaptcha_settings[ $recaptcha_version . '_secret_key' ] ) ? $recaptcha_settings[ $recaptcha_version . '_secret_key' ] : '';
		$recaptcha_site     = ! empty( $recaptcha_settings[ $recaptcha_version . '_site_key' ] ) ? $recaptcha_settings[ $recaptcha_version . '_site_key' ] : '';
		$token              = ! empty( $form_data['recaptcha-response'] ) ? $form_data['recaptcha-response'] : false;

		if ( ! empty( $recaptcha_secret ) && ! empty( $recaptcha_site ) ) {

			try {
				$validation_message = ! empty( $recaptcha_field['validation_message'] )
						? esc_html( $recaptcha_field['validation_message'] ) : '';

				if ( ! $token ) {
					throw new Exception(
						! empty( $validation_message ) ? $validation_message
						: esc_html__( 'reCAPTCHA must be verified to submit the form.', 'hustle' )
					);
				}

				$response = wp_remote_get(
					add_query_arg(
						array(
							'secret'   => $recaptcha_secret,
							'response' => $token,
							'remoteip' => isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
						),
						'https://www.google.com/recaptcha/api/siteverify'
					)
				);

				if ( is_wp_error( $response ) ) {
					throw new Exception( $response->get_error_message() );
				}

				$response_body = ! empty( $response['body'] ) ? json_decode( $response['body'] ) : '';

				if ( empty( $response_body ) || ! $response_body->success ) {
					throw new Exception(
						! empty( $validation_message ) ? $validation_message
						: esc_html__( 'reCAPTCHA validation failed. Please try again.', 'hustle' )
					);
				}

				if ( 'v3_recaptcha' === $recaptcha_version ) {
					$selected_score = $recaptcha_field['threshold'];

					if ( $selected_score > $response_body->score ) {
						throw new Exception(
							! empty( $validation_message ) ? $validation_message
							: esc_html__( 'reCAPTCHA validation failed. The score is too low.', 'hustle' )
						);
					}
				}
			} catch ( Exception $e ) {
				$submit_errors['recaptcha'] = $e->getMessage();
			}
		}

		return $submit_errors;
	}

	/**
	 * Validate fields.
	 *
	 * @since 4.0
	 * @since 4.0.2 $fields_to_validate parameter added.
	 *
	 * @param array $form_data
	 * @param array $required_fields
	 * @param array $fields_to_validate
	 * @param array $fields
	 * @return array
	 */
	private function validate_fields( $form_data, $required_fields, $fields_to_validate, $fields ) {

		$submit_errors = array();

		// Check required fields.
		foreach ( $required_fields as $slug ) {

			if ( ! isset( $form_data[ $slug ] ) || empty( trim( sanitize_text_field( $form_data[ $slug ] ) ) ) ) {
				$submit_errors[ $slug ] = $fields[ $slug ]['required_error_message'];
			}
		}

		// Validate url and email fields.
		if ( empty( $submit_errors ) ) {

			foreach ( $fields_to_validate as $slug ) {

				// Don't validate empty fields. These are validated under "required" if needed.
				if ( empty( trim( $form_data[ $slug ] ) ) ) {
					continue;
				}

				if ( 'email' === $fields[ $slug ]['type'] && ! is_email( $form_data[ $slug ] ) ||
					'url' === $fields[ $slug ]['type'] && false === filter_var( $form_data[ $slug ], FILTER_VALIDATE_URL ) ||
					'datepicker' === $fields[ $slug ]['type'] && false === $this->validate_date( $form_data[ $slug ], $fields[ $slug ]['date_format'] ) ) {

					$submit_errors[ $slug ] = $fields[ $slug ]['validation_message'];

				} elseif ( 'timepicker' === $fields[ $slug ]['type'] ) {

					$time         = str_replace( array( ' AM', ' PM' ), '', $form_data[ $slug ] );
					$format       = '12' === $fields[ $slug ]['time_format'] ? 'h:i' : 'H:i';
					$created_time = DateTime::createFromFormat( $format, $time );
					if ( ! $created_time || $created_time->format( $format ) !== $time ) {
						$submit_errors[ $slug ] = $fields[ $slug ]['validation_message'];
					}
				}
			}
		}

		return apply_filters( 'hustle_module_submit_errors_validated_fields', $submit_errors, $form_data, $required_fields, $fields_to_validate, $fields );
	}

	/**
	 * Get the generic error message.
	 *
	 * @since 4.0
	 *
	 * @param array $fields
	 * @return string
	 */
	private function get_invalid_form_message( $fields ) {

		if ( isset( $fields['submit'] ) && isset( $fields['submit']['error_message'] ) && ! empty( $fields['submit']['error_message'] ) ) {
			$message = $fields['submit']['error_message'];
		} else {
			$message = '';
		}

		return esc_html( $message );
	}

	private function parse_message_with_fields_placeholders( $raw_message, $submitted_data ) {

		$message = str_replace(
			array_keys( $submitted_data ),
			array_values( $submitted_data ),
			stripslashes( $raw_message )
		);

		return $message;
	}

	/**
	 * Log a conversion if tracking is enabled.
	 *
	 * @since 4.0
	 *
	 * @param int         $module
	 * @param int         $page_id
	 * @param string|null $module_sub_type
	 * @param string|null $cta
	 */
	private function maybe_log_conversion( $module, $page_id, $module_sub_type = null, $cta = null ) {

		$type = is_null( $module_sub_type ) ? $module->module_type : $module_sub_type;

		if ( ! $module->is_tracking_enabled( $type ) ) {
			return false;
		}

		$tracking = Hustle_Tracking_Model::get_instance();
		if ( 'social_sharing' === $module->module_type ) {
			$action = 'conversion';
		} elseif ( $cta ) {
			$action = $cta . '_conversion';
		} else {
			$action = 'optin_conversion';
		}
		$tracking->save_tracking( $module->id, $action, $module->module_type, $page_id, $module_sub_type );

		return true;
	}

	/**
	 * Get an array with the connected integrations to show in entries.
	 *
	 * @since 4.0
	 *
	 * @param int $module_id
	 * @return array
	 */
	private function get_module_active_integrations_to_store( $module_id ) {

		$active_integrations = array();
		$connected_addons    = Hustle_Provider_Utils::get_addons_instance_connected_with_module( $module_id );

		foreach ( $connected_addons as $addon ) {
			// Local list is not really an integration to be shown here.
			if ( 'local_list' === $addon->get_slug() ) {
				continue;
			}
			$active_integrations[ $addon->get_slug() ] = $addon->get_title();
		}

		return $active_integrations;
	}

	/**
	 * Executor on form submit for attached addons.
	 *
	 * @see Hustle_Provider_Form_Hooks_Abstract::on_form_submit()
	 * @since 4.0
	 *
	 * @param $module_id
	 * @param array     $submitted_data Data submitted by the user.
	 * @param bool      $allow_subscribed
	 * @return bool true on success|string error message from addon otherwise
	 */
	private function attach_addons_on_form_submit( $module_id, $submitted_data, $allow_subscribed ) {

		// Find is_form_connected
		$connected_addons = Hustle_Provider_Utils::get_addons_instance_connected_with_module( $module_id );

		$submitted_data = Opt_In_Utils::validate_and_sanitize_fields( $submitted_data );

		foreach ( $connected_addons as $connected_addon ) {
			try {
				$slug                     = $connected_addon->get_slug();
				$formatted_submitted_data = apply_filters( 'hustle_format_submitted_data', $submitted_data, $slug );
				$form_hooks               = $connected_addon->get_addon_form_hooks( $module_id );
				if ( $form_hooks instanceof Hustle_Provider_Form_Hooks_Abstract ) {
					$addon_return = $form_hooks->on_form_submit( $formatted_submitted_data, $allow_subscribed );
					if ( true !== $addon_return ) {
						return $form_hooks->get_submit_form_error_message();
					}
				}
			} catch ( Exception $e ) {
				Opt_In_Utils::maybe_log( $connected_addon->get_slug(), 'failed to attach_addons_on_form_submit', $e->getMessage() );
			}
		}

		return true;
	}

	/**
	 * Executor to add more entry fields for attached addons.
	 *
	 * @see Hustle_Provider_Form_Hooks_Abstract::add_entry_fields()
	 *
	 * @since 4.0
	 *
	 * @param $module_id
	 * @param Hustle_Module_Model $module
	 * @param array               $submitted_data Data submitted by the user.
	 * @param array               $current_entry_fields
	 * @return array fields to be added to entry
	 */
	private function attach_addons_add_entry_fields( $module_id, Hustle_Module_Model $module, $submitted_data, $current_entry_fields ) {
		$additional_fields_data = array();
		$connected_addons       = Hustle_Provider_Utils::get_addons_instance_connected_with_module( $module_id );
		foreach ( $connected_addons as $connected_addon ) {
			try {
				$slug                     = $connected_addon->get_slug();
				$formatted_submitted_data = apply_filters( 'hustle_format_submitted_data', $submitted_data, $slug );
				$form_hooks               = $connected_addon->get_addon_form_hooks( $module_id );

				if ( $form_hooks instanceof Hustle_Provider_Form_Hooks_Abstract ) {

					$addon_fields = $form_hooks->add_entry_fields( $formatted_submitted_data );
					// log errors
					if (
						! empty( $addon_fields[0] ) && ! empty( $addon_fields[0]['value'] ) &&
						isset( $addon_fields[0]['value']['is_sent'] ) &&
						false === $addon_fields[0]['value']['is_sent']
					) {
						$error = ! empty( $addon_fields[0]['value']['description'] ) ?
							$addon_fields[0]['value']['description']
							: __( 'Something went wrong.', 'hustle' );

						$error = $connected_addon->get_title() . ' ' . $error;

						Opt_In_Utils::maybe_log( $error );
					}

					$account_settings = $connected_addon->get_settings_values();
					if ( ! empty( $connected_addon->selected_global_multi_id ) ) {
						$addon_fields[0]['value']['account_name'] = isset( $account_settings[ $connected_addon->selected_global_multi_id ]['name'] )
								? $account_settings[ $connected_addon->selected_global_multi_id ]['name'] . ' (' . $connected_addon->selected_global_multi_id . ')'
								: $connected_addon->selected_global_multi_id;
					}

					// Reformat additional fields.
					$addon_fields           = self::format_addon_additional_fields( $connected_addon, $addon_fields );
					$additional_fields_data = array_merge( $additional_fields_data, $addon_fields );
				}
			} catch ( Exception $e ) {
				Opt_In_Utils::maybe_log( $connected_addon->get_slug(), 'failed to add_entry_fields', $e->getMessage() );
			}
		}

		return $additional_fields_data;
	}

	/**
	 * Format additional fields from provider.
	 * Format used is `hustle_provider_{$slug}_{$field_name}`
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Provider_Abstract $addon
	 * @param $additional_fields
	 * @return array
	 */
	private static function format_addon_additional_fields( Hustle_Provider_Abstract $addon, $additional_fields ) {
		// to `name` and `value` basis
		$formatted_additional_fields = array();
		if ( ! is_array( $additional_fields ) ) {
			return array();
		}

		foreach ( $additional_fields as $additional_field ) {
			if ( ! isset( $additional_field['name'] ) || ! isset( $additional_field['value'] ) ) {
				continue;
			}
			$formatted_additional_fields[] = array(
				'name'  => 'hustle_provider_' . $addon->get_slug() . '_' . $additional_field['name'],
				'value' => $additional_field['value'],
			);
		}

		return $formatted_additional_fields;
	}

	/**
	 * Handles the unsubscribe form submission.
	 *
	 * @since 3.0.5
	 */
	public function unsubscribe_submit_form() {

		parse_str( $_POST['data'], $submitted_data ); // WPCS: CSRF ok.
		$sanitized_data = Opt_In_Utils::validate_and_sanitize_fields( $submitted_data );
		$messages       = Hustle_Settings_Admin::get_unsubscribe_messages();
		// Check if we got the email address and if it's valid.
		if ( isset( $sanitized_data['email'] ) && filter_var( $sanitized_data['email'], FILTER_VALIDATE_EMAIL ) ) {

			$email = $sanitized_data['email'];

			// Handle 'choose_list' form step
			if ( isset( $sanitized_data['form_step'] ) && 'choose_list' === $sanitized_data['form_step'] ) {

				// If the lists are defined, submit the email with the nonce.
				if ( isset( $sanitized_data['lists_id'] ) && ! empty( $sanitized_data['lists_id'] ) && isset( $sanitized_data['current_url'] ) ) {

					// Do the process to send the unsubscription email.
					$email_processed = Hustle_Mail::handle_unsubscription_user_email( $email, $sanitized_data['lists_id'], $sanitized_data['current_url'] );

					if ( $email_processed ) {

						$html    = $messages['email_submitted'];
						$wrapper = '.hustle-form-body';

						$response = array(
							'html'    => apply_filters( 'hustle_unsubscribe_email_processed_html', $html, $sanitized_data ),
							'wrapper' => apply_filters( 'hustle_unsubscribe_email_processed_wrapper', $wrapper, $sanitized_data ),
						);
						wp_send_json_success( $response );

					}
				}

				$html = apply_filters( 'hustle_unsubscribe_email_not_processed_html', $messages['email_not_processed'], $sanitized_data );
				wp_send_json_error( array( 'html' => $html ) );

			} elseif ( isset( $sanitized_data['form_step'] ) && 'enter_email' === $sanitized_data['form_step'] ) {
				$entry      = new Hustle_Entry_Model();
				$modules_id = $entry->get_modules_id_by_email_in_local_list( $email );
				// The lists are not defined yet. Show the list for the user to select them.
				$module = new Hustle_Module_Model();

				// If not showing all, show only the ones defined in the shortcode.
				if ( '-1' !== $sanitized_data['form_module_id'] && ! empty( $sanitized_data['form_module_id'] ) ) {
					$form_modules_id = array_map( 'trim', explode( ',', $submitted_data['form_module_id'] ) );
					$modules_id      = array_intersect( $form_modules_id, $modules_id );
				}

				// If the email is not in any of the selected lists.
				if ( empty( $modules_id ) ) {

					$html    = $messages['email_not_found'];
					$wrapper = '.hustle-form-body';

					$response = array(
						'html'    => apply_filters( 'hustle_unsubscribe_email_not_found_html', $html, $modules_id, $email ),
						'wrapper' => apply_filters( 'hustle_unsubscribe_email_not_found_wrapper', $wrapper, $modules_id, $email ),
					);
					wp_send_json_success( $response );
				}

				$params   = array(
					'ajax_step'   => true,
					'modules_id'  => $modules_id,
					'module'      => $module,
					'email'       => $sanitized_data['email'],
					'current_url' => $sanitized_data['current_url'],
					'messages'    => $messages,
				);
				$renderer = new Hustle_Layout_Helper();
				$html     = $renderer->render( 'general/unsubscribe-form', $params, true );
				$wrapper  = '.hustle-form-body';

				$response = array(
					'html'    => apply_filters( 'hustle_render_unsubscribe_lists_html', $html, $modules_id, $email ),
					'wrapper' => apply_filters( 'hustle_render_unsubscribe_list_wrapper', $wrapper, $modules_id, $email ),
				);
				wp_send_json_success( $response );
			}
		} else {
			// Return an error if the email is missing or is invalid.
			$html = apply_filters( 'hustle_unsubscribe_invalid_email_address_message', $messages['invalid_email'], $sanitized_data );
			wp_send_json_error( array( 'html' => $html ) );
		}

		wp_send_json_success( $sanitized_data );
	}

	/**
	 * Retrieve the number of shares from the network's native APIs.
	 *
	 * @since 3.0.3
	 * @since 4.0 Get the networks' names from frontend.
	 */
	public function get_networks_native_shares() {

		// TODO: check the networks are the ones that have APIs, and sanitize.
		$networks = ! empty( $_POST['networks'] ) ? $_POST['networks'] : false; // CSRF: ok.

		$post_id = filter_input( INPUT_POST, 'postId', FILTER_VALIDATE_INT );

		if ( ! $networks || false === $post_id || is_null( $post_id ) ) {
			wp_send_json_error();
		}

		$module_instance = new Hustle_SShare_Model();
		$networks_shares = $module_instance->retrieve_networks_shares( $networks, $post_id );

		wp_send_json_success(
			array(
				'networks' => $networks_shares,
				'shorten'  => array(
					'thousand' => esc_html__( 'K', 'hustle' ),
					'million'  => esc_html__( 'M', 'hustle' ),
				),
			)
		);
	}

	public function log_module_conversion() {
		$data = json_decode( file_get_contents( 'php://input' ) );
		$data = get_object_vars( $data );

		if ( ! is_array( $data ) || empty( $data ) ) {
			return;
		}
		$module_id = $data['module_id'];

		if ( empty( $module_id ) ) {
			wp_send_json_error( __( 'Invalid Request!', 'hustle' ) . $module_id );
		}

		$module = Hustle_Module_Collection::instance()->return_model_from_id( $module_id );
		if ( is_wp_error( $module ) ) {
			wp_send_json_error( __( 'Invalid module!', 'hustle' ) );
		}

		if ( $module->id ) {

			$page_id         = $data['page_id'];
			$module_sub_type = ( isset( $data['module_sub_type'] ) && ! empty( $data['module_sub_type'] ) ) ? $data['module_sub_type'] : null;

			$cta = ! empty( $data['cta'] ) ? $data['cta'] : null;
			$res = $this->maybe_log_conversion( $module, $page_id, $module_sub_type, $cta );

		} else {
			$res = false;
		}

		if ( ! $res ) {
			wp_send_json_error( __( 'Error saving stats', 'hustle' ) );
		} else {
			wp_send_json_success( __( 'Stats Successfully saved', 'hustle' ) );
		}
	}

	public function module_viewed() {
		$data = json_decode( file_get_contents( 'php://input' ) );
		$data = get_object_vars( $data );

		if ( ! is_array( $data ) || empty( $data ) ) {
			return;
		}

		$module_id = $data['module_id'];

		if ( empty( $module_id ) ) {
			wp_send_json_error( __( 'Invalid Request: Module id invalid', 'hustle' ) );
		}

		$module = new Hustle_Module_Model( $module_id );
		if ( is_wp_error( $module ) ) {
			wp_send_json_error( __( 'Invalid module!', 'hustle' ) );
		}

		if ( $module->id ) {

			$module_type     = $data['module_type'];
			$page_id         = $data['page_id'];
			$module_sub_type = isset( $data['module_sub_type'] ) ? $data['module_sub_type'] : null;

			$tracking = Hustle_Tracking_Model::get_instance();
			$res      = $tracking->save_tracking( $module_id, 'view', $module_type, $page_id, $module_sub_type );

		} else {
			$res = false;
		}

		if ( ! $res ) {
			wp_send_json_error( __( 'Error saving stats', 'hustle' ) );
		} else {
			wp_send_json_success( __( 'Stats Successfully saved', 'hustle' ) );
		}

	}

	/**
	 * Update the click counter after an icon is clicked.
	 *
	 * @since 4.0
	 */
	public function update_sshare_click_counter() {

		$data = $_POST; // PHPCS CSRF: ok.

		$module = Hustle_Module_Collection::instance()->return_model_from_id( $data['moduleId'] );

		if ( ! is_wp_error( $module ) ) {

			$network = $data['network'];

			$content = $module->get_content()->to_array();

			if ( isset( $content['social_icons'][ $network ] ) ) {

				$content['social_icons'][ $network ]['counter'] = intval( $content['social_icons'][ $network ]['counter'] ) + 1;
				$module->update_meta( Hustle_Module_Model::KEY_CONTENT, $content );

				wp_send_json_success();
			}
		}

		wp_send_json_error();
	}

	/**
	 * Validate date field.
	 *
	 * @param string $date date.
	 * @param string $format date format.
	 * @since 4.0.2
	 */
	private function validate_date( $date, $format ) {
		$format = $this->validate_format( $format );
		$d      = DateTime::createFromFormat( $format, $date );

		return ( $d && $d->format( $format ) === $date );
	}

	/**
	 * Changes the date format from JS one to PHP version.
	 *
	 * @param string $format date format.
	 * @since 4.0.2
	 */
	private function validate_format( $format ) {
		// Formats based on https://api.jqueryui.com/datepicker/#utility-formatDate.
		$format_translate = array(
			'dd' => 'd',
			'mm' => 'm',
			'yy' => 'Y',
			'MM' => 'F',
			'oo' => 'z', // PHP doesn't have format like this, using the version without leading zeros.
			'DD' => 'l',
			'd'  => 'j',
			'o'  => 'z',
			'm'  => 'n',
			'@'  => 'U',
		);

		$format = strtr( $format, $format_translate );

		return $format;
	}
}