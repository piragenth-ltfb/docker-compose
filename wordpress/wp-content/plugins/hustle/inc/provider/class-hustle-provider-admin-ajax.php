<?php
/**
 * File for Hustle_Provider_Admin_Ajax class.
 *
 * @package Hustle
 * @since 3.0.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Hustle_Provider_Admin_Ajax.
 * Handles the AJAX actions for providers.
 *
 * @since 4.0.0
 */
class Hustle_Provider_Admin_Ajax {

	/**
	 * Default nonce action.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	private static $nonce_action = 'hustle_provider_action';

	/**
	 * Instance of this class.
	 *
	 * @since 4.2.0
	 * @var Hustle_Provider_Admin_Ajax
	 */
	private static $instance = null;

	/**
	 * Instance of Hustle_Layout_Helper.
	 *
	 * @since 4.2.0
	 * @var Hustle_Layout_Helper
	 */
	private $renderer = null;

	/**
	 * Returns an instance of this class.
	 *
	 * @since 4.2.0
	 * @return Hustle_Provider_Admin_Ajax
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {

		$this->renderer = new Hustle_Layout_Helper();

		add_action( 'wp_ajax_hustle_provider_get_providers', array( $this, 'get_addons' ) );
		add_action( 'wp_ajax_hustle_provider_get_form_providers', array( $this, 'get_form_addons' ) );
		add_action( 'wp_ajax_hustle_provider_deactivate', array( $this, 'deactivate' ) );
		add_action( 'wp_ajax_hustle_provider_is_on_module', array( $this, 'is_on_module' ) );
		add_action( 'wp_ajax_hustle_provider_settings', array( $this, 'settings' ) );
		add_action( 'wp_ajax_hustle_provider_form_settings', array( $this, 'form_settings' ) );
		add_action( 'wp_ajax_hustle_provider_form_deactivate', array( $this, 'form_deactivate' ) );
		add_action( 'wp_ajax_hustle_refresh_email_lists', array( $this, 'refresh_email_lists' ) );
		add_action( 'wp_ajax_hustle_provider_insert_local_list', array( $this, 'insert_local_list' ) );
		add_action( 'wp_ajax_hustle_provider_migrate_aweber', array( $this, 'migrate_aweber' ) );
	}

	/**
	 * Validates the Ajax request.
	 *
	 * @since 4.0.0
	 */
	private function validate_ajax() {
		Opt_In_Utils::validate_ajax_call( self::$nonce_action );
	}

	/**
	 * Executes the deactivation of the global instance of a provider.
	 *
	 * @since 4.0.0
	 */
	public function deactivate() {
		$this->validate_ajax();
		Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_integrations' );

		$post_data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$data  = Opt_In_Utils::validate_and_sanitize_fields( $post_data, array( 'slug' ) );
		$slug  = $data['slug'];
		$addon = Hustle_Provider_Utils::get_provider_by_slug( $slug );

		// . Hustle_Provider_Utils::maybe_attach_addon_hook( $addon ).
		$title = $addon->get_title();

		// Handling multi_id.
		if ( isset( $data['global_multi_id'] ) ) {

			$multi_id_label = '';
			$multi_ids      = $addon->get_global_multi_ids();
			foreach ( $multi_ids as $key => $multi_id ) {
				if ( isset( $multi_id['id'] ) && $multi_id['label'] ) {
					if ( $multi_id['id'] === $data['global_multi_id'] ) {
						$multi_id_label = $multi_id['label'];
						break;
					}
				}
			}

			if ( ! empty( $multi_id_label ) ) {
				$title .= ' - ' . $multi_id_label;
			}
		}

		$deactivated = Hustle_Providers::get_instance()->deactivate_addon( $slug, $data );

		if ( ! $deactivated ) {
			wp_send_json_error(
				array(
					'message' => Hustle_Providers::get_instance()->get_last_error_message(),
					'data'    => array(
						'notification' => array(
							'type' => 'error',
							'text' => Hustle_Providers::get_instance()->get_last_error_message(),
						),
					),
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __( 'Addon Deactivated', 'hustle' ),
				'data'    => array(
					'notification' => array(
						'type' => 'success',
						'text' => '<strong>' . $title . '</strong> ' . __( 'Successfully disconnected' ),
					),
				),
			)
		);
	}


	/**
	 * Get Addons list, grouped by connected status
	 *
	 * @since 4.0
	 */
	public function get_addons() {
		$this->validate_ajax();

		$providers          = Hustle_Provider_Utils::get_registered_addons_grouped_by_connected();
		$connected_html     = $this->renderer->render( 'admin/integrations/page-table-connected', array( 'providers' => $providers['connected'] ), true );
		$not_connected_html = $this->renderer->render( 'admin/integrations/page-table-not-connected', array( 'providers' => $providers['not_connected'] ), true );

		wp_send_json_success(
			array(
				'connected'     => $connected_html,
				'not_connected' => $not_connected_html,
			)
		);

	}

	/**
	 * Refresh email lists
	 *
	 * @since 4.0.2
	 */
	public function refresh_email_lists() {
		$this->validate_ajax();

		$module_id = filter_input( INPUT_POST, 'id' );
		$slug      = filter_input( INPUT_POST, 'slug' );
		$type      = filter_input( INPUT_POST, 'type' );

		$provider = Hustle_Provider_Utils::get_provider_by_slug( $slug );

		if ( ! $provider ) {
			wp_send_json_error( __( 'Provider not found', 'hustle' ) );
		}

		$class_name = $provider->get_form_settings_class_name();
		if ( empty( $class_name ) || ! class_exists( $class_name ) ) {
			wp_send_json_error( __( 'Settings class not found', 'hustle' ) );
		}
		$form_settings_instance = new $class_name( $provider, '' );
		$lists                  = $form_settings_instance->get_global_multi_lists( true, $module_id, $type );

		$list_id = empty( $type ) || 'forms' !== $type ? 'list_id' : 'form_id';
		$options = array(
			'list' => array(
				'id'       => $list_id,
				'type'     => 'select',
				'name'     => $list_id,
				'default'  => '',
				'options'  => $lists,
				'value'    => '',
				'selected' => '',
				'class'    => 'sui-select',
			),
		);
		$select  = Hustle_Provider_Utils::get_html_for_options( $options );

		wp_send_json_success(
			array(
				'select' => $select,
			)
		);
	}

	/**
	 * Get providers list, grouped by connected status with module
	 *
	 * @since 4.0.0
	 */
	public function get_form_addons() {
		$this->validate_ajax();

		$post_data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		$sanitized_data     = Opt_In_Utils::validate_and_sanitize_fields( $post_data, array( 'moduleId' => 'moduleId' ) );
		$module_id          = isset( $sanitized_data['moduleId'] ) ? $sanitized_data['moduleId'] : '';
		$providers          = Hustle_Provider_Utils::get_registered_addons_grouped_by_form_connected( $module_id );
		$connected_html     = $this->renderer->render(
			'admin/integrations/wizard-table-connected',
			array(
				'providers' => $providers['connected'],
				'module_id' => $module_id,
			),
			true
		);
		$not_connected_html = $this->renderer->render(
			'admin/integrations/wizard-table-not-connected',
			array(
				'providers' => $providers['not_connected'],
				'module_id' => $module_id,
			),
			true
		);

		$list_connected = array();

		if ( ! empty( $providers ) && isset( $providers['connected'] ) ) {
			foreach ( $providers['connected'] as $key => $value ) {
				$list_connected[] = $value['slug'];
			}
		}

		wp_send_json_success(
			array(
				'connected'            => $connected_html,
				'not_connected'        => $not_connected_html,
				'list_connected'       => implode( ',', $list_connected ),
				'list_connected_total' => count( $list_connected ),
			)
		);
	}

	/**
	 * Handles the provider's wizard for the global configuration.
	 *
	 * @since 4.0.0
	 */
	public function settings() {
		$this->validate_ajax();
		Opt_In_Utils::is_user_allowed_ajax( 'hustle_edit_integrations' );

		$sanitized_post_data = $this->get_sanitized_submitted_data();

		$check_required_fields_missing = $this->check_required_fields( $sanitized_post_data, array( 'slug', 'step', 'current_step' ) );

		if ( ! empty( $check_required_fields_missing ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Please check the required fields.', 'hustle' ),
					'errors'  => $check_required_fields_missing,
				)
			);
		}

		$slug         = $sanitized_post_data['slug'];
		$step         = $sanitized_post_data['step'];
		$current_step = $sanitized_post_data['current_step'];
		$module_id    = 0;
		if ( isset( $sanitized_post_data['module_id'] ) ) {
			$module_id = $sanitized_post_data['module_id'];
			// Module_id could be unset from $sanitized_post_data when the providers don't expect it anymore within they params.
		}

		$provider = Hustle_Provider_Utils::get_provider_by_slug( $slug );

		if ( ! $provider ) {
			wp_send_json_error( __( 'Provider not found', 'hustle' ) );
		}

		if ( ! $provider->is_settings_available() ) {
			wp_send_json_error(
				array(
					'data' => $provider->get_empty_wizard( __( 'This provider does not have settings available', 'hustle' ) ),
				)
			);
		}

		Hustle_Provider_Utils::maybe_attach_addon_hook( $provider );

		unset( $sanitized_post_data['slug'] );
		unset( $sanitized_post_data['current_step'] );
		unset( $sanitized_post_data['step'] );

		$wizard = $provider->get_settings_wizard( $sanitized_post_data, $module_id, $current_step, $step, true );

		wp_send_json_success(
			array(
				'data' => $wizard,
			)
		);
	}

	/**
	 * Handles the provider's wizard for the per module configuration.
	 *
	 * @since 4.0.0
	 */
	public function form_settings() {
		$this->validate_ajax();

		$sanitized_post_data = $this->get_sanitized_submitted_data( array( 'module_id' => 'FILTER_VALIDATE_INT' ) );

		$check_required_fields_missing = $this->check_required_fields( $sanitized_post_data, array( 'slug', 'step', 'current_step', 'module_id' ) );

		if ( ! empty( $check_required_fields_missing ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Please check the required fields.', 'hustle' ),
					'errors'  => $check_required_fields_missing,
				)
			);
		}

		$slug         = $sanitized_post_data['slug'];
		$step         = (int) $sanitized_post_data['step'];
		$current_step = (int) $sanitized_post_data['current_step'];
		$module_id    = $sanitized_post_data['module_id'];

		$provider = Hustle_Provider_Utils::get_provider_by_slug( $slug );

		if ( ! $provider ) {
			wp_send_json_error( __( 'Provider not found', 'hustle' ) );
		}

		if ( ! $provider->is_form_settings_available( $module_id ) ) {
			wp_send_json_success(
				array(
					'data' => $provider->get_empty_wizard( __( 'This provider does not have form settings available', 'hustle' ) ),
				)
			);
		}

		Hustle_Provider_Utils::maybe_attach_addon_hook( $provider );

		unset( $sanitized_post_data['slug'] );
		unset( $sanitized_post_data['current_step'] );
		unset( $sanitized_post_data['step'] );
		unset( $sanitized_post_data['module_id'] );

		$wizard = $provider->get_form_settings_wizard( $sanitized_post_data, $module_id, $current_step, $step );

		wp_send_json_success(
			array(
				'data' => $wizard,
			)
		);
	}

	/**
	 * Handles the provider's deactivation from modules.
	 *
	 * @since 4.0.0
	 */
	public function form_deactivate() {
		$this->validate_ajax();

		$sanitized_data = Opt_In_Utils::validate_and_sanitize_fields( $_POST['data'], array( 'slug', 'module_id' ) );
		$slug           = $sanitized_data['slug'];
		$module_id      = $sanitized_data['module_id'];

		$provider       = Hustle_Provider_Utils::get_provider_by_slug( $slug );
		$provider_title = $provider->get_title();

		if ( ! $provider ) {
			$response = array(
				'message' => __( 'Addon not found', 'hustle' ),
				'data'    => array(
					'notification' => array(
						'type' => 'error',
						'text' => '<strong>' . $slug . '</strong> ' . __( 'integration not found', 'hustle' ),
					),
				),
			);
			wp_send_json_error( $response );
		}

		$form_settings = $provider->get_provider_form_settings( $module_id );
		if ( $form_settings instanceof Hustle_Provider_Form_Settings_Abstract ) {
			unset( $sanitized_data['slug'] );
			unset( $sanitized_data['module_id'] );

			// Handling multi_id.
			if ( isset( $sanitized_data['multi_id'] ) ) {
				$multi_id_label = '';
				$multi_ids      = $form_settings->get_multi_ids();
				foreach ( $multi_ids as $key => $multi_id ) {
					if ( isset( $multi_id['id'] ) && $multi_id['label'] ) {
						if ( $multi_id['id'] === $sanitized_data['multi_id'] ) {
							$multi_id_label = $multi_id['label'];
							break;
						}
					}
				}

				if ( ! empty( $multi_id_label ) ) {
					$provider_title .= ' [' . $multi_id_label . '] ';
				}
			}

			$form_settings->disconnect_form( $sanitized_data );

			$response = array(
				'message' => sprintf( __( 'Successfully disconnected %1$s from this form', 'hustle' ), $provider_title ),
				'data'    => array(
					'notification' => array(
						'type' => 'success',
						'text' => '<strong>' . $provider_title . '</strong> ' . __( 'successfully disconnected from this form', 'hustle' ),
					),
				),
			);
			wp_send_json_success( $response );
		} else {
			$response = array(
				'message' => sprintf( __( 'Failed to disconnect %1$s from this form', 'hustle' ), $provider_title ),
				'data'    => array(
					'notification' => array(
						'type' => 'error',
						'text' => '<strong>' . $provider->get_title() . '</strong> ' . __( 'Failed to disconnected from this form', 'hustle' ),
					),
				),
			);
			wp_send_json_error( $response );
		}
	}

	/**
	 * Insert local list into module
	 *
	 * @since 4.0.1
	 */
	public function insert_local_list() {
		$this->validate_ajax();

		$id     = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
		$module = new Hustle_Module_Model( $id );

		if ( 0 < $id && ! is_wp_error( $module ) ) {
			$module->update_meta( 'local_list_provider_settings', array( 'local_list_name' => 'hustle-' . wp_rand() ) );

			$integrations_settings                        = $module->get_integrations_settings()->to_array();
			$integrations_settings['active_integrations'] = 'local_list';
			$module->update_meta( Hustle_Module_Model::KEY_INTEGRATIONS_SETTINGS, $integrations_settings );

			wp_send_json_success();
		}

		if ( is_wp_error( $module ) ) {
			wp_send_json_error( sprintf( __( 'Invalid module!', 'hustle' ) ) );
		}

		wp_send_json_error();
	}

	/**
	 * Migrate aweber integration from oAuth1 to oAuth2
	 *
	 * @since 4.1.1
	 */
	public function migrate_aweber() {
		$this->validate_ajax();

		if ( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) { // WPCS: CSRF ok.
			$post_data = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		} else {
			$post_data = filter_input( INPUT_POST, 'data' );
		}
		$sanitized_post_data = Opt_In_Utils::validate_and_sanitize_fields( $post_data, array( 'slug', 'api_key', 'global_multi_id' ) );

		$aweber = Hustle_Aweber::get_instance()->configure_migrated_api_key( $sanitized_post_data );

		if ( $aweber ) {
			wp_send_json_error();
		}

		wp_send_json_success();

	}

	/**
	 * Check if is active on module
	 *
	 * @since 4.0.1
	 */
	public function is_on_module() {
		$this->validate_ajax();

		$data = Opt_In_Utils::validate_and_sanitize_fields( $_POST['data'], array( 'slug' ) ); // WPCS: CSRF ok.
		$slug = $data['slug'];

		$provider           = Hustle_Provider_Utils::get_provider_by_slug( $slug );
		$is_multi_on_global = $provider->is_allow_multi_on_global();
		$is_multi_on_form   = $provider->is_allow_multi_on_form();

		$global_multi_id = filter_var( $_POST['data']['globalMultiId'], FILTER_SANITIZE_STRING );
		$global_multi_id = ( $is_multi_on_global && ! $is_multi_on_form && ! empty( $global_multi_id ) ) ? $global_multi_id : false;

		$modules = Hustle_Provider_Utils::get_modules_by_active_provider( $slug, $global_multi_id );

		$module_data = array();
		foreach ( $modules as $module ) {

			$meta = $module->get_meta( 'integrations_settings' );

			$module_data[ $module->module_id ] = array(
				'edit_url' => esc_url_raw( $module->get_edit_url( 'integrations' ) ),
				'name'     => $module->module_name,
				'type'     => $module->module_type,
				'active'   => json_decode( $meta ),
			);
		}
		if ( ! empty( $module_data ) ) {
			wp_send_json_success( $module_data );
		}

		if ( is_wp_error( $modules ) ) {
			wp_send_json_error( sprintf( __( 'Invalid module!', 'hustle' ) ) );
		}

		wp_send_json_error();
	}

	/**
	 * Sanitizes the incoming $_POST['data'].
	 *
	 * It uses FILTER_SANITIZE_STRINGS for all keys except the predefined ones.
	 *
	 * @since 4.1.0
	 *
	 * @param array $extra_base_filters Additional default filters to be used.
	 *
	 * @return array
	 */
	private function get_sanitized_submitted_data( $extra_base_filters = array() ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		if ( ! empty( $_POST ) && ! is_array( $_POST['data'] ) ) {
			$string_data = filter_input( INPUT_POST, 'data', FILTER_DEFAULT );
			parse_str( $string_data, $data );

		} else {
			$data = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		}

		// Required and shared fields along all providers' requests.
		$base_filters = array_merge(
			array(
				'slug'         => FILTER_SANITIZE_STRING,
				'step'         => FILTER_VALIDATE_INT,
				'current_step' => FILTER_VALIDATE_INT,
			),
			$extra_base_filters
		);

		// Let's not kill submitted arrays.
		$arrays_filters   = array();
		$submitted_arrays = array_filter( $data, 'is_array' );
		if ( ! empty( $submitted_arrays ) ) {

			$array_args = array(
				'filter' => FILTER_SANITIZE_STRING,
				'flags'  => FILTER_REQUIRE_ARRAY,
			);

			$arrays_filters = array_fill_keys( array_keys( $submitted_arrays ), $array_args );
		}

		// Implement FILTER_SANITIZE_STRING for all the other incoming fields.
		$generic_filters = array_fill_keys( array_keys( $data ), 'FILTER_SANITIZE_STRING' );

		// Merge both generic filters with the pre-defined and arrays ones.
		$filters = array_merge( $generic_filters, $arrays_filters, $base_filters );

		// Aand filter.
		$sanitized_data = filter_var_array( $data, $filters );

		if ( ! empty( $sanitized_data['name'] ) ) {
			$sanitized_data['name'] = wp_strip_all_tags( $sanitized_data['name'] );
		}

		return $sanitized_data;
	}

	/**
	 * Check that the required fields are set and not empty.
	 *
	 * @since 4.1.0
	 *
	 * @param array $data            Data to check.
	 * @param array $required_fields Key of the required fields to look for.
	 *
	 * @return array|bool Array on missing required fields. False otherwise.
	 */
	private function check_required_fields( $data, $required_fields ) {

		$errors = array();
		foreach ( $required_fields as $required_field ) {
			if ( ! isset( $data[ $required_field ] ) || ( empty( trim( $data[ $required_field ] ) ) && 0 !== $data[ $required_field ] ) ) {
				/* translators: required field name */
				$errors[ $required_field ] = sprintf( __( 'Field %s is required.', 'hustle' ), $required_field );
				continue;
			}
		}

		if ( ! empty( $errors ) ) {
			return $errors;
		}

		return false;
	}
}