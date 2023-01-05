<?php
/**
 * Defines the values for the "Midnight" palette
 *
 * @package Hustle
 */

$button_border_static = '#C54231';
$button_border_hover  = '#D14735';

$input_border_static = 'rgba(128,140,151,0.6)';
$input_border_hover  = '#636A71';

return array(
	// ==================================================|
	// 1. BASIC                                          |
	// ==================================================|

	// Main background.
	'main_bg_color'                      => '#25282B',

	// Image container BG.
	'image_container_bg'                 => '#232629',

	// Form area BG.
	'form_area_bg'                       => '#363B3F',

	// ==================================================|
	// 2. CONTENT                                        |
	// ==================================================|

	// ***************************************************
	// 2.1. DEFAULT

	// Title color.
	'title_color'                        => '#FFFFFF',
	'title_color_alt'                    => '#ADB5B7',

	// Subtitle color.
	'subtitle_color'                     => '#FFFFFF',
	'subtitle_color_alt'                 => '#ADB5B7',

	// Content color.
	'content_color'                      => '#ADB5B7',

	// OL counter.
	'ol_counter'                         => '#ADB5B7',

	// UL bullets.
	'ul_bullets'                         => '#ADB5B7',

	// Blockquote border.
	'blockquote_border'                  => '#DD4F3D',

	// Link color.
	'link_static_color'                  => '#DD4F3D',

	// ***************************************************
	// 2.2. HOVER

	// Link color.
	'link_hover_color'                   => '#C63D2B',

	// ***************************************************
	// 2.3. ACTIVE

	// Link color.
	'link_active_color'                  => '#C63D2B',

	// ==================================================|
	// 3. CALL TO ACTION                                 |
	// ==================================================|

	// ***************************************************
	// 3.1. DEFAULT

	// Border color.
	'cta_button_static_bo'               => $button_border_static,

	// Background color.
	'cta_button_static_bg'               => '#DD4F3D',

	// Label color.
	'cta_button_static_color'            => '#FFFFFF',

	// ***************************************************
	// 3.2. HOVER

	// Border color.
	'cta_button_hover_bo'                => $button_border_hover,

	// Background color.
	'cta_button_hover_bg'                => '#C63D2B',

	// Label color.
	'cta_button_hover_color'             => '#FFFFFF',

	// ***************************************************
	// 3.3. ACTIVE

	// Border color.
	'cta_button_active_bo'               => $button_border_hover,

	// Background color.
	'cta_button_active_bg'               => '#C63D2B',

	// Label color.
	'cta_button_active_color'            => '#FFFFFF',

	// ==================================================|
	// 4. INPUTS                                         |
	// ==================================================|

	// ***************************************************
	// 4.1. DEFAULT

	// Icon color.
	'optin_input_icon'                   => '#AAAAAA',

	// Border color.
	'optin_input_static_bo'              => $input_border_static,

	// Background color.
	'optin_input_static_bg'              => '#FFFFFF',

	// Text color.
	'optin_form_field_text_static_color' => '#363B3F',

	// Placeholder color.
	'optin_placeholder_color'            => '#AAAAAA',

	// ***************************************************
	// 4.2. HOVER

	// Icon color.
	'optin_input_icon_hover'             => '#232629',

	// Border color.
	'optin_input_hover_bo'               => $input_border_hover,

	// Background color.
	'optin_input_hover_bg'               => '#FFFFFF',

	// ***************************************************
	// 4.3. FOCUS

	// Icon color.
	'optin_input_icon_focus'             => '#232629',

	// Border color.
	'optin_input_active_bo'              => $input_border_hover,

	// Background color.
	'optin_input_active_bg'              => '#FFFFFF',

	// ***************************************************
	// 4.4. ERROR

	// Icon color.
	'optin_input_icon_error'             => '#DD4F3D',

	// Border color.
	'optin_input_error_border'           => '#DD4F3D',

	// Background color.
	'optin_input_error_background'       => '#FFFFFF',


	// ==================================================|
	// 5. RADIO AND CHECKBOX                             |
	// ==================================================|

	// ***************************************************
	// 5.1. DEFAULT

	// Border color.
	'optin_check_radio_bo'               => $input_border_static,

	// Background color.
	'optin_check_radio_bg'               => '#FFFFFF',

	// Label color.
	'optin_mailchimp_labels_color'       => '#FFFFFF',

	// ***************************************************
	// 5.2. CHECKED

	// Border color.
	'optin_check_radio_bo_checked'       => $input_border_hover,

	// Background color.
	'optin_check_radio_bg_checked'       => '#FFFFFF',

	// Icon color.
	'optin_check_radio_tick_color'       => '#DD4F3D',

	// ==================================================|
	// 6. GDPR CHECKBOX                                  |
	// ==================================================|

	// ***************************************************
	// 6.1. DEFAULT

	// Border color.
	'gdpr_chechbox_border_static'        => $input_border_static,

	// Background color.
	'gdpr_chechbox_background_static'    => '#FFFFFF',

	// Label color.
	'gdpr_content'                       => '#FFFFFF',

	// Label link color.
	'gdpr_content_link'                  => '#FFFFFF',

	// ***************************************************
	// 6.2. CHECKED

	// Border color.
	'gdpr_chechbox_border_active'        => $input_border_hover,

	// Background color.
	'gdpr_checkbox_background_active'    => '#FFFFFF',

	// Icon color.
	'gdpr_checkbox_icon'                 => '#DD4F3D',

	// ***************************************************
	// 6.3. ERROR

	// Border color.
	'gdpr_checkbox_border_error'         => '#DD4F3D',

	// Background color.
	'gdpr_checkbox_background_error'     => '#FFFFFF',

	// ==================================================|
	// 6. SELECT                                         |
	// ==================================================|

	// ***************************************************
	// 6.1. DEFAULT

	// Select Border color.
	'optin_select_border'                => $input_border_static,

	// Icon color.
	'optin_select_icon'                  => '#DD4F3D',

	// Background color.
	'optin_select_background'            => '#FFFFFF',

	// Placeholder color.
	'optin_select_placeholder'           => '#AAAAAA',

	// Label color.
	'optin_select_label'                 => '#DD4F3D',

	// ***************************************************
	// 6.2. HOVER

	// Border color.
	'optin_select_border_hover'          => $input_border_hover,

	// Icon color.
	'optin_select_icon_hover'            => '#C63D2B',

	// Background color.
	'optin_select_background_hover'      => '#FFFFFF',

	// ***************************************************
	// 6.3. OPEN

	// Border color.
	'optin_select_border_open'           => $input_border_hover,

	// Icon color.
	'optin_select_icon_open'             => '#C63D2B',

	// Background color.
	'optin_select_background_open'       => '#FFFFFF',

	// ***************************************************
	// 6.4. ERROR

	// Border color.
	'optin_select_border_error'          => '#DD4F3D',

	// Icon color.
	'optin_select_icon_error'            => '#DD4F3D',

	// Background color.
	'optin_select_background_error'      => '#FFFFFF',

	// ==================================================|
	// 7. DROPDOWN                                       |
	// ==================================================|

	// ***************************************************
	// 7.1. DEFAULT

	// Container BG.
	'optin_dropdown_background'          => '#FFFFFF',

	// Label color.
	'optin_dropdown_option_color'        => '#DD4F3D',

	// ***************************************************
	// 7.2. HOVER

	// Label color.
	'optin_dropdown_option_color_hover'  => '#FFFFFF',

	// Background color.
	'optin_dropdown_option_bg_hover'     => '#AAAAAA',

	// ***************************************************
	// 7.3. SELECTED

	// Label color.
	'optin_dropdown_option_color_active' => '#FFFFFF',

	// Background color.
	'optin_dropdown_option_bg_active'    => '#DD4F3D',

	// ==================================================|
	// 8. CALENDAR                                       |
	// ==================================================|

	// ***************************************************
	// 8.1. DEFAULT

	// Container BG.
	'optin_calendar_background'          => '#FFFFFF',

	// Title color.
	'optin_calendar_title'               => '#232629',

	// Navigation arrows.
	'optin_calendar_arrows'              => '#232629',

	// Table head color.
	'optin_calendar_thead'               => '#35414A',

	// Table cell background.
	'optin_calendar_cell_background'     => '#FFFFFF',

	// Table cell color.
	'optin_calendar_cell_color'          => '#5D7380',

	// ***************************************************
	// 8.2. HOVER

	// Navigation arrows.
	'optin_calendar_arrows_hover'        => '#165E80',

	// Table cell background.
	'optin_calendar_cell_bg_hover'       => '#DD4F3D',

	// Table cell color.
	'optin_calendar_cell_color_hover'    => '#FFFFFF',

	// ***************************************************
	// 8.3. ACTIVE

	// Navigation arrows.
	'optin_calendar_arrows_active'       => '#165E80',

	// Table cell background.
	'optin_calendar_cell_bg_active'      => '#DD4F3D',

	// Table cell color.
	'optin_calendar_cell_color_active'   => '#FFFFFF',

	// ==================================================|
	// 9. SUBMIT BUTTON                                  |
	// ==================================================|

	// ***************************************************
	// 9.1. DEFAULT

	// Border color.
	'optin_submit_button_static_bo'      => $button_border_static,

	// Background color.
	'optin_submit_button_static_bg'      => '#DD4F3D',

	// Label color.
	'optin_submit_button_static_color'   => '#FFFFFF',

	// ***************************************************
	// 9.2. HOVER

	// border color.
	'optin_submit_button_hover_bo'       => $button_border_hover,

	// Background color.
	'optin_submit_button_hover_bg'       => '#C63D2B',

	// Label color.
	'optin_submit_button_hover_color'    => '#FFFFFF',

	// ***************************************************
	// 9.3. ACTIVE

	// Border color.
	'optin_submit_button_active_bo'      => $button_border_hover,

	// Background color.
	'optin_submit_button_active_bg'      => '#C63D2B',

	// Label color.
	'optin_submit_button_active_color'   => '#FFFFFF',

	// ==================================================|
	// 10. CUSTOM FIELDS SECTION                         |
	// ==================================================|

	// Title color.
	'optin_mailchimp_title_color'        => '#FFFFFF',

	// Container background.
	'custom_section_bg'                  => '#232629',

	// ==================================================|
	// 11. ERROR MESSAGE                                 |
	// ==================================================|

	// Background color.
	'optin_error_text_bg'                => '#FFFFFF',

	// Border color.
	'optin_error_text_border'            => '#DD4F3D',

	// Message color.
	'optin_error_text_color'             => '#DD4F3D',

	// ==================================================|
	// 12. SUCCESS MESSAGE                               |
	// ==================================================|

	// Background color.
	'optin_success_background'           => '#25282B',

	// Icon color.
	'optin_success_tick_color'           => '#DD4F3D',

	// Content color.
	'optin_success_content_color'        => '#ADB5B7',

	// ==================================================|
	// 13. ADDITIONAL SETTINGS                           |
	// ==================================================|

	// ***************************************************
	// 13.1. DEFAULT

	// Pop-up mask.
	'overlay_bg'                         => 'rgba(51,51,51,0.9)',

	// Close button.
	'close_button_static_color'          => '#DD4F3D',

	// Never see link.
	'never_see_link_static'              => '#DD4F3D',

	// reCAPTCHA copy text.
	'recaptcha_copy_text'                => '#FFFFFF',

	// reCAPTCHA copy link.
	'recaptcha_copy_link_default'        => '#FFFFFF',

	// ***************************************************
	// 13.2. HOVER

	// Close button.
	'close_button_hover_color'           => '#C63D2B',

	// Never see link.
	'never_see_link_hover'               => '#C63D2B',

	// reCAPTCHA copy link.
	'recaptcha_copy_link_hover'          => '#FFFFFF',

	// ***************************************************
	// 13.3. ACTIVE

	// Close button.
	'close_button_active_color'          => '#C63D2B',

	// Never see link.
	'never_see_link_active'              => '#C63D2B',

	// reCAPTCHA copy link.
	'recaptcha_copy_link_focus'          => '#FFFFFF',

	// New colors settings to adjust and re-arrange.
	'module_cont_drop_shadow'            => 'rgba(0,0,0,0)',
	'module_cont_border'                 => '#DADADA',

	'layout_header_border'               => $is_optin ? 'rgba(0,0,0,0)' : 'rgba(0,0,0,0.16)',
	'layout_header_bg'                   => 'rgba(0,0,0,0)',
	'layout_header_drop_shadow'          => 'rgba(0,0,0,0)',

	'layout_content_border'              => 'rgba(0,0,0,0)',
	'layout_content_bg'                  => 'rgba(0,0,0,0)',

	'layout_footer_border'               => 'rgba(0,0,0,0)',
	'layout_footer_bg'                   => $is_optin ? 'rgba(0,0,0,0)' : 'rgba(0,0,0,0.16)',
	'layout_footer_drop_shadow'          => 'rgba(0,0,0,0)',

	'form_cont_border'                   => 'rgba(0,0,0,0)',
	'form_cont_drop_shadow'              => 'rgba(0,0,0,0)',

	'title_drop_shadow'                  => 'rgba(0,0,0,0)',
	'title_border'                       => 'rgba(0,0,0,0)',
	'title_bg'                           => 'rgba(0,0,0,0)',

	'subtitle_drop_shadow'               => 'rgba(0,0,0,0)',
	'subtitle_border'                    => 'rgba(0,0,0,0)',
	'subtitle_bg'                        => 'rgba(0,0,0,0)',

	'content_wrap_border'                => 'rgba(0,0,0,0)',
	'content_wrap_bg'                    => 'rgba(0,0,0,0)',

	'cta_button_static_drop_shadow'      => 'rgba(0,0,0,0)',
	'cta_button_hover_drop_shadow'       => 'rgba(0,0,0,0)',
	'cta_button_active_drop_shadow'      => 'rgba(0,0,0,0)',

	'form_extras_border'                 => 'rgba(0,0,0,0)',
	'form_extras_drop_shadow'            => 'rgba(0,0,0,0)',

	'optin_input_drop_shadow'            => 'rgba(0,0,0,0)',

	'optin_dropdown_border'              => '#FFFFFF',
	'optin_dropdown_drop_shadow'         => 'rgba(0,0,0,0)',

	'optin_success_border'               => 'rgba(0,0,0,0)',
	'optin_success_drop_shadow'          => 'rgba(0,0,0,0)',

	'submit_button_static_drop_shadow'   => 'rgba(0,0,0,0)',

	'recaptcha_copy_border'              => 'rgba(0,0,0,0)',
	'recaptcha_copy_drop_shadow'         => 'rgba(0,0,0,0)',

	'content_border'                     => 'rgba(0,0,0,0)',
	'cta_cont_border'                    => 'rgba(0,0,0,0)',
);