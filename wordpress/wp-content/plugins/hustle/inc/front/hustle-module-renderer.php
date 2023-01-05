<?php
/**
 * Class Hustle_Module_Renderer
 * Used to render Embedded, Popup, and Slidein modules.
 *
 * @since 4.0
 */
class Hustle_Module_Renderer extends Hustle_Renderer_Abstract {

	protected $is_optin = null;

	/**
	 * Whether the module is "optin" or "informational.
	 *
	 * @since 4.0
	 *
	 * @return boolean
	 */
	protected function is_optin() {

		if ( is_null( $this->is_optin ) ) {
			$this->is_optin = ( 'optin' === $this->module->module_mode );
		}

		return $this->is_optin;
	}

	/**
	 * Get main wrapper
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	public function get_wrapper_main( $subtype, $custom_classes = '' ) {

		$content        = $this->module->content;
		$design         = $this->module->design;
		$settings       = $this->module->settings;
		$module_type    = $this->module->module_type;
		$module_subtype = $subtype ? $subtype : $module_type;

		$id              = $this->module->module_id;
		$module_id_class = sprintf(
			'hustle_module_id_%d module_id_%d',
			$id,
			$id
		);

		$module_palette = str_replace( '.', '_', $design->color_palette );

		$module_data           = '';
		$tracking_enabled_data = $this->module->is_tracking_enabled( $module_subtype ) ? 'enabled' : 'disabled';
		$module_data           = sprintf(
			'
			data-id="%s"
			data-render-id="%d"
			data-tracking="%s"
			',
			$id,
			self::$render_ids[ $id ],
			$tracking_enabled_data
		);

		if ( Hustle_Module_Model::EMBEDDED_MODULE === $module_type ) {

			$module_type     = 'inline';
			$animation_intro = ( '' !== $settings->animation_in ) ? $settings->animation_in : 'no_animation';

			$module_data .= sprintf(
				'
				data-intro="%s"
				data-sub-type="%s"
				',
				$animation_intro,
				esc_attr( $subtype )
			);

			if ( '1' === $design->customize_size ) {

				if ( '' !== $design->custom_width || '' !== $design->custom_height ) {
					$custom_classes .= ' hustle-size--custom';
				}
			}
		} elseif ( Hustle_Module_Model::POPUP_MODULE === $module_type ) {

			$animation_intro = ( '' !== $settings->animation_in ) ? $settings->animation_in : 'no_animation';
			$animation_outro = ( '' !== $settings->animation_out ) ? $settings->animation_out : 'no_animation';

			$auto_close = '1' === $settings->auto_hide ? Hustle_Time_Helper::to_microseconds( $settings->auto_hide_time, $settings->auto_hide_unit ) : 'false';

			// TODO: remove when the preview view is updated.
			// This is forced on preview so modules like 'Adblock' can still be closed when previewing.
			$overlay_can_close = ! self::$is_preview ? $settings->close_on_background_click : '1';

			$module_data .= sprintf(
				'
				data-intro="%s"
				data-outro="%s"
				data-overlay-close="%s"
				data-close-delay="%s"
				',
				$animation_intro,
				$animation_outro,
				$overlay_can_close,
				$auto_close
			);

		} elseif ( Hustle_Module_Model::SLIDEIN_MODULE === $module_type ) {

			$position = $settings->display_position;

			$auto_close = '1' === $settings->auto_hide ? Hustle_Time_Helper::to_microseconds( $settings->auto_hide_time, $settings->auto_hide_unit ) : 'false';

			$module_data .= sprintf(
				'
				data-position="%s"
				data-close-delay="%s"
				',
				$position,
				$auto_close
			);

			if ( '1' === $design->customize_size ) {

				if ( '' !== $design->custom_width || '' !== $design->custom_height ) {
					$custom_classes .= ' hustle-size--custom';
				}
			}
		}

		$image_class = '';

		if (
			'' !== $content->feature_image && // Feat image exists
			(
				'' === $content->title && // Title is empty
				'' === $content->sub_title && // Sub-title is empty
				'' === $content->main_content && // Content is empty
				$this->is_show_cta( $content ) // CTA button is hidden
			)
		) {
			$image_class = 'hustle-image-only';
		}

		$inline_style = ! self::$is_preview ? 'style="opacity: 0;"' : 'style="opacity: 1;"';

		$html = sprintf(
			'<div
				class="hustle-ui hustle-%s hustle-palette--%s %s %s %s"
				%s
				%s
			>',
			$module_type,
			$module_palette,
			$module_id_class,
			$image_class,
			esc_attr( $custom_classes ),
			$module_data,
			$inline_style
		);

		return $html;
	}

	/**
	 * Get content wrapper
	 *
	 * @since 4.0
	 * @return string
	 */
	protected function get_wrapper_content() {

		$module_type = $this->module->module_type;

		if ( Hustle_Module_Model::EMBEDDED_MODULE === $this->module->module_type ) {
			$module_type = 'inline';
		}

		$html = sprintf(
			'<div class="hustle-%s-content">',
			$module_type
		);

		return $html;
	}

	/**
	 * Get the right body depending if the module is "optin" or "informational".
	 *
	 * @since 4.0
	 * @return string
	 */
	public function get_module_body() {

		if ( $this->is_optin() ) {
			$html = $this->get_optin_body();
		} else {
			$html = $this->get_informational_body();
		}

		return $html;
	}

	/**
	 * Get an overlay mask for pop-ups only.
	 *
	 * @since 4.0
	 * @return string
	 */
	protected function get_overlay_mask() {

		if ( Hustle_Module_Model::POPUP_MODULE !== $this->module->module_type ) {
			$overlay = '';
		} else {
			$overlay = '<div class="hustle-popup-mask hustle-optin-mask" aria-hidden="true"></div>';
		}

		return $overlay;
	}

	/**
	 * Get close button for pop-ups and slide-ins only.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_close_button() {

		$button = '<button class="hustle-button-icon hustle-button-close">
			<span class="hustle-icon-close" aria-hidden="true"></span>
			<span class="hustle-screen-reader">Close this module</span>
		</button>';

		if ( Hustle_Module_Model::EMBEDDED_MODULE === $this->module->module_type ) {
			$button = '';
		}

		$html = $button;

		return $html;
	}

	/**
	 * Get feature image markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_feature_image() {

		$html        = '';
		$design      = $this->module->design;
		$source      = esc_url( $this->module->content->feature_image );
		$position    = '';
		$overflow    = '';
		$mobile_hide = '';

		if ( 'custom' !== $design->feature_image_horizontal_position || 'custom' !== $design->feature_image_vertical_position ) {

			$x_axis = '';
			$y_axis = '';

			if ( 'custom' !== $design->feature_image_horizontal_position ) {
				$x_axis = $design->feature_image_horizontal_position;
			} else {
				$x_axis = 'custom';
			}

			if ( 'custom' !== $design->feature_image_vertical_position ) {
				$y_axis = $design->feature_image_vertical_position;
			} else {
				$y_axis = 'custom';
			}

			// Legacy class. We're not making use of it as of 4.3.0.
			// Not removing it in case users are using it for custom css.
			$position .= sprintf(
				' class="hustle-image-position--%s%s"',
				$x_axis,
				$y_axis
			);
		}

		if ( '1' === $design->feature_image_hide_on_mobile ) {
			$mobile_hide = ' hustle-hide-until-sm';
		}

		$thumb_id = attachment_url_to_postid( $source );
		$alt      = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );

		$html     .= sprintf(
			'<div class="hustle-image hustle-image-fit--%s%s"%s aria-hidden="true">',
			$design->feature_image_fit,
			$mobile_hide,
			$overflow
		);
			$html .= sprintf(
				'<img src="%s" alt="%s"%s />',
				$source,
				esc_attr( $alt ),
				$position
			);
			$html .= '</div>';

			return $html;
	}

	/**
	 * Check whether the CTA should be shown.
	 *
	 * @since 4.3.0
	 *
	 * @param array $content The stored module's content meta settings.
	 * @return boolean
	 */
	private function is_show_cta( $content ) {

		// If CTA is disabled.
		if ( '0' === $content->show_cta ) {
			return false;
		}

		// Checks for when 1 and 2 CTAs are enabled.
		if ( '1' === $content->show_cta ) {

			// Make sure it has a label.
			// And make sure it has an URL if its action isn't to close the module.
			if ( '' === $content->cta_label ) {
				return false;

			} elseif ( 'close' !== $content->cta_target && '' === $content->cta_url ) {
				return false;
			}
		} else {

			// Make both buttons have a label.
			// And make sure they have an URL if their action isn't to close the module.
			if ( '' === $content->cta_label || '' === $content->cta_two_label ) {
				return false;

			} elseif (
				( 'close' !== $content->cta_target && '' === $content->cta_url ) ||
				( 'close' !== $content->cta_two_target && '' === $content->cta_two_url )
			) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get call to action button.
	 *
	 * @since 4.0.0
	 * @return string
	 */
	private function get_cta_button() {

		$label  = $this->input_sanitize( $this->module->content->cta_label );
		$target = $this->input_sanitize( $this->module->content->cta_target );
		$link   = $this->input_sanitize( $this->module->content->cta_url );

		$html = $this->get_cta_markup( $label, $target, $link );

		if ( '2' === $this->module->content->show_cta ) {
			// CTA #2.
			$html .= $this->get_cta_markup(
				$this->module->content->cta_two_label,
				$this->module->content->cta_two_target,
				$this->module->content->cta_two_url,
				'cta_2'
			);
		}

		// Display CTA helper text if enabled and not empty.
		if ( '1' === $this->module->content->cta_helper_show && '' === $this->module->content->cta_helper_text ) {
			$allowed_html = array(
				'a'      => array(
					'href'   => true,
					'title'  => true,
					'target' => true,
					'alt'    => true,
				),
				'b'      => array(),
				'strong' => array(),
				'i'      => array(),
				'em'     => array(),
				'del'    => array(),
			);

			$cta_helper = '<p>' . wp_kses( $this->module->content->cta_helper_text, $allowed_html ) . '</p>';

			/**
			 * Filter the whole markup for the CTA helper text
			 *
			 * @since 4.3.0
			 *
			 * @param $cta_helper CTA helper text markup to be shown.
			 */
			$html .= apply_filters( 'hustle_get_cta_helper_text', $cta_helper );
		}

		/**
		 * Filter the markup for the CTA buttons.
		 *
		 * @since 4.3.0
		 *
		 * @param $html The markup.
		 * @param $this->module The current module.
		 */
		return apply_filters( 'hustle_get_cta_buttons', $html, $this->module );
	}

	/**
	 * Get the markup of each CTA.
	 *
	 * @since 4.3.0
	 *
	 * @param string $label
	 * @param string $target
	 * @param string $url
	 * @param string $cta_type
	 * @return string
	 */
	private function get_cta_markup( $label, $target, $url, $cta_type = 'cta' ) {

		$extra_class = 'cta_2' === $cta_type ? 'hustle-last-button' : '';
		$class       = 'hustle-cta-close ' . $extra_class;
		$data        = '';

		if ( 'close' !== $target ) {
			$data  = sprintf( 'href="%s" target="_%s"', $url, $target );
			$class = $extra_class;
		}

		/**
		 * Filter the extra classes for the CTA
		 *
		 * @since 4.3.0
		 *
		 * @param $class The class to be added to the button.
		 * @param $is_second Whether it's the CTA #2.
		 * @param $target The target.
		 */
		$class = apply_filters( 'hustle_cta_extra_classes', $class, $cta_type, $target );
		$html  = '';

		$html         .= '<div class="hustle-cta-container">';
			$html     .= sprintf( '<a class="hustle-button hustle-button-cta %1$s" %2$s %3$s>', $class, $data, 'data-cta-type="' . $cta_type . '"' );
				$html .= stripcslashes( $label );
			$html     .= '</a>';
		$html         .= '</div>';

		/**
		 * Filter the markup each CTA.
		 *
		 * @since 4.3.0
		 *
		 * @param $html The markup.
		 * @param $label The label.
		 * @param $target The target.
		 * @param $url You guess.
		 * @param $class Extra classes for the button.
		 */
		return apply_filters( 'hustle_get_cta_buttons', $html, $label, $target, $url, $class );
	}

	// ====================================
	// Informational only markup.
	// ====================================

	/**
	 * Get the body of Informational modules.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	private function get_informational_body() {

		$layout = $this->module->design->style;

		$module_layout = 'default';

		if ( 'simple' === $layout ) {
			$module_layout = 'compact';
		}

		if ( 'cabriolet' === $layout ) {
			$module_layout = 'stacked';
		}

		$html = sprintf(
			'<div class="hustle-info hustle-info--%s">',
			$module_layout
		);

			$html .= ( 'cabriolet' !== $layout ) ? $this->get_close_button() : '';

			$html .= '<div class="hustle-layout">';

				$html .= $this->get_informational_body_content();

			$html .= '</div>';

			// NSA Link
			$html .= $this->get_optin_nsa_link( false );

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the right optin body according to the design.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_informational_body_content() {

		switch ( $this->module->design->style ) {
			case 'minimal':
				return $this->get_informational_design_default();

			case 'simple':
				return $this->get_informational_design_compact();

			default: // 'cabriolet'.
				return $this->get_informational_design_stacked();

		}
	}

	/**
	 * Get default (minimal) layout markup for informational module.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_informational_design_default() {

		$html = '';

		$content = $this->module->content;

		// Header
		if ( '' !== $content->title || '' !== $content->sub_title ) {

			$html .= '<div class="hustle-layout-header">';

			if ( '' !== $content->title ) {
				$html     .= '<span class="hustle-title">';
					$html .= $this->input_sanitize( $content->title );
				$html     .= '</span>';
			}

			if ( '' !== $content->sub_title ) {
				$html     .= '<span class="hustle-subtitle">';
					$html .= $this->input_sanitize( $content->sub_title );
				$html     .= '</span>';
			}

			$html .= '</div>';
		}

		// Content
		if ( '' !== $content->main_content || '' !== $content->feature_image ) {

			$html .= '<div class="hustle-layout-content">';

			if ( '' !== $content->feature_image && 'left' === $this->module->design->feature_image_position ) {
				$html .= $this->get_feature_image();
			}

			if ( '' !== $content->main_content ) {
				$html             .= '<div class="hustle-content">';
					$html         .= '<div class="hustle-content-wrap">';
						$html     .= '<div class="hustle-group-content">';
							$html .= $this->get_module_main_content( $content );
						$html     .= '</div>';
					$html         .= '</div>';
				$html             .= '</div>';
			}

			if ( '' !== $content->feature_image && 'right' === $this->module->design->feature_image_position ) {
				$html .= $this->get_feature_image();
			}

			$html .= '</div>';

		}

		// Footer.
		if ( $this->is_show_cta( $content ) ) {

			$html     .= '<div class="hustle-layout-footer">';
				$html .= $this->get_cta_button();
			$html     .= '</div>';

		}

		return $html;
	}

	/**
	 * Get compact (simple) layout markup for informational module.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_informational_design_compact() {

		$html = '';

		$content = $this->module->content;

		// Image (Left)
		if ( '' !== $content->feature_image && 'left' === $this->module->design->feature_image_position ) {
			$html .= $this->get_feature_image();
		}

		// Content
		if (
			'' !== $content->title ||
			'' !== $content->sub_title ||
			'' !== $content->main_content ||
			$this->is_show_cta( $content )
		) {

			$html .= '<div class="hustle-content">';

				$html .= '<div class="hustle-content-wrap">';

			if ( '' !== $content->title || '' !== $content->sub_title ) {

				$html .= '<div class="hustle-group-title">';

				if ( '' !== $content->title ) {
					$html     .= '<span class="hustle-title">';
						$html .= $this->input_sanitize( $content->title );
					$html     .= '</span>';
				}

				if ( '' !== $content->sub_title ) {
					$html     .= '<span class="hustle-subtitle">';
						$html .= $this->input_sanitize( $content->sub_title );
					$html     .= '</span>';
				}

				$html .= '</div>';
			}

			if ( '' !== $content->main_content ) {

				$html     .= '<div class="hustle-group-content">';
					$html .= $this->get_module_main_content( $content );
				$html     .= '</div>';

			}

			if ( $this->is_show_cta( $content ) ) {

				$html .= $this->get_cta_button();

			}

				$html .= '</div>';

			$html .= '</div>';

		}

		// Image (Right)
		if ( '' !== $content->feature_image && 'right' === $this->module->design->feature_image_position ) {
			$html .= $this->get_feature_image();
		}

		return $html;
	}

	/**
	 * Get stacked (cabriolet) layout markup for informational module.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_informational_design_stacked() {

		$html = '';

		$content = $this->module->content;

		// Header
		$html .= '<div class="hustle-layout-header">';

		if ( '' !== $content->title ) {
			$html     .= '<span class="hustle-title">';
				$html .= $this->input_sanitize( $content->title );
			$html     .= '</span>';
		}

		if ( '' !== $content->sub_title ) {
			$html     .= '<span class="hustle-subtitle">';
				$html .= $this->input_sanitize( $content->sub_title );
			$html     .= '</span>';
		}

			$html .= $this->get_close_button();

		$html .= '</div>';

		// Body
		if (
			'' !== $content->main_content ||
			'' !== $content->feature_image ||
			$this->is_show_cta( $content )
		) {

			$html .= '<div class="hustle-layout-body">';

				// Image (Left)
			if ( '' !== $content->feature_image && 'left' === $this->module->design->feature_image_position ) {
				$html .= $this->get_feature_image();
			}

			if (
					'' !== $content->main_content ||
					$this->is_show_cta( $content )
				) {

				$html .= '<div class="hustle-content">';

					$html .= '<div class="hustle-content-wrap">';

				if ( '' !== $content->main_content ) {

					$html .= '<div class="hustle-group-content">';
					$html .= $this->get_module_main_content( $content );
					$html .= '</div>';

				}

				if ( $this->is_show_cta( $content ) ) {
					$html .= $this->get_cta_button();
				}

					$html .= '</div>';

				$html .= '</div>';

			}

				// Image (Right)
			if ( '' !== $content->feature_image && 'right' === $this->module->design->feature_image_position ) {
				$html .= $this->get_feature_image();
			}

			$html .= '</div>';

		}

		return $html;
	}

	// ====================================
	// Opt-in only markup.
	// ====================================

	/**
	 * Get the body of Opt-In modules.
	 *
	 * @since 4.0
	 *
	 * @return string
	 */
	public function get_optin_body() {

		$layout = $this->module->design->form_layout;

		$module_layout = 'default';

		if ( 'two' === $layout ) {
			$module_layout = 'compact';
		}

		if ( 'three' === $layout ) {
			$module_layout = 'focus-optin';
		}

		if ( 'four' === $layout ) {
			$module_layout = 'focus-content';
		}

		$html = sprintf(
			'<div class="hustle-optin hustle-optin--%s">',
			$module_layout
		);

			$html .= $this->get_close_button();

		if ( 'two' === $layout ) {
			$html .= '<div class="hustle-optin-content">';
		}

				$html .= $this->maybe_get_success_message();

				$html .= '<div class="hustle-layout">';

					$html .= $this->get_optin_body_content();

					$html .= $this->get_optin_nsa_link();

				$html .= '</div>';

		if ( 'two' === $layout ) {
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get opt-in success message.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function maybe_get_success_message() {

		$html            = '';
		$emails          = $this->module->emails;
		$success_option  = $emails->after_successful_submission;
		$success_message = $emails->success_message;
		$auto_close      = '1' === $emails->auto_close_success_message ? Hustle_Time_Helper::to_microseconds( $emails->auto_close_time, $emails->auto_close_unit ) : 'false';

		if ( 'show_success' === $success_option || ( 'redirect' === $success_option && '' === $emails->redirect_url ) ) {

			$html .= sprintf(
				'<div class="hustle-success" data-close-delay="%s" style="display: none;">',
				$auto_close
			);

				$html .= '<span class="hustle-icon-check" aria-hidden="true"></span>';

			if ( '' !== $success_message ) {

				$html .= '<div class="hustle-success-content">';

				if ( is_admin() ) {
					$html .= do_shortcode( wp_kses_post( $success_message ) );
				}

				$html .= '</div>';

			}

			$html .= '</div>';

		}

		return $html;
	}

	/**
	 * Get the right optin body according to the design.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_optin_body_content() {

		switch ( $this->module->design->form_layout ) {
			case 'one':
				return $this->get_optin_design_default();

			case 'two':
				return $this->get_optin_design_compact();

			case 'three':
				return $this->get_optin_design_focus_optin();

			default: // four
				return $this->get_optin_design_focus_content();
		}
	}

	/**
	 * Get the markup according to design "one".
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_optin_design_default() {

		$html    = '';
		$content = $this->module->content;
		$design  = $this->module->design;

		$html .= '<div class="hustle-layout-body">';

		if (
					'' !== $content->title ||
					'' !== $content->sub_title ||
					'' !== $content->feature_image ||
					'' !== $content->main_content ||
					$this->is_show_cta( $content )
			) {

			$html .= sprintf( '<div class="hustle-layout-content hustle-layout-position--%s">', $design->feature_image_position );

			if ( '' !== $content->feature_image && ( 'left' === $design->feature_image_position || 'above' === $design->feature_image_position ) ) {
				$html .= $this->get_feature_image();
			}

			if (
					'' !== $content->title ||
					'' !== $content->sub_title ||
					'' !== $content->main_content ||
					$this->is_show_cta( $content )
			) {

				$html .= '<div class="hustle-content">';

					$html .= '<div class="hustle-content-wrap">';

				if ( '' !== $content->title || '' !== $content->sub_title ) {

					$html .= '<div class="hustle-group-title">';

					if ( '' !== $content->title ) {
						$html .= '<span class="hustle-title">';
						$html .= $this->input_sanitize( $content->title );
						$html .= '</span>';
					}

					if ( '' !== $content->sub_title ) {
						$html .= '<span class="hustle-subtitle">';
						$html .= $this->input_sanitize( $content->sub_title );
						$html .= '</span>';
					}

						$html .= '</div>';
				}

				if ( '' !== $content->main_content ) {
					$html .= '<div class="hustle-group-content">';
					$html .= $this->get_module_main_content( $content );
					$html .= '</div>';
				}

				if ( $this->is_show_cta( $content ) ) {

					$html .= $this->get_cta_button();

				}

						$html .= '</div>';

						$html .= '</div>';
			}

			if ( '' !== $content->feature_image && ( 'right' === $design->feature_image_position || 'below' === $design->feature_image_position ) ) {
				$html .= $this->get_feature_image();
			}

			$html .= '</div>';

		}

			$html .= $this->get_form();

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the markup according to design "two".
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_optin_design_compact() {

		$html    = '';
		$content = $this->module->content;

		$html .= '<div class="hustle-layout-body">';

		if ( '' !== $content->feature_image && 'left' === $this->module->design->feature_image_position ) {
			$html .= $this->get_feature_image();
		}

			$html .= '<div class="hustle-layout-content">';

		if (
					'' !== $content->title ||
					'' !== $content->sub_title ||
					'' !== $content->main_content ||
					$this->is_show_cta( $content )
				) {

			$html .= '<div class="hustle-content">';

				$html .= '<div class="hustle-content-wrap">';

			if ( '' !== $content->title || '' !== $content->sub_title ) {

				$html .= '<div class="hustle-group-title">';

				if ( '' !== $content->title ) {
					$html     .= '<span class="hustle-title">';
						$html .= $this->input_sanitize( $content->title );
					$html     .= '</span>';
				}

				if ( '' !== $content->sub_title ) {
					$html     .= '<span class="hustle-subtitle">';
						$html .= $this->input_sanitize( $content->sub_title );
					$html     .= '</span>';
				}

				$html .= '</div>';

			}

			if ( '' !== $content->main_content ) {
				$html     .= '<div class="hustle-group-content">';
					$html .= $this->get_module_main_content( $content );
				$html     .= '</div>';
			}

			if ( $this->is_show_cta( $content ) ) {

				$html .= $this->get_cta_button();

			}

				$html .= '</div>';

					$html .= '</div>';

		}

				$html .= $this->get_form();

			$html .= '</div>';

		if ( '' !== $content->feature_image && 'right' === $this->module->design->feature_image_position ) {
			$html .= $this->get_feature_image();
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the markup according to design "three".
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_optin_design_focus_optin() {

		$html    = '';
		$content = $this->module->content;

		$html .= '<div class="hustle-layout-body">';

		if ( 'right' === $this->module->design->feature_image_position ) {
			$html .= $this->get_form();
		}

		if (
				'' !== $content->title ||
				'' !== $content->sub_title ||
				'' !== $content->feature_image ||
				'' !== $content->main_content ||
				$this->is_show_cta( $content )
			) {

			$html .= '<div class="hustle-layout-content">';

			if ( '' !== $content->feature_image ) {
				$html .= $this->get_feature_image();
			}

			if (
					'' !== $content->title ||
					'' !== $content->sub_title ||
					'' !== $content->main_content ||
					$this->is_show_cta( $content )
				) {

				$html .= '<div class="hustle-content">';

					$html .= '<div class="hustle-content-wrap">';

				if ( '' !== $content->title || '' !== $content->sub_title ) {

					$html .= '<div class="hustle-group-title">';

					if ( '' !== $content->title ) {
						$html     .= '<span class="hustle-title">';
							$html .= $this->input_sanitize( $content->title );
						$html     .= '</span>';
					}

					if ( '' !== $content->sub_title ) {
						$html     .= '<span class="hustle-subtitle">';
							$html .= $this->input_sanitize( $content->sub_title );
						$html     .= '</span>';
					}

						$html .= '</div>';

				}

				if ( '' !== $content->main_content ) {
					$html     .= '<div class="hustle-group-content">';
						$html .= $this->get_module_main_content( $content );
					$html     .= '</div>';
				}

				if ( $this->is_show_cta( $content ) ) {

					$html .= $this->get_cta_button();

				}

						$html .= '</div>';

							$html .= '</div>';

			}

				$html .= '</div>';

		}

		if ( 'left' === $this->module->design->feature_image_position ) {
			$html .= $this->get_form();
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the markup according to design "four".
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_optin_design_focus_content() {

		$html    = '';
		$content = $this->module->content;

		$html .= '<div class="hustle-layout-body">';

		if ( 'left' === $this->module->design->feature_image_position ) {

			$html .= '<div class="hustle-layout-sidebar">';

			if ( '' !== $content->feature_image ) {
				$html .= $this->get_feature_image();
			}

				$html .= $this->get_form();

				$html .= '</div>';

		}

		if (
				'' !== $content->title ||
				'' !== $content->sub_title ||
				'' !== $content->main_content ||
				$this->is_show_cta( $content )
			) {

			$html .= '<div class="hustle-layout-content">';

				$html .= '<div class="hustle-content">';

					$html .= '<div class="hustle-content-wrap">';

			if ( '' !== $content->title || '' !== $content->sub_title ) {

				$html .= '<div class="hustle-group-title">';

				if ( '' !== $content->title ) {
					$html     .= '<span class="hustle-title">';
						$html .= $this->input_sanitize( $content->title );
					$html     .= '</span>';
				}

				if ( '' !== $content->sub_title ) {
					$html     .= '<span class="hustle-subtitle">';
						$html .= $this->input_sanitize( $content->sub_title );
					$html     .= '</span>';
				}

					$html .= '</div>';

			}

			if ( '' !== $content->main_content ) {
				$html     .= '<div class="hustle-group-content">';
					$html .= $this->get_module_main_content( $content );
				$html     .= '</div>';
			}

			if ( $this->is_show_cta( $content ) ) {

				$html .= $this->get_cta_button();

			}

						$html .= '</div>';

						$html .= '</div>';

						$html .= '</div>';

		}

		if ( 'right' === $this->module->design->feature_image_position ) {

			$html .= '<div class="hustle-layout-sidebar">';

			if ( '' !== $content->feature_image ) {
				$html .= $this->get_feature_image();
			}

				$html .= $this->get_form();

				$html .= '</div>';

		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get module fields
	 *
	 * @return array
	 */
	private function form_elements() {
		/**
		 * Edit module fields
		 *
		 * @since 4.1.1
		 * @param string $form_elements Current module fields.
		 */
		$fields = apply_filters( 'hustle_form_elements', $this->module->emails->form_elements );

		return $fields;
	}

	/**
	 * Get the opt-in form markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_form( $show_recaptcha = true ) {

		$html   = '';
		$fields = $this->form_elements();
		$design = $this->module->design;

		$distribution = $design->optin_form_layout;

		if ( ! $this->is_admin ) {
			$tag        = 'form';
			$extra_data = ' novalidate="novalidate"';
		} else {
			$tag        = 'div';
			$extra_data = '';
		}

		$html .= sprintf(
			'<%s class="hustle-layout-form"%s>',
			$tag,
			$extra_data
		);

		// Form fields.
		$html .= sprintf(
			'<div class="hustle-form%s">',
			'inline' === $distribution ? ' hustle-form-inline' : ''
		);

			$html .= $this->get_form_fields( $fields );

			$html .= $this->get_custom_fields();

		$html .= '</div>';

		// Common hidden fields with useful data.
		$html .= $this->get_common_hidden_fields();

		// GDPR checkbox
		$html .= $this->get_field_gdpr( $fields );

		// reCaptchaget_recaptcha_container
		if ( $show_recaptcha ) {
			$html .= $this->get_recaptcha_container( $fields );
		}

		// Error message
		$html .= $this->get_form_error( $fields );

		$html .= sprintf( '</%s>', $tag );

		return $html;
	}

	/**
	 * Get opt-in form fields markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_form_fields( $fields ) {
		$html = '';
		// Keeping the `hustle-proximity-%s` class just for users. Don't use it.
		$html .= sprintf(
			'<div class="hustle-form-fields hustle-proximity-%s">',
			( '0' === $this->module->design->customize_form_fields_proximity ? 'joined' : 'separated' )
		);
		if ( is_array( $fields ) ) {

			$hidden_fields_markup = '';
			foreach ( $fields as $name => $field ) {
				if ( in_array( $field['type'], array( 'submit', 'gdpr', 'recaptcha' ), true ) ) {
					continue;
				}

				if ( 'hidden' !== $field['type'] ) {
					$html .= $this->get_form_input( $field );
				} else {
					$hidden_fields_markup .= $this->get_form_hidden_input( $field );
				}
			}
		}
		$html .= $this->get_form_submit( $fields );
		$html .= $hidden_fields_markup;
		$html .= '</div>';
		return $html;
	}

	/**
	 * Get opt-in form input field markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_form_input( $field ) {
		$type        = isset( $field['type'] ) ? $this->input_sanitize( $field['type'] ) : 'text';
		$name        = isset( $field['name'] ) ? $this->input_sanitize( $field['name'] ) : 'first_name';
		$label       = ( '' !== $field['placeholder'] ) ? $this->input_sanitize( $field['placeholder'] ) : $this->input_sanitize( $field['label'] );
		$sr_label    = ( '' !== $field['label'] ) ? $this->input_sanitize( $field['label'] ) : '';
		$required    = isset( $field['required'] ) && 'true' === $field['required'] ? true : false;
		$to_validate = isset( $field['validate'] ) && 'true' === $field['validate'] ? true : false;

		$module_type     = $this->module->module_type;
		$module_id       = $this->module->module_id;
		$icon            = $type;
		$value           = '';
		$field_icon      = '';
		$class_icon      = '';
		$class_input     = '';
		$class_status    = $required ? ' hustle-field-required' : '';
		$data_attributes = sprintf( 'data-validate="%s" ', $to_validate );

		if ( $required && ! empty( $field['required_error_message'] ) ) {
			$data_attributes .= sprintf( 'data-required-error="%s" ', $this->input_sanitize( $field['required_error_message'] ) );
		}
		if ( $to_validate && ! empty( $field['validation_message'] ) ) {
			$data_attributes .= sprintf( 'data-validation-error="%s" ', $this->input_sanitize( $field['validation_message'] ) );
		}

		switch ( $type ) {

			case 'email':
				$type = 'email';
				break;

			case 'phone':
				$type             = 'text';
				$data_attributes .= 'data-type="phone"';
				break;

			case 'url':
				$icon = 'website';
				break;

			case 'website':
				$type = 'url';
				break;

			case 'timepicker':
				$class_input = 'hustle-time';

				if ( '24' === $field['time_format'] ) {
					$time_format    = 'HH:mm';
					$time_hours     = ! empty( $field['time_hours'] ) ? $field['time_hours'] : '';
					$time_minutes   = ! empty( $field['time_minutes'] ) ? $field['time_minutes'] : '';
					$time_structure = $time_hours . ':' . $time_minutes;
					$time_default   = ( '' !== $time_hours && '' !== $time_minutes ) ? $time_structure : '';
					$value          = $time_default;
				} else {
					$time_format    = 'hh:mm p';
					$time_hours     = ! empty( $field['time_hours'] ) ? $field['time_hours'] : '';
					$time_minutes   = ! empty( $field['time_minutes'] ) ? $field['time_minutes'] : '';
					$time_period    = ! empty( $field['time_period'] ) ? $field['time_period'] : 'am';
					$time_structure = $time_hours . ':' . $time_minutes . ' ' . $time_period;
					$time_default   = ( '' !== $time_hours && '' !== $time_minutes && '' !== $time_period ) ? $time_structure : '';
					$value          = $time_default;
				}

				$data_attributes .= 'data-time-format="' . $time_format . '" data-time-default="' . $time_default . '" data-time-interval="1" data-time-dropdown="true"';
				break;

			case 'datepicker':
				$date_format = $field['date_format'];

				// These formats come from 4.0, so we keep the same display as in there, even though it was a bug.
				if ( in_array( $field['date_format'], array( 'm/d/Y', 'Y/m/d', 'd/m/Y' ), true ) ) {
					$date_format = 'MM d, yy';
				}

				$class_input      = 'hustle-date';
				$data_attributes .= 'data-min-date="null" data-rtl-support="false" data-format="' . $date_format . '"';
				break;

			default:
				break;
		}

		if ( 'none' !== $this->module->design->form_fields_icon ) {
			$field_icon = sprintf( '<span class="hustle-icon-%s"></span>', esc_attr( $icon ) );
			$class_icon = ' hustle-field-icon--' . $this->module->design->form_fields_icon;
		}

		$classes = array(
			sprintf(
				'hustle-field%s%s',
				esc_attr( $class_icon ),
				esc_attr( $class_status )
			),
		);

		if ( ! empty( $value ) ) {
			$classes[] = 'hustle-field-filled';
		}

		if ( isset( $field['css_classes'] ) ) {
			$classes[] = $field['css_classes'];
		}

		$html = sprintf(
			'<div class="%s">',
			esc_attr( implode( ' ', $classes ) )
		);

		if ( '' !== $sr_label ) {

			$html .= sprintf(
				'<label for="hustle-field-%s-module-%s" id="hustle-field-%s-module-%s-label" class="hustle-screen-reader">%s</label>',
				esc_attr( $name ),
				esc_attr( $module_id ),
				esc_attr( $name ),
				esc_attr( $module_id ),
				$sr_label
			);

		}

			$html .= sprintf(
				'<input id="hustle-field-%s-module-%s" type="%s" class="hustle-input %s" name="%s" value="%s" aria-labelledby="hustle-field-%s-module-%s-label" %s/>', // TODO: add autocomplete here or to form.
				esc_attr( $name ),
				esc_attr( $module_id ),
				esc_attr( $type ),
				esc_attr( $class_input ),
				esc_attr( $name ),
				esc_attr( $value ),
				esc_attr( $name ),
				esc_attr( $module_id ),
				$data_attributes
			);

			$html     .= '<span class="hustle-input-label" aria-hidden="true">';
				$html .= $field_icon;
				$html .= sprintf( '<span>%s</span>', $label );
			$html     .= '</span>';

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get the markup for hidden fields.
	 *
	 * @since 4.0.4
	 * @param array $field Current field data.
	 * @return string
	 */
	private function get_form_hidden_input( $field ) {
		$embed_url = Opt_In_Utils::get_current_url();
		$value     = '';

		switch ( $field['default_value'] ) {

			case 'user_ip':
				$value = Opt_In_Geo::get_user_ip();
				break;
			case 'date_mdy':
				$value = date_i18n( 'm/d/Y', Hustle_Time_Helper::get_local_timestamp(), true );
				break;
			case 'date_dmy':
				$value = date_i18n( 'd/m/Y', Hustle_Time_Helper::get_local_timestamp(), true );
				break;
			case 'embed_id':
				$value = Opt_In_Utils::get_post_data( 'ID' );
				break;
			case 'embed_title':
				$value = Opt_In_Utils::get_post_data( 'post_title' );
				break;
			case 'embed_url':
				$value = $embed_url;
				break;
			case 'user_agent':
				$value = $_SERVER['HTTP_USER_AGENT'];
				break;
			case 'refer_url':
				$value = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $embed_url;
				break;
			case 'user_id':
				$value = Opt_In_Utils::get_user_data( 'ID' );
				break;
			case 'user_name':
				$value = Opt_In_Utils::get_user_data( 'display_name' );
				break;
			case 'user_email':
				$value = Opt_In_Utils::get_user_data( 'user_email' );
				break;
			case 'user_login':
				$value = Opt_In_Utils::get_user_data( 'user_login' );
				break;
			case 'custom_value':
				$value = $field['custom_value'];
				break;

			default:
				break;
		}

		/**
		 * Edit the value of the hidden field.
		 *
		 * @since 4.0.4
		 * @param string $value Current value.
		 * @param array $field Current field data.
		 * @param Hustle_Module_Model $this->module Instance of the current module.
		 */
		$value = apply_filters( 'hustle_field_hidden_field_value', $value, $field, $this->module );

		$html = sprintf(
			'<input type="hidden" name="%s" value="%s"/>',
			esc_attr( $field['name'] ),
			esc_attr( $value )
		);

		return $html;
	}

	/**
	 * Get opt-in form submit button markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_form_submit( $fields ) {

		$html = '';

		$label   = esc_html__( 'Submit', 'hustle' );
		$loading = esc_html__( 'Form is being submitted, please wait a bit.', 'hustle' );

		$classes = isset( $fields['submit']['css_classes'] ) ? $fields['submit']['css_classes'] : '';

		if ( isset( $fields['submit'] ) && isset( $fields['submit']['label'] ) ) {
			$label = $fields['submit']['label'];
		}

		$html .= sprintf( '<button class="hustle-button hustle-button-submit %s" aria-live="polite" data-loading-text="%s">', $classes, $loading );

			$html .= sprintf( '<span class="hustle-button-text">%s</span>', $label );

			$html .= '<span class="hustle-icon-loader hustle-loading-icon" aria-hidden="true"></span>';

		$html .= '</button>';

		return $html;
	}

	/**
	 * Get opt-in custom fields markup.
	 * These custom fields are added by provider's, for example: Mailchimp groups.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_custom_fields() {

		$html = '';

		$connected_addons = Hustle_Provider_Utils::get_addons_instance_connected_with_module( $this->module->module_id );

		foreach ( $connected_addons as $connected_addon ) {

			try {

				$form_hooks = $connected_addon->get_addon_form_hooks( $this->module->module_id );

				if ( $form_hooks instanceof Hustle_Provider_Form_Hooks_Abstract ) {
					$addon_fields = $form_hooks->add_front_form_fields( $this->module );

					// Log errors.
					if ( ! is_string( $addon_fields ) ) {
						throw new Excpetion( 'The returned markup should be a string.' );
					}

					$html .= $addon_fields;
				}
			} catch ( Exception $e ) {
				Hustle_Utils::maybe_log( $connected_addon->get_slug(), 'failed to add custom front form fields.', $e->getMessage() );
			}
		}

		return $html;
	}

	/**
	 * Get common hidden fields with data about the displayed module.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_common_hidden_fields() {

		$html = '<input type="hidden" name="hustle_module_id" value="' . esc_attr( $this->module->module_id ) . '">';

		$html .= '<input type="hidden" name="post_id" value="' . esc_attr( $this->get_post_id() ) . '">';

		if ( ! empty( $this->sub_type ) ) {
			$html .= '<input type="hidden" name="hustle_sub_type" value="' . esc_attr( $this->sub_type ) . '">';
		}

		return $html;
	}

	/**
	 * Get the GDPR checkbox field markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_field_gdpr( $fields ) {

		$html      = '';
		$module_id = $this->module->module_id;
		$render_id = self::$render_ids[ $module_id ];
		$classes   = isset( $fields['gdpr']['css_classes'] ) ? $fields['gdpr']['css_classes'] : '';

		if ( isset( $fields['gdpr'] ) ) {

			$html .= sprintf(
				'<label for="hustle-gdpr-module-%d-%d" class="hustle-checkbox hustle-gdpr %s">',
				$module_id,
				$render_id,
				$classes
			);

			$data_attributes = ! empty( $fields['gdpr']['required_error_message'] ) ?
				sprintf( 'data-required-error="%s" ', $this->input_sanitize( $fields['gdpr']['required_error_message'] ) ) : '';

			$html .= sprintf(
				'<input type="checkbox" name="gdpr" id="hustle-gdpr-module-%d-%d" %s />',
				$module_id,
				$render_id,
				$data_attributes
			);

			$html .= '<span aria-hidden="true"></span>';

			$html .= sprintf(
				'<span>%s</span>',
				$this->input_sanitize( $fields['gdpr']['gdpr_message'] )
			);

			$html .= '</label>';

		}

		return $html;
	}

	/**
	 * Get the filtered and parsed main content for the module.
	 *
	 * @since 4.0
	 *
	 * @param object $content
	 * @return string
	 */
	private function get_module_main_content( $content ) {

		$allowed_html = wp_kses_allowed_html( 'post' );

		// iframe.
		$allowed_html['iframe'] = array(
			'src'             => array(),
			'height'          => array(),
			'width'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
		);
		// Form.
		$allowed_html['form'] = array(
			'action'         => true,
			'accept'         => true,
			'accept-charset' => true,
			'enctype'        => true,
			'method'         => true,
			'name'           => true,
			'target'         => true,
			'role'           => array(),
		);
		// Inputs.
		$allowed_html['input'] = array(
			'class'       => array(),
			'id'          => array(),
			'name'        => array(),
			'value'       => array(),
			'type'        => array(),
			'placeholder' => array(),
		);
		// Select.
		$allowed_html['select'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'type'  => array(),
		);
		// Select options.
		$allowed_html['option'] = array(
			'selected' => array(),
		);
		// Style.
		$allowed_html['style'] = array(
			'types' => array(),
		);

		// i for fontawesome.
		$allowed_html['i'] = array(
			'class' => array(),
		);
		// Keep allowing scripts because users are using it.
		$allowed_html['script'] = array();

		/**
		 * Allows editing the allowed html tags for the modules' main content.
		 *
		 * @since 4.0.0.1
		 */
		$allowed_html = apply_filters( 'hustle_module_main_content_allowed_html', $allowed_html, $this->module );

		$content = wp_kses( $content->main_content, $allowed_html );

		/**
		 * Allows editing the escaped main content before doing the shortcodes.
		 *
		 * @since 4.0.0.1
		 */
		$content = apply_filters( 'hustle_module_main_content', $content, $this->module );

		// Process the [embed] shortcode.
		if ( has_shortcode( $content, 'embed' ) ) {
			$wp_embed = new WP_Embed();
			$content  = $wp_embed->run_shortcode( $content );
		}

		return do_shortcode( $content );
	}

	/**
	 * Get the filtered and parsed content for the module.
	 *
	 * @since 4.2.1
	 *
	 * @param object $content
	 * @return string
	 */
	public function input_sanitize( $content ) {
		$allowed_html = wp_kses_allowed_html( 'post' );

		/**
		 * Custom filtering for every other field than main content.
		 * By default it uses wp_kses post rules.
		 *
		 * @since 4.2.1
		 */
		$allowed_html = apply_filters( 'hustle_module_input_content_allowed_html', $allowed_html, $this->module );

		$content = wp_kses( $content, $allowed_html );
		return $content;
	}

	/**
	 * Get the opt-in form error message markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_form_error( $fields ) {

		if ( isset( $fields['submit'] ) && ! empty( $fields['submit']['error_message'] ) ) {
			$default_error = $fields['submit']['error_message'];
		} else {
			$default_error = __( 'There was an error submitting the form', 'hustle' );
		}

		$html = sprintf(
			'<div class="hustle-error-message" style="display: none;" data-default-error="%s">',
			esc_attr( $default_error )
		);

		$html .= '</div>';

		return $html;
	}

	/**
	 * Get opt-in never see link markup.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_optin_nsa_link( $wrapper = true ) {

		$html = '';

		if ( Hustle_Module_Model::EMBEDDED_MODULE !== $this->module->module_type ) {

			$content = $this->module->content;
			$message = ( '' !== $content->never_see_link_text ) ? $content->never_see_link_text : esc_html__( 'Never see this message again', 'hustle' );

			if ( (int) $content->show_never_see_link ) {
				$html         .= ( true === $wrapper ) ? '<div class="hustle-layout-footer">' : '';
					$html     .= '<p class="hustle-nsa-link">';
						$html .= '<a href="#">' . $this->input_sanitize( $message ) . '</a>';
					$html     .= '</p>';
				$html         .= ( true === $wrapper ) ? '</div>' : '';
			}
		}
		return $html;
	}

	/**
	 * Get recaptcha container if configured.
	 *
	 * @since 4.0
	 * @return string
	 */
	private function get_recaptcha_container( $fields ) {

		$html   = '';
		$fields = $this->module->emails->form_elements;

		// Check if this module has a recaptcha field, and that its creds were added.
		if ( $this->is_recaptcha_active( $fields ) ) {

			$recaptcha = $fields['recaptcha'];

			$recaptcha_version = empty( $recaptcha['version'] ) ? 'v2_checkbox' : $recaptcha['version'];
			$site_key_key      = $recaptcha_version . '_site_key';
			$secret_key_key    = $recaptcha_version . '_secret_key';

			if ( 'v2_checkbox' === $recaptcha_version ) {
				$size       = $recaptcha['recaptcha_type'];
				$badge      = '';
				$show_badge = true;

			} else {
				$size       = 'invisible';
				$show_badge = '1' === $recaptcha[ $recaptcha_version . '_show_badge' ];
				$badge      = 'inline';
			}

			$recaptcha_classes = isset( $recaptcha['css_classes'] ) ? $recaptcha['css_classes'] : '';

			if ( ! $show_badge ) {
				$recaptcha_classes .= ' hustle-recaptcha-nobadge';
			}

			$recaptcha_theme = ( 'v2_invisible' === $recaptcha['version'] ) ? $recaptcha['v2_invisible_theme'] : $recaptcha['recaptcha_theme'];

			$extra_data = sprintf(
				'data-size="%s" data-theme="%s" data-badge="%3$s"',
				esc_attr( $size ),
				esc_attr( $recaptcha_theme ),
				$badge
			);

			$recaptcha_settings = Hustle_Settings_Admin::get_recaptcha_settings();
			$render_id          = self::$render_ids[ $this->module->module_id ];
			$html              .= sprintf(
				'<div id="hustle-modal-recaptcha-%1$d-%2$d" class="hustle-recaptcha %3$s" data-required-error="%4$s" data-sitekey="%5$s" data-version=%6$s %7$s></div>',
				$this->module->id,
				$render_id,
				$this->input_sanitize( $recaptcha_classes ),
				$this->input_sanitize( $fields['recaptcha']['validation_message'] ),
				$this->input_sanitize( $recaptcha_settings[ $site_key_key ] ),
				$recaptcha_version,
				$extra_data
			);

			// Display custom text instead of badge if hidden.
			if ( ! $show_badge ) {

				$html .= sprintf(
					'<div class="hustle-recaptcha-copy">%s</div>',
					$recaptcha[ $recaptcha_version . '_badge_replacement' ]
				);
			}

			// The input that will hold the recaptcha's response for backend validation on form submit.
			$html .= '<input type="hidden" name="recaptcha-response" class="recaptcha-response-input" value="">';

			/**
			 * Filter the markup for the recaptcha container
			 *
			 * @since 4.1.1
			 *
			 * @param object $this->module       Current module. Instance of Hustle_Module_Model.
			 * @param array  $recaptcha          Module's recaptcha field settings.
			 * @param array  $recaptcha_settings Global stored recaptcha credentials.
			 * @param string $render_id          The render ID of the currently rendered module instance.
			 */
			$html = apply_filters( 'hustle_get_module_recaptcha_container', $html, $this->module, $recaptcha, $recaptcha_settings, $render_id );
		}

		return $html;

	}

	/**
	 * Whether the current module's recaptcha can be displayed
	 * Check whether this module has a recaptcha field,
	 * and if the corresponding credentials were already stored.
	 *
	 * @since 4.1.1
	 * @param $fields This module's fields.
	 */
	private function is_recaptcha_active( $fields = array() ) {

		if ( empty( $fields ) ) {
			$fields = $this->module->emails->form_elements;
		}

		// The module does have recaptcha.
		if ( isset( $fields['recaptcha'] ) ) {

			$recaptcha = $fields['recaptcha'];

			$recaptcha_version = empty( $recaptcha['version'] ) ? 'v2_checkbox' : $recaptcha['version'];
			$site_key_key      = $recaptcha_version . '_site_key';
			$secret_key_key    = $recaptcha_version . '_secret_key';

			$recaptcha_settings = Hustle_Settings_Admin::get_recaptcha_settings();

			// Make sure the creds for the selected recaptcha type has been added.
			if ( ! empty( $recaptcha_settings[ $site_key_key ] ) && ! empty( $recaptcha_settings[ $secret_key_key ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Handle AJAX display
	 *
	 * @since 4.0
	 * @return string
	 */
	public function ajax_display( Hustle_Module_Model $module, $data = array(), $is_preview = true ) {

		self::$is_preview = $is_preview;

		if ( ! empty( $data ) ) {
			$this->module = $module->load_preview( $data );
		} else {
			$this->module = $module->load();
		}

		$response = array(
			'html'   => '',
			'style'  => array(),
			'script' => array(),
			'module' => $this->module,
		);

		$subtype          = Hustle_Module_Model::EMBEDDED_MODULE !== $module->module_type ? null : 'shortcode';
		$response['html'] = $this->get_module( $subtype, 'hustle-preview' );

		$styles = Hustle_Module_Front::print_front_fonts( $module->get_google_fonts(), true );

		// Add the recaptcha script inline for previews.
		if ( $is_preview && Hustle_Model::OPTIN_MODE === $this->module->module_mode ) {
			$fields = $this->module->emails->form_elements;

			// Load the recaptcha script if the module has it, and if the credentials are stored.
			if ( $this->is_recaptcha_active( $fields ) ) {

				$recaptcha = $fields['recaptcha'];
				$source    = Hustle_Module_Front::add_recaptcha_script( $recaptcha['recaptcha_language'], true, true );

				// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
				$response['script'] = '<script src="' . $source . '" async defer></script>';
			}
		}

		// This might be used later for ajax loading.
		ob_start();
		$this->print_styles();
		$styles           .= ob_get_clean();
		$response['style'] = $styles;

		return $response;
	}
}