<?php

if ( ! class_exists( 'Hustle_SendGrid' ) ) :

	class Hustle_SendGrid extends Hustle_Provider_Abstract {

		const SLUG = 'sendgrid';

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
		protected $_slug = 'sendgrid';

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
		protected $_title = 'SendGrid';

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_SendGrid_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_SendGrid_Form_Hooks';

		/**
		 * Provider constructor.
		 */
		public function __construct() {
			$this->_icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->_logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';

			include_once 'hustle-sendgrid-api.php';
			include_once 'hustle-sendgrid-api-new.php';
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

		public static function api( $api_key = '', $new_campaigns = '' ) {
			try {
				if ( 'new_campaigns' === $new_campaigns ) {
					include_once 'hustle-sendgrid-api-new.php';
					return new Hustle_New_SendGrid_Api( $api_key );
				} else {
					include_once 'hustle-sendgrid-api.php';
					return new Hustle_SendGrid_Api( $api_key );
				}
			} catch ( Exception $e ) {
				return $e;
			}
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
			$has_errors   = false;
			$default_data = array(
				'api_key'       => '',
				'new_campaigns' => '',
				'name'          => '',
			);
			$is_submit    = isset( $submitted_data['api_key'] );
			if ( $is_submit && ! isset( $submitted_data['new_campaigns'] ) ) {
				$submitted_data['new_campaigns'] = '';
			}
			$current_data    = $this->get_current_data( $default_data, $submitted_data );
			$global_multi_id = $this->get_global_multi_id( $submitted_data );

			$api_key_validated = true;
			if ( $is_submit ) {

				$api_key_validated = $this->validate_api_key( $submitted_data['api_key'], $submitted_data['new_campaigns'] );
				if ( ! $api_key_validated ) {
					$error_message = __( 'The API key is invalid for the selected Marketing Campaign. Please enter a valid API key or try with a different "Marketing Campaign" version below.', 'hustle' );
					$has_errors    = true;
				}

				if ( ! $has_errors ) {
					$settings_to_save = array(
						'api_key'       => $current_data['api_key'],
						'new_campaigns' => $current_data['new_campaigns'],
						'name'          => $current_data['name'],
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
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'SendGrid Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
							'value' => __( 'Please enter a valid SendGrid API key or choose the correct Marketing Campaign version below', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'elements' => array(
						'new_campaigns' => array(
							'type'        => 'sui_tabs',
							'name'        => 'new_campaigns',
							'value'       => 'new_campaigns' === $current_data['new_campaigns'] ? 'new_campaigns' : 'legacy',
							'options'     => array(
								'new_campaigns' => __( 'New', 'hustle' ),
								'legacy'        => __( 'Legacy', 'hustle' ),
							),
							'label'       => __( 'Marketing Campaigns', 'hustle' ),
							'description' => __( 'Choose the Marketing Campaigns version you are using.', 'hustle' ),
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
				__( 'Configure SendGrid', 'hustle' ),
				sprintf(
					/* translators: 1. opening 'a' tag to Sendgrid API page, 2. closing 'a' tag */
					__( 'Log in to your %1$sSendGrid account%2$s to get your API Key v3.', 'hustle' ),
					'<a href="https://app.sendgrid.com/settings/api_keys" target="_blank">',
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
		private function validate_api_key( $api_key, $new_campaigns ) {
			if ( empty( $api_key ) ) {
				return false;
			}

			// Check API Key by validating it on get_info request
			try {
				// Check if API key is valid
				$api = self::api( $api_key, $new_campaigns );

				if ( $api ) {
					$_lists = $api->get_all_lists();
				}

				if ( ! isset( $_lists ) || false === $_lists ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, __( 'Invalid SendGrid API key.', 'hustle' ) );
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
				'api_key' => 'api_key',
			);
		}
	}

endif;