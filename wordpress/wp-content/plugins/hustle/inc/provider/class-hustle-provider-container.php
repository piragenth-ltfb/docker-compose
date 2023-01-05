<?php
/**
 * Hustle_Provider_Container class.
 *
 * @package Hustle
 * @since 3.5.0
 */

/**
 * Class Hustle_Provider_Container
 *
 * @since 3.0.5
 */
class Hustle_Provider_Container implements ArrayAccess, Countable, Iterator {

	/**
	 * Providers array.
	 *
	 * @since 3.0.5
	 * @var Hustle_Provider_Abstract[]
	 */
	private $providers = array();

	/**
	 * Defines interface method.
	 *
	 * @since 3.0.5
	 * @param mixed $offset Passed offset.
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->providers[ $offset ] );
	}

	/**
	 * Defines interface method.
	 *
	 * @since 3.0.5
	 * @param mixed $offset Passed offset.
	 * @return Hustle_Provider_Abstract|mixed|null
	 */
	public function offsetGet( $offset ) {
		if ( isset( $this->providers[ $offset ] ) ) {
			return $this->providers[ $offset ];
		}

		return null;
	}

	/**
	 * Defines interface method.
	 *
	 * @since 3.0.5
	 * @param mixed $offset Passed offset.
	 * @param mixed $value Passed value.
	 */
	public function offsetSet( $offset, $value ) {
		$this->providers[ $offset ] = $value;
	}

	/**
	 * Defines interface method.
	 *
	 * @since 3.0.5
	 * @param mixed $offset Passed offset.
	 */
	public function offsetUnset( $offset ) {
		unset( $this->providers[ $offset ] );
	}

	/**
	 * Counts the elements of the object.
	 *
	 * @link  http://php.net/manual/en/countable.count.php
	 * @since 3.0.5
	 * @return int The custom count as an integer.
	 */
	public function count() {
		return count( $this->providers );
	}

	/**
	 * Gets All registered providers' slug.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function get_slugs() {
		return array_keys( $this->providers );
	}

	/**
	 * Groups the providers in array with their properties.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function to_grouped_array() {
		$providers = array();

		foreach ( $this->providers as $slug => $provider_members ) {
			// Force to offsetGet in case a hook is added.
			$provider = $this[ $slug ];

			$providers[ $provider->get_slug() ] = $provider->to_array();
		}

		return $providers;
	}

	/**
	 * Returns a list of the registered providers containing each provider's array of properties.
	 * The data included on the provider's array is defined in @see Hustle_Provider_Abstract.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function to_array() {
		$providers = array();
		foreach ( $this->providers as $slug => $provider_members ) {
			// Force to offsetGet: enable when needed in case a hook is added.
			$provider                           = $this[ $slug ];
			$providers[ $provider->get_slug() ] = $provider->to_array();
		}
		/**
		 * Sort elements by title
		 *
		 * @since 3.0.7
		 */
		uasort( $providers, array( $this, 'helper_sort_by_title' ) );
		return $providers;
	}

	/**
	 * Private helper to sort services by name.
	 *
	 * @since 3.0.7
	 *
	 * @param array $a First array to compare.
	 * @param array $b Second array to compare.
	 * @return integer sort order
	 */
	private function helper_sort_by_title( $a, $b ) {
		if ( ! isset( $a['title'] ) || ! isset( $b['title'] ) ) {
			return 0;
		}
		return strcasecmp( $a['title'], $b['title'] );
	}

	/**
	 * Return the current element
	 *
	 * @link  http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 4.0
	 */
	public function current() {
		return current( $this->providers );
	}

	/**
	 * Move forward to next element
	 *
	 * @link  http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 4.0
	 */
	public function next() {
		next( $this->providers );
	}

	/**
	 * Return the key of the current element
	 *
	 * @link  http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 4.0
	 */
	public function key() {
		return key( $this->providers );
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link  http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 * @since 4.0
	 */
	public function valid() {
		return key( $this->providers ) !== null;
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link  http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 4.0
	 */
	public function rewind() {
		reset( $this->providers );
	}
}