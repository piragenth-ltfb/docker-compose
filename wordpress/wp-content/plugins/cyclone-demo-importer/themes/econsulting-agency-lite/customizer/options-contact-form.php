<?php

add_action( 'init' , 'econsulting_agency_contact_fields' );
function econsulting_agency_contact_fields(){

	Kirki::add_section( 'econsulting_agency_contact', array(
	    'title'          => esc_html__( 'Contact Us', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_contact_status',
		'label'       => esc_html__( 'Enable Contact Us ?', 'cdi' ),
		'section'     => 'econsulting_agency_contact',
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
	            'section'  => 'econsulting_agency_contact',
	            'settings' => 'econsulting_agency_contact_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_contact_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_contact_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '160px'
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
		                            'element'       => '.econsult-contact'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.econsult-contact'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_contact_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '160px'
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
	                                'element'       => '.econsult-contact',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-contact',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'econsulting_agency_contact_spacing',
	                        'default'     => [
								'padding-top'     => '80px',
								'padding-bottom'  => '160px'
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
	                                'element'       => '.econsult-contact',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.econsult-contact',
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
	    'settings'    => 'econsulting_agency_contact_subtitle_settings',
	    'section'     => 'econsulting_agency_contact',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Subtitle Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_contact_subtitle',
		'label'    => esc_html__( 'Subtitle', 'cdi' ),
		'section'  => 'econsulting_agency_contact',
		'default'  => esc_html__( 'CONTACT WITH US', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_contact_subtitle' => [
				'selector'        => '.econsult-contact .section-title p',
				'render_callback' => 'econsulting_agency_contact_form_get_subtitle'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_contact_subtitle_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_contact',
		'default'     => '#1c2126',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-contact .section-title p',
				'property'      => 'background',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_contact_subtitle_text_color',
		'label'       => esc_html__( 'Text Color', 'cdi' ),
		'section'     => 'econsulting_agency_contact',
		'default'     => 'rgba(255, 74, 23,1)',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-contact .section-title p',
				'property'      => 'color',
				'value_pattern' => '$'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_contact_title_settings',
	    'section'     => 'econsulting_agency_contact',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_contact_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_contact',
		'default'  => esc_html__( 'We are Here to Help You & Your Business', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_contact_title' => [
				'selector'        => '.econsult-contact .section-title h2',
				'render_callback' => 'econsulting_agency_contact_form_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_contact_description_settings',
	    'section'     => 'econsulting_agency_contact',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Description Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'textarea',
		'settings' => 'econsulting_agency_contact_description',
		'label'    => esc_html__( 'Description', 'cdi' ),
		'section'  => 'econsulting_agency_contact',
		'default'  => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour,', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_contact_description' => [
				'selector'        => '.econsult-contact .section-title span.description',
				'render_callback' => 'econsulting_agency_contact_form_get_description'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_contact_form_settings',
	    'section'     => 'econsulting_agency_contact',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Contact Form Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'select',
		'settings'    => 'econsulting_agency_contact_form_7_forms',
		'label'       => esc_html__( 'Contact Forms', 'cdi' ),
		'section'     => 'econsulting_agency_contact',
		'multiple'    => 1,
		'default'     => apply_filters( 'econsulting_agency_contact_form_7_id', '1' ),
		'choices'     => econsulting_agency_contact_form_7_forms(),
		'partial_refresh'    => [
			'econsulting_agency_contact_form_7_forms' => [
				'selector'        => '.econsult-contact .econsult-contact-form',
				'render_callback' => 'econsulting_agency_get_contact_form_7'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_contact_form_locations',
	    'section'     => 'econsulting_agency_contact',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Office Locations Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_contact_form_locations_status',
		'label'       => esc_html__( 'Enable Locations ?', 'cdi' ),
		'section'     => 'econsulting_agency_contact',
		'default'     => true,
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_contact_form_locations_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_contact',
		'default'  => esc_html__( 'Visit Our Office', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_contact_form_locations_title' => [
				'selector'        => '.econsult-contact .econsult-contact-in h2',
				'render_callback' => 'econsulting_agency_contact_form_locations_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_contact_form_locations_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'econsulting_agency_contact_form_locations_columns',
		'label'       => esc_html__( 'Columns', 'cdi' ),
		'section'     => 'econsulting_agency_contact',
		'default'     => 4,
		'choices'     => [
			'min'  => 2,
			'max'  => 5,
			'step' => 1,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_contact_status',
                'operator' => '==',
                'value'    => true
            ),
            array(
                'setting'  => 'econsulting_agency_contact_form_locations_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport' => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-contact-address',
				'property'      => 'grid-template-columns',
				'value_pattern' => 'repeat($, 1fr)'
			),
		),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'advanced-repeater',
	    'label'       => esc_html__( 'Locations', 'cdi' ),
	    'section'     => 'econsulting_agency_contact',
	    'settings'    => 'econsulting_agency_contact_list',
	    'active_callback' => [
			[
				'setting'  => 'econsulting_agency_contact_status',
				'operator' => '==',
				'value'    => true,
			],
			array(
                'setting'  => 'econsulting_agency_contact_form_locations_status',
                'operator' => '==',
                'value'    => true
            ),
		],
		'partial_refresh'    => [
			'econsulting_agency_contact_list' => [
				'selector'        => '.econsult-contact-address',
				'render_callback' => 'econsulting_agency_contact_form_location_list'
			],
		],
	    'default' => json_encode([
	        [
	            'location'     => 'Austin',
	            'sub_location' => '22 Texas West Hills',
	            'email'        => 'noreply@example.com',
	            'tel'          => '+1-123-456-789',
	        ],
	        [
	            'location'     => 'Boston',
	            'sub_location' => '22 Texas West Hills',
	            'email'        => 'noreply@example.com',
	            'tel'          => '+1-123-456-789',
	        ],
	        [
	            'location'     => 'New York',
	            'sub_location' => '22 Texas West Hills',
	            'email'        => 'noreply@example.com',
	            'tel'          => '+1-123-456-789',
	        ],
	        [
	            'location'     => 'Dubai',
	            'sub_location' => '22 Texas West Hills',
	            'email'        => 'noreply@example.com',
	            'tel'          => '+1-123-456-789',
	        ],
	    ]),
	    'choices' => [
	        'button_label' => esc_html__( 'Add Locations', 'cdi' ),
	        'row_label' => [
	            'value' => esc_html__( 'Locations', 'cdi' ),
	        ],
	        'fields' => [
	            'location' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Location', 'cdi' ),
	                'default'     => esc_html__( 'Austin', 'cdi' )
	            ],
	            'sub_location' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Sub Location', 'cdi' ),
	                'default'     => esc_html__( '22 Texas West Hills', 'cdi' )
	            ],
	            'email' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Email', 'cdi' ),
	                'default'     => esc_html__( 'noreply@example.com', 'cdi' )
	            ],
	            'tel' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Telephone / Mobile', 'cdi' ),
	                'default'     => esc_html__( '+1-123-456-789', 'cdi' )
	            ],
	            'custom_field_1' => [
	                'type'        => 'heading',
	                'label'       => esc_html__( 'Custom Fields 1', 'cdi' ),
	                'choices'     => [
	                    'background'  => '#ff5722'
	                ],
	            ],
	            'custom_field_1_label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Custom Field 1 Label', 'cdi' ),
	                'default'     => ''
	            ],
	            'custom_field_1_link' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Custom Field 1 Link', 'cdi' ),
	                'default'     => ''
	            ],
	            'custom_field_1_target'  => [
	                'type'        => 'checkbox',
	                'label'       => esc_html__( 'Open in a new tab ?', 'cdi' ),
	                'default'     => false,
	            ],
	            'custom_field_2' => [
	                'type'        => 'heading',
	                'label'       => esc_html__( 'Custom Fields 2', 'cdi' ),
	                'choices'     => [
	                    'background'  => '#ff5722'
	                ],
	            ],
	            'custom_field_2_label' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Custom Field 2 Label', 'cdi' ),
	                'default'     => ''
	            ],
	            'custom_field_2_link' => [
	                'type'        => 'text',
	                'label'       => esc_html__( 'Custom Field 2 Link', 'cdi' ),
	                'default'     => ''
	            ],
	            'custom_field_2_target'  => [
	                'type'        => 'checkbox',
	                'label'       => esc_html__( 'Open in a new tab ?', 'cdi' ),
	                'default'     => false,
	            ],
	        ]
	    ]
	) );

}