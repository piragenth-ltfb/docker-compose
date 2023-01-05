<?php

if ( ! class_exists( 'Hustle_Popup_Admin' ) ) :

	/**
	 * Class Hustle_Popup_Admin
	 */
	class Hustle_Popup_Admin extends Hustle_Module_Page_Abstract {

		protected function set_page_properties() {

			$this->module_type = Hustle_Module_Model::POPUP_MODULE;

			$this->page_title = Opt_In_Utils::get_module_type_display_name( $this->module_type, true, true );

			$this->page_template_path      = '/admin/popup/listing';
			$this->page_edit_template_path = '/admin/popup/wizard';
		}
	}

endif;