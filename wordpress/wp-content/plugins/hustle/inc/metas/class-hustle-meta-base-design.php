<?php
/**
 * File for Hustle_Meta_Base_Design class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Module_Base_Design is the base class for the "design" meta of modules.
 * This class should handle what's related to the "design" meta.
 */
class Hustle_Meta_Base_Design extends Hustle_Meta {

	/**
	 * Available font families.
	 *
	 * @since 4.3.0
	 * @var array
	 */
	private static $font_families;

	/**
	 * Path to the folder containing the defaults.
	 *
	 * @since 4.3.0
	 * @var string
	 */
	private $path_to_defaults = 'inc/metas/default-design/';

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.0.2
	 * @return array
	 */
	public function get_defaults() {

		$defaults = array(
			'enable_mobile_settings'                      => '0',

			// ========================================|
			// 1. Layout.

			'form_layout'                                 => 'one',     // Default opt-in layout option.
			'style'                                       => 'minimal', // Default informational layout option.

			// ========================================|
			// 2. Customize Elements.

			'feature_image_position'                      => 'left',
			'feature_image_width'                         => '320',
			'feature_image_width_unit'                    => 'px',
			'feature_image_height'                        => '150',
			'feature_image_height_unit'                   => 'px',
			'feature_image_height_mobile'                 => '150',
			'feature_image_height_unit_mobile'            => 'px',
			'feature_image_fit'                           => 'contain',
			'feature_image_fit_mobile'                    => 'contain',
			'feature_image_horizontal_position'           => 'center',
			'feature_image_horizontal_position_mobile'    => 'center',
			'feature_image_horizontal_value'              => '-100',
			'feature_image_horizontal_value_mobile'       => '-100',
			'feature_image_horizontal_unit'               => 'px',
			'feature_image_horizontal_unit_mobile'        => 'px',
			'feature_image_vertical_position'             => 'center',
			'feature_image_vertical_position_mobile'      => 'center',
			'feature_image_vertical_value_mobile'         => '-100',
			'feature_image_vertical_value'                => '-100',
			'feature_image_vertical_unit'                 => 'px',
			'feature_image_vertical_unit_mobile'          => 'px',

			'background_image_width'                      => '',
			'background_image_width_mobile'               => '',
			'background_image_width_unit'                 => 'px',
			'background_image_width_unit_mobile'          => 'px',
			'background_image_height'                     => '',
			'background_image_height_mobile'              => '',
			'background_image_height_unit'                => 'px',
			'background_image_height_unit_mobile'         => 'px',
			'background_image_fit'                        => 'contain',
			'background_image_fit_mobile'                 => 'contain',
			'background_image_horizontal_position'        => 'center',
			'background_image_horizontal_position_mobile' => 'center',
			'background_image_horizontal_value'           => '-100',
			'background_image_horizontal_value_mobile'    => '-100',
			'background_image_horizontal_unit'            => 'px',
			'background_image_horizontal_unit_mobile'     => 'px',
			'background_image_vertical_position'          => 'center',
			'background_image_vertical_position_mobile'   => 'center',
			'background_image_vertical_value'             => '-100',
			'background_image_vertical_value_mobile'      => '-100',
			'background_image_vertical_unit'              => 'px',
			'background_image_vertical_unit_mobile'       => 'px',
			'background_image_repeat'                     => 'repeat',
			'background_image_repeat_mobile'              => 'repeat',

			'cta_buttons_alignment'                       => 'left',
			'cta_buttons_alignment_mobile'                => 'full',

			// Opt-in Form.
			'optin_form_layout'                           => 'inline',
			'optin_form_layout_mobile'                    => 'stacked',
			'form_fields_icon'                            => 'static',
			'customize_form_fields_proximity'             => '0',
			'customize_form_fields_proximity_mobile'      => '0',
			'form_fields_proximity_unit'                  => 'px',
			'form_fields_proximity_unit_mobile'           => 'px',
			'form_fields_proximity_value'                 => '1',
			'form_fields_proximity_value_mobile'          => '1',

			// ========================================|
			// 3. Typography.
			'customize_typography'                        => '0',
			'customize_typography_mobile'                 => '0',

			'global_font_family'                          => 'custom',
			'global_custom_font_family'                   => 'inherit',

			// ========================================|
			// 4. Advanced.

			// 3.1. Border, Spacing and Shadow.
			'customize_border_shadow_spacing'             => '0',
			'customize_border_shadow_spacing_mobile'      => '0',

			// Use "vanilla" theme.
			'use_vanilla'                                 => '0',

			// Visibility on mobile.
			'feature_image_hide_on_mobile'                => '0',

			// ========================================|
			// 5. COLORS PALETTE                       |
			// ========================================|
			// Colors palette.
			'color_palette'                               => 'gray_slate',

			// Customize the color palette.
			'customize_colors'                            => '0',

			// ========================================|
			// 8. CUSTOM { MODULE } SIZE               |
			// ========================================|
			// Enable custom size.
			'customize_size'                              => '0',
			'customize_size_mobile'                       => '0',

			// Enable custom size » Width (px).
			'custom_width'                                => 600,
			'custom_width_unit'                           => 'px',

			'custom_width_mobile'                         => 600,
			'custom_width_unit_mobile'                    => 'px',

			// Enable custom size » Height (px).
			'custom_height'                               => 300,
			'custom_height_unit'                          => 'px',

			'custom_height_mobile'                        => 300,
			'custom_height_unit_mobile'                   => 'px',

			// ========================================|
			// 9. CUSTOM CSS                           |
			// ========================================|
			// Enable Custom CSS.
			'customize_css'                               => '0',

			// Enable Custom CSS » Editor.
			'custom_css'                                  => '',

		);

		$advanced_desktop_defaults = $this->get_border_spacing_shadow_defaults( 'desktop' );
		$advanced_mobile_defaults  = $this->get_border_spacing_shadow_defaults( 'mobile' );

		$is_optin         = Hustle_Module_Model::OPTIN_MODE === $this->module->module_mode;
		$palette_defaults = Hustle_Palettes_Helper::get_palette_array( 'gray_slate', $is_optin );

		$typography_defaults_desktop = $this->get_typography_defaults( 'desktop' );
		$typography_defaults_mobile  = $this->get_typography_defaults( 'mobile' );

		return $defaults + $palette_defaults + $advanced_desktop_defaults + $advanced_mobile_defaults + $typography_defaults_desktop + $typography_defaults_mobile;
	}

	/**
	 * Gets the name of the available font families.
	 *
	 * @since 4.3.0
	 * @return array
	 */
	public static function get_font_families_names() {
		if ( ! self::$font_families ) {
			$fonts_handler = new Hustle_Custom_Fonts_Helper();

			self::$font_families = $fonts_handler->get_available_font_families();
		}

		return self::$font_families;
	}

	/**
	 * Retrieves the defaults for border, spacing, shadow properties for the given device.
	 *
	 * @since 4.3.0
	 *
	 * @param string $device Device to retrieve the deafults for, mobile|desktop.
	 * @return array
	 */
	public function get_border_spacing_shadow_defaults( $device ) {
		$file = 'desktop' === $device ? 'border-spacing-shadow-desktop' : 'border-spacing-shadow-mobile';
		return $this->get_default_from_file( $file );
	}

	/**
	 * Gets the typography defaults for the given device.
	 *
	 * @since 4.3.0
	 *
	 * @param string $device desktop|mobile.
	 * @return array
	 */
	public function get_typography_defaults( $device ) {
		$file = 'desktop' === $device ? 'typography-desktop' : 'typography-mobile';
		return $this->get_default_from_file( $file );
	}

	/**
	 * Retrieves the array from a default file.
	 *
	 * @since 4.3.0
	 *
	 * @param string $file File name within the design defaults directory.
	 * @return array
	 */
	private function get_default_from_file( $file ) {
		$file_path = Opt_In::$plugin_path . $this->path_to_defaults . $file . '.php';
		$is_optin  = Hustle_Module_Model::OPTIN_MODE === $this->module->module_mode;

		if ( is_file( $file_path ) ) {
			return include $file_path;
		}
		return array();
	}
}