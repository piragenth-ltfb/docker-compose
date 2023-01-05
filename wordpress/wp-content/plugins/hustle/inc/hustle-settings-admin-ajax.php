<?php

class Hustle_Settings_Admin_Ajax {
	private $_hustle;

	private $_admin;

	public function __construct() {

		add_action( 'wp_ajax_hustle_remove_ips', array( $this, 'remove_ips_from_tables' ) );
		add_action( 'wp_ajax_hustle_reset_settings', array( $this, 'reset_settings' ) );

		// Return the recaptcha script for preview
		add_action( 'wp_ajax_hustle_load_recaptcha_preview', array( $this, 'load_recaptcha_preview' ) );

		// Color Palette tab actions.
		add_action( 'wp_ajax_hustle_handle_palette_actions', array( $this, 'handle_palette_actions' ) );

		// Handle saving settings.
		add_action( 'wp_ajax_hustle_save_settings', array( $this, 'ajax_settings_save' ) );
	}

	/**
	 * Filter IPs
	 *
	 * @since 4.0
	 * @param string $ip_string
	 * @return array valid IPs
	 */
	private function filter_ips( $ip_string ) {

		// Create an array with their values.
		$ip_array = preg_split( '/[\s,]+/', $ip_string, null, PREG_SPLIT_NO_EMPTY );

		// Remove from the array the IPs that are not valid IPs.
		foreach ( $ip_array as $key => $ip ) {
			if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				unset( $ip_array[ $key ] );
				continue;
			}
		}

		return $ip_array;
	}

	/**
	 * Reset the plugin
	 *
	 * @since 4.0.3
	 */
	public function reset_settings() {
		Opt_In_Utils::validate_ajax_call( 'hustle_reset_settings' );
		Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_settings' );

		/**
		 * Fires before Settings reset
		 *
		 * @since 4.0.3
		 */
		do_action( 'hustle_before_reset_settings' );

		// Delete starts here.
		Hustle_Deletion::hustle_delete_custom_options();
		Hustle_Deletion::hustle_delete_addon_options();
		Hustle_Deletion::hustle_clear_module_views();
		Hustle_Deletion::hustle_clear_module_submissions();
		Hustle_Deletion::hustle_clear_modules();

		/**
		 * Fires after Settings reset
		 *
		 * @since 4.0.3
		 */
		do_action( 'hustle_after_reset_settings' );

	}

	/**
	 * Remove the requested IPs from views and conversions on batches.
	 *
	 * @since 3.0.6
	 */
	public function remove_ips_from_tables() {
		Opt_In_Utils::validate_ajax_call( 'hustle_remove_ips' );
		Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_settings' );

		/**
		 * From Tracking
		 */
		$range                = filter_input( INPUT_POST, 'range', FILTER_SANITIZE_STRING );
		$tracking             = Hustle_Tracking_Model::get_instance();
		$hustle_entries_admin = new Hustle_Entry_Model();

		if ( 'all' === $range ) {
			$tracking->set_null_on_all_ips();
			$hustle_entries_admin->delete_all_ips();
			$message = esc_html__( 'All IP addresses have been successfully deleted from the database.', 'hustle' );

		} else {
			$values = filter_input( INPUT_POST, 'ips', FILTER_SANITIZE_STRING );
			if ( ! empty( $values ) ) {
				$values = preg_replace( '/ /', '', $values );
				$r      = preg_split( '/[\r\n]/', $values );
				$ios    = array();
				foreach ( $r as $one ) {
					$is_valid = ( filter_var( $one, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) || filter_var( $one, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) );

					if ( $is_valid ) {
						$ips[] = $one;
						continue;
					}
					$a = explode( '-', $one );
					if ( 2 !== count( $a ) ) {
						continue;
					}
					$is_valid = filter_var( $a[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
					if ( ! $is_valid ) {
						continue;
					}
					$is_valid = filter_var( $a[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
					if ( ! $is_valid ) {
						continue;
					}
					$ips[] = array_map( 'ip2long', $a );
				}
				$tracking->set_null_on_selected_ips( $ips );
				$hustle_entries_admin->delete_selected_ips( $ips );
				$message = esc_html__( 'All selected IP addresses have been successfully deleted from the database.', 'hustle' );

			} else {
				$message = esc_html__( 'No IPs were deleted. You must provide at least one IP.', 'hustle' );
			}
		}

		wp_send_json_success( array( 'message' => $message ) );
	}

	/**
	 * Saves the global privacy settings.
	 *
	 * @since 4.0
	 */
	public function save_privacy_settings() {

		$filter_args = array(
			'ip_tracking'                       => FILTER_SANITIZE_STRING,
			// Account erasure request
			'retain_sub_on_erasure'             => FILTER_SANITIZE_STRING,
			// Submissions retention
			'retain_submission_forever'         => FILTER_SANITIZE_STRING,
			'submissions_retention_number'      => FILTER_SANITIZE_NUMBER_INT,
			'submissions_retention_number_unit' => FILTER_SANITIZE_STRING,
			// IPs retention
			'retain_ip_forever'                 => FILTER_SANITIZE_STRING,
			'ip_retention_number'               => FILTER_SANITIZE_NUMBER_INT,
			'ip_retention_number_unit'          => FILTER_SANITIZE_STRING,
			// Tracking retention
			'retain_tracking_forever'           => FILTER_SANITIZE_STRING,
			'tracking_retention_number'         => FILTER_SANITIZE_NUMBER_INT,
			'tracking_retention_number_unit'    => FILTER_SANITIZE_STRING,
		);
		$data        = filter_input_array( INPUT_POST, $filter_args, false );

		$stored_settings = Hustle_Settings_Admin::get_privacy_settings();

		$new_settings = array_merge( $stored_settings, $data );

		Hustle_Settings_Admin::update_hustle_settings( $new_settings, 'privacy' );
		wp_send_json_success();
	}

	/**
	 * Saves the global privacy settings.
	 *
	 * @since 4.0.2
	 */
	public function save_data_settings() {

		$reset_settings_uninstall = filter_input( INPUT_POST, 'reset_settings_uninstall', FILTER_SANITIZE_STRING );

		$value = array(
			'reset_settings_uninstall' => '1' === $reset_settings_uninstall ? '1' : '0',
		);

		Hustle_Settings_Admin::update_hustle_settings( $value, 'data' );
		wp_send_json_success();
	}

	/**
	 * Save the data under the Top Metric tab.
	 *
	 * @since 4.0.0
	 */
	private function save_top_metrics_settings() {
		$data    = filter_input( INPUT_POST, 'metrics', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
		$metrics = ! empty( $data ) ? array_filter( $data ) : array();

		// Only 3 metrics can be selected. No more.
		if ( 3 < count( $metrics ) ) {
			wp_send_json_error(
				array(
					'notification' => array(
						'status'  => 'error',
						'message' => esc_html__( "You can't select more than 3 metrics.", 'hustle' ),
					),
				)
			);
		}

		$allowed_metric_keys = array(
			'average_conversion_rate',
			'today_conversions',
			'last_week_conversions',
			'last_month_conversions',
			'total_conversions',
			'most_conversions',
			'inactive_modules_count',
			'total_modules_count',
		);

		$data_to_store = array();
		foreach ( $metrics as $name ) {
			if ( in_array( $name, $allowed_metric_keys, true ) ) {
				$data_to_store[] = $name;
			}
		}

		Hustle_Settings_Admin::update_hustle_settings( $data_to_store, 'top_metrics' );
		wp_send_json_success();
	}

	/**
	 * Save the reCaptcha settings.
	 *
	 * @since 4.0
	 */
	private function save_recaptcha_settings() {

		$settings_to_save = array(
			// V2 Checkbox
			'v2_checkbox_site_key'    => '',
			'v2_checkbox_secret_key'  => '',
			// V2 Invisible
			'v2_invisible_site_key'   => '',
			'v2_invisible_secret_key' => '',
			// V3 Recaptcha
			'v3_recaptcha_site_key'   => '',
			'v3_recaptcha_secret_key' => '',
			'language'                => 'automatic',
		);

		foreach ( $settings_to_save as $key => $value ) {
			$incoming_setting = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );

			if ( $incoming_setting ) {
				$settings_to_save[ $key ] = trim( $incoming_setting );
			}
		}

		// Keep these keys stored in case the user rolls back to before 4.0.3.
		$settings_to_save['sitekey'] = $settings_to_save['v2_checkbox_site_key'];
		$settings_to_save['secret']  = $settings_to_save['v2_checkbox_secret_key'];

		Hustle_Settings_Admin::update_hustle_settings( $settings_to_save, 'recaptcha' );

		wp_send_json_success(
			array(
				'notification' => array(
					'status'  => 'success',
					'message' => esc_html__( 'reCAPTCHA configured successfully. You can now add reCAPTCHA field to your opt-in forms where you want the reCAPTCHA to appear.', 'hustle' ),
				),
				'callback'     => 'actionSaveRecaptcha',
			)
		);
	}

	/**
	 * Save the Accessibility settings.
	 *
	 * @since 4.0.0
	 */
	private function save_accessibility_settings() {

		$accessibility_color = filter_input( INPUT_POST, 'hustle-accessibility-color', FILTER_VALIDATE_BOOLEAN );

		if ( is_null( $accessibility_color ) ) {
			wp_send_json_error();
		}
		$value = array(
			'accessibility_color' => $accessibility_color,
		);

		Hustle_Settings_Admin::update_hustle_settings( $value, 'accessibility' );

		wp_send_json_success( array( 'url' => true ) );
	}

	/**
	 * Save the Unsubscribe settings.
	 *
	 * @since 4.0.0
	 */
	private function save_unsubscribe_settings() {

		$data           = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		$email_body     = wp_json_encode( $data['email_message'] );
		$sanitized_data = Opt_In_Utils::validate_and_sanitize_fields( $data );

		// Save the messages to be displayed in the unsubscription process.
		$messages_data = array(
			'enabled'                   => isset( $sanitized_data['messages_enabled'] ) ? $sanitized_data['messages_enabled'] : '0',
			'get_lists_button_text'     => $sanitized_data['get_lists_button_text'],
			'submit_button_text'        => $sanitized_data['submit_button_text'],
			'invalid_email'             => $sanitized_data['invalid_email'],
			'email_not_found'           => $sanitized_data['email_not_found'],
			'invalid_data'              => $sanitized_data['invalid_data'],
			'email_submitted'           => $sanitized_data['email_submitted'],
			'successful_unsubscription' => $sanitized_data['successful_unsubscription'],
			'email_not_processed'       => $sanitized_data['email_not_processed'],
		);

		// Save the unsubscription email settings.
		$email_data = array(
			'enabled'       => isset( $sanitized_data['email_enabled'] ) ? $sanitized_data['email_enabled'] : '0',
			'email_subject' => $sanitized_data['email_subject'],
			'email_body'    => $email_body,
		);

		$value = array(
			'messages' => $messages_data,
			'email'    => $email_data,
		);
		Hustle_Settings_Admin::update_hustle_settings( $value, 'unsubscribe' );

		wp_send_json_success();

	}

	/**
	 * Return the recaptcha script to be added in the page.
	 * This script changes when the recaptcha's language changes,
	 * so it must be updated on language change when previewing.
	 *
	 * @since 4.0.3
	 */
	public function load_recaptcha_preview() {

		$source = Hustle_Module_Front::add_recaptcha_script( '', true, true );
		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$html = '<script src="' . $source . '" async defer></script>';

		wp_send_json_success( $html );
	}

	/**
	 * Save Hustle settings
	 *
	 * @since 4.0
	 *
	 * @todo Handle error messages
	 */
	public function ajax_settings_save() {
		Opt_In_Utils::validate_ajax_call( 'hustle_settings_save' );
		Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_settings' );

		$tab = filter_input( INPUT_POST, 'target', FILTER_SANITIZE_STRING );

		switch ( $tab ) {
			case 'permissions':
				$this->save_permissions_settings();
				break;

			case 'general':
				$this->save_general_settings();
				break;

			case 'top_metrics':
				$this->save_top_metrics_settings();
				break;

			case 'analytics':
				$this->save_dashboard_analytics_settings();
				break;

			case 'recaptcha':
				$this->save_recaptcha_settings();
				break;

			case 'accessibility':
				$this->save_accessibility_settings();
				break;

			case 'unsubscribe':
				$this->save_unsubscribe_settings();
				break;

			case 'privacy':
				$this->save_privacy_settings();
				break;

			case 'data':
				$this->save_data_settings();
				break;

			default:
				break;
		}

		// The action is not listed. No one should land here if following the regular plugin's paths.
		wp_send_json_error(
			array(
				'notification' => array(
					'status'  => 'error',
					'message' => esc_html__( "The action you're trying to perform was not found.", 'hustle' ),
				),
			)
		);
	}


	/**
	 * Handles saving the "Permissions" settings.
	 *
	 * @since 4.1.0
	 */
	private function save_permissions_settings() {

		// Handle per module roles. We'll go with per permission next.
		$current_modules_ids = filter_input( INPUT_POST, 'modules_ids', FILTER_SANITIZE_STRING );
		$modules_ids         = empty( $current_modules_ids ) ? array() : explode( ',', $current_modules_ids );

		if ( ! empty( $modules_ids ) ) {
			$modules_roles = filter_input( INPUT_POST, 'modules', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

			foreach ( $modules_ids as $module_id ) {

				$module = new Hustle_Module_Model( $module_id );
				if ( ! is_wp_error( $module ) ) {

					$selected_roles = isset( $modules_roles[ $module_id ] ) ? $modules_roles[ $module_id ] : array();
					$module->update_edit_roles( $selected_roles );
				}
			}
		}

		// Handling per permissions roles here.
		$filter         = array(
			'filter' => FILTER_SANITIZE_STRING,
			'flags'  => FILTER_REQUIRE_ARRAY,
		);
		$filter_options = array(
			'create'            => $filter,
			'edit_integrations' => $filter,
			'access_emails'     => $filter,
			'edit_settings'     => $filter,
		);
		$incoming       = filter_input_array( INPUT_POST, $filter_options );

		// If the role can create modules, it can also edit them.
		$incoming['edit'] = $incoming['create'];

		// Capability related to each incoming input.
		$hustle_capabilities = array(
			'create'            => 'hustle_create',
			'edit'              => 'hustle_edit_module',
			'edit_integrations' => 'hustle_edit_integrations',
			'access_emails'     => 'hustle_access_emails',
			'edit_settings'     => 'hustle_edit_settings',
		);

		$existing_roles = Opt_In_Utils::get_user_roles();

		// Loop through the submitted capabilities.
		foreach ( $incoming as $capability => $selected_roles ) {

			if ( ! is_array( $selected_roles ) ) {
				// The filter failed. No roles were selected.
				$incoming[ $capability ] = array();
				$selected_roles          = array();

			} else {

				// Loop through the selected roles of this capability. Unset any invalid role.
				foreach ( $selected_roles as $key => $role_slug ) {

					if ( ! isset( $existing_roles[ $role_slug ] ) ) {
						unset( $incoming[ $capability ][ $key ] );
					}
				}
			}

			// Update roles capabilities.
			foreach ( $existing_roles as $role_slug => $role_name ) {
				if ( Opt_In_Utils::is_admin_role( $role_slug ) ) {
					continue;
				}

				$role = get_role( $role_slug );

				$cap = $hustle_capabilities[ $capability ];
				if ( in_array( $role_slug, $selected_roles, true ) ) {
					// Add capability.
					$role->add_cap( $cap );

				} else {

					// Check if this role can edit at least one module before removing the cap.
					if ( 'edit' === $capability ) {

						if ( ! Hustle_Module_Model::can_role_edit_one_module( $role_slug ) ) {
							// Remove capability.
							$role->remove_cap( $cap );
						} else {
							$role->add_cap( $cap );
						}
					} else {
						// Remove capability.
						$role->remove_cap( $cap );
					}
				}
			}
		}

		// Store per permission roles.
		Hustle_Settings_Admin::update_hustle_settings( $incoming, 'permissions' );

		wp_send_json_success();

	}

	/**
	 * Handles saving the "General" settings.
	 *
	 * @since 4.1.0
	 */
	private function save_general_settings() {

		// Retrieve the stored data.
		$stored_values = Hustle_Settings_Admin::get_general_settings();

		// Sanitize the incoming data.
		foreach ( $stored_values as $key => $value ) {
			if ( 'sender_email_address' !== $key ) {
				$new_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
			} else {
				$new_value = filter_input( INPUT_POST, $key, FILTER_VALIDATE_EMAIL );
			}

			// Update it if valid.
			if ( false !== $new_value && ! is_null( $new_value ) ) {
				$stored_values[ $key ] = $new_value;
			}
		}

		Hustle_Settings_Admin::update_hustle_settings( $stored_values, 'general' );
		wp_send_json_success();
	}

	/**
	 * Handles saving the "Dashboard Analytics" settings.
	 *
	 * @since 4.1.0
	 */
	private function save_dashboard_analytics_settings() {

		$reload = false;
		$value  = Hustle_Settings_Admin::get_hustle_settings( 'analytics' );

		// Handle enable/disable action.
		$enable_toggled = filter_input( INPUT_POST, 'enabled', FILTER_SANITIZE_STRING );
		if ( false !== $enable_toggled && ! is_null( $enable_toggled ) ) {
			$value['enabled'] = $enable_toggled;
			$reload           = true;

		} else {

			// Handle storing the actual settings.
			$filter_args   = array(
				'modules' => array(
					'filter' => FILTER_SANITIZE_STRING,
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
				'role'    => array(
					'filter' => FILTER_SANITIZE_STRING,
					'flags'  => FILTER_REQUIRE_ARRAY,
				),
				'title'   => FILTER_SANITIZE_STRING,
			);
			$filtered_data = filter_input_array( INPUT_POST, $filter_args );

			// Use defaults if the filter fails or the value isn't set.
			$modules        = ! empty( $filtered_data['modules'] ) ? array_filter( $filtered_data['modules'] ) : array();
			$selected_roles = ! empty( $filtered_data['role'] ) ? $filtered_data['role'] : array();
			$title          = is_string( $filtered_data['title'] ) ? $filtered_data['title'] : '';

			$value = array(
				'enabled' => '1',
				'title'   => $title,
				'modules' => $modules,
				'role'    => Opt_In_Utils::get_admin_roles(),
			);

			// Store the roles if they exist.
			$roles = Opt_In_Utils::get_user_roles();
			foreach ( $selected_roles as $role_slug ) {
				if ( isset( $roles[ $role_slug ] ) ) {
					$value['role'][ $role_slug ] = $roles[ $role_slug ];
				}
			}

			// Update roles capability.
			foreach ( $roles as $role_key => $role_name ) {
				$role = get_role( $role_key );
				if ( Opt_In_Utils::is_admin_role( $role_key ) || ! $role ) {
					continue;
				}
				$cap = 'hustle_analytics';
				if ( in_array( $role_key, $selected_roles, true ) ) {
					// add capability.
					$role->add_cap( $cap );
				} else {
					// remove capability.
					$role->remove_cap( $cap );
				}
			}
		}

		// TODO: delete transient on uninstall.
		// TODO: get these dynamically.
		// Delete the transients set for retrieving this data in the WP Dashboard.
		// These are the same values available in Hustle_Wp_Dashboard_Page::get_analytic_ranges().
		delete_transient( 'hustle_wp_widget_daily_stats_7' );
		delete_transient( 'hustle_wp_widget_daily_stats_30' );
		delete_transient( 'hustle_wp_widget_daily_stats_90' );

		Hustle_Settings_Admin::update_hustle_settings( $value, 'analytics' );

		if ( ! $reload ) {
			wp_send_json_success();
		} else {
			wp_send_json_success( array( 'url' => true ) );
		}
	}

	/**
	 * Handle the palette's actions.
	 *
	 * @since 4.0.3
	 */
	public function handle_palette_actions() {

		Opt_In_Utils::validate_ajax_call( 'hustle_palette_action' );
		Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_settings' );

		$palette_id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
		$action     = filter_input( INPUT_POST, 'hustleAction', FILTER_SANITIZE_STRING );

		$args = array(
			'page'    => Hustle_Data::SETTINGS_PAGE,
			'section' => 'palettes',
		);

		switch ( $action ) {

			case 'delete':
				$name                 = Hustle_Settings_Admin::delete_custom_palette( $palette_id );
				$args['show-notice']  = 'success';
				$args['notice']       = 'palette_deleted';
				$args['deleted-name'] = rawurlencode( $name );
				break;

			case 'go-to-step':
				$step = filter_input( INPUT_POST, 'step', FILTER_SANITIZE_STRING );

				if ( '2' === $step ) {
					$this->action_edit_palette_go_second_step();
				} else {
					$id                  = $this->action_edit_palette_save();
					$args['show-notice'] = 'success';
					$args['notice']      = 'palette_saved';
					$args['saved-id']    = rawurlencode( $id );
				}
				break;

			default:
				break;
		}

		$url      = add_query_arg( $args, 'admin.php' );
		$response = array( 'url' => $url );

		wp_send_json_success( $response );
	}

	/**
	 * Palettes -> Edit palette. Handle the action from when going to second step.
	 *
	 * @since 4.0.3
	 */
	private function action_edit_palette_go_second_step() {

		$palette_slug = filter_input( INPUT_POST, 'slug', FILTER_SANITIZE_STRING );

		if ( $palette_slug ) { // Editing an existing palette.

			$palette_name          = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
			$palette_array         = Hustle_Palettes_Helper::get_palette_array( $palette_slug );
			$palette_array['slug'] = $palette_slug;
			$palette_array['name'] = $palette_name;

			$callback = 'actionOpenEditPalette';

		} else { // Creating a new palette.

			$callback    = 'actionGoToSecondStep';
			$base_source = filter_input( INPUT_POST, 'base_source', FILTER_SANITIZE_STRING );

			if ( 'palette' === $base_source ) {
				// Use an existing palette as the base.
				$palette       = filter_input( INPUT_POST, 'base_palette', FILTER_SANITIZE_STRING );
				$palette_array = Hustle_Palettes_Helper::get_palette_array( $palette );

			} else {
				// Use a module's palette as the base.

				$fallback_palette_name = filter_input( INPUT_POST, 'fallback_palette', FILTER_SANITIZE_STRING );
				$fallback_palette      = Hustle_Palettes_Helper::get_palette_array( $fallback_palette_name );

				$module_id = filter_input( INPUT_POST, 'module_id', FILTER_SANITIZE_STRING );

				$module = new Hustle_Module_Model( $module_id );

				if ( is_wp_error( $module ) ) {
					$palette_array = $fallback_palette;

				} else {
					$design = $module->get_design()->to_array();

					// remove option color keys from info modules.
					if ( 'informational' === $module->module_mode ) {
						$info   = Hustle_Palettes_Helper::get_palette_array( 'info-module' );
						$design = array_diff_key( $design, $info );
					}

					$module_palette = array_intersect_key( $design, $fallback_palette );
					$palette_array  = array_merge( $fallback_palette, $module_palette );
				}
			}
		}

		wp_send_json_success(
			array(
				'callback'     => $callback,
				'palette_data' => $palette_array,
			)
		);
	}

	/**
	 * Handle action for when saving the palette.
	 *
	 * @since 4.0.3
	 */
	private function action_edit_palette_save() {

		$palette_slug = filter_input( INPUT_POST, 'slug', FILTER_SANITIZE_STRING );
		$palette_name = filter_input( INPUT_POST, 'palette_name', FILTER_SANITIZE_STRING );

		// Remove non-palette data.
		$palette_colors = array_intersect_key( $_POST, Hustle_Palettes_Helper::get_palette_array( 'gray_slate' ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification

		$palette_data = array( 'palette' => $palette_colors );

		if ( $palette_slug ) {
			// Updating an existing palette.
			$palette_data['slug'] = $palette_slug;

		} else {
			// Creating a new one.
			$palette_data['name'] = $palette_name ? $palette_name : wp_rand();
		}

		$id = Hustle_Settings_Admin::save_custom_palette( $palette_data );

		return $id;
	}

}