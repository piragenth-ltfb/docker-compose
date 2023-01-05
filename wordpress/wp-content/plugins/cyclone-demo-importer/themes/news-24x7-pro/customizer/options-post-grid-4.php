<?php 

add_filter( 'news_24x7_repeater_news_choices', function( $choices ){
	$choices[7] = esc_html__( 'Post Grid 4 ( PRO )', 'cdi' );
	return $choices;
});

add_filter( 'news_24x7_repeater_news_fields', function( $fields ){

	$fields['layout_7_title'] = [
        'type'        => 'text',
        'label'       => esc_html__( 'Title', 'cdi' ),
        'default'     => esc_html__( "What’s New", 'cdi' ),
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_layout'] = [
        'type'        => 'select',
        'label'       => esc_html__( 'Layout', 'cdi' ),
        'default'     => '1',
        'choices'     => [
            '1'     => '1',
            '2'     => '2',
            '3'     => '3',
            '4'     => '4',
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_categories'] = [
        'type'        => 'select',
        'label'       => esc_html__( 'Post Categories', 'cdi' ),
        'choices'     => bizberg_get_post_categories(),
        'multiple'    => 99,
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_limit'] = [
        'type'        => 'select',
        'label'       => esc_html__( 'Post Limit', 'cdi' ),
        'default'     => 6,
        'choices'     => [
        	2 => 2,
        	3 => 3,
        	4 => 4,
        	5 => 5,
        	6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            11 => 11,
            12 => 12,
            13 => 13,
            14 => 14,
            15 => 15,
            16 => 16,
            17 => 17,
            18 => 18,
            19 => 19,
            20 => 20,
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_title_line_color'] = [
        'type'        => 'color',
        'label'       => esc_html__( 'Title Horizontal Line Color', 'cdi' ),
        'default'     => '#e5e5e5',
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_bg_color'] = [
        'type'        => 'color',
        'label'       => esc_html__( 'Background Color', 'cdi' ),
        'default'     => '#f7f7f7',
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_spacing_top'] = [
        'type'        => 'number',
        'label'       => esc_html__( 'Spacing Top', 'cdi' ),
        'default'     => '40',
        'choices'     => [
            'min'  => 0,
            'max'  => 100,
            'step' => 5,
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_spacing_bottom'] = [
        'type'        => 'number',
        'label'       => esc_html__( 'Spacing Bottom', 'cdi' ),
        'default'     => '40',
        'choices'     => [
            'min'  => 0,
            'max'  => 100,
            'step' => 5,
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    $fields['layout_7_image_height'] = [
        'type'        => 'number',
        'label'       => esc_html__( 'Height', 'cdi' ),
        'default'     => '350',
        'choices'     => [
            'min'  => 0,
            'max'  => 500,
            'step' => 5,
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '7'
            ]
        ],
    ];

    return $fields;

});