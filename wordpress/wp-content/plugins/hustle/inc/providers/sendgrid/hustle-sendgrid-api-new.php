<?php
/**
 * SendGrid API Helper for New Marketing Campaigns
 **/
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Hustle_New_SendGrid_Api' ) ) :

	class Hustle_New_SendGrid_Api {
		/**
		 * @var (string) SendGrid API KEY
		 **/
		private $api_key;

		protected $sendgrid_url = 'https://api.sendgrid.com/v3';

		public function __construct( $api_key ) {
			$this->api_key = $api_key;
		}

		/**
		 * Returns the appropriate header value of authorization depending on the available credentials.
		 *
		 * @return  mixed   string of the header value if successful, false otherwise.
		 */
		protected function get_headers() {

			$api_key = $this->api_key;

			if ( empty( $api_key ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'No API key is set.' );
				return false;
			}

			$args = array(
				'headers'    => array(
					'Authorization' => 'Bearer ' . $api_key,
				),
				'decompress' => false,
				'timeout'    => 10,
			);

			return $args;

		}

		/**
		 * Returns the contact lists from SendGrid
		 *
		 * @return  mixed   an array of lists if the request is successful, false otherwise.
		 */
		public function get_all_lists() {
			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url = $this->sendgrid_url . '/marketing/lists';

			$response = $this->request( $url, $args );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				return false;
			}

			$lists_response = json_decode( $response['body'], true );
			if ( isset( $lists_response['result'] ) ) {
				return $lists_response['result'];
			}

			return false;
		}

		/**
		 * Updates a recipient in the SendGrid
		 */
		public function update_recipient( $list_id, $data ) {
			return $this->create_and_add_recipient_to_list( $list_id, $data );
		}

		/**
		 * Adds/Updates a recipient in the SendGrid and adds it to the list
		 *
		 * @param   string $list_id        The list ID to which the recipient will be added.
		 * @param   string $data           The data of the recipient
		 *
		 * @return  WP_Error|boolean   True if successful, WP_Error otherwise.
		 */
		public function create_and_add_recipient_to_list( $list_id, $data ) {
			if ( empty( $list_id ) ) {
				return new WP_Error( 'subscribe_error', __( 'The list ID is not defined.', 'hustle' ) );
			}

			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url = $this->sendgrid_url . '/marketing/contacts';

			$data = $this->prepare_custom_fields( $data );

			$req_body                        = wp_json_encode(
				array(
					'contacts' => array( $data ),
					'list_ids' => array( $list_id ),
				)
			);
			$args['body']                    = $req_body;
			$args['headers']['Content-Type'] = 'application/json';

			$response = $this->request( $url, $args, 'PUT' );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				$error = __( 'The response is not an array or does not have a body.', 'hustle' );
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', $error );
				return new WP_Error( 'subscribe_error', $error );
			}

			$recipient_response = json_decode( $response['body'], true );

			if ( ! empty( $recipient_response['errors'][0]['message'] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', $recipient_response['errors'][0]['message'] );
				return new WP_Error( 'subscribe_error', $recipient_response['errors'][0]['message'] );
			}

			if ( empty( $recipient_response['job_id'] ) ) {
				$error = __( 'Persistent recipients is not set or does not contain values.', 'hustle' );
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', $error );
				return new WP_Error( 'subscribe_error', $error );
			}

			return true;
		}

		/**
		 * Prepare custom field to Sendgrid format
		 *
		 * @param array $data
		 * @return array
		 */
		public function prepare_custom_fields( $data ) {
			if ( empty( $data['custom_fields'] ) ) {
				return $data;
			}
			$custom_fields   = array();
			$existed_fields  = wp_list_pluck( $this->get_custom_fields(), 'id', 'name' );
			$reserved_fields = wp_list_pluck( $this->get_custom_fields( true ), 'id', 'name' );
			$saved_cf        = array_merge( $existed_fields, $reserved_fields );

			foreach ( $data['custom_fields'] as $cf_name => $cf_value ) {
				$cf_id = in_array( $cf_name, array_keys( $saved_cf ), true ) ? $saved_cf[ $cf_name ] : false;
				if ( $cf_id ) {
					$custom_fields[ $cf_id ] = $cf_value;
				}
				unset( $data[ $cf_name ] );
			}
			$data['custom_fields'] = (object) $custom_fields;

			return $data;
		}

		/**
		 * Check if an email is already used.
		 *
		 * @param string $email
		 * @return boolean true if the given email already in use otherwise false.
		 **/
		public function email_exists( $email, $list_id ) {
			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}
			$args['headers']['Content-Type'] = 'application/json';
			$args['body']                    = wp_json_encode(
				array(
					'query' => sprintf( "primary_email LIKE '%s%%' AND CONTAINS(list_ids, '%s')", $email, $list_id ),
				)
			);

			$url = $this->sendgrid_url . '/marketing/contacts/search';

			$response = $this->request( $url, $args, 'POST' );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				return false;
			}

			$response_array = json_decode( $response['body'], true );

			if ( isset( $response_array['errors'] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error retrieving recipient.', $response_array['errors'][0]['message'] );
				return false;
			}

			return ! empty( $response_array['result'][0]['id'] ) ? $response_array['result'][0]['id'] : false;

		}

		/**
		 * Get Sendgrid Custom/Reserved fields
		 *
		 * @param bool $reserved
		 * @return array
		 */
		private function get_custom_fields( $reserved = false ) {
			$args = $this->get_headers();

			if ( ! $args ) {
				return array();
			}

			$url = $this->sendgrid_url . '/marketing/field_definitions';

			$response = $this->request( $url, $args );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				return array();
			}

			$lists_response = json_decode( $response['body'], true );
			if ( ! $reserved && isset( $lists_response['custom_fields'] ) ) {
				return $lists_response['custom_fields'];
			} elseif ( $reserved && isset( $lists_response['reserved_fields'] ) ) {
				return $lists_response['reserved_fields'];
			}

			return array();
		}

		/**
		 * Get Sendgrid reserved fields
		 *
		 * @return array
		 */
		public function get_reserved_fields_name() {
			return wp_list_pluck( $this->get_custom_fields( true ), 'name' );
		}

		/**
		 * Add custom fields
		 *
		 * @param array $fields
		 */
		public function add_custom_fields( $fields ) {
			$existed_fields  = wp_list_pluck( $this->get_custom_fields(), 'id', 'name' );
			$reserved_fields = wp_list_pluck( $this->get_custom_fields( true ), 'id', 'name' );
			$existed_fields  = array_merge( $existed_fields, $reserved_fields );

			foreach ( $fields as $field ) {
				$type = strtolower( $field['type'] );
				$name = strtolower( $field['name'] );
				if ( in_array( $name, array_keys( $existed_fields ), true ) ) {
					continue;
				}
				if ( ! in_array( $type, array( 'text', 'number', 'date' ), true ) ) {
					$type = 'text';
				}
				$new_cf = $this->add_custom_field(
					array(
						'name'       => $name,
						'field_type' => ucfirst( $type ),
					)
				);
			}
		}

		/**
		 * Add custom field
		 *
		 * @param array $field_data (name, field_type)
		 */
		public function add_custom_field( $field_data ) {

			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url          = $this->sendgrid_url . '/marketing/field_definitions';
			$req_body     = wp_json_encode( $field_data );
			$args['body'] = $req_body;

			$response = $this->request( $url, $args, 'POST' );

			$response_array = json_decode( $response['body'], true );

			if ( isset( $response_array['errors'] ) && isset( $response_array['errors'][0] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error creating the custom field.', $response_array['errors'][0]['message'] );
			}

			return $response_array;
		}

		/**
		 * Request
		 *
		 * @param string $url
		 * @param array  $args
		 * @param string $method GET|POST
		 * @return array|WP_Error
		 */
		private function request( $url, $args, $method = 'GET' ) {
			if ( empty( $args['method'] ) && in_array( $method, array( 'GET', 'POST', 'PUT' ), true ) ) {
				$args['method'] = $method;
			}

			$response = wp_remote_request( $url, $args );

			$utils                      = Hustle_Provider_Utils::get_instance();
			$utils->_last_url_request   = $url;
			$utils->_last_data_sent     = $args;
			$utils->_last_data_received = $response;

			return $response;
		}

	}
endif;