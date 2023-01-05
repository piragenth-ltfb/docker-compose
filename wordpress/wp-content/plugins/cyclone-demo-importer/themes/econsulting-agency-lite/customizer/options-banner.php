<?php

add_action( 'init' , 'econsulting_agency_banner_fields' );
function econsulting_agency_banner_fields(){

	Kirki::add_section( 'econsulting_agency_banner', array(
	    'title'          => esc_html__( 'Banner', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_banner_status',
		'label'       => esc_html__( 'Enable Banner ?', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
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
	            'section'  => 'econsulting_agency_banner',
	            'settings' => 'econsulting_agency_banner_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_banner_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_banner_spacing',
	                        'default'     => [
								'padding-top'     => '50px',
								'padding-bottom'  => '50px'
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
		                            'element'       => '.econsult-banner'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-banner'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_banner_spacing',
	                        'default'     => [
								'padding-top'     => '50px',
								'padding-bottom'  => '50px'
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
	                                'element'       => '.econsult-banner',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-banner',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_banner_spacing',
	                        'default'     => [
								'padding-top'     => '50px',
								'padding-bottom'  => '50px'
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
	                                'element'       => '.econsult-banner',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-banner',
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
		'type'        => 'image',
		'settings'    => 'econsulting_agency_banner_image',
		'label'       => esc_html__( 'Image', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => apply_filters( 'cdi_econsulting_agency_banner_image', get_stylesheet_directory_uri() . '/images/adult-african-black-blonde-business-businessman-1629587-pxhere.com.jpg' ),
		'transport'   => 'auto',
		'active_callback' => [
			[
				'setting'  => 'econsulting_agency_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'output' => array(
			array(
				'element'  => '.econsult-banner .econsult-banner-image',
				'property' => 'background-image',
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_banner_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( 'PERFECT SOLUTION FOR YOUR COMPANY', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_banner_subtitle' => [
				'selector'        => '.econsult-banner .econsult-banner-content .subtitle',
				'render_callback' => 'econsulting_agency_banner_subtitle_text'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_banner_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( 'Organizational Development with Real-World Impact', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_banner_title' => [
				'selector'        => '.econsult-banner .econsult-banner-content h1',
				'render_callback' => 'econsulting_agency_banner_title_text'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
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
                'section'  => 'econsulting_agency_banner',
                'settings' => 'econsulting_agency_banner_title_font_size',
                'global_active_callback'    => array(
                	array(
		                'setting'  => 'econsulting_agency_banner_status',
		                'operator' => '==',
		                'value'    => true
		            )
                ),
                'fields'   => array(
                    'slider' => array(
                        'desktop' => array(
                            'label' => esc_html__( 'Title Font Size ( Desktop )', 'cdi' ),
                            'settings' => 'econsulting_agency_banner_title_font_size',
                            'default'     => 54.93,  
                            'choices'     => [
                                'min'  => 10,
                                'max'  => 60,
                                'step' => 1,
                            ],
                            'transport' => 'auto',
                            'output' => array(
                                array(
                                    'element'       => 'body .econsult-banner-content h1',
                                    'property'      => 'font-size',
                                    'value_pattern' => '$px !important'
                                )
                            ),
                        ),
                        'tablet' => array(
                            'label' => esc_html__( 'Title Font Size ( Tablet )', 'cdi' ),
                            'settings' => 'econsulting_agency_banner_title_font_size',
                            'default'     => 51.88,
                            'choices'     => [
                                'min'  => 10,
                                'max'  => 60,
                                'step' => 1,
                            ],
                            'transport' => 'auto',
                            'output' => array(
                                array(
                                    'element'       => 'body .econsult-banner-content h1',
                                    'property'      => 'font-size',
                                    'value_pattern' => '$px !important',
                                    'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
                                )
                            ),
                        ),
                        'mobile' => array(
                            'label' => esc_html__( 'Title Font Size ( Mobile )', 'cdi' ),
                            'settings' => 'econsulting_agency_banner_title_font_size',
                            'default'     => 40,
                            'choices'     => [
                                'min'  => 10,
                                'max'  => 60,
                                'step' => 1,
                            ],
                            'transport' => 'auto',
                            'output' => array(
                                array(
                                    'element'       => 'body .econsult-banner-content h1',
                                    'property'      => 'font-size',
                                    'value_pattern' => '$px !important',
                                    'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
                                )
                            ),
                        )
                    ),
                )
                
            ) 
        );

    }

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'econsulting_agency_banner_content',
		'label'    => esc_html__( 'Description', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. ', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_banner_content' => [
				'selector'        => '.econsult-banner .econsult-banner-content p',
				'render_callback' => 'econsulting_agency_banner_description'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_banner_button_section',
	    'section'     => 'econsulting_agency_banner',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'radio-buttonset',
		'settings'    => 'econsulting_agency_banner_button_status',
		'label'       => esc_html__( 'Button', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => 'flex',
		'choices'     => [
			'flex'   => esc_html__( 'Show', 'cdi' ),
			'none' => esc_html__( 'Hide', 'cdi' ),
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-banner-btn .button_wrapper',
				'property'      => 'display',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_banner_button_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-banner-in .econsult-banner-btn .per-btn::before',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_banner_button_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => '#222',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-banner-in .econsult-banner-btn .per-btn',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_banner_button_text',
		'label'    => esc_html__( 'Label', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( 'Work Together', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_banner_button_text' => [
				'selector'        => '.econsult-banner .econsult-banner-btn .button_wrapper',
				'render_callback' => 'econsulting_agency_banner_button'
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_banner_button_link',
		'label'    => esc_html__( 'Link', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( '#', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_banner_button_link' => [
				'selector'        => '.econsult-banner .econsult-banner-btn .button_wrapper',
				'render_callback' => 'econsulting_agency_banner_button'
			],
		],
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_banner_custom_field',
	    'section'     => 'econsulting_agency_banner',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Custom Field', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'radio-buttonset',
		'settings'    => 'econsulting_agency_banner_custom_field_status',
		'label'       => esc_html__( 'Custom Field', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => 'flex',
		'choices'     => [
			'flex'   => esc_html__( 'Show', 'cdi' ),
			'none' => esc_html__( 'Hide', 'cdi' ),
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-banner-btn .custom_field',
				'property'      => 'display',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_banner_custom_field_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => '#FFEDE7',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_custom_field_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-det.custom_field i',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_banner_custom_field_icon_color',
		'label'       => esc_html__( 'Icon Color', 'cdi' ),
		'section'     => 'econsulting_agency_banner',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_custom_field_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-det.custom_field i',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
		'type'              => 'font-awesome-6',
		'label'             => esc_html__( 'Icon', 'cdi' ),
		'section'           => 'econsulting_agency_banner',
		'settings'          => 'econsulting_agency_banner_custom_field_icon',
		'choices' => array(
			'height'       => '200px',
			'search_icons' => true
		),
		'default' => 'fas fa-phone',
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_custom_field_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_banner_custom_field_icon' => [
				'selector'        => '.econsult-banner .econsult-banner-btn .custom_field',
				'render_callback' => 'econsulting_agency_banner_custom_field'
			],
		],
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_banner_custom_field_label',
		'label'    => esc_html__( 'Label', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( 'CONTACT US', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_custom_field_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_banner_custom_field_label' => [
				'selector'        => '.econsult-banner .econsult-banner-btn .custom_field',
				'render_callback' => 'econsulting_agency_banner_custom_field'
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_banner_custom_field_value',
		'label'    => esc_html__( 'Value', 'cdi' ),
		'section'  => 'econsulting_agency_banner',
		'default'  => esc_html__( '+1 (000) 123 456 789', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_banner_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_banner_custom_field_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_banner_custom_field_value' => [
				'selector'        => '.econsult-banner .econsult-banner-btn .custom_field',
				'render_callback' => 'econsulting_agency_banner_custom_field'
			],
		],
	] );

}