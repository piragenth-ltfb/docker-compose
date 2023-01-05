<?php
/**
 * SendGrid API Helper
 **/
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Hustle_SendGrid_Api' ) ) :

	class Hustle_SendGrid_Api {
		/**
		 * @var (string) SendGrid API KEY
		 **/
		private $api_key;

		protected $sendgrid_url = 'https://api.sendgrid.com/v3/contactdb';

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

			$url = $this->sendgrid_url . '/lists';

			$response = $this->request( $url, $args );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				return false;
			}

			$lists_response = json_decode( $response['body'], true );
			if ( isset( $lists_response['lists'] ) ) {
				return $lists_response['lists'];
			}

			return false;
		}

		/**
		 * Adds a recipient in the SendGrid MC contact db
		 *
		 * @param   string $email          The email of the recipient
		 * @param   string $first_name     The first name of the recipient
		 * @param   string $last_name      The last name of the recipient
		 *
		 * @return  mixed   The recipient ID if successful, false otherwise.
		 */
		public function add_recipient( $data ) {
			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url = $this->sendgrid_url . '/recipients';

			$req_body     = wp_json_encode( array( $data ) );
			$args['body'] = $req_body;

			$response = $this->request( $url, $args, 'POST' );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', 'The response is not an array or does not have a body.' );
				return false;
			}

			$recipient_response = json_decode( $response['body'], true );

			if ( isset( $recipient_response['error_count'] ) && 0 !== $recipient_response['error_count'] ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', $recipient_response['errors'][0]['message'] );
				return false;
			}

			if ( ! isset( $recipient_response['persisted_recipients'] ) || ! isset( $recipient_response['persisted_recipients'][0] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', 'Persistent recipients is not set or does not contain values.' );
				return false;
			}

			return $recipient_response['persisted_recipients'][0];
		}

		/**
		 * Updates a recipient in the SendGrid MC contact db
		 *
		 * @param   array $data          Recipient data
		 *
		 * @return  mixed   The recipient ID if successful, false otherwise.
		 */
		public function update_recipient( $list_id, $data ) {
			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url = $this->sendgrid_url . '/recipients';

			$req_body       = wp_json_encode( array( $data ) );
			$args['body']   = $req_body;
			$args['method'] = 'PATCH';

			$response = $this->request( $url, $args );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', 'The response is not an array or does not have a body.' );
				return false;
			}

			$recipient_response = json_decode( $response['body'], true );

			if ( isset( $recipient_response['error_count'] ) && 0 !== $recipient_response['error_count'] ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', $recipient_response['errors'][0]['message'] );
				return false;
			}

			if ( ! isset( $recipient_response['persisted_recipients'] ) || ! isset( $recipient_response['persisted_recipients'][0] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient.', 'Persistent recipients is not set or does not contain values.' );
				return false;
			}

			return $recipient_response['persisted_recipients'][0];
		}

		/**
		 * Adds a recipient in the specified list
		 *
		 * @param   string $recipient_id      the ID of the recipient.
		 * @param   string $list_id           the ID of the list.
		 *
		 * @return  bool   True if successful, false otherwise.
		 */
		private function add_recipient_to_list( $recipient_id, $list_id ) {
			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url = $this->sendgrid_url . '/lists/' . $list_id . '/recipients/' . $recipient_id;

			$response = $this->request( $url, $args, 'POST' );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error adding the recipient to a list.', 'The response is not an array or does not have a body.' );
				return false;
			}

			if ( isset( $response['response']['code'] ) && 201 === $response['response']['code'] ) { // This used == before. check types if there are errors.
				return true;
			}

			return false;
		}

		/**
		 * Adds a recipient in the SendGrid MC contact db and adds it to the list
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

			$recipient_id = $this->add_recipient( $data );
			if ( ! $recipient_id ) {
				$missing_fields = $this->get_non_existent_fields( $data );
				$error_message  = empty( $missing_fields ) ?
					__( 'The recipient could not be created. Check if your settings are correct.', 'hustle' ) :
					sprintf( __( 'The recipient could not be created. Please make sure these fields exist in your Sendgrid account: %s.', 'hustle' ), implode( ', ', $missing_fields ) );
				return new WP_Error( 'subscribe_error', $error_message );
			}

			$recipient_added = $this->add_recipient_to_list( $recipient_id, $list_id );
			if ( ! $recipient_added ) {
				return new WP_Error( 'subscribe_error', __( 'The recipient could not be added to a list. Check if your settings are correct.', 'hustle' ) );
			}

			return true;
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

			$url = $this->sendgrid_url . '/recipients/search?email=' . $email . '&list_id=' . $list_id;

			$response = $this->request( $url, $args );

			if ( ! is_array( $response ) || ! isset( $response['body'] ) ) {
				return false;
			}

			$response_array = json_decode( $response['body'], true );

			if ( isset( $response_array['errors'] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error retrieving recipient.', $response_array['errors'][0]['message'] );
				return false;
			}

			return ( ! isset( $response_array['recipient_count'] ) || 0 !== $response_array['recipient_count'] );

		}

		/**
		 * Unsets the fields that don't exist at Sendgrid to prevent subscription errors.
		 *
		 * @param array $data Submitted data
		 * @return array
		 */
		private function get_non_existent_fields( $data ) {

			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			// Get reserved fields
			$reserved_fields_url      = $this->sendgrid_url . '/reserved_fields';
			$reserved_fields_response = $this->request( $reserved_fields_url, $args );
			$reserved_fields          = json_decode( $reserved_fields_response['body'], true );

			// Get custom fields
			$custom_fields_url      = $this->sendgrid_url . '/custom_fields';
			$custom_fields_response = $this->request( $custom_fields_url, $args );
			$custom_fields          = json_decode( $custom_fields_response['body'], true );

			$existing_reserved_fields = isset( $reserved_fields['reserved_fields'] ) ? $reserved_fields['reserved_fields'] : array();
			$existing_custom_fields   = isset( $custom_fields['custom_fields'] ) ? $custom_fields['custom_fields'] : array();

			$merged_array = array_merge( $existing_custom_fields, $existing_reserved_fields );

			if ( empty( $merged_array ) ) {
				return false;
			}

			$existing_fields_names = wp_list_pluck( $merged_array, 'name' );

			$non_existent_fields = array();
			foreach ( $data as $name => $value ) {
				if ( ! in_array( $name, $existing_fields_names, true ) ) {
					$non_existent_fields[] = $name;
				}
			}

			return $non_existent_fields;
		}

		/**
		 * Add custom fields
		 *
		 * @param array $fields
		 */
		public function add_custom_fields( $fields ) {
			foreach ( $fields as $field ) {
				$type = strtolower( $field['type'] );
				if ( ! in_array( $type, array( 'text', 'number', 'date' ), true ) ) {
					$type = 'text';
				}
				$this->add_custom_field(
					array(
						'name' => strtolower( $field['name'] ),
						'type' => $type,
					)
				);
			}
		}

		/**
		 * Add custom field
		 *
		 * @param array $field_data (name, type)
		 */
		private function add_custom_field( $field_data ) {

			$args = $this->get_headers();

			if ( ! $args ) {
				return false;
			}

			$url          = $this->sendgrid_url . '/custom_fields';
			$req_body     = wp_json_encode( $field_data );
			$args['body'] = $req_body;

			$response = $this->request( $url, $args, 'POST' );

			$response_array = json_decode( $response['body'], true );

			if ( isset( $response_array['errors'] ) && isset( $response_array['errors'][0] ) ) {
				Hustle_Provider_Utils::maybe_log( __METHOD__, 'Error creating the custom field.', $response_array['errors'][0]['message'] );
			}

			return true;
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
			if ( empty( $args['method'] ) && in_array( $method, array( 'GET', 'POST' ), true ) ) {
				$args['method'] = $method;
			}

			$response = wp_remote_request( $url, $args );

			$utils                      = Hustle_Provider_Utils::get_instance();
			$utils->_last_url_request   = $url;
			$utils->_last_data_sent     = $args;
			$utils->_last_data_received = $response;

			return $response;
		}

		public function get_reserved_fields_name() {
			return array(
				'email'      => '',
				'first_name' => '',
				'last_name'  => '',
			);
		}

	}
endif;