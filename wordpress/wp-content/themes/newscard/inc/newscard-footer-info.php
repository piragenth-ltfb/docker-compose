<?php
/**
 * Contains all current date, year and link of the theme
 *
 *
 * @package NewsCard
 */
?>
<?php
/**
 * To display the current year.
 *
 */
function newscard_the_year() {
	return date_i18n( 'Y' );
}
/**
 * To display a link back to the site.
 *
 */
function newscard_site_link() {
	return ' <a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" >' . get_bloginfo( 'name', 'display' ) . '</a>';
}
/**
 * To display a link to WordPress.org.
 *
 */
function newscard_wp_link() {
	return '<div class="wp-link">' .
		sprintf(
			esc_html__('Proudly Powered by: %s', 'newscard'),
			'<a href="' . esc_url('http://wordpress.org/') . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('WordPress', 'newscard') . '">' . esc_html__('WordPress', 'newscard') . '</a>'
		) . '</div>';
}
/**
 * To display a link to author.
 *
 */
function newscard_author_link() {
	return '<div class="author-link">' .
		sprintf(
			esc_html__('Theme by: %s', 'newscard'),
			'<a href="' . esc_url('https://www.themehorse.com') . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Theme Horse', 'newscard') . '" >' . esc_html__('Theme Horse', 'newscard') . '</a>'
		) . '</div>';
}
