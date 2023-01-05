<?php
/**
 * Opt_In_Condition_Source_Of_Arrival.
 *
 * @package Hustle
 * @since unknwon
 */

/**
 * Opt_In_Condition_Source_Of_Arrival.
 * Handles the source of arrival.
 *
 * @since unknwon
 */
class Opt_In_Condition_Source_Of_Arrival extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unknown
	 */
	public function is_allowed() {

		$is_allowed = null;

		// Check is source is external one.
		if ( ! $is_allowed && isset( $this->args->source_direct ) && 'true' === $this->args->source_direct ) {
			$is_allowed = $is_allowed || '' === $this->utils()->get_referrer();
		}

		// Check is source is external one.
		if ( ! $is_allowed && isset( $this->args->source_external ) && 'true' === $this->args->source_external ) {
			$internal = preg_replace( '#^https?://#', '', get_option( 'home' ) );
			// If not direct and not internal source.
			$is_allowed = $is_allowed || '' !== $this->utils()->get_referrer() && ! $this->utils()->test_referrer( $internal );
		}

		// Check is source is internal one.
		if ( ! $is_allowed && isset( $this->args->source_internal ) && 'true' === $this->args->source_internal ) {
			$internal   = preg_replace( '#^https?://#', '', get_option( 'home' ) );
			$is_allowed = $is_allowed || $this->utils()->test_referrer( $internal );
		}

		// Check is source is a search.
		if ( ! $is_allowed && isset( $this->args->source_search ) && 'true' === $this->args->source_search ) {
			$is_allowed = $is_allowed || $this->is_from_searchengine_ref();
		}

		// Check is source is not a search.
		if ( ! $is_allowed && isset( $this->args->source_not_search ) && 'true' === $this->args->source_not_search ) {
			$is_allowed = $is_allowed || ! $this->is_from_searchengine_ref();
		}

		if ( is_null( $is_allowed ) ) {
			return true;
		}

		return $is_allowed;
	}

	/**
	 * Tests if the current referrer is a search engine.
	 * Current referrer has to be specified in the URL param "thereferer".
	 *
	 * @return bool
	 */
	public function is_from_searchengine_ref() {
		$response = false;
		$referrer = $this->utils()->get_referrer();

		$patterns = array(
			'/search?',
			'.google.',
			'web.info.com',
			'search.',
			'del.icio.us/search',
			'delicious.com/search',
			'soso.com',
			'/search/',
			'.yahoo.',
			'.bing.',
		);

		foreach ( $patterns as $url ) {
			if ( false !== stripos( $referrer, $url ) ) {
				if ( '.google.' === $url ) {
					if ( $this->is_googlesearch( $referrer ) ) {
						$response = true;
					} else {
						$response = false;
					}
				} else {
					$response = true;
				}
				break;
			}
		}
		return $response;
	}

	/**
	 * Checks if the referrer is a google web-source.
	 * Courtesy Philipp Stracker.
	 *
	 * @param  string $referrer The referrer.
	 * @return bool
	 */
	public function is_googlesearch( $referrer = '' ) {
		$response = true;

		// Get the query strings and check its a web source.
		$qstring = wp_parse_url( $referrer, PHP_URL_QUERY );
		$qget    = array();

		foreach ( explode( '&', $qstring ) as $keyval ) {
			$kv = explode( '=', $keyval );
			if ( 2 === count( $kv ) ) {
				$qget[ trim( $kv[0] ) ] = trim( $kv[1] );
			}
		}

		if ( isset( $qget['source'] ) ) {
			$response = 'web' === $qget['source'];
		}

		return $response;
	}
}