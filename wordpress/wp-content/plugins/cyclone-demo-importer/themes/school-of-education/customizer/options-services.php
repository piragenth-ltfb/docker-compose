<?php

add_action( 'init' , 'school_of_education_services_fields' );
function school_of_education_services_fields(){

	Kirki::add_section( 'school_of_education_services', array(
	    'title'          => esc_html__( 'Services', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_services_status',
		'label'       => esc_html__( 'Enable Services ?', 'cdi' ),
		'section'     => 'school_of_education_services',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'advanced-repeater',
		'label'       => esc_attr__( 'Services', 'cdi' ),
		'section'     => 'school_of_education_services',
		'settings'    => 'school_of_education_services_repeater',
		'default'     => [
			[
				'icon'       => 'fas fa-graduation-cap',
				'title'      => 'TRENDING COURSES',
				'content'    => 'Your chance to be a trending expert in IT industries and make a successful career after completion of our courses.',
				'background' => '#333a65'
			],
			[
				'icon'       => 'fas fa-book',
				'title'      => 'BOOKS & LIBRARY',
				'content'    => 'Bizberg is one of the world`s busiest public library systems, with over 10 million books, movies and other items to borrow.',
				'background' => '#626ce1'
			],
			[
				'icon'       => 'far fa-gem',
				'title'      => 'CERTIFIED TEACHERS',
				'content'    => 'Get professional education and reliable consultation by our team of certified teachers and instructors.',
				'background' => '#00b6c7'
			],
			[
				'icon'       => 'far fa-newspaper',
				'title'      => 'CERTIFICATION',
				'content'    => 'Upon successful completion receive a certificate showing your achievement for completing one of our rigorous classes.',
				'background' => '#1f98df'
			]
		],
		'choices' => [
			'limit' => 4,
			'fields' => [
				'icon' => [
					'type'        => 'fontawesome',
					'label'       => esc_attr__( 'Icon', 'cdi' ),
					'choices'     => cdi_get_fontawesome_icons_list()
				],
				'title' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Title', 'cdi' )
				],
				'content' => [
					'type'        => 'textarea',
					'label'       => esc_attr__( 'Content', 'cdi' )
				],
				'background' => [
					'type'        => 'color',
					'label'       => esc_attr__( 'Background Color', 'cdi' )
				],
				'link' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Link', 'cdi' )
				],
			],
			'row_label' => [
				'value' => esc_html__( 'Service', 'cdi' ),
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_services_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_services_repeater' => [
				'selector'        => '.school-featured .school-featured-main',
				'render_callback' => 'school_of_education_get_services'
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
	            'section'  => 'school_of_education_services',
	            'settings' => 'school_of_education_services_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_services_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_services_spacing',
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
		                            'element'       => '.school-featured'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-featured'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_services_spacing',
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
	                                'element'       => '.school-featured',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-featured',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_services_spacing',
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
	                                'element'       => '.school-featured',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-featured',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

}