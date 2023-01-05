<?php

add_action( 'init' , 'econsulting_agency_pricing_fields' );
function econsulting_agency_pricing_fields(){

	Kirki::add_section( 'econsulting_agency_pricing', array(
	    'title'          => esc_html__( 'Pricing', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_pricing_status',
		'label'       => esc_html__( 'Enable Pricing ?', 'cdi' ),
		'section'     => 'econsulting_agency_pricing',
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
	            'section'  => 'econsulting_agency_pricing',
	            'settings' => 'econsulting_agency_pricing_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_pricing_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_pricing_spacing',
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
		                            'element'       => '.econsult-pricing'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-pricing'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_pricing_spacing',
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
	                                'element'       => '.econsult-pricing',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-pricing',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_pricing_spacing',
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
	                                'element'       => '.econsult-pricing',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-pricing',
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
	    'settings'    => 'econsulting_agency_pricing_subtitle_settings',
	    'section'     => 'econsulting_agency_pricing',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_pricing_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_pricing',
		'default'  => esc_html__( 'PRICING PACKAGES', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_pricing_subtitle' => [
				'selector'        => '.econsult-pricing .section-title p',
				'render_callback' => 'econsulting_agency_pricing_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_pricing_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_pricing',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-pricing .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_pricing_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_pricing',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-pricing .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_pricing_title_settings',
	    'section'     => 'econsulting_agency_pricing',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_pricing_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_pricing',
		'default'  => esc_html__( 'Choose Your Best Plan', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_pricing_title' => [
				'selector'        => '.econsult-pricing .section-title h2',
				'render_callback' => 'econsulting_agency_pricing_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_pricing_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_pricing',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-pricing .section-title h2',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_pricing_table_settings',
	    'section'     => 'econsulting_agency_pricing',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Pricing Table Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Pricing Table', 'cdi' ),
	    'section'     => 'econsulting_agency_pricing',
	    'settings'    => 'econsulting_agency_pricing_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_pricing_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_pricing_list' => [
				'selector'        => '.econsult-pricing .econsult-pricing-main',
				'render_callback' => 'econsulting_agency_pricing_get_pricing_table'
			],
		],
	    'default' => json_encode([
	        [
	            'label'                  => 'Basic Plan',
	            'title_color'            => '#ff4a17',
	            'currency'               => '$',
	            'price'                  => 59.99,
	            'currency_color'         => '#ff4a17',
	            'features'               => "Advanced\nChange Management\nCorporate Finance\nStrategy & Marketing\nInformation Technology\nDigital Marketing\nAI Technology",
	            'content_background'     => '#fff',
	            'separator_color'        => '#e5e5e5',
	            'text_color'             => '#64686d',
	            'button_status'          => true,
	            'button_label'           => 'Get Started',
	            'button_link'            => '#',
	            'btn_background_color'   => '#ff4a17',
	            'btn_label_color_normal' => '#222',
	            'tag_status'             => false,
	            'tag_label'              => '',
	            'tag_background'         => ''
	        ],
	        [
	            'label'                  => 'Ultra Plan',
	            'title_color'            => '#fff',
	            'currency'               => '$',
	            'price'                  => 99.99,
	            'currency_color'         => '#ff4a17',
	            'features'               => "Advanced\nChange Management\nCorporate Finance\nStrategy & Marketing\nInformation Technology\nDigital Marketing\nAI Technology",
	            'content_background'     => '#343a40',
	            'separator_color'        => '#e5e5e5',
	            'text_color'             => '#fff',
	            'button_status'          => true,
	            'button_label'           => 'Get Started',
	            'button_link'            => '#',
	            'btn_background_color'   => '#ff4a17',
	            'btn_label_color_normal' => '#fff',
	            'tag_status'             => true,
	            'tag_label'              => 'RECOMMENDED',
	            'tag_background'         => '#ff4a17'
	        ],
	        [
	            'label'                  => 'Premium Plan',
	            'title_color'            => '#ff4a17',
	            'currency'               => '$',
	            'price'                  => 399.99,
	            'currency_color'         => '#ff4a17',
	            'features'               => "Advanced\nChange Management\nCorporate Finance\nStrategy & Marketing\nInformation Technology\nDigital Marketing\nAI Technology",
	            'content_background'     => '#fff',
	            'separator_color'        => '#e5e5e5',
	            'text_color'             => '#64686d',
	            'button_status'          => true,
	            'button_label'           => 'Get Started',
	            'button_link'            => '#',
	            'btn_background_color'   => '#ff4a17',
	            'btn_label_color_normal' => '#222',
	            'tag_status'             => false,
	            'tag_label'              => '',
	            'tag_background'         => ''
	        ],
	    ]),
	    'choices' => [
	    	'limit' => 3,
	        'button_label' => esc_html__( 'Add Pricing', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Pricing', 'cdi' ),
	        ],
	        'fields' => [
	        	'title_settings' => [
	                'type'        => 'heading',
	                'label'       => esc_html__( 'Title Settings', 'cdi' ),
	                'choices'     => [
	                    'background'  => '#ff5722'
	                ],
	            ],
	            'label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Title', 'cdi' ),
	                'default'     => esc_html__( 'Basic Plan', 'cdi' ),
	            ],
	            'title_color'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Title Color', 'cdi' ),
	                'default'     => '#ff4a17',
	            ],
	            'currency_settings' => [
	                'type'        => 'heading',
	                'label'       => esc_html__( 'Currency Settings', 'cdi' ),
	                'choices'     => [
	                    'background'  => '#e91e63'
	                ],
	            ],
	            'currency' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Currency', 'cdi' ),
	                'default'     => '$',
	            ],
	            'price' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Price', 'cdi' ),
	                'default'     => 59.99,
	            ],
	            'currency_color'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Currency Color', 'cdi' ),
	                'default'     => '#ff4a17',
	            ],
	            'content_settings' => [
	                'type'        => 'heading',
	                'label'       => esc_html__( 'Content Settings', 'cdi' ),
	                'choices'     => [
	                    'background'  => '#9c27b0'
	                ],
	            ],
	            'tag_status'  => [
	                'type'        => 'checkbox',
	                'label'       => 'Enable Tag ?',
	                'default'     => false,
	            ],
	            'tag_label' => [
	                'type'        => 'text',
	                'label'       => 'Tag Label',
	                'default'     => 'RECOMMENDED',
	                'active_callback' => [
	                    [
	                        'setting'  => 'tag_status',
	                        'operator' => '==',
	                        'value'    => true
	                    ]
	                ],
	            ],
	            'tag_background'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Tag Background', 'cdi' ),
	                'default'     => '#ff4a17',
	                'active_callback' => [
	                    [
	                        'setting'  => 'tag_status',
	                        'operator' => '==',
	                        'value'    => true
	                    ]
	                ],
	            ],
	            'features' => [
	                'type'        => 'textarea',
	                'label'       => esc_html__( 'Features', 'cdi' ),
	                'description' => esc_html__( 'Press enter for new line', 'cdi' ),
	                'default'     => "Advanced\nChange Management\nCorporate Finance\nStrategy & Marketing\nInformation Technology\nDigital Marketing\nAI Technology",
	            ],
	            'content_background'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Background Color', 'cdi' ),
	                'default'     => '#fff',
	            ],
	            'separator_color'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Separator Color', 'cdi' ),
	                'default'     => '#e5e5e5',
	            ],
	            'text_color'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Text Color', 'cdi' ),
	                'default'     => '#64686d',
	            ],
	            'button_settings' => [
	                'type'        => 'heading',
	                'label'       => esc_html__( 'Button Settings', 'cdi' ),
	                'choices'     => [
	                    'background'  => '#673ab7'
	                ],
	            ],
	            'button_status'  => [
	                'type'        => 'checkbox',
	                'label'       => 'Enable Button ?',
	                'default'     => true,
	            ],
	            'button_label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Label', 'cdi' ),
	                'default'     => esc_html__( 'Get Started', 'cdi' ),
	                'active_callback' => [
	                    [
	                        'setting'  => 'button_status',
	                        'operator' => '==',
	                        'value'    => true
	                    ]
	                ],
	            ],
	            'button_link' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Link', 'cdi' ),
	                'default'     => '#',
	                'active_callback' => [
	                    [
	                        'setting'  => 'button_status',
	                        'operator' => '==',
	                        'value'    => true
	                    ]
	                ],
	            ],
	            'btn_background_color'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Background Color', 'cdi' ),
	                'default'     => '#ff4a17',
	                'active_callback' => [
	                    [
	                        'setting'  => 'button_status',
	                        'operator' => '==',
	                        'value'    => true
	                    ]
	                ],
	            ],
	            'btn_label_color_normal'  => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Text Color ( Normal )', 'cdi' ),
	                'default'     => '#222',
	                'active_callback' => [
	                    [
	                        'setting'  => 'button_status',
	                        'operator' => '==',
	                        'value'    => true
	                    ]
	                ],
	            ],
	        ]
	    ]
	) );

}