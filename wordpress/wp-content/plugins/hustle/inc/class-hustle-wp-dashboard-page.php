<?php
/**
 * File for Hustle_Wp_Dashboard_Page class.
 *
 * @package Hustle
 * @since 4.2.1
 */

/**
 * Class Hustle_Wp_Dashboard_Page.
 * Handles scripts and actions for the WP Dashboard Page.
 *
 * @since 4.2.1
 */
class Hustle_Wp_Dashboard_Page {

	/**
	 * Hustle's settings for the WP Dashboard analytics widget.
	 *
	 * @since 4.2.1
	 * @var array
	 */
	private $settings;

	/**
	 * Class constructor.
	 *
	 * @since 4.2.1
	 * @param array $analytics_settings Hustle's settings for the WP Dashboard analytics widget.
	 */
	public function __construct( $analytics_settings ) {

		$this->settings = $analytics_settings;

		// Load styles for Dashboard.
		add_action( 'admin_print_styles', array( $this, 'register_dashboard_styles' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_dashboard_body_class' ), 99 );

		// Load scripts for Dashboard.
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

		add_action( 'wp_dashboard_setup', array( $this, 'analytics_widget_setup' ) );

		add_action( 'wp_ajax_hustle_get_wp_dashboard_widget_data', array( $this, 'get_wp_dashboard_widget_data' ) );
	}

	/**
	 * Registers styles for admin dashboard.
	 *
	 * @since 4.2.1
	 */
	public function register_dashboard_styles() {
		wp_register_style(
			'hstl-roboto',
			'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i',
			array(),
			Opt_In::VERSION
		);

		wp_register_style(
			'hstl-opensans',
			'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i',
			array(),
			Opt_In::VERSION
		);

		wp_enqueue_style( 'hstl-roboto' );
		wp_enqueue_style( 'hstl-opensans' );

		wp_enqueue_style(
			'hustle_dashboard_styles',
			Opt_In::$plugin_url . 'assets/css/dashboard.min.css',
			array(),
			Opt_In::VERSION
		);
	}

	/**
	 * Registers scripts.
	 *
	 * @since 4.2.1
	 */
	public function register_scripts() {

		wp_enqueue_script(
			'chartjs',
			Opt_In::$plugin_url . 'assets/js/vendor/chartjs/Chart.bundle.min.js',
			array(),
			'2.7.2',
			true
		);

		wp_register_script(
			'hustle_wp_dashboard_script',
			Opt_In::$plugin_url . 'assets/js/wp-dashboard.min.js',
			array( 'jquery', 'chartjs' ),
			Opt_In::VERSION,
			true
		);

		// Days labels for the chart.
		for ( $h = 89; $h >= 0; $h-- ) {
			$time         = strtotime( '-' . $h . ' days' );
			$days_array[] = strftime( '%B %d', $time );
		}

		// These are the labels for the different tracking types.
		$tracking_actions = array(
			'view'             => esc_html__( 'Views', 'hustle' ),
			'conversion'       => esc_html__( 'All Conversions', 'hustle' ),
			'cta_conversion'   => esc_html__( 'CTA Conversions', 'hustle' ),
			'optin_conversion' => esc_html__( 'Optin Conversions', 'hustle' ),
			'rate'             => esc_html__( 'Conversion Rate', 'hustle' ),
		);

		$data = array(
			'days_labels'         => $days_array,
			'tracking_actions'    => $tracking_actions,
			'active_module_types' => $this->settings['modules'],
		);

		wp_localize_script( 'hustle_wp_dashboard_script', 'hustleVars', $data );
		wp_enqueue_script( 'hustle_wp_dashboard_script' );
	}

	/**
	 * Modify admin dashboard body class to our own advantage.
	 *
	 * @since 4.2.1
	 *
	 * @param string $classes Current classes.
	 * @return mixed
	 */
	public function admin_dashboard_body_class( $classes ) {
		$classes .= ' sui-hustle-dashboard';

		return $classes;

	}

	/**
	 * Setup the Hustle analytics dashboard widgets.
	 *
	 * @since 4.1.0
	 */
	public function analytics_widget_setup() {

		$title = ! empty( $this->settings['title'] ) ? $this->settings['title'] : ' ';
		wp_add_dashboard_widget(
			'hustle_analytics',
			esc_html( $title ),
			array( $this, 'render_analytics_widget' )
		);
	}

	/**
	 * Outputs the Analytics dashboard widget.
	 *
	 * @since 4.1.0
	 *
	 * @param string $post Widget Control ID.
	 */
	public function render_analytics_widget( $post ) {

		$renderer = new Hustle_Layout_Helper( $this );
		$renderer->render(
			'admin/widget-analytics',
			array(
				'settings' => $this->settings,
			)
		);
	}

	/**
	 * Get analytics ranges for dashboard widget
	 *
	 * @since 4.2.1
	 * @return array
	 */
	public function get_analytic_ranges() {
		$ranges = array(
			7  => __( 'Last 7 days', 'hustle' ),
			30 => __( 'Last 30 days', 'hustle' ),
			90 => __( 'Last 90 days', 'hustle' ),
		);

		return $ranges;
	}

	/**
	 * Retrieves the data for the widget via AJAX.
	 *
	 * @since 4.2.1
	 */
	public function get_wp_dashboard_widget_data() {

		Opt_In_Utils::validate_ajax_call( 'hustle_update_wp_dashboard_chart' );

		$days_range    = filter_input( INPUT_POST, 'days', FILTER_VALIDATE_INT );
		$tracking_type = filter_input( INPUT_POST, 'trackingType', FILTER_SANITIZE_STRING );

		$module_types_to_display = $this->settings['modules'];

		// The required fields are missing. Abort.
		if ( ! $days_range || ! $tracking_type || empty( $module_types_to_display ) ) {
			wp_send_json_error();
		}

		$allowed_ranges = array_keys( $this->get_analytic_ranges() );
		$days_range     = in_array( $days_range, $allowed_ranges, true ) ? $days_range : 7;

		// Try to retrieve the cached data.
		$transient_key = 'hustle_wp_widget_daily_stats_' . $days_range;
		// $widget_data   = get_transient( $transient_key );

		// Retrieve the data if not cached.
		// if ( false === $widget_data ) {

			$widget_data = $this->get_formatted_tracking_data( $days_range );

			// Cache for today.
			// The transient expires tonight at midnight, following WP's timezone.
			// $timezone        = wp_timezone();
			// $date            = new DateTime( 'tomorrow', $timezone );
			// $trasient_expire = $date->format( 'U' ) - time();
			// set_transient( $transient_key, $widget_data, $trasient_expire );
		// }

		wp_send_json_success( $widget_data );
	}

	/**
	 * Gets the data formatted as it's expected for the chart.
	 *
	 * @since 4.2.1
	 *
	 * @param integer $days_range Days before today.
	 * @return array
	 */
	private function get_formatted_tracking_data( $days_range ) {

		$tracking = Hustle_Tracking_Model::get_instance();

		// Daily stats for the period.
		$daily_stats = $this->get_and_format_daily_stats( $days_range, $tracking );

		// Totals from the previous range.
		$previous_range_totals = $this->get_and_format_prev_range_totals( $days_range, $tracking );

		// Overall totals to show, including the growth rate.
		$totals = $this->get_analytics_totals( $daily_stats, $previous_range_totals );

		$widget_data = array(
			'data'   => $daily_stats,
			'totals' => $totals,
		);

		return $widget_data;
	}

	/**
	 * Gets the raw values for the daily stats and formats it.
	 *
	 * @since 4.2.1
	 *
	 * @param integer               $days_range Number of days before today.
	 * @param Hustle_Tracking_Model $tracking Instance of the tracking model.
	 * @return array
	 */
	private function get_and_format_daily_stats( $days_range, $tracking ) {

		$raw_result = $tracking->get_wp_dash_daily_stats_data( $days_range );
		$final_data = $this->get_default_analytics_stats( $days_range );

		foreach ( $raw_result as $data ) {

			$module_type = $data['module_type'];
			$action      = $data['action'];

			$day = explode( ' ', $data['date_created'] )[0];

			// Use the default days as the base. Skip the current day if it's not defined.
			if ( ! isset( $final_data[ $module_type ][ $action ][ $day ] ) ) {
				continue;
			}
			$final_data[ $module_type ][ $action ][ $day ] += $data['counter'];
			$final_data['overall'][ $action ][ $day ]      += $data['counter'];

			// We have 2 conversion types: CTA and Optin. We also have an overall
			// count for conversions under simply 'conversion'. Sum these for that metric.
			if ( 'optin_conversion' === $action || 'cta_conversion' === $action ) {
				$final_data[ $module_type ]['conversion'][ $day ] += $data['counter'];
				$final_data['overall']['conversion'][ $day ]      += $data['counter'];
			}

			// Module types for embeds and ssharing module have the display type suffixed.
			// Map these to their main module type.
			if ( 0 === strpos( $module_type, 'embedded_' ) ) {
				$final_data['embedded'][ $action ][ $day ] += $data['counter'];

				// Same check as before: We merge CTA and Optin conversions into the 'conversion' key.
				if ( 'optin_conversion' === $action || 'cta_conversion' === $action ) {
					$final_data['embedded']['conversion'][ $day ] += $data['counter'];
				}
			}
			if ( 0 === strpos( $module_type, 'social_sharing_' ) ) {
				$final_data['social_sharing'][ $action ][ $day ] += $data['counter'];
			}
		}

		// Count rates.
		foreach ( $final_data as $block => $types ) {

			// Calculate the rates.
			foreach ( $types as $type => $days ) {
				if ( 'rate' !== $type ) {
					continue;
				}
				foreach ( $days as $day => $val ) {
					if ( ! empty( $final_data[ $block ]['view'][ $day ] ) && ! empty( $final_data[ $block ]['conversion'][ $day ] ) ) {
						$final_data[ $block ]['rate'][ $day ] = round( 100 * $final_data[ $block ]['conversion'][ $day ] / $final_data[ $block ]['view'][ $day ], 2 );
					}
				}
			}
		}

		return $final_data;
	}

	/**
	 * Gets default analytics stats with zero values.
	 *
	 * @since 4.1.0
	 *
	 * @param int $days_ago Number of days ago when to begin the stats.
	 * @return array
	 */
	private function get_default_analytics_stats( $days_ago ) {
		$days = array();
		$days_ago--;

		for ( $i = $days_ago; 0 <= $i; $i-- ) {
			$days[] = date( 'Y-m-d', time() - $i * DAY_IN_SECONDS ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		}

		$all_blocks = array(
			'overall',
			'popup',
			'slidein',
			'embedded',
			'embedded_inline',
			'embedded_widget',
			'embedded_shortcode',
			'social_sharing',
			'social_sharing_floating',
			'social_sharing_inline',
			'social_sharing_widget',
			'social_sharing_shortcode',
		);

		$all_types  = array(
			'view',
			'conversion',
			'cta_conversion',
			'optin_conversion',
			'rate',
		);
		$final_data = array();
		foreach ( $all_blocks as $block ) {
			foreach ( $all_types as $type ) {
				foreach ( $days as $day ) {
					$final_data[ $block ][ $type ][ $day ] = 0;
				}
			}
		}

		return $final_data;
	}

	/**
	 * Gets the raw values for the previous range totals and formats it.
	 * This is used to define the growth rate.
	 *
	 * @since 4.2.1
	 *
	 * @param integer               $days_range Number of days before today.
	 * @param Hustle_Tracking_Model $tracking Instance of the tracking model.
	 * @return array
	 */
	private function get_and_format_prev_range_totals( $days_range, $tracking ) {

		$module_types_map = array(
			'popup'                    => Hustle_Module_Model::POPUP_MODULE,
			'slidein'                  => Hustle_Module_Model::SLIDEIN_MODULE,
			'embedded_inline'          => Hustle_Module_Model::EMBEDDED_MODULE,
			'embedded_shortcode'       => Hustle_Module_Model::EMBEDDED_MODULE,
			'embedded_widget'          => Hustle_Module_Model::EMBEDDED_MODULE,
			'social_sharing_inline'    => Hustle_Module_Model::SOCIAL_SHARING_MODULE,
			'social_sharing_shortcode' => Hustle_Module_Model::SOCIAL_SHARING_MODULE,
			'social_sharing_widget'    => Hustle_Module_Model::SOCIAL_SHARING_MODULE,
			'social_sharing_floating'  => Hustle_Module_Model::SOCIAL_SHARING_MODULE,
		);

		$default_action_counters = array(
			'view'             => 0,
			'conversion'       => 0,
			'cta_conversion'   => 0,
			'optin_conversion' => 0,
			'rate'             => 0,
		);

		$main_counter = array(
			'overall'                                  => $default_action_counters,
			Hustle_Module_Model::POPUP_MODULE          => $default_action_counters,
			Hustle_Module_Model::SLIDEIN_MODULE        => $default_action_counters,
			Hustle_Module_Model::EMBEDDED_MODULE       => $default_action_counters,
			Hustle_Module_Model::SOCIAL_SHARING_MODULE => $default_action_counters,
		);

		// Get the raw data from the tracking model.
		$raw_result = $tracking->get_per_module_type_totals_prev_range( $days_range );

		// Loop through the results, which are grouped by module type.
		// The possible module types are the keys from the array $module_types_map.
		foreach ( $raw_result as $data ) {

			// Not a valid module type, abort.
			if ( empty( $module_types_map[ $data->module_type ] ) ) {
				continue;
			}

			// Not a valid action, abort.
			if ( ! isset( $default_action_counters[ $data->action ] ) ) {
				continue;
			}

			$module_type = $module_types_map[ $data->module_type ];
			$action      = $data->action;
			$count       = $data->tracked_count;

			$main_counter[ $module_type ][ $action ] += $count;

			$main_counter['overall'][ $action ] += $count;
		}

		// Let's now calculate the total conversions (CTA + Optin), the conversion rate, and overalls.
		foreach ( $main_counter as $module_type => $action_counter ) {

			$conversion_count = $action_counter['optin_conversion'] + $action_counter['cta_conversion'];

			$rate        = 0;
			$total_views = $action_counter['view'];
			if ( $total_views && $conversion_count ) {
				$rate = round( ( $conversion_count / $total_views ) * 100, 2 );
			}

			$main_counter[ $module_type ]['rate'] = $rate;

			$main_counter[ $module_type ]['conversion'] = $conversion_count;
		}

		return $main_counter;
	}

	/**
	 * Calculate the totals from the numbers we already got.
	 * Here you get the per module total for each tracking
	 * action (cta conversion, optin conversion, view),
	 * as well as the growth rate compared to the previous
	 * period of the same current length.
	 *
	 * @since 4.2.1
	 *
	 * @param array $stats The per day tracking data already retrieved for the chart.
	 * @param array $previous_range_totals The totals for the previous range.
	 * @return array
	 */
	private function get_analytics_totals( $stats, $previous_range_totals ) {

		$total_count = array();
		foreach ( $stats as $module_type => $action_data ) {

			// Skip the module types with their display type attached. We won't be using these.
			if ( 0 === strpos( $module_type, 'embedded_' ) || 0 === strpos( $module_type, 'social_sharing_' ) ) {
				continue;
			}

			foreach ( $action_data as $tracking_action => $data ) {

				// We'll handle the conversion rate in a sec.
				if ( 'rate' === $tracking_action ) {
					continue;
				}

				$prev_period_total = $previous_range_totals[ $module_type ][ $tracking_action ];
				$current_total     = array_sum( $data );
				$growth            = $this->calculate_growth( $current_total, $prev_period_total );

				$total_count[ $module_type ][ $tracking_action ] = array(
					'total' => $current_total,
					'trend' => $growth,
				);

			}

			// Let's calculate the conversion rate.
			$rate              = 0;
			$total_views       = $total_count[ $module_type ]['view']['total'];
			$total_conversions = $total_count[ $module_type ]['conversion']['total'];
			if ( $total_views && $total_conversions ) {
				$rate = round( ( $total_conversions / $total_views ) * 100, 2 );
			}
			$prev_period_rate = $previous_range_totals[ $module_type ]['rate'];
			$rate_growth      = $this->calculate_growth( $rate, $prev_period_rate );

			$total_count[ $module_type ]['rate'] = array(
				'total' => $rate,
				'trend' => $rate_growth,
			);
		}

		return $total_count;
	}

	/**
	 * Calculates the growth between two values.
	 *
	 * @since 4.2.1
	 *
	 * @param int $current_total Value of the current range.
	 * @param int $prev_period_total Value of the previous range.
	 * @return int
	 */
	private function calculate_growth( $current_total, $prev_period_total ) {

		$growth = 0;
		if ( $current_total && $prev_period_total ) {

			$growth = ( $current_total - $prev_period_total ) / $prev_period_total * 100;
			$growth = round( $growth, 2 );
		}
		return $growth;
	}
}