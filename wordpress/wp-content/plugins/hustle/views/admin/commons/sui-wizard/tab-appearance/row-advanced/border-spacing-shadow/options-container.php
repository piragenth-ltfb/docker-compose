<?php
/**
 * Border, Spacing and Shadow options container.
 *
 * @uses ./options-template
 * @uses admin/global/sui-tabs
 * @uses admin/global/sui-accordion
 *
 * @package Hustle
 * @since 4.3.0
 */

$device_suffix = empty( $device ) ? '' : '_' . $device;

$module_type_options = array();

if ( Hustle_Module_Model::POPUP_MODULE === $this->admin->module_type ) {
	$module_type_options['popup_cont'] = array(
		'label' => __( 'Pop-up Container', 'hustle' ),
		'class' => '.hustle-popup',
	);
} elseif ( Hustle_Module_Model::EMBEDDED_MODULE === $this->admin->module_type ) {
	$module_type_options['embed_cont'] = array(
		'label' => __( 'Embed Container', 'hustle' ),
		'class' => '.hustle-inline',
	);
}

$general_options = $module_type_options + array(
	'module_cont'    => array(
		'label' => __( 'Main Layout', 'hustle' ),
		'class' => ( $is_optin ) ? '.hustle-layout-body' : '.hustle-layout',
	),
	'layout_header'  => array(
		'label' => __( 'Layout Header', 'hustle' ),
		'class' => '.hustle-layout-header',
	),
	'layout_content' => array(
		'label' => __( 'Layout Content', 'hustle' ),
		'class' => '.hustle-layout-content',
	),
	'layout_footer'  => array(
		'label' => __( 'Layout Footer', 'hustle' ),
		'class' => '.hustle-layout-footer',
	),
	'content_wrap'   => array(
		'label' => __( 'Content Wrapper', 'hustle' ),
		'class' => '.hustle-content',
	),
	'title'          => array(
		'label' => __( 'Title', 'hustle' ),
		'class' => '.hustle-title',
	),
	'subtitle'       => array(
		'label'    => __( 'Subtitle', 'hustle' ),
		'class'    => '.hustle-subtitle',
		'row_name' => 'sub_title',
	),
	'main_content'   => array(
		'label' => __( 'Main Content', 'hustle' ),
		'class' => '.hustle-group-content',
	),
	'cta_cont'       => array(
		'label'    => __( 'Call to Action - Container', 'hustle' ),
		'class'    => '.hustle-cta-container',
		'row_name' => 'show_cta',
	),
	'cta'            => array(
		'label'    => __( 'Call to Action - Button', 'hustle' ),
		'class'    => '.hustle-button-cta',
		'row_name' => 'show_cta',
	),
	'nsa_link'       => array(
		'label'    => __( '"Never see this again" Link', 'hustle' ),
		'class'    => '.hustle-nsa-link',
		'row_name' => 'show_never_see_link',
	),
);

if ( Hustle_Module_Model::EMBEDDED_MODULE === $this->admin->module_type ) {
	unset( $general_options['nsa_link'] );
}

$optin_options = array(
	'form_cont'       => array(
		'label' => __( 'Form Container', 'hustle' ),
		'class' => '.hustle-layout-form',
	),
	'input'           => array(
		'label' => __( 'Input and Select', 'hustle' ),
		'class' => '',
	),
	'checkbox'        => array(
		'label' => __( 'Radio and Checkbox', 'hustle' ),
		'class' => '',
	),
	'submit_button'   => array(
		'label' => __( 'Submit Button', 'hustle' ),
		'class' => '.hustle-button-submit',
	),
	'form_extras'     => array(
		'label' => __( 'Form Extra Options Container', 'hustle' ),
		'class' => '.hustle-form-options',
	),
	'gdpr'            => array(
		'label' => __( 'GDPR Checkbox', 'hustle' ),
		'class' => '.hustle-gdpr',
	),
	'recaptcha'       => array(
		'label' => __( 'reCAPTCHA Text', 'hustle' ),
		'class' => '',
	),
	'error_message'   => array(
		'label' => __( 'Error Message', 'hustle' ),
		'class' => '.hustle-error',
	),
	'success_message' => array(
		'label' => __( 'Success Message Container', 'hustle' ),
		'class' => '.hustle-success',
	),
);

if ( $is_optin ) {

	// Elements used in informational only.
	unset( $general_options['layout_header'] );
	unset( $general_options['layout_footer'] );

	$general_option  = array();
	$general_content = array();

	foreach ( $general_options as $key => $option ) {
		$general_option['title']   = $option['label'];
		$general_option['notes']   = $option['class'];
		$general_option['key']     = isset( $option['row_name'] ) ? $option['row_name'] : $key;
		$general_option['content'] = $this->render(
			'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/options-template',
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

	foreach ( $optin_options as $key => $option ) {
		$optin_option['title']   = $option['label'];
		$optin_option['notes']   = $option['class'];
		$optin_option['key']     = $key;
		$optin_option['content'] = $this->render(
			'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/options-template',
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
			'name'     => 'custom-border-spacing-shadow' . $device_suffix,
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

	echo '<p class="sui-description" style="margin-top: 10px;">' . esc_html__( 'Switch between the General and Opt-in tabs for respective options.', 'hustle' ) . '</p>';

} else {

	$option  = array();
	$options = array();

	foreach ( $general_options as $key => $option ) {
		$option['title']   = $option['label'];
		$option['notes']   = $option['class'];
		$option['key']     = isset( $option['row_name'] ) ? $option['row_name'] : $key;
		$option['content'] = $this->render(
			'admin/commons/sui-wizard/tab-appearance/row-advanced/border-spacing-shadow/options-template',
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