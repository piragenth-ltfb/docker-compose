<?php
/**
 * ActiveCampaign API implementation
 *
 * Class Hustle_Activecampaign_Api
 */
class Hustle_Activecampaign_Api {

	private $_url;
	private $_key;

	public function __construct( $url, $api_key ) {
		$this->_url = trailingslashit( $url ) . 'admin/api.php';
		$this->_key = $api_key;
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

		$utils = Hustle_Provider_Utils::get_instance();

		$url = $this->_url;

		$args = array_merge(
			array(
				'api_action' => $action,
				'api_key'    => $this->_key,
				'api_output' => 'json',
			),
			$args
		);

		$headers = array(
			'Content-Type' => 'application/x-www-form-urlencoded',
		);

		$_args = array(
			'method'  => $verb,
			'headers' => $headers,
		);

		$request_data = $args;

		if ( 'GET' === $verb ) {
			$url .= ( '?' . http_build_query( $args ) );
		} else {
			$_args['body'] = $args;
		}

		$utils->_last_url_request = $url;
		$utils->_last_data_sent   = $args;

		$res = wp_remote_request( $url, $_args );

		if ( is_wp_error( $res ) || ! $res ) {
			Opt_In_Utils::maybe_log( __METHOD__, $res );
			throw new Exception(
				__( 'Failed to process request, make sure your API URL and API KEY are correct and your server has internet connection.', 'hustle' )
			);
		}

		if ( isset( $res['response']['code'] ) ) {
			$status_code = $res['response']['code'];
			$msg         = '';
			if ( $status_code > 400 ) {
				if ( isset( $res['response']['message'] ) ) {
					$msg = $res['response']['message'];
				}

				if ( 404 === $status_code ) {
					throw new Exception( sprintf( __( 'Failed to processing request : %s', 'hustle' ), $msg ) );
				}

				throw new Exception( sprintf( __( 'Failed to processing request : %s', 'hustle' ), $msg ) );
			}
		}

		$body = wp_remote_retrieve_body( $res );

		// probably silent mode
		if ( ! empty( $body ) ) {
			$res = json_decode( $body, true );

			// auto validate
			if ( ! empty( $res ) ) {
				// list_field_view may return empty when there are no custom fields, so we shouldn't throw an exception
				if ( ( ! isset( $res['result_code'] ) || 1 !== $res['result_code'] ) && 'list_field_view' !== $action ) {
					$message = '';
					if ( isset( $res['result_message'] ) && ! empty( $res['result_message'] ) ) {
						$message = ' ' . $res['result_message'];
					}
					throw new Exception( sprintf( __( 'Failed to get ActiveCampaign data.%1$s', 'hustle' ), $message ) );
				}
			}
		}

		$utils->_last_data_received = $res;

		return $res;
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
	 * Retrieves lists as array of objects
	 *
	 * @return array|WP_Error
	 */
	public function get_lists() {

		try {
			$res = $this->_get(
				'list_list',
				array(
					'ids'           => 'all',
					'global_fields' => 0,
				)
			);

			$res2 = array();
			foreach ( $res as $key => $value ) {
				if ( is_numeric( $key ) ) {
					array_push( $res2, $value );
				}
			}
		} catch ( Exception $e ) {
			return array();
		}

		return $res2;
	}

	/**
	 * Get Account Detail
	 *
	 * @since 4.1
	 *
	 * @return array|mixed|object
	 */
	public function get_account() {

		return $this->_get( 'account_view' );
	}

	/**
	 * Retrieves Custom fields
	 *
	 * @return array|WP_Error
	 */
	public function get_custom_fields() {
		$res = $this->_get(
			'list_field_view',
			array(
				'ids' => 'all',
			)
		);

		if ( is_wp_error( $res ) || ! is_array( $res ) ) {
			return $res;
		}

		$custom_fields = array();

		if ( isset( $res['result_code'] ) && $res['result_code'] !== 0 ) {
			foreach ( $res as $key => $value ) {
				if ( is_numeric( $key ) ) {
					array_push( $custom_fields, $value );
				}
			}
		}

		return $custom_fields;
	}

	/**
	 * Get the existing forms
	 *
	 * @return array
	 */
	public function get_forms() {

		$res2 = array();
		try {
			$res = $this->_get( 'form_getforms' );

			foreach ( $res as $key => $value ) {
				if ( is_numeric( $key ) ) {
					array_push( $res2, $value );
				}
			}
		} catch ( Exception $e ) {
			return array();
		}

		return $res2;
	}

	/**
	 * Add new contact
	 *
	 * @param string              $id ID of the List or Form to which the user will be subscribed to
	 * @param array               $data with the subscription data
	 * @param Hustle_Module_Model $module
	 * @param array               $orig_data
	 * @param string              $sign_up_to Indicates if the subscription is done to a Form or to a List
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( $id, array $data, Hustle_Module_Model $module, $orig_data, $sign_up_to = 'list' ) {
		if ( false === $this->email_exist( $data['email'], $id, $sign_up_to ) ) {
			if ( 'list' === $sign_up_to ) {
				if ( (int) $id > 0 ) {
					$data['p']      = array( $id => $id );
					$data['status'] = array( $id => 1 );
					$res            = $this->_post( 'contact_sync', $data );
				} else {
					$res = $this->_post( 'contact_add', $data );
				}
			} else {
				$data['form'] = $id;
				$res          = $this->_post( 'contact_sync', $data );
			}

			if ( is_array( $res ) && isset( $res['result_code'] ) && 'SUCCESS' === $res['result_code'] ) {
				return __( 'Successful subscription', 'hustle' );
			} elseif ( empty( $res ) ) {
				return __( 'Successful subscription', 'hustle' );
			}
		} else {
			$res = $this->_post( 'contact_sync', $data );
		}

		if ( is_array( $res ) && isset( $res['result_code'] ) ) {
			if ( 'FAILED' === $res['result_code'] ) {
				$orig_data['error'] = ! empty( $res['result_message'] ) ? $res['result_message'] : __( 'Unexpected error occurred.', 'hustle' );
				$module->log_error( $orig_data );
				return $orig_data['error'];
			}
		}

		return $res;
	}

	/**
	 * Checks email in a list
	 */
	public function email_exist( $email, $id, $type = 'list' ) {

		try {

			$res = $this->_get( 'contact_view_email', array( 'email' => $email ) );

			// See if duplicate exists.
			if (
				! empty( $res )
				&& ! empty( $res['id'] )
				&& ! empty( $res['lists'] )
			) {
				if ( 'list' === $type ) {
					// Also make sure duplicate is in active list.
					foreach ( $res['lists'] as $response_list ) {
						if ( $response_list['listid'] === $id ) {
							// Duplicate exists.
							return true;
						}
					}
				} else {
					// Or active form if checking on a form
					if ( $id === $res['formid'] ) {
						return true;
					}
				}
			}
		} catch ( Exception $e ) {
			return false;
		}

		// Otherwise assume no duplicate.
		return false;
	}

	public function add_custom_fields( $custom_fields, $list, Hustle_Module_Model $module ) {
		if ( ! empty( $custom_fields ) ) {
			foreach ( $custom_fields as $key => $value ) {

				$field_data = array(
					'title'   => $value['label'],
					'type'    => $value['type'], // support for text and date field,
					'perstag' => $key,
					'p[0]'    => 0,
					'req'     => 0,
				);
				$res        = $this->_post( 'list_field_add', $field_data );
			}
		}
	}

}