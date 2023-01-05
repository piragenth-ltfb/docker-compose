<?php

/**
 * Class Hustle_GHBlock_Abstract
 * Extend this class to create new gutenberg block
 *
 * @since 1.0 Gutenberg Addon
 */
abstract class Hustle_GHBlock_Abstract {

	/**
	 * Module's dependencies for rendering the preview
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * Type will be used as identifier
	 *
	 * @since 1.0 Gutenber Addon
	 *
	 * @var string
	 */
	protected $_slug;

	/**
	 * Get block type
	 *
	 * @since  1.0 Gutenber Addon
	 * @return string
	 */
	final public function get_slug() {
		return $this->_slug;
	}

	/**
	 * Initialize block
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function init() {
		// Register block
		$this->register_block();

		// Load block scripts
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_assets' ) );
	}

	/**
	 * Register block type callback
	 * Shouldn't be overridden on block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function register_block() {

		if ( function_exists( 'register_block_type' ) ) {

			register_block_type(
				'hustle/' . $this->get_slug(),
				array(
					'render_callback' => array( $this, 'render_block' ),
				)
			);
		}

	}

	/**
	 * Render block on front-end
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 * @param array $properties Block properties
	 *
	 * @return string
	 */
	public function render_block( $properties = array() ) {
		return '';
	}

	/**
	 * Enqueue assets ( scritps / styles )
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function load_assets() {
		return true;
	}

	/**
	 * Get modules list with shortcode
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @param string $type Module type.
	 *
	 * @return array $module_list List of modules with shortcode.
	 */
	protected function get_modules_by_type( $type ) {
		$modules     = Hustle_Module_Collection::instance()->get_all( true, array( 'module_type' => $type ) );
		$module_list = array(
			array(
				'value' => '',
				'label' => esc_html__( 'Choose module name', 'hustle' ),
			),
		);
		if ( is_array( $modules ) ) {
			foreach ( $modules as $module ) {
				$shortcode_id = $module->get_shortcode_id();
				if ( empty( $shortcode_id ) ) {
					continue;
				}
				if ( ! $this->is_module_included( $module ) ) {
					continue;
				}

				$this->check_dependencies( $module );

				$module_list[] = array(
					'value' => $shortcode_id,
					'label' => $module->module_name,
				);
			}
		}
		return $module_list;
	}

	/**
	 * Check for dependencies in each block type.
	 * To be overridden as required.
	 *
	 * @param Hustle_Model $module Module to be checked.
	 * @return void
	 */
	protected function check_dependencies( Hustle_Model $module ) {}

	/**
	 * Check in every block type if this module should be available.
	 *
	 * @since 4.0.0
	 *
	 * @param Hustle_Model $module Instance of the current module.
	 * @return bool
	 */
	protected function is_module_included( Hustle_Model $module ) {
		return true;
	}
}