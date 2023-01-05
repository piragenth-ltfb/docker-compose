<?php

add_action( 'init' , 'school_of_education_banner_fields' );
function school_of_education_banner_fields(){

	Kirki::add_section( 'school_of_education_banner', array(
	    'title'          => esc_html__( 'Banner', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_banner_status',
		'label'       => esc_html__( 'Enable Banner ?', 'cdi' ),
		'section'     => 'school_of_education_banner',
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
	            'section'  => 'school_of_education_banner',
	            'settings' => 'school_of_education_banner_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_banner_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_banner_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '0px'
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
		                            'element'       => '.school-banner'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-banner'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_banner_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '0px'
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
	                                'element'       => '.school-banner',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-banner',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_banner_spacing',
	                        'default'     => [
								'padding-top'     => '20px',
								'padding-bottom'  => '0px'
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
	                                'element'       => '.school-banner',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-banner',
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
		'settings'    => 'banner_image_id',
		'label'       => esc_html__( 'Banner Image', 'cdi' ),
		'section'     => 'school_of_education_banner',
		'default'     => apply_filters( 'school_of_education_banner_image_id', get_stylesheet_directory_uri() . '/images/adult-african-black-blonde-business-businessman-1629587-pxhere.com.jpg' ),
		'transport'   => 'auto',
		'output' => array(
			array(
				'element'  => '.banner-content-image',
				'property' => 'background-image',
			),
		),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_banner_subtitle_settings',
	    'section'     => 'school_of_education_banner',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	/**
	* Start Subtitle Settings
	*/

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'school_of_education_banner_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'school_of_education_banner',
		'default'  => esc_html__( 'A Great Place To Learn', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_banner_subtitle' => [
				'selector'        => '.school-banner .banner-content h4',
				'render_callback' => 'school_of_education_get_banner_subtitle',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_banner_subtitle_color',
		'label'       => __( 'Subtitle Color', 'cdi' ),
		'section'     => 'school_of_education_banner',
		'default'     => '#00b6c7',
		'active_callback' => [
			[
				'setting'  => 'school_of_education_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'transport'   => 'auto',
		'output' => array(
			array(
				'element'  => '.school-banner .banner-content h4',
				'property' => 'color',
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
	            'section'  => 'school_of_education_banner',
	            'settings' => 'school_of_education_subtitle_font_settings',
	            'global_active_callback'    => array(
	              	array(
	                	'setting'  => 'school_of_education_banner_status',
	                	'operator' => '==',
	                	'value'    => true
	            	),
	          	),
	            'fields'   => array(
	                'typography' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Font Settings', 'cdi' ),
	                        'settings' => 'school_of_education_subtitle_font_settings',
	                        'default'     => [
	                        	'font-family'    => 'Poppins',
								'variant'        => '500',
				              	'font-size'      => '20px',
				              	'line-height'    => '1.5',
				              	'letter-spacing' => '0'
				            ],
	                        'transport' => 'auto',
	                        'output'      => [
				              	[
				                	'element' => '.school-banner .banner-content h4'
				              	],
				            ],
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Font Settings', 'cdi' ),
	                        'settings' => 'school_of_education_subtitle_font_settings',
	                        'default'     => [
				              	'font-size'      => '20px',
				              	'line-height'    => '1.5',
				              	'letter-spacing' => '0',
				            ],
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'media_query' => '@media (min-width: 481px) and (max-width: 1024px)',
	                                'element'     => '.school-banner .banner-content h4'
	                            )
	                        ),
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Font Settings', 'cdi' ),
	                        'settings' => 'school_of_education_subtitle_font_settings',
	                        'default'     => [
				              	'font-size'      => '20px',
				              	'line-height'    => '1.5',
				              	'letter-spacing' => '0'
				            ],
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'media_query' => '@media (min-width: 320px) and (max-width: 480px)',
	                                'element'     => '.school-banner .banner-content h4'
	                            )
	                        ),
	                    )
	                ),
	            )
	            
	        ) 
	    );

	}

	/**
	* End Subtitle Settings
	*/

	/**
	* Start Title Settings
	*/

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_banner_title_settings',
	    'section'     => 'school_of_education_banner',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'school_of_education_banner_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'school_of_education_banner',
		'default'  => esc_html__( 'Learn boldly & inspire. Get an education, not just a degree.', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_banner_title' => [
				'selector'        => '.school-banner .banner-content h1 a',
				'render_callback' => 'school_of_education_get_banner_title',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_banner_title_color',
		'label'       => __( 'Title Color', 'cdi' ),
		'section'     => 'school_of_education_banner',
		'default'     => '#181d38',
		'active_callback' => [
			[
				'setting'  => 'school_of_education_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'transport'   => 'auto',
		'output' => array(
			array(
				'element'  => '.school-banner .banner-content h1.title a',
				'property' => 'color',
			),
			array(
				'element'       => '.school-banner .banner-content h1.title a',
				'property'      => 'background',
				'value_pattern' => 'linear-gradient(to right, $, $)'
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
	            'section'  => 'school_of_education_banner',
	            'settings' => 'school_of_education_title_font_settings',
	            'global_active_callback'    => array(
	              	array(
	                	'setting'  => 'school_of_education_banner_status',
	                	'operator' => '==',
	                	'value'    => true
	            	),
	          	),
	            'fields'   => array(
	                'typography' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Font Settings', 'cdi' ),
	                        'settings' => 'school_of_education_title_font_settings',
	                        'default'     => [
	                        	'font-family'    => 'Poppins',
								'variant'        => '800',
				              	'font-size'      => '58px',
				              	'line-height'    => '1.2',
				              	'letter-spacing' => '0'
				            ],
	                        'transport' => 'auto',
	                        'output'      => [
				              	[
				                	'element' => '.school-banner .banner-content h1.title'
				              	],
				            ],
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Font Settings', 'cdi' ),
	                        'settings' => 'school_of_education_title_font_settings',
	                        'default'     => [
				              	'font-size'      => '50px',
				              	'line-height'    => '1.2',
				              	'letter-spacing' => '0',
				            ],
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'media_query' => '@media (min-width: 481px) and (max-width: 1024px)',
	                                'element'     => '.school-banner .banner-content h1.title'
	                            )
	                        ),
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Font Settings', 'cdi' ),
	                        'settings' => 'school_of_education_title_font_settings',
	                        'default'     => [
				              	'font-size'      => '35px',
				              	'line-height'    => '1.2',
				              	'letter-spacing' => '0'
				            ],
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'media_query' => '@media (min-width: 320px) and (max-width: 480px)',
	                                'element'     => '.school-banner .banner-content h1.title'
	                            )
	                        ),
	                    )
	                ),
	            )
	            
	        ) 
	    );

	}

	/**
	* End Title Settings
	*/

	/**
	* Start Content Settings
	*/

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_banner_content_settings',
	    'section'     => 'school_of_education_banner',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'school_of_education_banner_content',
		'label'    => esc_html__( 'Content', 'cdi' ),
		'section'  => 'school_of_education_banner',
		'default'  => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_banner_content' => [
				'selector'        => '.school-banner .banner-content p',
				'render_callback' => 'school_of_education_get_banner_content',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	/**
	* End Content Settings
	*/

	/**
	* Start Button Settings
	*/

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_banner_button_settings',
	    'section'     => 'school_of_education_banner',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_banner_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'advanced-repeater',
		'label'       => esc_attr__( 'Buttons', 'cdi' ),
		'section'     => 'school_of_education_banner',
		'choices' => [
			'limit' => 2,
			'row_label' => [
				'value' => esc_html__( 'Button', 'cdi' ),
			],
			'fields' => [
				'title' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Label', 'cdi' )
				],
				'link' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Link', 'cdi' )
				],
				'background_color' => [
					'type'        => 'color',
					'label'       => esc_attr__( 'Background Color', 'cdi' )
				],
				'text_color' => [
					'type'        => 'color',
					'label'       => esc_attr__( 'Text Color', 'cdi' )
				],
			],
		],
		'settings'    => 'school_of_education_banner_buttons',
		'default'     => [
			[
				'title'             => esc_attr__( 'Discover', 'cdi' ),
				'link'              => '#',
				'background_color'  => '#00b6c7',
				'text_color'        => '#181d38',
			],
			[
				'title'             => esc_attr__( 'Get Started', 'cdi' ),
				'link'              => '#',
				'background_color'  => '#dd9933',
				'text_color'        => '#181d38',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_banner_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'school_of_education_banner_buttons' => [
				'selector'        => '.school-banner .banner-content .banner-btn',
				'render_callback' => 'school_of_education_get_banner_buttons',
			],
		],
	] );

	/**
	* End Button Settings
	*/

}