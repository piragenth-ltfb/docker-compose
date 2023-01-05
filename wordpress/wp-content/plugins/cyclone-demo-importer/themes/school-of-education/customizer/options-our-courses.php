<?php

add_action( 'init' , 'school_of_education_our_courses_fields' );
function school_of_education_our_courses_fields(){

	Kirki::add_section( 'school_of_education_our_courses', array(
	    'title'          => esc_html__( 'Courses', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_our_courses_status',
		'label'       => esc_html__( 'Enable Courses ?', 'cdi' ),
		'section'     => 'school_of_education_our_courses',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_our_courses_subtitle',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_our_courses',
		'default'     => esc_html__( 'Our Top Courses', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_our_courses_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_our_courses_subtitle' => [
				'selector'        => '.school-featured-course .school-section-title h4',
				'render_callback' => 'school_of_education_get_our_courses_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_our_courses_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_our_courses',
		'default'     => esc_html__( 'Pick a Course to Get Started', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_our_courses_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_our_courses_title' => [
				'selector'        => '.school-featured-course .school-section-title h3',
				'render_callback' => 'school_of_education_get_our_courses_title',
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
	            'section'  => 'school_of_education_our_courses',
	            'settings' => 'school_of_education_our_courses_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_our_courses_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_our_courses_spacing',
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
		                            'element'       => '.school-featured-course'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-featured-course'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_our_courses_spacing',
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
	                                'element'       => '.school-featured-course',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-featured-course',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_our_courses_spacing',
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
	                                'element'       => '.school-featured-course',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-featured-course',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	$bth_label = array( 'Read More' , 'View More' , 'Apply Now' );
	$color     = array( '#f14d5d' , '#ee8c1c' , '#333a65' );
	$title     = array( 'Competitive Strategy law for all students' , 'Machine Learning A-Z: Hands-On Python and java' , 'Music Theory Learn New student & Fundamentals' );

	for ( $i = 1; $i <= apply_filters( 'school_of_education_our_courses_limit', 3 ); $i++ ) { 

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'custom',
		    'settings'    => 'school_of_education_our_courses_heading_' . $i,
		    'section'     => 'school_of_education_our_courses',
		    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content ' . $i, 'cdi' ) . '</div>',
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
		) );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'checkbox',
		    'settings'    => 'school_of_education_our_courses_status_' . $i,
		    'section'     => 'school_of_education_our_courses',
		    'label'       => esc_html__( 'Enable ' . $i, 'cdi' ),
		    'default'     => ( $i <= 3 ? true : false ),
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_status_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		) );

		Kirki::add_field( 'bizberg', [
			'type'        => 'image',
			'settings'    => 'school_of_education_our_courses_content_image_' . $i,
			'label'       => esc_html__( 'Image '. $i, 'cdi' ),
			'section'     => 'school_of_education_our_courses',
			'default'     => apply_filters( 'school_of_education_our_courses_content_image', get_stylesheet_directory_uri() . '/images/work-man-person-pen-reading-male-845899-pxhere.com.jpg' ),
			'choices'     => [
				'save_as' => 'id',
			],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_our_courses_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_content_image_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		] );
		
		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_our_courses_content_title_' . $i,
			'label'       => esc_html__( 'Title ' . $i, 'cdi' ),
			'section'     => 'school_of_education_our_courses',
			'default'     => ( $i <= 3 ? $title[$i-1] : '' ),
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_our_courses_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_content_title_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'textarea',
			'settings'    => 'school_of_education_our_courses_content_description_' . $i,
			'label'       => esc_html__( 'Description ' . $i, 'cdi' ),
			'section'     => 'school_of_education_our_courses',
			'default'     => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'cdi' ),
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_our_courses_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_content_description_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_our_courses_content_button_label_' . $i,
			'label'       => esc_html__( 'Label ' . $i, 'cdi' ),
			'section'     => 'school_of_education_our_courses',
			'default'     => ( $i <= 3 ? $bth_label[$i-1] : '' ),
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_our_courses_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_content_button_label_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'link',
			'settings'    => 'school_of_education_our_courses_content_button_link_' . $i,
			'label'       => esc_html__( 'Link ' . $i, 'cdi' ),
			'section'     => 'school_of_education_our_courses',
			'default'     => esc_html__( '#', 'cdi' ),
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_our_courses_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_content_button_link_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'simple-color',
			'settings'    => 'school_of_education_our_courses_color_' . $i,
			'label'       => __( 'Button & Border Color', 'cdi' ),
			'section'     => 'school_of_education_our_courses',
			'default'     => ( $i <= 3 ? $color[$i-1] : '' ),
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_our_courses_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_our_courses_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_our_courses_color_' . $i => [
					'selector'        => '.school-featured-course .container .featured_courses_wrapper',
					'render_callback' => 'school_of_education_get_our_courses_content',
				],
			],
		] );

	}

}