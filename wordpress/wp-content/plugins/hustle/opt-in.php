<?php
/**
 * Hustle plugin.
 *
 * @link              http://wpmudev.com/projects/hustle/
 * @since             1.0.0
 * @package           Hustle
 *
 * @wordpress-plugin
 * Plugin Name: Hustle Pro
 * Plugin URI: https://wpmudev.com/project/hustle/
 * Description: Start collecting email addresses and quickly grow your mailing list with big bold pop-ups, slide-ins, widgets, or in post opt-in forms.
 * Version: 4.4.4
 * Author: WPMU DEV
 * Author URI: https://wpmudev.com
 * Text Domain: hustle
 * WDP ID: 1107020
 */

// +----------------------------------------------------------------------+
// | Copyright Incsub (http://incsub.com/)                                |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

add_action( 'activated_plugin', 'hustle_activated', 10, 2 );

if ( ! function_exists( 'hustle_activated' ) ) {

	/**
	 * Handles the deactivation of the free version if "pro" is active, and activation flags.
	 *
	 * @since unknown
	 *
	 * @param string $plugin             Path to the plugin file relative to the plugins directory.
	 * @param bool   $network_activation Whether to enable the plugin for all sites in the network or just the current site.
	 */
	function hustle_activated( $plugin, $network_activation ) {

		if ( is_plugin_active( 'hustle/opt-in.php' ) && is_plugin_active( 'wordpress-popup/popover.php' ) ) {

			// deactivate free version.
			deactivate_plugins( 'wordpress-popup/popover.php' );

			if ( 'hustle/opt-in.php' === $plugin ) {
				// Store in database about free version deactivated, in order to show a notice on page load.
				update_site_option( 'hustle_free_deactivated', 1 );
			} elseif ( 'wordpress-popup/popover.php' === $plugin ) {
				// Store in database about free version being activated even pro is already active.
				update_site_option( 'hustle_free_activated', 1 );
			}
		}
	}
}

// Require autoloader.
if ( ! class_exists( 'ComposerAutoloaderInitda98371940d11703c56dee923bbb392f' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

require_once dirname( __FILE__ ) . '/lib/wpmu-lib/core.php';

if ( ! defined( 'HUSTLE_SUI_VERSION' ) ) {
	define( 'HUSTLE_SUI_VERSION', '2.10.7' );
}

if ( ! class_exists( 'Opt_In' ) ) {

	/**
	 * Opt_In class.
	 */
	class Opt_In {

		const VERSION = '4.4.4';

		const VIEWS_FOLDER = 'views';

		/**
		 * Base file.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public static $plugin_base_file;

		/**
		 * Plugin URL.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public static $plugin_url;

		/**
		 * Plugin path.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public static $plugin_path;

		/**
		 * Path to "vendor".
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public static $vendor_path;

		/**
		 * Path to "views" files.
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public static $template_path;

		/**
		 * Array container for the registered providers.
		 *
		 * @since 3.0.5
		 * @var array
		 */
		protected static $registered_providers = array();

		/**
		 * Opt_In constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			self::$plugin_base_file = plugin_basename( __FILE__ );
			self::$plugin_url       = plugin_dir_url( self::$plugin_base_file );
			self::$plugin_path      = trailingslashit( dirname( __FILE__ ) );
			self::$vendor_path      = self::$plugin_path . 'vendor/';
			self::$template_path    = trailingslashit( dirname( __FILE__ ) ) . 'views/';

			$this->load_text_domain();

			// check caps.
			add_action( 'admin_init', array( $this, 'hustle_check_caps' ), 999 );

			new Hustle_Init();
		}

		/**
		 * Returns list of optin providers based on their declared classes that implement Opt_In_Provider_Interface
		 *
		 * @return array
		 */
		public function get_providers() {
			if ( empty( self::$registered_providers ) ) {
				self::$registered_providers = Hustle_Provider_Utils::get_activable_providers_list();
			}
			return self::$registered_providers;
		}

		/**
		 * Loads text domain
		 *
		 * @since 1.0.0
		 */
		public function load_text_domain() {
			load_plugin_textdomain( 'hustle', false, dirname( plugin_basename( self::$plugin_base_file ) ) . '/languages/' );
		}

		/**
		 * Callback function when user migrates from 3x to 4x from ftp.
		 * The activation hook won't run we'd have to check it in init.
		 *
		 * @since 4.0.0
		 */
		public function hustle_check_caps() {
			$admin = get_role( 'administrator' );
			$roles = get_editable_roles();
			if ( ( $admin && ! $admin->has_cap( 'hustle_menu' ) ) || ( ! $admin && ! empty( $roles ) ) ) {
				hustle_activation();
			}
		}
	}

};

if ( ! function_exists( 'hustle_init' ) ) {

	/**
	 * Instantiate Opt_In
	 */
	function hustle_init() {
		new Opt_In();
	}

	add_action( 'plugins_loaded', 'hustle_init' );
}

if ( ! function_exists( 'hustle_activation' ) ) {

	/**
	 * Handle tables creating if needed.
	 *
	 * @since unknown
	 */
	function hustle_activation() {

		if ( ! class_exists( 'Hustle_Db' ) ) {
			require_once trailingslashit( dirname( __FILE__ ) ) . 'inc/hustle-db.php';
		}
		update_option( 'hustle_activated_flag', 1 );

		Hustle_Db::maybe_create_tables( true );

		/**
		 * Add Hustle's custom capabilities.
		 *
		 * @since 4.0.1
		 */
		$hustle_capabilities = array(
			'hustle_menu',
			'hustle_edit_module',
			'hustle_create',
			'hustle_edit_integrations',
			'hustle_access_emails',
			'hustle_edit_settings',
			'hustle_analytics',
		);

		$admin = get_role( 'administrator' );

		if ( $admin ) {
			// If there's an "administrator" role.
			foreach ( $hustle_capabilities as $cap ) {
				$admin->add_cap( $cap );
			}
		} else {
			// If there's no "administrator".
			$roles = get_editable_roles();

			foreach ( $roles as $role_name => $data ) {

				// Add the capabilities to anyone who can manage options. This was the checked capability in 3.x.
				if ( isset( $data['capabilities']['manage_options'] ) && $data['capabilities']['manage_options'] ) {

					$role = get_role( $role_name );
					foreach ( $hustle_capabilities as $cap ) {
						if ( $role ) {
							$role->add_cap( $cap );
						}
					}
				}
			}
		}

	}
}
register_activation_hook( __FILE__, 'hustle_activation' );


if ( ! function_exists( 'hustle_deactivation' ) ) {
	/**
	 * On deactivation hook
	 *
	 * @since 4.2.0
	 */
	function hustle_deactivation() {
		// Remove the cron for data protection cleanup.
		wp_clear_scheduled_hook( 'hustle_general_data_protection_cleanup' );
	}
}

register_deactivation_hook( __FILE__, 'hustle_deactivation' );