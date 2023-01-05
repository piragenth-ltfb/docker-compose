<?php

if ( ! class_exists( 'Hustle_Embedded_Admin' ) ) :

	/**
	 * Class Hustle_Embedded_Admin
	 */
	class Hustle_Embedded_Admin extends Hustle_Module_Page_Abstract {

		/**
		 * Set the properties for the listing and wizard pages
		 *
		 * @since 4.0.1
		 */
		protected function set_page_properties() {

			$this->module_type = Hustle_Module_Model::EMBEDDED_MODULE;

			$this->page_title = Opt_In_Utils::get_module_type_display_name( $this->module_type, true, true );

			$this->page_template_path      = '/admin/embedded/listing';
			$this->page_edit_template_path = '/admin/embedded/wizard';
		}
	}

endif;