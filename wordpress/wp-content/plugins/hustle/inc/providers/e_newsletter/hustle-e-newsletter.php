<?php

if ( ! class_exists( 'Hustle_E_Newsletter' ) ) :

	class Hustle_E_Newsletter extends Hustle_Provider_Abstract {

		/**
		 * @var $_email_newsletter Email_Newsletter
		 */
		public $_email_newsletter;

		const SLUG = 'e_newsletter';
		// const NAME = "e-Newsletter";

		/**
		 * Activecampaign Provider Instance
		 *
		 * @since 3.0.5
		 *
		 * @var self|null
		 */
		protected static $_instance = null;

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_slug = 'e_newsletter';

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_version = '1.0';

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_class = __CLASS__;

		/**
		 * @since 3.0.5
		 * @var string
		 */
		protected $_title = 'e-Newsletter';

		/**
		 * @since 4.0
		 * @var bool
		 */
		protected $is_multi_on_global = false;

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_E_Newsletter_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @since 4.0
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_E_Newsletter_Form_Hooks';

		/**
		 * Provider constructor.
		 */
		public function __construct() {
			$this->_icon_2x = plugin_dir_url( __FILE__ ) . 'images/icon.png';
			$this->_logo_2x = plugin_dir_url( __FILE__ ) . 'images/logo.png';
		}

		/**
		 * Get Instance
		 *
		 * @return self|null
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Get the global instance of enewsletter.
		 *
		 * @since 4.0
		 * @return object|null
		 */
		public function get_enewsletter_instance() {
			if ( ! $this->_email_newsletter ) {
				global $email_newsletter;
				$this->_email_newsletter = $email_newsletter;
			}
			return $this->_email_newsletter;
		}

		public function active() {
			$setting_values = $this->get_settings_values();

			return ! empty( $setting_values['active'] );
		}

		/**
		 * Check if the settings are completed
		 *
		 * @since 4.0
		 * @return boolean
		 */
		protected function settings_are_completed( $multi_id = '' ) {
			return $this->active() && $this->is_plugin_active();
		}

		/**
		 * Get the wizard callbacks for the global settings.
		 *
		 * @since 4.0
		 *
		 * @return array
		 */
		public function settings_wizards() {
			return array(
				array(
					'callback'     => array( $this, 'configure' ),
					'is_completed' => array( $this, 'settings_are_completed' ),
				),
			);
		}


		/**
		 * Configure the API key settings. Global settings.
		 *
		 * @since 4.0
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function configure( $submitted_data ) {
			$has_errors = false;
			$active     = $this->active();
			$is_submit  = isset( $submitted_data['hustle_is_submit'] );

			if ( $is_submit ) {

				$active = ! empty( $submitted_data['active'] );
				// If not active, activate it.
				if ( ! Hustle_Provider_Utils::is_provider_active( $this->_slug ) ) {

					// TODO: Wrap this in a friendlier method.
					$activated = Hustle_Providers::get_instance()->activate_addon( $this->_slug );
					if ( ! $activated ) {
						$error_message = esc_html( $this->provider_connection_falied() );
						$has_errors    = true;
					} else {
						$this->save_settings_values( array( 'active' => $active ) );
					}
				} else {
					$this->save_settings_values( array( 'active' => $active ) );
				}

				if ( ! $has_errors ) {

					return array(
						'html'         => Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'e-Newsletter Added', 'hustle' ), __( 'You can now go to your pop-ups, slide-ins and embeds and assign them to this integration', 'hustle' ) ),
						'buttons'      => array(
							'close' => array(
								'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), 'sui-button-ghost', 'close' ),
							),
						),
						'redirect'     => false,
						'has_errors'   => false,
						'notification' => array(
							'type' => 'success',
							'text' => '<strong>' . $this->get_title() . '</strong> ' . __( 'Successfully connected', 'hustle' ),
						),
					);

				}
			}

			if ( ! $this->is_plugin_active() ) {
				$has_errors    = true;
				$error_message = sprintf(
					/* translators: 1. opening 'a' tag to the e-Newsletter github repo, 2. closing 'a' tag */
					esc_html__( 'Please activate e-Newsletter plugin to use this integration. If you don\'t have it installed, %1$sdownload it here%2$s.', 'hustle' ),
					'<a href="https://github.com/wpmudev/e-newsletter" target="_blank">',
					'</a>'
				);
			}

			$options = array(
				array(
					'type'  => 'hidden',
					'name'  => 'active',
					'value' => 1,
				),
			);

			if ( $has_errors ) {

				$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
					__( 'Install e-Newsletter', 'hustle' )
				);

				$error_notice = array(
					'type'  => 'notice',
					'icon'  => 'info',
					'class' => 'sui-notice-error',
					'value' => $error_message,
				);
				array_unshift( $options, $error_notice );

			} else {

				$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup(
					__( 'Configure e-Newsletter', 'hustle' ),
					__( 'Activate e-Newsletter to start using it on your forms.', 'hustle' )
				);
			}

			$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

			$is_edit = $this->is_connected() ? true : false;

			if ( ! $this->is_plugin_active() ) {
				$buttons = array();
			} elseif ( $is_edit ) {
				$buttons = array(
					'disconnect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Disconnect', 'hustle' ),
							'sui-button-ghost',
							'disconnect',
							true
						),
					),
					'close'      => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Save', 'hustle' ),
							'',
							'close'
						),
					),
				);
			} else {
				$buttons = array(
					'connect' => array(
						'markup' => Hustle_Provider_Utils::get_provider_button_markup(
							__( 'Activate', 'hustle' ),
							'sui-button-center',
							'connect',
							true
						),
					),
				);

			}

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => $has_errors,
			);

			return $response;
		}

		/**
		 * Checks if E-Newsletter plugin is active
		 *
		 * @since 1.1.1
		 * @return bool
		 */
		public function is_plugin_active() {
			return class_exists( 'Email_Newsletter' ) && $this->get_enewsletter_instance();
		}

		/**
		 * Returns groups
		 *
		 * @since 1.1.1
		 * @return array
		 */
		public function get_groups() {
			$e_newsletter = $this->get_enewsletter_instance();
			return (array) $e_newsletter->get_groups();
		}

		/**
		 * Checks if member with given email already exits
		 *
		 * @since 1.1.1
		 *
		 * @param $email
		 * @return bool
		 */
		public function is_member( $email ) {
			$e_newsletter = $this->get_enewsletter_instance();
			$member       = $e_newsletter->get_member_by_email( $email );
			return ! ! $member;
		}

		public static function get_synced( $module ) {
			return self::get_provider_details( $module, 'synced', self::SLUG );
		}

		public function migrate_30( $module, $old_module ) {
			$migrated = parent::migrate_30( $module, $old_module );
			if ( ! $migrated ) {
				return false;
			}

			/*
			 * Our regular migration would've saved the provider settings in a format that's incorrect for e-newsletter
			 *
			 * Let's fix that now.
			 */
			$module_provider_settings = $module->get_provider_settings( $this->get_slug() );
			if ( ! empty( $module_provider_settings ) ) {
				// At provider level we store a single boolean
				$this->save_settings_values( array( 'active' => true ) );

				// selected_global_multi_id not needed at module level
				unset( $module_provider_settings['selected_global_multi_id'] );
				$module->set_provider_settings( $this->get_slug(), $module_provider_settings );
			}

			return $migrated;
		}

		public function get_30_provider_mappings() {
			return array(
				'enabled' => 'active',
			);
		}

	}
endif;