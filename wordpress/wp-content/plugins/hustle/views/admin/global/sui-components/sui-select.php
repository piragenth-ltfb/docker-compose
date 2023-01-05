<?php
/**
 * SUI Select.
 *
 * @package Hustle
 * @since 4.3.0
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

?>

<div class="sui-form-field">

	<label id="hustle-<?php esc_attr( $name ); ?>-label" class="sui-label"><?php echo esc_html( $label ); ?></label>

	<select
		name="<?php echo esc_attr( $name ); ?>"
		id="hustle-select-<?php echo esc_attr( $name ); ?>"
		class="sui-select"
		data-attribute="<?php echo esc_attr( $name ); ?>"
		aria-labelledby="<?php echo esc_attr( $name ); ?>"
	>
		<?php foreach ( $options as $key => $option ) { ?>

			<option value="<?php echo esc_attr( $settings[ $name . '_' . $key ] ); ?>" selected>
				<?php echo esc_html( $settings[ $name ] ); ?>
			</option>

		<?php } ?>

	</select>

</div>