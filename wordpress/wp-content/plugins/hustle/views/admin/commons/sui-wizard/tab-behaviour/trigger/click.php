<?php
/**
 * Click trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<?php /* translators: module type smallcaps and in singular */ ?>
<p class="sui-description" style="margin: 0 0 20px;"><?php printf( esc_html__( 'Trigger your %s by clicking on an existing page element or render a new trigger button on your website.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

<div class="sui-form-field">

	<?php
	$this->render(
		'admin/global/sui-components/sui-checkbox',
		array(
			'name'         => 'triggers.enable_on_click_element',
			'saved_value'  => $triggers['enable_on_click_element'],
			'small'        => true,
			'stacked'      => true,
			'label'        => esc_html__( 'Trigger by clicking on an existing page element(s)', 'hustle' ),
			'custom_class' => 'hustle-toggle-with-container',
			'attributes'   => 'data-toggle-on=enable_on_click_element',
		)
	);
	?>

	<div style="margin-left: 26px;" data-toggle-content="enable_on_click_element">

		<label class="sui-label"><?php esc_html_e( 'CSS selector(s)', 'hustle' ); ?></label>

		<input
			type="text"
			value="<?php echo esc_attr( $triggers['on_click_element'] ); ?>"
			name="trigger_on_click_element"
			placeholder="<?php esc_attr_e( 'For example .element-class, #element-id', 'hustle' ); ?>"
			class="sui-form-control"
			data-attribute="triggers.on_click_element"
		/>

		<?php /* translators: module type in smallcaps and in singular */ ?>
		<p class="sui-description"><?php printf( esc_html__( 'You can add multiple selectors separated by a comma to trigger your %s from multiple elements.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>

	</div>

</div>

<hr />

<div class="sui-form-field">

	<?php
	$this->render(
		'admin/global/sui-components/sui-checkbox',
		array(
			'name'         => 'triggers.enable_on_click_shortcode',
			'saved_value'  => $triggers['enable_on_click_shortcode'],
			'small'        => true,
			'stacked'      => true,
			'label'        => esc_html__( 'Render a trigger button', 'hustle' ),
			'custom_class' => 'hustle-toggle-with-container',
			'attributes'   => 'data-toggle-on=enable_on_click_shortcode',
		)
	);
	?>

	<div style="margin-left: 26px;" data-toggle-content="enable_on_click_shortcode">

		<label class="sui-label"><?php esc_html_e( 'Button shortcode', 'hustle' ); ?></label>

		<div class="sui-with-button sui-with-button-inside">
			<input type="text"
				class="sui-form-control"
				value='[wd_hustle id="<?php echo esc_attr( $shortcode_id ); ?>" type="<?php echo esc_attr( $this->admin->module_type ); ?>"]<?php esc_attr_e( 'Click', 'hustle' ); ?>[/wd_hustle]'
				readonly="readonly">
			<button class="sui-button-icon hustle-copy-shortcode-button">
				<span aria-hidden="true" class="sui-icon-copy"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Copy shortcode', 'hustle' ); ?></span>
			</button>
		</div>

		<p class="sui-description"><?php esc_html_e( 'Copy the button shortcode and paste it wherever you want to render this trigger button.', 'hustle' ); ?></p>

	</div>

</div>