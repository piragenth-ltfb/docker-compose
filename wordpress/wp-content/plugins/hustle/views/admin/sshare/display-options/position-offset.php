<?php
/**
 * Positioning - offset section.
 *
 * @package Hustle
 * @since 4.3.0
 */

ob_start();
?>
<div class="sui-form-field hustle-css-selector">

<label for="hustle-offset--<?php echo esc_attr( $prefix ); ?>-selector" class="sui-label"><?php esc_html_e( 'CSS selector of the element', 'hustle' ); ?></label>

<input
	type="text"
	name="<?php echo esc_html( $prefix ); ?>_css_selector"
	data-attribute="<?php echo esc_html( $prefix ); ?>_css_selector"
	value="<?php echo esc_attr( $settings[ $prefix . '_css_selector' ] ); ?>"
	placeholder="#css-id"
	id="hustle-offset--<?php echo esc_html( $prefix ); ?>-selector"
	class="sui-form-control"
/>

<span class="sui-error-message" style="display: none; text-align: right;"><?php esc_html_e( 'CSS selector is required.', 'hustle' ); ?></span>

</div>
<?php
$content = ob_get_clean();

$options = array(
	'screen'       => array(
		'value' => 'screen',
		'label' => __( 'Screen', 'hustle' ),
	),
	'css_selector' => array(
		'value'   => 'css_selector',
		'label'   => __( 'CSS Selector', 'hustle' ),
		'content' => $content,
	),
);
?>

<div class="sui-form-field">

	<span class="sui-settings-label"><?php esc_html_e( 'Offset', 'hustle' ); ?></span>
	<span class="sui-description"><?php esc_html_e( "You can choose to offset the Floating Social relative to the screen of visitor's device or a specific CSS selector.", 'hustle' ); ?></span>

	</div>

	<?php // SETTINGS: Relative to. ?>
	<div class="sui-form-field">

	<label class="sui-label"><?php esc_html_e( 'Relative to', 'hustle' ); ?></label>

	<?php
	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'          => $prefix . '_offset',
			'radio'         => true,
			'saved_value'   => $settings[ $prefix . '_offset' ],
			'sidetabs'      => true,
			'content'       => true,
			'content_class' => 'sui-tabs-content-lg',
			'options'       => $options,
		)
	);
	?>

	</div>

	<?php // SETTINGS: Offset value. ?>
	<div class="sui-row">

	<div
		id="hustle-<?php echo esc_attr( $prefix ); ?>-offset-x-wrapper"
		class="sui-col<?php echo 'center' === $settings[ $prefix . '_position' ] ? ' sui-hidden' : ''; ?>"
	>

		<div class="sui-form-field">

			<label
				for="hustle-<?php echo esc_html( $prefix ); ?>-offset-pixels-x"
				id="hustle-<?php echo esc_attr( $prefix ); ?>-left-offset-label"
				class="sui-label<?php echo 'right' === $settings[ $prefix . '_position' ] ? ' sui-hidden' : ''; ?>"
			>
				<?php esc_html_e( 'Left offset value (px)', 'hustle' ); ?>
			</label>

			<label
				for="hustle-<?php echo esc_html( $prefix ); ?>-offset-pixels-x"
				id="hustle-<?php echo esc_attr( $prefix ); ?>-right-offset-label"
				class="sui-label<?php echo 'right' !== $settings[ $prefix . '_position' ] ? ' sui-hidden' : ''; ?>"
			>
				<?php esc_html_e( 'Right offset value (px)', 'hustle' ); ?>
			</label>

			<input
				type="number"
				name="<?php echo esc_html( $prefix ); ?>_offset_x"
				value="<?php echo esc_attr( $settings[ $prefix . '_offset_x' ] ); ?>"
				placeholder="0"
				id="hustle-<?php echo esc_html( $prefix ); ?>-offset-pixels-x"
				class="sui-form-control"
				data-attribute="<?php echo esc_html( $prefix ); ?>_offset_x"
			/>

		</div>

	</div>

	<div class="sui-col">

		<div class="sui-form-field">

			<label
				for="hustle-<?php echo esc_html( $prefix ); ?>-offset-pixels-y"
				id="hustle-<?php echo esc_attr( $prefix ); ?>-top-offset-label"
				class="sui-label<?php echo 'top' !== $settings[ $prefix . '_position_y' ] ? ' sui-hidden' : ''; ?>"
			>
					<?php esc_html_e( 'Top offset value (px)', 'hustle' ); ?>
			</label>

			<label
				for="hustle-<?php echo esc_html( $prefix ); ?>-offset-pixels-y"
				id="hustle-<?php echo esc_attr( $prefix ); ?>-bottom-offset-label"
				class="sui-label<?php echo 'top' === $settings[ $prefix . '_position_y' ] ? ' sui-hidden' : ''; ?>"
			>
				<?php esc_html_e( 'Bottom offset value (px)', 'hustle' ); ?>
			</label>

			<input
				type="number"
				name="<?php echo esc_html( $prefix ); ?>_offset_y"
				data-attribute="<?php echo esc_html( $prefix ); ?>_offset_y"
				value="<?php echo esc_attr( $settings[ $prefix . '_offset_y' ] ); ?>"
				placeholder="0"
				id="hustle-<?php echo esc_html( $prefix ); ?>-offset-pixels-y"
				class="sui-form-control"
			/>

		</div>

	</div>

</div>