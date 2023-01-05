<?php
/**
 * Class Hustle_Mailchimp
 * The class that defines mailchimp provider
 */
if ( ! class_exists( 'Hustle_Mailchimp' ) ) :

	include_once 'hustle-mailchimp-api.php';

	class Hustle_Mailchimp extends Hustle_Provider_Abstract {

		const GROUP_TRANSIENT = 'hustle-mailchimp-group-transient';

		const SLUG = 'mailchimp';

		/**
		 * @var $api Mailchimp
		 */
		protected static $api;

		/**
		 * Mailchimp Provider Instance
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
		protected $_slug = 'mailchimp';

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
		protected $_title = 'Mailchimp';

		/**
		 * Class name of form settings
		 *
		 * @since 3.0.5
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Mailchimp_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_Mailchimp_Form_Hooks';

		/**
		 * Hold the information of the account that's currently connected.
		 * Will be saved to @see Hustle_Mailchimp::save_settings_values()
		 * Specific for Mailchimp provider.
		 *
		 * @since 4.0
		 * @var array
		 */
		private $_connected_account = array();

		/**
		 * Provider constructor.
		 *
		 * @since 3.0.5
		 */
		public function __construct() {
			$this->_icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->_logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';

			if ( wp_doing_ajax() ) {
				add_action( 'wp_ajax_hustle_mailchimp_get_group_interests', array( $this, 'ajax_group_interests' ) );
			}
		}

		public function ajax_group_interests() {
			Opt_In_Utils::validate_ajax_call( 'hustle_mailchimp_interests' );
			$html      = '';
			$post_data = filter_input( INPUT_POST, 'data' );
			$data      = array();
			wp_parse_str( $post_data, $data );
			$module_id = isset( $data['module_id'] ) ? $data['module_id'] : '';
			if ( $module_id ) {
				$class_name             = $this->_form_settings;
				$form_settings_instance = new $class_name( $this, $module_id );
				$html                   = $form_settings_instance->get_group_interests( $data );
			}

			wp_send_json_success( $html );
		}

		/**
		 * Get Instance
		 *
		 * @since 3.0.5
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
		 * Hook before the save settings values method
		 * to include @see Hustle_Mailchimp::$_connected_account
		 * for future reference
		 *
		 * @since 4.0
		 *
		 * @param array $values
		 * @return array
		 */
		public function before_save_settings_values( $values ) {

			// if ( ! empty( $this->_connected_account ) ) {
			// $values['connected_account'] = $this->_connected_account;
			// }

			return $values;
		}

		/**
		 * @param string $api_key
		 * @return Hustle_Mailchimp_Api
		 */
		public function get_api( $api_key ) {

			if ( empty( self::$api ) ) {
				$exploded    = explode( '-', $api_key );
				$data_center = end( $exploded );
				self::$api   = new Hustle_Mailchimp_Api( $api_key, $data_center );
			}
			return self::$api;
		}

		/**
		 * @param string $email
		 * @param string $list_id
		 * @param array  $data
		 * @param string $api_key
		 *
		 * @return Object Returns the member if the email address already exists otherwise false.
		 */
		public function get_member( $email, $list_id, $data, $api_key ) {

			try {
				$api = $this->get_api( $api_key );

				$member_info = $api->check_email( $list_id, $email );
				// Mailchimp returns WP error if can't find member on a list
				if ( is_wp_error( $member_info ) && 404 === $member_info->get_error_code() ) {
					return false;
				}
				return $member_info;
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Failed to get member from Mailchimp list.', $e->getMessage() );

				return false;
			}
		}

		/**
		 * @param string $email
		 * @param string $list_id
		 * @param array  $data
		 * @param string $api_key
		 *
		 * @return NULL if the member is deleted otherwise false.
		 */
		public function delete_member( $email, $list_id, $data, $api_key ) {
			try {
				$api = $this->get_api( $api_key );

				$delete_status = $api->delete_email( $list_id, $email );
				// Mailchimp returns WP error if can't find member on a list
				if ( is_wp_error( $member_info ) && 404 === $delete_status->get_error_code() ) {
					return false;
				}
				return $delete_status;
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Failed to delete member from Mailchimp list.', $e->getMessage() );

				return false;
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

				$api_key_validated = $this->validate_api_key( $current_data['api_key'] );
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
						$error_message = __( "Provider couldn't be activated.", 'hustle' );
						$has_errors    = true;
					}
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Mailchimp Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
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
							'value' => __( 'Please enter a valid Mailchimp API key', 'hustle' ),
						),
					),
				),
				array(
					'type'     => 'wrapper',
					'style'    => 'margin-bottom: 0;',
					'elements' => array(
						'label'   => array(
							'type'  => 'label',
							'for'   => 'mailchimp-name-input',
							'value' => __( 'Identifier', 'hustle' ),
						),
						'name'    => array(
							'type'        => 'text',
							'name'        => 'name',
							'value'       => $current_data['name'],
							'placeholder' => __( 'E.g. Business Account', 'hustle' ),
							'id'          => 'mailchimp-name-input',
						),
						'message' => array(
							'type'  => 'description',
							'value' => __( 'Helps to distinguish your integrations if you have connected to the multiple accounts of this integration.', 'hustle' ),
						),
					),
				),
				array(
					'type'  => 'hidden',
					'name'  => 'global_multi_id',
					'value' => $global_multi_id,
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
				__( 'Configure Mailchimp', 'hustle' ),
				sprintf(
					/* translators: 1. opening 'a' tag to Mailchimp API page, 2. closing 'a' tag */
					__( 'Log in to your %1$sMailchimp account%2$s to get your API Key.', 'hustle' ),
					'<a href="https://admin.mailchimp.com/account/api/" target="_blank">',
					'</a>'
				)
			);

			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$is_edit = $this->settings_are_completed( $global_multi_id );
			if ( $is_edit ) {
				$buttons = array(
					'disconnect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Disconnect', 'hustle' ), 'sui-button-ghost', 'disconnect', true ),
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
				$info = $this->get_api( $api_key )->get_info();

				if ( is_wp_error( $info ) ) {
					Hustle_Provider_Utils::maybe_log( __METHOD__, $info->get_error_message() );
					return false;
				}

				$this->_connected_account = array(
					'account_id'   => $info->account_id,
					'account_name' => $info->account_name,
					'email'        => $info->email,
				);

			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, $e->getMessage() );
				return false;
			}

			return true;
		}

		protected function get_30_provider_mappings() {
			return array(
				'api_key' => 'api_key',
			);
		}

		public function migrate_30( $module, $old_module ) {
			$migrated = parent::migrate_30( $module, $old_module );
			if ( ! $migrated ) {
				return false;
			}

			/**
			 * For mailchimp version 3.x used to store a lot of unnecessary crap, let's get rid of it now.
			 */
			$redundant                = array( 'is_step', 'slug', 'step', 'current_step', 'module_type' );
			$module_provider_settings = $module->get_provider_settings( $this->get_slug() );
			if ( ! empty( $module_provider_settings ) ) {
				// Remove redundants
				$module_provider_settings = array_diff_key( $module_provider_settings, array_combine( $redundant, $redundant ) );

				// Interest options are stored differently so let's try to bridge the differences
				$interest_options                             = $this->transform_interest_options( $module_provider_settings );
				$module_provider_settings['interest_options'] = $interest_options;

				if ( isset( $module_provider_settings['group_interest'] ) ) {

					// 3.x didn't store group_interest_name so let's see if we can add that
					$module_provider_settings['group_interest_name'] = is_array( $interest_options ) && ! empty( $interest_options[ $module_provider_settings['group_interest'] ] )
						? $interest_options[ $module_provider_settings['group_interest'] ]
						: '';
					$module_provider_settings['group_type']          = 'radio';

				}

				$module->set_provider_settings( $this->get_slug(), $module_provider_settings );
			}

			return $migrated;
		}

		private function transform_interest_options( $module_provider_settings ) {
			if (
				empty( $module_provider_settings['list_id'] )
				|| empty( $module_provider_settings['interest_options'] )
				|| empty( $module_provider_settings['group'] )
			) {
				return array();
			}

			$original  = $module_provider_settings['interest_options'];
			$list_id   = $module_provider_settings['list_id'];
			$transient = get_site_transient( self::GROUP_TRANSIENT . $list_id );
			if ( empty( $transient ) ) {
				return $original;
			}

			$group_id  = $module_provider_settings['group'];
			$interests = array();
			foreach ( $transient as $group ) {
				if (
					isset( $group->id, $group->list_id, $group->interests )
					&& $group->id === $group_id
					&& $group->list_id === $list_id
				) {
					$interests = $group->interests;
				}
			}

			if ( empty( $interests ) ) {
				return $original;
			}

			$interest_options = array();
			foreach ( $interests as $interest ) {
				if (
					isset( $interest->name )
					&& strpos( $original . ',', $interest->name . ',' ) !== false
				) {
					$interest_options[ $interest->id ] = $interest->name;
				}
			}

			return $interest_options;
		}

		/**
		 * 3.x used to store interest options as a comma-separated string, in later versions we started storing it as an array.
		 * This method returns the interest options in a single, predictable format.
		 *
		 * @param $module Hustle_Module_Model Module model
		 *
		 * @return array Interest options formatted as id=>name pairs
		 */
		public function get_interest_options( Hustle_Module_Model $module ) {
			$settings = $module->get_provider_settings( $this->get_slug() );
			$required = array( 'selected_global_multi_id', 'interest_options', 'list_id', 'group' );
			if ( ! $this->array_has_items( $settings, $required ) ) {
				return array();
			}

			// If we already have the interest options in the correct format, don't bother calling remote
			if ( is_array( $settings['interest_options'] ) ) {
				return $settings['interest_options'];
			}

			// No dice, call api
			$remote_interest_options = $this->get_remote_interest_options(
				$settings['selected_global_multi_id'],
				$settings['list_id'],
				$settings['group']
			);

			// Save the new interest_options so we don't have to fetch them remotely again
			$settings['interest_options'] = $remote_interest_options;
			$module->set_provider_settings( $this->get_slug(), $settings );

			return $remote_interest_options;
		}

		/**
		 * Calls the API to fetch remote interest options
		 */
		public function get_remote_interest_options( $global_multi_id, $list_id, $group_id ) {
			if ( '-1' === $group_id ) {
				return array();
			}

			$api_key = $this->get_setting( 'api_key', '', $global_multi_id );
			try {
				$api      = $this->get_api( $api_key );
				$response = $api->get_interests( $list_id, $group_id, PHP_INT_MAX );
				if ( is_wp_error( $response ) || ! is_array( $response->interests ) ) {
					return array();
				}

				$interests = wp_list_pluck( $response->interests, 'name', 'id' );
			} catch ( Exception $e ) {
				return array();
			}

			return $interests;
		}

		public function maybe_add_custom_fields( $api, $list_id, $merge_data, $module_id ) {

			$existed_custom_fields = $api->get_custom_fields( $list_id );
			$existed_keys          = ! empty( $existed_custom_fields->merge_fields ) ? wp_list_pluck( $existed_custom_fields->merge_fields, 'tag' ) : array();
			$new_fields            = array();
			foreach ( $merge_data as $k => $v ) {
				if ( ! in_array( strtoupper( $k ), $existed_keys, true ) ) {
					$new_fields[] = $k;
				}
			}
			if ( ! empty( $new_fields ) ) {
				$module = new Hustle_Module_Model( $module_id );
				if ( is_wp_error( $module ) ) {
					return $module;
				}
				$form_fields    = $module->get_form_fields();
				$possible_types = array(
					'text',
					'number',
					'phone',
					'date',
					'url',
					'imageurl',
					'radio',
					'dropdown',
					'birthday',
					'zip',
				);

				foreach ( $new_fields as $field ) {
					$tag  = strtoupper( $field );
					$name = ! empty( $form_fields[ $field ]['label'] ) ? $form_fields[ $field ]['label'] : $field;
					$type = ! empty( $form_fields[ $field ]['type'] ) ? $form_fields[ $field ]['type'] : '';
					$api->add_custom_field(
						$list_id,
						array(
							'tag'  => $tag,
							'name' => $name,
							'type' => in_array( $type, $possible_types, true ) ? $type : 'text',
						)
					);
				}
			}

			return true;
		}

		private function array_has_items( $array, $keys ) {
			foreach ( $keys as $key ) {
				if ( ! isset( $array[ $key ] ) ) {
					return false;
				}
			}

			return true;
		}
	}
endif;