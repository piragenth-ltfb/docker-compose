<?php
/**
 * File for Hustle_Provider_Abstract class.
 *
 * @package Hustle
 * @since 3.0.5
 */

/**
 * Class Hustle_Provider_Abstract
 * Extend this class to create a new hustle provider / integration
 * Any change(s) to this file is subject to:
 * - Properly Written DocBlock! (what is this, why is that, how to be like those, etc, as long as you want!)
 * - Properly Written Changelog!
 *
 * This class must be extended by your integration in order to be integrated into Hustle.
 * For more information, more examples, and even sample integrations, visit this page at WPMUDev's site:
 *
 * @see https://wpmudev.com/docs/wpmu-dev-plugins/hustle-providers-api-doc/
 *
 * @since 3.0.5
 */
abstract class Hustle_Provider_Abstract implements Hustle_Provider_Interface {

	const LISTS = 'lists';

	/**
	 * Provider Instance
	 * Assigned and used by Hustle's core.
	 * -Required. Must be overridden and set to null.
	 *
	 * @since 3.0.5
	 * @var self|null
	 */
	protected static $_instance;

	/**
	 * Minimum Hustle version required by your integration in order to work properly.
	 * Your integration won't be instantiated if the active Hustle version is lower than the defined here.
	 * If the minimum Hustle version your integration requires is different than this one, override this property.
	 * Kept public so it can be retrieved by the abstract class on PHP 5.2.
	 * -Required. It's '3.0.5' by default.
	 *
	 * @example '3.0.6'
	 * @since 3.0.5
	 * @var string
	 */
	public static $_min_hustle_version = '3.0.5';

	/**
	 * Minimum PHP version required by your integration in order to work properly.
	 * Your integration won't be instantiated if the current PHP version is lower than the defined here.
	 * If your integration requires a minimum PHP version, override this property.
	 * Kept public so it can be retrieved by the abstract class on PHP 5.2.
	 * -Required. There's no minimum by default.
	 *
	 * @example '7.0.0'
	 * @since 3.0.5
	 * @var string
	 */
	public static $_min_php_version = PHP_VERSION;

	/**
	 * Slug will be used as an identifier throughout hustle.
	 * Make sure it's unique, else it won't be loaded or will carelessly override other provider with same slug.
	 * -Required.
	 *
	 * @example 'my_unique_provider_slug'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_slug;

	/**
	 * Version number of the integration.
	 * -Required.
	 *
	 * @example '1.0'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_version;

	/**
	 * Class name of your integration's main class.
	 * That's the one extending Hustle_Provider_Abstract class. Yes, this class.
	 * -Required.
	 *
	 * @example __CLASS__
	 * @since 3.0.5
	 * @var string
	 */
	protected $_class;

	/**
	 * Title of your integration.
	 * It will be shown on the integration's list, and when your integration is selected.
	 * -Required.
	 *
	 * @example 'My Unique Provider'
	 * @since 3.0.5
	 * @var string
	 */
	protected $_title;

	/**
	 * Icon url that will be displayed in the providers list.
	 * Should be retina ready.
	 * Used for JPG and PNG icons.
	 * -Optional. Required if you want to display an image for your provider.
	 *
	 * @example plugin_dir_url( __FILE__ ) . 'assets/icon.png'
	 * @since  3.0.5
	 * @var string
	 */
	protected $_icon_2x;

	/**
	 * Retina logo url that will be displayed in the provider's modal,
	 * Should be retina ready.
	 * Used for JPG and PNG icons.
	 * -Optional. Required if you want to display an image for your provider.
	 *
	 * @example plugin_dir_url( __FILE__ ) . 'assets/logo.png'
	 * @since  4.0.0
	 * @var string
	 */
	protected $_logo_2x;

	/**
	 * Regular banner for promoting the provider.
	 * Used in the not-connected column of the global integrations page.
	 * -Optional.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $_banner_1x;

	/**
	 * Retina banner for promoting the provider.
	 * Should be retina ready.
	 * Used in the not-connected column of the global integrations page.
	 * -Optional.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $_banner_2x;

	/**
	 * Provider's documentation URL.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $_documentation_url;

	/**
	 * Short description to be used in the non-connected integrations column.
	 *
	 * @since 4.0.1
	 * @var string
	 */
	protected $_short_description;

	/**
	 * Whether the provider supports having multiple instances in the modules.
	 * Override if required.
	 *
	 * @since 4.0.0
	 *
	 * @var bool
	 */
	protected $is_multi_on_form = false;

	/**
	 * Whether the provider supports having multiple instances in the global settings.
	 * Override if required.
	 *
	 * @since 4.0.0
	 *
	 * @var bool
	 */
	protected $is_multi_on_global = true;

	/**
	 * Flag that a provider can be activated.
	 * Hustle will assign its value according to @see Hustle_Provider_Abstract::check_is_activable().
	 * -Shouldn't be overridden.
	 *
	 * @since 3.0.5
	 * @var bool
	 */
	private $is_activable = null;

	/**
	 * Semaphore for non redundant hooks on admin side
	 *
	 * @since 4.0.0
	 * @var bool
	 */
	private $_is_admin_hooked = false;

	/**
	 * Semaphore non redundant hooks for global hooks
	 *
	 * @since 4.0.0
	 * @var bool
	 */
	private $_is_global_hooked = false;

	/*********************************** Errors Messages ********************************/

	/**
	 * Error Message on activation
	 *
	 * @since  4.0.0
	 * @var string
	 */
	protected $_activation_error_message = '';

	/*********************************** END Errors Messages ********************************/

	/**
	 * Class name of your integration form settings class.
	 * Leave empty your integration doesn't have settings.
	 * This class must exist on runtime in order to work.
	 * -Optional.
	 *
	 * @example 'Hustle_Mailchimp_Form_Settings'
	 * @since 3.0.5
	 * @var null|string
	 */
	protected $_form_settings = null;

	/**
	 * Classname of form hooks in string or empty if the form hooks is not needed.
	 * This class must exist on runtime in order to work.
	 * -Optional.
	 *
	 * @since 4.0.0
	 * @var null|string
	 */
	protected $_form_hooks = null;

	/**
	 * Form Setting Instances with 'module_id' as key
	 * If your integration has a value assigned to @see Hustle_Provider_Abstract::$_form_settings ,
	 * an instance of that class will be assigned to this property.
	 * -Shouldn't be overridden.
	 *
	 * @since 3.0.5
	 * @since 4.0.0 Array containing multiple instances
	 *
	 * @var Hustle_Provider_Form_Settings_Abstract[]|array
	 */
	protected $_provider_form_settings_instance = array();

	/**
	 * Form Hooks Instances with `module_id` as key
	 *
	 * @since 4.0.0
	 * @var Hustle_Provider_Form_Hooks_Abstract[]|array
	 */
	protected $_provider_form_hooks_instances = array();

	/**
	 * Array of options which should exist for confirming that settings are completed
	 *
	 * @since 4.0.0
	 * @var Hustle_Provider_Form_Hooks_Abstract[]|array
	 */
	protected $_completion_options = array( 'api_key' );

	/**
	 * ID of the selected provider's global instance.
	 *
	 * @since 4.0.0
	 * @var string
	 */
	public $selected_global_multi_id = '';

	/**
	 * Gets the instance of your integration.
	 * This must be added to each provider's class for it to work properly with PHP 5.2.
	 * -Required.
	 *
	 * @since 3.0.5
	 * @return self|null
	 *
	 *
	 * public static function get_instance() {
	 *      if ( is_null( self::$_instance ) ) {
	 *          self::$_instance = new self();
	 *      }
	 *
	 *      return self::$_instance;
	 *  }
	 */


	/**
	 * Gets this provider slug.
	 *
	 * @see Hustle_Provider_Abstract::$_slug
	 *
	 * The slug property behaves as `IDENTIFIER`, used for:
	 * - Easily calling this instance with @see Hustle_Provider_Utils::get_provider_by_slug(`slug`)
	 * - Avoid collision, registered as FIFO by @see Hustle_Providers::register()
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_slug() {
		return $this->_slug;
	}

	/**
	 * Gets this integration version.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_version() {
		return $this->_version;
	}

	/**
	 * Gets this integration class name.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_class() {
		return $this->_class;
	}

	/**
	 * Gets the title of this integration.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_title() {
		return $this->_title;
	}

	/**
	 * Gets retina icon URL.
	 *
	 * @since  3.0.5
	 * @return string
	 */
	final public function get_icon_2x() {
		return $this->_icon_2x;
	}

	/**
	 * Gets retina logo URL.
	 *
	 * @since 4.0.0
	 * @return string
	 */
	final public function get_logo_2x() {
		return $this->_logo_2x;
	}

	/**
	 * Get retina ready promotion banner.
	 *
	 * @since 4.0.1
	 * @return string
	 */
	final public function get_banner_1x() {
		return $this->_banner_1x;
	}

	/**
	 * Get retina ready banner
	 *
	 * @since 4.0.1
	 * @return string
	 */
	final public function get_banner_2x() {
		return $this->_banner_2x;
	}

	/**
	 * Get the documentation url.
	 *
	 * @since 4.0.1
	 * @return string
	 */
	final public function get_documentation_url() {
		return $this->_documentation_url;
	}

	/**
	 * Get the short description.
	 *
	 * @since 4.0.1
	 * @return string
	 */
	final public function get_short_description() {
		return $this->_short_description;
	}

	/**
	 * Get whether the provider allows having multiple instances on a form.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	final public function is_allow_multi_on_form() {
		return $this->is_multi_on_form;
	}

	/**
	 * Get whether the provider allows having multiple instances on the global settings.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	final public function is_allow_multi_on_global() {
		return $this->is_multi_on_global;
	}

	/**
	 * WP options name that holds the settings of the provider
	 *
	 * @since  4.0.0
	 * @return string
	 */
	final public function get_settings_options_name() {
		$addon_slug            = $this->get_slug();
		$addon                 = $this;
		$settings_options_name = 'hustle_provider_' . $this->get_slug() . '_settings';

		/**
		 * Filter wp options name for saving addon settings
		 *
		 * @since 4.0.1
		 *
		 * @param string $settings_options_name
		 * @param Hustle_Provider_Abstract $addon provider instance
		 */
		$settings_options_name = apply_filters( 'hustle_provider_' . $addon_slug . '_settings_options_name', $settings_options_name, $addon );

		return $settings_options_name;
	}

	/**
	 * WP options name that holds current version of addon
	 *
	 * @since  1.1
	 * @return string
	 */
	final public function get_version_options_name() {
		$addon_slug           = $this->get_slug();
		$addon                = $this;
		$version_options_name = 'hustle_provider_' . $this->get_slug() . '_version';

		/**
		 * Filter wp options name for saving addon settings
		 *
		 * @since 4.0.1
		 *
		 * @param string $version_options_name
		 * @param Hustle_Provider_Abstract $addon provider instance
		 */
		$version_options_name = apply_filters( 'hustle_provider_' . $addon_slug . '_version_options_name', $version_options_name, $addon );

		return $version_options_name;
	}

	/**
	 * Transforms some properties of the integration instance into an array.
	 *
	 * @since 3.0.5
	 *
	 * @return array
	 */
	final public function to_array() {
		$is_allow_multi_on_global = $this->is_allow_multi_on_global();

		$to_array = array(
			'slug'                  => $this->get_slug(),
			'title'                 => $this->get_title(),
			'icon_2x'               => $this->get_icon_2x(),
			'logo_2x'               => $this->get_logo_2x(),
			'banner_1x'             => $this->get_banner_1x(),
			'banner_2x'             => $this->get_banner_2x(),
			'documentation_url'     => $this->get_documentation_url(),
			'short_description'     => $this->get_short_description(),
			'version'               => $this->get_version(),
			'class'                 => $this->get_class(),
			'is_multi_on_global'    => $this->is_allow_multi_on_global(),
			'is_activable'          => $this->is_activable(),
			'is_settings_available' => $this->is_settings_available(),
			'is_connected'          => $this->is_connected(),
		);

		if ( $is_allow_multi_on_global ) {
			$to_array['global_multi_ids'] = $this->get_global_multi_ids();
		}

		return $to_array;
	}

	/**
	 * Transform provider instance into array with module relation
	 *
	 * @since  4.0.0
	 *
	 * @param string $module_id ID of the module to retrieve the settings from.
	 * @return array
	 */
	final public function to_array_with_form( $module_id ) {
		$to_array                               = $this->to_array();
		$is_allow_multi_on_form                 = $this->is_allow_multi_on_form();
		$to_array['is_form_connected']          = $this->is_form_connected( $module_id );
		$to_array['is_form_settings_available'] = $this->is_form_settings_available( $module_id );
		$to_array['is_allow_multi_on_form']     = $is_allow_multi_on_form;

		// Handle multiple form setting.
		if ( $is_allow_multi_on_form ) {
			$to_array['multi_ids'] = $this->get_form_settings_multi_ids( $module_id );
		}

		$to_array_with_form = $to_array;

		return $to_array_with_form;
	}

	/**
	 * Gets activable status.
	 *
	 * @return bool
	 */
	final public function is_activable() {
		if ( is_null( $this->is_activable ) ) {
			$this->is_activable = $this->check_is_activable();
		}

		return $this->is_activable;
	}

	/**
	 * Checks if the integration meets the requirements to be activated.
	 * Override this method if you have another logic for checking activable integrations.
	 * Non-activable integrations are instantiated, but not listed for the users to be used.
	 * If your integration has certain requirements that should prevent it from being
	 * instantiated if not met, override @see Hustle_Provider_Abstract::check_is_compatible() instead.
	 * -Optional.
	 *
	 * @return bool
	 */
	public function check_is_activable() {
		if ( ! self::check_is_compatible( $this->_class ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the provider meets the requirements to be instantiated.
	 * If the provider is not compatible, it won't be instantiated.
	 * Instantiating a not compatible provider may trigger PHP errors.
	 * By default, it will return false if:
	 * -The installed PHP version is lower than the required by your integration.
	 * -The installed Hustle version is lower than the required by your integration.
	 *
	 * Override this method if you have another logic for checking if your integration is compatible.
	 * -Optional.
	 *
	 * @since 3.0.5
	 *
	 * @param string $class_name Provider's class name.
	 * @return bool
	 */
	public static function check_is_compatible( $class_name ) {

		// PHP 5.2 compatibility.
		// We can remove this now. YAI!!
		$reflector = new ReflectionClass( $class_name );

		$_min_php_version         = $reflector->getStaticPropertyValue( '_min_php_version' );
		$is_php_version_supported = version_compare( PHP_VERSION, $_min_php_version, '>=' );
		if ( ! $is_php_version_supported ) {
			return false;
		}

		// If it's a test version, skip Hustle version validation.
		if ( false !== stripos( Opt_in::VERSION, 'beta' ) || false !== stripos( Opt_in::VERSION, 'alpha' ) ) {
			return true;
		}

		$_min_hustle_version         = $reflector->getStaticPropertyValue( '_min_hustle_version' );
		$is_hustle_version_supported = version_compare( Opt_In::VERSION, $_min_hustle_version, '>=' );
		if ( ! $is_hustle_version_supported ) {
			return false;
		}

		return true;
	}

	/**
	 * Override this method to add an action when the user deactivates the addon.
	 *
	 * @example DROP table
	 * return true when succes
	 * return false on failure, it will stop the deactivate process
	 *
	 * @since 4.0.0
	 *
	 * @param array $data Data passed during deactivation.
	 * @return bool
	 */
	public function deactivate( $data = array() ) {
		return true;
	}

	/**
	 * Override this method to add an action when the user activates the provider
	 *
	 * @example CREATE table
	 * return true when succes, false on failure. Hustle will stop activation process on failure.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public function activate() {
		return true;
	}

	/**
	 * Override this method to add an action when the version of the provider changed.
	 *
	 * @example CREATE table
	 * return true when succes
	 * return false on failure, forminator will stop activation process
	 *
	 * @since 4.0.3
	 *
	 * @param string $old_version Version of the previously installed provider.
	 * @param string $new_version Version of the current provider.
	 *
	 * @return bool true on success, false on failure. This will stop the activation process
	 */
	public function version_changed( $old_version, $new_version ) {
		return true;
	}

	/**
	 * Check if the version of the provider has changed.
	 *
	 * @since 4.0.3
	 * @return bool
	 */
	final public function is_version_changed() {
		$installed_version = $this->get_installed_version();
		// New installed.
		if ( false === $installed_version ) {
			return false;
		}
		$version_is_changed = version_compare( $this->_version, $installed_version, '!=' );
		if ( $version_is_changed ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the currently installed provider version
	 * retrieved from the wp options.
	 *
	 * @since 4.0.3
	 * @return string|bool
	 */
	final public function get_installed_version() {
		return get_option( $this->get_version_options_name(), false );
	}

	/**
	 * Get error message on activation
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function get_activation_error_message() {
		return $this->_activation_error_message;
	}

	/**
	 * Get Global settings wizard
	 * This function will process @see Hustle_Provider_Abstract::settings_wizards()
	 * Keep in mind this function will only be called when @see Hustle_Provider_Abstract::is_settings_available() returns `true`
	 * which will call @see Hustle_Provider_Abstract::settings_wizards() to check if requirements are passed.
	 *
	 * @since 4.0.0
	 *
	 * @param array $submitted_data Array with the submitted data. Softly sanitized by @see Opt_In_Utils::validate_and_sanitize_fields().
	 * @param int   $module_id ID of the module to which the settings wizard belongs to if retrieved from within a module and not from global settings.
	 * @param int   $current_step Step from which the call is made.
	 * @param int   $step Step to which the user is going.
	 *
	 * @return array|mixed
	 */
	final public function get_settings_wizard( $submitted_data, $module_id = 0, $current_step = 0, $step = 0 ) {

		$steps = $this->settings_wizards();

		if ( ! is_array( $steps ) ) {
			/* translators: provider's title */
			return $this->get_empty_wizard( sprintf( __( 'No settings available for %s', 'hustle' ), $this->get_title() ) );
		}

		$total_steps = count( $steps );
		if ( $total_steps < 1 ) {
			/* translators: provider's title */
			return $this->get_empty_wizard( sprintf( __( 'No settings available for %s', 'hustle' ), $this->get_title() ) );
		}

		if ( ! isset( $steps[ $step ] ) ) {
			// Go to last step.
			$step = $total_steps - 1;
			return $this->get_settings_wizard( $submitted_data, $module_id, $current_step );
		}

		if ( $step > 0 ) {
			if ( $current_step > 0 ) {
				// Check previous step is complete.
				$prev_step              = $current_step - 1;
				$prev_step_is_completed = true;
				// Only call `is_completed` when its defined.
				if ( isset( $steps[ $prev_step ]['is_completed'] ) && is_callable( $steps[ $prev_step ]['is_completed'] ) ) {
					$prev_step_is_completed = call_user_func( $steps[ $prev_step ]['is_completed'], $submitted_data );
				}
				if ( ! $prev_step_is_completed ) {
					$step --;

					return $this->get_settings_wizard( $submitted_data, $module_id, $current_step, $step );
				}
			}

			// Only validation when it moves forward.
			if ( $step > $current_step ) {
				$current_step_result = $this->get_settings_wizard( $submitted_data, $module_id, $current_step, $current_step );
				if ( isset( $current_step_result['has_errors'] ) && true === $current_step_result['has_errors'] ) {
					return $current_step_result;
				} else {
					// Set empty submitted data for next step.
					$submitted_data = array();
				}
			}
		}

		return $this->get_wizard( $steps, $submitted_data, $module_id, $step );

	}

	/**
	 * Get Form Setting Wizard
	 * This function will process @see Hustle_Provider_Abstract::form_settings_wizard()
	 * Keep in mind this function will only be called when @see Hustle_Provider_Abstract::is_form_settings_available() returns `true`
	 * which will call @see Hustle_Provider_Abstract::form_settings_wizard() to check if requirements are passed.
	 *
	 * @since 3.0.5
	 * @since 4.0.0   Add global form settings steps at the beginning if the provider is not already connected. $module_id param added.
	 *
	 * @param array $submitted_data Array with the submitted data. Softly sanitized by @see Opt_In_Utils::validate_and_sanitize_fields().
	 * @param int   $module_id ID of the module to which the setting wizard belongs to.
	 * @param int   $current_step Step from which the call is made.
	 * @param int   $step Step to which the user is going.
	 *
	 * @return array|mixed
	 */
	final public function get_form_settings_wizard( $submitted_data, $module_id, $current_step = 0, $step = 0 ) {

		// Check if provider is connected, if so - go to the next step.
		if ( $this->is_connected() && 0 === $current_step ) {
			++$step;
			++$current_step;
		}

		// Check if the global account was already selected, if so - go to the next step.
		$form_settings_instance = $this->get_provider_form_settings( $module_id );
		if ( $form_settings_instance->is_multi_global_select_step_completed() && 1 === $current_step ) {
			++$step;
			++$current_step;
		}

		$settings_steps      = $this->settings_wizards();
		$form_settings_steps = $this->get_form_settings_steps( $module_id );

		$steps = array_merge( $settings_steps, $form_settings_steps );

		if ( ! is_array( $steps ) ) {
			/* translators: provider's title */
			return $this->get_empty_wizard( sprintf( __( 'No Form Settings available for %s', 'hustle' ), $this->get_title() ) );
		}
		$total_steps = count( $steps );
		if ( $total_steps < 1 ) {
			/* translators: provider's title */
			return $this->get_empty_wizard( sprintf( __( 'No Form Settings available for %s', 'hustle' ), $this->get_title() ) );
		}

		if ( ! isset( $steps[ $step ] ) ) {
			// Go to last step.
			$step = $total_steps - 1;
			return $this->get_form_settings_wizard( $submitted_data, $module_id, $current_step, $step );
		}

		if ( $step > 0 ) {
			if ( $current_step > 0 ) {
				// Check previous step is complete.
				$prev_step              = $current_step - 1;
				$prev_step_is_completed = true;
				// Only call `is_completed` when its defined.
				if ( isset( $steps[ $prev_step ]['is_completed'] ) && is_callable( $steps[ $prev_step ]['is_completed'] ) ) {
					$prev_step_is_completed = call_user_func( $steps[ $prev_step ]['is_completed'], $submitted_data );
				}
				if ( ! $prev_step_is_completed ) {
					$step --;

					return $this->get_form_settings_wizard( $submitted_data, $module_id, $current_step, $step );
				}
			}

			// Only validation when it moves forward.
			if ( $step > $current_step ) {
				$current_step_result = $this->get_form_settings_wizard( $submitted_data, $module_id, $current_step, $current_step );
				if ( isset( $current_step_result['has_errors'] ) && true === $current_step_result['has_errors'] ) {
					return $current_step_result;
				} else {
					// Set empty submitted data for next step, except preserved as reference.
					$preserved_keys = array(
						'multi_id',
					);
					foreach ( $submitted_data as $key => $value ) {
						if ( ! in_array( $key, $preserved_keys, true ) ) {
							unset( $submitted_data[ $key ] );
						}
					}
				}
			}
		}
		return $this->get_wizard( $steps, $submitted_data, $module_id, $step );
	}

	/**
	 * Gets the steps from integration's form settings wizard.
	 *
	 * @since 3.0.5
	 * @since 4.0.0 $module_id param added
	 *
	 * @param string $module_id ID of the module to get the settings steps for.
	 * @param bool   $check_steps_exist Check are steps available?.
	 * @return array
	 */
	private function get_form_settings_steps( $module_id, $check_steps_exist = false ) {

		$form_settings_instance = $this->get_provider_form_settings( $module_id );
		$form_settings_steps    = array();
		if ( $this->is_allow_multi_on_global() ) {
			$form_settings_steps = $form_settings_instance->get_form_settings_global_multi_id_step();
		}

		if ( $check_steps_exist && ! empty( $form_settings_steps ) ) {
			// If we already got some steps and we're checking are steps available - just return these steps whithout additional work.
			return $form_settings_steps;
		}

		if ( ! is_null( $form_settings_instance ) && $form_settings_instance instanceof Hustle_Provider_Form_Settings_Abstract ) {
			$form_settings_steps = array_merge( $form_settings_steps, $form_settings_instance->form_settings_wizards() );
		}

		return $form_settings_steps;
	}

	/**
	 * Checks whether the integration has global settings available.
	 * This function will check @see Hustle_Provider_Abstract::settings_wizards()
	 * as a valid multi array.
	 *
	 * @since 3.0.5
	 * @return bool
	 */
	public function is_settings_available() {
		if ( ! is_admin() ) {
			return false;
		}
		$steps = $this->settings_wizards();
		if ( ! is_array( $steps ) ) {
			return false;
		}

		if ( count( $steps ) < 1 ) {
			return false;
		}

		return true;
	}


	/**
	 * Checks whether the integration has available form settings.
	 * This function will check @see Hustle_Provider_Form_Settings_Abstract::form_settings_wizards()
	 * as a valid multi array.
	 *
	 * @since 3.0.5
	 * @since 4.0.0 $module_id param added
	 *
	 * @param string $module_id ID of the module to check whether the form settings is available for.
	 * @return bool
	 */
	final public function is_form_settings_available( $module_id ) {
		if ( ! is_admin() ) {
			return false;
		}
		$steps = $this->get_form_settings_steps( $module_id, true );

		if ( ! is_array( $steps ) || count( $steps ) < 1 ) {
			return false;
		}

		return true;
	}

	/**
	 * Flag to check if a provider is connected. This is true if the global settings such as the API key is completed.
	 *
	 * @since   4.0.0
	 * @return boolean
	 */
	final public function is_connected() {
		if ( ! $this->is_active() ) {
			return false;
		}

		if ( $this->is_allow_multi_on_global() ) {
			// Mark as active when there's at least one active connection.
			if ( false !== $this->find_one_global_active_connection() ) {
				return true;
			}
		} else {
			if ( $this->settings_are_completed() ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Flag to check if the settings is completed. This is true if the global settings such as the API key is completed.
	 *
	 * @since   4.0.0
	 *
	 * @param string $multi_id ID of the global instance of the provider.
	 * @return boolean
	 */
	protected function settings_are_completed( $multi_id = '' ) {
		$settings_values = $this->get_settings_values();
		$is_connected    = true;

		foreach ( $this->_completion_options as $key ) {
			if ( empty( $multi_id ) || ! is_string( $multi_id ) ) {
				$is_connected = $is_connected && ! empty( $settings_values[ $key ] );
			} else {
				$is_connected = $is_connected && ! empty( $settings_values[ $multi_id ][ $key ] );
			}
		}

		return $is_connected;
	}

	/**
	 * Flag for check if a provider is connected to a module.
	 * This is true when a module's setting such as list id is completed.
	 *
	 * @since 4.0.0
	 *
	 * @param string $module_id ID of the module to check.
	 * @return boolean
	 */
	public function is_form_connected( $module_id ) {
		if ( ! $this->is_connected() ) {
			return false;
		}

		$form_settings_instance = $this->get_provider_form_settings( $module_id );
		if ( ! $form_settings_instance instanceof $this->_form_settings ) {
			return false;
		}

		$saved_form_settings = $form_settings_instance->get_form_settings_values();

		if ( empty( $saved_form_settings ) ) {
			return false;
		}

		$is_connected          = true;
		$required_form_options = $form_settings_instance->get_form_completion_options( $saved_form_settings );

		foreach ( $required_form_options as $option ) {
			$is_connected = $is_connected && ! empty( $saved_form_settings[ $option ] );
		}

		// Disconnect the form if the settings are half-way completed.
		if ( ! $is_connected ) {
			$form_settings_instance->disconnect_form( array() );

			// Check if the parent exists. Disconnect from form if it doesn't.
		} elseif ( $this->is_allow_multi_on_global() ) {
			$selected_global_multi_id = $this->get_selected_global_multi_id( $module_id );

			if ( empty( $selected_global_multi_id ) ) {
				$form_settings_instance->disconnect_form( array() );
			}
		}

		return $is_connected;
	}

	/**
	 * Add Identifier name to the provider $data
	 *
	 * @param array  $data Provider data.
	 * @param string $module_id Module id.
	 * @return array
	 */
	public function maybe_add_multi_name( $data, $module_id ) {
		$selected_global_multi_id = $this->get_selected_global_multi_id( $module_id );
		if ( ! empty( $selected_global_multi_id ) && ! empty( $selected_global_multi_id['name'] ) ) {
			$data['multi_name'] = $selected_global_multi_id['name'];
		}

		return $data;
	}

	/**
	 * Get selected global multi id (Identifier)
	 *
	 * @param string $module_id Module id.
	 * @return boolean|array
	 */
	public function get_selected_global_multi_id( $module_id ) {
		$form_settings_instance = $this->get_provider_form_settings( $module_id );
		if ( ! $form_settings_instance instanceof $this->_form_settings ) {
			return false;
		}

		$global_settings = $this->get_settings_values();
		$form_settings   = $form_settings_instance->get_form_settings_values();

		// Disconnect integration from form if the global instance it was connected to is gone.
		if ( ! empty( $form_settings['selected_global_multi_id'] ) && ! empty( $global_settings[ $form_settings['selected_global_multi_id'] ] ) ) {
			return $global_settings[ $form_settings['selected_global_multi_id'] ];
		}

		return false;
	}

	/**
	 * Return wether the provider is active.
	 *
	 * @since 4.0.0
	 *
	 * @return boolean
	 */
	final public function is_active() {
		return Hustle_Provider_Utils::is_provider_active( $this->get_slug() );
	}

	/**
	 * Gets the class name of the integration's form settings class.
	 *
	 * @see   Hustle_Provider_Form_Settings_Abstract
	 *
	 * @since 3.0.5
	 * @return null|string
	 */
	final public function get_form_settings_class_name() {
		$provider_slug            = $this->get_slug();
		$form_settings_class_name = $this->_form_settings;

		/**
		 * Filter the class name of the integration's form settings class.
		 *
		 * Form settings class name is a string
		 * it will be validated by `class_exists` and must be instanceof @see Hustle_Provider_Form_Settings_Abstract
		 *
		 * @since 3.0.5
		 * @param string $form_settings_class_name
		 */
		$form_settings_class_name = apply_filters( 'hustle_provider_' . $provider_slug . '_form_settings_class_name', $form_settings_class_name );

		return $form_settings_class_name;
	}

	/**
	 * Gets Form Settings Instance.
	 *
	 * @since   3.0.5
	 *
	 * @param string $module_id ID of the module.
	 * @return Hustle_Provider_Form_Settings_Abstract | null
	 * @throws Exception With the message to add to logs.
	 */
	final public function get_provider_form_settings( $module_id ) {
		$class_name = $this->get_form_settings_class_name();
		if ( ! isset( $this->_provider_form_settings_instance[ $module_id ] ) || ! $this->_provider_form_settings_instance[ $module_id ] instanceof Hustle_Provider_Form_Settings_Abstract ) {
			if ( empty( $class_name ) ) {
				return null;
			}

			if ( ! class_exists( $class_name ) ) {
				return null;
			}

			try {
				$form_settings_instance = new $class_name( $this, $module_id );
				if ( ! $form_settings_instance instanceof Hustle_Provider_Form_Settings_Abstract ) {
					throw new Exception( $class_name . ' is not instanceof Hustle_Provider_Form_Settings_Abstract' );
				}
				$this->_provider_form_settings_instance[ $module_id ] = $form_settings_instance;
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( $this->get_slug(), 'Failed to instantiate its _form_settings_instance', $e->getMessage() );

				return null;
			}
		}

		return $this->_provider_form_settings_instance[ $module_id ];
	}

	/**
	 * Executor of before_get_form_settings values, to be correctly mapped with form_setting instance for module_id.
	 *
	 * @since 4.0.0
	 *
	 * @param array  $values Settings to be stored.
	 * @param string $module_id ID of the module to store the settings into.
	 *
	 * @return mixed
	 */
	final public function before_get_form_settings_values( $values, $module_id ) {
		$form_settings = $this->get_provider_form_settings( $module_id );
		if ( $form_settings instanceof Hustle_Provider_Form_Settings_Abstract ) {
			if ( is_callable( array( $form_settings, 'before_get_form_settings_values' ) ) ) {
				return $form_settings->before_get_form_settings_values( $values );
			}
		}

		return $values;
	}

	/**
	 * Executor of before_save_form_settings_ values, to be correctly mapped with form_setting instance for module_id
	 *
	 * @since 4.0.0
	 *
	 * @param array  $values Settings to be stored.
	 * @param string $module_id ID of the module to store the settings into.
	 *
	 * @return mixed
	 */
	final public function before_save_form_settings_values( $values, $module_id ) {
		$form_settings = $this->get_provider_form_settings( $module_id );
		if ( $form_settings instanceof Hustle_Provider_Form_Settings_Abstract ) {
			if ( is_callable( array( $form_settings, 'before_save_form_settings_values' ) ) ) {
				return $form_settings->before_save_form_settings_values( $values );
			}
		}

		return $values;
	}

	/**
	 * Get Form Hooks of Addons
	 *
	 * @since 4.0.0
	 *
	 * @param string $module_id Module ID.
	 * @return Hustle_Provider_Form_Hooks_Abstract|null
	 */
	final public function get_addon_form_hooks( $module_id ) {
		if ( ! isset( $this->_provider_form_hooks_instances[ $module_id ] ) || ! $this->_provider_form_hooks_instances[ $module_id ] instanceof Hustle_Provider_Form_Hooks_Abstract ) {
			if ( empty( $this->_form_hooks ) ) {
				return null;
			}

			if ( ! class_exists( $this->_form_hooks ) ) {
				return null;
			}

			try {

				$classname = $this->_form_hooks;
				$this->_provider_form_hooks_instances[ $module_id ] = new $classname( $this, $module_id );
			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( $this->get_slug(), 'Failed to instantiate its _addon_form_hooks_instance', $e->getMessage() );

				return null;
			}
		}

		return $this->_provider_form_hooks_instances[ $module_id ];
	}

	/**
	 * Gets the requested wizard.
	 *
	 * @since 3.0.5
	 * @since 4.0.0 $module_id param added. $is_close, $is_submit, $data_to_save params removed.
	 *
	 * @param array  $steps Array with all the wizard's steps from the integration.
	 * @param array  $submitted_data Array with the submitted data. Softly sanitized by @see Opt_In_Utils::validate_and_sanitize_fields().
	 * @param string $module_id Module ID.
	 * @param int    $step Step from which the call is made.
	 *
	 * @return array|mixed
	 */
	private function get_wizard( $steps, $submitted_data, $module_id, $step = 0 ) {
		$total_steps = count( $steps );
		$is_submit   = ! empty( $submitted_data['hustle_is_submit'] );

		// Validate callback, when its empty or not callable, mark as no wizard.
		if ( ! isset( $steps[ $step ]['callback'] ) || ! is_callable( $steps[ $step ]['callback'] ) ) {
			/* translators: provider's title */
			return $this->get_empty_wizard( sprintf( __( 'No Settings available for %s', 'hustle' ), $this->get_title() ) );
		}

		$wizard = call_user_func( $steps[ $step ]['callback'], $submitted_data, $is_submit, $module_id );
		// A wizard to be able to processed by our application need to has at least `html`
		// which will be rendered or `redirect` which will be the url for redirect user to go to.
		if ( ! isset( $wizard['html'] ) && ! isset( $wizard['redirect'] ) ) {
			/* translators: provider's title */
			return $this->get_empty_wizard( sprintf( __( 'No Settings available for %s', 'hustle' ), $this->get_title() ) );
		}

		// Add 'hustle_is_submit' hidden input at the end.
		if ( isset( $wizard['html'] ) ) {
			$wizard['html'] = $wizard['html'] . $this->get_step_html_common_hidden_fields( $submitted_data );
		}

		$wizard['opt_in_provider_current_step']  = $step;
		$wizard['opt_in_provider_count_step']    = $total_steps;
		$wizard['opt_in_provider_has_next_step'] = ( ( $step + 1 ) >= $total_steps ? false : true );
		$wizard['opt_in_provider_has_prev_step'] = ( $step > 0 ? true : false );

		// If ['data_to_save] is set on $wizard, that would mean the provider hasn't been apdapted
		// to 4.0. Save the data here if it's not updated so it keeps working.
		if ( isset( $wizard['data_to_save'] ) ) {
			$form_settings_instance = $this->get_provider_form_settings( $module_id );
			$form_settings_instance->save_form_settings_values( $wizard['data_to_save'] );
		}

		// Close the modal if...
		$do_close = (
			// It's a submission.
			! empty( $submitted_data['hustle_is_submit'] ) &&
			// We're in the last step.
			! $wizard['opt_in_provider_has_next_step'] &&
			// And there are no errors.
			( ! isset( $wizard['has_errors'] ) || ! $wizard['has_errors'] )
		);

		if ( $do_close ) {
			$wizard['is_close'] = true;
		}

		$wizard_default_values = array(
			'has_errors'   => false,
			'is_close'     => false,
			'notification' => array(),
			'size'         => 'small',
			'has_back'     => false,
		);

		foreach ( $wizard_default_values as $key => $wizard_default_value ) {
			if ( ! isset( $wizard[ $key ] ) ) {
				$wizard[ $key ] = $wizard_default_value;
			}
		}

		$wizard = apply_filters( 'hustle_get_integration_form_wizard', $wizard, $this, $submitted_data, $module_id, $steps, $step );

		return $wizard;
	}

	/**
	 * Gets empty wizard markup.
	 * Helper to display a user friendly step when no settings are available.
	 *
	 * @since 3.0.5
	 * @param string $notice Message to be shown.
	 * @return array
	 */
	public function get_empty_wizard( $notice ) {

		$notice_markup  = '<div class="sui-notice sui-notice-error"><div class="sui-notice-content"><div class="sui-notice-message">';
		$notice_markup .= '<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>';
		$notice_markup .= '<p>' . esc_html( $notice ) . '</p>';
		$notice_markup .= '</div></div></div>';

		return array(
			'html'    => $notice_markup,
			'buttons' => array(
				'close' => array(
					'action' => 'close',
					'data'   => array(),
					'markup' => '<a href="" class="hustle-provider-next wpmudev-button wpmudev-button-ghost">' . __( 'Close', 'hustle' ) . '</a>',
				),
			),
		);
	}

	/**
	 * Override this function if your provider does something with the settings values.
	 * Called when rendering settings form.
	 *
	 * @example transform, load from other storage ?
	 *
	 * @since   4.0.0
	 *
	 * @param array $values Settings to be retrieved.
	 * @return mixed
	 */
	public function before_get_settings_values( $values ) {
		return $values;
	}

	/**
	 * Get settings value
	 *
	 * @see Hustle_Provider_Abstract::before_get_settings_values()
	 *
	 * @since 4.0.0
	 * @return array
	 */
	final public function get_settings_values() {
		$provider_slug = $this->get_slug();
		$values        = get_option( $this->get_settings_options_name(), array() );

		/**
		 * Filter the retrieved addon's settings values from db.
		 *
		 * @since 4.0.0
		 *
		 * @param mixed $values
		 */
		$values = apply_filters( 'hustle_provider_' . $provider_slug . '_get_settings_values', $values );

		return $values;
	}

	/**
	 * Override this function if your provider does something with the settings values.
	 * Called before saving the settings values to db.
	 *
	 * @example transform, save to other storage ?
	 *
	 * @since 4.0.0
	 *
	 * @param array $values Settings to be saved.
	 * @return mixed
	 */
	public function before_save_settings_values( $values ) {
		return $values;
	}

	/**
	 * Save settings value
	 * it's already hooked with
	 *
	 * @see Hustle_Provider_Abstract::before_save_settings_values()
	 *
	 * @since 4.0.0
	 * @param array $values Settings to be saved.
	 */
	final public function save_settings_values( $values ) {

		$provider_slug = $this->get_slug();

		/**
		 * Filter the settings values of the provider to be saved.
		 *
		 * `$provider_slug` is the slug of provider that will be saved.
		 * Example : `mailchimp`, `zapier`, `etc`
		 *
		 * @since 4.0.0
		 *
		 * @param mixed $values
		 */
		$values = apply_filters( 'hustle_provider_' . $provider_slug . '_save_settings_values', $values );

		update_option( $this->get_settings_options_name(), $values );
	}

	/**
	 * Saves the settings for the given $global_multi_id.
	 *
	 * @since 4.0.0
	 * @uses Hustle_Provider_Abstract::save_settings_values()
	 *
	 * @param string $global_multi_id ID of the global instance of the provider.
	 * @param array  $values Settings to be stored.
	 */
	public function save_multi_settings_values( $global_multi_id, $values ) {

		$saved_settings = $this->get_settings_values();
		if ( $this->is_allow_multi_on_global() ) {
			$settings_to_save = array_merge(
				$saved_settings,
				array(
					$global_multi_id => $values,
				)
			);
		} else {
			$settings_to_save = $values;
		}

		$this->save_settings_values( $settings_to_save );
	}

	/**
	 * Retrieves the settings for the provider's global instance.
	 *
	 * @since 4.2.0
	 *
	 * @param boolean|string $global_multi_id ID of the global instance of the provider. False if not used.
	 * @return array
	 */
	public function get_multi_settings_values( $global_multi_id = false ) {

		$settings = $this->get_settings_values();

		if ( $this->is_allow_multi_on_global() ) {
			$settings = ( $global_multi_id && ! empty( $settings[ $global_multi_id ] ) ) ? $settings[ $global_multi_id ] : array();
		}

		return $settings;
	}

	/**
	 * Auto attach default admin hooks for provider
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	final public function admin_hookable() {
		if ( $this->_is_admin_hooked ) {
			return true;
		}

		$default_filters = array(
			'hustle_provider_' . $this->get_slug() . '_save_settings_values' => array( array( $this, 'before_save_settings_values' ), 1 ),
		);

		if ( $this->is_connected() ) {
			$default_filters[ 'hustle_provider_' . $this->get_slug() . '_save_form_settings_values' ] = array( array( $this, 'before_save_form_settings_values' ), 2 );
		}

		foreach ( $default_filters as $filter => $default_filter ) {
			$function_to_add = $default_filter[0];
			if ( is_callable( $function_to_add ) ) {
				$accepted_args = $default_filter[1];
				add_filter( $filter, $function_to_add, 10, $accepted_args );
			}
		}
		$this->_is_admin_hooked = true;

		return true;
	}

	/**
	 * Maintain hooks on all pages for providers.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	final public function global_hookable() {
		if ( $this->_is_global_hooked ) {
			return true;
		}

		$default_filters = array(
			'hustle_provider_' . $this->get_slug() . '_get_settings_values' => array( array( $this, 'before_get_settings_values' ), 1 ),
		);

		if ( $this->is_connected() ) {
			$default_filters[ 'hustle_provider_' . $this->get_slug() . '_get_form_settings_values' ] = array( array( $this, 'before_get_form_settings_values' ), 2 );
		}

		foreach ( $default_filters as $filter => $default_filter ) {
			$function_to_add = $default_filter[0];
			if ( is_callable( $function_to_add ) ) {
				$accepted_args = $default_filter[1];
				add_filter( $filter, $function_to_add, 10, $accepted_args );
			}
		}
		$this->_is_global_hooked = true;

		return true;
	}

	/**
	 * Delete specific WP options for the current provider
	 *
	 * @since 4.0.1
	 */
	public function remove_wp_options() {
		return;
	}

	/**
	 * Gets the provider's data.
	 * General function to get the provider's details from database based on a module_id and field key.
	 * This method required an instance of Hustle_Module_Model. Now it accepts the module_id in order to prevent
	 * third party integrations from having to use new Hustle_Module_Model( $module_id ) just to use this method.
	 * -Helper.
	 *
	 * @param int|Hustle_Module_Model $module_id The ID of the module from which the data will be retrieved.
	 * @param string                  $field The field name in which the requested data is stored.
	 * @param string                  $slug The slug of the provider which data is retrieved.
	 *
	 * @return string
	 */
	public static function get_provider_details( $module_id, $field, $slug ) {
		$details = '';
		if ( is_object( $module_id ) && $module_id instanceof Hustle_Module_Model ) {
			$module = $module_id;
		} else {
			if ( ! ( $module_id instanceof Hustle_Module_Model ) || 0 === (int) $module_id ) {
				return $details;
			}
			$module = new Hustle_Module_Model( $module_id );
			if ( is_wp_error( $module ) ) {
				return $details;
			}
		}

		if ( ! is_null( $module->content->email_services )
			&& isset( $module->content->email_services[ $slug ] )
			&& isset( $module->content->email_services[ $slug ][ $field ] ) ) {

			$details = $module->content->email_services[ $slug ][ $field ];
		}
		return $details;
	}

	/**
	 * Process the return value of an external redirect.
	 * Also, return the behavior to have in the global integrations page.
	 * Useful for handling oAuth.
	 *
	 * @since 4.0.2
	 *
	 * @return array
	 */
	public function process_external_redirect() {
		return array();
	}

	/**
	 * Updates provider's db option with the new value.
	 *
	 * @uses update_option
	 * @param string $option_key Name of the provider's option to be stored.
	 * @param mixed  $option_value Value to be stored.
	 * @return bool
	 */
	public function update_provider_option( $option_key, $option_value ) {
		return update_option( $this->get_slug() . '_' . $option_key, $option_value );
	}

	/**
	 * Retrieves provider's option from db.
	 *
	 * @uses get_option
	 * @param string $option_key Name of the option to retrieve.
	 * @param mixed  $default    Value to return if the option wasn't found.
	 * @return mixed
	 */
	public function get_provider_option( $option_key, $default ) {
		return get_option( $this->get_slug() . '_' . $option_key, $default );
	}

	/**
	 * Delete provider's option from db.
	 *
	 * @since 4.0.1
	 * @uses delete_option
	 * @param string $option_key Name of the option to be deleted.
	 * @return bool
	 */
	public function delete_provider_option( $option_key ) {
		return delete_option( $this->get_slug() . '_' . $option_key );
	}


	/**
	 * Like form_settings_wizards(), but for global settings.
	 * Should be overridden in order to show a wizard in the global settings.
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function settings_wizards() {
		return array();
	}

	/**
	 * Get a stored setting.
	 * Handles global_multi_id if the id is passed.
	 *
	 * @since 4.0.0
	 *
	 * @param string $setting_name    Name of the setting to be retrieved.
	 * @param mixed  $default         Value to return if the setting wasn't found.
	 * @param string $global_multi_id ID of the global instance of the provider.
	 * @return mixed
	 */
	public function get_setting( $setting_name, $default = false, $global_multi_id = false ) {

		$setting_values    = $this->get_settings_values();
		$retrieved_setting = $default;
		if ( $global_multi_id ) {
			if ( isset( $setting_values[ $global_multi_id ] ) ) {
				$account = $setting_values[ $global_multi_id ];

				if ( isset( $account[ $setting_name ] ) ) {
					$retrieved_setting = $account[ $setting_name ];
				}
			}
		} else {
			if ( isset( $setting_values[ $setting_name ] ) ) {
				$retrieved_setting = $setting_values[ $setting_name ];
			}
		}

		return $retrieved_setting;
	}

	/**
	 * Get the first found global actie connection.
	 *
	 * @since 4.0.0
	 * @return false|Hustle_Provider_Abstract
	 */
	public function find_one_global_active_connection() {
		$setting_values = $this->get_settings_values();

		foreach ( $setting_values as $multi_id => $setting ) {
			if ( true === $this->settings_are_completed( $multi_id ) ) {
				return $setting;
			}
		}

		return false;
	}

	/**
	 * Override this function to generate your multiple id for form settings.
	 * Default is uniqid.
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function generate_multi_id() {
		return uniqid( '', true );
	}

	/**
	 * Get an array with the id of the multiple instances of a provider in a module.
	 *
	 * @since 4.0.0
	 *
	 * @param string $module_id ID of the module.
	 * @return array
	 */
	private function get_form_settings_multi_ids( $module_id ) {
		$addon_slug             = $this->get_slug();
		$addon                  = $this;
		$multi_ids              = array();
		$form_settings_instance = $this->get_provider_form_settings( $module_id );
		if ( $this->is_allow_multi_on_form() && ! is_null( $form_settings_instance ) && $form_settings_instance instanceof Hustle_Provider_Form_Settings_Abstract ) {
			$multi_ids = $form_settings_instance->get_multi_ids();
		}

		return $multi_ids;
	}

	/**
	 * Get the globally connected accounts of this integration.
	 * Returned as an array such as
	 * (
	 *  array(
	 *      'id' => {account ID},
	 *      'label' => {account name}
	 *  ),
	 *  array(
	 *      'id' => {account 2 ID},
	 *      'label' => {account 2 name}
	 *  )
	 * )
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function get_global_multi_ids() {
		$multi_ids      = array();
		$saved_settings = $this->get_settings_values();
		foreach ( $saved_settings as $key => $value ) {
			$multi_ids[] = array(
				'id'    => $key,
				// If 'name' exists, use it instead.
				'label' => isset( $value['name'] ) ? $value['name'] : $key,
			);
		}

		return $multi_ids;
	}

	/**
	 * Get existing global multi id or generate a new one
	 *
	 * @param array $submitted_data Submitted data.
	 * @return string
	 */
	public function get_global_multi_id( $submitted_data ) {
		$id = isset( $submitted_data['global_multi_id'] ) ? $submitted_data['global_multi_id'] : $this->generate_multi_id();

		return $id;
	}

	/**
	 * Get the current data for the integration.
	 * If not submitted, get it from the stored settings.
	 * Handles multi_id settings.
	 *
	 * @since 4.0.0
	 *
	 * @param array $current_data   Data that's currently stored.
	 * @param array $submitted_data Incoming data.
	 * @return array
	 */
	protected function get_current_data( $current_data, $submitted_data ) {
		$global_multi_id = isset( $submitted_data['global_multi_id'] ) ? $submitted_data['global_multi_id'] : false;
		$saved_settings  = $this->get_settings_values();

		foreach ( $current_data as $key => $current_field ) {

			if ( isset( $submitted_data[ $key ] ) ) {
				$current_data[ $key ] = $submitted_data[ $key ];

			} elseif ( isset( $saved_settings[ $key ] ) && ! $this->is_allow_multi_on_global() ) {
				$current_data[ $key ] = $saved_settings[ $key ];

			} elseif ( $global_multi_id && isset( $saved_settings[ $global_multi_id ][ $key ] ) ) {
				$current_data[ $key ] = $saved_settings[ $global_multi_id ][ $key ];
			}
		}

		return $current_data;
	}

	/**
	 * Get hidden fields that are common among the providers.
	 *
	 * @since 4.0.0
	 *
	 * @param array $submitted_data Submitted data.
	 * @return string
	 */
	protected function get_step_html_common_hidden_fields( $submitted_data ) {
		$options = array(
			array(
				'name'  => 'hustle_is_submit',
				'type'  => 'hidden',
				'value' => '1',
			),
		);

		if ( $this->is_allow_multi_on_form() ) {
			$options[] = array(
				'name'  => 'multi_id',
				'type'  => 'hidden',
				'value' => isset( $submitted_data['multi_id'] ) ? $submitted_data['multi_id'] : $this->generate_multi_id(),
			);
		}

		if ( $this->is_allow_multi_on_global() ) {
			$options[] = array(
				'name'  => 'global_multi_id',
				'type'  => 'hidden',
				'value' => isset( $submitted_data['global_multi_id'] ) ? $submitted_data['global_multi_id'] : $this->generate_multi_id(),
			);
		}
		$html = Hustle_Provider_Utils::get_html_for_options( $options );
		$html = apply_filters( 'hustle_providers_admin_add_common_hidden_fields', $html );
		return $html;
	}

	/**
	 * In version 3.0 provider details like API key and URL were stored at module level,
	 * now they are stored globally to avoid duplication.
	 *
	 * This method addresses this difference.
	 *
	 * @param Hustle_Module_Model $module     Current module.
	 * @param Object              $old_module Old module.
	 *
	 * @return bool
	 */
	public function migrate_30( $module, $old_module ) {
		$v3_provider = ! empty( $old_module->meta['content']['email_services'][ $this->get_slug() ] )
			? $old_module->meta['content']['email_services'][ $this->get_slug() ]
			: false;

		if ( empty( $v3_provider ) || $this->get_30_provider_mappings() === false ) {
			// Nothing to migrate.
			return false;
		}

		$v3_provider_active = '1' === $v3_provider['enabled'];

		// If the provider doesn't already exist globally, add it.
		$global_multi_id = $this->get_30_migrated_provider( $v3_provider );
		if ( empty( $global_multi_id ) ) {
			$global_multi_id = $this->generate_multi_id();
			$this->save_multi_settings_values(
				$global_multi_id,
				$this->map_30_provider( $v3_provider )
			);

			// Activate the addon.
			Hustle_Providers::get_instance()->activate_addon( $this->get_slug() );
		}

		// Link the provider to the module.
		if ( $v3_provider_active ) {
			$module_provider_link                             = $this->strip_30_global_provider_settings( $v3_provider );
			$module_provider_link['selected_global_multi_id'] = $global_multi_id;

			$module->set_provider_settings( $this->get_slug(), $module_provider_link );
		}

		return true;
	}

	/**
	 * Map the provider's field from the old settings to the new ones.
	 *
	 * @since 4.0.0
	 * @param array $v3_provider Old settings of the provider.
	 */
	private function map_30_provider( $v3_provider ) {
		$v4_provider = array();
		$mappings    = $this->get_30_provider_mappings();

		foreach ( $mappings as $v3_index => $v4_index ) {
			if ( isset( $v3_provider[ $v3_index ] ) ) {
				$v4_provider[ $v4_index ] = $v3_provider[ $v3_index ];
			}
		}

		return $v4_provider;
	}

	/**
	 * Gets the provider's map for the 3.x to 4.x migration.
	 *
	 * @since 4.0.0
	 * @return false|array
	 */
	protected function get_30_provider_mappings() {
		return false;
	}

	/**
	 * If a provider has already been migrated from 3.0 this method will return its id.
	 *
	 * @param array $v3_provider Old settings of the provider.
	 *
	 * @return bool|string Global multi ID
	 */
	private function get_30_migrated_provider( $v3_provider ) {
		$v40_providers      = $this->get_settings_values();
		$mapped_40_provider = $this->map_30_provider( $v3_provider );

		foreach ( $v40_providers as $global_multi_id => $v40_provider ) {
			if ( $v40_provider == $mapped_40_provider ) {
				return $global_multi_id;
			}
		}

		return false;
	}

	/**
	 * Strips unused old settings for the provider.
	 *
	 * @since 4.0.0
	 *
	 * @param array $v3_provider Old provider's settings.
	 * @return array
	 */
	private function strip_30_global_provider_settings( $v3_provider ) {
		$copy                     = array();
		$global_provider_settings = array_merge(
			array( 'enabled', 'optin_provider_name', 'desc' ),
			array_keys( $this->get_30_provider_mappings() )
		);

		foreach ( $v3_provider as $item => $value ) {
			if ( in_array( $item, $global_provider_settings, true ) ) {
				continue;
			}

			$copy[ $item ] = $value;
		}

		return $copy;
	}

	/**
	 * If a provider fails to connect,
	 * returns a generic message.
	 *
	 * @since 4.0.0
	 *
	 * @return string error message
	 */
	protected function provider_connection_falied() {
		/* translators: provider's title */
		$error_message = sprintf( __( "We couldn't connect to your %s account. Please resolve the errors below and try again.", 'hustle' ), $this->_title );
		return $error_message;
	}
}