<?php
/**
 * File for Hustle_Data class.
 *
 * @package Hustle
 * @since unknown
 */

/**
 * Class for.
 */
class Hustle_Data {

	const ADMIN_PAGE                  = 'hustle';
	const DASHBOARD_PAGE              = 'hustle_dashboard';
	const POPUP_LISTING_PAGE          = 'hustle_popup_listing';
	const POPUP_WIZARD_PAGE           = 'hustle_popup';
	const SLIDEIN_LISTING_PAGE        = 'hustle_slidein_listing';
	const SLIDEIN_WIZARD_PAGE         = 'hustle_slidein';
	const EMBEDDED_LISTING_PAGE       = 'hustle_embedded_listing';
	const EMBEDDED_WIZARD_PAGE        = 'hustle_embedded';
	const SOCIAL_SHARING_LISTING_PAGE = 'hustle_sshare_listing';
	const SOCIAL_SHARING_WIZARD_PAGE  = 'hustle_sshare';
	const INTEGRATIONS_PAGE           = 'hustle_integrations';
	const ENTRIES_PAGE                = 'hustle_entries';
	const UPSELL_PRO                  = 'hustle_pro';
	const SETTINGS_PAGE               = 'hustle_settings';

	/**
	 * Get the possible module types.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	public static function get_module_types() {
		return array(
			Hustle_Model::POPUP_MODULE,
			Hustle_Model::SLIDEIN_MODULE,
			Hustle_Model::EMBEDDED_MODULE,
			Hustle_Model::SOCIAL_SHARING_MODULE,
		);
	}

	/**
	 * Get the listing page for the given module type.
	 *
	 * @since 4.3.1
	 *
	 * @param string $module_type Given module type.
	 * @return string
	 */
	public static function get_listing_page_by_module_type( $module_type ) {

		switch ( $module_type ) {
			case Hustle_Module_Model::POPUP_MODULE:
				return self::POPUP_LISTING_PAGE;

			case Hustle_Module_Model::SLIDEIN_MODULE:
				return self::SLIDEIN_LISTING_PAGE;

			case Hustle_Module_Model::EMBEDDED_MODULE:
				return self::EMBEDDED_LISTING_PAGE;

			case Hustle_Module_Model::SOCIAL_SHARING_MODULE:
				return self::SOCIAL_SHARING_LISTING_PAGE;

			default:
				return self::POPUP_LISTING_PAGE;
		}
	}

	/**
	 * Get the wizard page for the given module type.
	 *
	 * @since 4.3.1
	 *
	 * @param string $module_type Given module type.
	 * @return string
	 */
	public static function get_wizard_page_by_module_type( $module_type ) {

		switch ( $module_type ) {
			case Hustle_Module_Model::POPUP_MODULE:
				return self::POPUP_WIZARD_PAGE;

			case Hustle_Module_Model::SLIDEIN_MODULE:
				return self::SLIDEIN_WIZARD_PAGE;

			case Hustle_Module_Model::EMBEDDED_MODULE:
				return self::EMBEDDED_WIZARD_PAGE;

			case Hustle_Module_Model::SOCIAL_SHARING_MODULE:
				return self::SOCIAL_SHARING_WIZARD_PAGE;

			default:
				return self::POPUP_WIZARD_PAGE;
		}
	}

	/**
	 * Retrieves a list with all hustle pages names.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	public static function get_hustle_pages() {
		return array(
			self::ADMIN_PAGE,
			self::DASHBOARD_PAGE,
			self::POPUP_LISTING_PAGE,
			self::POPUP_WIZARD_PAGE,
			self::SLIDEIN_LISTING_PAGE,
			self::SLIDEIN_WIZARD_PAGE,
			self::EMBEDDED_LISTING_PAGE,
			self::EMBEDDED_WIZARD_PAGE,
			self::SOCIAL_SHARING_LISTING_PAGE,
			self::SOCIAL_SHARING_WIZARD_PAGE,
			self::INTEGRATIONS_PAGE,
			self::ENTRIES_PAGE,
			self::UPSELL_PRO,
			self::SETTINGS_PAGE,
		);
	}

	/**
	 * Check whether a new module of this type can be created based on Free limits.
	 * If it's free and there's already 3 modules of this type, then it's a nope.
	 *
	 * @since 4.3.1
	 *
	 * @param string $module_type Module type to check the limits for.
	 * @return boolean
	 */
	public static function was_free_limit_reached( $module_type ) {

		// If it's Pro, the sky's the limit.
		if ( ! Opt_In_Utils::_is_free() ) {
			return false;
		}

		// Check the Module's type is valid.
		if ( ! in_array( $module_type, self::get_module_types(), true ) ) {
			return true;
		}

		$collection_args = array(
			'module_type' => $module_type,
			'count_only'  => true,
		);
		$total_modules   = Hustle_Module_Collection::instance()->get_all( null, $collection_args );

		// If we have less than 3 modules of this type, can create another one.
		if ( $total_modules >= 3 ) {
			return true;
		} else {
			return false;
		}
	}
}