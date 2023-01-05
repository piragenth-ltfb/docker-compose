<?php

if ( ! class_exists( 'Hustle_Slidein_Admin' ) ) :

	/**
	 * Class Hustle_Slidein_Admin
	 */
	class Hustle_Slidein_Admin extends Hustle_Module_Page_Abstract {

		protected function set_page_properties() {

			$this->module_type = Hustle_Module_Model::SLIDEIN_MODULE;

			$this->page_title = Opt_In_Utils::get_module_type_display_name( $this->module_type, true, true );

			$this->page_template_path      = '/admin/slidein/listing';
			$this->page_edit_template_path = '/admin/slidein/wizard';
		}
	}

endif;