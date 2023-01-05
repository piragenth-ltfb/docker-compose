<?php
/**
* Plugin Name: Cyclone Demo Importer
* Description: Import all the demos on your site
* Author: cyclonetheme
* Version: 2.9.40
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
define( 'CDI_PLUGIN_FILE', __FILE__ );

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

	case 'School of Education':
		require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/index.php';
		require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/demo.php';
		break;

	case 'School of Education PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education/index.php';
		require CDI_PLUGIN_DIR_PATH . 'themes/school-of-education-pro/demo.php';
		break;

	case 'Business Consulting Dark':
		require CDI_PLUGIN_DIR_PATH . 'themes/business-consulting-dark/demo.php';
		break;

	case 'Forever Young':
		require CDI_PLUGIN_DIR_PATH . 'themes/forever-young-lite/demo.php';
		break;

	case 'Forever Young PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/forever-young-pro/demo.php';
		break;

	case 'Fashion Freak':
		require CDI_PLUGIN_DIR_PATH . 'themes/fashion-freak-lite/demo.php';
		break;

	case 'Fashion Freak PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/fashion-freak-pro/demo.php';
		break;

	case 'eConsulting Agency':
		require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/index.php';
		break;

	case 'eConsulting Agency PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-lite/index.php';
		require CDI_PLUGIN_DIR_PATH . 'themes/econsulting-agency-pro/index.php';
		break;

	case 'News 24x7':
		require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7/demo.php';
		break;

	case 'News 24x7 PRO':
		require CDI_PLUGIN_DIR_PATH . 'themes/news-24x7-pro/index.php';
		break;

	case 'Clean Charity':
		require CDI_PLUGIN_DIR_PATH . 'themes/clean-charity/demo.php';
		break;

	case 'Construction Sewa':
		require CDI_PLUGIN_DIR_PATH . 'themes/construction-sewa/demo.php';
		break;

	case 'Fully Green':
		require CDI_PLUGIN_DIR_PATH . 'themes/fully-green/demo.php';
		break;

	case 'Epic Business Event':
		require CDI_PLUGIN_DIR_PATH . 'themes/epic-business-event/demo.php';
		break;

	case 'Higher Education Business':
		require CDI_PLUGIN_DIR_PATH . 'themes/higher-education-business/demo.php';
		break;

	case 'Smart Health Pharmacy':
		require CDI_PLUGIN_DIR_PATH . 'themes/smart-health-pharmacy/demo.php';
		break;

	case 'Green Globe':
		require CDI_PLUGIN_DIR_PATH . 'themes/green-globe/demo.php';
		break;

	case 'Medical Business':
		require CDI_PLUGIN_DIR_PATH . 'themes/medical-business/demo.php';
		break;

	case 'Education Shop':
		require CDI_PLUGIN_DIR_PATH . 'themes/education-shop/demo.php';
		break;

	case 'NGO Charity Hub':
		require CDI_PLUGIN_DIR_PATH . 'themes/ngo-charity-hub/demo.php';
		break;

	case 'Creative Construction':
		require CDI_PLUGIN_DIR_PATH . 'themes/creative-construction/demo.php';
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
			'education-business-school',
			'business-consulting-dark',
			'forever-young',
			'forever-young-pro',
			'fashion-freak',
			'news-24x7',
			'news-24x7-pro',
			'clean-charity',
			'construction-sewa',
			'fully-green',
			'epic-business-event',
			'higher-education-business',
			'smart-health-pharmacy',
			'green-globe',
			'medical-business',
			'education-shop',
			'ngo-charity-hub',
			'creative-construction',
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
        case 'School of Education Free':
        case 'School of Education PRO':
        case 'Business Consulting Dark Lite':
        case 'Forever Young Free':
        case 'Forever Young PRO':
        case 'Fashion Freak Free':
        case 'Fashion Freak PRO':
        case 'Econsultig Agency PRO':
        case 'News 24x7 Free':
        case 'News 24x7 PRO':
        case 'Homepage Clean Charity Lite':
        case 'Clean Charity Homepage 2':
        case 'Construction Sewa Homepage':
        case 'Homepage Construction Sewa PRO':
        case 'Fully Green Homepage Free':
        case 'Fully Green Homepage PRO':
        case 'Epic Business Event Free':
        case 'Epic Business Event PRO':
        case 'Higher Education Business Free':
        case 'Higher Education Business PRO':
        case 'Homepage Smart Health Pharmacy Free':
        case 'Homepage Smart Health Pharmacy':
        case 'Green Globe PRO':
        case 'Green Globe Homepage Free':
        case 'Homepage Medical Business':
        case 'Homepage Medical Business Free':
        case 'Education Shop PRO':
        case 'Education Shop Free':
        case 'NGO Charity Hub Free':
        case 'Creative Construction Homepage':

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

function cdi_get_fontawesome_icons_list(){

	$icons = '{"icons":[{"name":"500px","id":"fab fa-500px"},{"name":"Accessible icon","id":"fab fa-accessible-icon"},{"name":"Accusoft","id":"fab fa-accusoft"},{"name":"Address book","id":"fas fa-address-book"},{"name":"Address book","id":"far fa-address-book"},{"name":"Address card","id":"fas fa-address-card"},{"name":"Address card","id":"far fa-address-card"},{"name":"Adjust","id":"fas fa-adjust"},{"name":"Adn","id":"fab fa-adn"},{"name":"Adversal","id":"fab fa-adversal"},{"name":"Affiliatetheme","id":"fab fa-affiliatetheme"},{"name":"Algolia","id":"fab fa-algolia"},{"name":"Align center","id":"fas fa-align-center"},{"name":"Align justify","id":"fas fa-align-justify"},{"name":"Align left","id":"fas fa-align-left"},{"name":"Align right","id":"fas fa-align-right"},{"name":"Amazon","id":"fab fa-amazon"},{"name":"Ambulance","id":"fas fa-ambulance"},{"name":"American sign language interpreting","id":"fas fa-american-sign-language-interpreting"},{"name":"Amilia","id":"fab fa-amilia"},{"name":"Anchor","id":"fas fa-anchor"},{"name":"Android","id":"fab fa-android"},{"name":"Angellist","id":"fab fa-angellist"},{"name":"Angle double down","id":"fas fa-angle-double-down"},{"name":"Angle double left","id":"fas fa-angle-double-left"},{"name":"Angle double right","id":"fas fa-angle-double-right"},{"name":"Angle double up","id":"fas fa-angle-double-up"},{"name":"Angle down","id":"fas fa-angle-down"},{"name":"Angle left","id":"fas fa-angle-left"},{"name":"Angle right","id":"fas fa-angle-right"},{"name":"Angle up","id":"fas fa-angle-up"},{"name":"Angrycreative","id":"fab fa-angrycreative"},{"name":"Angular","id":"fab fa-angular"},{"name":"App store","id":"fab fa-app-store"},{"name":"App store ios","id":"fab fa-app-store-ios"},{"name":"Apper","id":"fab fa-apper"},{"name":"Apple","id":"fab fa-apple"},{"name":"Apple pay","id":"fab fa-apple-pay"},{"name":"Archive","id":"fas fa-archive"},{"name":"Arrow alt circle down","id":"fas fa-arrow-alt-circle-down"},{"name":"Arrow alt circle down","id":"far fa-arrow-alt-circle-down"},{"name":"Arrow alt circle left","id":"fas fa-arrow-alt-circle-left"},{"name":"Arrow alt circle left","id":"far fa-arrow-alt-circle-left"},{"name":"Arrow alt circle right","id":"fas fa-arrow-alt-circle-right"},{"name":"Arrow alt circle right","id":"far fa-arrow-alt-circle-right"},{"name":"Arrow alt circle up","id":"fas fa-arrow-alt-circle-up"},{"name":"Arrow alt circle up","id":"far fa-arrow-alt-circle-up"},{"name":"Arrow circle down","id":"fas fa-arrow-circle-down"},{"name":"Arrow circle left","id":"fas fa-arrow-circle-left"},{"name":"Arrow circle right","id":"fas fa-arrow-circle-right"},{"name":"Arrow circle up","id":"fas fa-arrow-circle-up"},{"name":"Arrow down","id":"fas fa-arrow-down"},{"name":"Arrow left","id":"fas fa-arrow-left"},{"name":"Arrow right","id":"fas fa-arrow-right"},{"name":"Arrow up","id":"fas fa-arrow-up"},{"name":"Arrows alt","id":"fas fa-arrows-alt"},{"name":"Arrows alt h","id":"fas fa-arrows-alt-h"},{"name":"Arrows alt v","id":"fas fa-arrows-alt-v"},{"name":"Assistive listening systems","id":"fas fa-assistive-listening-systems"},{"name":"Asterisk","id":"fas fa-asterisk"},{"name":"Asymmetrik","id":"fab fa-asymmetrik"},{"name":"At","id":"fas fa-at"},{"name":"Audible","id":"fab fa-audible"},{"name":"Audio description","id":"fas fa-audio-description"},{"name":"Autoprefixer","id":"fab fa-autoprefixer"},{"name":"Avianex","id":"fab fa-avianex"},{"name":"Aviato","id":"fab fa-aviato"},{"name":"Aws","id":"fab fa-aws"},{"name":"Backward","id":"fas fa-backward"},{"name":"Balance scale","id":"fas fa-balance-scale"},{"name":"Ban","id":"fas fa-ban"},{"name":"Bandcamp","id":"fab fa-bandcamp"},{"name":"Barcode","id":"fas fa-barcode"},{"name":"Bars","id":"fas fa-bars"},{"name":"Bath","id":"fas fa-bath"},{"name":"Battery empty","id":"fas fa-battery-empty"},{"name":"Battery full","id":"fas fa-battery-full"},{"name":"Battery half","id":"fas fa-battery-half"},{"name":"Battery quarter","id":"fas fa-battery-quarter"},{"name":"Battery three quarters","id":"fas fa-battery-three-quarters"},{"name":"Bed","id":"fas fa-bed"},{"name":"Beer","id":"fas fa-beer"},{"name":"Behance","id":"fab fa-behance"},{"name":"Behance square","id":"fab fa-behance-square"},{"name":"Bell","id":"fas fa-bell"},{"name":"Bell","id":"far fa-bell"},{"name":"Bell slash","id":"fas fa-bell-slash"},{"name":"Bell slash","id":"far fa-bell-slash"},{"name":"Bicycle","id":"fas fa-bicycle"},{"name":"Bimobject","id":"fab fa-bimobject"},{"name":"Binoculars","id":"fas fa-binoculars"},{"name":"Birthday cake","id":"fas fa-birthday-cake"},{"name":"Bitbucket","id":"fab fa-bitbucket"},{"name":"Bitcoin","id":"fab fa-bitcoin"},{"name":"Bity","id":"fab fa-bity"},{"name":"Black tie","id":"fab fa-black-tie"},{"name":"Blackberry","id":"fab fa-blackberry"},{"name":"Blind","id":"fas fa-blind"},{"name":"Blogger","id":"fab fa-blogger"},{"name":"Blogger b","id":"fab fa-blogger-b"},{"name":"Bluetooth","id":"fab fa-bluetooth"},{"name":"Bluetooth b","id":"fab fa-bluetooth-b"},{"name":"Bold","id":"fas fa-bold"},{"name":"Bolt","id":"fas fa-bolt"},{"name":"Bomb","id":"fas fa-bomb"},{"name":"Book","id":"fas fa-book"},{"name":"Bookmark","id":"fas fa-bookmark"},{"name":"Bookmark","id":"far fa-bookmark"},{"name":"Braille","id":"fas fa-braille"},{"name":"Briefcase","id":"fas fa-briefcase"},{"name":"Btc","id":"fab fa-btc"},{"name":"Bug","id":"fas fa-bug"},{"name":"Building","id":"fas fa-building"},{"name":"Building","id":"far fa-building"},{"name":"Bullhorn","id":"fas fa-bullhorn"},{"name":"Bullseye","id":"fas fa-bullseye"},{"name":"Buromobelexperte","id":"fab fa-buromobelexperte"},{"name":"Bus","id":"fas fa-bus"},{"name":"Buysellads","id":"fab fa-buysellads"},{"name":"Calculator","id":"fas fa-calculator"},{"name":"Calendar","id":"fas fa-calendar"},{"name":"Calendar","id":"far fa-calendar"},{"name":"Calendar alt","id":"fas fa-calendar-alt"},{"name":"Calendar alt","id":"far fa-calendar-alt"},{"name":"Calendar check","id":"fas fa-calendar-check"},{"name":"Calendar check","id":"far fa-calendar-check"},{"name":"Calendar minus","id":"fas fa-calendar-minus"},{"name":"Calendar minus","id":"far fa-calendar-minus"},{"name":"Calendar plus","id":"fas fa-calendar-plus"},{"name":"Calendar plus","id":"far fa-calendar-plus"},{"name":"Calendar times","id":"fas fa-calendar-times"},{"name":"Calendar times","id":"far fa-calendar-times"},{"name":"Camera","id":"fas fa-camera"},{"name":"Camera retro","id":"fas fa-camera-retro"},{"name":"Car","id":"fas fa-car"},{"name":"Caret down","id":"fas fa-caret-down"},{"name":"Caret left","id":"fas fa-caret-left"},{"name":"Caret right","id":"fas fa-caret-right"},{"name":"Caret square down","id":"fas fa-caret-square-down"},{"name":"Caret square down","id":"far fa-caret-square-down"},{"name":"Caret square left","id":"fas fa-caret-square-left"},{"name":"Caret square left","id":"far fa-caret-square-left"},{"name":"Caret square right","id":"fas fa-caret-square-right"},{"name":"Caret square right","id":"far fa-caret-square-right"},{"name":"Caret square up","id":"fas fa-caret-square-up"},{"name":"Caret square up","id":"far fa-caret-square-up"},{"name":"Caret up","id":"fas fa-caret-up"},{"name":"Cart arrow down","id":"fas fa-cart-arrow-down"},{"name":"Cart plus","id":"fas fa-cart-plus"},{"name":"Cc amex","id":"fab fa-cc-amex"},{"name":"Cc apple pay","id":"fab fa-cc-apple-pay"},{"name":"Cc diners club","id":"fab fa-cc-diners-club"},{"name":"Cc discover","id":"fab fa-cc-discover"},{"name":"Cc jcb","id":"fab fa-cc-jcb"},{"name":"Cc mastercard","id":"fab fa-cc-mastercard"},{"name":"Cc paypal","id":"fab fa-cc-paypal"},{"name":"Cc stripe","id":"fab fa-cc-stripe"},{"name":"Cc visa","id":"fab fa-cc-visa"},{"name":"Centercode","id":"fab fa-centercode"},{"name":"Certificate","id":"fas fa-certificate"},{"name":"Chart area","id":"fas fa-chart-area"},{"name":"Chart bar","id":"fas fa-chart-bar"},{"name":"Chart bar","id":"far fa-chart-bar"},{"name":"Chart line","id":"fas fa-chart-line"},{"name":"Chart pie","id":"fas fa-chart-pie"},{"name":"Check","id":"fas fa-check"},{"name":"Check circle","id":"fas fa-check-circle"},{"name":"Check circle","id":"far fa-check-circle"},{"name":"Check square","id":"fas fa-check-square"},{"name":"Check square","id":"far fa-check-square"},{"name":"Chevron circle down","id":"fas fa-chevron-circle-down"},{"name":"Chevron circle left","id":"fas fa-chevron-circle-left"},{"name":"Chevron circle right","id":"fas fa-chevron-circle-right"},{"name":"Chevron circle up","id":"fas fa-chevron-circle-up"},{"name":"Chevron down","id":"fas fa-chevron-down"},{"name":"Chevron left","id":"fas fa-chevron-left"},{"name":"Chevron right","id":"fas fa-chevron-right"},{"name":"Chevron up","id":"fas fa-chevron-up"},{"name":"Child","id":"fas fa-child"},{"name":"Chrome","id":"fab fa-chrome"},{"name":"Circle","id":"fas fa-circle"},{"name":"Circle","id":"far fa-circle"},{"name":"Circle notch","id":"fas fa-circle-notch"},{"name":"Clipboard","id":"fas fa-clipboard"},{"name":"Clipboard","id":"far fa-clipboard"},{"name":"Clock","id":"fas fa-clock"},{"name":"Clock","id":"far fa-clock"},{"name":"Clone","id":"fas fa-clone"},{"name":"Clone","id":"far fa-clone"},{"name":"Closed captioning","id":"fas fa-closed-captioning"},{"name":"Closed captioning","id":"far fa-closed-captioning"},{"name":"Cloud","id":"fas fa-cloud"},{"name":"Cloud download alt","id":"fas fa-cloud-download-alt"},{"name":"Cloud upload alt","id":"fas fa-cloud-upload-alt"},{"name":"Cloudscale","id":"fab fa-cloudscale"},{"name":"Cloudsmith","id":"fab fa-cloudsmith"},{"name":"Cloudversify","id":"fab fa-cloudversify"},{"name":"Code","id":"fas fa-code"},{"name":"Code branch","id":"fas fa-code-branch"},{"name":"Codepen","id":"fab fa-codepen"},{"name":"Codiepie","id":"fab fa-codiepie"},{"name":"Coffee","id":"fas fa-coffee"},{"name":"Cog","id":"fas fa-cog"},{"name":"Cogs","id":"fas fa-cogs"},{"name":"Columns","id":"fas fa-columns"},{"name":"Comment","id":"fas fa-comment"},{"name":"Comment","id":"far fa-comment"},{"name":"Comment alt","id":"fas fa-comment-alt"},{"name":"Comment alt","id":"far fa-comment-alt"},{"name":"Comments","id":"fas fa-comments"},{"name":"Comments","id":"far fa-comments"},{"name":"Compass","id":"fas fa-compass"},{"name":"Compass","id":"far fa-compass"},{"name":"Compress","id":"fas fa-compress"},{"name":"Connectdevelop","id":"fab fa-connectdevelop"},{"name":"Contao","id":"fab fa-contao"},{"name":"Copy","id":"fas fa-copy"},{"name":"Copy","id":"far fa-copy"},{"name":"Copyright","id":"fas fa-copyright"},{"name":"Copyright","id":"far fa-copyright"},{"name":"Cpanel","id":"fab fa-cpanel"},{"name":"Creative commons","id":"fab fa-creative-commons"},{"name":"Credit card","id":"fas fa-credit-card"},{"name":"Credit card","id":"far fa-credit-card"},{"name":"Crop","id":"fas fa-crop"},{"name":"Crosshairs","id":"fas fa-crosshairs"},{"name":"Css3","id":"fab fa-css3"},{"name":"Css3 alt","id":"fab fa-css3-alt"},{"name":"Cube","id":"fas fa-cube"},{"name":"Cubes","id":"fas fa-cubes"},{"name":"Cut","id":"fas fa-cut"},{"name":"Cuttlefish","id":"fab fa-cuttlefish"},{"name":"D and d","id":"fab fa-d-and-d"},{"name":"Dashcube","id":"fab fa-dashcube"},{"name":"Database","id":"fas fa-database"},{"name":"Deaf","id":"fas fa-deaf"},{"name":"Delicious","id":"fab fa-delicious"},{"name":"Deploydog","id":"fab fa-deploydog"},{"name":"Deskpro","id":"fab fa-deskpro"},{"name":"Desktop","id":"fas fa-desktop"},{"name":"Deviantart","id":"fab fa-deviantart"},{"name":"Digg","id":"fab fa-digg"},{"name":"Digital ocean","id":"fab fa-digital-ocean"},{"name":"Discord","id":"fab fa-discord"},{"name":"Discourse","id":"fab fa-discourse"},{"name":"Dochub","id":"fab fa-dochub"},{"name":"Docker","id":"fab fa-docker"},{"name":"Dollar sign","id":"fas fa-dollar-sign"},{"name":"Dot circle","id":"fas fa-dot-circle"},{"name":"Dot circle","id":"far fa-dot-circle"},{"name":"Download","id":"fas fa-download"},{"name":"Draft2digital","id":"fab fa-draft2digital"},{"name":"Dribbble","id":"fab fa-dribbble"},{"name":"Dribbble square","id":"fab fa-dribbble-square"},{"name":"Dropbox","id":"fab fa-dropbox"},{"name":"Drupal","id":"fab fa-drupal"},{"name":"Dyalog","id":"fab fa-dyalog"},{"name":"Earlybirds","id":"fab fa-earlybirds"},{"name":"Edge","id":"fab fa-edge"},{"name":"Edit","id":"fas fa-edit"},{"name":"Edit","id":"far fa-edit"},{"name":"Eject","id":"fas fa-eject"},{"name":"Ellipsis h","id":"fas fa-ellipsis-h"},{"name":"Ellipsis v","id":"fas fa-ellipsis-v"},{"name":"Ember","id":"fab fa-ember"},{"name":"Empire","id":"fab fa-empire"},{"name":"Envelope","id":"fas fa-envelope"},{"name":"Envelope","id":"far fa-envelope"},{"name":"Envelope open","id":"fas fa-envelope-open"},{"name":"Envelope open","id":"far fa-envelope-open"},{"name":"Envelope square","id":"fas fa-envelope-square"},{"name":"Envira","id":"fab fa-envira"},{"name":"Eraser","id":"fas fa-eraser"},{"name":"Erlang","id":"fab fa-erlang"},{"name":"Etsy","id":"fab fa-etsy"},{"name":"Euro sign","id":"fas fa-euro-sign"},{"name":"Exchange alt","id":"fas fa-exchange-alt"},{"name":"Exclamation","id":"fas fa-exclamation"},{"name":"Exclamation circle","id":"fas fa-exclamation-circle"},{"name":"Exclamation triangle","id":"fas fa-exclamation-triangle"},{"name":"Expand","id":"fas fa-expand"},{"name":"Expand arrows alt","id":"fas fa-expand-arrows-alt"},{"name":"Expeditedssl","id":"fab fa-expeditedssl"},{"name":"External link alt","id":"fas fa-external-link-alt"},{"name":"External link square alt","id":"fas fa-external-link-square-alt"},{"name":"Eye","id":"fas fa-eye"},{"name":"Eye dropper","id":"fas fa-eye-dropper"},{"name":"Eye slash","id":"fas fa-eye-slash"},{"name":"Eye slash","id":"far fa-eye-slash"},{"name":"Facebook","id":"fab fa-facebook"},{"name":"Facebook f","id":"fab fa-facebook-f"},{"name":"Facebook messenger","id":"fab fa-facebook-messenger"},{"name":"Facebook square","id":"fab fa-facebook-square"},{"name":"Fast backward","id":"fas fa-fast-backward"},{"name":"Fast forward","id":"fas fa-fast-forward"},{"name":"Fax","id":"fas fa-fax"},{"name":"Female","id":"fas fa-female"},{"name":"Fighter jet","id":"fas fa-fighter-jet"},{"name":"File","id":"fas fa-file"},{"name":"File","id":"far fa-file"},{"name":"File alt","id":"fas fa-file-alt"},{"name":"File alt","id":"far fa-file-alt"},{"name":"File archive","id":"fas fa-file-archive"},{"name":"File archive","id":"far fa-file-archive"},{"name":"File audio","id":"fas fa-file-audio"},{"name":"File audio","id":"far fa-file-audio"},{"name":"File code","id":"fas fa-file-code"},{"name":"File code","id":"far fa-file-code"},{"name":"File excel","id":"fas fa-file-excel"},{"name":"File excel","id":"far fa-file-excel"},{"name":"File image","id":"fas fa-file-image"},{"name":"File image","id":"far fa-file-image"},{"name":"File pdf","id":"fas fa-file-pdf"},{"name":"File pdf","id":"far fa-file-pdf"},{"name":"File powerpoint","id":"fas fa-file-powerpoint"},{"name":"File powerpoint","id":"far fa-file-powerpoint"},{"name":"File video","id":"fas fa-file-video"},{"name":"File video","id":"far fa-file-video"},{"name":"File word","id":"fas fa-file-word"},{"name":"File word","id":"far fa-file-word"},{"name":"Film","id":"fas fa-film"},{"name":"Filter","id":"fas fa-filter"},{"name":"Fire","id":"fas fa-fire"},{"name":"Fire extinguisher","id":"fas fa-fire-extinguisher"},{"name":"Firefox","id":"fab fa-firefox"},{"name":"First order","id":"fab fa-first-order"},{"name":"Firstdraft","id":"fab fa-firstdraft"},{"name":"Flag","id":"fas fa-flag"},{"name":"Flag","id":"far fa-flag"},{"name":"Flag checkered","id":"fas fa-flag-checkered"},{"name":"Flask","id":"fas fa-flask"},{"name":"Flickr","id":"fab fa-flickr"},{"name":"Fly","id":"fab fa-fly"},{"name":"Folder","id":"fas fa-folder"},{"name":"Folder","id":"far fa-folder"},{"name":"Folder open","id":"fas fa-folder-open"},{"name":"Folder open","id":"far fa-folder-open"},{"name":"Font","id":"fas fa-font"},{"name":"Font awesome","id":"fab fa-font-awesome"},{"name":"Font awesome alt","id":"fab fa-font-awesome-alt"},{"name":"Font awesome flag","id":"fab fa-font-awesome-flag"},{"name":"Fonticons","id":"fab fa-fonticons"},{"name":"Fonticons fi","id":"fab fa-fonticons-fi"},{"name":"Fort awesome","id":"fab fa-fort-awesome"},{"name":"Fort awesome alt","id":"fab fa-fort-awesome-alt"},{"name":"Forumbee","id":"fab fa-forumbee"},{"name":"Forward","id":"fas fa-forward"},{"name":"Foursquare","id":"fab fa-foursquare"},{"name":"Free code camp","id":"fab fa-free-code-camp"},{"name":"Freebsd","id":"fab fa-freebsd"},{"name":"Frown","id":"fas fa-frown"},{"name":"Frown","id":"far fa-frown"},{"name":"Futbol","id":"fas fa-futbol"},{"name":"Futbol","id":"far fa-futbol"},{"name":"Gamepad","id":"fas fa-gamepad"},{"name":"Gavel","id":"fas fa-gavel"},{"name":"Gem","id":"fas fa-gem"},{"name":"Gem","id":"far fa-gem"},{"name":"Genderless","id":"fas fa-genderless"},{"name":"Get pocket","id":"fab fa-get-pocket"},{"name":"Gg","id":"fab fa-gg"},{"name":"Gg circle","id":"fab fa-gg-circle"},{"name":"Gift","id":"fas fa-gift"},{"name":"Git","id":"fab fa-git"},{"name":"Git square","id":"fab fa-git-square"},{"name":"Github","id":"fab fa-github"},{"name":"Github alt","id":"fab fa-github-alt"},{"name":"Github square","id":"fab fa-github-square"},{"name":"Gitkraken","id":"fab fa-gitkraken"},{"name":"Gitlab","id":"fab fa-gitlab"},{"name":"Gitter","id":"fab fa-gitter"},{"name":"Glass martini","id":"fas fa-glass-martini"},{"name":"Glide","id":"fab fa-glide"},{"name":"Glide g","id":"fab fa-glide-g"},{"name":"Globe","id":"fas fa-globe"},{"name":"Gofore","id":"fab fa-gofore"},{"name":"Goodreads","id":"fab fa-goodreads"},{"name":"Goodreads g","id":"fab fa-goodreads-g"},{"name":"Google","id":"fab fa-google"},{"name":"Google drive","id":"fab fa-google-drive"},{"name":"Google play","id":"fab fa-google-play"},{"name":"Google plus","id":"fab fa-google-plus"},{"name":"Google plus g","id":"fab fa-google-plus-g"},{"name":"Google plus square","id":"fab fa-google-plus-square"},{"name":"Google wallet","id":"fab fa-google-wallet"},{"name":"Graduation cap","id":"fas fa-graduation-cap"},{"name":"Gratipay","id":"fab fa-gratipay"},{"name":"Grav","id":"fab fa-grav"},{"name":"Gripfire","id":"fab fa-gripfire"},{"name":"Grunt","id":"fab fa-grunt"},{"name":"Gulp","id":"fab fa-gulp"},{"name":"H square","id":"fas fa-h-square"},{"name":"Hacker news","id":"fab fa-hacker-news"},{"name":"Hacker news square","id":"fab fa-hacker-news-square"},{"name":"Hand lizard","id":"fas fa-hand-lizard"},{"name":"Hand lizard","id":"far fa-hand-lizard"},{"name":"Hand paper","id":"fas fa-hand-paper"},{"name":"Hand paper","id":"far fa-hand-paper"},{"name":"Hand peace","id":"fas fa-hand-peace"},{"name":"Hand peace","id":"far fa-hand-peace"},{"name":"Hand point down","id":"fas fa-hand-point-down"},{"name":"Hand point down","id":"far fa-hand-point-down"},{"name":"Hand point left","id":"fas fa-hand-point-left"},{"name":"Hand point left","id":"far fa-hand-point-left"},{"name":"Hand point right","id":"fas fa-hand-point-right"},{"name":"Hand point right","id":"far fa-hand-point-right"},{"name":"Hand point up","id":"fas fa-hand-point-up"},{"name":"Hand point up","id":"far fa-hand-point-up"},{"name":"Hand pointer","id":"fas fa-hand-pointer"},{"name":"Hand pointer","id":"far fa-hand-pointer"},{"name":"Hand rock","id":"fas fa-hand-rock"},{"name":"Hand rock","id":"far fa-hand-rock"},{"name":"Hand scissors","id":"fas fa-hand-scissors"},{"name":"Hand scissors","id":"far fa-hand-scissors"},{"name":"Hand spock","id":"fas fa-hand-spock"},{"name":"Hand spock","id":"far fa-hand-spock"},{"name":"Handshake","id":"fas fa-handshake"},{"name":"Handshake","id":"far fa-handshake"},{"name":"Hashtag","id":"fas fa-hashtag"},{"name":"Hdd","id":"fas fa-hdd"},{"name":"Hdd","id":"far fa-hdd"},{"name":"Heading","id":"fas fa-heading"},{"name":"Headphones","id":"fas fa-headphones"},{"name":"Heart","id":"fas fa-heart"},{"name":"Heart","id":"far fa-heart"},{"name":"Heartbeat","id":"fas fa-heartbeat"},{"name":"Hire a helper","id":"fab fa-hire-a-helper"},{"name":"History","id":"fas fa-history"},{"name":"Home","id":"fas fa-home"},{"name":"Hooli","id":"fab fa-hooli"},{"name":"Hospital","id":"fas fa-hospital"},{"name":"Hospital","id":"far fa-hospital"},{"name":"Hotjar","id":"fab fa-hotjar"},{"name":"Hourglass","id":"fas fa-hourglass"},{"name":"Hourglass","id":"far fa-hourglass"},{"name":"Hourglass end","id":"fas fa-hourglass-end"},{"name":"Hourglass half","id":"fas fa-hourglass-half"},{"name":"Hourglass start","id":"fas fa-hourglass-start"},{"name":"Houzz","id":"fab fa-houzz"},{"name":"Html5","id":"fab fa-html5"},{"name":"Hubspot","id":"fab fa-hubspot"},{"name":"I cursor","id":"fas fa-i-cursor"},{"name":"Id badge","id":"fas fa-id-badge"},{"name":"Id badge","id":"far fa-id-badge"},{"name":"Id card","id":"fas fa-id-card"},{"name":"Id card","id":"far fa-id-card"},{"name":"Image","id":"fas fa-image"},{"name":"Image","id":"far fa-image"},{"name":"Images","id":"fas fa-images"},{"name":"Images","id":"far fa-images"},{"name":"Imdb","id":"fab fa-imdb"},{"name":"Inbox","id":"fas fa-inbox"},{"name":"Indent","id":"fas fa-indent"},{"name":"Industry","id":"fas fa-industry"},{"name":"Info","id":"fas fa-info"},{"name":"Info circle","id":"fas fa-info-circle"},{"name":"Instagram","id":"fab fa-instagram"},{"name":"Internet explorer","id":"fab fa-internet-explorer"},{"name":"Ioxhost","id":"fab fa-ioxhost"},{"name":"Italic","id":"fas fa-italic"},{"name":"Itunes","id":"fab fa-itunes"},{"name":"Itunes note","id":"fab fa-itunes-note"},{"name":"Jenkins","id":"fab fa-jenkins"},{"name":"Joget","id":"fab fa-joget"},{"name":"Joomla","id":"fab fa-joomla"},{"name":"Js","id":"fab fa-js"},{"name":"Js square","id":"fab fa-js-square"},{"name":"Jsfiddle","id":"fab fa-jsfiddle"},{"name":"Key","id":"fas fa-key"},{"name":"Keyboard","id":"fas fa-keyboard"},{"name":"Keyboard","id":"far fa-keyboard"},{"name":"Keycdn","id":"fab fa-keycdn"},{"name":"Kickstarter","id":"fab fa-kickstarter"},{"name":"Kickstarter k","id":"fab fa-kickstarter-k"},{"name":"Language","id":"fas fa-language"},{"name":"Laptop","id":"fas fa-laptop"},{"name":"Laravel","id":"fab fa-laravel"},{"name":"Lastfm","id":"fab fa-lastfm"},{"name":"Lastfm square","id":"fab fa-lastfm-square"},{"name":"Leaf","id":"fas fa-leaf"},{"name":"Leanpub","id":"fab fa-leanpub"},{"name":"Lemon","id":"fas fa-lemon"},{"name":"Lemon","id":"far fa-lemon"},{"name":"Less","id":"fab fa-less"},{"name":"Level down alt","id":"fas fa-level-down-alt"},{"name":"Level up alt","id":"fas fa-level-up-alt"},{"name":"Life ring","id":"fas fa-life-ring"},{"name":"Life ring","id":"far fa-life-ring"},{"name":"Lightbulb","id":"fas fa-lightbulb"},{"name":"Lightbulb","id":"far fa-lightbulb"},{"name":"Line","id":"fab fa-line"},{"name":"Link","id":"fas fa-link"},{"name":"Linkedin","id":"fab fa-linkedin"},{"name":"Linkedin in","id":"fab fa-linkedin-in"},{"name":"Linode","id":"fab fa-linode"},{"name":"Linux","id":"fab fa-linux"},{"name":"Lira sign","id":"fas fa-lira-sign"},{"name":"List","id":"fas fa-list"},{"name":"List alt","id":"fas fa-list-alt"},{"name":"List alt","id":"far fa-list-alt"},{"name":"List ol","id":"fas fa-list-ol"},{"name":"List ul","id":"fas fa-list-ul"},{"name":"Location arrow","id":"fas fa-location-arrow"},{"name":"Lock","id":"fas fa-lock"},{"name":"Lock open","id":"fas fa-lock-open"},{"name":"Long arrow alt down","id":"fas fa-long-arrow-alt-down"},{"name":"Long arrow alt left","id":"fas fa-long-arrow-alt-left"},{"name":"Long arrow alt right","id":"fas fa-long-arrow-alt-right"},{"name":"Long arrow alt up","id":"fas fa-long-arrow-alt-up"},{"name":"Low vision","id":"fas fa-low-vision"},{"name":"Lyft","id":"fab fa-lyft"},{"name":"Magento","id":"fab fa-magento"},{"name":"Magic","id":"fas fa-magic"},{"name":"Magnet","id":"fas fa-magnet"},{"name":"Male","id":"fas fa-male"},{"name":"Map","id":"fas fa-map"},{"name":"Map","id":"far fa-map"},{"name":"Map marker","id":"fas fa-map-marker"},{"name":"Map marker alt","id":"fas fa-map-marker-alt"},{"name":"Map pin","id":"fas fa-map-pin"},{"name":"Map signs","id":"fas fa-map-signs"},{"name":"Mars","id":"fas fa-mars"},{"name":"Mars double","id":"fas fa-mars-double"},{"name":"Mars stroke","id":"fas fa-mars-stroke"},{"name":"Mars stroke h","id":"fas fa-mars-stroke-h"},{"name":"Mars stroke v","id":"fas fa-mars-stroke-v"},{"name":"Maxcdn","id":"fab fa-maxcdn"},{"name":"Medapps","id":"fab fa-medapps"},{"name":"Medium","id":"fab fa-medium"},{"name":"Medium m","id":"fab fa-medium-m"},{"name":"Medkit","id":"fas fa-medkit"},{"name":"Medrt","id":"fab fa-medrt"},{"name":"Meetup","id":"fab fa-meetup"},{"name":"Meh","id":"fas fa-meh"},{"name":"Meh","id":"far fa-meh"},{"name":"Mercury","id":"fas fa-mercury"},{"name":"Microchip","id":"fas fa-microchip"},{"name":"Microphone","id":"fas fa-microphone"},{"name":"Microphone slash","id":"fas fa-microphone-slash"},{"name":"Microsoft","id":"fab fa-microsoft"},{"name":"Minus","id":"fas fa-minus"},{"name":"Minus circle","id":"fas fa-minus-circle"},{"name":"Minus square","id":"fas fa-minus-square"},{"name":"Minus square","id":"far fa-minus-square"},{"name":"Mix","id":"fab fa-mix"},{"name":"Mixcloud","id":"fab fa-mixcloud"},{"name":"Mizuni","id":"fab fa-mizuni"},{"name":"Mobile","id":"fas fa-mobile"},{"name":"Mobile alt","id":"fas fa-mobile-alt"},{"name":"Modx","id":"fab fa-modx"},{"name":"Monero","id":"fab fa-monero"},{"name":"Money bill alt","id":"fas fa-money-bill-alt"},{"name":"Money bill alt","id":"far fa-money-bill-alt"},{"name":"Moon","id":"fas fa-moon"},{"name":"Moon","id":"far fa-moon"},{"name":"Motorcycle","id":"fas fa-motorcycle"},{"name":"Mouse pointer","id":"fas fa-mouse-pointer"},{"name":"Music","id":"fas fa-music"},{"name":"Napster","id":"fab fa-napster"},{"name":"Neuter","id":"fas fa-neuter"},{"name":"Newspaper","id":"fas fa-newspaper"},{"name":"Newspaper","id":"far fa-newspaper"},{"name":"Nintendo switch","id":"fab fa-nintendo-switch"},{"name":"Node","id":"fab fa-node"},{"name":"Node js","id":"fab fa-node-js"},{"name":"Npm","id":"fab fa-npm"},{"name":"Ns8","id":"fab fa-ns8"},{"name":"Nutritionix","id":"fab fa-nutritionix"},{"name":"Object group","id":"fas fa-object-group"},{"name":"Object group","id":"far fa-object-group"},{"name":"Object ungroup","id":"fas fa-object-ungroup"},{"name":"Object ungroup","id":"far fa-object-ungroup"},{"name":"Odnoklassniki","id":"fab fa-odnoklassniki"},{"name":"Odnoklassniki square","id":"fab fa-odnoklassniki-square"},{"name":"Opencart","id":"fab fa-opencart"},{"name":"Openid","id":"fab fa-openid"},{"name":"Opera","id":"fab fa-opera"},{"name":"Optin monster","id":"fab fa-optin-monster"},{"name":"Osi","id":"fab fa-osi"},{"name":"Outdent","id":"fas fa-outdent"},{"name":"Page4","id":"fab fa-page4"},{"name":"Pagelines","id":"fab fa-pagelines"},{"name":"Paint brush","id":"fas fa-paint-brush"},{"name":"Palfed","id":"fab fa-palfed"},{"name":"Paper plane","id":"fas fa-paper-plane"},{"name":"Paper plane","id":"far fa-paper-plane"},{"name":"Paperclip","id":"fas fa-paperclip"},{"name":"Paragraph","id":"fas fa-paragraph"},{"name":"Paste","id":"fas fa-paste"},{"name":"Patreon","id":"fab fa-patreon"},{"name":"Pause","id":"fas fa-pause"},{"name":"Pause circle","id":"fas fa-pause-circle"},{"name":"Pause circle","id":"far fa-pause-circle"},{"name":"Paw","id":"fas fa-paw"},{"name":"Paypal","id":"fab fa-paypal"},{"name":"Pen square","id":"fas fa-pen-square"},{"name":"Pencil alt","id":"fas fa-pencil-alt"},{"name":"Percent","id":"fas fa-percent"},{"name":"Periscope","id":"fab fa-periscope"},{"name":"Phabricator","id":"fab fa-phabricator"},{"name":"Phoenix framework","id":"fab fa-phoenix-framework"},{"name":"Phone","id":"fas fa-phone"},{"name":"Phone square","id":"fas fa-phone-square"},{"name":"Phone volume","id":"fas fa-phone-volume"},{"name":"Pied piper","id":"fab fa-pied-piper"},{"name":"Pied piper alt","id":"fab fa-pied-piper-alt"},{"name":"Pied piper pp","id":"fab fa-pied-piper-pp"},{"name":"Pinterest","id":"fab fa-pinterest"},{"name":"Pinterest p","id":"fab fa-pinterest-p"},{"name":"Pinterest square","id":"fab fa-pinterest-square"},{"name":"Plane","id":"fas fa-plane"},{"name":"Play","id":"fas fa-play"},{"name":"Play circle","id":"fas fa-play-circle"},{"name":"Play circle","id":"far fa-play-circle"},{"name":"Playstation","id":"fab fa-playstation"},{"name":"Plug","id":"fas fa-plug"},{"name":"Plus","id":"fas fa-plus"},{"name":"Plus circle","id":"fas fa-plus-circle"},{"name":"Plus square","id":"fas fa-plus-square"},{"name":"Plus square","id":"far fa-plus-square"},{"name":"Podcast","id":"fas fa-podcast"},{"name":"Pound sign","id":"fas fa-pound-sign"},{"name":"Power off","id":"fas fa-power-off"},{"name":"Print","id":"fas fa-print"},{"name":"Product hunt","id":"fab fa-product-hunt"},{"name":"Pushed","id":"fab fa-pushed"},{"name":"Puzzle piece","id":"fas fa-puzzle-piece"},{"name":"Python","id":"fab fa-python"},{"name":"Qq","id":"fab fa-qq"},{"name":"Qrcode","id":"fas fa-qrcode"},{"name":"Question","id":"fas fa-question"},{"name":"Question circle","id":"fas fa-question-circle"},{"name":"Question circle","id":"far fa-question-circle"},{"name":"Quora","id":"fab fa-quora"},{"name":"Quote left","id":"fas fa-quote-left"},{"name":"Quote right","id":"fas fa-quote-right"},{"name":"Random","id":"fas fa-random"},{"name":"Ravelry","id":"fab fa-ravelry"},{"name":"React","id":"fab fa-react"},{"name":"Rebel","id":"fab fa-rebel"},{"name":"Recycle","id":"fas fa-recycle"},{"name":"Red river","id":"fab fa-red-river"},{"name":"Reddit","id":"fab fa-reddit"},{"name":"Reddit alien","id":"fab fa-reddit-alien"},{"name":"Reddit square","id":"fab fa-reddit-square"},{"name":"Redo","id":"fas fa-redo"},{"name":"Redo alt","id":"fas fa-redo-alt"},{"name":"Registered","id":"fas fa-registered"},{"name":"Registered","id":"far fa-registered"},{"name":"Rendact","id":"fab fa-rendact"},{"name":"Renren","id":"fab fa-renren"},{"name":"Reply","id":"fas fa-reply"},{"name":"Reply all","id":"fas fa-reply-all"},{"name":"Replyd","id":"fab fa-replyd"},{"name":"Resolving","id":"fab fa-resolving"},{"name":"Retweet","id":"fas fa-retweet"},{"name":"Road","id":"fas fa-road"},{"name":"Rocket","id":"fas fa-rocket"},{"name":"Rocketchat","id":"fab fa-rocketchat"},{"name":"Rockrms","id":"fab fa-rockrms"},{"name":"Rss","id":"fas fa-rss"},{"name":"Rss square","id":"fas fa-rss-square"},{"name":"Ruble sign","id":"fas fa-ruble-sign"},{"name":"Rupee sign","id":"fas fa-rupee-sign"},{"name":"Safari","id":"fab fa-safari"},{"name":"Sass","id":"fab fa-sass"},{"name":"Save","id":"fas fa-save"},{"name":"Save","id":"far fa-save"},{"name":"Schlix","id":"fab fa-schlix"},{"name":"Scribd","id":"fab fa-scribd"},{"name":"Search","id":"fas fa-search"},{"name":"Search minus","id":"fas fa-search-minus"},{"name":"Search plus","id":"fas fa-search-plus"},{"name":"Searchengin","id":"fab fa-searchengin"},{"name":"Sellcast","id":"fab fa-sellcast"},{"name":"Sellsy","id":"fab fa-sellsy"},{"name":"Server","id":"fas fa-server"},{"name":"Servicestack","id":"fab fa-servicestack"},{"name":"Share","id":"fas fa-share"},{"name":"Share alt","id":"fas fa-share-alt"},{"name":"Share alt square","id":"fas fa-share-alt-square"},{"name":"Share square","id":"fas fa-share-square"},{"name":"Share square","id":"far fa-share-square"},{"name":"Shekel sign","id":"fas fa-shekel-sign"},{"name":"Shield alt","id":"fas fa-shield-alt"},{"name":"Ship","id":"fas fa-ship"},{"name":"Shirtsinbulk","id":"fab fa-shirtsinbulk"},{"name":"Shopping bag","id":"fas fa-shopping-bag"},{"name":"Shopping basket","id":"fas fa-shopping-basket"},{"name":"Shopping cart","id":"fas fa-shopping-cart"},{"name":"Shower","id":"fas fa-shower"},{"name":"Sign in alt","id":"fas fa-sign-in-alt"},{"name":"Sign language","id":"fas fa-sign-language"},{"name":"Sign out alt","id":"fas fa-sign-out-alt"},{"name":"Signal","id":"fas fa-signal"},{"name":"Simplybuilt","id":"fab fa-simplybuilt"},{"name":"Sistrix","id":"fab fa-sistrix"},{"name":"Sitemap","id":"fas fa-sitemap"},{"name":"Skyatlas","id":"fab fa-skyatlas"},{"name":"Skype","id":"fab fa-skype"},{"name":"Slack","id":"fab fa-slack"},{"name":"Slack hash","id":"fab fa-slack-hash"},{"name":"Sliders h","id":"fas fa-sliders-h"},{"name":"Slideshare","id":"fab fa-slideshare"},{"name":"Smile","id":"fas fa-smile"},{"name":"Smile","id":"far fa-smile"},{"name":"Snapchat","id":"fab fa-snapchat"},{"name":"Snapchat ghost","id":"fab fa-snapchat-ghost"},{"name":"Snapchat square","id":"fab fa-snapchat-square"},{"name":"Snowflake","id":"fas fa-snowflake"},{"name":"Snowflake","id":"far fa-snowflake"},{"name":"Sort","id":"fas fa-sort"},{"name":"Sort alpha down","id":"fas fa-sort-alpha-down"},{"name":"Sort alpha up","id":"fas fa-sort-alpha-up"},{"name":"Sort amount down","id":"fas fa-sort-amount-down"},{"name":"Sort amount up","id":"fas fa-sort-amount-up"},{"name":"Sort down","id":"fas fa-sort-down"},{"name":"Sort numeric down","id":"fas fa-sort-numeric-down"},{"name":"Sort numeric up","id":"fas fa-sort-numeric-up"},{"name":"Sort up","id":"fas fa-sort-up"},{"name":"Soundcloud","id":"fab fa-soundcloud"},{"name":"Space shuttle","id":"fas fa-space-shuttle"},{"name":"Speakap","id":"fab fa-speakap"},{"name":"Spinner","id":"fas fa-spinner"},{"name":"Spotify","id":"fab fa-spotify"},{"name":"Square","id":"fas fa-square"},{"name":"Square","id":"far fa-square"},{"name":"Stack exchange","id":"fab fa-stack-exchange"},{"name":"Stack overflow","id":"fab fa-stack-overflow"},{"name":"Star","id":"fas fa-star"},{"name":"Star","id":"far fa-star"},{"name":"Star half","id":"fas fa-star-half"},{"name":"Star half","id":"far fa-star-half"},{"name":"Staylinked","id":"fab fa-staylinked"},{"name":"Steam","id":"fab fa-steam"},{"name":"Steam square","id":"fab fa-steam-square"},{"name":"Steam symbol","id":"fab fa-steam-symbol"},{"name":"Step backward","id":"fas fa-step-backward"},{"name":"Step forward","id":"fas fa-step-forward"},{"name":"Stethoscope","id":"fas fa-stethoscope"},{"name":"Sticker mule","id":"fab fa-sticker-mule"},{"name":"Sticky note","id":"fas fa-sticky-note"},{"name":"Sticky note","id":"far fa-sticky-note"},{"name":"Stop","id":"fas fa-stop"},{"name":"Stop circle","id":"fas fa-stop-circle"},{"name":"Stop circle","id":"far fa-stop-circle"},{"name":"Strava","id":"fab fa-strava"},{"name":"Street view","id":"fas fa-street-view"},{"name":"Strikethrough","id":"fas fa-strikethrough"},{"name":"Stripe","id":"fab fa-stripe"},{"name":"Stripe s","id":"fab fa-stripe-s"},{"name":"Studiovinari","id":"fab fa-studiovinari"},{"name":"Stumbleupon","id":"fab fa-stumbleupon"},{"name":"Stumbleupon circle","id":"fab fa-stumbleupon-circle"},{"name":"Subscript","id":"fas fa-subscript"},{"name":"Subway","id":"fas fa-subway"},{"name":"Suitcase","id":"fas fa-suitcase"},{"name":"Sun","id":"fas fa-sun"},{"name":"Sun","id":"far fa-sun"},{"name":"Superpowers","id":"fab fa-superpowers"},{"name":"Superscript","id":"fas fa-superscript"},{"name":"Supple","id":"fab fa-supple"},{"name":"Sync","id":"fas fa-sync"},{"name":"Sync alt","id":"fas fa-sync-alt"},{"name":"Table","id":"fas fa-table"},{"name":"Tablet","id":"fas fa-tablet"},{"name":"Tablet alt","id":"fas fa-tablet-alt"},{"name":"Tachometer alt","id":"fas fa-tachometer-alt"},{"name":"Tag","id":"fas fa-tag"},{"name":"Tags","id":"fas fa-tags"},{"name":"Tasks","id":"fas fa-tasks"},{"name":"Taxi","id":"fas fa-taxi"},{"name":"Telegram","id":"fab fa-telegram"},{"name":"Telegram plane","id":"fab fa-telegram-plane"},{"name":"Tencent weibo","id":"fab fa-tencent-weibo"},{"name":"Terminal","id":"fas fa-terminal"},{"name":"Text height","id":"fas fa-text-height"},{"name":"Text width","id":"fas fa-text-width"},{"name":"Th","id":"fas fa-th"},{"name":"Th large","id":"fas fa-th-large"},{"name":"Th list","id":"fas fa-th-list"},{"name":"Themeisle","id":"fab fa-themeisle"},{"name":"Thermometer empty","id":"fas fa-thermometer-empty"},{"name":"Thermometer full","id":"fas fa-thermometer-full"},{"name":"Thermometer half","id":"fas fa-thermometer-half"},{"name":"Thermometer quarter","id":"fas fa-thermometer-quarter"},{"name":"Thermometer three quarters","id":"fas fa-thermometer-three-quarters"},{"name":"Thumbs down","id":"fas fa-thumbs-down"},{"name":"Thumbs down","id":"far fa-thumbs-down"},{"name":"Thumbs up","id":"fas fa-thumbs-up"},{"name":"Thumbs up","id":"far fa-thumbs-up"},{"name":"Thumbtack","id":"fas fa-thumbtack"},{"name":"Ticket alt","id":"fas fa-ticket-alt"},{"name":"Times","id":"fas fa-times"},{"name":"Times circle","id":"fas fa-times-circle"},{"name":"Times circle","id":"far fa-times-circle"},{"name":"Tint","id":"fas fa-tint"},{"name":"Toggle off","id":"fas fa-toggle-off"},{"name":"Toggle on","id":"fas fa-toggle-on"},{"name":"Trademark","id":"fas fa-trademark"},{"name":"Train","id":"fas fa-train"},{"name":"Transgender","id":"fas fa-transgender"},{"name":"Transgender alt","id":"fas fa-transgender-alt"},{"name":"Trash","id":"fas fa-trash"},{"name":"Trash alt","id":"fas fa-trash-alt"},{"name":"Trash alt","id":"far fa-trash-alt"},{"name":"Tree","id":"fas fa-tree"},{"name":"Trello","id":"fab fa-trello"},{"name":"Tripadvisor","id":"fab fa-tripadvisor"},{"name":"Trophy","id":"fas fa-trophy"},{"name":"Truck","id":"fas fa-truck"},{"name":"Tty","id":"fas fa-tty"},{"name":"Tumblr","id":"fab fa-tumblr"},{"name":"Tumblr square","id":"fab fa-tumblr-square"},{"name":"Tv","id":"fas fa-tv"},{"name":"Twitch","id":"fab fa-twitch"},{"name":"Twitter","id":"fab fa-twitter"},{"name":"Twitter square","id":"fab fa-twitter-square"},{"name":"Typo3","id":"fab fa-typo3"},{"name":"Uber","id":"fab fa-uber"},{"name":"Uikit","id":"fab fa-uikit"},{"name":"Umbrella","id":"fas fa-umbrella"},{"name":"Underline","id":"fas fa-underline"},{"name":"Undo","id":"fas fa-undo"},{"name":"Undo alt","id":"fas fa-undo-alt"},{"name":"Uniregistry","id":"fab fa-uniregistry"},{"name":"Universal access","id":"fas fa-universal-access"},{"name":"University","id":"fas fa-university"},{"name":"Unlink","id":"fas fa-unlink"},{"name":"Unlock","id":"fas fa-unlock"},{"name":"Unlock alt","id":"fas fa-unlock-alt"},{"name":"Untappd","id":"fab fa-untappd"},{"name":"Upload","id":"fas fa-upload"},{"name":"Usb","id":"fab fa-usb"},{"name":"User","id":"fas fa-user"},{"name":"User","id":"far fa-user"},{"name":"User circle","id":"fas fa-user-circle"},{"name":"User circle","id":"far fa-user-circle"},{"name":"User md","id":"fas fa-user-md"},{"name":"User plus","id":"fas fa-user-plus"},{"name":"User secret","id":"fas fa-user-secret"},{"name":"User times","id":"fas fa-user-times"},{"name":"Users","id":"fas fa-users"},{"name":"Ussunnah","id":"fab fa-ussunnah"},{"name":"Utensil spoon","id":"fas fa-utensil-spoon"},{"name":"Utensils","id":"fas fa-utensils"},{"name":"Vaadin","id":"fab fa-vaadin"},{"name":"Venus","id":"fas fa-venus"},{"name":"Venus double","id":"fas fa-venus-double"},{"name":"Venus mars","id":"fas fa-venus-mars"},{"name":"Viacoin","id":"fab fa-viacoin"},{"name":"Viadeo","id":"fab fa-viadeo"},{"name":"Viadeo square","id":"fab fa-viadeo-square"},{"name":"Viber","id":"fab fa-viber"},{"name":"Video","id":"fas fa-video"},{"name":"Vimeo","id":"fab fa-vimeo"},{"name":"Vimeo square","id":"fab fa-vimeo-square"},{"name":"Vimeo v","id":"fab fa-vimeo-v"},{"name":"Vine","id":"fab fa-vine"},{"name":"Vk","id":"fab fa-vk"},{"name":"Vnv","id":"fab fa-vnv"},{"name":"Volume down","id":"fas fa-volume-down"},{"name":"Volume off","id":"fas fa-volume-off"},{"name":"Volume up","id":"fas fa-volume-up"},{"name":"Vuejs","id":"fab fa-vuejs"},{"name":"Weibo","id":"fab fa-weibo"},{"name":"Weixin","id":"fab fa-weixin"},{"name":"Whatsapp","id":"fab fa-whatsapp"},{"name":"Whatsapp square","id":"fab fa-whatsapp-square"},{"name":"Wheelchair","id":"fas fa-wheelchair"},{"name":"Whmcs","id":"fab fa-whmcs"},{"name":"Wifi","id":"fas fa-wifi"},{"name":"Wikipedia w","id":"fab fa-wikipedia-w"},{"name":"Window close","id":"fas fa-window-close"},{"name":"Window close","id":"far fa-window-close"},{"name":"Window maximize","id":"fas fa-window-maximize"},{"name":"Window maximize","id":"far fa-window-maximize"},{"name":"Window minimize","id":"fas fa-window-minimize"},{"name":"Window restore","id":"fas fa-window-restore"},{"name":"Window restore","id":"far fa-window-restore"},{"name":"Windows","id":"fab fa-windows"},{"name":"Won sign","id":"fas fa-won-sign"},{"name":"Wordpress","id":"fab fa-wordpress"},{"name":"Wordpress simple","id":"fab fa-wordpress-simple"},{"name":"Wpbeginner","id":"fab fa-wpbeginner"},{"name":"Wpexplorer","id":"fab fa-wpexplorer"},{"name":"Wpforms","id":"fab fa-wpforms"},{"name":"Wrench","id":"fas fa-wrench"},{"name":"Xbox","id":"fab fa-xbox"},{"name":"Xing","id":"fab fa-xing"},{"name":"Xing square","id":"fab fa-xing-square"},{"name":"Y combinator","id":"fab fa-y-combinator"},{"name":"Yahoo","id":"fab fa-yahoo"},{"name":"Yandex","id":"fab fa-yandex"},{"name":"Yandex international","id":"fab fa-yandex-international"},{"name":"Yelp","id":"fab fa-yelp"},{"name":"Yen sign","id":"fas fa-yen-sign"},{"name":"Yoast","id":"fab fa-yoast"},{"name":"Youtube","id":"fab fa-youtube"}]}';

	$icons_arr = json_decode( $icons );
	$temp_arr = array();

	foreach ( $icons_arr->icons as $key => $value) {
		$temp_arr[$value->id] = $value->name;
	}

	return $temp_arr;

}