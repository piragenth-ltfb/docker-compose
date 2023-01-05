<?php 

add_filter( 'news_24x7_repeater_news_choices', function( $choices ){
	$choices[5] = esc_html__( 'Post Grid 3 ( PRO )', 'cdi' );
	return $choices;
});

add_filter( 'news_24x7_repeater_news_fields', function( $fields ){
	
	$fields['layout_5_title'] = [
        'type'        => 'text',
        'label'       => esc_html__( 'Title', 'cdi' ),
        'default'     => esc_html__( 'Latest In Tech', 'cdi' ),
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_categories'] = [
        'type'        => 'select',
        'label'       => esc_html__( 'Post Categories', 'cdi' ),
        'choices'     => bizberg_get_post_categories(),
        'multiple'    => 99,
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_limit'] = [
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
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_columns'] = [
        'type'        => 'select',
        'label'       => esc_html__( 'Columns', 'cdi' ),
        'default'     => '2|4',
        'choices'     => [
            '2|4'     => '2 | 4',
            '3|4'     => '3 | 4',
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_title_line_color'] = [
        'type'        => 'color',
        'label'       => esc_html__( 'Title Horizontal Line Color', 'cdi' ),
        'default'     => '#e5e5e5',
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_bg_color'] = [
        'type'        => 'color',
        'label'       => esc_html__( 'Background Color', 'cdi' ),
        'default'     => '#f7f7f7',
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_thumbnail_status'] = [
        'type'        => 'checkbox',
        'label'       => esc_html__( 'Hide Thumbnail', 'cdi' ),
        'default'     => false,
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_spacing_top'] = [
        'type'        => 'number',
        'label'       => esc_html__( 'Spacing Top', 'cdi' ),
        'default'     => '40',
        'choices'     => [
            'min'  => 0,
            'max'  => 100,
            'step' => 10,
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    $fields['layout_5_spacing_bottom'] = [
        'type'        => 'number',
        'label'       => esc_html__( 'Spacing Bottom', 'cdi' ),
        'default'     => '40',
        'choices'     => [
            'min'  => 0,
            'max'  => 100,
            'step' => 10,
        ],
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '5'
            ]
        ],
    ];

    return $fields;

});