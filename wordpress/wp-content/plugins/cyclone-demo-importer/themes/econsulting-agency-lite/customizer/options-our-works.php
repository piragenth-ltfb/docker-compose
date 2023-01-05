<?php

add_action( 'init' , 'econsulting_agency_our_work_fields' );
function econsulting_agency_our_work_fields(){

	Kirki::add_section( 'econsulting_agency_our_work', array(
	    'title'          => esc_html__( 'Our Work', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_our_work_status',
		'label'       => esc_html__( 'Enable Portfolio ?', 'cdi' ),
		'section'     => 'econsulting_agency_our_work',
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
	            'section'  => 'econsulting_agency_our_work',
	            'settings' => 'econsulting_agency_our_work_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_our_work_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_our_work_spacing',
	                        'default'     => [
								'padding-top'     => '60px',
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
		                            'element'       => '.econsult-works'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-works'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_our_work_spacing',
	                        'default'     => [
								'padding-top'     => '60px',
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
	                                'element'       => '.econsult-works',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-works',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_our_work_spacing',
	                        'default'     => [
								'padding-top'     => '60px',
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
	                                'element'       => '.econsult-works',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-works',
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
	    'settings'    => 'econsulting_agency_our_work_subtitle_settings',
	    'section'     => 'econsulting_agency_our_work',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_our_work_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_our_work',
		'default'  => esc_html__( 'Our Works', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_our_work_subtitle' => [
				'selector'        => '.econsult-works .section-title p',
				'render_callback' => 'econsulting_agency_our_work_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_our_work_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_our_work',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-works .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_our_work_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_our_work',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-works .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_our_work_title_settings',
	    'section'     => 'econsulting_agency_our_work',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_our_work_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_our_work',
		'default'  => esc_html__( "Things we've made", 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_our_work_title' => [
				'selector'        => '.econsult-works .section-title h2',
				'render_callback' => 'econsulting_agency_our_work_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_our_work_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_our_work',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-works .section-title h2',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_our_work_portfolio_settings',
	    'section'     => 'econsulting_agency_our_work',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Portfolio Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_our_work_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_our_work',
		'default'     => '#343a40',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-works',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_our_work_arrow_color',
		'label'       => esc_html__( 'Arrow Color', 'cdi' ),
		'section'     => 'econsulting_agency_our_work',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_our_work_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.work_slider .slick-dots li.slick-active button:before, .work_slider .slick-dots li button:before',
				'property'      => 'color',
				'value_pattern' => '$ !important'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Portfolios', 'cdi' ),
	    'section'     => 'econsulting_agency_our_work',
	    'settings'    => 'econsulting_agency_our_work_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_our_work_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_our_work_list' => [
				'selector'        => '.work_slider_wrapper',
				'render_callback' => 'econsulting_agency_our_work_get_slider'
			],
		],
	    'default' => json_encode([
	        [
	        	'image'    => apply_filters( 'cdi_econsulting_agency_our_work_list_1', get_stylesheet_directory_uri() . '/images/adult-african-black-blonde-business-businessman-1629587-pxhere.com.jpg' ),
	            'label'    => esc_html__( 'Product Design', 'cdi' ),
	            'subtitle' => esc_html__( 'Design / Marketing', 'cdi' ),
	            'link'     => '#'
	        ],
	        [
	        	'image'    => apply_filters( 'cdi_econsulting_agency_our_work_list_2', get_stylesheet_directory_uri() . '/images/computer-working-person-group-people-meeting-559565-pxhere.com.jpg' ),
	            'label'    => esc_html__( 'Woody', 'cdi' ),
	            'subtitle' => esc_html__( 'Architecture / Concept', 'cdi' ),
	            'link'     => '#'
	        ],
	        [
	        	'image'    => apply_filters( 'cdi_econsulting_agency_our_work_list_3', get_stylesheet_directory_uri() . '/images/laptop-computer-writing-working-table-person-764428-pxhere.com.jpg' ),
	            'label'    => esc_html__( 'Interior Design', 'cdi' ),
	            'subtitle' => esc_html__( 'Architecture / Design', 'cdi' ),
	            'link'     => '#'
	        ],
	        [
	        	'image'    => apply_filters( 'cdi_econsulting_agency_our_work_list_4', get_stylesheet_directory_uri() . '/images/blank-brainstorming-colleagues-communication-computer-conference-1450995-pxhere.com.jpg' ),
	            'label'    => esc_html__( 'Biker', 'cdi' ),
	            'subtitle' => esc_html__( 'Concept / Design', 'cdi' ),
	            'link'     => '#'
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add Portfolio', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Portfolio', 'cdi' ),
	        ],
	        'fields' => [
	        	'image'  => [
	                'type'        => 'image',
	                'label'       => esc_html__( 'Image', 'cdi' ),
	                'default'     => apply_filters( 'cdi_econsulting_agency_our_work_list_1', get_stylesheet_directory_uri() . '/images/adult-african-black-blonde-business-businessman-1629587-pxhere.com.jpg' ),
	            ],
	            'label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Title', 'cdi' ),
	                'default'     => esc_html__( 'Home Design', 'cdi' ),
	            ],
	            'subtitle' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Subtitle', 'cdi' ),
	                'default'     => esc_html__( 'Architecture / Design', 'cdi' ),
	            ],
	            'link' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Link', 'cdi' ),
	                'default'     => esc_html__( '#', 'cdi' ),
	            ],
	        ]
	    ]
	) );

}