<?php

add_action( 'init' , 'econsulting_agency_achievements_fields' );
function econsulting_agency_achievements_fields(){

	Kirki::add_section( 'econsulting_agency_achievements', array(
	    'title'          => esc_html__( 'Achievements', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_achievements_status',
		'label'       => esc_html__( 'Enable Achievements ?', 'cdi' ),
		'section'     => 'econsulting_agency_achievements',
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
	            'section'  => 'econsulting_agency_achievements',
	            'settings' => 'econsulting_agency_achievements_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_achievements_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_achievements_spacing',
	                        'default'     => [
								'padding-top'     => '40px',
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
		                            'element'       => '.econsult-counter'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-counter'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_achievements_spacing',
	                        'default'     => [
								'padding-top'     => '40px',
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
	                                'element'       => '.econsult-counter',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-counter',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_achievements_spacing',
	                        'default'     => [
								'padding-top'     => '40px',
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
	                                'element'       => '.econsult-counter',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-counter',
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
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_achievements_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_achievements',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_achievements_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-counter-in',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_achievements_separator',
		'label'       => esc_html__( 'Separator Color', 'cdi' ),
		'section'     => 'econsulting_agency_achievements',
		'default'     => '#fa8361',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_achievements_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-counter-in .d-line',
				'property'      => 'border-right',
				'value_pattern' => '1px solid $'
			),
			array(
				'element'       => '.rtl .econsult-counter-in .d-line',
				'property'      => 'border-left',
				'value_pattern' => '1px solid $'
			),
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Achievements', 'cdi' ),
	    'section'     => 'econsulting_agency_achievements',
	    'settings'    => 'econsulting_agency_achievements_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_achievements_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'partial_refresh'    => [
			'econsulting_agency_achievements_list' => [
				'selector'        => '.econsult-counter .container',
				'render_callback' => 'econsulting_agency_achievements_lists'
			],
		],
	    'default' => json_encode([
	        [
	            'icon'   => 'fas fa-briefcase',
	            'count'  => '25',
	            'label'  => esc_html__( 'Years of experience', 'cdi' ),
	            'suffix' => esc_html__( '+', 'cdi' ),
	        ],
	        [
	            'icon'  => 'fas fa-users',
	            'count' => '355',
	            'label' => esc_html__( 'Trusted Clients', 'cdi' ),
	            'suffix' => esc_html__( '+', 'cdi' ),
	        ],
	        [
	            'icon'  => 'fas fa-glass-cheers',
	            'count' => '35',
	            'label' => esc_html__( 'Visited Conferences', 'cdi' ),
	            'suffix' => esc_html__( '+', 'cdi' ),
	        ],
	        [
	            'icon'  => 'fas fa-award',
	            'count' => '20',
	            'label' => esc_html__( 'Master Certifications', 'cdi' ),
	            'suffix' => esc_html__( '+', 'cdi' ),
	        ],
	    ]),
	    'choices' => [
	        'limit' => 4,
	        'button_label' => esc_html__( 'Add Achievements', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Achievement', 'cdi' ),
	        ],
	        'fields' => [
	        	'icon'  => [
	                'type'        => 'fontawesome',
	                'label'       => esc_html__( 'Icon', 'cdi' ),
	                'default'     => 'fab fa-accusoft',
	                'choices'     => bizberg_get_fontawesome_options(),
	            ],
	            'count' => [
	                'type'        => 'number',
	                'label'       => esc_html__( 'Count', 'cdi' ),
	                'default'     => 30,
	                'choices'     => [
	                    'min'  => 0,
	                    'max'  => 99999,
	                    'step' => 1,
	                ],
	            ],
	            'label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Label', 'cdi' ),
	                'default'     => esc_html__( 'Your Label', 'cdi' ),
	            ],
	            'suffix' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Suffix', 'cdi' ),
	                'default'     => esc_html__( '+', 'cdi' ),
	            ],
	        ]
	    ]
	) );

}