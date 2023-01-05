<?php
/**
 * Hustle_Palettes_Helper class.
 *
 * @package Hustle
 * @since 4.3.0
 */

/**
 * Helper class for handling palettes.
 *
 * @since 4.3.0
 */
class Hustle_Palettes_Helper {

	/**
	 * Get all the default palettes and custom ones slugs and display names.
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public static function get_all_palettes_slug_and_name() {

		$stored_palettes = Hustle_Settings_Admin::get_custom_color_palettes();

		$stored_names = wp_list_pluck( $stored_palettes, 'name', 'slug' );

		$default_palettes = self::get_palettes_names( true );

		return array_merge( $stored_names, $default_palettes );
	}

	/**
	 * Get the names of the existing color palettes.
	 * Watch out if you change these. They are used during 3.x -> 4.x migration.
	 *
	 * @since 4.3.0
	 *
	 * @param bool $get_display_name Whether to includ the display name of the palettes.
	 * @return array
	 */
	public static function get_palettes_names( $get_display_name = false ) {
		$palettes = array(
			'gray_slate' => __( 'Gray Slate', 'hustle' ),
			'coffee'     => __( 'Coffee', 'hustle' ),
			'ectoplasm'  => __( 'Ectoplasm', 'hustle' ),
			'blue'       => __( 'Blue', 'hustle' ),
			'sunrise'    => __( 'Sunrise', 'hustle' ),
			'midnight'   => __( 'Midnight', 'hustle' ),
		);

		if ( ! $get_display_name ) {
			return array_keys( $palettes );
		}
		return $palettes;
	}

	/**
	 * Get all palettes, defaults + customized ones.
	 *
	 * @since 4.3.0
	 *
	 * @param bool $is_optin Whether the palette is for an optin module.
	 * @return array
	 */
	public static function get_all_palettes( $is_optin = false ) {

		$default_palettes = self::get_default_palettes( $is_optin );

		$custom_palettes_array = Hustle_Settings_Admin::get_custom_color_palettes();
		$custom_palettes       = array();

		foreach ( $custom_palettes_array as $slug => $data ) {
			// Merge with one of the default palettes in case we introduce a new property.
			$custom_palettes[ $slug ] = array_merge( $default_palettes['gray_slate'], $data['palette'] );
		}

		return array_merge( $default_palettes, $custom_palettes );
	}

	/**
	 * Returns palettes used to color optins.
	 *
	 * @since 4.3.0
	 *
	 * @param bool $is_optin Whether the palette is for an optin module.
	 * @return array
	 */
	private static function get_default_palettes( $is_optin = false ) {

		$default_palettes_slugs = self::get_palettes_names();
		$default_palettes       = array();

		foreach ( $default_palettes_slugs as $slug ) {
			$default_palettes[ $slug ] = self::get_palette_array( $slug, $is_optin );
		}

		return $default_palettes;
	}

	/**
	 * Returns palette array for palette name
	 *
	 * @since 4.3.0
	 *
	 * @param string $palette_name e.g. "gray_slate".
	 * @param bool   $is_optin Whether the palette is for an optin module.
	 * @return array
	 */
	public static function get_palette_array( $palette_name, $is_optin = false ) {

		$palette_data = array();

		// since this is just used for comparision
		// while creating custom palette.
		if ( 'info-module' === $palette_name ) {
			$palette_data = self::get_palette_file( $palette_name, $is_optin );
		}

		// If it's a default palette, get the array from the file.
		if ( in_array( $palette_name, self::get_palettes_names(), true ) ) {
			$palette_data = self::get_palette_file( $palette_name, $is_optin );

		} else {
			// If it's custom, retrieve it from the stored settings.
			$saved_palettes = Hustle_Settings_Admin::get_custom_color_palettes();

			// Check if the palette name still exists.
			if ( isset( $saved_palettes[ $palette_name ] ) ) {
				$palette = $saved_palettes[ $palette_name ];

				// We didn't saved _alt colors in custom palletes until version 4.2.1.
				// So if such value doesn't exist let's use default title and subtitle color.
				if ( ! isset( $palette['palette']['title_color_alt'] ) ) {
					$palette['palette']['title_color_alt'] = $palette['palette']['title_color'];
				}

				if ( ! isset( $palette['palette']['subtitle_color_alt'] ) ) {
					$palette['palette']['subtitle_color_alt'] = $palette['palette']['subtitle_color'];
				}

				// Merge it with a default in case we introduced new settings not present in the stored array.
				$palette_data = array_merge( self::get_palette_file( 'gray_slate', $is_optin ), $palette['palette'] );

			}
		}

		return $palette_data;
	}

	/**
	 * Load a palette array.
	 *
	 * @since 4.3.0
	 *
	 * @param string $name  Palette name = file name.
	 * @param bool   $is_optin Whether the palette is for an optin module.
	 * @return string
	 */
	private static function get_palette_file( $name, $is_optin = false ) {
		$name    = str_replace( '_', '-', $name );
		$file    = Opt_In::$plugin_path . "inc/palettes/{$name}.php";
		$content = array();

		if ( is_file( $file ) ) {
			/* @noinspection PhpIncludeInspection */
			$content = include $file;
		}

		return $content;

	}
}