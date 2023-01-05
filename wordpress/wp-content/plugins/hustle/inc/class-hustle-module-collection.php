<?php
/**
 * File for Hustle_Module_Collection class.
 *
 * @package Hustle
 * @since 1.0.0
 */

/**
 * Class Hustle_Module_Collection
 */
class Hustle_Module_Collection {

	/**
	 * Reference to $wpdb global var
	 *
	 * @since 1.0.0
	 *
	 * @var wpdb
	 * @access private
	 */
	private $wpdb;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
	 * Returns a new self.
	 *
	 * @since 1.0.0
	 * @return Hustle_Module_Collection
	 */
	public static function instance() {
		return new self();
	}

	/**
	 * Returns array of Hustle_Module_Model.
	 *
	 * @param bool|null $active True for published, false for draft,null for not filtering by their status.
	 * @param array     $args Query arguments.
	 * @param int       $limit Limit for the query.
	 * @return array Hustle_Module_Model[]
	 */
	public function get_all( $active = true, $args = array(), $limit = -1 ) {

		// Types.
		$types = ( isset( $args['module_type'] ) ) ? array( $args['module_type'] ) : array();
		if (
			empty( $types )
			&& isset( $args['filter'] )
			&& isset( $args['filter']['types'] )
			&& ! empty( $args['filter']['types'] )
		) {
			$types = $args['filter']['types'];
		}

		// Set offset.
		$offset = '';
		if ( 0 < $limit && isset( $args['page'] ) && 0 < $args['page'] ) {
			$offset = $this->wpdb->prepare( 'OFFSET %d ', ( $args['page'] - 1 ) * $limit );
		}

		// Set limit.
		$limit = -1 !== $limit ? $this->wpdb->prepare( 'LIMIT %d ', $limit ) : '';

		// Conditions.
		$module_type_condition = '';
		if ( is_array( $types ) && ! empty( $types ) ) {
			$v                      = implode( ',', array_map( array( $this, 'wrap_string' ), $types ) );
			$module_type_condition .= 'AND m.`module_type` IN ( ' . $v . ' ) ';
		}
		$module_type_condition .= ( isset( $args['except_types'] ) ) ? $this->prepare_except_module_types_condition( $args['except_types'] ) : '';

		// Join.
		$join = '';
		if (
			isset( $args['meta'] )
			&& isset( $args['meta']['key'] )
			&& isset( $args['meta']['value'] )
		) {
			switch ( $args['meta']['value'] ) {

				// Handle "NOT EXISTS" option.
				case 'NOT EXISTS':
					$join                  .= 'LEFT JOIN ' . Hustle_Db::modules_meta_table() . ' AS cf ON cf.`module_id` = m.`module_id` ';
					$join                  .= $this->wpdb->prepare(
						'AND cf.`meta_key` = %s ',
						$args['meta']['key']
					);
					$module_type_condition .= 'AND cf.`meta_value` IS NULL ';
					break;
				default:
					$join .= 'JOIN ' . Hustle_Db::modules_meta_table() . ' AS cf ON cf.`module_id` = m.`module_id` ';
					$join .= $this->wpdb->prepare(
						'AND cf.`meta_key` = %s AND cf.`meta_value` = %s ',
						$args['meta']['key'],
						$args['meta']['value']
					);
			}
		}

		// Get filter by 'edit_role'.
		if (
			isset( $args['filter'] )
			&& isset( $args['filter']['role'] )
			&& 'any' !== $args['filter']['role']
		) {
			$filter_role = $args['filter']['role'];
		}

		// Filter modules by edit_roles.
		if ( ! empty( $filter_role ) ) {
			$join .= 'JOIN ' . Hustle_Db::modules_meta_table() . ' AS cf1 ON cf1.`module_id` = m.`module_id` AND cf1.`meta_key` = "edit_roles" ';
			$join .= $this->wpdb->prepare(
				'AND ( cf1.`meta_value` LIKE %s ) ',
				'%"' . $filter_role . '"%'
			);
		}

		// Get filter by 'can_edit'.
		if ( isset( $args['filter']['can_edit'] ) && true === $args['filter']['can_edit'] && ! current_user_can( 'hustle_create' ) ) {

			$user               = wp_get_current_user();
			$current_user_roles = (array) $user->roles;

			$join .= ' JOIN ' . Hustle_Db::modules_meta_table() . ' AS cf1 ON cf1.`module_id` = m.`module_id` AND cf1.`meta_key` = "edit_roles" AND (1=0';
			foreach ( $current_user_roles as $role ) {
				$join .= $this->wpdb->prepare(
					' OR cf1.`meta_value` LIKE %s',
					'%"' . $role . '"%'
				);
			}
			$join .= ')';

		}

		// Search.
		if (
			isset( $args['filter'] )
			&& isset( $args['filter']['q'] )
			&& ! empty( $args['filter']['q'] )
		) {
			$module_type_condition .= $this->wpdb->prepare( 'AND m.`module_name` LIKE %s ', '%' . $args['filter']['q'] . '%' );
		}

		// Build query.
		$query = 'SELECT ';

		// Return count only.
		if ( isset( $args['count_only'] ) && $args['count_only'] ) {
			$limit  = '';
			$offset = '';
			$query .= 'COUNT( distinct m.`module_id` )';
		} else {
			$query .= 'm.`module_id` ';
		}
		$query .= 'FROM ' . Hustle_Db::modules_table() . ' AS m ' . $join . 'WHERE 1 ';

		// Add blog_id for multisite main site, to avoid getting modules from
		// another sites - it is only used before migration is done.
		$is_multiste = is_multisite();
		if ( $is_multiste ) {
			$main_id         = get_main_site_id();
			$current_blog_id = get_current_blog_id();
			if ( $main_id === $current_blog_id ) {
				$query .= $this->wpdb->prepare(
					'AND m.`blog_id` IN ( 0, %d ) ',
					$main_id
				);
			}
		}

		if ( 'any' !== $active && ! is_null( $active ) ) {
			$query .= $this->wpdb->prepare( 'AND m.`active`= %d ', (int) $active );
		}

		// Module mode.
		$module_mode = isset( $args['module_mode'] ) ? $args['module_mode'] : '';
		if ( ! empty( $module_mode ) ) {
			$query .= $this->wpdb->prepare( ' AND m.`module_mode`= %s ', $module_mode );
		}
		$query .= $module_type_condition . ' ';

		// Order.
		if ( ! isset( $args['count_only'] ) || ! $args['count_only'] ) {
			$query .= 'ORDER BY ';
			if (
				isset( $args['filter'] )
				&& isset( $args['filter']['sort'] )
				&& ! empty( $args['filter']['sort'] )
				&& 'module_name' !== $args['filter']['sort']
			) {
				$query .= $this->wpdb->prepare( 'm.%s, ', $args['filter']['sort'] );
			}
			$query .= 'm.`module_name` ';
		}
		$query .= $limit . ' ' . $offset;

		// Return count only.
		if ( isset( $args['count_only'] ) && $args['count_only'] ) {
			return $this->wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}

		$ids = $this->wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// Check is empty.
		if ( empty( $ids ) ) {
			return $ids;
		}

		// Return only ids if it is needed.
		if ( isset( $args['fields'] ) && 'ids' === $args['fields'] ) {
			return $ids;
		}
		return array_map( array( $this, 'return_model_from_id' ), $ids );
	}

	/**
	 * Prepare DB string value.
	 *
	 * @param string $v String to wrap.
	 * @since 4.0.0
	 */
	private function wrap_string( $v ) {
		return $this->wpdb->prepare( '%s', $v );
	}

	/**
	 * Helper for get_all() with pagination and filters.
	 *
	 * @since 4.0.0
	 */
	public function get_all_paginated() {
		$entries_per_page = apply_filters( 'hustle_module_collection_page_size', 10 );
		$page             = intval( filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT ) );
		$filters          = $this->get_filters();
		$total            = $this->get_all(
			null,
			array(
				'count_only' => true,
				'filter'     => $filters,
			)
		);

		$modules = $this->get_all(
			null,
			array(
				'page'   => $page,
				'filter' => $filters,
			),
			$entries_per_page
		);

		$results = array(
			'total'            => $total,
			'entries_per_page' => $entries_per_page,
			'modules'          => $modules,
			'filter'           => $filters,
		);
		return $results;
	}

	/**
	 * Prepares the 'except' query string for excluding module types.
	 *
	 * @since unknown
	 * @since 4.2.0 Visibility changed from public to private.
	 *
	 * @param array $excepts Module types to except.
	 * @return string
	 */
	private function prepare_except_module_types_condition( $excepts ) {
		$except_condition = '';
		foreach ( $excepts as $except ) {
			$except_condition .= " AND `module_type` != '" . $except . "'";
		}
		return $except_condition;
	}

	/**
	 * Returns the module's model by the given ID.
	 *
	 * @since unknown
	 * @param int $id Module ID.
	 * @return Hustle_Module_Model|WP_Error
	 */
	public function return_model_from_id( $id ) {
		return Hustle_Model::get_module( $id );
	}

	/**
	 * Includes Embed and Social Sharing module.
	 *
	 * @since unknwon
	 * @param array $module_types Module types.
	 * @return array
	 */
	public function get_embed_id_names( $module_types = array() ) {
		$types_query = '';
		if ( ! empty( $module_types ) ) {
			$types_query_array = array();
			foreach ( $module_types as $type ) {
				$type_string = $this->wpdb->prepare( '`module_type` = %s', $type );
				array_push( $types_query_array, $type_string );
			}
			$types_pre_query = implode( ' OR ', $types_query_array );
			$types_query     = ' AND ( ' . $types_pre_query . ' )';
		}
		$query = $this->wpdb->prepare( 'SELECT `module_id`, `module_name` FROM ' . Hustle_Db::modules_table() . ' WHERE `active`=%d' . $types_query, 1 ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $this->wpdb->get_results( $query, OBJECT ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Get the modules from 3.x for migration.
	 *
	 * @since 4.0.0
	 * @param int $blog_id Blog ID to return the modules from.
	 * @return array
	 */
	public function get_hustle_30_modules( $blog_id = null ) {
		$db                     = $this->wpdb;
		$sql                    = $db->prepare(
			'SELECT * FROM `' . $db->base_prefix . 'hustle_modules` WHERE `blog_id` > 0 AND `blog_id` = %d',
			get_current_blog_id()
		);
		$modules_result         = $db->get_results( $sql );
		$prepared_array         = array(
			'popup_view',
			'popup_conversion',
			'slidein_view',
			'slidein_conversion',
			'after_content_view',
			'shortcode_view',
			'floating_social_view',
			'floating_social_conversion',
			'widget_view',
			'after_content_conversion',
			'shortcode_conversion',
			'widget_conversion',
			'subscription',
		);
		$meta_keys_placeholders = implode( ', ', array_fill( 0, count( $prepared_array ), '%s' ) );
		$modules                = array();
		foreach ( $modules_result as $row ) {
			$module_id             = $row->module_id;
			$modules[ $module_id ] = $row;
			// Getting the modules with the regular methods shouldn't work in MU
			// because we use $db->prefix in 4.0, instead of $db->base_prefix as in 3.x.
			$sql         = $db->prepare(
				"SELECT `meta_value`, `meta_key`
				FROM `{$db->base_prefix}hustle_modules_meta`
				WHERE `module_id` = %d",
				$module_id
			);
			$sql        .= $db->prepare( " AND `meta_key` NOT IN ({$meta_keys_placeholders})", $prepared_array );
			$meta_result = $db->get_results( $sql );
			$meta        = array();
			foreach ( $meta_result as $row ) {
				if ( 'shortcode_id' !== $row->meta_key ) {
					$meta[ $row->meta_key ] = json_decode( $row->meta_value, true ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				} else {
					$meta[ $row->meta_key ] = $row->meta_value; // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				}
			}
			$modules[ $module_id ]->meta = $meta;
		}

		return $modules;

	}

	/**
	 * Get the id of the modules that belong to a blog.
	 * Used to migrate tracking data.
	 *
	 * @since 4.0.0
	 * @param int $blog_id Blog ID from which to get the modules.
	 * @return array
	 */
	public function get_30_modules_ids_by_blog( $blog_id ) {
		$modules_table = $this->wpdb->base_prefix . Hustle_Db::TABLE_HUSTLE_MODULES;
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$query = $this->wpdb->prepare( 'SELECT `module_id` FROM ' . $modules_table . ' WHERE `blog_id`=%d ORDER BY `module_id` ASC', $blog_id );
		return $this->wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Helper for filters
	 *
	 * @since 4.0.0
	 */
	private function get_filters() {
		$filters = isset( $_REQUEST['filter'] ) ? $_REQUEST['filter'] : array();
		if ( isset( $filters['types'] ) && is_string( $filters['types'] ) ) {
			$filters['types'] = explode( ',', $filters['types'] );
		}
		$defaults = array(
			'types' => array(),
			'q'     => '',
			'role'  => 'any',
			'sort'  => 'module_name',
		);
		$filters  = wp_parse_args( $filters, $defaults );
		return $filters;
	}

	/**
	 * Get active providers on modules
	 *
	 * @since 4.0.1
	 * @param string $slug Provider's slug.
	 */
	public static function get_active_providers_module( $slug ) {
		global $wpdb;
		$modules_meta_table = Hustle_Db::modules_meta_table();

		// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$query = $wpdb->prepare(
			"SELECT `module_id`
			FROM {$modules_meta_table}
			WHERE `meta_value`
			LIKE %s
			AND `meta_key` = 'integrations_settings'",
			'%' . $slug . '%'
		);
		return $wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		// phpcs:enable
	}

}