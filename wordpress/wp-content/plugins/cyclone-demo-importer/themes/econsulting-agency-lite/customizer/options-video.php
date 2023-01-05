<?php

add_action( 'init' , 'econsulting_agency_video_fields' );
function econsulting_agency_video_fields(){

	Kirki::add_section( 'econsulting_agency_video', array(
	    'title'          => esc_html__( 'Video', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_video_status',
		'label'       => esc_html__( 'Enable Video ?', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => true,
	] );

	if( function_exists( 'bizberg_kirki_dtm_options' ) ){

	    bizberg_kirki_dtm_options( 
	        array(
	            'display' => array(
	                'desktop' => 'desktop',
	                'tablet'  => 'tablet',
	                'mobile'  => 'mobile'
	            ),
	            'field_id' => 'bizberg',
	            'section'  => 'econsulting_agency_video',
	            'settings' => 'econsulting_agency_video_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_video_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_video_spacing',
	                        'default'     => [
								'padding-top'     => '100px',
								'padding-bottom'  => '100px'
							],
							'choices'     => [
								'labels' => [
									'padding-top'     => esc_html__( 'Top', 'cdi' ),
									'padding-bottom'  => esc_html__( 'Bottom', 'cdi' )
								],
							],
	                        'transport' => 'postMessage',
		                    'output'    => [
		                        [
		                            'element'       => '.econsult-call-to-action'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-call-to-action'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_video_spacing',
	                        'default'     => [
								'padding-top'     => '100px',
								'padding-bottom'  => '100px'
							],
							'choices'     => [
								'labels' => [
									'padding-top'     => esc_html__( 'Top', 'cdi' ),
									'padding-bottom'  => esc_html__( 'Bottom', 'cdi' )
								],
							],
	                        'transport' => 'postMessage',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-call-to-action',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-call-to-action',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_video_spacing',
	                        'default'     => [
								'padding-top'     => '100px',
								'padding-bottom'  => '100px'
							],
							'choices'     => [
								'labels' => [
									'padding-top'     => esc_html__( 'Top', 'cdi' ),
									'padding-bottom'  => esc_html__( 'Bottom', 'cdi' )
								],
							],
	                        'transport' => 'postMessage',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-call-to-action',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-call-to-action',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_video_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_video',
		'default'  => esc_html__( 'Mission is to protect your businesses & much more', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_video_title' => [
				'selector'        => '.econsult-call-to-action h2.title',
				'render_callback' => 'econsulting_agency_video_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'image',
		'settings'    => 'econsulting_agency_video_background_image',
		'label'       => esc_html__( 'Image', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => apply_filters( 'cdi_econsulting_agency_video_background_image', get_stylesheet_directory_uri() . '/images/laptop-computer-writing-working-table-person-764428-pxhere.com.jpg' ),
		'transport'   => 'auto',
		'active_callback' => [
			[
				'setting'  => 'econsulting_agency_video_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'output' => array(
			array(
				'element'  => '.econsult-call-to-action',
				'property' => 'background-image',
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_video_image_overlay',
		'label'       => esc_html__( 'Overlay Color', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => 'rgba(25, 24, 37, 0.64)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-call-to-action .overlay',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_video_button_settings',
	    'section'     => 'econsulting_agency_video',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'radio-buttonset',
		'settings'    => 'econsulting_agency_video_button_settings_status',
		'label'       => esc_html__( 'Button', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => 'flex',
		'choices'     => [
			'flex'   => esc_html__( 'Show', 'cdi' ),
			'none'   => esc_html__( 'Hide', 'cdi' ),
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-call-to-action .button_wrapper',
				'property'      => 'display',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_video_button_settings_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_video_button_settings_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-call-to-action .button_wrapper .per-btn::before',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_video_button_text',
		'label'    => esc_html__( 'Label', 'cdi' ),
		'section'  => 'econsulting_agency_video',
		'default'  => esc_html__( 'Discover More', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_video_button_settings_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_video_button_text' => [
				'selector'        => '.econsult-call-to-action .button_wrapper',
				'render_callback' => 'econsulting_agency_video_get_button'
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_video_button_link',
		'label'    => esc_html__( 'Link', 'cdi' ),
		'section'  => 'econsulting_agency_video',
		'default'  => esc_html__( '#', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_video_button_settings_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_video_button_link' => [
				'selector'        => '.econsult-call-to-action .button_wrapper',
				'render_callback' => 'econsulting_agency_video_get_button'
			],
		],
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_video_settings',
	    'section'     => 'econsulting_agency_video',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Video Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'radio-buttonset',
		'settings'    => 'econsulting_agency_video_url',
		'label'       => esc_html__( 'Channel', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => 'youtube',
		'choices'     => [
			'youtube' => esc_html__( 'Youtube', 'cdi' ),
			'vimeo'   => esc_html__( 'Vimeo', 'cdi' )
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_video_url_youtube',
		'label'    => esc_html__( 'Youtube Link', 'cdi' ),
		'section'  => 'econsulting_agency_video',
		'default'  => 'https://www.youtube.com/watch?v=C0DPdy98e4c&ab_channel=SimonYapp',
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_video_url',
                'operator' => '==',
                'value'    => 'youtube'
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_video_url_vimeo',
		'label'    => esc_html__( 'Vimeo Link', 'cdi' ),
		'section'  => 'econsulting_agency_video',
		'default'  => 'https://vimeo.com/446827718',
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_video_url',
                'operator' => '==',
                'value'    => 'vimeo'
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_video_border_color',
		'label'       => esc_html__( 'Border Color', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-video-btn:before',
				'property'      => 'border',
				'value_pattern' => '3px solid $'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_video_ripple_color',
		'label'       => esc_html__( 'Ripple Color', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => 'rgba(255,97,60,0.6)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-call-to-action .ripple, .econsult-call-to-action .ripple:before, .econsult-call-to-action .ripple:after',
				'property'      => 'box-shadow',
				'value_pattern' => '0 0 0 0 $'
			),
			array(
				'element'       => '.econsult-call-to-action .ripple, .econsult-call-to-action .ripple:before, .econsult-call-to-action .ripple:after',
				'property'      => '-ms-box-shadow',
				'value_pattern' => '0 0 0 0 $'
			),
			array(
				'element'       => '.econsult-call-to-action .ripple, .econsult-call-to-action .ripple:before, .econsult-call-to-action .ripple:after',
				'property'      => '-o-box-shadow',
				'value_pattern' => '0 0 0 0 $'
			),
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_video_icon_color_normal',
		'label'       => esc_html__( 'Icon Background ( Normal )', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => 'rgba(25,24,37,0.15)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-video-btn',
				'property'      => 'background-color'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_video_icon_color_hover',
		'label'       => esc_html__( 'Icon Background ( Hover )', 'cdi' ),
		'section'     => 'econsulting_agency_video',
		'default'     => 'rgba(25,24,37,1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_video_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-call-to-action a:hover .econsult-video-btn',
				'property'      => 'background-color'
			)
		),
	] );

}