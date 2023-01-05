<?php
/**
 * Defines the values for the "Gray Slate" palette
 *
 * @package Hustle
 */

$button_border_static = '#2CAE9F';
$button_border_hover  = '#39CDBD';

$input_border_static = '#B0BEC6';
$input_border_hover  = '#4F5F6B';

return array(
	// ==================================================|
	// 1. BASIC                                          |
	// ==================================================|

	// Main background.
	'main_bg_color'                      => '#38454E',

	// Image container BG.
	'image_container_bg'                 => '#35414A',

	// Form area BG.
	'form_area_bg'                       => '#5D7380',

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
	'blockquote_border'                  => '#38C5B5',

	// Link color.
	'link_static_color'                  => '#38C5B5',

	// ***************************************************
	// 2.2. HOVER

	// Link color.
	'link_hover_color'                   => '#2DA194',

	// ***************************************************
	// 2.3. ACTIVE

	// Link color.
	'link_active_color'                  => '#2DA194',

	// ==================================================|
	// 3. CALL TO ACTION                                 |
	// ==================================================|

	// ***************************************************
	// 3.1. DEFAULT

	// Border color.
	'cta_button_static_bo'               => $button_border_static,

	// Background color.
	'cta_button_static_bg'               => '#38C5B5',

	// Label color.
	'cta_button_static_color'            => '#FFFFFF',

	// ***************************************************
	// 3.2. HOVER

	// Border color.
	'cta_button_hover_bo'                => $button_border_hover,

	// Background color.
	'cta_button_hover_bg'                => '#2DA194',

	// Label color.
	'cta_button_hover_color'             => '#FFFFFF',

	// ***************************************************
	// 3.3. ACTIVE

	// Border color.
	'cta_button_active_bo'               => $button_border_hover,

	// Background color.
	'cta_button_active_bg'               => '#2DA194',

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
	'optin_form_field_text_static_color' => '#5D7380',

	// Placeholder color.
	'optin_placeholder_color'            => '#AAAAAA',

	// ***************************************************
	// 4.2. HOVER

	// Icon color.
	'optin_input_icon_hover'             => '#5D7380',

	// Border color.
	'optin_input_hover_bo'               => $input_border_hover,

	// Background color.
	'optin_input_hover_bg'               => '#FFFFFF',

	// ***************************************************
	// 4.3. FOCUS

	// Icon color.
	'optin_input_icon_focus'             => '#5D7380',

	// Border color.
	'optin_input_active_bo'              => $input_border_hover,

	// Background color.
	'optin_input_active_bg'              => '#FFFFFF',

	// ***************************************************
	// 4.4. ERROR

	// Icon color.
	'optin_input_icon_error'             => '#D43858',

	// Border color.
	'optin_input_error_border'           => '#D43858',

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
	'optin_check_radio_tick_color'       => '#38C5B5',

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
	'gdpr_checkbox_icon'                 => '#38C5B5',

	// ***************************************************
	// 6.3. ERROR

	// Border color.
	'gdpr_checkbox_border_error'         => '#D43858',

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
	'optin_select_icon'                  => '#38C5B5',

	// Background color.
	'optin_select_background'            => '#FFFFFF',

	// Placeholder color.
	'optin_select_placeholder'           => '#AAAAAA',

	// Label color.
	'optin_select_label'                 => '#5D7380',

	// ***************************************************
	// 6.2. HOVER

	// Border color.
	'optin_select_border_hover'          => $input_border_hover,

	// Icon color.
	'optin_select_icon_hover'            => '#49E2D1',

	// Background color.
	'optin_select_background_hover'      => '#FFFFFF',

	// ***************************************************
	// 6.3. OPEN

	// Border color.
	'optin_select_border_open'           => $input_border_hover,

	// Icon color.
	'optin_select_icon_open'             => '#49E2D1',

	// Background color.
	'optin_select_background_open'       => '#FFFFFF',

	// ***************************************************
	// 6.4. ERROR

	// Border color.
	'optin_select_border_error'          => '#D43858',

	// Icon color.
	'optin_select_icon_error'            => '#D43858',

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
	'optin_dropdown_option_color'        => '#5D7380',

	// ***************************************************
	// 7.2. HOVER

	// Label color.
	'optin_dropdown_option_color_hover'  => '#FFFFFF',

	// Background color.
	'optin_dropdown_option_bg_hover'     => '#ADB5B7',

	// ***************************************************
	// 7.3. SELECTED

	// Label color.
	'optin_dropdown_option_color_active' => '#FFFFFF',

	// Background color.
	'optin_dropdown_option_bg_active'    => '#38C5B5',

	// ==================================================|
	// 8. CALENDAR                                       |
	// ==================================================|

	// ***************************************************
	// 8.1. DEFAULT

	// Container BG.
	'optin_calendar_background'          => '#FFFFFF',

	// Title color.
	'optin_calendar_title'               => '#35414A',

	// Navigation arrows.
	'optin_calendar_arrows'              => '#5D7380',

	// Table head color.
	'optin_calendar_thead'               => '#35414A',

	// Table cell background.
	'optin_calendar_cell_background'     => '#FFFFFF',

	// Table cell color.
	'optin_calendar_cell_color'          => '#5D7380',

	// ***************************************************
	// 8.2. HOVER

	// Navigation arrows.
	'optin_calendar_arrows_hover'        => '#5D7380',

	// Table cell background.
	'optin_calendar_cell_bg_hover'       => '#38C5B5',

	// Table cell color.
	'optin_calendar_cell_color_hover'    => '#FFFFFF',

	// ***************************************************
	// 8.3. ACTIVE

	// Navigation arrows.
	'optin_calendar_arrows_active'       => '#5D7380',

	// Table cell background.
	'optin_calendar_cell_bg_active'      => '#38C5B5',

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
	'optin_submit_button_static_bg'      => '#38C5B5',

	// Label color.
	'optin_submit_button_static_color'   => '#FFFFFF',

	// ***************************************************
	// 9.2. HOVER

	// border color.
	'optin_submit_button_hover_bo'       => $button_border_hover,

	// Background color.
	'optin_submit_button_hover_bg'       => '#49E2D1',

	// Label color.
	'optin_submit_button_hover_color'    => '#FFFFFF',

	// ***************************************************
	// 9.3. ACTIVE

	// Border color.
	'optin_submit_button_active_bo'      => $button_border_hover,

	// Background color.
	'optin_submit_button_active_bg'      => '#49E2D1',

	// Label color.
	'optin_submit_button_active_color'   => '#FFFFFF',

	// ==================================================|
	// 10. CUSTOM FIELDS SECTION                         |
	// ==================================================|

	// Title color.
	'optin_mailchimp_title_color'        => '#FFFFFF',

	// Container background.
	'custom_section_bg'                  => '#35414A',

	// ==================================================|
	// 11. ERROR MESSAGE                                 |
	// ==================================================|

	// Background color.
	'optin_error_text_bg'                => '#FFFFFF',

	// Border color.
	'optin_error_text_border'            => '#D43858',

	// Message color.
	'optin_error_text_color'             => '#D43858',

	// ==================================================|
	// 12. SUCCESS MESSAGE                               |
	// ==================================================|

	// Background color.
	'optin_success_background'           => '#38454E',

	// Icon color.
	'optin_success_tick_color'           => '#38C5B5',

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
	'close_button_static_color'          => '#38C5B5',

	// Never see link.
	'never_see_link_static'              => '#38C5B5',

	// reCAPTCHA copy text.
	'recaptcha_copy_text'                => '#FFFFFF',

	// reCAPTCHA copy link.
	'recaptcha_copy_link_default'        => '#FFFFFF',

	// ***************************************************
	// 13.2. HOVER

	// Close button.
	'close_button_hover_color'           => '#49E2D1',

	// Never see link.
	'never_see_link_hover'               => '#49E2D1',

	// reCAPTCHA copy link.
	'recaptcha_copy_link_hover'          => '#FFFFFF',

	// ***************************************************
	// 13.3. ACTIVE

	// Close button.
	'close_button_active_color'          => '#49E2D1',

	// Never see link.
	'never_see_link_active'              => '#49E2D1',

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