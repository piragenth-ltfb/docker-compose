<?php

/**
 * Entry model.
 * Base model for all entries.
 *
 * @since 4.0
 */
class Hustle_Entry_Model {

	/**
	 * Entry id
	 *
	 * @var int
	 */
	public $entry_id = 0;

	/**
	 * Entry type
	 *
	 * @var string
	 */
	public $entry_type;

	/**
	 * Module id
	 *
	 * @var int
	 */
	public $module_id;

	/**
	 * Date created in sql format 0000-00-00 00:00:00
	 *
	 * @var string
	 */
	public $date_created_sql;

	/**
	 * Date created in sql format D M Y
	 *
	 * @var string
	 */
	public $date_created;

	/**
	 * Time created in sql format D M Y @ H:i A
	 *
	 * @var string
	 */
	public $time_created;

	/**
	 * Meta data
	 *
	 * @var array
	 */
	public $meta_data = array();

	/**
	 * The table name
	 *
	 * @var string
	 */
	protected $table_name;

	/**
	 * The table meta name
	 *
	 * @var string
	 */
	protected $table_meta_name;

	/**
	 * Hold information about connected addons
	 *
	 * @var array
	 */
	private static $connected_addons = array();

	/**
	 * Initialize the Model
	 *
	 * @since 4.0
	 */
	public function __construct( $entry_id = null ) {
		$this->table_name      = Hustle_Db::entries_table();
		$this->table_meta_name = Hustle_Db::entries_meta_table();

		if ( is_numeric( $entry_id ) && $entry_id > 0 ) {
			$this->get( $entry_id );
			// get connected addons
			// if ( ! empty( $this->module_id ) ) {

				// self::get_connected_addons( $this->module_id );
			// }
		}
	}

	/**
	 * Load an entry by its id.
	 * After load, set entry to cache.
	 *
	 * @since 4.0
	 *
	 * @param int $entry_id - the entry id
	 * @return bool|mixed
	 */
	public function get( $entry_id ) {
		global $wpdb;

		$cache_key          = get_class( $this );
		$entry_object_cache = wp_cache_get( $entry_id, $cache_key );

		if ( $entry_object_cache ) {
			$this->entry_id         = $entry_object_cache->entry_id;
			$this->entry_type       = $entry_object_cache->entry_type;
			$this->module_id        = $entry_object_cache->module_id;
			$this->date_created_sql = $entry_object_cache->date_created_sql;
			$this->date_created     = $entry_object_cache->date_created;
			$this->time_created     = $entry_object_cache->time_created;
			$this->meta_data        = $entry_object_cache->meta_data;

			return $entry_object_cache;
		} else {
			$sql   = "SELECT `entry_type`, `module_id`, `date_created` FROM {$this->table_name} WHERE `entry_id` = %d";
			$entry = $wpdb->get_row( $wpdb->prepare( $sql, $entry_id ) ); // WPCS: unprepared SQL ok. false positive
			if ( $entry ) {
				$this->entry_id         = $entry_id;
				$this->entry_type       = $entry->entry_type;
				$this->module_id        = $entry->module_id;
				$this->date_created_sql = $entry->date_created;
				$this->date_created     = date_i18n( 'j M Y', strtotime( $entry->date_created ) );
				$this->time_created     = date_i18n( 'j M Y @ H:i A', strtotime( $entry->date_created ) );
				$this->load_meta();
				// TODO: check if the cache behaves properly when the module's form fields are updated.
				wp_cache_set( $entry_id, $this, $cache_key );
			}
		}
	}

	/**
	 * Set entry fields.
	 *
	 * @since 4.0
	 *
	 * @param array  $meta_array {
	 *                           Array of data to be saved
	 *
	 * @type key - string the meta key
	 * @type value - string the meta value
	 * }
	 * @param string $date_created Created date, default null will be completed.
	 *
	 * @return bool
	 */
	public function set_fields( $meta_array, $date_created = null ) {
		global $wpdb;

		if ( $meta_array && ! is_array( $meta_array ) && ! empty( $meta_array ) ) {
			return false;
		}

		// Set the meta_data values even though entry_id is null for object reference for its usage in the future.
		// entry_id has null value is the outcome of failed to save or local list is disabled.
		if ( ! $this->entry_id ) {

			foreach ( $meta_array as $meta ) {
				if ( isset( $meta['name'] ) && isset( $meta['value'] ) ) {
					$key                     = $meta['name'];
					$value                   = $meta['value'];
					$key                     = wp_unslash( $key );
					$value                   = wp_unslash( $value );
					$this->meta_data[ $key ] = array(
						'id'    => $key,
						'value' => $value,
					);
				}
			}

			return false;
		}

		if ( empty( $date_created ) ) {
			$date_created = Hustle_Time_Helper::get_current_date();
		}

		// clear cache first
		$cache_key = get_class( $this );
		wp_cache_delete( $this->entry_id, $cache_key );
		foreach ( $meta_array as $meta ) {
			if ( isset( $meta['name'] ) && isset( $meta['value'] ) ) {
				$key   = $meta['name'];
				$value = $meta['value'];
				$key   = wp_unslash( $key );
				$value = wp_unslash( $value );
				$value = maybe_serialize( $value );

				$meta_id = $wpdb->insert(
					$this->table_meta_name,
					array(
						'entry_id'     => $this->entry_id,
						'meta_key'     => $key,
						'meta_value'   => $value,
						'date_created' => $date_created,
					)
				);

				// Set meta data for later usage
				if ( $meta_id ) {
					$this->meta_data[ $key ] = array(
						'id'    => $meta_id,
						'value' => is_array( $value ) ? array_map( 'maybe_unserialize', $value ) : maybe_unserialize( $value ),
					);
				}
			}
		}

		return true;
	}

	/**
	 * Load all meta data for entry
	 *
	 * @since 4.0
	 *
	 * @param object|bool $db - the WP_Db object
	 */
	public function load_meta( $db = false ) {
		if ( ! $db ) {
			global $wpdb;
			$db = $wpdb;
		}
		$this->meta_data = array();
		$sql             = "SELECT `meta_id`, `meta_key`, `meta_value` FROM {$this->table_meta_name} WHERE `entry_id` = %d";
		$results         = $db->get_results( $db->prepare( $sql, $this->entry_id ) );
		foreach ( $results as $result ) {
			$this->meta_data[ $result->meta_key ] = array(
				'id'    => $result->meta_id,
				'value' => is_array( $result->meta_value ) ? array_map( 'maybe_unserialize', $result->meta_value ) : maybe_unserialize( $result->meta_value ),
			);
		}
	}

	/**
	 * Get Meta
	 *
	 * @since 4.0
	 *
	 * @param string      $meta_key - the meta key
	 * @param bool|object $default_value - the default value
	 *
	 * @return bool|string
	 */
	public function get_meta( $meta_key, $default_value = false ) {
		if ( ! empty( $this->meta_data ) && isset( $this->meta_data[ $meta_key ] ) ) {
			return $this->meta_data[ $meta_key ]['value'];
		}

		return $default_value;
	}

	/**
	 * Update Meta
	 *
	 * @since 4.0.2
	 *
	 * @param             $meta_id
	 * @param string      $meta_key      - the meta key
	 * @param bool|object $default_value - the default value
	 * @param string      $date_updated
	 * @param string      $date_created
	 *
	 * @return bool|string
	 */
	public function update_meta( $meta_id, $meta_key, $default_value = false, $date_updated = '', $date_created = '' ) {
		global $wpdb;

		$updated_meta = array(
			'entry_id'   => $this->entry_id,
			'meta_key'   => $meta_key,
			'meta_value' => $default_value,
		);

		if ( ! empty( $date_updated ) ) {
			$updated_meta['date_updated'] = $date_updated;
		}

		if ( ! empty( $date_created ) ) {
			$updated_meta['date_created'] = $date_created;
		}

		$wpdb->update(
			$this->table_meta_name,
			$updated_meta,
			array(
				'meta_id' => $meta_id,
			)
		);
		$cache_key = get_class( $this );
		wp_cache_delete( $this->entry_id, $cache_key );
		$this->get( $this->entry_id );
	}

	/**
	 * Save entry
	 *
	 * @since 4.0
	 *
	 * @param string $date_created Created date, default null will be completed.
	 *
	 * @return bool
	 */
	public function save( $date_created = null ) {
		global $wpdb;

		if ( empty( $date_created ) ) {
			$date_created = Hustle_Time_Helper::get_current_date();
		}

		$result = $wpdb->insert(
			$this->table_name,
			array(
				'entry_type'   => $this->entry_type,
				'module_id'    => $this->module_id,
				'date_created' => $date_created,
			)
		);

		if ( ! $result ) {
			return false;
		}
		wp_cache_delete( $this->module_id, 'hustle_total_entries' );
		wp_cache_delete( 'all_module_types', 'hustle_total_entries' );
		wp_cache_delete( $this->entry_type . '_module_type', 'hustle_total_entries' );
		$this->entry_id = (int) $wpdb->insert_id;

		return true;
	}


	/**
	 * Custom Query entries
	 *
	 * @since 4.0
	 *
	 * @param array $args
	 * @param int   $count pass by reference for get count
	 *
	 * @return array Hustle_Entry_Model[]
	 */
	public static function query_entries( $args, &$count ) {
		global $wpdb;

		/**
		 * $args
		 * [
		 *  module_id => X,
		 *  date_created=> array(),
		 *  search = '',
		 *  min_id =>
		 *  max_id =>
		 *  orderby => 'x',
		 *  order => 'DESC',
		 *  per_page => '10'
		 *  offset => 0
		 * ]
		 */

		if ( ! isset( $args['per_page'] ) ) {
			$args['per_page'] = 10;
		}

		if ( ! isset( $args['offset'] ) ) {
			$args['offset'] = 0;
		}

		if ( ! isset( $args['order'] ) ) {
			$args['order'] = 'DESC';
		}

		$entries_table_name      = Hustle_Db::entries_table();
		$entries_meta_table_name = Hustle_Db::entries_meta_table();

		$entries = array();

		// Building where
		$where = 'WHERE 1=1';
		// exclude Addon meta
		$where .= $wpdb->prepare( ' AND metas.meta_key NOT LIKE %s', $wpdb->esc_like( 'hustle_provider_' ) . '%' );

		if ( isset( $args['module_id'] ) ) {
			$where .= $wpdb->prepare( ' AND entries.module_id = %d', $args['module_id'] );
		}

		if ( isset( $args['date_created'] ) ) {
			$date_created = $args['date_created'];
			if ( is_array( $date_created ) && isset( $date_created[0] ) && isset( $date_created[1] ) ) {
				$date_created[1] = $date_created[1] . ' 23:59:00';
				$where          .= $wpdb->prepare( ' AND ( entries.date_created >= %s AND entries.date_created <= %s )', $date_created[0], $date_created[1] );
			}
		}

		if ( isset( $args['search_email'] ) ) {
			$where .= $wpdb->prepare( ' AND metas.meta_value LIKE %s', '%' . $wpdb->esc_like( $args['search_email'] ) . '%' );
		}

		// group
		$group_by = 'GROUP BY entries.entry_id';
		$group_by = apply_filters( 'hustle_query_entries_group_by', $group_by, $args );

		// order by
		$order_by = 'ORDER BY entries.entry_id';
		if ( isset( $args['order_by'] ) ) {
			$order_by = 'ORDER BY ' . $args['order_by']; // unesacaped
		}
		$order_by = apply_filters( 'hustle_query_entries_order_by', $order_by, $args );

		// order (DESC/ASC)
		$order = $args['order'];
		$order = apply_filters( 'hustle_query_entries_order', $order, $args );

		// limit
		$limit = $wpdb->prepare( 'LIMIT %d, %d', $args['offset'], $args['per_page'] );
		$limit = apply_filters( 'hustle_query_entries_limit', $limit, $args );

		// sql count
		$sql_count
			= "SELECT count(DISTINCT entries.entry_id) as total_entries
				FROM
  				{$entries_table_name} AS entries
  				INNER JOIN {$entries_meta_table_name} AS metas
    			ON (entries.entry_id = metas.entry_id)
    			{$where}
				";

		$sql_count = apply_filters( 'hustle_query_entries_sql_count', $sql_count, $args );
		$count     = intval( $wpdb->get_var( $sql_count ) ); // WPCS: unprepared SQL ok. false positive

		if ( $count > 0 ) {
			// sql
			$sql
				= "SELECT entries.entry_id AS entry_id
				FROM
  				{$entries_table_name} AS entries
  				INNER JOIN {$entries_meta_table_name} AS metas
    			ON (entries.entry_id = metas.entry_id)
    			{$where}
    			{$group_by}
    			{$order_by} {$order}
    			{$limit}
    			";

			$sql     = apply_filters( 'hustle_query_entries_sql', $sql, $args );
			$results = $wpdb->get_results( $sql ); // WPCS: unprepared SQL ok. false positive

			foreach ( $results as $result ) {
				$entries[] = new Hustle_Entry_Model( $result->entry_id );
			}
		}

		return $entries;
	}

	/**
	 * Count entries by module
	 *
	 * @since 4.0
	 *
	 * @param int $module_id
	 * @return int - total entries
	 */
	public static function count_entries( $module_id, $db = false ) {

		if ( ! $db ) {
			global $wpdb;
			$db = $wpdb;
		}
		$cache_key     = 'hustle_total_entries';
		$entries_cache = wp_cache_get( $module_id, $cache_key );

		if ( $entries_cache ) {
			return $entries_cache;
		} else {
			$table_name = Hustle_Db::entries_table();
			$sql        = "SELECT count(`entry_id`) FROM {$table_name} WHERE `module_id` = %d";
			$entries    = $db->get_var( $db->prepare( $sql, $module_id ) );
			if ( $entries ) {
				wp_cache_set( $module_id, $entries, $cache_key );

				return $entries;
			}
		}

		return 0;
	}

	/**
	 * Get global count entries
	 *
	 * @since 4.0
	 *
	 * @return int - total entries
	 */
	public static function global_count_entries() {
		global $wpdb;

		$cache_group   = 'hustle_total_entries';
		$cache_key     = 'global_count';
		$entries_cache = wp_cache_get( $cache_key, $cache_group );

		if ( $entries_cache ) {
			$global_count = $entries_cache;
		} else {
			$table_name   = Hustle_Db::entries_table();
			$global_count = (int) $wpdb->get_var( "SELECT count(`entry_id`) FROM {$table_name}" ); // WPCS: unprepared SQL ok. false positive
			wp_cache_set( $cache_key, $global_count, $cache_group );
		}

		return $global_count;
	}

	/**
	 * Ignored fields
	 * Fields not saved nor shown in submissions or export.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public static function ignored_fields() {
		return apply_filters( 'hustle_entry_ignored_fields', array( 'recaptcha', 'submit' ) );
	}

	/**
	 * Get all entries from a module.
	 *
	 * @since 4.0.1
	 *
	 * @param int $module_id
	 * @return Hustle_Entry_Model[]
	 */
	public static function get_entries( $module_id ) {
		global $wpdb;
		$entries    = array();
		$table_name = Hustle_Db::entries_table();
		$sql        = "SELECT `entry_id` FROM {$table_name} WHERE `module_id` = %d ORDER BY `entry_id` DESC";
		$results    = $wpdb->get_results( $wpdb->prepare( $sql, $module_id ) ); // WPCS: unprepared SQL ok. false positive

		if ( ! empty( $results ) ) {
			foreach ( $results as $result ) {
				$entries[] = new Hustle_Entry_Model( $result->entry_id );
			}
		}

		return $entries;
	}

	/**
	 * Delete entries by a string of comma separated entres ids
	 *
	 * @since 4.0
	 *
	 * @param $module_id
	 * @param array     $entries
	 * @param bool|wpdb $db
	 *
	 * @return bool
	 */
	public static function delete_by_entries( $module_id, $entries, $db = false ) {

		// get connected addons
		// self::get_connected_addons( $module_id );

		if ( ! $db ) {
			global $wpdb;
			$db = $wpdb;
		}
		if ( ! $entries && ! empty( $entries ) ) {
			return false;
		}

		$table_name      = Hustle_Db::entries_table();
		$table_meta_name = Hustle_Db::entries_meta_table();

		if ( ! empty( $entries ) && is_array( $entries ) ) {
			foreach ( $entries as $entry_id ) {
				$module_id   = (int) $module_id;
				$entry_id    = (int) $entry_id;
				$entry_model = new Hustle_Entry_Model( $entry_id );
				// self::attach_addons_on_before_delete_entry( $module_id, $entry_model );
			}
		}

		$prepared_placeholders = implode( ', ', array_fill( 0, count( $entries ), '%s' ) );

		/**
		 * Fires just before an entry getting deleted
		 *
		 * @since 4.0
		 */
		do_action_ref_array( 'hustle_before_delete_entries', array( $module_id, $entries ) );

		$sql = $db->prepare( "DELETE FROM {$table_meta_name} WHERE `entry_id` IN ($prepared_placeholders)", $entries );

		$db->query( $sql );

		$sql = $db->prepare( "DELETE FROM {$table_name} WHERE `entry_id` IN ($prepared_placeholders)", $entries );
		$db->query( $sql );

		wp_cache_delete( $module_id, 'hustle_total_entries' );
		wp_cache_delete( 'all_module_types', 'hustle_total_entries' );

		$module_type = Hustle_Model::get_module_type_by_module_id( $module_id );
		if ( ! is_wp_error( $module_type ) ) {
			wp_cache_delete( $module_type . '_module_type', 'hustle_total_entries' );
		}
	}

	/**
	 * Delete by entry.
	 *
	 * @since 4.0
	 *
	 * @param int                                        $module_id
	 * @param int                                        $entry_id
	 * @param bool|object - the WP_Object optional param
	 */
	public static function delete_by_entry( $module_id, $entry_id, $db = false ) {

		if ( ! $db ) {
			global $wpdb;
			$db = $wpdb;
		}

		$table_name      = Hustle_Db::entries_table();
		$table_meta_name = Hustle_Db::entries_meta_table();
		$cache_key       = __CLASS__;

		$module_id = (int) $module_id;
		$entry_id  = (int) $entry_id;

		// do_action_ref_array( 'hustle_before_delete_entry', array( $module_id, $entry_id ) );
		$entry_model = new Hustle_Entry_Model( $entry_id );
		// self::attach_addons_on_before_delete_entry( $module_id, $entry_model );

		$sql = "DELETE FROM {$table_meta_name} WHERE `entry_id` = %d";
		$db->query( $db->prepare( $sql, $entry_id ) );

		$sql = "DELETE FROM {$table_name} WHERE `entry_id` = %d";
		$db->query( $db->prepare( $sql, $entry_id ) );

		wp_cache_delete( $entry_id, $cache_key );
		wp_cache_delete( $module_id, 'hustle_total_entries' );
		wp_cache_delete( 'all_module_types', 'hustle_total_entries' );
		wp_cache_delete( $entry_model->entry_type . '_module_type', 'hustle_total_entries' );

	}

	public static function delete_entries( $module_id ) {
		global $wpdb;

		$entires_table      = Hustle_Db::entries_table();
		$entires_meta_table = Hustle_Db::entries_meta_table();
		$entires            = $wpdb->get_col(
			$wpdb->prepare(
			"SELECT `entry_id` FROM {$entires_table} WHERE `module_id` = %d", //phpcs:ignore
				$module_id
			)
		);
		if ( $entires ) {
			// delete entries meta data
			$wpdb->query(
				"DELETE FROM {$entires_meta_table} WHERE `entry_id` IN ('" . implode( "','", $entires ) . "')" //phpcs:ignore
			);

			// delete entries data
			$wpdb->delete(
				$entires_table,
				array(
					'module_id' => $module_id,
				),
				array(
					'%d',
				)
			);

			wp_cache_delete( $module_id, 'hustle_total_entries' );
			wp_cache_delete( 'all_module_types', 'hustle_total_entries' );

			$available_entry_types = self::available_entry_types();
			foreach ( $available_entry_types as $type ) {
				wp_cache_delete( $type . '_module_type', 'hustle_total_entries' );
			}

			$cache_key = __CLASS__;
			foreach ( $entires as $entry_id ) {
				wp_cache_delete( $entry_id, $cache_key );
			}
		}
	}


	/**
	 * Convert meta value to string.
	 * Useful for displaying metadata without PHP warnings on conversion.
	 *
	 * @since 4.0
	 *
	 * @param      $field_type
	 * @param      $meta_value
	 * @param bool       $allow_html
	 * @param int        $truncate truncate returned string (usefull if display container is limited)
	 *
	 * @return string
	 */
	public static function meta_value_to_string( $field_type, $meta_value, $allow_html = false, $truncate = PHP_INT_MAX ) {
		switch ( $field_type ) {
			case 'email':
				if ( ! empty( $meta_value ) ) {
					$string_value = $meta_value;
					// truncate
					if ( $allow_html ) {
						// make link
						$email = $string_value;
						// truncate
						if ( strlen( $email ) > $truncate ) {
							$email = substr( $email, 0, $truncate ) . '...';
						}
						$string_value = '<a href="mailto:' . $email . '" target="_blank" title="' . __( 'Send Email', 'hustle' ) . '">' . $email . '</a>';
					} else {
						// truncate url
						if ( strlen( $string_value ) > $truncate ) {
							$string_value = substr( $string_value, 0, $truncate ) . '...';
						}
					}
				} else {
					$string_value = '';
				}

				break;
			case 'url':
				if ( ! empty( $meta_value ) ) {
					$string_value = $meta_value;
					// truncate
					if ( $allow_html ) {
						// make link
						$website = $string_value;
						// truncate
						if ( strlen( $website ) > $truncate ) {
							$website = substr( $website, 0, $truncate ) . '...';
						}
						$string_value = '<a href="' . $website . '" target="_blank" title="' . __( 'View Website', 'hustle' ) . '">' . $website . '</a>';
					} else {
						// truncate url
						if ( strlen( $string_value ) > $truncate ) {
							$string_value = substr( $string_value, 0, $truncate ) . '...';
						}
					}
				} else {
					$string_value = '';
				}

				break;
			default:
				// base flattener
				// implode on array
				if ( is_array( $meta_value ) ) {
					$string_value = implode( ', ', $meta_value );
				} else {
					// or juggling to string
					$string_value = (string) $meta_value;
				}
				// truncate
				if ( strlen( $string_value ) > $truncate ) {
					$string_value = substr( $string_value, 0, $truncate ) . '...';
				}
				break;
		}

		return $string_value;
	}

	/**
	 * Get the stored number of entries.
	 *
	 * @since 4.0
	 * @return int
	 */
	public static function get_total_entries_count() {

		global $wpdb;
		$table_name = Hustle_Db::entries_table();

		$sql = "SELECT COUNT(`entry_id`) FROM {$table_name}";

		return $wpdb->get_var( $sql );
	}

	private static function available_entry_types() {
		$available_entry_types = array(
			'popup',
			'slidein',
			'embedded',
			'all',
		);

		return $available_entry_types;
	}

	/**
	 * Get latest entry
	 *
	 * @since 4.0
	 *
	 * @param string $entry_type
	 * @return Hustle_Entry_Model|null
	 */
	public static function get_latest_entry( $entry_type = 'popup' ) {
		$available_entry_types = self::available_entry_types();

		if ( ! in_array( $entry_type, $available_entry_types, true ) ) {
			return null;
		}

		global $wpdb;
		$entry      = null;
		$table_name = Hustle_Db::entries_table();
		if ( 'all' !== $entry_type ) {
			$sql = "SELECT `entry_id` FROM {$table_name} WHERE `entry_type` = %s ORDER BY `date_created` DESC";
			$sql = $wpdb->prepare( $sql, $entry_type ); // WPCS: unprepared SQL ok. false positive
		} else {
			$sql = "SELECT `entry_id` FROM {$table_name} ORDER BY `date_created` DESC";
		}
		$entry_id = $wpdb->get_var( $sql ); // WPCS: unprepared SQL ok. false positive

		if ( ! empty( $entry_id ) ) {
			$entry = new Hustle_Entry_Model( $entry_id );
		}

		return $entry;
	}

	/**
	 * Get Latest Entry by module_id
	 *
	 * @since 4.0
	 *
	 * @param $module_id
	 * @return Hustle_Entry_Model|null
	 */
	public static function get_latest_entry_by_module_id( $module_id ) {

		global $wpdb;
		$entry      = null;
		$table_name = Hustle_Db::entries_table();
		$sql        = "SELECT `entry_id` FROM {$table_name} WHERE `module_id` = %d ORDER BY `date_created` DESC";
		$entry_id   = $wpdb->get_var( $wpdb->prepare( $sql, $module_id ) ); // WPCS: unprepared SQL ok. false positive

		if ( ! empty( $entry_id ) ) {
			$entry = new Hustle_Entry_Model( $entry_id );
		}

		return $entry;
	}

	/**
	 * Get entries newer than $date_created
	 * Previously get_newer_entry_ids
	 *
	 * @since 4.0
	 *
	 * @param $entry_type
	 * @param $date_created
	 *
	 * @return array
	 */
	public static function count_newer_entries_by_module_type( $entry_type, $date_created ) {

		global $wpdb;
		$entry_table_name = Hustle_Db::entries_table();
		$sql              = "SELECT count(`entry_id`) FROM {$entry_table_name} WHERE `entry_type` = %s AND `date_created` > %s";
		$entries          = $wpdb->get_var( $wpdb->prepare( $sql, $entry_type, $date_created ) ); // WPCS: unprepared SQL ok. false positive

		return $entries;
	}

	/**
	 * Check if there's a subscription with this email in this module.
	 *
	 * @since 4.0
	 *
	 * @param int    $module_id
	 * @param string $email
	 * @return bool
	 */
	public static function is_email_subscribed_to_module_id( $module_id, $email ) {
		global $wpdb;

		$entries_table      = Hustle_Db::entries_table();
		$entries_meta_table = Hustle_Db::entries_meta_table();
		$query              =
			"SELECT COUNT(*)
			FROM {$entries_table} e
			INNER JOIN {$entries_meta_table} m
			ON e.entry_id = m.entry_id
			AND e.module_id = %d
			AND m.meta_key = 'email'
			AND m.meta_value = %s";

		$query = $wpdb->prepare( $query, $module_id, $email ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$count = $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$is_subscribed = apply_filters( 'hustle_is_email_in_module_local_list', ! empty( $count ), $module_id, $email );

		return $is_subscribed;
	}

	/**
	 * Get email if there's a subscription with in this module.
	 *
	 * @since 4.0
	 *
	 * @param int    $module_id
	 * @param string $email
	 * @return bool
	 */
	public static function get_email_subscribed_to_module_id( $module_id, $email ) {
		global $wpdb;

		$entries_table      = Hustle_Db::entries_table();
		$entries_meta_table = Hustle_Db::entries_meta_table();
		$query              =
			"SELECT e.entry_id
			FROM {$entries_table} e
			INNER JOIN {$entries_meta_table} m
			ON e.entry_id = m.entry_id
			AND e.module_id = %d
			AND m.meta_key = 'email'
			AND m.meta_value = %s";

		$query = $wpdb->prepare( $query, $module_id, $email ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$id    = $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$is_subscribed = apply_filters( 'hustle_email_entry_id_in_module_local_list', $id );

		return $is_subscribed;
	}

	/**
	 * Returns an array with the IDs of the modules to which the given email is subscribed to the local list.
	 *
	 * @since 4.0.0
	 *
	 * @param string $email
	 * @return array
	 */
	public function get_modules_id_by_email_in_local_list( $email ) {
		global $wpdb;

		$cache_key = get_class( $this );
		$id        = wp_cache_get( 'local_' . $email, $cache_key );

		if ( empty( $id ) ) {
			$query = $wpdb->prepare(
				'SELECT DISTINCT `module_id` FROM ' . Hustle_Db::entries_table() . ' e INNER JOIN ' . Hustle_Db::entries_meta_table() . " m ON e.entry_id = m.entry_id AND m.meta_key = 'email' AND m.meta_value = %s",
				$email
			);

			$id = $wpdb->get_col( $query );//phpcs:ignore
			wp_cache_set( 'local_' . $email, $id, $cache_key );
		}

		return $id;
	}

	/**
	 * Does the actual email unsubscription.
	 *
	 * @since 4.0.0
	 * @param string $email Email to be unsubscribed.
	 * @param string $nonce Nonce associated with the email for the unsubscription.
	 * @return boolean
	 */
	public function unsubscribe_email( $email, $nonce ) {
		$data = get_option( Hustle_Module_Model::KEY_UNSUBSCRIBE_NONCES, false );
		if ( ! $data ) {
			return false;
		}
		if ( ! isset( $data[ $email ] ) || ! isset( $data[ $email ]['nonce'] ) || ! isset( $data[ $email ]['lists_id'] ) ) {
			return false;
		}
		$email_data = $data[ $email ];
		if ( ! hash_equals( (string) $email_data['nonce'], $nonce ) ) {
			return false;
		}
		// Nonce expired. Remove it. Currently giving 1 day of life span.
		if ( ( time() - (int) $email_data['date_created'] ) > DAY_IN_SECONDS ) {
			unset( $data[ $email ] );
			update_option( Hustle_Module_Model::KEY_UNSUBSCRIBE_NONCES, $data );
			return false;
		}
		// Proceed to unsubscribe
		foreach ( $email_data['lists_id'] as $id ) {
			$unsubscribed = $this->remove_local_subscription_by_email_and_module_id( $email, $id );
		}

		// Clear cache after unsubscription.
		$cache_key = get_class( $this );
		$id        = wp_cache_delete( 'local_' . $email, $cache_key );

		// The email was unsubscribed and the nonce was used. Remove it from the saved list.
		unset( $data[ $email ] );
		update_option( Hustle_Module_Model::KEY_UNSUBSCRIBE_NONCES, $data );
		return true;
	}

	/**
	 * Removes the given email from the local list of the given module id.
	 *
	 * @since 4.0.0
	 *
	 * @param string $email
	 * @param int    $module_id
	 * @return array
	 */
	public function remove_local_subscription_by_email_and_module_id( $email, $module_id ) {
		global $wpdb;
		$query = sprintf(
			'SELECT DISTINCT e.`entry_id` FROM %s e INNER JOIN %s m ON e.`entry_id` = m.`entry_id` AND m.`meta_key` = \'email\' AND m.`meta_value` = %%s WHERE e.`module_id` = %%d',
			Hustle_Db::entries_table(),
			Hustle_Db::entries_meta_table()
		);
		$query = $wpdb->prepare( $query, $email, $module_id ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$entries = $wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( empty( $entries ) ) {
			return;
		}

		self::delete_by_entries( $module_id, $entries );
	}

	/**
	 * Get entries older than $date_created
	 *
	 * @since 4.0.2
	 *
	 * @param $date_created
	 *
	 * @return array
	 */
	public static function get_older_entry_ids( $date_created ) {
		global $wpdb;

		$entries_table = Hustle_Db::entries_table();
		$query         = "SELECT e.entry_id AS entry_id
					FROM {$entries_table} e
					WHERE e.date_created < %s";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare( $query, $date_created );

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_col( $query );
	}

	/**
	 * Get entries by email
	 *
	 * @since 4.0.2
	 *
	 * @param $email
	 *
	 * @return array
	 */
	public static function get_entries_by_email( $email ) {
		global $wpdb;
		$meta_table = Hustle_Db::entries_meta_table();
		$query      = "SELECT m.entry_id AS entry_id
				FROM {$meta_table} m
				WHERE (m.meta_key LIKE %s)
				AND m.meta_value = %s
				GROUP BY m.entry_id";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare( $query, 'email', $email );
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_col( $query );
	}

	/**
	 * Delete all IP
	 *
	 * @since 4.0.0
	 */
	public function delete_all_ips() {
		global $wpdb;
		$wpdb->delete(
			$this->table_meta_name,
			array( 'meta_key' => 'hustle_ip' )
		);
	}

	/**
	 * Delete selected IPs
	 *
	 * @since 4.0.0
	 *
	 * @param array $ips Array of IPs to remove.
	 */
	public function delete_selected_ips( $ips ) {
		if ( empty( $ips ) || ! is_array( $ips ) ) {
			return;
		}
		global $wpdb;
		$in     = array();
		$ranges = array();
		foreach ( $ips as $one ) {
			if ( is_array( $one ) ) {
				$ranges[] = sprintf(
					'( INET_ATON( `meta_value` ) BETWEEN %d AND %d )',
					$one[0],
					$one[1]
				);
			} else {
				$in[] = $one;
			}
		}
		$query = sprintf(
			'DELETE FROM `%s` WHERE `meta_key` = \'hustle_ip\' AND ( ',
			Hustle_Db::entries_meta_table()
		);
		if ( ! empty( $in ) ) {
			$formatted_in_array = array_map(
				function( $a ) {
					return sprintf( '\'%s\'', $a );
				},
				$in
			);
			$query             .= sprintf( '`meta_value` IN ( %s ) ', implode( ', ', $formatted_in_array ) );
			if ( ! empty( $ranges ) ) {
				$query .= 'OR ';
			}
		}
		if ( ! empty( $ranges ) ) {
			$query .= implode( ' OR ', $ranges );
		}
		$query .= ' )';
		$wpdb->query( $query );
	}

}