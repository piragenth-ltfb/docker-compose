<?php
/**
 * File for Hustle_SShare_Content class.
 *
 * @package Hustle
 * @since unknown
 */

/**
 * Hustle_SShare_Content is the base class for the "content" meta of Social Sharing modules.
 *
 * @since unknwon
 */
class Hustle_SShare_Content extends Hustle_Meta {

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.4.1
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'social_icons'    => array(),
			'counter_enabled' => '1',
		);
	}

	/**
	 * Retrieves the list of social icons.
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public function get_social_icons() {
		$networks = ! empty( $this->data['social_icons'] ) ? $this->data['social_icons'] : array();
		return $networks;
	}

	/**
	 * Returns whether the module has CTA active.
	 *
	 * @since 4.3.1
	 *
	 * @return boolean
	 */
	public function has_cta() {
		return false;
	}
}