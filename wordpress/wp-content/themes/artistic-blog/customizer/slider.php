<?php

add_action( 'init' , 'artistic_blog_slider_fields' );
function artistic_blog_slider_fields(){

	Kirki::add_section( 'artistic_blog_slider', array(
	    'title'          => esc_html__( 'Slider', 'artistic-blog' ),
	    'section'        => 'homepage',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'checkbox',
		'settings'    => 'artistic_blog_slider_post_status',
		'label'       => esc_html__( 'Enable Slider', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => true,
	] );

	Kirki::add_field( 'bizberg', array(
        'type'        => 'select',
        'settings'    => 'artistic_blog_slider_category',
        'label'       => esc_html__( 'Select Post Category', 'artistic-blog' ),
        'section'     => 'artistic_blog_slider',
        'multiple'    => 99,
        'choices'     => bizberg_get_post_categories(),
        'partial_refresh'    => [
			'artistic_blog_slider_category' => [
				'selector'        => '.artistic_blog_banner .container',
				'render_callback' => 'artistic_blog_homepage_slider'
			]
		],
        'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
    	'description' => sprintf(
			esc_html__( 
				'In free version, only 2 slides will be displayed. %s', 
				'artistic-blog' 
			),
			'<a target="_blank" href="' . esc_url( bizberg_get_pro_link() ) . '">' . esc_html__( 'Upgrade to PRO', 'artistic-blog' ) . '</a>'
		),
    ) );

    Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'ab_slider_color_options',
	    'section'     => 'artistic_blog_slider',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Color Options', 'artistic-blog' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_background_gradient_1',
		'label'       => __( 'Background Gradient Color 1', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#be3026',
		'transport'   => 'postMessage',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_background_gradient_2',
		'label'       => __( 'Background Gradient Color 2', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#69077a',
		'transport'   => 'postMessage',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_content_background',
		'label'       => __( 'Content Background Color', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#181d20',
		'transport'   => 'auto',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
		'output' => array(
            array(
                'element'       => '.artistic_blog_banner .swiper-content',
                'property'      => 'background-color'
            ),
            array(
                'element'       => '.blog-banner .swiper-content .content-category a::after',
                'property'      => 'border-left-color'
            )
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_no_image_background',
		'label'       => __( 'No Image Background Color', 'artistic-blog' ),
		'description' => __( 'This background color will be visible if the post has no featured image.', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#e91e63',
		'transport'   => 'auto',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
		'output' => array(
            array(
                'element'       => '.blog-banner .slide-inner .slide-image',
                'property'      => 'background-color'
            )
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_category_background',
		'label'       => __( 'Category Background Color', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#dd3333',
		'transport'   => 'auto',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
		'output' => array(
            array(
                'element'       => '.blog-banner .swiper-content .content-category a',
                'property'      => 'background'
            ),
            array(
                'element'       => '.blog-banner .swiper-content .content-category a::before',
                'property'      => 'border-left-color'
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_arrow_background_normal',
		'label'       => __( 'Arrow Background Color', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#14181b',
		'transport'   => 'auto',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
		'output' => array(
            array(
                'element'       => '.blog-banner .swiper-button-prev, .blog-banner .swiper-button-next',
                'property'      => 'background'
            )
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'slider_arrow_background_hover',
		'label'       => __( 'Arrow Background Color ( Hover )', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#dd3333',
		'transport'   => 'auto',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
		'output' => array(
            array(
                'element'       => '.blog-banner .swiper-button-prev:hover, .blog-banner .swiper-button-next:hover',
                'property'      => 'background'
            )
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'ab_slider_title_options',
	    'section'     => 'artistic_blog_slider',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Title Options', 'artistic-blog' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	if( function_exists( 'bizberg_kirki_dtm_options' ) ){

	    bizberg_kirki_dtm_options( 
	        array(
	            'display' => array(
	                'desktop' => 'desktop',
	                'tablet'  => 'tablet',
	                'mobile'  => 'mobile'
	            ),
	            'field_id' => 'bizberg',
	            'section'  => 'artistic_blog_slider',
	            'settings' => 'artistic_blog_title_options',
	            'global_active_callback'    => array(
	              	array(
	                	'setting'  => 'artistic_blog_slider_post_status',
	                	'operator' => '==',
	                	'value'    => true
	            	),
	          	),
	            'fields'   => array(
	                'typography' => array(
	                    'desktop' => array(
	                        'label' => esc_html__( 'Title Font Settings', 'artistic-blog' ),
	                        'settings' => 'artistic_blog_title_options',
	                        'default'     => [
				              	'font-size'      => '64px',
				              	'line-height'    => '1.2',
				              	'letter-spacing' => '0',
				            ],
	                        'transport' => 'auto',
	                        'output'      => [
				              	[
				                	'element' => '.blog-banner .swiper-content h2',
				                	'suffix'  => ' !important'
				              	],
				            ],
	                    ),
	                    'tablet' => array(
	                        'label' => esc_html__( 'Title Font Settings', 'artistic-blog' ),
	                        'settings' => 'artistic_blog_title_options',
	                        'default'     => [
				              	'font-size'      => '50px',
				              	'line-height'    => '1.2',
				              	'letter-spacing' => '0',
				            ],
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'media_query' => '@media (min-width: 481px) and (max-width: 1024px)',
	                                'element'     => '.blog-banner .swiper-content h2',
				                	'suffix'      => ' !important'
	                            )
	                        ),
	                    ),
	                    'mobile' => array(
	                        'label' => esc_html__( 'Title Font Settings', 'artistic-blog' ),
	                        'settings' => 'artistic_blog_title_options',
	                        'default'     => [
				              	'font-size'      => '35px',
				              	'line-height'    => '1.2',
				              	'letter-spacing' => '0'
				            ],
	                        'transport' => 'auto',
	                        'output' => array(
	                            array(
	                                'media_query' => '@media (min-width: 320px) and (max-width: 480px)',
	                                'element'     => '.blog-banner .swiper-content h2',
				                	'suffix'      => ' !important'
	                            )
	                        ),
	                    )
	                ),
	            )
	            
	        ) 
	    );

	}

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'title_border_gradient_1',
		'label'       => __( 'Title Border Gradient Color 1', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#be3026',
		'transport'   => 'postMessage',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'color',
		'settings'    => 'title_border_gradient_2',
		'label'       => __( 'Title Border Gradient Color 2', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => '#69077a',
		'transport'   => 'postMessage',
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', array(
	    'type'        => 'custom',
	    'settings'    => 'ab_slider_other_options',
	    'section'     => 'artistic_blog_slider',
	    'default'     => '<div class="bizberg_customizer_custom_heading">' . esc_html__( 'Other Options', 'artistic-blog' ) . '</div>',
	    'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'select',
		'settings'    => 'ab_slider_meta',
		'label'       => esc_html__( 'Meta', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'default'     => array( 'date', 'comment' , 'read_time' ),
		'multiple'    => 3,
		'transport'   => 'postMessage',
		'choices'     => [
			'date'      => esc_html__( 'Date', 'artistic-blog' ),
			'comment'   => esc_html__( 'Comment', 'artistic-blog' ),
			'read_time' => esc_html__( 'Read Time', 'artistic-blog' )
		],
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
        'partial_refresh'    => [
			'ab_slider_meta' => [
				'selector'        => '.artistic_blog_banner .container',
				'render_callback' => 'artistic_blog_homepage_slider'
			]
		],
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'slider_autoplay_delay',
		'label'       => esc_html__( 'Slider Autoplay Delay', 'artistic-blog' ),
		'description' => esc_html__( 'Note : 1000 = 1 sec', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'transport'   => 'postMessage',
		'default'     => 10000,
		'choices'     => [
			'min'  => 3000,
			'max'  => 15000,
			'step' => 500,
		],
		'partial_refresh'    => [
			'slider_autoplay_delay' => [
				'selector'        => '.artistic_blog_banner .container',
				'render_callback' => 'artistic_blog_homepage_slider'
			]
		],
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

	Kirki::add_field( 'bizberg', [
		'type'        => 'slider',
		'settings'    => 'ab_slider_speed',
		'label'       => esc_html__( 'Slider Speed', 'artistic-blog' ),
		'description' => esc_html__( 'Note : 1000 = 1 sec', 'artistic-blog' ),
		'section'     => 'artistic_blog_slider',
		'transport'   => 'postMessage',
		'default'     => 500,
		'choices'     => [
			'min'  => 500,
			'max'  => 10000,
			'step' => 100,
		],
		'partial_refresh'    => [
			'ab_slider_speed' => [
				'selector'        => '.artistic_blog_banner .container',
				'render_callback' => 'artistic_blog_homepage_slider'
			]
		],
		'active_callback'    => array(
            array(
                'setting'  => 'artistic_blog_slider_post_status',
                'operator' => '==',
                'value'    => true
            ),
        ),
	] );

}