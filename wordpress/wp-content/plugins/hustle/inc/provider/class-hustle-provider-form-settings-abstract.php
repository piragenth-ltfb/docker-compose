<?php
/**
 * Hustle_Provider_Form_Settings_Abstract class.
 *
 * @package Hustle
 * @since 3.5.0
 */

/**
 * Class Hustle_Provider_Form_Settings_Abstract
 * Any change(s) to this file is subject to:
 * - Properly Written DocBlock! (what is this, why is that, how to be like those, etc, as long as you want!)
 * - Properly Written Changelog!
 *
 * This class should be extended by your integration in order to display a settings section for it within Hustle.
 * For more information, more examples, and even sample integrations, visit this page at WPMUDev's site:
 *
 * @see https://wpmudev.com/docs/wpmu-dev-plugins/hustle-providers-api-doc/
 *
 * @since 3.0.5
 */
abstract class Hustle_Provider_Form_Settings_Abstract {

	/**
	 * Current module ID
	 *
	 * @since 4.0
	 * @var int
	 */
	protected $module_id;

	/**
	 * Last loaded provider lists.
	 *
	 * @since 4.0.0
	 * @var array
	 */
	protected $lists;

	/**
	 * Integration's instance.
	 * Instance of the integration to whom the form settings belongs to.
	 *
	 * @since 3.0.5
	 * @var Hustle_Provider_Abstract
	 */
	protected $provider;

	/**
	 * Form settings for addon
	 *
	 * @since 4.0.0
	 * @var array
	 */
	protected $addon_form_settings = array();

	/**
	 * Options that must be set in order to consider the integration as "connected" to the form.
	 *
	 * @since 4.2.0
	 * @var array
	 */
	protected $form_completion_options = array();

	/**
	 * Instance of Hustle_Layout_Helper
	 *
	 * @since 4.4.0
	 * @var Hustle_Layout_Helper
	 */
	private $renderer;

	/**
	 * Hustle_Provider_Form_Settings_Abstract
	 *
	 * @since 3.0.5
	 * @since 4.0 $module_id parameter added
	 *
	 * @param Hustle_Provider_Abstract $provider Instance of the provider.
	 * @param string                   $module_id Module ID.
	 */
	public function __construct( Hustle_Provider_Abstract $provider, $module_id ) {

		$this->module_id = $module_id;
		$this->provider  = $provider;

		$this->addon_form_settings = $this->get_form_settings_values( false );
	}

	/**
	 * Gets the instance of the renderer class.
	 *
	 * @since 4.4.0
	 *
	 * @return Hustle_Layout_Helper
	 */
	final protected function get_renderer() {
		if ( empty( $this->renderer ) ) {
			$this->renderer = new Hustle_Layout_Helper();
		}
		return $this->renderer;
	}

	/**
	 * Get the settings value for this module.
	 * Hooked with
	 *
	 * @see Hustle_Provider_Form_Settings_Abstract::before_get_form_settings_values()
	 *
	 * @since 4.0.0
	 *
	 * @param bool $get_cached Whether to get the cached value.
	 * @return array
	 */
	final public function get_form_settings_values( $get_cached = true ) {

		$module_id     = $this->module_id;
		$provider_slug = $this->provider->get_slug();
		$module        = new Hustle_Module_Model( $module_id );
		if ( is_wp_error( $module ) ) {
			return array();
		}
		$values = $module->get_provider_settings( $provider_slug, $get_cached );
		/**
		 * Filter the retrived form settings data from db.
		 *
		 * @since 4.0
		 *
		 * @param mixed $values
		 * @param int   $module_id current module_id
		 */
		$values = apply_filters( 'hustle_provider_' . $provider_slug . '_get_form_settings_values', $values, $module_id );
		return $values;
	}

	/**
	 * Override this function if provider does something with the form settings values.
	 * Called when rendering the integrations tab on wizard.
	 *
	 * @example transform, load from other storage ?
	 *
	 * @since 4.0
	 *
	 * @param array $values Settings to be retrieved.
	 * @return mixed
	 */
	public function before_get_form_settings_values( $values ) {
		return $values;
	}

	/**
	 * Gets and stores lists for the provider's global instances.
	 *
	 * @since 4.0.2
	 *
	 * @param boolean $refresh Whether to refresh the stored lists.
	 * @param int     $module_id Module ID.
	 * @param string  $key lists|forms.
	 * @return array
	 */
	final public function get_global_multi_lists( $refresh = false, $module_id = false, $key = 'lists' ) {
		if ( $module_id ) {
			$this->module_id = $module_id;
		}

		$key = 'forms' === $key ? 'forms' : 'lists';

		$provider = $this->provider;
		$settings = $this->get_form_settings_values( false );

		$global_multi_id = isset( $settings['selected_global_multi_id'] ) ? $settings['selected_global_multi_id'] : '';

		// try to get cached lists.
		$lists = $provider->get_setting( $key, null, $global_multi_id );

		if ( is_null( $lists ) || $refresh ) {

			try {
				if ( 'lists' !== $key && isset( $this->list_type ) ) {
					$this->list_type = $key;
				}
				$lists = $this->refresh_global_multi_lists( $provider, $global_multi_id );
				// cache lists.
				$settings_to_save         = $provider->get_multi_settings_values( $global_multi_id );
				$settings_to_save[ $key ] = $lists;
				$provider->save_multi_settings_values( $global_multi_id, $settings_to_save );

			} catch ( Exception $e ) {
				Hustle_Provider_Utils::maybe_log( $this->provider->get_title(), $e->getMessage() );
				$lists = array();
			}
		}

		if ( empty( $lists ) ) {
			$lists = array(
				'0' => __( 'No options to select from.', 'hustle' ),
			);
		}

		return $lists;
	}

	/**
	 * Save form settings value
	 * Hooked with
	 *
	 * @see Hustle_Provider_Form_Settings_Abstract::before_save_form_settings_values()
	 * @since 4.0
	 *
	 * @param mixed $values Values to be stored.
	 * @param bool  $force If it's true - set $values, otherwise - update it.
	 */
	final public function save_form_settings_values( $values, $force = false ) {
		$module_id = $this->module_id;
		$module    = new Hustle_Module_Model( $module_id );
		if ( is_wp_error( $module ) ) {
			return;
		}
		$provider_slug = $this->provider->get_slug();

		if ( ! $force ) {
			$data_to_remove = apply_filters( 'hustle_save_form_settings_data_to_remove', array( 'hustle_is_submit', 'global_multi_id', 'multi_id' ), $values );

			$old_data = $module->get_provider_settings( $provider_slug );
			$values   = array_merge( $old_data, $values );

			foreach ( $data_to_remove as $key ) {
				if ( isset( $values[ $key ] ) ) {
					unset( $values[ $key ] );
				}
			}
		}

		/**
		 * Filter the form settings data to be save to db.
		 *
		 * @since 4.0
		 *
		 * @param mixed $values current form settings values
		 * @param int $module_id current module_id
		 */
		$values = apply_filters( 'hustle_provider_' . $provider_slug . '_save_form_settings_values', $values, $module_id );

		return $module->set_provider_settings( $provider_slug, $values );
	}

	/**
	 * Saves the form settings with the format for multi-form providers.
	 *
	 * @see Hustle_Zapier_Form_Settings
	 *
	 * @since 4.0.0
	 *
	 * @param array $values Values to be saved.
	 */
	final public function save_form_multi_id_settings_values( $values ) {

		if ( isset( $values['multi_id'] ) ) {
			$multi_id = $values['multi_id'];
		} else {
			$multi_id = $this->generate_multi_id();
		}

		$data_to_remove = apply_filters( 'hustle_save_form_settings_data_to_remove', array( 'hustle_is_submit', 'global_multi_id', 'multi_id' ), $values );
		foreach ( $data_to_remove as $key ) {
			if ( isset( $values[ $key ] ) ) {
				unset( $values[ $key ] );
			}
		}

		$data_to_save = array_merge(
			$this->addon_form_settings,
			array(
				$multi_id => $values,
			)
		);
		$this->save_form_settings_values( $data_to_save );
	}

	/**
	 * Override this function if your provider does something with the form settings values.
	 * Called when rendering the integrations tab in wizard.
	 *
	 * @example transform, load from other storage ?
	 *
	 * @since 4.0
	 *
	 * @param array $values Values to be saved.
	 * @return mixed
	 */
	public function before_save_form_settings_values( $values ) {
		return $values;
	}

	/**
	 * Gets the array that define the contents of the settings wizard.
	 * Override this function to set wizardable settings.
	 * Default is an empty array which is indicating that Provider doesn't have settings.
	 *
	 * -Optional. Required if your integration has settings.
	 *
	 * It's a multi-array with numerical keys, starting with `0`.
	 * Every step you'd like your settings wizard to have should be an array within the $steps array.
	 * Every step's array must have these key => value pairs:
	 *
	 * - 'callback' :     array with the function to be called by 'call_user_func'. @example array( $this, 'sample_first_step_callback' ),
	 *
	 *                    @see Hustle_Provider_Form_Settings_Abstract::sample_first_step_callback()
	 *
	 * - 'is_completed' : array with the function to be called by 'call_user_func'. @example array( $this, 'sample_is_first_step_completed' ),
	 *                    @see Hustle_Provider_Form_Settings_Abstract::sample_is_first_step_completed()
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function form_settings_wizards() {
		// What this function returns should look like this.
		$steps = array(
			// 1st Step / step '0'.
			array(
				/**
				 * The value within 'callback' will be passed as the first argument of 'call_user_func'.
				 * Passing '$this' as a reference such as "array( $this, 'sample_first_step_callback' )" is not required but it's encouraged.
				 * Passing '$this' class instance is helpful for calling private functions or variables inside your class.
				 * You could make this value to be 'some_function_name' as long as it's globally callable, which will be checked by 'is_callable'.
				 *
				 * This callback should accept 1 argument and return an array.
				 *
				 * @see Hustle_Provider_Form_Settings_Abstract::sample_first_step_callback()
				 */
				'callback'     => array( $this, 'sample_first_step_callback' ),
				/**
				 * When moving forward on the wizard's steps (when going from step 1 to step 2, for exmaple),
				 * Hustle will call 'is_completed' from the previous step before calling the 'callback' function.
				 * If this function returns 'false', the wizard won't move forward to the next step.
				 * Just like 'callback', the value of this element will be passed as the first argument of `call_user_func`.
				 *
				 * This callback should accept 1 argument and return a boolean.
				 *
				 * @see Hustle_Provider_Form_Settings_Abstract::sample_is_first_step_completed()
				 */
				'is_completed' => array( $this, 'sample_is_first_step_completed' ),
			),
			/**
			 * 2nd step / step '1'
			 * array (
			 *  'callback'     => array( $this, 'sample_second_step_callback' ),
			 *  'is_completed' => array( $this, 'sample_is_second_step_completed' ),
			 * ),
			*/
		);

		return array();
	}

	/**
	 * Handles the current wizard step.
	 * This function retrieves the form to be shown and handles the submitted data.
	 *
	 * Sample of what this function should return:
	 *
	 * @example
	 * $returned_data = [
	 *  'html' => string. Contains the HTML of the form settings to be displayed.
	 *  'has_errors' => boolean. True when it has errors, such as an invalid input. The wizard won't move forward if there are errors.
	 *  'buttons' =>
	 *      'submit' => [
	 *          markup => '<a>Submit</a>'
	 *      ],
	 *      'cancel' => [
	 *          markup: '<a>Cancel</a>'
	 *      ]
	 * ]
	 *  'is_close' => boolean. True if wizard should be instead of showing this step.
	 * ]
	 *
	 * @since   3.0.5
	 * @param array $submitted_data Array of the submitted data POST-ed by the user or by Hustle. Already sanitized by @see Opt_In_Utils::validate_and_sanitize_fields().
	 * @return array
	 */
	private function sample_first_step_callback( $submitted_data ) {
		return array(
			'html'       => '<p>Hello im from first step settings</p>',
			'has_errors' => false,
		);

	}

	/**
	 * Checks if the previous step was completed.
	 * When Hustle requests the wizard, it will check if the previous step 'is_completed' before proceeding to the next one.
	 *
	 * @since   3.0.5
	 * @param array $submitted_data Data submitted by the user and handled by the step's callback function.
	 * @return bool
	 */
	/**
	 * Example
	 * private function sample_is_first_step_completed( $submitted_data ) {
	 * Do some validation here and return 'true' if everything is okay to go to the next step and save the data.
	 * return true;
	 * }
	 */

	/**
	 * Disconnect this addon from this form.
	 * Override if needed
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data Submitted data.
	 */
	public function disconnect_form( $submitted_data ) {
		$this->save_form_settings_values( array(), true );
	}

	/**
	 * Gets the selected list.
	 *
	 * @since unkwnown
	 *
	 * @param array  $submitted_data Submitted data.
	 * @param string $key Key under which the list is stored.
	 * @return string
	 */
	protected function get_selected_list( $submitted_data, $key = 'list_id' ) {
		$lists = $this->lists;

		if ( isset( $submitted_data[ $key ] ) && array_key_exists( $submitted_data[ $key ], $lists ) ) {
			$selected_list = $submitted_data[ $key ];
		} else {
			$selected_list = Opt_In_Utils::array_key_first( $lists );
		}

		return $selected_list;
	}

	/**
	 * Retrieve the ids of the multiple instances of the provider's form settings.
	 * Default is the array keys as 'id' and 'label'.
	 * Override if you want a different setup.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_multi_ids() {
		$multi_ids = array();
		foreach ( $this->get_form_settings_values() as $key => $value ) {
			$multi_ids[] = array(
				'id'    => $key,
				// If 'name' exists, use it instead.
				'label' => isset( $value['name'] ) ? $value['name'] : $key,
			);
		}

		return $multi_ids;
	}

	/**
	 * Get the first found active instance of an integration to a module.
	 *
	 * @since 4.0
	 *
	 * @return false|Hustle_Provider_Form_Settings_Abstract
	 */
	public function find_one_active_connection() {
		$addon_form_settings = $this->get_form_settings_values();

		foreach ( $addon_form_settings as $multi_id => $addon_form_setting ) {
			if ( true === $this->is_multi_form_settings_complete( $multi_id, $addon_form_setting ) ) {
				return $addon_form_setting;
			}
		}

		return false;

	}

	/**
	 * Check wether a multi id instance is complete.
	 * To be overridden.
	 *
	 * @since 4.0.0
	 *
	 * @param string $multi_id ID of the provider global instance.
	 * @param array  $settings Settings to check.
	 * @return boolean
	 */
	public function is_multi_form_settings_complete( $multi_id, $settings ) {
		return false;
	}

	/**
	 * Override this function to generate your multiple id for form settings.
	 * Default is uniqid.
	 *
	 * @since 4.0
	 * @return string
	 */
	public function generate_multi_id() {
		return uniqid( '', true );
	}

	/**
	 * Get current data
	 *
	 * @since 4.0.0
	 *
	 * @param array $current_data Currently stored data.
	 * @param array $submitted_data Submitted data.
	 * @return array
	 */
	protected function get_current_data( $current_data, $submitted_data ) {
		$is_submit = ! empty( $submitted_data['hustle_is_submit'] );

		$saved_data = $this->get_form_settings_values( false );
		$multi_id   = ! isset( $submitted_data['multi_id'] ) ? false : $submitted_data['multi_id'];

		foreach ( $current_data as $key => $current_field ) {

			if ( isset( $submitted_data[ $key ] ) ) {
				$current_data[ $key ] = $submitted_data[ $key ];
			} elseif ( isset( $saved_data[ $key ] ) && ! $is_submit ) {
				$current_data[ $key ] = $saved_data[ $key ];
			} elseif ( $multi_id && isset( $saved_data[ $multi_id ][ $key ] ) && ! $is_submit ) {
				$current_data[ $key ] = $saved_data[ $multi_id ][ $key ];
			}
		}

		return $current_data;
	}

	/**
	 * Get the step to select an account.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_form_settings_global_multi_id_step() {

		$global_multi_id_step = array(
			array(
				'callback'     => array( $this, 'get_multi_global_select_step' ),
				'is_completed' => array( $this, 'is_multi_global_select_step_completed' ),
			),
		);

		return $global_multi_id_step;
	}

	/**
	 * Wizard step used when a provider has multi_global_id and has more than 1 account connected.
	 *
	 * @since 4.0.0
	 *
	 * @param array $submitted_data Submitted data.
	 * @return array
	 */
	public function get_multi_global_select_step( $submitted_data ) {

		$is_submit  = isset( $submitted_data['hustle_is_submit'] );
		$has_errors = false;

		$global_accounts = $this->provider->get_global_multi_ids();
		$select_options  = array();

		foreach ( $global_accounts as $account ) {
			$label = ! empty( $account['label'] ) ? $account['label'] : $account['id'];

			$select_options[ $account['id'] ] = $this->provider->get_title() . ' - ' . $label;
		}

		$selected = Opt_In_Utils::array_key_first( $select_options );

		$options    = array(
			'group_id_setup' => array(
				'type'     => 'wrapper',
				'style'    => 'margin-bottom: 0;',
				'elements' => array(
					array(
						'type'  => 'label',
						'for'   => 'select-email-list',
						'value' => __( 'Choose Account', 'hustle' ),
					),
					array(
						'type'     => 'select',
						'name'     => 'selected_global_multi_id',
						'options'  => $select_options,
						'selected' => $selected,
						'id'       => 'select-email-list',
					),
				),
			),
		);
		$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Connect Account', 'hustle' ),
			__( 'Select the integration account you want to connect your module to.', 'hustle' )
		);
		$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

		if ( $is_submit ) {
			$is_completed = $this->is_multi_global_select_step_completed( $submitted_data );

			if ( $is_completed ) {
				if ( ! $this->provider->is_allow_multi_on_form() ) {
					$this->save_form_settings_values( $submitted_data );
				} else {
					$this->save_form_multi_id_settings_values( $submitted_data );
				}
			} else {
				$error_message = esc_html__( 'Please select an account.', 'hustle' );
				$step_html    .= '<span class="sui-error-message">' . $error_message . '</span>';
				$has_errors    = true;
			}
		}

		$response = array(
			'html'       => $step_html,
			'buttons'    => array(
				'save' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Continue', 'hustle' ),
						'sui-button-right',
						'next',
						true
					),
				),
			),
			'has_errors' => $has_errors,
		);

		return $response;
	}

	/**
	 * Verify whether a global account was already selected.
	 *
	 * @since 4.0.0
	 *
	 * @param array $submitted_data Submitted data.
	 * @return boolean
	 */
	public function is_multi_global_select_step_completed( $submitted_data = array() ) {

		$global_multi_id = false;
		if ( isset( $submitted_data['selected_global_multi_id'] ) && ! empty( $submitted_data['selected_global_multi_id'] ) ) {
			$global_multi_id = $submitted_data['selected_global_multi_id'];

		} else {
			$saved_form_data = $this->get_form_settings_values( false );
			if ( ! empty( $saved_form_data['selected_global_multi_id'] ) ) {
				$global_multi_id = $saved_form_data['selected_global_multi_id'];
			}
		}

		$saved_global_data = $this->provider->get_settings_values();
		if ( $global_multi_id && isset( $saved_global_data[ $global_multi_id ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the options that should be completed for the form.
	 *
	 * @since 4.2.0
	 *
	 * @param array $saved_form_settings The currently stored provider's settings for the module.
	 * @return array
	 */
	public function get_form_completion_options( $saved_form_settings ) {
		return $this->form_completion_options;
	}

	/**
	 * Returns the module's fields to be used in the mapping step.
	 * This array is intended to be passed as the select's options argument.
	 *
	 * @since 4.4.0
	 *
	 * @param bool        $required Whether to retrieve required fields only.
	 * @param bool|string $type Field type to filter fields. False if retrieving all fields.
	 * @return array
	 */
	protected function get_form_fields_for_map_step( $required = false, $type = false ) {
		$module = new Hustle_Module_Model( $this->module_id );

		$module_fields = $module->get_form_fields();

		/**
		 * Filters the fields used for mapping the data sent to the provider.
		 *
		 * @since 4.4.0
		 *
		 * @param int $module_id Current module ID.
		 * @param string $provider_slug Slug of the current provider.
		 */
		$excluded_form_fields = apply_filters( 'hustle_fields_excluded_from_mapping', array( 'recaptcha', 'submit' ), $this->module_id, $this->provider->get_slug() );

		$map_fields = array();
		foreach ( $module_fields as $field ) {
			if ( ! in_array( $field['type'], $excluded_form_fields, true ) ) {
				// When retrieving required fields only.
				if ( $required && 'false' === $field['required'] ) {
					continue;
				}

				// When retrieving fields of a type only.
				if ( $type && $type !== $field['type'] ) {
					continue;
				}
				$map_fields[ $field['name'] ] = $field['label'] . ' | ' . $field['name'];
			}
		}

		return $map_fields;
	}

	/**
	 * Gets the main email field for the mapping step.
	 *
	 * @since 4.4.0
	 *
	 * @return array
	 */
	protected function get_main_email_field_for_map_step() {
		$module        = new Hustle_Module_Model( $this->module_id );
		$module_fields = $module->get_form_fields();

		return array(
			'email' => $module_fields['email']['label'] . ' | ' . $module_fields['email']['name'],
		);
	}
}