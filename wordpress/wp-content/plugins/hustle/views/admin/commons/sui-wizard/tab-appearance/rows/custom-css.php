<?php
/**
 * Custom CSS options.
 * Used only in desktop settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

$selectors = array(
	'modal_selectors'      => array(
		array(
			'name'     => 'Layout',
			'selector' => '.hustle-layout .hustle-layout-content ',
		),
		array(
			'name'     => 'Title',
			'selector' => '.hustle-layout .hustle-title',
		),
		array(
			'name'     => 'Subtitle',
			'selector' => '.hustle-layout .hustle-subtitle ',
		),
		array(
			'name'     => 'Feat. Image',
			'selector' => '.hustle-layout .hustle-layout-content .hustle-image img ',
		),
		array(
			'name'     => 'Main Content',
			'selector' => '.hustle-layout .hustle-layout-content .hustle-group-content p ',
		),
		array(
			'name'     => 'CTA Button',
			'selector' => '.hustle-layout .hustle-button-cta ',
		),
	),

	'form_selectors'       => array(
		array(
			'name'     => 'Form Container',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-layout-form ',
		),
		array(
			'name'     => 'Input',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-layout-form .hustle-input ',
		),
		array(
			'name'     => 'Submit',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-layout-form .hustle-button-submit ',
		),
		array(
			'name'     => 'Success Container',
			'selector' => '.hustle-success ',
		),
		array(
			'name'     => 'Success Message',
			'selector' => '.hustle-success .hustle-success-content p ',
		),
	),

	'form_extra_selectors' => array(
		array(
			'name'     => 'Container',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-form-options ',
		),
		array(
			'name'     => 'Title',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-form-options .hustle-group-title ',
		),
		array(
			'name'     => 'Radio',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-radio span[aria-hidden]',
		),
		array(
			'name'     => 'Radio (Label)',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-radio span:not([aria-hidden])',
		),
		array(
			'name'     => 'Checkbox',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-checkbox span[aria-hidden]',
		),
		array(
			'name'     => 'Checkbox (Label)',
			'selector' => '.hustle-layout .hustle-layout-body .hustle-checkbox span:not([aria-hidden])',
		),
	),
);

if ( Hustle_Module_Model::EMBEDDED_MODULE !== $module_type ) {
	$selectors['modal_selectors'][] = array(
		'name'     => 'Close Button',
		'selector' => '.hustle-button-close',
	);
}
?>
<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Custom CSS', 'hustle' ); ?></span>
		<span class="sui-description"><?php esc_html_e( 'For more advanced customization options use custom CSS.', 'hustle' ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<label
			for="hustle-customize-css"
			class="sui-toggle hustle-toggle-with-container"
			data-toggle-on="customize-css"
		>
			<input
				type="checkbox"
				id="hustle-customize-css"
				data-attribute="customize_css"
				<?php checked( $settings['customize_css'], '1' ); ?>
			/>
			<span class="sui-toggle-slider" aria-hidden="true"></span>
		</label>

		<label for="hustle-customize-css"><?php esc_html_e( 'Enable custom CSS', 'hustle' ); ?></label>

		<div class="sui-background-frame" data-toggle-content="customize-css">

			<label class="sui-label"><?php esc_html_e( 'Modal selectors', 'hustle' ); ?></label>

			<div class="sui-ace-selectors">

				<?php foreach ( $selectors['modal_selectors'] as $data ) : ?>
					<a href="#" class="sui-selector hustle-css-stylable" data-stylable="<?php echo esc_attr( $data['selector'] ); ?>" >
						<?php echo esc_html( $data['name'] ); ?>
					</a>
				<?php endforeach; ?>

			</div>

			<?php if ( $is_optin ) { ?>

				<label class="sui-label"><?php esc_html_e( 'Form selectors', 'hustle' ); ?></label>

				<div class="sui-ace-selectors">

					<?php foreach ( $selectors['form_selectors'] as $data ) : ?>
						<a href="#" class="sui-selector hustle-css-stylable" data-stylable="<?php echo esc_attr( $data['selector'] ); ?>" >
							<?php echo esc_html( $data['name'] ); ?>
						</a>
					<?php endforeach; ?>

				</div>

				<label class="sui-label"><?php esc_html_e( 'Form options selectors', 'hustle' ); ?></label>
				<label class="sui-label" style="font-weight: 400;"><?php esc_html_e( 'These are added through "Integrations" like Mailchimp that allow extra fields for users to select custom information requested.', 'hustle' ); ?></label>

				<div class="sui-ace-selectors">

					<?php foreach ( $selectors['form_extra_selectors'] as $data ) : ?>
						<a href="#" class="sui-selector hustle-css-stylable" data-stylable="<?php echo esc_attr( $data['selector'] ); ?>" >
							<?php echo esc_html( $data['name'] ); ?>
						</a>
					<?php endforeach; ?>

				</div>

			<?php } ?>

			<div id="hustle_custom_css" style="height: 210px;"><?php echo $settings['custom_css']; ?></div>

		</div>

	</div>

</div>