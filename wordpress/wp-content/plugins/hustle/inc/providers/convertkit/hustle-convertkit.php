<?php
if ( ! class_exists( 'Hustle_ConvertKit' ) ) :

	include_once 'hustle-convertkit-api.php';

	/**
	 * Convertkit Email Integration
	 *
	 * @class Hustle_ConvertKit
	 * @version 2.0.3
	 **/
	class Hustle_ConvertKit extends Hustle_Provider_Abstract {

		const SLUG = 'convertkit';
		// const NAME = "ConvertKit";

		/**
		 * @var $api ConvertKit
		 */
		protected static $api;
		protected static $errors;

		/**
		 * Provider Instance
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
		protected $_slug = 'convertkit';

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
		protected $_title = 'ConvertKit';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_ConvertKit_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_ConvertKit_Form_Hooks';

		/**
		 * Array of options which should exist for confirming that settings are completed
		 *
		 * @since 4.0
		 * @var array
		 */
		protected $_completion_options = array( 'api_key', 'api_secret' );

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
			$has_errors      = false;
			$default_data    = array(
				'api_key'    => '',
				'api_secret' => '',
				'name'       => '',
			);
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$is_submit       = isset( $submitted_data['api_key'] ) && isset( $submitted_data['api_secret'] );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$api_key_valid = $api_secret_valid = true;

			if ( $is_submit ) {

				$api_key_valid     = ! empty( trim( $current_data['api_key'] ) );
				$api_secret_valid  = ! empty( trim( $current_data['api_secret'] ) );
				$api_key_validated = $api_key_valid
									&& $api_secret_valid
									&& $this->validate_credentials( $submitted_data['api_secret'], $submitted_data['api_key'] );

				if ( ! $api_key_validated ) {
					$error_message = $this->provider_connection_falied();
					$api_key_valid = $api_secret_valid = false;
					$has_errors    = true;
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'api_key'    => $current_data['api_key'],
						'api_secret' => $current_data['api_secret'],
						'name'       => $current_data['name'],
					);
					// If not active, activate it.
					// TODO: Wrap this in a friendlier method
					if ( Hustle_Provider_Utils::is_provider_active( $this->_slug )
							|| Hustle_Providers::get_instance()->activate_addon( $this->_slug ) ) {
						$this->save_multi_settings_values( $global_multi_id, $settings_to_save );
					} else {
						$error_message = __( "Provider couldn't be activated.", 'hustle' );
						$has_errors    = true;
					}
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'ConvertKit Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
					'class'    => $api_key_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'api_key',
							'value' => __( 'API Key', 'hustle' ),
						),
						'api_key' => array(
							'type'        => 'text',
							'name'        => 'api_key',
							'value'       => $current_data['api_key'],
							'placeholder' => __( 'Enter API Key', 'hustle' ),
							'id'          => 'api_key',
							'icon'        => 'key',
						),
						'error'   => array(
							'type'  => 'error',
							'class' => $api_key_valid ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid ConvertKit API key', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'class'    => $api_secret_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'      => array(
							'type'  => 'label',
							'for'   => 'api_secret',
							'value' => __( 'API Secret', 'hustle' ),
						),
						'api_secret' => array(
							'type'        => 'text',
							'name'        => 'api_secret',
							'value'       => $current_data['api_secret'],
							'placeholder' => __( 'Enter API Secret', 'hustle' ),
							'id'          => 'api_secret',
							'icon'        => 'key',
						),
						'error'      => array(
							'type'  => 'error',
							'class' => $api_secret_valid ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid ConvertKit API secret', 'hustle' ),
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

			if ( $has_errors ) {
				$error_notice = array(
					'type'  => 'notice',
					'icon'  => 'info',
					'class' => 'sui-notice-error',
					'value' => esc_html( $error_message ),
				);
				array_unshift( $options, $error_notice );
			}

			$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
				__( 'Configure ConvertKit', 'hustle' ),
				sprintf(
					/* translators: 1. opening 'a' tag to ConvertKit account, 2. closing 'a' tag */
					__( 'Log in to your %1$sConvertKit%2$s account to get your API Key.', 'hustle' ),
					'<a href="https://app.convertkit.com/account/edit" target="_blank">',
					'</a>'
				)
			);
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
		 * Validate the provided API key and API secret.
		 *
		 * @since 4.0
		 *
		 * @param string $api_secret
		 * @param string $api_key
		 * @return bool
		 */
		private function validate_credentials( $api_secret, $api_key ) {
			if ( empty( $api_key ) ) {
				return false;
			}

			try {
				// Check if API key and API secret are valid
				$api         = self::api( $api_key, $api_secret );
				$subscribers = $api->get_subscribers(); // check API secret
				$forms       = $api->get_forms(); // check API key

				if ( is_wp_error( $subscribers ) || is_wp_error( $forms ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid ConvertKit API key ore API secret.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}


		/**
		 * @param $api_key
		 * @return Hustle_ConvertKit_Api
		 */
		public static function api( $api_key, $api_secret = '' ) {

			if ( empty( self::$api ) ) {
				try {
					self::$api    = new Hustle_ConvertKit_Api( $api_key, $api_secret );
					self::$errors = array();
				} catch ( Exception $e ) {
					self::$errors = array( 'api_error' => $e );
				}
			}
			return self::$api;
		}

		public function get_30_provider_mappings() {
			return array(
				'api_key'    => 'api_key',
				'api_secret' => 'api_secret',
			);
		}

		/**
		 * Creates necessary custom fields for the form
		 *
		 * @param string $global_multi_id
		 * @return array|mixed|object|WP_Error
		 */
		public function maybe_create_custom_fields( $global_multi_id, array $fields ) {
			$api_key    = $this->get_setting( 'api_key', '', $global_multi_id );
			$api_secret = $this->get_setting( 'api_secret', '', $global_multi_id );

			// check if already existing
			$custom_fields = self::api( $api_key, $api_secret )->get_form_custom_fields();
			$proceed       = true;
			foreach ( $custom_fields as $custom_field ) {
				if ( isset( $fields[ $custom_field->key ] ) ) {
					unset( $fields[ $custom_field->key ] );
				}
			}
			// create necessary fields
			// Note: we don't delete fields here, let the user do it on ConvertKit app.convertkit.com
			$api = self::api( $api_key );
			foreach ( $fields as $key => $field ) {
				$add_custom_field = $api->create_custom_fields(
					array(
						'api_secret' => $api_secret,
						'label'      => $field['label'],
					)
				);
				if ( is_wp_error( $add_custom_field ) ) {
					$proceed = false;
					break;
				}
			}

			return $proceed;
		}
	}

endif;