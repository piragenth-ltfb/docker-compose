<?php
/**
 * Opt_In_Condition_Wc_Categories.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_Wc_Categories.
 * Condition based on the WooCommerce products categories.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_Wc_Categories extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {

		if ( ! Opt_In_Utils::is_woocommerce_active() ) {
			return false;
		}

		$selected_categories = ! empty( $this->args->wc_categories ) ? (array) $this->args->wc_categories : array();
		$filter_type         = isset( $this->args->filter_type ) && in_array( $this->args->filter_type, array( 'only', 'except' ), true )
				? $this->args->filter_type : 'except';

		$current_categories = $this->get_current_wc_categories();

		// There was an error retrieving the categories.
		if ( is_null( $current_categories ) ) {
			return false;
		}

		if ( 'except' === $filter_type ) {
			return array() === array_intersect( $current_categories, $selected_categories );

		} else {
			return array() !== array_intersect( $current_categories, $selected_categories );
		}
	}

	/**
	 * Returns WooCommerce categories of the current post
	 *
	 * @since 4.1.0
	 * @return null|array
	 */
	private function get_current_wc_categories() {
		global $post;
		if ( ! isset( $post ) || ! ( $post instanceof WP_Post ) || 'product' !== $post->post_type || ! is_single() ) {
			return null;
		}

		$terms    = get_the_terms( $post, 'product_cat' );
		$term_ids = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'term_id' ) : array();
		return array_map( 'strval', $term_ids );
	}

}