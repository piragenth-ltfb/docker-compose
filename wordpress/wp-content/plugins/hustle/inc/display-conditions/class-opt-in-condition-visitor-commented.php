<?php
/**
 * Opt_In_Condition_Visitor_Commented.
 *
 * @package Hustle
 * @since unkwnown
 */

/**
 * Opt_In_Condition_Visitor_Commented.
 * Condition based on whether a user has commented.
 *
 * @since unkwnown
 */
class Opt_In_Condition_Visitor_Commented extends Opt_In_Condition_Abstract {

	/**
	 * Whether the user has commented.
	 *
	 * @since 4.3.1
	 * @var bool
	 */
	private $has_commented;

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnown
	 */
	public function is_allowed() {

		if ( 'true' === $this->args->filter_type ) {
			return $this->has_user_commented();
		}

		return ! ( $this->has_user_commented() );
	}

	/**
	 * Checks if user has already commented
	 *
	 * @return bool|int
	 */
	private function has_user_commented() {
		if ( null === $this->has_commented ) {
			// Guests (and maybe logged in users) are tracked via a cookie.
			$this->has_commented = isset( $_COOKIE[ 'comment_author_' . COOKIEHASH ] ) ? 1 : 0;

			if ( ! $this->has_commented && is_user_logged_in() ) {
				// For logged-in users we can also check the database.
				$count = get_comments(
					array(
						'count'   => true,
						'user_id' => get_current_user_id(),
					)
				);

				$this->has_commented = $count > 0;
			}
		}
		return $this->has_commented;
	}
}