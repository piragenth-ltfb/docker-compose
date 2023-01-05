<?php
/**
 * File for Hustle_Meta class.
 *
 * @package Hustle
 * @since unkown
 */

/**
 * Abstract Hustle_Meta class.
 * Extended by each handler of the modules' metas.
 *
 * When creating a new meta property:
 * -Booleans properties must be '0' and '1', as strings. Make sure they're also stored in this way when saving.
 */
abstract class Hustle_Meta {

	/**
	 * The meta's saved value.
	 *
	 * @since unknown
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Current module.
	 *
	 * @since unknown
	 * @var Hustle_Model
	 */
	protected $module;

	/**
	 * Hustle_Meta constructos.
	 *
	 * @param array        $data The saved meta's value.
	 * @param Hustle_Model $model Instance of the module this meta belongs to.
	 */
	public function __construct( array $data, Hustle_Model $model ) {
		$this->data   = $data;
		$this->module = $model;
	}

	/**
	 * Return an array with the default values.
	 * Must be overridden to return an array of default values
	 * without restricting them to static values.
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */
	abstract public function get_defaults();

	/**
	 * Returns the defaults for merging purposes.
	 * Allows handling unwanted overrides of the saved data.
	 *
	 * @since 4.4.1
	 *
	 * @return array
	 */
	protected function get_defaults_for_merge() {
		return $this->get_defaults();
	}

	/**
	 * Returns the meta value with the defaults as fallback.
	 * Useful for introducing new values without things exploding.
	 *
	 * @since unknown
	 *
	 * @todo Rename this method. It's inaccurate.
	 *
	 * @return array
	 */
	public function to_array() {
		$defaults = $this->get_defaults_for_merge();
		if ( $defaults ) {
			return array_replace_recursive( $defaults, $this->data );
		}

		return $this->data;
	}
}