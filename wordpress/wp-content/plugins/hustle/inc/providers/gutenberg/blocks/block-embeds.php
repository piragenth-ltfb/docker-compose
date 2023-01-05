<?php

/**
 * Class Hustle_GHBlock_Embeds
 *
 * @since 1.0 Gutenberg Addon
 */
class Hustle_GHBlock_Embeds extends Hustle_GHBlock_Abstract {

	/**
	 * Block identifier
	 *
	 * @since 1.0 Gutenberg Addon
	 *
	 * @var string
	 */
	protected $_slug = 'embedded';

	/**
	 * Hustle_GHBlock_Embeds constructor.
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
		$css_class = isset( $properties['css_class'] ) ? $properties['css_class'] : '';

		if ( isset( $properties['module_id'] ) ) {
			return '[wd_hustle id="' . $properties['module_id'] . '" type="embedded" css_class="' . $css_class . '"/]';
		}
	}

	/**
	 * Enqueue assets ( scritps / styles )
	 * Should be overriden in block class
	 *
	 * @since 1.0 Gutenberg Addon
	 */
	public function load_assets() {

		Hustle_Module_Front::add_hui_scripts();

		// Scripts
		wp_enqueue_script(
			'hustle-block-embeds',
			Hustle_Gutenberg::get_plugin_url() . '/js/embeds-block.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			filemtime( Hustle_Gutenberg::get_plugin_dir() . '/js/embeds-block.min.js' )
		);

		// Localize scripts
		wp_localize_script(
			'hustle-block-embeds',
			'hustle_embed_data',
			array(
				'modules'       => $this->get_modules(),
				'admin_url'     => admin_url( 'admin.php' ),
				'nonce'         => wp_create_nonce( 'hustle_gutenberg_get_module' ),
				'shortcode_tag' => Hustle_Module_Front::SHORTCODE,
				'l10n'          => $this->localize(),
			)
		);

		if ( isset( $this->dependencies['recaptcha'] ) ) {
			$language = '';

			if ( ! empty( $this->dependencies['recaptcha']['language'] ) ) {
				$language = $this->dependencies['recaptcha']['language'];
			}

			Hustle_Module_Front::add_recaptcha_script( $language, true );
		}

		wp_enqueue_style(
			'hustle_icons',
			Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-icons.min.css',
			array(),
			Opt_In::VERSION
		);

		wp_enqueue_style(
			'hustle_optin',
			Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-optin.min.css',
			array(),
			Opt_In::VERSION
		);

		wp_enqueue_style(
			'hustle_info',
			Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-info.min.css',
			array(),
			Opt_In::VERSION
		);
	}

	public function get_modules() {
		$module_list = $this->get_modules_by_type( 'embedded' );
		return $module_list;
	}

	private function localize() {
		return array(
			'name'                   => esc_html__( 'Name', 'hustle' ),
			'additional_css_classes' => esc_html__( 'Additional CSS Classes', 'hustle' ),
			'advanced'               => esc_html__( 'Advanced', 'hustle' ),
			'module'                 => esc_html__( 'Module', 'hustle' ),
			'customize_module'       => esc_html__( 'Customize embed', 'hustle' ),
			'rendering'              => esc_html__( 'Rendering...', 'hustle' ),
			'block_name'             => esc_html__( 'Embeds', 'hustle' ),
			'block_description'      => esc_html__( 'Display your Hustle Embed module in this block.', 'hustle' ),
		);
	}

	/**
	 * Check the modules' dependencies to be queued.
	 *
	 * @param Hustle_Model $module Module to be checked.
	 * @return void
	 */
	protected function check_dependencies( Hustle_Model $module ) {

		// Do check if recaptcha wasn't required already.
		if ( ! isset( $this->dependencies['recaptcha'] ) ) {

			$fields = $module->get_form_fields();

			// Check if the module has a recaptcha field.
			if ( isset( $fields['recaptcha'] ) ) {

				$this->dependencies['recaptcha'] = array();

				// Set the language of the first module to require recaptcha as the lang for the script.
				if ( ! empty( $fields['recaptcha']['recaptcha_language'] ) && 'automatic' !== $fields['recaptcha']['recaptcha_language'] ) {
					$this->dependencies['recaptcha']['language'] = $fields['recaptcha']['recaptcha_language'];
				}
			}
		}
	}

	protected function is_module_included( Hustle_Model $module ) {
		return $module->is_display_type_active( Hustle_Module_Model::SHORTCODE_MODULE );
	}
}

new Hustle_GHBlock_Embeds();