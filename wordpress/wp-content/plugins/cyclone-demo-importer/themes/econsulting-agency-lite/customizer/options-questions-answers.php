<?php

add_action( 'init' , 'econsulting_agency_questions_answers_fields' );
function econsulting_agency_questions_answers_fields(){

	Kirki::add_section( 'econsulting_agency_questions_answers', array(
	    'title'          => esc_html__( "FAQs", 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_questions_answers_status',
		'label'       => esc_html__( 'Enable FAQs ?', 'cdi' ),
		'section'     => 'econsulting_agency_questions_answers',
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
	            'section'  => 'econsulting_agency_questions_answers',
	            'settings' => 'econsulting_agency_questions_answers_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_questions_answers_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_questions_answers_spacing',
	                        'default'     => [
								'padding-top'     => '160px',
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
		                            'element'       => '.econsult-faq'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-faq'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_questions_answers_spacing',
	                        'default'     => [
								'padding-top'     => '160px',
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
	                                'element'       => '.econsult-faq',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-faq',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_questions_answers_spacing',
	                        'default'     => [
								'padding-top'     => '160px',
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
	                                'element'       => '.econsult-faq',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-faq',
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
	    'settings'    => 'econsulting_agency_questions_answers_subtitle_settings',
	    'section'     => 'econsulting_agency_questions_answers',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_questions_answers_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_questions_answers',
		'default'  => esc_html__( 'OUR BEST AGENCY', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_questions_answers_subtitle' => [
				'selector'        => '.econsult-faq .section-title p.sub',
				'render_callback' => 'econsulting_agency_questions_answers_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_questions_answers_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_questions_answers',
		'default'     => 'rgba(255, 75, 26, 0.1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-faq .section-title p.sub',
				'property'      => 'background'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_questions_answers_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_questions_answers',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-faq .section-title p.sub',
				'property'      => 'color'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_questions_answers_title_settings',
	    'section'     => 'econsulting_agency_questions_answers',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_questions_answers_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_questions_answers',
		'default'  => esc_html__( "Our Agency All Solutions", 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_questions_answers_title' => [
				'selector'        => '.econsult-faq .section-title h2',
				'render_callback' => 'econsulting_agency_questions_answers_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_questions_answers_title_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_questions_answers',
		'default'     => '#000',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-faq .section-title h2',
				'property'      => 'color'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_questions_answers_content_settings',
	    'section'     => 'econsulting_agency_questions_answers',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Description Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'econsulting_agency_questions_answers_content',
		'label'    => esc_html__( 'Description', 'cdi' ),
		'section'  => 'econsulting_agency_questions_answers',
		'default'  => esc_html__( "Sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.", 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_questions_answers_content' => [
				'selector'        => '.econsult-faq .section-title p.description',
				'render_callback' => 'econsulting_agency_questions_answers_get_content'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_questions_answers_questions_answers_settings',
	    'section'     => 'econsulting_agency_questions_answers',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Questions & Answers Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_questions_answers_border_color',
		'label'       => esc_html__( 'Border Color', 'cdi' ),
		'section'     => 'econsulting_agency_questions_answers',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-faq .panel:before',
				'property'      => 'background-color'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_questions_answers_repeater_title_color',
		'label'       => esc_html__( 'Title Color ( Active / Hover )', 'cdi' ),
		'section'     => 'econsulting_agency_questions_answers',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_questions_answers_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-faq .panel.active a,.econsult-faq .panel a:hover,.econsult-faq-accordion a',
				'property'      => 'color',
				'value_pattern' => '$ !important'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'FAQ', 'cdi' ),
	    'section'     => 'econsulting_agency_questions_answers',
	    'settings'    => 'econsulting_agency_questions_answers_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_questions_answers_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_questions_answers_list' => [
				'selector'        => '.econsult-faq .econsult-faq-accordion #accordion',
				'render_callback' => 'econsulting_agency_questions_answers_get_faq'
			],
		],
	    'default' => json_encode([
	        [
	            'title'        => esc_html__( 'WE HELP TO CREATE VISUAL STRATEGIES', 'cdi' ),
	            'content'     => esc_html__( "There are many variations of passages the majority have suffered alteration in some fo injected humour, or randomised words believable.", 'cdi' ),
	        ],
	        [
	            'title'        => esc_html__( 'MOTION GRAPHICS & ANIMATIONS', 'cdi' ),
	            'content'     => esc_html__( "There are many variations of passages the majority have suffered alteration in some fo injected humour, or randomised words believable.", 'cdi' ),
	        ],
	        [
	            'title'        => esc_html__( 'WE HELP TO ACHIEVE MUTUAL GOALS', 'cdi' ),
	            'content'     => esc_html__( "There are many variations of passages the majority have suffered alteration in some fo injected humour, or randomised words believable.", 'cdi' ),
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add FAQ', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'FAQ', 'cdi' ),
	        ],
	        'fields' => [
	            'title' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Title', 'cdi' ),
	                'default'     => esc_html__( 'WE HELP TO CREATE VISUAL STRATEGIES', 'cdi' ),
	            ],
	            'content' => [
	                'type'        => 'textarea',
	                'label'       => esc_html__( 'Content', 'cdi' ),
	                'default'     => esc_html__( "There are many variations of passages the majority have suffered alteration in some fo injected humour, or randomised words believable.", 'cdi' ),
	            ],
	        ]
	    ]
	) );

}