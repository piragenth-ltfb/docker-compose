<?php
/**
 * Opt_In_Condition_User_Registration.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_User_Registration.
 * Handles the time since a user registration.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_User_Registration extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {

		if ( ! is_user_logged_in() ) {
			return false;
		}
		if ( empty( $this->args->from_date ) ) {
			$this->args->from_date = 0;
		}

		$user              = get_userdata( get_current_user_id() );
		$registration_date = $user->user_registered;
		$show_from_date    = strtotime( $registration_date ) + DAY_IN_SECONDS * $this->args->from_date;
		$today             = time();

		if ( ! empty( $this->args->to_date ) ) {
			$show_to_date = strtotime( $registration_date ) + DAY_IN_SECONDS * $this->args->to_date;
			if ( $today >= $show_from_date && $today < $show_to_date ) {
				return true;
			} else {
				return false;
			}
		} elseif ( $today >= $show_from_date ) {
			return true;
		} else {
			return false;
		}

	}
}