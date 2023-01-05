<?php

/**
 * Class Hustle_Zapier_Form_Hooks
 * Define the form hooks that are used by Zapier
 *
 * @since 4.0
 */
class Hustle_Zapier_Form_Hooks extends Hustle_Provider_Form_Hooks_Abstract {


	/**
	 * Add Zapier data to entry.
	 *
	 * @since 4.0
	 *
	 * @param array $submitted_data
	 *
	 * @return array
	 */
	public function add_entry_fields( $submitted_data ) {

		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;

		/**
		 * Filter submitted form data to be processed
		 *
		 * @since 4.0
		 *
		 * @param array                                         $submitted_data
		 * @param int                                           $module_id                current Form ID
		 * @param Hustle_Zapier_Form_Settings                   $form_settings_instance
		 */
		$submitted_data = apply_filters(
			'hustle_provider_zapier_form_submitted_data',
			$submitted_data,
			$module_id,
			$form_settings_instance
		);

		$hooks        = $form_settings_instance->get_form_settings_values();
		$entry_fields = array();

		/**
		 * Fires before adding subscriber
		 *
		 * @since 4.0.2
		 *
		 * @param int    $module_id
		 * @param array  $submitted_data
		 * @param object $form_settings_instance
		 */
		do_action(
			'hustle_provider_zapier_before_add_subscriber',
			$module_id,
			$submitted_data,
			$form_settings_instance
		);

		foreach ( $hooks as $key => $hook ) {
			$entry_fields[] = $this->call_hook( $key, $hook, $submitted_data );
		}

		/**
		 * Fires before adding subscriber
		 *
		 * @since 4.0.2
		 *
		 * @param int    $module_id
		 * @param array  $submitted_data
		 * @param array  $entry_fields
		 * @param object $form_settings_instance
		 */
		do_action(
			'hustle_provider_zapier_after_add_subscriber',
			$module_id,
			$submitted_data,
			$entry_fields,
			$form_settings_instance
		);

		$entry_fields = apply_filters(
			'hustle_provider_zapier_entry_fields',
			$entry_fields,
			$module_id,
			$submitted_data,
			$form_settings_instance
		);

		return $entry_fields;
	}

	private function call_hook( $key, $connection_settings, $submitted_data ) {
		$submitted_data = $this->check_legacy( $submitted_data );
		if ( empty( $connection_settings['api_key'] ) || empty( $connection_settings['name'] ) ) {
			return $this->get_status( $key );
		}

		$hook_url        = $connection_settings['api_key'];
		$connection_name = $connection_settings['name'];
		$api_response    = Hustle_Zapier_API::make_request( $hook_url, $submitted_data );

		if ( is_wp_error( $api_response ) ) {
			return $this->get_status( $key, false, $api_response->get_error_message(), $connection_name );
		} else {
			return $this->get_status(
				$key,
				true,
				esc_html__( 'Successfully sent data to Zapier', 'hustle' ),
				$connection_name
			);
		}
	}

	private function get_status( $key, $status = false, $message = '', $connection_name = '' ) {
		return array(
			'name'  => 'status-' . $key,
			'value' => array(
				'is_sent'         => $status,
				'description'     => $message,
				'connection_name' => $connection_name,
			),
		);
	}

	/**
	 * @inheritdoc
	 * @see Hustle_Provider_Form_Hooks_Abstract::on_render_entry()
	 */
	public function on_render_entry( Hustle_Entry_Model $entry_model, $addon_meta_data ) {
		$addon_slug             = $this->addon->get_slug();
		$module_id              = $this->module_id;
		$form_settings_instance = $this->form_settings_instance;

		$addon_meta_data = apply_filters(
			'hustle_provider_' . $addon_slug . '_metadata',
			$addon_meta_data,
			$module_id,
			$entry_model,
			$form_settings_instance
		);

		$entry_items = $this->format_multi_metadata_for_entry( $entry_model, $addon_meta_data );

		$entry_items = apply_filters(
			'hustle_provider_' . $addon_slug . '_entry_items',
			$entry_items,
			$module_id,
			$entry_model,
			$addon_meta_data,
			$form_settings_instance
		);

		return $entry_items;
	}

	private function format_multi_metadata_for_entry( Hustle_Entry_Model $entry_model, $addon_meta_data ) {
		$entry_items = array();
		foreach ( $addon_meta_data as $addon_meta ) {
			$entry_items[] = $this->format_single_metadata_for_entry( $addon_meta );
		}
		return $entry_items;
	}

	private function format_single_metadata_for_entry( $addon_meta_data ) {
		if ( ! isset( $addon_meta_data['value'] ) || ! is_array( $addon_meta_data['value'] ) ) {
			return array();
		}

		if ( strpos( $addon_meta_data['name'], 'status-' ) !== 0 ) {
			return array();
		}

		$status                = $addon_meta_data['value'];
		$additional_entry_item = array(
			'name'      => $this->addon->get_title(),
			'icon'      => $this->addon->get_icon_2x(),
			'data_sent' => ! empty( $status['is_sent'] ),
		);

		$sub_entries = array();
		if ( isset( $status['connection_name'] ) ) {
			$sub_entries[] = array(
				'label' => __( 'Integration Name', 'hustle' ),
				'value' => $status['connection_name'],
			);
		}

		if ( isset( $status['is_sent'] ) ) {
			$is_sent       = true === $status['is_sent'] ? __( 'Yes', 'hustle' ) : __( 'No', 'hustle' );
			$sub_entries[] = array(
				'label' => __( 'Sent To Zapier', 'hustle' ),
				'value' => $is_sent,
			);
		}

		if ( isset( $status['description'] ) ) {
			$sub_entries[] = array(
				'label' => __( 'Info', 'hustle' ),
				'value' => $status['description'],
			);
		}
		$additional_entry_item['sub_entries'] = $sub_entries;

		return $additional_entry_item;
	}
}