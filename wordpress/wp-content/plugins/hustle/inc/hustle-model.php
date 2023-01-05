<?php

/**
 * Class Hustle_Model
 *
 * @property int $module_id
 * @property string $module_name
 * @property string $module_type
 * @property int $active
 */
abstract class Hustle_Model {

	const KEY_EMAILS  = 'emails';
	const KEY_CONTENT = 'content';

	/**
	 * Per provider settings. Used as {slug}_provider_settings.
	 *
	 * @since 4.0.0
	 */
	const KEY_PROVIDER        = '_provider_settings';
	const KEY_DESIGN          = 'design';
	const KEY_DISPLAY_OPTIONS = 'display';
	const KEY_VISIBILITY      = 'visibility';

	/**
	 * Per module settings applied to all integrations.
	 *
	 * @since 4.0.0
	 */
	const KEY_INTEGRATIONS_SETTINGS   = 'integrations_settings';
	const KEY_SETTINGS                = 'settings';
	const KEY_SHORTCODE_ID            = 'shortcode_id';
	const TRACK_TYPES                 = 'track_types';
	const KEY_UNSUBSCRIBE_NONCES      = 'hustle_unsubscribe_nonces';
	const KEY_MODULE_META_PERMISSIONS = 'edit_roles';

	const POPUP_MODULE          = 'popup';
	const SLIDEIN_MODULE        = 'slidein';
	const EMBEDDED_MODULE       = 'embedded';
	const SOCIAL_SHARING_MODULE = 'social_sharing';
	const OPTIN_MODE            = 'optin';
	const INFORMATIONAL_MODE    = 'informational';
	const INLINE_MODULE         = 'inline';
	const WIDGET_MODULE         = 'widget';
	const SHORTCODE_MODULE      = 'shortcode';
	const SUBSCRIPTION          = 'subscription';

	/**
	 * Optin id
	 *
	 * @since 1.0.0
	 *
	 * @var $id int
	 */
	public $id;

	protected $_track_types = array();

	protected $_decorator = false;

	/**
	 * Data.
	 *
	 * @since 1.0.0
	 *
	 * @var array $data
	 */
	protected $data;

	/**
	 * Reference to $wpdb global var
	 *
	 * @since 1.0.0
	 *
	 * @var $wpdb WPDB
	 * @access private
	 */
	protected $wpdb;

	/**
	 *
	 * Opt_In_Data constructor.
	 */
	public function __construct( $id = null ) {
		global $wpdb;
		$this->wpdb = $wpdb;

		if ( empty( $id ) ) {
			return;
		}

		$cache_group = 'hustle_model_data';
		$data        = wp_cache_get( $id, $cache_group );
		$id          = (int) $id;

		if ( false === $data ) {
			global $wpdb;
			$data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'hustle_modules WHERE module_id = %d', $id ), OBJECT );
			if ( empty( $data ) ) {
				return new WP_Error( 'hustle-module', __( 'Module does not exist!', 'hustle' ) );
			}

			wp_cache_set( $id, $data, $cache_group );
		}

		$this->data = $data;
		$this->populate();
	}

	/**
	 * Returns the module's model by the given ID.
	 *
	 * @since 4.3.0
	 *
	 * @param int $id Module ID.
	 * @return Hustle_Model|WP_Error
	 */
	public static function get_module( $id ) {
		$module_type = self::get_module_type_by_module_id( $id );
		if ( empty( $module_type ) ) {
			$module = new WP_Error( '404', 'Module not found' );
		} elseif ( self::SOCIAL_SHARING_MODULE === $module_type ) {
			$module = new Hustle_SShare_Model( $id );
		} else {
			$module = new Hustle_Module_Model( $id );
		}

		return $module;
	}

	/**
	 * Returns a module based on the provided ID.
	 *
	 * @since unknown
	 *
	 * @param integer $id Module ID.
	 * @return Hustle_Model
	 */
	public function get( $id ) {
		_deprecated_function( __METHOD__, '4.3.0', 'new ' . get_class( $this ) );

		global $wpdb;

		$cache_group = 'hustle_model_data';
		$this->data  = wp_cache_get( $id, $cache_group );
		$id          = (int) $id;

		if ( false === $this->data ) {
			$data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'hustle_modules WHERE module_id = %d', $id ), OBJECT );
			if ( empty( $data ) ) {
				return new WP_Error( 'hustle-module', __( 'Module does not exist!', 'hustle' ) );
			}
			$this->data = $data;
			wp_cache_set( $id, $this->data, $cache_group );
		}
		$this->populate();
		return $this;
	}

	/**
	 * Sets the core properties of the module as properties of the instance.
	 * These are module_id, module_name, module_type, active, and blog_id.
	 *
	 * @since unknown
	 */
	private function populate() {
		if ( $this->data ) {
			$this->id = $this->data->module_id;
			foreach ( $this->data as $key => $data ) {
				$this->{$key} = $data;
			}
		}
		$this->get_tracking_types();
	}

	/**
	 * Saves or updates optin
	 *
	 * @since 1.0.0
	 *
	 * @return false|int
	 */
	public function save() {
		$data  = get_object_vars( $this );
		$table = Hustle_Db::modules_table();
		if ( empty( $this->id ) ) {
			$this->wpdb->insert( $table, $this->_sanitize_model_data( $data ), array_values( $this->get_format() ) );
			$this->id = $this->wpdb->insert_id;

			/**
			 * Action Hustle after creation module
			 *
			 * @since 3.0.7
			 *
			 * @param string $module_type module type
			 * @param array $data module data
			 * @param int $id module id
			 */
			do_action( 'hustle_after_create_module', $this->module_type, $data, $this->id );
		} else {
			$this->wpdb->update( $table, $this->_sanitize_model_data( $data ), array( 'module_id' => $this->id ), array_values( $this->get_format() ), array( '%d' ) );

			/**
			 * Action Hustle after updating module
			 *
			 * @since 3.0.7
			 *
			 * @param string $module_type module type
			 * @param array $data module data
			 */
			do_action( 'hustle_after_update_module', $this->module_type, $data );
		}

		// Clear cache as well.
		$this->clean_module_cache( 'data' );

		return $this->id;
	}

	/**
	 * Update the module's data.
	 *
	 * @since 4.0.0
	 *
	 * @param array $data Data to save.
	 * @return bool|int
	 */
	public function update_module( $data ) {

		// Save to modules table.
		if ( isset( $data['module'] ) ) {
			$this->module_name = $data['module']['module_name'];
			$this->active      = (int) $data['module']['active'];
			$this->save();
		}

		$this->update_module_metas( $data );

		$this->clean_module_cache();

		return $this->id;
	}

	/**
	 * Duplicate a module.
	 *
	 * @since 3.0.5
	 * @since 4.0 moved from Hustle_Popup_Admin_Ajax to here. New settings added.
	 *
	 * @return bool
	 */
	public function duplicate_module() {

		if ( ! $this->id ) {
			return false;
		}

		// TODO: make use of the sshare model to extend this instead.
		if ( self::SOCIAL_SHARING_MODULE !== $this->module_type ) {

			$data = array(
				'content'                       => $this->get_content()->to_array(),
				'emails'                        => $this->get_emails()->to_array(),
				'design'                        => $this->get_design()->to_array(),
				'settings'                      => $this->get_settings()->to_array(),
				'visibility'                    => $this->get_visibility()->to_array(),
				self::KEY_INTEGRATIONS_SETTINGS => $this->get_integrations_settings()->to_array(),
			);

			if ( self::EMBEDDED_MODULE === $this->module_type ) {
				$data['display'] = $this->get_display()->to_array();
			}

			// Pass integrations.
			if ( 'optin' === $this->module_mode ) {
				$integrations = array();
				$providers    = Hustle_Providers::get_instance()->get_providers();
				foreach ( $providers as $slug => $provider ) {
					$provider_data = $this->get_provider_settings( $slug, false );
					// if ( 'local_list' !== $slug && $provider_data && $provider->is_connected()
					if ( $provider_data && $provider->is_connected()
							&& $provider->is_form_connected( $this->module_id ) ) {
						$integrations[ $slug ] = $provider_data;
					}
				}

				$data['integrations'] = $integrations;
			}
		} else {
			$data = array(
				'content'    => $this->get_content()->to_array(),
				'display'    => $this->get_display()->to_array(),
				'design'     => $this->get_design()->to_array(),
				'visibility' => $this->get_visibility()->to_array(),
			);
		}

		unset( $this->id );

		// rename
		$this->module_name .= __( ' (copy)', 'hustle' );

		// Turn status off.
		$this->active = 0;

		// Save.
		$result = $this->save();

		if ( $result && ! is_wp_error( $result ) ) {

			$this->update_module( $data );

			return true;
		}

		return false;
	}

	/**
	 * Activate the passed providers.
	 *
	 * @since 4.0
	 *
	 * @param array $data
	 */
	public function activate_providers( $data ) {

		if ( 'optin' !== $this->module_mode ) {
			return;
		}

		// Activate other saved providers
		if ( ! empty( $data['integrations'] ) ) {
			$providers = Hustle_Providers::get_instance()->get_providers();
			foreach ( $providers as $slug => $provider ) {
				if ( ! empty( $data['integrations'][ $slug ] ) ) {
					$this->set_provider_settings( $slug, $data['integrations'][ $slug ] );
				}
			}
		} else {
			// Activate Local list provider if there are no integrations.
			$slug          = 'local_list';
			$provider_data = array(
				'local_list_name' => $this->id,
			);
			$this->set_provider_settings( $slug, $provider_data );
		}
	}

	/**
	 * Clean all (or certain) the cache related to a module.
	 *
	 * @since 3.0.7
	 *
	 * @param string $type Optional. Type of cache which should be removed ( data | meta | shortcode )
	 * @return void
	 */
	public function clean_module_cache( $type = '' ) {

		$id = $this->id;

		if ( empty( $type ) || in_array( $type, array( 'shortcode', 'data' ), true ) ) {
			$shortcode_id    = $this->get_shortcode_id();
			$shortcode_group = 'hustle_shortcode_data';
			wp_cache_delete( $shortcode_id, $shortcode_group );
		}

		if ( empty( $type ) || 'data' === $type ) {
			$module_group = 'hustle_model_data';
			wp_cache_delete( $id, $module_group );
		}

		if ( empty( $type ) || 'meta' === $type ) {
			$module_meta_group = 'hustle_module_meta';
			wp_cache_delete( $id, $module_meta_group );
		}

	}

	/**
	 * Returns populated model attributes
	 *
	 * @return array
	 */
	public function get_attributes() {
		$data = (array) $this->data;
		return $this->_sanitize_model_data( $data );
	}

	/**
	 * Matches given data to the data format
	 *
	 * @param $data
	 * @return array
	 */
	private function _sanitize_model_data( array $data ) {
		$d = array();
		foreach ( $this->get_format() as $key => $format ) {
			$d[ $key ] = isset( $data[ $key ] ) ? $data[ $key ] : '';
		}
		return $d;
	}

	/**
	 * Adds meta for the current optin
	 *
	 * @since 1.0.0
	 *
	 * @param $meta_key
	 * @param $meta_value
	 * @return false|int
	 */
	public function add_meta( $meta_key, $meta_value ) {
		$this->clean_module_cache( 'meta' );

		return $this->wpdb->insert(
			Hustle_Db::modules_meta_table(),
			array(
				'module_id'  => $this->id,
				'meta_key'   => $meta_key,
				'meta_value' => is_array( $meta_value ) || is_object( $meta_value ) ? wp_json_encode( $meta_value ) : $meta_value,
			),
			array(
				'%d',
				'%s',
				'%s',
			)
		);
	}

	/**
	 * Updates meta for the current optin
	 *
	 * @since 1.0.0
	 *
	 * @param $meta_key
	 * @param $meta_value
	 * @return false|int
	 */
	public function update_meta( $meta_key, $meta_value ) {

		if ( $this->has_meta( $meta_key ) ) {
			$res = $this->wpdb->update(
				Hustle_Db::modules_meta_table(),
				array(
					'meta_value' => is_array( $meta_value ) || is_object( $meta_value ) ? wp_json_encode( $meta_value ) : $meta_value,
				),
				array(
					'module_id' => $this->id,
					'meta_key'  => $meta_key,
				),
				array(
					'%s',
				),
				array(
					'%d',
					'%s',
				)
			);

			$this->clean_module_cache( 'meta' );
			if ( self::KEY_SHORTCODE_ID === $meta_key ) {
				$this->clean_module_cache( 'shortcode' );
			}

			return false !== $res;

		}

		return $this->add_meta( $meta_key, $meta_value );

	}

	/**
	 * Checks if optin has $meta_key added disregarding the meta_value
	 *
	 * @param $meta_key
	 * @return bool
	 */
	public function has_meta( $meta_key ) {
		return (bool) $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT * FROM ' . Hustle_Db::modules_meta_table() . ' WHERE `meta_key`=%s AND `module_id`=%d', $meta_key, (int) $this->id ) );
	}

	/**
	 * Retrieves optin meta from db
	 *
	 * @since ??
	 * @since 4.0 param $get_cached added.
	 *
	 * @param string $meta_key
	 * @param mixed  $default
	 * @param bool   $get_cached
	 * @return null|string|$default
	 */
	public function get_meta( $meta_key, $default = null, $get_cached = true ) {
		$cache_group = 'hustle_module_meta';

		$module_meta = wp_cache_get( $this->id, $cache_group );

		if ( ! $get_cached || false === $module_meta || ! array_key_exists( $meta_key, $module_meta ) ) {

			if ( false === $module_meta ) {
				$module_meta = array();
			}

			$value                    = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT `meta_value` FROM ' . Hustle_Db::modules_meta_table() . ' WHERE `meta_key`=%s AND `module_id`=%d', $meta_key, (int) $this->id ) );
			$module_meta[ $meta_key ] = $value;
			wp_cache_set( $this->id, $module_meta, $cache_group );

		}

		return is_null( $module_meta[ $meta_key ] ) ? $default : $module_meta[ $meta_key ];
	}

	/**
	 * Returns db data for current optin
	 *
	 * @return array
	 */
	public function get_data() {
		return (array) $this->data;
	}

	/**
	 * Deactivate module
	 *
	 * @since unknwon
	 */
	public function deactivate() {
		// Clear cache.
		$this->clean_module_cache( 'data' );

		return $this->wpdb->update(
			Hustle_Db::modules_table(),
			array( 'active' => 0 ),
			array( 'module_id' => $this->id ),
			array( '%d' )
		);
	}

	/**
	 * Activate module
	 *
	 * @since unknwon
	 */
	public function activate() {
		// Clear cache.
		$this->clean_module_cache( 'data' );

		return $this->wpdb->update(
			Hustle_Db::modules_table(),
			array( 'active' => 1 ),
			array( 'module_id' => $this->id ),
			array( '%d' )
		);
	}

	/**
	 * Deletes optin from optin table and optin meta table
	 *
	 * @return bool
	 */
	public function delete() {

		$this->clean_module_cache();

		// delete optin
		$result = $this->wpdb->delete(
			Hustle_Db::modules_table(),
			array(
				'module_id' => $this->id,
			),
			array(
				'%d',
			)
		);

		// delete metas
		$result = $result && $this->wpdb->delete(
			Hustle_Db::modules_meta_table(),
			array(
				'module_id' => $this->id,
			),
			array(
				'%d',
			)
		);

		// delete tracking data
		$this->wpdb->delete(
			Hustle_Db::tracking_table(),
			array(
				'module_id' => $this->id,
			),
			array(
				'%d',
			)
		);

		// delete entries
		Hustle_Entry_Model::delete_entries( $this->id );

		return $result;
	}
	/**
	 * Retrieves active tracking types from db
	 *
	 * @return null|array
	 */
	public function get_tracking_types() {
		$this->_track_types = json_decode( $this->get_meta( self::TRACK_TYPES ), true );
		return $this->_track_types;
	}

	/**
	 * Get the "edit roles" stored for this module.
	 *
	 * @since 4.1.0
	 * @return array
	 */
	public function get_edit_roles() {
		$meta_edit_roles = $this->get_meta( self::KEY_MODULE_META_PERMISSIONS );
		$meta_edit_roles = ! empty( $meta_edit_roles ) ? json_decode( $meta_edit_roles, true ) : array();

		return apply_filters( 'hustle_module_get_edit_roles_meta', $meta_edit_roles, $this );
	}

	/**
	 * Checks if $type is active
	 *
	 * @since 4.0
	 *
	 * @param $type
	 * @return bool
	 */
	public function is_tracking_enabled( $type ) {
		$tracking_types = $this->get_tracking_types();

		$is_tracking_enabled = (
			is_array( $tracking_types )
			&& array_key_exists( $type, $tracking_types )
			&& true === $tracking_types[ $type ]
		);

		$is_tracking_enabled = apply_filters( 'hustle_is_tracking_enabled', $is_tracking_enabled, $this, $type );

		return $is_tracking_enabled;
	}

	/**
	 * Edit the modules' "edit_roles" meta.
	 *
	 * @since 4.0
	 * @param array $roles Roles
	 * @return false|integer
	 */
	public function update_edit_roles( $roles ) {

		$available_roles = Opt_In_Utils::get_user_roles();
		$roles           = array_intersect( $roles, array_keys( $available_roles ) );

		return $this->update_meta( self::KEY_MODULE_META_PERMISSIONS, $roles );
	}

	/**
	 * Checks if $type is allowed to track views and conversions
	 *
	 * @param $type
	 * @return bool
	 */
	public function is_track_type_active( $type ) {
		return isset( $this->_track_types[ $type ] );
	}

	/**
	 * Toggles $type's tracking mode
	 *
	 * @param $type
	 * @return bool
	 */
	public function toggle_type_track_mode( $type ) {

		if ( $this->is_track_type_active( $type ) ) {
			unset( $this->_track_types[ $type ] );
		} else {
			$this->_track_types[ $type ] = true;
		}
		$res = $this->update_meta( self::TRACK_TYPES, $this->_track_types );

		return $res;
	}

	/**
	 * Get the module type by module id
	 * without the overhead of populating the model.
	 *
	 * @since 4.0
	 *
	 * @param integer $module_id
	 * @return string|null
	 */
	public static function get_module_type_by_module_id( $module_id ) {
		global $wpdb;
		$query = $wpdb->prepare(
			'
			SELECT module_type FROM `' . Hustle_Db::modules_table() . '`
			WHERE `module_id`=%s',
			$module_id
		);

		return $wpdb->get_var( $query );
	}

	/**
	 * Get the module ID by the shortcode ID.
	 *
	 * @since 4.3.1
	 *
	 * @param string $shortcode_id ID used in the shortcode.
	 * @return int
	 */
	public static function get_module_id_by_shortcode_id( $shortcode_id ) {
		global $wpdb;

		$cache_group = 'hustle_id_from_shortcode';
		$module_id   = wp_cache_get( $shortcode_id, $cache_group );

		if ( empty( $module_id ) ) {
			// Retrieve the shortcode_id from the db for backwards compatibility.
			$module_id = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT module_id FROM ' . $wpdb->prefix . "hustle_modules_meta
					WHERE meta_key = 'shortcode_id'
					AND meta_value = %s",
					$shortcode_id
				)
			);

			// Since 4.2.1, the modules are retrieved by their ID, not by a shortcode ID.
			if ( empty( $module_id ) && is_numeric( $shortcode_id ) ) {
				$module_id = $shortcode_id;
			}

			wp_cache_set( $shortcode_id, $module_id, $cache_group );
		}

		return $module_id;
	}

	/**
	 * Disable $type's tracking mode
	 *
	 * @param $type
	 * @param bool $force
	 * @return bool
	 */
	public function disable_type_track_mode( $type, $force = false ) {
		if ( $force && ! empty( $this->_track_types ) ) {
			$this->_track_types = array();
			$updated            = true;
		} elseif ( $this->is_track_type_active( $type ) ) {
			unset( $this->_track_types[ $type ] );
			$updated = true;
		}
		if ( ! empty( $updated ) ) {
			$res = $this->update_meta( self::TRACK_TYPES, $this->_track_types );
		}
	}

	/**
	 * enable $type's tracking mode
	 *
	 * @param $type
	 * @param bool $force
	 * @return bool
	 */
	public function enable_type_track_mode( $type, $force = false ) {
		if ( $force && 'social_sharing' === $type ) {
			$subtypes           = static::get_sshare_types();
			$this->_track_types = array_fill_keys( $subtypes, true );
			$updated            = true;
		} elseif ( $force && 'embedded' === $type ) {
			$subtypes           = static::get_embedded_types();
			$this->_track_types = array_fill_keys( $subtypes, true );
			$updated            = true;
		} elseif ( ! $this->is_track_type_active( $type ) ) {
			$this->_track_types[ $type ] = true;
			$updated                     = true;
		}
		if ( ! empty( $updated ) ) {
			$res = $this->update_meta( self::TRACK_TYPES, $this->_track_types );
		}
	}

	/**
	 * Turn on or off the tracking for the passed types.
	 * The array should have the key-value pairs:
	 * { tracking mode } => { boolean }
	 *
	 * @since 4.0
	 *
	 * @param array $tracking_types
	 */
	private function set_sub_type_tracking_status( $tracking_types ) {

		foreach ( $tracking_types as $type => $status ) {
			if ( ! is_bool( $status ) ) {
				continue;
			}

			if ( $status ) {
				$this->_track_types[ $type ] = true;
			} elseif ( isset( $this->_track_types[ $type ] ) ) {
				unset( $this->_track_types[ $type ] );
			}
		}

		$res = $this->update_meta( self::TRACK_TYPES, $this->_track_types );

		return $res;
	}

	/**
	 * Create an array with the submitted data to update the tracking types.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_types
	 */
	public function update_submitted_tracking_types( $submitted_types ) {

		$tracking_types = $this->get_sub_types();

		$tracking_to_update = array();
		foreach ( $tracking_types as $type ) {
			$tracking_to_update[ $type ] = in_array( $type, $submitted_types, true );
		}

		$res = $this->set_sub_type_tracking_status( $tracking_to_update );

		return $res;
	}

	/**
	 * Returns settings saved as meta
	 *
	 * @since 2.0
	 * @param string $key
	 * @param string $default json string
	 * @return object|array
	 */
	protected function get_settings_meta( $key, $default = '{}', $as_array = false, $get_cached = true ) {
		$settings_json = $this->get_meta( $key, null, $get_cached );

		if ( $settings_json ) {
			$settings = json_decode( $settings_json, $as_array );

			if ( ! empty( $settings ) ) {
				return $settings;
			}
		}

		return is_string( $default ) ? json_decode( $default, $as_array ) : $default;
	}

	/**
	 * Load the model with the data to preview.
	 *
	 * @since 4.0.0
	 *
	 * @param array $data Preview data.
	 * @return Hustle_Module_Model
	 */
	public function load_preview( $data ) {

		if ( is_null( $this->module_id ) ) {
			return false;
		}

		$properties_to_remove = array( 'module_id', 'module_type', 'module_mode', 'active', 'blog_id' );

		foreach ( $properties_to_remove as $property ) {
			if ( isset( $data[ $property ] ) ) {
				unset( $data[ $property ] );
			}
		}

		$metas = $this->get_module_meta_names();

		foreach ( $metas as $meta ) {

			// Get meta's defaults.
			$method = 'get_' . $meta;
			if ( method_exists( $this, $method ) ) {
				$default = $this->{$method}()->to_array();
			} else {
				$default = array();
			}

			// Merge the passed value with the default.
			if ( isset( $data[ $meta ] ) ) {

				// "Settings" comes as a json encoded strings to allow empty arrays for triggers.
				if ( 'settings' === $meta ) {
					$data[ $meta ] = json_decode( $data[ $meta ], true );
				}
				$new_meta = array_merge( $default, $data[ $meta ] );
			} else {
				$new_meta = $default;
			}

			$this->$meta = (object) $new_meta;
		}

		return $this;
	}

	/**
	 * Load the model with its metas.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Module_Model
	 */
	public function load() {
		if ( ! $this->module_id ) {
			return false;
		}

		$module_metas = $this->get_module_meta_names();

		foreach ( $module_metas as $meta ) {
			$method        = 'get_' . $meta;
			$value         = method_exists( $this, $method ) ? $this->{$method}()->to_array() : array();
			$this->{$meta} = (object) $value;

		}

		return $this;
	}

	/**
	 * Returns the link to the wizard page into the defined tab.
	 *
	 * @since unknown
	 * @since 4.3.0 Moved from Hustle_Module_Decorator to this class.
	 *
	 * @param string $section Slug of the section to go to.
	 * @return string
	 */
	public function get_edit_url( $section = '' ) {
		$url = 'admin.php?page=' . $this->get_wizard_page() . '&id=' . $this->module_id;

		if ( ! empty( $section ) ) {
			$url .= '&section=' . $section;
		}

		return admin_url( $url );
	}

	/**
	 * Get the listing page for this module type.
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_listing_page() {
		return Hustle_Data::get_listing_page_by_module_type( $this->module_type );
	}

	/**
	 * Get wizard page for this module type.
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_wizard_page() {
		return Hustle_Data::get_wizard_page_by_module_type( $this->module_type );
	}

	/**
	 * Gets the shortcode ID of the current module.
	 * It used to retrieve a custom string before 4.2.1. Now we use the module ID.
	 *
	 * @since 4.3.0
	 *
	 * @return int
	 */
	public function get_shortcode_id() {
		return $this->id;
	}

	/**
	 * Decorates current model
	 *
	 * @return Hustle_Module_Decorator
	 */
	public function get_decorated() {
		if ( ! $this->_decorator ) {
			$this->_decorator = $this->get_decorator_instance();
		}

		return $this->_decorator;
	}

	/**
	 * Return whether the module's sub_type is active.
	 *
	 * @since the beginning of time
	 * @since 4.0 method name changed.
	 *
	 * @param string $type Module's display type.
	 * @return boolean
	 */
	public function is_display_type_active( $type ) {
		$settings = $this->get_display()->to_array();

		if ( isset( $settings[ $type . '_enabled' ] ) && in_array( $settings[ $type . '_enabled' ], array( '1', 1, 'true' ), true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Render the module.
	 *
	 * @since 4.0
	 *
	 * @param string $sub_type
	 * @param string $custom_classes
	 * @param bool   $is_preview
	 * @return string
	 */
	public function display( $sub_type = null, $custom_classes = '', $is_preview = false ) {
		if ( ! $this->id ) {
			return;
		}

		$this->load();

		$renderer = $this->get_renderer();
		return $renderer->display( $this, $sub_type, $custom_classes, $is_preview );
	}

	/**
	 * Get the stored settings for the "Visibility" tab.
	 *
	 * @since 4.3.0
	 *
	 * @return Hustle_Popup_Visibility
	 */
	public function get_visibility() {
		return new Hustle_Meta_Base_Visibility( $this->get_settings_meta( self::KEY_VISIBILITY, '{}', true ), $this );
	}

	/**
	 * Get the module's meta values.
	 * Note: it's not including the shortcode id, integrations' settings, nor edit roles.
	 *
	 * @since 4.0
	 * @since 4.0.3 $module_type, $module_mode and $with_display_name params added.
	 *
	 * @param string $module_type
	 * @param string $module_mode
	 * @param bool   $with_display_name
	 *
	 * @return array
	 */
	public function get_module_meta_names( $module_type = '', $module_mode = '', $with_display_name = false ) {

		$module_type = empty( $module_type ) ? $this->module_type : $module_type;

		if ( self::SOCIAL_SHARING_MODULE !== $module_type ) {
			$module_mode = empty( $module_mode ) ? $this->module_mode : $module_mode;
		}

		if ( ! $with_display_name ) {
			$metas = array( self::KEY_CONTENT, self::KEY_DESIGN, self::KEY_VISIBILITY );

			if ( self::SOCIAL_SHARING_MODULE !== $module_type ) {
				if ( 'optin' === $module_mode ) {
					$metas[] = self::KEY_EMAILS;
					$metas[] = self::KEY_INTEGRATIONS_SETTINGS;
				}

				$metas[] = self::KEY_SETTINGS;
			}

			if ( self::SOCIAL_SHARING_MODULE === $module_type || self::EMBEDDED_MODULE === $module_type ) {
				$metas[] = self::KEY_DISPLAY_OPTIONS;
			}
		} else {
			// 0 Content
			// 1 Emails
			// 2 Integrations
			// 3 Appearance
			// 4 Display Options
			// 5 Visibility
			// 6 Behavior

			$metas    = array();
			$metas[0] = array(
				'name'  => self::KEY_CONTENT,
				'label' => self::SOCIAL_SHARING_MODULE !== $module_type ? __( 'Content', 'hustle' ) : __( 'Services', 'hustle' ),
			);

			$metas[3] = array(
				'name'  => self::KEY_DESIGN,
				'label' => __( 'Appearance', 'hustle' ),
			);

			$metas[5] = array(
				'name'  => self::KEY_VISIBILITY,
				'label' => __( 'Visibility', 'hustle' ),
			);

			if ( self::SOCIAL_SHARING_MODULE !== $module_type ) {
				if ( 'optin' === $module_mode ) {

					$metas[1] = array(
						'name'  => self::KEY_EMAILS,
						'label' => __( 'Emails', 'hustle' ),
					);

					$metas[2] = array(
						'name'  => self::KEY_INTEGRATIONS_SETTINGS,
						'label' => __( 'Integrations', 'hustle' ),
					);
				}

				$metas[6] = array(
					'name'  => self::KEY_SETTINGS,
					'label' => __( 'Behavior', 'hustle' ),
				);
			}

			if ( self::SOCIAL_SHARING_MODULE === $module_type || self::EMBEDDED_MODULE === $module_type ) {

				$metas[4] = array(
					'name'  => self::KEY_DISPLAY_OPTIONS,
					'label' => __( 'Display Options', 'hustle' ),
				);
			}

			// Order and return without the keys.
			ksort( $metas );
			$metas = array_values( $metas );
		}

		return $metas;
	}

	/**
	 * Retrieve the module's metas as an array.
	 *
	 * @since 4.0.1
	 * @return array
	 */
	public function get_module_metas_as_array() {

		$metas_array  = array();
		$module_metas = $this->get_module_meta_names();

		foreach ( $module_metas as $meta ) {
			$method               = 'get_' . $meta;
			$value                = method_exists( $this, $method ) ? $this->{$method}()->to_array() : array();
			$metas_array[ $meta ] = $value;

		}

		return $metas_array;
	}

	/**
	 * Return whether the provided role can edit at least one module.
	 *
	 * @since 4.1.0
	 * @param string $role_slug The slug of the role to be checked.
	 * @return boolean
	 */
	public static function can_role_edit_one_module( $role_slug ) {

		global $wpdb;
		$cap   = 'hustle_edit_module';
		$table = Hustle_Db::modules_meta_table();

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare( "SELECT module_id FROM `{$table}` WHERE `meta_key`='edit_roles' AND meta_value LIKE %s LIMIT 1", '%"' . $role_slug . '"%' );
		return $wpdb->get_var( $query );
	}

	/**
	 * Returns format for optin table
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_format() {
		return array(
			'module_name' => '%s',
			'module_type' => '%s',
			'active'      => '%d',
			'module_mode' => '%s',
		);
	}

	/**
	 * Special save used in migration.
	 * It keeps the passed module id when saving a new module.
	 * It's useful when adding old modules in new tables in MU.
	 *
	 * @since 4.0.0
	 * @return false|int
	 */
	public function save_from_migration() {
		$module_data = get_object_vars( $this );
		$table       = Hustle_Db::modules_table();
		$data        = $this->_sanitize_model_data( $module_data );
		$format      = $this->get_format();
		$format      = array_values( $format );
		/**
		 * Add ID
		 */
		$data['module_id'] = $this->module_id;
		$this->id          = $this->module_id;
		$format[]          = '%d';
		$this->wpdb->insert( $table, $data, $format );
		/**
		 * Action Hustle after migration module
		 *
		 * @since 4.0.0
		 *
		 * @param string $module_type module type
		 * @param array $data module data
		 * @param int $id module id
		 */
		do_action( 'hustle_after_migrate_module', $this->module_type, $module_data, $this->id );

		// Clear cache as well.
		$this->clean_module_cache( 'data' );
		return $this->id;
	}

}