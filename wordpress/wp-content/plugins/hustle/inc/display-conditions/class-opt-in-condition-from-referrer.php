<?php
/**
 * Opt_In_Condition_From_Referrer class.
 *
 * @package Hustle
 * @since unwknown
 */

/**
 * Opt_In_Condition_From_Referrer.
 * Handles the current referrer.
 *
 * @since unknown
 */
class Opt_In_Condition_From_Referrer extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknwon
	 */
	public function is_allowed() {

		if ( ! isset( $this->args->refs ) ) {
			return false;
		}

		if ( 'true' === $this->args->filter_type ) {
			return $this->utils()->test_referrer( $this->args->refs );
		} else {
			return ! ( $this->utils()->test_referrer( $this->args->refs ) );
		}
	}
}