<?php

/**
 * Class Hustle_Module_Model
 *
 * @property Hustle_Module_Decorator $decorated
 */

class Hustle_Module_Model extends Hustle_Model {

	public static function instance() {
		_deprecated_function( __METHOD__, '4.3.0', 'new Hustle_Module_Model' );
		return new self();
	}

	/**
	 * Get the sub-types for embedded modules.
	 *
	 * @since the beggining of time
	 * @since 4.0 "after_content" changed to "inline"
	 *
	 * @return array
	 */
	public static function get_embedded_types( $with_titles = false ) {
		if ( ! $with_titles ) {
			return array( 'inline', 'widget', 'shortcode' );
		} else {
			return array(
				'inline'    => __( 'Inline', 'hustle' ),
				'widget'    => __( 'Widget', 'hustle' ),
				'shortcode' => __( 'Shortcode', 'hustle' ),
			);
		}
	}

	/**
	 * Get the sub-types for this module.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_sub_types( $with_titles = false ) {
		if ( self::EMBEDDED_MODULE === $this->module_type ) {
			return self::get_embedded_types( $with_titles );
		}

		return array();
	}

	/**
	 * Gets the instance of the decorator class for this module type.
	 *
	 * @since 4.3.0
	 *
	 * @return Hustle_Decorator_Non_Sshare
	 */
	public function get_decorator_instance() {
		return new Hustle_Decorator_Non_Sshare( $this );
	}

	/**
	 * Content Model based upon module type.
	 *
	 * @return Class
	 */
	public function get_content() {
		$data = $this->get_settings_meta( self::KEY_CONTENT, '{}', true );

		return new Hustle_Meta_Base_Content( $data, $this );
	}

	/**
	 * Get the content of the data stored under 'emails' meta.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Popup_Emails
	 */
	public function get_emails() {
		$data = $this->get_settings_meta( self::KEY_EMAILS, '{}', true );

		return new Hustle_Meta_Base_Emails( $data, $this );
	}

	/**
	 * Get the module's settings for the given provider.
	 *
	 * @since 4.0
	 *
	 * @param string $slug
	 * @param bool   $get_cached
	 * @return array
	 */
	public function get_provider_settings( $slug, $get_cached = true ) {
		return $this->get_settings_meta( $slug . self::KEY_PROVIDER, '{}', true, $get_cached );
	}

	/**
	 * Save the module's settings for the given provider.
	 *
	 * @since 4.0
	 *
	 * @param string $slug
	 * @param array  $data
	 * @return array
	 */
	public function set_provider_settings( $slug, $data ) {
		return $this->update_meta( $slug . self::KEY_PROVIDER, $data );
	}

	/**
	 * Get the all-integrations module's settings.
	 * This is not each provider's settings. Instead, these are per module settings
	 * that are applied to all the active providers of this module.
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public function get_integrations_settings() {
		$stored = $this->get_settings_meta( self::KEY_INTEGRATIONS_SETTINGS, '{}', true );
		return new Hustle_Meta_Base_Integrations( $stored, $this );
	}

	public function get_design() {
		$stored = $this->get_settings_meta( self::KEY_DESIGN, '{}', true );
		return new Hustle_Meta_Base_Design( $stored, $this );
	}

	/**
	 * Get the stored settings for the "Display" tab.
	 * Used for Embedded.
	 *
	 * @since 4.0
	 *
	 * @return Hustle_Embedded_Display
	 */
	public function get_display() {
		return new Hustle_Meta_Base_Display( $this->get_settings_meta( self::KEY_DISPLAY_OPTIONS, '{}', true ), $this );
	}

	/**
	 * Used when populating data with "get".
	 */
	public function get_settings() {
		$saved = $this->get_settings_meta( self::KEY_SETTINGS, '{}', true );

		if ( self::POPUP_MODULE === $this->module_type ) {
			return new Hustle_Popup_Settings( $saved, $this );

		} elseif ( self::EMBEDDED_MODULE === $this->module_type ) {
			return new Hustle_Meta_Base_Settings( $saved, $this );

		} elseif ( self::SLIDEIN_MODULE === $this->module_type ) {
			return new Hustle_Slidein_Settings( $saved, $this );
		}

		return false;
	}

	/**
	 * Get the stored schedule flags
	 *
	 * @since 4.2.0
	 * @return array
	 */
	public function get_schedule_flags() {
		$default = array(
			'is_currently_scheduled' => '1',
			'check_schedule_at'      => 1,
		);

		return $this->get_settings_meta( 'schedule_flags', $default, true );
	}

	/**
	 * Set the schedule flags.
	 *
	 * @since 4.2.0
	 * @param array $flags
	 * @return void
	 */
	public function set_schedule_flags( $flags ) {
		$this->update_meta( 'schedule_flags', $flags );
	}

	/**
	 * Get the module's data. Used to display it.
	 *
	 * @since 3.0.7
	 *
	 * @return array
	 */
	public function get_module_data_to_display() {
		$settings = array( 'settings' => $this->get_settings()->to_array() );
		$data     = array_merge( $settings, $this->get_data() );

		return $data;
	}

	/**
	 * Get the form fields of this module, if any.
	 *
	 * @since 4.0
	 *
	 * @return null|array
	 */
	public function get_form_fields() {

		if ( 'social_sharing' === $this->module_type || 'informational' === $this->module_mode ) {
			return null;
		}

		$emails_data = empty( $this->emails ) ? $this->get_emails()->to_array() : (array) $this->emails;
		/**
		 * Edit module fields
		 *
		 * @since 4.1.1
		 * @param string $form_elements Current module fields.
		 */
		$form_fields = apply_filters( 'hustle_form_elements', $emails_data['form_elements'] );

		return $form_fields;

	}

	/**
	 * Create a new module of the provided mode and type.
	 *
	 * @since 4.0
	 *
	 * @param array $data Must contain the Module's 'mode', 'name' and 'type.
	 * @return int|false Module ID if successfully saved. False otherwise.
	 */
	public function create_new( $data ) {
		$module_populated = $this->populate_module_from_data( $data );
		if ( ! $module_populated ) {
			return false;
		}

		// Save to modules table.
		$this->save();

		// Save the new module's meta.
		$this->store_new_module_meta( $data );

		$this->activate_providers( $data );

		return $this->id;
	}

	/**
	 * Populates the current model with the given data.
	 *
	 * @since 4.3.4
	 *
	 * @param array $data Data to populate the module with.
	 * @return bool
	 */
	private function populate_module_from_data( $data ) {
		// Verify it's a valid module type.
		if ( ! in_array( $data['module_type'], array( self::POPUP_MODULE, self::SLIDEIN_MODULE, self::EMBEDDED_MODULE ), true ) ) {
			return false;
		}

		// Abort if the mode isn't set.
		if ( ! in_array( $data['module_mode'], array( 'optin', 'informational' ), true ) ) {
			return false;
		}

		$this->module_name = sanitize_text_field( $data['module_name'] );
		$this->module_type = $data['module_type'];
		$this->active      = 0;
		$this->module_mode = $data['module_mode'];

		return true;
	}

	/**
	 * Store the metas in the db when creating a new module.
	 *
	 * @since 4.0.0
	 *
	 * @param array $data Module's data to store.
	 */
	private function store_new_module_meta( $data ) {
		$def_content = apply_filters( 'hustle_module_get_' . self::KEY_CONTENT . '_defaults', $this->get_content()->to_array(), $this, $data );
		$content     = empty( $data['content'] ) ? $def_content : array_merge( $def_content, $data['content'] );

		$def_emails = apply_filters( 'hustle_module_get_' . self::KEY_EMAILS . '_defaults', $this->get_emails()->to_array(), $this, $data );
		$emails     = empty( $data['emails'] ) ? $def_emails : array_merge( $def_emails, $data['emails'] );

		$def_design = apply_filters( 'hustle_module_get_' . self::KEY_DESIGN . '_defaults', $this->get_design()->to_array(), $this, $data );
		$design     = empty( $data['design'] ) ? $def_design : array_merge( $def_design, $data['design'] );

		$def_integrations_settings = apply_filters( 'hustle_module_get_' . self::KEY_INTEGRATIONS_SETTINGS . '_defaults', $this->get_integrations_settings()->to_array(), $this, $data );
		$integrations_settings     = empty( $data['integrations_settings'] ) ? $def_integrations_settings : array_merge( $def_integrations_settings, $data['integrations_settings'] );

		$def_settings = apply_filters( 'hustle_module_get_' . self::KEY_SETTINGS . '_defaults', $this->get_settings()->to_array(), $this, $data );
		$settings     = empty( $data['settings'] ) ? $def_settings : array_merge( $def_settings, $data['settings'] );

		$def_visibility = apply_filters( 'hustle_module_get_' . self::KEY_VISIBILITY . '_defaults', $this->get_visibility()->to_array(), $this, $data );
		$visibility     = empty( $data['visibility'] ) ? $def_visibility : array_merge( $def_visibility, $data['visibility'] );

		$this->update_meta( self::KEY_CONTENT, $content );
		$this->update_meta( self::KEY_EMAILS, $emails );
		$this->update_meta( self::KEY_INTEGRATIONS_SETTINGS, $integrations_settings );
		$this->update_meta( self::KEY_DESIGN, $design );
		$this->update_meta( self::KEY_SETTINGS, $settings );
		$this->update_meta( self::KEY_VISIBILITY, $visibility );

		// Embedded only. Display options.
		if ( self::EMBEDDED_MODULE === $this->module_type ) {
			$def_display = apply_filters( 'hustle_module_get_' . self::KEY_DISPLAY_OPTIONS . '_defaults', $this->get_display()->to_array(), $this, $data );
			$display     = empty( $data['display'] ) ? $def_display : array_merge( $def_display, $data['display'] );

			$this->update_meta( self::KEY_DISPLAY_OPTIONS, $display );
		}
	}

	/**
	 * Populates an instance of a module for a template preview.
	 * This is only used for displaying a preview. We're using a saved
	 * module nor creating a new one, thus we don't have an ID.
	 *
	 * @since 4.3.4
	 *
	 * @param array $data Module data to be populated.
	 */
	public function populate_module_for_template( $data ) {
		$this->module_id = 0;

		$this->populate_module_from_data( $data );
	}

	/**
	 * Creates and store the nonce used to validate email unsubscriptions.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param array  $lists_id IDs of the modules to which it will be unsubscribed.
	 * @return boolean
	 */
	public function create_unsubscribe_nonce( $email, array $lists_id ) {
		// Since we're supporting php 5.2, random_bytes or other strong rng are not available. So using this instead.
		$nonce = hash_hmac( 'md5', $email, wp_rand() . time() );

		$data = get_option( self::KEY_UNSUBSCRIBE_NONCES, array() );

		// If the email already created a nonce and didn't use it, replace its data.
		$data[ $email ] = array(
			'nonce'        => $nonce,
			'lists_id'     => $lists_id,
			'date_created' => time(),
		);

		$updated = update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );
		if ( $updated ) {
			return $nonce;
		} else {
			return false;
		}
	}

	/**
	 * Does the actual email unsubscription.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param string $nonce Nonce associated with the email for the unsubscription.
	 * @return boolean
	 */
	public function unsubscribe_email( $email, $nonce ) {
		$data = get_option( self::KEY_UNSUBSCRIBE_NONCES, false );
		if ( ! $data ) {
			return false;
		}
		if ( ! isset( $data[ $email ] ) || ! isset( $data[ $email ]['nonce'] ) || ! isset( $data[ $email ]['lists_id'] ) ) {
			return false;
		}
		$email_data = $data[ $email ];
		if ( ! hash_equals( (string) $email_data['nonce'], $nonce ) ) {
			return false;
		}
		// Nonce expired. Remove it. Currently giving 1 day of life span.
		if ( ( time() - (int) $email_data['date_created'] ) > DAY_IN_SECONDS ) {
			unset( $data[ $email ] );
			update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );
			return false;
		}

		// Proceed to unsubscribe
		foreach ( $email_data['lists_id'] as $id ) {
			$unsubscribed = $this->remove_local_subscription_by_email_and_module_id( $email, $id );
		}

		// The email was unsubscribed and the nonce was used. Remove it from the saved list.
		unset( $data[ $email ] );
		update_option( self::KEY_UNSUBSCRIBE_NONCES, $data );

		return true;

	}

	/**
	 * Updates the metas specific for Non Social Sharing modules.
	 *
	 * @since 4.3.0
	 * @param array $data Data to save.
	 * @return void
	 */
	protected function update_module_metas( $data ) {
		// Meta used in all module types.
		if ( isset( $data['content'] ) ) {
			$this->update_meta( self::KEY_CONTENT, $data['content'] );
		}
		// Meta used in all module types.
		if ( isset( $data['visibility'] ) ) {
			$this->update_meta( self::KEY_VISIBILITY, $data['visibility'] );
		}

		// Design tab.
		if ( isset( $data['design'] ) ) {
			$saved_design = $this->get_design()->to_array();
			$new_design   = array_merge( $saved_design, $data['design'] );

			$this->update_meta( self::KEY_DESIGN, $new_design );
		}

		// Emails tab.
		if ( isset( $data['emails'] ) ) {
			$emails = $data['emails'];
			if ( isset( $emails['form_elements'] ) ) {
				$emails['form_elements'] = $this->sanitize_form_elements( $emails['form_elements'] );
			}
			$this->update_meta( self::KEY_EMAILS, $emails );
		}

		// Settings tab.
		if ( isset( $data['settings'] ) ) {
			// Clear flags to skip cached schedule values.
			$this->set_schedule_flags( array() );
			$this->update_meta( self::KEY_SETTINGS, $data['settings'] );
		}

		// Integrations tab.
		if ( isset( $data['integrations_settings'] ) ) {
			$this->update_meta( self::KEY_INTEGRATIONS_SETTINGS, $data['integrations_settings'] );
		}

		// Embedded only meta.
		if ( self::EMBEDDED_MODULE === $this->module_type && isset( $data['display'] ) ) {
			$this->update_meta( self::KEY_DISPLAY_OPTIONS, $data['display'] );
		}

		// Activate integrations if provided.
		if ( isset( $data['integrations'] ) ) {
			$this->activate_providers( $data );
		}

		$this->maybe_update_custom_fields();
	}

	/**
	 * Sanitize/Replace the module's data.
	 *
	 * @param array $data Data to sanitize.
	 * @return array Sanitized data.
	 */
	public function sanitize_module( $data ) {
		$design_obj         = $this->get_design();
		$default_options    = $design_obj->get_defaults();
		$saved_options      = $design_obj->to_array();
		$new_options        = ! empty( $data['design'] ) ? $data['design'] : array();
		$typography_options = $design_obj->get_typography_defaults( 'desktop' );

		// Check is `Border, Spacing and Shadow` enabled for desktop.
		$spacing_on = $this->get_newest_value( 'customize_border_shadow_spacing', $new_options, $saved_options );
		// Check is `Typography` enabled for desktop.
		$typography_on = $this->get_newest_value( 'customize_typography', $new_options, $saved_options );

		foreach ( $new_options as $option_name => $value ) {
			if ( $spacing_on ) {
				$data = $this->replace_empty_spacing_numbers( $data, $option_name, $value, $default_options );
			}
			if ( $typography_on ) {
				$data = $this->replace_empty_typography_numbers( $data, $option_name, $value, $default_options, $typography_options );
			}
		}

		if ( ! empty( $data['module']['module_name'] ) ) {
			$data['module']['module_name'] = sanitize_text_field( $data['module']['module_name'] );
		}

		return $data;
	}

	/**
	 * Validates the module's data.
	 *
	 * @since 4.0.3
	 *
	 * @param array $data Data to validate.
	 * @return array
	 */
	public function validate_module( $data ) {
		$errors = array();

		// Name validation.
		if ( empty( $data['module']['module_name'] ) ) {
			$errors['error']['name_error'] = __( 'This field is required', 'hustle' );

			return $errors;
		}

		return true;
	}

	/**
	 * Get newest value.
	 *
	 * @param string $option_name Option name.
	 * @param array  $new_options New options.
	 * @param array  $saved_options Old options.
	 * @return bool
	 */
	private function get_newest_value( $option_name, $new_options, $saved_options ) {
		if ( isset( $new_options[ $option_name ] ) ) {
			$value = $new_options[ $option_name ];
		} elseif ( isset( $saved_options[ $option_name ] ) ) {
			$value = $saved_options[ $option_name ];
		} else {
			$value = '';
		}

		return $value;
	}

	/**
	 * Check if it's spacing option and value isn't set
	 *
	 * @param string $key Option name.
	 * @param string $value Option value.
	 * @return bool
	 */
	private function is_empty_spacing_number( $key, $value ) {
		$needles = array( '_margin_', '_padding_', '_shadow_', '_radius_', '_border_' );
		return $this->similar_in_array( $key, $needles ) && '' === $value;
	}

	/**
	 * Check if it's typography option and value isn't set
	 *
	 * @param string $key Option name.
	 * @param string $value Option value.
	 * @param array  $typography_options Typography options.
	 * @return bool
	 */
	private function is_empty_typography_number( $key, $value, $typography_options ) {
		return '' === $value && isset( $typography_options[ $key ] )
				&& ( is_float( $typography_options[ $key ] ) || is_int( $typography_options[ $key ] ) );
	}

	/**
	 * If it's an empty number option from Destop section - replace it to relevant default value.
	 *
	 * @param string $data Data for sanitize.
	 * @param string $option_name Option name.
	 * @param string $option_value Option value.
	 * @param array  $default_options Default options.
	 * @return string
	 */
	private function replace_empty_spacing_numbers( $data, $option_name, $option_value, $default_options ) {
		if ( $this->is_empty_spacing_number( $option_name, $option_value ) && '_mobile' !== substr( $option_name, -7 ) ) {
			$data['design'][ $option_name ] = isset( $default_options[ $option_name ] ) ? $default_options[ $option_name ] : 0;
		}

		return $data;
	}

	/**
	 * If it's an empty number option from Typography section - replace it to relevant default value.
	 *
	 * @param string $data Data for sanitize.
	 * @param string $option_name Option name.
	 * @param string $option_value Option value.
	 * @param array  $default_options Default options.
	 * @param array  $typography_options Typography options.
	 * @return string
	 */
	private function replace_empty_typography_numbers( $data, $option_name, $option_value, $default_options, $typography_options ) {
		if ( $this->is_empty_typography_number( $option_name, $option_value, $typography_options ) && '_mobile' !== substr( $option_name, -7 ) ) {
			$data['design'][ $option_name ] = isset( $default_options[ $option_name ] ) ? $default_options[ $option_name ] : 0;
		}

		return $data;
	}

	/**
	 * Checks if a value contains a part of any array item
	 *
	 * @param array  $haystack The value.
	 * @param string $needles The array of pices.
	 * @return boolean
	 */
	private function similar_in_array( $haystack, $needles ) {
		foreach ( $needles as $needle ) {
			if ( false !== strpos( $haystack, $needle ) ) {
				return true;
			}
		}

		return false;
	}

	public function get_renderer() {
		return new Hustle_Module_Renderer();
	}

	/**
	 * Sanitize the form fields name replacing spaces by underscores.
	 * This way the data is handled properly along hustle.
	 *
	 * @since 4.0
	 * @param string $name
	 * @return string
	 */
	private function sanitize_form_field_name( $name ) {
		$sanitized_name = apply_filters( 'hustle_sanitize_form_field_name', str_replace( ' ', '_', trim( $name ) ), $name );

		return sanitize_text_field( $sanitized_name );
	}

	public function sanitize_form_elements( $form_elements ) {
		// Sanitize GDPR message
		if ( isset( $form_elements['gdpr']['gdpr_message'] ) ) {
			$allowed_html                          = array(
				'a'      => array(
					'href'   => true,
					'title'  => true,
					'target' => true,
					'alt'    => true,
				),
				'b'      => array(),
				'strong' => array(),
				'i'      => array(),
				'em'     => array(),
				'del'    => array(),
			);
			$form_elements['gdpr']['gdpr_message'] = wp_kses( wp_unslash( $form_elements['gdpr']['gdpr_message'] ), $allowed_html );
		}

		$sanitized_fields = array();

		// Loop through each form field.
		foreach ( $form_elements as $field_data ) {
			$name = $this->sanitize_form_field_name( $field_data['name'] );

			// After sanitize if name become empty then create array key from label.
			if ( ! $name ) {
				$name = $this->sanitize_form_field_name( $field_data['label'] );

				// If still key is empty then go to next iteration.
				if ( ! $name ) {
					continue;
				}
			}

			// Check field name already exists or not. If exists then create a new name.
			$name = $this->get_unique_field_name( $sanitized_fields, $name );

			// Sanitize necessary fields.
			$field_data['name']        = $name;
			$field_data['label']       = sanitize_text_field( $field_data['label'] );
			$field_data['placeholder'] = sanitize_text_field( $field_data['placeholder'] );

			// Add new item with field data.
			$sanitized_fields[ $name ] = $field_data;
		}

		return $sanitized_fields;
	}

	/**
	 * Update Custom Fields for Sendgrid New Campaigns
	 */
	private function maybe_update_custom_fields() {
		$connected_addons = Hustle_Provider_Utils::get_addons_instance_connected_with_module( $this->module_id );

		foreach ( $connected_addons as $addon ) {

			// Change logic only for sendgrid for now.
			if ( 'sendgrid' !== $addon->get_slug() ) {
				continue;
			}
			$global_multi_id = $addon->selected_global_multi_id;
			$new_campaigns   = $addon->get_setting( 'new_campaigns', '', $global_multi_id );

			// only if it's the New Sendgrid Campaigns.
			if ( 'new_campaigns' !== $new_campaigns ) {
				continue;
			}
			$emails        = $this->get_emails()->to_array();
			$custom_fields = array();

			$api_key = $addon->get_setting( 'api_key', '', $global_multi_id );
			$api     = $addon::api( $api_key, $new_campaigns );

			foreach ( $emails['form_elements'] as $element ) {
				if ( empty( $element['type'] ) || in_array( $element['type'], array( 'submit', 'recaptcha' ), true ) ) {
					continue;
				}
				$custom_fields[] = array(
					'type' => 'text',
					'name' => $element['name'],
				);
			}

			if ( ! empty( $custom_fields ) ) {
				$api->add_custom_fields( $custom_fields );
			}
		}
	}

	/**
	 * Gets the selected Google fonts for the active elements in the module.
	 * Used for non-ssharing modules only.
	 *
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public function get_google_fonts() {
		$fonts = array();

		if ( '1' === $this->design->use_vanilla ) {
			return $fonts;
		}

		$elements = array(
			'title'                      => '' !== $this->content->title,
			'subtitle'                   => '' !== $this->content->sub_title,
			'main_content_paragraph'     => '' !== $this->content->main_content,
			'main_content_heading_one'   => '' !== $this->content->main_content,
			'main_content_heading_two'   => '' !== $this->content->main_content,
			'main_content_heading_three' => '' !== $this->content->main_content,
			'main_content_heading_four'  => '' !== $this->content->main_content,
			'main_content_heading_five'  => '' !== $this->content->main_content,
			'main_content_heading_six'   => '' !== $this->content->main_content,
			'cta'                        => '0' !== $this->content->show_cta,
			'never_see_link'             => '0' !== $this->content->show_never_see_link,
		);

		// Only list the font of the elements that are shown, and aren't using a 'custom' font.
		foreach ( $elements as $element_name => $is_shown ) {
			if ( ! $is_shown ) {
				continue;
			}

			$font = $this->design->{ $element_name . '_font_family' };
			if ( 'custom' !== $font ) {
				$font_weight = $this->design->{ $element_name . '_font_weight' };
				if ( ! isset( $fonts[ $font ] ) ) {
					$fonts[ $font ] = array();
				}
				if ( ! in_array( $font_weight, $fonts[ $font ], true ) ) {
					$fonts[ $font ][] = $font_weight;
				}
			}
		}

		// We're done here for informational modules.
		if ( self::OPTIN_MODE !== $this->module_mode ) {
			return $fonts;
		}

		$has_mailchimp = ! empty( $this->get_provider_settings( 'mailchimp' ) );

		$form_fields         = $this->get_form_fields();
		$has_success_message = 'show_success' === $this->emails->after_successful_submission;

		$elements_optin = array(
			'form_extras'                   => $has_mailchimp,
			'input'                         => true,
			'select'                        => $has_mailchimp,
			'checkbox'                      => $has_mailchimp,
			'dropdown'                      => $has_mailchimp,
			'gdpr'                          => ! empty( $form_fields['gdpr'] ),
			'recaptcha'                     => ! empty( $form_fields['recaptcha'] ),
			'submit_button'                 => true,
			'success_message_paragraph'     => $has_success_message,
			'success_message_heading_one'   => $has_success_message,
			'success_message_heading_two'   => $has_success_message,
			'success_message_heading_three' => $has_success_message,
			'success_message_heading_four'  => $has_success_message,
			'success_message_heading_five'  => $has_success_message,
			'success_message_heading_six'   => $has_success_message,
			'error_message'                 => true,
		);

		foreach ( $elements_optin as $element_name => $is_shown ) {
			if ( ! $is_shown ) {
				continue;
			}

			$font = $this->design->{ $element_name . '_font_family' };
			if ( 'custom' !== $font ) {
				$font_weight = $this->design->{ $element_name . '_font_weight' };
				if ( ! isset( $fonts[ $font ] ) ) {
					$fonts[ $font ] = array();
				}
				if ( ! in_array( $font_weight, $fonts[ $font ], true ) ) {
					$fonts[ $font ][] = $font_weight;
				}
			}
		}
		return $fonts;
	}

	/**
	 * Find the unique name of field without overriding others.
	 *
	 * @since 4.3.3
	 *
	 * @param array  $sanitized_fields Array for fields that are previously sanitized.
	 * @param string $field_name field name which will be compare.
	 *
	 * @return array
	 */
	private function get_unique_field_name( $sanitized_fields, $field_name ) {
		$new_name = $field_name;
		$i        = 0;

		while ( array_key_exists( $new_name, $sanitized_fields ) ) {
			$i++;
			$new_name = $field_name . '-' . $i;
		}

		return $new_name;
	}

}