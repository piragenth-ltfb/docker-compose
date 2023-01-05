<?php

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'=> __('Restaurant','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/options.dat',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/pizza-hub/homepage.jpg',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/widgets.wie',
            'preview_url' => 'https://bizbergthemes.com/pizza-hub-pro/'
        ),
        array(
            'import_file_name'=> __('Restaurant Banner','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/options.dat',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/pizza-hub/homepage-pro-1.jpg',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/widgets.wie',
            'preview_url' => 'https://bizbergthemes.com/pizza-hub-pro/homepage-pro/'
        ),
        array(
            'import_file_name'=> __('Restaurant Slider','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/options.dat',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/pizza-hub/homepage-pro-2.jpg',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/pizza-hub-pro/inc/widgets.wie',
            'preview_url' => 'https://bizbergthemes.com/pizza-hub-pro/homepage-pro-2/'
        ),
    );
}

add_action( 'pt-ocdi/after_import', 'cdi_after_import_setup' );
function cdi_after_import_setup( $selected_import ) {

    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'menu-1' => $main_menu->term_id,
        )
    );

    $import_file_name = $selected_import['import_file_name'];

    switch ( $import_file_name ) {

        case 'Restaurant':
            $front_page_id = get_page_by_path( 'homepage-free' );
            break;

        case 'Restaurant Banner':
            $front_page_id = get_page_by_path( 'homepage-pro' );
            break;

        case 'Restaurant Slider':
            $front_page_id = get_page_by_path( 'homepage-pro-2' );
            break;
        
        default:
            # code...
            break;
    }

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );

    // Change elementor options
    update_option( 'elementor_disable_color_schemes' , 'yes' );
    update_option( 'elementor_disable_typography_schemes' , 'yes' );
    update_option( 'elementor_page_title_selector' , 'h3.blog-title' );

    cdi_set_elementor_active_kit();

}

function cdi_set_elementor_active_kit(){

    $args = array(
        'post_type' => 'elementor_library',
        'numberposts' => 1,
        'post_status' => 'publish',
        'name' => 'default-kit-cyclone'
    );

    $my_posts = get_posts($args);
    if( $my_posts ) :
        update_option( 'elementor_active_kit',  absint( $my_posts[0]->ID ) );
    endif;

}

/**
* Remove unnecessary plugins
*/

add_filter( 'bizberg_recommended_plugins', function( $plugins ){

    if( empty( $plugins ) ){
        return $plugins;
    }

    $disable_plugins = array(
        'smart-slider-3'
    );

    foreach ( $plugins as $key => $value ) {        
        if( in_array( $value['slug'], $disable_plugins ) ){
            unset( $plugins[$key] );
        }
    }

    $new_plugins = array(
        array(
            'name' => esc_html__( 'Restaurant & Cafe Addon for Elementor', 'cdi' ),
            'slug' => 'restaurant-cafe-addon-for-elementor',
            'required' => true
        ),
    );

    return array_merge( array_values( $plugins ) , $new_plugins );

} , 99 );

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
        [ 
          'name'     => 'Restaurant & Cafe Addon for Elementor', 
          'slug'     => 'restaurant-cafe-addon-for-elementor', 
          'required' => true,             
        ],
    ];
 
    return $theme_plugins;

}
add_filter( 'ocdi/register_plugins', 'cdi_register_plugins' );