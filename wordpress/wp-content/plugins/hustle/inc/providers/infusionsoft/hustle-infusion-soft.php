<?php

if ( ! class_exists( 'Hustle_Infusion_Soft' ) ) :

	include_once 'hustle-infusion-soft-api.php';

	class Hustle_Infusion_Soft extends Hustle_Provider_Abstract {

		const SLUG = 'infusionsoft';

		const CLIENT_ID     = 'inc_opt_infusionsoft_clientid';
		const CLIENT_SECRET = 'inc_opt_infusionsoft_clientsecret';

		/**
		 * @var Opt_In_Infusionsoft_Api $api
		 */
		protected static $api;
		/**
		 * @var WP_Error $errors
		 */
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
		protected $_slug = 'infusionsoft';

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
		protected $_title = 'Infusionsoft';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Infusion_Soft_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_InfusionSoft_Form_Hooks';

		/**
		 * Array of options which should exist for confirming that settings are completed
		 *
		 * @since 4.0
		 * @var array
		 */
		protected $_completion_options = array( 'api_key', 'account_name' );

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
		 * Returns a cached api
		 *
		 * @param $api_key
		 * @param $app_name
		 * @return Opt_In_Infusionsoft_Api
		 */
		// protected static function api( $api_key, $app_name){
		public static function api( $api_key, $app_name ) {

			if ( empty( self::$api ) ) {
				try {
					self::$errors = array();
					self::$api    = new Opt_In_Infusionsoft_Api( $api_key, $app_name );
				} catch ( Exception $e ) {
					self::$errors = array( 'api_error' => $e );
				}
			}

			return self::$api;
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
				'api_key'      => '',
				'account_name' => '',
				'name'         => '',
			);
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$is_submit       = isset( $submitted_data['api_key'] ) && isset( $submitted_data['account_name'] );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$api_key_valid = $api_account_name_valid = true;

			if ( $is_submit ) {

				$api_key_valid          = ! empty( trim( $current_data['api_key'] ) );
				$api_account_name_valid = ! empty( trim( $current_data['account_name'] ) );
				$api_key_validated      = $api_key_valid
									 && $api_account_name_valid
									 && $this->validate_credentials( $submitted_data['api_key'], $submitted_data['account_name'] );
				if ( ! $api_key_validated ) {
					$error_message = $this->provider_connection_falied();
					$api_key_valid = $api_account_name_valid = false;
					$has_errors    = true;
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'api_key'      => $current_data['api_key'],
						'account_name' => $current_data['account_name'],
						'name'         => $current_data['name'],
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
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'InfusionSoft Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
							'value' => __( 'API Key (Encrypted)', 'hustle' ),
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
							'value' => __( 'Please enter a valid InfusionSoft encrypted API key', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'class'    => $api_account_name_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'        => array(
							'type'  => 'label',
							'for'   => 'account_name',
							'value' => __( 'Account Name', 'hustle' ),
						),
						'account_name' => array(
							'type'        => 'text',
							'name'        => 'account_name',
							'value'       => $current_data['account_name'],
							'placeholder' => __( 'Enter Account Name', 'hustle' ),
							'id'          => 'account_name',
							'icon'        => 'style-type',
						),
						'error'        => array(
							'type'  => 'error',
							'class' => $api_account_name_valid ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid InfusionSoft account name', 'hustle' ),
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
				__( 'Configure InfusionSoft', 'hustle' ),
				sprintf(
					/* translators: 1. opening 'a' tag to the API key guide, 2. closing 'a' tag, 3. opening 'a' tag to the account name guide */
					__( 'Log in to your account to get your %1$sAPI key (encrypted)%2$s and %3$saccount name%2$s.', 'hustle' ),
					'<a target="_blank" href="http://help.infusionsoft.com/userguides/get-started/tips-and-tricks/api-key">',
					'</a>',
					'<a target="_blank" href="http://help.mobit.com/infusionsoft-integration/how-to-find-your-infusionsoft-account-name" >'
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
		 * Validate the provided API key and account name.
		 *
		 * @since 4.0
		 *
		 * @param string $api_key
		 * @param string $account_name
		 * @return bool
		 */
		private function validate_credentials( $api_key, $account_name ) {
			if ( empty( $api_key ) || empty( $account_name ) ) {
				return false;
			}

			try {
				// Check if credentials are valid
				$_lists = self::api( $api_key, $account_name )->get_lists();

				if ( is_wp_error( $_lists ) && ! empty( $_lists ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid InfusionSoft credentials.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}

		public function get_30_provider_mappings() {
			return array(
				'api_key'      => 'api_key',
				'account_name' => 'account_name',
			);
		}
	}

endif;