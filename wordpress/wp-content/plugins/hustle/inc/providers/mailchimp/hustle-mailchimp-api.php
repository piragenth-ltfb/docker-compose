<?php
/**
 * Mailchimp API
 *
 * @class Hustle_Mailchimp_Api
 **/
class Hustle_Mailchimp_Api {

	private $_api_key;
	private $_data_center;
	private $_user;

	/*
	The <dc> part of the URL corresponds to the data center for your account. For example, if the last part of your Mailchimp API key is us6, all API endpoints for your account are available at https://us6.api.mailchimp.com/3.0/.
	*/
	private $_endpoint = 'https://<dc>.api.mailchimp.com/3.0/';

	/**
	 * Constructs class with required data
	 *
	 * Hustle_Mailchimp_Api constructor.
	 *
	 * @param $api_key
	 */
	public function __construct( $api_key, $data_center ) {
		$this->_api_key     = $api_key;
		$this->_data_center = $data_center;
		$this->_endpoint    = str_replace( '<dc>', $data_center, $this->_endpoint );
		$this->_user        = wp_get_current_user()->display_name;
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
				'Authorization' => 'apikey ' . $this->_api_key,
				'Content-Type'  => 'application/json;charset=utf-8',
				// 'X-Trigger-Error' => 'APIKeyMissing',
			),
		);

		if ( 'GET' === $verb ) {
			$url .= ( '?' . http_build_query( $args ) );
		} elseif ( ! empty( $args['body'] ) ) {
			$_args['body'] = wp_json_encode( $args['body'] );
		}

		$res = wp_remote_request( $url, $_args );

		$utils                      = Hustle_Provider_Utils::get_instance();
		$utils->_last_url_request   = $url;
		$utils->_last_data_received = $res;
		$utils->_last_data_sent     = $_args;

		if ( ! is_wp_error( $res ) && is_array( $res ) ) {
			if ( $res['response']['code'] <= 204 ) {
				return json_decode( wp_remote_retrieve_body( $res ) );
			}

			$err = new WP_Error();
			$err->add( $res['response']['code'], $res['response']['message'], $res['body'] );
			return $err;
		}

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
	 * Sends rest GET request
	 *
	 * @param $action
	 * @param array  $args
	 * @return array|mixed|object|WP_Error
	 */
	private function _delete( $action, $args = array() ) {
		return $this->_request( $action, 'DELETE', $args );
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
	  * Sends rest PUT request
	  *
	  * @param $action
	  * @param array  $args
	  * @return array|mixed|object|WP_Error
	  */
	private function _put( $action, $args = array() ) {
		return $this->_request( $action, 'PUT', $args );
	}

	 /**
	  * Sends rest PATCH request
	  *
	  * @param $action
	  * @param array  $args
	  * @return array|mixed|object|WP_Error
	  */
	private function _patch( $action, $args = array() ) {
		return $this->_request( $action, 'PATCH', $args );
	}

	/**
	 * Get User Info for the current API KEY
	 *
	 * @param $fields
	 * @return array|mixed|object|WP_Error
	 */
	public function get_info( $fields = array() ) {
		if ( empty( $fields ) ) {
			$fields = array( 'account_id', 'account_name', 'email' );
		}

		return $this->_request(
			'',
			'GET',
			array(
				'fields' => implode( ',', $fields ),
			)
		);
	}

	/**
	 * Gets all the lists
	 *
	 * @param $count - current total lists to show
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function get_lists( $offset = 50, $count = 10 ) {
		return $this->_get(
			'lists',
			array(
				'user'   => $this->_user . ':' . $this->_api_key,
				'offset' => $offset,
				'count'  => $count,
			)
		);
	}

	/**
	 * Gets all the groups under a list
	 *
	 * @param $list_id
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function get_interest_categories( $list_id, $total = 10 ) {
		return $this->_get(
			'lists/' . $list_id . '/interest-categories',
			array(
				'user'  => $this->_user . ':' . $this->_api_key,
				'count' => $total,
			)
		);
	}

	/**
	 * Gets all the GDPR fields under a list
	 *
	 * @param $list_id
	 *
	 * @return array
	 */
	public function get_gdpr_fields( $list_id ) {
		$gdpr_fieds = array();
		$members    = $this->get_members( $list_id );
		if ( ! $members ) {
			$email = 'dummy@incsub.com';
			$args  = array(
				'email_address' => $email,
				'status'        => 'unsubscribed',
			);
			$this->subscribe( $list_id, $args );
			$members = $this->get_members( $list_id );
			$this->delete_email( $list_id, $email );
		}

		if ( empty( $members ) || ! is_array( $members ) || empty( $members[0]->marketing_permissions ) || ! is_array( $members[0]->marketing_permissions ) ) {
			return $gdpr_fieds;
		}

		foreach ( $members[0]->marketing_permissions as $value ) {
			if ( ! isset( $value->marketing_permission_id ) || ! isset( $value->text ) ) {
				continue;
			}
			$gdpr_fieds[ $value->marketing_permission_id ] = $value->text;
		}

		return $gdpr_fieds;
	}

	/**
	 * Get members by list ID
	 *
	 * @param string $list_id
	 * @return array
	 */
	private function get_members( $list_id ) {
		$data = $this->_get(
			'lists/' . $list_id . '/members',
			array(
				'user' => $this->_user . ':' . $this->_api_key,
			)
		);

		return $data && is_object( $data ) && ! empty( $data->members ) ? $data->members : array();
	}

	/**
	 * Gets all the interests under a group list
	 *
	 * @param $list_id
	 * @param $category_id
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function get_interests( $list_id, $category_id, $total = 10 ) {
		return $this->_get(
			'lists/' . $list_id . '/interest-categories/' . $category_id . '/interests',
			array(
				'user'  => $this->_user . ':' . $this->_api_key,
				'count' => $total,
			)
		);
	}

	/**
	 * Gets all the tags/static segments on a list
	 *
	 * @param $list_id
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function get_tags( $list_id ) {
		return $this->_get(
			'lists/' . $list_id . '/segments',
			array(
				'count' => 1000,
				'user'  => $this->_user . ':' . $this->_api_key,
				'type'  => 'static',
			)
		);
	}

	/**
	 * Check member email address if already existing
	 *
	 * @param $list_id
	 * @param $email
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function check_email( $list_id, $email ) {
		$md5_email = md5( strtolower( $email ) );
		return $this->_get(
			'lists/' . $list_id . '/members/' . $md5_email,
			array(
				'user' => $this->_user . ':' . $this->_api_key,
			)
		);
	}

	/**
	 * Delete detail of member
	 *
	 * @param $list_id
	 * @param $email
	 *
	 * @return array|mixed|object|WP_Error
	 */

	public function delete_email( $list_id, $email ) {
		$md5_email = md5( strtolower( $email ) );
		$this->update_subscription_patch( $list_id, $email, array( 'status' => 'unsubscribed' ) );
		return $this->_delete( 'lists/' . $list_id . '/members/' . $md5_email );
	}

	/**
	 * Add custom field for list
	 *
	 * @param $list_id
	 * @param $field_data
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function add_custom_field( $list_id, $field_data ) {
		return $this->_post(
			'lists/' . $list_id . '/merge-fields',
			array(
				'body' => $field_data,
			)
		);
	}

	/**
	 * Get custom fields for list
	 *
	 * @param $list_id
	 * @param $count
	 * @param $offset
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function get_custom_fields( $list_id, $count = PHP_INT_MAX, $offset = 0 ) {
		return $this->_get(
			'lists/' . $list_id . '/merge-fields',
			array(
				'user'   => $this->_user . ':' . $this->_api_key,
				'offset' => $offset,
				'count'  => $count,
			)
		);
	}

	/**
	 * Add new subscriber
	 *
	 * @param $list_id
	 * @param $data
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( $list_id, $data ) {
		$res = $this->_post(
			'lists/' . $list_id . '/members',
			array(
				'body' => $data,
			)
		);

		if ( ! is_wp_error( $res ) ) {
			return $res;
			// return __("Successful subscription", 'hustle' );
		} else {
			if ( strpos( $res->get_error_data(), '"Forgotten Email Not Subscribed"' ) ) {
				$error  = __( "This contact was previously removed from this list via Mailchimp dashboard. To rejoin, they'll need to sign up using a Mailchimp native form.", 'hustle' );
				$error .= ' ' . __( 'Subscriber email: ', 'hustle' ) . $data['email_address'];
			} else {
				$error      = implode( ', ', $res->get_error_messages() );
				$error     .= __( 'Something went wrong.', 'hustle' );
				$error_data = $res->get_error_data();
				if ( ! empty( $error_data ) ) {
					$error .= ' ' . $error_data;
				}
			}
			throw new Exception( $error );
		}
	}

	/**
	 * Update subscription
	 *
	 * @param $list_id - the list id
	 * @param $email - the email
	 * @param $data - array
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function update_subscription( $list_id, $email, $data ) {
		$md5_email = md5( strtolower( $email ) );
		$res       = $this->_put(
			'lists/' . $list_id . '/members/' . $md5_email,
			array(
				'body' => $data,
			)
		);
		$error     = __( 'This email address has already subscribed', 'hustle' );

		if ( ! is_wp_error( $res ) ) {
			// returns object on success @since 4.0.2 as we need it for GDPR
			return $res;

			return __( 'You have been added to the new group', 'hustle' );
			throw new Exception( $error );
		}
	}

	/**
	 * Update subscription
	 *
	 * @param $list_id - the list id
	 * @param $email - the email
	 * @param $data - array
	 *
	 * @return array|mixed|object|WP_Error
	 */
	public function update_subscription_patch( $list_id, $email, $data ) {
		$md5_email = md5( strtolower( $email ) );
		if ( ! empty( $data['tags'] ) && is_array( $data['tags'] ) ) {
			foreach ( $data['tags'] as $tag_id ) {
				$res = $this->_post(
					'lists/' . $list_id . '/segments/' . $tag_id . '/members/',
					array(
						'body' => array(
							'email_address' => strtolower( $email ),
						),
					)
				);
			}
			unset( $data['tags'] );
		}
		$res = $this->_patch(
			'lists/' . $list_id . '/members/' . $md5_email,
			array(
				'body' => $data,
			)
		);

		$error = __( "Couldn't update the user", 'hustle' );
		if ( ! is_wp_error( $res ) ) {
			return __( 'User updated', 'hustle' );
		} else {
			throw new Exception( $error );
		}
	}

}