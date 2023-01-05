<?php
/**
 * Hustle_Provider_Utils class.
 *
 * @package Hustle
 * @since 3.5.0
 */

/**
 * Helper functions for providers
 *
 * @since 4.0.0
 */
class Hustle_Provider_Utils {

	/**
	 * Instance of Hustle Provider Utils.
	 *
	 * @since 4.0.0
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Renderer class.
	 *
	 * @var Hustle_Layout_Helper
	 */
	private static $renderer = null;

	/**
	 * Slug will be used as additional info in submission entries.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	public $_last_url_request;

	/**
	 * Slug will be used as additional info in submission entries.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	public $_last_data_received;

	/**
	 * Slug will be used as additional info in submission entries.
	 *
	 * @since 4.0
	 * @var string
	 */
	public $_last_data_sent;

	/**
	 * Return the existing instance of Hustle_Provider_Utils, or create a new one if none exists.
	 *
	 * @since 4.0
	 * @return Hustle_Provider_Utils
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the last URL request and unset it.
	 *
	 * @since  4.0
	 * @return string
	 */
	final public function get_last_url_request() {
		$last_url_request        = $this->_last_url_request;
		$this->_last_url_request = null;

		return $last_url_request;
	}

	/**
	 * Get the last received data and unset it.
	 *
	 * @since  4.0
	 * @param bool $unset Either unset it or not.
	 * @return string
	 */
	final public function get_last_data_received( $unset = true ) {
		$last_data_received = $this->_last_data_received;
		if ( $unset ) {
			$this->_last_data_received = null;
		}

		return $last_data_received;
	}

	/**
	 * Get the last sent data and unset it.
	 *
	 * @since  4.0
	 * @return string
	 */
	final public function get_last_data_sent() {
		$last_data_sent        = $this->_last_data_sent;
		$this->_last_data_sent = null;

		return $last_data_sent;
	}

	/**
	 * Gets all providers as list
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_registered_providers_list() {
		$providers_list = Hustle_Providers::get_instance()->get_providers()->to_array();

		// Late init properties.
		foreach ( $providers_list as $key => $provider ) {
			$providers_list[ $key ]['is_active'] = Hustle_Providers::get_instance()->addon_is_active( $key );
		}

		return $providers_list;
	}

	/**
	 * Get registered addons grouped by connected status
	 *
	 * @since 4.0
	 * @return array
	 */
	public static function get_registered_addons_grouped_by_connected() {
		$addon_list           = self::get_registered_providers_list();
		$connected_addons     = array();
		$not_connected_addons = array();

		// Late init properties.
		foreach ( $addon_list as $key => $addon ) {

			if ( $addon['is_multi_on_global'] ) {
				// Add instances to connected.
				if ( isset( $addon['global_multi_ids'] ) && is_array( $addon['global_multi_ids'] ) ) {
					foreach ( $addon['global_multi_ids'] as $multi_id ) {
						$addon_array                    = $addon;
						$addon_array['global_multi_id'] = $multi_id['id'];
						$addon_array['multi_name']      = ! empty( $multi_id['label'] ) ? $multi_id['label'] : $multi_id['id'];
						$connected_addons[]             = $addon_array;
					}
				}
				$not_connected_addons[] = $addon;
			} else {
				if ( $addon['is_connected'] ) {
					$connected_addons[] = $addon;
				} else {
					if ( 'zapier' !== $key ) {
						$not_connected_addons[] = $addon;
					} else {
						// Add Zapier at the top of the non-connected providers
						// in order to promote it in a more noticeable way.
						array_unshift( $not_connected_addons, $addon );
					}
				}
			}
		}

		return array(
			'connected'     => $connected_addons,
			'not_connected' => $not_connected_addons,
		);
	}

	/**
	 * Get the instance of the providers that are connected to a module.
	 *
	 * @since 4.0.0
	 *
	 * @param string $module_id Model ID.
	 * @return array Hustle_Provider_Abstract[]
	 */
	public static function get_addons_instance_connected_with_module( $module_id ) {
		$providers = array();

		$active_addons_slug = Hustle_Providers::get_instance()->get_activated_addons();

		// TODO: move local list first.

		foreach ( $active_addons_slug as $active_addon_slug ) {
			$provider = self::get_provider_by_slug( $active_addon_slug );
			if ( $provider ) {
				if ( $provider->is_form_connected( $module_id ) ) {
					$class_name             = $provider->get_form_settings_class_name();
					$form_settings_instance = new $class_name( $provider, $module_id );
					$form_settings_values   = $form_settings_instance->get_form_settings_values();
					if ( ! empty( $form_settings_values['selected_global_multi_id'] ) ) {
						$provider->selected_global_multi_id = $form_settings_values['selected_global_multi_id'];
					}
					$providers[] = $provider;
				}
			}
		}

		return $providers;
	}

	/**
	 * Get provider(s) in array format grouped by connected / not connected with $module_id
	 *
	 * Every addon inside this array will be formatted first by @see Hustle_Provider_Abstract::to_array_with_form().
	 *
	 * @since 4.0.0
	 *
	 * @param string $module_id Optional. Module ID.Get module id from URL if it's not set.
	 * @return array
	 */
	public static function get_registered_addons_grouped_by_form_connected( $module_id = null ) {

		if ( is_null( $module_id ) ) {
			$module_id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
			if ( is_null( $module_id ) ) {
				$module_id = '';
			}
		}

		$connected_addons     = array();
		$not_connected_addons = array();

		$providers = self::get_registered_providers_list();

		foreach ( $providers as $slug => $data ) {
			if ( ! $data['is_connected'] ) {
				continue;
			}

			$provider = self::get_provider_by_slug( $slug );

			if ( $provider->is_allow_multi_on_form() ) {
				$provider_array = $provider->to_array_with_form( $module_id );
				if ( isset( $provider_array['multi_ids'] ) && is_array( $provider_array['multi_ids'] ) ) {
					foreach ( $provider_array['multi_ids'] as $multi_id ) {
						$provider_array['multi_id']   = $multi_id['id'];
						$provider_array['multi_name'] = ! empty( $multi_id['label'] ) ? $multi_id['label'] : $multi_id['id'];
						$connected_addons[]           = $provider_array;
					}
				}
				$not_connected_addons[] = $provider->to_array_with_form( $module_id );
			} else {
				if ( $provider->is_connected() && $provider->is_form_connected( $module_id ) ) {
					$data               = $provider->to_array_with_form( $module_id );
					$data               = $provider->maybe_add_multi_name( $data, $module_id );
					$connected_addons[] = $data;
				} else {
					$not_connected_addons[] = $provider->to_array_with_form( $module_id );
				}
			}
		}

		return array(
			'connected'     => $connected_addons,
			'not_connected' => $not_connected_addons,
		);
	}

	/**
	 * Get registered addons.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Provider_Abstract[]
	 */
	public static function get_registered_addons() {
		$addons            = array();
		$registered_addons = Hustle_Providers::get_instance()->get_providers();

		foreach ( $registered_addons as $slug => $registered_addon ) {
			$addon = self::get_provider_by_slug( $slug );
			if ( $addon instanceof Hustle_Provider_Abstract ) {
				$addons[ $addon->get_slug() ] = $addon;
			}
		}

		return $addons;
	}

	/**
	 * Attach default hooks for provider.
	 *
	 * Call when needed only,
	 * defined in @see Hustle_Provider_Abstract::global_hookable()
	 * and @see Hustle_Provider_Abstract::admin_hookable() on admin side.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Provider_Abstract $provider Provider instance.
	 */
	public static function maybe_attach_addon_hook( Hustle_Provider_Abstract $provider ) {

		$provider->global_hookable();
		// Hooks that are available on admin only.
		if ( is_admin() ) {
			$provider->admin_hookable();
		}
	}

	/**
	 * Get all activable providers as list
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public static function get_activable_providers_list() {
		$providers_list = self::get_registered_providers_list();
		foreach ( $providers_list as $key => $provider ) {
			if ( ! $providers_list[ $key ]['is_activable'] ) {
				unset( $providers_list[ $key ] );
			}
		}
		return $providers_list;
	}

	/**
	 * Returns provider class by name
	 *
	 * @since 3.0.5
	 *
	 * @param string $slug provider slug.
	 * @return bool|Opt_In_Provider_Abstract
	 */
	public static function get_provider_by_slug( $slug ) {
		return Hustle_Providers::get_instance()->get_provider( $slug );
	}

	/**
	 * Return if the passed provider is active or not.
	 *
	 * @since 4.0.0
	 *
	 * @param string $slug Provider's slug.
	 * @return boolean
	 */
	public static function is_provider_active( $slug ) {
		return Hustle_Providers::get_instance()->addon_is_active( $slug );
	}

	/**
	 * Get the modules that are connected to the given provider.
	 * Optionally, check that the given global multi id is in use.
	 *
	 * @since 4.0.1
	 *
	 * @param string  $slug Provider's slug.
	 * @param boolean $global_multi_id Id of the provider global instance.
	 * @return array Hustle_Module_Model[]
	 */
	public static function get_modules_by_active_provider( $slug, $global_multi_id = false ) {

		$modules_ids = Hustle_Module_Collection::get_active_providers_module( $slug );
		$modules     = array();

		foreach ( $modules_ids as $id ) {

			$module = new Hustle_Module_Model( $id );

			if ( is_wp_error( $module ) ) {
				continue;
			}

			if ( $global_multi_id ) {

				$provider_settings = $module->get_provider_settings( $slug );

				// If the provider in this module isn't connected to the instance being disconnected, skip.
				if ( ! isset( $provider_settings['selected_global_multi_id'] ) || $provider_settings['selected_global_multi_id'] !== $global_multi_id ) {
					continue;
				}
			}

			$modules[] = $module;
		}

		return $modules;
	}

	/**
	 * Format Form Fields
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Module_Model $module Module instance.
	 *
	 * @return array
	 */
	public static function addon_format_form_fields( Hustle_Module_Model $module ) {
		$formatted_fields = array();
		$fields           = $module->get_form_fields();

		foreach ( $fields as $field ) {
			$ignored_fields = Hustle_Entry_Model::ignored_fields();
			if ( in_array( $field['type'], $ignored_fields, true ) ) {
				continue;
			}

			$formatted_fields[] = $field;
		}

		return $formatted_fields;
	}

	/**
	 * Find addon meta data from entry model that saved on db
	 *
	 * @since 4.0.0
	 *
	 * @param Hustle_Provider_Abstract $connected_addon Provider instance.
	 * @param Hustle_Entry_Model       $entry_model Entry model.
	 *
	 * @return array
	 */
	public static function find_addon_meta_data_from_entry_model( Hustle_Provider_Abstract $connected_addon, Hustle_Entry_Model $entry_model ) {
		$addon_meta_data        = array();
		$addon_meta_data_prefix = 'hustle_provider_' . $connected_addon->get_slug() . '_';
		foreach ( $entry_model->meta_data as $key => $meta_datum ) {
			if ( false !== stripos( $key, $addon_meta_data_prefix ) ) {
				$addon_meta_data[] = array(
					'name'  => str_ireplace( $addon_meta_data_prefix, '', $key ),
					'value' => $meta_datum['value'],
				);
			}
		}

		/**
		 * Filter addon's meta data retrieved from db
		 *
		 * @since 4.0
		 */
		$addon_meta_data = apply_filters( 'hustle_provider_meta_data_from_entry_model', $addon_meta_data, $connected_addon, $entry_model, $addon_meta_data_prefix );

		return $addon_meta_data;
	}

	/**
	 * Unset technical data of a form.
	 *
	 * @since 4.0.0
	 *
	 * @param array $data $_POST.
	 *
	 * @return array
	 */
	public static function format_submitted_data_for_addon( $data ) {
		$new_data = $data;
		unset( $new_data['form'], $new_data['module_id'], $new_data['uri'], $new_data['hustle_module_id'], $new_data['post_id'], $new_data['g-recaptcha-response'], $new_data['hustle_sub_type'], $new_data['recaptcha-response'] );
		$new_data = apply_filters( 'hustle_provider_form_formatted_submitted_data', $new_data, $data );

		return $new_data;
	}

	/**
	 * Retrieves the HTML markup given an array of options.
	 * Renders it from the file "general/option.php", which is a template.
	 * The array should be something like:
	 * array(
	 *      "optin_url_label" => array(
	 *          "id"    => "optin_url_label",
	 *          "for"   => "optin_url",
	 *          "value" => "Enter a Webhook URL:",
	 *          "type"  => "label",
	 *      ),
	 *      "optin_url_field_wrapper" => array(
	 *          "id"        => "optin_url_id",
	 *          "class"     => "optin_url_id_wrapper",
	 *          "type"      => "wrapper",
	 *          "elements"  => array(
	 *              "optin_url_field" => array(
	 *                  "id"            => "optin_url",
	 *                  "name"          => "api_key",
	 *                  "type"          => "text",
	 *                  "default"       => "",
	 *                  "value"         => "",
	 *                  "placeholder"   => "",
	 *                  "class"         => "wpmudev-input_text",
	 *              )
	 *          )
	 *      ),
	 *  );
	 *
	 * @since 4.0.0
	 * @since 4.2.0 Uses Hustle_Layout_Helper::render() instead of Opt_In::static_render().
	 *
	 * @uses Hustle_Layout_Helper::render()
	 * @param array $options Layout options.
	 * @return string
	 */
	public static function get_html_for_options( $options ) {
		$html     = '';
		$renderer = self::get_renderer();
		foreach ( $options as $key => $option ) {
			$html .= $renderer->render( 'general/option', array_merge( $option, array( 'key' => $key ) ), true );
		}
		return $html;
	}

	/**
	 * Temporary
	 *
	 * @todo remove
	 */
	private static function get_renderer() {
		if ( ! self::$renderer ) {
			self::$renderer = new Hustle_Layout_Helper();
		}
		return self::$renderer;
	}

	/**
	 * Return the markup used for the title of Integrations modal.
	 *
	 * @since 4.0.0
	 *
	 * @param string $title Integration wizard title.
	 * @param string $subtitle Integration wizard subtitle.
	 * @param string $class Title wrapper extra classes.
	 * @return string
	 */
	public static function get_integration_modal_title_markup( $title = '', $subtitle = '', $class = '' ) {

		$html = '<div class="integration-header ' . esc_attr( $class ) . '">';

		if ( ! empty( $title ) ) {
			$html .= '<h3 class="sui-box-title sui-lg" id="dialogTitle2">' . esc_html( $title ) . '</h3>';
		}

		if ( ! empty( $subtitle ) ) {
			$html .= '<p class="sui-description">' . $subtitle . '</p>';
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Return the markup for buttons.
	 *
	 * @since 4.0.0
	 *
	 * @param string $value Button text.
	 * @param string $class Extra classes.
	 * @param string $action next/prev/close/connect/disconnect. Action that this button triggers.
	 * @param bool   $loading whether the button should have the 'loading' markup.
	 * @param bool   $disabled Whether the button is disabled.
	 * @param string $custom_url Url to which the button should take the user to, if any.
	 * @return string
	 */
	public static function get_provider_button_markup( $value = '', $class = '', $action = '', $loading = false, $disabled = false, $custom_url = '' ) {

		$html         = '';
		$action_class = '';

		if ( ! empty( $action ) ) {

			switch ( $action ) {
				case 'next':
					$action_class = 'hustle-provider-next ';
					break;
				case 'prev':
					$action_class = 'hustle-provider-back ';
					break;
				case 'close':
					$action_class = 'hustle-provider-close hustle-modal-close ';
					break;
				case 'connect':
					$action_class = 'hustle-provider-connect ';
					break;
				case 'disconnect':
					$action_class = 'hustle-provider-disconnect ';
					break;
				case 'disconnect_form':
					$action_class = 'hustle-provider-form-disconnect ';
					break;
				case 'refresh_list':
					$action_class = 'hustle-refresh-email-lists ';
					break;
				default:
					$action_class = '';
			}
		}

		if ( empty( $custom_url ) ) {
			$tag         = 'button';
			$custom_attr = 'type="button"';
		} else {
			$tag         = 'a';
			$custom_attr = 'href="' . esc_url( $custom_url ) . '"';
		}

		if ( $loading ) {
			$action_class .= 'hustle-onload-icon-action ';
			$inner         = '<span class="sui-loading-text">' . esc_html( $value ) . '</span><i class="sui-icon-loader sui-loading" aria-hidden="true"></i>';

		} else {
			$inner = esc_html( $value );
		}

		if ( 'refresh_list' === $action ) {

			$html         .= sprintf(
				'<%1$s class="sui-button-icon sui-tooltip %2$s" data-tooltip="%3$s" %4$s >',
				$tag,
				esc_attr( $action_class . $class ),
				__( 'Refresh list', 'hustle' ),
				disabled( $disabled, true, false )
			);
				$html     .= '<span class="sui-loading-text" aria-hidden="true">';
					$html .= '<i class="sui-icon-refresh"></i>';
				$html     .= '</span>';
				$html     .= '<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>';
				$html     .= '<span class="sui-screen-reader-text">' . esc_html( $value ) . '</span>';
			$html         .= '</' . $tag . '>';

		} else {

			$html     .= '<' . $tag . ' ' . $custom_attr . ' class="sui-button ' . esc_attr( $action_class ) . esc_attr( $class ) . '" ' . disabled( $disabled, true, false ) . '>';
				$html .= $inner;
			$html     .= '</' . $tag . '>';

		}

		return $html;
	}

	/**
	 * Adds an entry to debug log
	 *
	 * By default it will check `HUSTLE_PROVIDER_DEBUG` to decide whether to add the log,
	 * then will check `filters`.
	 *
	 * @since 4.0
	 */
	public static function maybe_log() {
		$enabled = ( ! defined( 'HUSTLE_PROVIDER_DEBUG' ) || HUSTLE_PROVIDER_DEBUG );

		/**
		 * Filter to enable or disable log for Hustle
		 *
		 * @since 4.0
		 *
		 * @param bool $enabled current enabled status
		 */
		$enabled = apply_filters( 'hustle_provider_enable_log', $enabled );

		if ( $enabled && is_callable( array( 'Opt_In_Utils', 'maybe_log' ) ) ) {
			$args  = array( '[PROVIDER]' );
			$fargs = func_get_args();
			$args  = array_merge( $args, $fargs );
			call_user_func_array( array( 'Opt_In_Utils', 'maybe_log' ), $args );
		}
	}
}