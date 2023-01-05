<?php
/**
 * File for Hustle_Meta_Base_Content class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Meta_Base_Content is the base class for the "content" meta of modules.
 * This class should handle what's related to the "content" meta.
 *
 * @since 4.2.0
 */
class Hustle_Meta_Base_Content extends Hustle_Meta {

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.0.0
	 * @since 4.2.0 Moved from Hustle_Popup_Content to this class.
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'module_name'         => '',
			'title'               => '',
			'sub_title'           => '',
			'main_content'        => '',
			'feature_image'       => '',
			'background_image'    => '',
			'show_never_see_link' => '0',
			'never_see_link_text' => __( 'Never see this message again.', 'hustle' ),
			'show_cta'            => '0',
			'cta_label'           => '',
			'cta_url'             => '',
			'cta_target'          => 'blank',
			'cta_two_label'       => '',
			'cta_two_url'         => '',
			'cta_two_target'      => 'blank',
			'cta_helper_show'     => '0',
			'cta_helper_text'     => '',
		);
	}

	/**
	 * Returns whether the module has CTA active.
	 *
	 * @since 4.3.1
	 *
	 * @return boolean
	 */
	public function has_cta() {
		return '1' === $this->data['show_cta'];
	}
}