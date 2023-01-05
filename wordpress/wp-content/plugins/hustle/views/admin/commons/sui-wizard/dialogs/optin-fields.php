<?php
/**
 * Modal for selecting the inputs to add to the form.
 *
 * @package Hustle
 * @since 4.0.0
 */

$fields = array(
	'name'       => array(
		'icon'  => 'profile-male',
		'label' => __( 'Name', 'hustle' ),
	),
	'email'      => array(
		'icon'  => 'mail',
		'label' => __( 'Email', 'hustle' ),
	),
	'phone'      => array(
		'icon'  => 'phone',
		'label' => __( 'Phone', 'hustle' ),
	),
	'address'    => array(
		'icon'  => 'pin',
		'label' => __( 'Address', 'hustle' ),
	),
	'url'        => array(
		'icon'  => 'web-globe-world',
		'label' => __( 'Website', 'hustle' ),
	),
	'text'       => array(
		'icon'  => 'style-type',
		'label' => __( 'Text', 'hustle' ),
	),
	'number'     => array(
		'icon'  => 'element-number',
		'label' => __( 'Number', 'hustle' ),
	),
	'datepicker' => array(
		'icon'  => 'calendar',
		'label' => __( 'Datepicker', 'hustle' ),
	),
	'timepicker' => array(
		'icon'  => 'clock',
		'label' => __( 'Timepicker', 'hustle' ),
	),
	'recaptcha'  => array(
		'icon'   => 'recaptcha',
		'label'  => __( 'reCaptcha', 'hustle' ),
		'single' => true,
	),
	'gdpr'       => array(
		'icon'   => 'gdpr',
		'label'  => __( 'GDPR Approval', 'hustle' ),
		'single' => true,
	),
	'hidden'     => array(
		'icon'  => 'eye-hide',
		'label' => __( 'Hidden Field', 'hustle' ),
	),
);

ob_start();
?>

<div class="sui-box-selectors sui-box-selectors-col-5" style="margin-bottom: 0;">

	<ul class="sui-spacing-slim">

		<?php foreach ( $fields as $field_type => $data ) : ?>

			<li><label for="hustle-optin-insert-field--<?php echo esc_attr( $field_type ); ?>" class="sui-box-selector sui-box-selector-vertical hustle-optin-insert-field-label--<?php echo esc_attr( $field_type ); ?>">
				<input
					id="hustle-optin-insert-field--<?php echo esc_attr( $field_type ); ?>"
					type="checkbox"
					value="<?php echo esc_attr( $field_type ); ?>"
					name="optin_fields"
					<?php
					if ( ! empty( $data['single'] ) ) {
						disabled( array_key_exists( $field_type, $form_elements ) );
						checked( array_key_exists( $field_type, $form_elements ) );
					}
					?>
				/>
				<span>
					<span class="sui-icon-<?php echo esc_attr( $data['icon'] ); ?>" aria-hidden="true"></span>
					<?php echo esc_html( $data['label'] ); ?>
				</span>
			</label></li>

		<?php endforeach; ?>

	</ul>

</div>

<?php
$body_content = ob_get_clean();

$attributes = array(
	'modal_id'           => 'optin-fields',
	'has_description'    => true,
	'modal_size'         => 'lg',

	'header'             => array(
		'title' => __( 'Insert Fields', 'hustle' ),
	),
	'body'               => array(
		'classes'             => 'sui-spacing-bottom--0',
		'description'         => __( 'Choose which fields you want to insert into your opt-in form.', 'hustle' ),
		'description_classes' => false,
	),
	'after_body_content' => $body_content,
	'footer'             => array(
		'classes' => 'sui-content-separated sui-flatten sui-spacing-top--30',
		'buttons' => array(
			array(
				'classes'  => 'sui-button-ghost hustle-modal-close',
				'text'     => __( 'Cancel', 'hustle' ),
				'is_close' => true,
			),
			array(
				'id'       => 'hustle-insert-fields',
				'classes'  => 'sui-button-blue',
				'has_load' => true,
				'text'     => __( 'Insert Fields', 'hustle' ),
			),
		),
	),
);

$this->render_modal( $attributes );