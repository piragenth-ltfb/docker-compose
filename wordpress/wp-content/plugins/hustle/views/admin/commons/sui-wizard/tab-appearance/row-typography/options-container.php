<?php
/**
 * Typography options container.
 *
 * @uses ./options-template
 * @uses admin/global/sui-tabs
 * @uses admin/global/sui-accordion
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix = empty( $device ) ? '' : '_' . $device;

$general_options = array(
	'title'          => __( 'Title', 'hustle' ),
	'subtitle'       => __( 'Subtitle', 'hustle' ),
	'main_content'   => __( 'Main Content', 'hustle' ),
	'cta'            => __( 'Call to Action', 'hustle' ),
	'never_see_link' => __( '"Never see this again" Link', 'hustle' ),
);

if ( Hustle_Module_Model::EMBEDDED_MODULE === $this->admin->module_type ) {
	unset( $general_options['never_see_link'] );
}

$optin_options = array(
	'form_extras'     => __( 'Form - Extra Options Title', 'hustle' ),
	'input'           => __( 'Form - Input and Select', 'hustle' ),
	'checkbox'        => __( 'Form - Radio and Checkbox', 'hustle' ),
	'dropdown'        => __( 'Form - Dropdown', 'hustle' ),
	// 'calendar'        => __( 'Form - Calendar', 'hustle' ),
	'gdpr'            => __( 'Form - GDPR Checkbox', 'hustle' ),
	'recaptcha'       => __( 'Form - reCAPTCHA Text', 'hustle' ),
	'submit_button'   => __( 'Form - Submit Button', 'hustle' ),
	'error_message'   => __( 'Error Message', 'hustle' ),
	'success_message' => __( 'Success Message', 'hustle' ),
);

// This array gives ability to change data-names for accordion row.
$data_labels = array(
	'subtitle'       => 'sub_title',
	'cta'            => 'show_cta',
	'never_see_link' => 'show_never_see_link',
);

$global_family        = 'global_font_family';
$custom_font_name     = 'global_custom_font_family';
$selected_font_family = $settings[ $global_family ];

$available_families = $this->admin->get_font_families();
?>

<?php if ( empty( $device_suffix ) ) : ?>

	<div class="sui-border-frame hui-global-typography-disabled">

		<h5 class="sui-settings-label" style="margin-bottom: 30px; font-size: 13px;"><?php esc_html_e( 'Apply a font-family to all elements', 'hustle' ); ?></h5>

		<div class="sui-form-field">

			<label id="hustle-<?php esc_attr( $global_family ); ?>-label" class="sui-label"><?php esc_html_e( 'Font Family', 'hustle' ); ?></label>

			<select
				id="hustle-select-<?php echo esc_attr( $global_family ); ?>"
				class="sui-select hustle-font-family-select"
				name="<?php echo esc_attr( $global_family ); ?>"
				data-attribute="<?php echo esc_attr( $global_family ); ?>"
				data-custom="<?php echo esc_attr( $custom_font_name ); ?>"
				data-fonts-loaded="false"
				aria-labelledby="hustle-<?php echo esc_attr( $global_family ); ?>-label"
				tabindex="-1"
				aria-hidden="true"
			>
				<option value="<?php echo esc_attr( $selected_font_family ); ?>" selected>
					<?php echo esc_html( $available_families[ $selected_font_family ]['label'] ); ?>
				</option>
			</select>

		</div>

		<div class="sui-form-field" style="display: none;">

			<label id="hustle-<?php esc_attr( $custom_font_name ); ?>-label" class="sui-label"><?php esc_html_e( 'Custom Font Family', 'hustle' ); ?></label>

			<?php
			Hustle_Layout_Helper::get_html_for_options(
				array(
					array(
						'type'        => 'text',
						'name'        => $custom_font_name,
						'value'       => $settings[ $custom_font_name ],
						'placeholder' => __( 'E.g. Arial, sans-serif', 'hustle' ),
						'id'          => 'hustle-' . $custom_font_name,
						'attributes'  => array(
							'data-attribute'  => $custom_font_name,
							'aria-labelledby' => 'hustle-' . $custom_font_name . '-label',
						),
					),
				)
			);
			?>

		</div>

		<button class="sui-button sui-button-ghost hustle-button-apply-global-font">
			<span class="sui-loading-text"><?php esc_html_e( 'Apply', 'hustle' ); ?></span>
			<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
		</button>

	</div>

	<h3 class="sui-settings-label"><?php esc_html_e( 'Font Styles', 'hustle' ); ?></h3>
	<p class="sui-description" style="margin-bottom: 10px;"><?php esc_html_e( 'Customize the typographic properties of the pop-up elements to fit your need.', 'hustle' ); ?></p>

<?php endif ?>

<?php
if ( $is_optin ) {

	$general_option  = array();
	$general_content = array();

	foreach ( $general_options as $key => $label ) {
		$general_option['title']   = $label;
		$general_option['key']     = isset( $data_labels[ $key ] ) ? $data_labels[ $key ] : $key;
		$general_option['content'] = $this->render(
			'admin/commons/sui-wizard/tab-appearance/row-typography/options-template',
			array(
				'settings'            => $settings,
				'property_key'        => $key,
				'is_optin'            => $is_optin,
				'device'              => $device,
				'smallcaps_singular'  => $smallcaps_singular,
				'capitalize_singular' => $capitalize_singular,
			),
			true
		);

		$general_content[] = $general_option;
	}

	$optin_option  = array();
	$optin_content = array();
	$active_addons = Hustle_Provider_Utils::get_registered_addons_grouped_by_form_connected();
	$active_slugs  = ! empty( $active_addons['connected'] ) ? wp_list_pluck( $active_addons['connected'], 'slug' ) : array();

	foreach ( $optin_options as $key => $label ) {
		// Hide Extra fields options if activa providers aren't support them.
		$optin_option['hidden']  = ( 'form_extras' === $key ) && ! in_array( 'mailchimp', $active_slugs, true );
		$optin_option['title']   = $label;
		$optin_option['key']     = isset( $data_labels[ $key ] ) ? $data_labels[ $key ] : $key;
		$optin_option['content'] = $this->render(
			'admin/commons/sui-wizard/tab-appearance/row-typography/options-template',
			array(
				'settings'            => $settings,
				'property_key'        => $key,
				'is_optin'            => $is_optin,
				'device'              => $device,
				'smallcaps_singular'  => $smallcaps_singular,
				'capitalize_singular' => $capitalize_singular,
			),
			true
		);

		$optin_content[] = $optin_option;
	}

	$this->render(
		'admin/global/sui-components/sui-tabs',
		array(
			'name'     => 'custom-typography' . $device_suffix,
			'class'    => 'hustle-typography-tabs',
			'options'  => array(
				'general' => array(
					'label'   => esc_html__( 'General', 'hustle' ),
					'content' => $this->render(
						'admin/global/sui-components/sui-accordion',
						array(
							'options' => $general_content,
							'flushed' => true,
							'reset'   => true,
						),
						true
					),
				),
				'optin'   => array(
					'label'   => esc_html__( 'Opt-in', 'hustle' ),
					'content' => $this->render(
						'admin/global/sui-components/sui-accordion',
						array(
							'options' => $optin_content,
							'flushed' => true,
							'reset'   => true,
						),
						true
					),
				),
			),
			'content'  => true,
			'overflow' => true,
		)
	);
} else {

	$option  = array();
	$options = array();

	foreach ( $general_options as $key => $label ) {
		$option['title']   = $label;
		$option['key']     = isset( $data_labels[ $key ] ) ? $data_labels[ $key ] : $key;
		$option['content'] = $this->render(
			'admin/commons/sui-wizard/tab-appearance/row-typography/options-template',
			array(
				'settings'            => $settings,
				'property_key'        => $key,
				'is_optin'            => $is_optin,
				'device'              => $device,
				'smallcaps_singular'  => $smallcaps_singular,
				'capitalize_singular' => $capitalize_singular,
			),
			true
		);

		$options[] = $option;
	}

	$this->render(
		'admin/global/sui-components/sui-accordion',
		array(
			'options' => $options,
			'reset'   => true,
		)
	);
}