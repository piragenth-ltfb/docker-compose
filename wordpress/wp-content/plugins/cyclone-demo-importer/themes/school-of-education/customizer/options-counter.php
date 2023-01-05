<?php

add_action( 'init' , 'school_of_education_counter_fields' );
function school_of_education_counter_fields(){

	Kirki::add_section( 'school_of_education_counter', array(
	    'title'          => esc_html__( 'Counter', 'cdi' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'school_of_education_counter_status',
		'label'       => esc_html__( 'Enable Counter ?', 'cdi' ),
		'section'     => 'school_of_education_counter',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'advanced-repeater',
		'label'       => esc_attr__( 'Counter', 'cdi' ),
		'section'     => 'school_of_education_counter',
		'choices' => [
			'limit' => 4,
			'row_label' => [
				'value' => esc_html__( 'Counter', 'cdi' ),
			],
			'fields' => [
				'title' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Title', 'cdi' )
				],
				'number' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Number', 'cdi' )
				],
				'color' => [
					'type'        => 'color',
					'label'       => esc_attr__( 'Color', 'cdi' )
				],
				'prefix' => [
					'type'        => 'text',
					'label'       => esc_attr__( 'Suffix', 'cdi' )
				],
			],
		],
		'settings'    => 'school_of_education_counter_section_homepage',
		'default'     => [
			[
				'title'  => esc_attr__( 'Courses & Videos', 'cdi' ),
				'number' => esc_attr__( '365', 'cdi' ),
				'color'  => '#F14D5D',
				'prefix' => '+',
			],
			[
				'title'  => esc_attr__( 'Expert Teachers', 'cdi' ),
				'number' => esc_attr__( '560', 'cdi' ),
				'color'  => '#EE8C1C',
				'prefix' => '+',
			],
			[
				'title'  => esc_attr__( 'University Students', 'cdi' ),
				'number' => esc_attr__( '3472', 'cdi' ),
				'color'  => '#333a65',
				'prefix' => '+',
			],
			[
				'title'  => esc_attr__( 'Classes Complete', 'cdi' ),
				'number' => esc_attr__( '423', 'cdi' ),
				'color'  => '#55BC7E',
				'prefix' => '+',
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'school_of_education_counter_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'school_of_education_counter_section_homepage' => [
				'selector'        => '.school-counter .container .counter .row',
				'render_callback' => 'school_of_education_counter_container',
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
	            'section'  => 'school_of_education_counter',
	            'settings' => 'school_of_education_counter_spacing',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'school_of_education_counter_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'dimensions' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_counter_spacing',
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
		                            'element'       => '.school-counter'
		                        ],
		                    ],
		                    'js_vars'   => [
		                        [
		                            'element'       => '.school-counter'
		                        ]
		                    ]
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_counter_spacing',
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
	                                'element'       => '.school-counter',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-counter',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
		                        ]
		                    ]
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing', 'cdi' ),
	                        'settings' => 'school_of_education_counter_spacing',
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
	                                'element'       => '.school-counter',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                        'js_vars'   => [
		                        [
		                            'element'       => '.school-counter',
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