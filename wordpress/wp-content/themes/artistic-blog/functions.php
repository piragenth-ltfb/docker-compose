<?php

require get_stylesheet_directory() . '/sections/homepage-slider.php';
require get_stylesheet_directory() . '/sections/homepage-featured-posts.php';
require get_stylesheet_directory() . '/customizer/slider.php';
require get_stylesheet_directory() . '/customizer/blog-settings.php';

add_action( 'wp_enqueue_scripts', 'artistic_blog_chld_thm_parent_css' );
function artistic_blog_chld_thm_parent_css() {

    $artistic_blog_theme = wp_get_theme();
    $theme_version = $artistic_blog_theme->get( 'Version' );

    wp_enqueue_style( 
    	'artistic_blog_chld_css', 
    	trailingslashit( get_template_directory_uri() ) . 'style.css', 
    	array( 
    		'bootstrap',
    		'font-awesome-5',
    		'bizberg-main',
    		'bizberg-component',
    		'bizberg-style2',
    		'bizberg-responsive' 
    	),
        $theme_version
    );

    wp_enqueue_script( 'artistic_blog_script', get_stylesheet_directory_uri() . '/script.js', array('jquery'), $theme_version );
    
}

add_action( 'customize_preview_init', 'artistic_blog_customize_enqueue' );
function artistic_blog_customize_enqueue() {
    $artistic_blog_theme = wp_get_theme();
    $theme_version = $artistic_blog_theme->get( 'Version' );
    wp_enqueue_script( 'artistic_blog_customizer_js', get_stylesheet_directory_uri() . '/customizer.js' ,array('jquery') ,$theme_version ,false );
}


add_action( 'customize_register', 'artistic_blog_customizer_options', 100 );
function artistic_blog_customizer_options( $wp_customize ){

    /**
    * Remove sections/panels/control of parent theme
    */
    
    $wp_customize->remove_section("transparent_header");
    $wp_customize->remove_section("progress_bar");

    $wp_customize->remove_control("header_menu_color_hover_sticky_menu");
    $wp_customize->remove_control("header_menu_separator_sticky_menu");
    $wp_customize->remove_control("header_menu_text_color_sticky_menu");
    $wp_customize->remove_control("header_navbar_background_2_sticky_menu");
    $wp_customize->remove_control("header_navbar_background_1_sticky_menu");
    $wp_customize->remove_control("header_site_tagline_color_sticky_menu");
    $wp_customize->remove_control("header_site_title_color_sticky_menu");
    $wp_customize->remove_control("header_sticky_menu_options_heading");
    $wp_customize->remove_control("header_menu_child_menu_background_sticky_menu");
    $wp_customize->remove_control("header_menu_child_menu_border_sticky_menu");
    $wp_customize->remove_control("header_menu_child_menu_text_color_sticky_menu");
    $wp_customize->remove_control("header_button_color_sticky_menu");
    $wp_customize->remove_control("header_button_color_hover_sticky_menu");
    $wp_customize->remove_control("header_button_border_color_sticky_menu");
    $wp_customize->remove_control("header_menu_slide_in_animation");

}

add_filter( 'bizberg_header_menu_background_mobile', 'artistic_blog_header_menu_background_mobile' );
function artistic_blog_header_menu_background_mobile(){
    return '#14181b';
}

add_filter( 'bizberg_header_menu_background_hover_mobile', 'artistic_blog_header_menu_background_hover_mobile' );
function artistic_blog_header_menu_background_hover_mobile(){
    return '#181d20';
}

add_filter( 'bizberg_header_menu_font_mobile', 'artistic_blog_header_menu_font_mobile' );
function artistic_blog_header_menu_font_mobile(){
    return '#fff';
}

add_filter( 'bizberg_sidebar_widget_heading_font_size_status', 'artistic_blog_sidebar_widget_heading_font_size_status' );
function artistic_blog_sidebar_widget_heading_font_size_status(){
    return true;
}

add_filter( 'bizberg_number_setting_desktop_sidebar_widget_heading_font_sizes', 'artistic_blog_number_setting_desktop_sidebar_widget_heading_font_sizes' );
function artistic_blog_number_setting_desktop_sidebar_widget_heading_font_sizes(){
    return 32.81;
}

add_filter( 'bizberg_number_setting_tablet_sidebar_widget_heading_font_sizes', 'artistic_blog_number_setting_tablet_sidebar_widget_heading_font_sizes' );
function artistic_blog_number_setting_tablet_sidebar_widget_heading_font_sizes(){
    return 29.69;
}

add_filter( 'bizberg_number_setting_mobile_sidebar_widget_heading_font_sizes', 'artistic_blog_number_setting_mobile_sidebar_widget_heading_font_sizes' );
function artistic_blog_number_setting_mobile_sidebar_widget_heading_font_sizes(){
    return 23.44;
}

add_filter( 'bizberg_top_header_status', 'artistic_blog_top_header_status' );
function artistic_blog_top_header_status(){
    return false;
}

add_filter( 'bizberg_sticky_sidebar_margin_top_status', 'artistic_blog_sticky_sidebar_margin_top_status' );
function artistic_blog_sticky_sidebar_margin_top_status(){
    return 30;
}

add_filter( 'bizberg_header_2_spacing', 'artistic_blog_header_2_spacing' );
function artistic_blog_header_2_spacing(){
    return [
        'padding-top'     => '10px',
        'padding-bottom'  => '30px'
    ];
}

add_filter( 'bizberg_background_color_1', 'artistic_blog_top_bar_background_1' );
add_filter( 'bizberg_background_color_2', 'artistic_blog_top_bar_background_1' );
function artistic_blog_top_bar_background_1(){
    return '#181d20';
}

add_filter( 'bizberg_top_bar_border_bottom_color', 'artistic_blog_top_bar_border_bottom_color' );
function artistic_blog_top_bar_border_bottom_color(){
    return '#2f2f2f';
}

add_filter( 'bizberg_sticky_header_status', 'artistic_blog_sticky_header_status' );
function artistic_blog_sticky_header_status(){
    return 'false';
}

add_filter( 'bizberg_homepage_blog_title_font_weight', 'artistic_blog_homepage_blog_title_font_weight' );
function artistic_blog_homepage_blog_title_font_weight(){
    return 300;
}

add_filter( 'bizberg_homepage_title_font_size_desktop', 'artistic_blog_homepage_title_font_size_desktop' );
function artistic_blog_homepage_title_font_size_desktop(){
    return 45;
}

add_filter( 'bizberg_homepage_title_font_size_tablet', 'artistic_blog_homepage_title_font_size_tablet' );
function artistic_blog_homepage_title_font_size_tablet(){
    return 40;
}

add_filter( 'bizberg_homepage_title_font_size_mobile', 'artistic_blog_homepage_title_font_size_mobile' );
function artistic_blog_homepage_title_font_size_mobile(){
    return 35;
}

add_filter( 'bizberg_typography_h4_tablet', 'artistic_blog_typography_h4_tablet' );
function artistic_blog_typography_h4_tablet(){
    return 29.69;
}

add_filter( 'bizberg_typography_h4_mobile', 'artistic_blog_typography_h4_mobile' );
function artistic_blog_typography_h4_mobile(){
    return 23.44;
}

/**
* Change the theme color
*/
add_filter( 'bizberg_theme_color', 'artistic_blog_change_theme_color' );
function artistic_blog_change_theme_color(){
    return '#dd3333';
}

/**
* Change the theme background
*/
add_filter( 'bizberg_body_background_image', 'artistic_blog_body_background_image' );
function artistic_blog_body_background_image(){
    return [
        'background-color'      => '#181d20',
        'background-image'      => '',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'scroll',
    ];
}

add_filter( 'bizberg_theme_text_color', 'artistic_blog_theme_text_color' );
function artistic_blog_theme_text_color(){
    return '#fff';
}

add_filter( 'bizberg_link_color', 'artistic_blog_link_color' );
function artistic_blog_link_color(){
    return '#fff';
}

add_filter( 'bizberg_link_color_hover', 'artistic_blog_link_color_hover' );
function artistic_blog_link_color_hover(){
    return '#dd3333';
}

add_filter( 'bizberg_blog_listing_border', 'artistic_blog_blog_listing_border' );
function artistic_blog_blog_listing_border(){
    return '#14181b';
}

add_filter( 'bizberg_blog_listing_background', 'artistic_blog_blog_listing_background' );
function artistic_blog_blog_listing_background(){
    return '#14181b';
}

add_filter( 'bizberg_blog_listing_box_shadow', 'artistic_blog_blog_listing_box_shadow' );
function artistic_blog_blog_listing_box_shadow(){
    return '#14181b';
}

add_filter( 'bizberg_blog_listing_meta_divider_color', 'artistic_blog_blog_listing_meta_divider_color' );
function artistic_blog_blog_listing_meta_divider_color(){
    return '#262626';
}

add_filter( 'bizberg_blog_listing_pagination_border', 'artistic_blog_blog_listing_pagination_border' );
function artistic_blog_blog_listing_pagination_border(){
    return '#fff';
}

add_filter( 'bizberg_blog_listing_pagination_text', 'artistic_blog_blog_listing_pagination_text' );
function artistic_blog_blog_listing_pagination_text(){
    return '#fff';
}

add_filter( 'bizberg_blog_listing_pagination_active_hover_color', 'artistic_blog_blog_listing_pagination_active_hover_color' );
function artistic_blog_blog_listing_pagination_active_hover_color(){
    return '#dd3333';
}

add_filter( 'bizberg_sidebar_widget_link_color', 'artistic_blog_sidebar_widget_link_color' );
function artistic_blog_sidebar_widget_link_color(){
    return '#fff';
}

add_filter( 'bizberg_sidebar_widget_link_color_hover', 'artistic_blog_sidebar_widget_link_color_hover' );
function artistic_blog_sidebar_widget_link_color_hover(){
    return '#dd3333';
}

add_filter( 'bizberg_sidebar_widget_background_color', 'artistic_blog_sidebar_widget_background_color' );
function artistic_blog_sidebar_widget_background_color(){
    return 'rgba(255,255,255,0)';
}

add_filter( 'bizberg_sidebar_widget_border_color', 'artistic_blog_sidebar_widget_border_color' );
function artistic_blog_sidebar_widget_border_color(){
    return 'rgba(255,255,255,0)';
}

add_filter( 'bizberg_sidebar_widget_title_color', 'artistic_blog_sidebar_widget_title_color' );
function artistic_blog_sidebar_widget_title_color(){
    return '#dd3333';
}

add_filter( 'bizberg_sidebar_widget_link_separator', 'artistic_blog_sidebar_widget_link_separator' );
function artistic_blog_sidebar_widget_link_separator(){
    return '#303030';
}

add_filter( 'bizberg_sidebar_widget_select_color', 'artistic_blog_sidebar_widget_select_color' );
function artistic_blog_sidebar_widget_select_color(){
    return '#181d20';
}

add_filter( 'bizberg_primary_header_layout', 'artistic_blog_primary_header_layout' );
function artistic_blog_primary_header_layout(){
    return 'left';
}

add_filter( 'bizberg_site_title_font', 'artistic_blog_site_title_font' );
function artistic_blog_site_title_font(){
    return [
        'font-family'    => 'Playfair Display',
        'variant'        => '700',
        'font-size'      => '30px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'none',
        'text-align'     => 'left'
    ];
}

add_filter( 'bizberg_site_tagline_font', 'artistic_blog_site_tagline_font' );
function artistic_blog_site_tagline_font(){
    return [
        'font-family'    => 'Open Sans',
        'variant'        => 'regular',
        'font-size'      => '13px',
        'line-height'    => '1.8',
        'letter-spacing' => '2',
        'text-transform' => 'uppercase',
        'text-align'     => 'left'
    ];
}

add_filter( 'bizberg_header_site_title_color', 'artistic_blog_header_site_title_color' );
function artistic_blog_header_site_title_color(){
    return '#fff';
}

add_filter( 'bizberg_header_site_title_color_sticky_menu', 'artistic_blog_header_site_title_color_sticky_menu' );
function artistic_blog_header_site_title_color_sticky_menu(){
    return '#fff';
}

add_filter( 'bizberg_header_site_tagline_color', 'artistic_blog_header_site_tagline_color' );
function artistic_blog_header_site_tagline_color(){
    return '#fff';
}

add_filter( 'bizberg_header_site_tagline_color_sticky_menu', 'artistic_blog_header_site_tagline_color_sticky_menu' );
function artistic_blog_header_site_tagline_color_sticky_menu(){
    return '#fff';
}

add_filter( 'bizberg_header_navbar_background_1', 'artistic_blog_header_navbar_background_1' );
function artistic_blog_header_navbar_background_1(){
    return '#181d20';
}

add_filter( 'bizberg_header_navbar_background_2', 'artistic_blog_header_navbar_background_2' );
function artistic_blog_header_navbar_background_2(){
    return '#181d20';
}

add_filter( 'bizberg_header_menu_text_color', 'artistic_blog_header_menu_text_color' );
function artistic_blog_header_menu_text_color(){
    return '#fff';
}

add_filter( 'bizberg_header_menu_separator', 'artistic_blog_header_menu_separator' );
function artistic_blog_header_menu_separator(){
    return '#181d20';
}

add_filter( 'bizberg_header_menu_color_hover', 'artistic_blog_header_menu_color_hover' );
function artistic_blog_header_menu_color_hover(){
    return '#dd3333';
}

add_filter( 'bizberg_primary_header_layout_bottom_border_size', 'artistic_blog_primary_header_layout_bottom_border_size' );
function artistic_blog_primary_header_layout_bottom_border_size(){
    return '1';
}

add_filter( 'bizberg_primary_header_layout_top_border_color', 'artistic_blog_primary_header_layout_top_border_color' );
add_filter( 'bizberg_primary_header_layout_bottom_border_color', 'artistic_blog_primary_header_layout_top_border_color' );
function artistic_blog_primary_header_layout_top_border_color(){
    return '#2f2f2f';
}

add_filter( 'bizberg_blog_detail_content_border_color', 'artistic_blog_blog_detail_content_border_color' );
add_filter( 'bizberg_blog_detail_content_background', 'artistic_blog_blog_detail_content_border_color' );
function artistic_blog_blog_detail_content_border_color(){
    return '#14181b';
}

add_filter( 'bizberg_blog_detail_meta_divider_color', 'artistic_blog_blog_detail_meta_divider_color' );
add_filter( 'bizberg_blog_detail_comment_divider_color', 'artistic_blog_blog_detail_meta_divider_color' );
add_filter( 'bizberg_blog_detail_comment_input_border_color', 'artistic_blog_blog_detail_meta_divider_color' );
add_filter( 'bizberg_blog_detail_comment_input_background_color', 'artistic_blog_blog_detail_meta_divider_color' );
function artistic_blog_blog_detail_meta_divider_color(){
    return '#262626';
}

add_filter( 'bizberg_heading_color', 'artistic_blog_heading_color' );
function artistic_blog_heading_color(){
    return '#fff';
}

add_filter( 'bizberg_body_typo_heading_4_status', 'artistic_blog_body_typo_heading_4_status' );
function artistic_blog_body_typo_heading_4_status(){
    return true;
}

add_filter( 'bizberg_typography_h4', 'artistic_blog_typography_h4' );
function artistic_blog_typography_h4(){
    return [
        'font-family'    => 'Playfair Display',
        'variant'        => '700',
        'font-size'      => '32.81px',
        'line-height'    => '1.3',
        'letter-spacing' => '0',
        'text-transform' => 'inherit'
    ];
}

add_filter( 'bizberg_theme_container_width', 'artistic_blog_theme_container_width' );
function artistic_blog_theme_container_width(){
    return 1350;
}

add_filter( 'bizberg_header_menu_toggle_color_mobile', 'artistic_blog_header_menu_toggle_color_mobile' );
function artistic_blog_header_menu_toggle_color_mobile(){
    return '#fff';
}

add_filter( 'bizberg_body_content_typo_status', 'artistic_blog_body_content_typo_status' );
function artistic_blog_body_content_typo_status(){
    return true;
}

add_filter( 'bizberg_typography_body_content', 'artistic_blog_typography_body_content' );
function artistic_blog_typography_body_content(){
    return [
        'font-family'    => 'Poppins',
        'variant'        => '300',
        'font-size'      => '16px',
        'line-height'    => '1.8'
    ];
}

add_filter( 'bizberg_body_typo_heading_3_status', 'artistic_blog_body_typo_heading_3_status' );
function artistic_blog_body_typo_heading_3_status(){
    return true;
}

add_filter( 'bizberg_typography_h3', 'artistic_blog_bizberg_typography_h3' );
function artistic_blog_bizberg_typography_h3(){
    return [
        'font-family'    => 'Playfair Display',
        'variant'        => '700',
        'font-size'      => '41.02px',
        'line-height'    => '1.5',
        'letter-spacing' => '0',
        'text-transform' => 'inherit'
    ];
}

add_filter( 'bizberg_typography_h3_tablet', 'artistic_blog_typography_h3_tablet' );
function artistic_blog_typography_h3_tablet(){
    return 37.11;
}

add_filter( 'bizberg_typography_h3_mobile', 'artistic_blog_typography_h3_mobile' );
function artistic_blog_typography_h3_mobile(){
    return 29.30;
}

/**
* For Slider
*/

add_filter( 'bizberg_slider_banner_settings', 'artistic_blog_slider_banner_settings' );
function artistic_blog_slider_banner_settings(){
    return 'none';
}

add_filter( 'bizberg_shape_divider_bottom', 'artistic_blog_shape_divider_bottom' );
function artistic_blog_shape_divider_bottom(){
    return 'none';
}

add_filter( 'bizberg_slider_title_font_desktop_tablet', 'artistic_blog_slider_title_font_desktop_tablet' );
function artistic_blog_slider_title_font_desktop_tablet(){
    return [
        'font-family'    => 'PT Sans',
        'variant'        => '700',
        'font-size'      => '45px',
        'line-height'    => '1.2',
        'letter-spacing' => '0',
        'color'          => '#fff',
        'text-transform' => 'none',
    ];
}

add_filter( 'bizberg_slider_title_box_highlight_color', 'artistic_blog_slider_title_box_highlight_color' );
add_filter( 'bizberg_slider_arrow_background_color', 'artistic_blog_slider_title_box_highlight_color' );
add_filter( 'bizberg_read_more_background_color', 'artistic_blog_slider_title_box_highlight_color' );
add_filter( 'bizberg_read_more_background_color_2', 'artistic_blog_slider_title_box_highlight_color' );
add_filter( 'bizberg_slider_dot_active_color', 'artistic_blog_slider_title_box_highlight_color' );
add_filter( 'bizberg_header_button_color', 'artistic_blog_slider_title_box_highlight_color' );
add_filter( 'bizberg_header_button_color_hover', 'artistic_blog_slider_title_box_highlight_color' );
function artistic_blog_slider_title_box_highlight_color(){
    return '#dd3333';
}

add_filter( 'bizberg_slider_read_more_font', 'artistic_blog_slider_read_more_font' );
function artistic_blog_slider_read_more_font(){
    return [
        'font-family'    => 'Poppins',
        'variant'        => '600',
        'font-size'      => '14px',
        'line-height'    => '1.2',
        'letter-spacing' => '0px',
        'color'          => '#fff',
        'text-transform' => 'capitalize'
    ];
}

add_filter( 'bizberg_arrow_style', 'artistic_blog_bizberg_arrow_style' );
function artistic_blog_bizberg_arrow_style(){
    return 'diamond';
}

add_filter( 'bizberg_arrow_size', 'artistic_blog_arrow_size' );
function artistic_blog_arrow_size(){
    return 50;
}

add_filter( 'bizberg_slider_gradient_primary_color', 'artistic_blog_slider_gradient_primary_color' );
function artistic_blog_slider_gradient_primary_color(){
    return 'rgba(20,0,0,0.30)';
}

add_filter( 'bizberg_slider_gradient_secondary_color', 'artistic_blog_slider_gradient_secondary_color' );
function artistic_blog_slider_gradient_secondary_color(){
    return 'rgba(221,51,51,0.3)';
}

add_filter( 'bizberg_header_menu_child_menu_background', 'artistic_blog_header_menu_child_menu_background' );
add_filter( 'bizberg_header_menu_child_menu_border', 'artistic_blog_header_menu_child_menu_background' );
function artistic_blog_header_menu_child_menu_background(){
    return '#14181b';
}

add_filter( 'bizberg_header_menu_child_menu_text_color', 'artistic_blog_header_menu_child_menu_text_color' );
function artistic_blog_header_menu_child_menu_text_color(){
    return '#fff';
}

add_filter( 'bizberg_header_menu_child_menu_border', 'artistic_blog_header_menu_child_menu_border' );
function artistic_blog_header_menu_child_menu_border(){
    return 'rgba(37,37,38,0.57)';
}

add_filter( 'bizberg_header_navbar_background_1_sticky_menu', 'artistic_blog_header_navbar_background_1_sticky_menu' );
add_filter( 'bizberg_header_navbar_background_2_sticky_menu', 'artistic_blog_header_navbar_background_1_sticky_menu' );
add_filter( 'bizberg_header_menu_separator_sticky_menu', 'artistic_blog_header_navbar_background_1_sticky_menu' );
function artistic_blog_header_navbar_background_1_sticky_menu(){
    return '#dd3333';
}

add_filter( 'bizberg_header_menu_text_color_sticky_menu', 'artistic_blog_header_menu_text_color_sticky_menu' );
function artistic_blog_header_menu_text_color_sticky_menu(){
    return '#fff';
}

add_filter( 'bizberg_header_menu_color_hover_sticky_menu', 'artistic_blog_header_menu_color_hover_sticky_menu' );
function artistic_blog_header_menu_color_hover_sticky_menu(){
    return '#c71d1d';
}

add_filter( 'bizberg_header_menu_child_menu_background_sticky_menu', 'artistic_blog_header_menu_child_menu_background_sticky_menu' );
function artistic_blog_header_menu_child_menu_background_sticky_menu(){
    return '#dd3333';
}

add_filter( 'bizberg_header_menu_child_menu_border_sticky_menu', 'artistic_blog_header_menu_child_menu_border_sticky_menu' );
function artistic_blog_header_menu_child_menu_border_sticky_menu(){
    return '#df4343';
}

add_filter( 'bizberg_header_menu_child_menu_text_color_sticky_menu', 'artistic_blog_header_menu_child_menu_text_color_sticky_menu' );
function artistic_blog_header_menu_child_menu_text_color_sticky_menu(){
    return '#fff';
}

add_filter( 'bizberg_blog_detail_comment_input_text_color', 'artistic_blog_blog_detail_comment_input_text_color' );
function artistic_blog_blog_detail_comment_input_text_color(){
    return '#fff';
}

add_filter( 'bizberg_header_columns_middle_style', 'artistic_blog_header_columns_middle_style' );
function artistic_blog_header_columns_middle_style(){
    return 'col-sm-2|col-sm-8|col-sm-2';
}

add_filter( 'bizberg_footer_social_icon_color', 'artistic_blog_footer_social_icon_color' );
function artistic_blog_footer_social_icon_color(){
    return '#dd3333';
}

add_filter( 'bizberg_footer_copyright_background', 'artistic_blog_footer_copyright_background' );
function artistic_blog_footer_copyright_background(){
    return '#b11a1a';
}

add_filter( 'bizberg_banner_image', 'artistic_blog_banner_image' );
function artistic_blog_banner_image(){
    return [
        'background-color'      => 'rgba(20,20,20,.8)',
        'background-image'      => get_stylesheet_directory_uri() . '/images/man-person-black-and-white-people-white-photography-922038-pxhere.com.jpg',
        'background-repeat'     => 'repeat',
        'background-position'   => 'center center',
        'background-size'       => 'cover',
        'background-attachment' => 'scroll',
    ];
}

add_filter( 'bizberg_banner_spacing', 'artistic_blog_banner_spacing' );
function artistic_blog_banner_spacing(){
    return [
        'padding-top'    => '165px',
        'padding-bottom' => '165px',
        'padding-left'   => '0px',
        'padding-right'  => '0px',
    ];
}

add_filter( 'bizberg_banner_text_position', 'artistic_blog_banner_text_position' );
function artistic_blog_banner_text_position(){
    return 'left';
}

add_filter( 'bizberg_typography_h1', 'artistic_blog_typography_h1' );
function artistic_blog_typography_h1(){
    return [
        'font-family'    => 'Playfair Display',
        'variant'        => '700',
        'font-size'      => '64.09px',
        'line-height'    => '1.1',
        'letter-spacing' => '0',
        'text-transform' => 'inherit'
    ];
}

add_filter( 'bizberg_typography_h1_tablet', 'artistic_blog_typography_h1_tablet' );
function artistic_blog_typography_h1_tablet(){
    return 57.98;
}

add_filter( 'bizberg_typography_h1_mobile', 'artistic_blog_typography_h1_mobile' );
function artistic_blog_typography_h1_mobile(){
    return 45.78;
}

add_filter( 'bizberg_body_typo_heading_2_status', 'artistic_blog_body_typo_heading_2_status' );
function artistic_blog_body_typo_heading_2_status(){
    return true;
}

add_filter( 'bizberg_typography_h2', 'artistic_blog_typography_h2' );
function artistic_blog_typography_h2(){
    return [
        'font-family'    => 'Playfair Display',
        'variant'        => '700',
        'font-size'      => '51.27px',
        'line-height'    => '1',
        'letter-spacing' => '0',
        'text-transform' => 'inherit'
    ];
}

add_filter( 'bizberg_typography_h2_tablet', 'artistic_blog_typography_h2_tablet' );
function artistic_blog_typography_h2_tablet(){
    return 46.39;
}

add_filter( 'bizberg_typography_h2_mobile', 'artistic_blog_typography_h2_mobile' );
function artistic_blog_typography_h2_mobile(){
    return 36.62;
}

add_filter( 'bizberg_body_typo_heading_1_status', 'artistic_blog_body_typo_heading_1_status' );
function artistic_blog_body_typo_heading_1_status(){
    return true;
}

add_filter( 'bizberg_banner_title', 'artistic_blog_banner_title' );
function artistic_blog_banner_title(){
    return current_user_can( 'edit_theme_options' ) ? esc_html__( 'Martin Peterson' , 'artistic-blog' ) : '';
}

add_filter( 'bizberg_banner_subtitle', 'artistic_blog_banner_subtitle' );
function artistic_blog_banner_subtitle(){
    return current_user_can( 'edit_theme_options' ) ? esc_html__( "Lorem Ipsum has been the industry's standard dummy" , 'artistic-blog' ) : '';
}

add_filter( 'bizberg_sidebar_spacing_status', 'artistic_blog_sidebar_spacing_status' );
function artistic_blog_sidebar_spacing_status(){
    return '0px';
}

add_filter( 'bizberg_theme_output_css', 'artistic_blog_theme_output_css' );
function artistic_blog_theme_output_css( $output ){

    $output[] = array(
        'element'  => '.primary_header_2 a.logo:focus h3,.primary_header_2 a.logo:focus p',
        'property' => 'color'
    );

    $output[] = array(
        'element'  => '.detail-content.single_page a, .bizberg-list .entry-content p a, .comment-list .comment-content a, .widget_text.widget a, #comments ul.comment-item li .comment-header > a:focus',
        'property' => 'color'
    );

    $output[] = array(
        'element'  => '.detail-content.single_page .bizberg_post_date a:after, #comments a:focus code',
        'property' => 'background'
    );

    return $output;

}

add_filter( 'bizberg_recommended_plugins', 'artistic_blog_recommended_plugins' );
function artistic_blog_recommended_plugins( $plugins ){

    $plugins = array(

        array(
            'name'     => esc_html__( 'One Click Demo Import', 'artistic-blog' ),
            'slug'     => 'one-click-demo-import',
            'required' => false,
        ),
        array(
            'name'     => esc_html__( 'Cyclone Demo Importer', 'artistic-blog' ),
            'slug'     => 'cyclone-demo-importer',
            'required' => false
        )

    );

    return $plugins;

}

add_filter( 'bizberg_plugins', 'artistic_blog_plugins', 999 );
function artistic_blog_plugins(){

    $plugins = array(
        array(
            'slug' => 'one-click-demo-import/one-click-demo-import.php',
            'zip'  => 'one-click-demo-import'
        ), 
        array(
            'slug' => 'cyclone-demo-importer/index.php',
            'zip'  => 'cyclone-demo-importer'
        )           
    );

    return $plugins;

}

add_filter( 'bizberg_inline_style', 'artistic_blog_add_inline_css_slider_background' );
function artistic_blog_add_inline_css_slider_background( $css ){

    $gradient_1 = bizberg_get_theme_mod( 'slider_background_gradient_1' );
    $gradient_2 = bizberg_get_theme_mod( 'slider_background_gradient_2' );

    $css .= '.blog-banner .container::after { background : linear-gradient( 30deg ,' . esc_attr( $gradient_1 ) . ',' . esc_attr( $gradient_2 ) . ') }';

    return $css;

}

add_filter( 'bizberg_inline_style', 'artistic_blog_add_inline_css_slider_title' );
function artistic_blog_add_inline_css_slider_title( $css ){

    $gradient_1 = bizberg_get_theme_mod( 'title_border_gradient_1' );
    $gradient_2 = bizberg_get_theme_mod( 'title_border_gradient_2' );

    $css .= '.blog-banner .swiper-content h2 a { background-image: linear-gradient( to right, ' . esc_attr( $gradient_1 ) . ',' . esc_attr( $gradient_2 ) . '); }';

    return $css;

}

add_filter( 'bizberg_localize_scripts', 'artistic_blog_localize_scripts' );
function artistic_blog_localize_scripts( $data ){
    $data['slider_autoplay_delay'] = bizberg_get_theme_mod( 'slider_autoplay_delay' );
    $data['ab_slider_speed']       = bizberg_get_theme_mod( 'ab_slider_speed' );
    return $data;
}

// Enable dark mode options
add_filter( 'bizberg_dark_mode_status', function(){
    return true;
});

add_filter( 'bizberg_woo_product_color_status', function(){
    return true;
});

add_filter( 'bizberg_shop_sale_tag_color', function(){
    return '#dd3333';
});

add_filter( 'bizberg_shop_quick_view_background', function(){
    return '#dd3333';
});

add_filter( 'bizberg_shop_price_color', function(){
    return '#dd3333';
});

add_filter( 'bizberg_shop_add_to_cart_background', function(){
    return '#dd3333';
});

add_action( 'after_setup_theme', 'artistic_blog_starter_content' );
function artistic_blog_starter_content(){

    /** Starter Content */
    $starter_content = array(
        'posts' => array( 
            'home',
            'about',
            'news',
            'blog',
            'post_1' => array(
                'post_title' => esc_html( '50 Amazing Examples of Photography' , 'artistic-blog' ),
                'post_content' => esc_html( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel nibh lacus. Pellentesque nec magna mauris. Nam fringilla scelerisque ex vitae ultricies. Praesent mattis metus quis convallis accumsan. Ut finibus hendrerit magna, sagittis consectetur justo. Sed tellus diam, gravida quis imperdiet eu, varius vitae elit. Aenean efficitur augue mauris, in ultrices libero vehicula ut. Aenean euismod arcu nec diam molestie rhoncus. Sed et lectus enim. Curabitur blandit, magna eget ornare mollis, ligula neque tristique odio, dapibus bibendum sem mi ut orci.' , 'artistic-blog' ),
                'post_type' => 'post',
                'thumbnail' => '{{image_1}}',
            ),
            'post_2' => array(
                'post_title' => esc_html( 'Basic Inner Workings of Camera' , 'artistic-blog' ),
                'post_content' => esc_html( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel nibh lacus. Pellentesque nec magna mauris. Nam fringilla scelerisque ex vitae ultricies. Praesent mattis metus quis convallis accumsan. Ut finibus hendrerit magna, sagittis consectetur justo. Sed tellus diam, gravida quis imperdiet eu, varius vitae elit. Aenean efficitur augue mauris, in ultrices libero vehicula ut. Aenean euismod arcu nec diam molestie rhoncus. Sed et lectus enim. Curabitur blandit, magna eget ornare mollis, ligula neque tristique odio, dapibus bibendum sem mi ut orci.' , 'artistic-blog' ),
                'post_type' => 'post',
                'thumbnail' => '{{image_2}}',
            ),
            'post_3' => array(
                'post_title' => esc_html( 'How to Give Your Old Car a New Look' , 'artistic-blog' ),
                'post_content' => esc_html( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus vel nibh lacus. Pellentesque nec magna mauris. Nam fringilla scelerisque ex vitae ultricies. Praesent mattis metus quis convallis accumsan. Ut finibus hendrerit magna, sagittis consectetur justo. Sed tellus diam, gravida quis imperdiet eu, varius vitae elit. Aenean efficitur augue mauris, in ultrices libero vehicula ut. Aenean euismod arcu nec diam molestie rhoncus. Sed et lectus enim. Curabitur blandit, magna eget ornare mollis, ligula neque tristique odio, dapibus bibendum sem mi ut orci.' , 'artistic-blog' ),
                'post_type' => 'post',
                'thumbnail' => '{{image_3}}',
            ),
        ),
        'options' => array(
            'show_on_front' => 'posts'
        ),
        'attachments' => array(
            'image_1' => array(
                'post_title' => esc_html( 'hand-girl-hair-camera-photographer-pine-14200-pxhere.com', 'artistic-blog' ),
                'file' => 'images/hand-girl-hair-camera-photographer-pine-14200-pxhere.com.jpg'
            ),
            'image_2' => array(
                'post_title' => esc_html( 'hand-forest-snow-winter-girl-hair-795999-pxhere.com', 'artistic-blog' ),
                'file' => 'images/hand-forest-snow-winter-girl-hair-795999-pxhere.com.jpg'
            ),
            'image_3' => array(
                'post_title' => esc_html( 'hand-tree-nature-forest-person-snow-451320-pxhere.com', 'artistic-blog' ),
                'file' => 'images/hand-tree-nature-forest-person-snow-451320-pxhere.com.jpg'
            ),
        ),
        'nav_menus' => array(
            'menu-1' => array(
                'name' => __( 'Main Menu', 'artistic-blog' ),
                'items' => array(
                    'page_home',
                    'page_about',
                    'page_blog',
                    'page_news'
                ),
            ),
            'footer' => array(
                'name' => __( 'Footer Menu', 'artistic-blog' ),
                'items' => array(
                    'page_home',
                    'page_about',
                    'page_blog',
                    'page_news'
                ),
            ),
        ),
        
    );

    add_theme_support( 'starter-content', $starter_content );

}