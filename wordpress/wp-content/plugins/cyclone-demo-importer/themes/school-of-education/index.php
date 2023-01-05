<?php

/**
* Customizer Settings
*/

require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-banner.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-services.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-counter.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-about-us.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-course-categories.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-why-choose-us.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-our-courses.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-pricing.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-team.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-testimonials.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-call-to-action.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-map.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/customizer/options-blocks.php';

/**
* Homepage Settings
*/

require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/banner.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/services.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/counter.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/about-us.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/course-categories.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/why-choose-us.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/our-courses.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/pricing.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/team.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/testimonials.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/call-to-action.php';
require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/sections/map.php';

add_action( 'init' , 'school_of_education_register_sections' );
function school_of_education_register_sections(){

	Kirki::add_panel( 'school_of_education_homepage_settings', array(
	    'title'          => esc_html__( 'Homepage Settings', 'cdi' ),
	    'priority'       => 1
	) );

}

add_filter('body_class', 'school_of_education_body_class');
function school_of_education_body_class($classes) {

	if( is_home() ){
		$classes[] = 'school_of_education_homepage';	
	}    
    return $classes;

}

add_filter( 'bizberg_homepage_blog_title', function(){
	return 'Latest Blogs';
});

add_filter( 'bizberg_homepage_title_font_size_desktop', function(){
	return '31.25';
});

add_filter( 'bizberg_homepage_title_font_size_tablet', function(){
	return '29.3';
});

add_filter( 'bizberg_homepage_title_font_size_mobile', function(){
	return '27.34';
});

add_filter( 'bizberg_homepage_blog_title_font_weight', function(){
	return '600';
});

add_filter( 'bizberg_homepage_latest_posts_limit', function(){
	return '3';
});

add_filter( 'bizberg_homepage_top_bottom_spacing_desktop', function(){
	return [
        'padding-top'    => '40',
        'padding-bottom' => '30'
    ];
});

add_filter( 'bizberg_homepage_top_bottom_spacing_tablet', function(){
	return [
        'padding-top'    => '40',
        'padding-bottom' => '30'
    ];
});

add_filter( 'bizberg_homepage_top_bottom_spacing_mobile', function(){
	return [
        'padding-top'    => '40',
        'padding-bottom' => '20'
    ];
});

add_action( 'wp', 'school_of_education_get_page_content' );
function school_of_education_get_page_content(){

    $pages = bizberg_get_theme_mod( 'gutenberg_blocks_repeater' );
    $pages = is_array( $pages ) ? $pages : json_decode( urldecode( $pages ), true );

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
                    $action_name = 'school_of_education_after_banner';
                    break;

                case 'after_services':
                    $action_name = 'school_of_education_after_services';
                    break;

                case 'after_counter':
                    $action_name = 'school_of_education_after_counter';
                    break;

                case 'after_about_us':
                    $action_name = 'school_of_education_after_about_us';
                    break;

                case 'after_courses_categories':
                    $action_name = 'school_of_education_after_courses_categories';
                    break;

                case 'after_why_choose_us':
                    $action_name = 'school_of_education_after_why_choose_us';
                    break;

                case 'after_courses':
                    $action_name = 'school_of_education_after_courses';
                    break;

                case 'after_pricing':
                    $action_name = 'school_of_education_after_pricing';
                    break;

                case 'after_teams':
                    $action_name = 'school_of_education_after_teams';
                    break;

                case 'after_testimonials':
                    $action_name = 'school_of_education_after_testimonials';
                    break;

                case 'after_call_to_action':
                    $action_name = 'school_of_education_after_call_to_action';
                    break;

                case 'after_map':
                    $action_name = 'school_of_education_after_map';
                    break;

               	case 'after_blog':
                    $action_name = 'school_of_education_after_blog';
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

/**
* After plugin activation set 'show_on_front' option to 'posts'
*/

register_activation_hook( CDI_PLUGIN_FILE, 'school_of_education_activation_actions' );
function school_of_education_activation_actions(){
    do_action( 'school_of_education_set_default_value_on_activation' );
}

add_action( 'school_of_education_set_default_value_on_activation', 'school_of_education_default_options' );
function school_of_education_default_options(){

    $plugin_activation_status = get_option( 'school_of_education_plugin_activate_status', false );

    if( $plugin_activation_status == false ){
        update_option( 'show_on_front', 'posts' );
        update_option( 'school_of_education_plugin_activate_status', true );
    }

}

add_filter( 'bizberg_top_header_status', 'school_of_education_top_header_status' );
function school_of_education_top_header_status(){
    return false;
}

add_filter( 'bizberg_footer_social_links', 'school_of_education_footer_social_links2', 999 );
function school_of_education_footer_social_links2(){
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