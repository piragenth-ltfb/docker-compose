<?php
/**
* Plugin Name: Cyclone Demo Importer
* Description: Import all the demos on your site
* Author: cyclonetheme
* Version: 2.9.6
* License: GPL2+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* 
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CDI_PLUGIN_DIR_PATH' , plugin_dir_path( __FILE__ ) );
define( 'CDI_PLUGIN_DIR_URL' , plugin_dir_url( __FILE__ ) );

// add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

require CDI_PLUGIN_DIR_PATH . 'upgrade-to-pro-messsages.php';

// Include WooCommerce Settings
add_action( 'woocommerce_loaded', 'cdi_woocommerce_settings' );
function cdi_woocommerce_settings(){
	if ( class_exists( 'WooCommerce' ) ){
		require CDI_PLUGIN_DIR_PATH . 'woocommerce.php';
	}
}

$my_theme = wp_get_theme();

switch ( $my_theme->Name ) {

	case 'Business Event PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/business-event-pro/demo.php';
		break;

	case 'Business Event':
		require CDI_PLUGIN_DIR_PATH . 'themes/business-event-lite/demo.php';
		break;

	case 'Business Event Conference':
		require CDI_PLUGIN_DIR_PATH . 'themes/business-event-conference-lite/demo.php';
		break;

	case 'Education Business':
		require CDI_PLUGIN_DIR_PATH . 'themes/education-business-lite/demo.php';
		break;

	case 'Education Business PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/education-business-pro/demo.php';
		break;

	case 'Green Eco Planet':
		require CDI_PLUGIN_DIR_PATH . 'themes/green-eco-planet/demo.php';
		break;

	case 'Green Eco Planet PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/green-eco-planet-pro/demo.php';
		break;

	case 'Building Construction Architecture':
		require CDI_PLUGIN_DIR_PATH . 'themes/building-construction-architecture/demo.php';
		break;

	case 'Building Construction Architecture PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/building-construction-architecture-pro/demo.php';
		break;

	case 'NGO Charity Fundraising':
		require CDI_PLUGIN_DIR_PATH . 'themes/ngo-charity-fundraising-lite/demo.php';
		break;

	case 'NGO Charity Fundraising PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/ngo-charity-fundraising-pro/demo.php';
		break;

	case 'Happy Wedding Day':
		require CDI_PLUGIN_DIR_PATH . 'themes/happy-wedding-day-lite/demo.php';
		break;

	case 'Happy Wedding Day PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/happy-wedding-day-pro/demo.php';
		break;

	case 'Professional Education Consultancy':
		require CDI_PLUGIN_DIR_PATH . 'themes/professional-education-consultancy-lite/demo.php';
		break;

	case 'Professional Education Consultancy PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/professional-education-consultancy-pro/demo.php';
		break;

	case 'Dr. Life Saver':
		require CDI_PLUGIN_DIR_PATH . 'themes/dr-life-saver-lite/demo.php';
		break;

	case 'Dr. Life Saver PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/dr-life-saver-pro/demo.php';
		break;

	case 'Pizza Hub':
		require CDI_PLUGIN_DIR_PATH . 'themes/pizza-hub-lite/demo.php';
		break;

	case 'Pizza Hub PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/pizza-hub-pro/demo.php';
		break;

	case 'Bizberg':
	case 'Bizberg Agency':
	case 'Bizberg Individual Consultant':
		require CDI_PLUGIN_DIR_PATH . 'themes/bizberg-lite/demo.php';
		break;

	case 'Bizberg PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/bizberg-pro/demo.php';
		break;

	case 'My Travel Blogs':
		require CDI_PLUGIN_DIR_PATH . 'themes/my-travel-blogs-lite/demo.php';
		break;

	case 'My Travel Blogs PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/my-travel-blogs-pro/demo.php';
		break;

	case 'Eye Catching Blog':
		require CDI_PLUGIN_DIR_PATH . 'themes/eye-catching-blog-lite/demo.php';
		break;

	case 'Eye Catching Blog PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/eye-catching-blog-pro/demo.php';
		break;

	case 'Bizberg Consulting Dark':
		require CDI_PLUGIN_DIR_PATH . 'themes/bizberg-consulting-dark/demo.php';
		break;

	case 'Bizberg Consulting Dark PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/bizberg-consulting-dark-pro/demo.php';
		break;

	case 'OMG Blog':
		require CDI_PLUGIN_DIR_PATH . 'themes/omg-blog-lite/demo.php';
		break;

	case 'OMG Blog PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/omg-blog-pro/demo.php';
		break;

	case 'Next Level Blog':
		require CDI_PLUGIN_DIR_PATH . 'themes/next-level-blog-lite/demo.php';
		break;

	case 'Next Level Blog PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/next-level-blog-pro/demo.php';
		break;

	case 'Bizberg Shop':
		require CDI_PLUGIN_DIR_PATH . 'themes/bizberg-shop-lite/demo.php';
		break;

	case 'Bizberg Shop PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/bizberg-shop-pro/demo.php';
		break;

	case 'Oh My Blog':
		require CDI_PLUGIN_DIR_PATH . 'themes/oh-my-blog-lite/demo.php';
		break;

	case 'Oh My Blog PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/oh-my-blog-pro/demo.php';
		break;

	case 'Artistic Blog':
		require CDI_PLUGIN_DIR_PATH . 'themes/artistic-blog-lite/demo.php';
		break;

	case 'Artistic Blog PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/artistic-blog-pro/demo.php';
		break;

	case 'Planet Green':
		require CDI_PLUGIN_DIR_PATH . 'themes/planet-green/demo.php';
		break;

	case 'Education Business School':
		require CDI_PLUGIN_DIR_PATH . 'themes/education-business-school/demo.php';
		break;
	
	default:
		# code...
		break;
}

/**
 * Redirect after plugin activation
 */

register_activation_hook( __FILE__ , 'cdi_after_activation');
add_action( 'admin_init', 'cdi_redirect' , 999 );

function cdi_after_activation() {
    add_option( 'cdi_activation_redirect_status' , true );
}

function cdi_redirect() {

    if ( get_option( 'cdi_activation_redirect_status', false ) ) {

    	// Get theme details
		$theme = wp_get_theme();		

		// Give access to only selected themes
		$available_themes = array( 
			'bizberg', 
			'bizberg-pro',
			'pizza-hub',
			'pizza-hub-pro',
			'dr-life-saver',
			'dr-life-saver-pro',
			'professional-education-consultancy',
			'professional-education-consultancy-pro',
			'happy-wedding-day',
			'happy-wedding-day-pro',
			'ngo-charity-fundraising',
			'ngo-charity-fundraising-pro',
			'building-construction-architecture',
			'building-construction-architecture-pro',
			'education-business',
			'education-business-pro',
			'green-eco-planet',
			'green-eco-planet-pro',
			'business-event',
			'business-event-conference',
			'business-event-pro',
			'my-travel-blogs',
			'my-travel-blogs-pro',
			'eye-catching-blog',
			'eye-catching-blog-pro',
			'bizberg-consulting-dark',
			'bizberg-consulting-dark-pro',
			'omg-blog',
			'omg-blog-pro',
			'next-level-blog',
			'next-level-blog-pro',
			'bizberg-shop',
			'bizberg-shop-pro',
			'oh-my-blog',
			'oh-my-blog-pro',
			'artistic-blog',
			'artistic-blog-pro',
			'bizberg-agency',
			'bizberg-individual-consultant',
			'planet-green',
			'education-business-school'
		);

		if( in_array( $theme->get( 'TextDomain' ) , $available_themes ) ){			
			delete_option( 'cdi_activation_redirect_status' );
			wp_redirect( 'themes.php?page=one-click-demo-import' );
     		exit;
		}

		delete_option( 'cdi_activation_redirect_status' );
    	
    }

}

add_action('admin_enqueue_scripts', 'cdi_custom_wp_admin_style');
function cdi_custom_wp_admin_style(){

	if( empty( $_GET['page'] ) || $_GET['page'] != 'one-click-demo-import' ){
		return;
	}

    wp_enqueue_style( 'cdi_wp_admin_css', CDI_PLUGIN_DIR_URL . 'assets/css/admin.css' );
}

add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

add_filter( 'pt-ocdi/plugin_intro_text', 'ocdi_plugin_intro_text' );

if( !function_exists( 'ocdi_plugin_intro_text' ) ){
	function ocdi_plugin_intro_text( $default_text ) {
	    return '<br>';
	}
}

/**
* Set Logo
*/

add_action( 'pt-ocdi/after_import', 'cdi_setup_logo' );
function cdi_setup_logo(){
	set_theme_mod( 'custom_logo' , cdi_get_attachment_by_post_name( 'logo' ) );	
}

/**
* Set custom logo
*/

function cdi_get_attachment_by_post_name( $post_name ){

	 $args = array(
        'posts_per_page' => 1,
        'post_type'      => 'attachment',
        'name'           => trim( $post_name ),
    );

    $get_attachment = new WP_Query( $args );

    if ( empty( $get_attachment->posts[0]->ID ) ) {
        return false;
    }

    return absint( $get_attachment->posts[0]->ID );

}

/**
* Set theme colors
*/

function cdi_set_page_theme_mod( $slug ){

	switch ( $slug ) {

		case 'individual-consulting-homepage':
			set_theme_mod( 'transparent_header_homepage' , false );
			break;

		case 'construction-free':
			set_theme_mod( 'general-settings' , '#fcb80b' );
			set_theme_mod( 'header_menu_color_hover' , '#fcb80b' );
			set_theme_mod( 'header_button_color' , '#fcb80b' );
			set_theme_mod( 'header_button_color_hover' , '#fcb80b' );
			break;

		case 'dr-life-saver-free':
			set_theme_mod( 'general-settings' , '#2fbeef' );
			set_theme_mod( 'header_menu_color_hover' , '#2fbeef' );
			set_theme_mod( 'header_button_color' , '#2fbeef' );
			set_theme_mod( 'header_button_color_hover' , '#2fbeef' );
			break;

		case 'education-business-free':
			set_theme_mod( 'general-settings' , '#ffb606' );
			set_theme_mod( 'header_menu_color_hover' , '#ffb606' );
			set_theme_mod( 'header_button_color' , '#ffb606' );
			set_theme_mod( 'header_button_color_hover' , '#ffb606' );
			break;

		case 'nature-free':
			set_theme_mod( 'general-settings' , '#6ab43e' );
			set_theme_mod( 'header_menu_color_hover' , '#6ab43e' );
			set_theme_mod( 'header_button_color' , '#6ab43e' );
			set_theme_mod( 'header_button_color_hover' , '#6ab43e' );
			break;

		case 'wedding-free':
			set_theme_mod( 'general-settings' , '#f07677' );
			set_theme_mod( 'header_menu_color_hover' , '#f07677' );
			set_theme_mod( 'header_button_color' , '#f07677' );
			set_theme_mod( 'header_button_color_hover' , '#f07677' );
			break;

		case 'charity-free':
			set_theme_mod( 'general-settings' , '#e0be53' );
			set_theme_mod( 'header_menu_color_hover' , '#e0be53' );
			set_theme_mod( 'header_button_color' , '#e0be53' );
			set_theme_mod( 'header_button_color_hover' , '#e0be53' );
			break;

		case 'professional-education-consultancy-free':
			set_theme_mod( 'general-settings' , '#ff5202' );
			set_theme_mod( 'header_menu_color_hover' , '#ff5202' );
			set_theme_mod( 'header_button_color' , '#ff5202' );
			set_theme_mod( 'header_button_color_hover' , '#ff5202' );
			break;

		case 'restaurant-free':
			set_theme_mod( 'general-settings' , '#bb9f5d' );
			set_theme_mod( 'header_menu_color_hover' , '#bb9f5d' );
			set_theme_mod( 'header_button_color' , '#bb9f5d' );
			set_theme_mod( 'header_button_color_hover' , '#bb9f5d' );
			break;

		case 'business-event-free';
			set_theme_mod( 'slider_title_box_highlight_color' , '#e91e63' );
			set_theme_mod( 'slider_arrow_background_color' , '#e91e63' );
			set_theme_mod( 'slider_dot_active_color' , '#e91e63' );
			set_theme_mod( 'read_more_background_color' , '#e91e63' );
			set_theme_mod( 'theme_color' , '#e91e63' );
			set_theme_mod( 'link_color' , '#e91e63' );
			set_theme_mod( 'top_bar_background_2' , '#e91e63' );
			set_theme_mod( 'link_color_hover' , '#e91e63' );
			set_theme_mod( 'sidebar_widget_title_color' , '#e91e63' );
			set_theme_mod( 'blog_listing_pagination_active_hover_color' , '#e91e63' );
			set_theme_mod( 'heading_color' , '#e91e63' );
			set_theme_mod( 'sidebar_widget_link_color_hover' , '#e91e63' );
			set_theme_mod( 'footer_social_icon_color' , '#e91e63' );
			set_theme_mod( 'footer_copyright_background' , '#e91e63' );
			set_theme_mod( 'general-settings' , '#e91e63' );
			set_theme_mod( 'header_menu_color_hover_sticky_menu' , '#2e2d2e' );

			set_theme_mod( 'slider_banner' , 'slider' );
			set_theme_mod( 'slider_gradient_primary_color' , '#3a4cb4' );
			set_theme_mod( 'header_btn_border_radius' , array(
		        'top-left-radius'  => '0px',
		        'top-right-radius'  => '0px',
		        'bottom-left-radius' => '0px',
		        'bottom-right-radius' => '0px'
		    ) );
			set_theme_mod( 'header_button_border_dimensions' , array(
		        'top-width'  => '0px',
		        'bottom-width'  => '5px',
		        'left-width' => '0px',
		        'right-width' => '0px',
		    ) );
			set_theme_mod( 'slider_btn_border_radius' , array(
		        'border-top-left-radius'  => '0px',
		        'border-top-right-radius'  => '0px',
		        'border-bottom-right-radius' => '0px',
		        'border-bottom-left-radius' => '0px'
		    ) );
			set_theme_mod( 'read_more_border_color' , '#cc1451' );
			set_theme_mod( 'read_more_border_dimensions' , array(
		        'top-width'  => '0px',
		        'bottom-width'  => '5px',
		        'left-width' => '0px',
		        'right-width' => '0px',
		    ) );
			break;
		
		default:
			# code...
			break;
	}

}

add_action( 'pt-ocdi/after_import', 'cdi_before_content_import' , 999 );
function cdi_before_content_import( $selected_import ) {

	switch ( $selected_import['import_file_name'] ) {

		// Bizberg Lite Rerstaurant Theme
		case 'Restaurant':
		case 'Restaurant Banner';
		case 'Restaurant Slider';

			$recommended_plugins = array(
				'restaurant-cafe-addon-for-elementor' => array(
					'slug' => 'restaurant-cafe-addon-for-elementor/restaurant-cafe-addon-for-elementor.php',
					'zip' => 'https://downloads.wordpress.org/plugin/restaurant-cafe-addon-for-elementor.latest-stable.zip'
				),
			);

			cdi_install_activate_plugins( $recommended_plugins );
            
            break;

        case 'Business Event Free':
        case 'Business Event Homepage 1':
        case 'Business Event Homepage 2':
        case 'Business Event Homepage 3':
        case 'Dark Version 2':

        	$recommended_plugins = array(
				'events-addon-for-elementor' => array(
					'slug' => 'events-addon-for-elementor/events-addon-for-elementor.php',
					'zip' => 'https://downloads.wordpress.org/plugin/events-addon-for-elementor.latest-stable.zip'
				),
			);

			cdi_install_activate_plugins( $recommended_plugins );
            
            break;

        case 'My Travel Blogs Free':
        case 'My Travel Blogs PRO':
        case 'Eye Catching Blog':
        case 'Eye Catching Blog PRO':
        case 'OMG Blog Free':
        case 'OMG Blog PRO':
        case 'Next Level Blog':
        case 'Next Level Blog PRO':
        case 'Bizberg Shop':
        case 'Bizberg Shop PRO':
        case 'Oh My Blog Free':
        case 'Oh My Blog PRO':
        case 'Artistic Blog Free':
        case 'Artistic Blog PRO':
        case 'Education Business School Homepage':

        	// Skip installing extra plugins
        
           	break;
		
		default:
			
			$recommended_plugins = array(
				'smart-slider-3' => array(
					'slug' => 'smart-slider-3/smart-slider-3.php',
					'zip' => 'https://downloads.wordpress.org/plugin/smart-slider-3.latest-stable.zip'
				),
				'primary-addon-for-elementor' => array(
					'slug' => 'primary-addon-for-elementor/primary-addon-for-elementor.php',
					'zip' => 'https://downloads.wordpress.org/plugin/primary-addon-for-elementor.latest-stable.zip'
				)				
			);

			cdi_install_activate_plugins( $recommended_plugins );

			break;
	}

}

function cdi_install_activate_plugins( $recommended_plugins ){

	// Install and activate recommended plugins
	foreach ( $recommended_plugins as $key => $value ) {
		
		if ( !cdi_is_plugin_installed( $value['slug'] ) ) {
	    	cdi_install_plugin( $value['zip'] );
	  	}

	    activate_plugin( $value['slug'] , '' , '' , true );

	}


}

function cdi_is_plugin_installed( $slug ) {

  	if ( ! function_exists( 'get_plugins' ) ) {
    	require_once ABSPATH . 'wp-admin/includes/plugin.php';
  	}

  	$all_plugins = get_plugins();
   
  	if ( !empty( $all_plugins[$slug] ) ) {
    	return true;
  	} else {
    	return false;
  	}

}
 
function cdi_install_plugin( $plugin_zip ) {

	$upgrader = new \Plugin_Upgrader( new Quiet_Skin() );

  	$installed = $upgrader->install( $plugin_zip );
 
  	return $installed;

}

include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';   	
class Quiet_Skin extends \WP_Upgrader_Skin {

    public function feedback( $string, ...$args )
    {
        
    }
    public function header() 
    {
        
    }
    public function footer() 
    {
       
    }

}

function cdi_get_term_id_by_name( $term_name, $taxomomy = 'category', $search_by = 'name' ){

	$category = get_term_by( $search_by, $term_name, $taxomomy );

	if( empty( $category->term_id ) ){
		return;
	}
	return $category->term_id;
	
}

// Enable SVG upload for import
function cdi_upload_svg_mime_type($file_types) {
  	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
}

add_action( 'pt-ocdi/before_content_import', 'cdi_before_content_import_svg' );
function cdi_before_content_import_svg( $selected_import ) {
    add_filter('upload_mimes', 'cdi_upload_svg_mime_type');
}

//Stop WooCommerce redirect on activation
add_action( 'init', 'cdi_woocommerce_no_redirect_on_activation');
function cdi_woocommerce_no_redirect_on_activation() {
    delete_transient( '_wc_activation_redirect' );
};

add_action( 'admin_init', function() {
	if ( did_action( 'elementor/loaded' ) ) {
		remove_action( 'admin_init', [ \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ] );
	}
}, 1 );