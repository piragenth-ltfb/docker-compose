<?php
/**
 * File for Hustle_Meta_Base_Emails class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Meta_Base_Emails is the base class for the "emails" meta of modules.
 * This class should handle what's related to the "emails" meta.
 *
 * @since 4.2.0
 */
class Hustle_Meta_Base_Emails extends Hustle_Meta {

	/**
	 * Returns the defaults for merging purposes.
	 * Avoid overwritting the saved form elements when the default fields aren't present.
	 *
	 * @since 4.4.1
	 *
	 * @return array
	 */
	protected function get_defaults_for_merge() {
		$defaults = $this->get_defaults();

		// Avoid overwritting the saved form elements when the default fields aren't present.
		if ( isset( $defaults['form_elements'] ) && ! empty( $this->data['form_elements'] ) ) {
			unset( $defaults['form_elements'] );
		}
		return $defaults;
	}

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.2.0
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'form_elements'               => $this->get_default_form_fields(),
			'after_successful_submission' => 'show_success',
			'success_message'             => '',
			'auto_close_success_message'  => '0',
			'auto_close_time'             => 5,
			'auto_close_unit'             => 'seconds',
			'redirect_url'                => '',
			'automated_email'             => '0',
			'email_time'                  => 'instant',
			'recipient'                   => '{email}',
			'day'                         => '',
			'time'                        => '',
			'auto_email_time'             => '5',
			'schedule_auto_email_time'    => '5',
			'auto_email_unit'             => 'seconds',
			'schedule_auto_email_unit'    => 'seconds',
			'email_subject'               => '',
			'email_body'                  => '',
		);
	}

	/**
	 * Default form fields for new modules.
	 *
	 * @since the beginning of time
	 * @since 4.0.2 Moved from Opt_In_Static to Hustle_Popup_Emails.
	 * @since 4.2.0 Moved from Hustle_Popup_Emails to this class.
	 *
	 * @return array
	 */
	public static function get_default_form_fields() {

		$default_fields = array(
			'first_name' => array(
				'required'    => 'false',
				'label'       => __( 'First Name', 'hustle' ),
				'name'        => 'first_name',
				'type'        => 'name',
				'placeholder' => 'John',
				'can_delete'  => true,
			),
			'last_name'  => array(
				'required'    => 'false',
				'label'       => __( 'Last Name', 'hustle' ),
				'name'        => 'last_name',
				'type'        => 'name',
				'placeholder' => 'Smith',
				'can_delete'  => true,
			),
			'email'      => array(
				'required'    => 'true',
				'label'       => __( 'Your email', 'hustle' ),
				'name'        => 'email',
				'type'        => 'email',
				'placeholder' => 'johnsmith@example.com',
				'validate'    => 'true',
				'can_delete'  => false,
			),
			'submit'     => array(
				'required'      => 'true',
				'label'         => __( 'Submit', 'hustle' ),
				'error_message' => __( 'Something went wrong, please try again.', 'hustle' ),
				'name'          => 'submit',
				'type'          => 'submit',
				'placeholder'   => __( 'Subscribe', 'hustle' ),
				'can_delete'    => false,
			),
		);

		/**
		 * Filter the form fields that are added by default when creating a new module
		 *
		 * @since 4.2.0
		 */
		return apply_filters( 'hustle_get_module_default_form_fields', $default_fields );
	}
}