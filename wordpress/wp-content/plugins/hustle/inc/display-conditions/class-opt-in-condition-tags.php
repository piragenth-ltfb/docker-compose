<?php
/**
 * Opt_In_Condition_Tags.
 *
 * @package Hustle
 * @since unknwon
 */

/**
 * Opt_In_Condition_Tags.
 * Handles the post tags.
 *
 * @since unknwon
 */
class Opt_In_Condition_Tags extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknown
	 */
	public function is_allowed() {

		$selected_tags = ! empty( $this->args->tags ) ? (array) $this->args->tags : array();
		$filter_type   = isset( $this->args->filter_type ) && in_array( $this->args->filter_type, array( 'only', 'except' ), true )
				? $this->args->filter_type : 'except';

		$current_tags = $this->get_current_tags();

		// There was an error retrieving the tags.
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
	 * Returns tags of current page|post
	 *
	 * @since 2.0.0
	 * @return null|array
	 */
	private function get_current_tags() {
		global $post;
		if ( ! isset( $post ) || ! ( $post instanceof WP_Post ) || 'post' !== $post->post_type || ! is_single() ) {
			return null;
		}

		$terms    = get_the_tags( $post->ID );
		$term_ids = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'term_id' ) : array();
		return array_map( 'strval', $term_ids );
	}

}