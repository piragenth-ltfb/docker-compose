<?php

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'=> __('Homepage Free','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/options.dat',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/education-business/homepage.jpg',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/widgets.wie',
            'preview_url' => 'https://bizbergthemes.com/education-business-pro/'
        ),
        array(
            'import_file_name'=> __('Homepage PRO','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/options.dat',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/widgets.wie',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/education-business/homepage-pro.jpg',
            'preview_url' => 'https://bizbergthemes.com/education-business-pro/homepage-pro/'
        ),
        array(
            'import_file_name'=> __('Higher Education Business PRO','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/options.dat',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/widgets.wie',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/education-business/homepage-higher-education.jpg',
            'preview_url' => 'https://bizbergthemes.com/education-business-pro/higher-education-business-pro/'
        ),
        array(
            'import_file_name'=> __('Education Shop PRO','cdi'),
            'categories'      =>  array( 'Homepage' ),
            'local_import_file'=> CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/options.dat',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/education-business-pro/inc/widgets.wie',
            'import_preview_image_url'   => CDI_PLUGIN_DIR_URL . '/assets/images/education-business/homepage-pro-shop.jpg',
            'preview_url' => 'https://bizbergthemes.com/education-business-pro/education-shop-pro/'
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

        case 'Homepage Free':
            $front_page_id = get_page_by_path( 'homepage-free' );
            break;

        case 'Homepage PRO':
            $front_page_id = get_page_by_path( 'homepage-pro' );
            break;

        case 'Higher Education Business PRO':
            $front_page_id = get_page_by_path( 'higher-education-business-pro' );
            break;

        case 'Education Shop PRO':
            $front_page_id = get_page_by_path( 'education-shop-pro' );
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
          'name'     => 'Education Addon for Elementor', 
          'slug'     => 'education-addon', 
          'required' => true,             
        ],
        [ 
          'name'     => 'LearnPress – WordPress LMS Plugin', 
          'slug'     => 'learnpress', 
          'required' => true,             
        ],
        [ 
          'name'     => 'LearnPress – Course Review', 
          'slug'     => 'learnpress-course-review', 
          'required' => true,             
        ],
        [ 
          'name'     => 'The Events Calendar', 
          'slug'     => 'the-events-calendar', 
          'required' => true,             
        ],
        [ 
          'name'     => 'WooCommerce', 
          'slug'     => 'woocommerce', 
          'required' => true,             
        ],
        [ 
          'name'     => 'YITH WooCommerce Quick View',
          'slug'     => 'yith-woocommerce-quick-view',
          'required' => true,             
        ],
        [ 
          'name'     => 'YITH WooCommerce Wishlist', 
          'slug'     => 'yith-woocommerce-wishlist', 
          'required' => true,             
        ],
    ];
 
    return $theme_plugins;

}
add_filter( 'ocdi/register_plugins', 'cdi_register_plugins' );