<?php
/**
 * SUI Notice.
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$notice_class = 'sui-notice';
$notice_alert = ( isset( $alert ) && ! empty( $alert ) ) ? $alert : false;
$notice_type  = ( isset( $type ) && ! empty( $type ) ) ? $type : '';
$notice_icon  = ( isset( $icon ) && ! empty( $icon ) ) ? 'sui-icon-' . $icon : 'sui-icon-info';

switch ( $notice_type ) {

	case 'info':
	case 'success':
	case 'warning':
	case 'error':
	case 'upsell':
		$notice_class .= ' sui-notice-' . esc_attr( $notice_type );
		break;

	case 'blue':
	case 'green':
	case 'yellow':
	case 'red':
	case 'purple':
		$notice_class .= ' sui-notice-' . esc_attr( $notice_type );
		break;

	default:
		$notice_class .= '';
		break;
}

switch ( $notice_alert ) {

	case false:
		echo '<div class="' . esc_attr( $notice_class ) . '">
			<div class="sui-notice-content">
				<div class="sui-notice-message">
					<span class="sui-notice-icon ' . esc_attr( $notice_icon ) . ' sui-md" aria-hidden="true"></span>
					<p>' . $message . '</p>
				</div>
			</div>
		</div>';
		break;

	default:
		echo '<div role="alert" id="' . esc_attr( $id ) . '" class="' . esc_attr( $notice_class ) . '" aria-live="assertive"></div>';
		break;
}