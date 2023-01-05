<?php
if ( ! class_exists( 'Hustle_Icontact' ) ) :

	class Hustle_Icontact extends Hustle_Provider_Abstract {

		const SLUG = 'icontact';
		// const NAME = "IContact";

		/**
		 * Stores iContact API object.
		 *
		 * @var object $api
		 */
		protected static $api;

		/**
		 * Stores errors.
		 *
		 * @var object $errors
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
		protected $_slug = 'icontact';

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
		protected $_title = 'iContact';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Icontact_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_Icontact_Form_Hooks';

		/**
		 * Array of options which should exist for confirming that settings are completed
		 *
		 * @since 4.0
		 * @var array
		 */
		protected $_completion_options = array( 'app_id', 'username', 'password' );

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
					'callback'     => array( $this, 'configure_credentials' ),
					'is_completed' => array( $this, 'is_connected' ),
				),
			);
		}

		/**
		 * API Set up
		 *
		 * @param String $app_id - the application id
		 * @param String $api_password - the api password
		 * @param String $api_username - the api username
		 *
		 * @return WP_Error|Object
		 */
		public static function api( $app_id, $api_password, $api_username ) {
			if ( ! class_exists( 'Hustle_Icontact_Api' ) ) {
				require_once 'hustle-icontact-api.php';
			}

			if ( empty( self::$api ) ) {
				try {
					self::$api    = new Hustle_Icontact_Api( $app_id, $api_password, $api_username );
					self::$errors = array();
				} catch ( Exception $e ) {
					self::$errors = array( 'api_error' => $e );
				}
			}
			return self::$api;
		}

		/**
		 * Configure Global settings.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function configure_credentials( $submitted_data ) {
			$has_errors      = false;
			$default_data    = array(
				'app_id'   => '',
				'username' => '',
				'password' => '',
				'name'     => '',
			);
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$is_submit       = isset( $submitted_data['app_id'] ) && isset( $submitted_data['username'] )
				&& isset( $submitted_data['password'] );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$app_id_valid = $api_username_valid = $api_password_valid = true;
			if ( $is_submit ) {

				$app_id_valid       = ! empty( trim( $current_data['app_id'] ) );
				$api_username_valid = ! empty( trim( $current_data['username'] ) );
				$api_password_valid = ! empty( trim( $current_data['password'] ) );
				$api_key_validated  = $app_id_valid
								 && $api_username_valid
								 && $api_password_valid
								 && $this->validate_credentials( $submitted_data['app_id'], $submitted_data['username'], $submitted_data['password'] );
				if ( ! $api_key_validated ) {
					$error_message = $this->provider_connection_falied();
					$app_id_valid  = $api_username_valid = $api_password_valid = false;
					$has_errors    = true;
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'app_id'   => $current_data['app_id'],
						'username' => $current_data['username'],
						'password' => $current_data['password'],
						'name'     => $current_data['name'],
					);
					// TODO: Wrap this in a friendlier method
					// If not active, activate it.
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
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'iContact Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
					'class'    => $api_username_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'  => array(
							'type'  => 'label',
							'for'   => 'username',
							'value' => __( 'Email', 'hustle' ),
						),
						'app_id' => array(
							'type'        => 'text',
							'name'        => 'username',
							'value'       => $current_data['username'],
							'placeholder' => __( 'Enter Email', 'hustle' ),
							'id'          => 'username',
							'icon'        => 'mail',
						),
						'error'  => array(
							'type'  => 'error',
							'class' => $api_username_valid ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid iContact email', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'class'    => $app_id_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'  => array(
							'type'  => 'label',
							'for'   => 'app_id',
							'value' => __( 'Application ID', 'hustle' ),
						),
						'app_id' => array(
							'type'        => 'text',
							'name'        => 'app_id',
							'value'       => $current_data['app_id'],
							'placeholder' => __( 'Enter Application ID', 'hustle' ),
							'id'          => 'app_id',
							'icon'        => 'key',
						),
						'error'  => array(
							'type'  => 'error',
							'class' => $app_id_valid ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid iContact API Application ID (API-AppId)', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'class'    => $api_password_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'  => array(
							'type'  => 'label',
							'for'   => 'password',
							'value' => __( 'Application Password', 'hustle' ),
						),
						'app_id' => array(
							'type'        => 'password',
							'name'        => 'password',
							'value'       => $current_data['password'],
							'placeholder' => __( 'Enter Application Password', 'hustle' ),
							'id'          => 'password',
							'icon'        => 'eye-hide',
						),
						'error'  => array(
							'type'  => 'error',
							'class' => $api_password_valid ? 'sui-hidden' : '',
							'value' => __( 'Please make sure the password you entered is the same you created for your Application ID (API-AppId)', 'hustle' ),
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
				__( 'Configure iContact', 'hustle' ),
				sprintf(
					/* translators: 1. opening 'a' tag to the iContact apps page, 2. closing 'a' tag */
					__( 'Set up a new application in your %1$siContact account%2$s to get your credentials. Make sure your credentials have API 2.0 enabled', 'hustle' ),
					'<a href="https://app.icontact.com/icp/core/registerapp/" target="_blank">',
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
		 * Validate the provided credentials.
		 *
		 * @since 4.0
		 *
		 * @param string $app_id
		 * @param string $username
		 * @param string $password
		 * @return bool
		 */
		private function validate_credentials( $app_id, $username, $password ) {
			if ( empty( $app_id ) || empty( $username ) || empty( $password ) ) {
				return false;
			}

			try {
				// Check if credentials are valid
				$api = self::api( $app_id, $password, $username );

				if ( is_wp_error( $api ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid iContact API credentials.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}

		/**
		 * Check if email is already subcribed to list
		 */
		public function _is_subscribed( $api, $list_id, $email ) {
			$contacts = $api->get_contacts( $list_id );
			if ( ! is_wp_error( $contacts ) ) {
				if ( is_array( $contacts ) && isset( $contacts['contacts'] ) && is_array( $contacts['contacts'] ) ) {
					foreach ( $contacts['contacts'] as $contact ) {
						if ( $contact['email'] === $email ) {
							return true;
						}
					}
				}
			}
			return false;
		}

		public function get_30_provider_mappings() {
			return array(
				'app_id'   => 'app_id',
				'username' => 'username',
				'password' => 'password',
			);
		}

		public static function add_custom_fields( $fields, $api ) {
			$existed = array();
			$added   = array();
			$error   = array();
			foreach ( $fields as $field ) {
				$response = $api->add_custom_field(
					array(
						'displayToUser' => 1,
						'privateName'   => $field['name'],
						'fieldType'     => ( 'email' === $field['type'] ) ? 'text' : $field['type'],
					)
				);
				if ( isset( $response['customfields'] ) && isset( $response['warnings'][0] ) && is_array( $response['warnings'][0] ) ) {
					$existed[] = $field['name'];
				} elseif ( isset( $response['customfields'] ) && ! empty( $response['customfields'] ) ) {
					$added[] = $field['name'];
				} elseif ( isset( $response['warnings'][0] ) && ! is_array( $response['warnings'][0] ) ) {
					Hustle_Provider_Utils::maybe_log( $response['warnings'][0] );
					$error[] = $field['name'];
				}
			}
			return array(
				'success' => true,
				'field'   => $fields,
				'added'   => $added,
				'existed' => $existed,
				'error'   => $error,
			);
		}
	}

endif;