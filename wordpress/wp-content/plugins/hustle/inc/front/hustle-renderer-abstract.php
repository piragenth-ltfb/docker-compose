<?php

/**
 * Class Hustle_Renderer_Abstract
 *
 * @since 4.0
 */
abstract class Hustle_Renderer_Abstract {

	/**
	 * A unique ID for the current module.
	 *
	 * @var array
	 */
	protected static $render_ids = array();

	/**
	 * Module sub_type.
	 * Only for embedded and social sharing modules.
	 *
	 * @since 4.0
	 * @var string
	 */
	protected $sub_type = null;

	/**
	 * Whether the render is for a preview.
	 *
	 * @since 4.0
	 * @var boolean
	 */
	public static $is_preview = false;

	public function __construct() {
		$this->is_admin = is_admin();
	}

	/**
	 * Generate an ID for the current module
	 * represented as an integer, starting from 0.
	 *
	 * @since 4.0
	 *
	 * @param $id
	 */
	public function generate_render_id( $id ) {
		if ( ! isset( self::$render_ids[ $id ] ) ) {
			self::$render_ids[ $id ] = 0;
		} else {
			self::$render_ids[ $id ] ++;
		}
	}

	/**
	 * Return the markup of the module.
	 *
	 * @since 4.0
	 *
	 * @param Hustle_Model $module Module to display.
	 * @param string       $sub_type The sub_type for embedded and social sharing modules: widget, shortcode, etc.
	 * @param string       $custom_classes
	 * @param bool         $is_preview
	 */
	public function display( Hustle_Model $module, $sub_type = null, $custom_classes = '', $is_preview = false ) {

		$this->module   = $module;
		$this->sub_type = $sub_type;
		$this->generate_render_id( $this->module->module_id );

		self::$is_preview = $is_preview;

		if ( $is_preview || ( $this->module->active && $this->module->get_visibility()->is_allowed_to_display( $module->module_type, $sub_type ) ) ) {

			// Render form
			echo $this->get_module( $sub_type, $custom_classes ); // wpcs: xss ok.

			add_action( 'wp_footer', array( $this, 'print_styles' ), 9999 );
		}

	}

	/**
	 * Return markup
	 *
	 * @since 4.0
	 *
	 * @param string $sub_type
	 * @param string $custom_classes
	 *
	 * @return mixed|void
	 */
	public function get_module( $sub_type = null, $custom_classes = '' ) {
		$html        = '';
		$post_id     = $this->get_post_id();
		$id          = $this->module->module_id;
		$module_type = $this->module->module_type;
		// if rendered on Preview, the array is empty and sometimes PHP notices show up
		if ( $this->is_admin && ( empty( self::$render_ids ) || ! $id ) ) {
			self::$render_ids[ $id ] = 0;
		}
		$render_id = self::$render_ids[ $id ];

		// TODO: validate sub_types
		$data_type = is_null( $sub_type ) ? $this->module->module_type : $sub_type;

		do_action( 'hustle_before_module_render', $render_id, $this, $post_id, $sub_type );

		$html .= $this->get_wrapper_main( $sub_type, $custom_classes );

			$html .= $this->get_overlay_mask();

				$html .= $this->get_wrapper_content( $sub_type );

				$html .= $this->get_module_body( $sub_type );

			$html .= '</div>'; // Closing wrapper content.

		$html .= '</div>'; // Closing wrapper main.

		/**
		 * Tracking
		 */
		//$form_view = Hustle_Tracking_Model::get_instance();
		$post_id = $this->get_post_id();

		/**
		 * Output
		 */
		$html = apply_filters( 'hustle_render_module_markup', $html, $this, $render_id, $sub_type, $post_id );
		do_action( 'hustle_after_module_render', $this, $render_id, $post_id, $sub_type );
		return $html;
	}

	/**
	 * Return post ID
	 *
	 * @since 4.0
	 * @return int|string|bool
	 */
	public function get_post_id() {
		return get_queried_object_id();
	}

	public function print_styles() {

		$disable_styles = apply_filters( 'hustle_disable_front_styles', false, $this->module, $this );

		if ( ! $disable_styles ) {
			$render_id = self::$render_ids[ $this->module->module_id ];
			$style     = $this->module->get_decorated()->get_module_styles( $this->module->module_type );

			printf(
				'<style type="text/css" id="hustle-module-%1$s-%2$s-styles" class="hustle-module-styles hustle-module-styles-%3$s">%4$s</style>',
				esc_attr( $this->module->module_id ),
				esc_attr( $render_id ),
				esc_attr( $this->module->module_id ),
				wp_strip_all_tags( $style )
			);
		}

	}

	/**
	 * Loads a module via ajax.
	 * Currently used for preview only.
	 *
	 * @since 4.0.0
	 */
	public static function ajax_load_module() {
		$preview_data = filter_input( INPUT_POST, 'previewData', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );

		if ( ! $id && empty( $preview_data ) ) {
			return false;
		}

		if ( empty( $preview_data['template_name'] ) ) {
			// Previewing an already saved module.
			$module = Hustle_Module_Collection::instance()->return_model_from_id( $id );
		} else {
			// Previewing a template.
			// Only non-ssharing modules have templates.
			$module = new Hustle_Module_Model();

			$template_mode = $preview_data['template_mode'];
			$template_name = $preview_data['template_name'];
			$module_type   = $preview_data['module_type'];

			$templates_helper = new Hustle_Templates_Helper();
			$preview_data     = $templates_helper->get_template( $template_name, $template_mode );

			$preview_data['module_mode'] = $template_mode;
			$preview_data['module_type'] = $module_type;
			$preview_data['module_name'] = $template_name;

			$module->populate_module_for_template( $preview_data );
		}

		if ( empty( $module ) || is_wp_error( $module ) ) {
			wp_send_json_error( __( 'Invalid module.' ), 'hustle' );
		}

		$view = $module->get_renderer();

		// This might change later on. We're only using the ajax for preview at the moment.
		$is_preview = true;

		// Add filter for Forminator to load as a preview.
		add_filter( 'forminator_render_shortcode_is_preview', '__return_true' );

		// Define constant for other plugins to hook in preview.
		if ( ! defined( 'HUSTLE_RENDER_PREVIEW' ) || ! HUSTLE_RENDER_PREVIEW ) {
			define( 'HUSTLE_RENDER_PREVIEW', $is_preview );
		}

		do_action( 'hustle_before_ajax_display', $module, $is_preview );

		$response = $view->ajax_display( $module, $preview_data, $is_preview );

		$response = apply_filters( 'hustle_ajax_display_response', $response, $module, $is_preview );

		do_action( 'hustle_after_ajax_display', $module, $is_preview, $response );

		wp_send_json_success( $response );
	}

	protected function get_overlay_mask() {
		return '';
	}
}