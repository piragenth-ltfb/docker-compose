<?php
/**
 * Footer customizer controls manager.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Footer_Controls_Addon
 */
class Hestia_Footer_Controls extends Hestia_Register_Customizer_Controls {
	/**
	 * Add controls.
	 */
	public function add_controls() {
		$this->add_footer_options_section();
	}

	/**
	 * Add the footer options section.
	 */
	private function add_footer_options_section() {
		$this->add_section(
			new Hestia_Customizer_Section(
				'hestia_footer_content',
				array(
					'title'    => esc_html__( 'Footer Options', 'hestia' ),
					'priority' => 36,
				)
			)
		);
	}
}
