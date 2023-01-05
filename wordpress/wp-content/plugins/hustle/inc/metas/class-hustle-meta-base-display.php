<?php
/**
 * File for Hustle_Meta_Base_Display class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Meta_Base_Display is the base class for the "display" meta of modules.
 * This class should handle what's related to the "display" meta.
 * Only used by Embeddeds and Slide-ins.
 *
 * @since 4.2.0
 */
class Hustle_Meta_Base_Display extends Hustle_Meta {

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.2.0
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'inline_enabled'    => '0',
			'inline_position'   => 'below',
			'widget_enabled'    => '1',
			'shortcode_enabled' => '1',
		);
	}
}