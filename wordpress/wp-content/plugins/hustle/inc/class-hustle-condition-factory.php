<?php
/**
 * File for Hustle_Condition_Factory class.
 *
 * @package Hustle
 * @since unknwon
 */

/**
 * Factory to instantiate display conditions
 *
 * Class Hustle_Condition_Factory
 */
class Hustle_Condition_Factory {

	/**
	 * Callback to use with preg_replace_callback in self::build
	 *
	 * @param array $matches Matches.
	 * @return string
	 */
	private static function preg_replace_callback( $matches ) {
		return $matches[1] . ucfirst( $matches[2] );
	}

	/**
	 * Instantiates and returns instance of Opt_In_Condition_Abstract
	 *
	 * @param string $condition_key Condition slug.
	 * @param array  $args Arguments for the condition.
	 * @return Opt_In_Condition_Abstract
	 */
	public static function build( $condition_key, $args ) {
		$class = 'Opt_In_Condition_' . preg_replace_callback(
			'/(\\_)([A-Za-z]+)/ui',
			array( __CLASS__, 'preg_replace_callback' ),
			ucfirst( $condition_key )
		);

		return ( class_exists( $class ) )
			? new $class( $args )
			: false;
	}
}