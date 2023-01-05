<?php
/**
 * NewsCard functions and definitions
 *
 * This file contains all the functions and it's defination that particularly can't be
 * in other files.
 *
 * @package NewsCard
 */

/**
 * Default Option
 */
function newscard_get_option_defaults() {
	$newscard_array_of_default_settings = array(
		'newscard_content_layout' 								=> get_theme_mod('newscard_content_layout','right'),
		'newscard_nav_uppercase'								=> get_theme_mod('newscard_nav_uppercase',1),
		'newscard_breadcrumbs_hide'								=> get_theme_mod('newscard_breadcrumbs_hide',0),
		'newscard_top_bar_hide'									=> get_theme_mod('newscard_top_bar_hide',0),
		'newscard_social_profiles'								=> get_theme_mod('newscard_social_profiles',''),
		'newscard_top_bar_social_profiles'						=> get_theme_mod('newscard_top_bar_social_profiles',0),
		'newscard_header_bg_overlay' 							=> get_theme_mod('newscard_header_bg_overlay','none'),
		'newscard_header_background'							=> get_theme_mod('newscard_header_background',''),
		'newscard_header_add_image'								=> get_theme_mod('newscard_header_add_image',''),
		'newscard_header_add_link'								=> get_theme_mod('newscard_header_add_link',''),
		'newscard_top_stories_hide'								=> get_theme_mod('newscard_top_stories_hide', 0),
		'newscard_top_stories_title'							=> get_theme_mod('newscard_top_stories_title', 'Top Stories'),
		'newscard_top_stories_latest_post'						=> get_theme_mod('newscard_top_stories_latest_post', 'latest'),
		'newscard_top_stories_categories'						=> get_theme_mod('newscard_top_stories_categories', array()),
		'newscard_banner_display'								=> get_theme_mod('newscard_banner_display', 'front-blog'),
		'newscard_banner_slider_posts_hide'						=> get_theme_mod('newscard_banner_slider_posts_hide', 0),
		'newscard_banner_slider_posts_title'					=> get_theme_mod('newscard_banner_slider_posts_title', 'Main Stories'),
		'newscard_banner_slider_latest_post'					=> get_theme_mod('newscard_banner_slider_latest_post', 'latest'),
		'newscard_banner_slider_post_categories' 				=> get_theme_mod('newscard_banner_slider_post_categories', array()),
		'newscard_banner_featured_posts_1_hide'					=> get_theme_mod('newscard_banner_featured_posts_1_hide', 0),
		'newscard_banner_featured_posts_1_title'				=> get_theme_mod('newscard_banner_featured_posts_1_title', 'Editor\'s Pick'),
		'newscard_banner_featured_posts_1_latest_post'			=> get_theme_mod('newscard_banner_featured_posts_1_latest_post', 'latest'),
		'newscard_banner_featured_posts_1_post_categories' 		=> get_theme_mod('newscard_banner_featured_posts_1_post_categories', array()),
		'newscard_banner_featured_posts_2_hide'					=> get_theme_mod('newscard_banner_featured_posts_2_hide', 0),
		'newscard_banner_featured_posts_2_title'				=> get_theme_mod('newscard_banner_featured_posts_2_title', 'Trending Stories'),
		'newscard_banner_featured_posts_2_latest_post'			=> get_theme_mod('newscard_banner_featured_posts_2_latest_post', 'latest'),
		'newscard_banner_featured_posts_2_post_categories' 		=> get_theme_mod('newscard_banner_featured_posts_2_post_categories', array()),
		'newscard_header_featured_posts_hide'					=> get_theme_mod('newscard_header_featured_posts_hide', 0),
		'newscard_header_featured_posts_banner_display'			=> get_theme_mod('newscard_header_featured_posts_banner_display', 'front-blog'),
		'newscard_header_featured_posts_title'					=> get_theme_mod('newscard_header_featured_posts_title', 'Popular Stories'),
		'newscard_header_featured_latest_post'					=> get_theme_mod('newscard_header_featured_latest_post', 'latest'),
		'newscard_header_featured_post_categories'				=> get_theme_mod('newscard_header_featured_post_categories', array()),
		'newscard_footer_featured_posts_hide'					=> get_theme_mod('newscard_footer_featured_posts_hide', 0),
		'newscard_footer_featured_posts_title'					=> get_theme_mod('newscard_footer_featured_posts_title', 'You may Missed'),
		'newscard_footer_featured_latest_post'					=> get_theme_mod('newscard_footer_featured_latest_post', 'latest'),
		'newscard_footer_featured_post_categories'				=> get_theme_mod('newscard_footer_featured_post_categories', array()),
		'newscard_featured_image_page'							=> get_theme_mod('newscard_featured_image_page', 0),
		'newscard_featured_image_single'						=> get_theme_mod('newscard_featured_image_single', 0),
		'newscard_archive_title_label_hide'						=> get_theme_mod('newscard_archive_title_label_hide', 0),
	);
	return apply_filters( 'newscard_get_option_defaults', $newscard_array_of_default_settings );
}

if ( !function_exists( 'newscard_social_profiles' ) ) {
	/**
	 * Functions for Social Profiles.
	 */
	function newscard_social_profiles() {
		$newscard_settings = newscard_get_option_defaults(); ?>

		<ul class="clearfix">
			<?php $social_arr = explode(',',$newscard_settings['newscard_social_profiles']);
			foreach ($social_arr as $value) { ?>
				<li><a target="_blank" href="<?php echo esc_url(trim($value)); ?>"></a></li>
			<?php } ?>
		</ul>
	<?php }
}

if ( !function_exists('newscard_layout_primary') ) {
	/**
	 * Functions for Sidebars.
	 */
	function newscard_layout_primary() {
		$newscard_settings = newscard_get_option_defaults();
		global $post;

		if ($post) {
			$newscard_meta_layout = get_post_meta($post->ID, 'newscard_sidebarlayout', true);
		}
		$newscard_custom_layout = $newscard_settings['newscard_content_layout'];

		if ( empty($newscard_meta_layout) || is_archive() || is_search() || is_home() ) {
			$newscard_meta_layout = 'default';
		}

		if ( 'default' == $newscard_meta_layout ) {
			if ( ('right' == $newscard_custom_layout) || ('nosidebar' == $newscard_custom_layout) ) {
				$class = 'col-lg-8 ';
			}
			elseif ( 'left' == $newscard_custom_layout ) {
				$class = 'col-lg-8 order-lg-2 ';
			}
			elseif ( 'fullwidth' == $newscard_custom_layout ) {
				$class = 'col-lg-12 ';
			}
		}
		elseif ( ('meta-right' == $newscard_meta_layout) || ('meta-nosidebar' == $newscard_meta_layout) ) {
			$class = 'col-lg-8 ';
		}
		elseif ( 'meta-left' == $newscard_meta_layout ) {
			$class = 'col-lg-8 order-lg-2 ';
		}
		elseif ( 'meta-fullwidth' == $newscard_meta_layout ) {
			$class = 'col-lg-12 ';
		}

		echo '<div id="primary" class="' . $class . 'content-area">';

	}
}

if ( ! function_exists( 'newscard_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function newscard_posted_on() {

		$time_string = get_the_time( get_option( 'date_format' ) );

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" title="'. the_title_attribute('echo=0') . '">' . esc_html( $time_string ) . '</a> ';

		$byline = '<a href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ) . '">' . esc_html( get_the_author() ) . '</a> ';

		echo '<div class="date">' . $posted_on . '</div> <div class="by-author vcard author">' . $byline . '</div>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'newscard_breadcrumbs' ) ) :
	/**
	 * Simple Breadcrumbs.
	 *
	 * @since 1.1.1
	 */
	function newscard_breadcrumbs() {
		if ( ! function_exists( 'breadcrumb_trail' ) ) {
			require_once get_template_directory() . '/assets/library/breadcrumbs/breadcrumbs.php';
		}
		$args = array(
			'container'   => 'div',
			'show_browse' => false,
		);
		breadcrumb_trail($args);
	}

endif;


if ( ! function_exists( 'newscard_register_required_plugins' ) ) :
	/**
	 * Register the required plugins for this theme.
	 *
	 */
	function newscard_register_required_plugins() {

		$plugins = array(
			array(
				'name'     => esc_html__( 'One Click Demo Import', 'newscard' ),
				'slug'     => 'one-click-demo-import',
				'required' => false,
			),
		);

		tgmpa( $plugins );

	}
endif;

add_action( 'tgmpa_register', 'newscard_register_required_plugins' );

if ( ! function_exists( 'newscard_ocdi_after_import' ) ) :
	/**
	 * function to import/export demo data
	 */
	function newscard_ocdi_after_import() {

		// Set static front page and posts page
		$front_page = 'Home';
		$blog_page  = 'Blog';
		update_option( 'show_on_front', 'page' );

		$pages = array(
			'page_on_front'  => $front_page,
			'page_for_posts' => $blog_page,
		);

		foreach ( $pages as $option_key => $slug ) {
			$result = get_page_by_title( $slug );
			if ( $result ) {
				if ( is_array( $result ) ) {
					$object = array_shift( $result );
				} else {
					$object = $result;
				}

				update_option( $option_key, $object->ID );
			}
		}

		// Assign navigation menu locations.
		$menu_details = array(
			'primary'			=> 'main-menu',
			'right-section'     => 'top-right-menu',
		);

		if ( !empty($menu_details) ) {
			$nav_settings  = array();
			$current_menus = wp_get_nav_menus();

			if ( !empty( $current_menus ) && !is_wp_error( $current_menus ) ) {
				foreach ( $current_menus as $menu ) {
					foreach ( $menu_details as $location => $menu_slug ) {
						if ( $menu->slug === $menu_slug ) {
							$nav_settings[ $location ] = $menu->term_id;
						}
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $nav_settings );
		}
	}
endif;

add_action( 'pt-ocdi/after_import', 'newscard_ocdi_after_import' );

// Disable PT branding.
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
