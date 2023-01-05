<?php
/**
 * Customizer color controls.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Color_Controls
 */
class Hestia_Color_Controls extends Hestia_Register_Customizer_Controls {
	/**
	 * Add controls
	 */
	public function add_controls() {
		$this->add_control(
			new Hestia_Customizer_Control(
				'accent_color',
				array(
					'default'           => apply_filters( 'hestia_accent_color_default', '#e91e63' ),
					'transport'         => $this->selective_refresh,
					'sanitize_callback' => 'hestia_sanitize_colors',
				),
				array(
					'label'    => esc_html__( 'Accent Color', 'hestia' ),
					'section'  => 'colors',
					'palette'  => apply_filters( 'hestia_accent_color_palette', false ),
					'priority' => 10,
				),
				'Hestia_Customize_Alpha_Color_Control'
			)
		);

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_header_gradient_color',
				array(
					'default'           => apply_filters( 'hestia_header_gradient_default', '#a81d84' ),
					'transport'         => 'postMessage',
					'sanitize_callback' => 'hestia_sanitize_colors',
				),
				array(
					'label'    => esc_html__( 'Header Gradient', 'hestia' ),
					'section'  => 'header_image',
					'priority' => 30,
				),
				'WP_Customize_Color_Control'
			)
		);

		if ( ! class_exists( '\Hestia_Addon_Manager', false ) ) {
			$controls_to_add = array(
				'color_upsell_notice'           => array(
					'setting' => array(),
					'control' => array(
						'label'       => esc_html__( 'More Color Options', 'hestia' ),
						'description' => esc_html__( 'Extend the color options with Hestia PRO.  You can preview the extra color options below.', 'hestia' ),
						'section'     => 'colors',
						'priority'    => 15,
					),
				),
				'secondary_color'               => array(
					'setting' => array(
						'default'           => '#2d3359',
						'transport'         => $this->selective_refresh,
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Secondary Color', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 20,
						'readonly'     => true,
					),
				),
				'body_color'                    => array(
					'setting' => array(
						'default'           => '#999999',
						'transport'         => $this->selective_refresh,
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Body Text Color', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 30,
						'readonly'     => true,
					),
				),
				'header_overlay_color'          => array(
					'setting' => array(
						'default'           => apply_filters( 'hestia_overlay_color_default', 'rgba(0,0,0,0.5)' ),
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Header Overlay Color & Opacity', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 35,
						'readonly'     => true,
					),
				),
				'header_text_color'             => array(
					'setting' => array(
						'default'           => '#fff',
						'transport'         => $this->selective_refresh,
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Header / Slider Text Color', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 40,
						'readonly'     => true,
					),
				),
				'navbar_background_color'       => array(
					'setting' => array(
						'default'           => '#fff',
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Navbar Background Color', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 45,
						'readonly'     => true,
					),
				),
				'navbar_text_color'             => array(
					'setting' => array(
						'default'           => '#555',
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Navbar Text Color', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 50,
						'readonly'     => true,
					),
				),
				'navbar_text_color_hover'       => array(
					'setting' => array(
						'default'           => '#e91e63',
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Navbar Text Color on Hover', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 55,
						'readonly'     => true,
					),
				),
				'navbar_transparent_text_color' => array(
					'setting' => array(
						'default'           => '#fff',
						'sanitize_callback' => 'hestia_sanitize_colors',
					),
					'control' => array(
						'label'        => esc_html__( 'Transparent Navbar Text Color', 'hestia' ),
						'section'      => 'colors',
						'show_opacity' => true,
						'palette'      => false,
						'priority'     => 60,
						'readonly'     => true,
					),
				),
			);

			foreach ( $controls_to_add as $control_id => $settings ) {
				$this->add_control(
					new Hestia_Customizer_Control(
						$control_id,
						$settings['setting'],
						$settings['control'],
						'Hestia_Customize_Alpha_Color_Control'
					)
				);
			}
		}
	}
}
