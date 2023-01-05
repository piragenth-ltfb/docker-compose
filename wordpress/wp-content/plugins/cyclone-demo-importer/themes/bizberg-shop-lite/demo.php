<?php

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'             => __('Bizberg Shop','cdi'),
            'local_import_file'            => CDI_PLUGIN_DIR_PATH . '/themes/bizberg-shop-lite/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/bizberg-shop-lite/inc/options.dat',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/bizberg-shop-lite/inc/widgets.wie'
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

    // Assign Front Page
    $front_page_id = get_page_by_title( 'Ecommerce Homepage' );
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );

    // Assign YITH Settings
    update_option( 'yith_woocompare_fields_attrs', 'all' );
    update_option( 'yith_woocompare_is_button', 'button' );
    update_option( 'yith_woocompare_button_text', 'Compare' );
    update_option( 'yith_woocompare_compare_button_in_product_page', 'yes' );
    update_option( 'yith_woocompare_compare_button_in_products_list', 'no' );
    update_option( 'yith_woocompare_auto_open', 'yes' );
    update_option( 'yith_woocompare_table_text', 'Compare products' );
    update_option( 'yith_woocompare_price_end', 'yes' );
    update_option( 'yith_woocompare_add_to_cart_end', 'no' );
    update_option( 'yith_woocompare_image_size', array(
        'width' => 220,
        'height' => 154,
        'crop' => 1
    ));

    cdi_set_woo_category_font_icons();
    cdi_set_top_categories_cat();
    cdi_set_tab_product_categories();
    cdi_set_woo_slider_pages();
    cdi_set_wishlist_page_template();

}

function cdi_set_wishlist_page_template(){
    $wishlist_page = get_page_by_path( 'wishlist' );
    update_post_meta( $wishlist_page->ID, '_wp_page_template', 'page-templates/full-width-container.php' );
}

function cdi_set_woo_category_font_icons(){

    $data = array();
    $product_cat = array();
    $product_cat[] = cdi_get_term_id_by_name( 'Bike', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Car', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Clocks', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Clothing', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Cooking', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Decor', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Electronics', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Furniture', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Handmade', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Lighting', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Music', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Toys', 'product_cat' );

    $icons = array(
        'f553',
        'f51f',
        'f58f',
        'f6c0',
        'f2e7',
        'f017',
        'f0eb',
        'f11b',
        'f7b5',
        'f1e6',
        'f1b9',
        'f206'
    );

    if( empty( $product_cat ) ){
        return;
    }

    foreach ( $product_cat as $key => $value ) {
        $data[] = array(
            'category'  => $value,
            'icon_code' => $icons[$key]
        );
    }

    set_theme_mod( 'woo_icon_categories', $data );

}

function cdi_set_top_categories_cat(){

    $data    = array();
    $image   = array();
    $image[] = cdi_get_attachment_by_post_name( 'cat_img7' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img6' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img5' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img4' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img3' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img2' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img1' );
    $image[] = cdi_get_attachment_by_post_name( 'cat_img7' );

    $product_cat   = array();
    $product_cat[] = cdi_get_term_id_by_name( 'Bike', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Car', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Clocks', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Clothing', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Cooking', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Decor', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Electronics', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Furniture', 'product_cat' );

    if( empty( $product_cat ) ){
        return;
    }

    foreach ( $product_cat as $key => $value ) {
        $data[] = array(
            'category' => $value,
            'image'    => !empty( $image[$key] ) ? $image[$key] : ''
        );
    }

    set_theme_mod( 'top_categories_cat', $data );

}

function cdi_set_tab_product_categories(){

    $data          = array();
    $product_cat   = array();
    $product_cat[] = cdi_get_term_id_by_name( 'Clothing', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Tshirts', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Hoodies', 'product_cat' );
    $product_cat[] = cdi_get_term_id_by_name( 'Accessories', 'product_cat' );

    if( empty( $product_cat ) ){
        return;
    }

    foreach ( $product_cat as $key => $value ) {
        $data[] = array(
            'category_id' => $value,
            'limit'       => 5,
            'columns'     => 5
        );
    }

    set_theme_mod( 'tab_product_categories', $data );

}

function cdi_set_woo_slider_pages(){

    $data        = array();
    $slider_1 = get_page_by_title( 'Summer Look' );
    $slider_2 = get_page_by_title( 'The Best Tourist Fashion' );

    $data[] = array(
        'page'           => $slider_1->ID,
        'align'          => 'center',
        'content_width'  => 50,
        'translate_x'    => -20,
        'subtitle'       => 'Happy New Year',
        'button_text'    => 'Read More',
        'button_link'    => '#',
        'color_title'    => '#000',
        'color_subtitle' => '#f5848c',
        'color_content'  => '#888'
    );

    $data[] = array(
        'page'           => $slider_2->ID,
        'align'          => 'center',
        'content_width'  => 50,
        'translate_x'    => -90,
        'subtitle'       => 'Happy New Year',
        'button_text'    => 'Sale 20% off',
        'button_link'    => '#',
        'color_title'    => '#000000',
        'color_subtitle' => '#f4424e',
        'color_content'  => '#888'
    );

    set_theme_mod( 'woo_slider_pages', $data );

}

function cdi_register_plugins( $plugins ) {

    $theme_plugins = [
        [ 
          'name'     => 'WooCommerce', 
          'slug'     => 'woocommerce', 
          'required' => true,             
        ],
        [ 
          'name'     => 'YITH WooCommerce Compare', 
          'slug'     => 'yith-woocommerce-compare', 
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