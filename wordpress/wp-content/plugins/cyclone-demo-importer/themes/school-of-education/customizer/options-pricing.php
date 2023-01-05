<?php

add_action( 'init' , 'school_of_education_pricing_fields' );
function school_of_education_pricing_fields(){

	Kirki::add_section( 'school_of_education_pricing', array(
	    'title'          => esc_html__( 'Pricing', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_pricing_status',
		'label'       => esc_html__( 'Enable Pricing ?', 'cdi' ),
		'section'     => 'school_of_education_pricing',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_pricing_subtitle',
		'label'       => esc_html__( 'Subtitle', 'cdi' ),
		'section'     => 'school_of_education_pricing',
		'default'     => esc_html__( 'Pricing', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_pricing_subtitle' => [
				'selector'        => '.school-pricing .school-section-title h4',
				'render_callback' => 'school_of_education_get_pricing_subtitle',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'school_of_education_pricing_title',
		'label'       => esc_html__( 'Title', 'cdi' ),
		'section'     => 'school_of_education_pricing',
		'default'     => esc_html__( 'Join the Revolution', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_pricing_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_pricing_title' => [
				'selector'        => '.school-pricing .school-section-title h3',
				'render_callback' => 'school_of_education_get_pricing_title',
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
	            'section'  => 'school_of_education_pricing',
	            'settings' => 'school_of_education_pricing_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_pricing_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_pricing_spacing',
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
		                            'element'       => '.school-pricing'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-pricing'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_pricing_spacing',
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
	                                'element'       => '.school-pricing',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-pricing',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_pricing_spacing',
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
	                                'element'       => '.school-pricing',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-pricing',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
		                        ]
		                    ]
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	$titles = array( 'Starter' , 'Pro Trainer' , 'Learning Center' );
	$price  = array( '99' , '199' , '299' );
	$color  = array( '#333a65' , '#ed7800' , '#F14D5D' );

	for ( $i = 1; $i <= apply_filters( 'school_of_education_pricing_limit', 3 ); $i++ ) { 
		
		Kirki::add_field( 'bizberg', array(
		    'type'        => 'custom',
		    'settings'    => 'school_of_education_pricing_heading_' . $i,
		    'section'     => 'school_of_education_pricing',
		    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Content ' . $i, 'cdi' ) . '</div>',
		    'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
		) );

		Kirki::add_field( 'bizberg', [
			'type'        => 'checkbox',
			'settings'    => 'school_of_education_pricing_content_status_' . $i,
			'label'       => esc_html__( 'Enable ?', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => true,
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_status_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'simple-color',
			'settings'    => 'school_of_education_pricing_content_color_' . $i,
			'label'       => esc_html__( 'Color', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => $color[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_color_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_pricing_content_title_' . $i,
			'label'       => esc_html__( 'Title', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => $titles[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_title_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_pricing_content_currency_' . $i,
			'label'       => esc_html__( 'Currency', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => '$',
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_currency_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_pricing_content_price_' . $i,
			'label'       => esc_html__( 'Price', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => $price[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_price_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_pricing_content_duration_' . $i,
			'label'       => esc_html__( 'Duration', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => 'Month',
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_duration_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'textarea',
			'settings'    => 'school_of_education_pricing_content_description_' . $i,
			'label'       => esc_html__( 'Description', 'cdi' ),
			'description' => esc_html__( 'Press enter for new line', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'sanitize_callback' => 'wp_kses_post',
			'default'     => "Access to one Selected Theme\nLifetime rights to use your theme\nUnlimited domain licence\nOne year premium support\nOne year regular free updates\nExtensive documentation\nOnline chat support\nVideo tutorials",
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_description_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_pricing_content_btn_label_' . $i,
			'label'       => esc_html__( 'Button Label', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => "Choose Plan",
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_btn_label_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'text',
			'settings'    => 'school_of_education_pricing_content_btn_link_' . $i,
			'label'       => esc_html__( 'Button Link', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => "#",
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_btn_link_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'simple-color',
			'settings'    => 'school_of_education_pricing_content_btn_background_' . $i,
			'label'       => esc_html__( 'Button Background', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => $color[$i-1],
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_btn_background_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

		Kirki::add_field( 'bizberg', [
			'type'        => 'simple-color',
			'settings'    => 'school_of_education_pricing_content_btn_text_color_' . $i,
			'label'       => esc_html__( 'Button Text Color', 'cdi' ),
			'section'     => 'school_of_education_pricing',
			'default'     => '#000',
			'active_callback'    => array(
	            array(
	                'setting'  => 'school_of_education_pricing_status',
	                'operator' => '==',
	                'value'    => true
	            ),
	            array(
	                'setting'  => 'school_of_education_pricing_content_status_' . $i,
	                'operator' => '==',
	                'value'    => true
	            ),
	        ),
	        'partial_refresh'    => [
				'school_of_education_pricing_content_btn_text_color_' . $i => [
					'selector'        => '.school-pricing .price-list .row',
					'render_callback' => 'school_of_education_pricing_content',
				],
			],
		] );

	}

}