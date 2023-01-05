<?php

/**
 * Methods for the plugin's data deletion.
 *
 * @since 4.0.3
 */
class Hustle_Deletion {

	/**
	 * Reset onboarding notification
	 *
	 * @since 4.0.3
	 */
	public static function hustle_reset_notifications() {
		global $wpdb;
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE `meta_key` = 'hustle_dismissed_notifications'" );
	}

	/**
	 * Delete custom options
	 *
	 * @since 4.0.3
	 */
	public static function hustle_delete_custom_options() {
		delete_option( 'hustle_version' );
		delete_site_option( 'hustle_version' );
		delete_option( 'hustle_color_index' );
		delete_option( 'hustle_database_version' );
		delete_option( 'hustle_unsubscribe_nonces' );
		delete_option( 'hustle_migrations' );
		delete_option( 'hustle_previous_version' );
		delete_option( 'hustle_30_migration_data' );
		delete_option( 'hustle_settings' );
		delete_option( 'hustle_ss_refresh_counters' );
		delete_option( 'wpoi-county-id-map' );
		delete_option( 'hustle_custom_nonce' );
		delete_option( 'hustle_activated_flag' );
		delete_option( 'hustle_new_welcome_notice_dismissed' );
		delete_option( 'hustle_popup_migrated' );
		delete_option( 'hustle_global_unsubscription_settings' );
		delete_option( 'hustle_global_email_settings' );
		delete_option( 'hustle_database_version' );
		delete_option( 'widget_hustle_module_widget' );
		delete_option( 'opt_in_database_version' );
		delete_option( 'hustle_custom_palettes' );
		delete_option( 'hustle_notice_stop_support_m2' );
	}

	/**
	 * Delete options created by Packaged Hustle Addons.
	 *
	 * @since 4.0.3
	 * @param array $addons Existing addons as $slug => Hustle_Provider_Abstract instance.
	 */
	public static function hustle_delete_addon_options( $addons = array() ) {
		delete_option( 'hustle_activated_providers' );
		if ( empty( $addons ) ) {
			$addons = array_keys( Hustle_Provider_Utils::get_registered_providers_list() );
		}

		foreach ( $addons as $slug ) {
			delete_option( "hustle_provider_{$slug}_version" );
			delete_option( "hustle_provider_{$slug}_settings" );

			if ( 'constantcontact' === $slug || 'hubspot' === $slug ) {
				delete_option( 'hustle_opt-in-constant_contact-token' );
				delete_option( "hustle_opt-in-{$slug}-token" );
				delete_option( "hustle_{$slug}_referer" );
				delete_option( "hustle_{$slug}_current_page" );

			} elseif ( 'aweber' === $slug ) {
				// Old options.
				delete_option( "{$slug}_access_token" );
				delete_option( "{$slug}_access_secret" );
				delete_option( "{$slug}_aut_code" );
				delete_option( "{$slug}_consumer_secret" );
				delete_option( "{$slug}_consumer_key" );

			}
		}

	}

	/**
	 * Clear modules.
	 *
	 * @since 4.0.3
	 */
	public static function hustle_clear_modules() {
		global $wpdb;

		// Get max module id.
		$max_module_id_query = "SELECT MAX(`module_id`) FROM {$wpdb->prefix}hustle_modules";
		$max_module_id       = (int) $wpdb->get_var( $max_module_id_query ); // phpcs:ignore

		// Get max module meta id.
		$max_module_meta_id_query = "SELECT MAX(`meta_id`) FROM {$wpdb->prefix}hustle_modules_meta";
		$max_module_meta_id       = (int) $wpdb->get_var( $max_module_meta_id_query ); // phpcs:ignore

		// Delete module cache.
		if ( $max_module_id && is_numeric( $max_module_id ) && $max_module_id > 0 ) {
			for ( $i = 1; $i <= $max_module_id; $i ++ ) {
				wp_cache_delete( $i, 'hustle_model_data' );
				wp_cache_delete( $i, 'hustle_module_meta' );
			}
		}

		/**
		 * Hook to reset auto increment on entries reset.
		 * This is discouraged becuase users might run into
		 * cookie conflict.
		 *
		 * @since 4.0.3
		 *
		 * @param boolen
		 */
		$maintain_auto_increment = apply_filters( 'maintain_modules_auto_increment', true );

		if ( $maintain_auto_increment ) {

			// Alter auto increment for cookie compatibility.
			$alter_modules = $wpdb->prepare(
				"ALTER TABLE {$wpdb->prefix}hustle_modules
				AUTO_INCREMENT = %d",
				++$max_module_id
			);
			$alter_meta    = $wpdb->prepare(
				"ALTER TABLE {$wpdb->prefix}hustle_modules_meta
				AUTO_INCREMENT = %d",
				++$max_module_meta_id
			);

			$wpdb->query( "TRUNCATE {$wpdb->prefix}hustle_modules" );
			$wpdb->query( $alter_modules );// phpcs:ignore

			$wpdb->query( "TRUNCATE {$wpdb->prefix}hustle_modules_meta" );
			$wpdb->query( $alter_meta );// phpcs:ignore
		}
	}

	/**
	 * Clear submissions.
	 *
	 * @since 4.0.3
	 */
	public static function hustle_clear_module_submissions() {
		global $wpdb;

		// Delete entry cache.
		$max_entry_id_query = "SELECT MAX(`entry_id`) FROM {$wpdb->prefix}hustle_entries";
		$max_entry_id       = $wpdb->get_var( $max_entry_id_query ); // phpcs:ignore

		// Get entry meta id.
		$max_entry_meta_id_query = "SELECT MAX(`meta_id`) FROM {$wpdb->prefix}hustle_entries_meta";
		$max_entry_meta_id       = $wpdb->get_var( $max_entry_meta_id_query ); // phpcs:ignore

		if ( $max_entry_id && is_numeric( $max_entry_id ) && $max_entry_id > 0 ) {
			for ( $i = 1; $i <= $max_entry_id; $i ++ ) {
				wp_cache_delete( $i, 'Hustle_Entry_Model' );
			}
		}

		$wpdb->query( "TRUNCATE {$wpdb->prefix}hustle_entries" );

		$wpdb->query( "TRUNCATE {$wpdb->prefix}hustle_entries_meta" );

		/**
		 * Hook to reset auto increment on entries reset.
		 *
		 * This is discouraged becuase users might run into
		 * cookie conflict.
		 *
		 * @since 4.0.2
		 *
		 * @param boolen
		 */
		$maintain_auto_increment = apply_filters( 'maintain_entries_auto_increment', true );

		if ( $maintain_auto_increment ) {

			// Alter auto increment for cookie compatibility.
			$alter_entries = $wpdb->prepare(
				"ALTER TABLE {$wpdb->prefix}hustle_entries
				AUTO_INCREMENT = %d",
				++$max_entry_id
			);

			$alter_meta = $wpdb->prepare(
				"ALTER TABLE {$wpdb->prefix}hustle_entries_meta
				AUTO_INCREMENT = %d",
				++$max_entry_meta_id
			);
			$wpdb->query( $alter_entries );// phpcs:ignore
			$wpdb->query( $alter_meta );// phpcs:ignore
		}
		wp_cache_delete( 'all_module_types', 'hustle_total_entries' );
		wp_cache_delete( 'global_count', 'hustle_total_entries' );
		wp_cache_delete( 'hustle_icontact_account_id', 'HUSTLE_ICONTACT_API_CACHE' );
		wp_cache_delete( 'hustle_icontact_client_folder_id', 'HUSTLE_ICONTACT_API_CACHE' );
	}

	/**
	 * Clear views.
	 *
	 * @since 4.0.3
	 */
	public static function hustle_clear_module_views() {
		global $wpdb;
		$wpdb->query( "TRUNCATE {$wpdb->prefix}hustle_tracking" );
	}

	/**
	 * Drop custom tables.
	 *
	 * @since 4.0.3
	 */
	public static function hustle_drop_custom_tables() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hustle_entries" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hustle_entries_meta" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hustle_modules" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hustle_modules_meta" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}hustle_tracking" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}optin_meta" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}optins" );
	}

	/**
	 * Removes cronjobs.
	 *
	 * @since 4.3.3
	 */
	public static function clear_cronjobs() {
		// Remove the cron for refreshing Aweber's token.
		wp_clear_scheduled_hook( 'hustle_aweber_token_refresh' );
	}
}