<?php

add_filter( 'cdi_econsulting_agency_banner_image', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/adult-african-black-blonde-business-businessman-1629587-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_about_us_image', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/computer-working-person-group-people-meeting-559565-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_our_work_list_1', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/adult-african-black-blonde-business-businessman-1629587-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_our_work_list_2', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/computer-working-person-group-people-meeting-559565-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_our_work_list_3', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/laptop-computer-writing-working-table-person-764428-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_our_work_list_4', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/blank-brainstorming-colleagues-communication-computer-conference-1450995-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_partners_list', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/client-logo.png';
});

add_filter( 'cdi_econsulting_agency_testimonials_list', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/notebook-man-working-person-people-notepad-764654-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_video_background_image', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/laptop-computer-writing-working-table-person-764428-pxhere.com.jpg';
});

add_filter( 'cdi_econsulting_agency_work_process_image', function(){
	return get_stylesheet_directory_uri() . '/child-theme/images/computer-working-person-group-people-meeting-559565-pxhere.com.jpg';
});

add_filter( 'pt-ocdi/import_files', 'cdi_import_files' );
function cdi_import_files() {
    return array(
        array(
            'import_file_name'             => __( 'Econsultig Agency PRO','cdi' ),
            'local_import_file'            => CDI_PLUGIN_DIR_PATH . '/themes/econsulting-agency-pro/inc/content.xml',
            'local_import_customizer_file' => CDI_PLUGIN_DIR_PATH . '/themes/econsulting-agency-pro/inc/options.dat',
            'local_import_widget_file'     => CDI_PLUGIN_DIR_PATH . '/themes/econsulting-agency-pro/inc/widgets.wie'
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

    update_option( 'show_on_front', 'posts' );

}

add_filter( 'ocdi/register_plugins', 'cdi_register_plugins' );
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
        ],
        [ 
          'name'     => 'Contact Form 7',
          'slug'     => 'contact-form-7',
          'required' => true,             
        ]
    ];
 
    return $theme_plugins;

}