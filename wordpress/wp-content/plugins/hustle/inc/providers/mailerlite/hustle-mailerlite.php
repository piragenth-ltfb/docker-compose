<?php
if ( ! class_exists( 'Hustle_MailerLite' ) ) :

	include_once 'hustle-mailerlite-api.php';

	class Hustle_MailerLite extends Hustle_Provider_Abstract {

		const SLUG = 'mailerlite';
		// const NAME = "MailerLite";

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
		protected $_slug = 'mailerlite';

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
		protected $_title = 'MailerLite';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_MailerLite_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_MailerLite_Form_Hooks';

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

		public static function api( $api_key ) {
			$api = new Hustle_MailerLite_Api( $api_key );

			return $api;
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
				'api_key' => '',
				'name'    => '',
			);
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$is_submit       = isset( $submitted_data['api_key'] );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$api_key_validated = true;
			if ( $is_submit ) {

				$api_key_validated = $this->validate_api_key( $submitted_data['api_key'] );
				if ( ! $api_key_validated ) {
					$error_message = $this->provider_connection_falied();
					$has_errors    = true;
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'api_key' => $current_data['api_key'],
						'name'    => $current_data['name'],
					);
					// If not active, activate it.
					// TODO: Wrap this in a friendlier method
					if ( Hustle_Provider_Utils::is_provider_active( $this->_slug )
						|| Hustle_Providers::get_instance()->activate_addon( $this->_slug ) ) {
						$this->save_multi_settings_values( $global_multi_id, $settings_to_save );
					} else {
						$error_message = $this->provider_connection_falied();
						$has_errors    = true;
					}
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'MailerLite Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
							'class' => $api_key_validated ? 'sui-hidden' : '',
							'value' => __( 'Please enter a valid MailerLite API key', 'hustle' ),
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
				__( 'Configure MailerLite', 'hustle' ),
				sprintf(
					/* translators: 1. opening 'a' tag to MailerLite API page, 2. closing 'a' tag */
					__( 'Log in to %1$sMailerLite Integrations%2$s page to get your API Key.', 'hustle' ),
					'<a href="https://app.mailerlite.com/integrations/api/" target="_blank">',
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
		 * Validate the provided API key.
		 *
		 * @since 4.0
		 *
		 * @param string $api_key
		 * @return bool
		 */
		private function validate_api_key( $api_key ) {
			if ( empty( trim( $api_key ) ) ) {
				return false;
			}

			// Check API Key by validating it on get_info request
			try {
				// Check if API key is valid
				$api = self::api( $api_key );

				// Check if API key is valid
				$_lists = null;
				if ( $api ) {
					$_lists = $api->list_groups();
				}

				if ( ! is_array( $_lists ) || is_wp_error( $_lists ) || isset( $_lists['error'] ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid MailerLite API key.', 'hustle' ) );
					return false;
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}

		/**
		 * Check if an email exists
		 *
		 * @param $group_id - the group id
		 * @param $email - the email
		 * @param $api - the API class
		 *
		 * @return bool
		 */
		public function _email_exists( $group_id, $email, $api ) {
			$member_groups = $api->get_subscriber( $email );
			if ( is_wp_error( $member_groups ) ) {
				return false;
			} else {
				if ( ! isset( $member_groups['error'] ) ) {
					foreach ( $member_groups as $member_group => $group ) {
						if ( $group['id'] === (int) $group_id ) {
							return true;
						}
					}
				} else {
					return false;
				}
			}
			return false;
		}

		public function get_30_provider_mappings() {
			return array(
				'api_key' => 'api_key',
			);
		}

		public static function add_custom_fields( $fields, $api ) {
			$existing_fields     = $api->get_custom_field();
			$existing_field_keys = wp_list_pluck( $existing_fields, 'key' );

			foreach ( $fields as $field ) {
				if ( ! in_array( $field['name'], $existing_field_keys, true ) ) {
					$api->add_custom_field(
						array(
							'title' => $field['name'],
							'type'  => strtoupper( $field['type'] ),
						)
					);
				}
			}

			return array(
				'success' => true,
				'field'   => $fields,
			);
		}
	}

endif;