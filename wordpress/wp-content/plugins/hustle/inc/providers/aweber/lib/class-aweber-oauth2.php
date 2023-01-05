<?php

/**
 * Class Hustle_Addon_Constant_Contact_Oauth
 * Helpers for OAuth
 */
class Hustle_Addon_Aweber_Oauth2 {

	/**
	 * Oauth Client ID
	 *
	 * @var string
	 */
	public $client_id;

	/**
	 * Oauth Client Secret
	 *
	 * @var string
	 */
	public $client_secret;

	/**
	 * Redirect uri
	 *
	 * @var string
	 */
	public $redirect_uri;


	protected static $_instances = array();

	/**
	 * ConstactContact Oauth Version
	 *
	 * @var string
	 */
	const HUSTLE_AWEBER_OAUTH = '2.0';

	/**
	 * Constructor
	 */
	private function __construct( $client_id, $client_secret, $redirect_uri ) {
		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri  = $redirect_uri;
	}

	/**
	 * Get singleton
	 *
	 * @since 4.0.2
	 *
	 * @param string $api_key
	 *
	 * @return Hustle_Campaignmonitor|null
	 * @return Exception
	 */
	public static function boot( $client_id, $client_secret, $redirect_uri ) {
		if ( ! isset( self::$_instances[ md5( $client_secret ) ] ) ) {
			self::$_instances[ md5( $client_secret ) ] = new static( $client_id, $client_secret, $redirect_uri );
		}

		return self::$_instances[ md5( $client_secret ) ];
	}

	/**
	 * Get the URL at which the user can authenticate and authorize the requesting application
	 *
	 * @since 4.0.2
	 *
	 * @param boolean $server - Whether or not to use OAuth2 server flow, alternative is client flow
	 * @param string  $state - An optional value used by the client to maintain state between the request and callback.
	 *
	 * @return string $url - The url to send a user to, to grant access to their account
	 */
	public function getAuthorizationUrl( $server = true, $state = null ) {
		$params = array(
			'response_type'         => $this->_options( 'response_type_code' ),
			'client_id'             => $this->client_id,
			'scope'                 => implode( ' ', $this->_options( 'scopes' ) ),
			'code_challenge'        => $this->get_pkce(),
			'code_challenge_method' => 'S256',
			'redirect_uri'          => 'urn:ietf:wg:oauth:2.0:oob',
		);

		// add the state param if it was provided
		if ( null !== $state ) {
			$params['state'] = $state;
		}

		$url = $this->_options( 'authorization_endpoint' );
		$url = $url . '?' . http_build_query( $params, null, '&', \PHP_QUERY_RFC3986 );

		return $url;

	}

	/**
	 * Generate the PKCE hash
	 *
	 * @since 4.0.3
	 *
	 * @return string $hash - code verifier
	 */
	public function get_pkce() {
        return str_replace( array('=', '+', '/' ), array( '', '-', '_' ), base64_encode( hash( 'sha256', $this->_get_pkce_verifier(), true ) ) );  // phpcs:ignore -- ignore base64_encode warning
	}

	/**
	 * Generate the PKCE verifier
	 *
	 * @since 4.0.3
	 *
	 * @return string $code_verifier - code verifier
	 */
	private function _get_pkce_verifier() {

		$code_verifier = get_transient( 'hustle_aweber_code_verifier' );

		// if transient not found
		if ( empty( $code_verifier ) ) {
	        $code_verifier = str_replace( array( '=', '+', '/' ), array( '', '-', '_' ), base64_encode( random_bytes( 32 ) ) ); // phpcs:ignore -- ignore base64_encode warning
			set_transient( 'hustle_aweber_code_verifier', $code_verifier, DAY_IN_SECONDS );
		}
		return $code_verifier;

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
		$user_agent .= ' HustleAWEBERauth/' . self::HUSTLE_AWEBER_OAUTH;
		return $user_agent;
	}

	/**
	 * Obtain an access token
	 *
	 * @since 4.0.2
	 *
	 * @param string $code - code returned from Constant Contact after a user has granted access to their account
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getAccessToken( $code ) {
		$verifier = $this->_get_pkce_verifier();
		if ( empty( $verifier ) || empty( $code ) ) {
			return;
		}

		$params = array(
			'grant_type'    => $this->_options( 'authorization_code_grant_type' ),
			'code'          => $code,
			'redirect_uri'  => 'urn:ietf:wg:oauth:2.0:oob',
			'code_verifier' => $verifier,
			'client_id'     => $this->client_id,
		);

		$header = array(
			'Content-Type' => 'application/x-www-form-urlencoded',
		);

		$url = esc_url( $this->_options( 'base_url' ) . $this->_options( 'token_endpoint' ) );

		$response      = $this->post( $url, $header, $params );
		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( array_key_exists( 'error', $response_body ) ) {
			return false;
		}

		return $response_body;
	}

	/**
	 * Get an information about an access token
	 *
	 * @since 4.0.2
	 *
	 * @param string $accessToken - Constant Contact OAuth2 access token
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getTokenInfo( $access_token ) {
		$url      = $this->_options( 'base_url' ) . $this->_options( 'token_info' );
		$response = $this->post( $url, array(), 'access_token=' . $access_token );
		return json_decode( wp_remote_retrieve_body( $response ), true );
	}

	/**
	 * Make an Http POST request
	 *
	 * @since 4.0.2
	 *
	 * @param $url - request url
	 * @param array                            $headers - array of all http headers to send
	 * @param $data - data to send with request
	 *
	 * @return CurlResponse - The response body, http info, and error (if one exists)
	 */
	public function post( $url, array $headers = array(), $data = null ) {
		// Adding extra user agent for wp remote request
		add_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		$_args = array(
			'method'  => 'POST',
			'headers' => $headers,
		);

		$_args['body'] = $data;
		$response      = wp_remote_request( $url, $_args );
		remove_filter( 'http_headers_useragent', array( $this, 'filter_user_agent' ) );

		if ( is_wp_error( $response ) || ! $response ) {
			throw new Exception(
				__( 'Failed to process request, make sure your Webhook URL is correct and your server has internet connection.', 'hustle' )
			);
		}

		return $response;
	}

	/**
	 * Oauth Options
	 *
	 * @since 4.0.2
	 *
	 * @param string $key - key to fetch
	 *
	 * @return string
	 */
	private function _options( $key ) {
		$props = array(
			'auth' => array(
				'base_url'                      => 'https://auth.aweber.com/oauth2/',
				'response_type_code'            => 'code',
				'response_type_token'           => 'token',
				'authorization_code_grant_type' => 'authorization_code',
				'authorization_endpoint'        => 'https://auth.aweber.com/oauth2/authorize',
				'token_endpoint'                => 'token',
				'scopes'                        => array( 'account.read', 'list.read', 'list.write', 'subscriber.read', 'subscriber.write', 'subscriber.read-extended' ),
			),
		);

		return $props['auth'][ $key ];

	}

}