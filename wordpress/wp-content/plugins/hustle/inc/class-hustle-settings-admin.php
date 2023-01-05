<?php
/**
 * File for Hustle_Settings_Admin class.
 *
 * @package Hustle
 * @since unknown
 */

/**
 * Class Hustle_Settings_Admin.
 * Handles saving and retrieving Hustle's global settings.
 */
class Hustle_Settings_Admin {

	/**
	 * Key of the Hustle's settings in wp_options.
	 *
	 * @since 4.0.0
	 */
	const SETTINGS_OPTION_KEY = 'hustle_settings';

	/**
	 * Gets the saved or default global unsubscription messages.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_unsubscribe_messages() {

		$settings = self::get_hustle_settings( 'unsubscribe' );

		// Default unsubscription messages.
		$default = array(
			'enabled'                   => '0',
			'get_lists_button_text'     => __( 'Get Lists', 'hustle' ),
			'submit_button_text'        => __( 'Unsubscribe!', 'hustle' ),
			'invalid_email'             => __( 'Please enter a valid email address.', 'hustle' ),
			'email_not_found'           => __( "Looks like you're not in our list!", 'hustle' ),
			'invalid_data'              => __( "The unsubscription data doesn't seem to be correct.", 'hustle' ),
			'email_submitted'           => __( 'Please check your email to confirm your unsubscription.', 'hustle' ),
			'successful_unsubscription' => __( "You've been successfully unsubscribed.", 'hustle' ),
			'email_not_processed'       => __( 'Something went wrong submitting the email. Please make sure a list is selected, and your install is able to send emails.', 'hustle' ),
		);

		$messages = $default;

		// Use customized unsubscribe messages if they're set, and if it's enabled (for frontend), or is_admin() (for settings page).
		if ( ! empty( $settings['messages'] ) ) {

			$saved_messages = $settings['messages'];
			if ( is_string( $saved_messages ) ) {
				$saved_messages = json_decode( $saved_messages );
			}

			if ( is_admin() || '0' !== (string) $saved_messages['enabled'] ) {
				$messages = stripslashes_deep( array_merge( $default, $saved_messages ) );
			}
		}

		return apply_filters( 'hustle_get_unsubscribe_messages', $messages );
	}

	/**
	 * Gets the saved or default global unsubscription email settings.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_unsubscribe_email_settings() {

		$default_email_body = sprintf(
			/* translators: 1: opening 'p' tag, 2: closing 'p' tag, 3: 'br' tag, 4: link to the unsubscribe url */
			esc_html__(
				'%1$sHi%2$s
				%1$sWe\'re sorry to see you go!%2$s
				%1$sClick on the link below to unsubscribe:%3$s
				%4$s%2$s',
				'hustle'
			),
			'<p>',
			'</p>',
			'<br />',
			'<a href="{hustle_unsubscribe_link}" target="_blank">{hustle_unsubscribe_link}</a>'
		);

		$default_email_settings = array(
			'enabled'       => '0',
			'email_subject' => __( 'Unsubscribe', 'hustle' ),
			'email_body'    => $default_email_body,
		);

		$settings = self::get_hustle_settings( 'unsubscribe' );

		// Use customized unsubscribe email messages if they're set, and if it's enabled (for frontend), or is_admin() (for settings page).
		$saved_settings = isset( $settings['email'] ) && ( ( isset( $settings['email']['enabled'] ) && '0' !== (string) $settings['email']['enabled'] ) || is_admin() ) ?
			$settings['email'] : array();

		$stored_email_settings = array();
		if ( ! empty( $saved_settings ) ) {
			$saved_settings['email_body'] = isset( $saved_settings['email_body'] ) ? json_decode( $saved_settings['email_body'] ) : '';
			$stored_email_settings        = stripslashes_deep( $saved_settings );
		}

		$email_settings = array_merge( $default_email_settings, $stored_email_settings );

		return apply_filters( 'hustle_get_unsubscribe_email', $email_settings );
	}

	/**
	 * Get pagination limit
	 *
	 * @since 4.0.3
	 *
	 * @param string $type module|submission Pagination limit type.
	 * @return int
	 */
	public static function get_per_page( $type ) {
		$general_settings = self::get_general_settings();
		$limit            = isset( $general_settings[ $type . '_pagination' ] ) ? (int) $general_settings[ $type . '_pagination' ] : 0;
		if ( 1 > $limit ) {
			$limit = 1;
		}

		return $limit;
	}

	/**
	 * Gets the saved or default global general settings.
	 *
	 * @since 4.0.3
	 * @return array
	 */
	public static function get_general_settings() {

		$default_settings = array(
			'module_pagination'                     => 10,
			'submission_pagination'                 => 10,
			'sender_email_name'                     => get_bloginfo( 'name' ),
			'sender_email_address'                  => get_option( 'admin_email', '' ),
			'mobile_breakpoint'                     => 782,
			'published_popup_on_dashboard'          => '1',
			'draft_popup_on_dashboard'              => '1',
			'published_slidein_on_dashboard'        => '1',
			'draft_slidein_on_dashboard'            => '1',
			'published_embedded_on_dashboard'       => '1',
			'draft_embedded_on_dashboard'           => '1',
			'debug_enabled'                         => '0',
			// Dashboard settings.
			'popup_on_dashboard'                    => 5,
			'published_popup_on_dashboard'          => '1',
			'draft_popup_on_dashboard'              => '1',
			'slidein_on_dashboard'                  => 5,
			'published_slidein_on_dashboard'        => '1',
			'draft_slidein_on_dashboard'            => '1',
			'social_sharing_on_dashboard'           => 5,
			'published_social_sharing_on_dashboard' => '1',
			'draft_social_sharing_on_dashboard'     => '1',
			'embedded_on_dashboard'                 => 5,
			'published_embedded_on_dashboard'       => '1',
			'draft_embedded_on_dashboard'           => '1',
		);

		$general_settings = $default_settings;
		$saved_settings   = self::get_hustle_settings( 'general' );

		// If we have settings already stored in "general".
		if ( ! empty( $saved_settings ) ) {
			$saved_settings = array_filter( $saved_settings, 'strlen' );

			/**
			 * Email sender name and address were stored somewhere else before 4.0.3.
			 * Retrieve it from the old location if missing in the new one.
			 */
			if ( empty( $saved_settings['sender_email_name'] ) || empty( $saved_settings['sender_email_address'] ) ) {

				$old_emails_settings = self::get_hustle_settings( 'emails' );

				if ( empty( $saved_settings['sender_email_name'] ) && ! empty( $old_emails_settings['sender_email_name'] ) ) {
					$saved_settings['sender_email_name'] = $old_emails_settings['sender_email_name'];
				}

				if ( empty( $saved_settings['sender_email_address'] ) && ! empty( $old_emails_settings['sender_email_address'] ) ) {
					$saved_settings['sender_email_address'] = $old_emails_settings['sender_email_address'];
				}
			}

			$general_settings = array_merge( $default_settings, $saved_settings );

		} else {

			// When upgrading, we might not have anything in "general" but still have "emails" stored in its old location.
			$old_emails_settings = self::get_hustle_settings( 'emails' );

			if ( ! empty( $old_emails_settings ) ) {

				$saved_settings = array();

				if ( ! empty( $old_emails_settings['sender_email_name'] ) ) {
					$saved_settings['sender_email_name'] = $old_emails_settings['sender_email_name'];
				}

				if ( ! empty( $old_emails_settings['sender_email_address'] ) ) {
					$saved_settings['sender_email_address'] = $old_emails_settings['sender_email_address'];
				}

				$general_settings = array_merge( $default_settings, $saved_settings );
			}
		}

		return apply_filters( 'hustle_get_general_settings', $general_settings );
	}

	/**
	 * Get the 'permissions' settings.
	 *
	 * @since 4.0.4
	 * @return array
	 */
	public static function get_permissions_settings() {

		$defaults = array(
			'create'            => array(),
			'edit_integrations' => array(),
			'access_emails'     => array(),
			'edit_settings'     => array(),
		);

		$settings = self::get_hustle_settings( 'permissions' );

		foreach ( $defaults as $permission => $roles ) {
			if ( empty( $settings[ $permission ] ) ) {
				$settings[ $permission ] = Opt_In_Utils::get_admin_roles();
			}
		}

		return apply_filters( 'hustle_get_permissions_settings', $settings );
	}

	/**
	 * Gets the saved or default global reCaptcha settings.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_recaptcha_settings() {

		$default = array(
			// V2 Checkbox.
			'v2_checkbox_site_key'    => '',
			'v2_checkbox_secret_key'  => '',
			// V2 Invisible.
			'v2_invisible_site_key'   => '',
			'v2_invisible_secret_key' => '',
			// V3 Recaptcha.
			'v3_recaptcha_site_key'   => '',
			'v3_recaptcha_secret_key' => '',
			'language'                => 'automatic',
		);

		$recaptcha_settings = $default;
		$saved_settings     = self::get_hustle_settings( 'recaptcha' );

		// Use the standard 4.0.2 recapatcha keys (v2 recaptchas) with the new 4.0.3 keys if not set.
		if ( ! isset( $saved_settings['v2_checkbox_site_key'] ) && ! empty( $saved_settings['sitekey'] ) ) {
			$saved_settings['v2_checkbox_site_key'] = $saved_settings['sitekey'];
		}
		if ( ! isset( $saved_settings['v2_checkbox_secret_key'] ) && ! empty( $saved_settings['secret'] ) ) {
			$saved_settings['v2_checkbox_secret_key'] = $saved_settings['secret'];
		}

		if ( ! empty( $saved_settings ) ) {
			$recaptcha_settings = array_merge( $default, $saved_settings );
		}

		return apply_filters( 'hustle_get_recaptcha_settings', $recaptcha_settings );
	}

	/**
	 * Get the recaptcha versions that are available to be used.
	 *
	 * @since 4.0.3
	 * @return array
	 */
	public static function get_available_recaptcha_versions() {

		$available_recaptchas = array();
		$settings             = self::get_recaptcha_settings();
		$recaptcha_versions   = array(
			'v2_checkbox',
			'v2_invisible',
			'v3_recaptcha',
		);

		foreach ( $recaptcha_versions as $version ) {

			// If this versions has the Site key and Secret key stored, it's available to use.
			if ( ! empty( $settings[ $version . '_site_key' ] ) && ! empty( $settings[ $version . '_secret_key' ] ) ) {
				$available_recaptchas[] = $version;
			}
		}

		return $available_recaptchas;
	}

	/**
	 * Get the settings of the top metrics.
	 *
	 * @since 4.0.2
	 * @return array
	 */
	public static function get_top_metrics_settings() {

		$defaults        = array( 'average_conversion_rate', 'total_conversions', 'most_conversions' );
		$stored_settings = self::get_hustle_settings( 'top_metrics' );

		// Use defaults if empty.
		if ( empty( $stored_settings ) ) {
			$stored_settings = $defaults;
		}

		return $stored_settings;
	}

	/**
	 * Get the stored dashboard analytics settings.
	 *
	 * @since 4.1.0
	 * @return array
	 */
	public static function get_dashboard_analytics_settings() {

		$defaults = array(
			'title'   => '',
			'role'    => array(),
			'enabled' => '0',
			'modules' => array(),
		);

		$stored_settings = self::get_hustle_settings( 'analytics' );

		// If the stored settings' role is empty, use the default one.
		if ( empty( $stored_settings['role'] ) ) {
			$defaults['role'] = Opt_In_Utils::get_admin_roles();

			unset( $stored_settings['role'] );
		}

		$dashboard_analytics_settings = array_merge( $defaults, $stored_settings );

		return apply_filters( 'hustle_get_dashboard_analytics_settings', $dashboard_analytics_settings );
	}

	/**
	 * Get privacy settings.
	 *
	 * @since 4.0.2
	 * @return array
	 */
	public static function get_privacy_settings() {
		$defaults = array(
			'ip_tracking'                       => 'on',
			'retain_sub_on_erasure'             => '1',

			'retain_submission_forever'         => '1',
			'submissions_retention_number'      => 30,
			'submissions_retention_number_unit' => 'days',

			'retain_ip_forever'                 => '1',
			'ip_retention_number'               => 30,
			'ip_retention_number_unit'          => 'days',

			'retain_tracking_forever'           => '1',
			'tracking_retention_number'         => 30,
			'tracking_retention_number_unit'    => 'days',
		);

		$stored = self::get_hustle_settings( 'privacy' );

		$settings = array_merge( $defaults, $stored );

		return apply_filters( 'hustle_get_privacy_settings', $settings );
	}

	/**
	 * Get the values of the Data settings.
	 *
	 * @since 4.0.2
	 * @return array
	 */
	public static function get_data_settings() {
		$default = array(
			'reset_settings_uninstall' => '0',
		);

		$stored = self::get_hustle_settings( 'data' );

		$settings = array_merge( $default, $stored );

		return apply_filters( 'hustle_get_data_settings', $settings );
	}

	/**
	 * Get settings
	 *
	 * @since 4.0.0
	 *
	 * @param string $key Key from settings, can be null, then whole * settings is returned.
	 */
	public static function get_hustle_settings( $key = null ) {

		$settings = get_option( self::SETTINGS_OPTION_KEY, array() );

		if ( ! empty( $key ) ) {

			if ( isset( $settings[ $key ] ) ) {

				$specific_setting = $settings[ $key ];

				if ( ! is_array( $specific_setting ) ) {
					$specific_setting = json_decode( $specific_setting, true );
				}

				return $specific_setting;
			}

			return array();
		}

		return $settings;
	}

	/**
	 * Update Hustle Settings
	 *
	 * @since 4.0.0
	 * @param mixed  $value Value to store.
	 * @param string $key Key from settings, can be null, then whole settings will be saved.
	 */
	public static function update_hustle_settings( $value, $key = null ) {
		if ( empty( $key ) ) {
			return;
		}

		if ( 'all' === $key ) {
			update_option( self::SETTINGS_OPTION_KEY, $value, false );
			return;
		}
		$settings         = self::get_hustle_settings();
		$settings[ $key ] = $value;
		update_option( self::SETTINGS_OPTION_KEY, $settings, false );
	}

	/**
	 * Delete an existing custom palette.
	 *
	 * @since 4.0.3
	 *
	 * @param string $palette_id ID of the palette to be deleted.
	 */
	public static function delete_custom_palette( $palette_id ) {

		$name            = false;
		$stored_palettes = self::get_custom_color_palettes();

		if ( isset( $stored_palettes[ $palette_id ] ) ) {

			$name = $stored_palettes[ $palette_id ]['name'];
			unset( $stored_palettes[ $palette_id ] );
			update_option( 'hustle_custom_palettes', $stored_palettes, false );
		}

		return $name;
	}

	/**
	 * Do the actual saving of a custom palette.
	 * The passed array should be like:
	 * array(
	 *      'slug'      => { string }, // Required when updating an existing palette. Omit it when creating a new one.
	 *      'name'      => { string }, // The display name. Can be omitted when updating an existing one.
	 *      'palette'   => { array() } // The actual palette's colors
	 * )
	 *
	 * @since 4.0.3
	 * @param array $palette_data Data of the palette to be saved.
	 * @return string
	 */
	public static function save_custom_palette( $palette_data ) {

		$stored_palettes = self::get_custom_color_palettes();

		if ( isset( $palette_data['slug'] ) && isset( $stored_palettes[ $palette_data['slug'] ] ) ) {

			// Update existing palette.
			$id           = $palette_data['slug'];
			$palette_data = array_merge( $stored_palettes[ $id ], $palette_data );

		} else {
			// Create new palette.
			$id = uniqid( '', true );

			// Change the id until it's unique.
			while ( isset( $stored_palettes[ $id ] ) ) {
				$id = uniqid( '', true );
			}

			$palette_data['slug'] = $id;
		}

		$stored_palettes[ $id ] = $palette_data;

		update_option( 'hustle_custom_palettes', $stored_palettes, false );

		return $id;
	}

	/**
	 * Get the stored custom color palettes.
	 *
	 * @since 4.0.3
	 *
	 * @return array
	 */
	public static function get_custom_color_palettes() {

		$custom_palettes = get_option( 'hustle_custom_palettes', array() );

		return apply_filters( 'hustle_get_custom_color_palettes', $custom_palettes );
	}

}