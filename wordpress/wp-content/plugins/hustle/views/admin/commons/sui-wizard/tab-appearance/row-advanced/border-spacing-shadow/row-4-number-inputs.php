<?php
/**
 * Template for the row with four number inputs used under Border, spacing, shadow.
 *
 * @since 4.3.0
 * @package Hustle
 */

?>
<div class="sui-row">

	<?php
	foreach ( $options as $option ) :
		$render_options = array(
			'type'       => 'number',
			'name'       => $option['slug'],
			'value'      => $settings[ $option['slug'] ],
			'id'         => 'hustle-' . $option['slug'],
			'attributes' => array(
				'data-attribute'     => $option['slug'],
				'aria-labelledby'    => 'hustle-' . $option['slug'] . '-label',
				'data-linked-fields' => ! empty( $linked_fields ) ? $linked_fields : '',
			),
		);
		if ( ! empty( $has_min ) ) {
			$render_options['min'] = '0';
		}
		if ( $is_desktop ) {
			$render_options['class'] = 'hustle-required-field';
		}
		?>

		<div class="sui-col-md-3">

			<div class="sui-form-field">

				<label
					for="hustle-<?php echo esc_attr( $option['slug'] ); ?>"
					id="hustle-<?php echo esc_attr( $option['slug'] ); ?>-label"
					class="sui-label<?php echo ! empty( $option['label-nowrap'] ) ? ' hustle-nowrap' : ''; ?>"
					><?php echo esc_html( $option['name'] ); ?></label>

				<?php Hustle_Layout_Helper::get_html_for_options( array( $render_options ) ); ?>

				<?php if ( ! empty( $option['error_message'] ) ) : ?>
					<span class="sui-error-message" style="display: none; text-align: right;"><?php echo esc_html( $option['error_message'] ); ?></span>
				<?php endif; ?>

			</div>

		</div>

	<?php endforeach; ?>

</div>