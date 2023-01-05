<?php

if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {

	if ( ! class_exists( 'Ctct\CTCTOfficialSplClassLoader' ) ) {
		require_once dirname( __FILE__ ) . '/CtCt/autoload.php';
	}

	if ( ! class_exists( 'Hustle_ConstantContact_Api' ) ) :

		class Hustle_ConstantContact_Api extends Opt_In_WPMUDEV_API {

			const API_URL      = 'https://api.constantcontact.com/v2/';
			const AUTH_API_URL = 'https://oauth2.constantcontact.com/';

			const APIKEY          = 'wn8r98wcxnegkgy976xeuegt';
			const CONSUMER_SECRET = 'QZytJQReSTM3K9bH4NG9Dd2A';

			// Random client ID we use to verify our calls
			const CLIENT_ID = '9253e5C3-28d6-48fd-c102-b92b8f250G1b';

			const REFERER     = 'hustle_constantcontact_referer';
			const CURRENTPAGE = 'hustle_constantcontact_current_page';

			/**
			 * Auth token
			 *
			 * @var string
			 */
			private $option_token_name = 'hustle_opt-in-constant_contact-token';


			/**
			 * @var string
			 */
			private $action_event = 'hustle_constantcontact_event';

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
			 * Hustle_ConstantContact_Api constructor.
			 */
			public function __construct() {
				// Init request callback listener
				add_action( 'init', array( $this, 'process_callback_request' ) );
			}

			/**
			 * Helper function to listen to request callback sent from WPMUDEV
			 */
			public function process_callback_request() {
				if ( $this->validate_callback_request( 'constantcontact' ) ) {
					$code   = filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING );
					$status = 'error';

					// Get the referer page that sent the request
					$referer      = get_option( self::REFERER );
					$current_page = get_option( self::CURRENTPAGE );
					if ( $code ) {
						if ( $this->get_access_token( $code ) ) {
							if ( ! empty( $referer ) ) {
								$status = 'success';
							}
						}
					}

					if ( ! empty( $referer ) ) {
						$referer = add_query_arg( 'status', $status, $referer );
						wp_safe_redirect( $referer );
						exit;
					}

					// Allow retry but don't log referrer
					$authorization_uri = $this->get_authorization_uri( false, false, $current_page );

					$this->wp_die( esc_attr__( 'Constant Contact integration failed!', 'hustle' ), esc_url( $authorization_uri ), esc_url( $referer ) );
				}
			}


			/**
			 * Generates authorization URL
			 *
			 * @param int $module_id
			 *
			 * @return string
			 */
			public function get_authorization_uri( $module_id = 0, $log_referrer = true, $page = 'hustle_embedded' ) {
				$oauth = new Ctct\Auth\CtctOAuth2( self::APIKEY, self::CONSUMER_SECRET, $this->get_redirect_uri() );
				if ( $log_referrer ) {
					/**
					* Store $referer to use after retrieving the access token
					*/
					$params = array(
						'page'   => $page,
						'action' => 'external-redirect',
						'slug'   => 'constantcontact',
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
					'constantcontact',
					'authorize',
					array( 'client_id' => self::CLIENT_ID )
				);
			}

			/**
			 * Get Access token
			 *
			 * @param Array $args
			 */
			public function get_access_token( $code ) {
				$oauth        = new Ctct\Auth\CtctOAuth2( self::APIKEY, self::CONSUMER_SECRET, $this->get_redirect_uri() );
				$access_token = $oauth->getAccessToken( $code );

				$this->update_auth_token( $access_token );

				return true;
			}


			/**
			 * Get stored token data.
			 *
			 * @return array|null
			 */
			public function get_auth_token() {
				return get_option( $this->option_token_name );
			}


			/**
			 * Update token data.
			 *
			 * @param array $token
			 * @return void
			 */
			public function update_auth_token( array $token ) {
				update_option( $this->option_token_name, $token );
			}

			/**
			 * Get current account information.
			 *
			 * @since 4.0.2
			 * @throws Exception When there's a conflict with another CTCT plugin.
			 * @return object
			 */
			public function get_account_info() {
				$cc_api = new Ctct\ConstantContact( self::APIKEY );
				if ( ! method_exists( $cc_api, 'getAccountInfo' ) ) {
					throw new Exception( "There's a conflict with another plugin using the CTCT's library." );
				}
				return $cc_api->getAccountInfo( $this->get_token( 'access_token' ) );
			}

			/**
			 * Retrieve contact lists from ConstantContact
			 *
			 * @return array
			 */
			public function get_contact_lists() {

				$cc_api = new Ctct\ConstantContact( self::APIKEY );

				$access_token = $this->get_token( 'access_token' );

				$lists_data = $cc_api->listService->getLists( $access_token ); // phpcs:ignore

				return ( ! empty( $lists_data ) && is_array( $lists_data ) ) ? $lists_data : array();
			}


			/**
			 * Retrieve contact from ConstantContact
			 *
			 * @param string $email
			 * @return false|Object
			 */
			public function get_contact( $email ) {
				$contact      = false;
				$cc_api       = new Ctct\ConstantContact( self::APIKEY );
				$access_token = $this->get_token( 'access_token' );
				$res = $cc_api->contactService->getContacts( $access_token, array( 'email' => $email ) ); // phpcs:ignore
				if ( is_object( $res ) && ! empty( $res->results ) ) {
					$contact = $res->results[0];
				}
				return $contact;

			}


			/**
			 * Check if contact exists in certain list
			 *
			 * @param object $contact \Ctct\Components\Contacts\Contact
			 * @param string $list_id
			 * @return bool
			 */
			public function contact_exist( $contact, $list_id ) {
				$exists = false;
				if ( $contact instanceof Ctct\Components\Contacts\Contact ) {
					$lists = $contact->lists;
					foreach ( $lists as $list ) {
						$list = (array) $list;
						if ( (string) $list_id === (string) $list['id'] ) {
							$exists = true;
							break;
						}
					}
				}

				return $exists;
			}


			/**
			 * Subscribe contact
			 *
			 * @param String $email
			 * @param String $list
			 * @param Array  $custom_fields
			 */
			public function subscribe( $email, $first_name, $last_name, $list, $custom_fields = array() ) {
				$access_token = $this->get_token( 'access_token' );
				$cc_api       = new Ctct\ConstantContact( self::APIKEY );
				$contact      = new Ctct\Components\Contacts\Contact();
				$contact->addEmail( $email );
				if ( ! empty( $first_name ) ) {
					$contact->first_name = $first_name;
				}
				if ( ! empty( $last_name ) ) {
					$contact->last_name = $last_name;
				}
				$contact->addList( $list );

				if ( ! empty( $custom_fields ) ) {
					$allowed = array(
						'prefix_name',
						'job_title',
						'company_name',
						'home_phone',
						'work_phone',
						'cell_phone',
						'fax',
					);

					// Add extra fields
					$x = 1;
					foreach ( $custom_fields as $key => $value ) {
						if ( in_array( $key, $allowed, true ) ) {
							$contact->$key = $value;
						} else {
							if ( ! empty( $value ) ) {
								$custom_field             = array(
									'name'  => 'CustomField' . $x,
									'value' => $value,
								);
								$contact->custom_fields[] = $custom_field;
								$x++;
							}
						}
					}
				}

				$response = $cc_api->contactService->addContact( $access_token, $contact ); // phpcs:ignore

				return $response;
			}

			public function remove_wp_options() {
				delete_option( $this->option_token_name );
				delete_option( self::REFERER );
				delete_option( self::CURRENTPAGE );
			}

			/**
			 * Update Subscription
			 */
			public function updateSubscription( $contact, $first_name, $last_name, $list, $custom_fields = array() ) {
				$access_token = $this->get_token( 'access_token' );
				$cc_api       = new Ctct\ConstantContact( self::APIKEY );
				$contact->addList( $list );
				if ( ! empty( $first_name ) ) {
					$contact->first_name = $first_name;
				}
				if ( ! empty( $last_name ) ) {
					$contact->last_name = $last_name;
				}

				if ( ! empty( $custom_fields ) ) {
					$allowed = array(
						'prefix_name',
						'job_title',
						'company_name',
						'home_phone',
						'work_phone',
						'cell_phone',
						'fax',
					);

					// Add extra fields
					$x = 1;
					foreach ( $custom_fields as $key => $value ) {
						if ( in_array( $key, $allowed, true ) ) {
							$contact->$key = $value;
						} else {
							if ( ! empty( $value ) ) {
								$custom_field             = array(
									'name'  => 'CustomField' . $x,
									'value' => $value,
								);
								$contact->custom_fields[] = $custom_field;
								$x++;
							}
						}
					}
				}

				$response = $cc_api->contactService->updateContact( $access_token, $contact ); // phpcs:ignore

				return $response;
			}
		}
	endif;
}