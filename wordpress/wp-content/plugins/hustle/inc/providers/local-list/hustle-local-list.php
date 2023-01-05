<?php
if ( ! class_exists( 'Hustle_Local_List' ) ) :

	class Hustle_Local_List extends Hustle_Provider_Abstract {

		/**
		 * Provider Instance
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
		protected $_slug = 'local_list';

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
		protected $_title = 'Local List';

		/**
		 * @since 4.0
		 * @var boolean
		 */
		protected $is_multi_on_global = false;

		/**
		 * Class name of form settings
		 *
		 * @var string
		 */
		protected $_form_settings = 'Hustle_Local_List_Form_Settings';

		/**
		 * Class name of form hooks
		 *
		 * @var string
		 */
		protected $_form_hooks = 'Hustle_Local_List_Form_Hooks';

		/**
		 * Array of options which should exist for confirming that settings are completed
		 *
		 * @since 4.0
		 * @var array
		 */
		protected $_completion_options = array();

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

		public function active() {
			return true;
		}

		public function migrate_30( $module, $old_module ) {
			$save_local      = ! empty( $old_module->meta['content']['save_local_list'] );
			$local_list_name = ! empty( $old_module->meta['content']['local_list_name'] )
			? $old_module->meta['content']['local_list_name']
			: '';

			if ( $save_local ) {
				$module->set_provider_settings(
					$this->get_slug(),
					array(
						'local_list_name' => $local_list_name,
					)
				);

				// Activate the addon
				Hustle_Providers::get_instance()->activate_addon( $this->get_slug() );
			}
		}

	}

endif;