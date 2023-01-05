<?php
/**
 * Opt_In_Condition_On_Browser class.
 *
 * @package Hustle
 * @since 4.1.0
 */

/**
 * Opt_In_Condition_On_Browser.
 * Handles the visitor's browser.
 *
 * @since 4.1.0
 */
class Opt_In_Condition_On_Browser extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since 4.1.0
	 */
	public function is_allowed() {

		if ( isset( $this->args->browsers ) ) {

			if ( 'except' === $this->args->filter_type ) {
				return ! ( $this->verify_browser( $this->args->browsers ) );
			} elseif ( 'only' === $this->args->filter_type ) {
				return $this->verify_browser( $this->args->browsers );
			}
		}

		return false;
	}

	/**
	 * Checkes the user agent for known browsers
	 *
	 * @since 4.1.0
	 * @param  array $browsers List of browsers.
	 * @return bool
	 */
	public function verify_browser( $browsers ) {
		$browser = $this->get_current_user_agent();
		return in_array( $browser, (array) $browsers, true );
	}

	/**
	 * Returns the current browser's name based on the requests' user agent.
	 *
	 * @since 4.1.0
	 * @return string
	 * @throws Exception The current browser name based on the http user agent.
	 */
	private function get_current_user_agent() {
		$browser = 'other';
		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$unlashed_agent = wp_unslash( $_SERVER['HTTP_USER_AGENT'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$user_agent     = filter_var( $unlashed_agent, FILTER_SANITIZE_STRING );
		} else {
			$user_agent = false;
		}

		try {

			if ( ! $user_agent ) {
				throw new Exception( $browser );
			}

			// The order matters.
			if ( strpos( $user_agent, 'Opera' ) || strpos( $user_agent, 'OPR/' ) ) {
				throw new Exception( 'opera' );
			}

			if ( strpos( $user_agent, 'Edg' ) ) {
				throw new Exception( 'edge' );
			}

			if ( strpos( $user_agent, 'Firefox' ) ) {
				throw new Exception( 'firefox' );
			}

			if ( strpos( $user_agent, 'MSIE' ) || strpos( $user_agent, 'Trident/7' ) ) {
				throw new Exception( 'MSIE' );
			}

			if ( strpos( $user_agent, 'Chrome' ) ) {
				throw new Exception( 'chrome' );

			} else {

				// Chrome for iOS doesn't display 'Chrome' in the UA.
				preg_match_all( '/^.*(iPhone|iPad).*(OS\s[0-9]).*(CriOS|Version)\/[.0-9]*\sMobile.*$/', $user_agent, $matches );

				// TODO: watch out for old ios versions.
				if ( ! empty( $matches ) && ! empty( $matches[3] ) ) {
					if ( 'CriOS' === $matches[3][0] ) {
						throw new Exception( 'chrome' );
					} else {
						throw new Exception( 'safari' );
					}
				}
			}

			if ( strpos( $user_agent, 'Safari' ) ) {
				throw new Exception( 'safari' );
			}
		} catch ( Exception $e ) {
			$browser = $e->getMessage();
		}

		/**
		 * Filter the current browser based on the user agent
		 *
		 * @since 4.1.0
		 * @param string $browser    Detected browser.
		 * @param string $user_agent Passed user agent.
		 */
		return apply_filters( 'hustle_user_agent_visibility_verify', $browser, $user_agent );
	}
}