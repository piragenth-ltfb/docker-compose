<?php

require_once dirname( __FILE__ ) . '/class-aweber-oauth.php';
require_once dirname( __FILE__ ) . '/class-aweber-oauth2.php';
require_once dirname( __FILE__ ) . '/class-wp-aweber-api-exception.php';
require_once dirname( __FILE__ ) . '/class-wp-aweber-api-not-found-exception.php';

/**
 * Class Hustle_Addon_Aweber_Wp_Api
 */
class Hustle_Addon_Aweber_Wp_Api extends Opt_In_WPMUDEV_API {

	/**
	 * Instances of aweber api
	 *
	 * @var array
	 */

	const OAUTH_VERSION               = '1.0';
	const HUSTLE_ADDON_AWEBER_VERSION = '1.0';
	const APIKEY                      = 'YObwFlhWC4sBoAkpmiY4vNsZKULJ8Yn9';
	const CONSUMER_SECRET             = 'r8ky51g2n8F2lnkuTdsLfxAVNpVEOLJdT4bWGNGz';

	const CLIENT_ID                 = '9253e5C3-28d6-48fd-c102-b92b8f250G1b';
	const REFERER                   = 'hustle_aweber_referer';
	const CURRENTPAGE               = 'hustle_aweber_current_page';
	const HUSTLE_AWEBER_API_VERSION = '1.0';
	const HUSTLE_REFRESH_TOKEN_SPAN = 7200;
	const REFRESH_TOKEN_URL         = 'https://auth.aweber.com/oauth2/token';

	private $_application_key         = '';
	private $_application_secret      = '';
	private $_oauth_token             = '';
	private $_oauth_token_secret      = '';
	private static $_instances        = array();
	private static $_access_token_url = 'https://auth.aweber.com/1.0/oauth/access_token';
	private static $_api_base_url     = 'https://api.aweber.com/1.0/';

	/**
	 * Hustle_Addon_Aweber_Wp_Api constructor.
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param $_application_key
	 * @param $_application_secret
	 *
	 * @param $_oauth_token
	 * @param $_oauth_token_secret
	 *
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 */
	public function __construct( $creds = null ) {
		$this->_application_key            = isset( $creds['consumer_key'] ) ? $creds['consumer_key'] : '';
		$this->_application_secret         = isset( $creds['consumer_secret'] ) ? $creds['consumer_secret'] : '';
		$this->_oauth_token                = isset( $creds['access_token'] ) ? $creds['access_token'] : '';
		$this->_oauth_token_secret         = isset( $creds['access_secret'] ) ? $creds['access_secret'] : '';
		$this->_oauth2_token_access_token  = isset( $creds['access_oauth2_token'] ) ? $creds['access_oauth2_token'] : '';
		$this->_oauth2_token_refresh_token = isset( $creds['access_oauth2_refresh'] ) ? $creds['access_oauth2_refresh'] : '';
	}

	/**
	 * Get singleton
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param $_application_key
	 * @param $_application_secret
	 *
	 * @param $_oauth_token
	 * @param $_oauth_token_secret
	 *
	 * @return Hustle_Addon_Aweber_Wp_Api|null
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 */
	public static function get_instance( $creds = null ) {
		$args         = implode( '|', $creds );
		$instance_key = md5( $args );
		if ( ! isset( self::$_instances[ $instance_key ] ) ) {
			self::$_instances[ $instance_key ] = new self( $creds );
		}

		return self::$_instances[ $instance_key ];
	}

	/**
	 * Add custom user agent on request
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param $user_agent
	 *
	 * @return string
	 */
	public function filter_user_agent( $user_agent ) {
		$user_agent .= ' HustleAweber/' . self::HUSTLE_ADDON_AWEBER_VERSION;

		/**
		 * Filter user agent to be used by aweber api
		 *
		 * @since 1.1
		 *
		 * @param string $user_agent current user agent
		 */
		$user_agent = apply_filters( 'hustle_addon_aweber_api_user_agent', $user_agent );

		return $user_agent;
	}

	/**
	 * HTTP Request
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param string $verb
	 * @param        $url
	 * @param array  $args
	 *
	 * @param array  $headers
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	private function _request( $url, $verb = 'GET', $args = array(), $headers = array() ) {
		// Adding extra user agent for wp remote request
		add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		/**
		 * Filter aweber url to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param string $url  full url with scheme
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
		 * @param string $path requested path resource
		 * @param array  $args argument sent to this function
		 */
		$url = apply_filters( 'hustle_addon_aweber_api_url', $url, $verb, $args );

		if ( $this->_oauth2_token_access_token ) {
			$headers = array(
				'Authorization' => 'Bearer ' . $this->_oauth2_token_access_token,
				'Accept'        => 'application/json',
				'Content-Type'  => 'application/json',
			);
		}

		/**
		 * Filter aweber headers to sent on api request
		 *
		 * @since 1.1
		 *
		 * @param array  $headers
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
		 * @param string $url  full url with scheme
		 * @param array  $args argument sent to this function
		 */
		$headers = apply_filters( 'hustle_addon_aweber_api_request_headers', $headers, $verb, $url, $args );

		$_args = array(
			'method'  => $verb,
			'headers' => $headers,
		);

		$request_data = $args;
		ksort( $request_data );

		/**
		 * Filter aweber request data to be used on sending api request
		 *
		 * @since 1.1
		 *
		 * @param array  $request_data
		 * @param string $verb `GET` `POST` `PUT` `DELETE` `PATCH`
		 * @param string $url  full url with scheme
		 */
		$args = apply_filters( 'hustle_addon_aweber_api_request_data', $request_data, $verb, $url );

		if ( ! $this->_oauth2_token_access_token ) {
			if ( 'PATCH' === $verb ) {
				$oauth_url_params = $this->get_prepared_request( $verb, $url, array() );
				$url             .= ( '?' . http_build_query( $oauth_url_params ) );
				$_args['body']    = wp_json_encode( $args );
			} else {
				// WARNING: If not being sent as json, non-primitive items in data must be json serialized in GET and POST.
				foreach ( $args as $key => $value ) {
					if ( is_array( $value ) ) {
						$args[ $key ] = wp_json_encode( $value );
					}
				}
				if ( 'POST' === $verb ) {
					$_args['body'] = $this->get_prepared_request( $verb, $url, $args );
				} else {
					$oauth_url_params = $this->get_prepared_request( $verb, $url, $args );
					$url             .= ( '?' . http_build_query( $oauth_url_params ) );
				}
			}
		} else {
			if ( 'PATCH' === $verb ) {
				$_args['body'] = wp_json_encode( $args );
			} else {
				if ( 'POST' === $verb ) {
					$_args['body'] = json_encode( $args );
				} else {
					$url .= ( '?' . http_build_query( $args ) );
				}
			}
		}

		/**
		 * Filter aweber wp_remote_request args
		 *
		 * @since 1.1
		 *
		 * @param array $_args
		 */
		$_args = apply_filters( 'hustle_addon_aweber_api_remote_request_args', $_args );

		$res = wp_remote_request( $url, $_args );

		// logging data
		$utils                      = Hustle_Provider_Utils::get_instance();
		$utils->_last_url_request   = $url;
		$utils->_last_data_sent     = $_args;
		$utils->_last_data_received = $res;

		$wp_response = $res;

		remove_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		if ( is_wp_error( $res ) || ! $res ) {
			throw new Hustle_Addon_Aweber_Wp_Api_Exception(
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
					throw new Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception( sprintf( __( 'Failed processing the request : %s', 'hustle' ), $msg ) );
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
		 * Filter aweber api response returned to addon
		 *
		 * @since 1.1
		 *
		 * @param mixed          $response    original wp remote request response or decoded body if available
		 * @param string         $body        original content of http response's body
		 * @param array|WP_Error $wp_response original wp remote request response
		 */
		$res = apply_filters( 'hustle_addon_aweber_api_response', $response, $body, $wp_response );

		return $res;
	}

	/**
	 * Compose redirect_uri to use on request argument.
	 * The redirect uri must be constant and should not be change per request.
	 *
	 * @return string
	 */
	public function get_redirect_uri() {
		return $this->_get_redirect_uri(
			'aweber',
			'authorize',
			array( 'client_id' => self::CLIENT_ID )
		);
	}

	/**
	 * Get Oauth Request data of AWeber that need to be send on API Request
	 *
	 * @since 1.0 Aweber Addon
	 * @return array
	 */
	public function get_oauth_request_data() {
		$timestamp          = time();
		$oauth_request_data = array(
			'oauth_token'            => $this->get_oauth_token(),
			'oauth_consumer_key'     => $this->_application_key,
			'oauth_version'          => self::OAUTH_VERSION,
			'oauth_timestamp'        => $timestamp,
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_nonce'            => Hustle_Addon_Aweber_Oauth::generate_oauth_nonce( $timestamp ),
		);

		/**
		 * Filter required Oauth Request data of AWeber that need to be send on API Request
		 *
		 * @since 1.3
		 *
		 * @param array $oauth_request_data default oauth request data
		 * @param int   $timestamp          current timestamp for future reference
		 */
		$oauth_request_data = apply_filters( 'hustle_addon_aweber_oauth_request_data', $oauth_request_data, $timestamp );

		return $oauth_request_data;
	}

	/**
	 * Sign Aweber API request
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param $method
	 * @param $url
	 * @param $data
	 *
	 * @return mixed
	 */
	public function get_signed_request( $method, $url, $data ) {
		$application_secret = $this->_application_secret;
		$oauth_token_secret = $this->get_oauth_token_secret();

		$base                    = Hustle_Addon_Aweber_Oauth::create_signature_base( $method, $url, $data );
		$key                     = Hustle_Addon_Aweber_Oauth::create_signature_key( $application_secret, $oauth_token_secret );
		$data['oauth_signature'] = Hustle_Addon_Aweber_Oauth::create_signature( $base, $key );
		$signed_request          = $data;

		/**
		 * Filter signed request
		 *
		 * @since 1.3
		 *
		 * @param array  $signed_request
		 * @param string $method
		 * @param string $url
		 * @param array  $data
		 * @param string $application_secret
		 * @param string $oauth_token_secret
		 */
		$signed_request = apply_filters( 'hustle_addon_aweber_oauth_signed_request', $signed_request, $method, $url, $data, $application_secret, $oauth_token_secret );

		return $signed_request;
	}

	/**
	 * prepare Request
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param mixed $method HTTP method
	 * @param mixed $url    URL for the request
	 * @param mixed $data   The data to generate oauth data and be signed
	 *
	 * @return array
	 */
	public function get_prepared_request( $method, $url, $data ) {
		$oauth_data            = $this->get_oauth_request_data();
		$data                  = array_merge( $data, $oauth_data );
		$data                  = $this->get_signed_request( $method, $url, $data );
		$prepared_request_data = $data;

		/**
		 * Filter prepared request data, Oauth data added and signed
		 *
		 * @since 1.3
		 *
		 * @param array  $prepared_request_data
		 * @param string $method
		 * @param string $url
		 * @param array  $data
		 */
		$prepared_request_data = apply_filters( 'hustle_addon_aweber_oauth_prepared_request', $prepared_request_data, $method, $url, $data );

		return $prepared_request_data;
	}

	/**
	 * Get Access Token
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $oauth_verifier
	 * @param array $args
	 *
	 * @return object contains oauth_token and oauth_token_secret
	 *
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	// public function get_access_token( $oauth_verifier, $args = array() ) {
	// $default_args = array(
	// 'oauth_verifier' => $oauth_verifier,
	// );

	// $args = array_merge( $default_args, $args );

	// $access_tokens = $this->_request( 'POST', self::$_access_token_url, $args );

	// if ( ! is_object( $access_tokens ) ) {
	// throw new Hustle_Addon_Aweber_Wp_Api_Exception( __( 'Invalid access token', 'hustle' ) );
	// }

	// if ( ! isset( $access_tokens->oauth_token_secret ) || ! isset( $access_tokens->oauth_token ) ) {
	// throw new Hustle_Addon_Aweber_Wp_Api_Exception( __( 'Invalid access token', 'hustle' ) );
	// }

	// return $access_tokens;
	// }

	/**
	 * Get related accounts
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param array $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function get_accounts( $args = array() ) {
		$default_args = array();
		$args         = array_merge( $default_args, $args );
		return $this->_request( $this->get_api_url( 'accounts' ), 'GET', $args );
	}

	/**
	 * Get lists on an account
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param array      $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function get_account_lists( $account_id, $args = array() ) {
		$default_args = array();
		$args         = array_merge( $default_args, $args );

		return $this->_request( $this->get_api_url( 'accounts/' . rawurlencode( trim( $account_id ) ) . '/lists' ), 'GET', $args );
	}

	/**
	 * Get list on an account
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param array      $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function get_account_list( $account_id, $list_id, $args = array() ) {
		$default_args = array();
		$args         = array_merge( $default_args, $args );

		return $this->_request( $this->get_api_url( 'accounts/' . rawurlencode( trim( $account_id ) ) . '/lists/' . rawurlencode( trim( $list_id ) ) ), 'GET', $args );
	}

	/**
	 * Get Custom Fields on the list
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param       $list_id
	 * @param array      $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function get_account_list_custom_fields( $account_id, $list_id, $args = array() ) {
		$default_args = array();
		$args         = array_merge( $default_args, $args );

		return $this->_request(
			$this->get_api_url(
				'accounts/' .
				rawurlencode( trim( $account_id ) ) .
				'/lists/' .
				rawurlencode( trim( $list_id ) ) . '/custom_fields'
			),
			'GET',
			$args
		);
	}

	/**
	 * Create Custom Field on the list
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param       $list_id
	 * @param array      $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function add_custom_field( $account_id, $list_id, $args = array() ) {
		$default_args = array(
			'ws.op' => 'create',
			'name'  => '',
		);
		$args         = array_merge( $default_args, $args );

		if ( empty( $args['name'] ) ) {
			throw new Hustle_Addon_Aweber_Wp_Api_Exception( __( 'Name is required on add AWeber custom field.', 'hustle' ) );
		}

		$api_url = $this->get_api_url(
			'accounts/' .
			rawurlencode( trim( $account_id ) ) .
			'/lists/' .
			rawurlencode( trim( $list_id ) ) . '/custom_fields'
		);

		$res = $this->_request(
			$api_url,
			'POST',
			$args
		);

		return $res;
	}

	/**
	 * Add subscriber to account list
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param       $list_id
	 * @param array      $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function add_account_list_subscriber( $account_id, $list_id, $args = array() ) {
		$default_args = array(
			'ws.op' => 'create',
			'email' => '',
		);
		$args         = array_merge( $default_args, $args );

		if ( empty( $args['email'] ) ) {
			throw new Hustle_Addon_Aweber_Wp_Api_Exception( __( 'Email is required on add AWeber subscriber.', 'hustle' ) );
		}

		$api_url = $this->get_api_url(
			'accounts/' .
			rawurlencode( trim( $account_id ) ) .
			'/lists/' .
			rawurlencode( trim( $list_id ) ) . '/subscribers'
		);

		$res = $this->_request(
			$api_url,
			'POST',
			$args
		);

		return $res;
	}

	/**
	 * Update subscriber to account list
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param       $list_id
	 * @param       $subscriber_id
	 * @param array         $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function update_account_list_subscriber( $account_id, $list_id, $subscriber_id, $args = array() ) {
		$default_args = array(
			'email' => '',
		);
		$args         = array_merge( $default_args, $args );

		if ( empty( $args['email'] ) ) {
			throw new Hustle_Addon_Aweber_Wp_Api_Exception( __( 'Email is required on update AWeber subscriber.', 'hustle' ) );
		}

		$api_url = $this->get_api_url(
			'accounts/' .
			rawurlencode( trim( $account_id ) ) .
			'/lists/' .
			rawurlencode( trim( $list_id ) ) .
			'/subscribers/' .
			rawurlencode( trim( $subscriber_id ) )
		);

		$res = $this->_request(
			$api_url,
			'PATCH',
			$args,
			array(
				'Content-Type' => 'application/json',
			)
		);

		$utils                      = Hustle_Provider_Utils::get_instance();
		$utils->_last_data_received = $res;
		$utils->_last_url_request   = $api_url;
		$utils->_last_data_sent     = $args;

		return $res;
	}

	/**
	 * GET subscriber on account list
	 *
	 * @since 1.0 Aweber Addon
	 *
	 * @param       $account_id
	 * @param       $list_id
	 * @param array      $args
	 *
	 * @return array|mixed|object
	 * @throws Hustle_Addon_Aweber_Wp_Api_Exception
	 * @throws Hustle_Addon_Aweber_Wp_Api_Not_Found_Exception
	 */
	public function find_account_list_subscriber( $account_id, $list_id, $args = array() ) {
		$default_args = array(
			'ws.op' => 'find',
		);
		$args         = array_merge( $default_args, $args );

		$api_url = $this->get_api_url(
			'accounts/' .
			rawurlencode( trim( $account_id ) ) .
			'/lists/' .
			rawurlencode( trim( $list_id ) ) . '/subscribers'
		);

		$res = $this->_request(
			$api_url,
			'GET',
			$args,
			array(
				'Content-Type' => 'application/json',
			)
		);

		return $res;
	}

	/**
	 * Helper function to listen to request callback sent from WPMUDEV
	 */
	public function process_callback_request( $code, $migrate = false ) {

		$status = 'error';
		// Get the referer page that sent the request
		$referer      = get_option( self::REFERER );
		$current_page = get_option( self::CURRENTPAGE );

		if ( $code ) {
			$tokens = $this->get_access_token( $code, $migrate );
			if ( $tokens ) {
				return $tokens;
			}

			// if( 'success' === $status && true === $migrate  ){
			// $addon      = Hustle_Aweber::get_instance();
			// $referer    = add_query_arg( 'migration', true, $referer );
			// update_option( $addon->get_version_options_name(), $addon->get_version() );
			// }

			// if ( ! empty( $referer ) ) {
			// $referer = add_query_arg( 'status', $status, $referer );
			// wp_safe_redirect( $referer );
			// exit;
			// }
		}

		return false;
	}

	/**
	 * Generates authorization URL
	 *
	 * @param int $module_id
	 *
	 * @return string
	 */
	public function get_authorization_uri( $module_id = 0, $log_referrer = true, $page = 'hustle_integrations' ) {
		$oauth = Hustle_Addon_Aweber_Oauth2::boot( self::APIKEY, self::CONSUMER_SECRET, 'test' );
		if ( $log_referrer ) {

			/**
			* Store $referer to use after retrieving the access token
			*/
			$params = array(
				'page'   => $page,
				'action' => 'external-redirect',
				'slug'   => 'aweber',
				'nonce'  => wp_create_nonce( 'hustle_provider_external_redirect' ),
			);

			if ( ! empty( $module_id ) ) {
				$params['id']      = $module_id;
				$params['section'] = 'integrations';
			}
			$referer = add_query_arg( $params, admin_url( 'admin.php' ) );
			update_option( self::REFERER, $referer );
			update_option( self::CURRENTPAGE, $page );
		}

		return $oauth->getAuthorizationUrl();
	}

	/**
	 * Get Access token
	 *
	 * @param Array $args
	 */
	public function get_access_token( $code, $migrate = false ) {
		$oauth = Hustle_Addon_Aweber_Oauth2::boot( self::APIKEY, self::CONSUMER_SECRET, 'test' );

		try {
			$access_token = $oauth->getAccessToken( $code );

			// schedule token refresh
			// dont break the old schedule because of multiple instances
			if ( ! wp_next_scheduled( 'hustle_aweber_token_refresh' ) ) {
				wp_schedule_event( time(), 'hourly', 'hustle_aweber_token_refresh' );
			}
		} catch ( Exception $e ) {
			return false;
		}
		return $access_token;
	}

	/**
	 * Get stored token data.
	 *
	 * @return array|null
	 */
	public function get_auth_token() {
		$token_data = get_option( $this->option_token_name );
		return $token_data['access_token'];
	}


	/**
	 * Update token data.
	 *
	 * @param array $token
	 * @return void
	 */
	public function update_auth_token( array $token ) {
		$token[ $this->option_token_gen_time ] = time();
		update_option( $this->option_token_name, $token );
	}

	/**
	 * Update token data.
	 *
	 * @param array $token
	 * @return void
	 */
	public function validate_auth_token_lifespan( $multi_id ) {
		$this->_refresh_access_token( $multi_id );
	}

	/**
	 * Refresh access token
	 *
	 * @since 4.0.3
	 *
	 * @param String $email
	 * @param String $list
	 * @param Array  $custom_fields
	 */
	private function _refresh_access_token( $multi_id ) {
		$addon    = new Hustle_Aweber();
		$settings = $addon->get_settings_values( 'aweber' );
		// Adding extra user agent for wp remote request
		add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		// url to refresh auth token
		$url = self::REFRESH_TOKEN_URL;

		// headers to get auth token from refresh token
		$headers = array(
			'Accept'       => 'application/json',
			'Content-Type' => 'application/json',
		);

		$multi_id_settings = $settings[ $multi_id ];

		if ( ! isset( $multi_id_settings['expires_in'] ) ) {
			return;
		}

		$time = $multi_id_settings['expires_in'] - time();

		if ( $time >= 3000 ) {
			return;
		}

		$url .= ( '?' . http_build_query(
			array(
				'refresh_token' => $multi_id_settings['access_oauth2_refresh'],
				'grant_type'    => 'refresh_token',
				'client_id'     => self::APIKEY,
			)
		) );

		$_args = array(
			'headers' => $headers,
			'body'    => array(),
			'method'  => 'POST',
		);

		$res = wp_remote_request( $url, $_args );

		$has_error = false;
		if ( is_wp_error( $res ) ) {
			$has_error = true;
			$msg       = $res->get_error_code() . ' - ' . $res->get_error_message();

		} elseif ( isset( $res['response']['code'] ) ) {
			$status_code = $res['response']['code'];
			$msg         = '';
			if ( $status_code >= 400 ) {
				$has_error = true;

				if ( isset( $res['response']['message'] ) ) {
					$msg = $res['response']['message'];
				}

				$body_json = wp_remote_retrieve_body( $res );
				$res_json  = json_decode( $body_json );

				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				if ( ! is_null( $res_json ) && is_object( $res_json ) && isset( $res_json->Message ) ) {
					$msg = $res_json->Message; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				}
			}
		}

		if ( $has_error ) {
			Hustle_Provider_Utils::maybe_log( __METHOD__, $msg );
		} else {
			$body = wp_remote_retrieve_body( $res );
			// Probably silent mode.
			if ( ! empty( $body ) ) {
				$res = (array) json_decode( $body );

				$multi_id_settings['expires_in']            = time() + $res['expires_in'];
				$multi_id_settings['access_oauth2_token']   = $res['access_token'];
				$multi_id_settings['access_oauth2_refresh'] = $res['refresh_token'];

				$addon->save_multi_settings_values( $multi_id, $multi_id_settings );
			}
		}

		// update schedule after token has been updated.
		wp_clear_scheduled_hook( 'hustle_aweber_token_refresh' );
		wp_schedule_event( time(), 'hourly', 'hustle_aweber_token_refresh' );

		return true;
	}

	/**
	 * Get url for get access token
	 *
	 * @since 1.0 Aweber Addon
	 * @return string
	 */
	public function get_access_token_url() {
		$access_token_url = self::$_access_token_url;

		/**
		 * Filter access_token_url
		 *
		 * @since 1.3
		 *
		 * @param string $access_token_url
		 */
		$access_token_url = apply_filters( 'hustle_addon_aweber_oauth_access_token_url', $access_token_url );

		return $access_token_url;
	}

	/**
	 * Get API URL
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public function get_api_url( $path ) {
		$api_base_url = self::$_api_base_url;
		$api_url      = trailingslashit( $api_base_url ) . $path;

		/**
		 * Filter api_url to send request
		 *
		 * @since 1.3
		 *
		 * @param string $api_url
		 * @param string $api_base_url
		 * @param string $path
		 */
		$api_url = apply_filters( 'hustle_addon_aweber_oauth_api_url', $api_url, $api_base_url, $path );

		return $api_url;
	}

	/**
	 * Get Oauth Token
	 *
	 * @since 1.0 Aweber Addon
	 * @return string
	 */
	public function get_oauth_token() {
		return $this->_oauth_token;
	}

	/**
	 * Get Oauth Token Secret
	 *
	 * @since 1.0 Aweber Addon
	 * @return string
	 */
	public function get_oauth_token_secret() {
		return $this->_oauth_token_secret;
	}

	/**
	 * Filter corn schedule for refreshtoken
	 *
	 * @since 4.0.3
	 *
	 * @param Array $schedules
	 */
	public function aweber_cron_refresh_span( $schedules ) {

		$schedules['aweber_token_refresh_span'] = array(
			'interval' => self::HUSTLE_REFRESH_TOKEN_SPAN,
			'display'  => __( 'Every 2 hours', 'hustle' ),
		);

		return $schedules;
	}
}