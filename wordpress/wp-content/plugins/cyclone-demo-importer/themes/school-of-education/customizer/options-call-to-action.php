<?php

add_action( 'init' , 'school_of_education_call_to_action_fields' );
function school_of_education_call_to_action_fields(){

	Kirki::add_section( 'school_of_education_call_to_action', array(
	    'title'          => esc_html__( 'Call to Action', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_call_to_action_status',
		'label'       => esc_html__( 'Enable call to action ?', 'cdi' ),
		'section'     => 'school_of_education_call_to_action',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'school_of_education_call_to_action_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( 'Our campus information', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_call_to_action_subtitle' => [
				'selector'        => '.school-call-to-action .school-call-content h4',
				'render_callback' => 'school_of_education_get_call_to_action_subtitle',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'school_of_education_call_to_action_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( 'A Building With Four Walls And Tomorrow Inside.', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_call_to_action_title' => [
				'selector'        => '.school-call-to-action .school-call-content h3',
				'render_callback' => 'school_of_education_get_call_to_action_title',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'school_of_education_call_to_action_description',
		'label'    => esc_html__( 'Description', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( "Curabitur aliquet quam id dui posuere blandit. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Donec rutrum congue leo eget malesuada. Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus.Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam animi dicta ex labore. Ipsum nobis amet nisi voluptate corporis consectetur adipisicing elit alias", 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_call_to_action_description' => [
				'selector'        => '.school-call-to-action .school-call-content p',
				'render_callback' => 'school_of_education_get_call_to_action_description',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'simple-color',
		'settings' => 'school_of_education_call_to_action_background_color',
		'label'    => esc_html__( 'Background Color', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( '#1d223e', 'cdi' ),
		'transport' => 'auto',
		'output' => array(
			array(
				'element'  => '.school-call-to-action::after',
				'property' => 'background',
			),
		),
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
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
	            'section'  => 'school_of_education_call_to_action',
	            'settings' => 'school_of_education_call_to_action_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_call_to_action_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_call_to_action_spacing',
	                        'default'     => [
								'padding-top'     => '100px',
								'padding-bottom'  => '100px'
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
		                            'element'       => '.school-call-to-action'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-call-to-action'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_call_to_action_spacing',
	                        'default'     => [
								'padding-top'     => '100px',
								'padding-bottom'  => '100px'
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
	                                'element'       => '.school-call-to-action',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-call-to-action',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_call_to_action_spacing',
	                        'default'     => [
								'padding-top'     => '100px',
								'padding-bottom'  => '100px'
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
	                                'element'       => '.school-call-to-action',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-call-to-action',
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
	    'settings'    => 'school_of_education_call_to_action_image_settings',
	    'section'     => 'school_of_education_call_to_action',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Image Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'background',
		'settings'    => 'school_of_education_call_to_action_background_image',
		'section'     => 'school_of_education_call_to_action',
		'default'     => [
			'background-color'      => 'rgba(20,20,20,.8)',
			'background-image'      => apply_filters( 'school_of_education_call_to_action_background_image', get_stylesheet_directory_uri() . '/images/abundance-bookcase-books-bookstore-commerce-data-1418973-pxhere.com.jpg' ),
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		],
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => '.school-call-to-action',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_call_to_action_btn_settings',
	    'section'     => 'school_of_education_call_to_action',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'school_of_education_call_to_action_btn_label',
		'label'    => esc_html__( 'Label', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( 'Apply Now', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_call_to_action_btn_label' => [
				'selector'        => '.school-call-to-action .call_btn_wrapper',
				'render_callback' => 'school_of_education_get_call_to_action_button',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'school_of_education_call_to_action_btn_link',
		'label'    => esc_html__( 'Link', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( '#', 'cdi' ),
		'partial_refresh'    => [
			'school_of_education_call_to_action_btn_link' => [
				'selector'        => '.school-call-to-action .call_btn_wrapper',
				'render_callback' => 'school_of_education_get_call_to_action_button',
			],
		],
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'simple-color',
		'settings' => 'school_of_education_call_to_action_btn_background',
		'label'    => esc_html__( 'Background Color', 'cdi' ),
		'section'  => 'school_of_education_call_to_action',
		'default'  => esc_html__( '#dd9933', 'cdi' ),
		'transport' => 'auto',
		'output' => array(
			array(
				'element'  => '.call_btn_wrapper .per-btn::before',
				'property' => 'background',
			),
		),
		'active_callback' => [
			[
				'setting'  => 'school_of_education_call_to_action_status',
				'operator' => '==',
				'value'    => true,
			]
		],
	] );

}