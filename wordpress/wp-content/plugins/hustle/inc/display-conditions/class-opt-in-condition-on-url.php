<?php
/**
 * Opt_In_Condition_On_Url class.
 *
 * @package Hustle
 * @since unknown
 */

/**
 * Opt_In_Condition_On_Url.
 * Handles the currnet URL.
 *
 * @since unknown
 */
class Opt_In_Condition_On_Url extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknown
	 */
	public function is_allowed() {
		if ( ! isset( $this->args->urls ) || ! isset( $this->args->filter_type ) ) {
			return false;
		}

		$is_url = $this->check_url( preg_split( '/\r\n|\r|\n/', $this->args->urls ) );

		if ( 'only' === $this->args->filter_type ) {
			return $is_url;
		} else {
			return ! $is_url;
		}
	}

	/**
	 * Tests if the $test_url matches any pattern defined in the $list.
	 *
	 * @since  4.3.1
	 * @param array $list List of URL-patterns to test against.
	 * @return bool
	 */
	private function check_url( $list ) {
		$response = false;

		$list = array_map( 'trim', (array) $list );
		if ( empty( $list ) ) {
			$response = true;

		} else {

			$test_url             = strtok( $this->get_current_actual_url( true ), '#' );
			$test_url_no_protocol = strtok( $this->get_current_actual_url(), '#' );

			foreach ( $list as $match ) {
				$match = strtok( $match, '#' );

				// We're using '%' at the beggining of the string in visibility conditions to differentiate
				// regular urls from regex. If it's not regex, use regular url check.
				if ( 0 !== strpos( $match, '%' ) ) {

					// Check if we're using a wildcard.
					if ( false === strpos( $match, '*' ) ) {
						$match = preg_quote( $match, null );
						if ( false === strpos( $match, '://' ) ) {
							$match = '\w+://' . $match;
						}
						if ( '/' !== substr( $match, -1 ) ) {
							$match .= '/?';
						} else {
							$match .= '?';
						}
						$exp = '#^' . $match . '$#i';

						$res = preg_match( $exp, $test_url );

					} else {
						// Check wildcards.
						$res = fnmatch( $match, $test_url_no_protocol );
					}
				} else {
					// Check for regex urls.
					$match = ltrim( $match, '%' );
					$exp   = $match;

					$res = preg_match( $exp, $test_url );
				}

				if ( $res ) {
					$response = true;
					break;
				}
			}
		}

		return $response;
	}

	/**
	 * Returns current actual url, the one seen on browser
	 *
	 * @since 4.3.1
	 *
	 * @param bool $with_protocol Whether to retrieve the URL with the protocol.
	 * @return string
	 */
	private function get_current_actual_url( $with_protocol = false ) {

		if ( ! isset( $_SERVER['HTTP_HOST'] ) || ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return '';
		}

		$host = filter_var( wp_unslash( $_SERVER['HTTP_HOST'] ), FILTER_SANITIZE_STRING );
		$uri  = filter_var( wp_unslash( $_SERVER['REQUEST_URI'] ), FILTER_SANITIZE_STRING );

		$url = $host . $uri;

		if ( ! $with_protocol ) {
			return $url;
		}
		return 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . $url;
	}
}