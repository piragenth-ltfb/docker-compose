<?php
/**
 * Select palette field.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<div class="sui-form-field">

	<label for="hustle-color-palettes-list" id="hustle-color-palettes-list-label" class="sui-label"><?php esc_html_e( 'Select a color palette', 'hustle' ); ?></label>

	<select name="color_palette" id="hustle-color-palettes-list" class="sui-select" data-attribute="color_palette" aria-labelledby="hustle-color-palettes-list-label">

		<?php foreach ( Hustle_Palettes_Helper::get_all_palettes_slug_and_name() as $slug => $name ) : ?>

			<option
				value="<?php echo esc_attr( $slug ); ?>"
				<?php selected( $settings['color_palette'], $slug ); ?>
			>
				<?php echo esc_html( $name ); ?>
			</option>

		<?php endforeach; ?>

	</select>

	<div id="hustle-create-palette-link" style="display: none;">
		<li>
			<a href="<?php echo esc_url( $custom_pallete_url ); ?>" target="_blank" class="hui-button">
				<?php esc_html_e( 'Create custom color palettes', 'hustle' ); ?>
			</a>
		</li>
	</div>

</div>