<?php
/**
 * File for Hustle_Decorator_Non_Sshare class.
 *
 * @package Hustle
 * @since 4.3.0
 */

/**
 * Class Hustle_Decorator_Non_Sshare.
 * Handles the styling for the Non Social Sharing modules. Pop-ups, Slide-ins, and Embeds.
 *
 * @since 4.3.0
 */
class Hustle_Decorator_Non_Sshare extends Hustle_Decorator_Abstract {

	/**
	 * Names of the files for optin modules.
	 * It's in the order to be queued.
	 *
	 * @since 4.3.0
	 * @var array
	 */
	private $optin_files_list = array(
		'module-size-popup',
		'module-size-embed',
		'module-size-slidein',
		'general-01--module-container', // .hustle-popup or .hustle-slidein
		'general-02--main-layout', // .hustle-layout or .hustle-layout-body
		'general-03--layout-header', // .hustle-layout-header
		'general-04--layout-content', // .hustle-layout-content
		'general-05--layout-footer', // .hustle-layout-footer
		'general-06--image-size', // .hustle-image
		'general-07--image-fitting', // .hustle-image
		'general-08--image-position', // .hustle-image
		'general-09--content-wrapper', // .hustle-content
		'general-10--title', // .hustle-title
		'general-11--subtitle', // .hustle-subtitle
		'general-12--content', // .hustle-group-content
		'general-13--cta-container', // .hustle-cta-container
		'general-14--cta-button', // .hustle-button-cta
		'general-15--cta-alignment', // .hustle-content-wrap
		'general-16--nsa-link', // .hustle-nsa-link
		'general-17--close-button', // .hustle-button-close
		'general-18--popup-mask', // .hustle-popup-mask
		'form-01--form-container', // .hustle-layout-form
		'form-02--form-fields', // .hustle-form-fields
		'form-03--input', // .hustle-input
		'form-04--select2', // .hustle-select2
		'form-05--select2-dropdown', // .hustle-dropdown
		'form-06--timepicker-dropdown', // .hustle-timepicker
		'form-07--radio', // .hustle-radio
		'form-08--checkbox', // .hustle-checkbox
		'form-09--calendar', // .hustle-calendar
		'form-10--submit', // .hustle-button-submit
		'form-11--options-container', // .hustle-form-options
		'form-12--options-title', // .hustle-group-title
		'form-13--gdpr', // .hustle-gdpr
		'form-14--error-message', // .hustle-error-message
		'form-15--success-message', // .hustle-success
		'form-16--recaptcha', // .hustle-recaptcha-copy
	);

	/**
	 * Names of the files for informational modules.
	 * It's in the order to be queued.
	 *
	 * @since 4.3.0
	 * @var array
	 */
	private $info_files_list = array(
		'module-size-popup',
		'module-size-embed',
		'module-size-slidein',
		'general-01--module-container', // .hustle-popup or .hustle-slidein
		'general-02--main-layout', // .hustle-layout or .hustle-layout-body
		'general-03--layout-header', // .hustle-layout-header
		'general-04--layout-content', // .hustle-layout-content
		'general-05--layout-footer', // .hustle-layout-footer
		'general-06--image-size', // .hustle-image
		'general-07--image-fitting', // .hustle-image
		'general-08--image-position', // .hustle-image
		'general-09--content-wrapper', // .hustle-content
		'general-10--title', // .hustle-title
		'general-11--subtitle', // .hustle-subtitle
		'general-12--content', // .hustle-group-content
		'general-13--cta-container', // .hustle-cta-container
		'general-14--cta-button', // .hustle-button-cta
		'general-15--cta-alignment', // .hustle-content-wrap
		'general-16--nsa-link', // .hustle-nsa-link
		'general-17--close-button', // .hustle-button-close
		'general-18--popup-mask', // .hustle-popup-mask
	);

	protected function get_styles() {
		$styles  = $this->get_common_styles();
		$styles .= $this->get_custom_css();

		return $styles;
	}

	private function get_common_styles() {

		// Global prefix.
		// @todo @Danae Remove once all styles moved into new prefixes.
		$prefix = '.hustle-ui.module_id_' . $this->module->module_id . ' ';

		// Small prefix.
		$prefix_mobile = '.hustle-ui.module_id_' . $this->module->module_id . ' ';

		// Desktop prefix.
		// Applies styles to modules without `hustle-size--small` class on large screens.
		// @note It should be used along with $breakpoint.
		$prefix_desktop = '.hustle-ui:not(.hustle-size--small).module_id_' . $this->module->module_id . ' ';

		// Applies styles to screens larger than 783px.
		// Use it in conjunction with $prefix_desktop.
		$breakpoint    = '@media screen and (min-width: ' . $this->bp_desktop . 'px)';
		$breakpoint_sm = '@media screen and (max-width: ' . $this->bp_mobile . 'px)';

		// IE Support.
		$support_ie    = '@media all and (-ms-high-contrast: none), (-ms-high-contrast: active)';
		$breakpoint_ie = '@media all and (min-width: ' . $this->bp_desktop . 'px) and (-ms-high-contrast: none), (-ms-high-contrast: active)';

		$elements = $this->get_prefixed_popup_stylable_elements( $prefix );

		// NEW VARIABLES.
		// @todo re-arrange to avoid unnecessary vars.
		$design            = $this->design;
		$layout_info       = $design['style'];
		$layout_optin      = $design['form_layout'];
		$is_optin          = ( 'optin' === $this->module->module_mode );
		$is_popup          = ( Hustle_Module_Model::POPUP_MODULE === $this->module->module_type );
		$is_slidein        = ( Hustle_Module_Model::SLIDEIN_MODULE === $this->module->module_type );
		$is_embed          = ( Hustle_Module_Model::EMBEDDED_MODULE === $this->module->module_type );
		$is_mobile_enabled = ( '1' === $design['enable_mobile_settings'] );
		$is_vanilla        = '1' === $this->design['use_vanilla'];
		$form_fields       = $this->module->get_form_fields();

		$default_typography = ( '0' === $design['customize_typography_mobile'] ); // Applies for mobile settings only.
		$default_advanced   = ( '0' === $design['customize_border_shadow_spacing_mobile'] ); // Applies for mobile settings only.

		$behavior = array();
		$content  = (array) $this->module->content;
		if ( $is_optin ) {
			$emails = (array) $this->module->emails;
		}
		if ( $is_slidein ) {
			$behavior = (array) $this->module->settings;
		}

		// Get the defaults.
		$colors            = Hustle_Palettes_Helper::get_palette_array( $design['color_palette'], $is_optin );
		$advanced          = $this->design_meta->get_border_spacing_shadow_defaults( 'desktop' );
		$advanced_mobile   = $this->design_meta->get_border_spacing_shadow_defaults( 'mobile' );
		$typography        = $this->design_meta->get_typography_defaults( 'desktop' );
		$typography_mobile = $this->design_meta->get_typography_defaults( 'mobile' );

		// Get custom values if enabled.
		if ( ! $is_vanilla ) {

			// Sets colors for the decorator.
			if ( '1' === $design['customize_colors'] || empty( $colors ) ) {
				// The custom palette might not exist in the site. Grab the colors from the module if so.
				if ( empty( $colors ) ) {
					$colors = Hustle_Palettes_Helper::get_palette_array( 'gray_slate', $is_optin );
				}
				$colors = array_intersect_key( $design, $colors );
			}

			// Sets Border, spacing, shadow settings.
			if ( '1' === $design['customize_border_shadow_spacing'] ) {
				$advanced = array_intersect_key( $design, $advanced );
			}

			if ( '1' === $design['customize_border_shadow_spacing_mobile'] ) {
				$advanced_mobile = array_intersect_key( $design, $advanced_mobile );
			}

			// Sets typography settings.
			if ( '1' === $design['customize_typography'] ) {
				$typography = array_intersect_key( $design, $typography );
			}

			if ( '1' === $design['customize_typography_mobile'] ) {
				$typography_mobile = array_intersect_key( $design, $typography_mobile );
			}
		}
		$advanced   += $advanced_mobile;
		$typography += $typography_mobile;

		$style = '';

		if ( $is_optin ) {
			foreach ( $this->optin_files_list as $file_name ) {
				include Opt_In::$plugin_path . 'inc/front/non-sshare-styles/' . $file_name . '.php';
			}
		} else {
			foreach ( $this->info_files_list as $file_name ) {
				include Opt_In::$plugin_path . 'inc/front/non-sshare-styles/' . $file_name . '.php';
			}
		}

		$styles = $style;

		$stylable_elements = $this->get_popup_stylable_elements();

		/**
		 * Colors Palette.
		 * Works for opt-in and informational modules.
		 *
		 * @since 4.0
		 */

		// ****************************************
		// 2.1. DEFAULT

		// Blockquote border
		if ( '' !== $content['main_content'] ) {
			$styles     .= ' ';
			$styles     .= $prefix . $stylable_elements['layout_content'] . ' blockquote {';
				$styles .= 'border-left-color: ' . $colors['blockquote_border'] . ';';
			$styles     .= '}';
		}

		return $styles;

	}

	private function get_prefixed_popup_stylable_elements( $prefix ) {

		return array(
			'layout_content' => $prefix . '.hustle-layout .hustle-group-content',
		);
	}

	private function get_popup_stylable_elements() {

		return array(
			'layout_content' => '.hustle-layout .hustle-group-content',
		);
	}

	/**
	 * Get the module's custom CSS.
	 *
	 * @since 4.0.3
	 * @return string
	 */
	private function get_custom_css() {

		$styles      = '';
		$prefix_mode = 'informational' === $this->module->module_mode ? 'info' : 'optin';
		$prefix_alt  = '.hustle-ui[data-id="' . $this->module->module_id . '"] .hustle-' . $prefix_mode . ' ';

		/**
		 * Custom CSS
		 * Works for both opt-in and informational modules.
		 *
		 * @since 4.0
		 */

		// for Desktop
		if ( '1' === $this->design['customize_css'] && ! empty( $this->design['custom_css'] ) ) {
			$styles .= $this->prepare_css( $this->design['custom_css'], $prefix_alt, false, true );
		}

		return $styles;
	}

	/**
	 * Prepares the custom css string
	 *
	 * @since 1.0.0
	 * @since 4.2.0 Moved from the class Opt_In to here. Scope changed from 'public static' to 'private'.
	 *
	 * @param string $css_string      Raw CSS string.
	 * @param string $prefix          Prefix for the rules.
	 * @param bool   $as_array        Whether to return the prepared css as an array or as a string.
	 * @param bool   $separate_prefix Whether the prefix must be separated.
	 * @param string $wildcard        The sibling class of target selector.
	 * @return array|string
	 */
	private function prepare_css( $css_string, $prefix, $as_array = false, $separate_prefix = true, $wildcard = '' ) {

		// Master array to hold all values.
		$css_array = array();
		$elements  = explode( '}', $css_string );

		// Output is the final processed CSS string.
		$output          = '';
		$prepared        = '';
		$have_media      = false;
		$media_names     = array();
		$media_names_key = 0;
		$index           = 0;

		foreach ( $elements as $element ) {

			$check_element = trim( $element );

			if ( empty( $check_element ) ) {
				$index++; // Still increment $index even if empty.
				continue;
			}

			// get the name of the CSS element.
			$a_name = explode( '{', $element );
			$name   = $a_name[0];

			// check if @media is  present.
			$media_name = '';

			if ( strpos( $name, '@media' ) !== false && isset( $a_name[1] ) ) {

				$have_media                      = true;
				$media_name                      = $name;
				$media_names[ $media_names_key ] = array(
					'name' => $media_name,
				);
				$name                            = $a_name[1];
				$media_names_key++;

			}

			if ( $have_media ) {
				$prepared = '';
			}

			// get all the key:value pair styles.
			$a_styles = explode( ';', $element );

			// remove element name from first property element.
			$remove_element_name = ( ! empty( $media_name ) ) ? $media_name . '{' . $name : $name;
			$a_styles[0]         = str_replace( $remove_element_name . '{', '', $a_styles[0] );
			$names               = explode( ',', $name );

			foreach ( $names as $name ) {

				if ( $separate_prefix && empty( $wildcard ) ) {
					$space_needed = true;
				} elseif ( $separate_prefix && ! empty( $wildcard ) ) {

					// wildcard is the sibling class of target selector e.g. "wph-modal".
					if ( strpos( $name, $wildcard ) ) {
						$space_needed = false;
					} else {
						$space_needed = true;
					}
				} else {
					$space_needed = false;
				}

				$maybe_put_space = ( $space_needed ) ? ' ' : '';

				$prepared .= ( $prefix . $maybe_put_space . trim( $name ) . ',' );

			}

			$prepared  = trim( $prepared, ',' );
			$prepared .= '{';

			// loop through each style and split apart the key from the value.
			$count = count( $a_styles );

			for ( $a = 0;$a < $count; $a++ ) {

				if ( trim( $a_styles[ $a ] ) ) {

					$a_key_value = explode( ':', $a_styles[ $a ] );

					// build the master css array.
					if ( count( $a_key_value ) > 2 ) {
						$a_key_value_to_join = array_slice( $a_key_value, 1 );
						$a_key_value[1]      = implode( ':', $a_key_value_to_join );
					}

					if ( ! isset( $a_key_value[1] ) ) {
						continue;
					}

					$css_array[ $name ][ $a_key_value[0] ] = $a_key_value[1];
					$prepared                             .= ( $a_key_value[0] . ': ' . $a_key_value[1] );

					if ( '' === $a_key_value[1] ) {
						$prepared .= '';
					}

					$prepared .= ';';
				}
			}

			$prepared .= '}';

			// if have @media earlier, append these styles.
			$prev_media_names_key = $media_names_key - 1;

			if ( isset( $media_names[ $prev_media_names_key ] ) ) {

				if ( isset( $media_names[ $prev_media_names_key ]['styles'] ) ) {

					// See if there were two closing '}' or just one.
					// (each element is exploded/split on '}' symbol, so having two empty strings afterward in the elements array means two '}'s.
					$next_element = isset( $elements[ $index + 2 ] ) ? trim( $elements[ $index + 2 ] ) : false;

					// If inside @media block.
					if ( ! empty( $next_element ) ) {
						$media_names[ $prev_media_names_key ]['styles'] .= $prepared;
					} else {
						// If outside of @media block, add to output.
						$output .= $prepared;
					}
				} else {
					$media_names[ $prev_media_names_key ]['styles'] = $prepared;
				}
			} else {

				// If no @media, add styles to $output outside @media.
				$output .= $prepared;
			}

			// Increase index.
			$index++;
		}

		// if have @media, populate styles using $media_names.
		if ( $have_media ) {

			// reset first $prepared styles.
			$prepared = '';

			foreach ( $media_names as $media ) {
				$prepared .= $media['name'] . '{ ' . $media['styles'] . ' }';
			}

			// Add @media styles to output.
			$output .= $prepared;
		}

		return $as_array ? $css_array : $output;

	}
}