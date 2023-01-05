<?php

add_action( 'init' , 'econsulting_agency_testimonials_fields' );
function econsulting_agency_testimonials_fields(){

	Kirki::add_section( 'econsulting_agency_testimonials', array(
	    'title'          => esc_html__( 'Testimonials', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_testimonials_status',
		'label'       => esc_html__( 'Enable Testimonials ?', 'cdi' ),
		'section'     => 'econsulting_agency_testimonials',
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
	            'section'  => 'econsulting_agency_testimonials',
	            'settings' => 'econsulting_agency_testimonials_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_testimonials_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_testimonials_spacing',
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
		                            'element'       => '.econsult-testi'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-testi'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_testimonials_spacing',
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
	                                'element'       => '.econsult-testi',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-testi',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_testimonials_spacing',
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
	                                'element'       => '.econsult-testi',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-testi',
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
	    'settings'    => 'econsulting_agency_testimonials_subtitle_settings',
	    'section'     => 'econsulting_agency_testimonials',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_testimonials_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_testimonials',
		'default'  => esc_html__( 'OUR CUSTOMER FEEDBACKS', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_testimonials_subtitle' => [
				'selector'        => '.econsult-testi .section-title p',
				'render_callback' => 'econsulting_agency_testimonials_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_testimonials_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_testimonials',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-testi .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_testimonials_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_testimonials',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-testi .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_testimonials_title_settings',
	    'section'     => 'econsulting_agency_testimonials',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_testimonials_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_testimonials',
		'default'  => esc_html__( "What They're Saying", 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_testimonials_title' => [
				'selector'        => '.econsult-testi .section-title h2',
				'render_callback' => 'econsulting_agency_testimonials_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_testimonials_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_testimonials',
		'default'     => '#000',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-testi .section-title h2',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_testimonials_content_settings',
	    'section'     => 'econsulting_agency_testimonials',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_testimonials_content_color',
		'label'       => esc_html__( 'Color', 'cdi' ),
		'section'     => 'econsulting_agency_testimonials',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-testi-author-title h4',
				'property'      => 'color',
				'value_pattern' => '$'
			),
			array(
				'element'       => '.econsult-testi-details::before',
				'property'      => 'background',
				'value_pattern' => '$'
			),
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Testimonials', 'cdi' ),
	    'section'     => 'econsulting_agency_testimonials',
	    'settings'    => 'econsulting_agency_testimonials_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_testimonials_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_testimonials_list' => [
				'selector'        => '.econsult-testi .container .row',
				'render_callback' => 'econsulting_agency_testimonials_get_content'
			],
		],
	    'default' => json_encode([
	        [
	            'name'        => esc_html__( 'Mark Henry', 'cdi' ),
	            'content'     => esc_html__( "Lorem Ipsum is simply dummy text of the printing andypesetting industry. Lorem ipsum a simple Lorem Ipsum has been the industry's standard dummy hic et quidem. Dignissimos maxime velit unde inventore quasi vero dolorem.", 'cdi' ),
	            'position'    => esc_html__( 'Customer', 'cdi' ),
	            'profile_pic' => apply_filters( 'cdi_econsulting_agency_testimonials_list', get_stylesheet_directory_uri() . '/images/notebook-man-working-person-people-notepad-764654-pxhere.com.jpg' ),
	        ],
	        [
	            'name'        => esc_html__( 'Christine', 'cdi' ),
	            'content'     => esc_html__( "Lorem Ipsum is simply dummy text of the printing andypesetting industry. Lorem ipsum a simple Lorem Ipsum has been the industry's standard dummy hic et quidem. Dignissimos maxime velit unde inventore quasi vero dolorem.", 'cdi' ),
	            'position'    => esc_html__( 'Customer', 'cdi' ),
	            'profile_pic' => apply_filters( 'cdi_econsulting_agency_testimonials_list', get_stylesheet_directory_uri() . '/images/notebook-man-working-person-people-notepad-764654-pxhere.com.jpg' ),
	        ],
	        [
	            'name'        => esc_html__( 'Mike Shnoda', 'cdi' ),
	            'content'     => esc_html__( "Lorem Ipsum is simply dummy text of the printing andypesetting industry. Lorem ipsum a simple Lorem Ipsum has been the industry's standard dummy hic et quidem. Dignissimos maxime velit unde inventore quasi vero dolorem.", 'cdi' ),
	            'position'    => esc_html__( 'Supervisor', 'cdi' ),
	            'profile_pic' => apply_filters( 'cdi_econsulting_agency_testimonials_list', get_stylesheet_directory_uri() . '/images/notebook-man-working-person-people-notepad-764654-pxhere.com.jpg' ),
	        ],
	        [
	            'name'        => esc_html__( 'Jared Erondu', 'cdi' ),
	            'content'     => esc_html__( "Lorem Ipsum is simply dummy text of the printing andypesetting industry. Lorem ipsum a simple Lorem Ipsum has been the industry's standard dummy hic et quidem. Dignissimos maxime velit unde inventore quasi vero dolorem.", 'cdi' ),
	            'position'    => esc_html__( 'Supervisor', 'cdi' ),
	            'profile_pic' => apply_filters( 'cdi_econsulting_agency_testimonials_list', get_stylesheet_directory_uri() . '/images/notebook-man-working-person-people-notepad-764654-pxhere.com.jpg' ),
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add Testimonial', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Testimonial', 'cdi' ),
	        ],
	        'fields' => [
	            'name' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Title', 'cdi' ),
	                'default'     => esc_html__( 'Jared Erondu', 'cdi' ),
	            ],
	            'content' => [
	                'type'        => 'textarea',
	                'label'       => esc_html__( 'Content', 'cdi' ),
	                'default'     => esc_html__( "Lorem Ipsum is simply dummy text of the printing andypesetting industry. Lorem ipsum a simple Lorem Ipsum has been the industry's standard dummy hic et quidem. Dignissimos maxime velit unde inventore quasi vero dolorem.", 'cdi' ),
	            ],
	            'position' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Position', 'cdi' ),
	                'default'     => esc_html__( 'Customer', 'cdi' ),
	            ],
	            'profile_pic' => [
	                'type'        => 'image',
	                'label'       => esc_html__( 'Image', 'cdi' ),
	                'default'     => get_stylesheet_directory_uri() . '/images/notebook-man-working-person-people-notepad-764654-pxhere.com.jpg',
	            ],
	        ]
	    ]
	) );

}