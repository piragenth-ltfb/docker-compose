<?php
if ( ! class_exists( 'Hustle_Sendy' ) ) :

	class Hustle_Sendy extends Hustle_Provider_Abstract {

		const SLUG = 'sendy';
		// const NAME = "Sendy";

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
		protected $_slug = 'sendy';

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
		protected $_title = 'Sendy';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Sendy_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_Sendy_Form_Hooks';

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
				'installation_url' => '',
				'api_key'          => '',
				'list_id'          => '',
				'name'             => '',
			);
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$is_submit       = isset( $submitted_data['installation_url'] ) && isset( $submitted_data['api_key'] );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$installation_url_valid = true;
			$api_key_valid          = true;
			$list_id_valid          = true;

			if ( $is_submit ) {
				$installation_url_valid = ! empty( trim( $current_data['installation_url'] ) );
				$api_key_valid          = ! empty( trim( $current_data['api_key'] ) );
				$list_id_valid          = ! empty( trim( $current_data['list_id'] ) );
				$api_key_validated      = $installation_url_valid
								&& $api_key_valid
								&& $list_id_valid;

				// If api key is correct we try to connect with Sendy.
				if ( $api_key_validated ) {
					$api_key_validated = $this->validate_api_credentials( $current_data['installation_url'], $current_data['api_key'], $current_data['list_id'] );
					if ( is_wp_error( $api_key_validated ) ) {

						$error_message = $this->provider_connection_falied();
						$error_code    = $api_key_validated->get_error_code();
						$has_errors    = true;

						switch ( $error_code ) {
							case 'remote_error':
								$installation_url_valid = false;
								break;

							case 'Invalid API key':
								$api_key_valid = false;
								break;

							case 'List does not exist':
								$list_id_valid = false;
								break;

							default:
								// TODO: add info to the logs. Last request url, data, etc. to check what happens here.
								$error_message = __( 'Something went wrong.', 'hustle' );
								break;
						}
					}
				} else { // If some field is missing we just set an error.
					$error_message = $this->provider_connection_falied();
					$has_errors    = true;
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'installation_url' => $current_data['installation_url'],
						'api_key'          => $current_data['api_key'],
						'list_id'          => $current_data['list_id'],
						'name'             => $current_data['name'],
					);

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
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Sendy Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
					'class'    => $installation_url_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'            => array(
							'type'  => 'label',
							'for'   => 'installation_url',
							'value' => __( 'Installation URL', 'hustle' ),
						),
						'installation_url' => array(
							'type'        => 'url',
							'name'        => 'installation_url',
							'value'       => $current_data['installation_url'],
							'placeholder' => __( 'Enter URL', 'hustle' ),
							'id'          => 'installation_url',
							'icon'        => 'web-globe-world',
						),
						'error'            => array(
							'type'  => 'error',
							'class' => $installation_url_valid ? 'sui-hidden' : '',
							'value' => __( 'Please, enter a valid Sendy installation URL.', 'hustle' ),
						),
					),
				),
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
							'placeholder' => __( 'Enter Key', 'hustle' ),
							'id'          => 'api_key',
							'icon'        => 'key',
						),
						'error'   => array(
							'type'  => 'error',
							'class' => $api_key_valid ? 'sui-hidden' : '',
							'value' => __( 'Please, enter a valid Sendy API key.', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'class'    => $list_id_valid ? '' : 'sui-form-field-error',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'list_id',
							'value' => __( 'List ID', 'hustle' ),
						),
						'list_id' => array(
							'type'        => 'text',
							'name'        => 'list_id',
							'value'       => $current_data['list_id'],
							'placeholder' => __( 'Enter List ID', 'hustle' ),
							'id'          => 'list_id',
							'icon'        => 'target',
						),
						'error'   => array(
							'type'  => 'error',
							'class' => $list_id_valid ? 'sui-hidden' : '',
							'value' => __( 'Please, enter a valid Sendy list ID.', 'hustle' ),
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
				__( 'Configure Sendy', 'hustle' ),
				__( 'Log in to your Sendy installation to get your API Key and list ID.', 'hustle' )
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
		 * @param $global_multi_id
		 *
		 * @return Hustle_Sendy_API
		 */
		public function get_api( $global_multi_id ) {
			$installation_url = $this->get_setting( 'installation_url', '', $global_multi_id );
			$api_key          = $this->get_setting( 'api_key', '', $global_multi_id );
			$email_list       = $this->get_setting( 'list_id', '', $global_multi_id );

			return new Hustle_Sendy_API( $installation_url, $api_key, $email_list );
		}

		/**
		 * @param $data array
		 *
		 * @return boolean|WP_Error
		 */
		private function validate_api_credentials( $installation_url, $api_key, $list_id ) {
			$sendy = new Hustle_Sendy_API(
				$installation_url,
				$api_key,
				$list_id
			);

			return $sendy->get_subscriber_count();
		}

		public function get_30_provider_mappings() {
			return array(
				'installation_url' => 'installation_url',
				'api_key'          => 'api_key',
				'list_id'          => 'list_id',
			);
		}

	}

endif;