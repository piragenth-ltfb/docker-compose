<?php
/**
 * File for Hustle_SShare_Design class.
 *
 * @package Hustle
 * @since 4.0.0
 */

/**
 * Hustle_SShare_Design is the base class for the "design" meta of Social Sharing modules.
 *
 * @since unknwon
 */
class Hustle_SShare_Design extends Hustle_Meta {

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.4.1
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'icon_style'                  => 'squared', // flat|outline|rounded|squared.

			'floating_customize_colors'   => '0',
			'floating_icon_bg_color'      => 'rgba(146, 158, 170, 1)',
			'floating_icon_color'         => 'rgba(255, 255, 255, 1)',
			'floating_bg_color'           => 'rgba(4, 48, 69, 1)',
			'floating_counter_border'     => 'rgba(146, 158, 170, 1)',
			'floating_counter_color'      => 'rgba(255, 255, 255, 1)',
			'floating_animate_icons'      => '0',
			'floating_drop_shadow'        => '0',
			'floating_drop_shadow_x'      => '0',
			'floating_drop_shadow_y'      => '0',
			'floating_drop_shadow_blur'   => '0',
			'floating_drop_shadow_spread' => '0',
			'floating_drop_shadow_color'  => 'rgba(0,0,0,0.2)',
			'floating_inline_count'       => '0',

			'widget_customize_colors'     => '0',
			'widget_icon_bg_color'        => 'rgba(146, 158, 170, 1)',
			'widget_icon_color'           => 'rgba(255, 255, 255, 1)',
			'widget_bg_color'             => 'rgba(146, 158, 170, 1)',
			'widget_animate_icons'        => '0',
			'widget_drop_shadow'          => '0',
			'widget_drop_shadow_x'        => '0',
			'widget_drop_shadow_y'        => '0',
			'widget_drop_shadow_blur'     => '0',
			'widget_drop_shadow_spread'   => '0',
			'widget_drop_shadow_color'    => 'rgba(0,0,0,0.2)',
			'widget_inline_count'         => '0',
			'widget_counter_border'       => 'rgba(146, 158, 170, 1)',
			'widget_counter_color'        => 'rgba(255, 255, 255, 1)',
		);
	}
}