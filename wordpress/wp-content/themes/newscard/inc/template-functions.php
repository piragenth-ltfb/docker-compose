<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package NewsCard
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function newscard_body_classes( $classes ) {
	$newscard_settings = newscard_get_option_defaults();

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( has_header_video() && has_header_image() ) {
		if ( is_front_page() && is_home() ) {
			$classes[] = '';
		} elseif ( is_front_page() ) {
			$classes[] = '';
		} else {
			$classes[] = 'header-image';
		}
	} elseif ( has_header_image() ) {
		$classes[] = 'header-image';
	}

	return $classes;
}
add_filter( 'body_class', 'newscard_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function newscard_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'newscard_pingback_header' );

function newscard_sidebar_content() {

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
			if ( 'right' == $newscard_custom_layout ) {
				get_sidebar(); //used sidebar.php
			}
			elseif ( 'left' == $newscard_custom_layout ) {
				get_sidebar('left'); //used sidebar-left.php
			}
			else {
				return; // doesnot display sidebar
			}
		}
		elseif ( 'meta-right' == $newscard_meta_layout ) {
			get_sidebar(); //used sidebar.php
		}
		elseif ( 'meta-left' == $newscard_meta_layout ) {
			get_sidebar('left'); //used sidebar-left.php
		}
		else {
			return; // doesnot display sidebar
		}
}
add_action( 'newscard_sidebar', 'newscard_sidebar_content');
