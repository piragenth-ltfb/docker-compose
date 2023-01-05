<?php
/**
 * Hustle_Provider_Autoload class.
 *
 * @package Hustle
 * @since 4.0.0
 */

/**
 * Class Hustle_Provider_Autoload
 * Handling Autoloader
 */
class Hustle_Provider_Autoload {

	/**
	 * Flag for the providers being initiated.
	 *
	 * @since 4.2.0
	 * @var boolean
	 */
	private static $is_initiated = false;

	/**
	 * Class constructor.
	 *
	 * @since 3.0.5
	 */
	public function __construct() {

	}

	/**
	 * Loads the provicers.
	 *
	 * @since 3.0.5
	 */
	public function load() {
		$pro_providers_dir = Opt_In::$plugin_path . 'inc/providers/';

		// List of providers that should be launch in block editor.
		$block_editor_providers = apply_filters( 'hustle_providers_block_editor', array( 'gutenberg' ) );

		// Load Available Pro Providers.
		$directory = new DirectoryIterator( $pro_providers_dir );
		foreach ( $directory as $d ) {
			if ( $d->isDot() || $d->isFile() || in_array( $d->getBasename(), $block_editor_providers, true ) ) {
				continue;
			}
			// Take directory name as provider name.
			$provider_name = $d->getBasename();
			/**
			 * Add the new Provider.
			 * A valid provider should have `provider_name.php` inside its main directory
			 */
			$provider_initiator = $d->getPathname() . DIRECTORY_SEPARATOR . $provider_name . '.php';
			include_once $provider_initiator;
		}
	}

	/**
	 * Loads the providers only for block editor.
	 *
	 * @since 4.2.0
	 */
	public static function load_block_editor() {
		$pro_providers_dir = Opt_In::$plugin_path . 'inc/providers/';

		/**
		 * Hook to filter list of providers loaded only on block editor.
		 *
		 * @since 4.2.0
		 * @param array
		 */
		$block_editor_providers = apply_filters( 'hustle_providers_block_editor', array( 'gutenberg' ) );

		if ( $block_editor_providers ) {
			foreach ( $block_editor_providers as $provider ) {
				$provider_initiator = $pro_providers_dir . $provider . DIRECTORY_SEPARATOR . $provider . '.php';

				if ( file_exists( $provider_initiator ) ) {
					include_once $provider_initiator;
				}
			}
		}
	}

	/**
	 * Initiate providers.
	 *
	 * @since 3.0.5
	 * @since 4.2.0 Moved from Opt_In to this class.
	 */
	public static function initiate_providers() {

		// We just need this once.
		if ( self::$is_initiated ) {
			return;
		}

		/**
		 * Triggered before registering internal providers
		 *
		 * @since 3.0.5
		 */
		do_action( 'hustle_before_load_providers' );

		$hustle_provider_loader = Hustle_Providers::get_instance();

		// Load packaged providers.
		$autoloader = new Hustle_Provider_Autoload();
		$autoloader->load();

		/**
		 * Triggered after hustle packaged providers were loaded
		 *
		 * @since 3.0.5
		 */
		do_action( 'hustle_providers_loaded' );

		self::$is_initiated = true;
	}

}