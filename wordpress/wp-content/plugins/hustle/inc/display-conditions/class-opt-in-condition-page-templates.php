<?php
/**
 * Opt_In_Condition_Page_Templates class.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_Page_Templates.
 * Handles the currnet page's template.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_Page_Templates extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {
		global $post;

		if ( ! isset( $post ) || ! ( $post instanceof WP_Post ) ) {
			return false;
		}
		if ( isset( $this->args->templates ) ) {
			$templates = (array) $this->args->templates;

			if ( 'except' === $this->args->filter_type ) {
				return ! in_array( get_page_template_slug( get_the_ID() ), $templates, true );
			} elseif ( 'only' === $this->args->filter_type ) {
				return in_array( get_page_template_slug( get_the_ID() ), $templates, true );
			}
		}

		return false;
	}
}