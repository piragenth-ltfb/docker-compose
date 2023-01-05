<?php
/**
 * File for the Hustle_Installer class.
 *
 * @package Hustle
 * @since 4.4.1
 */

/**
 * Class Hustle_Installer
 *
 * @class Hustle_Installer
 */
class Hustle_Installer {

	/**
	 * Class constructor
	 *
	 * @since 4.4.1
	 */
	public function __construct() {

		$current_version = get_option( 'hustle_version' );
		$is_forced       = ! empty( filter_input( INPUT_GET, 'run-migration', FILTER_VALIDATE_INT ) );

		// If it's the first run of this version or the migration is being forced.
		if ( Opt_In::VERSION !== $current_version || $is_forced ) {

			// Using a site option for this prevents the migration from running in subsites.
			if ( is_multisite() ) {
				// Remove the site option introduced in 4.4.1.
				delete_site_option( 'hustle_version' );
			}

			// This isn't a fresh install. Do migrations.
			if ( ! empty( get_option( 'hustle_migrations' ) ) ) {

				// The option 'hustle_version' was introduced in 4.4.1.
				// Re-run migration for multisites.
				if (
					$is_forced ||
					empty( $current_version ) ||
					( is_multisite() && version_compare( Opt_In::VERSION, '4.4.3', '<' ) )
				) {
					$migrator_441 = new Hustle_441_Migration();
					$migrator_441->maybe_migrate();
				}
			}

			update_option( 'hustle_version', Opt_In::VERSION );
		}
	}
}