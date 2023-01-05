<?php
/**
 * File for Hustle_Decorator_Sshare class.
 *
 * @package Hustle
 * @since 4.3.0
 */

/**
 * Class Hustle_Decorator_Sshare.
 * Handles the styling for the Social Sharing modules.
 *
 * @since 4.3.0
 */
class Hustle_Decorator_Sshare extends Hustle_Decorator_Abstract {

	protected function get_styles() {
		$prefix = '.hustle-ui[data-id="' . $this->module->module_id . '"]';

		$styles = '';

		$module_id = $this->module->id;

		$content = (array) $this->module->content;
		$display = (array) $this->module->display;
		$design  = $this->design;

		/**
		 * Floating Social
		 *
		 * @since 1.0
		 */
		if ( (bool) $display['float_desktop_enabled'] || (bool) $display['float_mobile_enabled'] ) {

			$box_shadow = sprintf(
				'%spx %spx %spx %spx %s',
				$design['floating_drop_shadow_x'],
				$design['floating_drop_shadow_y'],
				$design['floating_drop_shadow_blur'],
				$design['floating_drop_shadow_spread'],
				$design['floating_drop_shadow_color']
			);

			// Custom position for desktops
			if ( (bool) $display['float_desktop_enabled'] ) {

				$desktop_x_offset = ( ! empty( $display['float_desktop_offset_x'] ) ) ? $display['float_desktop_offset_x'] : '0';
				$desktop_y_offset = ( ! empty( $display['float_desktop_offset_y'] ) ) ? $display['float_desktop_offset_y'] : '0';

				$styles .= '@media screen and (min-width: ' . esc_attr( $this->bp_desktop ) . 'px) {';

				if ( 'center' !== $display['float_desktop_position'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-float[data-desktop="true"] { %s: %spx }',
						esc_html( $display['float_desktop_position'] ),
						esc_attr( $desktop_x_offset )
					);
				}

					$styles .= sprintf(
						$prefix . '.hustle-float[data-desktop="true"] { %s: %spx }',
						esc_html( $display['float_desktop_position_y'] ),
						esc_attr( $desktop_y_offset )
					);

				$styles .= '}';
			}

			// Custom position for mobiles
			if ( (bool) $display['float_mobile_enabled'] ) {

				$mobile_x_offset = ( ! empty( $display['float_mobile_offset_x'] ) ) ? $display['float_mobile_offset_x'] : '0';
				$mobile_y_offset = ( ! empty( $display['float_mobile_offset_y'] ) ) ? $display['float_mobile_offset_y'] : '0';

				$styles .= '@media screen and (max-width: ' . esc_attr( $this->bp_mobile ) . 'px) {';

				if ( 'center' !== $display['float_mobile_position'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-float[data-mobiles="true"] { %s: %spx }',
						esc_html( $display['float_mobile_position'] ),
						esc_attr( $mobile_x_offset )
					);
				}

					$styles .= sprintf(
						$prefix . '.hustle-float[data-mobiles="true"] { %s: %spx }',
						esc_html( $display['float_mobile_position_y'] ),
						esc_attr( $mobile_y_offset )
					);

				$styles .= '}';
			}

			// Main background
			$styles .= sprintf(
				$prefix . '.hustle-float .hustle-social { background-color: %s; }',
				$design['floating_bg_color']
			);

			// Container shadow
			if ( (bool) $design['floating_drop_shadow'] ) {

				$styles .= sprintf(
					$prefix . '.hustle-float .hustle-social { box-shadow: %s; -moz-box-shadow: %s; -webkit-box-shadow: %s; }',
					$box_shadow,
					$box_shadow,
					$box_shadow
				);
			}

			// Counter colors
			if ( (bool) $content['counter_enabled'] ) {

				// Counter text
				$styles .= sprintf(
					$prefix . '.hustle-float .hustle-social .hustle-counter { color: %s; }',
					$design['floating_counter_color']
				);

				// DESIGN: Default
				if ( 'flat' === $design['icon_style'] ) {

					// Counter border
					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--default[data-custom="true"] ul:not(.hustle-counter--none) a[class*="hustle-share-"] { border-color: %s; }',
						$design['floating_counter_border']
					);
				}

				// DESIGN: Rounded
				if ( 'rounded' === $design['icon_style'] ) {

					// Counter border
					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--rounded[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
						$design['floating_counter_border']
					);
				}

				// DESIGN: Squared
				if ( 'squared' === $design['icon_style'] ) {

					// Counter border
					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--squared[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
						$design['floating_counter_border']
					);
				}
			} else {

				// Icons custom color
				if ( (bool) $design['floating_customize_colors'] ) {

					// DESIGN: Default
					if ( 'flat' === $design['icon_style'] ) {

						// Element border
						$styles .= sprintf(
							$prefix . '.hustle-float .hustle-social.hustle-social--default[data-custom="true"] ul.hustle-counter--none a[class*="hustle-share-"] { border-color: %s; }',
							'transparent'
						);
					}

					// DESIGN: Rounded
					if ( 'rounded' === $design['icon_style'] ) {

						// Element border
						$styles .= sprintf(
							$prefix . '.hustle-float .hustle-social.hustle-social--rounded[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
							$design['floating_icon_bg_color']
						);
					}

					// DESIGN: Squared
					if ( 'squared' === $design['icon_style'] ) {

						// Element border
						$styles .= sprintf(
							$prefix . '.hustle-float .hustle-social.hustle-social--squared[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
							$design['floating_icon_bg_color']
						);
					}
				}
			}

			// Icons custom color
			if ( (bool) $design['floating_customize_colors'] ) {

				// DESIGN: Default
				if ( 'flat' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--default[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { color: %s; }',
						$design['floating_icon_color']
					);
				}

				// DESIGN: Outlined
				if ( 'outline' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--outlined[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
						$design['floating_icon_bg_color']
					);

					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--outlined[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { color: %s; }',
						$design['floating_icon_color']
					);
				}

				// DESIGN: Rounded
				if ( 'rounded' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--rounded[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { background-color: %s; color: %s; }',
						$design['floating_icon_bg_color'],
						$design['floating_icon_color']
					);
				}

				// DESIGN: Squared
				if ( 'squared' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-float .hustle-social.hustle-social--squared[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { background-color: %s; color: %s; }',
						$design['floating_icon_bg_color'],
						$design['floating_icon_color']
					);
				}
			}
		}

		/**
		 * Inline Social
		 *
		 * @since 1.0
		 */
		if ( (bool) $display['inline_enabled'] || (bool) $display['widget_enabled'] || (bool) $display['shortcode_enabled'] ) {

			$box_shadow = sprintf(
				'%spx %spx %spx %spx %s',
				$design['widget_drop_shadow_x'],
				$design['widget_drop_shadow_y'],
				$design['widget_drop_shadow_blur'],
				$design['widget_drop_shadow_spread'],
				$design['widget_drop_shadow_color']
			);

			// Main background
			$styles .= sprintf(
				$prefix . '.hustle-inline .hustle-social { background-color: %s; }',
				$design['widget_bg_color']
			);

			// Container shadow
			if ( (bool) $design['widget_drop_shadow'] ) {

				$styles .= sprintf(
					$prefix . '.hustle-inline .hustle-social { box-shadow: %s; -moz-box-shadow: %s; -webkit-box-shadow: %s; }',
					$box_shadow,
					$box_shadow,
					$box_shadow
				);
			}

			// Counter colors
			if ( (bool) $content['counter_enabled'] ) {

				// Counter text
				$styles .= sprintf(
					$prefix . '.hustle-inline .hustle-social .hustle-counter { color: %s; }',
					$design['widget_counter_color']
				);

				// DESIGN: Default
				if ( 'flat' === $design['icon_style'] ) {

					// Counter border
					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--default[data-custom="true"] ul:not(.hustle-counter--none) a[class*="hustle-share-"] { border-color: %s; }',
						$design['widget_counter_border']
					);
				}

				// DESIGN: Rounded
				if ( 'rounded' === $design['icon_style'] ) {

					// Counter border
					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--rounded[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
						$design['widget_counter_border']
					);
				}

				// DESIGN: Squared
				if ( 'squared' === $design['icon_style'] ) {

					// Counter border
					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--squared[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
						$design['widget_counter_border']
					);
				}
			} else {

				// Icons custom color
				if ( (bool) $design['widget_customize_colors'] ) {

					// DESIGN: Default
					if ( 'flat' === $design['icon_style'] ) {

						// Element border
						$styles .= sprintf(
							$prefix . '.hustle-inline .hustle-social.hustle-social--default[data-custom="true"] ul.hustle-counter--none a[class*="hustle-share-"] { border-color: %s; }',
							'transparent'
						);
					}

					// DESIGN: Rounded
					if ( 'rounded' === $design['icon_style'] ) {

						// Element border
						$styles .= sprintf(
							$prefix . '.hustle-inline .hustle-social.hustle-social--rounded[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
							$design['widget_icon_bg_color']
						);
					}

					// DESIGN: Squared
					if ( 'squared' === $design['icon_style'] ) {

						// Element border
						$styles .= sprintf(
							$prefix . '.hustle-inline .hustle-social.hustle-social--squared[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
							$design['widget_icon_bg_color']
						);
					}
				}
			}

			// Icons custom color
			if ( (bool) $design['widget_customize_colors'] ) {

				// DESIGN: Default
				if ( 'flat' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--default[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { color: %s; }',
						$design['widget_icon_color']
					);
				}

				// DESIGN: Outlined
				if ( 'outline' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--outlined[data-custom="true"] a[class*="hustle-share-"] { border-color: %s; }',
						$design['widget_icon_bg_color']
					);

					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--outlined[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { color: %s; }',
						$design['widget_icon_color']
					);
				}

				// DESIGN: Rounded
				if ( 'rounded' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--rounded[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { background-color: %s; color: %s; }',
						$design['widget_icon_bg_color'],
						$design['widget_icon_color']
					);
				}

				// DESIGN: Squared
				if ( 'squared' === $design['icon_style'] ) {

					$styles .= sprintf(
						$prefix . '.hustle-inline .hustle-social.hustle-social--squared[data-custom="true"] a[class*="hustle-share-"] [class*="hustle-icon-social-"] { background-color: %s; color: %s; }',
						$design['widget_icon_bg_color'],
						$design['widget_icon_color']
					);
				}
			}
		}

		return $styles;

	}
}