<?php
/**
 * Hustle_Time_Helper class.
 *
 * @package Hustle
 * @since 4.3.1
 */

/**
 * Helper class for getting Date and Time related values.
 *
 * @since 4.3.1
 */
class Hustle_Time_Helper {

	/**
	 * Return meridiam periods (AM,PM)
	 *
	 * @since 4.3.1
	 * @return array
	 */
	public static function get_meridiam_periods() {
		$periods = array(
			'am' => __( 'AM', 'hustle' ),
			'pm' => __( 'PM', 'hustle' ),
		);

		return $periods;
	}

	/**
	 * Get the week days as translatable strings.
	 *
	 * @since 4.3.1
	 *
	 * @param string $version full|short|min.
	 * @return array
	 */
	public static function get_week_days( $version = 'full' ) {

		if ( 'full' === $version ) {
			$days = array(
				esc_html__( 'Sunday', 'hustle' ),
				esc_html__( 'Monday', 'hustle' ),
				esc_html__( 'Tuesday', 'hustle' ),
				esc_html__( 'Wednesday', 'hustle' ),
				esc_html__( 'Thursday', 'hustle' ),
				esc_html__( 'Friday', 'hustle' ),
				esc_html__( 'Saturday', 'hustle' ),
			);

		} elseif ( 'short' === $version ) {
			$days = array(
				esc_html__( 'Sun', 'hustle' ),
				esc_html__( 'Mon', 'hustle' ),
				esc_html__( 'Tue', 'hustle' ),
				esc_html__( 'Wed', 'hustle' ),
				esc_html__( 'Thu', 'hustle' ),
				esc_html__( 'Fri', 'hustle' ),
				esc_html__( 'Sat', 'hustle' ),
			);

		} else {
			$days = array(
				esc_html__( 'Su', 'hustle' ),
				esc_html__( 'Mo', 'hustle' ),
				esc_html__( 'Tu', 'hustle' ),
				esc_html__( 'We', 'hustle' ),
				esc_html__( 'Th', 'hustle' ),
				esc_html__( 'Fr', 'hustle' ),
				esc_html__( 'Sa', 'hustle' ),
			);
		}

		return apply_filters( 'hustle_get_months', $days, $version );
	}

	/**
	 * Get the months as translatable strings.
	 *
	 * @since 4.3.1
	 *
	 * @param string $version full|short.
	 * @return array
	 */
	public static function get_months( $version = 'full' ) {

		if ( 'full' === $version ) {
			$months = array(
				esc_html__( 'January', 'hustle' ),
				esc_html__( 'February', 'hustle' ),
				esc_html__( 'March', 'hustle' ),
				esc_html__( 'April', 'hustle' ),
				esc_html__( 'May', 'hustle' ),
				esc_html__( 'June', 'hustle' ),
				esc_html__( 'July', 'hustle' ),
				esc_html__( 'August', 'hustle' ),
				esc_html__( 'September', 'hustle' ),
				esc_html__( 'October', 'hustle' ),
				esc_html__( 'November', 'hustle' ),
				esc_html__( 'December', 'hustle' ),
			);

		} else {
			$months = array(
				esc_html__( 'Jan', 'hustle' ),
				esc_html__( 'Feb', 'hustle' ),
				esc_html__( 'Mar', 'hustle' ),
				esc_html__( 'Apr', 'hustle' ),
				esc_html__( 'May', 'hustle' ),
				esc_html__( 'Jun', 'hustle' ),
				esc_html__( 'Jul', 'hustle' ),
				esc_html__( 'Aug', 'hustle' ),
				esc_html__( 'Sep', 'hustle' ),
				esc_html__( 'Oct', 'hustle' ),
				esc_html__( 'Nov', 'hustle' ),
				esc_html__( 'Dec', 'hustle' ),
			);
		}

		return apply_filters( 'hustle_get_months', $months, $version );
	}

	/**
	 * Return date formats
	 *
	 * @since 4.3.1
	 * @return array
	 */
	public static function get_date_formats() {
		$formats = array(
			'yy/mm/dd' => __( '2012/07/31', 'hustle' ),
			'mm/dd/yy' => __( '07/31/2012', 'hustle' ),
			'dd/mm/yy' => __( '31/07/2012', 'hustle' ),
			'yy, MM d' => __( '2012, July 31', 'hustle' ),
			'd MM, yy' => __( '31 July, 2012', 'hustle' ),
			'MM d, yy' => __( 'July 31, 2012', 'hustle' ),
			'dd-mm-yy' => __( '31-07-2012', 'hustle' ),
			'mm-dd-yy' => __( '07-31-2012', 'hustle' ),
			'yy-mm-dd' => __( '2012-07-31', 'hustle' ),
			'dd.mm.yy' => __( '31.07.2012', 'hustle' ),
			'mm.dd.yy' => __( '07.31.2012', 'hustle' ),
			'yy.mm.dd' => __( '2012.07.31', 'hustle' ),
		);

		$formats = apply_filters( 'hustle_date_formats', $formats );

		return $formats;
	}

	/**
	 * Gets the formated current date.
	 *
	 * @since 4.3.1
	 *
	 * return string $date Current date, formated bu i18n.
	 */
	public static function get_current_date() {
		$date = date_i18n( 'Y-m-d H:i:s' );
		return $date;
	}

	/**
	 * Convert some unit of time to microseconds.
	 *
	 * @since 4.3.1
	 *
	 * @param int    $value Value to transform to microseconds.
	 * @param string $unit Unit to do the conversion from.
	 * @return int
	 */
	public static function to_microseconds( $value, $unit ) {

		if ( 'seconds' === $unit ) {
			return intval( $value, 10 ) * 1000;

		} elseif ( 'minutes' === $unit ) {
			return intval( $value, 10 ) * 60 * 1000;

		} else {
			return intval( $value, 10 ) * 60 * 60 * 1000;
		}
	}

	/**
	 * Return local timestamp
	 *
	 * @since 4.3.1
	 *
	 * @param int $timestamp Timestamp to convert to the local time.
	 * @return mixed
	 */
	public static function get_local_timestamp( $timestamp = null ) {
		// If no timestamp, get it current.
		if ( is_null( $timestamp ) ) {
			$timestamp = time();
		}

		return $timestamp + ( get_option( 'gmt_offset' ) * 3600 );
	}
}