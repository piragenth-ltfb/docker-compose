<?php
/**
 * File for Hustle_441_Migration class.
 *
 * @package Hustle
 * @since 4.4.1
 */

/**
 * Class Hustle_441_Migration.
 *
 * This class handles the migration when going to 4.4.1.
 * The only update here is making the "Triggers" property'
 * from "behavior" an array instead of a string.
 *
 * @since 4.4.1
 */
class Hustle_441_Migration {

	/**
	 * Flag name to mark the migration as "done".
	 *
	 * @since 4.4.1
	 */
	const MIGRATION_FLAG = '441';

	/**
	 * Run the migration if required.
	 *
	 * @since 4.4.1
	 *
	 * @todo To be abstracted in the base migration class.
	 */
	public function maybe_migrate() {
		if ( $this->is_do_migration() ) {
			$this->do_migration();
		}
	}

	/**
	 * Checks whether this migration should be run.
	 *
	 * @since 4.4.1
	 *
	 * @todo To be abstracted in the base migration class.
	 *
	 * @return boolean
	 */
	private function is_do_migration() {
		// Bail out if the migration is being forced but it's not 4.4.1.
		if (
			filter_input( INPUT_GET, 'run-migration', FILTER_VALIDATE_INT ) &&
			self::MIGRATION_FLAG !== filter_input( INPUT_GET, 'run-migration', FILTER_SANITIZE_STRING )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Does the migration to 4.4.1.
	 *
	 * @since 4.4.1
	 */
	public function do_migration() {
		global $wpdb;

		$limit = apply_filters( 'hustle_441_migration_limit', 100 );

		do {
			$offset   = get_option( 'hustle_441_migration_offset', 0 );
			$settings = $this->get_all_hustle_module_behavior_setting( $limit, $offset );

			foreach ( $settings as $meta ) {

				$meta_id = $meta->meta_id;
				$value   = json_decode( $meta->meta_value, true );

				if ( ! empty( $value['triggers'] ) ) {

					// Make the triggers an array to support having multiple.
					if ( ! is_array( $value['triggers']['trigger'] ) ) {

						if ( 'adblock' === $value['triggers']['trigger'] && '0' === $value['triggers']['on_adblock'] ) {
							// If "adblock" was selected and "on_adblock" was disabled, disable "adblock".
							// The old "on_adblock" setting will be unused.
							$value['triggers']['trigger'] = array();

						} else {
							// "Trigger" was a string before. We just need to make it an array now.
							$value['triggers']['trigger'] = array( $value['triggers']['trigger'] );
						}

						// The on/off setting for delaying the trigger on exit intent will be unused.
						if ( isset( $value['triggers']['on_exit_intent_delayed'] ) && '0' === $value['triggers']['on_exit_intent_delayed'] ) {
							$value['triggers']['on_exit_intent_delayed_time'] = '0';
						}

						// The on/off setting for delaying the trigger on adblock will be unused.
						if ( isset( $value['triggers']['enable_on_adblock_delay'] ) && '0' === $value['triggers']['enable_on_adblock_delay'] ) {
							$value['triggers']['on_adblock_delay'] = '0';
						}
					}
				} else {
					// The default value for 'triggers' was an empty string in old versions.
					// Remove that troubling value and let the module grab the new defaults.
					unset( $value['triggers'] );
				}

				// Save the transformed behavior.
				$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
					Hustle_Db::modules_meta_table(),
					array( 'meta_value' => wp_json_encode( $value ) ), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					array( 'meta_id' => $meta_id )
				);

				wp_cache_delete( $meta->module_id, 'hustle_module_meta' );
			}

			$count_settings = count( $settings );
			$offset        += $limit;

			update_option( 'hustle_441_migration_offset', $offset );

		} while ( $count_settings === $limit );

		Hustle_Migration::migration_passed( self::MIGRATION_FLAG );
		delete_option( 'hustle_441_migration_offset' );
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
	private function get_all_hustle_module_behavior_setting( $limit, $offset ) {
		global $wpdb;

		$modules_table = Hustle_Db::modules_meta_table();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$results = $wpdb->get_results(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare( "SELECT meta_id, module_id, meta_value FROM {$modules_table} WHERE meta_key = 'settings' LIMIT %d OFFSET %d", intval( $limit ), intval( $offset ) )
		);

		return $results;
	}
}