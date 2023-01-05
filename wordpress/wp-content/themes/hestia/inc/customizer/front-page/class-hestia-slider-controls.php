<?php
/**
 * Slider Controls.
 *
 * @package Hestia
 */

/**
 * Class Hestia_Slider_Controls
 */
class Hestia_Slider_Controls extends Hestia_Big_Title_Controls {

	/**
	 * Initialize the addon.
	 */
	public function init() {
		parent::init();
		add_action( 'customize_register', array( $this, 'remove_control_from_lite' ) );
		add_filter( 'hestia_parallax_layer1_default', array( $this, 'parallax_layer1_default' ) );
		add_filter( 'hestia_parallax_layer2_default', array( $this, 'parallax_layer2_default' ) );
	}

	/**
	 * Remove big title title control that was added via Hestia_Front_Page_Section_Controls_Abstract class.
	 *
	 * @param object $wp_customize Customize object.
	 */
	public function remove_control_from_lite( $wp_customize ) {
		$wp_customize->remove_control( 'hestia_big_title_title' );
	}

	/**
	 * Add background control.
	 * Overwrite parent method to remove the control.
	 */
	public function add_background_image_control() {
	}

	/**
	 * Add button controls.
	 * Overwrite parent method to remove the control.
	 */
	public function add_button_controls() {
	}

	/**
	 * Add content control.
	 * Overwrites the parent function to add repeater control.
	 */
	public function add_content_controls() {
		$description = sprintf(
			/* translators: %s is the Learn more link */
			__( 'More Options Available for big title section in the PRO version. %s', 'hestia' ),
			/* translators: %s is the Learn more label*/
			sprintf(
				'<a class="button button-primary" target="_blank" href="' . tsdk_utmify( 'https://themeisle.com/themes/hestia-pro/upgrade/', 'bigtitlesection' ) . '" style="display: block; clear: both; width: fit-content; margin: 15px 0;">%s</a>',
				__( 'Upgrade to Unlock', 'hestia' )
			)
		);
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_big_title_upsell_notice',
				array(
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'section'     => 'hestia_big_title',
					'description' => $description . '<hr style="margin-left: 0px; border-bottom: none;">',
					'priority'    => 1,
					'type'        => 'hidden',
				)
			)
		);
		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_slider_disable_autoplay',
				array(
					'sanitize_callback' => 'hestia_sanitize_checkbox',
				),
				array(
					'type'     => 'checkbox',
					'label'    => esc_html__( 'Disable auto-play', 'hestia' ),
					'section'  => 'hestia_big_title',
					'priority' => 50,
				)
			)
		);

		$slider_default = $this->get_slider_default();

		$this->add_control(
			new Hestia_Customizer_Control(
				'hestia_slider_content',
				array(
					'sanitize_callback' => 'hestia_repeater_sanitize',
					'default'           => json_encode( $slider_default ),
					'transport'         => $this->selective_refresh,
				),
				array(
					'label'                                => esc_html__( 'Slider Content', 'hestia' ),
					'section'                              => 'hestia_big_title',
					'priority'                             => 100,
					'item_name'                            => esc_html__( 'Slide', 'hestia' ),
					'customizer_repeater_image_control'    => true,
					'customizer_repeater_title_control'    => true,
					'customizer_repeater_subtitle_control' => true,
					'customizer_repeater_text_control'     => true,
					'customizer_repeater_link_control'     => true,
					'customizer_repeater_text2_control'    => true,
					'customizer_repeater_link2_control'    => true,
					'customizer_repeater_color_control'    => true,
					'customizer_repeater_color2_control'   => true,
				),
				'Hestia_Repeater',
				array(
					'selector'        => '.carousel-inner',
					'settings'        => 'hestia_slider_content',
					'render_callback' => array( $this, 'slider_render_callback' ),
				)
			)
		);
	}


	/**
	 * Change controls from lite version.
	 */
	public function change_controls() {

		$this->change_customizer_object( 'control', 'header_video', 'section', 'hestia_big_title' );
		$this->change_customizer_object( 'control', 'header_video', 'priority', 15 );
		$this->change_customizer_object( 'control', 'external_header_video', 'section', 'hestia_big_title' );
		$this->change_customizer_object( 'control', 'external_header_video', 'priority', 20 );

		$this->change_customizer_object(
			'control',
			'hestia_slider_type',
			'choices',
			array(
				'image'    => esc_html__( 'Image', 'hestia' ),
				'parallax' => esc_html__( 'Parallax', 'hestia' ),
				'video'    => esc_html__( 'Video (PRO)', 'hestia' ),
			)
		);

		$this->change_customizer_object(
			'control',
			'hestia_slider_type',
			'subcontrols',
			array(
				'image'    => array(
					'hestia_slider_content',
				),
				'parallax' => array(
					'hestia_slider_content',
					'hestia_parallax_layer1',
					'hestia_parallax_layer2',
				),
				'video'    => array(
					'hestia_slider_content',
					'header_video',
					'external_header_video',
				),
			)
		);

		$this->change_customizer_object( 'setting', 'header_image', 'transport', 'refresh' );

		$this->change_customizer_object(
			'control',
			'hestia_slider_tabs',
			'controls',
			array(
				'slider' => array(
					'hestia_big_title_hide' => array(),
					'hestia_slider_type'    => array(
						'image'    => array(
							'hestia_slider_content',
						),
						'parallax' => array(
							'hestia_slider_content',
							'hestia_parallax_layer1',
							'hestia_parallax_layer2',
						),
						'video'    => array(
							'hestia_slider_content',
							'header_video',
							'external_header_video',
						),
					),
				),
				'extra'  => array(
					'hestia_slider_alignment'        => array(
						'left'   => array(
							'hestia_big_title_widgets_title',
							'widgets',
						),
						'center' => array(),
						'right'  => array(
							'hestia_big_title_widgets_title',
							'widgets',
						),
					),
					'hestia_slider_disable_autoplay' => array(),
				),

			)
		);

	}

	/**
	 * Add filter for default value of parallax layer 1.
	 *
	 * @return string
	 */
	public function parallax_layer1_default() {
		return get_template_directory_uri() . '/assets/img/parallax_1.jpg';
	}

	/**
	 * Add filter for default value of parallax layer 2.
	 *
	 * @return string
	 */
	public function parallax_layer2_default() {
		return get_template_directory_uri() . '/assets/img/parallax_2.png';
	}

	/**
	 * Selective refresh for slider content.
	 */
	public function slider_render_callback() {
		$slider = new Hestia_Slider_Section();
		echo '<div class="carousel slide" data-ride="carousel">';
		echo '<div class="carousel-inner">';
		echo $slider->render_slider_content();
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Slider render callback.
	 */
	public function alignment_render_callback() {
		$this->slider_render_callback();
	}

	/**
	 * Import lite content to slider
	 *
	 * @return array
	 */
	public function get_slider_default() {
		$default = array(
			array(
				'image_url' => get_template_directory_uri() . '/assets/img/slider1.jpg',
				'title'     => esc_html__( 'Lorem Ipsum', 'hestia' ),
				'subtitle'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
				'text'      => esc_html__( 'Button', 'hestia' ),
				'link'      => '#',
				'id'        => 'customizer_repeater_56d7ea7f40a56',
				'color'     => '#e91e63',
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/img/slider2.jpg',
				'title'     => esc_html__( 'Lorem Ipsum', 'hestia' ),
				'subtitle'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
				'text'      => esc_html__( 'Button', 'hestia' ),
				'link'      => '#',
				'id'        => 'customizer_repeater_56d7ea7f40a57',
				'color'     => '#e91e63',
			),
			array(
				'image_url' => get_template_directory_uri() . '/assets/img/slider3.jpg',
				'title'     => esc_html__( 'Lorem Ipsum', 'hestia' ),
				'subtitle'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'hestia' ),
				'text'      => esc_html__( 'Button', 'hestia' ),
				'link'      => '#',
				'id'        => 'customizer_repeater_56d7ea7f40a58',
				'color'     => '#e91e63',
			),
		);

		$lite_content = get_option( 'theme_mods_hestia' );

		if ( $lite_content ) {

			$hestia_big_title_title       = '';
			$hestia_big_title_text        = '';
			$hestia_big_title_button_text = '';
			$hestia_big_title_button_link = '';
			$hestia_big_title_background  = apply_filters( 'hestia_big_title_background_default', get_template_directory_uri() . '/assets/img/slider1.jpg' );

			if ( array_key_exists( 'hestia_big_title_title', $lite_content ) ) {
				$hestia_big_title_title = $lite_content['hestia_big_title_title'];
			}
			if ( array_key_exists( 'hestia_big_title_text', $lite_content ) ) {
				$hestia_big_title_text = $lite_content['hestia_big_title_text'];
			}
			if ( array_key_exists( 'hestia_big_title_button_text', $lite_content ) ) {
				$hestia_big_title_button_text = $lite_content['hestia_big_title_button_text'];
			}
			if ( array_key_exists( 'hestia_big_title_button_link', $lite_content ) ) {
				$hestia_big_title_button_link = $lite_content['hestia_big_title_button_link'];
			}
			if ( array_key_exists( 'hestia_big_title_background', $lite_content ) ) {
				$hestia_big_title_background = $lite_content['hestia_big_title_background'];
			}
			if ( ! empty( $hestia_big_title_title ) || ! empty( $hestia_big_title_text ) || ! empty( $hestia_big_title_button_text ) || ! empty( $hestia_big_title_button_link ) || ! empty( $hestia_big_title_background ) ) {
				return array(
					array(
						'id'        => 'customizer_repeater_56d7ea7f40a56',
						'title'     => $hestia_big_title_title,
						'subtitle'  => $hestia_big_title_text,
						'text'      => $hestia_big_title_button_text,
						'link'      => $hestia_big_title_button_link,
						'image_url' => $hestia_big_title_background,
					),
				);
			}
		}

		return $default;
	}
}
