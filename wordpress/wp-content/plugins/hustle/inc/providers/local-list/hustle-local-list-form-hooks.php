<?php

/**
 * Class Hustle_Local_List_Form_Hooks
 * Define the form hooks that are used by Local List
 *
 * @since 4.0
 */
class Hustle_Local_List_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {

	/**
	 * Check whether the email is already subscribed.
	 *
	 * @since 4.0
	 *
	 * @param $submitted_data
	 * @return bool
	 */
	public function on_form_submit( $submitted_data, $allow_subscribed = true ) {
		$is_success = true;

		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;

		if ( ! $allow_subscribed ) {

			/**
			 * Filter submitted form data to be processed
			 *
			 * @since 4.0
			 *
			 * @param array                                    $submitted_data
			 * @param int                                      $module_id                current module_id
			 * @param Hustle_Local_List_Form_Settings $form_settings_instance
			 */
			$submitted_data = apply_filters(
				'hustle_provider_local_list_form_submitted_data_before_validation',
				$submitted_data,
				$module_id,
				$form_settings_instance
			);

			$is_subscribed = Hustle_Entry_Model::is_email_subscribed_to_module_id( $module_id, $submitted_data['email'] );

			// Subscribe only if the email wasn't subscribed already.
			if ( $is_subscribed ) {
				$is_success = self::ALREADY_SUBSCRIBED_ERROR;
			}
		}

		/**
		 * Return `true` if success, or **(string) error message** on fail
		 *
		 * @since 4.0
		 *
		 * @param bool                                     $is_success
		 * @param int                                      $module_id                current module_id
		 * @param array                                    $submitted_data
		 * @param Hustle_Local_List_Form_Settings $form_settings_instance
		 */
		$is_success = apply_filters(
			'hustle_provider_local_list_form_submitted_data_after_validation',
			$is_success,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);

		// process filter
		if ( true !== $is_success ) {
			// only update `_submit_form_error_message` when not empty
			if ( ! empty( $is_success ) ) {
				$this->_submit_form_error_message = (string) $is_success;
			}

			return $is_success;
		}

		return true;
	}

	/**
	 * We're adding the local list's entries in the front-ajax file because
	 * we need all integrations' hook to run first in order to add their data
	 * to entries. Move that behavior to this file if we want to do it here instead,
	 * as it should be.
	 * Hustle_Module_Modal::add_local_subscription() doesn't exist anymore.
	 * We're handling entries with hustle_Entry_Model class.
	 */

	/**
	 * Add Local List data to entry.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	// public function add_entry_fields( $submitted_data ) {

	// $module_id = $this->module_id;
	// $form_settings_instance = $this->form_settings_instance;

	// **
	// * @since 4.0
	// */
	// $submitted_data = apply_filters( 'hustle_provider_' . $this->addon->get_slug() . '_form_submitted_data', $submitted_data, $module_id, $form_settings_instance );

	// $addon_setting_values = $form_settings_instance->get_form_settings_values();

	// try {
	// if ( empty( $submitted_data['email'] ) ) {
	// throw new Exception( __('Required Field "email" was not filled by the user.', 'hustle' ) );
	// }

	// $submitted_data = $this->check_legacy( $submitted_data );

	// $module = new Hustle_Module_Model( $module_id );

	// $local_subscription_data = wp_parse_args( $submitted_data, array(
	// 'module_type' => $module->module_type,
	// 'time' => current_time( 'timestamp' ),
	// ) );

	// $res = $module->add_local_subscription( $local_subscription_data );

	// if ( is_wp_error( $res ) ) {
	// $entry_fields = array(
	// array(
	// 'name'  => 'status',
	// 'value' => array(
	// 'is_sent'       => false,
	// 'description'   => $res->get_error_message(),
	// ),
	// ),
	// );
	// } else {

	// $entry_fields = array(
	// array(
	// 'name'  => 'status',
	// 'value' => array(
	// 'is_sent'       => true,
	// 'description'   => __( 'Successfully added or updated member on Local list', 'hustle' ),
	// ),
	// ),
	// );
	// }

	// } catch ( Exception $e ) {
	// $entry_fields = $this->exception( $e );
	// }

	// return $entry_fields;
	// }

}