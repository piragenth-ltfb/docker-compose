<?php

add_action( 'init' , 'school_of_education_why_choose_us_fields' );
function school_of_education_why_choose_us_fields(){

	Kirki::add_section( 'school_of_education_why_choose_us', array(
	    'title'          => esc_html__( 'Why Choose Us', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_why_choose_us_status',
		'label'       => esc_html__( 'Enable Why Choose Us ?', 'cdi' ),
		'section'     => 'school_of_education_why_choose_us',
		'default'     => true
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_why_choose_us_subtitle',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_why_choose_us',
		'default'     => esc_html__( 'About Us', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_why_choose_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_why_choose_us_subtitle' => [
				'selector'        => '.school-choose .school-section-title h4',
				'render_callback' => 'school_of_education_get_why_choose_us_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_why_choose_us_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_why_choose_us',
		'default'     => esc_html__( 'Why Choose Us', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_why_choose_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_why_choose_us_title' => [
				'selector'        => '.school-choose .school-section-title h3',
				'render_callback' => 'school_of_education_get_why_choose_us_title',
			],
		],
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
	            'section'  => 'school_of_education_why_choose_us',
	            'settings' => 'school_of_education_why_choose_us_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_why_choose_us_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_why_choose_us_spacing',
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
		                            'element'       => '.school-choose'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-choose'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_why_choose_us_spacing',
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
	                                'element'       => '.school-choose',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-choose',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_why_choose_us_spacing',
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
	                                'element'       => '.school-choose',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-choose',
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
		'type'        => 'advanced-repeater',
		'label'       => esc_attr__( 'Why Choose Us', 'cdi' ),
		'section'     => 'school_of_education_why_choose_us',
		'settings'    => 'school_of_education_why_choose_us_repeater',
		'default'     => [
			[
				'title'   => esc_attr__( 'Online/Offline Classrooms', 'cdi' ),
				'content' => esc_attr__( 'Real skills for real-world application. Experience interactive', 'cdi' ),
				'color'   => '#f14d5d',
				'icon'    => 'fas fa-magic'
			],
			[
				'title'   => esc_attr__( 'Personal Mentor Support', 'cdi' ),
				'content' => esc_attr__( 'The most impressive is collection of shared me online college', 'cdi' ),
				'color'   => '#ee8c1c',
				'icon'    => 'fas fa-fire'
			],
			[
				'title'   => esc_attr__( 'Lifetime Slack Chat Support', 'cdi' ),
				'content' => esc_attr__( 'North America right at your fingertips. All 24 of Turitor.', 'cdi' ),
				'color'   => '#181d38',
				'icon'    => 'fab fa-slack-hash'
			],
			[
				'title'   => esc_attr__( 'Job Placement Support', 'cdi' ),
				'content' => esc_attr__( 'Experience interactive courses in AWS, Google Cloud, and Azure', 'cdi' ),
				'color'   => '#55bc7e',
				'icon'    => 'fab fa-shirtsinbulk'
			],
		],
		'choices' => [
			'limit' => apply_filters( 'school_of_education_why_choose_us_repeater_limit', 4 ),
			'row_label' => [
				'value' => esc_html__( 'Options', 'cdi' ),
			],
			'fields' => [
				'title' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Title', 'cdi' )
				],
				'content' => [
					'type'        => 'textarea',
					'label'       => esc_attr__( 'Content', 'cdi' )
				],
				'color' => [
					'type'        => 'color',
					'label'       => esc_attr__( 'Color', 'cdi' )
				],
				'icon' => [
					'type'        => 'fontawesome',
					'label'       => esc_attr__( 'Icon', 'cdi' ),
					'choices'     => cdi_get_fontawesome_icons_list()
				],
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_why_choose_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_why_choose_us_repeater' => [
				'selector'        => '.school-choose .container .row',
				'render_callback' => 'school_of_education_why_choose_us_section_content'
			],
		],
	] );

}