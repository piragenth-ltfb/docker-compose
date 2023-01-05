<?php
/**
 * File for Hustle_410_Migration class.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Class Hustle_410_Migration.
 *
 * This class handles the migration when going from 4.0.x to 4.1.x
 * We adjusted the modules' visibility conditions, and we need the
 * conditions from 4.0.x to remain compatible to how we're handling them from 4.1.x on.
 * Note this won't make the modules behave as they used to. This just makes the conditions that
 * changed, such as Source of Arrival, Posts/Pages/Tags/Categories all/none options, and so on,
 * compatible with their expected values in 4.1.x.
 *
 * @since 4.1.0
 */
class Hustle_410_Migration {

	/**
	 * Flag name to mark the migration as "done".
	 *
	 * @since 4.1.0
	 */
	const MIGRATION_FLAG = 'hustle_40_migrated';

	/**
	 * Meta key for the visibility backup.
	 *
	 * @since 4.1.0
	 */
	const VISIBILITY_BACKUP_META = 'visibility_backup_40x';

	/**
	 * Instance of the wpdb class.
	 *
	 * @since 4.1.0
	 * @var object
	 */
	private $wpdb;

	/**
	 * Array of metas to be inserted.
	 *
	 * @since 4.1.0
	 * @var array
	 */
	private $backup_metas = array();

	/**
	 * Hustle_401_Migration class constructor.
	 */
	public function __construct() {

		global $wpdb;
		$this->wpdb = $wpdb;

		if ( $this->is_migrating() ) {
			add_action( 'init', array( $this, 'do_migration' ) );
		}
	}

	/**
	 * Checks whether we should run da migration.
	 *
	 * @since 4.1.0
	 *
	 * @return bool
	 */
	private function is_migrating() {

		// If migration is being forced, do it.
		if ( filter_input( INPUT_GET, 'run_41_migration', FILTER_VALIDATE_BOOLEAN ) ) {
			return true;
		}

		// If migration was already done, skip.
		if ( Hustle_Migration::is_migrated( self::MIGRATION_FLAG ) ) {
			return false;
		}

		return ! self::is_fresh();
	}

	/**
	 * Checks if it's a fresh 4.1 installation or not
	 *
	 * @since 4.1.0
	 *
	 * @return bool
	 */
	private static function is_fresh() {
		$is_fresh = Hustle_Db::$is_fresh_install;

		if ( $is_fresh ) {
			Hustle_Migration::migration_passed( self::MIGRATION_FLAG );
		}

		return $is_fresh;
	}

	/**
	 * Does the migration from 4.0.x to 4.1.x.
	 *
	 * @since 4.1.0
	 */
	public function do_migration() {

		$limit = apply_filters( 'hustle_40_migration_limit', 100 );

		// Restore the 4.0.x metas if they exist.
		// Avoid duplicated backup metas and running migration on already migrated settings.
		if ( $this->is_backup_created() ) {
			$this->restore( false );
		}

		do {
			$offset     = get_option( 'hustle_40_migration_offset', 0 );
			$m2_modules = get_option( 'hustle_notice_stop_support_m2', array() );
			$conditions = $this->get_all_hustle_module_conditions( $limit, $offset );

			foreach ( $conditions as $meta ) {

				// Let's keep aside this meta to save them all together at the end.
				$this->queue_meta_to_backup( $meta );

				$meta_id = $meta->meta_id;
				$value   = json_decode( $meta->meta_value, true );

				if ( empty( $value['conditions'] ) || ! is_array( $value['conditions'] ) || 1 > count( $value['conditions'] ) ) {

					$group_id = substr( md5( wp_rand() ), 0, 10 );

					$value['conditions'] = array(
						$group_id => array(
							'filter_type' => 'all',
							'group_id'    => $group_id,
						),
					);
				}

				foreach ( $value['conditions'] as $group_id => $conds ) {

					if ( ! empty( $conds['ms_membership'] ) || ! empty( $conds['ms_membership-n'] ) ) {
						$m2_modules[] = $meta_id;
					}

					unset( $conds['group_id'], $conds['filter_type'] );
					$count_conds = count( $conds );

					if ( ! $count_conds || ! empty( $conds['page_404'] ) ) {

						// Hide on 404 page according old behavior.
						$filter_type            = ! $count_conds ? 'except' : 'only';
						$conds['wp_conditions'] = array(
							'wp_conditions' => array( 'is_404' ),
							'filter_type'   => $filter_type,
						);

						// By default, we start showing modules on 404 page.
						unset( $conds['page_404'] );
					}

					if ( ! empty( $conds['source_of_arrival']['source_external'] )
							&& 'true' === $conds['source_of_arrival']['source_external'] ) {
						$conds['source_of_arrival']['source_direct'] = 'true';
					}

					// Remove 'all' values.
					$post_types = Opt_In_Utils::get_post_types();
					$cpts       = array_keys( $post_types );
					$types      = array_merge( array( 'posts', 'pages', 'tags', 'categories' ), $cpts );

					foreach ( $types as $type ) {
						if (
							! empty( $conds[ $type ][ $type ] ) &&
							in_array( 'all', $conds[ $type ][ $type ], true ) ||
							! empty( $conds[ $type ]['selected_cpts'] ) &&
							in_array( 'all', $conds[ $type ]['selected_cpts'], true )
						) {
							unset( $conds[ $type ][ $type ], $conds[ $type ]['selected_cpts'] );
							$conds[ $type ]['filter_type'] = empty( $conds[ $type ]['filter_type'] ) || 'only' !== $conds[ $type ]['filter_type'] ? 'only' : 'except';
						}
					}

					// Transform condition rules according new logic.
					$and_rules = array(
						'visitor_logged_in_status',
						'visitor_device',
						'from_referrer',
						'source_of_arrival',
						'on_url',
						'visitor_commented',
						'visitor_country',
						'shown_less_than',
					);

					$or_rules = array_diff_key( $conds, array_flip( $and_rules ) );

					// Get "AND" conditions.
					$and_conds = array_intersect_key( $conds, array_flip( $and_rules ) );

					if ( isset( $or_rules['pages'] ) && 1 < count( $or_rules ) ) {
						$this->add_new_group(
							$value,
							array_merge( $and_conds, array( 'pages' => $conds['pages'] ) )
						);
						unset( $conds['pages'] );
						unset( $or_rules['pages'] );
					}

					$post_group = array_intersect_key( $or_rules, array_flip( array( 'posts', 'tags', 'categories' ) ) );
					if ( ! empty( $post_group ) && count( $post_group ) < count( $or_rules ) ) {
						$this->add_new_group( $value, array_merge( $and_conds, $post_group ) );

						unset( $conds['posts'], $conds['tags'], $conds['categories'] );
						unset( $or_rules['posts'], $or_rules['tags'], $or_rules['categories'] );
					}

					foreach ( $or_rules as $name => $args ) {
						if ( 2 > count( $or_rules ) ) {
							break;
						}
						$this->add_new_group(
							$value,
							array_merge( $and_conds, array( $name => $args ) )
						);
						unset( $conds[ $name ] );
						unset( $or_rules[ $name ] );
					}

					// Add new show/hide option.
					$conds['show_or_hide_conditions'] = 'show';
					$conds['filter_type']             = 'all';
					$conds['group_id']                = $group_id;
					$value['conditions'][ $group_id ] = $conds;
				}

				// Save transformed conditions.
				$this->wpdb->update(
					Hustle_Db::modules_meta_table(),
					array( 'meta_value' => wp_json_encode( $value ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					array( 'meta_id' => $meta_id )
				);

				wp_cache_delete( $meta->module_id, 'hustle_module_meta' );
			}

			$count_conditions = count( $conditions );
			$offset          += $limit;

			update_option( 'hustle_40_migration_offset', $offset );
			update_option( 'hustle_notice_stop_support_m2', $m2_modules );

			// Store the backup metas.
			$this->insert_backup_metas();

		} while ( $count_conditions === $limit );

		Hustle_Migration::migration_passed( self::MIGRATION_FLAG );
		delete_option( 'hustle_40_migration_offset' );
	}

	/**
	 * Gets all the stored visibility metas.
	 *
	 * @since 4.1.0
	 *
	 * @param int $limit  Query limit.
	 * @param int $offset Query offset.
	 * @return array
	 */
	private function get_all_hustle_module_conditions( $limit, $offset ) {

		$modules_table = Hustle_Db::modules_meta_table();

		// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$query   = $this->wpdb->prepare( "SELECT meta_id, module_id, meta_value FROM {$modules_table} WHERE meta_key = 'visibility' LIMIT %d OFFSET %d", intval( $limit ), intval( $offset ) );
		$results = $this->wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		return $results;
	}

	/**
	 * Adds a new group within the passed visibility settings.
	 *
	 * @since 4.1.0
	 *
	 * @param array $conditions Reference to the settings to which this group will be added.
	 * @param array $args       Group's properties and conditions.
	 */
	private function add_new_group( &$conditions, $args ) {

		$new_group_id = substr( md5( wp_rand() ), 0, 10 );

		if ( ! isset( $args['filter_type'] ) ) {
			$args['filter_type'] = 'all';
			$args['group_id']    = $new_group_id;
		}

		// Add new show/hide option.
		$args['show_or_hide_conditions'] = 'show';

		$conditions['conditions'][ $new_group_id ] = $args;
	}

	/**
	 * Checks if there's any backup meta already created.
	 *
	 * These should be deleted when rolling back, so if a single one exists,
	 * it's likely that we have them all and creating new ones isn't needed.
	 *
	 * @since 4.1.0
	 *
	 * @return bool
	 */
	public function is_backup_created() {

		$modules_table = Hustle_Db::modules_meta_table();

		$query = $this->wpdb->prepare( "SELECT meta_id FROM {$modules_table} WHERE meta_key = %s LIMIT 1", self::VISIBILITY_BACKUP_META ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$results = $this->wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$is_backup_created = 0 < count( $results );

		return $is_backup_created;
	}

	/**
	 * Adds the old meta to be inserted later on.
	 *
	 * Store the old 4.0.x meta formatted for INSERT in the class property,
	 * to be saved in the db later on. Used when looping through the metas.
	 *
	 * @since 4.1.0
	 *
	 * @param object $meta The original 4.0.x visibility meta retrieved from the db.
	 */
	private function queue_meta_to_backup( $meta ) {

		// Format this ready for IMPORT.
		$row = $this->wpdb->prepare( '(%d, %s, %s)', $meta->module_id, self::VISIBILITY_BACKUP_META, $meta->meta_value );

		$this->backup_metas[] = $row;
	}

	/**
	 * Inserts the backup visibility metas into the db.
	 *
	 * @since 4.1.0
	 */
	private function insert_backup_metas() {

		// Skip if there isn't any meta queued.
		if ( empty( $this->backup_metas ) ) {
			return;
		}

		$modules_meta_table = Hustle_Db::modules_meta_table();
		$backup_values      = implode( ', ', $this->backup_metas );

		// Build the query with the already queued metas.
		$sql = "INSERT INTO {$modules_meta_table} (module_id, meta_key, meta_value) VALUES {$backup_values}";

		// Do the insert.
		$this->wpdb->query( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// Empty the property.
		$this->backup_metas = array();
	}

	/*
	---------- Deactivation stuff ----------
	*/

	/**
	 * Deletes the 4.1.x visibility metas and restore the ones for 4.0.x.
	 *
	 * This will allow admins to find their old visibility settings
	 * when they activate 4.0.x again.
	 *
	 * @since 4.1.0
	 *
	 * @param bool $check_if_exists Skip check for existing backup. False only if already checked.
	 *
	 * @throws Exception When there's no data to restore or the restore failed.
	 * @return bool
	 */
	private function restore( $check_if_exists = true ) {

		try {

			// If there's nothing to restore, abort.
			if ( $check_if_exists && ! $this->is_backup_created() ) {
				throw new Exception( __( "There's no backup to restore.", 'hustle' ) );
			}

			$modules_meta_table = Hustle_Db::modules_meta_table();

			// Get the meta id and module id of the modules with backups.
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$backup_sql    = $this->wpdb->prepare( "SELECT meta_id, module_id FROM {$modules_meta_table} WHERE meta_key = %s", self::VISIBILITY_BACKUP_META );
			$backup_result = $this->wpdb->get_results( $backup_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			$backup_modules_id = array_column( $backup_result, 'module_id' );

			if ( empty( $backup_modules_id ) ) {
				throw new Exception( __( "There's no backup to restore.", 'hustle' ) );
			}

			// Delete the visibility conditions created for 4.1.x migration.
			$modules_id_holder = implode( ', ', array_fill( 0, count( $backup_modules_id ), '%s' ) );

			// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$delete_sql = $this->wpdb->prepare( "DELETE FROM {$modules_meta_table} WHERE module_id IN ($modules_id_holder) AND meta_key = 'visibility'", $backup_modules_id );
			$deleted    = $this->wpdb->query( $delete_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			// Check if the metas were successfully deleted.
			if ( false === $deleted ) {
				throw new Exception( __( "The 4.1.x visibility metas couldn't be deleted.", 'hustle' ) );
			}

			// Restore the visibility conditions created in 4.0.x.
			$backup_metas_id = array_column( $backup_result, 'meta_id' );

			$metas_id_holder = implode( ', ', array_fill( 0, count( $backup_metas_id ), '%s' ) );

			// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$rename_sql = $this->wpdb->prepare( "UPDATE {$modules_meta_table} SET meta_key = 'visibility' WHERE meta_id IN ($metas_id_holder)", $backup_metas_id );
			$renamed    = $this->wpdb->query( $rename_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			// Check if the old metas were successfully restored.
			if ( false === $renamed ) {
				throw new Exception( __( "The 4.0.x visibility metas couldn't be restored.", 'hustle' ) );
			}
		} catch ( Exception $e ) {

			$message = Opt_In_Utils::maybe_log( __METHOD__, $e->getMessage() );
			return false;
		}

		// Clear caches.
		foreach ( $backup_modules_id as $id ) {
			wp_cache_delete( $id, 'hustle_module_meta' );
		}

		// Reset the migration flag so migration runs next time 4.1.x is enabled.
		Hustle_Migration::remove_migration_passed_flag( self::MIGRATION_FLAG );

		return true;
	}
}