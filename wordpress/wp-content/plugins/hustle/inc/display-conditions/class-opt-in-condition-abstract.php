<?php
/**
 * Opt_In_Condition_Abstract class.
 *
 * @package Hustle
 * @since unwknown
 */

/**
 * Opt_In_Condition_Abstract.
 * The abstract class for all the visibility conditions.
 *
 * @since unknown
 */
abstract class Opt_In_Condition_Abstract {

	/**
	 * Current module type.
	 *
	 * @since unkwnon
	 * @var string popup|slidein|embedded|social_sharing
	 */
	protected $module_type;

	/**
	 * Arguments for the condition.
	 *
	 * @since unkwnon
	 * @var array
	 */
	protected $args;

	/**
	 * Instance of Opt_In_Condition_Utils
	 *
	 * @since unkwnon
	 * @var Opt_In_Utils
	 */
	private $utils;

	/**
	 * Instance of
	 *
	 * @since unkwnon
	 * @var Opt_In_Geo
	 */
	private $geo;

	/**
	 * Hustle module
	 *
	 * @since unkwnon
	 * @var Hustle_Model
	 */
	public $module;

	/**
	 * Class constructor.
	 *
	 * @since unkwnon
	 * @param array $args Arguments for the condition.
	 */
	public function __construct( $args ) {
		$this->args = (object) $args;
	}

	/**
	 * Instanctiates and returns Opt_In_Condition_Utils
	 *
	 * @since unkwnon
	 * @return Opt_In_Utils
	 */
	public function utils() {
		if ( empty( $this->utils ) ) {
			$this->utils = new Opt_In_Utils();
		}

		return $this->utils;
	}

	/**
	 * Sets optin type for the condition.
	 *
	 * @since unkwnon
	 * @param string $module_type popup|slidein|embedded|social_sharing.
	 */
	public function set_type( $module_type ) {
		$this->module_type = $module_type;
	}

	/**
	 * Returns whether the condition was met.
	 *
	 * @since unkwnon
	 * @return boolean
	 */
	abstract public function is_allowed();
}