<?php
/**
 * File for Hustle_Module_Page_Abstract class.
 *
 * @package Hustle
 * @since 4.0.1
 */

/**
 * Class Hustle_Module_Page.
 * Base model for the listing pages and their wizards.
 *
 * @since 4.0.1
 */
abstract class Hustle_Module_Page_Abstract extends Hustle_Admin_Page_Abstract {

	/**
	 * Edit page slug defined by WordPress when registering the page.
	 *
	 * @since 4.3.1
	 * @var string
	 */
	private $page_edit_slug;

	/**
	 * Wizard page slug assigned by us.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $page_edit;

	/**
	 * Wizard page title.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $page_edit_title;

	/**
	 * Capability required for the wizard page to be available.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $page_edit_capability;

	/**
	 * Path to the wizard's template page relative to the 'views' folder.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $page_edit_template_path;

	/**
	 * Current module. Only set on wizards when the module exists.
	 *
	 * @since 4.0.3
	 * @var integer
	 */
	protected $module = false;

	/**
	 * Count of the active module of the current type.
	 *
	 * @since 4.2.0
	 * @var integer
	 */
	private $module_count_type;

	/**
	 * Module type this page belongs to.
	 *
	 * @since 4.2.0
	 * @var string
	 */
	public $module_type;

	/**
	 * Established the properties for the page.
	 *
	 * @since 4.0.1
	 */
	protected function init() {

		$this->set_page_properties();

		$this->page_menu_title = $this->page_title;

		$this->page = Hustle_Data::get_listing_page_by_module_type( $this->module_type );

		$this->page_capability = 'hustle_edit_module';

		$this->page_edit = Hustle_Data::get_wizard_page_by_module_type( $this->module_type );

		$this->page_edit_capability = 'hustle_edit_module';

		/* translators: module's type */
		$this->page_edit_title = sprintf( esc_html__( 'New %s', 'hustle' ), Opt_In_Utils::get_module_type_display_name( $this->module_type ) );

		add_filter( 'submenu_file', array( $this, 'admin_submenu_file' ), 10, 2 );

		add_action( 'admin_head', array( $this, 'hide_unwanted_submenus' ) );

		// Admin-menu-editor compatibility.
		add_action( 'admin_menu_editor-menu_replaced', array( $this, 'hide_unwanted_submenus' ) );

		// Actions to perform when the current page is the listing or the wizard page.
		if ( ! empty( $this->current_page ) && ( $this->current_page === $this->page || $this->current_page === $this->page_edit ) ) {
			$this->on_listing_and_wizard_actions();
		}

	}

	/**
	 * Set up the page's own properties
	 * Like the current module type, page title, path to the listing page and wizard page template.
	 *
	 * @since 4.0.2
	 */
	abstract protected function set_page_properties();

	/**
	 * Actions to be performed on Dashboard page.
	 *
	 * @since 4.0.4
	 */
	protected function on_listing_and_wizard_actions() {

		if ( $this->page_edit === $this->current_page ) {
			$this->on_wizard_only_actions();
		} else {
			$this->on_listing_only_actions();
		}
	}

	/**
	 * Method called when the action 'load-' . $this->page_slug runs.
	 * That is, Listing page only.
	 *
	 * @since 4.2.0
	 */
	public function current_page_loaded() {
		parent::current_page_loaded();
	}

	/**
	 * Actions to run on listing pages only
	 *
	 * @since 4.2.0
	 */
	private function on_listing_only_actions() {
		add_filter( 'removable_query_args', array( $this, 'maybe_remove_paged' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_listing_scripts' ), 99 );
	}

	/**
	 * Remove paged get attribute if there isn't a module and it's not the first page
	 *
	 * @since 4.0.0
	 * @param array $removable_query_args URL query args to be removed.
	 * @return array
	 */
	public function maybe_remove_paged( $removable_query_args ) {
		$paged       = filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT );
		$module_type = $this->module_type;

		if ( $paged && 1 !== $paged && $module_type ) {
			$args             = array(
				'module_type' => $module_type,
				'page'        => $paged,
			);
			$entries_per_page = Hustle_Settings_Admin::get_per_page( 'module' );
			$modules          = Hustle_Module_Collection::instance()->get_all( null, $args, $entries_per_page );
			if ( empty( $modules ) ) {
				$_SERVER['REQUEST_URI'] = remove_query_arg( 'paged' );
				$removable_query_args[] = 'paged';
				unset( $_GET['paged'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		if ( $module_type ) {
			self::maybe_view_stats( $module_type );
		}

		return $removable_query_args;
	}

	/**
	 * Change pagination page to the relevant one for View Stats links
	 *
	 * @param string $module_type Module type.
	 */
	private static function maybe_view_stats( $module_type ) {
		$module_id = filter_input( INPUT_GET, 'view-stats', FILTER_VALIDATE_INT );
		if ( ! $module_id ) {
			return;
		}
		$module_id        = (string) $module_id;
		$args             = array(
			'module_type' => $module_type,
			'fields'      => 'ids',
		);
		$entries_per_page = Hustle_Settings_Admin::get_per_page( 'module' );
		$modules          = Hustle_Module_Collection::instance()->get_all( null, $args );
		$i                = array_search( $module_id, $modules, true );
		if ( false === $i ) {
			return;
		}

		$paged = ceil( ( $i + 1 ) / $entries_per_page );
		if ( 1 < $paged ) {
			$_GET['paged'] = $paged;
		}
	}

	/**
	 * Actions to run on wizard pages only
	 *
	 * @since 4.0.3
	 */
	private function on_wizard_only_actions() {

		// Set the current module on Wizards, abort if invalid.
		$module_id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );
		$module    = Hustle_Module_Collection::instance()->return_model_from_id( $module_id );

		if ( is_wp_error( $module ) ) {
			// Redirect asap.
			add_action( 'admin_init', array( $this, 'redirect_module_not_found' ) );
			return;
		}

		$this->module = $module;

		// Scripts for all wizards.
		add_action( 'admin_enqueue_scripts', array( $this, 'register_wizard_scripts' ) );

		if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $this->module_type ) {

			// Scripts for non ssharing wizards.
			add_action( 'admin_enqueue_scripts', array( $this, 'register_non_sshare_wizard_scripts' ) );

			// Allow rich editor in.
			add_filter( 'user_can_richedit', '__return_true' );

			// Set up all the filters and buttons for tinymce editors.
			$this->set_up_tinymce();

			// Add hustle's button to tinymce editor.
			add_filter( 'mce_buttons', array( $this, 'register_tinymce_buttons' ) );

			add_filter( 'mce_external_plugins', array( $this, 'add_hustle_tinymce_button_and_remove_externals' ) );
		}
	}

	/**
	 * Scripts used in all wizards
	 * They used to be enqueued by Hustle_Module_Admin.
	 *
	 * @since 4.2.0
	 */
	public function register_wizard_scripts() {

		wp_enqueue_script( 'jquery-ui-sortable' );

		wp_register_script( 'wp-color-picker-alpha', Opt_In::$plugin_url . 'assets/js/vendor/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '1.2.2', true );

		$color_picker_strings = array(
			'clear'            => __( 'Clear', 'hustle' ),
			'clearAriaLabel'   => __( 'Clear color', 'hustle' ),
			'defaultString'    => __( 'Default', 'hustle' ),
			'defaultAriaLabel' => __( 'Select default color', 'hustle' ),
			'pick'             => __( 'Select Color', 'hustle' ),
			'defaultLabel'     => __( 'Color value', 'hustle' ),
		);
		wp_localize_script( 'wp-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );
		wp_enqueue_script( 'wp-color-picker-alpha' );
	}

	/**
	 * Scripts used in all listings.
	 * They used to be enqueued by Hustle_Module_Admin.
	 *
	 * @since 4.2.0
	 */
	public function register_listing_scripts() {

		wp_enqueue_script(
			'chartjs',
			Opt_In::$plugin_url . 'assets/js/vendor/chartjs/Chart.bundle.min.js',
			array(),
			'2.7.2',
			true
		);
	}

	/**
	 * Register the scripts used in wizards, but not in the Ssharing one
	 * Scripts used to be enqueued by Hustle_Module_Admin.
	 *
	 * @since 4.2.0
	 */
	public function register_non_sshare_wizard_scripts() {

		wp_enqueue_script( 'thickbox' );
		wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );

//		wp_enqueue_script(
//			'optin_admin_ace',
//			Opt_In::$plugin_url . 'assets/js/vendor/ace/ace.js',
//			array(),
//			Opt_In::VERSION,
//			true
//		);

		Opt_In_Utils::maybe_add_scripts_for_ie();

		// Datepicker and timpicker for automated email in optins.
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script(
			'jquery-ui-timepicker',
			Opt_In::$plugin_url . 'assets/js/vendor/jquery.timepicker.min.js',
			array( 'jquery' ),
			'1.3.5',
			true
		);

		wp_enqueue_style(
			'jquery-ui-timepicker',
			Opt_In::$plugin_url . 'assets/css/jquery.timepicker.min.css',
			array(),
			'1.3.5'
		);

		// Register moment.js and its timezone extension.
		// Used for schedule, to calculate time with timezones on client side.
		wp_enqueue_script(
			'hustle-moment',
			Opt_In::$plugin_url . 'assets/js/vendor/moment.min.js',
			array( 'jquery' ),
			Opt_In::VERSION,
			true
		);

		wp_enqueue_script(
			'hustle-moment-timezone',
			Opt_In::$plugin_url . 'assets/js/vendor/moment-timezone-with-data.min.js',
			array( 'hustle-moment' ),
			Opt_In::VERSION,
			true
		);
	}

	/**
	 * Removing all MCE external plugins which often break our pages and add Hustle's button
	 *
	 * @since 3.0.8
	 * @param array $external_plugins External plugins.
	 * @return array
	 */
	public function add_hustle_tinymce_button_and_remove_externals( $external_plugins ) {
		remove_all_filters( 'mce_external_plugins' );

		$external_plugins           = array();
		$external_plugins['hustle'] = Opt_In::$plugin_url . 'assets/js/vendor/tiny-mce-button.js';
		add_action( 'admin_footer', array( $this, 'add_tinymce_variables' ) );

		return $external_plugins;
	}

	/**
	 * Add the current fields to the editor's selector
	 *
	 * @since 3.0.8
	 */
	public function add_tinymce_variables() {

		$var_button   = array();
		$saved_fields = $this->module->get_form_fields();
		$var_button   = array();

		if ( is_array( $saved_fields ) && ! empty( $saved_fields ) ) {
			$fields         = array();
			$ignored_fields = Hustle_Entry_Model::ignored_fields();

			foreach ( $saved_fields as $field_name => $data ) {
				if ( ! in_array( $data['type'], $ignored_fields, true ) ) {
					$fields[ $field_name ] = $data['label'];
				}
			}

			$available_editors = array( 'success_message', 'email_body' );

			/**
			 * Print JS details for the custom TinyMCE "Insert Variable" button
			 *
			 * @see assets/js/vendor/tiny-mce-button.js
			 */
			$var_button = array(
				'button_title'      => __( 'Add Hustle Fields', 'hustle' ),
				'fields'            => $fields,
				'available_editors' => $available_editors,
			);
		}

		printf(
			'<script>window.hustleData = %s;</script>',
			wp_json_encode( $var_button )
		);
	}

	/**
	 * Register hustle's button for tinymce
	 *
	 * @since 4.0.0
	 * @since 4.2.0 Moved from Hustle_Module_Admin to this class.
	 *
	 * @param array $buttons Registered buttons.
	 * @return array
	 */
	public function register_tinymce_buttons( $buttons ) {
		array_unshift( $buttons, 'hustlefields' );
		return $buttons;
	}

	/**
	 * Register the listing page and its wizard
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		parent::register_admin_menu();

		$this->page_edit_slug = add_submenu_page( 'hustle', $this->page_edit_title, $this->page_edit_title, $this->page_edit_capability, $this->page_edit, array( $this, 'render_edit_page' ) );

		add_action( 'load-' . $this->page_edit_slug, array( $this, 'current_page_loaded' ) );
	}

	/**
	 * Get the arguments used when rendering the main page.
	 *
	 * @since 4.0.1
	 * @return array
	 */
	protected function get_page_template_args() {

		$entries_per_page = Hustle_Settings_Admin::get_per_page( 'module' );

		$capability = array(
			'hustle_create'        => current_user_can( 'hustle_create' ),
			'hustle_access_emails' => current_user_can( 'hustle_access_emails' ),
		);

		// Don't use filter_input() here, because of see Hustle_Module_Admin::maybe_remove_paged function.
		$paged = ! empty( $_GET['paged'] ) ? (int) $_GET['paged'] : 1; // phpcs:ignore

		$modules = Hustle_Module_Collection::instance()->get_all(
			null,
			array(
				'module_type' => $this->module_type,
				'page'        => $paged,
				'filter'      => array( 'can_edit' => true ),
			),
			$entries_per_page
		);

		$active_modules = Hustle_Module_Collection::instance()->get_all(
			true,
			array(
				'module_type' => $this->module_type,
				'count_only'  => true,
			)
		);

		return array(
			'total'            => $this->get_total_count_modules_current_type(),
			'active'           => $active_modules,
			'modules'          => $modules,
			'is_free'          => Opt_In_Utils::_is_free(),
			'capability'       => $capability,
			'entries_per_page' => $entries_per_page,
			'message'          => filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING ),
			'sui'              => $this->get_sui_summary_config( 'sui-summary-sm' ),
		);
	}

	/**
	 * Hide module's edit pages from the submenu on dashboard.
	 *
	 * @since 4.0.1
	 */
	public function hide_unwanted_submenus() {
		remove_submenu_page( 'hustle', $this->page_edit );
	}

	/**
	 * Highlight submenu's parent on admin page.
	 *
	 * @since 4.0.1
	 *
	 * @param string $submenu_file The submenu file.
	 * @param string $parent_file The submenu item's parent file.
	 *
	 * @return string
	 */
	public function admin_submenu_file( $submenu_file, $parent_file ) {
		global $plugin_page;

		if ( 'hustle' !== $parent_file ) {
			return $submenu_file;
		}

		if ( $this->page_edit === $plugin_page ) {
			$submenu_file = $this->page;
		}

		return $submenu_file;
	}

	/**
	 * Redirect to the listing page when in wizard and the module wasn't found.
	 *
	 * @since 4.0.0
	 */
	public function redirect_module_not_found() {

		// We're on wizard, but the current module isn't valid. Aborting.
		$url = add_query_arg(
			array(
				'page'         => $this->page,
				'show-notice'  => 'error',
				'notice'       => 'module-not-found',
				'notice-close' => 'false',
			),
			'admin.php'
		);

		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * Add data to the current json array.
	 *
	 * @since 4.3.1
	 *
	 * @return array
	 */
	protected function get_vars_to_localize() {
		$current_array = parent::get_vars_to_localize();

		// Wizard page only.
		if ( $this->module ) {

			$data         = $this->module->get_data();
			$module_metas = $this->module->get_module_metas_as_array();

			$current_array = $this->register_visibility_conditions_js_vars( $current_array );

			$current_array += $this->get_wizard_js_variables_to_localize();

			$current_array['current'] = array_merge(
				$module_metas,
				array(
					'is_optin'     => 'optin' === $this->module->module_mode,
					'listing_page' => $this->page,
					'wizard_page'  => $this->page_edit,
					'section'      => $this->get_current_section(),
					'data'         => $data,
					'shortcode_id' => $this->module->get_shortcode_id(),
				)
			);

			$type_capitalized = Opt_In_Utils::get_module_type_display_name( $this->module_type, false, true );
			$type_lowercase   = Opt_In_Utils::get_module_type_display_name( $this->module_type );

			$messages = array(
				'module_error'        => __( "Couldn't save your module settings because there were some errors on {page} tab(s). Please fix those errors and try again.", 'hustle' ),
				'module_error_reload' => __( 'Something went wrong. Please reload this page and try saving again', 'hustle' ),
				/* translators: 1. module type capitalized, 2. module type in lowercase */
				'module_created'      => sprintf( __( '%1$s created successfully. Get started by adding content to your new %2$s below.', 'hustle' ), $type_capitalized, $type_lowercase ), // only when 'is_new'.
			);

			$current_array['single_module_action_nonce'] = wp_create_nonce( 'hustle_single_action' );

			$current_array['messages'] = array_merge( $current_array['messages'], $messages );

			// Listing page only.
		} elseif ( $this->page === $this->current_page ) {

			$current_array['current'] = array(
				'wizard_page' => $this->page_edit,
				'module_type' => $this->module_type,
			);

			$current_array['labels'] = array(
				/* translators: number of conversions */
				'submissions' => __( '%d Conversions', 'hustle' ),
				/* translators: number of views */
				'views'       => __( '%d Views', 'hustle' ),
			);

			// Also defined in dashboard.
			$current_array['single_module_action_nonce'] = wp_create_nonce( 'hustle_single_action' );

			$total_modules            = $this->get_total_count_modules_current_type();
			$module_not_found_message = esc_html__( "Oops! The module you are looking for doesn't exist.", 'hustle' );

			if ( 0 < $total_modules && current_user_can( 'hustle_create' ) ) {
				$module_not_found_message .= sprintf(
					/* translators: 1. opening 'a' tag for adding a new module, 2. closing 'a' tag, 3. opening 'a' tag for importing */
					esc_html__( ' You can %1$screate%2$s a new module or %3$simport%2$s an existing module.', 'hustle' ),
					'<a href="#" class="hustle-create-module">',
					'</a>',
					'<a href="#" class="hustle-import-module-button">'
				);
			}

			$messages = array(
				'module_imported'       => esc_html__( 'Module successfully imported.', 'hustle' ),
				'module_duplicated'     => esc_html__( 'Module successfully duplicated.', 'hustle' ),
				'module_tracking_reset' => esc_html__( "Module's tracking data successfully reset.", 'hustle' ),
				'module-not-found'      => $module_not_found_message,
			);

			$current_array['messages'] = array_merge( $current_array['messages'], $messages );
		}

		// Both Wizard and Listing pages.
		$current_array['messages']['days_and_months'] = array(
			'days_full'    => Hustle_Time_Helper::get_week_days(),
			'days_short'   => Hustle_Time_Helper::get_week_days( 'short' ),
			'days_min'     => Hustle_Time_Helper::get_week_days( 'min' ),
			'months_full'  => Hustle_Time_Helper::get_months(),
			'months_short' => Hustle_Time_Helper::get_months( 'short' ),
		);

		$current_array['module_tabs'] = array(
			'services' => esc_html__( 'Services', 'hustle' ),
			'display'  => esc_html__( 'Display Options', 'hustle' ),
		);

		return $current_array;
	}

	/**
	 * Include the visibility conditions variables required in js side.
	 * These used to be registered in Hustle_Module_Admin before 4.0.3.
	 *
	 * @since 4.0.3
	 *
	 * @param array $vars Current registered variables.
	 * @return array
	 */
	private function register_visibility_conditions_js_vars( $vars ) {

		$post_ids   = array();
		$page_ids   = array();
		$tag_ids    = array();
		$cat_ids    = array();
		$wc_cat_ids = array();
		$wc_tag_ids = array();
		$tags       = array();
		$cats       = array();
		$wc_cats    = array();
		$wc_tags    = array();

		$module = new Hustle_Module_Model( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ) );
		if ( ! is_wp_error( $module ) ) {
			$settings = $module->get_visibility()->to_array();

			$post_ids = $this->get_conditions_ids( $settings, 'posts' );
			$page_ids = $this->get_conditions_ids( $settings, 'pages' );
			$tag_ids  = $this->get_conditions_ids( $settings, 'tags' );
			$cat_ids  = $this->get_conditions_ids( $settings, 'categories' );
			if ( Opt_In_Utils::is_woocommerce_active() ) {
				$wc_cat_ids = $this->get_conditions_ids( $settings, 'wc_categories' );
				$wc_tag_ids = $this->get_conditions_ids( $settings, 'wc_tags' );
			}
		}

		if ( $tag_ids ) {
			$tags = array_map(
				array( $this, 'terms_to_select2_data' ),
				get_categories(
					array(
						'hide_empty' => false,
						'include'    => $tag_ids,
						'taxonomy'   => 'post_tag',
					)
				)
			);
		}

		if ( $cat_ids ) {
			$cats = array_map(
				array( $this, 'terms_to_select2_data' ),
				get_categories(
					array(
						'include'    => $cat_ids,
						'hide_empty' => false,
					)
				)
			);
		}

		if ( $wc_cat_ids ) {
			$wc_cats = array_map(
				array( $this, 'terms_to_select2_data' ),
				get_categories(
					array(
						'include'    => $wc_cat_ids,
						'hide_empty' => false,
						'taxonomy'   => 'product_cat',
					)
				)
			);
		}

		if ( $wc_tag_ids ) {
			$wc_tags = array_map(
				array( $this, 'terms_to_select2_data' ),
				get_categories(
					array(
						'include'    => $wc_tag_ids,
						'hide_empty' => false,
						'taxonomy'   => 'product_tag',
					)
				)
			);
		}

		$posts = Opt_In_Utils::get_select2_data( 'post', $post_ids );

		$pages = Opt_In_Utils::get_select2_data( 'page', $page_ids );

		/**
		 * Add all custom post types
		 */
		$post_types = array();
		$cpts       = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'objects'
		);
		foreach ( $cpts as $cpt ) {

			// Skip ms_invoice.
			if ( 'ms_invoice' === $cpt->name ) {
				continue;
			}

			$cpt_ids = $this->get_conditions_ids( $settings, $cpt->label );

			$cpt_array['name']  = $cpt->name;
			$cpt_array['label'] = $cpt->label;
			$cpt_array['data']  = Opt_In_Utils::get_select2_data( $cpt->name, $cpt_ids );

			$post_types[ $cpt->name ] = $cpt_array;
		}

		$vars['cats']       = $cats;
		$vars['wc_cats']    = $wc_cats;
		$vars['wc_tags']    = $wc_tags;
		$vars['tags']       = $tags;
		$vars['posts']      = $posts;
		$vars['post_types'] = Opt_In_Utils::get_post_types();
		$vars['pages']      = $pages;

		$vars['countries'] = Opt_In_Utils::get_countries();
		$vars['roles']     = Opt_In_Utils::get_user_roles();
		$vars['templates'] = Opt_In_Utils::hustle_get_page_templates();

		$vars['type_singular_lower'] = Opt_In_Utils::get_module_type_display_name( $this->module_type );

		// Visibility conditions titles, labels and bodies.
		$vars['messages']['conditions'] = array(
			'visitor_logged_in'           => __( 'Logged in status', 'hustle' ),
			'shown_less_than'             => __( 'Number of times visitor has seen', 'hustle' ),
			'only_on_mobile'              => __( "Visitor's Device", 'hustle' ),
			'from_specific_ref'           => __( 'Referrer', 'hustle' ),
			'from_search_engine'          => __( 'Source of Arrival', 'hustle' ),
			'on_specific_url'             => __( 'Specific URL', 'hustle' ),
			'on_specific_browser'         => __( 'Visitor\'s Browser', 'hustle' ),
			'visitor_has_never_commented' => __( 'Visitor Commented Before', 'hustle' ),
			'not_in_a_country'            => __( "Visitor's Country", 'hustle' ),
			'on_specific_roles'           => __( 'User Roles', 'hustle' ),
			'wp_conditions'               => __( 'Static Pages', 'hustle' ),
			'archive_pages'               => __( 'Archive Pages', 'hustle' ),
			'on_specific_templates'       => __( 'Page Templates', 'hustle' ),
			'user_registration'           => __( 'After Registration', 'hustle' ),
			'page_404'                    => __( '404 page', 'hustle' ),
			'posts'                       => __( 'Posts', 'hustle' ),
			'pages'                       => __( 'Pages', 'hustle' ),
			'categories'                  => __( 'Categories', 'hustle' ),
			'tags'                        => __( 'Tags', 'hustle' ),
			'wc_pages'                    => __( 'WooCommerce Pages', 'hustle' ),
			'wc_categories'               => __( 'WooCommerce Categories', 'hustle' ),
			'wc_tags'                     => __( 'WooCommerce Tags', 'hustle' ),
			'wc_archive_pages'            => __( 'WooCommerce Archives', 'hustle' ),
			'wc_static_pages'             => __( 'WooCommerce Static Pages', 'hustle' ),
			'cookie_set'                  => __( 'Browser Cookie', 'hustle' ),
		);

		$vars['messages']['condition_labels'] = array(
			'mobile_only'         => __( 'Mobile only', 'hustle' ),
			'desktop_only'        => __( 'Desktop only', 'hustle' ),
			'any_conditions'      => __( '{number} condition(s)', 'hustle' ),
			'number_views'        => '< {number}',
			'number_views_more'   => '> {number}',
			'any'                 => __( 'Any', 'hustle' ),
			'all'                 => __( 'All', 'hustle' ),
			'no'                  => __( 'No', 'hustle' ),
			'none'                => __( 'None', 'hustle' ),
			'true'                => __( 'True', 'hustle' ),
			'false'               => __( 'False', 'hustle' ),
			'logged_in'           => __( 'Logged in', 'hustle' ),
			'logged_out'          => __( 'Logged out', 'hustle' ),
			'only_these'          => __( 'Only {number}', 'hustle' ),
			'except_these'        => __( 'All except {number}', 'hustle' ),
			'reg_date'            => __( 'Day {number} ', 'hustle' ),
			'immediately'         => __( 'Immediately', 'hustle' ),
			'forever'             => __( 'Forever', 'hustle' ),
			'cookie_anything'     => __( '{name} is anything', 'hustle' ),
			'cookie_doesnt_exist' => __( '{name} does not exist', 'hustle' ),
			'cookie_value'        => __( '{name} {value_condition} {value}', 'hustle' ),
		);

		$vars['wp_conditions'] = array(
			'is_front_page' => __( 'Front page', 'hustle' ),
			'is_404'        => __( '404 page', 'hustle' ),
			'is_search'     => __( 'Search results', 'hustle' ),
		);

		$vars['wc_static_pages'] = array(
			'is_cart'           => __( 'Cart', 'hustle' ),
			'is_checkout'       => __( 'Checkout', 'hustle' ),
			'is_order_received' => __( 'Order Received', 'hustle' ),
			'is_account_page'   => __( 'My account', 'hustle' ),
		);

		$vars['archive_pages'] = array(
			'is_category'          => __( 'Category archive', 'hustle' ),
			'is_tag'               => __( 'Tag archive', 'hustle' ),
			'is_author'            => __( 'Author archive', 'hustle' ),
			'is_date'              => __( 'Date archive', 'hustle' ),
			'is_post_type_archive' => __( 'Custom post archive', 'hustle' ),
		);

		$vars['wc_archive_pages'] = array(
			'is_shop'             => __( 'Shop', 'hustle' ),
			'is_product_category' => __( 'Product Category', 'hustle' ),
			'is_product_tag'      => __( 'Product Tag', 'hustle' ),
		);

		$vars['wp_cookie_set'] = array(
			'anything'             => __( 'is anything', 'hustle' ),
			'equals'               => __( 'equals', 'hustle' ),
			'contains'             => __( 'contains', 'hustle' ),
			'matches_pattern'      => __( 'matches a pattern', 'hustle' ),
			'doesnt_equals'        => __( 'does not equals', 'hustle' ),
			'doesnt_contain'       => __( 'does not contain', 'hustle' ),
			'doesnt_match_pattern' => __( 'does not match a pattern', 'hustle' ),
			'less_than'            => __( 'is less than', 'hustle' ),
			'less_equal_than'      => __( 'is less or equal to', 'hustle' ),
			'greater_than'         => __( 'is greater than', 'hustle' ),
			'greater_equal_than'   => __( 'is greater or equal to', 'hustle' ),
		);

		$vars['roles']     = Opt_In_Utils::get_user_roles();
		$vars['browsers']  = Opt_In_Utils::get_browsers();
		$vars['countries'] = Opt_In_Utils::get_countries();
		$vars['templates'] = Opt_In_Utils::hustle_get_page_templates();

		return $vars;
	}

	/**
	 * Gets the JS variables to be localized in Wizard for non-ssharing modules.
	 * This method is overwritten in Hustle_Sshare_Admin.
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */
	protected function get_wizard_js_variables_to_localize() {
		$is_optin = Hustle_Module_Model::OPTIN_MODE === $this->module->module_mode;

		$variables = array(
			'form_fields'    => $this->get_form_fields_js_vars(),
			'schedule'       => $this->get_schedule_js_vars(),
			'palettes'       => Hustle_Palettes_Helper::get_all_palettes( $is_optin ),
			'integrations'   => array(
				'action_nonce'  => wp_create_nonce( 'hustle_provider_action' ), // Also defined in integrations.
				'fetching_list' => esc_html__( 'Fetching integration list…', 'hustle' ),
			),
			'typography'     => array(
				'global_font_applied' => esc_html__( 'Your font was correctly applied to all elements', 'hustle' ),
				'fetch_nonce'         => wp_create_nonce( 'hustle_fetch_font_families' ),
			),
			'media_uploader' => array(
				'select_or_upload' => esc_html__( 'Select or Upload Image', 'hustle' ),
				'use_this_image'   => esc_html__( 'Use this image', 'hustle' ),
			),
			'triggers'       => array(
				'immediately_tag'       => esc_html__( 'Immediately', 'hustle' ),
				'seconds'               => esc_html__( 'seconds', 'hustle' ),
				'minutes'               => esc_html__( 'minutes', 'hustle' ),
				'hours'                 => esc_html__( 'hours', 'hustle' ),
				/* translators: 1. {time} tag to replace, 2. {unit} tag to replace */
				'delayed_tag'           => sprintf( esc_html__( 'Delay %1$s %2$s', 'hustle' ), '{time}', '{unit}' ),
				/* translators: {value}% tag to replace */
				'scroll_percentage_tag' => sprintf( esc_html__( '%s page scroll', 'hustle' ), '{value}%' ),
				/* translators: {value} tag to replace */
				'scroll_element_tag'    => sprintf( esc_html__( 'Scroll to %s', 'hustle' ), '{value}' ),
			),
		);

		$variables['defaults']  = $this->module->get_design()->get_border_spacing_shadow_defaults( 'desktop' );
		$variables['defaults'] += $this->module->get_design()->get_typography_defaults( 'desktop' );

		return $variables;
	}

	/**
	 * Include the form fields variables required in js side.
	 * These used to be registered in Hustle_Module_Admin before 4.0.3.
	 *
	 * @since 4.0.3
	 *
	 * @return array
	 */
	private function get_form_fields_js_vars() {
		$renderer = $this->get_renderer();

		$no_fields_notice_args = array(
			array(
				'type'  => 'inline_notice',
				'icon'  => 'info',
				'value' => esc_html__( 'You don\'t have any {field_type} field in your opt-in form.', 'hustle' ),
			),
		);

		$variables = array(
			'no_fields_of_type_notice'     => $renderer->get_html_for_options( $no_fields_notice_args, true ),
			'is_required'                  => esc_html__( '{field} is required.', 'hustle' ),
			'cant_empty'                   => esc_html__( 'This field can\'t be empty.', 'hustle' ),
			'url_required_error_message'   => esc_html__( 'Your website url is required.', 'hustle' ),
			'required_error_message'       => esc_html__( 'Your {field} is required.', 'hustle' ),
			'date_validation_message'      => esc_html__( 'Please enter a valid date.', 'hustle' ),
			'time_validation_message'      => esc_html__( 'Please enter a valid time.', 'hustle' ),
			'validation_message'           => esc_html__( 'Please enter a valid {field}.', 'hustle' ),
			'recaptcha_error_message'      => esc_html__( 'reCAPTCHA verification failed. Please try again.', 'hustle' ),
			'recaptcha_validation_message' => esc_html__( 'reCAPTCHA verification failed. Please try again.', 'hustle' ),
			'gdpr_required_error_message'  => esc_html__( 'Please accept the terms and try again.', 'hustle' ),
			/* translators: 1. opening 'a' tag, 2. closing 'a' tag */
			'gdpr_message'                 => sprintf( esc_html__( 'I\'ve read and accept the %1$sterms & conditions%2$s', 'hustle' ), '<a href="#">', '</a>' ),
			'label'                        => array(
				'placeholder'            => esc_html__( 'Enter placeholder here', 'hustle' ),
				'name_label'             => esc_html__( 'Name', 'hustle' ),
				'name_placeholder'       => esc_html__( 'E.g. John', 'hustle' ),
				'email_label'            => esc_html__( 'Email Address', 'hustle' ),
				'enail_placeholder'      => esc_html__( 'E.g. john@doe.com', 'hustle' ),
				'phone_label'            => esc_html__( 'Phone Number', 'hustle' ),
				'phone_placeholder'      => esc_html__( 'E.g. +1 300 400 500', 'hustle' ),
				'address_label'          => esc_html__( 'Address', 'hustle' ),
				'address_placeholder'    => '',
				'hidden_label'           => esc_html__( 'Hidden Field', 'hustle' ),
				'hidden_placeholder'     => '',
				'url_label'              => esc_html__( 'Website', 'hustle' ),
				'url_placeholder'        => esc_html__( 'E.g. https://example.com', 'hustle' ),
				'text_label'             => esc_html__( 'Text', 'hustle' ),
				'text_placeholder'       => esc_html__( 'E.g. Enter your nick name', 'hustle' ),
				'number_label'           => esc_html__( 'Number', 'hustle' ),
				'number_placeholder'     => esc_html__( 'E.g. 1', 'hustle' ),
				'datepicker_label'       => esc_html__( 'Date', 'hustle' ),
				'datepicker_placeholder' => esc_html__( 'Choose date', 'hustle' ),
				'timepicker_label'       => esc_html__( 'Time', 'hustle' ),
				'timepicker_placeholder' => '',
				'recaptcha_label'        => 'reCAPTCHA',
				'recaptcha_placeholder'  => '',
				'gdpr_label'             => esc_html__( 'GDPR', 'hustle' ),
			),
			'recaptcha_badge_replacement'  => sprintf(
				/* translators: 1: closing 'a' tag, 2: opening privacy 'a' tag, 3: opening terms 'a' tag */
				esc_html__( 'This site is protected by reCAPTCHA and the Google %2$sPrivacy Policy%1$s and %3$sTerms of Service%1$s apply.', 'hustle' ),
				'</a>',
				'<a href="https://policies.google.com/privacy" target="_blank">',
				'<a href="https://policies.google.com/terms" target="_blank">'
			),
		);

		return $variables;
	}

	/**
	 * Includes the variables used for the Schedule functionality.
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */
	private function get_schedule_js_vars() {
		$type_lowercase = Opt_In_Utils::get_module_type_display_name( $this->module_type );

		return array(
			'wp_gmt_offset'    => get_option( 'gmt_offset' ),
			'new_schedule_set' => sprintf(
				/* translators: 1. module type in lowercase */
				esc_html__( 'Successfully added a schedule for your %1$s. However, make sure to save changes and publish your %1$s for it to start appearing as per your schedule.', 'hustle' ),
				esc_html( $type_lowercase )
			),
			'months'           => Hustle_Time_Helper::get_months(),
			'week_days'        => Hustle_Time_Helper::get_week_days( 'short' ),
			'meridiem'         => Hustle_Time_Helper::get_meridiam_periods(),
		);
	}

	/**
	 * Gets the conditions ID.
	 *
	 * @since 3.0.7
	 * @since 4.0.3 moved from Hustle_Modules_Admin to here.
	 *
	 * @param array  $settings Display settings.
	 * @param string $type posts|pages|tags|categories|{cpt}.
	 * @return array
	 */
	private function get_conditions_ids( $settings, $type ) {
		$ids = array();
		if ( ! empty( $settings['conditions'] ) ) {
			foreach ( $settings['conditions'] as $conditions ) {
				if ( ! empty( $conditions[ $type ] )
					&& ( ! empty( $conditions[ $type ][ $type ] )
					|| ! empty( $conditions[ $type ]['selected_cpts'] ) ) ) {
					$new_ids = ! empty( $conditions[ $type ][ $type ] )
					? $conditions[ $type ][ $type ]
					: $conditions[ $type ]['selected_cpts'];

					$ids = array_merge( $ids, $new_ids );
				}
			}
		}

		return array_unique( $ids );
	}

	/**
	 * Converts term object to usable object for select2
	 *
	 * @since 4.0.3 moved from Hustle_Modules_Admin to here.
	 * @param stdClass $term Term.
	 * @return stdClass
	 */
	public static function terms_to_select2_data( $term ) {
		$obj       = new stdClass();
		$obj->id   = $term->term_id;
		$obj->text = $term->name;
		return $obj;
	}

	/**
	 * Render the module's wizard page.
	 *
	 * @since 4.0.1
	 */
	public function render_edit_page() {

		$template_args = $this->get_page_edit_template_args();
		$allowed       = Opt_In_Utils::is_user_allowed( 'hustle_edit_module', $template_args['module_id'] );
		if ( ! $allowed ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.' ), 403 );
		}

		if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $this->module_type ) {
			wp_enqueue_editor();
		}

		$main_class = $this->get_sui_wrap_class();

		?>
		<main class="<?php echo esc_attr( $main_class ); ?>">

			<?php
			$renderer      = $this->get_renderer();
			$renderer->render( $this->page_edit_template_path, $template_args );
			?>

		</main>
		<?php
	}

	/**
	 * Get the args for the wizard page.
	 *
	 * @since 4.0.1
	 * @return array
	 */
	protected function get_page_edit_template_args() {
		return array(
			'section'   => $this->get_current_section( 'content' ),
			'module_id' => $this->module->module_id,
			'module'    => $this->module,
			'is_active' => (bool) $this->module->active,
			'is_optin'  => ( 'optin' === $this->module->module_mode ),
		);
	}

	/**
	 * Gets the count of the modules (both active and non active) of the current type.
	 *
	 * @since 4.2.0
	 * @return int
	 */
	private function get_total_count_modules_current_type() {

		if ( is_null( $this->module_count_type ) ) {
			$this->module_count_type = Hustle_Module_Collection::instance()->get_all(
				null,
				array(
					'module_type' => $this->module_type,
					'count_only'  => true,
				)
			);
		}
		return $this->module_count_type;
	}

	/**
	 * Get data needed for rendering the tracking charts in the listing pages
	 * Retrieved via AJAX.
	 *
	 * @since 4.0.4
	 * @since 4.2.0 Moved from Hustle_Module_Model to this class, and $module_id param added.
	 *
	 * @param int $module_id ID of the requested module.
	 */
	public static function get_tracking_charts_markup( $module_id ) {

		$module = Hustle_Model::get_module( $module_id );
		if ( is_wp_error( $module ) ) {
			return '';
		}

		$tracking_model           = Hustle_Tracking_Model::get_instance();
		$total_module_conversions = $tracking_model->count_tracking_data( $module_id, 'all_conversion' );
		$total_module_views       = $tracking_model->count_tracking_data( $module_id, 'view' );
		$last_entry_time          = $tracking_model->get_latest_conversion_time_by_module_id( $module_id );
		$rate                     = $total_module_views ? round( ( $total_module_conversions * 100 ) / $total_module_views, 1 ) : 0;
		$module_sub_types         = $module->get_sub_types( true );

		$multiple_charts = array();

		// Get each sub type's tracking data if the module type has sub types.
		if ( ! empty( $module_sub_types ) ) {

			foreach ( $module_sub_types as $slug => $display_name ) {

				$subtype         = $module->module_type . '_' . $slug;
				$views           = $tracking_model->count_tracking_data( $module_id, 'view', $subtype );
				$conversions     = $tracking_model->count_tracking_data( $module_id, 'all_conversion', $subtype );
				$conversion_rate = $views ? round( ( $conversions * 100 ) / $views, 1 ) : 0;

				$multiple_charts[ $slug ] = array(
					'display_name'    => $display_name,
					'last_entry_time' => $tracking_model->get_latest_conversion_time_by_module_id( $module_id, $subtype ),
					'views'           => $views,
					'conversions'     => $conversions,
					'conversion_rate' => $conversion_rate,
				);
			}
		}

		$render_arguments = array(
			'module'                   => $module,
			'total_module_views'       => $total_module_views,
			'total_module_conversions' => $total_module_conversions,
			'tracking_types'           => $module->get_tracking_types(),
			'last_entry_time'          => $last_entry_time,
			'rate'                     => $rate,
		);

		if ( $module->get_content()->has_cta() ) {
			$notice_for_old_data                     = $tracking_model->has_old_tracking_data( $module_id );
			$render_arguments['notice_for_old_data'] = $notice_for_old_data;
		}

		ob_start();

		$renderer = new Hustle_Layout_Helper();

		// ELEMENT: Tracking data.
		$renderer->render(
			'admin/commons/sui-listing/elements/tracking-data',
			array(
				'render_arguments' => $render_arguments,
				'multiple_charts'  => $multiple_charts,
			)
		);

		$html = ob_get_clean();

		$charts_data = self::get_charts_data( array_keys( $module_sub_types ), $total_module_views, $module );

		$data = array(
			'html'        => $html,
			'charts_data' => $charts_data,
		);

		return $data;
	}

	/**
	 * Get tracking data for building charts on listing page
	 *
	 * @since 4.0.4
	 * @since 4.2.0 Move from Hustle_Module_Model to this class. $module param added.
	 *
	 * @param array        $sub_types Module's sub types.
	 * @param int          $views Module's views count.
	 * @param Hustle_Model $module Instance of the module to get the charts data for.
	 * @return array
	 */
	private static function get_charts_data( $sub_types, $views, Hustle_Model $module ) {

		$sql_month_start_date = date( 'Y-m-d H:i:s', strtotime( '-30 days midnight' ) );
		$tracking_model       = Hustle_Tracking_Model::get_instance();
		$days_array           = array();
		$default_array        = array();

		for ( $h = 30; $h >= 0; $h-- ) {
			$time                   = strtotime( '-' . $h . ' days' );
			$date                   = date( 'Y-m-d', $time );
			$default_array[ $date ] = 0;
			$days_array[]           = date( 'M j, Y', $time );
		}

		$sub_types[]      = 'overall';
		$conversion_types = array( 'all' );

		if ( Hustle_Module_Model::SOCIAL_SHARING_MODULE !== $module->module_type ) {
			$conversion_types[] = 'cta';

			if ( Hustle_Module_Model::OPTIN_MODE === $module->module_mode ) {
				$conversion_types[] = 'optin';
			}
		}

		$data = array();
		foreach ( $sub_types as $sub_type ) {

			$chart_container_id = sprintf(
				'hustle-%1$s-%2$s-stats--%3$s',
				$module->module_type,
				$module->module_id,
				$sub_type
			);
			$data[ $sub_type ]  = array(
				'id'   => $chart_container_id,
				'days' => $days_array,
			);

			foreach ( $conversion_types as $conversion_type ) {

				$last_month_conversions = $tracking_model->get_form_latest_tracking_data_count_grouped_by_day( $module->module_id, $sql_month_start_date, $conversion_type . '_conversion', $module->module_type, $sub_type );
				$last_month_views       = $tracking_model->get_form_latest_tracking_data_count_grouped_by_day( $module->module_id, $sql_month_start_date, 'view', $module->module_type, $sub_type );

				if ( ! $last_month_conversions ) {
					$submissions_data = $default_array;
				} else {
					$submissions_array = wp_list_pluck( $last_month_conversions, 'tracked_count', 'date_created' );
					$submissions_data  = array_merge( $default_array, array_intersect_key( $submissions_array, $default_array ) );
				}

				if ( ! $last_month_views ) {
					$views_data = $default_array;
				} else {
					$views_array = wp_list_pluck( $last_month_views, 'tracked_count', 'date_created' );
					$views_data  = array_merge( $default_array, array_intersect_key( $views_array, $default_array ) );
				}

				$query_sub_type        = 'overall' === $sub_type ? null : $module->module_type . '_' . $sub_type;
				$query_conversion_type = $conversion_type . '_conversion';
				$sub_type_conversions  = $tracking_model->count_tracking_data( $module->module_id, $query_conversion_type, $query_sub_type );

				$data[ $sub_type ][ $conversion_type ] = array(
					'conversions_count' => $sub_type_conversions,
					'conversion_rate'   => $views ? round( ( $sub_type_conversions * 100 ) / $views, 1 ) : 0,
					'conversions'       => array_values( $submissions_data ),
				);
			}

			$data[ $sub_type ]['views']       = array_values( $views_data );
			$data[ $sub_type ]['conversions'] = array_values( $submissions_data );
		}

		return $data;
	}

	/**
	 * Returns whether the current module is optin.
	 * This method should be only used in Wizard pages
	 * because $this->module is only defined within Wizards.
	 *
	 * @since 4.3.0
	 * @return boolean
	 */
	public function is_optin_module() {
		return ! empty( $this->module ) && 'optin' === $this->module->module_mode;
	}

	/**
	 * Gets the font family names.
	 * Used for rendering the selects.
	 *
	 * @since 4.3.0
	 * @return array
	 */
	public function get_font_families() {
		return Hustle_Meta_Base_Design::get_font_families_names();
	}
}