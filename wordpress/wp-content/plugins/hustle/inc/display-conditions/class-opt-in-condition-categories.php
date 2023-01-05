<?php
/**
 * Opt_In_Condition_Categories class.
 *
 * @package Hustle
 * @since unwknown
 */

/**
 * Class Opt_In_Condition_Categories.
 * Handles posts categories.
 *
 * @since unknown
 */
class Opt_In_Condition_Categories extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknwon
	 */
	public function is_allowed() {

		$selected_categories = ! empty( $this->args->categories ) ? (array) $this->args->categories : array();
		$filter_type         = isset( $this->args->filter_type ) && in_array( $this->args->filter_type, array( 'only', 'except' ), true )
				? $this->args->filter_type : 'except';

		$current_categories = $this->get_current_categories();

		// There was an error retrieving the categories.
		if ( is_null( $current_categories ) ) {
			return false;
		}

		if ( 'except' === $filter_type ) {

			// The current post has no categories.
			// Matching "all categories except: {any|none}". We're matching only posts with at least 1 category.
			if ( empty( $current_categories ) ) {
				return false;
			}

			return array() === array_intersect( $current_categories, $selected_categories );

		} else {

			// No categories were selected and the current post has no categories.
			// Matching "only these categories: {none}". We're matching only posts with no categories.
			if ( empty( $current_categories ) && empty( $selected_categories ) ) {
				return true;
			}

			return array() !== array_intersect( $current_categories, $selected_categories );
		}
	}

	/**
	 * Returns categories of current page|post
	 *
	 * @since 2.0.0
	 * @return null|array
	 */
	private function get_current_categories() {
		global $post;
		if ( ! isset( $post ) || ! ( $post instanceof WP_Post ) || 'post' !== $post->post_type || ! is_single() ) {
			return null;
		}

		$terms    = get_the_terms( $post, 'category' );
		$term_ids = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'term_id' ) : array();
		return array_map( 'strval', $term_ids );
	}

}