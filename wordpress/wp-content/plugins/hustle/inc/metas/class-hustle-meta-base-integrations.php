<?php
/**
 * File for Hustle_Meta_Base_Integrations class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Meta_Base_Integrations is the base class for the "integrations" meta of modules.
 * This class should handle what's related to the "integrations" meta.
 *
 * @since 4.2.0
 */
class Hustle_Meta_Base_Integrations extends Hustle_Meta {

	/**
	 * Get the defaults for this meta.
	 *
	 * @since 4.0.0
	 * @since 4.2.0 Moved from Hustle_Popup_Integrations to this class.
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'allow_subscribed_users'      => '1',
			'disallow_submission_message' => __( 'This email address is already subscribed.', 'hustle' ),
			'active_integrations'         => 'local_list', // Default active integration.
			'active_integrations_count'   => '1', // Default. Only local_list is active.
		);
	}
}