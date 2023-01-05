<?php
/**
 * SUI Button.
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$color    = ( isset( $color ) && ! empty( $color ) ) ? ' sui-button-' . $color : '';
$outlined = ( isset( $outlined ) && true === $outlined ) ? ' sui-button-ghost' : '';

if ( 'link' ===  $type ) {

	echo '<a class="sui-button' . esc_attr( $color ) . esc_attr( $outlined ) . '">' . $label . '</a>';

} elseif ( 'button' ===  $type ) {

	echo '<button class="sui-button' . esc_attr( $color ) . esc_attr( $outlined ) . '">'
			. '<span class="sui-loading-text">'
				. $label
			. '</span>'
			. '<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>'
		. '</button>';

}