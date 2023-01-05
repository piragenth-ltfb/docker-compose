<?php

/**
* Customizer Settings
*/

require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-banner.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-achievements.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-about-us.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-our-works.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-services.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-video.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-work-process.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-testimonials.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-call-to-action.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-questions-answers.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-pricing.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-partners.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-contact-form.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-map.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/customizer/options-blocks.php';

/**
* Homepage Settings
*/

require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/banner.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/achievements.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/about-us.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/our-works.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/services.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/video.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/work-process.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/testimonials.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/call-to-action.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/questions-answers.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/pricing.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/partners.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/contact-form.php';
require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/sections/map.php';

add_action( 'init' , 'econsulting_agency_register_sections' );
function econsulting_agency_register_sections(){

	Kirki::add_panel( 'econsulting_agency_homepage_settings', array(
	    'title'          => esc_html__( 'Homepage Settings', 'cdi' ),
	    'priority'       => 1
	) );

}

add_action( 'bizberg_theme_output_css' , 'econsulting_agency_theme_output_css' );
function econsulting_agency_theme_output_css( $css ){
	$css[] = array(
		'element'       => 'body .econsult-banner-content h1 span',
		'property'      => 'color',
		'value_pattern' => '$'
	);
	return $css;
}

function econsulting_agency_contact_form_7_forms(){

	$args = array(
		'post_type' => 'wpcf7_contact_form', 
		'posts_per_page' => -1
	); 

	$cf7Forms = get_posts( $args );
	$data = [];
	$data = [ '1' => 'Select One Form' ];

	if( !empty( $cf7Forms ) ){

		foreach ( $cf7Forms as $key => $value ) {
			
			$id        = absint( $value->ID );
			$title     = sanitize_text_field( $value->post_title );
			$data[$id] = $title;

		}

	}

	return $data;

}

add_action( 'bizberg_homepage_blog_title' , 'econsulting_agency_homepage_blog_title' );
function econsulting_agency_homepage_blog_title(){
	return esc_html__( 'News & Articles', 'cdi' );
}

add_filter( 'econsulting_agency_contact_form_7_id', 'econsulting_agency_get_contact_form_7_id' );
function econsulting_agency_get_contact_form_7_id(){

	$args = array(
		'post_type'      => 'wpcf7_contact_form', 
		'posts_per_page' => 1,
		's'              => 'Contact form 1'
	); 
	$cf7Forms = get_posts( $args );

	if( !empty( $cf7Forms[0]->ID ) && is_array( $cf7Forms ) ){
		return (string) absint( $cf7Forms[0]->ID );
	}

	return '1';

}

/**
* After plugin activation set 'show_on_front' option to 'posts'
*/

register_activation_hook( CDI_PLUGIN_FILE, 'econsulting_agency_activation_actions' );
function econsulting_agency_activation_actions(){
    do_action( 'econsulting_agency_set_default_value_on_activation' );
}

add_action( 'econsulting_agency_set_default_value_on_activation', 'econsulting_agency_default_options' );
function econsulting_agency_default_options(){

	$plugin_activation_status = get_option( 'cyclone_plugin_activate_status', false );

	if( $plugin_activation_status == false ){
    	update_option( 'show_on_front', 'posts' );
    	update_option( 'cyclone_plugin_activate_status', true );
	}

}

add_action( 'wp', 'econsulting_agency_get_page_content' );
function econsulting_agency_get_page_content(){

    $pages = bizberg_get_theme_mod( 'gutenberg_blocks_repeater' );
    $pages = json_decode( urldecode( $pages ), true );

    if( empty( $pages ) || !is_array( $pages ) ){
        return;
    }

    foreach ( $pages as $key => $value ) {
        
        $location       = !empty( $value['location'] ) ? $value['location'] : '';
        $width          = !empty( $value['width'] ) ? $value['width'] : 'box_width';
        $page_id        = !empty( $value['page_id'] ) ? $value['page_id'] : '';
        $spacing_top    = !empty( $value['spacing_top'] ) ? $value['spacing_top'] : '';
        $spacing_bottom = !empty( $value['spacing_bottom'] ) ? $value['spacing_bottom'] : '';
        $background     = !empty( $value['background'] ) ? $value['background'] : '';

        if( !empty( $location ) && !empty( $page_id ) ){

            switch ( $location ) {

                case 'after_banner':
                    $action_name = 'econsulting_agency_after_banner';
                    break;

                case 'after_achievements':
                    $action_name = 'econsulting_agency_after_achievements';
                    break;

                case 'after_about_us':
                    $action_name = 'econsulting_agency_after_about_us';
                    break;

                case 'after_our_work':
                    $action_name = 'econsulting_agency_after_our_work';
                    break;

                case 'after_services':
                    $action_name = 'econsulting_agency_after_services';
                    break;

                case 'after_video':
                    $action_name = 'econsulting_agency_after_video';
                    break;

                case 'after_work_process':
                    $action_name = 'econsulting_agency_after_work_process';
                    break;

                case 'after_testimonials':
                    $action_name = 'econsulting_agency_after_testimonials';
                    break;

                case 'after_call_to_action':
                    $action_name = 'econsulting_agency_after_call_to_action';
                    break;

                case 'after_faq':
                    $action_name = 'econsulting_agency_after_questions_answers';
                    break;

                case 'after_pricing':
                    $action_name = 'econsulting_agency_after_pricing';
                    break;

                case 'after_partners':
                    $action_name = 'econsulting_agency_after_partners';
                    break;

               	case 'after_contact_us':
                    $action_name = 'econsulting_agency_after_contact_form';
                    break;

                case 'after_map':
                    $action_name = 'econsulting_agency_after_map';
                    break;

                case 'after_blog':
                    $action_name = 'bizberg_after_homepage_blog';
                    break;
                
                default:
                    # code...
                    break;
            }

            add_action( $action_name , function() use ( $page_id, $action_name, $width, $spacing_top, $spacing_bottom, $background ) {

                $args = array(
                    'post_type' => 'page',
                    'posts_per_page' => 1,
                    'post__in' => array( $page_id )
                );

                $block_query = new WP_Query( $args );

                if( $block_query->have_posts() ):

                    while( $block_query->have_posts() ): $block_query->the_post();

                    	echo '<div class="custom_pages" style="padding-top:' . esc_attr( $spacing_top ) . ';padding-bottom:' . esc_attr( $spacing_bottom ) . ';background-color:' . esc_attr( $background ) . '">';
                        if( $width == 'box_width' ){
                            echo '<div class="container">';
                            the_content();
                            echo '</div>';
                        } else {
                            the_content();
                        }
                        echo '</div>';

                    endwhile;

                endif;

                wp_reset_postdata();

            });

        }

    }

}

add_filter( 'bizberg_top_header_status', 'econsulting_agency_top_header_status' );
function econsulting_agency_top_header_status(){
    return false;
}

add_filter( 'bizberg_footer_social_links', 'econsulting_agency_footer_social_links2', 999 );
function econsulting_agency_footer_social_links2(){
    return [
        [
            'icon' => 'fab fa-facebook-f',
            'link'  => '#',
            'target' => true
        ],
        [
            'icon' => 'fab fa-twitter',
            'link'  => '#',
            'target' => true
        ],
        [
            'icon' => 'fab fa-instagram',
            'link'  => '#',
            'target' => true
        ],
        [
            'icon' => 'fab fa-youtube',
            'link'  => '#',
            'target' => true
        ],
    ];
}