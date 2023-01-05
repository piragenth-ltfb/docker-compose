<?php
/**
 * GetResponse API implementation
 *
 * Class Hustle_Get_Response_Api
 */
class Hustle_Get_Response_Api {

	private $_api_key;

	private $_endpoint = 'https://api.getresponse.com/v3/';

	/**
	 * Constructs class with required data
	 *
	 * Hustle_Get_Response_Api constructor.
	 *
	 * @param $api_key
	 * @param array   $args
	 */
	public function __construct( $api_key, $args = array() ) {
		$this->_api_key = $api_key;

		if ( isset( $args['endpoint'] ) ) {
			$this->_endpoint = $args['endpoint'];
		}
	}


	/**
	 * Sends request to the endpoint url with the provided $action
	 *
	 * @param string $verb
	 * @param string $action rest action
	 * @param array  $args
	 * @return object|WP_Error
	 */
	private function _request( $action, $verb = 'GET', $args = array() ) {
		$url = trailingslashit( $this->_endpoint ) . $action;

		$_args = array(
			'method'  => $verb,
			'headers' => array(
				'X-Auth-Token' => 'api-key ' . $this->_api_key,
				'Content-Type' => 'application/json;charset=utf-8',
			),
		);

		if ( 'GET' === $verb ) {
			$url .= ( '?' . http_build_query( $args ) );

			if ( 'contacts' === $action ) {
				$url = rawurldecode( $url );
			}
		} else {
			$_args['body'] = wp_json_encode( $args['body'] );
		}

		$res = wp_remote_request( $url, $_args );

		// logging data
		$utils                      = Hustle_Provider_Utils::get_instance();
		$utils->_last_url_request   = $url;
		$utils->_last_data_sent     = $_args;
		$utils->_last_data_received = $res;

		if ( ! is_wp_error( $res ) && is_array( $res ) && $res['response']['code'] <= 204 ) {
			return json_decode( wp_remote_retrieve_body( $res ) );
		}

		if ( is_wp_error( $res ) ) {
			return $res;
		}

		$err      = new WP_Error();
		$message  = $res['response']['message'];
		$message .= wp_remote_retrieve_body( $res );

		$err->add( $res['response']['code'], $message );
		return $err;
	}

	/**
	 * Sends rest GET request
	 *
	 * @param $action
	 * @param array  $args
	 * @return array|mixed|object|WP_Error
	 */
	private function _get( $action, $args = array() ) {
		return $this->_request( $action, 'GET', $args );
	}

	/**
	 * Sends rest POST request
	 *
	 * @param $action
	 * @param array  $args
	 * @return array|mixed|object|WP_Error
	 */
	private function _post( $action, $args = array() ) {
		return $this->_request( $action, 'POST', $args );
	}

	/**
	 * Retrieves campaigns as array of objects
	 *
	 * @return array|WP_Error
	 */
	public function get_campaigns() {
		return $this->_get(
			'campaigns',
			array(
				'name'    => array( 'CONTAINS' => '%' ),
				'perPage' => 1000,
			)
		);
	}

	/**
	 * Retrieves contactID
	 *
	 * @since 4.0
	 * @param array $data
	 * @return string
	 */
	public function get_contact( $data ) {
		$res = $this->_get(
			'contacts',
			array(
				'query[email]'      => rawurlencode( $data['email'] ),
				'query[campaignId]' => $data['list_id'],
			)
		);
		$contact_id = '';

		if ( ! empty( $res[ 0 ] ) && ! empty( $res[ 0 ]->contactId ) ) {
			$contact_id = $res[ 0 ]->contactId;
		}

		return $contact_id;
	}

	/**
	 * Add new contact
	 *
	 * @param $data
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( $data ) {
		$url  = 'contacts';
		$args = array(
			'body' => $data,
		);
		$res  = $this->_post( $url, $args );

		return empty( $res ) ? __( 'Successful subscription', 'hustle' ) : $res;
	}

	/**
	 * Update contact
	 *
	 * @param string $contact_id Contact ID.
	 * @param array  $data New data.
	 * @return array|mixed|object|WP_Error
	 */
	public function update_contact( $contact_id, $data ) {
		$url  = 'contacts/' . $contact_id;
		$args = array(
			'body' => $data,
		);
		$res  = $this->_post( $url, $args );

		return empty( $res ) ? __( 'Successful subscription', 'hustle' ) : $res;
	}

	public function get_custom_fields() {
		$args = array( 'fields' => 'name, type' );
		$res  = $this->_get( 'custom-fields', $args );

		return $res;
	}

	/**
	 * Add custom field
	 *
	 * @param (array) $custom_field
	 **/
	public function add_custom_field( $custom_field ) {
		$url  = 'custom-fields';
		$args = array(
			'body' => $custom_field,
		);
		$res  = $this->_post( $url, $args );

		if ( is_wp_error( $res ) ) {
			return $res;
		}
		if ( ! empty( $res ) && ! empty( $res->customFieldId ) ) { // phpcs:ignore
			return $res->customFieldId; // phpcs:ignore
		}

		return false;
	}
}