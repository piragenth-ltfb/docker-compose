<?php
/**
 * Hustle_Layout_Helper class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Helper class for rendering markup on admin side.
 * This is used along admin pages to standardize certain elements markup.
 *
 * @since 4.2.0
 */
class Hustle_Layout_Helper {

	/**
	 * Instance of the class that controls the template.
	 *
	 * @since 4.2.0
	 * @var Object
	 */
	private $admin;

	/**
	 * White labeling based on Dash Plugin Settings.
	 *
	 * @since 4.2.0
	 * @var boolean
	 */
	private $is_branding_hidden = false;

	/**
	 * To be removed.
	 *
	 * @var string something.
	 */
	public static $plugin_url;

	/**
	 * Flag for letting SUI doesn't run auto init selects to suiSelect.
	 *
	 * @var bool
	 */
	private static $dont_init_selects;

	/**
	 * Hustle_Layout_Helper class constructor.
	 *
	 * @since 4.2.0
	 * @param object $referer The class that has the properties to access from within templates.
	 */
	public function __construct( $referer = null ) {

		self::$plugin_url = Opt_In::$plugin_url;

		$this->is_branding_hidden = apply_filters( 'wpmudev_branding_hide_branding', $this->is_branding_hidden );

		/**
		 * Sets the referer class as a property.
		 * This allows us to access the referer class' properties if needed
		 * from the template files.
		 */
		// TODO maybe check if the referer has the two allowed classes.
		if ( $referer ) {
			$this->admin = $referer;
		}
	}

	/**
	 * Gets the previously set referer.
	 *
	 * @since 4.2.0
	 * @return object
	 */
	public function get_referer() {
		if ( ! $this->admin ) {
			return false;
		}
		return $this->admin;
	}

	/**
	 * Returns or echoes markup from the given $options array.
	 * Uses the file 'admin/commons/options' as the markup template.
	 *
	 * @since 4.2.0
	 *
	 * @param  array   $options Array with the options that define the markup to be returned.
	 * @param  boolean $return Whether to echo or return the markup.
	 * @return string
	 */
	public function get_html_for_options( $options, $return = false ) {
		$html = '';
		foreach ( $options as $key => $option ) {
			$html .= $this->render( 'admin/commons/options', $option, $return );
		}
		return $html;
	}

	/**
	 * Renders a view file with static call.
	 *
	 * @since 1.0
	 * @since 4.2.0 Moved from Opt_In to this class.
	 *
	 * @param string     $file Path to the view file.
	 * @param array      $params Array whose keys will be variable names when within the view file.
	 * @param bool|false $return Whether to echo or return the contents.
	 * @return string
	 */
	public function render( $file, $params = array(), $return = false ) {

		// Assign $file to a variable which is unlikely to be used by users of the method.
		$opt_in_to_be_file_name = $file;
		extract( $params, EXTR_OVERWRITE ); // phpcs:ignore

		if ( $return ) {
			ob_start();
		}

		$template_file = trailingslashit( Opt_In::$plugin_path ) . Opt_In::VIEWS_FOLDER . '/' . $opt_in_to_be_file_name . '.php';
		if ( file_exists( $template_file ) ) {
			include $template_file;

		} else {
			$template_path = Opt_In::$template_path . $opt_in_to_be_file_name . '.php';

			// Render file located outside the plugin's folder. Useful when adding third party integrations.
			$external_path = $opt_in_to_be_file_name . '.php';

			if ( file_exists( $template_path ) ) {
				include $template_path;
			} elseif ( file_exists( $external_path ) ) {
				include $external_path;
			} elseif ( file_exists( $opt_in_to_be_file_name ) ) {
				include $opt_in_to_be_file_name;
			}
		}

		if ( $return ) {
			return ob_get_clean();
		}

		if ( ! empty( $params ) ) {
			foreach ( $params as $param ) {
				unset( $param );
			}
		}
	}

	/**
	 * Renders custom attributes within views templates.
	 *
	 * @since 1.0.0
	 * @since 4.2.0 Moved from Opt_In to this class.
	 * @since 4.3.0 Removed the $echo parameter.
	 *
	 * @param array $html_options Attributes as an array to be renderd.
	 * @return string
	 */
	public function render_attributes( $html_options ) {

		if ( array() === $html_options ) {
			return '';
		}

		$special_attributes = array(
			'async',
			'autofocus',
			'autoplay',
			'checked',
			'controls',
			'declare',
			'default',
			'defer',
			'disabled',
			'formnovalidate',
			'hidden',
			'ismap',
			'loop',
			'multiple',
			'muted',
			'nohref',
			'noresize',
			'novalidate',
			'open',
			'readonly',
			'required',
			'reversed',
			'scoped',
			'seamless',
			'selected',
			'typemustmatch',
		);

		$html = '';
		if ( isset( $html_options['encode'] ) ) {
			$raw = ! $html_options['encode'];
			unset( $html_options['encode'] );
		} else {
			$raw = false;
		}
		foreach ( $html_options as $name => $value ) {
			if ( in_array( $name, $special_attributes, true ) ) {
				if ( $value ) {
					$html .= ' ' . $name;
					$html .= '="' . $name . '"';
				}
			} elseif ( null !== $value ) {
				$html .= ' ' . esc_attr( $name ) . '="' . ( $raw ? $value : esc_attr( $value ) ) . '"'; }
		}

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Renders a basic modal with the passed attributes.
	 *
	 * @since 4.2.0
	 * @param array $arguments Arguments for the modal. Documented in the template file.
	 */
	private function render_modal( $arguments ) {
		$this->render( '/admin/commons/modal-template', $arguments );
	}

	/**
	 * Image function
	 * Return image element with 2x and 1x support.
	 *
	 * @since 4.3.1
	 *
	 * @param string      $image_path URL for the given image.
	 * @param string      $image_suffix Image format, like png, jpg, etc.
	 * @param string      $image_class Class for the image HTML element.
	 * @param string|bool $support Whether the image has retina support.
	 */
	private function hustle_image( $image_path, $image_suffix, $image_class, $support ) {
		$image = '';
		/**
		 * White labeling based on Dash Plugin Settings
		 */
		$hide_branding = apply_filters( 'wpmudev_branding_hide_branding', false );
		if ( $hide_branding ) {
			return $image;
		}
		$image_name = esc_html__( 'Hustle image', 'hustle' );
		if ( ( true === $support ) || ( '2x' === $support ) ) {
			if ( '' !== $image_class ) {
				$image = '<img src="' . $image_path . '.' . $image_suffix . '" srcset="' . $image_path . '.' . $image_suffix . ' 1x, ' . $image_path . '@2x.' . $image_suffix . ' 2x" alt="' . $image_name . '" class="' . $image_class . '" aria-hidden="true">';
			} else {
				$image = '<img src="' . $image_path . '.' . $image_suffix . '" srcset="' . $image_path . '.' . $image_suffix . ' 1x, ' . $image_path . '@2x.' . $image_suffix . ' 2x" alt="' . $image_name . '" aria-hidden="true">';
			}
		} else {
			if ( '' !== $image_class ) {
				$image = '<img src="' . $image_path . '.' . $image_suffix . '" alt="' . $image_name . '" class="' . $image_class . '" aria-hidden="true">';
			} else {
				$image = '<img src="' . $image_path . '.' . $image_suffix . '" alt="' . $image_name . '" aria-hidden="true">';
			}
		}
		echo $image; // phpcs:ignore
	}

	/**
	 * Return the image markup for retina and no retina images
	 *
	 * @since 4.3.1
	 *
	 * @param string $image_path URL for the given image.
	 * @param string $image_retina_path URL for the given image for retina.
	 * @param string $image_class Class for the image HTML element.
	 * @param string $image_width Image max width.
	 * @param string $image_height Image max height.
	 *
	 * @return string
	 */
	private function render_image_markup( $image_path, $image_retina_path = '', $image_class = '', $image_width = '', $image_height = '' ) {

		$image = '';

		$image_path   = esc_url( $image_path );
		$image_srcset = '';
		$image_styles = '';

		if ( ! empty( $image_retina_path ) || '' !== $image_retina_path ) {
			$image_srcset = $image_path . ' 1x, ' . esc_url( $image_retina_path ) . ' 2x';
		}

		if ( '' !== $image_width || '' !== $image_height ) {

			$image_styles .= ' styles="';

			if ( '' !== $image_width ) {
				$image_styles = 'max-width: ' . $image_width . 'px';

				if ( '' !== $image_height ) {
					$image_styles = ' ';
				}
			}

			if ( '' !== $image_height ) {
				$image_styles = 'max-height: ' . $image_height . 'px';
			}

			$image_styles .= '"';

		}

		$image .= '<img';

		$image .= ' src="' . $image_path . '"';

		if ( '' !== $image_srcset ) {
			$image .= ' srcset="' . $image_srcset . '"';
		}

		$image .= ' title="Hustle image"';
		$image .= ' alt="Hustle image commonly Hustle-Man doing something fun"';

		if ( ! empty( $image_class ) || '' !== $image_class ) {
			$image .= ' class="' . $image_class . '"';
		}

		$image .= ' aria-hidden="true"';

		$image .= '/>';

		return $image;
	}

	/**
	 * Color Picker
	 *
	 * Return the correct color picker markup that's compatible with Shared UI 2.0
	 *
	 * @since 4.3.1
	 *
	 * @param string $id "id" attribute of the input.
	 * @param string $name "name" attribute of the input.
	 * @param string $alpha "false"/"true". Enables or disables the alpha selector in the colorpicker.
	 * @param bool   $is_js_template whether this colorpicker will be filled via js templating.
	 * @param string $value Value to be used when js templating isn't used.
	 */
	private function sui_colorpicker( $id, $name, $alpha = 'false', $is_js_template = true, $value = false ) {

		$value = ( ! $is_js_template && $value ) ? $value : '{{ ' . $name . ' }}';

		echo '<div class="sui-colorpicker-wrap">

			<div class="sui-colorpicker" aria-hidden="true">
				<div class="sui-colorpicker-value">
					<span role="button">
						<span style="background-color: ' . esc_attr( $value ) . '"></span>
					</span>
					<input class="hustle-colorpicker-input" type="text" value="' . esc_attr( $value ) . '"/>
					<button><span class="sui-icon-close" aria-hidden="true"></span></button>
				</div>
				<button class="sui-button">' . esc_html__( 'Select', 'hustle' ) . '</button>
			</div>

			<input type="text"
				name="' . esc_attr( $name ) . '"
				value="' . esc_attr( $value ) . '"
				id="' . esc_attr( $id ) . '"
				class="sui-colorpicker-input"
				data-alpha="' . esc_attr( $alpha ) . '"
				data-attribute="' . esc_attr( $name ) . '" />

		</div>';

	}
}