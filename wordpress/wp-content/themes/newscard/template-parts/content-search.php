<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package NewsCard
 */

?>

<?php global $newscard_settings; ?>
<div class="col-sm-6 col-xxl-4 post-col">
	<div <?php post_class(); ?>>

		<?php if ( has_post_thumbnail() ) { ?>

			<figure class="post-featured-image post-img-wrap">
				<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>" class="post-img" <?php if ( has_post_thumbnail() ) { ?> style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');" <?php } ?>></a>
				<?php if ( 'post' === get_post_type() ) { ?>
					<div class="entry-meta category-meta">
						<div class="cat-links"><?php the_category(' '); ?></div>
					</div><!-- .entry-meta -->
				<?php } ?>
			</figure><!-- .post-featured-image .post-img-wrap -->

		<?php } elseif ( 'post' === get_post_type() ) { ?>

			<div class="entry-meta category-meta">
				<div class="cat-links"><?php the_category(' '); ?></div>
			</div><!-- .entry-meta -->

		<?php } ?>

		<?php if ( !has_post_format( 'quote' ) ) { // for not format quote ?>

			<header class="entry-header">

				<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() && !has_post_format('link') ) { ?>

					<div class="entry-meta">
						<?php newscard_posted_on(); ?>
						<?php if ( comments_open() && get_comments_number() ) { ?>
							<div class="comments">
								<?php comments_popup_link( __('No Comments', 'newscard'), __('1 Comment', 'newscard'), __('% Comments', 'newscard'), '', __('Comments Off', 'newscard') ); ?>
							</div><!-- .comments -->
						<?php } ?>
					</div><!-- .entry-meta -->

				<?php } ?>
			</header><!-- .entry-header -->

		<?php } ?>

		<div class="entry-content">

			<?php if ( !(has_post_format('link') || has_post_format('quote')) ) { ?>

				<p><?php echo wp_trim_words( get_the_excerpt(), 16 ); ?></p>

			<?php } else {

				the_content();

			} ?>

		</div><!-- .entry-content -->
	</div><!-- .post-<?php the_ID(); ?> -->
</div><!-- .col-sm-6 .col-xxl-4 .post-col -->
