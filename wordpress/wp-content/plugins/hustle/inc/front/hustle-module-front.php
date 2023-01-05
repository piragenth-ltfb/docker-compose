<?php
class Hustle_Module_Front {

	private $_modules = array();

	/**
	 * Contains the queued modules types as keys, 1 as the value.
	 * Used to queue the required styles only.
	 *
	 * @since 4.0.1
	 * @var array
	 */
	private $_module_types_to_display = array();
	private $_non_inline_modules      = array();
	private $_inline_modules          = array();

	/**
	 * Array with data about the modules.
	 * This is used to conditionally add scripts.
	 *
	 * @since 4.0.4
	 * @var array
	 */
	private $modules_data_for_scripts = array();

	private static $the_content_filter_priority = 20;

	const SHORTCODE = 'wd_hustle';

	/**
	 * Hustle_Module_Front constructor.
	 *
	 * @since unknown
	 */
	public function __construct() {

		// Schedule email cron action.
		add_action( 'hustle_send_email', array( 'Hustle_Mail', 'send_email' ), 10, 3 );
		add_action( 'hustle_aweber_token_refresh', array( 'Hustle_Aweber', 'refresh_token' ) );

		$is_preview = filter_input( INPUT_GET, 'hustle_preview', FILTER_VALIDATE_BOOLEAN ) && Opt_In_Utils::is_user_allowed( 'hustle_edit_module' );

		// Don't render Hustle's widgets and shortcodes on preview mode.
		if ( ! $is_preview ) {
			$this->register_shortcodes_and_widget();
		}

		// Abort here if it's admin or widget preview. Only register the shortcodes and widget.
		if ( is_admin() || isset( $_GET['widgetPreview'] ) ) {
			return;
		}

		if ( ! $is_preview ) {
			$this->prepare_for_front();
		} else {
			new Hustle_Module_Preview();
		}
	}

	private function prepare_for_front() {
		Hustle_Provider_Autoload::initiate_providers();
		Hustle_Provider_Autoload::load_block_editor();

		add_action(
			'wp_enqueue_scripts',
			array( $this, 'register_scripts' )
		);

		// Enqueue it in the footer to overrider all the css that comes with the popup.
		add_action(
			'wp_footer',
			array( $this, 'register_styles' )
		);

		add_action(
			'template_redirect',
			array( $this, 'create_modules' ),
			0
		);

		add_action(
			'wp_footer',
			array( $this, 'render_non_inline_modules' ),
			-1
		);

		add_filter( 'get_the_excerpt', array( $this, 'remove_the_content_filter' ), 9 );
		add_filter( 'wp_trim_excerpt', array( $this, 'restore_the_content_filter' ) );

		add_filter(
			'the_content',
			array( $this, 'show_after_page_post_content' ),
			self::$the_content_filter_priority
		);

		// NextGEN Gallery compat.
		add_filter(
			'run_ngg_resource_manager',
			array( $this, 'nextgen_compat' )
		);
	}

	/**
	 * Register shortcodes and widget.
	 *
	 * @since 4.3.1
	 *
	 * @return void
	 */
	private function register_shortcodes_and_widget() {
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_shortcode( self::SHORTCODE, array( $this, 'shortcode' ) );

		// Legacy custom content support.
		add_shortcode(
			'wd_hustle_cc',
			array( $this, 'shortcode' )
		);

		// Legacy social sharing support.
		add_shortcode(
			'wd_hustle_ss',
			array( $this, 'shortcode' )
		);

		// Unsubscribe shortcode.
		add_shortcode(
			'wd_hustle_unsubscribe',
			array( $this, 'unsubscribe_shortcode' )
		);
	}

	/**
	 * Don't apply the_content filter for excerpts.
	 *
	 * @param string $post_excerpt The post's excerpt.
	 */
	public function remove_the_content_filter( $post_excerpt ) {
		remove_filter( 'the_content', array( $this, 'show_after_page_post_content' ), self::$the_content_filter_priority );

		return $post_excerpt;
	}

	public function restore_the_content_filter( $text ) {
		add_filter( 'the_content', array( $this, 'show_after_page_post_content' ), self::$the_content_filter_priority );

		return $text;
	}

	public function register_widget() {
		register_widget( 'Hustle_Module_Widget' );
		register_widget( 'Hustle_Module_Widget_Legacy' );
	}

	public function register_scripts() {

		// There aren't any published modules. We don't need scripts.
		if ( ! count( $this->_modules ) ) {
			return;
		}

		$is_on_upfront_builder = class_exists( 'UpfrontThemeExporter' ) && function_exists( 'upfront_exporter_is_running' ) && upfront_exporter_is_running();
		if ( ! $is_on_upfront_builder ) {
			if ( is_customize_preview() || isset( $_REQUEST['fl_builder'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( ! is_singular() ) {
					return;
				}
				global $post;
				// Check for shortcode wd_hustle_unsubscribe.
				if ( ! preg_match( '/wd_hustle_unsubscribe/', $post->post_content ) ) {
					return;
				}
			}
		}

		// Register popup requirements.
		wp_register_script(
			'hustle_front',
			Opt_In::$plugin_url . 'assets/js/front.min.js',
			array( 'jquery', 'underscore' ),
			Opt_In::VERSION,
			true
		);

		$modules = apply_filters( 'hustle_front_modules', $this->_modules );
		wp_localize_script( 'hustle_front', 'Modules', $modules );

		// force set archive page slug.
		global $wp;
		$slug = is_home() && is_front_page() ? 'hustle-front-blog-page' : sanitize_title( $wp->request );

		$vars = apply_filters(
			'hustle_front_vars',
			array(
				'is_admin'              => is_admin(),
				'native_share_enpoints' => Hustle_SShare_Model::get_sharing_endpoints( false ),
				'ajaxurl'               => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
				'page_id'               => get_queried_object_id(), // Used in many places to decide whether to show the module and cookies.
				'page_slug'             => $slug, // Used in many places to decide whether to show the module and cookies on archive pages.
				'is_upfront'            => class_exists( 'Upfront' ) && isset( $_GET['editmode'] ) && 'true' === $_GET['editmode'], // Used.
				'script_delay'          => apply_filters( 'hustle_lazy_load_script_delay', 3000 ), // to lazyload script for later on added elements
			)
		);

		$modules_deps = $this->modules_data_for_scripts;

		// Datepicker. Add translated strings only if some module has a datepicker.
		if ( ! empty( $modules_deps['datepicker'] ) ) {
			$vars['days_and_months'] = array(
				'days_full'    => Hustle_Time_Helper::get_week_days(),
				'days_short'   => Hustle_Time_Helper::get_week_days( 'short' ),
				'days_min'     => Hustle_Time_Helper::get_week_days( 'min' ),
				'months_full'  => Hustle_Time_Helper::get_months(),
				'months_short' => Hustle_Time_Helper::get_months( 'short' ),
			);
		}
		wp_localize_script( 'hustle_front', 'incOpt', $vars );

		do_action( 'hustle_register_scripts' );

		// Queue adblocker if a module requires it.
		if ( ! empty( $modules_deps['adblocker'] ) ) {
			wp_enqueue_script(
				'hustle_front_ads',
				Opt_In::$plugin_url . 'assets/js/adblock.js',
				array(),
				Opt_In::VERSION,
				true
			);
		}

		// Queue recaptchas if required. Only added if the keys are set.
		if ( ! empty( $modules_deps['recaptcha'] ) ) {
			$this->add_recaptcha_script( $modules_deps['recaptcha']['language'] );
		}

		// Queue Pinteres if reuqired.
		if ( ! empty( $modules_deps['pinterest'] ) ) {
			wp_enqueue_script(
				'hustle_sshare_pinterest',
				'//assets.pinterest.com/js/pinit.js',
				array(),
				Opt_In::VERSION,
				true
			);
		}

		self::add_hui_scripts();
		wp_enqueue_script( 'hustle_front' );

		Opt_In_Utils::maybe_add_scripts_for_ie();
	}

	/**
	 * Add Hustle UI scripts.
	 * Used for displaying and previewing modules.
	 *
	 * @since 4.0
	 */
	public static function add_hui_scripts() {

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );

		// Register Hustle UI functions.
		wp_register_script(
			'hui_scripts',
			Opt_In::$plugin_url . 'assets/hustle-ui/js/hustle-ui.min.js',
			array( 'jquery' ),
			Opt_In::VERSION,
			true
		);

		wp_enqueue_script( 'hui_scripts' );
	}

	/**
	 * Enqueue the recaptcha script if recaptcha is globally configured.
	 *
	 * @since 4.0
	 * @since 4.0.3 param $recaptcha_versions and $is_preview added
	 *
	 * @param string $language reCAPTCHA language.
	 * @param bool   $is_preview if it's preview.
	 */
	public static function add_recaptcha_script( $language = '', $is_preview = false, $is_return = false ) {

		$recaptcha_settings = Hustle_Settings_Admin::get_recaptcha_settings();

		if ( empty( $language ) || 'automatic' === $language ) {
			$language = ! empty( $recaptcha_settings['language'] ) && 'automatic' !== $recaptcha_settings['language']
				? $recaptcha_settings['language'] : get_locale();
		}
		$script_url = 'https://www.google.com/recaptcha/api.js?render=explicit&hl=' . $language;

		if ( ! $is_return ) {
			wp_enqueue_script( 'recaptcha', $script_url, array(), false, true );

		} elseif ( $is_preview ) {
			return $script_url;
		}
	}

	/**
	 * Registeres front styles and fonts
	 */
	public function register_styles() {

		// There aren't any published modules. We don't need styles.
		if ( ! count( $this->_modules ) ) {
			return;
		}

		$is_on_upfront_builder = class_exists( 'UpfrontThemeExporter' ) && function_exists( 'upfront_exporter_is_running' ) && upfront_exporter_is_running();

		if ( ! $is_on_upfront_builder ) {
			if ( ! $this->has_modules() || isset( $_REQUEST['fl_builder'] ) ) { // CSRF ok.
				return;
			}
		}

		$module_types_to_display = array_keys( $this->_module_types_to_display );

		self::print_front_styles( $module_types_to_display, $this->modules_data_for_scripts );
		self::print_front_fonts( $this->modules_data_for_scripts['fonts'] );
	}

	/**
	 * Register and enqueue the required styles according to the given module's types.
	 * The accepted module's types are:
	 * popup, slidein, embedded, social_sharing, optin, informational, floating (ssharing), inline (ssharing).
	 *
	 * @since 4.0
	 * @since 4.0.1 enequeues only the given module's types.
	 * @since 4.2.0 $dependencies param added.
	 *
	 * @param array $module_types_to_display Array with the module's type to be displayed.
	 * @param array $dependencies Array with the module's style dependencies.
	 */
	public static function print_front_styles( $module_types_to_display = array(), $dependencies = array() ) {

		if ( ! empty( $dependencies['select2'] ) ) {
			wp_register_style(
				'select2',
				Opt_In::$plugin_url . 'assets/css/select2.min.css',
				array(),
				'4.0.6'
			);
			wp_enqueue_style( 'select2' );
		}

		wp_register_style(
			'hustle_icons',
			Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-icons.min.css',
			array(),
			Opt_In::VERSION
		);
		wp_enqueue_style( 'hustle_icons' );

		wp_register_style(
			'hustle_global',
			Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-global.min.css',
			array(),
			Opt_In::VERSION
		);
		wp_enqueue_style( 'hustle_global' );

		// Informational mode.
		if ( ! $module_types_to_display || in_array( Hustle_Module_Model::INFORMATIONAL_MODE, $module_types_to_display, true ) ) {

			wp_register_style(
				'hustle_info',
				Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-info.min.css',
				array(),
				Opt_In::VERSION
			);
			wp_enqueue_style( 'hustle_info' );
		}

		// Optin mode.
		if ( ! $module_types_to_display || in_array( Hustle_Module_Model::OPTIN_MODE, $module_types_to_display, true ) ) {

			wp_register_style(
				'hustle_optin',
				Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-optin.min.css',
				array(),
				Opt_In::VERSION
			);
			wp_enqueue_style( 'hustle_optin' );
		}

		// Popup type.
		if ( ! $module_types_to_display || in_array( Hustle_Module_Model::POPUP_MODULE, $module_types_to_display, true ) ) {

			wp_register_style(
				'hustle_popup',
				Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-popup.min.css',
				array(),
				Opt_In::VERSION
			);
			wp_enqueue_style( 'hustle_popup' );
		}

		// Slidein type.
		if ( ! $module_types_to_display || in_array( Hustle_Module_Model::SLIDEIN_MODULE, $module_types_to_display, true ) ) {

			wp_register_style(
				'hustle_slidein',
				Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-slidein.min.css',
				array(),
				Opt_In::VERSION
			);
			wp_enqueue_style( 'hustle_slidein' );
		}

		$has_social_sharing_module = in_array( Hustle_Module_Model::SOCIAL_SHARING_MODULE, $module_types_to_display, true );
		$has_embedded_module       = in_array( Hustle_Module_Model::EMBEDDED_MODULE, $module_types_to_display, true );

		// Social share and Embedded both need hustle-inline CSS.
		if ( ! $module_types_to_display || $has_social_sharing_module || $has_embedded_module ) {
			wp_register_style(
				'hustle_inline',
				Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-inline.min.css',
				array(),
				Opt_In::VERSION
			);
		}

		if ( ! $module_types_to_display || $has_embedded_module ) {
			wp_enqueue_style( 'hustle_inline' );
		}

		// SSharing type.
		if ( ! $module_types_to_display || $has_social_sharing_module ) {

			wp_register_style(
				'hustle_social',
				Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-social.min.css',
				array(),
				Opt_In::VERSION
			);
			wp_enqueue_style( 'hustle_social' );

			// Inline display.
			if ( ! $module_types_to_display || in_array( Hustle_SShare_Model::INLINE_MODULE, $module_types_to_display, true ) ) {

				wp_enqueue_style( 'hustle_inline' );
			}

			// Floating display.
			if ( ! $module_types_to_display || in_array( Hustle_SShare_Model::FLOAT_MODULE, $module_types_to_display, true ) ) {

				wp_register_style(
					'hustle_float',
					Opt_In::$plugin_url . 'assets/hustle-ui/css/hustle-float.min.css',
					array(),
					Opt_In::VERSION
				);
				wp_enqueue_style( 'hustle_float' );
			}
		}
	}

	/**
	 * Enqueues the required Google fonts to be included in front.
	 *
	 * @since unknown
	 * @return void|string
	 */
	public static function print_front_fonts( $fonts, $is_ajax = false ) {
		if ( empty( $fonts ) ) {
			return;
		}

		$families_args = array();
		foreach ( $fonts as $font_family => $variations ) {
			$families_args[] = $font_family . ':' . implode( ',', array_unique( $variations ) );
		}

		// The final URL must have a 'family' parameter with all font families and variations
		// formatted like ?family=Tangerine:bold,bolditalic|Inconsolata:italic|Droid+Sans .
		$google_font_url = add_query_arg(
			array(
				'family'  => implode( '|', $families_args ),
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css'
		);

		$id = 'hustle-fonts';
		if ( ! $is_ajax ) {
			wp_enqueue_style( $id, $google_font_url, array(), '1.0' );
		} else {
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
			return '<link rel="stylesheet" id="' . $id . '" href="' . $google_font_url . '" media="all">';
		}
	}

	/**
	 * Enqueue modules to be displayed on Frontend.
	 */
	public function create_modules() {

		// Retrieve all active modules.
		$modules            = apply_filters( 'hustle_sort_modules', Hustle_Module_Collection::instance()->get_all( true ) );
		$datepicker_found   = false;
		$recaptcha_found    = false;
		$select2_found      = false;
		$recaptcha_language = '';
		$enqueue_adblock    = false;
		$pinterest_found    = false;

		/**
		 * Disables the load of Google fonts in frontend.
		 *
		 * @since unknown
		 *
		 * @param bool Whether Google fonts should be used.
		 */
		$use_google_fonts = apply_filters( 'hustle_load_google_fonts', true );
		$google_fonts     = array();

		foreach ( $modules as $module ) {

			if ( ! $module instanceof Hustle_Model || ! $module->active ) {
				continue;
			}

			// Check the schedule. Ssharing modules don't have schedules.
			if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {
				$settings = $module->get_settings();
				if ( ! $settings->is_currently_scheduled() ) {
					continue;
				}
			}

			$is_non_inline_module = ( Hustle_Module_Model::POPUP_MODULE === $module->module_type || Hustle_Module_Model::SLIDEIN_MODULE === $module->module_type );

			if ( ! $module->get_visibility()->is_allowed_to_display( $module->module_type ) ) {

				// If shortcode is enabled for inline modules, don't abort.
				// Shortcodes shouldn't follow the visibility conditions.
				if ( ! $is_non_inline_module ) {
					$display_options = $module->get_display()->to_array();
					if ( '1' !== $display_options['shortcode_enabled'] ) {
						continue;
					}
				} else {
					continue;
				}
			}

			// Setting up stuff for all modules except social sharing.
			if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {
				$module->load();

				// Skip if Google fonts were deativated via hook.
				if ( $use_google_fonts ) {
					$google_fonts = array_merge_recursive( $google_fonts, $module->get_google_fonts() );
				}

				if ( 'optin' === $module->module_mode ) {

					if ( ! $datepicker_found || empty( $recaptcha_language ) ) {
						$form_fields = $module->get_form_fields();

						// Datepicker.
						// Check if the module has a datepicker unless we already found one in other modules.
						// We'll localize some variables if the modules have a datepicker.
						if ( ! $datepicker_found ) {
							$field_types      = wp_list_pluck( $form_fields, 'type', true );
							$datepicker_found = in_array( 'datepicker', $field_types, true );
						}

						// Recaptcha.
						// Check if the module has a recaptcha to enqueue scripts unless we already found one.
						// We'll queue the script afterwards.
						if ( ! empty( $form_fields['recaptcha'] ) && empty( $recaptcha_language ) ) {

							$recaptcha_found = true;

							$recaptcha_field = $form_fields['recaptcha'];
							// Get only first recaptcha language. Skip if not set or it's "automatic".
							if ( ! empty( $recaptcha_field['recaptcha_language'] ) && 'automatic' !== $recaptcha_field['recaptcha_language'] ) {
								$recaptcha_language = $recaptcha_field['recaptcha_language'];
							}
						}
					}

					// Select2.
					// We're only using select2 for Mailchimp dropdown groups.
					if ( ! $select2_found ) {
						$mailchimp_settings = $module->get_provider_settings( 'mailchimp' );
						if (
							! empty( $mailchimp_settings ) &&
							! is_null( $mailchimp_settings['group'] ) &&
							'-1' !== $mailchimp_settings['group'] &&
							'dropdown' === $mailchimp_settings['group_type']
						) {
							$select2_found = true;
						}
					}
				}

				// For popups and slideins.
				if ( $is_non_inline_module ) {
					$this->_non_inline_modules[ $module->module_id ] = $module;

					if ( ! $enqueue_adblock ) {

						$settings = $module->get_settings()->to_array();

						// If trigger is adblock.
						if ( in_array( 'adblock', $settings['triggers']['trigger'], true ) ) {
							$enqueue_adblock = true;
						}
					}
				} elseif ( Hustle_Module_Model::EMBEDDED_MODULE === $module->module_type ) {
					$this->_inline_modules[ $module->module_id ] = $module;
				}
			} else { // Social sharing modules.
				$ssharing_networks = $module->get_content()->get_social_icons();
				if (
					! empty( $ssharing_networks )
					&& in_array( 'pinterest', array_keys( $ssharing_networks ), true )
					&& empty( $ssharing_networks['pinterest']['link'] )
				) {
					$pinterest_found = true;
				}
				$this->_inline_modules[ $module->module_id ] = $module;

				$this->_non_inline_modules[ $module->module_id ] = $module;
			}

			$this->log_module_type_to_load_styles( $module );

			$this->_modules[] = $module->get_module_data_to_display();

		} // End looping through the modules.

		// Set flag for scripts: datepicker field.
		if ( $datepicker_found ) {
			$this->modules_data_for_scripts['datepicker'] = true;
		}

		// Set flag for scripts: adblocker.
		if ( $enqueue_adblock ) {
			$this->modules_data_for_scripts['adblocker'] = true;
		}

		// Set flag for scripts: select2.
		if ( $select2_found ) {
			$this->modules_data_for_scripts['select2'] = true;
		}

		// Set flag for scripts: recaptcha field.
		if ( $recaptcha_found ) {
			$this->modules_data_for_scripts['recaptcha'] = array( 'language' => $recaptcha_language );
		}

		if ( $pinterest_found ) {
			$this->modules_data_for_scripts['pinterest'] = true;
		}

		$this->modules_data_for_scripts['fonts'] = $google_fonts;
	}

	/**
	 * Store the modules' types to be displayed in order to enqueue
	 * their required styles.
	 * Called within self::create_modules() method.
	 *
	 * @since 4.0.1
	 *
	 * @param Hustle_Model $module Current module to check.
	 */
	private function log_module_type_to_load_styles( Hustle_Model $module ) {

		// Keep track of the of the modules types and modes to display
		// in order to queue the required styles only.
		$this->_module_types_to_display[ $module->module_type ] = 1;

		// Register the module mode for non SSharing modules.
		if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {
			$this->_module_types_to_display[ $module->module_mode ] = 1;

		} else { // Register the module display type for SSharing modules.

			// Floating display.
			if (
				$module->is_display_type_active( Hustle_SShare_Model::FLOAT_MOBILE ) ||
				$module->is_display_type_active( Hustle_SShare_Model::FLOAT_DESKTOP )
			) {
				$this->_module_types_to_display[ Hustle_SShare_Model::FLOAT_MODULE ] = 1;
			}

			// Inline display.
			if (
				$module->is_display_type_active( Hustle_SShare_Model::INLINE_MODULE ) ||
				$module->is_display_type_active( Hustle_SShare_Model::WIDGET_MODULE ) ||
				$module->is_display_type_active( Hustle_SShare_Model::SHORTCODE_MODULE )
			) {
				$this->_module_types_to_display[ Hustle_SShare_Model::INLINE_MODULE ] = 1;
			}
		}
	}

	/**
	 * Check if current page has renderable opt-ins.
	 **/
	public function has_modules() {
		$has_modules = ! empty( $this->_non_inline_modules ) || ! empty( $this->_inline_modules );
		return apply_filters( 'hustle_front_handler', $has_modules );
	}

	/**
	 * By-pass NextGEN Gallery resource manager
	 *
	 * @return false
	 */
	public function nextgen_compat() {
		return false;
	}

	public function render_non_inline_modules() {

		foreach ( $this->_non_inline_modules as $module ) {

			if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {
				$module->display();

			} elseif ( $module->is_display_type_active( Hustle_SShare_Model::FLOAT_DESKTOP ) || $module->is_display_type_active( Hustle_SShare_Model::FLOAT_MOBILE ) ) {
				$module->display( Hustle_SShare_Model::FLOAT_MODULE );
			}
		}
	}

	/**
	 * Handles the data for the unsubscribe shortcode
	 *
	 * @since 3.0.5
	 * @param array $atts The values passed through the shortcode attributes
	 * @return string The content to be rendered within the shortcode.
	 */
	public function unsubscribe_shortcode( $atts ) {
		$messages = Hustle_Settings_Admin::get_unsubscribe_messages();
		if ( isset( $_GET['token'] ) && isset( $_GET['email'] ) ) { // WPCS: CSRF ok.
			$error_message  = $messages['invalid_data'];
			$sanitized_data = Opt_In_Utils::validate_and_sanitize_fields( $_GET ); // WPCS: CSRF ok.
			$email          = $sanitized_data['email'];
			$nonce          = $sanitized_data['token'];
			// checking if email is valid
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				return $error_message;
			}
			$entry        = new Hustle_Entry_Model();
			$unsubscribed = $entry->unsubscribe_email( $email, $nonce );
			if ( $unsubscribed ) {
				return $messages['successful_unsubscription'];
			} else {
				return $error_message;
			}
		}
		// Show all modules' lists by default.
		$attributes = shortcode_atts( array( 'id' => '-1' ), $atts );
		$params     = array(
			'ajax_step'         => false,
			'shortcode_attr_id' => $attributes['id'],
			'messages'          => $messages,
		);

		$renderer = new Hustle_Layout_Helper();
		$html     = $renderer->render( 'general/unsubscribe-form', $params, true );
		apply_filters( 'hustle_render_unsubscribe_form_html', $html, $params );
		return $html;
	}

	/**
	 * Render the modules' wrapper to render the actual module using their shortcodes.
	 *
	 * @since the beginning of time.
	 *
	 * @param array  $atts
	 * @param string $content
	 * @return string
	 */
	public function shortcode( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'id'        => '',
				'type'      => 'embedded',
				'css_class' => '',
			),
			$atts,
			self::SHORTCODE
		);

		if ( empty( $atts['id'] ) ) {
			return '';
		}

		$type = $atts['type'];

		// If shortcode type is not embed or sshare.
		if ( 'embedded' !== $type && 'social_sharing' !== $type ) {
			// Do not enforce embedded/social_sharing type.
			$enforce_type = false;
		} else {
			// Enforce embedded/social_sharing type.
			$enforce_type = true;
		}

		$module_id = Hustle_Model::get_module_id_by_shortcode_id( $atts['id'] );

		$custom_classes = esc_attr( $atts['css_class'] );

		if ( isset( $this->_inline_modules[ $module_id ] ) ) {
			$module = $this->_inline_modules[ $module_id ];

			if ( ! $module->is_display_type_active( Hustle_Module_Model::SHORTCODE_MODULE ) ) {
				return '';
			}

			// Display the module.
			ob_start();

			$module->display( Hustle_Module_Model::SHORTCODE_MODULE, $custom_classes );

			return ob_get_clean();
		}

		if ( isset( $this->_non_inline_modules[ $module_id ] ) && ! empty( $content ) ) {
			$module = $this->_non_inline_modules[ $module_id ];

			// If shortcode click trigger is disabled, print nothing.
			$settings = $module->get_settings()->to_array();
			if ( ! isset( $settings['triggers']['enable_on_click_shortcode'] ) || '0' === $settings['triggers']['enable_on_click_shortcode'] ) {
				return '';
			}

			return sprintf(
				'<a href="#" class="%s hustle_module_%s %s" data-id="%s" data-type="%s">%s</a>',
				'hustle_module_shortcode_trigger',
				esc_attr( $module->id ),
				esc_attr( $custom_classes ),
				esc_attr( $module->id ),
				esc_attr( $type ),
				wp_kses_post( $content )
			);
		}

		return '';
	}

	/**
	 * Display inline modules.
	 * Embedded and Social Sharing modules only.
	 *
	 * @since the beginning of time.
	 *
	 * @param $content
	 * @return string
	 */
	public function show_after_page_post_content( $content ) {

		// Return the content immediately if there are no modules or the page doesn't have a content to embed into.
		if ( ! count( $this->_inline_modules ) || isset( $_REQUEST['fl_builder'] ) || is_home() || is_archive() ) { // CSRF: ok.
			return $content;
		}

		$in_the_loop = in_the_loop();

		/**
		 * Filters whether to skip inline modules because we're not in da loop.
		 * This can also be used to prevent loading the inline modules conditionally.
		 *
		 * @param bool $in_the_loop Returned value from WordPress' in_the_loop() function.
		 * @since 4.2.0
		 */
		$is_in_da_loop = apply_filters( 'hustle_inline_modules_render_in_the_loop', $in_the_loop );

		// We only render the inline modules in the first call of 'the_content' filter.
		// Prevent the modules from being printed when they shouldn't and
		// leaving the page's main content without them.
		if ( ! $is_in_da_loop ) {
			return $content;
		}

		$modules = apply_filters( 'hustle_inline_modules_to_display', $this->_inline_modules );

		foreach ( $modules as $module ) {

			// Skip if "inline" display is disabled.
			if ( ! $module->is_display_type_active( Hustle_Module_Model::INLINE_MODULE ) ) {
				continue;
			}

			$custom_classes = apply_filters( 'hustle_inline_module_custom_classes', '', $module );

			ob_start();
			$module->display( Hustle_Module_Model::INLINE_MODULE, $custom_classes );
			$module_markup = ob_get_clean();

			$display          = $module->get_display()->to_array();
			$display_position = $display['inline_position'];

			if ( 'both' === $display_position ) {
				$content = $module_markup . $content . $module_markup;

			} elseif ( 'above' === $display_position ) {
				$content = $module_markup . $content;

			} else { // Below.
				$content .= $module_markup;

			}
		}

		$is_render_inline_once = true;
		/**
		 * Filters whether to render the inline modules once.
		 * By default, we only render the inline modules in the first call of 'the_content' filter.
		 * Allow rendering them in following calls of the filter if needed.
		 *
		 * @param bool $is_render_inline_once Whether to render the inline modules in several calls.
		 * @since 4.2.0
		 */
		$is_render_inline_once = apply_filters( 'hustle_inline_modules_render_once', $is_render_inline_once );

		if ( $is_render_inline_once ) {
			remove_filter( 'the_content', array( $this, 'show_after_page_post_content' ), self::$the_content_filter_priority );
		}

		return $content;
	}
}