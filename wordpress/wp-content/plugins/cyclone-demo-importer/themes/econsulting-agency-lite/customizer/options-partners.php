<?php

add_action( 'init' , 'econsulting_agency_partners_fields' );
function econsulting_agency_partners_fields(){

	Kirki::add_section( 'econsulting_agency_partners', array(
	    'title'          => esc_html__( 'Partners', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_partners_status',
		'label'       => esc_html__( 'Enable Partners ?', 'cdi' ),
		'section'     => 'econsulting_agency_partners',
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
	            'section'  => 'econsulting_agency_partners',
	            'settings' => 'econsulting_agency_partners_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_partners_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_partners_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '80px'
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
		                            'element'       => '.econsult-partner'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-partner'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_partners_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '80px'
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
	                                'element'       => '.econsult-partner',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-partner',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_partners_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '80px'
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
	                                'element'       => '.econsult-partner',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-partner',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_partners_subtitle_settings',
	    'section'     => 'econsulting_agency_partners',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_partners_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_partners',
		'default'  => esc_html__( 'OUR PARTNERS', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_partners_subtitle' => [
				'selector'        => '.econsult-partner .section-title p',
				'render_callback' => 'econsulting_agency_partners_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_partners_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_partners',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-partner .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_partners_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_partners',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-partner .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_partners_title_settings',
	    'section'     => 'econsulting_agency_partners',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_partners_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_partners',
		'default'  => esc_html__( 'Trusted By 250+ Companies', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_partners_title' => [
				'selector'        => '.econsult-partner .section-title h2',
				'render_callback' => 'econsulting_agency_partners_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_partners_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_partners',
		'default'     => '#222',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-partner .section-title h2',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_partners_content_settings',
	    'section'     => 'econsulting_agency_partners',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'econsulting_agency_partners_columns',
		'label'       => esc_html__( 'Columns', 'cdi' ),
		'section'     => 'econsulting_agency_partners',
		'default'     => 4,
		'choices'     => [
			'min'  => 2,
			'max'  => 5,
			'step' => 1,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_partners_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-partner-main',
				'property'      => 'grid-template-columns',
				'value_pattern' => 'repeat($, 1fr)'
			),
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Partners', 'cdi' ),
	    'section'     => 'econsulting_agency_partners',
	    'settings'    => 'econsulting_agency_partners_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_partners_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_partners_list' => [
				'selector'        => '.econsult-partner .econsult-partner-main',
				'render_callback' => 'econsulting_agency_partners_get_content'
			],
		],
	    'default' => json_encode([
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	        [
	            'partner_image' => apply_filters( 'cdi_econsulting_agency_partners_list', get_stylesheet_directory_uri() . '/images/client-logo.png' ),
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add Partner', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Partner', 'cdi' ),
	        ],
	        'fields' => [
	            'partner_image' => [
	                'type'        => 'image',
	                'label'       => esc_html__( 'Partner Image', 'cdi' )
	            ],
	        ]
	    ]
	) );

}