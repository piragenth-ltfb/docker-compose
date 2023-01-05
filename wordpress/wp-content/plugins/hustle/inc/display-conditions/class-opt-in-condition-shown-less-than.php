<?php
/**
 * Opt_In_Condition_Shown_Less_Than.
 *
 * @package Hustle
 * @since unknwon
 */

/**
 * Opt_In_Condition_Shown_Less_Than.
 * Handles the number of instances a module has been shown.
 *
 * @since unknwon
 */
class Opt_In_Condition_Shown_Less_Than extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknown
	 */
	public function is_allowed() {
		$module = $this->module;

		if ( ! isset( $this->args->less_than ) ) {
			return false;
		}

		$cookie_key = $this->get_cookie_key( $module->module_type ) . $module->id;

		$show_count = isset( $_COOKIE[ $cookie_key ] ) ? (int) $_COOKIE[ $cookie_key ] : 0;

		$is_less = empty( $this->args->less_or_more ) || 'more_than' !== $this->args->less_or_more;

		if ( empty( $this->args->less_than ) ) {
			return true;
		} elseif ( $is_less ) {
			return $show_count < (int) $this->args->less_than;
		} else {
			return $show_count > (int) $this->args->less_than;
		}
	}

	/**
	 * Gets the name of the cookie based on the module type.
	 *
	 * @since unknown
	 * @param string $module_type Current module type popup|slidein|embedded|social_sharing.
	 * @return string
	 */
	public function get_cookie_key( $module_type ) {
		return 'hustle_module_show_count-' . $module_type . '-';
	}
}