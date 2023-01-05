<?php 

require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/customizer/options-post-grid-3.php';
require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/customizer/options-post-grid-4.php';
require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/customizer/options-page.php';
require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/section/post-grid-3.php';
require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/section/post-grid-4.php';
require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/section/page.php';

add_action( 'wp_enqueue_scripts', 'cdi_news_24x7_pro_style' );
function cdi_news_24x7_pro_style(){
    wp_enqueue_style( 'cdi_news_24x7_pro_style', CDI_PLUGIN_DIR_URL . 'themes/news-24x7-pro/style.css' );
}

add_filter( 'bizberg_theme_output_css', function( $css ){
    $css[] = array(
        'element'       => '.news_24x7_post_grid_3 .content .news_row.top .column .post_list .meta-info .post-category, .news_24x7_post_grid_4 .item .image .meta-info .post-category',
        'property'      => 'background',
        'value_pattern' => '$'
    );
    return $css;
});

add_filter( 'bizberg_link_color_hover_output_css', function( $css ){
    $css[] = array(
        'element'       => '.news_24x7_post_grid_3 .content .news_row.bottom .column .list h4 a:hover, .news_24x7_post_grid_3 .content .news_row.bottom .column .list .time_ago small a:hover',
        'property'      => 'color',
        'value_pattern' => '$'
    );
    return $css;
});

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'             => __('News 24x7 PRO','cdi'),
            'local_import_file'            => CDI_PLUGIN_DIR_PATH . '/themes/news-24x7-pro/inc/content.xml',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/news-24x7-pro/inc/widgets.wie',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/news-24x7-pro/inc/options.dat'
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

    remove_theme_mod( 'news_24x7_breaking_news_category' );
    remove_theme_mod( 'news_24x7_featured_news_category' );
    remove_theme_mod( 'news_24x7_featured_news_main_news_categories' );
    remove_theme_mod( 'news_24x7_featured_news_popular_news_categories' );

    // Assign menus to their locations.
    $main_menu   = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'menu-1' => $main_menu->term_id,
            'footer' => $footer_menu->term_id
        )
    );

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

/**
* After plugin activation set 'show_on_front' option to 'posts'
*/

register_activation_hook( CDI_PLUGIN_FILE, 'news_24x7_activation_actions' );
function news_24x7_activation_actions(){
    do_action( 'news_24x7_set_default_value_on_activation' );
}

add_action( 'news_24x7_set_default_value_on_activation', 'news_24x7_default_options' );
function news_24x7_default_options(){

    $plugin_activation_status = get_option( 'news_24x7_plugin_activate_status', false );

    if( $plugin_activation_status == false ){
        update_option( 'show_on_front', 'posts' );
        update_option( 'news_24x7_plugin_activate_status', true );
    }

}