<?php

add_action( 'init' , 'econsulting_agency_about_us_fields' );
function econsulting_agency_about_us_fields(){

	Kirki::add_section( 'econsulting_agency_about_us', array(
	    'title'          => esc_html__( 'About Us', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_about_us_status',
		'label'       => esc_html__( 'Enable About Us ?', 'cdi' ),
		'section'     => 'econsulting_agency_about_us',
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
	            'section'  => 'econsulting_agency_about_us',
	            'settings' => 'econsulting_agency_about_us_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_about_us_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_about_us_spacing',
	                        'default'     => [
								'padding-top'     => '0px',
								'padding-bottom'  => '70px'
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
		                            'element'       => '.econsult-about-us'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-about-us'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_about_us_spacing',
	                        'default'     => [
								'padding-top'     => '0px',
								'padding-bottom'  => '70px'
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
	                                'element'       => '.econsult-about-us',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-about-us',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_about_us_spacing',
	                        'default'     => [
								'padding-top'     => '0px',
								'padding-bottom'  => '70px'
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
	                                'element'       => '.econsult-about-us',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-about-us',
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
		'settings'    => 'econsulting_agency_about_us_image',
		'label'       => esc_html__( 'Image', 'cdi' ),
		'section'     => 'econsulting_agency_about_us',
		'default'     => apply_filters( 'cdi_econsulting_agency_about_us_image', get_stylesheet_directory_uri() . '/images/computer-working-person-group-people-meeting-559565-pxhere.com.jpg' ),
		'transport'   => 'auto',
		'active_callback' => [
			[
				'setting'  => 'econsulting_agency_about_us_status',
				'operator' => '==',
				'value'    => true,
			]
		],
		'output' => array(
			array(
				'element'  => '.econsult-about-us .about-us-image',
				'property' => 'background-image',
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_about_us_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_about_us',
		'default'     => '#343a40',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-about-us .about-us-content',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_about_us_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_about_us',
		'default'  => esc_html__( 'Consultancy like never before with the best team', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_about_us_title' => [
				'selector'        => '.econsult-about-us .about-us-content h4',
				'render_callback' => 'econsulting_agency_get_about_us_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'econsulting_agency_about_us_content',
		'label'    => esc_html__( 'Content', 'cdi' ),
		'section'  => 'econsulting_agency_about_us',
		'default'  => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.

It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_about_us_content' => [
				'selector'        => '.econsult-about-us .about-us-content .content',
				'render_callback' => 'econsulting_agency_get_about_us_content'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_about_us_button_section',
	    'section'     => 'econsulting_agency_about_us',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'radio-buttonset',
		'settings'    => 'econsulting_agency_about_us_button_status',
		'label'       => esc_html__( 'Button', 'cdi' ),
		'section'     => 'econsulting_agency_about_us',
		'default'     => 'flex',
		'choices'     => [
			'flex'   => esc_html__( 'Show', 'cdi' ),
			'none'   => esc_html__( 'Hide', 'cdi' ),
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-about-us .button_wrapper',
				'property'      => 'display',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_about_us_button_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_about_us',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_about_us_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.about-us-content .button_wrapper .per-btn::before',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_about_us_button_text',
		'label'    => esc_html__( 'Label', 'cdi' ),
		'section'  => 'econsulting_agency_about_us',
		'default'  => esc_html__( 'View More', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_about_us_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_about_us_button_text' => [
				'selector'        => '.econsult-about-us .button_wrapper',
				'render_callback' => 'econsulting_agency_get_about_us_button'
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_about_us_button_link',
		'label'    => esc_html__( 'Link', 'cdi' ),
		'section'  => 'econsulting_agency_about_us',
		'default'  => esc_html__( '#', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_about_us_button_status',
                'operator' => '==',
                'value'    => 'flex'
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_about_us_button_link' => [
				'selector'        => '.econsult-about-us .button_wrapper',
				'render_callback' => 'econsulting_agency_get_about_us_button'
			],
		],
	] );

}