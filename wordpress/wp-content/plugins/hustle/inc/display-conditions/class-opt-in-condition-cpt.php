<?php
/**
 * Opt_In_Condition_Cpt class.
 *
 * @package Hustle
 * @since unwknown
 */

/**
 * Opt_In_Condition_Cpt.
 * Handles custom post types.
 *
 * @since unknown
 */
class Opt_In_Condition_Cpt extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknwon
	 */
	public function is_allowed() {
		global $post;

		$selected_cpts = ! empty( $this->args->selected_cpts ) ? (array) $this->args->selected_cpts : array();
		$filter_type   = isset( $this->args->filter_type ) && in_array( $this->args->filter_type, array( 'only', 'except' ), true )
				? $this->args->filter_type : 'except';

		$post_type = ! empty( $this->args->postType ) ? $this->args->postType : false;

		/**
		 * Filter Custop Post Type condition behavior
		 *
		 * @since 4.1
		 *
		 * @param mixed $custom_return  Returned value - is allowed showing module or not
		 * @param object $this Opt_In_Condition_Cpt object
		 */
		$custom_return = apply_filters( 'hustle_cpt_condition', null, $post_type, $filter_type, $selected_cpts );
		if ( ! is_null( $custom_return ) ) {
			return $custom_return;
		}

		if ( ! isset( $post ) || ! ( $post instanceof WP_Post ) || empty( $post_type ) || $post->post_type !== $post_type || ! is_single() ) {
			return false;
		}

		switch ( $filter_type ) {
			case 'only':
				return in_array( strval( $post->ID ), $selected_cpts, true );

			case 'except':
			default:
				return ! in_array( strval( $post->ID ), $selected_cpts, true );
		}
	}
}