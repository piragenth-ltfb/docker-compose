<?php
if ( ! class_exists( 'Hustle_HubSpot_Api' ) ) :
	/**
	 * Class Hustle_HubSpot_Api
	 */
	class Hustle_HubSpot_Api extends Opt_In_WPMUDEV_API {
		const CLIENT_ID     = '5253e533-2dd2-48fd-b102-b92b8f250d1b';
		const CLIENT_SECRET = '2ed54e79-6ceb-4fc6-96d9-58b4f98e6bca';
		const HAPIKEY       = 'db9600bf-648c-476c-be42-6621d7a1f96a';
		const BASE_URL      = 'https://app.hubspot.com/';
		const API_URL       = 'https://api.hubapi.com/';

		const REFERER     = 'hustle_hubspot_referer';
		const CURRENTPAGE = 'hustle_hubspot_current_page';

		/**
		 * @var string
		 */
		private $option_name = 'hustle_opt-in-hubspot-token';

		/**
		 * @var bool
		 */
		public $is_error = false;

		/**
		 * @var string
		 */
		public $error_message;

		/**
		 * @var boolean
		 */
		public $sending = false;

		/**
		 * Hustle_HubSpot_Api constructor.
		 */
		public function __construct() {
			// Init request callback listener
			add_action( 'init', array( $this, 'process_callback_request' ) );

		}

		/**
		 * Helper function to listen to request callback sent from WPMUDEV
		 */
		public function process_callback_request() {
			if ( $this->validate_callback_request( 'hubspot' ) ) {

				$code   = filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING );
				$status = 'error';

				// Get the referer page that sent the request
				$referer      = get_option( self::REFERER );
				$current_page = get_option( self::CURRENTPAGE );
				if ( $code ) {
					if ( $this->get_access_token( array( 'code' => $code ) ) ) {
						$status = 'success';
					}
				}

				if ( ! empty( $referer ) ) {
					$referer = add_query_arg( 'status', $status, $referer );
					wp_safe_redirect( $referer );
					exit;
				}

				// Allow retry but don't log referrer
				$authorization_uri = $this->get_authorization_uri( false, false, $current_page );

				$this->wp_die( esc_attr__( 'HubSpot integration failed!', 'hustle' ), esc_url( $authorization_uri ), esc_url( $referer ) );
			}
		}

		/**
		 * @param string $key
		 *
		 * @return bool|mixed
		 */
		public function get_token( $key ) {
			$auth = $this->get_auth_token();

			if ( ! empty( $auth ) && ! empty( $auth[ $key ] ) ) {
				return $auth[ $key ]; }

			return false;
		}

		/**
		 * Compose redirect_uri to use on request argument.
		 * The redirect uri must be constant and should not be change per request.
		 *
		 * @return string
		 */
		public function get_redirect_uri() {
			return $this->_get_redirect_uri(
				'hubspot',
				'authorize',
				array( 'client_id' => self::CLIENT_ID )
			);
		}

		public function refresh_access_token() {
			$args = array(
				'grant_type'    => 'refresh_token',
				'refresh_token' => $this->get_token( 'refresh_token' ),
			);

			return $this->get_access_token( $args );
		}

		/**
		 * Get or retrieve access token from HubSpot.
		 *
		 * @param array $args
		 * @return bool
		 */
		public function get_access_token( array $args ) {
			$args = wp_parse_args(
				$args,
				array(
					'redirect_uri' => $this->get_redirect_uri(),
					'grant_type'   => 'authorization_code',
				)
			);

			$response = $this->_request( 'oauth/v1/token', 'POST', $args, false, true );

			if ( ! is_wp_error( $response ) && ! empty( $response->refresh_token ) ) {
				$token_data = get_object_vars( $response );

				$token_data['expires_in'] += time();

				// Update auth token
				$this->update_auth_token( $token_data );

				return true;
			}

			return false;
		}

		/**
		 * @param string  $endpoint The endpoint the request will be sent to.
		 * @param string  $method
		 * @param array   $query_args Additional args to include in the request body.
		 * @param string  $access_token
		 * @param boolean $x_www Whether the request is sent in application/x-www-form format.
		 *
		 * @return mixed
		 */
		public function _request( $endpoint, $method = 'GET', $query_args = array(), $access_token = '', $x_www = false, $json = false ) {
			// Avoid multiple call at once
			if ( $this->sending ) {
				return false; }

			$this->sending = true;
			$url           = self::API_URL . $endpoint;

			$args = array(
				'client_id'     => self::CLIENT_ID,
				'client_secret' => self::CLIENT_SECRET,
				'scope'         => 'contacts',
			);
			$args = wp_parse_args( $args, $query_args );

			if ( ! $x_www && $json ) {
				$args = wp_json_encode( $args ); }

			$_args = array(
				'method'  => $method,
				'headers' => array(
					'Authorization' => 'Bearer ' . ( ! empty( $access_token ) ? $access_token : self::HAPIKEY ),
					'Content-Type'  => 'application/json;charset=utf-8',
				),
				'body'    => $args,
			);

			if ( 'POST' === $method && $x_www ) {
				$_args['headers']['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8'; }

			$response = wp_remote_request( $url, $_args );

			// logging data
			$utils                      = Hustle_Provider_Utils::get_instance();
			$utils->_last_url_request   = $url;
			$utils->_last_data_sent     = $_args;
			$utils->_last_data_received = $response;

			$this->sending = false;

			if ( ! is_wp_error( $response ) ) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );

				if ( 204 === $response['response']['code'] && 'No Content' === $response['response']['message'] ) {
					return true;
				}

				if ( $response['response']['code'] <= 204
					|| isset( $body->status ) && 'error' === $body->status ) {
					return $body; }
			}
			return $response;
		}

		/**
		 * Helper function to send authenticated Post request.
		 *
		 * @param $end_point
		 * @param array     $query_args
		 * @param bool      $x_www
		 *
		 * @return mixed
		 */
		public function send_authenticated_post( $end_point, $query_args = array(), $x_www = false, $json = false ) {
			$access_token = $this->get_token( 'access_token' );
			return $this->_request( $end_point, 'POST', $query_args, $access_token, $x_www, $json );
		}

		/**
		 * Helper function to send authenticated GET request.
		 *
		 * @param $endpoint
		 * @param array    $query_args
		 *
		 * @return mixed
		 */
		public function send_authenticated_get( $endpoint, $query_args = array() ) {
			$access_token = $this->get_token( 'access_token' );
			return $this->_request( $endpoint, 'GET', $query_args, $access_token );
		}

		/**
		 * Get stored token data.
		 *
		 * @return array|null
		 */
		public function get_auth_token() {
			return get_option( $this->option_name );
		}

		/**
		 * Update token data.
		 *
		 * @param array $token
		 * @return void
		 */
		public function update_auth_token( array $token ) {
			update_option( $this->option_name, $token );
		}

		public function remove_wp_options() {
			delete_option( $this->option_name );
			delete_option( self::REFERER );
			delete_option( self::CURRENTPAGE );
		}

		/**
		 * @return bool
		 */
		public function is_authorized() {
			$auth = $this->get_auth_token();

			if ( empty( $auth ) ) {
				return false; }

			if ( ! empty( $auth['expires_in'] ) && time() < $auth['expires_in'] ) {
				return true;
			}

			// Attempt to refresh token
			$refresh = $this->refresh_access_token();
			return $refresh;
		}

		/**
		 * Generates authorization URL
		 *
		 * @param int $module_id
		 *
		 * @return string
		 */
		public function get_authorization_uri( $module_id = 0, $log_referrer = true, $page = 'hustle_embedded' ) {
			$args = array(
				'client_id'    => self::CLIENT_ID,
				'scope'        => 'contacts',
				'redirect_uri' => $this->get_redirect_uri(),
			);
			$args = http_build_query( $args );

			if ( $log_referrer ) {

				$params = array(
					'page'   => $page,
					'action' => 'external-redirect',
					'slug'   => 'hubspot',
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

			return self::BASE_URL . 'oauth/authorize?' . $args;
		}

		/**
		 * Get the current token's information.
		 *
		 * @since 4.0.2
		 * @return array
		 */
		public function get_access_token_information() {

			$res   = array();
			$token = $this->get_token( 'access_token' );

			if ( ! empty( $token ) ) {
				$res = $this->send_authenticated_get( 'oauth/v1/access-tokens/' . $token );
			}

			return $res;
		}

		/**
		 * Retrieve contact lists from Hubspot
		 *
		 * @return array
		 */
		public function get_contact_list() {
			$listing = array();

			$args = array(
				'count'  => 200,
				'offset' => 0,
			);
			$res  = $this->send_authenticated_get( 'contacts/v1/lists/static', $args );

			if ( ! is_wp_error( $res ) && ! empty( $res->lists ) ) {
				$listing = wp_list_pluck( $res->lists, 'name', 'listId' );
			}

			return $listing;
		}

		/**
		 * Check if the given email address is already a subscriber.
		 *
		 * @param string $email The email address to check.
		 *
		 * @return bool|mixed
		 */
		public function email_exists( $email ) {
			$endpoint = 'contacts/v1/contact/email/' . $email . '/profile';

			$res = $this->send_authenticated_get( $endpoint );

			if ( ! is_wp_error( $res ) && ! empty( $res->vid ) ) {
				return $res; }

			return false;
		}

		/**
		 * Get the list of existing properties from HubSpot account.
		 *
		 * @return array
		 */
		public function get_properties() {
			$properties = array();
			$res        = $this->send_authenticated_get( 'properties/v1/contacts/properties' );
			if ( ! is_wp_error( $res ) && ! isset( $res->status ) ) {
				foreach ( $res as $prop ) {
					$properties[ $prop->name ] = $prop->label; }
			}

			return $properties;
		}

		/**
		 * Add new field contact property to HubSpot.
		 *
		 * @param array $property
		 *
		 * @return bool
		 */
		public function add_property( array $property ) {
			$res = $this->send_authenticated_post( 'properties/v1/contacts/properties', $property, false, true );

			return ! is_wp_error( $res ) && ! empty( $res->name );
		}

		/**
		 * Add contact subscriber to HubSpot.
		 *
		 * @param array $data
		 *
		 * @return mixed
		 */
		public function add_contact( $data ) {
			$props = array();

			// Add error log entries for subscription errors caused by custom fields not registered in HubSpot.
			$default_data        = array( 'first_name', 'last_name' );
			$existing_properties = array_merge( $this->get_properties(), array_flip( $default_data ) );
			$filtered_data       = array_intersect_key( $data, $existing_properties );

			$difference = array_diff_key( $data, $filtered_data );
			if ( ! empty( $difference ) ) {
				$message = 'These fields are preventing your users from subscribing because they do not exist in your Hubspot account: ' . implode( ', ', array_keys( $difference ) );
				throw new Exception( $message );
			}

			foreach ( $data as $key => $value ) {
				if ( 'first_name' === $key ) {
					$key = 'firstname';
				}
				if ( 'last_name' === $key ) {
					$key = 'lastname';
				}

				$props[] = array(
					'property' => $key,
					'value'    => $value,
				);
			}

			$args     = array( 'properties' => $props );
			$endpoint = 'contacts/v1/contact';

			$res = $this->send_authenticated_post( $endpoint, $args, false, true );

			if ( ! is_wp_error( $res ) && ! empty( $res->vid ) ) {
				return $res->vid; }

			return $res;
		}

		/**
		 * Add contact subscriber to HubSpot.
		 *
		 * @param array $data
		 *
		 * @return mixed
		 */
		public function update_contact( $id, $data ) {
			$props = array();

			// Add error log entries for subscription errors caused by custom fields not registered in HubSpot.
			$default_data        = array( 'first_name', 'last_name' );
			$existing_properties = array_merge( $this->get_properties(), array_flip( $default_data ) );
			$filtered_data       = array_intersect_key( $data, $existing_properties );

			$difference = array_diff_key( $data, $filtered_data );
			if ( ! empty( $difference ) ) {
				$message = 'These fields are preventing your users from subscribing because they do not exist in your Hubspot account: ' . implode( ', ', array_keys( $difference ) );
				throw new Exception( $message );
			}

			foreach ( $data as $key => $value ) {
				if ( 'first_name' === $key ) {
					$key = 'firstname';
				}
				if ( 'last_name' === $key ) {
					$key = 'lastname';
				}

				$props[] = array(
					'property' => $key,
					'value'    => $value,
				);
			}

			$args     = array( 'properties' => $props );
			$endpoint = 'contacts/v1/contact/vid/' . $id . '/profile';

			$res = $this->send_authenticated_post( $endpoint, $args, false, true );

			if ( ! is_wp_error( $res ) && ! empty( $res->vid ) ) {
				return $res->vid; }

			return $res;
		}

		/**
		 * Add contact to contact list.
		 *
		 * @param $contact_id
		 * @param $email
		 * @param $email_list
		 *
		 * @return bool|mixed
		 */
		public function add_to_contact_list( $contact_id, $email, $email_list ) {
			$args     = array(
				'listId' => $email_list,
				'vid'    => array( $contact_id ),
				'emails' => array( $email ),
			);
			$endpoint = 'contacts/v1/lists/' . $email_list . '/add';
			$res      = $this->send_authenticated_post( $endpoint, $args, false, true );

			if ( ! is_wp_error( $res ) && ! empty( $res->updated ) ) {
				return true;
			}

			if ( ! empty( $res->status ) && 'error' === $res->status && ! empty( $res->message ) ) {
				$res = new WP_Error( 'provider_error', $res->message );
			}

			return $res;
		}
	}
endif;