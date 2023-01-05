<?php

add_action( 'init' , 'econsulting_agency_block_repeater' );
function econsulting_agency_block_repeater(){

	Kirki::add_section( 'econsulting_agency_blocks_repeater', array(
	    'title'          => esc_html__( 'Gutenberg Blocks', 'cdi' ),
	    'panel'          => 'econsulting_agency_homepage_settings',
	    'priority'       => 1,
	) );

	Kirki::add_field( 'bizberg', [
		'type'        => 'advanced-repeater',
		'label'       => esc_html__( 'Gutenberg Blocks', 'cdi' ),
		'section'     => 'econsulting_agency_blocks_repeater',
		'choices' => [
			'row_label' => [
				'value' => esc_html__( 'Page', 'cdi' ),
			],
			'fields' => [
				'page_id' => [
					'type'        => 'select',
					'label'       => esc_html__( 'Pages', 'cdi' ),
					'choices'     => bizberg_get_all_pages()
				],
				'width' => [
					'type'        => 'select',
					'label'       => esc_html__( 'Width', 'cdi' ),
					'default'     => 'box_width',
					'choices'     => [
						'box_width'  => esc_html__( 'Box Width', 'cdi' ),
						'full_width' => esc_html__( 'Full Width', 'cdi' ),
					]
				],
				'location' => [
					'type'        => 'select',
					'label'       => esc_html__( 'Location', 'cdi' ),
					'default'     => '',
					'choices'     => [
						''                         => esc_html__( 'Select One From Below', 'cdi' ),
						'after_banner'             => esc_html__( 'After Banner Section', 'cdi' ),
						'after_achievements'       => esc_html__( 'After Achievements Section', 'cdi' ),
						'after_about_us'           => esc_html__( 'After About Us Section', 'cdi' ),
						'after_our_work'           => esc_html__( 'After Our Work Section', 'cdi' ),
						'after_services'           => esc_html__( 'After Services Section', 'cdi' ),
						'after_video'              => esc_html__( 'After Video Section', 'cdi' ),
						'after_work_process'       => esc_html__( 'After Work Process Section', 'cdi' ),
						'after_testimonials'       => esc_html__( 'After Testimonials Section', 'cdi' ),
						'after_call_to_action'     => esc_html__( 'After Call To Action Section', 'cdi' ),
						'after_faq'                => esc_html__( 'After FAQ Section', 'cdi' ),
						'after_pricing'            => esc_html__( 'After Pricing Section', 'cdi' ),
						'after_partners'           => esc_html__( 'After Partners Section', 'cdi' ),
						'after_contact_us'         => esc_html__( 'After Contact Us Section', 'cdi' ),
						'after_map'                => esc_html__( 'After Map Section', 'cdi' ),
						'after_blog'               => esc_html__( 'After Blog Section', 'cdi' ),
					]
				],
				'spacing_top' => [ 
					'type'        => 'select',
					'label'       => esc_html__( 'Spacing Top', 'cdi' ),
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
					'label'       => esc_html__( 'Spacing Bottom', 'cdi' ),
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
					'label'   => esc_html__( 'Background', 'cdi' ),
					'choices'     => [
						'alpha' => true,
					],
				]
			],
		],
		'settings'     => 'gutenberg_blocks_repeater',
		'default'      => json_encode([])
	] );

}