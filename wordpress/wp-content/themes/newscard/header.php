<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package NewsCard
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class('theme-body'); ?>>
<?php wp_body_open();
global $newscard_settings;
$newscard_settings = newscard_get_option_defaults(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'newscard' ); ?></a>
	<?php if (has_header_video() || has_header_image()) {
		the_custom_header_markup();
	} ?>

	<header id="masthead" class="site-header">
		<?php if ( $newscard_settings['newscard_top_bar_hide'] == 0 ) { ?>
			<div class="info-bar<?php echo ( has_nav_menu('right-section') ) ? ' infobar-links-on' : ''; ?>">
				<div class="container">
					<div class="row gutter-10">
						<div class="col col-sm contact-section">
							<div class="date">
								<ul><li><?php echo esc_html(date_i18n("l, F j, Y")); ?></li></ul>
							</div>
						</div><!-- .contact-section -->

						<?php if ( $newscard_settings['newscard_social_profiles'] != '' && $newscard_settings['newscard_top_bar_social_profiles'] === 0 ) { ?>
							<div class="col-auto social-profiles order-md-3">
								<?php echo esc_html( newscard_social_profiles() ); ?>
							</div><!-- .social-profile -->
						<?php }

						if ( has_nav_menu('right-section') ) { ?>
							<div class="col-md-auto infobar-links order-md-2">
								<button class="infobar-links-menu-toggle"><?php esc_html_e('Responsive Menu', 'newscard' ); ?></button>
								<?php wp_nav_menu( array(
									'theme_location'	=> 'right-section',
									'container'			=> '',
									'depth'				=> 1,
									'items_wrap'      	=> '<ul class="clearfix">%3$s</ul>',
								) ); ?>
							</div><!-- .infobar-links -->
						<?php } ?>
					</div><!-- .row -->
          		</div><!-- .container -->
        	</div><!-- .infobar -->
        <?php } ?>
		<nav class="navbar navbar-expand-lg d-block">
			<div class="navbar-head<?php echo ($newscard_settings['newscard_header_background'] !== '') ? ' navbar-bg-set' : '' ; echo ($newscard_settings['newscard_header_bg_overlay'] === 'dark') ? ' header-overlay-dark' : '' ; echo ($newscard_settings['newscard_header_bg_overlay'] === 'light') ? ' header-overlay-light' : '' ;?>" <?php if ($newscard_settings['newscard_header_background'] !== '') { ?> style="background-image:url('<?php echo esc_url($newscard_settings['newscard_header_background']); ?>');"<?php } ?>>
				<div class="container">
					<div class="row navbar-head-row align-items-center">
						<div class="col-lg-4">
							<div class="site-branding navbar-brand">
								<?php
								the_custom_logo();
								if ( is_page_template('templates/front-page-template.php') || is_home() ) :
									?>
									<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
								<?php
								else :
									?>
									<h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h2>
								<?php
								endif;
								$newscard_description = get_bloginfo( 'description', 'display' );
								if ( $newscard_description || is_customize_preview() ) :
									?>
									<p class="site-description"><?php echo $newscard_description; /* WPCS: xss ok. */ ?></p>
								<?php endif; ?>
							</div><!-- .site-branding .navbar-brand -->
						</div>
						<?php if ( $newscard_settings['newscard_header_add_image'] !== '' ) { ?>
							<div class="col-lg-8 navbar-ad-section">
								<?php if ( $newscard_settings['newscard_header_add_link'] !== '' ) { ?>
									<a href="<?php echo esc_url( $newscard_settings['newscard_header_add_link'] ); ?>" class="newscard-ad-728-90" target="_blank">
								<?php } ?>
									<img class="img-fluid" src="<?php echo esc_url( $newscard_settings['newscard_header_add_image'] ); ?>" alt="<?php esc_attr_e('Banner Add', 'newscard'); ?>">
								<?php if ( $newscard_settings['newscard_header_add_link'] !== '' ) { ?>
									</a>
								<?php } ?>
							</div>
						<?php } ?>
					</div><!-- .row -->
				</div><!-- .container -->
			</div><!-- .navbar-head -->
			<div class="navigation-bar">
				<div class="navigation-bar-top">
					<div class="container">
						<button class="navbar-toggler menu-toggle" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'newscard'); ?>"></button>
						<span class="search-toggle"></span>
					</div><!-- .container -->
					<div class="search-bar">
						<div class="container">
							<div class="search-block off">
								<?php get_search_form(); ?>
							</div><!-- .search-box -->
						</div><!-- .container -->
					</div><!-- .search-bar -->
				</div><!-- .navigation-bar-top -->
				<div class="navbar-main">
					<div class="container">
						<div class="collapse navbar-collapse" id="navbarCollapse">
							<div id="site-navigation" class="main-navigation<?php echo ($newscard_settings['newscard_nav_uppercase'] == 1) ? " nav-uppercase" : "";?>" role="navigation">
								<?php
								if ( has_nav_menu('primary') ) {
									wp_nav_menu( array(
										'theme_location'	=> 'primary',
										'container'			=> '',
										'items_wrap'		=> '<ul class="nav-menu navbar-nav d-lg-block">%3$s</ul>',
									) );
								} else {
									wp_page_menu( array(
										'before' 			=> '<ul class="nav-menu navbar-nav d-lg-block">',
										'after'				=> '</ul>',
									) );
								}
								?>
							</div><!-- #site-navigation .main-navigation -->
						</div><!-- .navbar-collapse -->
						<div class="nav-search">
							<span class="search-toggle"></span>
						</div><!-- .nav-search -->
					</div><!-- .container -->
				</div><!-- .navbar-main -->
			</div><!-- .navigation-bar -->
		</nav><!-- .navbar -->

		<?php if ( ( is_front_page() || is_home() ) && $newscard_settings['newscard_top_stories_hide'] === 0 ) {

			$newscard_cat_tp = absint($newscard_settings['newscard_top_stories_categories']);

			$post_type_tp = array(
				'posts_per_page' => 5,
				'post__not_in' => get_option('sticky_posts'),
				'post_type' => array(
					'post'
				),
			);
			if ( $newscard_settings['newscard_top_stories_latest_post'] == 'category' ) {
				$post_type_tp['category__in'] = $newscard_cat_tp;
			}

			$newscard_get_top_stories = new WP_Query($post_type_tp); ?>

			<div class="top-stories-bar">
				<div class="container">
					<div class="row top-stories-box clearfix">
						<div class="col-sm-auto">
							<div class="top-stories-label">
								<div class="top-stories-label-wrap">
									<span class="flash-icon"></span>
									<span class="label-txt">
										<?php echo esc_html($newscard_settings['newscard_top_stories_title']); ?>
									</span>
								</div>
							</div>
						</div>
						<div class="col-12 col-sm top-stories-lists">
							<div class="row align-items-center">
								<div class="col">
									<div class="marquee<?php echo (is_rtl()) ? " marquee-rtl" : " marquee-ltr"; ?>">
										<?php while ($newscard_get_top_stories->have_posts()) {
											$newscard_get_top_stories->the_post();
											the_title( '<a href="' . esc_url( get_permalink() ) . '">', '</a>' );
										}
										// Reset Post Data
										wp_reset_postdata(); ?>
									</div><!-- .marquee -->
								</div><!-- .col -->
							</div><!-- .row .align-items-center -->
						</div><!-- .col-12 .col-sm .top-stories-lists -->
					</div><!-- .row .top-stories-box -->
				</div><!-- .container -->
			</div><!-- .top-stories-bar -->
		<?php } ?>

		<?php if ( ( ( is_front_page() || ( is_home() && $newscard_settings['newscard_banner_display'] === 'front-blog' ) ) && ( $newscard_settings['newscard_banner_slider_posts_hide'] === 0 || $newscard_settings['newscard_banner_featured_posts_1_hide'] === 0 || $newscard_settings['newscard_banner_featured_posts_2_hide'] === 0 ) ) || ( ( is_front_page() || ( is_home() && $newscard_settings['newscard_header_featured_posts_banner_display'] === 'front-blog' ) ) && $newscard_settings['newscard_header_featured_posts_hide'] === 0 ) ) { ?>
			<section class="featured-section">
				<div class="container">
					<?php if ( ( is_front_page() || ( is_home() && $newscard_settings['newscard_banner_display'] === 'front-blog' ) ) && ( $newscard_settings['newscard_banner_slider_posts_hide'] === 0 || $newscard_settings['newscard_banner_featured_posts_1_hide'] === 0 || $newscard_settings['newscard_banner_featured_posts_2_hide'] === 0 ) ) {

						$col_wrap_class = '';
						$col_slider_class = '';
						$col_mid_class = '';
						$col_mid_child_class = '';

						if ( $newscard_settings['newscard_banner_featured_posts_1_hide'] === 0 && $newscard_settings['newscard_banner_featured_posts_2_hide'] === 0 ) {
							$col_slider_class = 'col-lg-5 col-xl-6';
							$col_mid_class = 'col-sm-6 col-lg-3pt5 col-xl-3';
							$col_mid_child_class = 'col-12';
						} elseif ( ($newscard_settings['newscard_banner_featured_posts_1_hide'] === 1 && $newscard_settings['newscard_banner_featured_posts_2_hide'] === 0) || ($newscard_settings['newscard_banner_featured_posts_1_hide'] === 0 && $newscard_settings['newscard_banner_featured_posts_2_hide'] === 1) ) {
							$col_slider_class = 'col-12 col-lg-8';
							$col_wrap_class = 'two-column-featured-section';
							$col_mid_class = 'col-lg-4';
							$col_mid_child_class = 'col-sm-6 col-lg-12';
						} else {
							$col_slider_class = 'col-12';
							$col_wrap_class = 'one-column-featured-section';
						}

						?>
						<div class="row gutter-parent-10 <?php echo $col_wrap_class ?>">
							<?php if ( $newscard_settings['newscard_banner_slider_posts_hide'] === 0 ) {

								$newscard_bs_cat = absint($newscard_settings['newscard_banner_slider_post_categories']);

								$post_type_bs = array(
									'posts_per_page' => 5,
									'post__not_in' => get_option('sticky_posts'),
									'post_type' => array(
										'post'
									),
								);
								if ( $newscard_settings['newscard_banner_slider_latest_post'] == 'category' ) {
									$post_type_bs['category__in'] = $newscard_bs_cat;
								}

								$newscard_get_banner_slider = new WP_Query($post_type_bs); ?>

								<div class="<?php echo $col_slider_class ?>">
									<div class="featured-slider post-slider<?php echo ( $newscard_settings['newscard_banner_slider_posts_title'] === '' ) ? " slider-no-title" : ""; ?>">
										<div class="post-slider-header title-wrap">
											<?php if ( $newscard_settings['newscard_banner_slider_posts_title'] !== '' ) { ?>
												<h3 class="stories-title"><?php echo esc_html($newscard_settings['newscard_banner_slider_posts_title']); ?></h3>
											<?php } ?>
										</div>
										<div class="owl-carousel">
											<?php while ($newscard_get_banner_slider->have_posts()) {
												$newscard_get_banner_slider->the_post(); ?>
												<div class="item">
													<div class="post-item post-block">
														<div class="post-img-wrap">
															<a href="<?php the_permalink(); ?>" class="post-img" <?php if ( has_post_thumbnail() ) { ?> style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');" <?php } ?>></a>
														</div>
														<div class="entry-header">
															<div class="entry-meta category-meta">
																<div class="cat-links"><?php the_category(' '); ?></div>
															</div><!-- .entry-meta -->
															<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
															<?php if ( 'post' === get_post_type() ) { ?>
																<div class="entry-meta">
																	<?php newscard_posted_on(); ?>
																</div>
															<?php } ?>
														</div><!-- .entry-header -->
													</div><!-- .post-item .post-block -->
												</div>
											<?php }
											// Reset Post Data
											wp_reset_postdata(); ?>
										</div><!-- .owl-carousel -->
									</div><!-- .featured-slider .post-slider -->
								</div><!-- <?php echo $col_slider_class ?> -->
							<?php } ?>

							<?php if ( $newscard_settings['newscard_banner_featured_posts_1_hide'] === 0 ) {

								$newscard_fp_1_cat = absint($newscard_settings['newscard_banner_featured_posts_1_post_categories']);

								$post_type_fp_1 = array(
									'posts_per_page' => 2,
									'post__not_in' => get_option('sticky_posts'),
									'post_type' => array(
										'post'
									),
								);
								if ( $newscard_settings['newscard_banner_featured_posts_1_latest_post'] == 'category' ) {
									$post_type_fp_1['category__in'] = $newscard_fp_1_cat;
								}

								$newscard_get_featured_post_1 = new WP_Query($post_type_fp_1); ?>

								<div class="<?php echo $col_mid_class ?>">
									<div class="featured-post">
										<div class="title-wrap">
											<?php if ( $newscard_settings['newscard_banner_featured_posts_1_title'] !== '' ) { ?>
												<h3 class="stories-title"><?php echo esc_html($newscard_settings['newscard_banner_featured_posts_1_title']); ?></h3>
											<?php } ?>
										</div>
										<div class="row">
											<?php while ($newscard_get_featured_post_1->have_posts()) {
												$newscard_get_featured_post_1->the_post(); ?>
												<div class="<?php echo $col_mid_child_class ?>">
													<div class="post-item post-block">
														<div class="post-img-wrap">
															<a href="<?php the_permalink(); ?>" class="post-img" <?php if ( has_post_thumbnail() ) { ?> style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');" <?php } ?>></a>
														</div>
														<div class="entry-header">
															<div class="entry-meta category-meta">
																<div class="cat-links"><?php the_category(' '); ?></div>
															</div><!-- .entry-meta -->
															<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
															<?php if ( 'post' === get_post_type() ) { ?>
																<div class="entry-meta">
																	<?php newscard_posted_on(); ?>
																</div>
															<?php } ?>
														</div><!-- .entry-header -->
													</div><!-- .post-item .post-block -->
												</div><!-- <?php echo $col_mid_child_class ?> -->
											<?php }
											// Reset Post Data
											wp_reset_postdata(); ?>
										</div><!-- .row -->
									</div><!-- .featured-post -->
								</div><!-- <?php echo $col_mid_class ?> -->
							<?php } ?>

							<?php if ( $newscard_settings['newscard_banner_featured_posts_2_hide'] === 0 ) {

								$newscard_fp_2_cat = absint($newscard_settings['newscard_banner_featured_posts_2_post_categories']);

								$post_type_fp_2 = array(
									'posts_per_page' => 2,
									'post__not_in' => get_option('sticky_posts'),
									'post_type' => array(
										'post'
									),
								);
								if ( $newscard_settings['newscard_banner_featured_posts_2_latest_post'] == 'category' ) {
									$post_type_fp_2['category__in'] = $newscard_fp_2_cat;
								}

								$newscard_get_featured_post_2 = new WP_Query($post_type_fp_2); ?>

								<div class="<?php echo $col_mid_class ?>">
									<div class="featured-post">
										<div class="title-wrap">
											<?php if ( $newscard_settings['newscard_banner_featured_posts_2_title'] !== '' ) { ?>
												<h3 class="stories-title"><?php echo esc_html($newscard_settings['newscard_banner_featured_posts_2_title']); ?></h3>
											<?php } ?>
										</div>
										<div class="row">
											<?php while ($newscard_get_featured_post_2->have_posts()) {
												$newscard_get_featured_post_2->the_post(); ?>
												<div class="<?php echo $col_mid_child_class ?>">
													<div class="post-item post-block">
														<div class="post-img-wrap">
															<a href="<?php the_permalink(); ?>" class="post-img" <?php if ( has_post_thumbnail() ) { ?> style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');" <?php } ?>></a>
														</div>
														<div class="entry-header">
															<div class="entry-meta category-meta">
																<div class="cat-links"><?php the_category(' '); ?></div>
															</div><!-- .entry-meta -->
															<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
															<?php if ( 'post' === get_post_type() ) { ?>
																<div class="entry-meta">
																	<?php newscard_posted_on(); ?>
																</div>
															<?php } ?>
														</div><!-- .entry-header -->
													</div><!-- .post-item .post-block -->
												</div><!-- <?php echo $col_mid_child_class ?> -->
											<?php }
											// Reset Post Data
											wp_reset_postdata(); ?>
										</div><!-- .row -->
									</div><!-- .featured-post -->
								</div><!-- <?php echo $col_mid_class ?> -->
							<?php } ?>
						</div><!-- .row -->
					<?php } ?>

					<?php if ( ( is_front_page() || ( is_home() && $newscard_settings['newscard_header_featured_posts_banner_display'] === 'front-blog' ) ) && $newscard_settings['newscard_header_featured_posts_hide'] === 0 ) {

						$header_newscard_cat = absint($newscard_settings['newscard_header_featured_post_categories']);

						$header_post_type = array(
							'posts_per_page' => 4,
							'post__not_in' => get_option('sticky_posts'),
							'post_type' => array(
								'post'
							),
						);
						if ( $newscard_settings['newscard_header_featured_latest_post'] == 'category' ) {
							$header_post_type['category__in'] = $header_newscard_cat;
						}

						$header_newscard_get_featured_post = new WP_Query($header_post_type); ?>

						<section class="featured-stories">
							<h2 class="stories-title"><?php echo esc_html($newscard_settings['newscard_header_featured_posts_title']); ?></h2>
							<div class="row gutter-parent-10">
								<?php while ($header_newscard_get_featured_post->have_posts()) {
									$header_newscard_get_featured_post->the_post(); ?>
									<div class="col-sm-6 col-lg-3 post-col">
										<div class="post-boxed">
											<?php if ( has_post_thumbnail() ) { ?>
												<div class="post-img-wrap">
													<div class="featured-post-img">
														<a href="<?php the_permalink(); ?>" class="post-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')); ?>');"></a>
													</div>
													<div class="entry-meta category-meta">
														<div class="cat-links"><?php the_category(' '); ?></div>
													</div><!-- .entry-meta -->
												</div><!-- .post-img-wrap -->
											<?php } ?>
											<div class="post-content">
												<?php if ( !has_post_thumbnail() ) { ?>
													<div class="entry-meta category-meta">
														<div class="cat-links"><?php the_category(' '); ?></div>
													</div><!-- .entry-meta -->
												<?php } ?>
												<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); ?>
												<?php if ( 'post' === get_post_type() ) { ?>
													<div class="entry-meta">
														<?php newscard_posted_on(); ?>
													</div>
												<?php } ?>
											</div><!-- .post-content -->
										</div><!-- .post-boxed -->
									</div><!-- .col-sm-6 .col-lg-3 .post-col-->
								<?php }
								// Reset Post Data
								wp_reset_postdata(); ?>
							</div><!-- .row -->
						</section><!-- .featured-stories -->
					<?php } ?>
				</div><!-- .container -->
			</section><!-- .featured-section -->
		<?php } ?>

		<?php if ( !is_front_page() && !is_home() && !is_page_template('templates/front-page-template.php') && function_exists('newscard_breadcrumbs') && $newscard_settings['newscard_breadcrumbs_hide'] === 0 ) { ?>
			<div id="breadcrumb">
				<div class="container">
					<?php newscard_breadcrumbs(); ?>
				</div>
			</div><!-- .breadcrumb -->
		<?php } ?>
	</header><!-- #masthead -->
	<div id="content" class="site-content <?php echo ( ( ( is_front_page() || ( is_home() && $newscard_settings['newscard_banner_display'] === 'front-blog' ) ) && ( $newscard_settings['newscard_banner_slider_posts_hide'] === 0 || $newscard_settings['newscard_banner_featured_posts_1_hide'] === 0 || $newscard_settings['newscard_banner_featured_posts_2_hide'] === 0 ) ) || ( ( is_front_page() || ( is_home() && $newscard_settings['newscard_header_featured_posts_banner_display'] === 'front-blog' ) ) && $newscard_settings['newscard_header_featured_posts_hide'] === 0 ) ) ? "pt-0" : ""; ?>">
		<div class="container">
			<?php if ( is_page_template('templates/front-page-template.php') ) { ?>
				<div class="row gutter-14 justify-content-center site-content-row">
			<?php } else { ?>
				<div class="row justify-content-center site-content-row">
			<?php } ?>
