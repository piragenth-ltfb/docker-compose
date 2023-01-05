<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Hustle_General_Data_Protection
 *
 * @since 4.0.2
 */
class Hustle_General_Data_Protection {

	/**
	 * Clean up interval in string
	 *
	 * @var string
	 */
	protected $cron_cleanup_interval;

	/**
	 * Privacy settings array
	 *
	 * @var array
	 */
	private static $_privacy_settings = array();


	public function __construct( $cron_cleanup_interval = 'hourly' ) {
		$this->cron_cleanup_interval = $cron_cleanup_interval;
		$this->init();
	}

	protected function init() {

		// for data removal / anonymize data
		if ( ! wp_next_scheduled( 'hustle_general_data_protection_cleanup' ) ) {
			wp_schedule_event( time(), $this->get_cron_cleanup_interval(), 'hustle_general_data_protection_cleanup' );
		}

		add_action( 'hustle_general_data_protection_cleanup', array( $this, 'personal_data_cleanup' ) );
		add_filter( 'wp_privacy_personal_data_erasers', array( $this, 'register_eraser' ), 10 );
		add_filter( 'wp_privacy_personal_data_exporters', array( $this, 'register_exporter' ), 10 );

	}

	/**
	 * Append registered eraser to wp eraser
	 *
	 * @param array $erasers
	 *
	 * @since   4.0.2
	 *
	 * @return array
	 */
	public function register_eraser( $erasers = array() ) {
		$erasers['hustle-module-submissions'] = array(
			'eraser_friendly_name' => __( 'Hustle Module Submissions', 'hustle' ),
			'callback'             => array( 'Hustle_General_Data_Protection', 'do_submissions_eraser' ),
		);
		return $erasers;
	}

	/**
	 * Append registered eraser to wp eraser
	 *
	 * @param array $erasers
	 *
	 * @since   4.0.2
	 *
	 * @return array
	 */
	public function register_exporter( $exporter = array() ) {
		$exporter['hustle-module-submissions'] = array(
			'exporter_friendly_name' => __( 'Hustle Module Submissions', 'hustle' ),
			'callback'               => array( 'Hustle_General_Data_Protection', 'do_submissions_exporter' ),
		);
		return $exporter;
	}

	/**
	 * Get Interval
	 *
	 * @since   4.0.2
	 *
	 * @return string
	 */
	public function get_cron_cleanup_interval() {
		$cron_cleanup_interval = $this->cron_cleanup_interval;

		/**
		 * Filter interval to be used for cleanup process
		 *
		 * @since  4.0.2
		 *
		 * @params string $cron_cleanup_interval interval in string (daily,hourly, etc)
		 */
		$cron_cleanup_interval = apply_filters( 'hustle_general_data_cleanup_interval', $cron_cleanup_interval );

		return $cron_cleanup_interval;
	}

	/**
	 * Eraser
	 *
	 * @since 4.0.2
	 *
	 * @param $email
	 * @param $page
	 *
	 * @return array
	 */
	public static function do_submissions_eraser( $email, $page ) {

		$settings = self::_get_privacy_settings();

		$erasure_disabled = '1' === $settings['retain_sub_on_erasure'];

		$response = array(
			'items_removed'  => false,
			'items_retained' => true,
			'messages'       => array(),
			'done'           => true,
		);

		if ( true === $erasure_disabled ) {

			$response['messages'][] = __( 'Hustle submissions were retained.', 'hustle' );
			return $response;
		}

		$entry_ids = Hustle_Entry_Model::get_entries_by_email( $email );

		// using action instead of filter here to stop data manipulation
		do_action( 'hustle_before_submission_eraser', $email, $page, $entry_ids );

		if ( ! empty( $entry_ids ) ) {
			foreach ( $entry_ids as $entry_id ) {
				$entry_model = new Hustle_Entry_Model( $entry_id );
				Hustle_Entry_Model::delete_by_entry( $entry_model->module_id, $entry_id );
				$response['messages'][] = sprintf( __( 'Hustle submission #%d was deleted.', 'hustle' ), $entry_id );

			}
			$response['items_removed']  = true;
			$response['items_retained'] = false;
		} else {
			$response['messages'][] = __( ' Hustle submissions not found.', 'hustle' );
		}

		// using action instead of filter here to stop data manipulation
		do_action( 'hustle_after_submission_eraser', $email, $page, $entry_ids );

		return $response;
	}

	/**
	 * Export module submissions
	 *
	 * @since 4.0.2
	 *
	 * @param $email
	 * @param $page
	 *
	 * @return array
	 */
	public static function do_submissions_exporter( $email, $page ) {
		$entry_ids      = Hustle_Entry_Model::get_entries_by_email( $email );
		$data_to_export = array();

		if ( ! empty( $entry_ids ) && is_array( $entry_ids ) ) {
			foreach ( $entry_ids as $entry_id ) {
				$entry_model = new Hustle_Entry_Model( $entry_id );

				$data = array();

				if ( is_object( $entry_model ) ) {
					$data = self::get_custom_form_export_mappers( $entry_model );
				}

				$data_to_export[] = array(
					'group_id'    => 'hustle_module_submissions',
					'group_label' => __( 'Hustle Module Submissions', 'hustle' ),
					'item_id'     => 'entry-' . $entry_id,
					'data'        => $data,
				);
			}
		}

		/**
		 * Filter Export data for Custom form submission on tools.php?page=export_personal_data
		 *
		 * @since 4.0.2
		 *
		 * @param array  $data_to_export
		 * @param string $email
		 * @param array  $entry_ids
		 */
		$data_to_export = apply_filters( 'hustle_module_submissions_export_data', $data_to_export, $email, $entry_ids );

		return array(
			'data' => $data_to_export,
			'done' => true,
		);
	}

	/**
	 * Get data mappers and their values
	 *
	 * @since   4.0.2
	 *
	 * @param Hustle_Entry_Model $model
	 *
	 * @return array
	 */
	public static function get_custom_form_export_mappers( $model ) {

		$ignored_field_types = Hustle_Entry_Model::ignored_fields();
		$meta                = $model->meta_data;

		$mappers = array(
			array(
				'name'  => __( 'Entry ID', 'hustle' ),
				'value' => $model->entry_id,
			),
			array(
				'name'  => __( 'Submission Date', 'hustle' ),
				'value' => $model->date_created_sql,
			),
		);

		if ( ! empty( $meta ) ) {
			foreach ( $meta as $key => $value ) {
				// base mapper for every field
				if ( is_array( $value['value'] ) ) {
					continue;
				}

				$mapper             = array();
				$mapper['meta_key'] = $key;
				$mapper['name']     = $key;
				$mapper['value']    = $value['value'];

				if ( ! empty( $mapper ) ) {
					$mappers[] = $mapper;
				}
			}
		}

		return $mappers;
	}

	/**
	 * Anonymizing data
	 *
	 * @since 4.0.2
	 *
	 * @return bool
	 */
	public function personal_data_cleanup() {

		$settings = self::_get_privacy_settings();

		$this->_cleanup_submissions( $settings );
		$this->_cleanup_ip_address( $settings );
		$this->_cleanup_tracking_data( $settings );

		return true;
	}

	/**
	 * Clean up form submissions
	 *
	 * @since 4.0.2
	 *
	 * @param privacy settings $settings
	 *
	 * @return bool
	 */
	private function _cleanup_submissions( $settings ) {

		$retain_number = $settings['submissions_retention_number'];
		$retain_unit   = $settings['submissions_retention_number_unit'];

		if ( '1' === $settings['retain_submission_forever'] || 0 === $retain_number ) {
			return false;
		}

		$possible_units = array(
			'days',
			'weeks',
			'months',
			'years',
		);

		if ( ! in_array( $retain_unit, $possible_units, true ) ) {
			return false;
		}

		$retain_time = strtotime( '-' . $retain_number . ' ' . $retain_unit, current_time( 'timestamp' ) );
		$retain_time = date_i18n( 'Y-m-d H:i:s', $retain_time );

		$entry_ids = Hustle_Entry_Model::get_older_entry_ids( $retain_time );

		foreach ( $entry_ids as $entry_id ) {
			$entry_model = new Hustle_Entry_Model( $entry_id );
			Hustle_Entry_Model::delete_by_entry( $entry_model->module_id, $entry_id );
		}

		return true;
	}

	/**
	 * Cleanup IP Address based on settings
	 *
	 * @since 4.0.2
	 *
	 * @param privacy settings $settings
	 *
	 * @return bool
	 */
	private function _cleanup_ip_address( $settings ) {

		$retain_number = $settings['ip_retention_number'];
		$retain_unit   = $settings['ip_retention_number_unit'];

		if ( '1' === $settings['retain_ip_forever'] || 0 === $retain_number ) {
			return false;
		}

		$possible_units = array(
			'days',
			'weeks',
			'months',
			'years',
		);

		if ( ! in_array( $retain_unit, $possible_units, true ) ) {
			return false;
		}

		$retain_time = strtotime( '-' . $retain_number . ' ' . $retain_unit, current_time( 'timestamp' ) );
		$retain_time = date_i18n( 'Y-m-d H:i:s', $retain_time );

		$entry_ids    = Hustle_Entry_Model::get_older_entry_ids( $retain_time );
		$tracking_ids = Hustle_Tracking_Model::get_older_tracking_ids( $retain_time );

		foreach ( $entry_ids as $entry_id ) {
			$entry_model = new Hustle_Entry_Model( $entry_id );
			$this->_anonymize_entry_model( $entry_model );
		}

		foreach ( $tracking_ids as $tracking_id ) {
			$this->_anonymize_tracking_model( $tracking_id );
		}

		return true;
	}

	/**
	 * Anon Entry model IP
	 *
	 * @since 4.0.2
	 *
	 * @param Hustle_Entry_Model $entry_model
	 */
	private function _anonymize_entry_model( Hustle_Entry_Model $entry_model ) {
		if ( isset( $entry_model->meta_data['hustle_ip'] ) ) {
			$meta_id    = $entry_model->meta_data['hustle_ip']['id'];
			$meta_value = $entry_model->meta_data['hustle_ip']['value'];

			if ( function_exists( 'wp_privacy_anonymize_ip' ) ) {
				$anon_value = wp_privacy_anonymize_ip( $meta_value );
			} else {
				$anon_value = '';
			}

			if ( $anon_value !== $meta_value ) {
				$entry_model->update_meta( $meta_id, 'hustle_ip', $anon_value );
			}
		}
	}

	/**
	 * Cleanup tracking data
	 *
	 * @since 4.0.2
	 * @param privacy settings $settings
	 * @return bool
	 */
	private function _cleanup_tracking_data( $settings ) {

		$retain_number = $settings['tracking_retention_number'];
		$retain_unit   = $settings['tracking_retention_number_unit'];

		if ( '1' === $settings['retain_tracking_forever'] || 0 === $retain_number ) {
			return false;
		}

		$possible_units = array(
			'days',
			'weeks',
			'months',
			'years',
		);

		if ( ! in_array( $retain_unit, $possible_units, true ) ) {
			return false;
		}

		$retain_time = strtotime( '-' . $retain_number . ' ' . $retain_unit, current_time( 'timestamp' ) );
		$retain_time = date_i18n( 'Y-m-d H:i:s', $retain_time );

		$tracking_ids = Hustle_Tracking_Model::get_older_tracking_ids( $retain_time );

		foreach ( $tracking_ids as $tracking_id ) {
			Hustle_Tracking_Model::delete_data_by_tracking_id( $tracking_id );
		}

		return true;
	}

	/**
	 * Get privacy settings
	 *
	 * @since 4.0.2
	 *
	 * @return settings array()
	 */
	private static function _get_privacy_settings() {
		if ( empty( self::$_privacy_settings ) ) {
			self::$_privacy_settings = Hustle_Settings_Admin::get_privacy_settings();
		}
		return self::$_privacy_settings;
	}

	/**
	 * Anon Tracking model IP
	 *
	 * @since 4.0.2
	 *
	 * @param tracking id $tracking
	 */
	private function _anonymize_tracking_model( $tracking ) {
		if ( ! empty( $tracking ) ) {

			$ip = Hustle_Tracking_Model::get_ip_from_tracking_id( $tracking );

			if ( ! empty( $ip ) ) {

				if ( function_exists( 'wp_privacy_anonymize_ip' ) ) {
					$anon_value = wp_privacy_anonymize_ip( $ip[0] );
				} else {
					$anon_value = '';
				}

				Hustle_Tracking_Model::anonymise_tracked_id( $tracking, $anon_value );
			}
		}
	}

}