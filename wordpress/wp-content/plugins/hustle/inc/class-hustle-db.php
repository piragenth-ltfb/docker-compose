<?php
/**
 * File for the Hustle_Db class.
 *
 * @package Hustle
 * @since unknown
 */

/**
 * File for Hustle_Db class.
 *
 * @package Hustle
 * @since unknwon
 */

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
if ( ! class_exists( 'Hustle_Db' ) ) :

	/**
	 * Class Hustle_Db
	 *
	 * Takes care of all the db initializations
	 */
	class Hustle_Db {

		const DB_VERSION_KEY = 'hustle_database_version';

		const TABLE_HUSTLE_MODULES = 'hustle_modules';

		const TABLE_HUSTLE_MODULES_META = 'hustle_modules_meta';

		/**
		 * Last version where the db was updated.
		 * Change this if you want the 'create tables' function and migration (if conditions are met) to run.
		 *
		 * @since 4.0.0
		 */
		const DB_VERSION = '4.0';

		/**
		 * Store module's entries.
		 *
		 * @since 4.0.0
		 */
		const TABLE_HUSTLE_ENTRIES = 'hustle_entries';

		/**
		 * Store module's entries' meta.
		 *
		 * @since 4.0.0
		 */
		const TABLE_HUSTLE_ENTRIES_META = 'hustle_entries_meta';

		/**
		 * Store module's views and conversions.
		 *
		 * @since 4.0.0
		 */
		const TABLE_HUSTLE_TRACKING = 'hustle_tracking';

		/**
		 * Current tables.
		 *
		 * @since 4.0.0
		 *
		 * @var array
		 */
		private static $tables = array();

		/**
		 * It's true only for the FIRST load for fresh plugin installations
		 *
		 * @var bool
		 */
		public static $is_fresh_install = false;

		/**
		 * Check whether the db is up to date.
		 *
		 * @since 4.0.0
		 * @return boolean
		 */
		public static function is_db_up_to_date() {
			$stored_db_version = get_option( self::DB_VERSION_KEY, false );

			// Check if current version is equal to database version.
			if ( version_compare( $stored_db_version, self::DB_VERSION, '=' ) ) {
				return true;
			}

			if ( false === $stored_db_version ) {
				self::$is_fresh_install = true;
			}

			return false;
		}

		/**
		 * Creates plugin tables
		 *
		 * @since 1.0.0
		 *
		 * @param bool $force Whether to create tables even if the db is up to date.
		 */
		public static function maybe_create_tables( $force = false ) {
			if ( ! $force && self::is_db_up_to_date() ) {
				return;
			}

			$hustle_db = new self();
			foreach ( $hustle_db->get_tables() as $name => $columns ) {
				$sql    = $hustle_db->create_table_sql( $name, $columns );
				$result = dbDelta( $sql );
			}

			update_option( self::DB_VERSION_KEY, self::DB_VERSION );
		}

		/**
		 * Generates CREATE TABLE sql script for provided table name and columns list.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @param string $name The name of a table.
		 * @param array  $columns The array  of columns, indexes, constraints.
		 * @return string The sql script for table creation.
		 */
		private function create_table_sql( $name, array $columns ) {
			global $wpdb;
			$charset = '';
			if ( ! empty( $wpdb->charset ) ) {
				$charset = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
			}
			$collate = '';
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= ' COLLATE ' . $wpdb->collate;
			}
			$name = $wpdb->prefix . $name;
			return sprintf(
				'CREATE TABLE %s (%s%s%s)%s%s',
				$name,
				PHP_EOL,
				implode( ',' . PHP_EOL, $columns ),
				PHP_EOL,
				$charset,
				$collate
			);
		}

		/**
		 * Returns "module_meta" table array with their "Create syntax"
		 *
		 * @since 4.0.0
		 * @since 4.0 'module_mode' added. 'test_mode' removed.
		 *
		 * @return array
		 */
		public static function get_table_modules() {
			return array(
				'module_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
				"blog_id bigint(20) UNSIGNED NOT NULL DEFAULT '0'",
				'module_name varchar(255) NOT NULL',
				'module_type varchar(100) NOT NULL',
				'active tinyint DEFAULT 1',
				'module_mode varchar(100) NOT NULL',
				'PRIMARY KEY  (module_id)',
				'KEY active (active)',
			);
		}

		/**
		 * Returns "module_meta" table array with their "Create syntax"
		 *
		 * @since 4.0.0
		 * @since 4.0 'module_mode' added. 'test_mode' removed.
		 *
		 * @return array
		 */
		public static function get_table_modules_meta() {
			global $wpdb;
			$collate = '';
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= ' COLLATE ' . $wpdb->collate;
			}
			return array(
				'meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
				"module_id bigint(20) UNSIGNED NOT NULL DEFAULT '0'",
				'meta_key varchar(191) ' . $collate . ' DEFAULT NULL',
				'meta_value longtext ' . $collate,
				'PRIMARY KEY  (meta_id)',
				'KEY module_id (module_id)',
				'KEY meta_key (meta_key)',
			);
		}

		/**
		 * Returns db table arrays with their "Create syntax"
		 *
		 * @since 1.0.0
		 * @since 4.0 'module_mode' added. 'test_mode' removed.
		 *
		 * @return array
		 */
		private function get_tables() {
			return array(
				self::TABLE_HUSTLE_MODULES      => self::get_table_modules(),
				self::TABLE_HUSTLE_MODULES_META => self::get_table_modules_meta(),
				self::TABLE_HUSTLE_ENTRIES      => array(
					'entry_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
					'entry_type varchar(191) NOT NULL',
					'module_id bigint(20) UNSIGNED NOT NULL',
					"date_created datetime NOT NULL default '0000-00-00 00:00:00'",
					'PRIMARY KEY (entry_id)',
					'KEY entry_type (entry_type)',
					'KEY entry_module_id (module_id)',
				),
				self::TABLE_HUSTLE_ENTRIES_META => array(
					'meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
					'entry_id bigint(20) UNSIGNED NOT NULL',
					'meta_key varchar(191) DEFAULT NULL',
					'meta_value longtext NULL',
					"date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
					"date_updated datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
					'PRIMARY KEY  (meta_id)',
					'KEY meta_key (meta_key)',
					'KEY meta_entry_id (entry_id ASC )',
					'KEY meta_key_object (entry_id ASC, meta_key ASC)',
				),
				self::TABLE_HUSTLE_TRACKING     => array(
					'tracking_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT',
					'module_id bigint(20) UNSIGNED NOT NULL',
					'page_id bigint(20) UNSIGNED NOT NULL',
					'module_type varchar(100) NOT NULL',
					'action varchar(100) NOT NULL',
					'ip varchar(191) DEFAULT NULL',
					'counter mediumint(8) UNSIGNED NOT NULL DEFAULT 1',
					"date_created datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
					"date_updated datetime NOT NULL DEFAULT '0000-00-00 00:00:00'",
					'PRIMARY KEY  (tracking_id)',
					'KEY tracking_module_id (module_id ASC )',
					'KEY action (action)',
					'KEY tracking_module_object (action ASC, module_id ASC, module_type ASC)',
					'KEY tracking_module_object_ip (module_id ASC, tracking_id ASC, ip ASC)',
					'KEY tracking_date_created (date_created DESC)',
				),
			);
		}

		/**
		 * Add $wpdb prefix to table name
		 *
		 * @since 4.0.0
		 *
		 * @global object $wpdb
		 * @param string $table Table name.
		 * @return string
		 */
		private static function add_prefix( $table ) {
			global $wpdb;
			return $wpdb->prefix . $table;
		}

		/**
		 * Get modules table name
		 *
		 * @since 4.0.0
		 *
		 * @return string
		 */
		public static function modules_table() {
			return self::add_prefix( self::TABLE_HUSTLE_MODULES );
		}

		/**
		 * Get modules meta table name
		 *
		 * @since 4.0
		 * @return string
		 */
		public static function modules_meta_table() {
			return self::add_prefix( self::TABLE_HUSTLE_MODULES_META );
		}

		/**
		 * Get entries table name
		 *
		 * @since 4.0
		 * @return string
		 */
		public static function entries_table() {
			return self::add_prefix( self::TABLE_HUSTLE_ENTRIES );
		}

		/**
		 * Get entries meta table name
		 *
		 * @since 4.0
		 * @return string
		 */
		public static function entries_meta_table() {
			return self::add_prefix( self::TABLE_HUSTLE_ENTRIES_META );
		}

		/**
		 * Get tracking table name
		 *
		 * @since 4.0
		 * @return string
		 */
		public static function tracking_table() {
			return self::add_prefix( self::TABLE_HUSTLE_TRACKING );
		}
	}

endif;