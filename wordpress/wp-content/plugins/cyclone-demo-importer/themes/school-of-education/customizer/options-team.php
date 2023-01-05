<?php

add_action( 'init' , 'school_of_education_team_fields' );
function school_of_education_team_fields(){

	Kirki::add_section( 'school_of_education_teams', array(
	    'title'          => esc_html__( 'Teams', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_teams_status',
		'label'       => esc_html__( 'Enable Teams ?', 'cdi' ),
		'section'     => 'school_of_education_teams',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_teams_subtitle',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_teams',
		'default'     => esc_html__( 'Our Teachers', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_teams_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_teams_subtitle' => [
				'selector'        => '.school-team .school-section-title h4',
				'render_callback' => 'school_of_education_teams_get_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_teams_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_teams',
		'default'     => esc_html__( 'Meet Our Expert Teachers', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_teams_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_teams_title' => [
				'selector'        => '.school-team .school-section-title h2',
				'render_callback' => 'school_of_education_teams_get_title',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'textarea',
		'settings'    => 'school_of_education_teams_description',
		'label'       => esc_html__( 'Description', 'cdi' ),
		'section'     => 'school_of_education_teams',
		'default'     => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_teams_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_teams_description' => [
				'selector'        => '.school-team .school-section-title .testimonial-abt p',
				'render_callback' => 'school_of_education_teams_get_description',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_teams_primary_color',
		'label'       => esc_html__( 'Primary Color', 'cdi' ),
		'section'     => 'school_of_education_teams',
		'default'     => '#1d223e',
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_teams_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'  => '.school-team',
				'property' => 'background'
			),
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_teams_secondary_color',
		'label'       => esc_html__( 'Secondary Color', 'cdi' ),
		'section'     => 'school_of_education_teams',
		'default'     => '#ed7800',
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_teams_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'  => '.school-team::before',
				'property' => 'background'
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
	            'section'  => 'school_of_education_teams',
	            'settings' => 'school_of_education_teams_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_teams_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_teams_spacing',
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
		                            'element'       => '.school-team'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-team'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_teams_spacing',
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
	                                'element'       => '.school-team',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-team',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_teams_spacing',
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
	                                'element'       => '.school-team',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-team',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	for ($i=1; $i <= apply_filters( 'school_of_education_teams_limit', 4 ); $i++) { 
		
		Kirki::add_field( 'bizberg', array(
		    'type'        => 'custom',
		    'settings'    => 'school_of_education_teams_heading_' . $i,
		    'section'     => 'school_of_education_teams',
		    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Team ' . $i, 'cdi' ) . '</div>',
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_teams_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
		) );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'checkbox',
		    'settings'    => 'school_of_education_teams_status_' . $i,
		    'section'     => 'school_of_education_teams',
		    'label'       => esc_html__( 'Enable ' . $i, 'cdi' ),
		    'default'     => ( $i <= 4 ? true : false ),
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_teams_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_teams_status_' . $i => [
					'selector'        => '.school-team .team_wrapper',
					'render_callback' => 'school_of_education_teams_get_team_content',
				],
			],
		) );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'image',
		    'settings'    => 'school_of_education_teams_image_' . $i,
		    'section'     => 'school_of_education_teams',
		    'label'       => esc_html__( 'Image ' . $i, 'cdi' ),
		    'default'     => apply_filters( 'school_of_education_teams_images', get_stylesheet_directory_uri() . '/images/nature-person-light-people-girl-sun-943113-pxhere.com.jpg' ),
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_teams_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_teams_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_teams_image_' . $i => [
					'selector'        => '.school-team .team_wrapper',
					'render_callback' => 'school_of_education_teams_get_team_content',
				],
			],
			'choices'     => [
				'save_as' => 'id',
			],
		) );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'text',
		    'settings'    => 'school_of_education_teams_content_title_' . $i,
		    'section'     => 'school_of_education_teams',
		    'label'       => esc_html__( 'Title ' . $i, 'cdi' ),
		    'default'     => 'Adam Smith',
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_teams_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_teams_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_teams_content_title_' . $i => [
					'selector'        => '.school-team .team_wrapper',
					'render_callback' => 'school_of_education_teams_get_team_content',
				],
			]
		) );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'text',
		    'settings'    => 'school_of_education_teams_content_subtitle_' . $i,
		    'section'     => 'school_of_education_teams',
		    'label'       => esc_html__( 'Subtitle ' . $i, 'cdi' ),
		    'default'     => 'Language Teacher',
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_teams_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_teams_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_teams_content_subtitle_' . $i => [
					'selector'        => '.school-team .team_wrapper',
					'render_callback' => 'school_of_education_teams_get_team_content',
				],
			]
		) );

	}

}