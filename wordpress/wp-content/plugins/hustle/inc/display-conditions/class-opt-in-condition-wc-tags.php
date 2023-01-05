<?php
/**
 * Opt_In_Condition_Wc_Tags.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_Wc_Tags.
 * Condition based on the WooCommerce products tags.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_Wc_Tags extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {

		if ( ! Opt_In_Utils::is_woocommerce_active() ) {
			return false;
		}

		$selected_tags = ! empty( $this->args->wc_tags ) ? (array) $this->args->wc_tags : array();
		$filter_type   = isset( $this->args->filter_type ) && in_array( $this->args->filter_type, array( 'only', 'except' ), true )
				? $this->args->filter_type : 'except';

		$current_tags = $this->get_current_wc_tags();
		if ( is_null( $current_tags ) ) {
			return false;
		}

		if ( 'except' === $filter_type ) {

			// The current post has no tags.
			// Matching "all tags except: {any|none}". We're matching only posts with at least 1 tag.
			if ( empty( $current_tags ) ) {
				return false;
			}

			return array() === array_intersect( $current_tags, $selected_tags );

		} else {

			// No tags were selected and the current post has no tags.
			// Matching "only these tags: {none}". We're matching only posts with no tags.
			if ( empty( $current_tags ) && empty( $selected_tags ) ) {
				return true;
			}

			return array() !== array_intersect( $current_tags, $selected_tags );
		}
	}

	/**
	 * Returns WooCommerce tags of the current post
	 *
	 * @since 4.1.0
	 * @return null|array
	 */
	private function get_current_wc_tags() {
		global $post;
		if ( ! isset( $post ) || ! ( $post instanceof WP_Post ) || 'product' !== $post->post_type || ! is_single() ) {
			return null;
		}

		$terms    = get_the_terms( $post, 'product_tag' );
		$term_ids = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'term_id' ) : array();
		return array_map( 'strval', $term_ids );
	}

}