<?php 

add_filter( 'news_24x7_repeater_news_choices', function( $choices ){
	$choices[6] = esc_html__( 'Page ( PRO )', 'cdi' );
	return $choices;
});

add_filter( 'news_24x7_repeater_news_fields', function( $fields ){

	$fields['layout_6_page'] = [
        'type'        => 'select',
        'label'       => esc_html__( 'Page', 'cdi' ),
        'choices'     => bizberg_get_all_pages(),
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '6'
            ]
        ],
    ];

    $fields['layout_6_bg_color'] = [
        'type'        => 'color',
        'label'       => esc_html__( 'Background Color', 'cdi' ),
        'default'     => '#f7f7f7',
        'active_callback' => [
            [
                'setting'  => 'layout',
                'operator' => '==',
                'value'    => '6'
            ]
        ],
    ];

    $fields['layout_6_spacing_top'] = [
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
                'value'    => '6'
            ]
        ],
    ];

    $fields['layout_6_spacing_bottom'] = [
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
                'value'    => '6'
            ]
        ],
    ];

    return $fields;

});