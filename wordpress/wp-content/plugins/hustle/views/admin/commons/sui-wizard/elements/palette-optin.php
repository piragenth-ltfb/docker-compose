<?php
/**
 * Template for the color pickers to customize opt-in elements.
 *
 * @package Hustle
 * @since 4.0.0
 */

$palette_optin = array(
	'layout_form'     => array(
		'title'   => __( 'Form Container', 'hustle' ),
		'notes'   => '.hustle-layout-form',
		'key'     => 'layout_form',
		'palette' => array(
			'colors' => array(
				'form_border_color' => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'form_cont_border',
					'alpha' => 'true',
				),
				'form_background'   => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'form_area_bg',
					'alpha' => 'true',
				),
			),
		),
	),
	'inputs'          => array(
		'title'   => __( 'Inputs', 'hustle' ),
		'notes'   => '.hustle-input',
		'key'     => 'inputs',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'popup_field_icon'        => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_input_icon',
							'alpha' => 'true',
						),
						'popup_field_placeholder' => array(
							'name'  => __( 'Placeholder color', 'hustle' ),
							'value' => 'optin_placeholder_color',
							'alpha' => 'false',
						),
						'popup_field_color'       => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'optin_form_field_text_static_color',
							'alpha' => 'false',
						),
						'popup_field_border'      => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_input_static_bo',
							'alpha' => 'true',
						),
						'popup_field_background'  => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_input_static_bg',
							'alpha' => 'true',
						),
						'popup_field_shadow'      => array(
							'name'  => __( 'Box shadow color', 'hustle' ),
							'value' => 'optin_input_drop_shadow',
							'alpha' => 'true',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'popup_field_icon_hover'       => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_input_icon_hover',
							'alpha' => 'true',
						),
						'popup_field_border_hover'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_input_hover_bo',
							'alpha' => 'true',
						),
						'popup_field_background_hover' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_input_hover_bg',
							'alpha' => 'true',
						),
					),
				),
				'focus'   => array(
					'name'   => __( 'Focus', 'hustle' ),
					'colors' => array(
						'popup_field_icon_focus'       => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_input_icon_focus',
							'alpha' => 'true',
						),
						'popup_field_border_focus'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_input_active_bo',
							'alpha' => 'true',
						),
						'popup_field_background_focus' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_input_active_bg',
							'alpha' => 'true',
						),
					),
				),
				'error'   => array(
					'name'   => __( 'Error', 'hustle' ),
					'colors' => array(
						'popup_field_icon_error'       => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_input_icon_error',
							'alpha' => 'true',
						),
						'popup_field_border_error'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_input_error_border',
							'alpha' => 'true',
						),
						'popup_field_background_error' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_input_error_background',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'checkbox'        => array(
		'title'   => __( 'Radio and Checkbox', 'hustle' ),
		'notes'   => '.hustle-radio and .hustle-checkbox',
		'key'     => 'checkbox',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'checkbox_border'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_check_radio_bo',
							'alpha' => 'true',
						),
						'checkbox_background' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_check_radio_bg',
							'alpha' => 'true',
						),
						'checkbox_label'      => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'optin_mailchimp_labels_color',
							'alpha' => 'false',
						),
					),
				),
				'checked' => array(
					'name'   => __( 'Checked', 'hustle' ),
					'colors' => array(
						'checkbox_icon'               => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_check_radio_tick_color',
							'alpha' => 'false',
						),
						'checkbox_border_checked'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_check_radio_bo_checked',
							'alpha' => 'true',
						),
						'checkbox_background_checked' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_check_radio_bg_checked',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'select'          => array(
		'title'   => __( 'Select', 'hustle' ),
		'notes'   => '',
		'key'     => 'select',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'select_icon'        => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_select_icon',
							'alpha' => 'true',
						),
						'select_placeholder' => array(
							'name'  => __( 'Placeholder', 'hustle' ),
							'value' => 'optin_select_placeholder',
							'alpha' => 'true',
						),
						'select_label'       => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'optin_select_label',
							'alpha' => 'false',
						),
						'select_border'      => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_select_border',
							'alpha' => 'true',
						),
						'select_background'  => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_select_background',
							'alpha' => 'true',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'select_icon_hover'       => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_select_icon_hover',
							'alpha' => 'true',
						),
						'select_border_hover'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_select_border_hover',
							'alpha' => 'true',
						),
						'select_background_hover' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_select_background_hover',
							'alpha' => 'true',
						),
					),
				),
				'open'    => array(
					'name'   => __( 'Open', 'hustle' ),
					'colors' => array(
						'select_icon_open'       => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_select_icon_open',
							'alpha' => 'true',
						),
						'select_border_open'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_select_border_open',
							'alpha' => 'true',
						),
						'select_background_open' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_select_background_open',
							'alpha' => 'true',
						),
					),
				),
				'error'   => array(
					'name'   => __( 'Error', 'hustle' ),
					'colors' => array(
						'select_icon_error'       => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'optin_select_icon_error',
							'alpha' => 'true',
						),
						'select_background_error' => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_select_border_error',
							'alpha' => 'true',
						),
						'select_background_error' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_select_background_error',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'dropdown'        => array(
		'title'   => __( 'Dropdown', 'hustle' ),
		'notes'   => '',
		'key'     => 'dropdown',
		'palette' => array(
			'group_states' => array(
				'default'  => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'dropdown_border'       => array(
							'name'  => __( 'Container | Border color', 'hustle' ),
							'value' => 'optin_dropdown_border',
							'alpha' => 'true',
						),
						'dropdown_background'   => array(
							'name'  => __( 'Container | Background color', 'hustle' ),
							'value' => 'optin_dropdown_background',
							'alpha' => 'true',
						),
						'dropdown_shadow'       => array(
							'name'  => __( 'Container | Box shadow color', 'hustle' ),
							'value' => 'optin_dropdown_drop_shadow',
							'alpha' => 'true',
						),
						'dropdown_option_color' => array(
							'name'  => __( 'Option | Font color', 'hustle' ),
							'value' => 'optin_dropdown_option_color',
							'alpha' => 'false',
						),
					),
				),
				'hover'    => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'dropdown_option_bg_hover'    => array(
							'name'  => __( 'Option | Background color', 'hustle' ),
							'value' => 'optin_dropdown_option_bg_hover',
							'alpha' => 'true',
						),
						'dropdown_option_color_hover' => array(
							'name'  => __( 'Option | Font color', 'hustle' ),
							'value' => 'optin_dropdown_option_color_hover',
							'alpha' => 'false',
						),
					),
				),
				'selected' => array(
					'name'   => __( 'Selected', 'hustle' ),
					'colors' => array(
						'dropdown_option_bg_active'    => array(
							'name'  => __( 'Option | Background color', 'hustle' ),
							'value' => 'optin_dropdown_option_bg_active',
							'alpha' => 'true',
						),
						'dropdown_option_color_active' => array(
							'name'  => __( 'Option | Font color', 'hustle' ),
							'value' => 'optin_dropdown_option_color_active',
							'alpha' => 'false',
						),
					),
				),
			),
		),
	),
	'calendar'        => array(
		'title'   => __( 'Calendar', 'hustle' ),
		'notes'   => '',
		'key'     => 'calendar',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'calendar_background'       => array(
							'name'  => __( 'Container background', 'hustle' ),
							'value' => 'optin_calendar_background',
							'alpha' => 'true',
						),
						'optin_calendar_title'      => array(
							'name'  => __( 'Title color', 'hustle' ),
							'value' => 'optin_calendar_title',
							'alpha' => 'false',
						),
						'optin_calendar_arrows'     => array(
							'name'  => __( 'Navigation arrows', 'hustle' ),
							'value' => 'optin_calendar_arrows',
							'alpha' => 'true',
						),
						'optin_calendar_thead'      => array(
							'name'  => __( 'Table head color', 'hustle' ),
							'value' => 'optin_calendar_thead',
							'alpha' => 'false',
						),
						'optin_calendar_cell_bg'    => array(
							'name'  => __( 'Table cell background', 'hustle' ),
							'value' => 'optin_calendar_cell_background',
							'alpha' => 'true',
						),
						'optin_calendar_cell_color' => array(
							'name'  => __( 'Table cell color', 'hustle' ),
							'value' => 'optin_calendar_cell_color',
							'alpha' => 'true',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'optin_calendar_arrows_hover'     => array(
							'name'  => __( 'Navigation arrows', 'hustle' ),
							'value' => 'optin_calendar_arrows_hover',
							'alpha' => 'true',
						),
						'optin_calendar_cell_bg_hover'    => array(
							'name'  => __( 'Table cell background', 'hustle' ),
							'value' => 'optin_calendar_cell_bg_hover',
							'alpha' => 'true',
						),
						'optin_calendar_cell_color_hover' => array(
							'name'  => __( 'Table cell color', 'hustle' ),
							'value' => 'optin_calendar_cell_color_hover',
							'alpha' => 'true',
						),
					),
				),
				array(
					'name'   => __( 'Active', 'hustle' ),
					'colors' => array(
						'optin_calendar_arrows_active'     => array(
							'name'  => __( 'Navigation arrows', 'hustle' ),
							'value' => 'optin_calendar_arrows_active',
							'alpha' => 'true',
						),
						'optin_calendar_cell_bg_active'    => array(
							'name'  => __( 'Table cell background', 'hustle' ),
							'value' => 'optin_calendar_cell_bg_active',
							'alpha' => 'true',
						),
						'optin_calendar_cell_color_active' => array(
							'name'  => __( 'Table cell color', 'hustle' ),
							'value' => 'optin_calendar_cell_color_active',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'submit_button'   => array(
		'title'   => __( 'Submit Button', 'hustle' ),
		'notes'   => '.hustle-button-submit',
		'key'     => 'submit_button',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'submit_border'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_submit_button_static_bo',
							'alpha' => 'true',
						),
						'submit_background' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_submit_button_static_bg',
							'alpha' => 'true',
						),
						'submit_box_shadow' => array(
							'name'  => __( 'Box shadow color', 'hustle' ),
							'value' => 'submit_button_static_drop_shadow',
							'alpha' => 'true',
						),
						'submit_label'      => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'optin_submit_button_static_color',
							'alpha' => 'false',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'submit_border_hover'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_submit_button_hover_bo',
							'alpha' => 'true',
						),
						'submit_background_hover' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_submit_button_hover_bg',
							'alpha' => 'true',
						),
						'submit_label_hover'      => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'optin_submit_button_hover_color',
							'alpha' => 'false',
						),
					),
				),
				'active'  => array(
					'name'    => __( 'Active', 'hustle' ),
					'current' => false,
					'colors'  => array(
						'submit_border_active'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'optin_submit_button_active_bo',
							'alpha' => 'true',
						),
						'submit_background_active' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'optin_submit_button_active_bg',
							'alpha' => 'true',
						),
						'submit_label_active'      => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'optin_submit_button_active_color',
							'alpha' => 'false',
						),
					),
				),
			),
		),
	),
	'form_extras'     => array(
		'title'   => __( 'Mailchimp Options', 'hustle' ),
		'notes'   => '.hustle-form-options',
		'key'     => 'form_extras',
		'palette' => array(
			'colors' => array(
				'form_options_title'      => array(
					'name'  => __( 'Title color', 'hustle' ),
					'value' => 'optin_mailchimp_title_color',
					'alpha' => 'false',
				),
				'form_options_border'     => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'form_extras_border',
					'alpha' => 'true',
				),
				'form_options_background' => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'custom_section_bg',
					'alpha' => 'true',
				),
				'form_options_shadow'     => array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'form_extras_drop_shadow',
					'alpha' => 'true',
				),
			),
		),
	),
	'gdpr'            => array(
		'title'   => __( 'GDPR Checkbox', 'hustle' ),
		'notes'   => '.hustle-checkbox.hustle-gdpr',
		'key'     => 'gdpr',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'gdpr_border'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'gdpr_chechbox_border_static',
							'alpha' => 'true',
						),
						'gdpr_background' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'gdpr_chechbox_background_static',
							'alpha' => 'true',
						),
						'gdpr_label'      => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'gdpr_content',
							'alpha' => 'false',
						),
						'gdpr_label_link' => array(
							'name'  => __( 'Link color', 'hustle' ),
							'value' => 'gdpr_content_link',
							'alpha' => 'false',
						),
					),
				),
				'checked' => array(
					'name'   => __( 'Checked', 'hustle' ),
					'colors' => array(
						'gdpr_border_checked'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'gdpr_chechbox_border_active',
							'alpha' => 'true',
						),
						'gdpr_background_checked' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'gdpr_checkbox_background_active',
							'alpha' => 'true',
						),
						'gdpr_icon'               => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'gdpr_checkbox_icon',
							'alpha' => 'false',
						),
					),
				),
				'error'   => array(
					'name'   => __( 'Error', 'hustle' ),
					'colors' => array(
						'gdpr_border_error'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'gdpr_checkbox_border_error',
							'alpha' => 'true',
						),
						'gdpr_background_error' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'gdpr_checkbox_background_error',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'recaptcha'       => array(
		'title'   => __( 'reCAPTCHA Copy Text', 'hustle' ),
		'notes'   => '',
		'key'     => 'recaptcha',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'recaptcha_copy_border',
							'alpha' => 'true',
						),
						array(
							'name'  => __( 'Box shadow color', 'hustle' ),
							'value' => 'recaptcha_copy_drop_shadow',
							'alpha' => 'true',
						),
						'recaptcha_text'         => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'recaptcha_copy_text',
							'alpha' => 'true',
						),
						'recaptcha_link_default' => array(
							'name'  => __( 'Link(s) color', 'hustle' ),
							'value' => 'recaptcha_copy_link_default',
							'alpha' => 'true',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'recaptcha_link_hover' => array(
							'name'  => __( 'Link(s) color', 'hustle' ),
							'value' => 'recaptcha_copy_link_hover',
							'alpha' => 'true',
						),
					),
				),
				'active'  => array(
					'name'   => __( 'Active', 'hustle' ),
					'colors' => array(
						'recaptcha_link_focus' => array(
							'name'  => __( 'Link(s) color', 'hustle' ),
							'value' => 'recaptcha_copy_link_focus',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'error_message'   => array(
		'title'   => __( 'Error Message', 'hustle' ),
		'key'     => 'error_message',
		'palette' => array(
			'colors' => array(
				'error_message_border'     => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'optin_error_text_border',
					'alpha' => 'true',
				),
				'error_message_background' => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'optin_error_text_bg',
					'alpha' => 'true',
				),
				'error_message_label'      => array(
					'name'  => __( 'Message color', 'hustle' ),
					'value' => 'optin_error_text_color',
					'alpha' => 'false',
				),
			),
		),
	),
	'success_message' => array(
		'title'   => __( 'Success Message', 'hustle' ),
		'notes'   => '.hustle-success',
		'key'     => 'success_message',
		'palette' => array(
			'colors' => array(
				array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'optin_success_border',
					'alpha' => 'true',
				),
				'success_background' => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'optin_success_background',
					'alpha' => 'true',
				),
				array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'optin_success_drop_shadow',
					'alpha' => 'true',
				),
				'success_icon'       => array(
					'name'  => __( 'Success icon color', 'hustle' ),
					'value' => 'optin_success_tick_color',
					'alpha' => 'false',
				),
				'success_color'      => array(
					'name'  => __( 'Content color', 'hustle' ),
					'value' => 'optin_success_content_color',
					'alpha' => 'false',
				),
			),
		),
	),
);

foreach ( $palette_optin as $key => $accordion ) {
	$palette_optin[ $key ]['content'] = $this->render(
		'admin/commons/sui-wizard/elements/palette-accordion-content',
		array(
			'palette'          => $palette_optin[ $key ]['palette'],
			'settings'         => ! $is_settings_page ? $settings : array(),
			'is_settings_page' => $is_settings_page,
		),
		true
	);
}
?>

<div id="hustle-color-palette" class="sui-form-field">

	<?php if ( ! empty( $colors_label ) ) : ?>
		<label class="sui-label"><?php esc_html_e( 'Colors', 'hustle' ); ?></label>
	<?php endif; ?>

	<?php
	$this->render(
		'admin/global/sui-components/sui-accordion',
		array(
			'options' => $palette_optin,
			'flushed' => true,
			'reset'   => ! $is_settings_page,
		)
	);
	?>

</div>