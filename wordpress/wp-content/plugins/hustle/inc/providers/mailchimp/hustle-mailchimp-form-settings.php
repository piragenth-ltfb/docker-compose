<?php
/**
 * Class Hustle_Mailchimp_Form_Settings
 * Form Settings Mailchimp Process
 */
class Hustle_Mailchimp_Form_Settings extends Hustle_Provider_Form_Settings_Abstract {

	/**
	 * stores mailchimp group data
	 */
	private $groups_data = array();

	/**
	 *
	 * stores mailchimp tags data ( static segments )
	 *
	 * @since 4.0.2
	 */
	private $tags_data = array();

	/**
	 * Settings wizard steps
	 *
	 * @since 4.1.1
	 * @var type
	 */
	private $steps;

	/**
	 * Options that must be set in order to consider the integration as "connected" to the form.
	 *
	 * @since 4.2.0
	 * @var array
	 */
	protected $form_completion_options = array( 'selected_global_multi_id', 'list_id' );

	/**
	 * For settings Wizard steps
	 *
	 * @since 3.0.5
	 * @since 4.0 Second and third steps added.
	 *
	 * @return array
	 */
	public function form_settings_wizards() {
		// Get cached data if exists
		if ( ! is_null( $this->steps ) ) {
			return $this->steps;
		}

		// already filtered on Abstract
		// numerical array steps
		$this->steps = array(
			// 0 - Select List
			array(
				'callback'     => array( $this, 'first_step_callback' ),
				'is_completed' => array( $this, 'is_first_step_completed' ),
			),
			// 3 - Select Tags (yes, fourth step here. It's temporary)
			array(
				'callback'     => array( $this, 'second_step_callback' ),
				'is_completed' => array( $this, 'step_is_completed' ),
			),
		);

		$this->addon_form_settings = $this->get_form_settings_values( false );

		if ( ! empty( $this->addon_form_settings['list_id'] ) ) {

			$groups = $this->get_groups( $this->addon_form_settings['list_id'] );

			// If the selected list doesn't have groups, close the modal. No need for this step.
			if ( ! empty( $groups ) && is_array( $groups ) ) {
				$this->steps[] = array(
					// 1 - Select Group and Interests
					'callback'     => array( $this, 'third_step_callback' ),
					'is_completed' => array( $this, 'step_is_completed' ),
				);
			}

			// If GDPR isn't selected on form, close the modal. No need for this step.
			if ( $this->is_optin_gpdr() ) {
				$gdpr_fields = $this->get_gdpr_fields( $this->addon_form_settings['list_id'] );

				// If the selected list doesn't have GDPR fields - no need for this step.
				if ( ! empty( $gdpr_fields ) && is_array( $gdpr_fields ) ) {
					$this->steps[] = array(
						'callback'     => array( $this, 'fourth_step_callback' ),
						'is_completed' => array( $this, 'step_is_completed' ),
					);
				}
			}
		}

		// return successful message
		$this->steps[] = array(
			'callback'     => array( $this, 'get_successful_message' ),
			'is_completed' => array( $this, 'step_is_completed' ),
		);

		return $this->steps;
	}

	// -------------------------------------------------------
	// Step 'is completed' validations
	// -------------------------------------------------------

	/**
	 * Check if step is completed
	 *
	 * @since 3.0.5
	 * @return bool
	 */
	public function is_first_step_completed() {
		$this->addon_form_settings = $this->get_form_settings_values();
		if ( ! isset( $this->addon_form_settings['list_id'] ) ) {
			// preliminary value
			$this->addon_form_settings['list_id'] = 0;

			return false;
		}

		if ( empty( $this->addon_form_settings['list_id'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Return as if the step is indeed completed.
	 * The second and third steps are optional, so no real validation is done here.
	 *
	 * @return boolean
	 */
	public function step_is_completed() {
		return $this->is_first_step_completed();
	}

	// -------------------------------------------------------
	// Main steps callbacks
	// -------------------------------------------------------

	/**
	 * Returns all settings and conditions for the "list" settings.
	 * Select list step.
	 *
	 * @since 3.0.5
	 * @since 4.0 param $is_submit removed.
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	public function first_step_callback( $submitted_data ) {

		$this->addon_form_settings = $this->get_form_settings_values();
		$current_data              = array(
			'auto_optin' => '0',
			'list_id'    => '',
		);
		$current_data              = $this->get_current_data( $current_data, $submitted_data );

		$is_submit = ! empty( $submitted_data['hustle_is_submit'] ) && empty( $submitted_data['page'] );
		if ( $is_submit && empty( $submitted_data['list_id'] ) ) {
			$error_message = __( 'The email list is required.', 'hustle' );
		}
		if ( ! $is_submit && ! empty( $submitted_data['page'] ) ) {
			$settings         = array();
			$settings['page'] = $submitted_data['page'];
			$this->save_form_settings_values( $settings );
		}

		$options = $this->get_first_step_options( $current_data );

		if ( is_wp_error( $options ) ) {
			$error_message = $options->get_error_message();
			$options       = array();
			// There was an error with the API. No sense on continuing to next step.
			$buttons = array();

		} else {
			$buttons = array(
				'disconnect' => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Disconnect', 'hustle' ),
						'sui-button-ghost',
						'disconnect_form',
						true
					),
				),
				'save'       => array(
					'markup' => Hustle_Provider_Utils::get_provider_button_markup(
						__( 'Continue', 'hustle' ),
						'',
						'next',
						true
					),
				),
			);
		}

		if ( ! isset( $error_message ) ) {
			$has_errors = false;
		} else {
			$options[]  = array(
				'type'  => 'error',
				'id'    => '',
				'value' => $error_message,
			);
			$has_errors = true;
		}

		$step_html  = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Mailchimp List', 'hustle' ),
			__( 'Choose the list you want to send form data to.', 'hustle' )
		);
		$step_html .= Hustle_Provider_Utils::get_html_for_options( $options );

		$response = array(
			'html'       => $step_html,
			'buttons'    => $buttons,
			'has_errors' => $has_errors,
		);

		// Save only after the step has been validated and there are no errors
		if ( $is_submit && ! $has_errors ) {
			// Save additional data for submission's entry
			if ( ! empty( $current_data['list_id'] ) ) {
				$current_data['list_name'] = ! empty( $this->lists[ $current_data['list_id'] ] )
						? $this->lists[ $current_data['list_id'] ] . ' (' . $current_data['list_id'] . ')' : $current_data['list_id'];
			}
			if (
				empty( $current_data['list_id'] ) ||
				empty( $this->addon_form_settings['list_id'] ) ||
				$current_data['list_id'] !== $this->addon_form_settings['list_id']
			) {
				$current_data['group']                      = null;
				$current_data['group_interest']             = null;
				$current_data['group_interest_placeholder'] = null;
			}

			$this->save_form_settings_values( $current_data );
		}

		return $response;
	}

	/**
	 * Get parameters for successful_message
	 *
	 * @return array
	 */
	public function get_successful_message() {
		return array(
			'html'         => '',
			'notification' => array(
				'type' => 'success',
				'text' => '<strong>' . $this->provider->get_title() . '</strong> ' . __( 'successfully connected to your form', 'hustle' ),
			),
			'is_close'     => true,
		);
	}

	/**
	 * Returns all settings and conditions for the "group" settings.
	 * Select group step. This step is optional.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	public function third_step_callback( $submitted_data ) {

		$this->addon_form_settings = $this->get_form_settings_values( false );

		$groups = $this->get_groups( $this->addon_form_settings['list_id'] );

		$is_submit = ! empty( $submitted_data );

		// check groups
		if ( $is_submit && isset( $submitted_data['group'] ) ) {
			$group_id = $submitted_data['group'];
		} elseif ( isset( $this->addon_form_settings['group'] ) ) {
			$group_id = $this->addon_form_settings['group'];
		} else {
			$group_id = '-1';
		}
		$html = '';

		if ( ! empty( $groups ) && is_array( $groups ) ) {
			$options = $this->get_second_step_options( $groups, $group_id );
			$html   .= Hustle_Provider_Utils::get_integration_modal_title_markup(
				__( 'Mailchimp Group', 'hustle' ),
				__( 'Mailchimp group allows you to categorize your audience based on their interest. Add a group category to your opt-in form and let your visitors choose their interested group.', 'hustle' )
			);
			$html   .= Hustle_Provider_Utils::get_html_for_options( $options );
		}

		if ( $is_submit ) {

			// Store the selected group_id
			$this->addon_form_settings['group'] = $group_id;

			if ( '-1' !== $group_id ) {
				// Store the group name.
				$this->addon_form_settings['group_name'] = $this->groups_data[ $group_id ]['name'];

				// Store the group type. New in 4.0
				$this->addon_form_settings['group_type'] = $this->groups_data[ $group_id ]['type'];
			}

			$this->addon_form_settings['group_interest'] = isset( $submitted_data['group_interest'] ) ? $submitted_data['group_interest'] : '';

			$interests                                     = $this->provider->get_remote_interest_options(
				$this->addon_form_settings['selected_global_multi_id'],
				$this->addon_form_settings['list_id'],
				$this->addon_form_settings['group']
			);
			$this->addon_form_settings['interest_options'] = $interests;

			$this->addon_form_settings['group_interest_placeholder'] = isset( $submitted_data['group_interest_placeholder'] ) ? $submitted_data['group_interest_placeholder'] : '';

			$this->save_form_settings_values( $this->addon_form_settings );

		}

		$buttons = array(
			'cancel' => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Back', 'hustle' ), '', 'prev', true ),
			),
			'save'   => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Continue', 'hustle' ), '', 'next', true ),
			),
		);
		return array(
			'html'    => $html,
			'buttons' => $buttons,
		);
	}


	/**
	 * Returns GDPR settings.
	 *
	 * @since 4.1.1
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	public function fourth_step_callback( $submitted_data ) {

		$this->addon_form_settings = $this->get_form_settings_values( false );

		$gdpr_fields = $this->get_gdpr_fields( $this->addon_form_settings['list_id'] );

		$is_submit = ! empty( $submitted_data );

		// check gdpr_fields
		if ( $is_submit ) {
			$selected_gdpr_fields = isset( $submitted_data['gdpr_fields'] ) ? $submitted_data['gdpr_fields'] : array();
		} elseif ( isset( $this->addon_form_settings['gdpr_fields'] ) ) {
			$selected_gdpr_fields = $this->addon_form_settings['gdpr_fields'];
		} else {
			$selected_gdpr_fields = array();
		}
		$html = '';

		if ( ! empty( $gdpr_fields ) && is_array( $gdpr_fields ) ) {
			$options = $this->get_fourth_step_options( $gdpr_fields, $selected_gdpr_fields );
			$html   .= Hustle_Provider_Utils::get_integration_modal_title_markup(
				__( 'Mailchimp GDPR Permissions', 'hustle' ),
				__( 'You can optionally opt-in the subscribers into your Mailchimp audience\'s GDPR permissions. Choose the GDPR permissions to opt-in your subscribers into.', 'hustle' )
			);
			$html   .= Hustle_Provider_Utils::get_html_for_options( $options );
		}

		if ( $is_submit ) {
			// Store the selected GDPR fields
			$this->addon_form_settings['gdpr_fields'] = $selected_gdpr_fields;
			$this->save_form_settings_values( $this->addon_form_settings );
		}

		$buttons = array(
			'cancel' => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Back', 'hustle' ), '', 'prev', true ),
			),
			'save'   => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Save', 'hustle' ), '', 'next', true ),
			),
		);
		return array(
			'html'    => $html,
			'buttons' => $buttons,
		);
	}

	/**
	 * If GDPR checkbox is selected on Email fields
	 *
	 * @return boolean
	 */
	private function is_optin_gpdr() {
		$module = new Hustle_Module_Model( $this->module_id );
		if ( is_wp_error( $module ) ) {
			return false;
		}
		$form_fields = $module->get_form_fields();

		return ! empty( $form_fields['gdpr'] );
	}

	/**
	 * Returns Mailchimp group interests list
	 *
	 * @since 4.0.3
	 *
	 * @param array $data
	 * @return string
	 */
	public function get_group_interests( $data ) {

		$this->addon_form_settings = $this->get_form_settings_values( false );

		if ( ! empty( $data['group'] ) ) {
			$group = $data['group'];
		} else {
			return '';
		}

		$interests = $this->provider->get_remote_interest_options(
			$this->addon_form_settings['selected_global_multi_id'],
			$this->addon_form_settings['list_id'],
			$group
		);

		// If no group was selected or the selected group doesn't have interests.
		if ( empty( $interests ) || ! is_array( $interests ) ) {
			return '';
		}

		if ( isset( $this->addon_form_settings['group'] ) && isset( $this->addon_form_settings['group_interest'] ) && $this->addon_form_settings['group'] === $group ) {
			$interest_id = $this->addon_form_settings['group_interest'];
			$placeholder = isset( $this->addon_form_settings['group_interest_placeholder'] ) ? $this->addon_form_settings['group_interest_placeholder'] : '';
		} else {
			$interest_id = '';
			$placeholder = '';
		}

		$groups     = $this->get_groups( $this->addon_form_settings['list_id'] );
		$groups     = wp_list_pluck( $groups, 'type', 'id' );
		$group_type = isset( $groups[ $group ] ) ? $groups[ $group ] : '';

		$options = $this->get_group_interest_options( $group_type, $interests, $interest_id, $placeholder );
		$html    = Hustle_Provider_Utils::get_html_for_options( $options );

		return $html;
	}

	/**
	 * Returns all settings and conditions for the "tags" settings.
	 * Select tags. This step is optional
	 *
	 * @since 4.0.2
	 *
	 * @param array $submitted_data
	 * @param bool  $is_submit
	 * @return array
	 */
	public function second_step_callback( $submitted_data, $is_submit ) {

		$this->addon_form_settings = $this->get_form_settings_values( false );
		$tags                      = $this->get_tags( $this->addon_form_settings['list_id'] );

		// check tags
		if ( $is_submit && isset( $submitted_data['tags'] ) ) {
			$tags_id = $submitted_data['tags'];
		} elseif ( isset( $this->addon_form_settings['tags'] ) ) {
			$tags_id = $this->addon_form_settings['tags'];
		} else {
			$tags_id = '-1';
		}

		$options = $this->get_second_step_options_tags( $tags, $tags_id );

		$html  = Hustle_Provider_Utils::get_integration_modal_title_markup(
			__( 'Mailchimp Tags', 'hustle' ),
			__( 'Mailchimp tags help you organize your contacts. You can add as many tags as you want to the subscribers.', 'hustle' )
		);
		$html .= Hustle_Provider_Utils::get_html_for_options( $options );

		$buttons = array(
			'cancel' => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Back', 'hustle' ), '', 'prev', true ),
			),
			'save'   => array(
				'markup' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Continue', 'hustle' ), '', 'next', true ),
			),
		);

		if ( $is_submit ) {

			if ( is_array( $tags_id ) ) {

				$save_tags = array();
				// Store the tag id and tag name.
				foreach ( $tags_id as $key => $tag_id ) {

					if ( '-1' === $tag_id || empty( $this->tags_data[ $tag_id ] ) ) {
						continue;
					}

					$save_tags[ $tag_id ] = esc_html( $this->tags_data[ $tag_id ] );
				}

				$this->addon_form_settings['tags'] = $save_tags;
			}

			$this->save_form_settings_values( $this->addon_form_settings );

		}

		return array(
			'html'    => $html,
			'buttons' => $buttons,
		);
	}

	/**
	 * Refresh list array via API
	 *
	 * @param object $provider
	 * @param string $global_multi_id
	 * @return array
	 */
	public function refresh_global_multi_lists( $provider, $global_multi_id ) {
		$api_key = $provider->get_setting( 'api_key', '', $global_multi_id );
		$api     = $provider->get_api( $api_key );

		$lists  = array();
		$limit  = 50;
		$offset = 0;

		do {
			$response = $api->get_lists( $offset, $limit );

			if ( is_wp_error( $response ) ) {
				$integrations_global_url = add_query_arg( 'page', Hustle_Data::INTEGRATIONS_PAGE, admin_url( 'admin.php' ) );
				$message                 = sprintf( __( 'There was an error fetching the lists. Please make sure the %1$sselected account settings%2$s are correct.', 'hustle' ), '<a href="' . $integrations_global_url . '" target="_blank">', '</a>' );

				// TODO: handle errors from here on all providers gracefully.

				return array();
			}

			$_lists = $response->lists;
			$total  = $response->total_items;
			if ( is_array( $_lists ) ) {
				$lists += wp_list_pluck( $_lists, 'name', 'id' );
			}

			$offset += $limit;
		} while ( $total > $offset );

		return $lists;
	}

	// -------------------------------------------------------
	// Getting the array of options for each step
	// -------------------------------------------------------

	/**
	 * Return an array of options used to display the settings of the 1st step.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 * @return array
	 */
	private function get_first_step_options( $submitted_data ) {

		$checked = ! isset( $submitted_data['auto_optin'] ) ? '' : $submitted_data['auto_optin'];

		$lists       = $this->get_global_multi_lists();
		$this->lists = $lists;
		try {
			$selected_list = $this->get_selected_list( $submitted_data );
		} catch ( Exception $e ) {
			return new WP_Error( 'api_error', $e->getMessage() );
		}

		$options = array(
			array(
				'type'     => 'wrapper',
				'elements' => array(
					'label'   => array(
						'type'  => 'label',
						'for'   => 'list_id',
						'value' => __( 'Email List', 'hustle' ),
					),
					'wrapper' => array(
						'type'                 => 'wrapper',
						'is_not_field_wrapper' => true,
						'class'                => 'hui-select-refresh',
						'elements'             => array(
							'lists'   => array(
								'type'     => 'select',
								'id'       => 'list_id',
								'class'    => 'sui-select',
								'name'     => 'list_id',
								'value'    => $selected_list,
								'options'  => $lists,
								'selected' => $selected_list,
							),
							'refresh' => array(
								'type'  => 'raw',
								'value' => Hustle_Provider_Utils::get_provider_button_markup( __( 'Refresh', 'hustle' ), '', 'refresh_list', true ),
							),
						),
					),
				),
			),
			array(
				'type'     => 'wrapper',
				'style'    => 'margin-bottom: 0;',
				'elements' => array(
					'label'     => array(
						'type'  => 'label',
						'value' => __( 'Extra Options', 'hustle' ),
					),
					'new_users' => array(
						'type'       => 'checkbox',
						'name'       => 'auto_optin',
						'value'      => 'subscribed',
						'id'         => 'auto_optin',
						'class'      => 'sui-checkbox-sm sui-checkbox-stacked',
						'attributes' => array(
							'checked' => ( 'subscribed' === $checked || '1' === $checked ) ? 'checked' : '',
						),
						'label'      => __( 'Automatically opt-in new users to the mailing list.', 'hustle' ),
					),
				),
			),
		);

		return $options;
	}

	/**
	 * Return an array of options used to display the settings of the 4th step.
	 *
	 * @since 4.1.1
	 *
	 * @param array  $gdpr_fields GDPR fields
	 * @param string $selected_gdpr_fields
	 * @return array
	 */
	private function get_fourth_step_options( $gdpr_fields, $selected_gdpr_fields = array() ) {

		return array(
			'gdpr_setup' => array(
				'type'     => 'wrapper',
				'style'    => 'margin-bottom: 0;',
				'elements' => array(
					'label'       => array(
						'type'  => 'label',
						'for'   => 'gdpr_fields',
						'value' => __( 'GDPR Permissions (optional)', 'hustle' ),
					),
					'gdpr_fields' => array(
						'type'     => 'checkboxes',
						'name'     => 'gdpr_fields[]',
						'value'    => $selected_gdpr_fields,
						'id'       => 'gdpr_fields',
						'class'    => 'sui-checkbox-sm sui-checkbox-stacked',
						'options'  => $gdpr_fields,
						'nonce'    => wp_create_nonce( 'hustle_mailchimp_gdpr_fields' ),
						'selected' => $selected_gdpr_fields,
					),
				),
			),
		);
	}

	/**
	 * Return an array of options used to display the settings of the 2nd step.
	 *
	 * @since 4.0
	 *
	 * @param array  $groups
	 * @param string $group_id
	 * @return array
	 */
	private function get_second_step_options( $groups, $group_id = '-1' ) {

		$options = array( '-1' => __( 'No group', 'hustle' ) );

		$groups_data = array();

		foreach ( $groups as $group_key => $group ) {
			$group = (array) $group;
			// Create an array with the proper format for the select options.
			$options[ $group['id'] ] = $group['title'] . ' ( ' . ucfirst( $group['type'] ) . ' )';

			// Create an array with the groups data to use it before saving.
			$groups_data[ $group['id'] ]['type'] = $group['type'];
			$groups_data[ $group['id'] ]['name'] = $group['title'];

		}
		$this->groups_data = $groups_data;

		if ( '-1' !== $group_id && isset( $options[ $group_id ] ) ) {
			$first = $group_id;
		} else {
			$first = Opt_In_Utils::array_key_first( $options );
		}

		return array(
			'group_id_setup' => array(
				'type'     => 'wrapper',
				'style'    => 'margin-bottom: 0;',
				'elements' => array(
					'label' => array(
						'type'  => 'label',
						'for'   => 'group',
						'value' => __( 'Group Category', 'hustle' ),
					),
					'group' => array(
						'type'     => 'select',
						'name'     => 'group',
						'value'    => $first,
						'id'       => 'group',
						'class'    => 'hustle_provider_on_change_ajax',
						'options'  => $options,
						'nonce'    => wp_create_nonce( 'hustle_mailchimp_interests' ),
						'selected' => $first,
					),
				),
			),
		);
	}

	/**
	 * Return an array of options used to display the settings of the 2nd step for tags.
	 *
	 * @since 4.0
	 *
	 * @param array  $tags
	 * @param string $tag_id
	 * @return array
	 */
	private function get_second_step_options_tags( $tags, $tag_ids = array( '-1' ) ) {

		$tags    = $tags->segments;
		$options = wp_list_pluck( $tags, 'name', 'id' );

		$this->tags_data = $options;

		if ( '-1' !== $tag_ids && is_array( $tag_ids ) ) {
			$selected = array_intersect( array_keys( $tag_ids ), array_keys( $options ) );
		} else {
			$selected = Opt_In_Utils::array_key_first( $options );
		}

		return array(
			'tag_id_setup' => array(
				'type'     => 'wrapper',
				'style'    => 'margin-bottom: 0;',
				'elements' => array(
					'label' => array(
						'type'  => 'label',
						'for'   => 'tags',
						'value' => __( 'Tags', 'hustle' ),
					),
					'tags'  => array(
						'type'       => 'multiselect',
						'name'       => 'tags[]',
						'id'         => 'tags',
						'class'      => 'sui-select',
						'options'    => $options,
						'selected'   => $selected,
						'attributes' => array( 'multiple' => 'multiple' ),
					),
				),
			),
		);
	}

	/**
	 * Return an array of options used to display the settings of Group interests.
	 *
	 * @todo use $interest_id to show the selected values if set. This can be an array if group type is checkbox.
	 *
	 * @since 4.0
	 *
	 * @param string $_type
	 * @param array  $interests
	 * @param string $interest_id
	 * @return array
	 */
	private function get_group_interest_options( $_type, $interests, $interest_id, $placeholder = '' ) {

		$interests_options = array( '-1' => __( 'No default choice', 'hustle' ) );

		// TODO: this can probably be improved
		$type = 'radio' === $_type ? 'radios' : $_type;
		$type = 'dropdown' === $type || 'hidden' === $type ? 'select' : $type;

		if ( 'select' !== $type ) {
			$interests_options = array();
		}

		$interests_options += $interests;

		$first = Opt_In_Utils::array_key_first( $interests_options );

		$field_type    = $type;
		$choose_prompt = __( 'Default Interest', 'hustle' );
		$input_name    = 'group_interest';

		switch ( $_type ) {

			case 'dropdown':
				$field_type = 'select';
				$class      = '';
				break;

			case 'checkboxes':
				$choose_prompt = __( 'Default Interest(s)', 'hustle' );
				$input_name    = 'group_interest[]';
				$class         = 'sui-checkbox-sm sui-checkbox-stacked';
				break;

			case 'radio':
				$field_type    = 'radios';
				$class         = 'sui-radio-sm sui-radio-stacked';
				$choose_prompt = sprintf(
					__( 'Default Interest %1$s(clear selection)%2$s', 'hustle' ),
					'<a href="#" class="hustle-provider-clear-radio-options" style="margin-left: 5px;" data-name="group_interest">',
					'</a>'
				);
				break;

			case 'hidden':
				$class         = '';
				$choose_prompt = __( 'Default Interest', 'hustle' );
				break;

			default:
				break;
		}

		$fields[] = array(
			'type'     => 'wrapper',
			'style'    => 'margin-top: 20px; margin-bottom: 0;',
			'elements' => array(
				'label'          => array(
					'type'  => 'label',
					'for'   => 'group_interest',
					'value' => $choose_prompt,
				),
				'group_interest' => array(
					'type'            => $field_type,
					'name'            => $input_name,
					'value'           => $first,
					'id'              => 'group_interest',
					'class'           => $class,
					'options'         => $interests_options,
					'selected'        => $interest_id,
					'item_attributes' => array(),
				),
			),
		);
		if ( 'select' === $field_type && 'hidden' !== $_type ) {
			$fields[] = array(
				'type'     => 'wrapper',
				'style'    => 'margin-top: 20px; margin-bottom: 0;',
				'class'    => 'group_interest_placeholder_wrap',
				'elements' => array(
					'label'                           => array(
						'type'  => 'label',
						'for'   => 'group_interest_placeholder',
						'value' => __( 'Placeholder', 'hustle' ),
					),
					'group_interest_placeholder'      => array(
						'type'        => 'text',
						'name'        => 'group_interest_placeholder',
						'value'       => ( empty( $placeholder ) ? __( 'Select a group', 'hustle' ) : $placeholder ),
						'id'          => 'group_interest_placeholder',
						'class'       => 'sui-form-control optin_text_text text',
						'description' => __( 'Choose a placeholder text for group dropdown field', 'hustle' ),
					),
					'group_interest_placeholder_desc' => array(
						'type'  => 'description',
						'value' => __( 'Choose a placeholder text for group dropdown field', 'hustle' ),
					),
				),
			);
		}

		return $fields;

	}


	// -------------------------------------------------------
	// Retrieving and formatting the API responses
	// -------------------------------------------------------


	/**
	 * Get the GDPR fields that belong to the given list.
	 *
	 * @param string $list_id
	 * @param string $api_key
	 * @return array
	 */
	private function get_gdpr_fields( $list_id, $api_key = '' ) {

		if ( empty( $api_key ) ) {
			$settings        = $this->get_form_settings_values( false );
			$global_multi_id = $settings['selected_global_multi_id'];
			$api_key         = $this->provider->get_setting( 'api_key', '', $global_multi_id );
		}

		try {
			$api         = $this->provider->get_api( $api_key );
			$gdpr_fields = $api->get_gdpr_fields( $list_id );

			return $gdpr_fields;

		} catch ( Exception $e ) {
			// TODO: handle exception
			return array();
		}

	}

	/**
	 * Get the groups that belong to the given list.
	 *
	 * @param string $list_id
	 * @param string $api_key
	 * @return array
	 */
	private function get_groups( $list_id, $api_key = '' ) {

		if ( empty( $api_key ) ) {
			$settings        = $this->get_form_settings_values( false );
			$global_multi_id = $settings['selected_global_multi_id'];
			$api_key         = $this->provider->get_setting( 'api_key', '', $global_multi_id );
		}

		$api = null;
		try {
			$api = $this->provider->get_api( $api_key );

			$api_categories = $api->get_interest_categories( $list_id, 50 );
			if ( is_wp_error( $api_categories ) ) {

				// TODO: handle the wp error properly.
				// Check out how it's handled in first step.
				return array();
				/*
				return array(
					array(
						"value" => "<label class='wpmudev-label--notice'><span>" . __( 'There was an error fetching the data. Please review your settings and try again.', 'hustle' ) . "</span></label>",
						"type"  => "label",
					)
				);*/
			}

			$total_groups = $api_categories->total_items;

			// If there are more groups than the ones that were retrieved, get them all.
			if ( $total_groups > count( (array) $api_categories->categories ) ) {

				if ( $total_groups < 10 ) {
					$total_groups = 10;
				}

				$groups = (array) $api->get_interest_categories( $list_id, $total_groups )->categories;
			} else {
				$groups = (array) $api_categories->categories;
			}

			return $groups;

		} catch ( Exception $e ) {
			// TODO: handle exception
			// return $e;
			return array();
		}

	}

	// -------------------------------------------------------
	// Retrieving and formatting the API responses
	// -------------------------------------------------------


	/**
	 * Get the tags on the given list.
	 *
	 * @since 4.0.2
	 *
	 * @param string $list_id
	 * @param string $api_key
	 * @return array
	 */
	private function get_tags( $list_id, $api_key = '' ) {

		if ( empty( $api_key ) ) {
			$settings        = $this->get_form_settings_values( false );
			$global_multi_id = $settings['selected_global_multi_id'];
			$api_key         = $this->provider->get_setting( 'api_key', '', $global_multi_id );
		}

		$api      = $this->provider->get_api( $api_key );
		$api_tags = $api->get_tags( $list_id );

		// error handling on first step
		return $api_tags;
	}

}