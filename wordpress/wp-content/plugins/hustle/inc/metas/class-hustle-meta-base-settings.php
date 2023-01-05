<?php
/**
 * File for Hustle_Meta_Base_Content class.
 *
 * @package Hustle
 * @since 4.2.0
 */

/**
 * Hustle_Meta_Base_Settings is the base class for the "settings" meta of modules.
 * It's extended by popup, slidein, and embed modules.
 * This class should handle what's related to the "settings" meta.
 */
class Hustle_Meta_Base_Settings extends Hustle_Meta {

	/**
	 * Returns the defaults for merging purposes.
	 * Avoid overwritting the triggers when the saved value is an empty array.
	 *
	 * @since 4.4.1
	 *
	 * @return array
	 */
	protected function get_defaults_for_merge() {
		$defaults = $this->get_defaults();

		// Avoid overwritting the saved form elements when the default fields aren't present.
		if ( ! empty( $this->data['triggers'] ) && isset( $defaults['triggers']['trigger'] ) && is_array( $this->data['triggers']['trigger'] ) ) {
			unset( $defaults['triggers']['trigger'] );
		}
		return $defaults;
	}

	/**
	 * Retrieves the base defaults for the 'settings' meta.
	 * Extended by embeds, popups, and slideins.
	 *
	 * @since 4.0.2
	 * @return array
	 */
	public function get_defaults() {
		return array(
			'auto_close_success_message' => '0',
			'triggers'                   => array(
				'trigger'                     => array( 'time' ),
				'on_time_delay'               => '0',
				'on_time_unit'                => 'seconds',
				'on_scroll'                   => 'scrolled',
				'on_scroll_page_percent'      => 20,
				'on_scroll_css_selector'      => '',
				'enable_on_click_element'     => '1',
				'on_click_element'            => '',
				'enable_on_click_shortcode'   => '1',
				'on_exit_intent_per_session'  => '1',
				'on_exit_intent_delayed_time' => '0',
				'on_exit_intent_delayed_unit' => 'seconds',
				'on_adblock_delay'            => '0',
				'on_adblock_delay_unit'       => 'seconds',
			),
			'animation_in'               => 'no_animation',
			'animation_out'              => 'no_animation',
			'after_close_trigger'        => array( 'click_close_icon' ),
			'after_close'                => 'keep_show',
			'expiration'                 => 365,
			'expiration_unit'            => 'days',
			'on_submit'                  => 'nothing', // close | default |nothing | redirect.
			'on_submit_delay'            => '5',
			'on_submit_delay_unit'       => 'seconds',
			'close_cta'                  => '0',
			'close_cta_time'             => '0',
			'close_cta_unit'             => 'seconds',
			'hide_after_cta'             => 'keep_show', // keep_show | no_show_on_post | no_show_all.
			'hide_after_subscription'    => 'keep_show', // keep_show | no_show_on_post | no_show_all.

			'is_schedule'                => '0',

			'schedule'                   => array(
				'not_schedule_start'        => '1',
				'start_date'                => date( 'm/d/Y', strtotime( 'tomorrow' ) ),
				'start_hour'                => '12',
				'start_minute'              => '00',
				'start_meridiem_offset'     => 'am',

				'not_schedule_end'          => '1',
				'end_date'                  => date( 'm/d/Y', strtotime( '+7 days' ) ),
				'end_hour'                  => '11',
				'end_minute'                => '59',
				'end_meridiem_offset'       => 'pm',

				'active_days'               => 'all', // all | week_days.
				'week_days'                 => array(),

				'is_active_all_day'         => '1',
				'day_start_hour'            => '00',
				'day_start_minute'          => '00',
				'day_start_meridiem_offset' => 'am',

				'day_end_hour'              => '11',
				'day_end_minute'            => '59',
				'day_end_meridiem_offset'   => 'pm',

				'time_to_use'               => 'server', // server | custom.
				'custom_timezone'           => 'UTC',
			),
		);
	}

	// ****************************************
	// SCHEDULE.
	// ****************************************
	/**
	 * Whether this module will be shown any time in the future.
	 * Used in dashboard to display a notice if it won't.
	 *
	 * @since 4.2.0
	 * @return bool
	 */
	public function will_be_shown_again() {

		$is_scheduled = $this->is_currently_scheduled();

		if ( ! $is_scheduled ) {
			$flags = $this->module->get_schedule_flags();

			// The module isn't shown now and won't be checked to show later on.
			if ( '0' === $flags['check_schedule_at'] ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Returns whether the "schedule" setting allows this module to be displayed
	 *
	 * @since 4.2.0
	 * @return boolean
	 */
	public function is_currently_scheduled() {

		$schedule_flags = $this->module->get_schedule_flags();

		$is_active         = '1' === $schedule_flags['is_currently_scheduled'];
		$check_schedule_at = $schedule_flags['check_schedule_at'];
		$current_time      = time();
		$skip_cache        = false;

		/**
		 * Whether to use cached flags to check schedule settings.
		 * Useful for debugging.
		 *
		 * @since 4.2.0
		 *
		 * @param bool                $skip_cache Set to true if you want the schedule to be checked on each run.
		 * @param Hustle_Module_Model $module
		 */
		$skip_cache = apply_filters( 'hustle_skip_schedule_cache', $skip_cache, $this->module );

		// Run the check if the flag isn't set to "don't check again"
		// and the time the "schedule" should be checked again already passed.
		if ( $skip_cache || ( '0' !== $check_schedule_at && $current_time > $check_schedule_at ) ) {
			$is_active = $this->check_schedule();
		}

		/**
		 * Filter whether the schedule allows this module to be displayed.
		 *
		 * @since 4.2.0
		 *
		 * @param bool $is_currently_scheduled Whether the module should be shown.
		 * @param Hustle_Module_Model $module This module's instance.
		 */
		return apply_filters( 'hustle_module_is_currently_scheduled', $is_active, $this->module );
	}

	/**
	 * Returns the "schedule" check and saves the "schedule" flags for future use
	 *
	 * @since 4.2.0
	 * @return bool
	 */
	private function check_schedule() {

		$flags = $this->get_schedule_flags();

		/**
		 * Filter the schedule flags.
		 * Change their values before it's stored and before it's used
		 * to define whether the module should be shown in frontend.
		 *
		 * @since 4.2.0
		 *
		 * @param array $flags Schedule flags.
		 * [
		 *   is_currently_scheduled => 1|0,          // As strings. 1 to display the module, 0 otherwise.
		 *   check_schedule_at      => timestamp|0   // As strings. Next time the schedule will be checked. 0 to skip check.
		 * ]
		 * @param Hustle_Meta_Base_Settings $this
		 */
		$flags = apply_filters( 'hustle_get_schedule_flags', $flags, $this );

		// Store the flags for future use.
		$this->module->set_schedule_flags( $flags );

		return ( '1' === $flags['is_currently_scheduled'] );
	}

	/**
	 * Returns the 'schedule flags' to be stored.
	 *
	 * @since 4.2.0
	 * @return array
	 */
	private function get_schedule_flags() {

		$settings = $this->to_array();

		// Schedule is deactivated. Show it right away.
		if ( '0' === $settings['is_schedule'] ) {

			// Skip schedule check in next runs.
			return array(
				'is_currently_scheduled' => '1',
				'check_schedule_at'      => '0',
			);
		}

		$schedule = $settings['schedule'];

		$start_strtotime_str         = "{$schedule['start_date']} {$schedule['start_hour']}:{$schedule['start_minute']} {$schedule['start_meridiem_offset']}";
		$schedule['start_timestamp'] = $this->get_time_with_timezone( $start_strtotime_str );

		$end_strtotime_str         = "{$schedule['end_date']} {$schedule['end_hour']}:{$schedule['end_minute']} {$schedule['end_meridiem_offset']}";
		$schedule['end_timestamp'] = $this->get_time_with_timezone( $end_strtotime_str );

		$is_within_date_range = $this->check_schedule_date_range( $schedule );

		// We're not within the active date ranges. Return the flags to be set.
		if ( true !== $is_within_date_range ) {
			return $is_within_date_range;
		}

		$check_daily_range = $this->check_schedule_daily_range( $schedule );

		// There's no daily range.
		if ( false === $check_daily_range ) {

			// Run the next check at the end of the date range, if set.
			$end_timestamp = '1' !== $schedule['not_schedule_end'] ? $schedule['end_timestamp'] : '0';
			return array(
				'is_currently_scheduled' => '1',
				'check_schedule_at'      => $end_timestamp,
			);
		}

		return $check_daily_range;
	}

	/**
	 * Checks whether we're within the date range.
	 *
	 * @since 4.2.0
	 * @param array $schedule Stored 'schedule' settings.
	 * @return array|true Array if the module isn't active. True otherwise.
	 */
	private function check_schedule_date_range( $schedule ) {

		$current_time = time();

		// End is scheduled.
		if ( '1' !== $schedule['not_schedule_end'] ) {

			$end_timestamp = $schedule['end_timestamp'];

			// End moment already passed.
			if ( $current_time > $end_timestamp ) {

				// Skip schedule check in the future.
				return array(
					'is_currently_scheduled' => '0',
					'check_schedule_at'      => '0',
				);
			}
		}

		// Start is scheduled.
		if ( '1' !== $schedule['not_schedule_start'] ) {

			$start_timestamp = $schedule['start_timestamp'];

			// Start moment hasn't passed yet.
			if ( $current_time < $start_timestamp ) {

				// Run the schedule check again then.
				return array(
					'is_currently_scheduled' => '0',
					'check_schedule_at'      => $start_timestamp,
				);
			}
		}

		return true;
	}

	/**
	 * Check out the daily time range.
	 *
	 * @since 4.2.0
	 * @param array $schedule Stored 'schedule' settings.
	 * @return array|false Array if there's a 'next check' scheduled. False otherwise.
	 */
	private function check_schedule_daily_range( $schedule ) {

		$check_day  = 'all' !== $schedule['active_days'] && ! empty( $schedule['week_days'] );
		$check_hour = '1' !== $schedule['is_active_all_day'];

		// The module is displayed all day every day.
		if ( ! $check_day && ! $check_hour ) {
			return false;
		}

		// Get today's week day as a number between 0 (Sun) and 6 (Mon).
		$current_time = time();
		$todays_day   = date( 'w', $current_time );
		$week_days    = $schedule['week_days'];

		if ( $check_day ) {

			// Not displaying today.
			if ( ! in_array( $todays_day, $week_days, true ) ) {

				// Run the check again the next day it should be shown.
				$next_check = $this->get_schedule_next_week_day_timestamp( $todays_day, $week_days );
				return array(
					'is_currently_scheduled' => '0',
					'check_schedule_at'      => $next_check,
				);
			}
		}

		if ( $check_hour ) {

			$end_strtotime_str = "{$schedule['day_end_hour']}:{$schedule['day_end_minute']} {$schedule['day_end_meridiem_offset']}";
			$end_timestamp     = $this->get_time_with_timezone( $end_strtotime_str );

			$start_strtotime_str = "{$schedule['day_start_hour']}:{$schedule['day_start_minute']} {$schedule['day_start_meridiem_offset']}";
			$start_timestamp     = $this->get_time_with_timezone( $start_strtotime_str );

			// End time already passed.
			if ( $current_time > $end_timestamp ) {

				// Run the check again the next day it should be shown.
				if ( $check_day ) {
					$next_check = $this->get_schedule_next_week_day_timestamp( $todays_day, $week_days );
				} else {
					$next_check = $this->get_time_with_timezone( 'tomorrow ' . $start_strtotime_str );
				}

				return array(
					'is_currently_scheduled' => '0',
					'check_schedule_at'      => $next_check,
				);
			}

			// Start time hasn't passed.
			if ( $current_time < $start_timestamp ) {

				// Run the check again at the time it should be shown again.
				$next_check = $this->get_time_with_timezone( $start_strtotime_str );

				return array(
					'is_currently_scheduled' => '0',
					'check_schedule_at'      => $next_check,
				);
			}

			// Start time already passed and end time hasn't passed.
			return array(
				'is_currently_scheduled' => '1',
				'check_schedule_at'      => $end_timestamp,
			);
		}

		return false;
	}

	/**
	 * Get the next day of the week when the module should be displayed.
	 *
	 * @since 4.2.0
	 * @param string $todays_day Today's week day as a 'w' format for date().
	 * @param array  $week_days Selected weeks days for the module to be shown.
	 * @return string timestamp
	 */
	private function get_schedule_next_week_day_timestamp( $todays_day, $week_days ) {

		// Get the following week day to be displayed.
		$next_day = false;
		foreach ( $week_days as $day ) {

			// Get the following one this week.
			if ( intval( $day ) > intval( $todays_day ) ) {
				$next_day      = $day;
				$strtotime_str = "Sunday last week +{$next_day} days";
				break;
			}
		}

		// If the next day to display isn't ahead this week, get the first selected week day.
		if ( false === $next_day ) {
			$next_day      = $week_days[0];
			$strtotime_str = "Sunday this week +{$next_day} days";
		}

		// Run the check again the next day it should be shown.
		$next_check = $this->get_time_with_timezone( $strtotime_str );

		return $next_check;
	}

	/**
	 * Returns the given date string as Unix timestamp according to the selected wp timezone.
	 *
	 * @since 4.2.0
	 * @param string $str Time as a human readable string.
	 * @return string Timestamp.
	 */
	private function get_time_with_timezone( $str = 'now' ) {

		$settings = $this->to_array(); // We can probably make this a property of this class.

		// Using WP's selected timezone.
		if ( 'server' === $settings['schedule']['time_to_use'] ) {
			$timezone_string = $this->get_wp_timezone();

		} else {
			$selected_timezone = $settings['schedule']['custom_timezone'];
			$timezone_string   = $this->format_datetimezone_compatible_string( $selected_timezone );
		}

		$timezone_object    = new DateTimeZone( $timezone_string );
		$date_time_instance = new DateTime( $str, $timezone_object );
		return $date_time_instance->format( 'U' );
	}

	/**
	 * Gets the timezone set up in WordPress settings.
	 * This timezone string is valid for the DateTimeZone constructor.
	 *
	 * @since 4.2.0
	 *
	 * @return string
	 */
	private function get_wp_timezone() {

		// Available since WP 5.3.0.
		if ( function_exists( 'wp_timezone_string' ) ) {
			return wp_timezone_string();
		}

		/**
		 * Copied from @see https://developer.wordpress.org/reference/functions/wp_timezone_string/
		 * This is intended for WP versions previous to 5.3.0
		 */
		$timezone_string = get_option( 'timezone_string' );

		if ( $timezone_string ) {
			return $timezone_string;
		}

		$offset_str = get_option( 'gmt_offset' );
		$tz_offset  = $this->format_timezone_utc_offset( $offset_str );

		return $tz_offset;
	}

	/**
	 * Formats a timezone string so it's compatible with DateTimeZone.
	 *
	 * @since 4.2.0
	 * @param string $timezone_string Timezone string to format.
	 * @return string
	 */
	private function format_datetimezone_compatible_string( $timezone_string ) {

		if ( '0' === $timezone_string ) {
			$timezone_string = 'UTC';
		}

		if ( false === stripos( $timezone_string, 'UTC' ) ) {
			return $timezone_string;
		}

		$offset_str = str_replace( 'UTC', '', $timezone_string );
		$tz_offset  = $this->format_timezone_utc_offset( $offset_str );

		return $tz_offset;
	}

	/**
	 * Formats the utc offset so it's compatible with the DateTimezone constructor.
	 *
	 * @since 4.3.0
	 * @param string $offset_str UTC offset string.
	 * @return string
	 */
	private function format_timezone_utc_offset( $offset_str ) {

		$offset  = (float) $offset_str;
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );

		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

		return $tz_offset;
	}
}