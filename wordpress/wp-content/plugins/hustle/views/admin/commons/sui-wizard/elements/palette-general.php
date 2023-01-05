<?php
/**
 * Template for the color pickers to customize the elements of informational modules.
 *
 * @package Hustle
 * @since 4.0.0
 */

$palette_info = array(
	'main_container'      => array(
		/* translators: %s: module name */
		'title'   => sprintf( __( '%s Container', 'hustle' ), $capitalize_singular ),
		'notes'   => '.hustle-popup',
		'key'     => 'main_container',
		'palette' => array(
			'colors' => array(
				'overlay_background' => array(
					'name'  => __( 'Overlay Mask', 'hustle' ),
					'value' => 'overlay_bg',
					'alpha' => 'true',
				),
			),
		),
	),
	'main_layout'         => array(
		'title'   => __( 'Main Layout', 'hustle' ),
		'notes'   => $is_settings_page || $is_optin ? '.hustle-layout-body' : '.hustle-layout',
		'key'     => 'module_cont',
		'palette' => array(
			'colors' => array(
				'container_border'     => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'module_cont_border',
					'alpha' => 'true',
				),
				'container_background' => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'main_bg_color',
					'alpha' => 'true',
				),
				'container_shadow'     => array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'module_cont_drop_shadow',
					'alpha' => 'true',
				),
			),
		),
	),
	'layout_header'       => array(
		'title'   => __( 'Layout Header', 'hustle' ),
		'notes'   => '.hustle-layout-header',
		'key'     => 'layout_header',
		'palette' => array(
			'colors' => array(
				'layout_header_border'      => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'layout_header_border',
					'alpha' => 'true',
				),
				'layout_header_bg'          => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'layout_header_bg',
					'alpha' => 'true',
				),
				'layout_header_drop_shadow' => array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'layout_header_drop_shadow',
					'alpha' => 'true',
				),
			),
		),
	),
	'layout_content'      => array(
		'title'   => __( 'Layout Content', 'hustle' ),
		'notes'   => '.hustle-layout-content',
		'key'     => 'layout_content',
		'palette' => array(
			'colors' => array(
				'container_border'     => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'layout_content_border',
					'alpha' => 'true',
				),
				'container_background' => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'layout_content_bg',
					'alpha' => 'true',
				),
			),
		),
	),
	'layout_footer'       => array(
		'title'   => __( 'Layout Footer', 'hustle' ),
		'notes'   => '.hustle-layout-footer',
		'key'     => 'layout_footer',
		'palette' => array(
			'colors' => array(
				'layout_footer_border'      => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'layout_footer_border',
					'alpha' => 'true',
				),
				'layout_footer_bg'          => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'layout_footer_bg',
					'alpha' => 'true',
				),
				'layout_footer_drop_shadow' => array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'layout_footer_drop_shadow',
					'alpha' => 'true',
				),
			),
		),
	),
	'feature_image'       => array(
		'title'   => __( 'Feature Image', 'hustle' ),
		'notes'   => '.hustle-image',
		'key'     => 'feature_image',
		'palette' => array(
			'colors' => array(
				'image_background' => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'image_container_bg',
					'alpha' => 'true',
				),
			),
		),
	),
	'content_wrap'        => array(
		'title'   => __( 'Content Wrapper', 'hustle' ),
		'notes'   => '.hustle-content',
		'key'     => 'content_wrap',
		'palette' => array(
			'colors' => array(
				'content_wrap_border' => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'content_wrap_border',
					'alpha' => 'true',
				),
				'content_wrap_bg'     => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'content_wrap_bg',
					'alpha' => 'true',
				),
			),
		),
	),
	'title'               => array(
		'title'   => __( 'Title', 'hustle' ),
		'notes'   => '.hustle-title',
		'key'     => 'title',
		'palette' => array(
			'colors' => array(
				'title_color'          => array(
					'name'  => __( 'Font color', 'hustle' ),
					'value' => $is_settings_page || $is_optin ? 'title_color' : 'title_color_alt',
					'alpha' => 'false',
				),
				'title_color_settings' => array(
					'name'  => __( 'Font color - Informational modules', 'hustle' ),
					'value' => 'subtitle_color_alt',
					'alpha' => 'false',
				),
				'title_border'         => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'title_border',
					'alpha' => 'true',
				),
				'title_bg'             => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'title_bg',
					'alpha' => 'true',
				),
				'title_shadow'         => array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'title_drop_shadow',
					'alpha' => 'true',
				),
			),
		),
	),
	'sub_title'           => array(
		'title'   => __( 'Subtitle' ),
		'notes'   => '.hustle-subtitle',
		'key'     => 'sub_title',
		'palette' => array(
			'colors' => array(
				'subtitle_color'          => array(
					'name'  => __( 'Font color', 'hustle' ),
					'value' => $is_settings_page || $is_optin ? 'subtitle_color' : 'subtitle_color_alt',
					'alpha' => 'false',
				),
				'subtitle_color_settings' => array(
					'name'  => __( 'Font color - Informational modules', 'hustle' ),
					'value' => 'subtitle_color_alt',
					'alpha' => 'false',
				),
				'subtitle_border'         => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'subtitle_border',
					'alpha' => 'true',
				),
				'subtitle_bg'             => array(
					'name'  => __( 'Background color', 'hustle' ),
					'value' => 'subtitle_bg',
					'alpha' => 'true',
				),
				'subtitle_shadow'         => array(
					'name'  => __( 'Box shadow color', 'hustle' ),
					'value' => 'subtitle_drop_shadow',
					'alpha' => 'true',
				),
			),
		),
	),
	'main_content'        => array(
		'title'   => __( 'Main Content', 'hustle' ),
		'notes'   => '.hustle-group-content',
		'key'     => 'main_content',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'content_border'    => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'content_border',
							'alpha' => 'true',
						),
						'paragraph'         => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'content_color',
							'alpha' => 'false',
						),
						'ol_number'         => array(
							'name'  => __( 'OL counter', 'hustle' ),
							'value' => 'ol_counter',
							'alpha' => 'false',
						),
						'ul_bullet'         => array(
							'name'  => __( 'UL bullets', 'hustle' ),
							'value' => 'ul_bullets',
							'alpha' => 'false',
						),
						'blockquote_border' => array(
							'name'  => __( 'Blockquote border', 'hustle' ),
							'value' => 'blockquote_border',
							'alpha' => 'false',
						),
						'a_default'         => array(
							'name'  => __( 'Link color', 'hustle' ),
							'value' => 'link_static_color',
							'alpha' => 'false',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'a_hover' => array(
							'name'  => __( 'Link color', 'hustle' ),
							'value' => 'link_hover_color',
							'alpha' => 'false',
						),
					),
				),
				'focus'   => array(
					'name'   => __( 'Focus', 'hustle' ),
					'colors' => array(
						'a_focus' => array(
							'name'  => __( 'Link color', 'hustle' ),
							'value' => 'link_active_color',
							'alpha' => 'false',
						),
					),
				),
			),
		),
	),
	'cta_cont'            => array(
		'title'   => __( 'Call to Action - Container', 'hustle' ),
		'notes'   => '.hustle-cta-container',
		'key'     => 'show_cta',
		'palette' => array(
			'colors' => array(
				'subtitle_border' => array(
					'name'  => __( 'Border color', 'hustle' ),
					'value' => 'cta_cont_border',
					'alpha' => 'true',
				),
			),
		),
	),
	'show_cta'            => array(
		'title'   => __( 'Call To Action - Button', 'hustle' ),
		'notes'   => '.hustle-button-cta',
		'key'     => 'show_cta',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'cta_button_label'      => array(
							'name'  => __( 'Label color', 'hustle' ),
							'value' => 'cta_button_static_color',
							'alpha' => 'false',
						),
						'cta_button_border'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'cta_button_static_bo',
							'alpha' => 'true',
						),
						'cta_button_background' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'cta_button_static_bg',
							'alpha' => 'true',
						),
						array(
							'name'  => __( 'Box shadow color', 'hustle' ),
							'value' => 'cta_button_static_drop_shadow',
							'alpha' => 'true',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'cta_button_label_hover'      => array(
							'name'  => __( 'Label color', 'hustle' ),
							'value' => 'cta_button_hover_color',
							'alpha' => 'false',
						),
						'cta_button_border_hover'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'cta_button_hover_bo',
							'alpha' => 'true',
						),
						'cta_button_background_hover' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'cta_button_hover_bg',
							'alpha' => 'true',
						),
						array(
							'name'  => __( 'Box shadow color', 'hustle' ),
							'value' => 'cta_button_hover_drop_shadow',
							'alpha' => 'true',
						),
					),
				),
				'focus'   => array(
					'name'   => __( 'Focus', 'hustle' ),
					'colors' => array(
						'cta_button_label_active'      => array(
							'name'  => __( 'Label color', 'hustle' ),
							'value' => 'cta_button_active_color',
							'alpha' => 'false',
						),
						'cta_button_border_active'     => array(
							'name'  => __( 'Border color', 'hustle' ),
							'value' => 'cta_button_active_bo',
							'alpha' => 'true',
						),
						'cta_button_background_active' => array(
							'name'  => __( 'Background color', 'hustle' ),
							'value' => 'cta_button_active_bg',
							'alpha' => 'true',
						),
						array(
							'name'  => __( 'Box shadow color', 'hustle' ),
							'value' => 'cta_button_active_drop_shadow',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'close_button'        => array(
		'title'   => __( 'Close Button', 'hustle' ),
		'notes'   => '',
		'key'     => 'close_button',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'close_icon_default' => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'close_button_static_color',
							'alpha' => 'true',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'close_icon_hover' => array(
							'name'  => __( 'Icon color', 'hustle' ),
							'value' => 'close_button_hover_color',
							'alpha' => 'true',
						),
					),
				),
				'focus'   => array(
					'name'   => __( 'Focus', 'hustle' ),
					'colors' => array(
						'close_icon_focus' => array(
							'name'  => __( 'Close button', 'hustle' ),
							'value' => 'close_button_active_color',
							'alpha' => 'true',
						),
					),
				),
			),
		),
	),
	'show_never_see_link' => array(
		'title'   => __( '"Never see this again" Link', 'hustle' ),
		'notes'   => '.hustle-nsa-link',
		'key'     => 'show_never_see_link',
		'palette' => array(
			'group_states' => array(
				'default' => array(
					'name'   => __( 'Default', 'hustle' ),
					'colors' => array(
						'nsa_color_default' => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'never_see_link_static',
							'alpha' => 'false',
						),
					),
				),
				'hover'   => array(
					'name'   => __( 'Hover', 'hustle' ),
					'colors' => array(
						'nsa_color_hover' => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'never_see_link_hover',
							'alpha' => 'false',
						),
					),
				),
				'focus'   => array(
					'name'   => __( 'Focus', 'hustle' ),
					'colors' => array(
						'nsa_color_focus' => array(
							'name'  => __( 'Font color', 'hustle' ),
							'value' => 'never_see_link_active',
							'alpha' => 'false',
						),
					),
				),
			),
		),
	),
);

if ( ! $is_settings_page ) {
	unset(
		$palette_info['title']['palette']['colors']['title_color_settings'],
		$palette_info['sub_title']['palette']['colors']['subtitle_color_settings']
	);
}

// Unset colors for embed module.
if ( ! $is_settings_page && Hustle_Module_Model::EMBEDDED_MODULE === $module_type ) {
	unset( $palette_info['close_button'] ); // Embed modules don't use close button.
	unset( $palette_info['main_container'] ); // Embed modules don't use overlay mask.
	unset( $palette_info['show_never_see_link'] );
}

// Unset colors for slide-in module.
if ( ! $is_settings_page && Hustle_Module_Model::SLIDEIN_MODULE === $module_type ) {
	unset( $palette_info['main_container'] ); // Slide-in modules don't use overlay mask.
}

// Unset Layout header for optin modules.
if ( ! $is_settings_page && $is_optin ) {
	unset( $palette_info['layout_header'] );
}

foreach ( $palette_info as $key => $accordion ) {
	$palette_info[ $key ]['content'] = $this->render(
		'admin/commons/sui-wizard/elements/palette-accordion-content',
		array(
			'palette'          => $palette_info[ $key ]['palette'],
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
			'options' => $palette_info,
			'flushed' => $is_settings_page || $is_optin,
			'reset'   => ! $is_settings_page,
		)
	);
	?>

</div>