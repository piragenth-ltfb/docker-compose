<?php
/**
 * Opt_In_Condition_Wc_Static_Pages.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_Wc_Static_Pages.
 * Condition based on the WooCommerce static pages.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_Wc_Static_Pages extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {
		if ( ! Opt_In_Utils::is_woocommerce_active() ) {
			return false;
		}

		if ( isset( $this->args->wc_static_pages ) ) {
			$conditions = (array) $this->args->wc_static_pages;

			if ( is_cart() ) {
				$allowed = in_array( 'is_cart', $conditions, true );
			} elseif ( is_wc_endpoint_url( 'order-received' ) ) {
				$allowed = in_array( 'is_order_received', $conditions, true );
			} elseif ( is_checkout() ) {
				$allowed = in_array( 'is_checkout', $conditions, true );
			} elseif ( is_account_page() ) {
				$allowed = in_array( 'is_account_page', $conditions, true );
			}

			if ( ! isset( $allowed ) ) {
				return false;
			}

			if ( 'except' === $this->args->filter_type ) {
				return ! $allowed;
			} elseif ( 'only' === $this->args->filter_type ) {
				return $allowed;
			}
		}
		return false;
	}

}