<?php

/**
 * Class Hustle_Decorator_Abstract
 */
abstract class Hustle_Decorator_Abstract {

	protected $module;

	/**
	 * Instance of the design meta handler of the module.
	 *
	 * @since 4.3.0
	 * @var Hustle_Meta_Base_Design
	 */
	protected $design_meta;

	protected $design;

	protected $bp_desktop;
	protected $bp_mobile;

	/**
	 * Gets the string with the module's styles.
	 * The meat of the class.
	 *
	 * @since 4.3.0
	 * @return string
	 */
	abstract protected function get_styles();

	public function __construct( Hustle_Model $module ) {
		$this->module = $module;

		$general_settings  = Hustle_Settings_Admin::get_general_settings();
		$mobile_breakpoint = (int) $general_settings['mobile_breakpoint'];

		$this->bp_mobile  = ! empty( $mobile_breakpoint ) ? $mobile_breakpoint : 782;
		$this->bp_desktop = $this->bp_mobile + 1;
	}

	public function get_module_styles( $module_type ) {

		$this->design_meta = $this->module->get_design();
		$this->design      = (array) $this->module->design; // Making it an array to avoic changing all the decorator files.

		$styles = $this->get_styles();

		return $styles;
	}
}