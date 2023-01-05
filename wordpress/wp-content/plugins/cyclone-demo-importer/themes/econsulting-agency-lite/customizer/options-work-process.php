<?php

add_action( 'init' , 'econsulting_agency_work_process_fields' );
function econsulting_agency_work_process_fields(){

	Kirki::add_section( 'econsulting_agency_work_process', array(
	    'title'          => esc_html__( 'Work Process', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_work_process_status',
		'label'       => esc_html__( 'Enable Work Process ?', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
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
	            'section'  => 'econsulting_agency_work_process',
	            'settings' => 'econsulting_agency_work_process_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_work_process_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_work_process_spacing',
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
		                            'element'       => '.econsult-our-process'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-our-process'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_work_process_spacing',
	                        'default'     => [
								'padding-top'     => '60px',
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
	                                'element'       => '.econsult-our-process',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-our-process',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_work_process_spacing',
	                        'default'     => [
								'padding-top'     => '60px',
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
	                                'element'       => '.econsult-our-process',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-our-process',
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
		'settings'    => 'econsulting_agency_work_process_image',
		'label'       => esc_html__( 'Image', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
		'default'     => apply_filters( 'cdi_econsulting_agency_work_process_image', get_stylesheet_directory_uri() . '/images/computer-working-person-group-people-meeting-559565-pxhere.com.jpg' ),
		'transport'   => 'auto',
		'active_callback' => [
			[
				'setting'  => 'econsulting_agency_work_process_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'output' => array(
			array(
				'element'  => '.econsult-our-process .process_image',
				'property' => 'background-image',
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_work_process_subtitle_settings',
	    'section'     => 'econsulting_agency_work_process',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_work_process_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_work_process',
		'default'  => esc_html__( '30 YEARS OF EXPERIENCE', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_work_process_subtitle' => [
				'selector'        => '.econsult-our-process .section-title p',
				'render_callback' => 'econsulting_agency_work_process_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_work_process_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-our-process .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_work_process_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-our-process .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_work_process_title_settings',
	    'section'     => 'econsulting_agency_work_process',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_work_process_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_work_process',
		'default'  => esc_html__( 'We Execute Our Ideas from Start to Finish', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_work_process_title' => [
				'selector'        => '.econsult-our-process .section-title h2',
				'render_callback' => 'econsulting_agency_work_process_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_work_process_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
		'default'     => '#000',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-our-process .section-title h2',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_work_process_content_settings',
	    'section'     => 'econsulting_agency_work_process',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_work_process_content_background_color',
		'label'       => esc_html__( 'Background Color', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
		'default'     => '#343a40',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-our-process .process_content',
				'property'      => 'background',
				'value_pattern' => '$'
			),
			array(
				'element'       => '.process_content_items_icon span',
				'property'      => 'border',
				'value_pattern' => '6px solid $'
			),
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_work_process_content_count_border_color',
		'label'       => esc_html__( 'Count Border Color', 'cdi' ),
		'section'     => 'econsulting_agency_work_process',
		'default'     => '#40484f',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_work_process_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.process_content_items_icon::after',
				'property'      => 'border-right',
				'value_pattern' => '2px solid $'
			),
			array(
				'element'       => '.process_content_items_icon',
				'property'      => 'border',
				'value_pattern' => '2px solid $'
			),
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Process', 'cdi' ),
	    'section'     => 'econsulting_agency_work_process',
	    'settings'    => 'econsulting_agency_work_process_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_work_process_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_work_process_list' => [
				'selector'        => '.econsult-our-process .process_content',
				'render_callback' => 'econsulting_agency_work_process_get_content'
			],
		],
	    'default' => json_encode([
	        [
	            'label'      => esc_html__( 'Gathering Information', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusod tempor idunt ut labore dolore magna aliqua koiern koiners.', 'cdi' ),
	            'color'      => '#d83030',
	        ],
	        [
	        	'label'      => esc_html__( 'Research, Ideas & Sketch', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusod tempor idunt ut labore dolore magna aliqua koiern koiners.', 'cdi' ),
	            'color'      => '#f3a712',
	        ],
	        [
	        	'label'      => esc_html__( 'Testing & Website Launch', 'cdi' ),
	            'content'    => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusod tempor idunt ut labore dolore magna aliqua koiern koiners.', 'cdi' ),
	            'color'      => '#235789',
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add Process', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Process', 'cdi' ),
	        ],
	        'fields' => [
	            'label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Title', 'cdi' ),
	                'default'     => esc_html__( 'Gathering Information', 'cdi' ),
	            ],
	            'content' => [
	                'type'        => 'textarea',
	                'label'       => esc_html__( 'Content', 'cdi' ),
	                'default'     => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusod tempor idunt ut labore dolore magna aliqua koiern koiners.', 'cdi' ),
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