<?php
if ( ! class_exists( 'Hustle_Sendy_Form_Settings' ) ) :

	/**
	 * Class Hustle_Sendy_Form_Settings
	 * Form Settings Sendy Process
	 */
	class Hustle_Sendy_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

		/**
		 * Options that must be set in order to consider the integration as "connected" to the form.
		 *
		 * @since 4.2.0
		 * @var array
		 */
		protected $form_completion_options = array( 'selected_global_multi_id' );

		/**
		 * For settings Wizard steps
		 *
		 * @since 3.0.5
		 * @return array
		 */
		public function form_settings_wizards() {
			// already filtered on Abstract
			// numerical array steps
			return array(
				// 0
				array(
					'callback'     => array( $this, 'first_step_callback' ),
					'is_completed' => array( $this, 'is_multi_global_select_step_completed' ),
				),
			);
		}

		/**
		 * Returns all settings and conditions for 1st step of Mautic settings
		 *
		 * @since 3.0.5
		 * @since 4.0 param $validate removed.
		 *
		 * @param array $submitted_data
		 * @return array
		 */
		public function first_step_callback( $submitted_data ) {

			$message   = sprintf( esc_html__( "Sendy is activated for this module.%1\$sRemember:%2\$s if you add new fields or change the default fields' names from the Hustle form, you must add them in your Sendy dashboard as well for them to be added.", 'hustle' ), '<br/><b>', '</b>' );
			$step_html = Hustle_Provider_Utils::get_integration_modal_title_markup( __( 'Sendy', 'hustle' ), $message );

			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Disconnect', 'hustle' ),
						'sui-button-ghost',
						'disconnect_form',
						true
					),
				),
				'close'      => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Close', 'hustle' ), '', 'close', true ),
				),
			);

			$response = array(
				'html'       => $step_html,
				'buttons'    => $buttons,
				'has_errors' => false,
			);

			return $response;
		}

	} // Class end.

endif;