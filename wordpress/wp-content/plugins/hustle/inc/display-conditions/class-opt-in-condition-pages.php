<?php
/**
 * Opt_In_Condition_Pages class.
 *
 * @package Hustle
 * @since unknwon
 */

/**
 * Opt_In_Condition_Pages.
 * Handles the page post type.
 *
 * @since unknwon
 */
class Opt_In_Condition_Pages extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknown
	 */
	public function is_allowed() {
		global $post, $wp_query;

		$all         = false;
		$none        = false;
		$pages       = ! empty( $this->args->pages ) ? (array) $this->args->pages : array();
		$filter_type = isset( $this->args->filter_type ) && in_array( $this->args->filter_type, array( 'only', 'except' ), true )
				? $this->args->filter_type : 'except';

		$is_wc_shop    = class_exists( 'woocommerce' ) && is_shop();
		$is_posts_page = $wp_query->is_posts_page;

		if ( ! $is_wc_shop && ! $is_posts_page && ( ! isset( $post ) || ! ( $post instanceof WP_Post ) || 'page' !== $post->post_type || ! is_page() ) ) {
			return false;
		}
		if ( empty( $pages ) ) {
			if ( 'except' === $filter_type ) {
				$all = true;
			} else {
				$none = true;
			}
		}
		if ( $none ) {
			return false;
		}

		if ( $is_wc_shop ) {
			$page_id = wc_get_page_id( 'shop' );
		} elseif ( $is_posts_page ) {
			$page_id = get_option( 'page_for_posts' );
		} else {
			$page_id = $post->ID;
		}
		$is_selected_page = in_array( (string) $page_id, $pages, true );
		switch ( $filter_type ) {
			case 'only':
				return $all || $is_selected_page;

			case 'except':
			default:
				return $all || ! $is_selected_page;
		}
	}

}