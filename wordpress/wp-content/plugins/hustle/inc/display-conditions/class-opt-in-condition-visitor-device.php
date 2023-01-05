<?php
/**
 * Opt_In_Condition_Visitor_Device.
 *
 * @package Hustle
 * @since unkwnown
 */

/**
 * Opt_In_Condition_Visitor_Device.
 * Condition based on the visitor's device.
 *
 * @since unkwnown
 */
class Opt_In_Condition_Visitor_Device extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnown
	 */
	public function is_allowed() {

		if ( 'mobile' === $this->args->filter_type ) {
			return wp_is_mobile();
		} else {
			return ! wp_is_mobile();
		}
	}
}