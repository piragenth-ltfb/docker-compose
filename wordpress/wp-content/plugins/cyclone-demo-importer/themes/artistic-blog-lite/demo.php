<?php

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'             => __('Artistic Blog Free','cdi'),
            'local_import_file'            => CDI_PLUGIN_DIR_PATH . '/themes/artistic-blog-lite/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/artistic-blog-lite/inc/options.dat'
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
    $slider_cat_id   = array();
    $slider_cat_id[] = cdi_get_term_id_by_name( 'Featured' );
    set_theme_mod( 'artistic_blog_slider_category', $slider_cat_id );

    // Get Editor ID
    $featured_cat_id   = array();
    $featured_cat_id[] = cdi_get_term_id_by_name( 'Editor Choice' );
    set_theme_mod( 'featured_post_grid_category', $featured_cat_id );

    update_option( 'show_on_front', 'posts' );

}

function cdi_register_plugins( $plugins ) {

    $theme_plugins = [
        [ 
          'name'     => 'One Click Demo Import', 
          'slug'     => 'one-click-demo-import', 
          'required' => true,             
        ],
        [ 
          'name'     => 'Cyclone Demo Importer', 
          'slug'     => 'cyclone-demo-importer', 
          'required' => true,             
        ]
    ];
 
    return $theme_plugins;

}
add_filter( 'ocdi/register_plugins', 'cdi_register_plugins' );