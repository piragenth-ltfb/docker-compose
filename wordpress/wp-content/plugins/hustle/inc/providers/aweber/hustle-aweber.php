<?php
if ( ! class_exists( 'Hustle_Aweber' ) ) :

	class Hustle_Aweber extends Hustle_Provider_Abstract {

		const SLUG = 'aweber';
		// const NAME = "AWeber";

		const APP_ID = 'b0cd0152';

		const AUTH_CODE             = 'aut_code';
		const CONSUMER_KEY          = 'consumer_key';
		const CONSUMER_SECRET       = 'consumer_secret';
		const ACCESS_TOKEN          = 'access_token';
		const ACCESS_SECRET         = 'access_secret';
		const ACCESS_OAUTH2_TOKEN   = 'access_oauth2_token';
		const REFRESH_TIME          = 'expires_in';
		const ACCESS_OAUTH2_REFRESH = 'access_oauth2_refresh';

		/**
		 * @var $api AWeberAPI
		 */
		protected static $api;
		protected static $errors;

		/**
		 * Aweber Provider Instance
		 *
		 * @since 3.0.5
		 *
		 * @var self|null
		 */
		protected static $_instance = null;

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_slug = 'aweber';

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_version = '1.0';

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_class = __CLASS__;

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_title = 'Aweber';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Aweber_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_Aweber_Form_Hooks';

		/**
		 * Connected Account Info
		 *
		 * @var integer
		 */
		private $_account_id = 0;

		/**
		 * Provider constructor.
		 */
		public function __construct() {
			$this->_icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->_logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';
		}

		/**
		 * Get Instance
		 *
		 * @return self|null
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public static function api() {
			if ( ! is_null( self::$api ) ) {

				return self::$api;
			}

			return null;
		}
		/**
		 * Get API Instance
		 *
		 * @since 1.0
		 * @since 4.0.1 $multi_global_id parameter added
		 *
		 * @param string|null $multi_global_id
		 * @param array|null  $api_credentials
		 *
		 * @return Hustle_Addon_Aweber_Wp_Api
		 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
		 */
		public function get_api( $multi_global_id = null, $api_credentials = array() ) {

			if ( is_null( self::$api ) ) {

				if ( ! empty( $api_credentials ) ) {
					$api = new Hustle_Addon_Aweber_Wp_Api( $api_credentials );
				} elseif ( empty( $api_credentials ) && ! empty( $multi_global_id ) ) {

					if ( ! $multi_global_id ) {
						throw new Hustle_Addon_Aweber_Wp_Api_Exception( __( 'Missing global ID instance.', 'hustle' ) );
					}
					$api_credentials = $this->get_credentials_keys( $multi_global_id );
					$api             = new Hustle_Addon_Aweber_Wp_Api( $api_credentials );
				} else {
					$api = new Hustle_Addon_Aweber_Wp_Api();
				}

				self::$api = $api;
			}

			return self::$api;
		}

		/**
		 * Retrieve the stored credentials key.
		 * Checks the global-multi settings first. If empty, checks
		 * the old wp_options keys where it was stored before 4.0.1.
		 *
		 * @since 4.0.1
		 *
		 * @param string $multi_global_id
		 * @return array
		 */
		private function get_credentials_keys( $multi_global_id ) {

			$get_keys_from_options = false;

			$keys_names        = self::get_option_keys();
			$api_credentials   = array_fill_keys( $keys_names, '' );
			$setting_values    = $this->get_settings_values();
			$instance_settings = $setting_values[ $multi_global_id ];

			if ( isset( $instance_settings[ self::ACCESS_OAUTH2_TOKEN ] ) ) {
				foreach ( $api_credentials as $api_credentials_key => $value ) {

					// Some of our pre-defined keys aren't used for oAuth2.
					if ( ! isset( $instance_settings[ $api_credentials_key ] ) ) {
						continue;
					}
					$api_credentials[ $api_credentials_key ] = $instance_settings[ $api_credentials_key ];
				}
			} else {
				foreach ( $api_credentials as $api_credentials_key => $value ) {

					/**
					 * If there's any key missing in the saved multi settings,
					 * try retrieving them from the wp_options instead.
					 */
					if ( empty( $instance_settings[ $api_credentials_key ] ) ) {
						$get_keys_from_options = true;
						break;
					}

					$api_credentials[ $api_credentials_key ] = $instance_settings[ $api_credentials_key ];
				}

				/**
				 * Any of the keys is missing in the saved multi settings.
				 * Try retrieving them from wp_options.
				 * This is were they were stored before 4.0.1.
				 */
				if ( $get_keys_from_options ) {

					foreach ( $api_credentials as $api_credentials_key => $value ) {

						$saved_key = $this->get_provider_option( $api_credentials_key, '' );
						if ( empty( $saved_key ) ) {
							break;
						}

						$api_credentials[ $api_credentials_key ] = $saved_key;
					}
				}
			}

			return $api_credentials;
		}

		/**
		 * Get the account ID
		 *
		 * @since 4.0.1
		 *
		 * @param string $global_multi_id
		 * @return string|false
		 */
		public function get_account_id( $global_multi_id ) {

			$account_id = $this->get_setting( 'account_id', false, $global_multi_id );

			if ( ! $account_id ) {
				try {
					$account_id                     = $this->get_validated_account_id( $global_multi_id );
					$saved_settings                 = $this->get_settings_values();
					$settings_to_save               = $saved_settings[ $global_multi_id ];
					$settings_to_save['account_id'] = $account_id;

					$this->save_multi_settings_values( $global_multi_id, $settings_to_save );

				} catch ( Exception $e ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				}
			}

			return $account_id;
		}

		/**
		 * Get validated account_id
		 *
		 * @return integer
		 * @throws Hustle_Addon_Aweber_Exception
		 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
		 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
		 */
		public function get_validated_account_id( $global_multi_id = null, $api_key = array() ) {

			$api = $this->get_api( $global_multi_id, $api_key );

			$accounts = $api->get_accounts();
			if ( ! isset( $accounts->entries ) ) {
				throw new Hustle_Addon_Aweber_Exception( __( 'Failed to get AWeber account information', 'hustle' ) );
			}

			$entries = $accounts->entries;
			if ( ! isset( $entries[0] ) ) {
				throw new Hustle_Addon_Aweber_Exception( __( 'Failed to get AWeber account information', 'hustle' ) );
			}

			$first_entry = $entries[0];
			$account_id  = $first_entry->id;

			/**
			 * Filter validated account_id
			 *
			 * @since 1.3
			 *
			 * @param integer                        $account_id
			 * @param object                         $accounts
			 * @param Hustle_Addon_Aweber_Wp_Api $api
			 */
			$account_id = apply_filters( 'hustle_addon_aweber_validated_account_id', $account_id, $accounts, $api );

			return $account_id;
		}

		/**
		 * Validate Access Token
		 *
		 * @param $application_key
		 * @param $application_secret
		 * @param $request_token
		 * @param $token_secret
		 * @param $oauth_verifier
		 *
		 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
		 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
		 */
		public function get_validated_access_token( $verifier_code ) {
			// reinit api
			self::$api = null;

			// get access_token
			$api           = $this->get_api();
			$access_tokens = $api->process_callback_request( $verifier_code );

			// reinit api with new access token open success for future usage
			self::$api = null;
			return $access_tokens;
		}

		/**
		 * Get the wizard callbacks for the global settings.
		 *
		 * @since 4.0
		 *
		 * @return array
		 */
		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'configure_api_key' ),
					'is_completed' => array( $this, 'is_connected' ),
				),
			);
		}


		/**
		 * Configure the API key settings. Global settings.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function configure_api_key( $submitted_data ) {
			$has_errors        = false;
			$default_data      = array(
				'api_key' => '',
				'name'    => '',
			);
			$current_data      = $this->get_current_data( $default_data, $submitted_data );
			$is_submit         = isset( $submitted_data['api_key'] );
			$global_multi_id   = $this->get_global_multi_id( $submitted_data );
			$api_key_validated = true;
			if ( $is_submit ) {

				$validated_credentials = $this->get_validated_credentials( $submitted_data['api_key'] );

				if ( empty( $validated_credentials ) || ! is_array( $validated_credentials ) ) {
					$api_key_validated = false;
					$error_message     = $this->provider_connection_falied();
					$has_errors        = true;
				}

				if ( ! $has_errors ) {

					// If not active, activate it.
					if (
					$this->is_active() ||
					Hustle_Providers::get_instance()->activate_addon( $this->_slug )
					) {

						$keys_names = self::get_option_keys();

						$settings_to_save = array(
							'api_key' => $current_data['api_key'],
							'name'    => $current_data['name'],
						);

						foreach ( $keys_names as $name ) {

							// Some of our pre-defined keys aren't used for oAuth2.
							if ( ! isset( $validated_credentials[ $name ] ) ) {
								continue;
							}

							// Add the key to the $settings_to_save.
							$settings_to_save[ $name ] = $validated_credentials[ $name ];

							// Store it in the wp_options to remain compatible with 4.0.0 in case of a rollback, even though these won't be used.
							$this->update_provider_option( $name, $validated_credentials[ $name ] );
						}

						$this->save_multi_settings_values( $global_multi_id, $settings_to_save );

					} else {
						$error_message = __( "Provider couldn't be activated.", 'hustle' );
						$has_errors    = true;

					}
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Aweber Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
						'buttons'      => array(
							'close' => array(
								'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost', 'close' ),
							),
						),
						'redirect'     => false,
						'has_errors'   => false,
						'notification' => array(
							'type' => 'success',
							'text' => '<strong>' . $this->get_title() . '</strong> ' . __( 'Successfully connected', 'hustle' ),
						),
					);

				}
			}

			$options = array(
				array(
					'type'     => 'wrapper',
					'class'    => $api_key_validated ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'api_key',
							'value' => __( 'Authorization code', 'hustle' ),
						),
						'api_key' => array(
							'type'        => 'text',
							'name'        => 'api_key',
							'value'       => $current_data['api_key'],
							'placeholder' => __( 'Enter Code', 'hustle' ),
							'id'          => 'api_key',
							'icon'        => 'key',
						),
						'error'   => array(
							'type'  => 'error',
							'class' => $api_key_validated ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid Aweber authorization code', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'instance-name-input',
							'value' => __( 'Identifier', 'hustle' ),
						),
						'name'    => array(
							'type'        => 'text',
							'name'        => 'name',
							'value'       => $current_data['name'],
							'placeholder' => __( 'E.g. Business Account', 'hustle' ),
							'id'          => 'instance-name-input',
						),
						'message' => array(
							'type'  => 'description',
							'value' => __( 'Helps to distinguish your integrations if you have connected to the multiple accounts of this integration.', 'hustle' ),
						),
					),
				),
			);

			$api = $this->get_api();

			$auth_url = $api->get_authorization_uri( 0, true, Hustle_Data::INTEGRATIONS_PAGE );

			$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Configure Aweber', 'hustle' ), sprintf( __( 'Please %1$sclick here%2$s to connect to Aweber service to get your authorization code.', 'hustle' ), '<a href="' . esc_url( $auth_url ) . '" target="_blank">', '</a>' ) );
			if ( $has_errors ) {

				$error_notice = array(
					'type'  => 'notice',
					'icon'  => 'info',
					'class' => 'sui-notice-error',
					'value' => esc_html( $error_message ),
				);
				array_unshift( $options, $error_notice );
			}
			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$is_edit = $this->settings_are_completed( $global_multi_id );
			if ( $is_edit ) {
				$buttons = array(
					'disconnect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Disconnect', 'hustle' ),
							'sui-button-ghost',
							'disconnect',
							true
						),
					),
					'save'       => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Save', 'hustle' ),
							'',
							'connect',
							true
						),
					),
				);
			} else {
				$buttons = array(
					'connect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Connect', 'hustle' ),
							'sui-button-right',
							'connect',
							true
						),
					),
				);

			}

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => $has_errors,
			);

			return $response;
		}

		/**
		 * Validate the migrated API key.
		 *
		 * @since 4.1.1
		 *
		 * @param array $data
		 * @return array
		 */
		public function configure_migrated_api_key( $data ) {
			$has_errors   = false;
			$default_data = array(
				'api_key' => '',
				'name'    => '',
			);

			$current_data          = $this->get_current_data( $default_data, $data );
			$global_multi_id       = $this->get_global_multi_id( $data );
			$validated_credentials = $this->get_validated_credentials( $data['api_key'] );

			if ( empty( $validated_credentials ) || ! is_array( $validated_credentials ) ) {
				$has_errors = true;
			}

			if ( ! $has_errors ) {

				// If not active, activate it.
				if (
				$this->is_active() ||
				Hustle_Providers::get_instance()->activate_addon( $this->_slug )
				) {

					$keys_names = self::get_option_keys();

					$settings_to_save = array(
						'api_key' => $current_data['api_key'],
						'name'    => $current_data['name'],
					);

					foreach ( $keys_names as $name ) {

						// Some of our pre-defined keys aren't used for oAuth2.
						if ( ! isset( $validated_credentials[ $name ] ) ) {
							continue;
						}

						// Add the key to the $settings_to_save
						$settings_to_save[ $name ] = $validated_credentials[ $name ];

						// Store it in the wp_options to remain compatible with 4.0.0 in case of a rollback, even though these won't be used.
						$this->update_provider_option( $name, $validated_credentials[ $name ] );
					}

					$this->save_multi_settings_values( $global_multi_id, $settings_to_save );

				} else {
					$has_errors = true;
				}
			}

			return $has_errors;
		}

		/**
		 * Validate the provided API key.
		 *
		 * @since 4.0
		 *
		 * @param string $api_key
		 * @return bool
		 */
		private function get_validated_credentials( $api_key ) {
			if ( empty( trim( $api_key ) ) ) {
				return false;
			}

			// Check if API key is valid
			try {

				$tokens = $this->get_validated_access_token( $api_key );
				if ( ! $tokens ) {
					return false;
				} else {
					$api_key = array(
						self::ACCESS_OAUTH2_REFRESH => $tokens['refresh_token'],
						self::ACCESS_OAUTH2_TOKEN   => $tokens['access_token'],
						self::REFRESH_TIME          => time() + $tokens['expires_in'],
					);
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( $e->getMessage() );
				return false;
			}

			// Check API Key by validating it on get_info request
			try {
				$account_id = $this->get_validated_account_id( null, $api_key );
				if ( ! $account_id ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid Aweber authorization code.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return $api_key;
		}

		public function remove_wp_options() {
			$keys_names = self::get_option_keys();

			foreach ( $keys_names as $name ) {
				$this->delete_provider_option( $name );
			}
		}

		/**
		 * Get additional params' keys
		 *
		 * @return array
		 */
		private static function get_option_keys() {
			return array(
				self::CONSUMER_KEY,
				self::CONSUMER_SECRET,
				self::ACCESS_TOKEN,
				self::ACCESS_SECRET,
				self::AUTH_CODE,
				self::ACCESS_OAUTH2_REFRESH,
				self::ACCESS_OAUTH2_TOKEN,
				self::REFRESH_TIME,
			);
		}

		public function get_30_provider_mappings() {
			return array(
				'api_key' => 'api_key',
			);
		}

		/**
		 * Refreshes the token for the registered multi-ids.
		 *
		 * @since 4.3.3
		 */
		public static function refresh_token() {
			$aweber_instance = self::get_instance();

			// Aweber is supposed to be connected if we're executing this, but check just in case.
			if ( ! $aweber_instance->is_connected() ) {
				return;
			}

			$multi_ids = wp_list_pluck( $aweber_instance->get_global_multi_ids(), 'id' );

			foreach ( $multi_ids as $multi_id ) {
				$api_instance = $aweber_instance->get_api( $multi_id );
				$api_instance->validate_auth_token_lifespan( $multi_id );
			}
		}
	}

endif;