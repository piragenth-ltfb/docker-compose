<?php

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'=> __('My Travel Blogs PRO','cdi'),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/my-travel-blogs-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/my-travel-blogs-pro/inc/options.dat',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/my-travel-blogs-pro/inc/widgets.wie'
        )
    );
}

if( !function_exists( 'ocdi_plugin_intro_text' ) ){
    function ocdi_plugin_intro_text( $default_text ) {
        return $default_text;
    }
}

add_action( 'pt-ocdi/after_import', 'cdi_after_import_setup' );
function cdi_after_import_setup( $selected_import ) {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'menu-1' => $main_menu->term_id,
        )
    );

    // Get Slider ID
    $slider_cat_id = cdi_get_term_id_by_name( 'Slider' );
    set_theme_mod( 'slider_category', $slider_cat_id );

    // About Me Page
    $about_me_obj = get_page_by_title( 'How I Started Travelling' );
    set_theme_mod( 'about_me_page_id', $about_me_obj->ID );

    // Recent Trips
    $recent_trips_id = cdi_get_term_id_by_name( 'ASIA' );
    set_theme_mod( 'my_travel_blog_recent_trips_category', $recent_trips_id );

    // Call To Action Page
    $call_to_action_obj = get_page_by_title( 'How I Started Travelling' );
    set_theme_mod( 'my_travel_blog_call_to_action_page', $call_to_action_obj->ID );

    update_option( 'show_on_front', 'posts' );

}

add_filter( 'my_travel_blogs_destinations_categories', function(){
    return;
});

add_filter( 'my_travel_blogs_where_am_i_section_limit', function(){

    if( function_exists( 'bizberg_get_theme_mod' ) ){
        $limit = bizberg_get_theme_mod( 'my_travel_blog_pro_recent_trips_limit' );
        return $limit;
    }

    return -1;
    
});

add_action( 'init' , 'cdi_my_travel_blogs_kirki_fields' );
function cdi_my_travel_blogs_kirki_fields(){

    if( !class_exists( 'Kirki' ) ){
        return;
    }

    Kirki::add_field( 'bizberg', [
        'type'        => 'slider',
        'settings'    => 'my_travel_blog_pro_recent_trips_limit',
        'label'       => esc_html__( 'Limit', 'cdi' ),
        'section'     => 'my_travel_blog_recent_trips',
        'default'     => 3,
        'choices'     => [
            'min'  => 1,
            'max'  => 15,
            'step' => 1
        ],
        'priority' => 20,
        'active_callback' => [
            [
                'setting'  => 'my_travel_blog_recent_trips_status',
                'operator' => '==',
                'value'    => true,
            ]
        ],
    ] );

}

function cdi_register_plugins( $plugins ) {

    $theme_plugins = [
        [ 
          'name'     => 'Contact Form 7', 
          'slug'     => 'contact-form-7', 
          'required' => true,             
        ],
        [ 
          'name'     => 'Elementor Page Builder', 
          'slug'     => 'elementor', 
          'required' => true,             
        ],
        [ 
          'name'     => 'Essential Addons for Elementor', 
          'slug'     => 'essential-addons-for-elementor-lite', 
          'required' => true,             
        ],
    ];
 
    return $theme_plugins;

}
add_filter( 'ocdi/register_plugins', 'cdi_register_plugins' );