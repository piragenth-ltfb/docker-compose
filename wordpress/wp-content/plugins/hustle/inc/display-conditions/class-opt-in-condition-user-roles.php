<?php
/**
 * Opt_In_Condition_User_Roles.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_User_Roles.
 * Condition based on the role of the current user.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_User_Roles extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		if ( ! empty( $this->args->filter_type ) ) {
			$user        = wp_get_current_user();
			$roles       = (array) $user->roles;
			$saved_roles = (array) $this->args->roles;
			$valid_roles = array_intersect( $roles, $saved_roles );

			if ( 'except' === $this->args->filter_type ) {
				return empty( $valid_roles );
			} elseif ( 'only' === $this->args->filter_type ) {
				return ! empty( $valid_roles );
			}
		}

		return false;
	}

}