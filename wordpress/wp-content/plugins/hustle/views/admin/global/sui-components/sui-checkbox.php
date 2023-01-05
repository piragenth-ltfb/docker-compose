<?php
/**
 * SUI Checkbox
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$class  = 'sui-checkbox';
$class .= ( isset( $small ) && true === $small ) ? ' sui-checkbox-sm' : '';
$class .= ( isset( $stacked ) && true === $stacked ) ? ' sui-checkbox-stacked' : '';
$class .= ! empty( $custom_class ) ? ' ' . $custom_class : '';
?>

<label
	for="hustle-option-<?php echo esc_attr( $name ); ?>"
	class="<?php echo esc_attr( $class ); ?>"
	<?php echo empty( $attributes ) ? '' : esc_attr( $attributes ); ?>
>
	<input
		type="checkbox"
		name="<?php echo esc_attr( $name ); ?>"
		id="hustle-option-<?php echo esc_attr( $name ); ?>"
		data-attribute="<?php echo esc_attr( $name ); ?>"
		aria-labelledby="hustle-option-<?php echo esc_attr( $name ); ?>-label"
		<?php checked( $saved_value, '1' ); ?>
	>
	<span aria-hidden="true"></span>
	<span id="hustle-option-<?php echo esc_attr( $name ); ?>-label"><?php echo esc_html( $label ); ?></span>
</label>