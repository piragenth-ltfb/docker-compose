<?php
/**
 * File for Hustle_Module_Admin class.
 *
 * @package Hustle
 * @since unknown
 */

if ( ! class_exists( 'Hustle_Module_Admin' ) ) :

	/**
	 * Class Hustle_Module_Admin
	 */
	class Hustle_Module_Admin {

		const UPGRADE_MODAL_PARAM = 'requires-pro';

		/**
		 * Hustle_Module_Admin constructor
		 */
		public function __construct() {
			$admin_notices = Hustle_Notifications::get_instance();

			if ( $this->is_hustle_page() ) {
				$admin_notices->add_in_hustle_notices();
				$this->maybe_add_recommended_plugins_notice();
			} else {
				$this->handle_non_hustle_pages();
			}

			if ( $this->is_hustle_page() || wp_doing_ajax() ) {
				Hustle_Provider_Autoload::initiate_providers();
			}

			Hustle_Provider_Autoload::load_block_editor();

			add_action( 'admin_init', array( $this, 'add_privacy_message' ) );

			add_filter( 'w3tc_save_options', array( $this, 'filter_w3tc_save_options' ), 10, 1 );
			add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );
			add_filter( 'network_admin_plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links' ), 10, 2 );

			add_action( 'upgrader_process_complete', array( $this, 'upgrader_process_complete' ), 10, 2 );
		}

		/**
		 * Add Privacy Messages
		 *
		 * @since 3.0.6
		 */
		public function add_privacy_message() {
			if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
				$external_integrations_list             = '';
				$external_integrations_privacy_url_list = '';
				$params                                 = array(
					'external_integrations_list' => apply_filters( 'hustle_privacy_external_integrations_list', $external_integrations_list ),
					'external_integrations_privacy_url_list' => apply_filters( 'hustle_privacy_url_external_integrations_list', $external_integrations_privacy_url_list ),
				);

				$renderer = new Hustle_Layout_Helper();
				$content  = $renderer->render( 'general/policy-text', $params, true );
				wp_add_privacy_policy_content( 'Hustle', wp_kses_post( $content ) );
			}
		}

		/**
		 * Forcefully rejects the minify for Hustle's js and css.
		 *
		 * @since unknown
		 *
		 * @param array $config w3tc configs.
		 * @return array
		 */
		public function filter_w3tc_save_options( $config ) {

			// Reject js.
			$defined_rejected_js = $config['new_config']->get( 'minify.reject.files.js' );
			$reject_js           = array(
				Opt_In::$plugin_url . 'assets/js/admin.min.js',
				Opt_In::$plugin_url . 'assets/js/ad.js',
				Opt_In::$plugin_url . 'assets/js/front.min.js',
			);
			foreach ( $reject_js as $r_js ) {
				if ( ! in_array( $r_js, $defined_rejected_js, true ) ) {
					array_push( $defined_rejected_js, $r_js );
				}
			}
			$config['new_config']->set( 'minify.reject.files.js', $defined_rejected_js );

			// Reject css.
			$defined_rejected_css = $config['new_config']->get( 'minify.reject.files.css' );
			$reject_css           = array(
				Opt_In::$plugin_url . 'assets/css/front.min.css',
			);
			foreach ( $reject_css as $r_css ) {
				if ( ! in_array( $r_css, $defined_rejected_css, true ) ) {
					array_push( $defined_rejected_css, $r_css );
				}
			}
			$config['new_config']->set( 'minify.reject.files.css', $defined_rejected_css );

			return $config;
		}

		/**
		 * Adds custom links on "Plugins" page.
		 *
		 * @since unknown
		 *
		 * @param array  $actions Array of plugin actions links.
		 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
		 * @return array
		 */
		public function add_plugin_action_links( $actions, $plugin_file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = Opt_In::$plugin_base_file;
			}

			if ( $plugin === $plugin_file ) {
				$admin_url    = self_admin_url( 'admin.php' );
				$settings_url = add_query_arg( 'page', 'hustle_settings', $admin_url );
				$links        = array(
					'settings' => '<a href="' . $settings_url . '">' . esc_html__( 'Settings', 'hustle' ) . '</a>',
					'docs'     => '<a href="' . esc_url( Opt_In_Utils::get_link( 'docs', 'hustle_pluginlist_docs' ) ) . '" target="_blank">' . esc_html__( 'Docs', 'hustle' ) . '</a>',
				);

				// Upgrade link.
				if ( Opt_In_Utils::_is_free() ) {
					if ( ! Opt_In_Utils::is_hustle_included_in_membership() ) {
						$url   = Opt_In_Utils::get_link( 'wpmudev', 'hustle_pluginlist_upgrade' );
						$label = __( 'Upgrade to Hustle Pro', 'hustle' );
					} else {
						$url   = Opt_In_Utils::get_link( 'install_plugin' );
						$label = __( 'Upgrade', 'hustle' );
					}
					if ( is_network_admin() || ! is_multisite() ) {
						$links['upgrade'] = '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $label ) . '" target="_blank" style="color: #8D00B1;">' . esc_html( $label ) . '</a>';
					}
				} else {
					if ( 'expired' === Opt_In_Utils::get_membership_status() ) {
						$links['renew'] = '<a href="' . esc_url( Opt_In_Utils::get_link( 'wpmudev', 'hustle_pluginlist_renew' ) ) . '" target="_blank" style="color: #8D00B1;">' . esc_html__( 'Renew Membership', 'hustle' ) . '</a>';
					}
				}

				$actions = array_merge( $links, $actions );
			}

			return $actions;
		}

		/**
		 * Links next to version number in the "Plugins" page.
		 *
		 * @since unknown
		 *
		 * @param array  $plugin_meta An array of the plugin's metadata.
		 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
		 * @return array
		 */
		public function add_plugin_meta_links( $plugin_meta, $plugin_file ) {
			if ( Opt_In::$plugin_base_file === $plugin_file ) {
				$row_meta = array();

				if ( Opt_In_Utils::_is_free() ) {
					$row_meta['rate'] = '<a href="https://wordpress.org/support/plugin/wordpress-popup/reviews/#new-post" target="_blank">' . esc_html__( 'Rate Hustle', 'hustle' ) . '</a>';
				}

				$support_text = 'full' === Opt_In_Utils::get_membership_status() ? esc_html__( 'Premium Support', 'hustle' ) : esc_html__( 'Support', 'hustle' );

				// Returns the wp.org link when ! is_member(), and the premium link otherwise.
				$row_meta['support'] = '<a href="' . esc_url( Opt_In_Utils::get_link( 'support' ) ) . '" target="_blank">' . $support_text . '</a>';

				$row_meta['roadmap'] = '<a href="' . esc_url( Opt_In_Utils::get_link( 'roadmap' ) ) . '" target="_blank">' . esc_html__( 'Roadmap', 'hustle' ) . '</a>';
				$plugin_meta         = array_merge( $plugin_meta, $row_meta );
			}

			return $plugin_meta;
		}

		/**
		 * Flags the previous version on upgrade so we can handle notices and modals.
		 * This action runs in the old version of the plugin, not the new one.
		 *
		 * @since 4.2.0
		 *
		 * @param WP_Upgrader $upgrader_object Instance of the WP_Upgrader class.
		 * @param array       $data Upgrade data.
		 */
		public function upgrader_process_complete( $upgrader_object, $data ) {

			if ( 'update' === $data['action'] && 'plugin' === $data['type'] && ! empty( $data['plugins'] ) ) {

				foreach ( $data['plugins'] as $plugin ) {

					// Make sure our plugin is among the ones being updated and set the flag for the previous version.
					if ( Opt_In::$plugin_base_file === $plugin ) {
						update_site_option( 'hustle_previous_version', Opt_In::VERSION );
					}
				}
			}
		}

		/**
		 * Handles the scripts for non-hustle pages.
		 *
		 * @since 4.2.0
		 */
		private function handle_non_hustle_pages() {
			global $pagenow;

			if ( 'index.php' === $pagenow || wp_doing_ajax() ) {

				$analytic_settings = Hustle_Settings_Admin::get_hustle_settings( 'analytics' );
				$analytics_enabled = ! empty( $analytic_settings['enabled'] ) && ! empty( $analytic_settings['modules'] );

				// Only initialize if the analytics are enabled.
				// That's the only use for this class for now.
				if ( $analytics_enabled && current_user_can( 'hustle_analytics' ) ) {
					new Hustle_Wp_Dashboard_Page( $analytic_settings );
				}
			}
		}

		/**
		 * Renders the recommended plugins notice when the conditions are correct.
		 * This is shown when:
		 * -The current version is Free
		 * -The admin isn't logged to WPMU Dev dashboard
		 * -The notice hasn't been dismissed
		 * -The majority of our plugins aren't installed
		 * -A month has passed since the plugin was installed
		 * There are filters to force the display.
		 *
		 * @see https://bitbucket.org/incsub/recommended-plugins-notice/src/master/
		 *
		 * @since 4.2.0
		 */
		private function maybe_add_recommended_plugins_notice() {
			if ( ! Opt_In_Utils::_is_free() ) {
				return;
			}

			require_once Opt_In::$plugin_path . 'lib/plugin-notice/notice.php';
			do_action(
				'wpmudev-recommended-plugins-register-notice', // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
				Opt_In::$plugin_base_file,
				__( 'Hustle', 'hustle' ),
				$this->get_admin_pages_for_free(),
				array( 'after', '.sui-wrap .sui-header' )
			);
		}

		/**
		 * Checks if it's module admin page
		 *
		 * @return bool
		 */
		private function is_hustle_page() {
			$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
			return in_array( $page, Hustle_Data::get_hustle_pages(), true );
		}

		/**
		 * Return an array with the slugs of the admin pages
		 *
		 * @since 4.1.1
		 * @return array
		 */
		private function get_admin_pages_for_free() {
			$names       = Hustle_Data::get_hustle_pages();
			$with_prefix = array( 'toplevel_page_hustle' );

			foreach ( $names as $name ) {
				$with_prefix[] = 'hustle_page_' . $name;
			}
			return $with_prefix;
		}
	}

endif;