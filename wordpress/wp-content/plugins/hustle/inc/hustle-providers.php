<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Hustle Providers
 */
class Hustle_Providers {

	/**
	 * wp_option name of the activated providers
	 *
	 * @since 4.0
	 * @var string
	 */
	private static $_active_addons_option = 'hustle_activated_providers';

	/**
	 * Instance of Hustle Providers.
	 *
	 * @since 3.0.5
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Returns the existing instance of Hustle_Providers, or creates a new one if none exists.
	 *
	 * @since 3.0.5
	 * @return Hustle_Providers
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Container for all the instantiated providers.
	 *
	 * @since 3.0.5
	 * @var Hustle_Provider_Container
	 */
	private $providers;

	/**
	 * Array with the slugs of the activated providers
	 *
	 * @since 4.0
	 * @var array
	 */
	private $activated_addons = array();

	/**
	 * Default error messages
	 * will be used when an error happens and the loader can't get the provider's error message
	 *
	 * @since 4.0
	 * @var array
	 */
	private $default_addon_error_messages = array();

	/**
	 * Last error message on loader
	 *
	 * @since 4.0
	 * @var string
	 */
	private $last_error_message = '';

	public function __construct() {
		$this->providers = new Hustle_Provider_Container();

		/**
		 * Initiate activated addons
		 */
		$active_addons = get_option( self::$_active_addons_option, false );

		if ( empty( $active_addons ) ) {
			$active_addons = array();
		}

		// Local list is always activated
		$active_addons = array_unique( array_merge( array( 'local_list' ), $active_addons ) );

		$this->activated_addons = $active_addons;

		/**
		 * Initiate standard default error messages
		 */
		$this->default_addon_error_messages = array(
			'activate'             => __( 'Failed to activate addon', 'hustle' ),
			'deactivate'           => __( 'Failed to deactivate addon', 'hustle' ),
			'update_settings'      => __( 'Failed to update settings', 'hustle' ),
			'update_form_settings' => __( 'Failed to update form settings', 'hustle' ),
		);

		// Only enable wp_ajax hooks
		if ( wp_doing_ajax() ) {
			Hustle_Provider_Admin_Ajax::get_instance();
		}
	}

	/**
	 * Registers a new Provider.
	 * Created just to avoid third parties having to use Hustle_Providers::get_instance().
	 *
	 * @since 3.0.5
	 * @param Hustle_Provider_Abstract|string $class_name instance of Provider or its classname.
	 * @return bool True if the provider was successfully instantiated and registered. False otherwise.
	 */
	public static function register_provider( $class_name ) {
		return self::get_instance()->register( $class_name );
	}

	/**
	 * Registers a new Provider.
	 *
	 * @since 3.0.5
	 * @param Hustle_Provider_Abstract|string $class_name instance of Provider or its classname.
	 * @return bool True if the provider was successfully instantiated and registered. False otherwise.
	 */
	public function register( $class_name ) {
		try {
			/**
			 * Fires when a provider is registered.
			 *
			 * This action is executed before the whole process of registering a provider.
			 * Validation and requirement check has not been done at this point,
			 * so it's possible that a registered class ends up not being instantiated nor registered
			 * at the end of the process when the validation of the requirements fails.
			 *
			 * @since 3.0.5
			 * @param Hustle_Provider_Abstract|string $class_name instance of Provider or its class name
			 * @return bool True if the provider was registered. False otherwise.
			 */
			do_action( 'hustle_before_provider_registered', $class_name );

			if ( $class_name instanceof Hustle_Provider_Abstract ) {
				$provider_class = $class_name;
			} else {
				$provider_class = $this->validate_provider_class( $class_name );
				if ( ! $provider_class ) {
					return false;
				}
			}
			$registered_providers = $this->providers;

			/**
			 * Filter provider instance.
			 *
			 * It's possible to replace / modify the provider instance when it's registered.
			 * Keep in mind that the instance returned by this filter will be used throughout the plugin.
			 * Return must be instance of @see Hustle_Provider_Abstract.
			 * It will be then validated by @see Hustle_Providers::validate_provider_instance().
			 *
			 * @since 3.0.5
			 * @param Hustle_Provider_Abstract $provider_class Current Provider class instance
			 * @param array $registered_providers Current registered providers
			 */

			$provider_class = apply_filters( 'hustle_provider_instance', $provider_class, $registered_providers );

			$provider_class = $this->validate_provider_instance( $provider_class );

			$this->providers[ $provider_class->get_slug() ] = $provider_class;

			/**
			 * Fires after the provider is successfully registered.
			 *
			 * If the provider is not registered because any reason,
			 * this action will not be executed.
			 *
			 * @since 3.0.5
			 * @param Hustle_Provider_Abstract $provider_class Current provider that's successfully registered
			 */
			do_action( 'hustle_after_provider_registered', $provider_class );

			return true;
		} catch ( Exception $e ) {
			Opt_In_Utils::maybe_log( __METHOD__, $class_name, $e->getMessage() );
			return false;
		}

	}

	/**
	 * Validates provider by its class name.
	 * Validation will fail if:
	 * -The class name passed on $class_name does not exist.
	 * -The provider doesn't have a callable 'get_instance' method. It's properly defined by default on @see Hustle_Provider_Abstract.
	 * -The provider doesn't have a callable 'check_is_compatible' method. It's properly defined by default on @see Hustle_Provider_Abstract.
	 * -The provider's 'check_is_compatible' returns false.
	 *
	 * @since 3.0.5
	 * @param string $class_name
	 * @return Hustle_Provider_Abstract
	 * @throws Exception
	 */
	private function validate_provider_class( $class_name ) {
		if ( ! class_exists( $class_name ) ) {
			throw new Exception( 'Provider with ' . $class_name . ' does not exist' );
		}

		if ( ! is_callable( array( $class_name, 'get_instance' ) ) ) {
			throw new Exception( 'Provider with ' . $class_name . ' does not have get_instance method' );
		}

		if ( ! is_callable( array( $class_name, 'check_is_compatible' ) ) ) {
			throw new Exception( 'Provider with ' . $class_name . ' does not have check_is_compatible method' );
		}

		if ( ! call_user_func( array( $class_name, 'check_is_compatible' ), $class_name ) ) {
			return false;
		}

		$provider_class = call_user_func( array( $class_name, 'get_instance' ), $class_name );

		return $provider_class;

	}

	/**
	 * Validates the provider instance.
	 * Validation will fail if the provider instance:
	 * -Is not an instance of @see Hustle_Provider_Abstract.
	 * -Doesn't have a _slug property.
	 * -Doesn't have a _title property.
	 * -Doesn't have a _version property.
	 * -Doesn't have a _class property.
	 * -Has the same slug of an existing provider.
	 *
	 * @since 3.0.5
	 * @param Hustle_Provider_Abstract $instance
	 * @return Hustle_Provider_Abstract
	 * @throws Exception
	 */
	private function validate_provider_instance( Hustle_Provider_Abstract $instance ) {
		/** @var Hustle_Provider_Abstract $provider_class */
		$provider_class = $instance;
		$class_name     = get_class( $instance );

		if ( ! $provider_class instanceof Hustle_Provider_Abstract ) {
			throw new Exception( 'The provider ' . $class_name . ' is not instanceof Hustle_Provider_Abstract' );
		}
		$slug    = $provider_class->get_slug();
		$title   = $provider_class->get_title();
		$version = $provider_class->get_version();
		$class   = $provider_class->get_class();

		if ( empty( $slug ) ) {
			throw new Exception( 'The provider ' . $class_name . ' does not have the required _slug property.' );
		}
		if ( empty( $title ) ) {
			throw new Exception( 'The provider ' . $class_name . ' does not have the required _title property.' );
		}
		if ( empty( $class ) ) {
			throw new Exception( 'The provider ' . $class_name . ' does not the required _class property.' );
		}

		// FIFO
		if ( isset( $this->providers[ $slug ] ) ) {
			throw new Exception( 'The provider with the slug ' . $slug . ' already exists.' );
		}
		if ( empty( $version ) ) {
			throw new Exception( 'Provider with the slug ' . $slug . ' does not have a valid _version property.' );
		}

		// check if the version changed if active
		if ( $this->addon_is_active( $slug ) ) {
			try {
				// silent
				if ( $provider_class->is_version_changed() ) {
					$provider_class->version_changed( $provider_class->get_installed_version(), $provider_class->get_installed_version() );
				}
			} catch ( Exception $e ) {
				Hustle_Provider_Utils( $provider_class->get_slug(), 'failed to trigger version_changed', $e->getMessage() );
			}
		}

		return $provider_class;
	}

	/**
	 * Gets an instace of a provider by its slug.
	 *
	 * @param string $slug Slug of the provider to be retrieved
	 * @return Hustle_Provider_Abstract|mixed|null
	 */
	public function get_provider( $slug ) {
		if ( isset( $this->providers[ $slug ] ) ) {
			return $this->providers[ $slug ];
		}
	}

	/**
	 * Activate provider
	 * This function will call the 'activate' method of the provider if available.
	 *
	 * @since 4.0
	 *
	 * @param string $slug
	 * @return bool
	 */
	public function activate_addon( $slug ) {
		$addon = $this->get_provider( $slug );

		if ( is_null( $addon ) ) {
			$this->last_error_message = __( 'Provider not found', 'hustle' );
			return false;
		}

		if ( $this->addon_is_active( $slug ) ) {
			$this->last_error_message = __( 'The provider is already active', 'hustle' );
			return false;
		}

		if ( ! $addon->is_activable() ) {
			$this->last_error_message = __( 'The provider is not activable', 'hustle' );
			return false;
		}

		$activated = $addon->activate();
		if ( ! $activated ) {
			$error_message = $addon->get_activation_error_message();
			if ( empty( $error_message ) ) {
				$error_message = $this->default_addon_error_messages['activate'];
			}
			$this->last_error_message = $error_message;

			return false;
		}

		$this->add_activated_addons( $slug );

		return true;
	}

	public function get_last_error_message() {
		return $this->last_error_message;
	}

	public function get_default_messages() {
		return $this->default_addon_error_messages;
	}

	/**
	 * Returns the container of all registered providers.
	 * Keep in mind that a provider that is successfully registered and listed here
	 * might not be included on the application if its 'check_is_activable' method returns false.
	 *
	 * @return Hustle_Provider_Container
	 */
	public function get_providers() {
		return $this->providers;
	}

	/**
	 * Check if the provider is active
	 *
	 * @since 4.0
	 *
	 * @param string $slug
	 * @return bool
	 */
	public function addon_is_active( $slug ) {
		if ( in_array( $slug, $this->activated_addons, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Disconnect the addon.
	 *
	 * @since 3.0.5
	 *
	 * @param string $slug provider slus
	 * @param array  $submitted_data
	 * @return bool
	 */
	public function deactivate_addon( $slug, $submitted_data = array() ) {
		$addon = $this->get_provider( $slug );
		if ( is_null( $addon ) ) {
			$this->last_error_message = __( 'Provider not found', 'hustle' );
			return false;
		}

		if ( ! $this->addon_is_active( $slug ) ) {
			$this->last_error_message = __( 'Provider is not activated before', 'hustle' );
			return false;
		}

		$deactivated = $addon->deactivate( $submitted_data );
		if ( ! $deactivated ) {
			$error_message = $addon->get_deactivation_error_message();
			if ( empty( $error_message ) ) {
				$error_message = $this->default_addon_error_messages['deactivate'];
			}
			$this->last_error_message = $error_message;
			return false;
		}

		// If the provider allows having multiple instances globally, remove that instance only.
		if ( $addon->is_allow_multi_on_global() ) {
			if ( ! empty( $submitted_data['global_multi_id'] ) ) {
				$settings_values = $addon->get_settings_values();
				unset( $settings_values[ $submitted_data['global_multi_id'] ] );

				if ( ! empty( $settings_values ) ) {
					// Simply remove this instance from the global ones if there are other instances connected.
					$addon->save_settings_values( $settings_values );
				} else {
					// Remove the global provider's settings if there aren't more global instances of this one.
					$this->force_remove_activated_addons( $slug );
				}
			}
		} else {
			// Do this only if global_multi_id is disabled.
			$this->force_remove_activated_addons( $slug );
		}

		// Remove the provider from the modules.
		$this->disconnect_provider_instance_from_modules( $addon, $submitted_data );

		return true;
	}

	/**
	 * Disconnect the given provider from all the modules.
	 *
	 * @since 4.0.1
	 *
	 * @param Hustle_Provider_Abstract $provider
	 * @param array                    $submitted_data
	 */
	private function disconnect_provider_instance_from_modules( Hustle_Provider_Abstract $provider, $submitted_data ) {

		$is_multi_on_global = $provider->is_allow_multi_on_global();
		$is_multi_on_form   = $provider->is_allow_multi_on_form();

		$global_multi_id = ( $is_multi_on_global && ! $is_multi_on_form && ! empty( $submitted_data['global_multi_id'] ) ) ? $submitted_data['global_multi_id'] : false;

		$modules = Hustle_Provider_Utils::get_modules_by_active_provider( $provider->get_slug(), $global_multi_id );

		foreach ( $modules as $module ) {

			$form_settings = $provider->get_provider_form_settings( $module->module_id );

			if ( $form_settings instanceof Hustle_Provider_Form_Settings_Abstract ) {
				$form_settings->disconnect_form( $submitted_data );
			}
		}
	}

	/**
	 * Add activated provider to wp_options
	 *
	 * @since 4.0
	 *
	 * @param $slug
	 */
	private function add_activated_addons( $slug ) {
		$addon                    = $this->get_provider( $slug );
		$this->activated_addons[] = $slug;
		update_option( self::$_active_addons_option, $this->activated_addons );
		// take from get_version() since it's a new provider
		update_option( $addon->get_version_options_name(), $addon->get_version() );
	}

	/**
	 * Force Remove activated addons
	 * remove activated addons from wp options, without calling deactivate on addon function
	 *
	 * @since 4.0
	 * @param $slug
	 */
	public function force_remove_activated_addons( $slug ) {
		$addon = $this->get_provider( $slug );

		$index = array_search( $slug, $this->activated_addons, true );
		if ( false !== $index ) {
			unset( $this->activated_addons[ $index ] );
			// reset keys
			$this->activated_addons = array_values( $this->activated_addons );
			update_option( self::$_active_addons_option, $this->activated_addons );
		}

		if ( $addon ) {
			$version_options_name  = $addon->get_version_options_name();
			$settions_options_name = $addon->get_settings_options_name();
		} else {
			// probably just want to remove the options
			$version_options_name  = 'hustle_provider_' . $slug . '_version';
			$settions_options_name = 'hustle_provider_' . $slug . '_settings';
		}

		// delete version
		delete_option( $version_options_name );
		// delete general settings
		delete_option( $settions_options_name );

		$addon->remove_wp_options();
	}


	/**
	 * Get an array with the slug of the activated providers
	 *
	 * @since 4.0
	 * @return array
	 */
	public function get_activated_addons() {
		return $this->activated_addons;
	}

}