<?php

add_action( 'init' , 'school_of_education_testimonials_fields' );
function school_of_education_testimonials_fields(){ 

	Kirki::add_section( 'school_of_education_testimonials', array(
	    'title'          => esc_html__( 'Testimonials', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_testimonials_status',
		'label'       => esc_html__( 'Enable Testimonials ?', 'cdi' ),
		'section'     => 'school_of_education_testimonials',
		'default'     => true
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_testimonials_subtitle',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_testimonials',
		'default'     => esc_html__( 'Happy Student', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_testimonials_subtitle' => [
				'selector'        => '.school-testimonial .school-section-title h4',
				'render_callback' => 'school_of_education_get_testimonials_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_testimonials_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_testimonials',
		'default'     => esc_html__( 'What People Say About Us', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_testimonials_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_testimonials_title' => [
				'selector'        => '.school-testimonial .school-section-title h3',
				'render_callback' => 'school_of_education_get_testimonials_title',
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
	            'section'  => 'school_of_education_testimonials',
	            'settings' => 'school_of_education_testimonials_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_testimonials_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_testimonials_spacing',
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
		                            'element'       => '.school-testimonial'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-testimonial'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_testimonials_spacing',
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
	                                'element'       => '.school-testimonial',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-testimonial',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_testimonials_spacing',
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
	                                'element'       => '.school-testimonial',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-testimonial',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	$names = array( 'Liam Olivia' , 'Noah Emma' , 'Oliver Ava' , 'Elijah Charlotte' );

	for ( $i=1; $i <= apply_filters( 'school_of_education_testimonials_limit', 4 ); $i++ ) { 
		
		Kirki::add_field( 'bizberg', array(
		    'type'        => 'custom',
		    'settings'    => 'school_of_education_testimonials_heading_' . $i,
		    'section'     => 'school_of_education_testimonials',
		    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Testimonial ' . $i, 'cdi' ) . '</div>',
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_testimonials_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
		) );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'checkbox',
		    'settings'    => 'school_of_education_testimonials_content_status_' . $i,
		    'label'       => esc_html__( 'Enable', 'cdi' ),
		    'section'     => 'school_of_education_testimonials',
		    'default'     => ( $i <= 4 ? true : false ),
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_testimonials_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_testimonials_content_status_' . $i => [
					'selector'        => '.school-testimonial .testimonial_wrapper',
					'render_callback' => 'school_of_education_get_testimonials_get_testimonial_content',
				],
			],
		) );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_testimonials_name_' . $i,
			'label'       => esc_html__( 'Name', 'cdi' ),
			'section'     => 'school_of_education_testimonials',
			'default'     => ( $i <= 4 ? $names[$i-1] : '' ),
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_testimonials_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_testimonials_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	  		'partial_refresh'    => [
				'school_of_education_testimonials_name_' . $i => [
					'selector'        => '.school-testimonial .testimonial_wrapper',
					'render_callback' => 'school_of_education_get_testimonials_get_testimonial_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_testimonials_designation_' . $i,
			'label'       => esc_html__( 'Designation', 'cdi' ),
			'section'     => 'school_of_education_testimonials',
			'default'     => 'Supervisor',
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_testimonials_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_testimonials_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	  		'partial_refresh'    => [
				'school_of_education_testimonials_designation_' . $i => [
					'selector'        => '.school-testimonial .testimonial_wrapper',
					'render_callback' => 'school_of_education_get_testimonials_get_testimonial_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'textarea',
			'settings'    => 'school_of_education_testimonials_description_' . $i,
			'label'       => esc_html__( 'Description', 'cdi' ),
			'section'     => 'school_of_education_testimonials',
			'default'     => 'Lorem Ipsum is simply dummy text of the printing andypesetting industry. Lorem ipsum a simple Lorem Ipsum has been the industrys standard dummy hic et quidem. Dignissimos maxime velit unde inventore quasi vero dolorem.',
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_testimonials_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_testimonials_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	  		'partial_refresh'    => [
				'school_of_education_testimonials_description_' . $i => [
					'selector'        => '.school-testimonial .testimonial_wrapper',
					'render_callback' => 'school_of_education_get_testimonials_get_testimonial_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', array(
		    'type'        => 'image',
		    'settings'    => 'school_of_education_testimonials_image_' . $i,
		    'section'     => 'school_of_education_testimonials',
		    'label'       => esc_html__( 'Image ' . $i, 'cdi' ),
		    'default'     => apply_filters( 'school_of_education_testimonials_images', get_stylesheet_directory_uri() . '/images/nature-person-light-people-girl-sun-943113-pxhere.com.jpg' ),
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_testimonials_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_testimonials_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_testimonials_image_' . $i => [
					'selector'        => '.school-testimonial .testimonial_wrapper',
					'render_callback' => 'school_of_education_get_testimonials_get_testimonial_content',
				],
			],
			'choices'     => [
				'save_as' => 'id',
			],
		) );

	}

}