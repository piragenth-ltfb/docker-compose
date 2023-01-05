<?php

add_action( 'init' , 'school_of_education_course_category_fields' );
function school_of_education_course_category_fields(){

	Kirki::add_section( 'school_of_education_course_category', array(
	    'title'          => esc_html__( 'Courses Categories', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_course_category_status',
		'label'       => esc_html__( 'Enable Courses Categories ?', 'cdi' ),
		'section'     => 'school_of_education_course_category',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_course_category_subtitle',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_course_category',
		'default'     => esc_html__( 'Courses Categories', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_course_category_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_about_us_subtitle' => [
				'selector'        => '.school-category .school-section-title h4',
				'render_callback' => 'school_of_education_get_course_categories_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_course_category_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_course_category',
		'default'     => esc_html__( 'Browse Trending Categories', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_course_category_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_course_category_title' => [
				'selector'        => '.school-category .school-section-title h3',
				'render_callback' => 'school_of_education_get_course_categories_title',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'typography',
		'settings'    => 'school_of_education_course_category_title_font_settings',
		'label'       => esc_html__( 'Title Font', 'cdi' ),
		'section'     => 'school_of_education_course_category',
		'default'     => [
			'font-family'    => 'Poppins',
			'variant'        => '600',
			'font-size'      => '19px',
			'line-height'    => '1.3',
			'letter-spacing' => '0'
		],
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.school-category-main .partner-slider h5.category-title',
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_course_category_status',
                'operator' => '==',
                'value'    => true
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
	            'section'  => 'school_of_education_course_category',
	            'settings' => 'school_of_education_course_category_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_course_category_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_course_category_spacing',
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
		                            'element'       => '.school-category'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-category'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_course_category_spacing',
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
	                                'element'       => '.school-category',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-category',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_course_category_spacing',
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
	                                'element'       => '.school-category',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-category',
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
	    'settings'    => 'school_of_education_course_category_content_settings',
	    'section'     => 'school_of_education_course_category',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_course_category_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'advanced-repeater',
		'label'       => esc_attr__( 'Courses', 'cdi' ),
		'section'     => 'school_of_education_course_category',
		'settings'    => 'school_of_education_course_category_content_repeater',
		'default'     => [
			[
				'title'       => esc_attr__( 'Graphic & Web Design', 'cdi' ),
				'icon'        => 'fas fa-headphones',
				'background'  => '#00b6c7',
				'link'        => '#'
			],
			[
				'title'       => esc_attr__( 'Business & Management', 'cdi' ),
				'icon'        => 'far fa-chart-bar',
				'background'  => '#fb7f6a',
				'link'        => '#'
			],
			[
				'title'       => esc_attr__( 'Programming Courses', 'cdi' ),
				'icon'        => 'fas fa-laptop',
				'background'  => 'rgb(72, 167, 212)',
				'link'        => '#'
			],
			[
				'title'       => esc_attr__( 'Logical Thinking', 'cdi' ),
				'icon'        => 'fas fa-lightbulb',
				'background'  => '#333a65',
				'link'        => '#'
			],
			[
				'title'       => esc_attr__( 'Social Media Management', 'cdi' ),
				'icon'        => 'fas fa-users',
				'background'  => 'rgb(217, 77, 166)',
				'link'        => '#'
			],
			[
				'title'       => esc_attr__( 'Movie Film Making', 'cdi' ),
				'icon'        => 'fas fa-camera',
				'background'  => 'rgb(48, 122, 213)',
				'link'        => '#'
			],
			[
				'title'       => esc_attr__( 'Software Training', 'cdi' ),
				'icon'        => 'fas fa-wrench',
				'background'  => 'rgb(16, 196, 92)',
				'link'        => '#'
			],
		],
		'choices' => [
			'limit' => apply_filters( 'school_of_education_course_category_content_repeater_limit', 6 ),
			'fields' => [
				'title' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Title', 'cdi' ),
				],
				'icon' => [
					'type'        => 'fontawesome',
					'label'       => esc_attr__( 'Icon', 'cdi' ),
					'choices'     => cdi_get_fontawesome_icons_list()
				],
				'background' => [
					'type'        => 'color',
					'label'       => esc_attr__( 'Background Color', 'cdi' )
				],
				'link' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Link', 'cdi' ),
				],
			],
			'row_label' => [
				'value' => esc_html__( 'Course', 'cdi' ),
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_course_category_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
		'partial_refresh'    => [
			'school_of_education_course_category_content_repeater' => [
				'selector'        => '.school-category .school-category-main',
				'render_callback' => 'school_of_education_get_course_content'
			],
		],
	] );

}