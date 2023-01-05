<?php

add_action( 'init' , 'econsulting_agency_map_fields' );
function econsulting_agency_map_fields(){

	Kirki::add_section( 'econsulting_agency_map', array(
	    'title'          => esc_html__( 'Map', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'econsulting_agency_map_status',
		'label'       => esc_html__( 'Enable Map ?', 'cdi' ),
		'section'     => 'econsulting_agency_map',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'text',
		'settings'    => 'econsulting_agency_map_location',
		'label'       => esc_html__( 'Location', 'cdi' ),
		'section'     => 'econsulting_agency_map',
		'default'     => esc_html__( 'Automattic', 'cdi' ),
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_map_location' => [
				'selector'        => '.econsult-map .map',
				'render_callback' => 'econsulting_agency_map_get_map_iframe',
			],
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'econsulting_agency_map_zoom',
		'label'       => esc_html__( 'Zoom In/Out', 'cdi' ),
		'section'     => 'econsulting_agency_map',
		'default'     => 14,
		'choices'     => [
			'min'  => 10,
			'max'  => 20,
			'step' => 1
		],
		'active_callback'    => array(
            array(
                'setting'  => 'econsulting_agency_map_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'econsulting_agency_map_zoom' => [
				'selector'        => '.econsult-map .map',
				'render_callback' => 'econsulting_agency_map_get_map_iframe',
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
	            'section'  => 'econsulting_agency_map',
	            'settings' => 'econsulting_agency_map_spacing_top',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_map_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'number' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing Top', 'cdi' ),
	                        'settings' => 'econsulting_agency_map_spacing_top',
	                        'default'     => -80,
	                        'transport' => 'auto',
		                    'output'    => [
		                        [
		                            'element'       => '.econsult-map .map',
		                            'property'      => 'margin-top',
		                            'value_pattern' => '$px'
		                        ],
		                    ],
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing Top', 'cdi' ),
	                        'settings' => 'econsulting_agency_map_spacing_top',
	                        'default'     => -80,
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-map .map',
	                                'property'      => 'margin-top',
	                                'value_pattern' => '$px',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing Top', 'cdi' ),
	                        'settings' => 'econsulting_agency_map_spacing_top',
	                        'default'     => -80,
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-map .map',
	                                'property'      => 'margin-top',
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

	if( function_exists( 'bizberg_kirki_dtm_options' ) ){

	    bizberg_kirki_dtm_options( 
	        array(
	            'display' => array(
	                'desktop' => 'desktop',
	                'tablet'  => 'tablet',
	                'mobile'  => 'mobile'
	            ),
	            'field_id' => 'bizberg',
	            'section'  => 'econsulting_agency_map',
	            'settings' => 'econsulting_agency_map_spacing_bottom',
	            'global_active_callback'    => array(
	            	array(
		            	'setting'  => 'econsulting_agency_map_status',
		            	'operator' => '==',
		            	'value'    => true
		        	)
		        ),
	            'fields'   => array(
	                'number' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Spacing Bottom', 'cdi' ),
	                        'settings' => 'econsulting_agency_map_spacing_bottom',
	                        'default'     => 20,
	                        'transport' => 'auto',
		                    'output'    => [
		                        [
		                            'element'       => '.econsult-map',
		                            'property'      => 'padding-bottom',
		                            'value_pattern' => '$px'
		                        ],
		                    ],
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Spacing Bottom', 'cdi' ),
	                        'settings' => 'econsulting_agency_map_spacing_bottom',
	                        'default'     => 0,
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-map',
	                                'property'      => 'padding-bottom',
	                                'value_pattern' => '$px',
	                                'media_query'   => '@media (min-width: 481px) and (max-width: 1024px)'
	                            )
	                        ),
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Spacing Bottom', 'cdi' ),
	                        'settings' => 'econsulting_agency_map_spacing_bottom',
	                        'default'     => 0,
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'element'       => '.econsult-map',
	                                'property'      => 'padding-bottom',
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

}