<?php

add_action( 'init' , 'econsulting_agency_call_to_action_fields' );
function econsulting_agency_call_to_action_fields(){

	Kirki::add_section( 'econsulting_agency_call_to_action', array(
	    'title'          => esc_html__( 'Call To Action', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_call_to_action_status',
		'label'       => esc_html__( 'Enable Call to Action ?', 'cdi' ),
		'section'     => 'econsulting_agency_call_to_action',
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
	            'section'  => 'econsulting_agency_call_to_action',
	            'settings' => 'econsulting_agency_call_to_action_spacing_bottom',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_call_to_action_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'number' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing Bottom', 'cdi' ),
	                        'settings' => 'econsulting_agency_call_to_action_spacing_bottom',
	                        'default'     => -80,
	                        'transport' => 'auto',
		                    'output'    => [
		                        [
		                            'element'       => '.econsult-callto',
		                            'property'      => 'margin-bottom',
		                            'value_pattern' => '$px'
		                        ],
		                    ],
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing Bottom', 'cdi' ),
	                        'settings' => 'econsulting_agency_call_to_action_spacing_bottom',
	                        'default'     => -80,
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-callto',
	                                'property'      => 'margin-bottom',
	                                'value_pattern' => '$px',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing Bottom', 'cdi' ),
	                        'settings' => 'econsulting_agency_call_to_action_spacing_bottom',
	                        'default'     => -80,
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-callto',
	                                'property'      => 'margin-bottom',
	                                'value_pattern' => '$px',
	                                'media_query'   => '@media (min-width: 320px) and (max-width: 480px)'
	                            )
	                        ),
	                    )
	                ),
	            )
	            
	        ) 
	    );
	}

	Kirki::add_field( 'bizberg', [
		'type'        => 'simple-color',
		'settings'    => 'econsulting_agency_call_to_action_background_color',
		'label'       => esc_html__( 'Background', 'cdi' ),
		'section'     => 'econsulting_agency_call_to_action',
		'default'     => '#ff4a17',
		'choices'     => [
			'alpha' => true,
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'transport'   => 'auto',
        'output' => array(
			array(
				'element'       => '.econsult-callto-main',
				'property'      => 'background-color'
			)
		),
	] );

	Kirki::add_field( 'bizberg', array(
		'type'              => 'font-awesome-6',
		'label'             => esc_html__( 'Icon', 'cdi' ),
		'section'           => 'econsulting_agency_call_to_action',
		'settings'          => 'econsulting_agency_call_to_action_icon',
		'choices' => array(
			'height'       => '200px',
			'search_icons' => true
		),
		'default' => 'fas fa-headset',
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_call_to_action_icon' => [
				'selector'        => '.econsult-callto .econsult-callto-icon',
				'render_callback' => 'econsulting_agency_call_to_action_get_icon'
			],
		],
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_call_to_action_title',
		'label'    => esc_html__( 'Title', 'cdi' ),
		'section'  => 'econsulting_agency_call_to_action',
		'default'  => esc_html__( 'WE’RE DELIVERING THE BEST CUSTOMER EXPERIENCE', 'cdi' ),
		'partial_refresh'    => [
			'econsulting_agency_call_to_action_title' => [
				'selector'        => '.econsult-callto .econsult-callto-main h2',
				'render_callback' => 'econsulting_agency_call_to_action_get_title'
			],
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'econsulting_agency_call_to_action_button_settings',
	    'section'     => 'econsulting_agency_call_to_action',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Button Settings', 'cdi' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_call_to_action_button_text',
		'label'    => esc_html__( 'Label', 'cdi' ),
		'section'  => 'econsulting_agency_call_to_action',
		'default'  => esc_html__( 'Get Started', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_call_to_action_button_text' => [
				'selector'        => '.econsult-callto .button',
				'render_callback' => 'econsulting_agency_call_to_action_get_button'
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'     => 'text',
		'settings' => 'econsulting_agency_call_to_action_button_link',
		'label'    => esc_html__( 'Link', 'cdi' ),
		'section'  => 'econsulting_agency_call_to_action',
		'default'  => esc_html__( '#', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_call_to_action_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_call_to_action_button_link' => [
				'selector'        => '.econsult-callto .button',
				'render_callback' => 'econsulting_agency_call_to_action_get_button'
			],
		],
	] );

}