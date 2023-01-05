<?php
/**
 * SUI Icon.
 *
 * @package Hustle
 * @since 4.3.0
 */

$size = ( isset( $size ) && ! empty( $size ) ) ? $size : '';

switch ( $size ) {

	case 'sm':
	case 'md':
	case 'lg':
		$size = ' sui-' + $size;
		break;

	default:
		$size = '';
		break;
}

echo '<span class="sui-icon-' . esc_attr( $icon ) . esc_attr( $size ) . '" aria-hidden="true"></span>';