<?php

add_action( 'init' , 'school_of_education_about_us_fields' );
function school_of_education_about_us_fields(){

	Kirki::add_section( 'school_of_education_about_us', array(
	    'title'          => esc_html__( 'About Us', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_about_us_status',
		'label'       => esc_html__( 'Enable About Us ?', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_about_us_subtitle1',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => esc_html__( 'About Us', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_subtitle1' => [
				'selector'        => '.school-about-content h4',
				'render_callback' => 'school_of_education_get_about_us_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_about_us_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => esc_html__( 'Learn New Skills to go ahead for Your Career', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_title' => [
				'selector'        => '.school-about-content .about-title',
				'render_callback' => 'school_of_education_get_about_us_title',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'textarea',
		'settings'    => 'school_of_education_about_us_content',
		'label'       => esc_html__( 'Content', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => esc_html__( 'We can support student forum 24/7 for national and international students.There are many variations of passages of. Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don’t look.', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_content' => [
				'selector'        => '.school-about-content .content',
				'render_callback' => 'school_of_education_get_about_us_content',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_about_us_background_color',
		'label'       => __( 'Background Color', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => '#181d38',
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'  => '.school-about-content',
				'property' => 'background',
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
	            'section'  => 'school_of_education_about_us',
	            'settings' => 'school_of_education_about_us_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_about_us_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_about_us_spacing',
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
		                            'element'       => '.school-about'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-about'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_about_us_spacing',
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
	                                'element'       => '.school-about',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-about',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_about_us_spacing',
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
	                                'element'       => '.school-about',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-about',
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
	    'settings'    => 'school_of_education_button_settings',
	    'section'     => 'school_of_education_about_us',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_about_us_button_label',
		'label'       => esc_html__( 'Label', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => esc_html__( 'Learn More', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_button_label' => [
				'selector'        => '.school-about-content .button_wrapper',
				'render_callback' => 'school_of_education_get_about_us_button',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_about_us_button_link',
		'label'       => esc_html__( 'URL', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => esc_html__( '#', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_button_link' => [
				'selector'        => '.school-about-content .button_wrapper',
				'render_callback' => 'school_of_education_get_about_us_button',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_about_us_button_background_color',
		'label'       => __( 'Background Color', 'cdi' ),
		'section'     => 'school_of_education_about_us',
		'default'     => '#dd9933',
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_button_link' => [
				'selector'        => '.school-about-content .button_wrapper',
				'render_callback' => 'school_of_education_get_about_us_button',
			],
		],
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_image_settings',
	    'section'     => 'school_of_education_about_us',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Image Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'background',
		'settings'    => 'school_of_education_background_image',
		'section'     => 'school_of_education_about_us',
		'default'     => [
			'background-color'      => 'rgba(20,20,20,.8)',
			'background-image'      => apply_filters( 'school_of_education_background_image_about', get_stylesheet_directory_uri() . '/images/abundance-bookcase-books-bookstore-commerce-data-1418973-pxhere.com.jpg' ),
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		],
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.school-about-main',
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_about_us_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

}