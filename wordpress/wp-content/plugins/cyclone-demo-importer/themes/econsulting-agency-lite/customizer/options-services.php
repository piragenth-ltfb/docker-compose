<?php

add_action( 'init' , 'econsulting_agency_services_fields' );
function econsulting_agency_services_fields(){

	Kirki::add_section( 'econsulting_agency_services', array(
	    'title'          => esc_html__( 'Services', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_services_status',
		'label'       => esc_html__( 'Enable Services ?', 'cdi' ),
		'section'     => 'econsulting_agency_services',
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
	            'section'  => 'econsulting_agency_services',
	            'settings' => 'econsulting_agency_services_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_services_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_services_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '40px'
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
		                            'element'       => '.econsult-services'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-services'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_services_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '40px'
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
	                                'element'       => '.econsult-services',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-services',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_services_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '40px'
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
	                                'element'       => '.econsult-services',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-services',
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
	    'settings'    => 'econsulting_agency_services_subtitle_settings',
	    'section'     => 'econsulting_agency_services',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_services_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_services',
		'default'  => esc_html__( 'OUR SERVICES', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_services_subtitle' => [
				'selector'        => '.econsult-services .section-title p',
				'render_callback' => 'econsulting_agency_services_section_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_services_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_services',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-services .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_services_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_services',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-services .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_services_title_settings',
	    'section'     => 'econsulting_agency_services',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_services_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_services',
		'default'  => esc_html__( 'Services We Offer', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_services_title' => [
				'selector'        => '.econsult-services .section-title h2',
				'render_callback' => 'econsulting_agency_services_section_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_services_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_services',
		'default'     => '#000',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-services .section-title h2',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_services_settings',
	    'section'     => 'econsulting_agency_services',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Services Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'econsulting_agency_services_column',
		'label'       => esc_html__( 'Column', 'cdi' ),
		'section'     => 'econsulting_agency_services',
		'default'     => 4,
		'choices'     => [
			'min'  => 2,
			'max'  => 5,
			'step' => 1,
		],
		'active_callback' => [
			[
				'setting'  => 'econsulting_agency_services_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-services-main',
				'property'      => 'grid-template-columns',
				'value_pattern' => 'repeat($, 1fr)'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Services', 'cdi' ),
	    'section'     => 'econsulting_agency_services',
	    'settings'    => 'econsulting_agency_services_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_services_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_services_list' => [
				'selector'        => '.econsult-services-main',
				'render_callback' => 'econsulting_agency_services_section_get_content'
			],
		],
	    'default' => json_encode([
	        [
	        	'icon'       => 'fas fa-gifts',
	            'label'      => esc_html__( 'Strategic advice', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum is are many variations of pass of majority.', 'cdi' ),
	            'link'       => '#',
	            'color'      => '#ff4a17',
	            'link_label' => esc_html__( 'Read More', 'cdi' ),
	        ],
	        [
	        	'icon'      => 'fas fa-chart-pie',
	            'label'      => esc_html__( 'Audit Marketing', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum is are many variations of pass of majority.', 'cdi' ),
	            'link'       => '#',
	            'color'      => '#ff4a17',
	            'link_label' => esc_html__( 'Read More', 'cdi' ),
	        ],
	        [
	        	'icon'      => 'fas fa-award',
	            'label'      => esc_html__( 'Banking Advicing', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum is are many variations of pass of majority.', 'cdi' ),
	            'link'       => '#',
	            'color'      => '#ff4a17',
	            'link_label' => esc_html__( 'Read More', 'cdi' ),
	        ],
	        [
	        	'icon'      => 'far fa-lightbulb',
	            'label'      => esc_html__( 'Marketing Rules', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum is are many variations of pass of majority.', 'cdi' ),
	            'link'       => '#',
	            'color'      => '#ff4a17',
	            'link_label' => esc_html__( 'Read More', 'cdi' ),
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add Service', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Service', 'cdi' ),
	        ],
	        'fields' => [
	        	'icon'  => [
	                'type'        => 'fontawesome',
	                'label'       => esc_html__( 'Icon', 'cdi' ),
	                'default'     => 'fab fa-accusoft',
	                'choices'     => bizberg_get_fontawesome_options(),
	            ],
	            'label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Title', 'cdi' ),
	                'default'     => esc_html__( 'Strategic advice', 'cdi' ),
	            ],
	            'content' => [
	                'type'        => 'textarea',
	                'label'       => esc_html__( 'Content', 'cdi' ),
	                'default'     => esc_html__( 'Lorem ipsum is are many variations of pass of majority.', 'cdi' ),
	            ],
	            'link' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Link', 'cdi' ),
	                'default'     => esc_html__( '#', 'cdi' ),
	            ],
	            'link_label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Link Label', 'cdi' ),
	                'default'     => esc_html__( 'Read More', 'cdi' ),
	            ],
	            'color' => [
	                'type'        => 'color',
	                'label'       => esc_html__( 'Color', 'cdi' ),
	                'default'     => '#ff4a17',
	            ],
	        ]
	    ]
	) );

}