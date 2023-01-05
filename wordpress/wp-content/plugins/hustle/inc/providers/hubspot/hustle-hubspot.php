<?php

if ( ! class_exists( 'Hustle_HubSpot' ) ) :

	require_once 'hustle-hubspot-api.php';

	/**
	 * Class Hustle_HubSpot
	 */
	class Hustle_HubSpot extends Hustle_Provider_Abstract {
		const SLUG = 'hubspot';
		// const NAME = "HubSpot";

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
		protected $_slug = 'hubspot';

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
		protected $_title = 'HubSpot';

		/**
		 * @since 4.0
		 * @var boolean
		 */
		protected $is_multi_on_global = false;

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_HubSpot_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_HubSpot_Form_Hooks';

		/**
		 * Provider constructor.
		 */
		public function __construct() {
			$this->_icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->_logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';

			// Instantiate API when instantiating because it's used after getting the authorization
			$hustle_hubpost = new Hustle_HubSpot_Api();
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
		 * Check if the settings are completed
		 *
		 * @since 4.0
		 * @return boolean
		 */
		protected function settings_are_completed( $multi_id = '' ) {
			$api          = $this->api();
			$is_authorize = $api && ! $api->is_error && $api->is_authorized();

			return $is_authorize;
		}

		/**
		 * @return bool|Hustle_HubSpot_Api
		 */
		public function api() {
			return self::static_api();
		}

		public static function static_api() {
			if ( ! class_exists( 'Hustle_HubSpot_Api' ) ) {
				require_once 'opt-in-hubspot-api.php'; }

			$api = new Hustle_HubSpot_Api();

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
		 * @return array
		 */
		public function configure_api_key( $submitted_data, $is_submit, $module_id ) {

			$api = $this->api();

			if ( ! $module_id ) {
				$auth_url = $api->get_authorization_uri( 0, true, Hustle_Data::INTEGRATIONS_PAGE );

			} else {

				$module = new Hustle_Module_Model( $module_id );
				if ( ! is_wp_error( $module ) ) {
					$auth_url = $api->get_authorization_uri( $module_id, true, $module->get_wizard_page() );
				}
			}

			$is_connected = $this->is_connected();

			if ( $is_connected ) {

				$description = __( 'You are already connected to Hubspot. You can disconnect your Hubspot Integration (if you need to) using the button below.', 'hustle' );

				$buttons = array(
					'disconnect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Disconnect', 'hustle' ),
							'sui-button-ghost sui-button-center',
							'disconnect',
							true
						),
					),
				);

			} else {

				$description = __( 'Connect the Hubspot integration by authenticating it using the button below. Note that you’ll be taken to the Hubspot website to grant access to Hustle and then redirected back.', 'hustle' );

				$buttons = array(
					'auth' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Authenticate', 'hustle' ),
							'sui-button-center',
							'',
							true,
							false,
							$auth_url
						),
					),
				);
			}

			$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Connect Hubspot', 'hustle' ), $description );

			if ( $is_connected ) {
				$account_details = $this->get_settings_values();

				// Integrations coming from before 4.0.2 don't have this data.
				if ( ! isset( $account_details['user'] ) ) {
					$account_details = $this->save_account_details();
				}

				$account = ! empty( $account_details['hub_domain'] ) ? $account_details['user'] . ' - ' . $account_details['hub_domain'] : $account_details['user'];
				$account = '<b>' . esc_html( $account ) . '</b>';

				$step_html .= Hustle_Provider_Utils::get_html_for_options(
					array(
						array(
							'type'  => 'notice',
							'icon'  => 'info',
							/* translators: account the provider is connected to */
							'value' => sprintf( esc_html__( 'You are connected to %s', 'hustle' ), $account ),
							'class' => 'sui-notice-success',
						),
					)
				);

			}

			$response = array(
				'html'    => $step_html,
				'buttons' => $buttons,
			);

			return $response;
		}

		public function remove_wp_options() {
			$api = $this->api();
			$api->remove_wp_options();
		}

		public function migrate_30( $module, $old_module ) {
			$migrated = parent::migrate_30( $module, $old_module );
			if ( ! $migrated ) {
				return false;
			}

			/*
			 * Our regular migration would've saved the provider settings in a format that's incorrect for HubSpot
			 *
			 * Let's fix that now.
			 */
			$module_provider_settings = $module->get_provider_settings( $this->get_slug() );
			if ( ! empty( $module_provider_settings ) ) {
				// At provider level don't store anything (at least not in the regular option)
				delete_option( $this->get_settings_options_name() );

				// selected_global_multi_id not needed at module level
				unset( $module_provider_settings['selected_global_multi_id'] );
				$module->set_provider_settings( $this->get_slug(), $module_provider_settings );
			}

			return $migrated;
		}

		public function get_30_provider_mappings() {
			return array();
		}

		public function add_custom_fields( $fields ) {
			$api   = $this->api();
			$error = false;

			if ( $api && ! $api->is_error ) {
				// Get the existing fields
				$props = $api->get_properties();

				$new_fields = array();
				foreach ( $fields as $field ) {
					if ( ! isset( $props[ $field['name'] ] ) ) {
						$new_fields[] = $field;
					}
				}

				foreach ( $new_fields as $field ) {
					// Add the new field as property
					$property = array(
						'name'      => $field['name'],
						'label'     => $field['label'],
						'type'      => 'text' === $field['type'] ? 'string' : $field['type'],
						'fieldType' => $field['type'],
						'groupName' => 'contactinformation',
					);

					if ( ! $api->add_property( $property ) ) {
						$error = true;
					}
				}
			}

			if ( ! $error ) {
				return array(
					'success' => true,
					'field'   => $fields,
				);
			} else {
				return array(
					'error' => true,
					'code'  => 'cannot_create_custom_field',
				);
			}
		}

		/**
		 * Save the account details.
		 *
		 * @since 4.0.2
		 * @return array
		 */
		private function save_account_details() {

			$api             = $this->api();
			$account_details = $api->get_access_token_information();
			$account_data    = array();

			if ( isset( $account_details->response ) && 400 <= $account_details->response['code'] ) {
				Hustle_Providers_Utils::maybe_log( $this->_title, __METHOD__, $account_details->response['code'], $account_details['response']['message'] );

			} else {
				$account_data = array(
					'user'       => $account_details->user,
					'hub_domain' => $account_details->hub_domain,
				);

				$this->save_settings_values( $account_data );
			}

			return $account_data;
		}

		/**
		 * Process the request after coming from authentication.
		 *
		 * @since 4.0.2
		 * @return array
		 */
		public function process_external_redirect() {

			$status   = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING );
			$response = array();

			$api           = $this->api();
			$is_authorized = $api && ! $api->is_error && $api->is_authorized();

			// API Auth was successful.
			if ( 'success' === $status && $is_authorized ) {

				$providers_instance = Hustle_Providers::get_instance();

				if ( ! $this->is_active() ) {

					$activated = $providers_instance->activate_addon( $this->_slug );

					// Provider successfully activated.
					if ( $activated ) {

						$response = array(
							'action'  => 'notification',
							'status'  => 'success',
							'message' => sprintf( esc_html__( '%s successfully connected.', 'hustle' ), '<strong>' . $this->_title . '</strong>' ),
						);

						$this->save_account_details();

					} else { // Provider couldn't be activated.

						$response = array(
							'action'  => 'notification',
							'status'  => 'error',
							'message' => $providers_instance->get_last_error_message(),
						);
					}
				}
			} else { // API Auth failed.

				$response = array(
					'action'  => 'notification',
					'status'  => 'error',
					'message' => sprintf( esc_html__( 'Authentication failed! Please check your %s credentials and try again.', 'hustle' ), $this->_title ),
				);

			}

			return $response;

		}
	}

endif;