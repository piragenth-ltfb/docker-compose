<?php

/**
 * Class Hustle_GHBlock_Popup_Trigger
 *
 * @since 1.0 Gutenberg Addon
 */
class Hustle_GHBlock_Popup_Trigger extends Hustle_GHBlock_Abstract {

	/**
	 * Block identifier
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @var string
	 */
	protected $_slug = 'popup-trigger';

	/**
	 * Hustle_GHBlock_Popup_Trigger constructor.
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function __construct() {
		// Initialize block
		$this->init();
	}

	/**
	 * Render block markup on front-end
	 *
	 * @since 1.0 Gutenberg Addon
	 * @param array $properties Block properties
	 *
	 * @return string
	 */
	public function render_block( $properties = array() ) {

		$content   = isset( $properties['content'] ) ? $properties['content'] : __( 'Click here', 'hustle' );
		$css_class = isset( $properties['css_class'] ) ? $properties['css_class'] : '';

		if ( isset( $properties['id'] ) ) {
			return '[wd_hustle id="' . $properties['id'] . '" type="popup" css_class="' . $css_class . '"]' . $content . '[/wd_hustle]';
		}
	}

	/**
	 * Enqueue assets ( scritps / styles )
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function load_assets() {
		// Scripts
		wp_enqueue_script(
			'hustle-block-popup-trigger',
			Hustle_Gutenberg::get_plugin_url() . '/js/popup-trigger-block.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( Hustle_Gutenberg::get_plugin_dir() . '/js/popup-trigger-block.min.js' )
		);

		// Localize scripts
		wp_localize_script(
			'hustle-block-popup-trigger',
			'hustle_popup_trigger_data',
			array(
				'wizard_page'   => Hustle_Data::POPUP_WIZARD_PAGE,
				'modules'       => $this->get_modules(),
				'admin_url'     => admin_url( 'admin.php' ),
				'nonce'         => wp_create_nonce( 'hustle_gutenberg_get_module' ),
				'shortcode_tag' => Hustle_Module_Front::SHORTCODE,
				'text_domain'   => 'hustle',
				'l10n'          => $this->localize(),
			)
		);
	}

	public function get_modules() {
		$module_list = $this->get_modules_by_type( 'popup' );
		return $module_list;
	}

	private function localize() {
		return array(
			'module'                 => esc_html__( 'Module', 'hustle' ),
			'additional_css_classes' => esc_html__( 'Additional CSS Classes', 'hustle' ),
			'click_here'             => esc_html__( 'Click here', 'hustle' ),
			'content_here'           => esc_html__( 'Add the clickable text that will trigger the module.', 'hustle' ),
			'advanced'               => esc_html__( 'Advanced', 'hustle' ),
			'trigger_content'        => esc_html__( 'Trigger Content', 'hustle' ),
			'name'                   => esc_html__( 'Name', 'hustle' ),
			'customize_module'       => esc_html__( 'Customize Popup', 'hustle' ),
			'rendering'              => esc_html__( 'Rendering...', 'hustle' ), // Unused
			'block_name'             => esc_html__( 'Popup Trigger', 'hustle' ),
			'block_description'      => esc_html__( 'Embed the trigger button for a popup module.', 'hustle' ),
			'block_more_description' => esc_html__( 'Note: the Trigger property of the Popup should be set to Click to embed the trigger button for the module.', 'hustle' ),
		);
	}
}

new Hustle_GHBlock_Popup_Trigger();