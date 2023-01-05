<?php

add_action( 'init' , 'school_of_education_block_repeater' );
function school_of_education_block_repeater(){

	Kirki::add_section( 'school_of_education_blocks_repeater', array(
	    'title'          => esc_html__( 'Gutenberg Blocks', 'school-of-education' ),
	    'panel'          => 'school_of_education_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'advanced-repeater',
		'label'       => esc_html__( 'Gutenberg Blocks', 'school-of-education' ),
		'section'     => 'school_of_education_blocks_repeater',
		'choices' => [
			'row_label' => [
				'value' => esc_html__( 'Page', 'school-of-education' ),
			],
			'fields' => [
				'page_id' => [
					'type'        => 'select',
					'label'       => esc_html__( 'Pages', 'school-of-education' ),
					'choices'     => bizberg_get_all_pages()
				],
				'width' => [
					'type'        => 'select',
					'label'       => esc_html__( 'Width', 'school-of-education' ),
					'default'     => 'box_width',
					'choices'     => [
						'box_width'  => esc_html__( 'Box Width', 'school-of-education' ),
						'full_width' => esc_html__( 'Full Width', 'school-of-education' ),
					]
				],
				'location' => [
					'type'        => 'select',
					'label'       => esc_html__( 'Location', 'school-of-education' ),
					'default'     => '',
					'choices'     => [
						''                         => esc_html__( 'Select One From Below', 'school-of-education' ),
						'after_banner'             => esc_html__( 'After Banner Section', 'school-of-education' ),
						'after_services'           => esc_html__( 'After Services Section', 'school-of-education' ),
						'after_counter'            => esc_html__( 'After Counter Section', 'school-of-education' ),
						'after_about_us'           => esc_html__( 'After About Us Section', 'school-of-education' ),
						'after_courses_categories' => esc_html__( 'After Courses Categories Section', 'school-of-education' ),
						'after_why_choose_us'      => esc_html__( 'After Why Choose Us Section', 'school-of-education' ),
						'after_courses'            => esc_html__( 'After Courses Detail Section', 'school-of-education' ),
						'after_pricing'            => esc_html__( 'After Pricing Section', 'school-of-education' ),
						'after_teams'              => esc_html__( 'After Teams Section', 'school-of-education' ),
						'after_testimonials'       => esc_html__( 'After Testimonials Section', 'school-of-education' ),
						'after_call_to_action'     => esc_html__( 'After Call To Action Section', 'school-of-education' ),
						'after_blog'               => esc_html__( 'After Blog Section', 'school-of-education' ),
						'after_map'                => esc_html__( 'After Map Section', 'school-of-education' )
					]
				],
				'spacing_top' => [ 
					'type'        => 'select',
					'label'       => esc_html__( 'Spacing Top', 'school-of-education' ),
					'default'     => '70px',
					'choices'     => [
						'0px'   => '0px',
						'10px'  => '10px',
						'20px'  => '20px',
						'30px'  => '30px',
						'40px'  => '40px',
						'50px'  => '50px',
						'60px'  => '60px',
						'70px'  => '70px',
						'80px'  => '80px',
						'90px'  => '90px',
						'100px' => '100px'
					]
				],
				'spacing_bottom' => [ 
					'type'        => 'select',
					'label'       => esc_html__( 'Spacing Bottom', 'school-of-education' ),
					'default'     => '0px',
					'choices'     => [
						'0px'   => '0px',
						'10px'  => '10px',
						'20px'  => '20px',
						'30px'  => '30px',
						'40px'  => '40px',
						'50px'  => '50px',
						'60px'  => '60px',
						'70px'  => '70px',
						'80px'  => '80px',
						'90px'  => '90px',
						'100px' => '100px'
					]
				],
				'background' => [
					'type'    => 'color',
					'label'   => esc_html__( 'Background', 'school-of-education' ),
					'choices'     => [
						'alpha' => true,
					],
				]
			],
		],
		'settings'     => 'gutenberg_blocks_repeater',
		'default'      => []
	] );

}