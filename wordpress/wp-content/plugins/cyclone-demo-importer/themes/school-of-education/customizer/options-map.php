<?php

add_action( 'init' , 'school_of_education_map_fields' );
function school_of_education_map_fields(){

	Kirki::add_section( 'school_of_education_map', array(
	    'title'          => esc_html__( 'Map', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_map_status',
		'label'       => esc_html__( 'Enable Map ?', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_map_location',
		'label'       => esc_html__( 'Location', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => esc_html__( 'Automattic', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_map_location' => [
				'selector'        => '.school-contact .map',
				'render_callback' => 'school_of_education_get_map',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'school_of_education_map_zoom',
		'label'       => esc_html__( 'Zoom In/Out', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => 14,
		'choices'     => [
			'min'  => 10,
			'max'  => 20,
			'step' => 1
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_map_zoom' => [
				'selector'        => '.school-contact .map',
				'render_callback' => 'school_of_education_get_map',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'school_of_education_map_height',
		'label'       => esc_html__( 'Height', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => 400,
		'choices'     => [
			'min'  => 300,
			'max'  => 600,
			'step' => 25
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'       => '.school-contact .contact-info iframe',
				'property'      => 'height',
				'value_pattern' => '$px'
			),
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_map_icon_background',
		'label'       => esc_html__( 'Icon Background', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => '#00b6c7',
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'  => '.contact-info .info-icon i',
				'property' => 'background',
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'school_of_education_map_section_background',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => 'rgba(238,238,238,0.25)',
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'choices'     => [
			'alpha' => true,
		],
        'transport' => 'auto',
        'output' => array(
			array(
				'element'  => '.school-contact',
				'property' => 'background',
			)
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
	            'section'  => 'school_of_education_map',
	            'settings' => 'school_of_education_map_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_map_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_map_spacing',
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
		                            'element'       => '.school-contact'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-contact'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_map_spacing',
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
	                                'element'       => '.school-contact',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-contact',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_map_spacing',
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
	                                'element'       => '.school-contact',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-contact',
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
	    'settings'    => 'school_of_education_map_other_informations',
	    'section'     => 'school_of_education_map',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Other Informations', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_map_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => esc_html__( 'Information About Us', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_map_title' => [
				'selector'        => '.school-contact .contact-info h4.title',
				'render_callback' => 'school_of_education_map_title',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'textarea',
		'settings'    => 'school_of_education_map_description',
		'label'       => esc_html__( 'Description', 'cdi' ),
		'section'     => 'school_of_education_map',
		'default'     => esc_html__( 'Sagittis posuere id nam quis vestibulum vestibulum a facilisi at elit hendrerit scelerisque sodales nam dis orci.', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_map_description' => [
				'selector'        => '.school-contact .contact-info .contact-para',
				'render_callback' => 'school_of_education_map_description',
			],
		],
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'school_of_education_map_contact_details',
	    'section'     => 'school_of_education_map',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Contact Details', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	$info_title = array( 'Location' , 'Mobile' , 'Email' );
	$info_desc  = array( "545, Marina Rd.,\nMohammed Bin Rashid Boulevard,\nDubai 123234" , '+977 722 225591' , 'info@example.com' );
	$info_icon  = array( 'fas fa-map-marker-alt' , 'fas fa-mobile-alt' , 'fas fa-envelope' );

	for ( $i=1; $i <= apply_filters( 'school_of_education_contact_limit', 3 ); $i++ ) { 
		
		Kirki::add_field( 'bizberg', [
			'type'        => 'checkbox',
			'settings'    => 'school_of_education_map_contact_status_' . $i,
			'label'       => esc_html__( 'Status ' . $i, 'cdi' ),
			'section'     => 'school_of_education_map',
			'default'     => true,
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_map_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_map_contact_status_' . $i => [
					'selector'        => '.school-contact .info_item_wrapper',
					'render_callback' => 'school_of_education_map_get_info',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_map_contact_info_title_' . $i,
			'label'       => esc_html__( 'Info Title ' . $i, 'cdi' ),
			'section'     => 'school_of_education_map',
			'default'     => $info_title[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_map_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_map_contact_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_map_contact_info_title_' . $i => [
					'selector'        => '.school-contact .info_item_wrapper',
					'render_callback' => 'school_of_education_map_get_info',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'textarea',
			'settings'    => 'school_of_education_map_contact_info_description_' . $i,
			'label'       => esc_html__( 'Info Description ' . $i, 'cdi' ),
			'section'     => 'school_of_education_map',
			'default'     => $info_desc[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_map_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_map_contact_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_map_contact_info_description_' . $i => [
					'selector'        => '.school-contact .info_item_wrapper',
					'render_callback' => 'school_of_education_map_get_info',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'font-awesome-6',
			'settings'    => 'school_of_education_map_contact_info_icon_' . $i,
			'label'       => esc_html__( 'Icon ' . $i, 'cdi' ),
			'choices' => array(
				'height'       => '150px',
				'search_icons' => true
			),
			'section'     => 'school_of_education_map',
			'default'     => $info_icon[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_map_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_map_contact_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_map_contact_info_icon_' . $i => [
					'selector'        => '.school-contact .info_item_wrapper',
					'render_callback' => 'school_of_education_map_get_info',
				],
			],
		] );

	}

}