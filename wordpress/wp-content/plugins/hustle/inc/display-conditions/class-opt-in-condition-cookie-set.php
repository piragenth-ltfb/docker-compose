<?php
/**
 * Opt_In_Condition_Cookie_Set class.
 *
 * @package Hustle
 * @since 4.2.1
 */

/**
 * Opt_In_Condition_Cookie_Set
 * Handles the current cookie name and/or value
 *
 * @since 4.2.1
 */
class Opt_In_Condition_Cookie_Set extends Opt_In_Condition_Abstract {
	/**
	 * Prefix for all the condintions methods.
	 *
	 * @var string
	 */
	private $function_prefix = 'hustle_cookie_set_';

	/**
	 * Helper variable to keep cookie keys.
	 *
	 * @var array
	 */
	private $cookie_keys;

	/**
	 * Helper variable to keep browser cookie.
	 *
	 * @var array
	 */
	private $browser_cookie_value;

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.2.1
	 */
	public function is_allowed() {
		if ( empty( $this->args->cookie_name ) || ! isset( $this->args->filter_type ) ) {
			return false;
		}

		$this->cookie_keys = array_keys( $_COOKIE );
		// phpcs:disable WordPress
		// Value is only used for bool operations.
		$this->browser_cookie_value = isset( $_COOKIE[ $this->args->cookie_name ] ) ? $_COOKIE[ $this->args->cookie_name ] : '';
		// phpcs:enable
		return $this->is_cookie_set();
	}

	/**
	 * Launches the needed function accoridng to what user set in admin panel.
	 *
	 * @return boolean
	 */
	private function is_cookie_set() {
		$method_name = $this->function_prefix . $this->args->cookie_value_conditions;

		if ( ! method_exists( $this, $method_name ) ) {
			return false;
		}

		if ( 'exists' === $this->args->filter_type ) {
			$is_cookie_set = call_user_func( array( $this, $method_name ) );
			return $is_cookie_set;
		} else {
			$method_name   = $this->function_prefix . 'anything';
			$is_cookie_set = call_user_func( array( $this, $method_name ) );

			return ! $is_cookie_set;
		}
	}

	/**
	 * Checks if cookie itself is set. Value doesn't matters.
	 *
	 * @return boolean
	 */
	private function hustle_cookie_set_anything() {
		return in_array( $this->args->cookie_name, $this->cookie_keys, true );
	}

	/**
	 * Checks if cookie is set and value is equal.
	 */
	private function hustle_cookie_set_equals() {
		return ! empty( $this->browser_cookie_value ) &&
		$this->browser_cookie_value === $this->args->cookie_value;
	}

	/**
	 * Checks if cookie is set and value is not equal.
	 */
	private function hustle_cookie_set_doesnt_equals() {
		return ! empty( $this->browser_cookie_value ) &&
		$this->browser_cookie_value !== $this->args->cookie_value;
	}

	/**
	 * Checks if cookie is set and value contains the value set by user.
	 */
	private function hustle_cookie_set_contains() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		strpos( $this->browser_cookie_value, $this->args->cookie_value ) !== false;
	}

	/**
	 * Checks if cookie is set and value does not contains the value set by user.
	 */
	private function hustle_cookie_set_doesnt_contain() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		strpos( $this->browser_cookie_value, $this->args->cookie_value ) === false;
	}

	/**
	 * Checks if cookie is set and value is less than value set by user.
	 */
	private function hustle_cookie_set_less_than() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		is_numeric( $this->browser_cookie_value ) &&
		is_numeric( $this->args->cookie_value ) &&
		$this->browser_cookie_value < $this->args->cookie_value;
	}

	/**
	 * Checks if cookie is set and value is less or equal than value set by user.
	 */
	private function hustle_cookie_set_less_equal_than() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		is_numeric( $this->browser_cookie_value ) &&
		is_numeric( $this->args->cookie_value ) &&
		$this->browser_cookie_value <= $this->args->cookie_value;
	}

	/**
	 * Checks if cookie is set and value is greater than value set by user.
	 */
	private function hustle_cookie_set_greater_than() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		is_numeric( $this->browser_cookie_value ) &&
		is_numeric( $this->args->cookie_value ) &&
		$this->browser_cookie_value > $this->args->cookie_value;
	}

	/**
	 * Checks if cookie is set and value is greater or equal than value set by user.
	 */
	private function hustle_cookie_set_greater_equal_than() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		is_numeric( $this->browser_cookie_value ) &&
		is_numeric( $this->args->cookie_value ) &&
		$this->browser_cookie_value >= $this->args->cookie_value;
	}

	/**
	 * Checks if cookie is set and it value matches the pattern set by the user.
	 */
	private function hustle_cookie_set_matches_pattern() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		preg_match( $this->args->cookie_value, $this->browser_cookie_value ) === 1;
	}

	/**
	 * Checks if cookie is set and it value doesn't matches the pattern set by the user.
	 */
	private function hustle_cookie_set_doesnt_match_pattern() {
		return ! empty( $this->browser_cookie_value ) &&
		! empty( $this->args->cookie_value ) &&
		preg_match( $this->args->cookie_value, $this->browser_cookie_value ) === 0;
	}
}