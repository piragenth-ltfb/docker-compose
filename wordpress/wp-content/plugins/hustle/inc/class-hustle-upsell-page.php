<?php
/**
 * File for Hustle_Admin_Page_Abstract class.
 *
 * @package Hustle
 * @since 4.3.0
 */

/**
 * Class Hustle_Upsell_Page
 * This class handles the global "Hustle pro" page view for free versions.
 *
 * @since 4.3.0
 */
class Hustle_Upsell_Page extends Hustle_Admin_Page_Abstract {

	/**
	 * Initiates the page's properties
	 *
	 * @since 4.3.0
	 */
	public function init() {

		$this->page = 'hustle_pro';

		$this->page_title = __( 'Hustle Pro', 'hustle' );

		$this->page_menu_title = __( 'Hustle Pro', 'hustle' );

		$this->page_capability = 'hustle_menu';

		$this->page_template_path = 'admin/upsell';
	}

	/**
	 * Get the arguments used when rendering the main page.
	 *
	 * @since 4.3.0
	 * @return array
	 */
	public function get_page_template_args() {
		return array();
	}

}