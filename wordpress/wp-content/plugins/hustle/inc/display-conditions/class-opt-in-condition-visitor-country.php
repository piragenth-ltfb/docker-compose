<?php
/**
 * Opt_In_Condition_Visitor_Country.
 *
 * @package Hustle
 * @since unkwnown
 */

/**
 * Opt_In_Condition_Visitor_Country.
 * Condition based on the visitor's country.
 *
 * @since unkwnown
 */
class Opt_In_Condition_Visitor_Country extends Opt_In_Condition_Abstract {

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnown
	 */
	public function is_allowed() {

		if ( isset( $this->args->countries ) ) {

			if ( 'except' === $this->args->filter_type ) {
				return ! ( $this->test_country( $this->args->countries ) );
			} elseif ( 'only' === $this->args->filter_type ) {
				return $this->test_country( $this->args->countries );
			}
		}

		return false;
	}

	/**
	 * Checks if the current user IP belongs to one of the countries defined in
	 * country_codes list.
	 *
	 * @since 4.3.1
	 *
	 * @param  array $country_codes List of country codes.
	 * @return bool
	 */
	private function test_country( $country_codes ) {
		$response = true;

		$geo     = new Opt_In_Geo();
		$country = $geo->get_user_country();

		if ( 'XX' === $country ) {
			return $response;
		}

		return in_array( $country, (array) $country_codes, true );
	}
}