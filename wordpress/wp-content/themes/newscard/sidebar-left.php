<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package NewsCard
 */

if ( ! is_active_sidebar( 'newscard_left_sidebar' ) ) {
	return;
}
?>

<aside id="secondary" class="col-lg-4 widget-area order-lg-1" role="complementary">
	<div class="sticky-sidebar">
		<?php dynamic_sidebar( 'newscard_left_sidebar' ); ?>
	</div><!-- .sticky-sidebar -->
</aside><!-- #secondary -->
