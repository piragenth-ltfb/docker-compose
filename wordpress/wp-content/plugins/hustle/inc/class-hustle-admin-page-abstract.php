<?php
/**
 * File for Hustle_Admin_Page_Abstract class.
 *
 * @package Hustle
 * @since 4.0.1
 */

if ( ! class_exists( 'Hustle_Admin_Page_Abstract' ) ) :
	/**
	 * Class Hustle_Admin_Page_Abstract.
	 * This is the base class for all Hustle's pages.
	 *
	 * @since 4.0.1
	 */
	abstract class Hustle_Admin_Page_Abstract {

		/**
		 * Page slug defined by us.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page;

		/**
		 * Template path for the page relative to the 'views' folder.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_template_path;

		/**
		 * Page title.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_title;

		/**
		 * Page title for the WordPress menu.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_menu_title;

		/**
		 * Required capability for the page to be available.
		 *
		 * @since 4.0.1
		 * @var string
		 */
		protected $page_capability;

		/**
		 * The current page that's being requested.
		 *
		 * @since 4.0.2
		 * @var string|bool
		 */
		protected $current_page;

		/**
		 * Page slug defined by WordPress when registering the page.
		 *
		 * @since 4.0.0
		 * @var string
		 */
		protected $page_slug;

		/**
		 * Instance of Hustle_Layout_Helper
		 *
		 * @since 4.2.0
		 * @var Hustle_Layout_Helper
		 */
		private $renderer;

		/**
		 * Class constructor.
		 *
		 * @since 4.0.1
		 */
		public function __construct() {

			$this->current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

			$this->init();

			add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		}

		/**
		 * Initiate the page's properties
		 * Should be overridden by each page.
		 *
		 * @since 4.0.1
		 */
		abstract protected function init();

		/**
		 * Register the admin menus.
		 *
		 * @since 4.0.1
		 */
		public function register_admin_menu() {

			$this->page_slug = add_submenu_page( 'hustle', $this->page_title, $this->page_menu_title, $this->page_capability, $this->page, array( $this, 'render_main_page' ) );

			add_action( 'admin_init', array( $this, 'maybe_export' ) );
			add_action( 'load-' . $this->page_slug, array( $this, 'current_page_loaded' ) );
		}

		/**
		 * Gets an instance of the renderer class.
		 *
		 * @since 4.2.1
		 * @return Hustle_Layout_Helper
		 */
		protected function get_renderer() {
			if ( ! $this->renderer ) {
				$this->renderer = new Hustle_Layout_Helper( $this );
			}
			return $this->renderer;
		}

		/**
		 * Check if it's export - run the relevant action.
		 */
		public function maybe_export() {
			$this->export_module();
		}

		/**
		 * Render the main page
		 *
		 * @since 4.0.1
		 */
		public function render_main_page() {
			?>
			<div class="<?php echo esc_attr( $this->get_sui_wrap_class() ); ?>">

				<?php
				$template_args = $this->get_page_template_args();
				$renderer      = $this->get_renderer();
				$renderer->render( $this->page_template_path, $template_args );

				$this->render_modals();
				?>

			</div>
			<?php
		}

		/**
		 * Perform actions during the 'load-{page}' hook.
		 *
		 * @since 4.0.4
		 */
		public function current_page_loaded() {
			$this->maybe_export();
			add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ), 99 );
			add_action( 'admin_print_styles', array( $this, 'register_styles' ) );
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ), 99 );
			add_filter( 'removable_query_args', array( $this, 'remove_notice_params' ) );
		}

		/**
		 * Print forminator scripts for preview.
		 * Used by Dashboard, Wizards, and Listings.
		 *
		 * @since 4.0.1
		 */
		public function maybe_print_forminator_scripts() {

			// Add Forminator's front styles and scripts for preview.
			if ( defined( 'FORMINATOR_VERSION' ) ) {
				forminator_print_front_styles( FORMINATOR_VERSION );
				forminator_print_front_scripts( FORMINATOR_VERSION );

			}
		}

		/**
		 * Register scripts for the admin page.
		 *
		 * @since 4.3.1
		 *
		 * @param string $page_slug Page slug.
		 */
		public function register_scripts( $page_slug ) {

			wp_enqueue_script(
				'shared-ui',
				Opt_In::$plugin_url . 'assets/js/shared-ui.min.js',
				array( 'jquery' ),
				HUSTLE_SUI_VERSION,
				true
			);

			/**
			 * Filters the variable to be localized into the js side of Hustle's admin pages.
			 *
			 * @since unknown
			 */
			$optin_vars = apply_filters( 'hustle_optin_vars', $this->get_vars_to_localize() );

			wp_register_script(
				'optin_admin_scripts',
				Opt_In::$plugin_url . 'assets/js/admin.min.js',
				array( 'jquery', 'backbone', 'jquery-effects-core' ),
				Opt_In::VERSION,
				true
			);
			wp_localize_script( 'optin_admin_scripts', 'optinVars', $optin_vars );
			wp_enqueue_script( 'optin_admin_scripts' );
		}

		/**
		 * Register the js variables to be localized for this page.
		 *
		 * @since 4.3.1
		 *
		 * @return array
		 */
		protected function get_vars_to_localize() {
			return array(
				'dismiss_notice_nonce' => wp_create_nonce( 'hustle_dismiss_notification' ),
				'urlParams'            => $_GET, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'module_page'          => array(
					'popup'          => Hustle_Data::POPUP_LISTING_PAGE,
					'slidein'        => Hustle_Data::SLIDEIN_LISTING_PAGE,
					'embedded'       => Hustle_Data::EMBEDDED_LISTING_PAGE,
					'social_sharing' => Hustle_Data::SOCIAL_SHARING_LISTING_PAGE,
				),
				'messages'             => array(
					'something_went_wrong'        => __( 'Something went wrong. Please try again', 'hustle' ), // everywhere.
					'something_went_wrong_reload' => '<label class="wpmudev-label--notice"><span>' . __( 'Something went wrong. Please reload this page and try again.', 'hustle' ) . '</span></label>', // everywhere.
					/* translators: "Aweber" between "strong" tags */
					'aweber_migration_success'    => sprintf( esc_html__( '%s integration successfully migrated to the oAuth 2.0.', 'hustle' ), '<strong>' . esc_html__( 'Aweber', 'hustle' ) . '</strong>' ), // everywhere. views.js.
					'integraiton_required'        => '<label class="wpmudev-label--notice"><span>' . __( 'An integration is required on opt-in module.', 'hustle' ) . '</span></label>', // wizard and integrations.
					'module_deleted'              => __( 'Module successfully deleted.', 'hustle' ), // listing and dashboard.
					'shortcode_copied'            => __( 'Shortcode copied successfully.', 'hustle' ), // listing and dashboard.
					'commons'                     => array(
						'published' => __( 'Published', 'hustle' ), // dashboard and wizard.
						'draft'     => __( 'Draft', 'hustle' ), // dashboard and wizard.
						'dismiss'   => __( 'Dismiss', 'hustle' ), // everywhere, views.js.
					),
					'request_error_reload_notice' => __( 'There was an issue processing your request. Please reload the page and try again.', 'hustle' ),
				),
			);
		}

		/**
		 * Registers styles for the admin pages.
		 *
		 * @since 4.3.1
		 *
		 * @param string $page_slug Slug of the current page.
		 */
		public function register_styles( $page_slug ) {
			wp_enqueue_style( 'thickbox' );

			wp_register_style(
				'hstl-roboto',
				'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i',
				array(),
				Opt_In::VERSION
			);
			wp_register_style(
				'hstl-opensans',
				'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i',
				array(),
				Opt_In::VERSION
			);
			wp_register_style(
				'hstl-source',
				'https://fonts.googleapis.com/css?family=Source+Code+Pro',
				array(),
				Opt_In::VERSION
			);

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'wdev_ui' );
			wp_enqueue_style( 'wdev_notice' );
			wp_enqueue_style( 'hstl-roboto' );
			wp_enqueue_style( 'hstl-opensans' );
			wp_enqueue_style( 'hstl-source' );

			wp_enqueue_style(
				'sui_styles',
				Opt_In::$plugin_url . 'assets/css/shared-ui.min.css',
				array(),
				HUSTLE_SUI_VERSION
			);
		}

		/**
		 * Adds a class to the page body with the SUI version.
		 *
		 * @since 4.3.1
		 *
		 * @param  string $classes Current set of classes to be added.
		 * @return string
		 */
		public function add_admin_body_class( $classes ) {
			$formatted_version = str_replace( '.', '-', HUSTLE_SUI_VERSION );

			$classes .= ' sui-' . $formatted_version;

			return $classes;
		}

		/**
		 * Remove Get parameters for Hustle notices
		 *
		 * @since 4.3.1
		 *
		 * @param string[] $vars An array of query variables to remove from a URL.
		 * @return array
		 */
		public function remove_notice_params( $vars ) {
			$vars[] = 'show-notice';
			$vars[] = 'notice';
			$vars[] = 'notice-close';

			return $vars;
		}

		/**
		 * Exports a single module.
		 * Used by Dashboard and Listing.
		 *
		 * @since 4.0.0
		 * @since 4.2.0 Moved from Hustle_Modules_Common_Admin to this class.
		 */
		protected function export_module() {

			$nonce = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			if ( ! wp_verify_nonce( $nonce, 'hustle_module_export' ) ) {
				return;
			}
			$id = filter_input( INPUT_POST, 'id', FILTER_VALIDATE_INT );
			if ( ! $id ) {
				return;
			}
			// Plugin data.
			$plugin = get_plugin_data( WP_PLUGIN_DIR . '/' . Opt_In::$plugin_base_file );

			// Get module.
			$module = new Hustle_Module_Model( $id );
			if ( is_wp_error( $module ) ) {
				return;
			}

			// Export data.
			$settings = array(
				'plugin'     => array(
					'name'    => $plugin['Name'],
					'version' => Opt_In::VERSION,
					'network' => $plugin['Network'],
				),
				'timestamp'  => time(),
				'attributes' => $module->get_attributes(),
				'data'       => $module->get_data(),
				'meta'       => array(),
			);

			if ( 'optin' === $module->module_mode ) {
				$integrations = array();
				$providers    = Hustle_Providers::get_instance()->get_providers();
				foreach ( $providers as $slug => $provider ) {
					$provider_data = $module->get_provider_settings( $slug, false );
					if ( $provider_data && $provider->is_connected()
							&& $provider->is_form_connected( $id ) ) {
						$integrations[ $slug ] = $provider_data;
					}
				}

				$settings['meta']['integrations'] = $integrations;
			}

			$meta_names = $module->get_module_meta_names();
			foreach ( $meta_names as $meta_key ) {
				$settings['meta'][ $meta_key ] = json_decode( $module->get_meta( $meta_key ) );
			}
			/**
			 * Filename
			 */
			$filename = sprintf(
				'hustle-%s-%s-%s-%s.json',
				$module->module_type,
				gmdate( 'Ymd-his' ),
				get_bloginfo( 'name' ),
				$module->module_name
			);
			$filename = strtolower( $filename );
			$filename = sanitize_file_name( $filename );
			/**
			 * Print HTTP headers
			 */
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/bin; charset=' . get_option( 'blog_charset' ), true );
			/**
			 * Check PHP version, for PHP < 3 do not add options
			 */
			$version = phpversion();
			$compare = version_compare( $version, '5.3', '<' );
			if ( $compare ) {
				echo wp_json_encode( $settings );
				exit;
			}
			$option = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;
			echo wp_json_encode( $settings, $option );
			exit;
		}

		/**
		 * Filter related to TinyMCE
		 * Used by Settings and Wizard pages.
		 *
		 * @since 4.2.0 Moved from Hustle_Module_Admin to this class.
		 */
		protected function set_up_tinymce() {

			add_filter( 'tiny_mce_before_init', array( $this, 'set_tinymce_settings' ), 10, 2 );
			add_filter( 'wp_default_editor', array( $this, 'set_editor_to_tinymce' ) );
			add_filter( 'tiny_mce_plugins', array( $this, 'remove_despised_editor_plugins' ) );
		}

		/**
		 * Modify tinymce editor settings.
		 *
		 * @param array  $settings Registered settings.
		 * @param string $editor_id Current editor ID.
		 */
		public function set_tinymce_settings( $settings, $editor_id ) {
			$settings['paste_as_text'] = 'true';

			return $settings;
		}

		/**
		 * Sets default editor to tinymce for opt-in admin
		 *
		 * @param string $editor_type Current editor type.
		 * @return string
		 */
		public function set_editor_to_tinymce( $editor_type ) {
			return 'tinymce';
		}

		/**
		 * Removes unnecessary editor plugins
		 *
		 * @param array $plugins Registered plugins.
		 * @return mixed
		 */
		public function remove_despised_editor_plugins( $plugins ) {
			$k = array_search( 'fullscreen', $plugins, true );
			if ( false !== $k ) {
				unset( $plugins[ $k ] );
			}
			$plugins[] = 'paste';
			return $plugins;
		}

		/**
		 * Gets the current tab the page is on load.
		 * Used by wizards and the global settings page.
		 *
		 * @since 4.3.1
		 *
		 * @param boolean|string $default Default value.
		 * @return boolean|string
		 */
		protected function get_current_section( $default = false ) {
			$section = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_STRING );
			return empty( $section ) ? $default : $section;
		}

		/**
		 * SUI summary config.
		 *
		 * @since 4.2.0
		 *
		 * @param string|null $class Class to be added.
		 */
		protected function get_sui_summary_config( $class = null ) {
			$style     = '';
			$image_url = apply_filters( 'wpmudev_branding_hero_image', null );
			if ( ! empty( $image_url ) ) {
				$style = 'background-image:url(' . esc_url( $image_url ) . ')';
			}
			$sui = array(
				'summary' => array(
					'style'   => $style,
					'classes' => array(
						'sui-box',
						'sui-summary',
					),
				),
			);
			if ( ! empty( $class ) && is_string( $class ) ) {
				$sui['summary']['classes'][] = $class;
			}
			/**
			 * Dash integration
			 *
			 * @since 4.0.0
			 */
			$hide_branding  = apply_filters( 'wpmudev_branding_hide_branding', false );
			$branding_image = apply_filters( 'wpmudev_branding_hero_image', null );
			if ( $hide_branding && ! empty( $branding_image ) ) {
				$sui['summary']['classes'][] = 'sui-rebranded';
			} elseif ( $hide_branding && empty( $branding_image ) ) {
				$sui['summary']['classes'][] = 'sui-unbranded';
			}
			return $sui;
		}

		/**
		 * Gets the SUI classes according to the selected setitngs in WPMU Dev dashboard.
		 *
		 * @since 4.3.1
		 *
		 * @return string
		 */
		protected function get_sui_wrap_class() {
			$classes = array( 'sui-wrap', 'sui-wrap-hustle' );

			/**
			 * Add high contrast mode.
			 */
			$accessibility         = Hustle_Settings_Admin::get_hustle_settings( 'accessibility' );
			$is_high_contrast_mode = ! empty( $accessibility['accessibility_color'] );
			if ( $is_high_contrast_mode ) {
				$classes[] = 'sui-color-accessible';
			}

			/**
			 * Set hide branding.
			 *
			 * @since 4.0.0
			 */
			$hide_branding = apply_filters( 'wpmudev_branding_hide_branding', false );
			if ( $hide_branding ) {
				$classes[] = 'no-hustle';
			}
			/**
			 * Hero image.
			 *
			 * @since 4.0.0
			 */
			$image = apply_filters( 'wpmudev_branding_hero_image', 'hustle-default' );
			if ( empty( $image ) ) {
				$classes[] = 'no-hustle-hero';
			}

			$classes = apply_filters( 'hustle_sui_wrap_class', $classes );

			return implode( ' ', $classes );
		}

		/**
		 * Renders the modals.
		 * This abstract class renders the modals that are displayed on all hustle's pages.
		 * Each page should override this method to add the specific modals for it.
		 *
		 * @since 4.3.5
		 */
		protected function render_modals() {}
	}

endif;