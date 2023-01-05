<?php

if ( class_exists( 'Opt_In_Infusionsoft_Api' ) ) {
	return;
}

class Opt_In_Infusionsoft_Api {

	/**
	 * @var string $_api_key
	 */
	private $_api_key;

	/**
	 * @var string $_app_name
	 */
	private $_app_name;

	/**
	 * @var object $xml SimpleXMLElement class instance
	 **/
	public $xml;

	/**
	 * @var object $params SimpleXMLElement params node.
	 **/
	public $params;

	/**
	 * @var object $struct SimpleXMLElement struct node.
	 **/
	public $struct;

	/**
	 * Store the values getting from custom field request.
	 *
	 * @var array
	 */
	public $custom_fields_with_data_type;

	/**
	 * Opt_In_Infusionsoft_Api constructor.
	 *
	 * @param $api_key
	 * @param $app_name
	 */
	public function __construct( $api_key, $app_name ) {
		$this->_api_key  = $api_key;
		$this->_app_name = $app_name;
		return $this;
	}

	public function set_method( $method_name ) {
		$xml       = '<?xml version="1.0" encoding="UTF-8"?><methodCall></methodCall>';
		$this->xml = new SimpleXMLElement( $xml );
		$this->xml->addChild( 'methodName', $method_name );
		$this->params = $this->xml->addChild( 'params' );
		$this->set_param( $this->_api_key );
		$this->struct = false;
	}

	public function set_param( $value, $type = 'string' ) {
		$param = $this->params->addChild( 'param' );
		return $param->addChild( 'value' )->addChild( $type, $value );
	}

	public function set_member( $name, $value = '', $type = 'string' ) {
		if ( ! $this->struct ) {
			$this->struct = $this->params->addChild( 'param' )->addChild( 'value' )->addChild( 'struct' );
		}

		$member = $this->struct->addChild( 'member' );
		$member->addChild( 'name', $name );
		if ( ! empty( $value ) ) {
			$member->addChild( 'value' )->addChild( $type, $value );
		}
	}

	/**
	 * Contains the list of built-in custom fields.
	 **/
	public function builtin_custom_fields() {
		$custom_fields = array(
			'Anniversary',
			'AssistantName',
			'AssistantPhone',
			'Birthday',
			'City',
			'City2',
			'City3',
			'Company',
			'CompanyID',
			'ContactNotes',
			'ContactType',
			'Country',
			'Country2',
			'Country3',
			'Email',
			'EmailAddress2',
			'EmailAddress3',
			'Fax1',
			'Fax1Type',
			'Fax2',
			'Tax2Type',
			'FirstName',
			'JobTitle',
			'Language',
			'LastName',
			'MiddleName',
			'Nickname',
			'Password',
			'Phone1',
			'Phone1Ext',
			'Phone1Type',
			'Phone2',
			'Phone2Ext',
			'Phone2Type',
			'PostalCode',
			'PostalCode2',
			'ReferralCode',
			'SpouseName',
			'State',
			'State2',
			'StreetAddress1',
			'StreetAddress2',
			'Suffix',
			'TimeZone',
			'Title',
			'Website',
			'ZipFour1',
			'ZipFour2',
		);

		return $custom_fields;
	}

	/**
	 * Get the custom fields at InfusionSoft account.
	 **/
	public function get_custom_fields() {
		$this->set_method( 'DataService.query' );
		$this->set_param( 'DataFormField' );
		$this->set_param( 1000, 'int' );
		$this->set_param( 0, 'int' );
		$this->set_member( 'FormId', '-1' );

		$data = $this->params->addChild( 'param' )->addChild( 'value' )->addChild( 'array' )->addChild( 'data' );
		$data->addChild( 'value' )->addChild( 'string', 'Name' );

		$res = $this->_request( $this->xml->asXML() );
		if ( is_wp_error( $res ) ) {
			return $res;
		}

		$builtin_custom_fields              = $this->builtin_custom_fields();
		$extra_custom_fields                = array();
		$this->custom_fields_with_data_type = array();

		foreach ( $res->get_value()->data->value as $custom_field ) {
			$name  = '';
			$value = '';

			foreach ( $custom_field->struct->member as $info ) {
				if ( 'Name' === (string) $info->name ) {
					$extra_custom_fields[] = (string) $info->value;
					$name = (string) $info->value;
				}

				if ( 'DataType' === (string) $info->name ) {
					$value = (int) $info->value->i4;
				}
			}

			$this->custom_fields_with_data_type[ $name ] = $value;
		}

		$custom_fields = array_merge( $builtin_custom_fields, $extra_custom_fields );

		return $custom_fields;
	}

	/**
	 * Get Custom Field Groups for getting HeaderId for creating new Custom Field
	 *
	 * @return type
	 */
	private function get_custom_field_groups() {
		$this->set_method( 'DataService.query' );
		$this->set_param( 'DataFormGroup' );
		$this->set_param( 1000, 'int' );
		$this->set_param( 0, 'int' );

		$this->set_member( 'Id', '%' );

		$data = $this->params->addChild( 'param' )->addChild( 'value' )->addChild( 'array' )->addChild( 'data' );
		$data->addChild( 'value' )->addChild( 'string', 'Id' );
		$data->addChild( 'value' )->addChild( 'string', 'Name' );

		$res = $this->_request( $this->xml->asXML() );
		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return $res->response_to_array();
	}

	/**
	 * Create custom field at InfusionSoft account.
	 **/
	public function add_custom_field( $name ) {
		$headers = $this->get_custom_field_groups();
		if ( is_wp_error( $headers ) ) {
			return $headers;
		}
		$cf_group_id = array_search( 'Custom Fields', $headers );
		$header_id   = false !== $cf_group_id ? $cf_group_id : array_keys( $headers )[0];
		$this->set_method( 'DataService.addCustomField' );
		$this->set_param( 'Contact' );
		$this->set_param( $name );
		$this->set_param( 'Text' );
		$this->set_param( $header_id, 'int' );

		$res = $this->_request( $this->xml->asXML() );
		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return $res->get_value();
	}

	/**
	 * Add new contact to infusionsoft and return contact ID on success or WP_Error.
	 *
	 * @param array $contact            An array of contact details.
	 **/
	public function add_contact( $contact ) {
		if ( false === $this->email_exist( $contact['Email'] ) ) {
			$this->optInEmail( $contact['Email'] ); // First optin the email

			$this->set_method( 'ContactService.add' );

			// According to their documentations custom fields should be prefixed with "_".
			foreach ( $contact as $key => $value ) {
				if ( ! in_array( $key, $this->builtin_custom_fields(), true ) ) {
					$key = '_' . $key;
				}

				$this->set_member( $key, $value );
			}

			$res = $this->_request( $this->xml->asXML() );

			if ( is_wp_error( $res ) ) {
				return $res;
			}

			// make email marketable
			$this->set_method( 'APIEmailService.optIn' );
			$this->set_param( $contact['Email'] );
			$this->set_param( 'Customer opted-in through webform' );
			$optin = $this->_request( $this->xml->asXML() );

			return $res->get_value( 'i4' );
		} else {
			$err->add( 'email_exist', __( 'This email address has already subscribed.', 'hustle' ) );
			return $err;
		}
	}

	/**
	 * Updates an existing contact.
	 *
	 * @since 3.0.7
	 *
	 * @param array $contact Array of contact details to be updated.
	 * @return integer|WP_Error Contact ID if everything went well, WP_Error otherwise.
	 */
	public function update_contact( $contact ) {

		$this->optInEmail( $contact['Email'] ); // First optin the email

		$contact_id = $this->get_contact_id( $contact['Email'] );

		if ( ! $contact_id ) {
			return new WP_Error( 'contact_not_found', __( 'The existing contact could not be updated.', 'hustle' ) );
		}

		$this->set_method( 'ContactService.update' );

		$this->set_param( $contact_id, 'int' );
		foreach ( $contact as $key => $value ) {
			$this->set_member( $key, $value );
		}

		$res = $this->_request( $this->xml->asXML() );

		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return $res->get_value( 'i4' );

	}

	public function email_exist( $email ) {
		$this->set_method( 'ContactService.findByEmail' );
		$this->set_param( $email );
		$data = $this->params->addChild( 'param' )->addChild( 'value' )->addChild( 'array' )->addChild( 'data' );
		$data->addChild( 'value' )->addChild( 'string', 'Id' );

		$res = $this->_request( $this->xml->asXML() );

		if ( ! is_wp_error( $res ) ) {
			$subscriber_id = $res->get_value( 'array.data.value.struct.member.value.i4' );

			return (int) $subscriber_id > 0;
		}

		return false;
	}

	/**
	 * Get the ID of an existing contact
	 *
	 * @param string $email
	 * @return integer|boolean The ID of the existing contact, false on error. An ID of 0 or less means the contact does not exist.
	 */
	public function get_contact_id( $email ) {
		$this->set_method( 'ContactService.findByEmail' );
		$this->set_param( $email );
		$data = $this->params->addChild( 'param' )->addChild( 'value' )->addChild( 'array' )->addChild( 'data' );
		$data->addChild( 'value' )->addChild( 'string', 'Id' );

		$res = $this->_request( $this->xml->asXML() );

		if ( ! is_wp_error( $res ) ) {
			$subscriber_id = $res->get_value( 'array.data.value.struct.member.value.i4' );

			return (int) $subscriber_id;
		}

		return false;
	}

	/**
	 * Opt-in email
	 * This allows the email to be marketable
	 *
	 * @param String $email
	 *
	 * @return WP_Error|Xml
	 */
	private function optInEmail( $email ) {
		$site_name = get_bloginfo( 'name' );
		$this->set_method( 'ContactService.findByEmail' );
		$this->set_param( $email );
		$this->set_param( $site_name );
		$res = $this->_request( $this->xml->asXML() );
		return $res;
	}

	/**
	 * Adds contact with $contact_id to group with $group_id
	 *
	 * @param $contact_id
	 * @param $tag_id
	 * @return Opt_In_Infusionsoft_XML_Res|WP_Error
	 */
	public function add_tag_to_contact( $contact_id, $tag_id ) {
		$xml = "<?xml version='1.0' encoding='UTF-8'?>
				<methodCall>
				  <methodName>ContactService.addToGroup</methodName>
				  <params>
					<param>
					  <value>
						<string>{$this->_api_key}</string>
					  </value>
					</param>
					<param>
					  <value>
						<int>$contact_id</int>
					  </value>
					</param>
					<param>
					  <value>
						<int>$tag_id</int>
					  </value>
					</param>
				  </params>
				</methodCall>";

		$res = $this->_request( $xml );

		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return $res->get_value();

	}

	public function get_lists() {
		$page = 0;
		$xml  = "<?xml version='1.0' encoding='UTF-8'?>
				<methodCall>
				  <methodName>DataService.query</methodName>
				  <params>
					<param>
					  <value>
						<string>{$this->_api_key}</string>
					  </value>
					</param>
					<param>
					  <value>
						<string>ContactGroup</string>
					  </value>
					</param>
					<param>
					  <value>
						<int>1000</int>
					   </value>
					</param>
					<param>
					  <value>
						<int>$page</int>
					  </value>
					</param>
					<param>
					  <value><struct>
						<member>
							  <name>Id</name>
							  <value>
								<string>%</string>
							  </value>
						</member>
					  </struct></value>
					</param>
					<param>
					  <value><array>
						<data>
						  <value><string>Id</string></value>
						  <value><string>GroupName</string></value>
						</data>
					  </array></value>
					</param>
				  </params>
				</methodCall>";

		$res = $this->_request( $xml );

		if ( is_wp_error( $res ) ) {
			return $res;
		}

		return $res->get_tags_list();
	}

	/**
	 * Dispatches the request to the Infusionsoft server
	 *
	 * @param $query_str
	 * @return Opt_In_Infusionsoft_XML_Res|WP_Error
	 */
	private function _request( $query_str ) {
		$url = esc_url_raw( 'https://' . $this->_app_name . '.infusionsoft.com/api/xmlrpc' );

		$headers = array(
			'Content-Type'   => 'text/xml',
			'Accept-Charset' => 'UTF-8,ISO-8859-1,US-ASCII',
		);

		$res = wp_remote_post(
			$url,
			array(
				'sslverify' => false,
				'headers'   => $headers,
				'body'      => $query_str,
			)
		);

		$utils                      = Hustle_Provider_Utils::get_instance();
		$utils->_last_url_request   = $url;
		$utils->_last_data_received = $res;
		$utils->_last_data_sent     = $query_str;

		$code    = wp_remote_retrieve_response_code( $res );
		$message = wp_remote_retrieve_response_message( $res );
		$err     = new WP_Error();

		if ( $code < 204 ) {
			$xml = simplexml_load_string( wp_remote_retrieve_body( $res ), 'Opt_In_Infusionsoft_XML_Res' );

			if ( empty( $xml ) ) {
				$err->add( 'Invalid_app_name', __( 'Invalid app name, please check app name and try again', 'hustle' ) );
				return $err;
			}

			if ( $xml->is_faulty() ) {
				return $xml->get_fault();
			}

			return $xml;
		}

		$err->add( $code, $message );
		return $err;
	}
}

class Opt_In_Infusionsoft_XML_Res extends  SimpleXMLElement {

	/**
	 * Returns value from xml like the template
	 *  <methodResponse>
	 *       <params>
	 *            <param>
	 *               <value><i4>contactIDNumber</i4></value>
	 *           </param>
	 *       </params>
	 *   </methodResponse>
	 *
	 * @return mixed
	 */
	public function get_value( $xml_structure = '' ) {
		$value = reset( $this->params->param->value );

		if ( ! empty( $xml_structure ) ) {
			$xml = explode( '.', $xml_structure );
			$xml = array_filter( $xml );

			foreach ( $xml as $key ) {
				if ( is_object( $value ) && isset( $value->$key ) ) {
					$value = $value->$key;
				}
			}
		}

		return $value;
	}

	/**
	 * Retrieves tag list from the query result
	 *
	 * @return array
	 */
	public function get_tags_list() {
		$lists = array();
		$count = count( $this->get_value()->data->value );

		for ( $i = 0; $i < $count; $i++ ) {
			$list  = $this->get_value()->data->value[ $i ];
			$label = (string) $list->struct->member[0]->value;
			if ( ! empty( $label ) ) {
				$id           = (int) reset( $list->struct->member[1]->value );
				$lists[ $id ] = $label;
			}
		}

		return $lists;
	}

	public function response_to_array() {
		$array = array();

		foreach ( $this->get_value()->data->value as $list ) {
			foreach ( $list->struct->member as $info ) {
				if ( 'Name' === (string) $info->name ) {
					$label = (string) $info->value;
				} elseif ( 'Id' === (string) $info->name ) {
					$id = (int) reset( $info->value );
				}
				if ( isset( $label ) && isset( $id ) ) {
					$array[ $id ] = $label;
					unset( $label, $id );
				}
			}
			unset( $label, $id );
		}

		return $array;
	}

	/**
	 * Checks if responsive is faulty
	 *
	 * @return bool
	 */
	public function is_faulty() {
		return isset( $this->fault );
	}

	/**
	 * Returns bool false in case response is not faulty or a WP_Error with the fault code and message
	 *
	 * @return bool|WP_Error
	 */
	public function get_fault() {
		if ( ! $this->is_faulty() ) {
			return false;
		}

		$err = new WP_Error();
		$err->add( (int) $this->fault->value->struct->member[0]->value, (string) $this->fault->value->struct->member[1]->value );
		return $err;
	}
}