<?php
/**
 * Mautic API Helper
 **/
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Hustle_Mautic_Api' ) ) :

	class Hustle_Mautic_Api {
		/**
		 * @var (string) Mautic installation URL
		 **/
		private $base_url;

		/**
		 * @var (string) The username use to login.
		 **/
		private $username;

		/**
		 * @var (string) The password use to authenticate.
		 **/
		private $password;

		/**
		 * @var (object) \MauticApi class instance
		 **/
		private $api;

		/**
		 * @var (object) \Mautic\Auth\ApiAuth class instance.
		 **/
		private $auth;

		/**
		 * Instances of mautic api
		 *
		 * @since 4.0.2
		 * @var array
		 */
		private static $_instances = array();

		/**
		 * Instances of mautic api
		 *
		 * @since 4.0.2
		 * @var array
		 */
		const HUSTLE_ADDON_MAUTIC_VERSION = '1.0';

		/**
		 * Hustle_Mautic_Api constructor.
		 *
		 * @param $base_url
		 * @param $username
		 * @param $password
		 */
		private function __construct( $username, $base_url, $password ) {
			// final check here
			if ( ! $base_url || ! $username || ! $password ) {
				throw new Exception( __( 'Missing required API Credentials', 'hustle' ) );
			}

			$this->base_url = $base_url;
			$this->username = $username;
			$this->password = $password;
		}

		/**
		 * Get singleton
		 *
		 * @since 4.0.2
		 *
		 * @param $base_url
		 * @param $username
		 * @param $password
		 *
		 * @return Hustle_Mautic_Api|null
		 */
		public static function get_instance( $username, $base_url = '', $password = '' ) {
			// initial check here
			if ( ! $username ) {
				throw new Exception( __( 'Missing required API Credentials', 'hustle' ) );
			}

			if ( ! isset( self::$_instances[ md5( $username ) ] ) ) {
				self::$_instances[ md5( $username ) ] = new self( $username, $base_url, $password );
			}
			return self::$_instances[ md5( $username ) ];
		}

		/**
		 * Add custom user agent on request
		 *
		 * @since 4.0.2
		 *
		 * @param $user_agent
		 *
		 * @return string
		 */
		public function filter_user_agent( $user_agent ) {
			$user_agent .= ' HustleMautic/' . self::HUSTLE_ADDON_MAUTIC_VERSION;

			/**
			 * Filter user agent to be used by mautic api
			 *
			 * @since 1.1
			 *
			 * @param string $user_agent current user agent
			 */
			$user_agent = apply_filters( 'hustle_addon_mautic_api_user_agent', $user_agent );

			return $user_agent;
		}

		/**
		 * HTTP Request
		 *
		 * @since 4.0.2
		 *
		 * @param string $verb
		 * @param        $url
		 * @param array  $args
		 *
		 * @param array  $headers
		 *
		 * @return array|mixed|object
		 * @throws Exception
		 */
		private function _request( $url, $verb = 'GET', $args = array() ) {
			// Adding extra user agent for wp remote request
			add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

			$url = esc_url( trailingslashit( $this->base_url ) . 'api/' . $url );

			/**
			 * Filter mautic url to be used on sending api request
			 *
			 * @since 4.0.2
			 *
			 * @param string $url  full url with scheme
			 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
			 * @param string $path requested path resource
			 * @param array  $args argument sent to this function
			 */
			$url = apply_filters( 'hustle_addon_mautic_api_url', $url, $verb, $args );

			$headers = array(
				'Authorization' => 'Basic ' . base64_encode( $this->username . ':' . $this->password ), //phpcs:ignore
				'Expect'        => '',
				'Accept'        => 'application/json',
				'Content-Type'  => 'application/json',
			);

			/**
			 * Filter mautic headers to sent on api request
			 *
			 * @since 4.0.2
			 *
			 * @param array  $headers
			 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
			 * @param string $url  full url with scheme
			 * @param array  $args argument sent to this function
			 */
			$headers = apply_filters( 'hustle_addon_mautic_api_request_headers', $headers, $verb, $url, $args );

			$_args = array(
				'method'  => $verb,
				'headers' => $headers,
			);

			$request_data = $args;
			ksort( $request_data );

			/**
			 * Filter mautic request data to be used on sending api request
			 *
			 * @since 4.0.2
			 *
			 * @param array  $request_data
			 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
			 * @param string $url  full url with scheme
			 */
			$args = apply_filters( 'hustle_addon_mautic_api_request_data', $request_data, $verb, $url );

			if ( 'GET' === $verb ) {
				$url .= ( '?' . http_build_query( $args ) );
			} else {
				$_args['body'] = wp_json_encode( $args );
			}

			/**
			 * Filter mautic wp_remote_request args
			 *
			 * @since 4.0.2
			 *
			 * @param array $_args
			 */
			$_args = apply_filters( 'hustle_addon_mautic_api_remote_request_args', $_args );

			$res = wp_remote_request( $url, $_args );

			// logging data
			$utils                      = Hustle_Provider_Utils::get_instance();
			$utils->_last_url_request   = $url;
			$utils->_last_data_sent     = $_args;
			$utils->_last_data_received = $res;

			$wp_response = $res;

			remove_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

			if ( is_wp_error( $res ) || ! $res ) {
				throw new Exception(
					__( 'Failed to process request, make sure your API URL is correct and your server has internet connection.', 'hustle' )
				);
			}

			if ( isset( $res['response']['code'] ) ) {
				$status_code = $res['response']['code'];
				$msg         = '';
				if ( $status_code >= 400 ) {

					if ( isset( $res['response']['message'] ) ) {
						$msg = $res['response']['message'];
					}

					$body_json = wp_remote_retrieve_body( $res );

					$res_json = json_decode( $body_json );
					if ( ! is_null( $res_json ) && is_object( $res_json ) && isset( $res_json->error ) && isset( $res_json->error->message ) ) {
						$msg = $res_json->error->message;
					}

					if ( 404 === $status_code ) {
						throw new Exception( sprintf( __( 'Failed to processing request : %s', 'hustle' ), $msg ) );
					}
				}
			}

			$body = wp_remote_retrieve_body( $res );

			// probably silent mode
			if ( ! empty( $body ) ) {
				$res = json_decode( $body );
				// fallback to parse args when fail
				if ( empty( $res ) ) {
					$res = wp_parse_args( $body, array() );

					// json-ify to make same format as json response (which is object not array)
					$res = wp_json_encode( $res );
					$res = json_decode( $res );
				}
			}

			$response = $res;
			/**
			 * Filter mautic api response returned to addon
			 *
			 * @since 4.0.2
			 *
			 * @param mixed          $response    original wp remote request response or decoded body if available
			 * @param string         $body        original content of http response's body
			 * @param array|WP_Error $wp_response original wp remote request response
			 */
			$res = apply_filters( 'hustle_addon_mautic_api_response', $response, $body, $wp_response );

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
		 * Sends rest PUT request
		 *
		 * @param $action
		 * @param array  $args
		 * @return array|mixed|object|WP_Error
		 */
		private function _patch( $action, $args = array() ) {
			return $this->_request( $action, 'PATCH', $args );
		}

		/**
		 * Retrieve the list of segments from Mautic installation.
		 **/
		public function get_segments( $offset = 0 ) {
			try {
				$segments = $this->_get( 'segments' );
				if ( ! empty( $segments ) && isset( $segments->lists ) ) {
					return $segments;
				}
			} catch ( Exception $e ) {
				return false;
			}
			return false;
		}

		/**
		 * Add contact to Mautic installation.
		 *
		 * @param (associative_array) $data         An array of contact details to add.
		 * @return Returns contact ID on success or WP_Error.
		 **/
		public function add_contact( $data ) {
			$err = new WP_Error();
			try {
				$res = $this->_post( 'contacts/new', $data );

				if ( $res && ! empty( $res->contact ) ) {
					$contact = $res->contact;
					return $contact->id;
				} else {
					$err->add( 'subscribe_error', __( 'Something went wrong. Please try again', 'hustle' ) );
				}
			} catch ( Exception $e ) {
				$error = $e->getMessage();
				$err   = new WP_Error();
				$err->add( 'subscribe_error', $error );
			}

			return $err;
		}

		/**
		 * Add contact to Mautic installation.
		 *
		 * @param (associative_array) $data         An array of contact details to add.
		 * @return Returns contact ID on success or WP_Error.
		 **/
		public function update_contact( $id, $data ) {
			$err = new WP_Error();
			try {
				$res = $this->_patch( 'contacts/' . $id . '/edit', $data );

				if ( $res && ! empty( $res->contact ) ) {
					$contact = $res->contact;
					return $contact->id;
				} else {
					$err->add( 'subscribe_error', __( 'Something went wrong. Please try again', 'hustle' ) );
				}
			} catch ( Exception $e ) {
				$error = $e->getMessage();
				$err   = new WP_Error();
				$err->add( 'subscribe_error', $error );
			}

			return $err;
		}

		/**
		 * Check if an email is already used.
		 *
		 * @param (string) $email
		 * @return Returns true if the given email already in use otherwise false.
		 **/
		public function email_exist( $email ) {
			$err = new WP_Error();
			try {
				$args       = array(
					'search' => $email,
					'limit'  => 1000,
				);
				$res        = $this->_get( 'contacts', $args );
				$contact_id = '';
				if ( $res && ! empty( $res->contacts ) ) {
					$contact_id = wp_list_pluck( $res->contacts, 'id' );
				}
				return ! empty( $contact_id ) ? key( $contact_id ) : false;
			} catch ( Exception $e ) {
				$err->add( 'server_error', $e->getMessage() );
			}

			return $err;
		}

		/**
		 * Add contact to segment list.
		 *
		 * @param (int) $segment_id
		 * @param (int) $contact_id
		 **/
		public function add_contact_to_segment( $segment_id, $contact_id ) {
			$err = new WP_Error();
			try {
				$add = $this->_post( 'segments/' . $segment_id . '/contact/' . $contact_id . '/add' );
				return $add;
			} catch ( Exception $e ) {
				$err->add( 'subscribe_error', $e->getMessage() );
			}
			return $err;
		}

		/**
		 * Get the list of available contact custom fields.
		 **/
		public function get_custom_fields() {
			$fields = $this->_get( 'contacts/list/fields' );
			return $fields;
		}

		/**
		 * Add custom contact field.
		 *
		 * @param (array) $field
		 **/
		public function add_custom_field( $field ) {
			$res = $this->_post( 'fields/contact/new', $field );
			return ! empty( $res ) && ! empty( $res->field );
		}
	}
endif;