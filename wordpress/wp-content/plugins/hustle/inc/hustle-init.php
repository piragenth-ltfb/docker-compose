<?php
/**
 * File for the Hustle_Init class.
 *
 * @package Hustle
 * @since unknown
 */

if ( ! class_exists( 'Hustle_Init' ) ) {

	/**
	 * Class Hustle_Init
	 */
	class Hustle_Init {

		/**
		 * Hustle_Init constructor
		 */
		public function __construct() {

			Hustle_Db::maybe_create_tables();

			new Hustle_Installer();

			// Hustle Migration.
			new Hustle_Migration();

			// Admin.
			if ( is_admin() ) {
				new Hustle_Module_Admin();

				new Hustle_Dashboard_Admin();

				new Hustle_Popup_Admin();

				new Hustle_Slidein_Admin();

				new Hustle_Embedded_Admin();

				new Hustle_SShare_Admin();

				// Global Integrations page.
				new Hustle_Providers_Admin();

				new Hustle_Entries_Admin();

				new Hustle_Settings_Page();

				if ( Opt_In_Utils::_is_free() ) {
					new Hustle_Upsell_Page();
				}
				new Hustle_General_Data_Protection();
			}

			// Front.
			new Hustle_Module_Front();

			// Ajax files.
			if ( wp_doing_ajax() ) {

				// Common actions along the modules on admin side.
				new Hustle_Modules_Common_Admin_Ajax();

				// Actions for the global settings page.
				new Hustle_Settings_Admin_Ajax();

				// Actions for frontend.
				new Hustle_Module_Front_Ajax();
			}
		}
	}

}