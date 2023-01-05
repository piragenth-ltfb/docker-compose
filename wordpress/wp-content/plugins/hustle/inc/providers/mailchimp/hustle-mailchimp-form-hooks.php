<?php

/**
 * Class Hustle_Mailchimp_Form_Hooks
 * Define the form hooks that are used by Mailchimp
 *
 * @since 4.0
 */
class Hustle_Mailchimp_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {

	public function __construct( Hustle_Provider_Abstract $addon, $module_id ) {
		parent::__construct( $addon, $module_id );
		add_filter( 'hustle_format_submitted_data', array( $this, 'format_submitted_data' ), 10, 2 );
	}

	/**
	 * Prepare GDPR fields for Mailchimp API
	 *
	 * @param array $gdpr_fields Saved GDPR fields
	 * @return array
	 */
	private function prepare_marketing_permissions( $gdpr_fields ) {
		$permissions = array();
		foreach ( $gdpr_fields as $key ) {
			$permissions[] = array(
				'marketing_permission_id' => $key,
				'enabled'                 => true,
			);
		}

		return $permissions;
	}

	/**
	 * Add Mailchimp data to entry.
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
		 * @param Hustle_Mailchimp_Form_Settings           $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_mailchimp_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$addon_setting_values = $form_settings_instance->get_form_settings_values();
		$global_multi_id      = $addon_setting_values['selected_global_multi_id'];
		$api_key              = $addon->get_setting( 'api_key', '', $global_multi_id );

		try {
			$api = $addon->get_api( $api_key );

			if ( empty( $submitted_data['email'] ) ) {
				throw new Exception( __( 'Required Field "email" was not filled by the user.', 'hustle' ) );
			}

			$list_id    = $addon_setting_values['list_id'];
			$list_id    = apply_filters(
				'hustle_provider_mailchimp_add_update_member_request_mail_list_id',
				$list_id,
				$module_id,
				$submitted_data,
				$form_settings_instance
			);
			$sub_status = 'subscribed' === $addon_setting_values['auto_optin'] ? 'subscribed' : 'pending';

			$email          = $submitted_data['email'];
			$submitted_data = $this->check_legacy( $submitted_data );
			$merge_vals     = array();
			$interests      = array();
			$gdpr           = false;
			if ( isset( $submitted_data['first_name'] ) ) {
				$merge_vals['MERGE1'] = $submitted_data['first_name'];
				$merge_vals['FNAME']  = $submitted_data['first_name'];
			}
			if ( isset( $submitted_data['last_name'] ) ) {
				$merge_vals['MERGE2'] = $submitted_data['last_name'];
				$merge_vals['LNAME']  = $submitted_data['last_name'];
			}
			if ( isset( $submitted_data['gdpr'] ) ) {
				$gdpr = ( 'on' === $submitted_data['gdpr'] ? true : false );
				unset( $submitted_data['gdpr'] );
			}
			// Add extra fields
			$merge_data = array_diff_key(
				$submitted_data,
				array(
					'email'                    => '',
					'first_name'               => '',
					'last_name'                => '',
					'mailchimp_group_id'       => '',
					'mailchimp_group_interest' => '',
				)
			);

			// Array containing the shortened keys.
			$shortened_keys = array();
			foreach ( $merge_data as $key => $val ) {

				// Remove empty fields.
				if ( empty( $val ) ) {
					unset( $merge_data[ $key ] );
					continue;
				}

				// Shorten fields keys longer than what's accepted by Mailchimp.
				if ( 10 < strlen( $key ) ) {
					$shortened_key = substr( $key, 0, 10 );
					unset( $merge_data[ $key ] );
					$merge_data[ $shortened_key ] = $val;

					$shortened_keys[] = $key . " ($shortened_key)";
				}
			}

			// Add a warning in the entry letting the admin know how we're handling their keys.
			if ( ! empty( $shortened_keys ) ) {
				$success_message_extra = sprintf(
					__( " These fields' names are being truncated to have a max length of 10. In parenthesis is the name currently used by Mailchimp: %s", 'hustle' ),
					implode( ', ', $shortened_keys )
				);
			}

			if ( ! empty( $merge_data ) ) {
				$merge_vals = array_merge( $merge_vals, $merge_data );
			}
			$merge_vals = array_change_key_case( $merge_vals, CASE_UPPER );

			/**
			 * Add args for interest groups
			 */
			if ( ! empty( $submitted_data['mailchimp_group_id'] ) && ! empty( $submitted_data['mailchimp_group_interest'] ) ) {
				$data_interest = (array) $submitted_data['mailchimp_group_interest'];
				foreach ( $data_interest as $interest ) {
					$interests[ $interest ] = true;
				}
			}

			$subscribe_data = array(
				'email_address' => $email,
				'status'        => $sub_status,
			);
			if ( ! empty( $merge_vals ) ) {
				$subscribe_data['merge_fields'] = $merge_vals;
			}
			if ( ! empty( $interests ) ) {
				$subscribe_data['interests'] = $interests;
			}

			$error_detail = __( 'Something went wrong.', 'hustle' );
			try {
				// Add custom fields
				$add_cf_result = $addon->maybe_add_custom_fields( $api, $list_id, $merge_data, $module_id );
				if ( is_wp_error( $add_cf_result ) ) {
					$error_message = $add_cf_result->get_error_message();
					throw new Exception( $error_message );
				}

				$existing_member = $addon->get_member( $email, $list_id, $submitted_data, $api_key );
				$member_exists   = ! is_wp_error( $existing_member ) && $existing_member;

				// tags
				$static_segments     = isset( $addon_setting_values['tags'] ) ? $addon_setting_values['tags'] : '';
				$static_segments_val = ! empty( $static_segments ) ? ( $member_exists ? array_keys( $static_segments ) : array_values( $static_segments ) ) : '';

				if ( ! empty( $static_segments_val ) ) {
					$subscribe_data['tags'] = $static_segments_val;
				}

				if ( true === $gdpr && ! empty( $addon_setting_values['gdpr_fields'] ) ) {
					$subscribe_data['marketing_permissions'] = $this->prepare_marketing_permissions( $addon_setting_values['gdpr_fields'] );
				}

				if ( $member_exists ) {
					$member_interests = isset( $existing_member->interests ) ? (array) $existing_member->interests : array();
					$can_subscribe    = true;
					if ( isset( $subscribe_data['interests'] ) ) {

						$local_interest_keys = array_keys( $subscribe_data['interests'] );
						if ( ! empty( $member_interests ) ) {
							foreach ( $member_interests as $member_interest => $subscribed ) {
								if ( ! $subscribed && in_array( $member_interest, $local_interest_keys, true ) ) {
									$can_subscribe = true;
								}
							}
						} else {
							$can_subscribe = true;
						}
					}

					if ( 'pending' === $existing_member->status ) {
						$delete                   = $addon->delete_member( $email, $list_id, $submitted_data, $api_key );
						$subscribe_data['status'] = 'pending';
						$can_subscribe            = true;
					} elseif ( 'unsubscribed' === $existing_member->status ) {
						// Resend Confirm Subscription Email even if `Automatically opt-in new users to the mailing list` is set
						$subscribe_data['status'] = 'pending';
						$can_subscribe            = true;
					} else {
						unset( $subscribe_data['status'] );
					}

					if ( $can_subscribe ) {
						unset( $subscribe_data['email_address'] );

						$subscribe_data = apply_filters(
							'hustle_provider_mailchimp_update_member_request_args',
							$subscribe_data,
							$module_id,
							$submitted_data,
							$form_settings_instance,
							$list_id,
							$email
						);
						do_action(
							'hustle_provider_mailchimp_before_update_member',
							$subscribe_data,
							$module_id,
							$submitted_data,
							$form_settings_instance,
							$list_id,
							$email
						);

						$response = $api->update_subscription_patch( $list_id, $email, $subscribe_data );
					} else {
						$error_message = __( 'This email address has already subscribed', 'hustle' );
						throw new Exception( $error_message );
					}

					// TODO: translate.
					$member_status = $existing_member->status;

				} elseif ( is_wp_error( $existing_member ) ) {
					$error_data    = json_decode( $existing_member->get_error_data(), true );
					$error_message = __( 'Error', 'hustle' ) . ': ' . $error_data['status'] . ' - ' . $error_data['title'] . '. ' . $error_data['detail'];
					throw new Exception( $error_message );

				} else {
					$subscribe_data = apply_filters(
						'hustle_provider_mailchimp_add_member_request_args',
						$subscribe_data,
						$module_id,
						$submitted_data,
						$form_settings_instance,
						$list_id
					);
					do_action(
						'hustle_provider_mailchimp_before_update_member',
						$subscribe_data,
						$module_id,
						$submitted_data,
						$form_settings_instance,
						$list_id
					);

					$response = $api->subscribe( $list_id, $subscribe_data );

					// TODO: handle errors here.

					$member_status = $subscribe_data['status'];
				}

				$is_sent = true;

			} catch ( Exception $e ) {
				$is_sent       = false;
				$member_status = __( 'Member could not be subscribed.', 'hustle' );
				$error_detail  = $e->getMessage();
			}

			// If there's extra information to display in the success entry description, add it.
			$success_message = __( 'Successfully added or updated member on Mailchimp list.', 'hustle' );
			if ( ! empty( $success_message_extra ) ) {
				$success_message = $success_message . $success_message_extra;
			}

			$entry_fields = array(
				array(
					'name'  => 'status',
					'value' => array(
						'is_sent'       => $is_sent,
						'description'   => $is_sent ? $success_message : $error_detail,
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

		if ( ! empty( $addon_setting_values['group_name'] ) ) {
			$entry_fields[0]['value']['group_name'] = $addon_setting_values['group_name'];
		}

		if ( ! empty( $interests ) ) {
			$interest_name = array();
			foreach ( $interests as $key => $interest ) {
				$interest_name[] = ! empty( $addon_setting_values['interest_options'][ $key ] )
					? $addon_setting_values['interest_options'][ $key ] : __( 'Noname', 'hustle' );
			}
			$entry_fields[0]['value']['group_interest_name'] = implode( ', ', $interest_name );
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
	 * Add the groups' and interests' form fields in front.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module
	 * @return string
	 */
	public function add_front_form_fields( Hustle_Module_Model $module ) {

		$settings = $this->form_settings_instance->get_form_settings_values();

		if ( ! isset( $settings['group'] ) || '-1' === $settings['group'] ) {
			return '';
		}

		$template_path    = plugin_dir_path( __FILE__ ) . 'views/front-fields-template.php';
		$interest_options = Hustle_Mailchimp::get_instance()->get_interest_options( $module );
		$default_interest = 'checkboxes' !== $settings['group_type'] ? '' : array();

		$args = array(
			'module_id'            => $module->module_id,
			'group_id'             => $settings['group'],
			'group_name'           => $settings['group_name'],
			'group_type'           => $settings['group_type'],
			'interest_options'     => $interest_options,
			'selected_interest'    => ! empty( $settings['group_interest'] ) ? $settings['group_interest'] : $default_interest,
			'dropdown_placeholder' => ! empty( $settings['group_interest_placeholder'] ) ? $settings['group_interest_placeholder'] : __( 'Select a group', 'hustle' ),
		);

		$renderer = new Hustle_Layout_Helper();
		return $renderer->render( $template_path, $args, true );
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
		$api                    = $addon->get_api( $addon->get_setting( 'api_key', '', $global_multi_id ) );

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
			 * @param Hustle_Mailchimp_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_mailchimp_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			// triggers exception if not found.
			$is_sub = $this->get_subscriber(
				$api,
				array(
					'email'   => $submitted_data['email'],
					'list_id' => $addon_setting_values['list_id'],
				)
			);

			if ( ! is_wp_error( $is_sub ) ) {
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
		 * @param Hustle_Mailchimp_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_mailchimp_form_submitted_data_after_validation',
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
			$this->_subscriber[ md5( $data['email'] ) ] = $api->check_email( $data['list_id'], $data['email'] );
		}

		return $this->_subscriber[ md5( $data['email'] ) ];
	}

	/**
	 * Format submitted data
	 *
	 * @since 4.0
	 * @param array  $submitted_data
	 * @param string $slug Provider slug
	 * @return array
	 */
	public function format_submitted_data( $submitted_data, $slug ) {
		if ( 'mailchimp' !== $slug ) {
			unset( $submitted_data['mailchimp_group_id'], $submitted_data['mailchimp_group_interest'] );
		}

		return $submitted_data;
	}

}